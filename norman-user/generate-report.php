<?php include("header.php"); ?>
				<?php
					if($_GET["formid"] == 1 || $_GET["formid"] == 13):
						include("site-supervisor.php");
					elseif($_GET["formid"] == 3 || $_GET["formid"] == 14):
						include("method-statement-report.php");
					elseif($_GET["formid"] == 4 || $_GET["formid"] == 16):
						include("toolbox-talk-report.php");
					elseif($_GET["formid"] == 5):
						include("safety-harness-inspection.php");
					elseif($_GET["formid"] == 6):
						include("inspection-report.php");
					elseif($_GET["formid"] == 8):
						include("handover-report.php");
					elseif($_GET["formid"] == 9 || $_GET["formid"] == 15):
						include("new-starter-form.php");
					elseif($_GET["formid"] == 10 || $_GET["formid"] == 12):
						include("health-medical-self-certification.php");
					elseif($_GET["formid"] == 11 || $_GET["formid"] == 17):
						include("work_equipment_inspection.php");
					elseif($_GET["formid"] == 18):
							include("datawork-sheet-report.php");
					elseif($_GET["formid"] == 19):
							include("RFI-report.php");
					endif;
				?>
<?php include("footer.php"); ?>