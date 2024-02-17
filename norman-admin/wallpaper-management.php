<?php include("header.php"); ?>
				<!-- page content -->
				<div class="right_col" role="main">
					<div class="">
						<div class="row x_title">
							<div class="col-md-6">
								<h3>Welcome <font class="lbl-adminid"></font> <small> > Slider Images</small></h3>
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
									<div class="add-delete-manage-poll text-right">
										<div class="add-register-user">
											<a href="add-slider.php" class="btn btn-primary restricted-user">
												<i class="fa fa-plus-circle"></i> Add Slider
											</a>
											<button type="button" class="btn btn-warning inactivesliders" data-bind="click:inactivesliders">
												<i class="fa fa-eye-slash"></i> Inactive Sliders
											</button>
											<button type="button" class="btn btn-success activesliders" data-bind="click:activesliders" style="display:none;">
												<i class="fa fa-eye"></i> Active Sliders
											</button>
										</div>
									</div>
									<div class="x_content">
										<div class="table-responsive">
											<table class="table table-striped jambo_table bulk_action" id="registered-user-table">
												<thead>
													<tr class="headings">
														<th class="column-title">Sno</th>
														<th class="column-title text-center">Slider Image</th>
														<th class="column-title">Description</th>
														<th class="column-title">Status</th>
														<th class="column-title">Action</th>
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

				<!-- user info view modal -->
				<div class="modal fade view-slider-image" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
								</button>
								<h4 class="modal-title" id="myModalLabel">View Slider Details</h4>
							</div>
							<div class="modal-body">
								<div class="table-responsive">
									<table id="view-slider-table" class="table table-bordred table-striped view-slider-table">
										<tbody>
											<tr>
												<td>Slider Image</td>
												<td class="lblImage"></td>
											</tr>
											<tr>
												<td>Slider Description</td>
												<td class="lblDesc"></td>
											</tr>
											<tr>
												<td>Status</td>
												<td class="lblStatus"></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
				<!-- /user info view modal -->

				<script>
				$.getScript('scripts/viewmodel/wallpaper-management.js',function(){
					ko.applyBindings(userslist);
					userslist.init();
				});
				</script>
<?php include("footer.php"); ?>