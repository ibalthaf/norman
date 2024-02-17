<?php
											/******************************************************************
												project description:

												Project Name 	: Norman Group
												Created By		: Veerakumar (PDF Generation & PHP) & Rabert (IOS)
												Started On		: 20-12-2016
												Backend 		: PHP, SLIM-3 (framework)
												Database		: MySQL
												FrontEnd		: IOS
											******************************************************************/
//set default time zone
	date_default_timezone_set('Europe/London');
//required files
	require 'includes/db.php';
	require 'vendor/autoload.php';
	require 'api/PHPMailer/PHPMailerAutoload.php';
	require 'api/dompdf/autoload.inc.php';

//reference the Dompdf namespace
	use Dompdf\Dompdf;
	use Dompdf\Options;

//slim app initialization
	$app = new \Slim\App();

//***********POST method***********//
//user signup and login
	$app->POST('/customer_login'			, 'customer_login');
	$app->POST('/reset_password_otp'		, 'reset_password_otp');
	$app->POST('/reset_password'			, 'reset_password');
	$app->POST('/get_login_info'			, 'get_login_info');

	$app->POST('/get_site_info'				, 'get_site_info');

	$app->POST('/get_handover_report'		, 'get_handover_report');
	$app->POST('/get_method_stmt_report'	, 'get_method_stmt_report');
	$app->POST('/get_inspection_report'		, 'get_inspection_report');
	$app->POST('/get_toolboxtalk_report'	, 'get_toolboxtalk_report');
	$app->POST('/get_work_equipment_report'	, 'get_work_equipment_report');
	$app->POST('/get_new_starter_report'	, 'get_new_starter_report');
	$app->POST('/get_site_supervisor_report', 'get_site_supervisor_report');
	$app->POST('/get_day_worksheet_report'	, 'get_day_worksheet_report');
	$app->POST('/get_request_for_information_report', 'get_request_for_information_report');

	$app->POST('/get_assigned_forms'		, 'get_assigned_forms');

	$app->POST('/prepare_pdf_file'			, 'prepare_pdf_file');

//***********GET method***********//
	$app->GET('/get_pdf_forms/{formtype}'	, 'get_pdf_forms');
	$app->run();

//customer login
	function customer_login($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
		isset($reqData->txtUserName)		? $reqData->txtUserName			: $error[]	= "User Email";
		isset($reqData->txtPassword)		? $reqData->txtPassword			: $error[]	= "User Password";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
			$site_email		= stripslashes($reqData->txtUserName);
			$site_password	= stripslashes(md5(md5($reqData->txtPassword)));
			try {
				$db	  = getDB();
			//select data
				$sql	= "SELECT * FROM `nman_site_master` WHERE `site_email`=:site_email AND `site_password`=:site_password";
			//prepare statement
				$stmt = $db->prepare($sql);
					$stmt->bindParam("site_email"	, $site_email);
					$stmt->bindParam("site_password", $site_password);
				$stmt->execute();
			//fetch data
				$fetchData	= $stmt->fetch(PDO::FETCH_OBJ);
			//variable declaration
				$resultDetail= new stdClass();
			//check condition
				if($stmt->rowCount() > 0):
				//check user active/inactive
					if($fetchData->site_visibility == 1):
					//generate mobile token
						$mobile_token	= generate_mobile_token($fetchData->site_email);
					//fetch data
						$resultDetail	= $fetchData;
						$resultDetail->mobile_token	= $mobile_token;
						$status			= "true";
						$statuscode		= "200";
					//update mobile token
						$updsql = "UPDATE `nman_site_master` SET `mobile_token`=:mobile_token WHERE `pk_site_id`=:pk_site_id";
					//prepare statement
						$updstmt = $db->prepare($updsql);
							$updstmt->bindParam("mobile_token"	, $mobile_token);
							$updstmt->bindParam("pk_site_id" 	, $fetchData->pk_site_id);
						$updstmt->execute();
					elseif($fetchData->site_visibility 	== 0):
						$resultDetail->message	= "For security reasons your account is temporarily locked. Please contact admin";
					endif;
				else:
					$resultDetail->message	= "Invalid email/password";
				endif;
			}
			catch(PDOException $e) {
				$resultDetail->message= $e->getMessage();
			}
		//clear database
			$db	= null;
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultDetail);
		endif;
	//return result
		echo json_encode($response);
	}

//send reset password OTP
	function reset_password_otp($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->txtEmail)?$reqData->txtEmail:$error[]			= "User Email";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//try block
			try {
			//database initialization
				$db 		= getDB();
				$site_otp 	= generateOTP();
			//SQL query to check email and mobile number exist
				$selectsql 	= "SELECT * FROM `nman_site_master` WHERE `site_email`=:site_email";
			//prepare statement
				$sltstmt 	= $db->prepare($selectsql);
					$sltstmt->bindParam("site_email", $reqData->txtEmail);
				$sltstmt->execute();
			//check email/phone duplication
				if($sltstmt->rowCount() > 0):
				//SQL query insert data
					$sql = "UPDATE `nman_site_master` SET `site_otp`=:site_otp WHERE `site_email`=:site_email";
				//prepare statement
					$stmt 	= $db->prepare($sql);
						$stmt->bindParam("site_otp"		, $site_otp);
						$stmt->bindParam("site_email"	, $reqData->txtEmail);
					$stmt->execute();
				//fetch last inserted data
					$status					= "true";
					$statuscode				= "200";
					$resultData->message	= "Success! Reset password link has been sent to your registered Email Address.";
				//prepare email
					$emailData=new stdClass();
					$emailData->Subject		= "Norman Group - Reset Password";
					$emailData->email		= $reqData->txtEmail;
					$emailData->site_otp	= $site_otp;
					$username				= $reqData->site_owner_name;
					$resetpasswordlink 		= ConstantURL()."index.php/reset_cust_password/". $site_otp;
				//get email contents
					$mailcontent			= file_get_contents('email_templates/forgot-password.html');
					$emailData->body		= str_replace('{{reset-link}}', $resetpasswordlink, $mailcontent);
				//callback send email function
					$mailCheck	= send_email($emailData);
				else:
					$resultData->message= "Invalid Email Address!";
				endif;
			//clear database initialization
				$db = null;
			}
			catch(PDOException $e) {
				$resultData->message= $e->getMessage();
			}
		//prepare return string
			$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
		endif;
	//return result
		echo json_encode($response);
	}

