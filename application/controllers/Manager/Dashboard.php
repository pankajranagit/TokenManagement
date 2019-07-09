<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (empty($this->session->userdata('login_detail'))) {
            redirect(base_url());
        }
    }

    public function index($currUnixDate = '')
    {
        $main_menu['active'] = 'Dashboard';
        $this->session->set_userdata($main_menu);

        $login_info = $this->customlib->userdetail();

        //$data['operator'] = $this->Operator->OperatorByEmail($email)[0];
        // print_r($login_info);

        $center_id = $login_info['center_id'];

        if ($currUnixDate) {
            $data['currDate'] = $currDate = date('Y-m-d', $currUnixDate);
        } else {
            $data['currDate'] = $currDate = date('Y-m-d');
        }

        $data['operator_list'] = $this->General->getRowByWhere('operator_list', array('center_id' => $center_id));
        $data['total_applicant'] = $this->TokenList->totalApplicant($center_id);
        $data['today_applicant'] = $this->TokenList->todayApplicant($center_id);
        $data['tokenInQueue'] = $this->TokenList->tokenInQueue($center_id);
        $data['tokenLogging'] = $this->TokenList->tokenLogging($currDate, $center_id);
        $data['center_list'] = $this->Center_list->getcActive();

        $data['login_info'] = $login_info;
        $data['topbar'] = "Dashboard";

        $this->load->view('layout/Manager/header', $data);
        $this->load->view('layout/Manager/sidebar', $data);
        $this->load->view('Manager/dashboard', $data);
        $this->load->view('layout/Manager/footer', $data);
    }
}
