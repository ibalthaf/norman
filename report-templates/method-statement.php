<?php
	require '../includes/db.php';
//initiate db
	$db = getDB();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Norman Scaffolding</title>
		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<!-- themestyle -->
		<?php
			$logo = "";
			if($_GET["worktype"]==1):
				$logo = "images/Norman-Group-logo-4.png";
		?>
		<link href="css/report-templates/scaffolding/method-statement.css" type="text/css" rel="stylesheet">
		<?php
			else:
				$logo = "images/Norman-Group-logo-5.png";
		?>
		<link href="css/report-templates/brickwork/method-statement.css" type="text/css" rel="stylesheet">
		<?php
			endif;
		?>
	</head>
	<body>
		<div class="container">
			<div class="header w-100">
				<table>
					<tr>
						<th class="w-100">
							<div class="logo_div w-100">
								<img class="logo" src="<?php echo $logo; ?>">
							</div>
						</th>
					</tr>
				</table>
			</div>
			<div class="FormTitle">
				<h5 style="color:#000;    font-size: large;"><strong><b>METHOD STATEMENT & RISK ASSESSMENT REGISTER</b></strong></h5>
			</div>
			<div class="handover_form">
				<?php
					$site_visibility = 1;
				//check date
					if(empty($_GET["to"])):
					//check user type
						if($_GET["usertype"]==0):
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.input_4, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS username, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_method_statement` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`site_visibility`=:site_visibility";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $_GET["worktype"]);
								$stmt->bindParam("site_visibility"	, $site_visibility);
							$stmt->execute();
						else:
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.input_4, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS username, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_method_statement` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $_GET["worktype"]);
								$stmt->bindParam("site_visibility"	, $site_visibility);
								$stmt->bindParam("fk_user_id"		, $_GET["userid"]);
							$stmt->execute();
						endif;
					else:
					//check user type
						if($reqData->usertype==0):
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.input_4, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS username, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_method_statement` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type AND DATE(REPORT.`mform_createdon`)>=:startdate AND DATE(REPORT.`mform_createdon`)<=:enddate INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`site_visibility`=:site_visibility";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $_GET["worktype"]);
								$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($_GET["from"])));
								$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($_GET["to"])));
								$stmt->bindParam("site_visibility"	, $site_visibility);
							$stmt->execute();
						else:
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.input_4, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS username, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND REPORT.`work_type`=:work_type AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_method_statement` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND DATE(REPORT.`mform_createdon`)>=:startdate AND DATE(REPORT.`mform_createdon`)<=:enddate INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $_GET["worktype"]);
								$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($_GET["from"])));
								$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($_GET["to"])));
								$stmt->bindParam("site_visibility"	, $site_visibility);
								$stmt->bindParam("fk_user_id"		, $_GET["userid"]);
							$stmt->execute();
						endif;
					endif;
				//fetch data
					$fetchData 	= $stmt->fetchAll(PDO::FETCH_ASSOC);
				?>
				<table class="w-100" cellpadding="5px">
					<thead class="height">
						<tr>
							<th class="w-5 cls_th text-center">No</th>
							<th class="w-20 cls_th text-center">Name</th>
							<th class="w-15 cls_th text-center">Singn</th>
							<th class="w-20 cls_th text-center">Date</th>
							<th class="w-20 cls_th text-center">Site</th>
							<th class="w-20 cls_th text-center">Client</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach($fetchData AS $key=>$rows):
						?>
						<tr>
							<td class="text-center"><?php echo ($key+1) ?></td>
							<td class="text-center"><?php echo $rows["input_1"]; ?></td>
							<td class="text-center"><img src="data:image/png;base64,<?php echo $rows["input_3"]; ?>" width="75px" /></td>
							<td class="text-center"><?php echo $rows["createdon"]; ?></td>
							<td class="text-center"><?php echo $rows["sitename"]; ?></td>
							<td class="text-center"><?php echo $rows["ownername"]; ?></td>
						</tr>
						<?php
							endforeach;
						?>
					</tbody>
				</table>
			</div>
		</div>
	</body>
</html>