//send reset password
	function reset_password($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->txtConfirmPassword)	? $reqData->txtConfirmPassword	: $error[] = "User Password";
		isset($reqData->txtSiteId)			? $reqData->txtSiteId			: $error[] = "User ID";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//try block
			try {
			//database initialization
				$db 		= getDB();
			//SQL query insert data
				$updsql = "UPDATE `nman_site_master` SET `site_password`=:site_password WHERE `pk_site_id`=:pk_site_id";
			//prepare statement
				$updstmt 	= $db->prepare($updsql);
					$updstmt->bindParam("site_password"	, md5(md5($reqData->txtConfirmPassword)));
					$updstmt->bindParam("pk_site_id"	, $reqData->txtSiteId);
				$updstmt->execute();
			//fetch last inserted data
				$status					= "true";
				$statuscode				= "200";
				$resultData->message	= "Password updated successfully";
			//clear database initialization
				$db = null;
			}
			catch(PDOException $e) {
				$resultData->message= $e->getMessage();
			}
		//prepare return string
			$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
		endif;
	//return result
		echo json_encode($response);
	}

//get user imformation
	function get_login_info($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->siteid)		? $reqData->siteid		: $error[]	= "Customer Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Session Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->siteid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
					$db	  = getDB();
				//select data
					$sql	= "SELECT * FROM `nman_site_master` WHERE `pk_site_id`=:pk_site_id";
				//prepare statement
					$stmt = $db->prepare($sql);
						$stmt->bindParam("pk_site_id", $reqData->siteid);
					$stmt->execute();
				//fetch data
					$fetchData	= $stmt->fetch(PDO::FETCH_OBJ);
				//check condition
					if($stmt->rowCount() > 0):
						$resultDetail 	= $fetchData;
						$status			= "true";
						$statuscode		= "200";
					else:
						$resultDetail->message	= "Records Not Found";
					endif;
				}
				catch(PDOException $e) {
					$resultDetail->message= $e->getMessage();
				}
			endif;
		//clear database
			$db	= null;
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultDetail);
		endif;
	//return result
		echo json_encode($response);
	}

//get site imformation
	function get_site_info($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->siteid)	? $reqData->siteid	: $error[]	= "Site Name";
		isset($reqData->userid)	? $reqData->userid	: $error[]	= "User Name";
		isset($reqData->session)? $reqData->session	: $error[]	= "Session Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
					$db	  = getDB();
				//select data
					$sql	= "SELECT SITE.`pk_site_id` AS siteid, SITE.`site_name` AS sitename, SITE.`site_owner_name` AS ownername, SITE.`site_email` AS siteemail, SITE.`site_phone` AS sitephone, SITE.`site_address` AS siteaddress, SITE.`site_plot_no` AS plotno, SITE.`site_signature` AS signature, SITE.`site_manager` AS sitemanager, SITE.`site_job_number` AS jobnumber, SITE.`site_visibility` AS status, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS createdby  FROM `nman_site_master` AS SITE INNER JOIN `nman_user_master` AS USERS ON USERS.pk_user_id=SITE.fk_user_id WHERE SITE.`pk_site_id`=:pk_site_id";
				//prepare statement
					$stmt = $db->prepare($sql);
						$stmt->bindParam("pk_site_id", $reqData->siteid);
					$stmt->execute();
				//fetch data
					$fetchData	= $stmt->fetch(PDO::FETCH_OBJ);
				//check condition
					if($stmt->rowCount() > 0):
						$resultDetail 	= $fetchData;
						$status			= "true";
						$statuscode		= "200";
					else:
						$resultDetail->message	= "Records Not Found";
					endif;
				}
				catch(PDOException $e) {
					$resultDetail->message= $e->getMessage();
				}
			endif;
		//clear database
			$db	= null;
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultDetail);
		endif;
	//return result
		echo json_encode($response);
	}

