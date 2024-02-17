<?php include("header.php"); ?>
				<!-- page content -->
				<div class="right_col" role="main">
					<div class="">
						<div class="row x_title">
							<div class="col-md-6">
								<h3>Welcome <font class="lbl-adminid"></font> <small> > <a href="users-management.php">Users Management</a> </small> <small> > Add User </small></h3>
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
										<div class="text-center">
											<img src="images/profile.jpg" class="user-profile-img addImage" />
										</div>
										<form name="form-add-user" id="form-add-user" data-parsley-validate class="form-horizontal form-label-left">
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtUserType">
													User Type <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<select name="txtUserType" id="txtUserType" class="form-control" data-bind="options: userTypeObservableArray, optionsText: 'title', optionsValue: 'value',  optionsCaption: '-- Select --', value:adduser.txtUserType,validationOptions: { errorElementClass: 'validationMessage' }">
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtWorkType">
													Work Type <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<select name="txtWorkType" id="txtWorkType" class="form-control" data-bind="options: workTypeObservableArray, optionsText: 'title', optionsValue: 'value'">
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtEmpID">
													Employee ID <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="txtEmpID" id="txtEmpID" class="form-control" data-bind="value:adduser.txtEmpID,validationOptions: { errorElementClass: 'validationMessage' }" required />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtFirstName">
													First Name <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="txtFirstName" id="txtFirstName" class="form-control" data-bind="value:adduser.txtFirstName,validationOptions: { errorElementClass: 'validationMessage' }" required />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtLastName">
													Last Name <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="txtLastName" id="txtLastName" class="form-control" data-bind="value:adduser.txtLastName,validationOptions: { errorElementClass: 'validationMessage' }" required />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtEmail">
													Email <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="txtEmail" id="txtEmail" class="form-control" data-bind="value:adduser.txtEmail,validationOptions: { errorElementClass: 'validationMessage' }" required />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtPhone">
													Phone <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="txtPhone" id="txtPhone" class="form-control" data-bind="value:adduser.txtPhone,validationOptions: { errorElementClass: 'validationMessage' }" required />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtProfileImg">Image</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="file" name="txtProfileImg" id="txtProfileImg" class="form-control" onchange="encodeImageFileAsURL();" accept="image/*" />
													<input type="hidden" name="txtProfileData" id="txtProfileData" value="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" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtStatus">Status <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<div class="radio">
														<label>
															<input type="radio" name="txtStatus" class="txtStatus"  value="1" checked> Active
														</label>
														<label>
															<input type="radio" name="txtStatus" class="txtStatus"  value="0"> Inactive
														</label>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div class="register-user-submit-button text-right">
										<div class="add-new-submit-button">
											<div class="container">
												<button type="button" name="btnNewUser" id="btnNewUser" class="btn btn-primary" data-bind="click:adduserinfo">Submit</button>
												<a href="users-management.php" class="btn btn-danger"><i class="fa fa-backward"></i> Cancel</a>
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
				$.getScript('scripts/viewmodel/add-user.js',function(){
					ko.applyBindings(addnewuser);
					addnewuser.init();
				});
				</script>
<?php include("footer.php"); ?>