<?php include("header.php"); ?>
				<!-- page content -->
				<div class="right_col" role="main">
					<div class="">
						<div class="row x_title">
							<div class="col-md-6 col-sm-6 col-xs-6">
								<h3>Welcome <font class="lbl-adminid"></font> <small> > Harness Management </small></h3>
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
											<a href="add-harness.php" class="btn btn-primary restricted-user">
												<i class="fa fa-plus-circle"></i> Add Harness Details
											</a>
											<button type="button" class="btn btn-warning inactiveharness" data-bind="click:inactiveharness">
												<i class="fa fa-eye-slash"></i> Inactive Harness Details
											</button>
											<button type="button" class="btn btn-success activeharness" data-bind="click:activeharness" style="display:none;">
												<i class="fa fa-eye"></i> Active Harness Details
											</button>
										</div>
									</div>
									<div class="x_content">
										<div class="table-responsive">
											<table class="table table-striped jambo_table bulk_action" id="harness-details-table">
												<thead>
													<tr class="headings">
														<th class="column-title">Sno</th>
														<th class="column-title">Owner Name</th>
														<th class="column-title">Id No.</th>
														<th class="column-title">Make</th>
														<th class="column-title">Model</th>
														<th class="column-title">Serial No.</th>
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

				<!-- harness info view modal -->
				<div class="modal fade view-harness-details" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
								</button>
								<h4 class="modal-title" id="myModalLabel">View Harness Details</h4>
							</div>
							<div class="modal-body">
								<div class="table-responsive">
									<table id="view-harness-info" class="table table-bordred table-striped view-harness-info">
										<tbody>
											<tr>
												<td>Owner Name</td>
												<td class="lblUserNames"></td>
											</tr>
											<tr>
												<td>ID No.</td>
												<td class="lblIdNo"></td>
											</tr>
											<tr>
												<td>Make</td>
												<td class="lblMake"></td>
											</tr>
											<tr>
												<td>Model</td>
												<td class="lblModel"></td>
											</tr>
											<tr>
												<td>Serial No.</td>
												<td class="lblSerialNo"></td>
											</tr>
											<tr>
												<td>Date of Maufacture</td>
												<td class="lblDOM"></td>
											</tr>
											<tr>
												<td>Purchase Date</td>
												<td class="lblPurchaseDate"></td>
											</tr>
											<tr>
												<td>Inspection Frequency</td>
												<td class="lblInsFrequency"></td>
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
				<!-- /harness info view modal -->

				<!-- harness info edit modal -->
				<div class="modal fade edit-harness-details" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
								</button>
								<h4 class="modal-title" id="myModalLabel">Edit Harness Details</h4>
							</div>
							<div class="modal-body">
								<form name="form-edit-user" id="form-edit-user" data-parsley-validate class="form-horizontal form-label-left">
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtOwnerName">
											Owner Name <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtOwnerName" id="txtOwnerName" class="form-control" data-bind="value:editharness.txtOwnerName,validationOptions: { errorElementClass: 'validationMessage' }" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtIDNo">
											ID No. <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtIDNo" id="txtIDNo" class="form-control" data-bind="value:editharness.txtIDNo,validationOptions: { errorElementClass: 'validationMessage' }" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtMake">
											Make  <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtMake" id="txtMake" class="form-control" data-bind="value:editharness.txtMake,validationOptions: { errorElementClass: 'validationMessage' }" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtModel">
											Model <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtModel" id="txtModel" class="form-control" data-bind="value:editharness.txtModel,validationOptions: { errorElementClass: 'validationMessage' }" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtSerialNo">
											Serial No. <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtSerialNo" id="txtSerialNo" class="form-control" data-bind="value:editharness.txtSerialNo,validationOptions: { errorElementClass: 'validationMessage' }" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtDOM">
											Date of Manufacture <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtDOM" id="txtDOM" class="form-control" data-bind="value:editharness.txtDOM,validationOptions: { errorElementClass: 'validationMessage' }" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtDOP">
											Purchase Date <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtDOP" id="txtDOP" class="form-control" data-bind="value:editharness.txtDOP,validationOptions: { errorElementClass: 'validationMessage' }" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtFrequency">
											Inspection Frequency <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtFrequency" id="txtFrequency" class="form-control" data-bind="value:editharness.txtFrequency,validationOptions: { errorElementClass: 'validationMessage' }" required />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtStatus">Status <span class="required">*</span>
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
								<input type="hidden" name="txtHarnessId" id="txtHarnessId" />
								<button type="button" class="btn btn-danger close-modal" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" data-bind="click:editharnessinfo">Save changes</button>
							</div>
						</div>
					</div>
				</div>
				<!-- /harness info edit modal -->

				<script>
				$.getScript('scripts/viewmodel/harness-management.js',function(){
					ko.applyBindings(harnesslist);
					harnesslist.init();
				});
				</script>
<?php include("footer.php"); ?>