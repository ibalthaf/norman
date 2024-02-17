<!-- footer content -->
				<footer>
					<div class="pull-right">
						Copyright &copy; <?php echo date("Y"); ?> Norman Group. All rights reserved
					</div>
					<div class="clearfix"></div>
				</footer>
				<!-- /footer content -->
			</div>
		</div>
		<!-- jQuery -->
		<script src="vendors/jquery/dist/jquery.min.js"></script>
		<!-- Bootstrap -->
		<script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
		<!-- FastClick -->
		<script src="vendors/fastclick/lib/fastclick.js"></script>
		<!-- NProgress -->
		<script src="vendors/nprogress/nprogress.js"></script>
		<!-- bootstrap-daterangepicker -->
		<script src="vendors/moment/min/moment.min.js"></script>
		<script src="vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
		<!-- jQuery Smart Wizard -->
		<script src="vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>
		<!-- gauge.js -->
		<script src="vendors/gauge.js/dist/gauge.min.js"></script>
		<!-- bootstrap-progressbar -->
		<script src="vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
		<!-- iCheck -->
		<script src="vendors/iCheck/icheck.min.js"></script>
		<!-- Skycons -->
		<script src="vendors/skycons/skycons.js"></script>
		<!-- Flot -->
		<script src="vendors/Flot/jquery.flot.js"></script>
		<script src="vendors/Flot/jquery.flot.pie.js"></script>
		<script src="vendors/Flot/jquery.flot.time.js"></script>
		<script src="vendors/Flot/jquery.flot.stack.js"></script>
		<script src="vendors/Flot/jquery.flot.resize.js"></script>
		<!-- Flot plugins -->
		<script src="vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
		<script src="vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
		<script src="vendors/flot.curvedlines/curvedLines.js"></script>
		<!-- DateJS -->
		<script src="vendors/DateJS/build/date.js"></script>
		<!-- JQVMap -->
		<script src="vendors/jqvmap/dist/jquery.vmap.js"></script>
		<script src="vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
		<script src="vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
		
		<!-- Datatables -->
		<script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
		<script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
		<script src="vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
		<script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
		<script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
		<script src="vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
		<script src="vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
		<script src="vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
		<script src="vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>

		<!-- Custom Theme Scripts -->
		<script src="build/js/custom.js"></script>
		<script src="scripts/viewmodel/common.js" type="text/javascript"></script>

		<script type="text/javascript">
			$('#menu_toggle').click(function(){
			    if($(".norman-scaffolding").attr('src') === 'images/norman-logo.png')
			    {
			        $(".norman-scaffolding").attr('src','images/norman-symbol.png' );
			        $('.norman-scaffolding').addClass('plain_logo');
			    }
			    else{
			        $('.norman-scaffolding').removeClass('plain_logo');
			        $(".norman-scaffolding").attr('src','images/norman-logo.png' );
			
			    }
			});
		</script>
		<script>
	  $(document).ready(function() {
          $.extend(jQuery.fn.dataTableExt.oSort, {
    "date-br-pre": function ( a ) {
        if (a == null || a == "") {
            return 0;
        }	
        var brDatea = a.split('/');
        return (brDatea[2] + brDatea[1] + brDatea[0]) * 1;
    },
	  "date-br-asc": function ( a, b ) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },
	  "date-br-desc": function ( a, b ) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
}); 
        });
			$(document).on("click", ".logout", function(){
			//clear session values
				localStorage.clear();
			//redirect page
				location.href = "index.php?session=1";
			});
		</script>
	</body>
</html>