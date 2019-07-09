<?php

defined('BASEPATH') or exit('No direct script access allowed');

class CenterDashboard extends CI_Controller
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

        //$this->load->model('Center_list');
    }

    public function index($id = '')
    {


        $data['center_list'] = $this->General->getRowByWhere('center_list', array('status' => 0));

        $main_menu['active'] = 'CenterDashboard';
        $this->session->set_userdata($main_menu);
        $login_info = $this->customlib->userdetail();
        $data['login_info'] = $login_info;
        $data['topbar'] = "Center Dashboard";


        $this->load->view('layout/Admin/header', $data);
        $this->load->view('layout/Admin/sidebar', $data);
        $this->load->view('Admin/centerdashboard', $data);
        $this->load->view('layout/Admin/footer', $data);
    }


    public function getcenterinfo()
    {
        $cat_id = $this->input->post('catg_id');
        $subctg_list = $this->General->getRowByWhere('center_list', array('id' => $cat_id), '', 'single');

        if (!empty($subctg_list)) {
            echo '<table class="table table-bordered">';
            echo "<tr>";
            echo "<th>Center Name</th>";
            echo "<td>" . $subctg_list->center_name . "</td>";
            echo "<th>Total Operator</th>";
            echo "<td>0</td>";
            echo "</tr>";

            echo "<tr>";
            echo "<th>City</th>";
            echo "<td>" . $subctg_list->city . "</td>";
            echo "<th>Help Desk</th>";
            echo "<td>0</td>";
            echo "</tr>";

            echo "<tr>";
            echo "<th>State</th>";
            echo "<td>" . $subctg_list->state . "</td>";
            echo "<th>PD</th>";
            echo "<td>0</td>";
            echo "</tr>";

            echo "<tr>";
            echo "<th>Address</th>";
            echo "<td>" . $subctg_list->center_address . "</td>";
            echo "<th>ECMP</th>";
            echo "<td>0</td>";
            echo "</tr>";



            echo "</table>";

            echo '<div align="center"><a href=' . base_url('Admin/CenterDashboard/viewdashboard/' . base64_encode($cat_id)) . ' class="btn btn-primary">Proceed</a></div>';
        } else {
            echo 'NORECORD';
        }
    }

    public function viewdashboard($id = "")
    {
        $id = base64_decode($id);
        if ($id) {

            $subctg_list = $this->General->getRowByWhere('center_list', array('id' => $id), '', 'single');

            if (!empty($subctg_list)) {





                $opt_typ = $this->customlib->getOperatorType();
                $data['opt_typ'] = $opt_typ;


                $data['operator_list'] = $this->General->getRowByWhere('operator_list', array('center_id' => $id, 'status' => 0));

                $main_menu['active'] = 'CenterDashboard';
                $this->session->set_userdata($main_menu);
                $login_info = $this->customlib->userdetail();
                $data['login_info'] = $login_info;
                $data['topbar'] = "Center Dashboard";
                $data['cebter_data'] = $subctg_list;

                $data['total_applicant'] = $this->TokenList->totalApplicant($id);
                $data['today_applicant'] = $this->TokenList->todayApplicant($id);

                $this->load->view('layout/Admin/header', $data);
                $this->load->view('layout/Admin/sidebar', $data);
                $this->load->view('Admin/viewcenteroperator', $data);
                $this->load->view('layout/Admin/footer', $data);
            } else {
                // redirect(base_url('Admin/ManageOperator/'));
            }
        } else {
            // redirect(base_url('Admin/ManageOperator/'));
        }
    }
}
