<?php

defined('BASEPATH') or exit('No direct script access allowed');

class CounterMapping extends CI_Controller
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

        $main_menu['active'] = 'CounterMapping';
        $this->session->set_userdata($main_menu);
        $login_info = $this->customlib->userdetail();
        $data['login_info'] = $login_info;
        $data['topbar'] = "Counter Mapping";


        $this->load->view('layout/Admin/header', $data);
        $this->load->view('layout/Admin/sidebar', $data);
        $this->load->view('Admin/countermapping', $data);
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
            echo "<th>Total Counter</th>";
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

            echo '<div align="center"><a href=' . base_url('Admin/CounterMapping/getinfo/' . base64_encode($cat_id)) . ' class="btn btn-primary">Proceed</a></div>';
        } else {
            echo 'NORECORD';
        }
    }

    public function getinfo($id = "")
    {
        $id = base64_decode($id);
        if ($id) {

            $subctg_list = $this->General->getRowByWhere('center_list', array('id' => $id), '', 'single');

            if (!empty($subctg_list)) {

                $this->db->select('operator_type, count(*) as cnt');
                $this->db->from('operator_list');
                $this->db->where('status !=', '1');
                $this->db->where('center_id', $id);
                $this->db->group_by('operator_type');
                $query = $this->db->get();

                $data['count_data'] = $query->result();

                //$data['count_data'] = $this->db->query("SELECT operator_type, count(*) as cnt FROM operator_list where status!='1' AND center_id='" . $id . "' GROUP BY operator_type")->result();

                $main_menu['active'] = 'CounterMapping';
                $this->session->set_userdata($main_menu);
                $login_info = $this->customlib->userdetail();
                $data['login_info'] = $login_info;
                $data['topbar'] = "Manage Counter";
                $data['cebter_data'] = $subctg_list;

                $this->load->view('layout/Admin/header', $data);
                $this->load->view('layout/Admin/sidebar', $data);
                $this->load->view('Admin/countinfo', $data);
                $this->load->view('layout/Admin/footer', $data);
            } else {
                // redirect(base_url('Admin/ManageOperator/'));
            }
        } else {
            // redirect(base_url('Admin/ManageOperator/'));
        }
    }
}
