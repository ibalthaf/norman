<?php
	require '../includes/db.php';
	$db = getDB();
	$selectquery 	= "SELECT * FROM `nman_site_master` AS SITES INNER JOIN `nman_site_assignment` AS ASSIGN INNER JOIN nman_user_master AS USERS ON USERS.pk_user_id=:pk_user_id AND ASSIGN.fk_site_id=SITES.pk_site_id WHERE SITES.pk_site_id=:pk_site_id";
	$sltstmt= $db->prepare($selectquery);
		$sltstmt->bindParam("pk_site_id", $_GET["siteid"]);
		$sltstmt->bindParam("pk_user_id", $_GET["userid"]);
	$sltstmt->execute();
//fetch data
	$siteData = $sltstmt->fetch(PDO::FETCH_ASSOC);
	$datetime	= date("d/m/Y h:i A");
	$dateArray  = explode(" ", $datetime);
	$date = $dateArray[0];
	$time = $dateArray[1]." ".$dateArray[2];
	$mstdata = "SELECT * FROM `nman_request_information_form` WHERE `fk_site_id`=:fk_site_id AND `fk_user_id`=:fk_user_id AND `work_type`=:work_type AND `rform_visibility`=1 ORDER BY pk_rform_id DESC";
//execute query
	$sltmst	= $db->prepare($mstdata);
		$sltmst->bindParam("fk_site_id", $_GET["siteid"]);
		$sltmst->bindParam("fk_user_id", $_GET["userid"]);
		$sltmst->bindParam("work_type", $_GET["worktype"]);
	$sltmst->execute();
//fetch data
	$fetchmst 	= $sltmst->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Norman Scaffolding</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/brickwork/request-for-information/style.css" type="text/css" rel="stylesheet">
	</head>
	<body>
		<div id="footer">
			<p class="page text-right">REQUEST FOR INFORMATION (RFI) FORM</p>
		</div>

		<table class="table table-bordered1 w-100">
			<tbody>
				<tr>
					<div class="icon-gatwick-image"><img src="images/Norman-Group-logo-5.png" alt="" width ="300px"></div>
				</tr>
			</tbody>
		</table>

		<div class="managerTitle">
			<h5 style="color:#fff;"><strong>REQUEST FOR INFORMATION (RFI) FORM</strong></h5>
		</div>
		<table style="width:100%" class="arialTable">
			<tbody>
				<tr>
					<td class="w-10">Devlopment</td>
					<td class="w-10">
						<div class="input2"><?php echo $siteData["site_name"]; ?></div>
					</td>
					<td class="w-15" style="text-align:left; padding-left: 80px !important;">Job RFI Number</td>
					<td class="w-10">
						<div class="input2"><?php echo $fetchmst["job_rfi_no"]; ?></div>
					</td>
				</tr>
			</tbody>
		</table>
		<table style="width:100%" class="arialTable">
			<tbody>
				<tr>
					<td class="w-10">Client</td>
					<td class="w-10">
						<div class="input2"><?php echo $siteData["site_owner_name"]; ?></div>
					</td>
					<td class="w-15" style="text-align:left; padding-left: 80px !important;">NBL RFI Number</td>
					<td class="w-10">
						<div class="input2"><?php echo $fetchmst["nbl_rfi_no"]; ?></div>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="managerTitle">
			<h5 style="color:#fff;"><strong>DETAILS</strong></h5>
		</div>
		<div style="background-color:#d9d9d9; padding:1%; border-radius:40px; margin-top: 4px;">
			<table  style="width:100%" class="arialTable">
				<tbody>
					<tr>
						<td class="w-30">Plot</td>
						<td class="w-20">
							<div class="customtd" style="width: 35%;"><?php echo $fetchmst["input_1"]; ?></div>	
						</td>
					</tr>
					<tr>
						<td class="w-30">Relavent Drawing Numbers</td>
						<td class="w-20">
							<div class="customtd" style="width: 35%;"><?php echo $fetchmst["input_2"]; ?></div>	
						</td>
					</tr>
					<tr>
						<td class="w-20">Description of work</td>
						<td class="w-80"></td>
					</tr>
				</tbody>
			</table>
			<table>
				<tbody>
					<tr>
						<td class="w-100">
							<div class="customtd" style="height:250px;weight:600px;"><?php echo $fetchmst["input_3"]; ?></div>	
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="managerTitle">
			<h5 style="color:#fff;"><strong>SUBMITTED BY</strong></h5>
		</div>
		<div style="background-color:#d9d9d9">
			<table  style="width:100%" class="arialTable">
				<tbody>
					<td class=" text-center w-5">Name</td>
					<td class="w-20">
						<div class="customtd"><?php echo $siteData["user_first_name"].' '.$siteData["user_last_name"]; ?></div>	
					</td>
					<td class=" text-center w-5">Signature</td>
					<td class="w-20">
						<div class="customtd text-center" style="background:#fff;">
							<img src="<?php echo "data:image/png;base64,".$fetchmst["signature"]; ?>" width="40px" height="25px" />
							</div>	
					</td>
					<td class=" text-center w-5">Date</td>
					<td class="w-20">
						<div class="customtd"><?php echo $date; ?></div>	
					</td>
				</tbody>
			</table>
		</div>
		<!-- image -->
		<?php
		//select form fields
			$sltimg 	= "SELECT * FROM `nman_request_information_images` WHERE `fk_rform_id`=:fk_rform_id  AND `image_visibility`=1";
			$sltimages	= $db->prepare($sltimg);
				$sltimages->bindParam("fk_rform_id"	, $fetchmst["pk_rform_id"]);

			$sltimages->execute();
		//fetch data
			$imageData = $sltimages->fetchAll(PDO::FETCH_ASSOC);
			if($sltimages->rowCount() > 0):
				echo "<table><tr>";
				foreach($imageData as $key=>$img):
					echo "<td class='text-center'><img src='".$img["rsf_image"]."' /></td>";
					echo (((($key+1)%2)==0)? "</tr><tr>" : "");
				endforeach;
				echo "</tr></table>";
			endif;
		?>
	</body>
</html>