//get handover report
	function get_handover_report($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->siteid)		? $reqData->siteid		: $error[]	= "Customer Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Mobile Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->siteid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//database init
					$db	  = getDB();
				//check date
					if(empty($reqData->txtDateTo)):
					//select data
						$sql	= "SELECT SITE.`site_name` AS sitename, SITE.`site_owner_name` AS ownername, SITE.`site_plot_no` AS plotno, REPORT.*, DATE_FORMAT(REPORT.hform_createdon, '%d/%m/%Y') AS createdon, PDF.`pdf_name` AS pdfname FROM `nman_handover_certificate` AS REPORT INNER JOIN `nman_site_master` AS SITE ON REPORT.`fk_site_id`=SITE.`pk_site_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITE.`pk_site_id`=:pk_site_id AND SITE.`site_visibility`=:site_visibility";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("pk_site_id"		, $reqData->siteid);
							$stmt->bindParam("site_visibility"	, $reqData->visibility);
						$stmt->execute();
					else:
					//select data
						$sql	= "SELECT SITE.`site_name` AS sitename, SITE.`site_owner_name` AS ownername, SITE.`site_plot_no` AS plotno, REPORT.*, DATE_FORMAT(REPORT.hform_createdon, '%d/%m/%Y') AS createdon, PDF.`pdf_name` AS pdfname FROM `nman_handover_certificate` AS REPORT INNER JOIN `nman_site_master` AS SITE ON REPORT.`fk_site_id`=SITE.`pk_site_id` AND DATE(REPORT.`hform_createdon`)>=:startdate AND DATE(REPORT.`hform_createdon`)<=:enddate LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITE.`pk_site_id`=:pk_site_id AND SITE.`site_visibility`=:site_visibility";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($reqData->txtDateFrom)));
							$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
							$stmt->bindParam("pk_site_id"		, $reqData->siteid);
							$stmt->bindParam("site_visibility"	, $reqData->visibility);
						$stmt->execute();
					endif;
				//fetch data
					$fetchData	= $stmt->fetchAll(PDO::FETCH_OBJ);
				//check condition
					if($stmt->rowCount() > 0):
						$resultDetail 	= $fetchData;
						$status			= "true";
						$statuscode		= "200";
					else:
						$resultDetail->message	= "Records Not Found";
					endif;
				}
				catch(PDOException $e) {
					$resultDetail->message= $e->getMessage();
				}
			endif;
		//clear database
			$db	= null;
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultDetail);
		endif;
	//return result
		echo json_encode($response);
	}


//get method statement report
	function get_method_stmt_report($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->worktype)	? $reqData->worktype	: $error[]	= "Work Type";
		isset($reqData->txtDateFrom)? $reqData->txtDateFrom	: $error[]	= "Start Date";
		isset($reqData->txtDateTo)	? $reqData->txtDateTo	: $error[]	= "End Date";
		isset($reqData->siteid)		? $reqData->siteid		: $error[]	= "Customer Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Mobile Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->siteid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//database init
					$db	  = getDB();
				//check date
					if(empty($reqData->txtDateTo)):
					//select data
						$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.input_4, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS username, USERS.`user_signature`, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_method_statement` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`pk_site_id`=:pk_site_id AND SITES.`site_visibility`=:site_visibility";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("work_type"		, $reqData->worktype);
							$stmt->bindParam("pk_site_id"		, $reqData->siteid);
							$stmt->bindParam("site_visibility"	, $reqData->visibility);
						$stmt->execute();
					else:
					//select data
						$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.input_4, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS username, USERS.`user_signature`, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_method_statement` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type AND DATE(REPORT.`mform_createdon`)>=:startdate AND DATE(REPORT.`mform_createdon`)<=:enddate INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`pk_site_id`=:pk_site_id AND SITES.`site_visibility`=:site_visibility";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("work_type"		, $reqData->worktype);
							$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($reqData->txtDateFrom)));
							$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
							$stmt->bindParam("pk_site_id"		, $reqData->siteid);
							$stmt->bindParam("site_visibility"	, $reqData->visibility);
						$stmt->execute();
					endif;
				//fetch data
					$fetchData	= $stmt->fetchAll(PDO::FETCH_OBJ);
				//check condition
					if($stmt->rowCount() > 0):
						$resultDetail 	= $fetchData;
						$status			= "true";
						$statuscode		= "200";
					else:
						$resultDetail->message	= "Records Not Found";
					endif;
				}
				catch(PDOException $e) {
					$resultDetail->message= $e->getMessage();
				}
			endif;
		//clear database
			$db	= null;
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultDetail);
		endif;
	//return result
		echo json_encode($response);
	}

//get inspection report
	function get_inspection_report($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->txtDateFrom)? $reqData->txtDateFrom	: $error[]	= "Start Date";
		isset($reqData->txtDateTo)	? $reqData->txtDateTo	: $error[]	= "End Date";
		isset($reqData->siteid)		? $reqData->siteid		: $error[]	= "Customer Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Mobile Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->siteid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//database init
					$db	  = getDB();
				//check date
					if(empty($reqData->txtDateTo)):
					//select data
						$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.iform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_inspection_form` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`pk_site_id`=:pk_site_id AND SITES.`site_visibility`=:site_visibility GROUP BY REPORT.`pdf_key`";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("pk_site_id"		, $reqData->siteid);
							$stmt->bindParam("site_visibility"	, $reqData->visibility);
						$stmt->execute();
					else:
					//select data
						$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.iform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_inspection_form` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND DATE(REPORT.`iform_createdon`)>=:startdate AND DATE(REPORT.`iform_createdon`)<=:enddate INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`pk_site_id`=:pk_site_id AND SITES.`site_visibility`=:site_visibility GROUP BY REPORT.`pdf_key`";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($reqData->txtDateFrom)));
							$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
							$stmt->bindParam("pk_site_id"		, $reqData->siteid);
							$stmt->bindParam("site_visibility"	, $reqData->visibility);
						$stmt->execute();
					endif;
				//fetch data
					$fetchData	= $stmt->fetchAll(PDO::FETCH_OBJ);
				//check condition
					if($stmt->rowCount() > 0):
						$resultDetail 	= $fetchData;
						$status			= "true";
						$statuscode		= "200";
					else:
						$resultDetail->message	= "Records Not Found";
					endif;
				}
				catch(PDOException $e) {
					$resultDetail->message= $e->getMessage();
				}
			endif;
		//clear database
			$db	= null;
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultDetail);
		endif;
	//return result
		echo json_encode($response);
	}

