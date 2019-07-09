<?php
$ci = new CI_Controller();
$ci = &get_instance();
$ci->load->helper('url');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Aadhaar Seva Kendra | <?= $ci->lang->line('project_name'); ?></title>
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
				<h3 class="head_name">Aadhaar Seva Kendra<br><b><?= $ci->lang->line('project_name'); ?></b></h3>
			</div>

			<div class="col-lg-6 offset-lg-6">
				<div class="row">
					<div class="col-lg-8 offset-lg-2 login-panel">
						<h1><?php echo $heading; ?></h1>
						<?php echo $message; ?>
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