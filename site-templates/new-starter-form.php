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
		<link href="css/report-templates/new-starter-form.css" type="text/css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<div class="header w-100">
				<div class="logo_div w-100">
					<img class="logo" src="images/Norman-Group-logo-4.jpg">
				</div>
			</div>
			<div class="FormTitle">
				<h2><strong>NEW STARTER FORM&nbsp;&nbsp;&nbsp;</strong></h2>
			</div>
			<div class="handover_form">
				<?php
					$site_visibility = 1;
				//check date
					if(empty($_GET["to"])):
					//select data
						$sql	= "SELECT @count:=@count+1 AS sno, `nman_new_starter_form`.*, DATE_FORMAT(`nman_new_starter_form`.date_of_birth, '%d/%m/%Y') AS dob, DATE_FORMAT(`nman_new_starter_form`.date, '%d/%m/%Y') AS createdon, DATE_FORMAT(`nman_new_starter_form`.card_expiry_date, '%d/%m/%Y') AS expiredon FROM (SELECT @count:=0) AS serialno, `nman_new_starter_form`";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("site_visibility", $site_visibility);
						$stmt->execute();
					else:
					//select data
						$sql	= "SELECT @count:=@count+1 AS sno, `nman_new_starter_form`.*, DATE_FORMAT(`nman_new_starter_form`.date_of_birth, '%d/%m/%Y') AS dob, DATE_FORMAT(`nman_new_starter_form`.date, '%d/%m/%Y') AS createdon, DATE_FORMAT(`nman_new_starter_form`.card_expiry_date, '%d/%m/%Y') AS expiredon FROM (SELECT @count:=0) AS serialno, `nman_new_starter_form` WHERE DATE(`nman_new_starter_form`.date)>=:startdate AND DATE(`nman_new_starter_form`.date)<=:enddate";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($_GET["from"])));
							$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($_GET["to"])));
							$stmt->bindParam("site_visibility"	, $site_visibility);
						$stmt->execute();
					endif;
				//fetch data
					$fetchData 	= $stmt->fetchAll(PDO::FETCH_ASSOC);
				?>
				<table class="w-100" cellpadding="3px">
					<thead class="height">
						<tr>
							<th class="w-3 cls_th text-center">No</th>
							<th class="w-8 cls_th text-center">Surename</th>
							<th class="w-8 cls_th text-center">Forename</th>
							<th class="w-6 cls_th text-center">DOB</th>
							<th class="w-10 cls_th text-center">Address</th>
							<th class="w-8 cls_th text-center">Mobile No</th>
							<th class="w-5 cls_th text-center">Home No</th>
							<th class="w-10 cls_th text-center">Email</th>
							<th class="w-5 cls_th text-center">UTR</th>
							<th class="w-5 cls_th text-center">NI No</th>
							<th class="w-5 cls_th text-center">Next Of Kin</th>
							<th class="w-6 cls_th text-center">Relationship</th>
							<th class="w-5 cls_th text-center">Next of Kin No.</th>
							<th class="w-5 cls_th text-center">CISRS  Card No</th>
							<th class="w-5 cls_th text-center">CISRS Grade</th>
							<th class="w-6 cls_th text-center">CISRS Expiry Date</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach($fetchData AS $key=>$rows):
						?>
						<tr>
							<td class="text-center"><?php echo ($key+1) ?></td>
							<td class="text-center"><?php echo $rows["surname"]; ?></td>
							<td class="text-center"><?php echo $rows["forenames"]; ?></td>
							<td class="text-center"><?php echo $rows["dob"]; ?></td>
							<td class="text-center"><?php echo $rows["address"]; ?></td>
							<td class="text-center"><?php echo $rows["mobile_no"]; ?></td>
							<td class="text-center"><?php echo $rows["home_no"]; ?></td>
							<td class="text-center"><?php echo $rows["email"]; ?></td>
							<td class="text-center"><?php echo $rows["utr_no"]; ?></td>
							<td class="text-center"><?php echo $rows["ni_no"]; ?></td>
							<td class="text-center"><?php echo $rows["next_of_kin"]; ?></td>
							<td class="text-center"><?php echo $rows["relationship"]; ?></td>
							<td class="text-center"><?php echo $rows["next_of_kin_contact_no"]; ?></td>
							<td class="text-center"><?php echo $rows["card_no"]; ?></td>
							<td class="text-center"><?php echo $rows["grade"]; ?></td>
							<td class="text-center"><?php echo $rows["expiredon"]; ?></td>
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