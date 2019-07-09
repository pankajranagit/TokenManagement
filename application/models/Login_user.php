<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Login_user extends CI_Model
{

    //put your code here
    private $table_name = 'login_user';

    public function check_login($username, $password)
    {
        $this->db->select('id, username, password, login_type, operator_type, center_id, email_verify');
        $this->db->from($this->table_name);
        $array = array('username' => $username);
        $this->db->where($array);
        $query = $this->db->get();
        $data = $query->row_array();
        //echo $this->db->last_query();
        $DBpass = $this->encrypt->decode($data['password']);
        if ($DBpass == $password) :
            return $query->row_array();
        else :
            return 0;
        endif;
    }

    public function checkAmsLogin($username, $password)
    {
        $this->db->select('id, username, login_type, operator_type, center_id, email_verify');
        $this->db->from($this->table_name);
        $array = array('username' => $username, 'ams_password' => $password);
        $this->db->where($array);
        $query = $this->db->get();

        if ($this->db->count_all_results() === 1) :
            return $query->row_array();
        else :
            return 0;
        endif;
    }

    public function startOperatorDay($op_id, $center_id, $operator_type)
    {
        $currdate = date('Y-m-d');
        $currdatetime = date('Y-m-d H:i:s');
        if ($this->DayStarted($op_id) <= 0) {

            $data = array(
                'opid'    =>  $op_id,
                'curr_date' =>  $currdate,
                'center_id'    =>  $center_id,
                'operator_type'    =>  $operator_type,
                'create_on'    =>  $currdatetime
            );
            $this->db->insert('daily_login', $data);
        }
    }

    public function DayStarted($op_id)
    {
        $currdate = date('Y-m-d');

        $this->db->select('id');
        $this->db->from('daily_login');
        $this->db->where(array('opid' => $op_id));
        $this->db->where(array('curr_date' => $currdate));

        $query = $this->db->get();

        //$query = $this->db->query("SELECT id FROM daily_login WHERE opid = $op_id and curr_date = '$currdate'");
        return $query->num_rows();
    }

    public function getDailyLogin($op_id)
    {
        $currdate = date('Y-m-d');

        $this->db->select('daily_login.*, token_pool.application_id, token_pool.token_number, token_pool.applicant_type, token_pool.op_list, token_pool.op_status');
        $this->db->from('daily_login');
        $this->db->join('token_pool', 'token_pool.token_id = daily_login.curr_tokenid', 'left');
        $this->db->where('daily_login.opid', $op_id);
        $this->db->where('daily_login.curr_date', $currdate);
        $query = $this->db->get();


        // $query = $this->db->query("SELECT daily_login.*, token_pool.application_id, token_pool.token_number, token_pool.applicant_type, token_pool.op_list, token_pool.op_status FROM daily_login 
        // LEFT JOIN token_pool ON token_pool.token_id = daily_login.curr_tokenid
        // WHERE daily_login.opid = $op_id and  = '$currdate'");
        //echo $this->db->last_query();
        //die;
        return $query->row_array();
    }

    public function updateDailyCounter($id, $counterlable)
    {
        if ($id == '')
            redirect(base_url('Login/logout'));

        $currdate = date('Y-m-d H:i:s');

        $data = array(
            'counter_lable' => $counterlable,
            'lastupdate' => $currdate
        );

        $this->db->where('id', $id);
        $this->db->update('daily_login', $data);

        //echo $this->db->last_query();
        //die;

        // $this->db->query("UPDATE daily_login SET counter_lable = '" . $counterlable . "', lastupdate = '" . $currdate . "' WHERE id = $id");
    }

    public function updateMyToken_old($id, $token)
    {
        $currdate = date('Y-m-d H:i:s');
        if ($token != 0) {

            $data = array(
                'curr_tokenid' => $token,
                'lastupdate' => $currdate
            );

            $this->db->where('id', $id);
            $this->db->update('daily_login', $data);

            //$this->db->query("UPDATE daily_login SET curr_tokenid = '" . $token . "', lastupdate = '" . $currdate . "' WHERE id = $id");
        }

        if ($token > 0) {

            $this->db->select('op_status');
            $this->db->from('token_list');
            $this->db->where('id', $token);
            $tokendata = $this->db->get()->row_array();

            //$tokendata = $this->db->query("SELECT op_status FROM token_list WHERE id = $token")->row_array();
            //print_r($tokendata);
            $op_status = $tokendata['op_status'];
            $op_status = explode(',', $op_status);

            array_push($op_status, "1");

            $op_status = ltrim(implode(',', $op_status), ",");

            //$data_log['op_status'] = $op_status;


            $data = array(
                'op_status' => $op_status
            );

            $this->db->where('id', $token);
            $this->db->update('token_list', $data);

            //$this->db->query("UPDATE token_list SET op_status = '" . $op_status . "' WHERE id = $token");
        }
    }

    public function UpdateDailyLogin($id, $token)
    {
        $currdate = date('Y-m-d H:i:s');
        if (is_null($token)) {

            $data = array(
                'curr_tokenid' => NULL,
                'lastupdate' => $currdate
            );

            $this->db->where('id', $id);
            $this->db->update('daily_login', $data);
            return true;
        } else {
            $data = array(
                'curr_tokenid' => $token,
                'lastupdate' => $currdate
            );

            $this->db->where('id', $id);
            $this->db->where(array('curr_tokenid' => NULL));
            $this->db->update('daily_login', $data);

            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function updateMyToken($id, $token)
    {

        $this->db->select('token_list.op_status, services.counter');
        $this->db->from('token_list');
        $this->db->join('services', 'services.applicant_type = token_list.applicant_type', 'left');
        $this->db->where('token_list.id', $token);
        $tokendata = $this->db->get()->row_array();

        //$tokendata = $this->db->query("SELECT token_list.op_status, services.counter FROM token_list LEFT JOIN services ON services.applicant_type = token_list.applicant_type WHERE token_list.id = $token")->row_array();

        $op_size = sizeof(explode(",", $tokendata['counter']));

        $op_status = $tokendata['op_status'];
        $op_status = explode(',', $op_status);

        if (sizeof($op_status) >= $op_size) {

            $data = array(
                'is_end' => 1
            );

            $this->db->where('id', $token);
            $this->db->update('token_list', $data);

            //$this->db->query("UPDATE token_list SET is_end = 1 WHERE id = $token");
        } else {
            array_push($op_status, "1");

            $op_status = ltrim(implode(',', $op_status), ",");

            //$data_log['op_status'] = $op_status;

            $data = array(
                'op_status' => $op_status
            );

            $this->db->where('id', $token);
            $this->db->update('token_list', $data);

            //$this->db->query("UPDATE token_list SET op_status = '" . $op_status . "' WHERE id = $token");
        }
    }
}
