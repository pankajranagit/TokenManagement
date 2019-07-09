<!DOCTYPE html>
<html lang="en">

<head>
    <title>Aadhaar Seva Kendra | <?= $this->lang->line('project_name'); ?></title>
    <link rel="icon" href="<?= base_url('assets/images/favicon.ico') ?>" type="image/x-icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/helpdesk.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/print.min.css') ?>">
</head>
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
                        <img src="<?= base_url('assets/images/ic_langauge.svg') ?>" style="width: 1.5em;">
                        <select style="width: 100px; padding: 5px; border: 0px;">
                            <option>English</option>
                            <option disabled>Hindi</option>
                        </select>
                    </li>
                    <li><a class="btn btn-danger btn-xs" style="color: #FFF" href="<?= base_url('Login/logout') ?>">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<nav class="uidai-header-2 p-3">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="centerinfo"><b>ASK Center : </b><?= $operator['center_name'] ?>, <?= $operator['city'] ?>, <?= $operator['state'] ?></h4>
            </div>
            <div class="col-lg-4">
                <h4 class="centerinfo text-right"><b>Counter Type</b> : <?= $this->customlib->token_lable($user['operator_type']) ?></h4>
            </div>
        </div>
    </div>
</nav>