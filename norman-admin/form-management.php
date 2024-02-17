<?php include("header.php"); ?>
				<!-- page content -->
				<div class="right_col" role="main">
					<div class="">
						<div class="row x_title">
							<div class="col-md-6 col-sm-6 col-xs-6">
								<h3>Welcome <font class="lbl-adminid"></font> <small> > Manage Forms </small></h3>
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
											<button type="button" class="btn btn-warning inactiveforms" data-bind="click:inactiveforms">
												<i class="fa fa-eye-slash"></i> Inactive Forms
											</button>
											<button type="button" class="btn btn-success activeforms" data-bind="click:activeforms" style="display:none;">
												<i class="fa fa-eye"></i> Active Forms
											</button>
										</div>
									</div>
									<div class="x_content">
										<div class="table-responsive">
											<table class="table table-striped jambo_table bulk_action" id="form-list-table">
												<thead>
													<tr class="headings">
														<th class="column-title">Sno</th>
														<th class="column-title">Form Name</th>
														<th class="column-title">Work Type</th>
														<th class="column-title">Status</th>
														<th class="column-title">Report</th>
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

				<!-- form info view modal -->
				<div class="modal fade view-form-info" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">

							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
								</button>
								<h4 class="modal-title" id="myModalLabel">View Form Details</h4>
							</div>
							<div class="modal-body">
								<div class="table-responsive">
									<table id="view-regietered-user" class="table table-bordred table-striped view-regietered-user">
										<tbody>
											<tr>
												<td>Form Name</td>
												<td class="lblFormName"></td>
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
				<!-- /form info view modal -->

				
				<!-- form info edit modal -->
				<div class="modal fade edit-form-info" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">

							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
								</button>
								<h4 class="modal-title" id="myModalLabel">Edit Form Details</h4>
							</div>
							<div class="modal-body">
								<form name="form-edit-user" id="form-edit-user" data-parsley-validate class="form-horizontal form-label-left">
									<div class="form-group">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtFormName">
											Form Name  <span class="required">*</span>
										</label>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<input type="text" name="txtFormName" id="txtFormName" class="form-control" data-bind="value:editform.txtFormName,validationOptions: { errorElementClass: 'validationMessage' }" required />
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
								<input type="hidden" name="txtFormId" id="txtFormId" />
								<button type="button" class="btn btn-danger close-modal" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" data-bind="click:updateforminfo">Save changes</button>
							</div>

						</div>
					</div>
				</div>
				<!-- /form info edit modal -->
				
				<script>
				$.getScript('scripts/viewmodel/form-management.js',function(){
					ko.applyBindings(formslist);
					formslist.init();
				});
				</script>
<?php include("footer.php"); ?>