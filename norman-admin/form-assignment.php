 <?php include("header.php"); ?>
				<!-- page content -->
				<div class="right_col" role="main">
					<div class="">
						<div class="row x_title">
							<div class="col-md-6 col-sm-6 col-xs-6">
								<h3>Welcome <font class="lbl-adminid"></font> <small> > Forms Assignment </small></h3>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6">
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
									<div class="add-delete-manage-poll text-right">
										<div class="add-register-user">
											<a href="bulk-form-assignment.php" class="btn btn-primary">
												<i class="fa fa-plus-circle"></i> Bulk Assignment
											</a>
										</div>
									</div>
									<div class="x_content">
										<div class="table-responsive">
											<table class="table table-striped jambo_table bulk_action" id="assignment-user-table">
												<thead>
													<tr class="headings">
														<th class="column-title">Sno</th>
														<th class="column-title">User Name</th>
														<th class="column-title">Email</th>
														<th class="column-title">User Type</th>
														<th class="column-title">Profile</th>
														<th class="column-title">Form Assignment</th>
													</tr>
												</thead>
												<tbody>
													<!-- dynamic content -->
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /page content -->

				<script>
				$.getScript('scripts/viewmodel/form-assignment.js',function(){
					ko.applyBindings(assignmentlist);
					assignmentlist.init();
				});
				</script>
<?php include("footer.php"); ?>