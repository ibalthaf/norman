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
	$datetime	= date("d/m/Y h:i A");
	$dateArray  = explode(" ", $datetime);
	$date = $dateArray[0];
	$time = $dateArray[1]." ".$dateArray[2];
	$logo = "";
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
			if($_GET["worktype"]==1):
				$logo = "images/Norman-Group-logo-3.png";
		?>
			<link href="css/scaffolding/new-starter-form/style.css" type="text/css" rel="stylesheet">
		<?php
			else:
				$logo = "images/Norman-Group-logo-6.png";
				// $logo = "images/Norman-Group-logo-3.png";
		?>
			<link href="css/brickwork/new-starter-form/style.css" type="text/css" rel="stylesheet">
		<?php
			endif;
		?>
	</head>
	<body>
		<div id="footer">
			<p class="page text-right">NEW STARTER FORM <?php echo $datetime; ?></p>
		</div>
		<div class="container">
			<div class="health-medical-self-certification">
				<div class="health-medical-self-certification-logo">
					<div class="gatwick">
						<div class="icon-gatwick-image">
							<img src="<?php echo $logo; ?>" alt="" width ="400px">
						</div>
					</div>
				</div>
				<div class="health-medical-self-certification-title">
					<strong><u>NEW STARTER FORM</u></strong>
				</div>
				<div class="health-medical-self-certification-title-content text-justify">
					<strong><u>NOTE :</u> </strong>
					NEW STARTERS ARE TO PROVIDE THEIR OWN TOOLS, SAFETY HELMETS AND SAFETY FOOTWARE, AND MUST COMPLY WITH THE SAFETY POLICY IN FORCE ON THE SITE ON WHICH THEY ARE WORKING.
					<p style="margin-top:0px;">All fields marked with * are compulsory, otherwise work will not be continued to be offered</p>
				</div>
				<?php
				//select form fields
					$sltqry 	= "SELECT * FROM `nman_new_starter_form` WHERE `pk_nsform_id`=:pk_nsform_id";
					$sltfields= $db->prepare($sltqry);
						$sltfields->bindParam("pk_nsform_id", $_GET["others"]);
					$sltfields->execute();
				//fetch data
					$fieldData = $sltfields->fetch(PDO::FETCH_ASSOC);

				//select field input values
					$sltOutqry = "SELECT * FROM `nman_new_starter_form_course` WHERE fk_nsform_id=:fk_nsform_id";
					$sltOutput= $db->prepare($sltOutqry);
						$sltOutput->bindParam("fk_nsform_id", $_GET["others"]);
					$sltOutput->execute();
				//fetch data
					$courseData = $sltOutput->fetchAll(PDO::FETCH_ASSOC);
				?>
				<table class="table-bordered w-100">
					<thead>
						<tr class="theadColor">
							<th class="w-50">Personal Details</th>
							<th class="w-50"></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Surname*: <span class="result"><?php echo $fieldData["surname"]; ?></span></td>
							<td>
								<?php $result = ($fieldData["emp_type"]=="D" ? "D" : "LOS"); ?>
								Direct / Labour Only Subcontractor*:   <span class="result"><?php echo $result; ?></span>
							</td>
						</tr>
						<tr>
							<td>Forenames*: <span class="result"><?php echo $fieldData["forenames"]; ?></span></td>
							<td></td>
						</tr>
						<tr style="height:50px;">
							<td>
								Address*:<br>
								<span class="result"><?php echo $fieldData["address"]; ?></span>
							</td>
							<td></td>
						</tr>
						<tr>
							<td>Date of birth*: <span class="result"><?php echo date("d/m/Y", strtotime($fieldData["date_of_birth"])); ?></span></td>
							<td>Mobile no*: <span class="result"><?php echo $fieldData["mobile_no"]; ?></span></td>
						</tr>
						<tr>
							<td>Home No: <span class="result"><?php echo $fieldData["home_no"]; ?></span></td>
							<td>Email: <span class="result"><?php echo $fieldData["email"]; ?></span></td>
						</tr>
						<tr>
							<td>UTR No*: <span class="result"><?php echo $fieldData["utr_no"]; ?></span></td>
							<td>NI No*: <span class="result"><?php echo $fieldData["ni_no"]; ?></span></td>
						</tr>
						<tr>
							<td>Next of Kin*: <span class="result"><?php echo $fieldData["next_of_kin"]; ?></span></td>
							<td>Relationship to yourself: <span class="result"><?php echo $fieldData["relationship"]; ?></span></td>
						</tr>
						<tr style="height:50px;">
							<td >
								Next of Kin Address:<br>(If as above leave blank) <br>
								<span class="result"><?php echo $fieldData["next_of_kin_address"]; ?></span>
							</td>
							<td></td>
						</tr>
						<tr >
							<td colspan="2" >Next of Kin contact* : <span class="result"><?php echo $fieldData["next_of_kin_contact_no"]; ?></span></td>
						</tr>
					</tbody>
				</table>
				
								<table class="table-bordered w-100" style="margin-top:5px;">
					<thead>
						<tr class="theadColor">
							<th class="w-50">Bank Details</th>
							<th class="w-50"></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Bank name : <span class="result"><?php echo $fieldData["bank_name"]; ?></span></td>
							<td></td>
						</tr>
						<tr>
							<td>Account Name: <span class="result"><?php echo $fieldData["account_name"]; ?></span></td>
							<td></td>
						</tr>
						<tr>
							<td>Account No: <span class="result"><?php echo $fieldData["account_no"]; ?></span></td>
							<td>Sort Code (6 digits): <span class="result"><?php echo $fieldData["sort_code"]; ?></span></td>
						</tr>
						<tr>
							<td>If a Building Society, A/C Roll No: <span class="result"><?php echo $fieldData["ac_roll_no"]; ?></span></td>
							<td></td>
						</tr>
					</tbody>
				</table>

				<?php if($_GET['worktype']==2): ?>
					<table class="table-bordered w-100" style="margin-top:5px;">
						<thead>
							<tr class="theadColor">
								<th class="w-100">Public Liability Insurance Details</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><span style="font-weight:600px;font-size:32px;">Please provide a copy of your insurance policy*<br>
								If you do not have this insurance it can be obtained via Cardale Assurance – 01293 786295, 
								if you need further assistance please contact our office – 01342 843556</span></td>
							</tr>
						</tbody>
					</table>
					<table class="table-bordered w-100" style="margin-top:5px;">
					<thead>
						<tr class="theadColor">
							<th colspan="6">Training Details</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<?php 
								$yes 	= ($fieldData["cisrs_card"]=="Yes" 	? "Yes" : "<font style='text-decoration: line-through;'>Yes</font>");
								$no 	= ($fieldData["cisrs_card"]=="No" 	? "No" 	: "<font style='text-decoration: line-through;'>No</font>");
							?>
							<td colspan="4">Do you hold a current CSCS Card?</td>
							<td><span class="result"><?php echo $yes; ?></span></td>
							<td><span class="result"><?php echo $no; ?></span></td>
						</tr>
						<tr>
							<td colspan="2">Card no*: <span class="result"><?php echo $fieldData["card_no"]; ?></span></td>
							<td colspan="4">Card Expiry Date: <span class="result"><?php echo date("d/m/Y", strtotime($fieldData["card_expiry_date"])); ?></span></td>
						</tr>
						<tr>
							<td>Grade:</td>
							<td>
								<span class="result">
									<?php echo ($fieldData["grade"]=="Supervisor" ? "Supervisor" : "<font style='text-decoration: line-through;'>Supervisor</font>"); ?>
								</span>
							</td>
							<td>
								<span class="result">
									<?php echo ($fieldData["grade"]=="Skilled Worker"   ? "Skilled Worker"   : "<font style='text-decoration: line-through;'>Skilled Worker</font>"); ?>
								</span>
							</td>
							<td>
								<span class="result">
									<?php echo ($fieldData["grade"]=="Site Operative" ? "Site Operative" : "<font style='text-decoration: line-through;'>Site Operative</font>"); ?>
								</span>
							</td>
							<td colspan="2">
								<span class="result">
									<?php echo ($fieldData["grade"]=="Trainee"    ? "Trainee"    : "<font style='text-decoration: line-through;'>Trainee</font>"); ?>
								</span>
							</td>
						</tr>
						<tr>
							<?php 
								$yes 	= ($fieldData["other_training"]=="Yes" 	? "Yes" : "<font style='text-decoration: line-through;'>Yes</font>");
								$no 	= ($fieldData["other_training"]=="No" 	? "No" 	: "<font style='text-decoration: line-through;'>No</font>");
							?>
							<td colspan="4">Have you any other training? (first aid, forklift, abrasive wheel, banksman, SSSTS etc)</td>
							<td><span class="result"><?php echo $yes; ?></span></td>
							<td><span class="result"><?php echo $no; ?></span></td>
						</tr>
						<tr style="text-align:center;">
							<td colspan="3">Course</td>
							<td colspan="3">Date</td>
						</tr>
						<?php
							if(sizeof($courseData)>0):
								foreach($courseData AS $key=>$course):
						?>
						<tr style="">
							<td colspan="3"><span class="result"><?php echo ($key+1);?>. <?php echo $course["course"]; ?></span></td>
							<td colspan="3"><span class="result"><?php echo ($key+1);?>. <?php echo date("d/m/Y", strtotime($course["course_date"])); ?></span></td>
						</tr>
						<?php
								endforeach;
							else:
						?>
						<tr style="">
							<td colspan="3" class="text-center">-</td>
							<td colspan="3" class="text-center">-</td>
						</tr>
						<?php
							endif;
						?>
					</tbody>
				</table>
				<?php else: ?>
				
				<table class="table-bordered w-100" style="margin-top:5px;">
					<thead>
						<tr class="theadColor">
							<th colspan="6">Training Details</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<?php 
								$yes 	= ($fieldData["cisrs_card"]=="Yes" 	? "<font style='text-decoration: line-through;'>Yes</font>" : "Yes");
								$no 	= ($fieldData["cisrs_card"]=="No" 	? "<font style='text-decoration: line-through;'>No</font>" 	: "No");
							?>
							<td colspan="4">Do you hold a current CISRS Card?</td>
							<td><span class="result"><?php echo $yes; ?></span></td>
							<td><span class="result"><?php echo $no; ?></span></td>
						</tr>
						<tr>
							<td colspan="2">Card no*: <span class="result"><?php echo $fieldData["card_no"]; ?></span></td>
							<td colspan="4">Card Expiry Date: <span class="result"><?php echo date("d/m/Y", strtotime($fieldData["card_expiry_date"])); ?></span></td>
						</tr>
						<tr>
							<td>Grade:</td>
							<td>
								<span class="result">
									<?php echo ($fieldData["grade"]=="Supervisor" ? "Supervisor" : "<font style='text-decoration: line-through;'>Supervisor</font>"); ?>
								</span>
							</td>
							<td>
								<span class="result">
									<?php echo ($fieldData["grade"]=="Advanced"   ? "Advanced"   : "<font style='text-decoration: line-through;'>Advanced</font>"); ?>
								</span>
							</td>
							<td>
								<span class="result">
									<?php echo ($fieldData["grade"]=="Scaffolder" ? "Scaffolder" : "<font style='text-decoration: line-through;'>Scaffolder</font>"); ?>
								</span>
							</td>
							<td>
								<span class="result">
									<?php echo ($fieldData["grade"]=="Trainee"    ? "Trainee"    : "<font style='text-decoration: line-through;'>Trainee</font>"); ?>
								</span>
							</td>
							<td>
								<span class="result">
									<?php echo ($fieldData["grade"]=="Labourer"   ? "Labourer"   : "<font style='text-decoration: line-through;'>Labourer</font>"); ?>
								</span>
							</td>
						</tr>
						<tr>
							<?php 
								$yes 	= ($fieldData["other_training"]=="Yes" 	?  "Yes" : "<font style='text-decoration: line-through;'>Yes</font>");
								$no 	= ($fieldData["other_training"]=="No" 	?  "No"	 : "<font style='text-decoration: line-through;'>No</font>");
							?>
							<td colspan="4">Have you any other training? (first aid, forklift, abrasive wheel, banksman, SSSTS etc)</td>
							<td><span class="result"><?php echo $yes; ?></span></td>
							<td><span class="result"><?php echo $no; ?></span></td>
						</tr>
						<tr style="text-align:center;">
							<td colspan="3">Course</td>
							<td colspan="3">Date</td>
						</tr>
						<?php
							if(sizeof($courseData)>0):
								foreach($courseData AS $key=>$course):
						?>
						<tr style="">
							<td colspan="3"><span class="result"><?php echo ($key+1);?>. <?php echo $course["course"]; ?></span></td>
							<td colspan="3"><span class="result"><?php echo ($key+1);?>. <?php echo date("d/m/Y", strtotime($course["course_date"])); ?></span></td>
						</tr>
						<?php
								endforeach;
							else:
						?>
						<tr style="">
							<td colspan="3" class="text-center">-</td>
							<td colspan="3" class="text-center">-</td>
						</tr>
						<?php
							endif;
						?>
					</tbody>
				</table>

				<table class="table-bordered w-100" style="margin-top:5px;">
					<thead>
						<tr class="theadColor">
							<th class="w-50" colspan="2">-</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Have you been trained on TG20:13 : <span class="result"><?php echo $fieldData["trained_tg"]; ?></span></td>
							<td>Have you been trained on SG4:15 : <span class="result"><?php echo $fieldData["trained_sg"]; ?></span></td>
						</tr>
					</tbody>
				</table>
				<?php 
					endif;
				?>

				<table style="width:100%; margin-top:10px;" class="arialTable">
					<tbody>
						<tr>
							<td class="w-10">Signature</td>
							<td class="w-20">
								<div class="input3"><img src="<?php echo "data:image/png;base64,".$fieldData["signature"]; ?>" width="100px" height="40px" /></div>
							</td>
							<td class="w-10" style="text-align:center;">Date</td>
							<td class="w-20">
								<div class="input2"><?php echo $date; ?></div>
							</td>
						</tr>
					</tbody>
				</table>
				<?php
					if(sizeof($courseData)>3):
				?>
					<div style="page-break-before: always;"></div>
				<?php
					endif;
				?>
				<table class="w-100 table-footer footer-address">
					<thead>
						<tr>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="padding-left: 5px;">Park Chesterfield - Chapel Road - Smallfield - Surrey RH6 9NW</td>
							<td style="text-align:right;padding-right: 5px;">Registered Office:</td>
						</tr>
						<tr>
							<td style="padding-left: 5px;">Tel: 01342 843556 - Fax: 01342 844439</td>
							<td style="text-align:right;padding-right: 5px;">30 Addiscombe Grove</td>
						</tr>
						<tr>
							<td style="padding-left: 5px;">email:info@norman-group.com</td>
							<td style="text-align:right;padding-right: 5px;">Croydon - CR9 5AY</td>
						</tr>
						<tr>
							<td></td>
							<td style="text-align:right;padding-right: 5px;">Registered in England No 2451414</td>
						</tr>
					</tbody>
				</table>
			</div>

			<?php
			//select form fields
				$sltimg 	= "SELECT * FROM `nman_new_starter_images` WHERE `fk_nsform_id`=:fk_nsform_id AND `image_visibility`=1";
				$sltimages	= $db->prepare($sltimg);
					$sltimages->bindParam("fk_nsform_id", $_GET["others"]);
				$sltimages->execute();
			//fetch data
				$imageData = $sltimages->fetchAll(PDO::FETCH_ASSOC);
				if($sltimages->rowCount() > 0):
					echo "<table class='w-100' cellpadding='5'>";
					foreach($imageData as $key=>$img):
						echo "<tr class='text-center'><td><img src='".$img["nsf_image"]."' /></td></tr>";
					endforeach;
					echo "</table>";
				endif;
			?>
		</div>
	</body>
</html>
