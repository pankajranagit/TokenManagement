<?php

class Operator extends CI_Model
{
    private $table = 'operator_list';
    public function __construct()
    {
        parent::__construct();
    }

    public function OperatorByEmail($email = null)
    {
        $this->db->select("operator_list.id, operator_list.center_id, operator_list.operator_name, operator_list.operator_number, operator_list.operator_type, operator_list.operator_email, center_list.center_name, center_list.state, center_list.city, center_list.center_address");
        $this->db->from($this->table);
        $this->db->join('center_list', 'center_list.id = operator_list.center_id', 'left');
        if ($email != null) {
            $this->db->where(array('operator_email' => $email));
        }

        $query = $this->db->get();
        return $query->result_array();
    }


    public function assignOperatorToken($token_id, $center_id)
    {
        //1. Get the next operator type
        $operator_type = $this->getFirstOperator($token_id);

        $this->assignToken('', $center_id, $operator_type, '');
    }

    public function assignToken($daily_login_id, $center_id, $operator_type, $opid)
    {
        $operator_info = $this->getEmptyOperator($operator_type, $center_id);
        $daily_login_id = $operator_info['id'];
        $opid = $operator_info['opid'];

        if($daily_login_id){
            $waiting_token = $this->waitingToken($center_id, $operator_type);
            $index = "";
            foreach ($waiting_token as $key => $value) {
                $index = $key;
                break;
            }

            $my_token = $waiting_token[$index];
            if ($my_token['token_id'] > 0) {
                if ($this->Login_user->UpdateDailyLogin($daily_login_id, $my_token['token_id'])) {
                    $this->Login_user->updateMyToken($daily_login_id, $my_token['token_id']);
                    //insert into token log
                    $log_data['operator_id'] = (int) $opid;
                    $log_data['center_id'] = $center_id;
                    $log_data['token_id'] = $my_token['token_id'];
                    $log_data['token_date'] = date('Y-m-d');
                    $log_data['intime'] = date('Y-m-d H:i:s');
                    $log_data['create_on'] = date('Y-m-d H:i:s');

                    if ($my_token['token_id'] != 0) {
                        $this->General->add_data('token_log', $log_data);
                    }
                }
            }
        }
    }

    public function getFirstOperator($token_id)
    {
        $this->db->select('op_list, op_status');
        $this->db->from('token_pool');
        $this->db->where(array('token_id' => $token_id));

        $query = $this->db->get();
        $data = $query->row_array();
        $op_list = $data['op_list'];

        $index = explode(',', $data['op_status']);

        if (empty($data['op_status'])) {
            $index = 0;
        } else {
            $index = sizeof($index);
        }


        return explode(',', $op_list)[$index];
    }

    public function getEmptyOperator_test($optype, $center_id)
    {
        $curr_date = date('Y-m-d');
        $this->db->select('id, opid');
        $this->db->from('daily_login');
        $this->db->where('operator_type', $optype);
        $this->db->where('counter_lable is NOT NULL', NULL, FALSE);
        $this->db->where(array('curr_status' => NULL, 'curr_tokenid' => NULL, 'center_id' => $center_id, 'curr_date' => $curr_date));
        $this->db->order_by('lastupdate', "asc");
        $query = $this->db->get();

        // echo $this->db->last_query();
        return $query->row_array();
    }

    public function getEmptyOperator($optype, $center_id)
    {
        $currdate = date('Y-m-d');

        $this->db->select('daily_login.opid, daily_login.id, COUNT(token_log.token_id) as token_served');
        $this->db->from('daily_login');
        $this->db->join('token_log', "token_log.operator_id = daily_login.opid AND token_log.token_date = '" . $currdate . "'", 'LEFT');
        $this->db->where('daily_login.counter_lable is NOT NULL', NULL, FALSE);

        $array = array('daily_login.center_id' => $center_id, 'daily_login.curr_date' => $currdate, 'daily_login.operator_type' => $optype, 'daily_login.curr_status' => NULL, 'daily_login.curr_tokenid' => NULL);
        $this->db->where($array);
        $this->db->group_by('token_log.operator_id');
        $this->db->order_by('token_served', 'ASC');
        // $this->db->order_by('daily_login.lastupdate', 'ASC');
        $query = $this->db->get();

        // $curr_date = date('Y-m-d');
        // $getEmptyOperator = "CALL getEmptyOperator(?, ?, ?)";
        // $data = array('optype' => $optype, 'centerid' => (int) $center_id, 'currdate' => $curr_date);
        // $query = $this->db->query($getEmptyOperator, $data);

        //echo $this->db->last_query();
        return $query->row_array();
    }


