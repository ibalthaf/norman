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
		<!-- Font Awesome -->
		<link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<!-- NProgress -->
		<link href="vendors/nprogress/nprogress.css" rel="stylesheet">
		<!-- Animate.css -->
		<link href="vendors/animate.css/animate.min.css" rel="stylesheet">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/toaster.css" rel="stylesheet">
		<link href="fonts/font-awesome.css" rel="stylesheet">
		<!-- Custom Theme Style -->
		<link href="css/my-style.css" type="text/css" rel="stylesheet">
		<link href="build/css/custom.min.css" rel="stylesheet">

		<!-- JS Files -->
		<script src="scripts/viewmodel/settings.js" type="text/javascript"></script>
		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/toaster.js" type="text/javascript"></script>
		<script src="js/jquery.min.js"></script>
		<script src="scripts/services/datasource-service.js" type="text/javascript"></script>
		<script src="js/knockout-3.3.0.js" type="text/javascript"></script>
		<script src="js/knockout.validation -2.0.3.js" type="text/javascript"></script>
	</head>
	<body class="login">
		<!-- pre-loader -->
		<div class="ajax-loader">
			<img src="images/loader.gif"/>
		</div>
		<!-- /pre-loader -->
		<div class="log-content">
			<div class="col-sm-6 col-md-4 col-md-offset-4 login-page" style="display:none">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="panel-content">
							<form method="POST" action="" name="loginForm" id="loginForm">
								<fieldset>
									<div class="row">
										<div class="center-block">
											<div class="profile-img">
												<img src="images/Norman-Group-logo-1.png" class="logo" alt="logo-image">
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12 col-md-10  col-md-offset-1 ">
												<div class="form-group">
													<div class="input-group">
														<span class="input-group-addon">
														<i class="glyphicon glyphicon-user"></i>
														</span>
														<input type="text" name="txtUserName" id="txtUserName" class="form-control" placeholder="USER NAME" autofocus data-bind="value:loginPage.txtUserName,validationOptions: { errorElementClass: 'validationMessage' }" />
													</div>
												</div>
												<div class="form-group">
													<div class="input-group">
														<span class="input-group-addon">
														<i class="glyphicon glyphicon-lock"></i>
														</span>
														<input type="password" name="txtPassword" id="txtPassword" class="form-control" placeholder="PASSWORD"  data-bind="value:loginPage.txtPassword,validationOptions: { errorElementClass: 'validationMessage' }" />
													</div>
												</div>
												<div class="sign-in-button">
													<div class="form-group">
														<button type="button" name="btnLogin" class="btn btn-lg btn-warning btn-block" data-bind="click:login">Login</button>
													</div>
												</div>
												<a href="forgot-password.php" name="btnForgotPassword" style="float:right;padding-top: 10px;">Forgot Password?</a>
												<a href="../norman-user/index.php" name="btnForgotPassword" style="position:relative;top:25px;"><i class="fa fa-user"></i> Login as a Customer</a>
											</div>
										</div>
											<div class="row" style="margin-top:5%;">
												<div class="col-sm-12 col-md-10  col-md-offset-1 ">
												<center> <a class="btn" style="padding:0px" href="itms-services://?action=download-manifest&url=https://app.norman-group.com/.plist">
    <img src="../images/app_norman_image.jpg" width="100%" height="100%"></a></center>  
    										</div>
											</div>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
		//on page load
			$.getScript('scripts/viewmodel/index.js',function(){
			    ko.applyBindings(commonlogin);
				commonlogin.init();
			});
		</script>
	</body>
</html>