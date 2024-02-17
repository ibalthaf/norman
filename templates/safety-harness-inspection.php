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
	$date 		= $dateArray[0];
	$time 		= $dateArray[1]." ".$dateArray[2];
//logo
	$logo = "";
	if($_GET["worktype"]==1):
		$logo = "images/Norman-Group-logo-3.png";
	else:
		$logo = "images/Norman-Group-logo-6.png";
	endif;
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Norman Scaffolding</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/scaffolding/safety-harness-inspection/style.css" type="text/css" rel="stylesheet">
	</head>
	<body>
		<div id="footer">
			<p class="page text-right">HARNESS & FALL ARREST REGISTER <?php echo $datetime; ?></p>
		</div>
		<div class="container-fluid">
			<div class="Manger-supervisor-site-report">
				<?php
					$sltdata 	= "select * from `nman_safety_harness_inspection_forms` WHERE `work_type`=:work_type AND `shiform_created_on` > DATE_SUB(now(), INTERVAL 3 MONTH)";
				//execute query
					$sltoutput	= $db->prepare($sltdata);
						$sltoutput->bindParam("work_type", $_GET["worktype"]);
					$sltoutput->execute();
				//fetch data
					$fetchData 	= $sltoutput->fetchAll(PDO::FETCH_ASSOC);
				?>
				<table class="table table-bordered w-100"  >
					<thead>
						<tr class="table-1">
							<th colspan="6" rowspan="2">
								<div class="straight-new"> <span class="dark">HARNESS & FALL ARREST REGISTER</span></div>
							</th>
							<th colspan="6" rowspan="2">
								<div class="straight-new">
									<div class="icon-gatwick-image"><img src="<?php echo $logo; ?>" /></div>
								</div>
							</th>
							<th colspan="17">
								<div class="straight-new text-justify">
									Place Y or N in relevant box,if Y in any box the equipment should be removed from service and office notification of faults
								</div>
							</th>
							<th>
								<div class="straight-new">
								</div>
							</th>
						</tr>
						<tr class="table-1">
							<th colspan="6">Hardware</th>
							<th colspan="7">Webbing</th>
							<th colspan="2">Stitching </th>
							<th colspan="2">Labels</th>
							<th></th>
						</tr>
						<tr class="table-1">
							<th class="w-2">
								<div class="straight"> ID NO</div>
							</th>
							<th class="w-3">
								<div class="straight"> Make</div>
							</th>
							<th class="w-2">
								<div class="straight"> Model</div>
							</th>
							<th class="w-2">
								<div class="straight"> Serial</div>
							</th>
							<th class="w-4">
								<div class="straight"> Date of Manufacture</div>
							</th>
							<th class="w-4">
								<div class="straight"> Purchase Date</div>
							</th>
							<th class="w-4">
								<div class="straight"> Owner</div>
							</th>
							<th class="w-4">
								<div class="straight"> Inspection(in sp.)Frequency</div>
							</th>
							<th class="w-5">
								<div class="straight"> Location</div>
							</th>
							<th class="w-5">
								<div class="straight"> State</div>
							</th>
							<th class="w-4">
								<div class="straight"> Insp. on</div>
							</th>
							<th class="w-4">
								<div class="straight"> Insp. by</div>
							</th>
							<th class="w-1">
								<div class="rotate">
									<span>
										<div>Burrs</div>
									</span>
								</div>
							</th>
							<th class="w-1">
								<div class="rotate">
									<span>
										<div>Corrosion</div>
									</span>
								</div>
							</th>
							<th class="w-1">
								<div class="rotate">
									<span>
										<div>Cracks</div>
									</span>
								</div>
							</th>
							<th class="w-1">
								<div class="rotate">
									<span>
										<div>Damaged</div>
									</span>
								</div>
							</th>
							<th class="w-1">
								<div class="rotate">
									<span>
										<div>Distorted</div>
									</span>
								</div>
							</th>
							<th class="w-1">
								<div class="rotate">
									<span>
										<div>Sharp Edges</div>
									</span>
								</div>
							</th>
							<th class="w-1">
								<div class="rotate">
									<span>
										<div>Abrasions</div>
									</span>
								</div>
							</th>
							<th class="w-1">
								<div class="rotate">
									<span>
										<div>Burns</div>
									</span>
								</div>
							</th>
							<th class="w-1">
								<div class="rotate">
									<span>
										<div>Cuts</div>
									</span>
								</div>
							</th>
							<th class="w-1">
								<div class="rotate">
									<span>
										<div>Discoloration</div>
									</span>
								</div>
							</th>
							<th class="w-1">
								<div class="rotate">
									<span>
										<div>Excessive Soliling</div>
									</span>
								</div>
							</th>
							<th class="w-1">
								<div class="rotate">
									<span>
										<div>Frays</div>
									</span>
								</div>
							</th>
							<th class="w-1">
								<div class="rotate">
									<span>
										<div>Tears</div>
									</span>
								</div>
							</th>
							<th class="w-1">
								<div class="rotate">
									<span>
										<div>Cut Stitches</div>
									</span>
								</div>
							</th>
							<th class="w-1">
								<div class="rotate">
									<span>
										<div>Pulled Stitches</div>
									</span>
								</div>
							</th>
							<th class="w-1">
								<div class="rotate">
									<span>
										<div>Not Legible</div>
									</span>
								</div>
							</th>
							<th class="w-1">
								<div class="rotate">
									<span>
										<div>Not Secure</div>
									</span>
								</div>
							</th>
							<th class="w-6">
								<div class="straight">Next Insp.due</div>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$status = true;
							if($sltoutput->rowCount() > 0):
								foreach($fetchData AS $key=>$values):
						?>
						<tr>
							<td><?php echo ($key+1); ?></td>
							<td><?php echo $values["make"]; ?></td>
							<td><?php echo $values["model"]; ?></td>
							<td><?php echo $values["serial"]; ?></td>
							<td><?php echo date("d-M-Y", strtotime($values["date_of_manufacture"])); ?></td>
							<td><?php echo date("d-M-Y", strtotime($values["purchase_date"])); ?></td>
							<td><?php echo $values["owner"]; ?></td>
							<td><?php echo $values["inspection_frequency"]; ?></td>
							<td><?php echo $values["location"]; ?></td>
							<td><?php echo $values["state"]; ?></td>
							<td><?php echo date("d-M-Y", strtotime($values["inspected_on"])); ?></td>
							<td><?php echo $values["inspected_by"]; ?></td>
							<td><?php echo $values["burrs"]; ?></td>
							<td><?php echo $values["corrosion"]; ?></td>
							<td><?php echo $values["cracks"]; ?></td>
							<td><?php echo $values["damaged"]; ?></td>
							<td><?php echo $values["distorted"]; ?></td>
							<td><?php echo $values["sharp_edges"]; ?></td>
							<td><?php echo $values["abrasions"]; ?></td>
							<td><?php echo $values["burns"]; ?></td>
							<td><?php echo $values["cuts"]; ?></td>
							<td><?php echo $values["discolouraction"]; ?></td>
							<td><?php echo $values["excessive_soiling"]; ?></td>
							<td><?php echo $values["frays"]; ?></td>
							<td><?php echo $values["tears"]; ?></td>
							<td><?php echo $values["cut_stiches"]; ?></td>
							<td><?php echo $values["pulled_stiches"]; ?></td>
							<td><?php echo $values["not_legible"]; ?></td>
							<td><?php echo $values["not_secure"]; ?></td>
							<td><?php echo date("d-M-Y", strtotime($values["next_inspection_due"])); ?></td>
						</tr>
						<?php
								endforeach;
							else:
								$status = false;
						?>
						<tr>
							<td></td><td></td><td></td><td></td>
							<td></td><td></td><td></td><td></td>
							<td></td><td></td><td></td><td></td>
							<td></td><td></td><td></td><td></td>
							<td></td><td></td><td></td><td></td>
							<td></td><td></td><td></td><td></td>
							<td></td><td></td><td></td><td></td>
							<td></td><td></td>
						</tr>
						<?php
							endif;
						?>
					</tbody>
				</table>
				<?php
					if($status==false):
				?>
					<div class="w-100 no-record-found text-center">No records found</div>
				<?php
					endif;
				?>
			</div>
		</div>
	</body>
</html>