    public function waitingToken($centerId, $opType = "")
    {
        //$centerId = $this->db->escape($centerId);
        $running_token = $this->getRuningToken();

        $token_list = array_column($running_token, 'curr_tokenid');
        $tokens = implode(',', $token_list);

        $tokens = rtrim($tokens, ",");
        $tokens = ltrim($tokens, ",");

        //$in = ($tokens ? "AND token_pool.token_id NOT IN ($tokens)" : '');

        $curr_data = date('Y-m-d');

        $this->db->select('token_pool.op_status, token_pool.token_id, token_pool.op_list, token_log.outtime');
        $this->db->from('token_pool');
        $this->db->join('token_log', 'token_pool.token_id=token_log.token_id', 'left');

        $where = "token_pool.applicant_type IS NOT NULL";
        $this->db->where($where);

        $this->db->where('token_pool.center_id', $centerId);
        $this->db->where(array('token_pool.is_end' => NULL));

        $this->db->where('token_pool.curr_date', $curr_data);
        if (!empty($token_list)) {
            $this->db->where_not_in('token_pool.token_id', $token_list);
        }

        $this->db->where('(token_log.id IN (SELECT MAX(id) FROM token_log GROUP BY token_id) OR ISNULL(token_log.id))', NULL, FALSE);

        $this->db->order_by('token_pool.token_id', 'ASC');
        $this->db->order_by('token_pool.update_on', 'DESC');

        $query = $this->db->get();

        // echo $this->db->last_query();
        // die;
        $data = $query->result_array();
        foreach ($data as $value) {
            $temp['token_id'] = $value['token_id'];
            $temp['outtime'] = $value['outtime'];
            $temp['op_status'] = $value['op_status'];
            $temp['op_list'] = $value['op_list'];
            if (!empty($value['op_status'])) {
                $opStatusArr = explode(",", $value['op_status']);
                $arr_index = sizeof($opStatusArr);

                $oplist = explode(",", $value['op_list']);
                $oplistSize = sizeof($oplist);
            } else {
                $arr_index = 0;
            }

            $temp['curr_operator'] = explode(',', $value['op_list'])[$arr_index];
            //echo $arr_index ." + ".$oplistSize;
            if (!empty($value['op_status'])) {
                if ($value['outtime'] != '0000-00-00 00:00:00' && $arr_index != $oplistSize) {
                    $result[] = $temp;
                }
            } else {
                if ($value['outtime'] != '0000-00-00 00:00:00') {
                    $result[] = $temp;
                }
            }
        }

        if ($opType != "") {
            return array_filter($result, function ($var) use ($opType) {
                return ($var['curr_operator'] == $opType);
            });
        } else {
            return $result;
        }
    }

    public function counterToken($center_id)
    {
        $date = date('Y-m-d');

        $this->db->select('daily_login.id, daily_login.curr_tokenid as dltoken, daily_login.opid, daily_login.center_id, daily_login.counter_lable, token_list.token_number as curr_tokenid, daily_login.lastupdate, daily_login.operator_type, daily_login.curr_status');
        $this->db->from('daily_login');
        $this->db->join('token_list', 'daily_login.curr_tokenid = token_list.id', 'left');
        $this->db->where('daily_login.curr_date', $date);
        $this->db->where('daily_login.center_id', $center_id);
        $this->db->order_by('daily_login.id', 'ASC');

        $query = $this->db->get();

        //echo $this->db->last_query();
        //die;
        //$query = $this->db->query("SELECT counter_lable, token_list.token_number as curr_tokenid, lastupdate, operator_type FROM daily_login LEFT JOIN token_list ON daily_login.curr_tokenid = token_list.id WHERE daily_login.curr_date = '$date' AND daily_login.center_id = $center_id ORDER BY daily_login.id");
        return $query->result_array();
    }

    public function Temp__getRuningToken($date = NULL)
    {
        if ($date == NULL) {
            $date = date('Y-m-d');
            $this->db->select('token_list.token_number as curr_tokenid');
            $this->db->from('daily_login');
            $this->db->join('token_list', 'daily_login.curr_tokenid = token_list.id', 'left');
            $this->db->where('daily_login.curr_date', $date);
            $where = "daily_login.curr_tokenid IS NOT NULL";
            $this->db->where($where);
            $query = $this->db->get();

            //$query = $this->db->query("SELECT token_list.token_number as curr_tokenid FROM daily_login LEFT JOIN token_list ON daily_login.curr_tokenid = token_list.id WHERE daily_login.curr_date = '$date' AND daily_login.curr_tokenid IS NOT NULL");
            return $query->result_array();
        }
    }


