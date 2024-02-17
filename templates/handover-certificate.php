<?php
	require '../includes/db.php';
	$db = getDB();
	$selectquery 	= "SELECT * FROM nman_site_master AS SITES INNER JOIN `nman_site_assignment` AS ASSIGN INNER JOIN nman_user_master AS USERS ON USERS.pk_user_id=:pk_user_id AND ASSIGN.fk_site_id=SITES.pk_site_id WHERE SITES.pk_site_id=:pk_site_id";
	$sltstmt= $db->prepare($selectquery);
		$sltstmt->bindParam("pk_user_id", $_GET["userid"]);
		$sltstmt->bindParam("pk_site_id", $_GET["siteid"]);
	$sltstmt->execute();
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
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Norman Scaffolding</title>
		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<!-- themestyle -->
		<?php
			$logo = "";
			if($_GET["worktype"]==1):
				$logo = "images/Norman-Group-logo-4.png";
		?>
		<link href="css/scaffolding/handover-certificate/style-certificate.css" type="text/css" rel="stylesheet" >
		<?php
			else:
				$logo = "images/Norman-Group-logo-5.png";
		?>
		<link href="css/brickwork/handover-certificate/style-certificate.css" type="text/css" rel="stylesheet" >
		<?php
			endif;
		?>
	</head>
	<body>
		<div id="footer">
			<p class="page text-right">Scaffold - Handover Certificate <?php echo $datetime; ?></p>
		</div>
		<div class="container scaffold-border text-justify">
			<!-- page 1 -->
			<div class="scaffolding-certificate">
				<div class="light-solid-border">
					<p>
						<h3 class="title-content text-center" style="margin-bottom: 10px;"><strong>SCAFFOLDING HANDING OVER CERTIFICATE</strong></h3>
					</p>
					<div class="client-date">
						<div class="client">
							<p>Client &nbsp;<span class="dotted-line"><?php echo $siteData["site_owner_name"] ?></span></p>
						</div>
						<div class="date">
							<p>Date &nbsp;<span class="dotted-line"><?php echo $date;?></span></p>
						</div>
					</div>
					<div class="site-time">
						<div class="location">
							<p>Site &nbsp;<span class="dotted-line"><?php echo $siteData["site_name"]; ?></span></p>
						</div>
						<div class="time">
							<p>Time &nbsp;<span class="dotted-line"><?php echo $time;?></span></p>
						</div>
					</div>
					<?php
						$selectoutput = "SELECT * FROM `nman_handover_certificate` WHERE `fk_site_id`=:fk_site_id AND `work_type`=:work_type AND `fk_user_id`=:fk_user_id AND `is_new`=1";
					//execute query
						$sltoutput= $db->prepare($selectoutput);
							$sltoutput->bindParam("fk_site_id"	, $_GET["siteid"]);
							$sltoutput->bindParam("work_type"	, $_GET["worktype"]);
							$sltoutput->bindParam("fk_user_id"	, $_GET["userid"]);
						$sltoutput->execute();
					//fetch data
						$outputData = $sltoutput->fetch(PDO::FETCH_ASSOC);
					?>
					<div class="location-plot">
						<div class="location">
							<p>Location &nbsp;<span class="dotted-line"><?php echo $siteData["site_address"]; ?></span></p>
						</div>
						<div class="plot">
							<p>Plot &nbsp;<span class="dotted-line"><?php echo $outputData["input_3"]; ?></span></p>
							<!--<p>Plot &nbsp;<span class="dotted-line"><?php echo $siteData["site_plot_no"]; ?></span></p>-->
						</div>
					</div>
					<div class="description">
						<p>Description of scaffold being handed over &nbsp;
						<span class="dotted-line">
							<?php echo $outputData["input_1"]; ?>
						</span>
						</p>
					</div>
					<div class="relavent">
						<p>Relevant Drawing Numbers: &nbsp;<span class="dotted-line"><?php echo $outputData["input_2"]; ?></span></p>
					</div>
					<div class="scaffold-description text-justify">
						<p>Scaffolding as described above has been completed in accordance with client requirements and in accordance with
							all relevant standards. It is structurally sound and should only be used and loaded in accordance with the following:
						</p>
						<ul class="use-loading">
							<li>
								Use only for: &nbsp;
								<span class="dotted-line">
									<strong>
									<?php
										$userarray = array(1=>"Light Duty", 2=>"General Purpose", 3=>"Heavy Duty");
										echo $userarray[$outputData["input_4"]]; 
									?>
									</strong>
								</span>
							</li>
							<li>
								Number of Working Lifts:
									&nbsp;<span class="dotted-line"><?php echo $outputData["input_5"]; ?></span>
							</li>
							<li>
								With distributed load of &nbsp;<span class="dotted-line"><?php echo $outputData["input_6"]; ?></span>&nbsp;(Kn/m²)
							</li>
						</ul>
						<div class="number-of-ties">
							<p>Type of Ties (if applicable): &nbsp;<span class="dotted-line"><?php echo $outputData["input_7"]; ?></span></p>
						</div>
						<div class="total-nuber-of-ties">
							<p>Total number of ties: &nbsp;<span class="dotted-line"><?php echo $outputData["input_8"]; ?></span></p>
						</div>
						<p>Number of ties tested: &nbsp;
							<span class="dotted-line">
								<?php echo $outputData["input_9"]; ?>
							</span>  (test a minimum of 3 anchors and at least 5% (1 in 20) of total job)
						</p>
						<p>
							The detailed requirements of the Regulations with regards to guardrails, working platforms, toeboards, bracing and
							ties have been complied with. &nbsp;<strong class="dotted-line"><?php echo ($outputData["input_10"]==0 ? "Yes" : "No"); ?></strong>
						</p>
						<p>
							<?php 
								$result = ($outputData["input_11"]=="Yes" ? "has" : "has not"); 
							?>
							The scaffold <span class="dotted-line"><?php echo $result; ?></span> been designed to take monoflex (or other windsails) &nbsp;
						</p>
						<p>Signed on behalf of Gatwick scaffolding Ltd:</p>
						<div class="signed-name">
							<p>Name: &nbsp;<span class="dotted-line"><?php echo $siteData["user_first_name"]." ".$siteData["user_last_name"]; ?></span></p>
						</div>
						<div class="signed-sign">
							<p>Sign: &nbsp;<span class="dotted-line strike-line"><img src="<?php echo "data:image/png;base64,".$siteData["user_signature"]; ?>" width="100px" height="30px" /></span></p>
						</div>
						<p>Signed on behalf of Client:</p>
						<div class="signed-name">
							<p>Name: &nbsp;<span class="dotted-line"><?php echo $outputData["input_12"]; ?></span></p>
						</div>
						<div class="signed-sign">
							<p>Sign: &nbsp;<span class="dotted-line strike-line"><img src="<?php echo "data:image/png;base64,".$outputData["input_13"]; ?>" width="100px" height="30px" /></span></p>
						</div>
						<div class="scaffold-inspection">
							<p>INSPECTION OF SCAFFOLDING:-</p>
							<p>The instructions listed below are the responsibility of the USER and must be recorded in the scaffold register kept on
								site. A competent person must inspect the scaffold at the following periods:- 
							</p>
							<ol class="scaffold-inspection-details" style="list-style:none;">
								<li><span style="font-size:10px;">1. </span>&nbsp; Before first use</li>
								<li><span style="font-size:10px;">2. </span>&nbsp; After any substantial addition, dismantle or other alterations</li>
								<li><span style="font-size:10px;">3. </span>&nbsp; After any event likely to have affected its strength or stability</li>
								<li><span style="font-size:10px;">4. </span>&nbsp; At regular intervals not exceeding seven days since last inspection</li>
							</ol>
							<p>These inspections must be carried out and recorded under Regulation 12 of the Work at Height Regulations 2005, to
								ensure that the scaffolding continues to comply with the regulation
							</p>
							<p>It is also the responsibility of every employer under Regulation 3 to ensure that the requirements of the Regulation,
								which apply to his own employees are complied with.
							</p>
							<p>The Work at Height Regulations 2005 places the duty to ensure safety in the use of scaffolding on the employer or
								person in control of the construction work. The user should not carry out any alterations, which affect the strength,
								stability or safety of the scaffold.
							</p>
							<p>Copy of this report must be kept for at least 3 months after completion of the construction work.</p>
							<div class="empty-row-3"></div>
						</div>
					</div>
					<div class="bottom-img">
						<img class="botimg" src="<?php echo $logo; ?>" alt="Norman" width="300px" />
					</div>
					<div class="note">
						<p>NOTE: IMPORTANT SAFETY INFORMATION ON THE REVERSE OF THIS CERTIFICATE</p>
					</div>
				</div>
			</div>
			<div class="user-responsibility-alwys-never">
				<div class="light-solid-border">
					<p><h4><strong>USERS RESPONSIBILITIES</strong></h4></p>
					<p><h4><strong>Scaffolding is safe if you comply with the Regulations and:-</strong></h4></p>
					<p><h4><strong>ALWAYS</strong></h4></p>
					<ul class="Always">
						<li>
							<p>Ensure the scaffold specification meets your requirements</p>
						</li>
						<li>
							<p>Ensure there is a safe method of access and egress to and from the work place</p>
						</li>
						<li>
							<p>Check use and loading overleaf is what you require </p>
						</li>
						<li>
							<p>Inspect scaffold before signing and accepting handing-over certificate</p>
						</li>
						<li>
							<p>Carry out and record 7 day inspections</p>
						</li>
						<li>
							<p>Use suitable warning signs for incomplete or unsafe scaffolding</p>
						</li>
						<li>
							<p>Record weekly inspections in a report of inspection register</p>
						</li>
						<li>
							<p>Spread the load – instruct users on maximum loading</p>
						</li>
						<li>
							<p>Use suitable loading towers (require a design)</p>
						</li>
						<li>
							<p>Rectify immediately any faults identified in the scaffold</p>
						</li>
						<li>
							<p>Report damage of scaffold material (erected or not) to Scaffold Company</p>
						</li>
					</ul>
					<p><h4><strong>Scaffolding is very dangerous if abused so:-</strong></h4></p>
					<p><h4><strong>NEVER</strong></h4></p>
					<ul class="Always">
						<li>
							<p>Remove ties</p>
						</li>
						<li>
							<p>Remove, adjust, alter or modify any part of the scaffold </p>
						</li>
						<li>
							<p>Overload the scaffold </p>
						</li>
						<li>
							<p>Allow digging under or near the scaffold</p>
						</li>
						<li>
							<p>Add sheeting or netting unless the scaffold has been designed for it</p>
						</li>
						<li>
							<p>Forklift loads directly onto access scaffolds</p>
						</li>
					</ul>
					<div class="empty-row-1"></div>
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
					$sltimages->bindParam("img_key"		, $outputData["img_key"]);
				$sltimages->execute();
			//fetch data
				$imageData = $sltimages->fetchAll(PDO::FETCH_ASSOC);
				if($sltimages->rowCount() > 0):
					echo "<table class='w-100'>";
					foreach($imageData as $key=>$img):
						echo "<tr class='text-center'><td style='padding:10px;'><img src='".$img["site_image"]."' /></td></tr>";
					endforeach;
					echo "</table>";
				endif;
			?>
		</div>
	</body>
</html>