//get toolbox talk report
	function get_toolboxtalk_report($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->worktype)	? $reqData->worktype	: $error[]	= "Work Type";
		isset($reqData->txtDateFrom)? $reqData->txtDateFrom	: $error[]	= "Start Date";
		isset($reqData->txtDateTo)	? $reqData->txtDateTo	: $error[]	= "End Date";
		isset($reqData->siteid)		? $reqData->siteid		: $error[]	= "Customer Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Mobile Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->siteid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//initiate db
					$db	  = getDB();
				//check date
					if(empty($reqData->txtDateTo)):
					//select data
						$sql	= "SELECT REPORT.*, FORMS.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.mtform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_tool_boxtalk_master` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type INNER JOIN `nman_tool_boxtalk_form` AS FORMS ON FORMS.`fk_mtform_id`=REPORT.`pk_mtform_id` INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`pk_site_id`=:pk_site_id AND SITES.`site_visibility`=:site_visibility";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("work_type"		, $reqData->worktype);
							$stmt->bindParam("pk_site_id"		, $reqData->siteid);
							$stmt->bindParam("site_visibility"	, $reqData->visibility);
						$stmt->execute();
					else:
					//select data
						$sql	= "SELECT REPORT.*, FORMS.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.mtform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_tool_boxtalk_master` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type AND DATE(REPORT.`mtform_createdon`)>=:startdate AND DATE(REPORT.`mtform_createdon`)<=:enddate  INNER JOIN `nman_tool_boxtalk_form` AS FORMS ON FORMS.`fk_mtform_id`=REPORT.`pk_mtform_id` INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`pk_site_id`=:pk_site_id AND SITES.`site_visibility`=:site_visibility";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("work_type"		, $reqData->worktype);
							$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($reqData->txtDateFrom)));
							$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
							$stmt->bindParam("pk_site_id"		, $reqData->siteid);
							$stmt->bindParam("site_visibility"	, $reqData->visibility);
						$stmt->execute();
					endif;
				//fetch data
					$fetchData	= $stmt->fetchAll(PDO::FETCH_OBJ);
				//check condition
					if($stmt->rowCount() > 0):
						$resultDetail 	= $fetchData;
						$status			= "true";
						$statuscode		= "200";
					else:
						$resultDetail->message	= "Records Not Found";
					endif;
				}
				catch(PDOException $e) {
					$resultDetail->message= $e->getMessage();
				}
			endif;
		//clear database
			$db	= null;
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultDetail);
		endif;
	//return result
		echo json_encode($response);
	}

//get work equipment report
	function get_work_equipment_report($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->worktype)	? $reqData->worktype	: $error[]	= "Work Type";
		isset($reqData->txtDateFrom)? $reqData->txtDateFrom	: $error[]	= "Start Date";
		isset($reqData->txtDateTo)	? $reqData->txtDateTo	: $error[]	= "End Date";
		isset($reqData->siteid)		? $reqData->siteid		: $error[]	= "Customer Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Mobile Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->siteid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//initiate db
					$db	  = getDB();
				//check date
					if(empty($reqData->txtDateTo)):
					//select data
						$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.wei_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_work_equipment_inspection` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`pk_site_id`=:pk_site_id AND SITES.`site_visibility`=:site_visibility GROUP BY REPORT.`pdf_key`";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("pk_site_id"		, $reqData->siteid);
							$stmt->bindParam("work_type"		, $reqData->worktype);
							$stmt->bindParam("site_visibility"	, $reqData->visibility);
						$stmt->execute();
					else:
					//select data
						$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.wei_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_work_equipment_inspection` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type AND DATE(REPORT.`wei_createdon`)>=:startdate AND DATE(REPORT.`wei_createdon`)<=:enddate INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`pk_site_id`=:pk_site_id AND SITES.`site_visibility`=:site_visibility GROUP BY REPORT.`pdf_key`";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($reqData->txtDateFrom)));
							$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
							$stmt->bindParam("pk_site_id"		, $reqData->siteid);
							$stmt->bindParam("work_type"		, $reqData->worktype);
							$stmt->bindParam("site_visibility"	, $reqData->visibility);
						$stmt->execute();
					endif;
				//fetch data
					$fetchData	= $stmt->fetchAll(PDO::FETCH_OBJ);
				//check condition
					if($stmt->rowCount() > 0):
						$resultDetail 	= $fetchData;
						$status			= "true";
						$statuscode		= "200";
					else:
						$resultDetail->message	= "Records Not Found";
					endif;
				}
				catch(PDOException $e) {
					$resultDetail->message= $e->getMessage();
				}
			endif;
		//clear database
			$db	= null;
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultDetail);
		endif;
	//return result
		echo json_encode($response);
	}

