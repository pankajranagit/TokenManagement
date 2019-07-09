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

        $main_menu['active'] = 'ManageOperator';
        $this->session->set_userdata($main_menu);
        $login_info = $this->customlib->userdetail();
        $data['login_info'] = $login_info;
        $data['topbar'] = "Manage Counter";


        $this->load->view('layout/Admin/header', $data);
        $this->load->view('layout/Admin/sidebar', $data);
        $this->load->view('Admin/operator', $data);
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

            echo '<div align="center"><a href=' . base_url('Admin/ManageOperator/addoperator/' . base64_encode($cat_id)) . ' class="btn btn-primary">Proceed</a></div>';
        } else {
            echo 'NORECORD';
        }
    }

    public function addoperator($id = "")
    {
        $id = base64_decode($id);
        if ($id) {
            $subctg_list = $this->General->getRowByWhere('center_list', array('id' => $id), '', 'single');
            if (!empty($subctg_list)) {
                $this->form_validation->set_rules('operator_name', 'Operator Name', 'trim|required|min_length[3]');
                $this->form_validation->set_rules('operator_number', 'Mobile Number', 'trim|required|min_length[3]|is_numeric');
                $this->form_validation->set_rules('operator_type', 'Operator Type', 'trim|required');
                $this->form_validation->set_rules('operator_email', 'Operator Email', 'trim|required|min_length[3]|is_unique[operator_list.operator_email]');

                if ($this->form_validation->run() == TRUE) {

                    $form_data['operator_name'] = $this->input->post('operator_name');
                    $form_data['operator_number'] = $this->input->post('operator_number');
                    $form_data['operator_type'] = $this->input->post('operator_type');
                    $form_data['operator_email'] = $this->input->post('operator_email');
                    $form_data['center_id'] = $id;
                    $form_data['create_on'] = date('Y-m-d');

                    $userDetails = $this->General->add_data('operator_list', $form_data);
                    if ($userDetails > 0) {
                        $username = $this->input->post('operator_email');
                        $password = "Uidai@2019#";
                        $log_data['username'] = $this->input->post('operator_email');
                        //$log_data['password'] = md5($password);

                        $log_data['password'] = $this->encrypt->encode($password);
                        //die;
                        $log_data['login_type'] = 'OPERATOR';
                        $log_data['operator_type'] = $this->input->post('operator_type');
                        $log_data['create_on'] = date('Y-m-d h:i:s');
                        $log_data['email_verify'] = 1;
                        $log_data['center_id'] = $id;

                        $this->General->add_data('login_user', $log_data);

                        $subject = "ASK Admin";

                        $message = '<h3>Welcome to ASK</h3>';
                        $message .= "<h4>Login Credential</h3>";
                        $message .= "<p>Link : " . base_url() . "</p>";
                        $message .= "<p>Username : $username</p>";
                        $message .= "<p>Password : $password</p>";

                        // Get full html:
                        $body = $this->customlib->mail_body($message);
                        $this->customlib->send_mail($this->input->post('operator_email'), $subject, $body);





                        $this->session->set_flashdata('msg', "<p class='text-success'>Operator added successfully</p>");
                        redirect(current_url());
                    } else {
                        $this->session->set_flashdata('msg', "<p class='text-danger'>Please Try Again</p>");
                        redirect(current_url());
                    }
                } else {

                    $opt_typ = $this->customlib->getOperatorType();
                    $data['opt_typ'] = $opt_typ;


                    $data['operator_list'] = $this->General->getRowByWhere('operator_list', array('center_id' => $id));

                    $main_menu['active'] = 'ManageOperator';
                    $this->session->set_userdata($main_menu);
                    $login_info = $this->customlib->userdetail();
                    $data['login_info'] = $login_info;
                    $data['topbar'] = "Manage Operator";
                    $data['cebter_data'] = $subctg_list;

                    $this->load->view('layout/Admin/header', $data);
                    $this->load->view('layout/Admin/sidebar', $data);
                    $this->load->view('Admin/addoperator', $data);
                    $this->load->view('layout/Admin/footer', $data);
                }
            } else {
                // redirect(base_url('Admin/ManageOperator/'));
            }
        } else {
            // redirect(base_url('Admin/ManageOperator/'));
        }
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
                    redirect(base_url('Admin/ManageOperator/addoperator/' . $centerid));
                } else {
                    $this->session->set_flashdata('msg', "<p class='text-danger'>Please Try Again.</p>");
                    redirect(base_url('Admin/ManageOperator/addoperator/' . $centerid));
                }
            } else {
                redirect(base_url('Admin/ManageOperator/addoperator/' . $centerid));
            }
        } else {
            redirect(base_url('Admin/ManageOperator/addoperator/' . $centerid));
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
                    redirect(base_url('Admin/ManageOperator/addoperator/' . $centerid));
                } else {
                    $this->session->set_flashdata('msg', "<p class='text-danger'>Please Try Again.</p>");
                    redirect(base_url('Admin/ManageOperator/addoperator/' . $centerid));
                }
            } else {
                redirect(base_url('Admin/ManageOperator/addoperator/' . $centerid));
            }
        } else {
            redirect(base_url('Admin/ManageOperator/addoperator/' . $centerid));
        }
    }




    public function edit_operator($did = "")
    {
        $d_id = base64_decode($did);
        if ($d_id) {
            $getdata = $this->Center_list->getSingleRowById('operator_list', 'id', $d_id);
            if (!empty($getdata)) {

                $centerid = base64_encode($getdata->center_id);

                $this->form_validation->set_rules('operator_name', 'Operator Name', 'trim|required|min_length[3]');
                $this->form_validation->set_rules('operator_number', 'Mobile Number', 'trim|required|min_length[3]|is_numeric');
                $this->form_validation->set_rules('operator_type', 'Operator Type', 'trim|required');

                if ($this->form_validation->run() == TRUE) {

                    $form_data['operator_name'] = $this->input->post('operator_name');
                    $form_data['operator_number'] = $this->input->post('operator_number');
                    $form_data['operator_type'] = $this->input->post('operator_type');


                    $this->Center_list->modify('operator_list', 'id', $d_id, $form_data);

                    $this->session->set_flashdata('msg', "<p class='text-success'>Operator Updated successfully</p>");
                    redirect('Admin/ManageOperator/addoperator/' . $centerid);
                } else {

                    $opt_typ = $this->customlib->getOperatorType();
                    $data['opt_typ'] = $opt_typ;

                    $subctg_list = $this->General->getRowByWhere('center_list', array('id' => $getdata->center_id), '', 'single');

                    $main_menu['active'] = 'ManageOperator';
                    $this->session->set_userdata($main_menu);
                    $login_info = $this->customlib->userdetail();
                    $data['login_info'] = $login_info;
                    $data['topbar'] = "Manage Operator";
                    $data['cebter_data'] = $subctg_list;
                    $data['op_data'] = $getdata;

                    $this->load->view('layout/Admin/header', $data);
                    $this->load->view('layout/Admin/sidebar', $data);
                    $this->load->view('Admin/editoperator', $data);
                    $this->load->view('layout/Admin/footer', $data);
                }
            } else {
                redirect(base_url('Admin/ManageOperator/addoperator/' . $centerid));
            }
        } else {
            redirect(base_url('Admin/ManageOperator/addoperator/' . $centerid));
        }
    }
}
