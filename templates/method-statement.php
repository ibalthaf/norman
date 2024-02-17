<?php
	require '../includes/db.php';
	$db = getDB();
	$selectquery 	= "SELECT * FROM nman_site_master AS SITES INNER JOIN `nman_site_assignment` AS ASSIGN INNER JOIN nman_user_master AS USERS ON USERS.pk_user_id=:pk_user_id AND ASSIGN.fk_site_id=SITES.pk_site_id WHERE SITES.pk_site_id=:pk_site_id";
	$sltstmt= $db->prepare($selectquery);
		$sltstmt->bindParam("pk_site_id", $_GET["siteid"]);
		$sltstmt->bindParam("pk_user_id", $_GET["userid"]);
	$sltstmt->execute();
//fetch data
	$siteData 	= $sltstmt->fetch(PDO::FETCH_ASSOC);
	$datetime	= date("d/m/Y h:i A");
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
		<link href="css/scaffolding/method-statement/style.css" type="text/css" rel="stylesheet">
		<?php
			else:
				$logo = "images/Norman-Group-logo-5.png";
		?>
		<link href="css/brickwork/method-statement/style.css" type="text/css" rel="stylesheet">
		<?php
			endif;
		?>
	</head>
	<body>
		<div id="footer">
			<p class="page text-right">Method Statement & Risk Assessment Register <?php echo $datetime; ?></p>
		</div>
		<div class="container">
			<div class="new-starter-form">
				<div class="new-starter-form-title-table">
					<table class="table table-bordered1 w-100">
						<tbody>
							<tr>
								<div class="icon-gatwick-image"><img src="<?php echo $logo; ?>" alt="" width ="300px"></div>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="managerTitle">
					<h4 style="color:#fff;"><strong>METHOD STATEMENT/RISK ASSESSMENT BRIEFING REGISTER</strong></h4>
				</div>
				<table style="width:100%" class="arialTable">
					<tbody>
						<tr>
							<td class="w-10">Site</td>
							<td class="w-40">
								<div class="input2"><?php echo $siteData["site_name"]; ?></div>
							</td>
							<td class="w-10" style="text-align:center;">Client</td>
							<td class="w-40">
								<div class="input2"><?php echo $siteData["site_owner_name"]; ?></div>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="textRow text-justify">
					We (the undersigned) have read & fully understood the Method Statement & Risk Assessment, and have had the opportunity to ask questions. We will comply with the specified requirements and control measures. If the work activity changes or deviates from that originally envisaged, we will seek further advice and request an amended method statement: 
				</div>
				<?php
					if($_GET["others"]==1):
						$sltdata 	= "SELECT * FROM `nman_method_statement` WHERE `fk_site_id`=:fk_site_id AND `work_type`=:work_type AND `mform_visibility`=1 AND `mform_createdon` > DATE_SUB(now(), INTERVAL 3 MONTH)";
					//execute query
						$sltoutput	= $db->prepare($sltdata);
							$sltoutput->bindParam("fk_site_id", $_GET["siteid"]);
							$sltoutput->bindParam("work_type", $_GET["worktype"]);
						$sltoutput->execute();
					else:
						$sltdata 	= "SELECT * FROM `nman_method_statement` WHERE `fk_site_id`=:fk_site_id AND `work_type`=:work_type AND `mform_visibility`=1 AND `fk_user_id`=:fk_user_id AND `is_new`=1";
					//execute query
						$sltoutput	= $db->prepare($sltdata);
							$sltoutput->bindParam("fk_site_id"	, $_GET["siteid"]);
							$sltoutput->bindParam("fk_user_id"	, $_GET["userid"]);
							$sltoutput->bindParam("work_type"	, $_GET["worktype"]);
						$sltoutput->execute();
					endif;
				//fetch data
					$fetchData 	= $sltoutput->fetchAll(PDO::FETCH_ASSOC);
				?>
				<table class="bordered">
					<thead>
						<tr>
							<th style="border-right: 1px solid #fff;"></th>
							<th style="border-right: 1px solid #fff;width:16%;">
								<label>Name (Print)</label>
							</th>
							<th style="border-right: 1px solid #fff; width:16%;">
								<label>Occupation </label>
							</th>
							<th style="border-right: 1px solid #fff; width:16%;">
								<label>Signed</label>
							</th>
							<th style="border-right: 1px solid #fff; width:16%;">
								<label>Date</label>
							</th>
							<th style="border-right: 1px solid #fff; none; width:16%;">
								<label>Briefing Given By (Print)</label>
							</th>
							<th style="border-right: none;">
								<label>Signed</label>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$imgkey = "";
						//each loop
							foreach($fetchData AS $key=>$rows):
								$imgkey = $rows["img_key"];
						?>
						<tr>
							<td><div class="customtd"><?php echo $key+1; ?></div></td>
							<td><div class="customtd"><?php echo $rows["input_1"]; ?></div></td>
							<td><div class="customtd"><?php echo $rows["input_2"]; ?></div></td>
							<td>
								<div class="customtd text-center" style="padding:6px 0px 0px 0px;">
									<img src="<?php echo "data:image/png;base64,".$rows["input_3"]; ?>" width="100px" height="50px" />
								</div>
							</td>
							<td><div class="customtd"><?php echo date("d/m/Y", strtotime($rows["input_4"])); ?></div></td>
							<td><div class="customtd"><?php echo $siteData["user_first_name"]." ".$siteData["user_last_name"]; ?></div></td>
							<td>
								<div class="customtd text-center" style="padding:6px 0px 0px 0px;">
									<img src="<?php echo "data:image/png;base64,".$siteData["user_signature"]; ?>" width="100px" height="50px" />
								</div>
							</td>
						</tr>
						<?php
							endforeach;
						?>
					</tbody>
				</table>
				<div class="textRow">
					Important note: 
				</div>
				<p class="text-justify">Toolbox talks records; Every method statement should be explained to those carrying out the works in a toolbox talk.  Records of attendance should be archived together with other information as stated above.  Failure to communicate site based instructions and to keep evidence could be a very costly mistake. </p>
			</div>
			<!-- image -->
			<?php
			//select form fields
				if($_GET["others"]==1):
					$sltimg 	= "SELECT * FROM `nman_site_images` WHERE `fk_site_id`=:fk_site_id AND `fk_form_id`=:fk_form_id AND `work_type`=:work_type AND `image_visibility`=1";
				//prepare statement
					$sltimages	= $db->prepare($sltimg);
						$sltimages->bindParam("fk_site_id"	, $_GET["siteid"]);
						$sltimages->bindParam("fk_form_id"	, $_GET["formid"]);
						$sltimages->bindParam("work_type"	, $_GET["worktype"]);
					$sltimages->execute();
				else:
					$sltimg 	= "SELECT * FROM `nman_site_images` WHERE `fk_site_id`=:fk_site_id AND `fk_form_id`=:fk_form_id AND `work_type`=:work_type AND `img_key`=:img_key AND `image_visibility`=1";
				//prepare statement
					$sltimages	= $db->prepare($sltimg);
						$sltimages->bindParam("fk_site_id"	, $_GET["siteid"]);
						$sltimages->bindParam("fk_form_id"	, $_GET["formid"]);
						$sltimages->bindParam("work_type"	, $_GET["worktype"]);
						$sltimages->bindParam("img_key"		, $imgkey);
					$sltimages->execute();
				endif;
			//fetch data
				$imageData = $sltimages->fetchAll(PDO::FETCH_ASSOC);
				if($sltimages->rowCount() > 0):
					echo "<table><tr>";
					foreach($imageData as $key=>$img):
						echo "<td class='text-center' style='padding:10px;'><img src='".$img["site_image"]."' /></td>";
						echo (((($key+1)%2)==0)? "</tr><tr>" : "");
					endforeach;
					echo "</tr></table>";
				endif;
			?>
		</div>
	</body>
</html>