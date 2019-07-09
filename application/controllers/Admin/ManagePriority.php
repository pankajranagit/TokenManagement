<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ManagePriority extends CI_Controller
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

        $main_menu['active'] = 'ManagePriority';
        $this->session->set_userdata($main_menu);
        $login_info = $this->customlib->userdetail();
        $data['login_info'] = $login_info;
        $data['topbar'] = "Manage Priority";


        $this->load->view('layout/Admin/header', $data);
        $this->load->view('layout/Admin/sidebar', $data);
        $this->load->view('Admin/priority', $data);
        $this->load->view('layout/Admin/footer', $data);
    }


    public function getpropertyinfo()
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

            echo '<div align="center"><a href=' . base_url('Admin/ManagePriority/addpriority/' . base64_encode($cat_id)) . ' class="btn btn-primary">Proceed</a></div>';
        } else {
            echo 'NORECORD';
        }
    }

    public function addpriority($id = "")
    {
        $id = base64_decode($id);
        if ($id) {

            $subctg_list = $this->General->getRowByWhere('center_list', array('id' => $id), '', 'single');

            if (!empty($subctg_list)) {





                $this->form_validation->set_rules('priority_type', 'Priority Type', 'trim|required|min_length[2]');

                if ($this->form_validation->run() == TRUE) {

                    $form_data['priority_type'] = $this->input->post('priority_type');
                    $form_data['center_id'] = $id;
                    $form_data['create_on'] = date('Y-m-d');

                    $userDetails = $this->General->add_data('priority', $form_data);
                    if ($userDetails > 0) {

                        $this->session->set_flashdata('msg', "<p class='text-success'>Priority added successfully</p>");
                        redirect(current_url());
                    } else {
                        $this->session->set_flashdata('msg', "<p class='text-danger'>Please Try Again</p>");
                        redirect(current_url());
                    }
                } else {

                    $opt_typ = $this->customlib->getOperatorType();
                    $data['opt_typ'] = $opt_typ;


                    $data['operator_list'] = $this->General->getRowByWhere('priority', array('center_id' => $id));

                    $main_menu['active'] = 'ManagePriority';
                    $this->session->set_userdata($main_menu);
                    $login_info = $this->customlib->userdetail();
                    $data['login_info'] = $login_info;
                    $data['topbar'] = "Manage Priority";
                    $data['cebter_data'] = $subctg_list;

                    $this->load->view('layout/Admin/header', $data);
                    $this->load->view('layout/Admin/sidebar', $data);
                    $this->load->view('Admin/addpriority', $data);
                    $this->load->view('layout/Admin/footer', $data);
                }
            } else {
                // redirect(base_url('Admin/ManageOperator/'));
            }
        } else {
            // redirect(base_url('Admin/ManageOperator/'));
        }
    }



    public function change_priority()
    {
        $order_no = $this->input->post('status_id');
        $slider_id = $this->input->post('ord_id');
        $center_id = $this->input->post('center_id');

        $checkStatus = $this->General->getRowByWhere('priority', array('priority' => $order_no, 'center_id' => $center_id), '', 'single');

        if ($checkStatus->priority > 0) {
            echo "Fail";
        } else {
            $this->General->modify('priority', 'id', $slider_id, array('priority' => $order_no));
            echo "success";
        }
    }
}
