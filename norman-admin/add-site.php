<?php include("header.php"); ?>
				<!-- page content -->
				<div class="right_col" role="main">
					<div class="">
						<div class="row x_title">
							<div class="col-md-6">
								<h3>Welcome <font class="lbl-adminid"></font> <small> > <a href="sites-management.php">Sites Management</a> </small> <small> > Add Site </small></h3>
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
										<form name="form-add-user" id="form-add-user" data-parsley-validate class="form-horizontal form-label-left">
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtSiteName">
													Site Name <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="txtSiteName" id="txtSiteName" class="form-control" data-bind="value:addsite.txtSiteName,validationOptions: { errorElementClass: 'validationMessage' }" required />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtOwnerName">
													Client/Developer <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="txtOwnerName" id="txtOwnerName" class="form-control" data-bind="value:addsite.txtOwnerName,validationOptions: { errorElementClass: 'validationMessage' }" required />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtSiteManager">
													Site Manager <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="txtSiteManager" id="txtSiteManager" class="form-control" data-bind="value:addsite.txtSiteManager,validationOptions: { errorElementClass: 'validationMessage' }" required />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtSiteEmail">
													Site Email <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="txtSiteEmail" id="txtSiteEmail" class="form-control" data-bind="value:addsite.txtSiteEmail,validationOptions: { errorElementClass: 'validationMessage' }" required />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtSitePhone">
													Site Phone <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="txtSitePhone" id="txtSitePhone" class="form-control" data-bind="value:addsite.txtSitePhone,validationOptions: { errorElementClass: 'validationMessage' }" required />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtPlotNo">
													Site No. <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="txtPlotNo" id="txtPlotNo" class="form-control" data-bind="value:addsite.txtPlotNo,validationOptions: { errorElementClass: 'validationMessage' }" required />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtSiteAddress">
													Address <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<textarea name="txtSiteAddress" id="txtSiteAddress" class="form-control" data-bind="value:addsite.txtSiteAddress,validationOptions: { errorElementClass: 'validationMessage' }" required></textarea>
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
												<button type="button" name="btnNewSite" id="btnNewSite" class="btn btn-primary" data-bind="click:addsiteinfo">Submit</button>
												<a href="sites-management.php" class="btn btn-danger"><i class="fa fa-backward"></i> Cancel</a>
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
				$.getScript('scripts/viewmodel/add-site.js',function(){
					ko.applyBindings(addnewsite);
					addnewsite.init();
				});
				</script>
<?php include("footer.php"); ?>