//get new starter report
	function get_new_starter_report($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->worktype)	? $reqData->worktype	: $error[]	= "Work Type";
		isset($reqData->txtDateFrom)? $reqData->txtDateFrom	: $error[]	= "Start Date";
		isset($reqData->txtDateTo)	? $reqData->txtDateTo	: $error[]	= "End Date";
		isset($reqData->siteid)		? $reqData->siteid		: $error[]	= "User Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Mobile Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->siteid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//initiate db
					$db	  = getDB();
				//check date
					if(empty($reqData->txtDateTo)):
					//select data
						$sql	= "SELECT REPORT.*, DATE_FORMAT(REPORT.date_of_birth, '%d/%m/%Y') AS dob, DATE_FORMAT(REPORT.date, '%d/%m/%Y') AS createdon, DATE_FORMAT(REPORT.card_expiry_date, '%d/%m/%Y') AS expiredon, PDF.`pdf_name` AS pdfname FROM `nman_new_starter_form` AS REPORT LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE REPORT.`ns_visibility`=1 AND REPORT.`work_type`=:work_type";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("work_type", $reqData->worktype);
						$stmt->execute();
					else:
					//select data
						$sql	= "SELECT REPORT.*, DATE_FORMAT(REPORT.date_of_birth, '%d/%m/%Y') AS dob, DATE_FORMAT(REPORT.date, '%d/%m/%Y') AS createdon, DATE_FORMAT(REPORT.card_expiry_date, '%d/%m/%Y') AS expiredon, PDF.`pdf_name` AS pdfname FROM `nman_new_starter_form` AS REPORT LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE DATE(REPORT.date)>=:startdate AND DATE(REPORT.date)<=:enddate AND REPORT.`ns_visibility`=1 AND REPORT.`work_type`=:work_type";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("work_type", $reqData->worktype);
							$stmt->bindParam("startdate", date("Y-m-d", strtotime($reqData->txtDateFrom)));
							$stmt->bindParam("enddate"	, date("Y-m-d", strtotime($reqData->txtDateTo)));
						$stmt->execute();
					endif;
				//fetch data
					$fetchData	= $stmt->fetchAll(PDO::FETCH_OBJ);
				//check condition
					if($stmt->rowCount() > 0):
						$resultDetail 	= $fetchData;
						$status			= "true";
						$statuscode		= "200";
					else:
						$resultDetail->message	= "Records Not Found";
					endif;
				}
				catch(PDOException $e) {
					$resultDetail->message= $e->getMessage();
				}
			endif;
		//clear database
			$db	= null;
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultDetail);
		endif;
	//return result
		echo json_encode($response);
	}

//get site supervisor report
	function get_site_supervisor_report($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->worktype)	? $reqData->worktype	: $error[]	= "Work Type";
		isset($reqData->txtDateFrom)? $reqData->txtDateFrom	: $error[]	= "Start Date";
		isset($reqData->txtDateTo)	? $reqData->txtDateTo	: $error[]	= "End Date";
		isset($reqData->siteid)		? $reqData->siteid		: $error[]	= "Customer Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Mobile Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->siteid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//initiate db
					$db	  = getDB();
				//check date
					if(empty($reqData->txtDateTo)):
					//select data
						$sql	= "SELECT REPORT.*, SITE.`site_name` AS sitename, SITE.`site_owner_name` AS ownername,  DATE_FORMAT(REPORT.sform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inspectedby, PDF.`pdf_name` AS pdfname FROM `nman_supervisor_form` AS REPORT INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` AND REPORT.`work_type`=:work_type INNER JOIN `nman_site_master` AS SITE ON SITE.`pk_site_id`=REPORT.`fk_site_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE REPORT.`fk_site_id`=:fk_site_id AND REPORT.`sform_visibility`=:sform_visibility";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("work_type"		, $reqData->worktype);
							$stmt->bindParam("fk_site_id"		, $reqData->siteid);
							$stmt->bindParam("sform_visibility"	, $reqData->visibility);
						$stmt->execute();
					else:
					//select data
						$sql	= "SELECT REPORT.*, SITE.`site_name` AS sitename, SITE.`site_owner_name` AS ownername, DATE_FORMAT(REPORT.sform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inspectedby, PDF.`pdf_name` AS pdfname FROM `nman_supervisor_form` AS REPORT INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` AND REPORT.`work_type`=:work_type INNER JOIN `nman_site_master` AS SITE ON SITE.`pk_site_id`=REPORT.`fk_site_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE DATE(REPORT.`sform_createdon`)>=:startdate AND DATE(REPORT.`sform_createdon`)<=:enddate AND REPORT.`fk_site_id`=:fk_site_id AND REPORT.`sform_visibility`=:sform_visibility";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("work_type"		, $reqData->worktype);
							$stmt->bindParam("fk_site_id"		, $reqData->siteid);
							$stmt->bindParam("sform_visibility"	, $reqData->visibility);
							$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($reqData->txtDateFrom)));
							$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
						$stmt->execute();
					endif;
				//fetch data
					$fetchData	= $stmt->fetchAll(PDO::FETCH_OBJ);
				//check condition
					if($stmt->rowCount() > 0):
						$resultDetail 	= $fetchData;
						$status			= "true";
						$statuscode		= "200";
					else:
						$resultDetail->message	= "Records Not Found";
					endif;
				}
				catch(PDOException $e) {
					$resultDetail->message= $e->getMessage();
				}
			endif;
		//clear database
			$db	= null;
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultDetail);
		endif;
	//return result
		echo json_encode($response);
	}
	
