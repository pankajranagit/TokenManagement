<!DOCTYPE html>
<html lang="en">

<head>
    <title>Aadhaar Seva Kendra | <?= $this->lang->line('project_name'); ?></title>
    <link rel="icon" href="<?= base_url('assets/images/favicon.ico') ?>" type="image/x-icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link href="<?= base_url('assets/css/plugins/iCheck/custom.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/helpdesk.css') ?>">
    <link href="<?= base_url('assets/css/animate.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') ?>" rel="stylesheet">
</head>
<section id="header-section" style="display:none">
    <div class="container">
        <div class="top-bar">
            <div class="row">
                <div class="col-lg-6">
                    <table class="logo-table">
                        <tr>
                            <td><img src="<?= base_url('assets/images/Aadhaar_Logo.svg') ?>" class="aadhaar-logo" /></td>
                            <td>
                                <div class="main-h"><?= $this->lang->line('project_name'); ?></div>
                                <div class="sub-h"><?= $this->lang->line('organisation'); ?></div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-6 text-right">
                    <ul class="inline-ul" style="font-size: .9em">
                        <li><?= date('l, d M, Y') ?></li>
                        <li>
                            <a href="<?= base_url('Login/logout') ?>">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<nav class="uidai-header-2 p-3">
    <div class="">
        <div class="row">
            <div class="col-lg-8" style="display:none">
                <h4 class="centerinfo"><b>ASK Center</b> : <?= $operator['center_name'] ?></h4>
            </div>
            <div class="col-lg-4" style="display:none">
                <h4 class="centerinfo text-right"><b>Counter Type</b> : <?= $this->customlib->token_lable($user['operator_type']) ?></h4>
            </div>
            <div class="col-lg-8">
                <h4 class="centerinfo text-left"><b>Current Token Status</b>
            </div>
            <div class="col-lg-4 text-right">
                <select class="form-control" onchange="change_status('<?= $daily_login['id'] ?>', this.value)">
                    <option value="NULL">Active</option>
                    <?php
                    $curr_status = $this->customlib->curr_status();
                    foreach ($curr_status as $key => $val) {
                        if ($daily_login['curr_status'] == $key) {
                            echo "<option selected value='" . $key . "'>" . $val . "</option>";
                        } else {
                            echo "<option value='" . $key . "'>" . $val . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
</nav>