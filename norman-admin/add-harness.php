<?php include("header.php"); ?>
				<!-- page content -->
				<div class="right_col" role="main">
					<div class="">
						<div class="row x_title">
							<div class="col-md-6">
								<h3>Welcome <font class="lbl-adminid"></font> <small> > <a href="harness-management.php">Harness Management</a> </small> <small> > Add Harness Details </small></h3>
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
										<form name="form-add-harness" id="form-add-harness" data-parsley-validate class="form-horizontal form-label-left">
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtOwnerName">
													Owner Name <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="txtOwnerName" id="txtOwnerName" class="form-control" data-bind="value:addharness.txtOwnerName,validationOptions: { errorElementClass: 'validationMessage' }" required />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtIDNo">
													ID No. <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="txtIDNo" id="txtIDNo" class="form-control" data-bind="value:addharness.txtIDNo,validationOptions: { errorElementClass: 'validationMessage' }" required />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtMake">
													Make <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="txtMake" id="txtMake" class="form-control" data-bind="value:addharness.txtMake,validationOptions: { errorElementClass: 'validationMessage' }" required />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtModel">
													Model <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="txtModel" id="txtModel" class="form-control" data-bind="value:addharness.txtModel,validationOptions: { errorElementClass: 'validationMessage' }" required />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtSerialNo">
													Serial No. <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="txtSerialNo" id="txtSerialNo" class="form-control" data-bind="value:addharness.txtSerialNo,validationOptions: { errorElementClass: 'validationMessage' }" required />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtDOM">
													Date of Manufacture <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="txtDOM" id="txtDOM" class="form-control" data-bind="value:addharness.txtDOM,validationOptions: { errorElementClass: 'validationMessage' }" required />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtDOP">
													Purchase Date <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="txtDOP" id="txtDOP" class="form-control" data-bind="value:addharness.txtDOP,validationOptions: { errorElementClass: 'validationMessage' }" required />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtFrequency">
													Inspection Frequency <span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" name="txtFrequency" id="txtFrequency" class="form-control" data-bind="value:addharness.txtFrequency,validationOptions: { errorElementClass: 'validationMessage' }" required />
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
												<button type="button" name="btnNewUser" id="btnNewUser" class="btn btn-primary" data-bind="click:addharnessinfo">Submit</button>
												<a href="harness-management.php" class="btn btn-danger"><i class="fa fa-backward"></i> Cancel</a>
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
				$.getScript('scripts/viewmodel/add-harness.js',function(){
					ko.applyBindings(addnewharness);
					addnewharness.init();
				});
				</script>
<?php include("footer.php"); ?>