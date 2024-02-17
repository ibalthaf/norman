<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- Meta, title, CSS, favicons, etc. -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/png" href="images/favicon.png">
		<title>Norman Brickwork & Gatwick Scaffolding Horley,Redhill,Reigate,Surrey|Norman Group</title>
		<!-- Bootstrap -->
		<link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="css/toaster.css" rel="stylesheet">
		<!-- Font Awesome -->
		<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<!-- NProgress -->
		<link href="vendors/nprogress/nprogress.css" rel="stylesheet">
		<!-- bootstrap-daterangepicker -->
		<link href="vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
		<!-- iCheck -->
		<link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
		
		<!-- Datatables -->
		<link href="vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
		<link href="vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
		<link href="vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
		<link href="vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
		<link href="vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

		<!-- bootstrap-progressbar -->
		<link href="vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
		<!-- JQVMap -->
		<link href="vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>

		<!-- Custom Theme Style -->
		<link href="build/css/custom.min.css" rel="stylesheet">

		<link href="css/my-style.css" rel="stylesheet">

		<!-- JS Files -->
		<script src="scripts/viewmodel/settings.js" type="text/javascript"></script>
		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/knockout-3.3.0.js" type="text/javascript"></script>
		<script src="js/knockout.validation -2.0.3.js" type="text/javascript"></script>
		<script src="js/toaster.js" type="text/javascript"></script>
		<script src="scripts/services/datasource-service.js" type="text/javascript"></script>
		<style>
			.plain_logo{
				height: 29px !important;
				padding-right: 16px !important;
				width: 50px !important;
			}
		</style>
	</head>
	<body class="nav-md">
		<!-- pre-loader -->
		<div class="ajax-loader">
			<img src="images/loader.gif"/>
		</div>
		<!-- /pre-loader -->
		<div class="container body">
			<div class="main_container">
				<div class="col-md-3 col-sm-12 col-xs-12 left_col">
					<div class="left_col scroll-view">
						<div class="navbar nav_title" style="border: 0;">
							<a href="dashboard.php" class="site_title">
								<div class="norman-image">
									<img src="images/norman-logo.png" class="norman-scaffolding" alt="norman" height="10px">
								</div>
							</a>
						</div>
						<div class="clearfix"></div>
						<!-- menu profile quick info -->
						<div class="profile clearfix">
						  <div class="profile_pic">
							<!--<img src="images/loader-sm.gif" class="img-circle profile_img" alt="Profile Image">-->
						  </div>
						  <div class="profile_info">
							<span>Welcome,</span>
							<h2 class="lbllogin"></h2>
						  </div>
						</div>
						<!-- /menu profile quick info -->
						<!-- sidebar menu -->
						<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
							<div class="menu_section">
								<ul class="nav side-menu login-side-menu">
									<li>
										<a href="dashboard.php"><i class="fa fa-list"></i> Forms Management</a>
									</li>
								</ul>
							</div>
						</div>
						<!-- /sidebar menu -->
					</div>
				</div>
				<!-- top navigation -->
				<div class="top_nav">
					<div class="nav_menu">
						<nav>
							<div class="nav toggle">
								<a id="menu_toggle"><i class="fa fa-bars"></i></a>
							</div>
							<ul class="nav navbar-nav navbar-right">
								<li role="presentation" class="dropdown">
									<a href="" class="btn btn-default logout" title="Logout">
										<span class="glyphicon glyphicon-off"></span>
									</a>
								</li>
								<li class="">
									<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										<!--<img src="images/loader-sm.gif" alt="Profile Image" class="profile_img">-->
										<label class="lbllogin"></label>
									</a>
								</li>
							</ul>
						</nav>
					</div>
				</div>
				<!-- /top navigation -->