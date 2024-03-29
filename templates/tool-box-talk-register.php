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
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Norman Scaffolding</title>
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<?php
			$logo = "";
			if($_GET["worktype"]==1):
				$logo = "../images/Norman-Group-logo-4.png";
		?>
		<link href="../css/scaffolding/tool-box-talk-register/style.css" type="text/css" rel="stylesheet">
		<?php
			else:
				$logo = "../images/Norman-Group-logo-5.png";
		?>
		<link href="../css/brickwork/tool-box-talk-register/style.css" type="text/css" rel="stylesheet">
		<?php
			endif;
		?>
	</head>
	<body>
		<div id="footer">
			<p class="page text-right">TOOLBOX TALK REGISTER <?php echo $datetime; ?></p>
		</div>

		<table class="table table-bordered1 w-100">
			<tbody>
				<tr>
					<div class="icon-gatwick-image"><img src="<?php echo $logo; ?>" alt="" width ="300px"></div>
				</tr>
			</tbody>
		</table>

		<div class="managerTitle">
			<h5 style="color:#fff;"><strong>TOOLBOX TALK REGISTER</strong></h5>
		</div>
		<table style="width:100%" class="arialTable">
			<tbody>
				<tr>
					<td class="w-10">Site</td>
					<td class="w-25">
						<div class="input2"><?php echo $siteData["site_name"]; ?></div>
					</td>
					<td class="w-15" style="text-align:center;">Client</td>
					<td class="w-20">
						<div class="input2"><?php echo $siteData["site_owner_name"]; ?></div>
					</td>
					<td class="w-10" style="text-align:center;">Date</td>
					<td class="w-25">
						<div class="input2"><?php echo $date; ?></div>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
			$mstdata = "SELECT * FROM `nman_tool_boxtalk_master` WHERE `fk_site_id`=:fk_site_id AND `fk_user_id`=:fk_user_id AND `work_type`=:work_type AND `is_new`=1 AND `mtform_visibility`=1";
		//execute query
			$sltmst	= $db->prepare($mstdata);
				$sltmst->bindParam("fk_site_id", $_GET["siteid"]);
				$sltmst->bindParam("fk_user_id", $_GET["userid"]);
				$sltmst->bindParam("work_type", $_GET["worktype"]);
			$sltmst->execute();
		//fetch data
			$fetchmst 	= $sltmst->fetch(PDO::FETCH_ASSOC);
		?>
		<table style="width:100%" class="arialTable">
			<tbody>
				<tr>
					<td class="w-10">Topic</td>
					<td class="w-25">
						<div class="input2"><?php echo $fetchmst["topic"]; ?></div>
					</td>
					<td class="w-15" style="text-align:center;">GT700 / NG Nr</td>
					<td class="w-20">
						<div class="input2"><?php echo $fetchmst["citp_gt"]; ?></div>
					</td>
					<td class="w-10" style="text-align:center;">Duration</td>
					<td class="w-25">
						<div class="input2"><?php echo $fetchmst["duration"]; ?></div>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="textRow text-justify">
			I have been given the above toolbox talk and I fully understand all that has been told to me.  I was also given the chance to mention any points that I felt were imperative in the interests of Health, Safety and Welfare of others and myself working on this site.
		</div>
		<?php
			$sltdata 	= "SELECT * FROM `nman_tool_boxtalk_form` WHERE `fk_mtform_id`=:fk_mtform_id AND `work_type`=:work_type AND `tform_visibility`=1";
		//execute query
			$sltoutput	= $db->prepare($sltdata);
				$sltoutput->bindParam("fk_mtform_id", $fetchmst["pk_mtform_id"]);
				$sltoutput->bindParam("work_type", $_GET["worktype"]);
			$sltoutput->execute();
		//fetch data
			$fetchData 	= $sltoutput->fetchAll(PDO::FETCH_ASSOC);
		?>
		<div class="w-100">
			<table class="bordered">
				<thead>
					<tr>
						<th style="border-right: 1px solid #fff; border-spacing: 0px;"></th>
						<th style="border-right: 1px solid #fff; border-spacing: 0px;"><label>Name (Print)</label></th>
						<th style="border-right: none; border-spacing: 0px;"><label>Signed</label></th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($fetchData AS $key=>$rows):
					?>
					<tr class="<?php echo (((($key+1)%2)==0) ? "tr-even" : "tr-odd"); ?>">
						<td><div class="customtd"><?php echo ($key+1); ?></div></td>
						<td><div class="customtd"><?php echo $rows["input_1"]; ?></div></td>
						<td>
							<div class="customtd" style="2px 0px 2px 0px;">
								<img src="<?php echo "data:image/png;base64,".$rows["input_2"]; ?>" width="100px" height="40px" />
							</div>
						</td>
					</tr>
					<?php
						endforeach;
					?>
				</tbody>
			</table>
		</div>

		<table style="width:100%" class="arialTable" style="float:none;">
			<tbody>
				<tr>
					<td class="w-10">Any other issue raised</td>
					<td class="w-20">
						<div class="input2"><?php echo $fetchmst["other_issues"]; ?></div>
					</td>
				</tr>
			</tbody>
		</table>

		<!-- <p style="text-align:center;">I confirm that the above persons have attended the toolbox talk.</p>
		<?php
			$sltsql 	= "SELECT `type_name` FROM `nman_user_master` AS USER INNER JOIN `nman_user_types` AS TYPE ON TYPE.pk_type_id=USER.fk_type_id WHERE `pk_user_id`=:pk_user_id";
		//execute query
			$slttype	= $db->prepare($sltsql);
				$slttype->bindParam("pk_user_id", $_GET["userid"]);
			$slttype->execute();
		//fetch data
			$fetchType 	= $slttype->fetch(PDO::FETCH_ASSOC);
		?>
		<table style="width:100%" class="arialTable">
			<tbody>
				<tr>
					<td class="w-10">Delivered by</td>
					<td class="w-30">
						<div class="input2"><?php echo $siteData["user_first_name"]." ".$siteData["user_last_name"]; ?></div>
					</td>
					<td class="w-10" style="text-align:center;">Position</td>
					<td class="w-30">
						<div class="input2"><?php echo $fetchType["type_name"]; ?></div>
					</td>
				</tr>
			</tbody>
		</table> -->
		<!-- <strong>
			<p style="text-align:center;">This form to be returned to main office and retained on file for reference purposes.</p>
		</strong> -->
		<div class="w-100 mainBorder">
			<div class="innerColor">
				I confirm that the above persons have attended the toolbox talk.  
			</div>
			<div class="inputBack">
				<div class="w-100" style="display:flex; flex-direction:row; justify-content: space-between;">
					<div style="display:flex; flex-direction:column;">
						<p class="" style="padding-left:10px; padding-top:10px; width:180px;">
							<strong>Delivered By: <?php echo $siteData["user_first_name"]." ".$siteData["user_last_name"]; ?></strong>
						</p>
						<p class="" style="padding-left:10px; padding-top:10px; width:180px;">
							<strong>Position: <?php echo $fetchType["type_name"]; ?></strong>
						</p>
					</div>
					<div style="display:flex; flex-direction:row;">
						<p class="" style="padding:23px; width:80px;">
							<strong>Signature</strong>
						</p>
						<div class="input1 text-center" style="background:#fff; margin:8px">
							<img src="<?php echo "data:image/png;base64,".$siteData["user_signature"]; ?>" width="75px" height="40px" />
						</div>
					</div>
				</div>
				<!-- <table class="w-100">
					<tbody>
						<tr>
								<td class="w-10"><strong>Signature</strong></td>
								<td>
									<div class="input1 text-center" style="background:#fff;">
										<img src="<?php echo "data:image/png;base64,".$siteData["user_signature"]; ?>" width="75px" height="40px" />
									</div>
								</td>
						</tr>
					</tbody>
				</table> -->
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
				$sltimages->bindParam("img_key"		, $fetchmst["img_key"]);
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