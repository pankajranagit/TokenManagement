<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ManageOperator extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (empty($this->session->userdata('login_detail'))) {
            redirect(base_url());
        }

        $this->load->model('Operator');
    }

    public function index($currUnixDate = '')
    {
        $main_menu['active'] = 'ManageOperator';
        $this->session->set_userdata($main_menu);

        $login_info = $this->customlib->userdetail();

        $center_id = $login_info['center_id'];

        if ($currUnixDate) {
            $data['currDate'] = $currDate = date('Y-m-d', $currUnixDate);
        } else {
            $data['currDate'] = $currDate = date('Y-m-d');
        }

        $data['operator_list'] = $this->Operator->DailyOperatorStatus($currDate, $center_id);

        $data['login_info'] = $login_info;
        $data['topbar'] = "ManageOperator";

        $this->load->view('layout/Manager/header', $data);
        $this->load->view('layout/Manager/sidebar', $data);
        $this->load->view('Manager/operator', $data);
        $this->load->view('layout/Manager/footer', $data);
    }



    public function remove($did = "")
    {
        $d_id = base64_decode($did);
        if ($d_id) {
            $getdata = $this->Center_list->getSingleRowById('operator_list', 'id', $d_id);
            if (!empty($getdata)) {

                $centerid = base64_encode($getdata->center_id);

                $add_data['status'] = 1;
                $this->Center_list->modify('operator_list', 'id', $d_id, $add_data);
                if ($this->db->affected_rows() > 0) {
                    $this->session->set_flashdata('msg', "<p class='text-success'>Operator has been Disabled Successfully.</p>");
                    redirect(base_url('Manager/ManageOperator/'));
                } else {
                    $this->session->set_flashdata('msg', "<p class='text-danger'>Please Try Again.</p>");
                    redirect(base_url('Manager/ManageOperator/'));
                }
            } else {
                redirect(base_url('Manager/ManageOperator/'));
            }
        } else {
            redirect(base_url('Manager/ManageOperator/'));
        }
    }


    public function enable($did = "")
    {
        $d_id = base64_decode($did);
        if ($d_id) {
            $getdata = $this->Center_list->getSingleRowById('operator_list', 'id', $d_id);
            if (!empty($getdata)) {

                $centerid = base64_encode($getdata->center_id);

                $add_data['status'] = 0;
                $this->Center_list->modify('operator_list', 'id', $d_id, $add_data);
                if ($this->db->affected_rows() > 0) {
                    $this->session->set_flashdata('msg', "<p class='text-success'>Operator has been Enabled Successfully.</p>");
                    redirect(base_url('Manager/ManageOperator/'));
                } else {
                    $this->session->set_flashdata('msg', "<p class='text-danger'>Please Try Again.</p>");
                    redirect(base_url('Manager/ManageOperator/'));
                }
            } else {
                redirect(base_url('Manager/ManageOperator/'));
            }
        } else {
            redirect(base_url('Manager/ManageOperator/'));
        }
    }


    public function changeStatusOperator()
    {
        $dailyId = $_POST['dailyId'];
        $status = $_POST['status'];

        $this->Operator->update_dailylogin_status($dailyId, $status);
    }
}
