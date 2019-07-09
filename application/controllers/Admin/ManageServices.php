<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ManageServices extends CI_Controller
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

        $this->load->model('Helpdesk');
    }

    public function index($id = '')
    {


        $data['center_list'] = $this->General->getRowByWhere('center_list', array('status' => 0));

        $main_menu['active'] = 'ManageServices';
        $this->session->set_userdata($main_menu);
        $login_info = $this->customlib->userdetail();
        $data['login_info'] = $login_info;
        $data['topbar'] = "Manage Services";


        $this->load->view('layout/Admin/header', $data);
        $this->load->view('layout/Admin/sidebar', $data);
        $this->load->view('Admin/services', $data);
        $this->load->view('layout/Admin/footer', $data);
    }


    public function getservicesinfo()
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

            echo '<div align="center"><a href=' . base_url('Admin/ManageServices/addservices/' . base64_encode($cat_id)) . ' class="btn btn-primary">Proceed</a></div>';
        } else {
            echo 'NORECORD';
        }
    }

    public function addservices($id = "")
    {

        $id = base64_decode($id);
        if ($id) {

            $subctg_list = $this->General->getRowByWhere('center_list', array('id' => $id), '', 'single');

            if (!empty($subctg_list)) {
                $this->form_validation->set_rules('operator_type[]', 'Operator Type', 'trim|required|min_length[2]');

                if ($this->form_validation->run() == TRUE) {
                    $operator_type = $this->input->post('operator_type');

                    foreach ($_SESSION['selected'] as $vd) {
                        $arr[] = $vd['id'];
                        $vid = implode(',', $arr);
                    }

                    $operator_type = implode(',', $operator_type);

                    $form_data['applicant_type'] = $vid;
                    $form_data['counter'] = $operator_type;
                    $form_data['center_id'] = $id;
                    $form_data['create_on'] = date('Y-m-d');

                    $userDetails = $this->General->add_data('services', $form_data);
                    if ($userDetails > 0) {
                        unset($_SESSION['selected']);
                        $this->session->set_flashdata('msg', "<p class='text-success'>Service added successfully</p>");
                        redirect(current_url());
                    } else {
                        $this->session->set_flashdata('msg', "<p class='text-danger'>Please Try Again</p>");
                        redirect(current_url());
                    }
                } else {
                    unset($_SESSION['selected']);

                    $data['helpdesk_list'] = $this->Helpdesk->getParents($ids = NULL);
                    $data['services'] = $this->General->getRowByWhere('services', array('center_id' => $id));

                    //echo $this->db->last_query(); die;

                    $main_menu['active'] = 'ManageServices';
                    $this->session->set_userdata($main_menu);
                    $login_info = $this->customlib->userdetail();
                    $data['login_info'] = $login_info;
                    $data['topbar'] = "Manage Priority";

                    $data['cebter_data'] = $subctg_list;

                    $opt_typ = $this->customlib->getOperator();
                    $data['opt_typ'] = $opt_typ;

                    $this->load->view('layout/Admin/header', $data);
                    $this->load->view('layout/Admin/sidebar', $data);
                    $this->load->view('Admin/addservices', $data);
                    $this->load->view('layout/Admin/footer', $data);
                }
            } else {
                // redirect(base_url('Admin/ManageOperator/'));
            }
        } else {
            // redirect(base_url('Admin/ManageOperator/'));
        }
    }

    public function deleteservices($center_id = "", $id = "")
    {
        $center_id = base64_decode($center_id) . '<br />';
        $id = base64_decode($id);

        $data_where['center_id'] = $center_id;
        $data_where['id'] = $id;

        $this->db->trans_start();

        $userDetails = $this->General->delete('services', $data_where);

        $this->db->trans_complete();

        if ($userDetails > 0) {
            unset($_SESSION['selected']);
            $this->session->set_flashdata('msg', "<p class='text-success'>Service delete successfully</p>");
            redirect(base_url('Admin/ManageServices/addservices/' . base64_encode($center_id)));
        } else {
            $this->session->set_flashdata('msg', "<p class='text-danger'>Please Try Again</p>");
            redirect(base_url('Admin/ManageServices/addservices/' . base64_encode($center_id)));
        }
    }


    public function getchildinfo()
    {
        $cat_id = $this->input->post('catg_id');

        $sessionid = $this->input->post('id');

        $nxt_sessionid = $sessionid + 1;

        $temp["lable"] = $this->Helpdesk->getInfo($cat_id)['lable'];
        $temp["id"] = $cat_id;

        $_SESSION['selected'][$sessionid] = $temp;

        $subctg_list = $this->Helpdesk->getParents($cat_id);



        if (!empty($subctg_list)) {
            echo '<div class="col-sm-12"><label>&nbsp;</label><br>';
            echo "<table width='100%'>";
            echo "<tr>";
            foreach ($_SESSION['selected'] as $sessionvalue) {
                echo "<td>" . $sessionvalue['lable'] . "</td>";
            }

            echo "<td>";


            echo '<select class="form-control" id="labeloneselect" onchange="getchildinfo(this.value,' . $nxt_sessionid . ')" name="lable[]" required="">';
            echo '<option value="" selected disabled>Select</option>';

            foreach ($subctg_list as $slist) {

                echo '<option value="' . $slist['id'] . '" >' . $slist['lable'] . '</option>';
            }
            echo "</select>";
            echo "</td>";
            echo "</tr></table>";
            echo "</div>";
        } else {
            echo 'NORECORD';
        }
    }
}
