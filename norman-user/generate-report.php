<?php include("header.php"); ?>
				<?php
					if($_GET["formid"] == 1):
						include("site-supervisor.php");
					elseif($_GET["formid"] == 3):
						include("method-statement-report.php");
					elseif($_GET["formid"] == 4):
						include("toolbox-talk-report.php");
					elseif($_GET["formid"] == 5):
						include("safety-harness-inspection.php");
					elseif($_GET["formid"] == 6):
						include("inspection-report.php");
					elseif($_GET["formid"] == 8):
						include("handover-report.php");
					elseif($_GET["formid"] == 9):
						include("new-starter-form.php");
					elseif($_GET["formid"] == 10):
						include("health-medical-self-certification.php");
					elseif($_GET["formid"] == 11):
						include("work_equipment_inspection.php");
					endif;
				?>
<?php include("footer.php"); ?>