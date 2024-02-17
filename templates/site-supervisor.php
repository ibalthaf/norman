<?php
	require '../includes/db.php';
	$db = getDB();
	$selectquery = "SELECT * FROM `nman_site_master` AS SITES INNER JOIN `nman_user_master` AS USERS ON USERS.pk_user_id=:pk_user_id WHERE SITES.pk_site_id=:pk_site_id";
//prepare execute
	$sltstmt	= $db->prepare($selectquery);
		$sltstmt->bindParam("pk_user_id", $_GET["userid"]);
		$sltstmt->bindParam("pk_site_id", $_GET["siteid"]);
	$sltstmt->execute();
//fetch data
	$fecthData 	= $sltstmt->fetch(PDO::FETCH_ASSOC);
//image variabe 
	$checkimg	= "<img src='images/check.png' />";
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
		<link href="css/scaffolding/site-supervisor/style.css" type="text/css" rel="stylesheet">
		<?php
			else:
				$logo = "images/Norman-Group-logo-5.png";
		?>
		<link href="css/brickwork/site-supervisor/style.css" type="text/css" rel="stylesheet">
		<?php
			endif;
		?>
	</head>
	<body>
		<div id="footer">
			<p class="page text-right">MANAGER / SUPERVISOR SITE VISIT REPORT <?php echo $datetime; ?></p>
		</div>
		<div class="container">
			<div class="new-starter-form">
				<div class="new-starter-form-title-table">
					<table class="table table-bordered1 w-100">
						<tbody>
							<tr>
								<div class="icon-gatwick-image"><img src="<?php echo $logo; ?>" alt="" width ="250px"></div>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="managerTitle text-center">
					<h5 style="color:#fff;"><strong>MANAGER / SUPERVISOR SITE VISIT REPORT</strong></h5>
				</div>
				<table style="width:100%" class="arialTable">
					<tbody>
						<tr>
							<td class="w-10">Devlopment</td>
							<td class="w-30"><div class="input2"><?php echo $fecthData["site_name"]; ?></div></td>
							<td class="w-10" style="text-align:center;">Client</td>
							<td class="w-30"><div class="input2"><?php echo $fecthData["site_owner_name"]; ?></div></td>
						</tr>
					</tbody>
				</table>
				<table style="width:100%" class="margin arialTable">
					<tbody >
						<tr>
							<td class="w-30" style=" font-size: 13px;"><span style="font-size:14px;">Name </span>of person(s) carrying out the inspection</td>
							<td class="w-20"><div class="input2"><?php echo $fecthData["user_first_name"]." ".$fecthData["user_last_name"]; ?></div></td>
							<td class="w-10">Date</td>
							<td class="w-15"><div class="input2"><?php echo $date;?></div></td>
							<td class="w-10">Time</td>
							<td class="w-15"><div class="input2"><?php echo $time;?></div></td>
						</tr>
					</tbody>
				</table>
				<?php
				//select form fields
					$sltqry 	= "SELECT * FROM nman_form_master AS FORM INNER JOIN nman_form_fields AS FIELDS ON FIELDS.fk_form_id=FORM.pk_form_id AND FIELDS.field_visibility=1 AND FIELDS.`work_type`=:work_type WHERE pk_form_id=:pk_form_id";
					$sltfields= $db->prepare($sltqry);
						$sltfields->bindParam("pk_form_id", $_GET["formid"]);
						$sltfields->bindParam("work_type", $_GET["worktype"]);
					$sltfields->execute();
				//fetch data
					$fieldData = $sltfields->fetchAll(PDO::FETCH_ASSOC);
				//select field input values
					$sltOutqry = "SELECT * FROM `nman_supervisor_form` WHERE `fk_site_id`=:fk_site_id AND `fk_user_id`=:fk_user_id AND `work_type`=:work_type AND `is_new`=1";
					$sltOutput= $db->prepare($sltOutqry);
						$sltOutput->bindParam("fk_site_id", $_GET["siteid"]);
						$sltOutput->bindParam("fk_user_id", $_GET["userid"]);
						$sltOutput->bindParam("work_type", $_GET["worktype"]);
					$sltOutput->execute();
				//fetch data
					$outputData = $sltOutput->fetch(PDO::FETCH_ASSOC);
				?>
				<table class="w-100 table-bordered margin1" style="margin-bottom:10px;">
					<thead class="height" style="text-align:center;">
						<tr>
							<th class="w-30 bigHeading">ACTIVITY/TOPIC</th>
							<th class="w-8">
								<div class="rotate">
									<span>
										<div>Very Poor </div>
									</span>
								</div>
							</th>
							<th class="w-8">
								<div class="rotate">
									<span>
										<div>Poor</div>
									</span>
								</div>
							</th>
							<th class="w-8">
								<div class="rotate">
									<span>
										<div> Acceptable</div>
									</span>
								</div>
							</th>
							<th class="w-8">
								<div class="rotate">
									<span>
										<div> Good</div>
									</span>
								</div>
							</th>
							<th class="w-8">
								<div class="rotate">
									<span>
										<div>Excellent</div>
									</span>
								</div>
							</th>
							<th class="w-0 bigHeading">COMMENTS</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach($fieldData as $key=>$row):
								if($row["field_set"] == 1):
							//execute output
								$output_1 = $output_2 = $output_3 = $output_4 = $output_5 = $output_6 = "";
								$output_1 = ($outputData["input_".($key+1)]==1 ? $checkimg : "");
								$output_2 = ($outputData["input_".($key+1)]==2 ? $checkimg : "");
								$output_3 = ($outputData["input_".($key+1)]==3 ? $checkimg : "");
								$output_4 = ($outputData["input_".($key+1)]==4 ? $checkimg : "");
								$output_5 = ($outputData["input_".($key+1)]==5 ? $checkimg : "");
								$output_6 = $outputData["comment_".($key+1)];
						?>
						<tr>
							<td><?php echo $row["field_name"]; ?></td>
							<td class="text-center"><?php echo $output_1; ?></td>
							<td class="text-center"><?php echo $output_2; ?></td>
							<td class="text-center"><?php echo $output_3; ?></td>
							<td class="text-center"><?php echo $output_4; ?></td>
							<td class="text-center"><?php echo $output_5; ?></td>
							<td><?php echo $output_6; ?></td>
						</tr>
						<?php
								endif;
							endforeach
						?>
						<tr style="height:10px;">
							<td colspan="7"></td>
						</tr>
						<?php
							foreach($fieldData as $key=>$row):
								if($row["field_set"] == 2):
								//execute output
									$output_1 = $output_2 = $output_3 = "";
									$output_1 = ($outputData["input_".($key+1)]==1 ? $checkimg : "");
									$output_2 = ($outputData["input_".($key+1)]==0 ? $checkimg : "");
									$output_3 = $outputData["comment_".($key+1)];
						?>
						<tr>
							<td><?php echo $row["field_name"]; ?></td>
							<td>Yes</td>
							<td class="text-center"><?php echo $output_1; ?></td>
							<td>No</td>
							<td class="text-center"><?php echo $output_2; ?></td>
							<td colspan="2"><?php echo $output_3; ?></td>
						</tr>
						<?php
								endif;
							endforeach;
						?>
						<tr style="height:10px;">
							<td colspan="7"></td>
						</tr>
						<tr style="height:10px;">
							<td colspan="7"><strong>Documentation held on site</strong></td>
						</tr>
						<?php
							$imgkey = "";
							foreach($fieldData as $key=>$row):
								if($row["field_set"] == 3):
							//execute output
								$output_1 = $output_2 = $output_3 = "";
								$output_1 	= ($outputData["input_".($key+1)]==1 ? $checkimg : "");
								$output_2 	= ($outputData["input_".($key+1)]==0 ? $checkimg : "");
								$output_3 	= $outputData["comment_".($key+1)];
								$imgkey		= $outputData["img_key"];
						?>
						<tr>
							<td><?php echo $row["field_name"]; ?></td>
							<td>Yes</td>
							<td class="text-center"><?php echo $output_1; ?></td>
							<td>No</td>
							<td class="text-center"><?php echo $output_2; ?></td>
							<td colspan="2"><?php echo $output_3; ?></td>
						</tr>
						<?php
								endif;
							endforeach;
						?>
						<tr>
							<td colspan="7">
								<p class="text-justify">
									<strong>
									Record items actioned, including details of any operative not conforming to company policy. Ensure that details of remedialaction that cannot be completed immediately is set against a timescale and signed off when completed
									</strong>
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="w-100 mainBorder">
					<div class="innerColor header-title">
						<h5 style="color:#fff;"><strong>SIGNATURE – I CONFIRM THAT THE INFORMATION ABOVE IS TRUE AND ACCURATE AT THE TIME OF THE INSPECTION</h5>
					</div>
					<?php
						$sltsignqry	= "SELECT `user_signature` FROM `nman_user_master` WHERE `pk_user_id`=:pk_user_id";
						$sltsign= $db->prepare($sltsignqry);
							$sltsign->bindParam("pk_user_id", $_GET["userid"]);
						$sltsign->execute();
					//fetch data
						$signData = $sltsign->fetch(PDO::FETCH_ASSOC);
					?>
					<div class="inputBack">
						<table class="w-100">
							<tbody>
								<tr>
									<td class="w-10"><strong>Signature</strong></td>
									<td>
										<div class="input1 text-center" style="background:#fff;">
											<?php
												echo "<img src='data:image/png;base64,".$signData["user_signature"]."' width='200px' height='50px' />";
											?>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			
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
					echo "<div style='text-align:center;'>";
					foreach($imageData as $key=>$img):
						echo "<div style='text-align:center;margin-top:10px;'><img src='".$img["site_image"]."' /></div>";
					endforeach;
					echo "</div>";
				endif;
			?>
		</div>
	</body>
</html>