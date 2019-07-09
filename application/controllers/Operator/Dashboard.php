<?php

class Dashboard extends CI_Controller
{
    public $user;
    public function __construct()
    {
        parent::__construct();
        if (empty($this->session->userdata('login_detail'))) {
            redirect(base_url());
        }

        $this->User = $this->customlib->userdetail();
        $this->load->model('Operator');

        //print_r($this->User);

        if ($this->User['login_type'] == 'ADMIN') {
            redirect(base_url('Login/logout'));
        }
    }

    public function index()
    {
        $email = $this->User['username'];
        $daily_login = $this->Login_user->getDailyLogin($this->User['id']);
        $data['daily_login'] = $daily_login;

        if (isset($daily_login['counter_lable']) && is_null($daily_login['curr_tokenid'])) {
            // $this->assignToken($daily_login['id']);
            //echo "I am about to assign";
        }

        if (!is_null($daily_login['curr_tokenid'])) {
            $data['remark'] = $this->Operator->getRemark($daily_login['curr_tokenid']);
        }

        //$daily_login = $this->Login_user->getDailyLogin($this->User['id']);
        //print_r($daily_login);
        //die;
        //$data['daily_login'] = $daily_login;

        $data['statistic'] = $this->Operator->getServedApplication($this->User['id']);

        $queue = $this->Operator->waitingToken($this->User['center_id'], $this->User['operator_type']);
        $data['statistic']['queue'] = sizeof($queue);

        $data['user'] = $this->User;
        $data['operator'] = $this->Operator->OperatorByEmail($email)[0];
        $this->load->view('layout/Operator/header', $data);
        $this->load->view('Operator/dashboard', $data);
        $this->load->view('layout/Operator/footer', $data);
    }

    public function livedata($center_id)
    {
        $data['livedata'] = $this->Operator->counterToken($center_id);
        $this->load->view('Operator/livedata', $data);
    }

    public function setCounterNumber()
    {

        $this->form_validation->set_rules('dailyloginid', 'Login id', 'trim|required');

        //$rules['counter_lable']  = "required|callback_alpha_dash_space";
        $this->form_validation->set_rules('counter_lable', 'Counter Lable', 'required|callback_counter_lable[counter_lable]');

        if ($this->form_validation->run() == TRUE) {
            $dailyloginid = $this->input->post('dailyloginid');
            $counterlable = $this->input->post('counter_lable');
            $this->Login_user->updateDailyCounter($dailyloginid, $counterlable);

            redirect(base_url('Operator/Dashboard'));
        }


        $email = $this->User['username'];
        $daily_login = $this->Login_user->getDailyLogin($this->User['id']);
        $data['daily_login'] = $daily_login;

        if (isset($daily_login['counter_lable']) && is_null($daily_login['curr_tokenid'])) {
            // $this->assignToken($daily_login['id']);
            //echo "I am about to assign";
        }

        if (!is_null($daily_login['curr_tokenid'])) {
            $data['remark'] = $this->Operator->getRemark($daily_login['curr_tokenid']);
        }

        $daily_login = $this->Login_user->getDailyLogin($this->User['id']);
        //print_r($daily_login);
        //die;
        $data['daily_login'] = $daily_login;

        $data['statistic'] = $this->Operator->getServedApplication($this->User['id']);

        $queue = $this->Operator->waitingToken($this->User['center_id'], $this->User['operator_type']);
        $data['statistic']['queue'] = sizeof($queue);

        $data['user'] = $this->User;
        $data['operator'] = $this->Operator->OperatorByEmail($email)[0];
        $this->load->view('layout/Operator/header', $data);
        $this->load->view('Operator/dashboard', $data);
        $this->load->view('layout/Operator/footer', $data);
    }

