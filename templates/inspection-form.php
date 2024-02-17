<?php
require '../includes/db.php';
$db = getDB();
$selectquery 	= "SELECT * FROM nman_site_master AS SITES INNER JOIN `nman_site_assignment` AS ASSIGN INNER JOIN nman_user_master AS USERS ON USERS.pk_user_id=:pk_user_id AND ASSIGN.fk_site_id=SITES.pk_site_id WHERE SITES.pk_site_id=:pk_site_id";
$sltstmt = $db->prepare($selectquery);
$sltstmt->bindParam("pk_site_id", $_GET["siteid"]);
$sltstmt->bindParam("pk_user_id", $_GET["userid"]);
$sltstmt->execute();
//fetch data
$siteData = $sltstmt->fetch(PDO::FETCH_ASSOC);
$datetime	= date("d/m/Y h:i A");
$dateArray  = explode(" ", $datetime);
$date = $dateArray[0];
$time = $dateArray[1] . " " . $dateArray[2];
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
	if ($_GET["worktype"] == 1) :
		$logo = "images/Norman-Group-logo-4.png";
	?>
		<link href="css/scaffolding/inspection-form/style.css" type="text/css" rel="stylesheet">
	<?php
	else :
		$logo = "images/Norman-Group-logo-5.png";
	?>
		<link href="css/brickwork/inspection-form/style.css" type="text/css" rel="stylesheet">
	<?php
	endif;
	?>
</head>

<body>
	<div id="footer">
		<p class="page text-right">SCAFFOLDING INSPECTION FORM <?php echo $datetime; ?></p>
	</div>
	<div class="container">
		<div class="new-starter-form">
			<div class="new-starter-form-title-table">
				<table class="table table-bordered1 w-100">
					<tbody>
						<tr>
							<div class="icon-gatwick-image"><img src="<?php echo $logo; ?>" alt="" width="300px"></div>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="managerTitle">
				<h4 style="color:#fff;"><strong>SCAFFOLD INSPECTION FORM</strong></h4>
			</div>
			<table style="width:100%" class="arialTable">
				<tbody>
					<tr>
						<td class="w-20">Inspection Carried out by</td>
						<td class="w-40">
							<div class="input2"><?php echo $siteData["user_first_name"] . " " . $siteData["user_last_name"]; ?></div>
						</td>
						<td class="w-10" style="text-align:center;">Client</td>
						<td class="w-30">
							<div class="input2"><?php echo $siteData["site_owner_name"]; ?></div>
						</td>
					</tr>
				</tbody>
			</table>
			<table style="width:100%" class="margin arialTable">
				<tbody>
					<tr>
						<td class="w-20" style=" font-size: 13px;"><span style="font-size:14px;">Address of the site</td>
						<td class="w-30">
							<div class="input2"><?php echo $siteData["site_address"] ?></div>
						</td>
						<td class="w-10">Date</td>
						<td class="w-15">
							<div class="input2"><?php echo $date; ?></div>
						</td>
						<td class="w-10">Time</td>
						<td class="w-15">
							<div class="input2"><?php echo $time; ?></div>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
			$sltdata 	= "SELECT * FROM `nman_inspection_form` WHERE `fk_site_id`=:fk_site_id AND `fk_user_id`=:fk_user_id AND `is_new`=1 AND `work_type`=:work_type AND `iform_visibility`=1";
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
						<th class="w-10 text-center" style="border-right: 1px solid;">
							<label>Description of place of work (or part inspected area)</label>
						</th>
						<th class="w-30 text-center" style="border-right: 1px solid;">
							<label>Details of any matter identified giving rise to the risk of health and safety of any person</label>
						</th>
						<th class="w-30 text-center" style="border-right: 1px solid;">
							<label>Details of any action taken as a result of any identified</label>
						</th>
						<th class="w-30 text-center" style="border-right: none;">
							<label>Details of any further action considered necessary</label>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$imgkey = "";
					foreach ($fetchData as $key => $rows) :
						$imgkey = $rows["img_key"];
					?>
						<tr>
							<td>
								<div class="customtd"><?php echo $rows["input_1"]; ?></div>
							</td>
							<td>
								<div class="customtd"><?php echo $rows["input_2"]; ?></div>
							</td>
							<td>
								<div class="customtd"><?php echo $rows["input_3"]; ?></div>
							</td>
							<td>
								<div class="customtd"><?php echo $rows["input_4"]; ?></div>
							</td>
						</tr>
					<?php
					endforeach;
					?>
				</tbody>
			</table>
			<div class="w-100 mainBorder" style="margin-top: 10px;">
				<div class="innerColor header-title">
					<h5 style="color:#fff;"><strong>SIGNATURE OF THE INSPECTOR</h5>
				</div>
				<div class="inputBack">
					<table class="w-100">
						<tbody>
							<tr>
								<td class="w-10"><strong>Signature</strong></td>
								<td>
									<div class="input1 text-center" style="background:#fff;">
										<img src="<?php echo "data:image/png;base64," . $siteData["user_signature"]; ?>" width="100px" height="40px" />
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- image -->
		<?php
		//select form fields
		$sltimg 	= "SELECT * FROM `nman_site_images` WHERE `fk_site_id`=:fk_site_id AND `fk_form_id`=:fk_form_id AND `work_type`=:work_type AND `img_key`=:img_key AND `image_visibility`=1";
		$sltimages	= $db->prepare($sltimg);
		$sltimages->bindParam("fk_site_id", $_GET["siteid"]);
		$sltimages->bindParam("fk_form_id", $_GET["formid"]);
		$sltimages->bindParam("work_type", $_GET["worktype"]);
		$sltimages->bindParam("img_key", $imgkey);
		$sltimages->execute();
		//fetch data
		$imageData = $sltimages->fetchAll(PDO::FETCH_ASSOC);
		if ($sltimages->rowCount() > 0) :
			echo "<div style='width:100%; text-align:center' class='w-100'>";
			foreach ($imageData as $key => $img) :
				echo "<div class='text-center' style='margin-top:20px;'><img  style='margin-top:10px;' src='" . $img["site_image"] . "' /></div>";
			endforeach;
			echo "</div>";
		endif;
		?>
	</div>
</body>

</html>