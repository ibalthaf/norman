<?php
	require '../includes/db.php';
	$db = getDB();
//image variabe
	$checkimg	= "<img src='images/check.png' />";
//get user information
	$selectquery = "SELECT * FROM `nman_user_master` WHERE `pk_user_id`=:pk_user_id";
//prepare execute
	$sltstmt	= $db->prepare($selectquery);
		$sltstmt->bindParam("pk_user_id", $_GET["userid"]);
	$sltstmt->execute();
//fetch data
	$userData 	= $sltstmt->fetch(PDO::FETCH_ASSOC);
	$logo="";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Norman Group</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
				<?php
			if($_GET["worktype"]==1):
				$logo = "images/Norman-Group-logo-3.png";
		?>
			<link href="css/medical-self-certification/style.css" type="text/css" rel="stylesheet">
		<?php
			else:
				$logo = "images/Norman-Group-logo-6.png";
		?>
			<link href="css/brickwork/medical-self-certification/style.css" type="text/css" rel="stylesheet">
		<?php
			endif;
		?>
	</head>
	<body>
		<div class="container">
			<div class="health-medical-self-certification">
				<div class="health-medical-self-certification-logo">
					<div class="gatwick">
						<div class="icon-gatwick-image"><img src="<?php echo $logo; ?>" alt="" width ="277" height="40" style="margin-top:12px; margin-left:5px;"></div>
					</div>
				</div>
				<div class="health-medical-self-certification-title">
					<h4><strong>Health & Medical Self-Certification</strong></h4>
				</div>
				<div class="health-medical-self-certification-title-content text-justify">
					Alertness and reasonable physical fitness are essential for your duties within the construction industry, in particular for works which interface with moving plant, machines or equipment.  Please be aware when you answer ‘No’ that you are accepting a certain degree of responsibility for your safety, and the safety of others who may be affected by your medical condition.
				</div>
				<div class="health-medical-self-certification-title-sub-content text-justify">
					If you answer ‘Yes’ to any questions, could you please expand on the question in the comments box below, (include serial number).  Thank you for your time in this matter.
				</div>

				<?php
				//select form fields
					$sltqry 	= "SELECT * FROM `nman_form_master` AS FORM INNER JOIN `nman_form_fields` AS FIELDS ON FIELDS.fk_form_id=FORM.pk_form_id  AND FIELDS.`work_type`=:work_type WHERE FORM.pk_form_id=:pk_form_id";
					$sltfields= $db->prepare($sltqry);
						$sltfields->bindParam("work_type", $_GET["worktype"]);
						$sltfields->bindParam("pk_form_id", $_GET["formid"]);
					$sltfields->execute();
				//fetch data
					$fieldData = $sltfields->fetchAll(PDO::FETCH_ASSOC);

				//select field input values
					$sltOutqry = "SELECT * FROM `nman_health_assessment` WHERE `fk_user_id`=:fk_user_id AND `is_new`=1 AND work_type=:work_type";
					$sltOutput= $db->prepare($sltOutqry);
						$sltOutput->bindParam("fk_user_id", $_GET["userid"]);
						$sltOutput->bindParam("work_type", $_GET["worktype"]);
					$sltOutput->execute();
				//fetch data
					$outputData = $sltOutput->fetch(PDO::FETCH_ASSOC);
				?>
				<div class="health-medical-self-certification-table">
					<table class="table table-bordered w-100 bordered">
						<thead>
							<tr>
								<th class="w-6">Serial <br>Number </th>
								<th class="w-60">
									All information divulged here will be held in the strictest confidence and<br>
									will not br released / actioned on without the written permission of the<br>person signing below:
								</th>
								<th class="w-7">Yes</th>
								<th class="w-7">No</th>
								<th class="w-20">Comments</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($fieldData AS $key=>$fields):
									if($fields["field_set"] == 0):
										$true 	= ($outputData["input_".($key+1)]==1 ? $checkimg : "");
										$false 	= ($outputData["input_".($key+1)]==0 ? $checkimg : "");
										$comment= $outputData["comment_".($key+1)];
							?>
							<tr>
								<td class="text-center"><?php echo ($key+1); ?></td>
								<td><?php echo $fields["field_name"]; ?></td>
								<td class="text-center"><?php echo $true; ?></td>
								<td class="text-center"><?php echo $false; ?></td>
								<td><?php echo $comment; ?></td>
							</tr>
							<?php
									endif;
								endforeach; 
							?>
						</tbody>
					</table>
				</div>

				<table style="width:100%" class="arialTable">
					<tbody>
						<tr>
							<td class="w-30">Any other issue raised</td>
							<td class="w-70">
								<div class="input2">
									<?php
										echo $outputData["input_18"];
									?>
								</div>
							</td>
						</tr>
					</tbody>
				</table>

				<table style="width:100%; margin-top:10px; margin-bottom:10px;" class="arialTable">
					<tbody>
						<tr>
							<td class="w-10">Name</td>
							<td class="w-30">
								<div class="input2" style="padding-left: 7px !important;"><?php echo $outputData["input_19"]; ?></div>
							</td>
							<td class="w-15" style="text-align:center;">Date</td>
							<td class="w-15">
								<div class="input2" style="padding-left: 7px !important;"><?php echo date('d/m/Y'); ?></div>
							</td>
							<td class="w-15" style="text-align:center;">Time</td>
							<td class="w-15">
								<div class="input2" style="padding-left: 7px !important;"><?php echo date('h:i A');?></div>
							</td>
						</tr>
					</tbody>
				</table>

				<div class="w-100 mainBorder">
					<div class="innerColor header-title">
						<h5 style="color:#fff;"><strong>SIGNATURE</h5>
					</div>
					<div class="inputBack">
						<table class="w-100">
							<tbody>
								<tr>
									<td class="w-10"><strong>Signature</strong></td>
									<td style="padding:1px 0px 1px 0px;">
										<div class="input1 text-center" style="background:#fff;">
											<?php
												echo "<img src='data:image/png;base64,".$outputData["input_20"]."' width='200px' height='40px' />";
											?>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>