    public function getRuningToken($date = NULL)
    {
        if ($date == NULL) {
            $date = date('Y-m-d');
            $this->db->select('token_list.id as curr_tokenid');
            $this->db->from('daily_login');
            $this->db->join('token_list', 'daily_login.curr_tokenid = token_list.id', 'left');
            $this->db->where('daily_login.curr_date', $date);
            $where = "daily_login.curr_tokenid IS NOT NULL";
            $this->db->where($where);
            $query = $this->db->get();

            // $query = $this->db->query("SELECT token_list.token_number as curr_tokenid FROM daily_login LEFT JOIN token_list ON daily_login.curr_tokenid = token_list.id WHERE daily_login.curr_date = '$date' AND daily_login.curr_tokenid IS NOT NULL");

            // echo $this->db->last_query();
            return $query->result_array();
        }
    }


    public function getServedApplication($op_id)
    {
        $op_id = $this->db->escape($op_id);
        //SELECT * FROM `token_log` 
        $this->db->select('count(*) as app_served');
        $this->db->from('token_log');
        $this->db->where('operator_id', $op_id);
        $query = $this->db->get();
        //$query = $this->db->query("SELECT count(*) as app_served FROM token_log WHERE operator_id = $op_id");
        return $query->row_array();
    }

    public function parkToken($token_id, $is_park = 1)
    {
        // $token_id = $this->db->escape($token_id);
        // $is_park = $this->db->escape($is_park);

        $data = array(
            'is_parked' => $is_park
        );

        $this->db->where('id', $token_id);
        $this->db->update('token_list', $data);


        //$this->db->query("UPDATE token_list SET is_parked = $is_park WHERE id = $token_id");
    }

    public function getRemark($token_id)
    {
        //$token_id = $this->db->escape($token_id);

        $this->db->select('assign_remark');
        $this->db->from('token_log');
        $this->db->where('token_id', $token_id);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(2);
        $query = $this->db->get();

        // $query = $this->db->query("SELECT assign_remark FROM token_log WHERE token_id = $token_id ORDER BY id DESC LIMIT 2");

        $data = $query->result_array();
        foreach ($data as $value) {
            $assign_remark = $value['assign_remark'];
        }

        if ($assign_remark != '')
            return $assign_remark;
        else
            return 0;
    }

    public function DailyOperatorStatus($date, $center_in)
    {
        $this->db->select("login_user.id, token_pool.token_number, daily_login.id as dailyId, login_user.username, daily_login.curr_tokenid, daily_login.curr_status, daily_login.lastupdate, daily_login.create_on, daily_login.counter_lable, operator_list.operator_number, operator_list.operator_name, login_user.operator_type");
        $this->db->from('login_user');
        $this->db->join('daily_login', "daily_login.opid = login_user.id && daily_login.curr_date = '$date'", 'LEFT');
        $this->db->join('operator_list', 'operator_list.operator_email = login_user.username', 'LEFT');
        $this->db->join('token_pool', 'token_pool.token_id = daily_login.curr_tokenid', 'LEFT');
        $in = array('PD', 'ECMP', 'VERIFIER', 'CASHIER');
        $this->db->where_in('login_user.operator_type', $in);
        $this->db->where(array('login_user.center_id' => $center_in));
        $this->db->order_by('daily_login.counter_lable', 'DESC');

        $query = $this->db->get();
        // echo $this->db->last_query();
        return $query->result();
    }

    public function update_dailylogin_status($dailyId, $status)
    {
        if ($status == '' || is_null($status) || $status == 'NULL') {
            $data = array(
                'curr_status' => NULL
            );
        } else {
            $data = array(
                'curr_status' => $status
            );
        }

        $this->db->where('id', (int) $dailyId);
        $this->db->update('daily_login', $data);
        //echo $this->db->last_query();
        $currdatetime = date('Y-m-d H:i:s');

        $data = array(
            'dailylogin_id' => $dailyId,
            'changed_status' => ($status == 'NULL' ? 'Running' : $status),
            'log_datetime' =>  $currdatetime
        );
        $this->db->insert('operator_log', $data);
    }

    public function reject_token($curr_tokenid, $dailyloginid, $reject_reason, $center_id)
    {
        $this->db->trans_start();

        //1. Update table = token_list, is_end = 1
        $data = array(
            'is_end' => 1
        );

        $this->db->where('id', $curr_tokenid);
        $this->db->update('token_list', $data);

        //2. Update table = token_log, outtime = 1
        $data = array(
            'outtime' => date('Y-m-d H:i:s')
        );

        $this->db->where('token_id', $curr_tokenid);
        $this->db->update('token_log', $data);

        //3. Insert log to the table = token_reject
        $data = array(
            'token_id' => $curr_tokenid,
            'daily_logid' => $dailyloginid,
            'center_id' => $center_id,
            'reason' => $reject_reason,
            'create_on' => date('Y-m-d H:i:s')
        );

        $this->db->insert('token_reject', $data);

        //4. Update the table = daily_login to curr_tokenid = NULL
        $data = array(
            'curr_tokenid' => NULL
        );

        $this->db->where('id', $dailyloginid);
        $this->db->update('daily_login', $data);

        $this->db->trans_complete();
    }
}