//get day worksheet form
	function get_day_worksheet_report($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->worktype)	? $reqData->worktype	: $error[]	= "Work Type";
		isset($reqData->txtDateFrom)? $reqData->txtDateFrom	: $error[]	= "Start Date";
		isset($reqData->txtDateTo)	? $reqData->txtDateTo	: $error[]	= "End Date";
		isset($reqData->siteid)		? $reqData->siteid		: $error[]	= "Site Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Admin Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->siteid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//initiate db
					$db	  = getDB();
				//check date
					if(empty($reqData->txtDateTo)):
					//select data
						$sql	= "SELECT REPORT.*, SITE.`site_name` AS sitename, SITE.`site_owner_name` AS ownername,  DATE_FORMAT(REPORT.dform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inspectedby, PDF.`pdf_name` AS pdfname FROM `nman_daywork_sheet_form` AS REPORT INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` AND REPORT.`work_type`=:work_type INNER JOIN `nman_site_master` AS SITE ON SITE.`pk_site_id`=REPORT.`fk_site_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE REPORT.`fk_site_id`=:fk_site_id AND REPORT.`dform_visibility`=:dform_visibility";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("work_type"		, $reqData->worktype);
							$stmt->bindParam("fk_site_id"		, $reqData->siteid);
							$stmt->bindParam("dform_visibility"	, $reqData->visibility);
						$stmt->execute();
					else:
					//select data
						$sql	= "SELECT REPORT.*, SITE.`site_name` AS sitename, SITE.`site_owner_name` AS ownername, DATE_FORMAT(REPORT.dform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inspectedby, PDF.`pdf_name` AS pdfname FROM `nman_daywork_sheet_form` AS REPORT INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` AND REPORT.`work_type`=:work_type INNER JOIN `nman_site_master` AS SITE ON SITE.`pk_site_id`=REPORT.`fk_site_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE DATE(REPORT.`dform_createdon`)>=:startdate AND DATE(REPORT.`dform_createdon`)<=:enddate AND REPORT.`fk_site_id`=:fk_site_id AND REPORT.`dform_visibility`=:dform_visibility";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("work_type"		, $reqData->worktype);
							$stmt->bindParam("fk_site_id"		, $reqData->siteid);
							$stmt->bindParam("dform_visibility"	, $reqData->visibility);
							$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($reqData->txtDateFrom)));
							$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
						$stmt->execute();
					endif;
				//fetch data
					$fetchData	= $stmt->fetchAll(PDO::FETCH_OBJ);
				//check condition
					if($stmt->rowCount() > 0):
						$resultDetail 	= $fetchData;
						$status			= "true";
						$statuscode		= "200";
					else:
						$resultDetail->message	= "Records Not Found";
					endif;
				}
				catch(PDOException $e) {
					$resultDetail->message= $e->getMessage();
				}
			endif;
		//clear database
			$db	= null;
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultDetail);
		endif;
	//return result
		echo json_encode($response);
	}

//get request for information form
	function get_request_for_information_report($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->worktype)	? $reqData->worktype	: $error[]	= "Work Type";
		isset($reqData->txtDateFrom)? $reqData->txtDateFrom	: $error[]	= "Start Date";
		isset($reqData->txtDateTo)	? $reqData->txtDateTo	: $error[]	= "End Date";
		isset($reqData->siteid)		? $reqData->siteid		: $error[]	= "Site Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Admin Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->siteid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//initiate db
					$db	  = getDB();
				//check date
					if(empty($reqData->txtDateTo)):
					//select data
						$sql	= "SELECT REPORT.*, SITE.`site_name` AS sitename, SITE.`site_owner_name` AS ownername, DATE_FORMAT(REPORT.rform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inspectedby, PDF.`pdf_name` AS pdfname FROM `nman_request_information_form` AS REPORT INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` AND REPORT.`work_type`=:work_type INNER JOIN `nman_site_master` AS SITE ON SITE.`pk_site_id`=REPORT.`fk_site_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE REPORT.`fk_site_id`=:fk_site_id AND REPORT.`rform_visibility`=:rform_visibility";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("work_type"		, $reqData->worktype);
							$stmt->bindParam("fk_site_id"		, $reqData->siteid);
							$stmt->bindParam("rform_visibility"	, $reqData->visibility);
						$stmt->execute();
					else:
					//select data
						$sql	= "SELECT REPORT.*, SITE.`site_name` AS sitename, SITE.`site_owner_name` AS ownername, DATE_FORMAT(REPORT.rform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inspectedby, PDF.`pdf_name` AS pdfname FROM `nman_request_information_form` AS REPORT INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` AND REPORT.`work_type`=:work_type INNER JOIN `nman_site_master` AS SITE ON SITE.`pk_site_id`=REPORT.`fk_site_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE DATE(REPORT.`rform_createdon`)>=:startdate AND DATE(REPORT.`rform_createdon`)<=:enddate AND REPORT.`fk_site_id`=:fk_site_id AND REPORT.`rform_visibility`=:rform_visibility";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("work_type"		, $reqData->worktype);
							$stmt->bindParam("fk_site_id"		, $reqData->siteid);
							$stmt->bindParam("rform_visibility"	, $reqData->visibility);
							$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($reqData->txtDateFrom)));
							$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
						$stmt->execute();
					endif;
				//fetch data
					$fetchData	= $stmt->fetchAll(PDO::FETCH_OBJ);
				//check condition
					if($stmt->rowCount() > 0):
						$resultDetail 	= $fetchData;
						$status			= "true";
						$statuscode		= "200";
					else:
						$resultDetail->message	= "Records Not Found";
					endif;
				}
				catch(PDOException $e) {
					$resultDetail->message= $e->getMessage();
				}
			endif;
		//clear database
			$db	= null;
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultDetail);
		endif;
	//return result
		echo json_encode($response);
	}

