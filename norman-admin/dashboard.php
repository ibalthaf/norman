<?php include("header.php"); ?>
				<!-- page content -->
				<div class="right_col" role="main">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="dashboard_graph">
								<div class="row x_title">
									<div class="col-md-6 col-sm-6 col-xs-6">
										<h3>Welcome <font class="lbl-adminid"></font> <small> > Dashboard </small></h3>
									</div>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<div class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
											<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
											<span class="last-login"> - </span>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="dashboard-content">
								<div class="report-overview">
									<h3>Report Overview Of Last 24 Hours</h3>
								</div>
								<div class="register-event">
									<div class="row">
										<div class="col-md-6 col-sm-6 col-xs-12">
											<div class="registration-reports">
												<div class="registration-report-title">
													<h4>New Users Registered</h4>
												</div>
												<div class="users-added">
													<table id="new-user-table" class="table table-bordered table-striped table-hover">
														<tbody>
															<!-- dynamic content-->
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<div class="event-reports">
												<div class="event-report-title">
													<h4>New Sites</h4>
												</div>
												<div class="event-EQ">
													<table id="new-site-table" class="table table-bordered">
														<tbody>
															<!-- dynamic content-->
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<br />
				</div>
				<!-- /page content -->

				<script>
				$.getScript('scripts/viewmodel/dashboard.js',function(){
					ko.applyBindings(dashbaord);
					dashbaord.init();
				});
				</script>
<?php include("footer.php"); ?>