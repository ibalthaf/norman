<?php include("header.php"); ?>
				<!-- page content -->
				<div class="right_col" role="main">
					<div class="">
						<div class="row x_title">
							<div class="col-md-6">
								<h3>Welcome <font class="lbl-adminid"></font> <small> > <a href="form-assignment.php">Forms Assignment</a> </small> <small> > Assign Forms</small></h3>
							</div>
							<div class="col-md-6">
								<div class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
									<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
									<span class="last-login"> - </span>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_content">
										
										<div class="table-responsive">
											<div class="col-md-6 col-sm-6 col-xs-6">
												<table class="table table-striped jambo_table bulk_action" id="menu-user-table">
													<thead>
														<tr class="headings">
															<th class="column-title"></th>
															<th class="column-title">User Name</th>
															<th class="column-title">User Type</th>
														</tr>
													</thead>
													<tbody>
														<!-- dynamic content -->
													</tbody>
												</table>
											</div>
											<div class="col-md-6 col-sm-6 col-xs-6">
												<table class="table table-striped jambo_table bulk_action" id="form-list-table">
													<thead>
														<tr class="headings">
															<th class="column-title"></th>
															<th class="column-title">Form Name</th>
														</tr>
													</thead>
													<tbody>
														<!-- dynamic content -->
													</tbody>
												</table>
											</div>
										</div>
										
										<div class="register-user-submit-button text-right">
											<div class="add-new-submit-button">
												<div class="container">
													<button type="button" name="btnFormAssign" id="btnFormAssign" class="btn btn-primary" data-bind="click:addformassign">Submit</button>
													<a href="form-assignment.php" class="btn btn-danger"><i class="fa fa-backward"></i> Cancel</a>
												</div>
											</div>
										</div>
										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /page content -->

				<script>
				$.getScript('scripts/viewmodel/bulk-form-assignment.js',function(){
					ko.applyBindings(bulkassignforms);
					bulkassignforms.init();
				});
				</script>
<?php include("footer.php"); ?>