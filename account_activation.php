<!DOCTYPE HTML>
<html>
	<head>
		<!-- Custom Theme files -->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Norman - Email Verification</title>
		<!--Google Fonts-->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
		<!-- Custom Theme files -->
		<link href="./css/style.css" rel="stylesheet" type="text/css" media="all"/>
		<style>
			.my-text p {
				text-align: center;
				color: #fff;
				padding: 0 30px;
				font-size: 20px;
				margin-bottom: 15px;
			}
		</style>
	</head>
	<body>
		<div class="login">
			<div class="logo">
				<!--<img src="./images/nibio_logo_black.png" alt="iGaz" width="25%">-->
			</div>
			<div class="my-text" style="text-align: center; padding-bottom: 0px;color: #000000;font-size: 24px;">
				<h4>Account Activation</h4>
			</div>
			<div class="my-text" style="padding-top: 20px;">
				<?php if($_GET['status']=='true'){?>
				<p>Your account has been activated.</p>
				<?php } ?>
			</div>
			<div class="my-text">
				<?php if($_GET['status']=='false'){?>
				<p>Your account already activated.</p>
				<?php } ?>
			</div>
		</div>
	</body>
</html>