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

        $this->User = $this->customlib->userdetail();
        //print_r($this->User);
        //$login_type = $this->Login_user->LoginType($this->User['username']);

        if ($this->User['login_type'] != 'ADMIN') {
            redirect(base_url('Login/logout'));
        }
    }

    public function index()
    {
        $main_menu['active'] = 'Dashboard';
        $this->session->set_userdata($main_menu);

        $login_info = $this->customlib->userdetail();

        $data['login_info'] = $login_info;
        $data['topbar'] = "Dashboard";

        $data['center_list'] = $this->Center_list->getcActive();

        $data['total_applicant'] = $this->TokenList->totalApplicant();
        $data['today_applicant'] = $this->TokenList->todayApplicant();

        $this->load->view('layout/Admin/header', $data);
        $this->load->view('layout/Admin/sidebar', $data);
        $this->load->view('Admin/dashboard', $data);
        $this->load->view('layout/Admin/footer', $data);
    }
}