//view assigned forms 
	function get_assigned_forms($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->visibility)	? $reqData->visibility	: $error[]	= "Menu status";
		isset($reqData->siteid)		? $reqData->siteid		: $error[]	= "Customer ID";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Mobile token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->siteid, $reqData->session);
		//check token
			if($checktoken > 0):
                //try block
                try {
                    //database initialization
                    $db = getDB();
                    //select menu
                    $sltform = "SELECT `fk_form_id` FROM `nman_user_forms_assignment` WHERE `fk_site_id`=:fk_site_id AND `assign_visibility`=:assign_visibility";
                    $formstmt 	= $db->prepare($sltform);
                    $formstmt->bindParam("fk_site_id"		    , $reqData->siteid);
                    $formstmt->bindParam("assign_visibility"   , $reqData->visibility);
                    $formstmt->execute();
                    //fetch form
                    $resultForm = $formstmt->fetch(PDO::FETCH_OBJ);
                    //check menu assigned
                    if($formstmt->rowCount() > 0):
                        //select data
                        $sltsql = "SELECT `pk_form_id` AS formid, `form_name` AS formname, `form_visibility` AS status, `work_type` AS worktype FROM `nman_form_master` WHERE `pk_form_id` IN (".$resultForm->fk_form_id.") AND `form_visibility`=:form_visibility";
                        //prepare statement
                        $sltstmt 	= $db->prepare($sltsql);
                        $sltstmt->bindParam("form_visibility"	, $reqData->visibility);
                        $sltstmt->execute();
                        //fetch data
                        $resultData = $sltstmt->fetchAll(PDO::FETCH_OBJ);
                        //fetch result
                        $status		= "true";
                        $statuscode	= "200";
                    else:
                        $resultData["message"]= "Forms Not Assigned";
                    endif;
                    //clear db
                    $db 	= null;
                }
                catch(PDOException $e) {
                    $resultData["message"]= $e->getMessage();
                }
			else:
				$statuscode	= "202";
				$resultData["message"]= "Your session expired. You will be redirected to the login page.";
			endif;
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
		endif;
	//return result
		echo json_encode($response);
	}

//create/generate pdf file
	function prepare_pdf_file($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->txtFormId)	? $reqData->txtFormId	: $error[]	= "Form Name";
		isset($reqData->txtDateFrom)? $reqData->txtDateFrom	: $error[]	= "Start Date";
		isset($reqData->txtDateTo)	? $reqData->txtDateTo	: $error[]	= "End Date";
		isset($reqData->worktype)	? $reqData->worktype	: $error[]	= "Work Type";
		isset($reqData->siteid)		? $reqData->siteid		: $error[]	= "Customer Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Mobile Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->siteid, $reqData->session);
		//check token
			if($checktoken > 0):
				try{
					$template = array(1=>"site-supervisor", 2=>"", 3=>"method-statement", 4=>"toolbox-talk-register", 5=>"safety-harness-inspection", 6=>"inspection-form", 7=>"", 8=>"handover-certificate", 9=>"new-starter-form", 10=>"health-medical-self-certification", 11=>"work_equipment_inspection", 12=>"health-medical-self-certification", 13=>"site-supervisor", 14=>"method-statement", 15=>"new-starter-form", 16=>"toolbox-talk-register", 17=>"work_equipment_inspection", 18=>"day-worksheet-form", 19=>"request-information-form");
				//PDF file specification
					$paper 		= array(1=>array(0=>"A4", 1=>"portrait"), 3=>array(0=>"A4", 1=>"portrait"), 4=>array(0=>"A4", 1=>"portrait"), 5=>array(0=>"A4", 1=>"portrait"), 6=>array(0=>"A4", 1=>"portrait"), 8=>array(0=>"A4", 1=>"portrait"), 9=>array(0=>"A3", 1=>"landscape"), 10=>array(0=>"A4", 1=>"portrait"), 11=>array(0=>"A4", 1=>"portrait"), 12=>array(0=>"A4", 1=>"portrait"), 13=>array(0=>"A4", 1=>"portrait"), 14=>array(0=>"A4", 1=>"portrait"), 15=>array(0=>"A3", 1=>"landscape"), 16=>array(0=>"A4", 1=>"portrait"), 17=>array(0=>"A4", 1=>"portrait"), 18=>array(0=>"A4", 1=>"portrait"), 19=>array(0=>"A4", 1=>"portrait"));
				//instantiate and use the dompdf class
					$options= new Options();
					$options->set('isRemoteEnabled', TRUE);
					$options->set('isHtml5ParserEnabled', true);
					$dompdf = new Dompdf($options);
				//read html content
					$html 	= file_get_contents(ConstantURL()."/site-templates/".$template[$reqData->txtFormId].".php?formid=".$reqData->txtFormId."&siteid=".$reqData->siteid."&worktype=".$reqData->worktype."&from=".$reqData->txtDateFrom."&to=".$reqData->txtDateTo);
					$dompdf->loadHtml($html);
				//(Optional) Setup the paper size and orientation
					$dompdf->setPaper($paper[$reqData->txtFormId][0], $paper[$reqData->txtFormId][1]);
				//Render the HTML as PDF
					$dompdf->render();
				//Output the generated PDF (1 = download and 0 = preview)
					$output = $dompdf->output();
					$tmpName	= strtoupper($template[$reqData->txtFormId]);
					$pdfname	= preg_replace('/\s+/', '', $tmpName)."_[".date("d-m-Y_h-i-A").'].pdf';
					$filename	= 'site-reports/'.$pdfname;
				//output file
					file_put_contents($filename, $output);
					chmod($filename,0777);
				//destroy db
					$db = null;
				//return PDF file name
					$resultDetail->filename= $filename;
					$status			= "true";
					$statuscode		= "200";
				}
				catch(PDOException $e) {
					$resultDetail->message= $e->getMessage();
				}
			endif;
		//clear database
			$db	= null;
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultDetail);
		endif;
	//return result
		echo json_encode($response);
	}

//get pdf form by type
	function get_pdf_forms($request, $response, $args)
	{
		$status 	= "false";
		$statuscode	= "201";
		isset($args["formtype"])?$args["formtype"]: $error[] = "Form type";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
			try
			{
			//initiate db
				$db = getDB();
			//select query
				if($args["visible"]==0):
					$sltsql	= "SELECT * FROM `nman_form_master` WHERE `form_type`=:form_type AND `form_visibility`=1 ORDER BY `form_name` ASC";
				else:
					$sltsql	= "SELECT * FROM `nman_form_master` WHERE `form_type`=:form_type AND `is_visible`!=1 AND `form_visibility`=1 ORDER BY `form_name` ASC";
				endif;
			//prepare statement
				$sltstmt= $db->prepare($sltsql);
					$sltstmt->bindParam("form_type", $args["formtype"]);
				$sltstmt->execute();
			//fetch data
				$resultData = $sltstmt->fetchAll(PDO::FETCH_OBJ);
			//check OTP valid
				if($sltstmt->rowCount() > 0):
					$status 	= "true";
					$statuscode	= "200";
				else:
					$resultData["message"] = "Records not found";
				endif;
			} catch(PDOException $e) {
				$resultData["message"] = $e->getMessage();
			}
		//destroy db
			$db = null;
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
		endif;
	//return result
		echo json_encode($response);
	}

