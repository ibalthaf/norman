				<!-- page content -->
				<div class="right_col" role="main">
					<div class="">
						<div class="row x_title">
							<div class="col-md-6">
								<h3>Welcome <font class="lbl-adminid"></font> <small> > Manage Forms </small> <small> > HANDOVER CERTIFICATE </small></h3>
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
									<div class="add-delete-manage-poll text-center">
										<div class="add-register-user">
											<input type="text" name="txtDateFrom" id="txtDateFrom" placeholder="From Date" />
											<input type="text" name="txtDateTo" id="txtDateTo" placeholder="To Date" />
											<button type="button" class="btn btn-success downloadreport" data-bind="click:downloadreport">
												<i class="fa fa-download"></i> Download
											</button>
										</div>
									</div>
									<div class="x_content">
										<div class="table-responsive">
											<table class="table table-striped jambo_table bulk_action" id="hand-over-table">
												<thead>
													<tr class="headings">
														<th class="column-title text-center">No</th>
														<th class="column-title text-center">Date</th>
														<th class="column-title text-center">Site</th>
														<th class="column-title text-center">Client</th>
														<th class="column-title text-center">Plot</th>
														<th class="column-title text-center">Description of scaffold being handed over</th>
														<th class="column-title text-center">Download</th>
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
				$.getScript('scripts/viewmodel/handover-report.js',function(){
					ko.applyBindings(formslist);
					formslist.init();
				});
				</script>