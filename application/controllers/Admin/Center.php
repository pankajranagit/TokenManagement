<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Center extends CI_Controller
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

        $this->load->model('Center_list');
    }

    public function index($id = '')
    {
        if ($this->form_validation->run('add_center') == true) {
            $form_data['center_name'] = $this->input->post('center_name');
            $form_data['state'] = $this->input->post('state');
            $form_data['city'] = $this->input->post('city');
            $form_data['center_address'] = $this->input->post('center_address');



            $cityname = strtolower($this->input->post('city'));
            $countcity = get_count('center_list', array('city' => $this->input->post('city')));
            $incresebyone = $countcity + 1;
            $add_data['username'] = $cityname . $incresebyone . "@aadhaar.in";
            //$add_data['password'] = md5('Uidai@2019#');
            $add_data['password'] = $this->encrypt->encode('Uidai@2019#');;
            $add_data['login_type'] = "MANAGER";
            $add_data['create_on'] = date('Y-m-d h:i:s');
            $add_data['email_verify'] = 1;

            $userDetails = $this->Center_list->add($form_data);
            if ($userDetails > 0) {

                $add_data['center_id'] = $userDetails;

                $this->Center_list->add_login('login_user', $add_data);

                $this->session->set_flashdata('msg', "<p class='text-success'>Aadhaar Seva kendra added successfully</p>");
                redirect(base_url('Admin/Center/'));
            } else {
                $this->session->set_flashdata('msg', "<p class='text-danger'>Failed, Please Try Again</p>");
                redirect(base_url('Admin/Center/'));
            }
        }

        $data['center_list'] = $this->Center_list->get();

        $main_menu['active'] = 'Center';
        $this->session->set_userdata($main_menu);
        $login_info = $this->customlib->userdetail();
        $data['login_info'] = $login_info;
        $data['topbar'] = "Manage Center";

        $data['statelist'] = $this->Center_list->getlocation();

        $this->load->view('layout/Admin/header', $data);
        $this->load->view('layout/Admin/sidebar', $data);
        $this->load->view('Admin/center', $data);
        $this->load->view('layout/Admin/footer', $data);
    }




    public function remove($did = "")
    {
        $d_id = base64_decode($did);
        if ($d_id) {
            $getdata = $this->Center_list->getSingleRowById('center_list', 'id', $d_id);
            if (!empty($getdata)) {
                $add_data['status'] = 1;
                $this->Center_list->modify('center_list', 'id', $d_id, $add_data);
                if ($this->db->affected_rows() > 0) {
                    $this->session->set_flashdata('msg', "<p class='text-success'>Center has been Disabled Successfully.</p>");
                    redirect(base_url('Admin/Center/'));
                } else {
                    $this->session->set_flashdata('msg', "<p class='text-danger'>Please Try Again.</p>");
                    redirect(base_url('Admin/Center/'));
                }
            } else {
                redirect(base_url('Admin/Center/'));
            }
        } else {
            redirect(base_url('Admin/Center/'));
        }
    }

    public function enable($did = "")
    {
        $d_id = base64_decode($did);
        if ($d_id) {
            $getdata = $this->Center_list->getSingleRowById('center_list', 'id', $d_id);
            if (!empty($getdata)) {
                $add_data['status'] = 0;
                $this->Center_list->modify('center_list', 'id', $d_id, $add_data);
                if ($this->db->affected_rows() > 0) {
                    $this->session->set_flashdata('msg', "<p class='text-success'>Center has been Enabled Successfully.</p>");
                    redirect(base_url('Admin/Center/'));
                } else {
                    $this->session->set_flashdata('msg', "<p class='text-danger'>Please Try Again.</p>");
                    redirect(base_url('Admin/Center/'));
                }
            } else {
                redirect(base_url('Admin/Center/'));
            }
        } else {
            redirect(base_url('Admin/Center/'));
        }
    }






    public function edit_center($did = "")
    {
        $d_id = base64_decode($did);
        if ($d_id) {
            $getdata = $this->Center_list->getSingleRowById('center_list', 'id', $d_id);
            if (!empty($getdata)) {

                $this->form_validation->set_rules('center_name', 'Center Name', 'trim|required|min_length[3]|callback_check_center_name');
                $this->form_validation->set_rules('center_address', 'Center Address', 'trim|required|min_length[3]');

                if ($this->form_validation->run() == TRUE) {
                    $form_data['center_name'] = $this->input->post('center_name');
                    $form_data['center_address'] = $this->input->post('center_address');

                    $this->Center_list->modify('center_list', 'id', $d_id, $form_data);
                    if ($this->db->affected_rows() > 0) {
                        $this->session->set_flashdata('msg', "<p class='text-success'>Update Successfully.</p>");
                        redirect(base_url('Admin/Center/'));
                    } else {
                        $this->session->set_flashdata('msg', "<p class='text-danger'>Please Try Again.</p>");
                        redirect(base_url('Admin/Center/'));
                    }
                } else {
                    $main_menu['active'] = 'Center';
                    $this->session->set_userdata($main_menu);
                    $login_info = $this->customlib->userdetail();
                    $data['login_info'] = $login_info;
                    $data['topbar'] = "Manage Center";
                    $data['centerdata'] = $getdata;

                    $this->load->view('layout/Admin/header', $data);
                    $this->load->view('layout/Admin/sidebar', $data);
                    $this->load->view('Admin/editcenter', $data);
                    $this->load->view('layout/Admin/footer', $data);
                }
            } else {
                //redirect(base_url('Admin/Center/'));  
            }
        } else {
            // redirect(base_url('Admin/Center/'));
        }
    }

    function check_center_name($center_name)
    {
        $cid = $this->input->post('cid');
        $result = $this->Center_list->check_unique_center_name($center_name, $cid);
        if ($result == 0) {
            $response = true;
        } else {
            $this->form_validation->set_message('check_center_name', 'Center Name Allready Exist');
            $response = false;
        }
        return $response;
    }





    public function getcitylocation()
    {
        $cat_id = $this->input->post('catg_id');
        $subctg_list = $this->Center_list->getRowByWhere('location', array('state_name' => $cat_id));

        if (!empty($subctg_list)) {
            echo '<select class="form-control" name="city" required="">';
            echo '<option value="" >Select City</option>';

            foreach ($subctg_list as $slist) {

                echo '<option value="' . $slist->city_name . '" >' . $slist->city_name . '</option>';
            }
            echo "</select>";
        } else {
            echo 'NORECORD';
        }
    }
}
