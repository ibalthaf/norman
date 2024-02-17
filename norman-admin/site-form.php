<?php include("header.php"); ?>
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="row x_title">
                <div class="col-md-6">
                    <h3>Welcome <font class="lbl-adminid"></font> <small> > <a href="sites-management.php">Sites Management</a> </small> <small> > Assign Form</small></h3>
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

                            <div class="col-md-4 col-lg-4 col-sm-5">
                                <div class="table-responsive">
                                    <table class="table table-striped jambo_table bulk_action" style="margin-top: 7px !important;">
                                        <thead>
                                        <tr class="headings">
                                            <th class="column-title"></th>
                                            <th class="column-title">Site Details</th>
                                        </tr>
                                        </thead>
                                        <tr>
                                            <td>Site Name</td>
                                            <td class="lblSiteName"></td>
                                        </tr>
                                        <tr>
                                            <td>Site Email</td>
                                            <td class="lblEmail"></td>
                                        </tr>
                                        <tr>
                                            <td>Site Owner</td>
                                            <td class="lblSiteOwner"></td>
                                        </tr>
                                        <tr>
                                            <td>Site Manager</td>
                                            <td class="lblSiteManager"></td>
                                        </tr>
                                        <tr>
                                            <td>Site Phone No</td>
                                            <td class="lblSitePhone"></td>
                                        </tr>
                                        <tr>
                                            <td>Site Address</td>
                                            <td class="lblSiteAddress"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-8 col-sm-7">
                                <div class="table-responsive">

                                    <table class="table table-striped jambo_table bulk_action" id="menu-list-table">
                                        <thead>
                                        <tr class="headings">
                                            <th class="column-title"></th>
                                            <th class="column-title">Work Type</th>
                                            <th class="column-title">Form Name</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <!-- dynamic content -->
                                        </tbody>
                                    </table>
                                </div>

                                <div class="register-user-submit-button text-right">
                                    <div class="add-new-submit-button">
                                        <div class="container">
                                            <button type="button" name="btnNewUser" id="btnNewUser" class="btn btn-primary" data-bind="click:addformassign">Submit</button>
                                            <a href="sites-management.php" class="btn btn-danger"><i class="fa fa-backward"></i> Cancel</a>
                                        </div>
                                    </div>
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
        $.getScript('scripts/viewmodel/site-form.js',function(){
            ko.applyBindings(sitemenu);
            sitemenu.init();
        });
    </script>
<?php include("footer.php"); ?>