<?php include("header.php"); ?>
				<!-- page content -->
				<div class="right_col" role="main">
					<div class="">
						<div class="row x_title">
							<div class="col-md-6 col-sm-6 col-xs-6">
								<h3>Welcome <font class="lbl-adminid"></font> <small> > Sites Management </small></h3>
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
											<a href="add-site.php" class="btn btn-primary restricted-user">
												<i class="fa fa-plus-circle"></i> Add New Site
											</a>
											<button type="button" class="btn btn-warning inactivesites" data-bind="click:inactivesites">
												<i class="fa fa-eye-slash"></i> Inactive Sites
											</button>
											<button type="button" class="btn btn-success activesites" data-bind="click:activesites" style="display:none;">
												<i class="fa fa-eye"></i> Active Sites
											</button>
										</div>
									</div>
									<div class="x_content">
										<div class="table-responsive">
											<table class="table table-striped jambo_table bulk_action" id="registered-user-table">
												<thead>
													<tr class="headings">
														<th class="column-title">Sno</th>
														<th class="column-title">Site Name</th>
														<th class="column-title">Site Email</th>
														<th class="column-title">Assign</th>
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

				<!-- site info view modal -->
				<div class="modal fade view-site-info" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">

							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
								</button>
								<h4 class="modal-title" id="myModalLabel">View Site Details</h4>
							</div>
							<div class="modal-body">
								<div class="table-responsive">
									<!-- site information -->
									<table id="view-regietered-user" class="table table-bordred table-striped view-regietered-user">
										<tbody>
											<tr>
												<td>Site Name</td>
												<td class="lblSiteName"></td>
											</tr>
											<tr>
												<td>Client/Developer</td>
												<td class="lblOwnerName"></td>
											</tr>
											<tr>
												<td>Site Email</td>
												<td class="lblEmail"></td>
											</tr>
											<tr>
												<td>Site Phone</td>
												<td class="lblPhone"></td>
											</tr>
											<tr>
												<td>Site Address</td>
												<td class="lblAddress"></td>
											</tr>
											<tr>
												<td>Site No.</td>
												<td class="lblPlotNo"></td>
											</tr>
											<tr>
												<td>Site Manager</td>
												<td class="lblManger"></td>
											</tr>
											<tr>
												<td>Site Job Number</td>
												<td class="lblJobNo"></td>
											</tr>
											<tr>
												<td>Site Created By</td>
												<td class="lblCreatedBy"></td>
											</tr>
											<tr>
												<td>Status</td>
												<td class="lblStatus"></td>
											</tr>
										</tbody>
									</table>
									<!-- /site information -->
									<h4>Site Assignment Information</h4>
									<!-- site assignment information -->
									<table id="table-user-assignment" class="table table-bordred table-striped table-user-assignment">
										<thead>
											<tr>
												<th class='text-center'>Sno</th>
												<th>Assingned To</th>
												<th>Work Type</th>
												<th class='text-center'>Profile Image</th>
											</tr>
										</thead>
										<tbody>
											<!-- dynamic content -->
										</tbody>
									</table>
									<!-- /site assignment information -->
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
				<!-- /site info view modal -->

				
				<!-- site info edit modal -->
				<div class="modal fade edit-site-info" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">

							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
								</button>
								<h4 class="modal-title" id="myModalLabel">Edit User Details</h4>
							</div>
							<div class="modal-body">
								<form name="form-edit-user" id="form-edit-user" data-parsley-validate class="form-horizontal form-label-left">
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtSiteName">
											Site Name  <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtSiteName" id="txtSiteName" class="form-control" data-bind="value:editsiteinfo.txtSiteName,validationOptions: { errorElementClass: 'validationMessage' }" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtOwnerName">
											Client/Developer<span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtOwnerName" id="txtOwnerName" class="form-control" data-bind="value:editsiteinfo.txtOwnerName,validationOptions: { errorElementClass: 'validationMessage' }" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtSiteEmail">
											Site Email <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtSiteEmail" id="txtSiteEmail" class="form-control" data-bind="value:editsiteinfo.txtSiteEmail,validationOptions: { errorElementClass: 'validationMessage' }" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtSitePhone">
											Site Phone <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtSitePhone" id="txtSitePhone" class="form-control" data-bind="value:editsiteinfo.txtSitePhone,validationOptions: { errorElementClass: 'validationMessage' }" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtSiteAddress">
											Address <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtSiteAddress" id="txtSiteAddress" class="form-control" data-bind="value:editsiteinfo.txtSiteAddress,validationOptions: { errorElementClass: 'validationMessage' }" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtPlotNo">
											Site No. <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtPlotNo" id="txtPlotNo" class="form-control" data-bind="value:editsiteinfo.txtPlotNo,validationOptions: { errorElementClass: 'validationMessage' }" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtSiteManager">
											Site Manager <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtSiteManager" id="txtSiteManager" class="form-control" data-bind="value:editsiteinfo.txtSiteManager,validationOptions: { errorElementClass: 'validationMessage' }" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtLinkPosition">Status <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<div class="radio">
												<label>
													<input type="radio" name="txtStatus" class="txtStatus"  value="1"> Active
												</label>
												<label>
													<input type="radio" name="txtStatus" class="txtStatus"  value="0"> Inactive
												</label>
											</div>
										</div>
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<input type="hidden" name="txtView" id="txtView" />
								<input type="hidden" name="txtSiteId" id="txtSiteId" />
								<button type="button" class="btn btn-danger close-modal" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" data-bind="click:updatesiteinfo">Save changes</button>
							</div>

						</div>
					</div>
				</div>
				<!-- /site info edit modal -->

				<!-- site assignment modal supervisor -->
				<div class="modal fade manager-site-assignment" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
								</button>
								<h4 class="modal-title" id="myModalLabel">Site Assignment</h4>
							</div>
							<div class="modal-body">
								<form name="form-site-assignment" id="form-site-assignment" data-parsley-validate class="form-horizontal form-label-left">
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtMSiteName">
											Site Name  <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtMSiteName" id="txtMSiteName" class="form-control" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtMUserName">
											User Name <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<select name="txtMUserName" id="txtMUserName" class="form-control" required>
												<!-- dynamic data -->
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtMEmail">Email</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtMEmail" id="txtMEmail" class="form-control" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtMPhone">Phone</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtMPhone" id="txtMPhone" class="form-control" readonly />
										</div>
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<input type="hidden" name="txtMAssignmentId" id="txtMAssignmentId" />
								<input type="hidden" name="txtMSiteId" id="txtMSiteId" />
								<button type="button" class="btn btn-danger close-modal" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" data-bind="click:assignmanager">Assign Manager</button>
							</div>
						</div>
					</div>
				</div>
				<!-- /site assignment modal supervisor -->

				<!-- site assignment modal supervisor -->
				<div class="modal fade supervisor-site-assignment" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
								</button>
								<h4 class="modal-title" id="myModalLabel">Site Assignment</h4>
							</div>
							<div class="modal-body">
								<form name="form-site-assignment" id="form-site-assignment" data-parsley-validate class="form-horizontal form-label-left">
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtSSiteName">
											Site Name  <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtSSiteName" id="txtSSiteName" class="form-control" readonly />
										</div>
									</div>
									<div class="form-group restricted-admin">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtSWorkType">
											Work Type  <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<select type="text" name="txtSWorkType" id="txtSWorkType" class="form-control">
												<option value="">-- SELECT --</option>
												<option value="1">SCAFFOLDING</option>
												<option value="2">BRIKWORK</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtSUserName">
											User Name <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<select name="txtSUserName" id="txtSUserName" class="form-control" required>
												<!-- dynamic data -->
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtSEmail">Email</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtSEmail" id="txtSEmail" class="form-control" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtSPhone">Phone</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtSPhone" id="txtSPhone" class="form-control" readonly />
										</div>
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<input type="hidden" name="txtSSiteId" id="txtSSiteId" />
								<input type="hidden" name="txtSAssignmentId" id="txtSAssignmentId" />
								<button type="button" class="btn btn-danger close-modal" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" data-bind="click:assignsupervisor">Assign Supervisor</button>
							</div>
						</div>
					</div>
				</div>
				<!-- /site assignment modal supervisor -->

				<script>
				$.getScript('scripts/viewmodel/sites-management.js',function(){
					ko.applyBindings(siteslist);
					siteslist.init();
				});
				</script>
<?php include("footer.php"); ?>