    function counter_lable($str_in = '')
    {
        if (!preg_match("/^([-a-z0-9_ ])+$/i", $str_in)) {
            $this->form_validation->set_message('counter_lable', 'The %s field may only contain alpha-numeric characters, spaces, underscores, and dashes.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function getTokenInfo()
    {
        $result = $this->Operator->waitingToken($this->User['center_id']);
        //$result = $this->Operator->waitingToken($this->User['center_id']);
        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }

    public function assignToken($daily_login_id)
    {
        $operator_type = $this->User['operator_type'];
        $center_id = $this->User['center_id'];

        $waiting_token = $this->Operator->waitingToken($center_id, $operator_type);

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
                $log_data['operator_id'] = (int) $this->User['id'];
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

    public function callNextToken($current_tokenId, $daily_loginid)
    {
        //step 1 null current token
        //step 2 update out time for current token
        //step 3 redirect to index

        $operator_id = (int) $this->User['id'];
        $center_id = $this->User['center_id'];

        $this->Login_user->UpdateDailyLogin($daily_loginid, NULL);
        sleep(0);
        $outtime = date('Y-m-d H:i:s');
        $token_date = date('Y-m-d');

        $data = array(
            'outtime' => $outtime
        );

        $this->db->where('token_id', $current_tokenId);
        $this->db->where('operator_id', $operator_id);
        $this->db->where('center_id', $center_id);
        $this->db->where('token_date', $token_date);
        $this->db->update('token_log', $data);
        sleep(0);
        //Assigning operator if any operator is available
        $this->Operator->assignOperatorToken($current_tokenId, $center_id);

        $_SESSION['STATUS'][$operator_id] = NULL;
        //echo "<hr>" . $this->db->last_query();

        redirect(base_url('Operator/Dashboard'));
    }

    public function getApplicationList($askId)
    {
        $ams_api = $this->config->item('ams_base_url') . "Enrollment/GettmsapplicationId?askId=" . $askId;

        $objectlist = json_decode(file_get_contents($ams_api), true);

        foreach ($objectlist as $value) {
            $data[] = $value['appointmentReferenceNo'];
        }

        return implode(',', $data);
    }

    public function setApplicatinId()
    {
        //AMS API

        // $app_ids = $this->getApplicationList('F4A6AB2D-8CE7-47C1-8FD0-9AD9813A818E');

        $inlist = "in_list[" . $app_ids . "]|is_unique[token_list.application_id]";
        $this->form_validation->set_rules('curr_tokenid', 'Login id', 'trim|required');
        //$this->form_validation->set_rules('application_id', 'Application Id', 'trim|required|alpha_numeric|' . $inlist, array('in_list' => 'Application ID is Not Valid'));

        $this->form_validation->set_rules('application_id', 'Application Id', 'trim|required|alpha_numeric|is_unique[token_list.application_id]');

        if ($this->form_validation->run() == TRUE) {
            $curr_tokenid = $this->input->post('curr_tokenid');
            $data['application_id'] = $this->input->post('application_id');

            $this->General->modify('token_list', 'id', $curr_tokenid, $data);
            redirect(base_url('Operator/Dashboard'));
        }

        $email = $this->User['username'];
        $daily_login = $this->Login_user->getDailyLogin($this->User['id']);
        $data['daily_login'] = $daily_login;

        if (isset($daily_login['counter_lable']) && is_null($daily_login['curr_tokenid'])) {
            // $this->assignToken($daily_login['id']);
            //echo "I am about to assign";
        }

        if (!is_null($daily_login['curr_tokenid'])) {
            $data['remark'] = $this->Operator->getRemark($daily_login['curr_tokenid']);
        }

        $daily_login = $this->Login_user->getDailyLogin($this->User['id']);
        //print_r($daily_login);
        //die;
        $data['daily_login'] = $daily_login;

        $data['statistic'] = $this->Operator->getServedApplication($this->User['id']);

        $queue = $this->Operator->waitingToken($this->User['center_id'], $this->User['operator_type']);
        $data['statistic']['queue'] = sizeof($queue);

        $data['user'] = $this->User;
        $data['operator'] = $this->Operator->OperatorByEmail($email)[0];
        $this->load->view('layout/Operator/header', $data);
        $this->load->view('Operator/dashboard', $data);
        $this->load->view('layout/Operator/footer', $data);
    }

    // always call by the operator to check new assignment 
    // UPDATE_ON['25-06-2019']
    public function checkAssignment()
    {
        $daily_login = $this->Login_user->getDailyLogin($this->User['id']);
        // print_r($daily_login);
        $opid = $daily_login['opid'];
        if (isset($daily_login['counter_lable'])) {
            if (is_null($daily_login['curr_tokenid'])) {
                $_SESSION['STATUS'][$opid] = NULL;
                echo 0;
            } else {
                if (is_null($_SESSION['STATUS'][$opid]) || !isset($_SESSION['STATUS'][$opid])) {
                    $_SESSION['STATUS'][$opid] = $daily_login['curr_tokenid'];
                    echo 1;
                } else {
                    echo 0;
                }
            }
        } else {
            echo 0;
        }
    }

    public function park_token($token_id, $daily_login_id, $is_park = 1)
    {
        //is_parked = 1 (parked) or 0 (not parked)

        $this->Operator->parkToken($token_id, $is_park);
        $this->Login_user->UpdateDailyLogin($daily_login_id, NULL);
        redirect(base_url('Operator/Dashboard'));
    }

    public function Reassign()
    {
        //print_r($_POST);
        //Add Outtime to table : token_log against Operator
        //Add value to assign_remark = OP_ID#Message to the token_list & update op_status = "[new_opstatus]"

        $daily_loginid = $this->input->post('dailyloginid');
        $new_opstatus = $this->input->post('new_opstatus');
        $assign_remark = $this->input->post('remark');
        $current_tokenId = $this->input->post('curr_tokenid');

        $this->form_validation->set_rules('dailyloginid', 'Login id', 'trim|required');
        $this->form_validation->set_rules('curr_tokenid', 'New Token', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            $operator_id = (int) $this->User['id'];
            $center_id = $this->User['center_id'];

            $this->Login_user->UpdateDailyLogin($daily_loginid, NULL);

            $outtime = date('Y-m-d H:i:s');
            $token_date = date('Y-m-d');

            $data = array(
                'outtime' => $outtime,
                'assign_remark' => $assign_remark
            );

            $this->db->where('token_id', $current_tokenId);
            $this->db->where('operator_id', $operator_id);
            $this->db->where('center_id', $center_id);
            $this->db->where('token_date', $token_date);

            $this->db->update('token_log', $data);

            // $this->db->query("update token_log set outtime='" . $outtime . "', assign_remark='" . $assign_remark . "' WHERE token_id='" . $current_tokenId . "' AND operator_id='" . $operator_id . "' AND center_id='" . $center_id . "' AND token_date='" . $token_date . "'");

            $this->up_tokenlist($new_opstatus, $current_tokenId);

            // $data = array(
            //     'op_status' => $new_opstatus
            // );

            // $this->db->where('id', $current_tokenId);
            // $this->db->update('token_list', $data);

            // $this->db->query("update token_list set op_status='" . $new_opstatus . "' WHERE id = '" . $current_tokenId . "'");

        }

        redirect(base_url('Operator/Dashboard'));
    }

    public function up_tokenlist($new_opstatus, $current_tokenId)
    {
        $data = array(
            'op_status' => $new_opstatus
        );

        $this->db->where('id', $current_tokenId);
        $this->db->update('token_list', $data);

        return true;
    }

    public function rejectoken()
    {
        $this->form_validation->set_rules('curr_tokenid', 'Token ID', 'trim|required');
        $this->form_validation->set_rules('dailyloginid', 'Daily Login ID', 'trim|required');
        $this->form_validation->set_rules('reject_reason', 'Reject Reason', 'trim|required');
        print_r($_POST);
        if ($this->form_validation->run() == TRUE) {
            $curr_tokenid = $this->input->post('curr_tokenid');
            $dailyloginid = $this->input->post('dailyloginid');
            $reject_reason = $this->input->post('reject_reason');
            $center_id = $this->User['center_id'];
            $this->Operator->reject_token($curr_tokenid, $dailyloginid, $reject_reason, $center_id);
        }

        redirect(base_url('Operator/Dashboard'));
    }
}
