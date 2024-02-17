<?php include("header.php"); ?>
				<!-- page content -->
				<div class="right_col" role="main">
					<div class="">
						<div class="row x_title">
							<div class="col-md-6">
								<h3>Welcome <font class="lbl-adminid"></font> <small> > <a href="slider-management.php">Slider Images</a> </small> <small> > Add Slider </small></h3>
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
										<div class="col-lg-8 col-md-8">
											<form name="form-add-slider" id="form-add-slider" class="form-horizontal form-label-left" enctype="multipart/form-data" method="post" action="" >
												<div class="form-group">
													<label class="control-label col-md-3 col-sm-3 col-xs-12" for="txtSliderImg">
														Slider Image <span class="required">*</span>
													</label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<input type="file" name="txtSliderImg" id="txtSliderImg" class="form-control" required onchange="encodeImageFileAsURL();" accept="image/*" />
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
												<div class="form-group text-center">
													<input type="hidden" name="txtSliderData" id="txtSliderData" />
													<button type="button" name="btnNewSlider" id="btnNewSlider" class="btn btn-primary" data-bind="click:addsliderinfo">Submit</button>
													<a href="slider-management.php" class="btn btn-danger"><i class="fa fa-backward"></i> Cancel</a>
												</div>
											</form>
										</div>
										<div class="col-lg-4 col-md-4">
											<img class="slider-thumbnail" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /page content -->

				<script>
				$.getScript('scripts/viewmodel/add-slider.js',function(){
					ko.applyBindings(addnewslider);
					addnewslider.init();
				});
				</script>
<?php include("footer.php"); ?>