<?php include("header.php"); ?>
				<!-- page content -->
				<div class="right_col" role="main">
					<div class="">
						<div class="row x_title">
							<div class="col-md-6 col-sm-6 col-xs-6">
								<h3>Welcome <font class="lbl-adminid"></font> <small> > Users Management </small></h3>
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
											<a href="add-user.php" class="btn btn-primary restricted-user">
												<i class="fa fa-plus-circle"></i> Add User
											</a>
											<button type="button" class="btn btn-warning inactiveusers" data-bind="click:inactiveusers">
												<i class="fa fa-eye-slash"></i> Inactive Users
											</button>
											<button type="button" class="btn btn-success activeusers" data-bind="click:activeusers" style="display:none;">
												<i class="fa fa-eye"></i> Active Users
											</button>
										</div>
									</div>
									<div class="x_content">
										<div class="table-responsive">
											<table class="table table-striped jambo_table bulk_action" id="registered-user-table">
												<thead>
													<tr class="headings">
														<th class="column-title">Sno</th>
														<th class="column-title">User Name</th>
														<th class="column-title">Email</th>
														<th class="column-title">User Type</th>
														<th class="column-title">Profile</th>
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
				<div class="modal fade view-registered-user" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
								</button>
								<h4 class="modal-title" id="myModalLabel">View User Details</h4>
							</div>
							<div class="modal-body">
								<div class="text-center">
									<img src="images/loader-sm.gif" class="user-profile-img lblImage"/>
								</div>
								<div class="text-center">
									<label class="lblUserName"></label>
								</div>
								<div class="table-responsive">
									<table id="view-regietered-user" class="table table-bordred table-striped view-regietered-user">
										<tbody>
											<tr>
												<td>User Type</td>
												<td class="lblUserType"></td>
											</tr>
											<tr>
												<td>Employee Id</td>
												<td class="lblEmpId"></td>
											</tr>
											<tr>
												<td>User Email</td>
												<td class="lblEmail"></td>
											</tr>
											<tr>
												<td>User Phone</td>
												<td class="lblPhone"></td>
											</tr>
											<tr>
												<td>Signature</td>
												<td class="lblSignature"></td>
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
								<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
				<!-- /user info view modal -->

				<!-- user info edit modal -->
				<div class="modal fade edit-registered-user" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
								</button>
								<h4 class="modal-title" id="myModalLabel">Edit User Details</h4>
							</div>
							<div class="modal-body">
								<div class="text-center">
									<img src="images/loader-sm.gif" class="user-profile-img editImage"/>
								</div>
								<form name="form-edit-user" id="form-edit-user" data-parsley-validate class="form-horizontal form-label-left">
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtUserType">
											User Type <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<select name="txtUserType" id="txtUserType" class="form-control" required >
												<option value="" hidden>-- SELECT --</option>
												<option value="0">Administrator</option>
												<option value="1">Manager</option>
												<option value="2">Supervisor</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtWorkType">
											Work Type <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<select name="txtWorkType" id="txtWorkType" class="form-control" required >
												<option value="" hidden>-- SELECT --</option>
												<option value="1">Scaffolding</option>
												<option value="2">Brickwork</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtEmpID">
											Employee ID <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtEmpID" id="txtEmpID" class="form-control" data-bind="value:edituser.txtEmpID,validationOptions: { errorElementClass: 'validationMessage' }" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtFirstName">
											First Name  <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtFirstName" id="txtFirstName" class="form-control" data-bind="value:edituser.txtFirstName,validationOptions: { errorElementClass: 'validationMessage' }" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtLastName">
											Last Name<span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtLastName" id="txtLastName" class="form-control" data-bind="value:edituser.txtLastName,validationOptions: { errorElementClass: 'validationMessage' }" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtEmail">
											Email <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtEmail" id="txtEmail" class="form-control" data-bind="value:edituser.txtEmail,validationOptions: { errorElementClass: 'validationMessage' }" required />

										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtPhone">
											Phone <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtPhone" id="txtPhone" class="form-control" data-bind="value:edituser.txtPhone,validationOptions: { errorElementClass: 'validationMessage' }" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtPhone">Image</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="file" name="txtProfileImg" id="txtProfileImg" class="form-control" onchange="encodeImageFileAsURL();" accept="image/*" />
											<input type="hidden" name="txtProfileData" id="txtProfileData" />
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
								<input type="hidden" name="hiddenEmail" id="hiddenEmail" class="form-control"  data-bind="value:edituser.hiddenEmail" >
								
								<input type="hidden" name="txtView" id="txtView" />
								<input type="hidden" name="txtUserId" id="txtUserId" />
								<button type="button" class="btn btn-danger close-modal" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" data-bind="click:edituserinfo">Save changes</button>
							</div>
						</div>
					</div>
				</div>
				<!-- /user info edit modal -->

				<script>
				$.getScript('scripts/viewmodel/users-management.js',function(){
					ko.applyBindings(userslist);
					userslist.init();
				});
				</script>
<?php include("footer.php"); ?>