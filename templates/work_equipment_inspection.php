<?php
	require '../includes/db.php';
	$db = getDB();
	$selectquery 	= "SELECT * FROM nman_site_master AS SITES INNER JOIN `nman_site_assignment` AS ASSIGN INNER JOIN nman_user_master AS USERS ON USERS.pk_user_id=:pk_user_id AND ASSIGN.fk_site_id=SITES.pk_site_id WHERE SITES.pk_site_id=:pk_site_id";
	$sltstmt= $db->prepare($selectquery);
		$sltstmt->bindParam("pk_site_id", $_GET["siteid"]);
		$sltstmt->bindParam("pk_user_id", $_GET["userid"]);
	$sltstmt->execute();
//user type
	$sltsql 	= "SELECT `type_name` FROM `nman_user_master` AS USER INNER JOIN `nman_user_types` AS TYPE ON TYPE.pk_type_id=USER.fk_type_id WHERE `pk_user_id`=:pk_user_id";
//execute query
	$slttype	= $db->prepare($sltsql);
		$slttype->bindParam("pk_user_id", $_GET["userid"]);
	$slttype->execute();
//fetch data
	$fetchType 	= $slttype->fetch(PDO::FETCH_ASSOC);

//fetch data
	$siteData 	= $sltstmt->fetch(PDO::FETCH_ASSOC);
	$datetime	= date("d/m/Y h:i A");
	$dateArray  = explode(" ", $datetime);
	$date = $dateArray[0];
	$time = $dateArray[1]." ".$dateArray[2];
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Norman Scaffolding</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<?php
			$logo = "";
			if($_GET["worktype"]==1):
				$logo = "images/Norman-Group-logo-4.png";
		?>
		<link href="css/scaffolding/work-equipment-inspection/style.css" type="text/css" rel="stylesheet">
		<?php
			else:
				$logo = "images/Norman-Group-logo-5.png";
		?>
		<link href="css/brickwork/work-equipment-inspection/style.css" type="text/css" rel="stylesheet">
		<?php
			endif;
		?>
	</head>
	<body>
		<div id="footer">
			<p class="page text-right">WORK EQUIPMENT INSPECTION FORM <?php echo $datetime; ?></p>
		</div>
		<table class="table table-bordered1 w-100">
			<thead>
				<tr>
					<div class="icon-gatwick-image"><img src="<?php echo $logo; ?>" alt="" width ="300px" ></div>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
		<div class="managerTitle" style="margin-top:5px;">
			<h5 style="color:#fff;"><strong>WORK EQUIPMENT INSPECTION FORM</strong></h5>
		</div>
		<table style="width:100%" class="arialTable">
			<tbody>
				<tr>
					<td class="w-10">Devlopement</td>
					<td class="w-40">
						<div class="input2"><?php echo $siteData["site_name"]; ?></div>
					</td>
					<td class="w-10" style="text-align:center;">Client</td>
					<td class="w-20">
						<div class="input2"><?php echo $siteData["site_owner_name"]; ?></div>
					</td>
			</tbody>
		</table>
		<table style="width:100%" class="arialTable">
			<tbody>
				<tr>
					<td class="w-35">Name of person(s) carrying out the inspection</td>
					<td class="w-15">
						<div class="input2"><?php echo $siteData["user_first_name"]." ".$siteData["user_last_name"]; ?></div>
					</td>
					<td class="w-10" style="text-align:center;">Position</td>
					<td class="w-15">
						<div class="input2"><?php echo $fetchType["type_name"]; ?></div>
					</td>
					<td class="w-10" style="text-align:center;">Date</td>
					<td class="w-15">
						<div class="input2"><?php echo $date; ?></div>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
			$sltdata 	= "SELECT * FROM `nman_work_equipment_inspection` WHERE `fk_site_id`=:fk_site_id AND `fk_user_id`=:fk_user_id AND `is_new`=1 AND `work_type`=:work_type AND `wei_visibility`=1";
		//execute query
			$sltoutput	= $db->prepare($sltdata);
				$sltoutput->bindParam("fk_site_id", $_GET["siteid"]);
				$sltoutput->bindParam("fk_user_id", $_GET["userid"]);
				$sltoutput->bindParam("work_type", $_GET["worktype"]);
			$sltoutput->execute();
		//fetch data
			$fetchData 	= $sltoutput->fetchAll(PDO::FETCH_ASSOC);
		?>
		<table class="bordered">
			<thead>
				<tr>
					<th style="border-right: 1px solid #fff;border-spacing: 0px;color:#fff;">Description of the work Equipment</th>
					<th style="border-right: 1px solid #fff; border-spacing: 0px;color:#fff;">Identification Number</th>
					<th style="border-right: 1px solid #fff; border-spacing: 0px;color:#fff;">Overview of inspection and details of any issues that require action</th>
					<th style="border-right: 1px solid #fff; border-spacing: 0px;color:#fff;">Action taken to rectify any issues raised </th>
					<th style="border-right: none; border-spacing: 0px;color:#fff;">Is the equipment safe to use?</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$status = true;
					$imgkey = "";
					if(sizeof($fetchData)>0):
						foreach($fetchData AS $key=>$rows):
							$imgkey = $rows["img_key"];
				?>
				<tr>
					<td><div class="customtd"><?php echo $rows["input_1"]; ?></div></td>
					<td><div class="customtd"><?php echo $rows["input_2"]; ?></div></td>
					<td><div class="customtd"><?php echo $rows["input_3"]; ?></div></td>
					<td><div class="customtd"><?php echo $rows["input_4"]; ?></div></td>
					<td><div class="customtd"><?php echo $rows["input_5"]; ?></div></td>
				</tr>
				<?php
						endforeach;
					else:
						$status = false;
				?>
				<tr>
					<td><div class="customtd">-</div></td>
					<td><div class="customtd">-</div></td>
					<td><div class="customtd">-</div></td>
					<td><div class="customtd">-</div></td>
					<td><div class="customtd">-</div></td>
				</tr>
				<?php
					endif;
				?>
			</tbody>
		</table>
		
		<?php
			if($status==false):
		?>
			<br>
			<div class="w-100 no-record-found text-center">No records found</div>
		<?php
			endif;
		?>
		
		<div></div>
		<div></div>
		<br>

		<div class="w-100 mainBorder" style="margin-top:20px;">
			<div class="innerColor">
				<p><strong>SIGNATURE –I CONFIRM THAT THE INFORMATION ABOVE IS TRUE AND ACCURATE AT THE TIME OF THE INSPECTION</strong></p>
				<p>This form must be submitted on the date of completion</p>
			</div>
			<div class="inputBack">
				<table class="w-100">
					<tbody>
						<tr>
							<td class="w-10"><strong>Signature</strong></td>
							<td>
								<div class="input1 text-center" style="background:#fff;">
									<img src="<?php echo "data:image/png;base64,".$siteData["user_signature"]; ?>" width="150px" height="50px" />
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<!-- image -->
		<?php
		//select form fields
			$sltimg 	= "SELECT * FROM `nman_site_images` WHERE `fk_site_id`=:fk_site_id AND `fk_form_id`=:fk_form_id AND `work_type`=:work_type AND `img_key`=:img_key AND `image_visibility`=1";
			$sltimages	= $db->prepare($sltimg);
				$sltimages->bindParam("fk_site_id"	, $_GET["siteid"]);
				$sltimages->bindParam("fk_form_id"	, $_GET["formid"]);
				$sltimages->bindParam("work_type"	, $_GET["worktype"]);
				$sltimages->bindParam("img_key"		, $imgkey);
			$sltimages->execute();
		//fetch data
			$imageData = $sltimages->fetchAll(PDO::FETCH_ASSOC);
			if($sltimages->rowCount() > 0):
				echo "<table><tr>";
				foreach($imageData as $key=>$img):
					echo "<td class='text-center'><img src='".$img["site_image"]."' /></td>";
					echo (((($key+1)%2)==0)? "</tr><tr>" : "");
				endforeach;
				echo "</tr></table>";
			endif;
		?>
	</body>
</html>