//send email
	function send_email($emailData)
	{
		try
		{

			$mail = new PHPMailer;
			$mail->isSMTP();                                      	// Set mailer to use SMTP
		//	$mail->Host 		= 'smtp.gmail.com';  				// Specify main and backup SMTP servers
			$mail->Host 		= 'smtp.livemail.co.uk';  				// Specify main and 
			$mail->SMTPAuth 	= true;                             // Enable SMTP authentication
			/*$mail->Username 	= 'equatortestmail@gmail.com';      // SMTP user name
			$mail->Password 	= 'Equator@6466';                   // SMTP password*/
			$mail->Username 	= 'app@norman-group.com';      // SMTP user name
			$mail->Password 	= 'Normangroupapp9';                   // SMTP password
			$mail->SMTPSecure 	= 'tls';                            // Enable encryption, 'SSL' also accepted
			$mail->Port 		= 587;
			$mail->From 		= 'app@norman-group.com';
			$mail->FromName 	= 'Norman Group';
			$mail->addAddress($emailData->email);     				// Add a recipient
			//$mail->AddBCC("app@norman-group.com", "Norman Group");
			$mail->addBCC("app@norman-group.com", "Norman Group");
		//	$mail->addBCC("mns.ajaydave@gmail.com", "Norman Group");
		//	$mail->AddBCC("mns.ajaydave@gmail.com", "Norman Group");
			$mail->WordWrap 	= 100;                              // Set word wrap to 50 characters
			$mail->isHTML(true);                                  	// Set email format to HTML
			$mail->Subject 		= $emailData->Subject;
			$mail->Body    		= $emailData->body;
		//send email
			if ($mail->send()):
				$status="true";
			else:
				$status="false";
			endif;
		
		}
		catch (phpmailerException $pe)
		{
			$status=$pe->errorMessage();
		}
	//return result
		return $status;
	}

//send notification
	function send_notification($to, $title, $message, $type)
	{
	//initialize db
		$db = getDB();
	//select query
		$sltsql	= "SELECT notify_token FROM `nman_user_master` WHERE `pk_user_id`=:pk_user_id";
	//prepare statement
		$sltstmt= $db->prepare($sltsql);
			$sltstmt->bindParam("pk_user_id", $to);
		$sltstmt->execute();
	//fetch data
		$fetchData	= $sltstmt->fetch(PDO::FETCH_ASSOC);
	//prepare for notification
		$fields = array (
			'to' => $fetchData["notify_token"],
			'notification' 	=> array (
				"body" 		=> $message,
				"title" 	=> $title,
				"icon" 		=> "myicon",
				"sound" 	=> "default",
				"click_action"=>"FCM_PLUGIN_ACTIVITY",
			),
			"data"=>array(
				"notify_type" => $type
			),
		);
	//destroy db
		$db = null;
	//JSON encode
		$fields = json_encode ($fields);
	//FCM initialization
		$ch = curl_init("https://fcm.googleapis.com/fcm/send");
		$header=array('Content-Type: application/json', "Authorization: key=AIzaSyBWkdJqpiV68-isbhLGM6bkmvg9gVqgq8I");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

		curl_exec($ch);
		curl_close($ch);
	}

//generate OTP code
	function generateOTP()
	{
		$chars			= '0123456789'.time();
		$randomStrLength= 6;
		$otp 			= '';
	//loop
		for($i = 0; $i < $randomStrLength; $i++)
		{
			$otp .= $chars[rand(0,strlen($chars) -1)];
		}
	//return data
		return $otp;
	}

//default url	
	function ConstantURL()
	{
		if(strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,4))=='http') {
			$strOut = sprintf('https://%s:%d/',
				$_SERVER['SERVER_NAME'],
				$_SERVER['SERVER_PORT']);
		} else {
			$strOut = sprintf('https://%s:%d/',
				$_SERVER['SERVER_NAME'],
				$_SERVER['SERVER_PORT']);
		}
	//return result
		return $strOut;
	}

//function to generate random token
	function generate_mobile_token($email)
	{
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'.time().md5($email);
		$randomStringLength = 128;
		$randomString = '';
	//loop data
		for($i = 0; $i < $randomStringLength; $i++)
		{
			$randomString .= $chars[rand(0,strlen($chars) -1)];
		}
	//return result
		return $randomString;
	}

//verify mobile token
	function verify_session_token($siteid, $mobile_token)
	{
		try
		{
		//initiate db
			$db = getDB();
		//select query
			$sltsql = "SELECT * FROM `nman_site_master` WHERE `pk_site_id`=:pk_site_id  AND `mobile_token`=:mobile_token";
		//prepare select statement
			$stmt = $db->prepare($sltsql);
				$stmt->bindParam("pk_site_id"	, $siteid);
				$stmt->bindParam("mobile_token"	, $mobile_token);
			$stmt->execute();
		//destroy db
			$db = null;
		//return result
			return $stmt->rowCount();
		}
		catch(PDOException $e) {
			return $e->getMessage();
		}
	}
?>