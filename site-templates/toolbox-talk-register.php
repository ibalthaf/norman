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
		<link href="css/report-templates/toolbox-talk-register.css" type="text/css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<div class="header w-100">
				<table>
					<tr>
						<th class="w-100">
							<div class="logo_div w-100">
								<img class="logo" src="images/Norman-Group-logo-4.jpg">
							</div>
						</th>
					</tr>
				</table>
			</div>
			<div class="FormTitle">
				<h5 style="color:#000;    font-size: large;"><strong><b>TOOLBOX TALK REGISTER</b></strong></h5>
			</div>
			<div class="handover_form">
				<?php
					$site_visibility = 1;
				//check date
					if(empty($_GET["to"])):
					//select data
						$sql	= "SELECT @count:=@count+1 AS sno, REPORT.*, FORMS.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.mtform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM (SELECT @count:=0) AS serialno, `nman_site_master` AS SITES INNER JOIN `nman_tool_boxtalk_master` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` INNER JOIN `nman_tool_boxtalk_form` AS FORMS ON FORMS.`fk_mtform_id`=REPORT.`pk_mtform_id` INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`pk_site_id`=:pk_site_id AND SITES.`site_visibility`=:site_visibility";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("pk_site_id"		, $_GET["siteid"]);
							$stmt->bindParam("site_visibility"	, $site_visibility);
						$stmt->execute();
					else:
					//select data
						$sql	= "SELECT @count:=@count+1 AS sno, REPORT.*, FORMS.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.mtform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM (SELECT @count:=0) AS serialno, `nman_site_master` AS SITES INNER JOIN `nman_tool_boxtalk_master` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND DATE(REPORT.`mtform_createdon`)>=:startdate AND DATE(REPORT.`mtform_createdon`)<=:enddate  INNER JOIN `nman_tool_boxtalk_form` AS FORMS ON FORMS.`fk_mtform_id`=REPORT.`pk_mtform_id` INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`pk_site_id`=:pk_site_id AND SITES.`site_visibility`=:site_visibility";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($_GET["from"])));
							$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($_GET["to"])));
							$stmt->bindParam("pk_site_id"		, $_GET["siteid"]);
							$stmt->bindParam("site_visibility"	, $site_visibility);
						$stmt->execute();
					endif;
				//fetch data
					$fetchData 	= $stmt->fetchAll(PDO::FETCH_ASSOC);
				?>
				<table class="w-100" cellpadding="5px">
					<thead class="height">
						<tr>
							<th class="w-10 cls_th text-center">No</th>
							<th class="w-20 cls_th text-center">Name</th>
							<th class="w-20 cls_th text-center">Sign</th>
							<th class="w-20 cls_th text-center">Topic</th>
							<th class="w-20 cls_th text-center">CITB No.</th>
							<th class="w-20 cls_th text-center">Site</th>
							<th class="w-10 cls_th text-center">Date</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach($fetchData AS $key=>$rows):
						?>
						<tr>
							<td class="text-center"><?php echo ($key+1) ?></td>
							<td class="text-center"><?php echo $rows["ownername"]; ?></td>
							<td class="text-center"><img src="data:image/png;base64,<?php echo $rows["input_2"]; ?>" width="50px" /></td>
							<td class="text-center"><?php echo $rows["topic"]; ?></td>
							<td class="text-center"><?php echo $rows["citp_gt"]; ?></td>
							<td class="text-center"><?php echo $rows["sitename"]; ?></td>
							<td class="text-center"><?php echo $rows["createdon"]; ?></td>
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