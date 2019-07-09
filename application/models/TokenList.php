<?php


class TokenList extends CI_Model
{
    public $table = "token_list";

    public function __construct()
    {
        parent::__construct();
    }

    public function getNewToken($applicant_id, $center_id)
    {
        $response = $this->applicant_id_exist($applicant_id);
        if ($response['num'] == 0) {
            $token_number = $this->getFreshToken($center_id, date('Y-m-d'));
            $data = array(
                'curr_date' => date('Y-m-d'),
                'create_on' => date('Y-m-d H:i:s'),
                'applicant_id' => $applicant_id,
                'center_id' => $center_id,
                'token_number' => $token_number
            );

            $this->db->insert($this->table, $data);
            return  $token_number;
        } else {
            return $response['token_number'];
        }
    }

    public function applicant_id_exist($applicant_id)
    {
        $this->db->select('count(*) as num, id, token_number');
        $this->db->from($this->table);
        $this->db->where(array('applicant_id' => $applicant_id));

        $query = $this->db->get();

        //echo $this->db->last_query();
        return $query->row_array();
    }

    public function updateApplicantType($applicant_id, $applicant_type)
    {
        $applicant_type = implode(',', $applicant_type);

        $this->db->set('applicant_type', $applicant_type);
        $this->db->where('applicant_id', $applicant_id);
        $this->db->update($this->table);

        //get Token Id
        return $this->tokenIdfromAppId($applicant_id);
    }

    public function tokenIdfromAppId($app_id)
    {
        $this->db->select('id');
        $this->db->from($this->table);
        $this->db->where(array('applicant_id' => $app_id));

        $query = $this->db->get();
        return $query->row_array()['id'];
    }

    public function getFreshToken($centerId, $date)
    {
        $this->db->select('count(*) as num');
        $this->db->from($this->table);
        $this->db->where(array('center_id' => $centerId, 'curr_date' => $date));

        $query = $this->db->get();
        $data = $query->row_array();
        //echo $this->db->last_query();
        return ($data['num'] + 1);
    }

    public function totalApplicant($center_id = '')
    {
        $this->db->select('id, applicant_id, application_id, applicant_type, center_id, op_status, is_end, is_parked, curr_date, create_on, update_on');
        $this->db->from('token_list');

        // $query = "SELECT id, applicant_id, application_id, applicant_type, center_id, op_status, is_end, is_parked, curr_date, create_on, update_on FROM `token_list` ";

        if ($center_id !== '') {
            $this->db->where(array('center_id' => $center_id));
            // $query .= "WHERE (`center_id` = '" . $center_id . "') ";
        }
        //$query.= "GROUP BY `center_id`, `curr_date`";
        $query = $this->db->get();
        return $query->result_array();
    }

    public function todayApplicant($center_id = '')
    {
        $this->db->select('id');
        $this->db->from('token_list');

        if ($center_id !== '') {
            $this->db->where(array('center_id' => $center_id));
            $this->db->where(array('curr_date' => date('Y-m-d')));
        }
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result_array();
    }

    public function tokenInQueue($center_id = '')
    {
        $inQueue = 0;
        $this->db->select('op_list, op_status');
        $this->db->from('token_pool');

        if ($center_id !== '') {
            $this->db->where(array('center_id' => $center_id));
            $this->db->where(array('curr_date' => date('Y-m-d')));
        }
        $query = $this->db->get();
        //echo $this->db->last_query();
        $data = $query->result_array();
        foreach ($data as $value) {
            $op_list = explode(',', $value['op_list']);
            $op_status = explode(',', $value['op_status']);
            //print_r(count($op_status));
            if (count($op_list) > count($op_status)) {
                $inQueue++;
            }
        }

        return $inQueue;
    }

    public function todayCounter($center_id = '')
    {
        $this->db->select('id');
        $this->db->from('operator_list');

        if ($center_id !== '') {
            $this->db->where(array('center_id' => $center_id));
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function tokenLogging($date = '', $center_id = '')
    {
        $this->db->select('token_log.id, token_log.operator_id, token_log.intime, token_log.outtime, token_log.assign_remark, login_user.username, token_list.token_number, token_list.application_id, operator_list.operator_name, operator_list.operator_number, operator_list.operator_type');
        $this->db->from('token_log');
        $this->db->join('login_user', 'login_user.id = token_log.operator_id', 'left');
        $this->db->join('token_list', 'token_list.id = token_log.token_id', 'left');
        $this->db->join('operator_list', 'operator_list.operator_email = login_user.username', 'left');

        if ($date !== '') {
            $this->db->where(array('token_log.token_date' => $date));
        }

        if ($center_id !== '') {
            $this->db->where(array('token_log.center_id' => $center_id));
        }

        $this->db->order_by('token_list.token_number', 'ASC');
        $this->db->order_by('token_log.intime', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function ifAppTypexist($app_type)
    {
        $this->db->select('count(*) as num');
        $this->db->from('services');
        $this->db->where(array('applicant_type' => $app_type));
        $query = $this->db->get();
        $data = $query->result_array();

        //print_r($data);
        return $data[0]['num'];
    }
}
