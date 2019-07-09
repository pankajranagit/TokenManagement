<?php
$CI = &get_instance();
$login_info = $CI->customlib->userdetail();

$CI->load->model('Center_list');
$center_rs = $CI->Center_list->get($login_info['center_id'])[0];
// print_r($center_rs['center_address']);
?>
<div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element text-center">
                        <img alt="image" class="img-fluid" style="padding: 10px 20px" src="<?= base_url('assets/ASK_LOGO.png') ?>" />
                        <p style="font-size: 14px;"><?= $this->lang->line('organisation'); ?><br><b><?= $this->lang->line('project_name'); ?></b><br><b><?= $center_rs['center_address'] ?></b> </p>
                    </div>
                    <div class="logo-element">
                        TMS
                    </div>
                </li>

                <li class="<?= $this->session->userdata('active') == 'Dashboard' ? 'active' : '' ?>"><a href="<?= base_url('Manager/Dashboard') ?>"><i class="fa fa-th-large"></i><span class="nav-label">Dashboards</span></a></li>

                <li class="<?= $this->session->userdata('active') == 'ManageOperator' ? 'active' : '' ?>"><a href="<?= base_url('Manager/ManageOperator') ?>"><i class="fa fa-desktop"></i> <span class="nav-label">Manage Operator</span></a></li>

                <li><a href="#"><i class="fa fa-credit-card"></i> <span class="nav-label">Manage Parked token</span></a></li>

                <li><a href="#"><i class="fa fa-file"></i> <span class="nav-label">Report</span></a></li>

                <li><a href="#"><i class="fa fa-cogs"></i> <span class="nav-label">Settings</span></a></li>
            </ul>

        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li style="padding: 20px">
                        <span class="m-r-sm welcome-message">Welcome to ASK Manager Login.</span>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                            <i class="fa fa-bell"></i> <span class="label label-primary">0</span>
                        </a>
                    </li>

                    <li>
                        <a href="<?= base_url('Login/logout') ?>">
                            <i class="fa fa-sign-out"></i> Log out
                        </a>
                    </li>
                </ul>

            </nav>
        </div>