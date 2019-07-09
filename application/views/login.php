<!DOCTYPE html>
<html lang="en">

<head>
    <title>Aadhaar Seva Kendra | <?= $this->lang->line('project_name'); ?></title>
    <link rel="icon" href="<?= base_url('assets/images/favicon.ico') ?>" type="image/x-icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 text-center first_half">
                <img src="<?= base_url('assets/images/Aadhaar_Logo_White.svg') ?>" class="img-fluid" />
                <h3 class="head_name">Aadhaar Seva Kendra<br><b><?= $this->lang->line('project_name'); ?></b></h3>
            </div>

            <div class="col-lg-6 offset-lg-6">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 login-panel">
                        <h3>PLEASE ENTER YOUR DETAILS</h3>
                        <div class="border-bottom"></div>
                        <!-- <form method="post" action="<?= base_url('Login/index') ?>"> -->
                        <?php echo form_open(base_url('Login/index')); ?>
                        <div class="form-group">
                            <label>Username</label>
                            <input name="username" type="text" class="form-control" placeholder="Enter Username">
                            <?php echo form_error('username'); ?>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter Password">
                            <?php echo form_error('password'); ?>
                        </div>
                        <div class="form-group">
                            <table style="width: 100%">
                                <tr>
                                    <td><label class="small">Type the character you see in this image</label></td>
                                    <td class="text-right"><label class="small"><a href="#" class="refreshCaptcha">New Image</a> </label></td>
                                </tr>
                            </table>
                            <table class="table">
                                <tr>
                                    <td>
                                        <input required="" value="<?= $sessCaptcha ?>" autocomplete="off" type="text" class="form-control" name="captcha" placeholder="Type Characters">
                                        <?= ($captcha_error ? "<lable class=\"small text-danger\">You have mistyped the captcha.</lable>" : "") ?>
                                    </td>

                                    <td>
                                        <div id="captImg"><?php echo $captchaImg; ?></div>
                                    </td>
                                </tr>
                            </table>
                            <input type="hidden" id="base_url_captcha" value="<?= base_url('Login/refresh') ?>">
                        </div>
                        <?php echo $this->session->flashdata('msg'); ?>
                        <hr>
                        <button class="btn btn-warning btn-block" name="submit" type="submit">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/js/jquery-3.3.1.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/custom.js') ?>"></script>
</body>

</html>