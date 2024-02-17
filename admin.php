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
	$app->POST('/admin_login'				, 'admin_login');
	$app->POST('/reset_password_otp'		, 'reset_password_otp');
	$app->POST('/reset_password'			, 'reset_password');
	$app->POST('/get_users_list'			, 'get_users_list');
	$app->POST('/get_new_users_list'		, 'get_new_users_list');
	$app->POST('/get_users_by_type'			, 'get_users_by_type');
	$app->POST('/get_user_info'				, 'get_user_info');
	$app->POST('/get_login_info'			, 'get_login_info');
	$app->POST('/update_user_visibility'	, 'update_user_visibility');
	$app->POST('/add_user_info'				, 'add_user_info');
	$app->POST('/edit_user_info'			, 'edit_user_info');

	$app->POST('/get_sites_list'			, 'get_sites_list');
	$app->POST('/get_new_sites_list'		, 'get_new_sites_list');
	$app->POST('/get_site_info'				, 'get_site_info');
	$app->POST('/get_site_assignment'		, 'get_site_assignment');
	$app->POST('/update_site_visibility'	, 'update_site_visibility');
	$app->POST('/add_site_info'				, 'add_site_info');
	$app->POST('/edit_site_info'			, 'edit_site_info');
	$app->POST('/site_assignment'			, 'site_assignment');
	$app->POST('/site_reassignment'			, 'site_reassignment');

	$app->POST('/get_forms_list'			, 'get_forms_list');
	$app->POST('/get_form_info'				, 'get_form_info');
	$app->POST('/update_form_visibility'	, 'update_form_visibility');

	$app->POST('/get_handover_report'		, 'get_handover_report');
	$app->POST('/get_method_stmt_report'	, 'get_method_stmt_report');
	$app->POST('/get_inspection_report'		, 'get_inspection_report');
	$app->POST('/get_toolboxtalk_report'	, 'get_toolboxtalk_report');
	$app->POST('/get_work_equipment_report'	, 'get_work_equipment_report');
	$app->POST('/get_new_starter_report'	, 'get_new_starter_report');
	$app->POST('/get_site_supervisor_report', 'get_site_supervisor_report');
	$app->POST('/get_day_worksheet_report'	, 'get_day_worksheet_report');
	$app->POST('/get_request_for_information_report', 'get_request_for_information_report');
	$app->POST('/get_safety_harness_report', 'get_safety_harness_report');

	$app->POST('/get_harness_list'			, 'get_harness_list');
	$app->POST('/get_harness_info'			, 'get_harness_info');
	$app->POST('/add_harness_info'			, 'add_harness_info');
	$app->POST('/edit_harness_info'			, 'edit_harness_info');
	$app->POST('/update_harness_visibility'	, 'update_harness_visibility');

	$app->POST('/add_slider_images'			, 'add_slider_images');
	$app->POST('/get_slider_images'			, 'get_slider_images');
	$app->POST('/get_slider_info'			, 'get_slider_info');
	$app->POST('/update_slider_visibility'	, 'update_slider_visibility');

	$app->POST('/get_menu_list'				, 'get_menu_list');
	$app->POST('/get_assigned_menu'			, 'get_assigned_menu');
	$app->POST('/add_menu_assign'			, 'add_menu_assign');

	$app->POST('/get_assigned_forms'		, 'get_assigned_forms');
	$app->POST('/get_user_assigned_forms'	, 'get_user_assigned_forms');
	$app->POST('/add_form_assign'			, 'add_form_assign');
	$app->POST('/add_user_form_assign'		, 'add_user_form_assign');

	$app->POST('/prepare_pdf_file'			, 'prepare_pdf_file');

//***********GET method***********//
	$app->GET('/get_pdf_forms/{formtype}'	, 'get_pdf_forms');
	$app->run();

//user login
	function admin_login($request, $response)
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
			$user_email		= stripslashes($reqData->txtUserName);
			$user_password	= stripslashes(md5(md5($reqData->txtPassword)));
			$token_expired	= date("Y-m-d H:i:s", strtotime("+1 days"));
			try {
				$db	  = getDB();
			//select data
				$sql	= "SELECT *,DATE_FORMAT(user_dob, '%d/%m/%Y') AS user_dob FROM `nman_user_master` WHERE `user_email`=:user_email AND `user_password`=:user_password";
			//prepare statement
				$stmt = $db->prepare($sql);
					$stmt->bindParam("user_email"	, $user_email);
					$stmt->bindParam("user_password", $user_password);
				$stmt->execute();
			//fetch data
				$fetchData	= $stmt->fetch(PDO::FETCH_OBJ);
			//variable declaration
				$resultDetail= new stdClass();
			//check condition
				if($stmt->rowCount() > 0):
				//check user active/inactive
					if($fetchData->user_is_active == 1 && $fetchData->user_visibility == 1 && $fetchData->is_approved == 1):
					//generate mobile token
						$mobile_token	= generate_mobile_token($fetchData->user_email);
					//fetch data
						$resultDetail	= $fetchData;
						$resultDetail->mobile_token	= $mobile_token;
						$status			= "true";
						$statuscode		= "200";
					//update mobile token
						$updsql = "UPDATE `nman_user_master` SET `mobile_token`=:mobile_token WHERE `pk_user_id`=:pk_user_id";
					//prepare statement
						$updstmt = $db->prepare($updsql);
							$updstmt->bindParam("mobile_token"	, $mobile_token);
							$updstmt->bindParam("pk_user_id" 	, $fetchData->pk_user_id);
						$updstmt->execute();
					elseif($fetchData->user_is_active	==	0):
						$resultDetail->message	= "User email not verified";
					elseif($fetchData->is_approved 	== 0):
						$resultDetail->message	= "Waiting for admin approval. Please contact admin";
					elseif($fetchData->user_visibility 	== 0):
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
				$user_otp 	= generateOTP();
			//SQL query to check email and mobile number exist
				$selectsql 	= "SELECT * FROM `nman_user_master` WHERE `user_email`=:user_email";
			//prepare statement
				$sltstmt 	= $db->prepare($selectsql);
					$sltstmt->bindParam("user_email"	, $reqData->txtEmail);
				$sltstmt->execute();
			//check email/phone duplication
				if($sltstmt->rowCount() > 0):
				//SQL query insert data
					$sql = "UPDATE `nman_user_master` SET `user_otp`=:user_otp WHERE `user_email`=:user_email";
				//prepare statement
					$stmt 	= $db->prepare($sql);
						$stmt->bindParam("user_otp"		, $user_otp);
						$stmt->bindParam("user_email"	, $reqData->txtEmail);
					$stmt->execute();
				//fetch last inserted data
					$status						= "true";
					$statuscode					= "200";
					$resultData->message		= "Success! Reset password link has been sent to your registered Email Address.";
				//prepare email
					$emailData=new stdClass();
					$emailData->Subject		= "Norman Group - Reset Password";
					$emailData->email		= $reqData->txtEmail;
					$emailData->user_otp	= $user_otp;
					$username				= $reqData->userfirstname." ".$reqData->userlastname;
					$resetpasswordlink 		= ConstantURL()."index.php/resetpassword/". $user_otp;
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
		isset($reqData->txtUserId)			? $reqData->txtUserId			: $error[] = "User ID";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//try block
			try {
			//database initialization
				$db 		= getDB();
			//SQL query insert data
				$updsql = "UPDATE `nman_user_master` SET `user_password`=:user_password WHERE `pk_user_id`=:pk_user_id";
			//prepare statement
				$updstmt 	= $db->prepare($updsql);
					$updstmt->bindParam("user_password"	, md5(md5($reqData->txtConfirmPassword)));
					$updstmt->bindParam("pk_user_id"	, $reqData->txtUserId);
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

//get all users list
	function get_users_list($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->visibility)	? $reqData->visibility	: $error[]	= "Visibility";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "Admin Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Admin Token";
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
					$sql	= "SELECT USERS.pk_user_id AS userid, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS username, USERS.`user_email` AS useremail, USERS.`work_type` AS worktype, USERS.`user_img` AS userimg, USERS.`user_visibility` AS status, DATE_FORMAT(USERS.`user_created_on`, '%d/%m/%Y') AS createdon, TYPES.`pk_type_id` AS usertypeid, TYPES.`type_name` AS typename FROM `nman_user_master` AS USERS LEFT JOIN `nman_user_types` AS TYPES ON TYPES.`pk_type_id`=USERS.`fk_type_id` WHERE USERS.`user_visibility`=:user_visibility ORDER BY USERS.`pk_user_id` DESC";
				//prepare statement
					$stmt = $db->prepare($sql);
						$stmt->bindParam("user_visibility"	, $reqData->visibility);
					$stmt->execute();
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
	
//get all users list
	function get_new_users_list($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->visibility)	? $reqData->visibility	: $error[]	= "Visibility";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "Admin Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Admin Token";
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
					$sql	= "SELECT USERS.pk_user_id AS userid, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS username, USERS.`user_email` AS useremail, USERS.`work_type` AS worktype, USERS.`user_img` AS userimg, USERS.`user_visibility` AS status, DATE_FORMAT(USERS.`user_created_on`, '%d/%m/%Y') AS createdon, TYPES.`pk_type_id` AS usertypeid, TYPES.`type_name` AS typename FROM `nman_user_master` AS USERS INNER JOIN `nman_user_types` AS TYPES ON TYPES.`pk_type_id`=USERS.`fk_type_id` WHERE USERS.`user_visibility`=:user_visibility AND DATE(`user_created_on`) = CURDATE() ORDER BY USERS.`pk_user_id` DESC";
				//prepare statement
					$stmt = $db->prepare($sql);
						$stmt->bindParam("user_visibility"	, $reqData->visibility);
					$stmt->execute();
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
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "status" => $status, "data" => $resultDetail);
		endif;
	//return result
		echo json_encode($response);
	}

//get personal information
	function get_users_by_type($request, $response, $args)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= "";
		$status		= "false";
		$statuscode	= "201";
	//check requested values
		isset($reqData->txtUserType)? $reqData->txtUserType	: $error[]	= "User Type";
		isset($reqData->txtVisibility)? $reqData->txtVisibility	: $error[]	= "User Visibility";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "User Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Session Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
			try
			{
			//db initiation
				$db 	= getDB();
			//select query
				$sltsql	= "SELECT `pk_user_id` AS assignid, `user_emp_id` AS empid, `user_first_name` AS firstname, `user_last_name` AS lastname, `user_email` AS useremail, user_email_verified as emailverified, `user_phone` AS userphone, `work_type` AS worktype, `user_img` AS userimg, `is_approved` AS isapproved, `user_visibility` AS uservisibility FROM `nman_user_master` WHERE `fk_type_id`=:fk_type_id AND `user_visibility`=:user_visibility";
			//prepare statement
				$sltstmt= $db->prepare($sltsql);
					$sltstmt->bindParam("fk_type_id"		, $reqData->txtUserType);
					$sltstmt->bindParam("user_visibility"	, $reqData->txtVisibility);
				$sltstmt->execute();
			//fetch data
				$resultData = $sltstmt->fetchAll(PDO::FETCH_OBJ);
			//return result
				$status		= "true";
				$statuscode	= "200";
			} catch(PDOException $e) {
				$resultData->message= $e->getMessage();
			}
		//clear database
			$db = null;
		//prepare return string
			$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
		endif;
	//return result
		echo json_encode($response);
	}

//get user imformation
	function get_user_info($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->edituserid)	? $reqData->edituserid	: $error[]	= "Visibility";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "User Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Session Token";
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
					$sql	= "SELECT USERS.`pk_user_id` AS userid, USERS.`user_emp_id` AS empid, USERS.`user_first_name` AS firstname, USERS.`user_last_name` AS lastname, USERS.`user_email` AS useremail, USERS.`user_phone` AS userphone, USERS.`user_dob` AS userdob, USERS.`user_img` AS userimg, USERS.`user_signature` AS signature, USERS.`fk_type_id` AS usertypeid, USERS.`work_type` AS worktype, USERS.`user_visibility` AS status, TYPE.type_name AS usertype  FROM `nman_user_master` AS USERS LEFT JOIN `nman_user_types` AS TYPE ON TYPE.`pk_type_id`=USERS.fk_type_id WHERE USERS.`pk_user_id`=:pk_user_id";
				//prepare statement
					$stmt = $db->prepare($sql);
						$stmt->bindParam("pk_user_id"	, $reqData->edituserid);
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

//get user imformation
	function get_login_info($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "User Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Session Token";
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
					$sql	= "SELECT `pk_user_id` AS userid, CONCAT(`user_first_name` , ' ', `user_last_name`) AS username, `user_img` AS userimg, `mobile_token` AS session FROM `nman_user_master` WHERE `pk_user_id`=:pk_user_id";
				//prepare statement
					$stmt = $db->prepare($sql);
						$stmt->bindParam("pk_user_id"	, $reqData->userid);
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

//user visibility update
	function update_user_visibility($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->edituserid)		? $reqData->edituserid		: $error[]	= "Visibility";
		isset($reqData->txtVisibility)	? $reqData->txtVisibility	: $error[]	= "Visibility";
		isset($reqData->userid)			? $reqData->userid			: $error[]	= "Admin Name";
		isset($reqData->session)		? $reqData->session			: $error[]	= "Admin Token";
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
					$sql	= "UPDATE `nman_user_master` SET `user_visibility`=:user_visibility WHERE `pk_user_id`=:pk_user_id";
				//prepare statement
					$stmt = $db->prepare($sql);
						$stmt->bindParam("user_visibility"	, $reqData->txtVisibility);
						$stmt->bindParam("pk_user_id"		, $reqData->edituserid);
					$stmt->execute();
				//fetch data
					$status			= "true";
					$statuscode		= "200";
					$resultDetail->message= "User visibility changed successfully!";
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

//add user information
	function add_user_info($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->txtEmpID)		? $reqData->txtEmpID		: $error[]	= "User Employee id";
		isset($reqData->txtFirstName)	? $reqData->txtFirstName	: $error[]	= "User First Name";
		isset($reqData->txtLastName)	? $reqData->txtLastName		: $error[]	= "User Last Name";
		isset($reqData->txtEmail)		? $reqData->txtEmail		: $error[]	= "User Email";
		isset($reqData->txtPhone)		? $reqData->txtPhone		: $error[]	= "User Phone";
		isset($reqData->txtUserType)	? $reqData->txtUserType		: $error[]	= "User type";
		isset($reqData->txtWorkType)	? $reqData->txtWorkType		: $error[]	= "Work type";
		isset($reqData->txtProfileImg)	? $reqData->txtProfileImg	: $error[]	= "User Profile Image";
		isset($reqData->userid)			? $reqData->userid			: $error[]	= "Admin Name";
		isset($reqData->session)		? $reqData->session			: $error[]	= "Admin Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db 			= getDB();
					$user_otp 		= generateOTP();
					$userpassword	= "NORMAN@".date("Y");
				//SQL query to check email and mobile number exist
					$selectsql 	= "SELECT * FROM `nman_user_master` WHERE `user_emp_id`=:user_emp_id OR `user_email`=:user_email OR `user_phone`=:user_phone";
				//prepare statement
					$sltstmt 	= $db->prepare($selectsql);
						$sltstmt->bindParam("user_emp_id"	, $reqData->txtEmpID);
						$sltstmt->bindParam("user_email"	, $reqData->txtEmail);
						$sltstmt->bindParam("user_phone"	, $reqData->txtPhone);
					$sltstmt->execute();
				//check email/phone duplication
					if($sltstmt->rowCount() == 0):
					//SQL query insert data
						$sql = "INSERT INTO `nman_user_master` (`user_emp_id`, `user_first_name`, `user_last_name`, `user_email`, `user_phone`, `user_password`, `fk_type_id`, `work_type`, `user_img`, `user_otp`, `user_created_on`) VALUES (:user_emp_id, :user_first_name, :user_last_name, :user_email, :user_phone, :user_password, :fk_type_id, :work_type, :user_img, :user_otp, :user_created_on)";
					//prepare statement
						$stmt 	= $db->prepare($sql);
							$stmt->bindParam("user_emp_id"		, $reqData->txtEmpID);
							$stmt->bindParam("user_first_name"	, $reqData->txtFirstName);
							$stmt->bindParam("user_last_name"	, $reqData->txtLastName);
							$stmt->bindParam("user_email"		, $reqData->txtEmail);
							$stmt->bindParam("user_phone"		, $reqData->txtPhone);
							$stmt->bindParam("user_password"	, md5(md5($userpassword)));
							$stmt->bindParam("fk_type_id"		, $reqData->txtUserType);
							$stmt->bindParam("work_type"		, $reqData->txtWorkType);
							$stmt->bindParam("user_img"			, $reqData->txtProfileImg);
							$stmt->bindParam("user_otp"			, $user_otp);
							$stmt->bindParam("user_created_on"	, date('Y-m-d H:i:s'));
						$stmt->execute();
					//fetch last inserted data
						$resultData->registered_id	= $db->lastInsertId();
						$status						= "true";
						$statuscode					= "200";
						$resultData->message		= "Registration successful, kindly check your email";
					//check prepare email
						if($stmt->rowCount() > 0):
							$emailData=new stdClass();
						//prepare email
							$emailData->Subject		= "E-mail Confirmation";
							$emailData->email		= $reqData->txtEmail;
							$emailData->user_otp	= $user_otp;
							$username				= $reqData->txtFirstName." ".$reqData->txtLastName;
							$verificationLink 		= ConstantURL()."index.php/activation/". $user_otp;
						//get email contents
							$mailcontent			= file_get_contents('email_templates/admin-account-verify.html');
							$replacements 			= array('({{verification-link}})'=> $verificationLink, '({{password}})' => $userpassword);
							$emailData->body 		= preg_replace(array_keys($replacements), array_values($replacements), $mailcontent);
						//callback send email function
							$mailCheck	= send_email($emailData);
						endif;
					else:
						$resultData->message= "Employee ID/Email/Phone already exist!";
					endif;
				//clear database initialization
					$db = null;
				}
				catch(PDOException $e) {
					$resultData->message= $e->getMessage();
				}
			else:
				$resultData->message= "Session Expired!";
			endif;
		//prepare return string
			$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
		endif;
	//return result
		echo json_encode($response);
	}

//edit user information
	function edit_user_info($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->txtEmpID)		? $reqData->txtEmpID		: $error[]	= "User Employee id";
		isset($reqData->txtFirstName)	? $reqData->txtFirstName	: $error[]	= "User First Name";
		isset($reqData->txtLastName)	? $reqData->txtLastName		: $error[]	= "User Last Name";
		isset($reqData->txtEmail)		? $reqData->txtEmail		: $error[]	= "User Email";
		isset($reqData->txtPhone)		? $reqData->txtPhone		: $error[]	= "User Phone";
		isset($reqData->txtUserType)	? $reqData->txtUserType		: $error[]	= "User type";
		isset($reqData->txtWorkType)	? $reqData->txtWorkType		: $error[]	= "Work type";
		isset($reqData->txtProfileImg)	? $reqData->txtProfileImg	: $error[]	= "User Image";
		isset($reqData->userid)			? $reqData->userid			: $error[]	= "Admin Name";
		isset($reqData->session)		? $reqData->session			: $error[]	= "Admin Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db 			= getDB();
					$user_otp 		= generateOTP();
					$userpassword	= "NORMAN@".date("Y");
				//SQL query to check email and mobile number exist
					$selectsql 	= "SELECT * FROM `nman_user_master` WHERE (`user_emp_id`=:user_emp_id OR `user_email`=:user_email OR `user_phone`=:user_phone) AND `pk_user_id`!=:pk_user_id";
				//prepare statement
					$sltstmt 	= $db->prepare($selectsql);
						$sltstmt->bindParam("user_emp_id"	, $reqData->txtEmpID);
						$sltstmt->bindParam("user_email"	, $reqData->txtEmail);
						$sltstmt->bindParam("user_phone"	, $reqData->txtPhone);
						$sltstmt->bindParam("pk_user_id"	, $reqData->txtUserId);
					$sltstmt->execute();
				//fetch data
					$fetchData	= $sltstmt->fetch(PDO::FETCH_OBJ);
				//check email/phone duplication
					//echo $reqData->hiddenEmail.'!='.$reqData->txtEmail;die;
					if($sltstmt->rowCount() == 0):
					//SQL query insert data
						if($reqData->hiddenEmail!=$reqData->txtEmail)
						{
							$sql = "UPDATE `nman_user_master` SET `user_emp_id`=:user_emp_id, `user_first_name`=:user_first_name, `user_last_name`=:user_last_name, `user_email`=:user_email, `user_phone`=:user_phone, `fk_type_id`=:fk_type_id, `work_type`=:work_type, `user_img`=:user_img,`user_visibility`=:user_visibility,`user_otp`=:user_otp,`user_is_active`=:user_is_active,`user_email_verified`=:user_email_verified WHERE `pk_user_id`=:pk_user_id";

						}
						else
						{
						$sql = "UPDATE `nman_user_master` SET `user_emp_id`=:user_emp_id, `user_first_name`=:user_first_name, `user_last_name`=:user_last_name, `user_email`=:user_email, `user_phone`=:user_phone, `fk_type_id`=:fk_type_id, `work_type`=:work_type, `user_img`=:user_img,`user_visibility`=:user_visibility WHERE `pk_user_id`=:pk_user_id";
						}

					//prepare statement
						$stmt 	= $db->prepare($sql);
							$stmt->bindParam("user_emp_id"		, $reqData->txtEmpID);
							$stmt->bindParam("user_first_name"	, $reqData->txtFirstName);
							$stmt->bindParam("user_last_name"	, $reqData->txtLastName);
							$stmt->bindParam("user_email"		, $reqData->txtEmail);
							$stmt->bindParam("user_phone"		, $reqData->txtPhone);
							$stmt->bindParam("fk_type_id"		, $reqData->txtUserType);
							$stmt->bindParam("work_type"		, $reqData->txtWorkType);
							$stmt->bindParam("user_img"			, $reqData->txtProfileImg);
							$stmt->bindParam("user_visibility"	, $reqData->txtStatus);
							$stmt->bindParam("pk_user_id"		, $reqData->txtUserId);
							if($reqData->hiddenEmail!=$reqData->txtEmail)
							{
								$user_status=0;	
								$stmt->bindParam("user_otp",$user_otp);
							//	$stmt->bindParam("is_approved",$user_status);
								$stmt->bindParam("user_is_active",$user_status);
								$stmt->bindParam("user_email_verified",$user_status); 
							} 

						$stmt->execute();
					//fetch last inserted data
						$status				= "true";
						$statuscode			= "200";
						$resultData->message= "User information updated successfully";
				 	if($stmt->rowCount() > 0 && $reqData->hiddenEmail!=$reqData->txtEmail):
							$emailData=new stdClass();
						//prepare email
							$emailData->Subject		= "E-mail Confirmation";
							$emailData->email		= $reqData->txtEmail;
							$emailData->user_otp	= $user_otp;
							$username				= $reqData->txtFirstName." ".$reqData->txtLastName;
							$verificationLink 		= ConstantURL()."index.php/activation/". $user_otp;
						//get email contents
							$mailcontent			= file_get_contents('email_templates/admin-email-account-verify.html');
							$replacements 			= array('({{verification-link}})'=> $verificationLink, '({{password}})' => $userpassword);
							$emailData->body 		= preg_replace(array_keys($replacements), array_values($replacements), $mailcontent);

						//callback send email function
					 	$mailCheck	= send_email($emailData);
						endif;	

					else:
						if( $fetchData->user_emp_id == $reqData->txtEmpID ):
							$resultData->message= "Employee ID already exist!";
						elseif( $fetchData->user_email == $reqData->txtEmail ):
							$resultData->message= "Email already exist!";
						else:
							$resultData->message= "Phone number already exist!";
						endif;
					endif;
				//clear database initialization
					$db = null;
				}
				catch(PDOException $e) {

					$resultData->message= $e->getMessage();
				}
			else:
				$resultData->message= "Session Expired!";
			endif;
		//prepare return string
			$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
		endif;
	//return result
		echo json_encode($response);
	}

//get all sites list
	function get_sites_list($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->visibility)	? $reqData->visibility	: $error[]	= "Visibility";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "Admin Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Admin Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//database initiate
					$db	  = getDB();
				//check user type
					if($reqData->usertype==0):
					//select data
						$sql	= "SELECT `pk_site_id` AS siteid, `site_name` AS sitename, `site_email` AS siteemail, `site_visibility` AS status,`site_owner_name` AS siteowner, DATE_FORMAT(`site_created_on`, '%d/%m/%Y') AS createdon FROM `nman_site_master` WHERE `site_visibility`=:site_visibility";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("site_visibility"	, $reqData->visibility);
						$stmt->execute();
					else:
					//select data
						$sql	= "SELECT SITES.`pk_site_id` AS siteid, SITES.`site_name` AS sitename, SITES.`site_email` AS siteemail, SITES.`site_visibility` AS status,`site_owner_name` AS siteowner, DATE_FORMAT(SITES.`site_created_on`, '%d/%m/%Y') AS createdon FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility WHERE ASSIGN.`fk_user_id`=:fk_user_id";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("fk_user_id"		, $reqData->userid);
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
						$resultDetail->message = "Records Not Found";
					endif;
				}
				catch(PDOException $e) {
					$resultDetail->message = $e->getMessage();
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

//get new sites list
	function get_new_sites_list($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->visibility)	? $reqData->visibility	: $error[]	= "Visibility";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "Admin Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Admin Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//database initiate
					$db	  = getDB();
				//check user type
					if($reqData->usertype==0):
					//select data
						$sql	= "SELECT `pk_site_id` AS siteid, `site_name` AS sitename, `site_email` AS siteemail, `site_visibility` AS status,`site_owner_name` AS siteowner, DATE_FORMAT(`site_created_on`, '%d/%m/%Y') AS createdon FROM `nman_site_master` WHERE `site_visibility`=:site_visibility AND DATE(`site_created_on`) = CURDATE()";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("site_visibility"	, $reqData->visibility);
						$stmt->execute();
					else:
					//select data
						$sql	= "SELECT SITES.`pk_site_id` AS siteid, SITES.`site_name` AS sitename, SITES.`site_email` AS siteemail, SITES.`site_visibility` AS status,`site_owner_name` AS siteowner, DATE_FORMAT(SITES.`site_created_on`, '%d/%m/%Y') AS createdon FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility WHERE ASSIGN.`fk_user_id`=:fk_user_id AND DATE(SITES.`site_created_on`) = CURDATE()";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("fk_user_id"		, $reqData->userid);
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
						$resultDetail->message = "Records Not Found";
					endif;
				}
				catch(PDOException $e) {
					$resultDetail->message = $e->getMessage();
				}
			endif;
		//clear database
			$db	= null;
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "status" => $status, "data" => $resultDetail);
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

//get site assignment information
	function get_site_assignment($request, $response)
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
					$sql	= "SELECT USERS.`pk_user_id` AS userid, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS username, USERS.`user_email` AS useremail, USERS.`user_phone` AS userphone, USERS.`user_img` AS userimg, USERS.`work_type` AS worktype, TYPE.`type_name` AS usertype, USERS.`fk_type_id` AS usertypeid, ASSIGN.`pk_assign_id` AS assignid FROM `nman_site_assignment` AS ASSIGN LEFT JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=ASSIGN.`fk_user_id` LEFT JOIN `nman_user_types` AS TYPE ON TYPE.`pk_type_id`=USERS.`fk_type_id` WHERE ASSIGN.`fk_site_id`=:fk_site_id AND ASSIGN.`assign_visibility`=1";
				//prepare statement
					$stmt = $db->prepare($sql);
						$stmt->bindParam("fk_site_id", $reqData->siteid);
					$stmt->execute();
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

//site visibility update
	function update_site_visibility($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->siteid)			? $reqData->siteid			: $error[]		= "Site Name";
		isset($reqData->txtVisibility)	? $reqData->txtVisibility	: $error[]	= "Visibility";
		isset($reqData->userid)			? $reqData->userid			: $error[]	= "Admin Name";
		isset($reqData->session)		? $reqData->session			: $error[]	= "Admin Token";
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
					$sql	= "UPDATE `nman_site_master` SET `site_visibility`=:site_visibility WHERE `pk_site_id`=:pk_site_id";
				//prepare statement
					$stmt = $db->prepare($sql);
						$stmt->bindParam("site_visibility"	, $reqData->txtVisibility);
						$stmt->bindParam("pk_site_id"		, $reqData->siteid);
					$stmt->execute();
				//fetch data
					$status			= "true";
					$statuscode		= "200";
					$resultDetail->message= "Site visibility changed successfully!";
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

//get all sites list
	function get_forms_list($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->visibility)	? $reqData->visibility	: $error[]	= "Visibility";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "Admin Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Admin Token";
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
					if(($reqData->worktype)==0):
				//select data
					$sql	= "SELECT `pk_form_id` AS formid, `form_name` AS formname,`work_type` AS worktype, `form_visibility` AS status FROM `nman_form_master` WHERE `form_visibility`=:form_visibility ORDER BY `work_type` ASC";
				//prepare statement
					$stmt = $db->prepare($sql);
						$stmt->bindParam("form_visibility"	, $reqData->visibility);
					$stmt->execute();
					else:
					//select data
					$sql	= "SELECT `pk_form_id` AS formid, `form_name` AS formname,`work_type` AS worktype, `form_visibility` AS status FROM `nman_form_master` WHERE `form_visibility`=:form_visibility AND `work_type`=:work_type ORDER BY `work_type` ASC";
				//prepare statement
					$stmt = $db->prepare($sql);
						$stmt->bindParam("form_visibility"	, $reqData->visibility);
						$stmt->bindParam("work_type"	, $reqData->worktype);
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

//get form imformation
	function get_form_info($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->formid)	? $reqData->formid	: $error[]	= "Form Name";
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
					$sql	= "SELECT `pk_form_id` AS formid, `form_name` AS formname, `form_visibility` AS status FROM `nman_form_master` WHERE `pk_form_id`=:pk_form_id";
				//prepare statement
					$stmt = $db->prepare($sql);
						$stmt->bindParam("pk_form_id", $reqData->formid);
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

//create new site
	function add_site_info($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->txtSiteName)	? $reqData->txtSiteName		: $error[]	= "Site Name";
		isset($reqData->txtOwnerName)	? $reqData->txtOwnerName	: $error[]	= "Site Owner Name";
		isset($reqData->txtSiteEmail)	? $reqData->txtSiteEmail	: $error[]	= "Site Email";
		isset($reqData->txtSitePhone)	? $reqData->txtSitePhone	: $error[]	= "Site Phone";
		isset($reqData->txtPlotNo)		? $reqData->txtPlotNo		: $error[]	= "Site Plot No.";
		isset($reqData->txtSiteAddress)	? $reqData->txtSiteAddress	: $error[]	= "Site Address";
		isset($reqData->txtSiteManager)	? $reqData->txtSiteManager	: $error[]	= "Site Manager Name";
		isset($reqData->txtStatus)		? $reqData->txtStatus		: $error[]	= "Site Status";
		isset($reqData->userid)			? $reqData->userid			: $error[]	= "User Name";
		isset($reqData->session)		? $reqData->session			: $error[]	= "Session Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "Validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//SQL query to check email and mobile number exist
					$selectsql 	= "SELECT * FROM `nman_site_master` WHERE `site_name`=:site_name AND `site_owner_name`=:site_owner_name AND `site_address`=:site_address";
				//prepare statement
					$sltstmt 	= $db->prepare($selectsql);
						$sltstmt->bindParam("site_name"			, $reqData->txtSiteName);
						$sltstmt->bindParam("site_owner_name"	, $reqData->txtOwnerName);
						$sltstmt->bindParam("site_address"		, $reqData->txtSiteAddress);
					$sltstmt->execute();
				//check email/phone duplication
					if($sltstmt->rowCount() == 0):
						$password_s	= "NORMAN".date("d");
						$password	= md5(md5($password_s));
					//SQL query insert data
						$sql = "INSERT INTO `nman_site_master` (`fk_user_id`, `site_name`, `site_owner_name`, `site_email`, `site_phone`, `site_plot_no`, `site_manager`, `site_address`, `site_status`, `site_password`, `site_created_on`) VALUES (:fk_user_id, :site_name, :site_owner_name, :site_email, :site_phone, :site_plot_no, :site_manager, :site_address, :site_status, :site_password, :site_created_on)";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("fk_user_id"		, $reqData->userid);
							$stmt->bindParam("site_name"		, $reqData->txtSiteName);
							$stmt->bindParam("site_owner_name"	, $reqData->txtOwnerName);
							$stmt->bindParam("site_email"		, $reqData->txtSiteEmail);
							$stmt->bindParam("site_phone"		, $reqData->txtSitePhone);
							$stmt->bindParam("site_plot_no"		, $reqData->txtPlotNo);
							$stmt->bindParam("site_manager"		, $reqData->txtSiteManager);
							$stmt->bindParam("site_address"		, $reqData->txtSiteAddress);
							$stmt->bindParam("site_status"		, $reqData->txtStatus);
							$stmt->bindParam("site_password"	, $password);
							$stmt->bindParam("site_created_on"	, date('Y-m-d H:i:s'));
						$stmt->execute();
					//fetch last inserted data
						$resultData->siteid= $db->lastInsertId();
					//update query
						$job_number = "NMAN".sprintf("%04d", $resultData->siteid);
						$updqry = "UPDATE `nman_site_master` SET `site_job_number`=:site_job_number WHERE `pk_site_id`=:pk_site_id";
						$updstmt = $db->prepare($updqry);
							$updstmt->bindParam("site_job_number"	, $job_number);
							$updstmt->bindParam("pk_site_id"		, $resultData->siteid);
						$updstmt->execute();
					//create folder
						if(!file_exists("reports/".$resultData->siteid)):
							mkdir("reports/".$resultData->siteid, 0777, true);
						endif;
					//send email
						$emailData=new stdClass();
					//prepare email
						$emailData->Subject		= "Norman Group";
						$emailData->email		= $reqData->txtSiteEmail;
					//get email contents
						$mailcontent			= file_get_contents('email_templates/user-verify.html');
						$replacements 			= array('({{user-name}})'=> $reqData->txtSiteEmail, '({{user-password}})'=>$password_s);
						$emailData->body 		= preg_replace(array_keys($replacements), array_values($replacements), $mailcontent);
					//callback send email function
						$mailCheck	= send_email($emailData);
					//status code
						$status		= "true";
						$statuscode	= "200";
						$resultData->message= "Site created successfully";
					else:
						$resultData->message= "Site name already exist!";
					endif;
				//clear database initialization
					$db = null;
				}
				catch(PDOException $e) {
					$resultData->message= $e->getMessage();
				}
			else:
				$statuscode	= "202";
				$resultData->message= "Your session expired. You will be redirected to the login page.";
			endif;
		//push values to array
			array_push($resturnData, $resultData);
		//prepare return string
			$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resturnData);
		endif;
	//return result
		echo json_encode($response);
	}

//edit site info
	function edit_site_info($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->txtSiteName)	? $reqData->txtSiteName		: $error[]	= "Site Name";
		isset($reqData->txtOwnerName)	? $reqData->txtOwnerName	: $error[]	= "Site Owner Name";
		isset($reqData->txtSiteEmail)	? $reqData->txtSiteEmail	: $error[]	= "Site Email";
		isset($reqData->txtSitePhone)	? $reqData->txtSitePhone	: $error[]	= "Site Phone";
		isset($reqData->txtPlotNo)		? $reqData->txtPlotNo		: $error[]	= "Site Plot No.";
		isset($reqData->txtSiteAddress)	? $reqData->txtSiteAddress	: $error[]	= "Site Address";
		isset($reqData->txtSiteManager)	? $reqData->txtSiteManager	: $error[]	= "Site Manager Name";
		isset($reqData->txtStatus)		? $reqData->txtStatus		: $error[]	= "Site Status";
		isset($reqData->txtSiteId)		? $reqData->txtSiteId		: $error[]	= "Site Id";
		isset($reqData->userid)			? $reqData->userid			: $error[]	= "User Name";
		isset($reqData->session)		? $reqData->session			: $error[]	= "Session Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "Validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//SQL query to check email and mobile number exist
					$selectsql 	= "SELECT * FROM `nman_site_master` WHERE `site_name`=:site_name AND `site_owner_name`=:site_owner_name AND `site_address`=:site_address AND `pk_site_id`!=:pk_site_id";
				//prepare statement
					$sltstmt 	= $db->prepare($selectsql);
						$sltstmt->bindParam("site_name"			, $reqData->txtSiteName);
						$sltstmt->bindParam("site_owner_name"	, $reqData->txtOwnerName);
						$sltstmt->bindParam("site_address"		, $reqData->txtSiteAddress);
						$sltstmt->bindParam("pk_site_id"		, $reqData->txtSiteId);
					$sltstmt->execute();
				//check email/phone duplication
					if($sltstmt->rowCount() == 0):
					//SQL query insert data
						$sql = "UPDATE `nman_site_master` SET `site_name`=:site_name, `site_owner_name`=:site_owner_name, `site_phone`=:site_phone, `site_plot_no`=:site_plot_no, `site_manager`=:site_manager, `site_address`=:site_address, `site_address`=:site_address, `site_visibility`=:site_visibility WHERE `pk_site_id`=:pk_site_id";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("site_name"		, $reqData->txtSiteName);
							$stmt->bindParam("site_owner_name"	, $reqData->txtOwnerName);
							$stmt->bindParam("site_phone"		, $reqData->txtSitePhone);
							$stmt->bindParam("site_plot_no"		, $reqData->txtPlotNo);
							$stmt->bindParam("site_manager"		, $reqData->txtSiteManager);
							$stmt->bindParam("site_address"		, $reqData->txtSiteAddress);
							$stmt->bindParam("site_visibility"	, $reqData->txtStatus);
							$stmt->bindParam("pk_site_id"		, $reqData->txtSiteId);
						$stmt->execute();
					//SQL query update data
						$updsql = "UPDATE `nman_site_master` SET `site_email`=:site_email WHERE `pk_site_id`=:pk_site_id";
					//prepare statement
						$updstmt 	= $db->prepare($updsql);
							$updstmt->bindParam("site_email"		, $reqData->txtSiteEmail);
							$updstmt->bindParam("pk_site_id"		, $reqData->txtSiteId);
						$updstmt->execute();
					//check update success/failed
						if($updstmt->rowCount()>0):
							$password_s	= "NORMAN".date("d");
							$password	= md5(md5($password_s));
						//send email
							$emailData=new stdClass();
						//prepare email
							$emailData->Subject		= "Norman Group";
							$emailData->email		= $reqData->txtSiteEmail;
						//get email contents
							$mailcontent			= file_get_contents('email_templates/user-verify.html');
							$replacements 			= array('({{user-name}})'=> $reqData->txtSiteEmail, '({{user-password}})'=>$password_s);
							$emailData->body 		= preg_replace(array_keys($replacements), array_values($replacements), $mailcontent);
						//callback send email function
							$mailCheck	= send_email($emailData);
						endif;
					//status code
						$status		= "true";
						$statuscode	= "200";
						$resultData->message= "Site updated successfully";
					else:
						$resultData->message= "Site name already exist!";
					endif;
				//clear database initialization
					$db = null;
				}
				catch(PDOException $e) {
					$resultData->message= $e->getMessage();
				}
			else:
				$statuscode	= "202";
				$resultData->message= "Your session expired. You will be redirected to the login page.";
			endif;
		//prepare return string
			$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
		endif;
	//return result
		echo json_encode($response);
	}

//site assignment
	function site_assignment($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->txtSiteId)	? $reqData->txtSiteId	: $error[]	= "Site name";
		isset($reqData->txtUserType)? $reqData->txtUserType	: $error[]	= "User type";
		isset($reqData->txtWorkType)? $reqData->txtWorkType	: $error[]	= "Work type";
		isset($reqData->txtAssignId)? $reqData->txtAssignId	: $error[]	= "Assigned user";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "User name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "User token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db 		= getDB();
				//SQL query insert data
					$sql = "INSERT INTO `nman_site_assignment` (`fk_site_id`, `fk_user_id`, `user_type`, `work_type`, `assigned_by`) VALUES (:fk_site_id, :fk_user_id, :user_type, :work_type, :assigned_by)";
				//prepare statement
					$stmt 	= $db->prepare($sql);
						$stmt->bindParam("fk_site_id"	, $reqData->txtSiteId);
						$stmt->bindParam("fk_user_id"	, $reqData->txtAssignId);
						$stmt->bindParam("user_type"	, $reqData->txtUserType);
						$stmt->bindParam("work_type"	, $reqData->txtWorkType);
						$stmt->bindParam("assigned_by"	, $reqData->userid);
					$stmt->execute();
				//fetch last inserted data
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "Site assigned successfully";
				//clear database initialization
					$db = null;
				}
				catch(PDOException $e) {
					$resultData->message= $e->getMessage();
				}
			else:
				$statuscode	= "202";
				$resultData->message= "Your session expired. You will be redirected to the login page.";
			endif;
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
		endif;
	//return result
		echo json_encode($response);
	}

//site assignment
	function site_reassignment($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->txtAssignedId)	? $reqData->txtAssignedId	: $error[]	= "Assigment id";
		isset($reqData->txtSiteId)		? $reqData->txtSiteId		: $error[]	= "Site name";
		isset($reqData->txtUserType)	? $reqData->txtUserType		: $error[]	= "User type";
		isset($reqData->txtWorkType)	? $reqData->txtWorkType		: $error[]	= "Work type";
		isset($reqData->txtAssignId)	? $reqData->txtAssignId		: $error[]	= "Assigned user";
		isset($reqData->userid)			? $reqData->userid			: $error[]	= "User name";
		isset($reqData->session)		? $reqData->session			: $error[]	= "User token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db 		= getDB();
				//SQL query update data
					$sql 	= "UPDATE `nman_site_assignment` SET `fk_site_id`=:fk_site_id, `fk_user_id`=:fk_user_id, `user_type`=:user_type, `work_type`=:work_type, `assigned_by`=:assigned_by WHERE `pk_assign_id`=:pk_assign_id";
				//prepare statement
					$stmt 	= $db->prepare($sql);
						$stmt->bindParam("fk_site_id"	, $reqData->txtSiteId);
						$stmt->bindParam("fk_user_id"	, $reqData->txtAssignId);
						$stmt->bindParam("user_type"	, $reqData->txtUserType);
						$stmt->bindParam("work_type"	, $reqData->txtWorkType);
						$stmt->bindParam("assigned_by"	, $reqData->userid);
						$stmt->bindParam("pk_assign_id"	, $reqData->txtAssignedId);
					$stmt->execute();
				//fetch last inserted data
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "Site re-assigned successfully";
				//clear database initialization
					$db = null;
				}
				catch(PDOException $e) {
					$resultData->message= $e->getMessage();
				}
			else:
				$statuscode	= "202";
				$resultData->message= "Your session expired. You will be redirected to the login page.";
			endif;
		//push values to array
			array_push($resturnData, $resultData);
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resturnData);
		endif;
	//return result
		echo json_encode($response);
	}

//form visibility update
	function update_form_visibility($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->formid)			? $reqData->formid			: $error[]	= "Form Name";
		isset($reqData->txtVisibility)	? $reqData->txtVisibility	: $error[]	= "Visibility";
		isset($reqData->userid)			? $reqData->userid			: $error[]	= "Admin Name";
		isset($reqData->session)		? $reqData->session			: $error[]	= "Admin Token";
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
					$sql	= "UPDATE `nman_form_master` SET `form_visibility`=:form_visibility WHERE `pk_form_id`=:pk_form_id";
				//prepare statement
					$stmt = $db->prepare($sql);
						$stmt->bindParam("form_visibility"	, $reqData->txtVisibility);
						$stmt->bindParam("pk_form_id"		, $reqData->formid);
					$stmt->execute();
				//fetch data
					$status			= "true";
					$statuscode		= "200";
					$resultDetail->message= "Form visibility changed successfully!";
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
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "Admin Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Admin Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//database init
					$db	  = getDB();
				//check date
					if(empty($reqData->txtDateTo)):
					//check user type
						if($reqData->usertype==0):
						//select data
							$sql	= "SELECT SITE.`site_name` AS sitename, SITE.`site_owner_name` AS ownername, SITE.`site_plot_no` AS plotno, REPORT.*, DATE_FORMAT(REPORT.hform_createdon, '%d/%m/%Y') AS createdon, PDF.`pdf_name` AS pdfname FROM `nman_handover_certificate` AS REPORT INNER JOIN `nman_site_master` AS SITE ON REPORT.`fk_site_id`=SITE.`pk_site_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITE.`site_visibility`=:site_visibility ORDER BY REPORT.hform_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
							$stmt->execute();
						else:
						//select data
							$sql	= "SELECT SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, REPORT.*, DATE_FORMAT(REPORT.hform_createdon, '%d/%m/%Y') AS createdon, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_handover_certificate` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id ORDER BY REPORT.hform_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
								$stmt->bindParam("fk_user_id"		, $reqData->userid);
							$stmt->execute();
						endif;
					else:
					//check user type
						if($reqData->usertype==0):
						//select data
							$sql	= "SELECT SITE.`site_name` AS sitename, SITE.`site_owner_name` AS ownername, SITE.`site_plot_no` AS plotno, REPORT.*, DATE_FORMAT(REPORT.hform_createdon, '%d/%m/%Y') AS createdon, PDF.`pdf_name` AS pdfname FROM `nman_handover_certificate` AS REPORT INNER JOIN `nman_site_master` AS SITE ON REPORT.`fk_site_id`=SITE.`pk_site_id` AND DATE(REPORT.`hform_createdon`)>=:startdate AND DATE(REPORT.`hform_createdon`)<=:enddate LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITE.`site_visibility`=:site_visibility ORDER BY REPORT.hform_createdon DESC";
							//prepare statement

							$stmt = $db->prepare($sql);
								$stmt->bindParam("startdate",date("Y-m-d", strtotime($reqData->txtDateFrom)));
								$stmt->bindParam("enddate", date("Y-m-d", strtotime($reqData->txtDateTo)));
								
								$stmt->bindParam("site_visibility", $reqData->visibility);


							
							$stmt->execute();
						else:
							$sql	= "SELECT SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, REPORT.*, DATE_FORMAT(REPORT.hform_createdon, '%d/%m/%Y') AS createdon, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_handover_certificate` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND DATE(REPORT.`hform_createdon`)>=:startdate AND DATE(REPORT.`hform_createdon`)<=:enddate LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id ORDER BY REPORT.hform_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($reqData->txtDateFrom)));
								$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
								$stmt->bindParam("fk_user_id"		, $reqData->userid);
							$stmt->execute();
						endif;
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
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "Admin Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Admin Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//database init
					$db	  = getDB();
				//check date
					if(empty($reqData->txtDateTo)):
					//check user type
						if($reqData->usertype==0):
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.input_4, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS username, USERS.`user_signature`, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_method_statement` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`site_visibility`=:site_visibility ORDER BY pk_mform_id DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type", $reqData->worktype);
								$stmt->bindParam("site_visibility", $reqData->visibility);
							$stmt->execute();
						else:
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.input_4, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS username, USERS.`user_signature`, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_method_statement` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id ORDER BY pk_mform_id DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type", $reqData->worktype);
								$stmt->bindParam("site_visibility", $reqData->visibility);
								$stmt->bindParam("fk_user_id"		, $reqData->userid);
							$stmt->execute();
						endif;
					else:
					//check user type
						if($reqData->usertype==0):
						//select data
							/*$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.input_4, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS username, USERS.`user_signature`, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_method_statement` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type AND DATE(REPORT.`mform_createdon`)>=:startdate AND DATE(REPORT.`mform_createdon`)<=:enddate INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`site_visibility`=:site_visibility ORDER BY REPORT.input_4 asc"; */
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.input_4, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS username, USERS.`user_signature`, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_method_statement` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type AND DATE(REPORT.`mform_createdon`)>=:startdate AND DATE(REPORT.`mform_createdon`)<=:enddate INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`site_visibility`=:site_visibility ORDER BY REPORT.mform_createdon asc";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		,$reqData->worktype);
								$stmt->bindParam("startdate", date("Y-m-d", strtotime($reqData->txtDateFrom)));
								$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
							$stmt->execute();
						else:
						//select data
							/* $sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.input_4, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS username, USERS.`user_signature`, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_method_statement` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type AND DATE(REPORT.`mform_createdon`)>=:startdate AND DATE(REPORT.`mform_createdon`)<=:enddate INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id ORDER BY REPORT.input_4 asc"; */
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.input_4, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS username, USERS.`user_signature`, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_method_statement` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type AND DATE(REPORT.`mform_createdon`)>=:startdate AND DATE(REPORT.`mform_createdon`)<=:enddate INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id ORDER BY REPORT.mform_createdon asc";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("startdate", date("Y-m-d", strtotime($reqData->txtDateFrom)));
								$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
								$stmt->bindParam("fk_user_id"		, $reqData->userid);
							$stmt->execute();
						endif;
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
 // get inspection report 
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
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "Admin Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Admin Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//database init
					$db	  = getDB();
				//check date
					if(empty($reqData->txtDateTo)):
					//check user type
						if($reqData->usertype==0):
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(PDF.pdf_created_on, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_inspection_form` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`site_visibility`=:site_visibility GROUP BY REPORT.`pdf_key` ORDER BY REPORT.iform_createdon DESC";

						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("site_visibility", $reqData->visibility);
							$stmt->execute();
						else:
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(PDF.pdf_created_on, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_inspection_form` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id GROUP BY REPORT.`pdf_key` ORDER BY REPORT.iform_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
								$stmt->bindParam("fk_user_id"		, $reqData->userid);
							$stmt->execute();
						endif;
					else:
					//check user type
						if($reqData->usertype==0):
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(PDF.pdf_created_on, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_inspection_form` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND DATE(REPORT.`iform_createdon`)>=:startdate AND DATE(REPORT.`iform_createdon`)<=:enddate INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`site_visibility`=:site_visibility GROUP BY REPORT.`pdf_key` ORDER BY REPORT.iform_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
					//		echo '<pre>';print_r($stmt);

								$stmt->bindParam("startdate",date("Y-m-d", strtotime($reqData->txtDateFrom)));
								$stmt->bindParam("enddate", date("Y-m-d", strtotime($reqData->txtDateTo)));
								$stmt->bindParam("site_visibility", $reqData->visibility);
							$stmt->execute();
						else:
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(PDF.pdf_created_on, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_inspection_form` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND DATE(REPORT.`iform_createdon`)>=:startdate AND DATE(REPORT.`iform_createdon`)<=:enddate INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id GROUP BY REPORT.`pdf_key` ORDER BY REPORT.iform_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("startdate",date("Y-m-d", strtotime($reqData->txtDateFrom)));
								$stmt->bindParam("enddate",date("Y-m-d", strtotime($reqData->txtDateTo)));
								$stmt->bindParam("site_visibility", $reqData->visibility);
								$stmt->bindParam("fk_user_id", $reqData->userid);
							$stmt->execute();
						endif;
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

//get inspection report  17-02-2020

	function get_inspection_report_old_backup($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->txtDateFrom)? $reqData->txtDateFrom	: $error[]	= "Start Date";
		isset($reqData->txtDateTo)	? $reqData->txtDateTo	: $error[]	= "End Date";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "Admin Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Admin Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//database init
					$db	  = getDB();
				//check date
					if(empty($reqData->txtDateTo)):
					//check user type
						if($reqData->usertype==0):
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(PDF.pdf_created_on, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_inspection_form` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`site_visibility`=:site_visibility GROUP BY REPORT.`pdf_key` ORDER BY REPORT.iform_createdon asc";

						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("site_visibility", $reqData->visibility);
							$stmt->execute();
						else:
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(PDF.pdf_created_on, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_inspection_form` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id GROUP BY REPORT.`pdf_key` ORDER BY REPORT.iform_createdon asc";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
								$stmt->bindParam("fk_user_id"		, $reqData->userid);
							$stmt->execute();
						endif;
					else:
					//check user type
						if($reqData->usertype==0):
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(PDF.pdf_created_on, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_inspection_form` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND DATE(REPORT.`iform_createdon`)>=:startdate AND DATE(REPORT.`iform_createdon`)<=:enddate INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`site_visibility`=:site_visibility GROUP BY REPORT.`pdf_key` ORDER BY REPORT.iform_createdon asc";
						//prepare statement
							$stmt = $db->prepare($sql);
					//		echo '<pre>';print_r($stmt);

								$stmt->bindParam("startdate",date("Y-m-d", strtotime($reqData->txtDateFrom)));
								$stmt->bindParam("enddate", date("Y-m-d", strtotime($reqData->txtDateTo)));
								$stmt->bindParam("site_visibility", $reqData->visibility);
							$stmt->execute();
						else:
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(PDF.pdf_created_on, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_inspection_form` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND DATE(REPORT.`iform_createdon`)>=:startdate AND DATE(REPORT.`iform_createdon`)<=:enddate INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id GROUP BY REPORT.`pdf_key` ORDER BY REPORT.iform_createdon asc";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("startdate",date("Y-m-d", strtotime($reqData->txtDateFrom)));
								$stmt->bindParam("enddate",date("Y-m-d", strtotime($reqData->txtDateTo)));
								$stmt->bindParam("site_visibility", $reqData->visibility);
								$stmt->bindParam("fk_user_id", $reqData->userid);
							$stmt->execute();
						endif;
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
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "Admin Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Admin Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//initiate db
					$db	  = getDB();
				//check date
					if(empty($reqData->txtDateTo)):
					//check user type
						if($reqData->usertype==0):
						//select data
							$sql	= "SELECT REPORT.*, FORMS.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.mtform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_tool_boxtalk_master` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type INNER JOIN `nman_tool_boxtalk_form` AS FORMS ON FORMS.`fk_mtform_id`=REPORT.`pk_mtform_id` INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`site_visibility`=:site_visibility ORDER BY REPORT.mtform_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
							$stmt->execute();
						else:
						//select data
							$sql	= "SELECT REPORT.*, FORMS.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.mtform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_tool_boxtalk_master` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type INNER JOIN `nman_tool_boxtalk_form` AS FORMS ON FORMS.`fk_mtform_id`=REPORT.`pk_mtform_id` INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id ORDER BY REPORT.mtform_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
								$stmt->bindParam("fk_user_id"		, $reqData->userid);
							$stmt->execute();
						endif;
					else:
					//check user type
						if($reqData->usertype==0):
						//select data
							$sql	= "SELECT REPORT.*, FORMS.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.mtform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_tool_boxtalk_master` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type AND DATE(REPORT.`mtform_createdon`)>=:startdate AND DATE(REPORT.`mtform_createdon`)<=:enddate  INNER JOIN `nman_tool_boxtalk_form` AS FORMS ON FORMS.`fk_mtform_id`=REPORT.`pk_mtform_id` INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`site_visibility`=:site_visibility ORDER BY REPORT.mtform_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($reqData->txtDateFrom)));
								$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
							$stmt->execute();
						else:
						//select data
							$sql	= "SELECT REPORT.*, FORMS.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.mtform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_tool_boxtalk_master` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type AND DATE(REPORT.`mtform_createdon`)>=:startdate AND DATE(REPORT.`mtform_createdon`)<=:enddate  INNER JOIN `nman_tool_boxtalk_form` AS FORMS ON FORMS.`fk_mtform_id`=REPORT.`pk_mtform_id` INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id ORDER BY REPORT.mtform_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($reqData->txtDateFrom)));
								$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
								$stmt->bindParam("fk_user_id"		, $reqData->userid);
							$stmt->execute();
						endif;
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
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "Admin Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Admin Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//initiate db
					$db	  = getDB();
				//check date
					if(empty($reqData->txtDateTo)):
					//check user type
						if($reqData->usertype==0):
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.wei_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_work_equipment_inspection` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`site_visibility`=:site_visibility GROUP BY REPORT.`pdf_key` ORDER BY REPORT.wei_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
							$stmt->execute();
						else:
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.wei_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_work_equipment_inspection` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id GROUP BY REPORT.`pdf_key` ORDER BY REPORT.wei_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
								$stmt->bindParam("fk_user_id"		, $reqData->userid);
							$stmt->execute();
						endif;
					else:
					//check user type
						if($reqData->usertype==0):
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.wei_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_master` AS SITES INNER JOIN `nman_work_equipment_inspection` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type AND DATE(REPORT.`wei_createdon`)>=:startdate AND DATE(REPORT.`wei_createdon`)<=:enddate INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE SITES.`site_visibility`=:site_visibility GROUP BY REPORT.`pdf_key` ORDER BY REPORT.wei_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($reqData->txtDateFrom)));
								$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
							$stmt->execute();
						else:
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername, SITES.`site_plot_no` AS plotno, DATE_FORMAT(REPORT.wei_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inpectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_work_equipment_inspection` AS REPORT ON REPORT.`fk_site_id`=SITES.`pk_site_id` AND REPORT.`work_type`=:work_type AND DATE(REPORT.`wei_createdon`)>=:startdate AND DATE(REPORT.`wei_createdon`)<=:enddate INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id GROUP BY REPORT.`pdf_key` ORDER BY REPORT.wei_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($reqData->txtDateFrom)));
								$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
								$stmt->bindParam("fk_user_id"		, $reqData->userid);
							$stmt->execute();
						endif;
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
	// get new safety harness inspection report
	 function get_safety_harness_report($request,$response)	
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
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "Admin Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Admin Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//initiate db
					$db	  = getDB();
				//check date
					if(empty($reqData->txtDateTo)):
					//select data

					/*	$sql	= "SELECT REPORT.*, DATE_FORMAT(REPORT.date_of_birth, '%d/%m/%Y') AS dob, DATE_FORMAT(REPORT.date, '%d/%m/%Y') AS createdon, DATE_FORMAT(REPORT.card_expiry_date, '%d/%m/%Y') AS expiredon, PDF.`pdf_name` AS pdfname FROM `nman_new_starter_form` AS REPORT LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key`  WHERE  REPORT.`ns_visibility`=1 AND 
					REPORT.`work_type`=:work_type ORDER BY REPORT.date asc";   */
						$sql	= "SELECT REPORT.*, DATE_FORMAT(REPORT.shiform_created_on, '%d/%m/%Y') AS createdon, DATE_FORMAT(REPORT.inspected_on, '%d/%m/%Y') AS inspected_on,PDF.`pdf_name` AS pdfname FROM `nman_safety_harness_inspection_forms` AS REPORT LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key`  WHERE  REPORT.`shiform_visibility`=1 AND REPORT.`work_type`=:work_type ORDER BY REPORT.shiform_created_on DESC"; 

					
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("work_type", $reqData->worktype);
						$stmt->execute();
					else:
					//select data
						$sql	= "SELECT REPORT.*,DATE_FORMAT(REPORT.shiform_created_on, '%d/%m/%Y') AS createdon, DATE_FORMAT(REPORT.inspected_on, '%d/%m/%Y') AS inspected_on, PDF.`pdf_name` AS pdfname FROM `nman_safety_harness_inspection_forms` AS REPORT LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE DATE(REPORT.shiform_created_on)>=:startdate AND DATE(REPORT.shiform_created_on)<=:enddate AND REPORT.`work_type`=:work_type  AND REPORT.`shiform_visibility`=1 ORDER BY REPORT.shiform_created_on DESC";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("work_type"	, $reqData->worktype);
							$stmt->bindParam("startdate"	, date("Y-m-d", strtotime($reqData->txtDateFrom)));
							$stmt->bindParam("enddate"		, date("Y-m-d", strtotime($reqData->txtDateTo)));
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
						$resultDetail	= [];
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
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "Admin Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Admin Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//initiate db
					$db	  = getDB();
				//check date
					if(empty($reqData->txtDateTo)):
					//select data
						/*$sql	= "SELECT REPORT.*, DATE_FORMAT(REPORT.date_of_birth, '%d/%m/%Y') AS dob, DATE_FORMAT(REPORT.date, '%d/%m/%Y') AS createdon, DATE_FORMAT(REPORT.card_expiry_date, '%d/%m/%Y') AS expiredon, PDF.`pdf_name` AS pdfname FROM `nman_new_starter_form` AS REPORT LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE REPORT.`ns_visibility`=1 AND REPORT.`work_type`=:work_type ORDER BY createdon asc"; */
						$sql	= "SELECT REPORT.*, DATE_FORMAT(REPORT.date_of_birth, '%d/%m/%Y') AS dob, DATE_FORMAT(REPORT.date, '%d/%m/%Y') AS createdon, DATE_FORMAT(REPORT.card_expiry_date, '%d/%m/%Y') AS expiredon, PDF.`pdf_name` AS pdfname FROM `nman_new_starter_form` AS REPORT LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key`  WHERE  REPORT.`ns_visibility`=1 AND REPORT.`work_type`=:work_type ORDER BY REPORT.date DESC"; 

						
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("work_type", $reqData->worktype);
						$stmt->execute();
					else:
					//select data
						$sql	= "SELECT REPORT.*, DATE_FORMAT(REPORT.date_of_birth, '%d/%m/%Y') AS dob, DATE_FORMAT(REPORT.date, '%d/%m/%Y') AS createdon, DATE_FORMAT(REPORT.card_expiry_date, '%d/%m/%Y') AS expiredon, PDF.`pdf_name` AS pdfname FROM `nman_new_starter_form` AS REPORT LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE DATE(REPORT.date)>=:startdate AND DATE(REPORT.date)<=:enddate AND REPORT.`work_type`=:work_type  AND REPORT.`ns_visibility`=1 ORDER BY REPORT.date DESC";
					//prepare statement
						$stmt = $db->prepare($sql);
							$stmt->bindParam("work_type"	, $reqData->worktype);
							$stmt->bindParam("startdate"	, date("Y-m-d", strtotime($reqData->txtDateFrom)));
							$stmt->bindParam("enddate"		, date("Y-m-d", strtotime($reqData->txtDateTo)));
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
						$resultDetail	= [];
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
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "Admin Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Admin Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//initiate db
					$db	  = getDB();
				//check date
					if(empty($reqData->txtDateTo)):
					//check user type
						if($reqData->usertype==0):
						//select data
							$sql	= "SELECT REPORT.*, SITE.`site_name` AS sitename, SITE.`site_owner_name` AS ownername,  DATE_FORMAT(REPORT.sform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inspectedby, PDF.`pdf_name` AS pdfname FROM `nman_supervisor_form` AS REPORT INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` INNER JOIN `nman_site_master` AS SITE ON SITE.`pk_site_id`=REPORT.`fk_site_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE REPORT.`sform_visibility`=:sform_visibility AND REPORT.`work_type`=:work_type ORDER BY REPORT.sform_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("sform_visibility"	, $reqData->visibility);
							$stmt->execute();
						else:
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername,  DATE_FORMAT(REPORT.sform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inspectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_supervisor_form` AS REPORT INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id AND REPORT.`work_type`=:work_type ORDER BY REPORT.sform_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
								$stmt->bindParam("fk_user_id"		, $reqData->userid);
							$stmt->execute();
						endif;
					else:
					//check user type
						if($reqData->usertype==0):
						//select data
							$sql	= "SELECT REPORT.*, SITE.`site_name` AS sitename, SITE.`site_owner_name` AS ownername, DATE_FORMAT(REPORT.sform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inspectedby, PDF.`pdf_name` AS pdfname FROM `nman_supervisor_form` AS REPORT INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` INNER JOIN `nman_site_master` AS SITE ON SITE.`pk_site_id`=REPORT.`fk_site_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE DATE(REPORT.`sform_createdon`)>=:startdate AND DATE(REPORT.`sform_createdon`)<=:enddate AND REPORT.`sform_visibility`=:sform_visibility AND REPORT.`work_type`=:work_type ORDER BY REPORT.sform_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("sform_visibility"	, $reqData->visibility);
								$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($reqData->txtDateFrom)));
								$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
							$stmt->execute();
						else:
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername,  DATE_FORMAT(REPORT.sform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inspectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_supervisor_form` AS REPORT INNER JOIN `nman_user_master` AS USERS On USERS.`pk_user_id`=REPORT.`fk_user_id` AND DATE(REPORT.`sform_createdon`)>=:startdate AND DATE(REPORT.`sform_createdon`)<=:enddate LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id AND REPORT.`work_type`=:work_type ORDER BY REPORT.sform_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($reqData->txtDateFrom)));
								$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
								$stmt->bindParam("fk_user_id"		, $reqData->userid);
							$stmt->execute();
						endif;
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
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "Admin Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Admin Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//initiate db
					$db	  = getDB();
				//check date
					if(empty($reqData->txtDateTo)):
					//check user type
						if($reqData->usertype==0):
						//select data
							$sql	= "SELECT REPORT.*, SITE.`site_name` AS sitename, SITE.`site_owner_name` AS ownername,  DATE_FORMAT(REPORT.dform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inspectedby, PDF.`pdf_name` AS pdfname FROM `nman_daywork_sheet_form` AS REPORT INNER JOIN `nman_user_master` AS USERS On USERS.`pk_user_id`=REPORT.`fk_user_id` INNER JOIN `nman_site_master` AS SITE ON SITE.`pk_site_id`=REPORT.`fk_site_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE REPORT.`dform_visibility`=:dform_visibility AND REPORT.`work_type`=:work_type ORDER BY REPORT.dform_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("dform_visibility"	, $reqData->visibility);
							$stmt->execute();
						else:
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername,  DATE_FORMAT(REPORT.dform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inspectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_daywork_sheet_form` AS REPORT INNER JOIN `nman_user_master` AS USERS On USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id AND REPORT.`work_type`=:work_type ORDER BY REPORT.dform_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
								$stmt->bindParam("fk_user_id"		, $reqData->userid);
							$stmt->execute();
						endif;
					else:
					//check user type
						if($reqData->usertype==0):
						//select data
							$sql	= "SELECT REPORT.*, SITE.`site_name` AS sitename, SITE.`site_owner_name` AS ownername, DATE_FORMAT(REPORT.dform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inspectedby, PDF.`pdf_name` AS pdfname FROM `nman_daywork_sheet_form` AS REPORT INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` INNER JOIN `nman_site_master` AS SITE ON SITE.`pk_site_id`=REPORT.`fk_site_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE DATE(REPORT.`dform_createdon`)>=:startdate AND DATE(REPORT.`dform_createdon`)<=:enddate AND REPORT.`dform_visibility`=:dform_visibility AND REPORT.`work_type`=:work_type ORDER BY REPORT.dform_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("dform_visibility"	, $reqData->visibility);
								$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($reqData->txtDateFrom)));
								$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
							$stmt->execute();
						else:
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername,  DATE_FORMAT(REPORT.dform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inspectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_daywork_sheet_form` AS REPORT INNER JOIN `nman_user_master` AS USERS On USERS.`pk_user_id`=REPORT.`fk_user_id` AND DATE(REPORT.`dform_createdon`)>=:startdate AND DATE(REPORT.`dform_createdon`)<=:enddate LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id AND REPORT.`work_type`=:work_type ORDER BY REPORT.dform_createdon DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($reqData->txtDateFrom)));
								$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
								$stmt->bindParam("fk_user_id"		, $reqData->userid);
							$stmt->execute();
						endif;
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
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "Admin Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Admin Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
				try {
				//initiate db
					$db	  = getDB();
				//check date
					if(empty($reqData->txtDateTo)):
					//check user type
						if($reqData->usertype==0):
						//select data
							$sql	= "SELECT REPORT.*, SITE.`site_name` AS sitename, SITE.`site_owner_name` AS ownername,  DATE_FORMAT(REPORT.rform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inspectedby, PDF.`pdf_name` AS pdfname FROM `nman_request_information_form` AS REPORT INNER JOIN `nman_user_master` AS USERS On USERS.`pk_user_id`=REPORT.`fk_user_id` INNER JOIN `nman_site_master` AS SITE ON SITE.`pk_site_id`=REPORT.`fk_site_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE REPORT.`rform_visibility`=:rform_visibility AND REPORT.`work_type`=:work_type ORDER BY pk_rform_id DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("rform_visibility"	, $reqData->visibility);
							$stmt->execute();
						else:
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername,  DATE_FORMAT(REPORT.rform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inspectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_request_information_form` AS REPORT INNER JOIN `nman_user_master` AS USERS On USERS.`pk_user_id`=REPORT.`fk_user_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id AND REPORT.`work_type`=:work_type ORDER BY pk_rform_id DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
								$stmt->bindParam("fk_user_id"		, $reqData->userid);
							$stmt->execute();
						endif;
					else:
					//check user type
						if($reqData->usertype==0):
						//select data
							

							$sql	= "SELECT REPORT.*, SITE.`site_name` AS sitename, SITE.`site_owner_name` AS ownername, DATE_FORMAT(REPORT.rform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inspectedby, PDF.`pdf_name` AS pdfname FROM `nman_request_information_form` AS REPORT INNER JOIN `nman_user_master` AS USERS ON USERS.`pk_user_id`=REPORT.`fk_user_id` INNER JOIN `nman_site_master` AS SITE ON SITE.`pk_site_id`=REPORT.`fk_site_id` LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE DATE(REPORT.`rform_createdon`)>=:startdate AND DATE(REPORT.`rform_createdon`)<=:enddate AND REPORT.`rform_visibility`=:rform_visibility AND REPORT.`work_type`=:work_type ORDER BY pk_rform_id DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("rform_visibility"	, $reqData->visibility);
								$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($reqData->txtDateFrom)));
								$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
							$stmt->execute();
						else:
						//select data
							$sql	= "SELECT REPORT.*, SITES.`site_name` AS sitename, SITES.`site_owner_name` AS ownername,  DATE_FORMAT(REPORT.rform_createdon, '%d/%m/%Y') AS createdon, CONCAT(USERS.`user_first_name`, ' ', USERS.`user_last_name`) AS inspectedby, PDF.`pdf_name` AS pdfname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES ON SITES.`pk_site_id`=ASSIGN.`fk_site_id` AND SITES.`site_visibility`=:site_visibility INNER JOIN `nman_request_information_form` AS REPORT INNER JOIN `nman_user_master` AS USERS On USERS.`pk_user_id`=REPORT.`fk_user_id` AND DATE(REPORT.`rform_createdon`)>=:startdate AND DATE(REPORT.`rform_createdon`)<=:enddate LEFT JOIN `nman_pdf_master` AS PDF ON PDF.`pdf_key`=REPORT.`pdf_key` WHERE ASSIGN.`fk_user_id`=:fk_user_id AND REPORT.`work_type`=:work_type ORDER BY pk_rform_id DESC";
						//prepare statement
							$stmt = $db->prepare($sql);
								$stmt->bindParam("work_type"		, $reqData->worktype);
								$stmt->bindParam("startdate"		, date("Y-m-d", strtotime($reqData->txtDateFrom)));
								$stmt->bindParam("enddate"			, date("Y-m-d", strtotime($reqData->txtDateTo)));
								$stmt->bindParam("site_visibility"	, $reqData->visibility);
								$stmt->bindParam("fk_user_id"		, $reqData->userid);
							$stmt->execute();
						endif;
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

//view all harness details
	function get_harness_list($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->visibility)	? $reqData->visibility	: $error[]	= "Harness status";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "User id";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Mobile token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//select data
					$sltsql = "SELECT `nman_safety_harness_details`.`pk_sdform_id` AS harnessid, `nman_safety_harness_details`.`id_no` AS idno, `nman_safety_harness_details`.`make`, `nman_safety_harness_details`.`model`, `nman_safety_harness_details`.`serial_no` AS serialno, `nman_safety_harness_details`.`date_of_manufacture` AS dateofmanufacture, `nman_safety_harness_details`.`purchase_date` AS purchasedate, `nman_safety_harness_details`.`owner`, `nman_safety_harness_details`.`inspection_frequency` AS inspectionfrequency, `nman_safety_harness_details`.`sdform_visibility` AS status  FROM `nman_safety_harness_details` WHERE `sdform_visibility`=:sdform_visibility";
				//prepare statement
					$sltstmt 	= $db->prepare($sltsql);
						$sltstmt->bindParam("sdform_visibility", $reqData->visibility);
					$sltstmt->execute();
				//check duplicate site entry
					if($sltstmt->rowCount() > 0):
					//fetch result
						$status		= "true";
						$statuscode	= "200";
					//fetch data
						$resultData = $sltstmt->fetchAll(PDO::FETCH_OBJ);
					else:
						$resultData["message"]= "Record not found";
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

//view harness details info
	function get_harness_info($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->txtHarnessId)	? $reqData->txtHarnessId	: $error[]	= "Harness Id";
		isset($reqData->userid)			? $reqData->userid			: $error[]	= "User id";
		isset($reqData->session)		? $reqData->session			: $error[]	= "Session token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//select data
					$sltsql = "SELECT *, DATE_FORMAT(`date_of_manufacture`, '%d/%m/%Y') AS dom, DATE_FORMAT(`purchase_date`, '%d/%m/%Y') AS dop FROM `nman_safety_harness_details` WHERE `pk_sdform_id`=:pk_sdform_id";
				//prepare statement
					$sltstmt 	= $db->prepare($sltsql);
						$sltstmt->bindParam("pk_sdform_id", $reqData->txtHarnessId);
					$sltstmt->execute();
				//check duplicate site entry
					if($sltstmt->rowCount() > 0):
					//fetch result
						$status		= "true";
						$statuscode	= "200";
					//fetch data
						$resultData = $sltstmt->fetch(PDO::FETCH_OBJ);
					else:
						$resultData["message"]= "Record not found";
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

//add safty harness details
	function add_harness_info($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->txtOwnerName)	? $reqData->txtOwnerName: $error[]	= "Owner Name";
		isset($reqData->txtIDNo)		? $reqData->txtIDNo		: $error[]	= "ID No.";
		isset($reqData->txtMake)		? $reqData->txtMake		: $error[]	= "Make";
		isset($reqData->txtModel)		? $reqData->txtModel	: $error[]	= "Model";
		isset($reqData->txtSerialNo)	? $reqData->txtSerialNo	: $error[]	= "Serial Number";
		isset($reqData->txtDOM)			? $reqData->txtDOM		: $error[]	= "Date of Manufacture";
		isset($reqData->txtDOP)			? $reqData->txtDOP		: $error[]	= "Date of Purchase";
		isset($reqData->txtFrequency)	? $reqData->txtFrequency: $error[]	= "Inspection Frequency";
		isset($reqData->userid)			? $reqData->userid		: $error[]	= "User id";
		isset($reqData->session)		? $reqData->session		: $error[]	= "Mobile token";
		//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//select data
					$sltsql = "SELECT * FROM `nman_safety_harness_details` WHERE `id_no`=:id_no";
				//prepare statement
					$sltstmt 	= $db->prepare($sltsql);
						$sltstmt->bindParam("id_no"	, $reqData->txtIDNo);
					$sltstmt->execute();
				//check duplicate site entry
					if($sltstmt->rowCount() == 0):
					//insert master data
						$sql = "INSERT INTO `nman_safety_harness_details` (`id_no`, `make`, `model`, `serial_no`, `date_of_manufacture`, `purchase_date`, `owner`, `inspection_frequency`) VALUES (:id_no, :make, :model, :serial_no, :date_of_manufacture, :purchase_date, :owner, :inspection_frequency)";
					//prepare statement						
						$stmt 	= $db->prepare($sql);
							$stmt->bindParam("id_no"				, $reqData->txtIDNo);
							$stmt->bindParam("make"					, $reqData->txtMake);
							$stmt->bindParam("model"				, $reqData->txtModel);
							$stmt->bindParam("serial_no"			, $reqData->txtSerialNo);
							$stmt->bindParam("date_of_manufacture"	, date("Y-m-d", strtotime($reqData->txtDOM)));
							$stmt->bindParam("purchase_date"		, date("Y-m-d", strtotime($reqData->txtDOP)));
							$stmt->bindParam("owner"				, $reqData->txtOwnerName);
							$stmt->bindParam("inspection_frequency"	, $reqData->txtFrequency);
						$stmt->execute();
					//last inserted data
						$pk_sdform_id	= $db->lastInsertId();
					//fetch result
						$status		= "true";
						$statuscode	= "200";
						$resultData->message= "Harness details added successfully";
					else:
						$resultData->message= "ID.No already exists";
					endif;
				//clear db
					$db 	= null;
				}
				catch(PDOException $e) {
					$resultData->message= $e->getMessage();
				}
			else:
				$statuscode	= "202";
				$resultData->message= "Your session expired. You will be redirected to the login page.";
			endif;
		//push values to array
			array_push($resturnData, $resultData);
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resturnData);
		endif;
	//return result
		echo json_encode($response);
	}

//edit safty harness details
	function edit_harness_info($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->txtIDNo)	? $reqData->txtIDNo	:$error[]		= "ID No.";
		isset($reqData->txtMake)	? $reqData->txtMake	:$error[]		= "Make";
		isset($reqData->txtModel)	? $reqData->txtModel:$error[]		= "Model";
		isset($reqData->txtSerialNo)? $reqData->txtSerialNo:$error[]	= "Serial No.";
		isset($reqData->txtDOM)		? $reqData->txtDOM	:$error[]		= "Date of Manufacture";
		isset($reqData->txtDOP)		? $reqData->txtDOP	:$error[]		= "Purchase Date";
		isset($reqData->txtOwnerName)? $reqData->txtOwnerName:$error[]	= "Owner Name";
		isset($reqData->txtFrequency)? $reqData->txtFrequency:$error[]	= "Frequency";
		isset($reqData->txtStatus)	? $reqData->txtStatus:$error[]		= "Status";
		isset($reqData->txtHarnessId)? $reqData->txtHarnessId:$error[]	= "Harness Id";
		isset($reqData->userid)		? $reqData->userid	:$error[]		= "User id";
		isset($reqData->session)	? $reqData->session	: $error[]		= "Session token";
		//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//select data
					$sltsql = "SELECT * FROM `nman_safety_harness_details` WHERE `id_no`=:id_no AND `pk_sdform_id`!=:pk_sdform_id";
				//prepare statement
					$sltstmt 	= $db->prepare($sltsql);
						$sltstmt->bindParam("pk_sdform_id"	, $reqData->txtHarnessId);
						$sltstmt->bindParam("id_no"			, $reqData->txtIDNo);
					$sltstmt->execute();
				//check duplicate site entry
					if($sltstmt->rowCount() == 0):
					//insert master data
						$updsql = "UPDATE `nman_safety_harness_details` SET `id_no`=:id_no, `make`=:make, `model`=:model, `serial_no`=:serial_no, `date_of_manufacture`=:date_of_manufacture, `purchase_date`=:purchase_date, `owner`=:owner, `inspection_frequency`=:inspection_frequency, `sdform_visibility`=:sdform_visibility  WHERE `pk_sdform_id`=:pk_sdform_id";
					//prepare statement
						$stmt 	= $db->prepare($updsql);
							$stmt->bindParam("pk_sdform_id"			, $reqData->txtHarnessId);
							$stmt->bindParam("id_no"				, $reqData->txtIDNo);
							$stmt->bindParam("make"					, $reqData->txtMake);
							$stmt->bindParam("model"				, $reqData->txtModel);
							$stmt->bindParam("serial_no"			, $reqData->txtSerialNo);
							$stmt->bindParam("date_of_manufacture"	, date("Y-m-d", strtotime($reqData->txtDOM)));
							$stmt->bindParam("purchase_date"		, date("Y-m-d", strtotime($reqData->txtDOP)));
							$stmt->bindParam("owner"				, $reqData->txtOwnerName);
							$stmt->bindParam("inspection_frequency"	, $reqData->txtFrequency);
							$stmt->bindParam("sdform_visibility"	, $reqData->txtStatus);
						$stmt->execute();
					//fetch result
						$status		= "true";
						$statuscode	= "200";
						$resultData->message= "Harness details updated successfully";
					else:
						$resultData->message= "ID.No already exists";
					endif;
				//clear db
					$db 	= null;
				}
				catch(PDOException $e) {
					$resultData->message= $e->getMessage();
				}
			else:
				$statuscode	= "202";
				$resultData->message= "Your session expired. You will be redirected to the login page.";
			endif;
		//push values to array
			array_push($resturnData, $resultData);
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resturnData);
		endif;
	//return result
		echo json_encode($response);
	}

//harness visibility update
	function update_harness_visibility($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->txtHarnessId)	? $reqData->txtHarnessId	: $error[]	= "Harness Id";
		isset($reqData->txtVisibility)	? $reqData->txtVisibility	: $error[]	= "Visibility";
		isset($reqData->userid)			? $reqData->userid			: $error[]	= "User Name";
		isset($reqData->session)		? $reqData->session			: $error[]	= "Session Token";
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
					$sql	= "UPDATE `nman_safety_harness_details` SET `sdform_visibility`=:sdform_visibility WHERE `pk_sdform_id`=:pk_sdform_id";
				//prepare statement
					$stmt = $db->prepare($sql);
						$stmt->bindParam("sdform_visibility", $reqData->txtVisibility);
						$stmt->bindParam("pk_sdform_id"		, $reqData->txtHarnessId);
					$stmt->execute();
				//fetch data
					$status			= "true";
					$statuscode		= "200";
					$resultDetail->message= "Harness visibility changed successfully!";
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

//add new slider image
	function add_slider_images($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->txtSliderImg)	? $reqData->txtSliderImg	: $error[]	= "User id";
		isset($reqData->userid)			? $reqData->userid			: $error[]	= "User id";
		isset($reqData->session)		? $reqData->session 		: $error[]	= "User token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//try block
			try {
			//verify user token
				$checktoken = verify_session_token($reqData->userid, $reqData->session);
			//check token
				if($checktoken > 0):
				//database initialization
					$db 		= getDB();
				//SQL query insert data
					$sql = "INSERT INTO `nman_slider_images` (`slider_image`) VALUES (:slider_image)";
				//prepare statement
					$stmt= $db->prepare($sql);
						$stmt->bindParam("slider_image"	, $reqData->txtSliderImg);
					$stmt->execute();
				//fetch last inserted data
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "Image uploaded successfully";
				//clear database initialization
					$db = null;
				else:
					$statuscode	= "202";
					$resultData->message= "Your session expired. You will be redirected to the login page.";
				endif;
			}
			catch(PDOException $e) {
				$resultData->message= $e->getMessage();
			}
		//push values to array
			array_push($resturnData, $resultData);
		//prepare return string
			$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resturnData);
		endif;
	//return result
		echo json_encode($response);
	}

//get app slider images
	function get_slider_images($request, $response)
	{
		//variable declaration
		$reqData	= json_decode($request->getBody());
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->visibility)	? $reqData->visibility	: $error[]	= "Slider status";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "User id";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Mobile token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//select data
					$sltsql = "SELECT `nman_slider_images`.* FROM `nman_slider_images` WHERE `nman_slider_images`.`is_visible`=:is_visible";
				//prepare statement
					$sltstmt 	= $db->prepare($sltsql);
						$sltstmt->bindParam("is_visible", $reqData->visibility);
					$sltstmt->execute();
				//check duplicate site entry
					if($sltstmt->rowCount() > 0):
					//fetch result
						$status		= "true";
						$statuscode	= "200";
					//fetch data
						$resultData = $sltstmt->fetchAll(PDO::FETCH_OBJ);
					else:
						$resultData["message"]= "Record not found";
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

//get app slider images
	function get_slider_info($request, $response)
	{
		//variable declaration
		$reqData	= json_decode($request->getBody());
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->txtSliderId)? $reqData->txtSliderId	: $error[]	= "Slider Id";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "User id";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Mobile token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//select data
					$sltsql = "SELECT * FROM `nman_slider_images` WHERE `pk_slider_id`=:pk_slider_id";
				//prepare statement
					$sltstmt 	= $db->prepare($sltsql);
						$sltstmt->bindParam("pk_slider_id", $reqData->txtSliderId);
					$sltstmt->execute();
				//fetch result
					$status		= "true";
					$statuscode	= "200";
				//fetch data
					$resultData = $sltstmt->fetch(PDO::FETCH_OBJ);
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

//slider visibility update
	function update_slider_visibility($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
	//variable declaration
		$resultDetail= new stdClass();
	//POST data
		isset($reqData->txtSliderId)	? $reqData->txtSliderId		: $error[]	= "Slider Image Id";
		isset($reqData->txtVisibility)	? $reqData->txtVisibility	: $error[]	= "Visibility";
		isset($reqData->userid)			? $reqData->userid			: $error[]	= "User Name";
		isset($reqData->session)		? $reqData->session			: $error[]	= "Session Token";
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
					$sql	= "UPDATE `nman_slider_images` SET `is_visible`=:is_visible WHERE `pk_slider_id`=:pk_slider_id";
				//prepare statement
					$stmt = $db->prepare($sql);
						$stmt->bindParam("is_visible"	, $reqData->txtVisibility);
						$stmt->bindParam("pk_slider_id"	, $reqData->txtSliderId);
					$stmt->execute();
				//fetch data
					$status			= "true";
					$statuscode		= "200";
					$resultDetail->message= "Slider image visibility changed successfully!";
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

//view all menu details
	function get_menu_list($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->visibility)	? $reqData->visibility	: $error[]	= "Menu status";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "User id";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Mobile token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//select data
					$sltsql = "SELECT `nman_menu_master`.* FROM `nman_menu_master` WHERE `nman_menu_master`.`menu_visibility`=:menu_visibility";
				//prepare statement
					$sltstmt 	= $db->prepare($sltsql);
						$sltstmt->bindParam("menu_visibility", $reqData->visibility);
					$sltstmt->execute();
				//check duplicate site entry
					if($sltstmt->rowCount() > 0):
					//fetch result
						$status		= "true";
						$statuscode	= "200";
					//fetch data
						$resultData = $sltstmt->fetchAll(PDO::FETCH_OBJ);
					else:
						$resultData["message"]= "Record not found";
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

//view assigned menu 
	function get_assigned_menu($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->viewuserid)	? $reqData->viewuserid	: $error[]	= "User ID";
		isset($reqData->visibility)	? $reqData->visibility	: $error[]	= "Menu status";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "User ID";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Mobile token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//select menu
					$sltmenu = "SELECT `fk_menu_id` FROM `nman_menu_assignment` WHERE `fk_user_id`=:fk_user_id AND `assign_visibility`=:assign_visibility";
					$menustmt 	= $db->prepare($sltmenu);
						$menustmt->bindParam("fk_user_id"		, $reqData->viewuserid);
						$menustmt->bindParam("assign_visibility", $reqData->visibility);
					$menustmt->execute();
				//fetch menu
					$resultMenu = $menustmt->fetch(PDO::FETCH_OBJ);
				//check menu assigned
					if($menustmt->rowCount() > 0):
					//select data
						$sltsql = "SELECT * FROM `nman_menu_master` WHERE `pk_menu_id` IN (".$resultMenu->fk_menu_id.") AND `menu_visibility`=:menu_visibility";
					//prepare statement
						$sltstmt 	= $db->prepare($sltsql);
							$sltstmt->bindParam("menu_visibility"	, $reqData->visibility);
						$sltstmt->execute();
					//fetch data
						$resultData = $sltstmt->fetchAll(PDO::FETCH_OBJ);
					//fetch result
						$status		= "true";
						$statuscode	= "200";
					else:
						$resultData["message"]= "Menu Not Assigned";
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

//assign menu to users
	function add_menu_assign($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->userslist)	? $reqData->userslist	: $error[]	= "Users List";
		isset($reqData->menulist)	? $reqData->menulist	: $error[]	= "Menu List";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "User id";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Mobile token";
		//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//convert in to string
					$str_menu = implode(",", $reqData->menulist);
				//loop user list
					foreach($reqData->userslist AS $row=>$users):
					//select data
						$sltsql = "SELECT * FROM `nman_menu_assignment` WHERE `fk_user_id`=:fk_user_id";
					//prepare statement
						$sltstmt 	= $db->prepare($sltsql);
							$sltstmt->bindParam("fk_user_id", $users);
						$sltstmt->execute();
					//check duplicate site entry
						if($sltstmt->rowCount() == 0):
						//insert query
							$inssql = "INSERT INTO `nman_menu_assignment` (`fk_user_id`, `fk_menu_id`) VALUES (:fk_user_id, :fk_menu_id)";
						//prepare statement
							$insstmt 	= $db->prepare($inssql);
								$insstmt->bindParam("fk_user_id", $users);
								$insstmt->bindParam("fk_menu_id", $str_menu);
							$insstmt->execute();
						else:
						//update query
							$updsql = "UPDATE `nman_menu_assignment` SET `fk_menu_id`=:fk_menu_id WHERE `fk_user_id`=:fk_user_id";
						//prepare statement
							$updstmt 	= $db->prepare($updsql);
								$updstmt->bindParam("fk_menu_id", $str_menu);
								$updstmt->bindParam("fk_user_id", $users);
							$updstmt->execute();
						endif;
					endforeach;
				//fetch result
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "Menu assignment successfull";
				//clear db
					$db 	= null;
				}
				catch(PDOException $e) {
					$resultData->message= $e->getMessage();
				}
			else:
				$statuscode	= "202";
				$resultData->message= "Your session expired. You will be redirected to the login page.";
			endif;
		//push values to array
			array_push($resturnData, $resultData);
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resturnData);
		endif;
	//return result
		echo json_encode($response);
	}

//view assigned menu 
	function get_assigned_forms($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
		$visibility = "1";
	//JSON values
		isset($reqData->viewuserid)	? $reqData->viewuserid	: $error[]	= "User ID";
		isset($reqData->visibility)	? $reqData->visibility	: $error[]	= "Menu status";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "User ID";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Mobile token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//select menu
					$sltmenu = "SELECT `fk_form_id` FROM `nman_forms_assignment` WHERE `fk_user_id`=:fk_user_id AND `assign_visibility`=:assign_visibility";
					$menustmt 	= $db->prepare($sltmenu);
						$menustmt->bindParam("fk_user_id"		, $reqData->viewuserid);
						$menustmt->bindParam("assign_visibility", $visibility);
					$menustmt->execute();
				//fetch menu
					$resultMenu = $menustmt->fetch(PDO::FETCH_OBJ);
				//check menu assigned
					if($menustmt->rowCount() > 0):
					//select data
						$sltsql = "SELECT `pk_form_id` AS formid, `form_name` AS formname, `form_visibility` AS status, `work_type` AS worktype FROM `nman_form_master` WHERE `pk_form_id` IN (".$resultMenu->fk_form_id.") AND `form_visibility`=:form_visibility";
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

	//view assigned form for users
function get_user_assigned_forms($request, $response)
{
    //variable declaration
    $reqData	= json_decode($request->getBody());
    $resturnData= array();
    $status		= "false";
    $statuscode	= "201";
    //JSON values
    isset($reqData->viewsiteid)	? $reqData->viewsiteid	: $error[]	= "Site ID";
    isset($reqData->visibility)	? $reqData->visibility	: $error[]	= "Menu status";
    isset($reqData->userid)		? $reqData->userid		: $error[]	= "User ID";
    isset($reqData->session)	? $reqData->session		: $error[]	= "Mobile token";
    //check condition
    if(isset($error) && sizeof($error)):
        $response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
    else:
        //verify user token
        $checktoken = verify_session_token($reqData->userid, $reqData->session);
        //check token
        if($checktoken > 0):
            //try block
            try {
                //database initialization
                $db = getDB();
                //select menu
                $sltmenu = "SELECT `fk_form_id` FROM `nman_user_forms_assignment` WHERE `fk_site_id`=:fk_site_id AND `assign_visibility`=:assign_visibility";
                $menustmt 	= $db->prepare($sltmenu);
                $menustmt->bindParam("fk_site_id"		, $reqData->viewsiteid);
                $menustmt->bindParam("assign_visibility", $reqData->visibility);
                $menustmt->execute();
                //fetch menu
                $resultMenu = $menustmt->fetch(PDO::FETCH_OBJ);
                //check menu assigned
                if($menustmt->rowCount() > 0):
                    //select data
                    $sltsql = "SELECT `pk_form_id` AS formid, `form_name` AS formname, `form_visibility` AS status FROM `nman_form_master` WHERE `pk_form_id` IN (".$resultMenu->fk_form_id.") AND `form_visibility`=:form_visibility";
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

//assign forms to users
	function add_form_assign($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->userslist)	? $reqData->userslist	: $error[]	= "Users List";
		isset($reqData->formlist)	? $reqData->formlist	: $error[]	= "Forms List";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "User id";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Mobile token";
		//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//convert in to string
					$str_form = implode(",", $reqData->formlist);
				//loop user list
					foreach($reqData->userslist AS $row=>$users):
					//select data
						$sltsql = "SELECT * FROM `nman_forms_assignment` WHERE `fk_user_id`=:fk_user_id";
					//prepare statement
						$sltstmt 	= $db->prepare($sltsql);
							$sltstmt->bindParam("fk_user_id", $users);
						$sltstmt->execute();
					//check duplicate site entry
						if($sltstmt->rowCount() == 0):
						//insert query
							$inssql = "INSERT INTO `nman_forms_assignment` (`fk_user_id`, `fk_form_id`) VALUES (:fk_user_id, :fk_form_id)";
						//prepare statement
							$insstmt 	= $db->prepare($inssql);
								$insstmt->bindParam("fk_user_id", $users);
								$insstmt->bindParam("fk_form_id", $str_form);
							$insstmt->execute();
						else:
						//update query
							$updsql = "UPDATE `nman_forms_assignment` SET `fk_form_id`=:fk_form_id WHERE `fk_user_id`=:fk_user_id";
						//prepare statement
							$updstmt 	= $db->prepare($updsql);
								$updstmt->bindParam("fk_form_id", $str_form);
								$updstmt->bindParam("fk_user_id", $users);
							$updstmt->execute();
						endif;
					endforeach;
				//fetch result
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "Forms assignment successfull";
				//clear db
					$db 	= null;
				}
				catch(PDOException $e) {
					$resultData->message= $e->getMessage();
				}
			else:
				$statuscode	= "202";
				$resultData->message= "Your session expired. You will be redirected to the login page.";
			endif;
		//push values to array
			array_push($resturnData, $resultData);
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resturnData);
		endif;
	//return result
		echo json_encode($response);
	}

//assign forms to users
	function add_user_form_assign($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->siteslist)	? $reqData->siteslist	: $error[]	= "Sites List";
		isset($reqData->formlist)	? $reqData->formlist	: $error[]	= "Forms List";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "User id";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Mobile token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//convert in to string
					$str_form = implode(",", $reqData->formlist);
				//loop user list
					foreach($reqData->siteslist AS $row=>$sites):
					//select data
						$sltsql = "SELECT * FROM `nman_user_forms_assignment` WHERE `fk_site_id`=:fk_site_id";
					//prepare statement
						$sltstmt 	= $db->prepare($sltsql);
						$sltstmt->bindParam("fk_site_id", $sites);
						$sltstmt->execute();
					//check duplicate site entry
						if($sltstmt->rowCount() == 0):
						//insert query
							$inssql = "INSERT INTO `nman_user_forms_assignment` (`fk_site_id`, `fk_form_id`) VALUES (:fk_site_id, :fk_form_id)";
						//prepare statement
							$insstmt 	= $db->prepare($inssql);
							$insstmt->bindParam("fk_site_id", $sites);
							$insstmt->bindParam("fk_form_id", $str_form);
							$insstmt->execute();
						else:
						//update query
							$updsql = "UPDATE `nman_user_forms_assignment` SET `fk_form_id`=:fk_form_id WHERE `fk_site_id`=:fk_site_id";
						//prepare statement
							$updstmt 	= $db->prepare($updsql);
							$updstmt->bindParam("fk_form_id", $str_form);
							$updstmt->bindParam("fk_site_id", $sites);
							$updstmt->execute();
						endif;
					endforeach;
				//fetch result
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "Forms assignment successfull";
				//clear db
					$db 	= null;
				}
				catch(PDOException $e) {
					$resultData->message= $e->getMessage();
				}
			else:
				$statuscode	= "202";
				$resultData->message= "Your session expired. You will be redirected to the login page.";
			endif;
		//push values to array
			array_push($resturnData, $resultData);
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resturnData);
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
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "Admin Name";
		isset($reqData->session)	? $reqData->session		: $error[]	= "Admin Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->session);
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
					$html 	= file_get_contents(ConstantURL()."/report-templates/".$template[$reqData->txtFormId].".php?formid=".$reqData->txtFormId."&userid=".$reqData->userid."&usertype=".$reqData->usertype."&worktype=".$reqData->worktype."&from=".$reqData->txtDateFrom."&to=".$reqData->txtDateTo);
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
			//$mail->Host 		= 'smtp.gmail.com';  				// Specify main and backup SMTP servers

			$mail->Host 		= 'smtp.livemail.co.uk';  				// Specify main and backup SMTP servers
			$mail->SMTPAuth 	= true;                             // Enable SMTP authentication
			//$mail->Username 	= 'equatortestmail@gmail.com';      // SMTP user name
			//$mail->Password 	= 'Equator@6466';                   // SMTP password
			//$mail->Username 	= 'app@norman-group.com';      // SMTP user name
		//	$mail->Password 	= 'Normangroupapp9';          // SMTP password
		    $mail->Username 	= 'app@norman-group.com';      // SMTP user name
		    $mail->Password 	= 'Normangroupapp9';          // SMTP password
			$mail->SMTPSecure 	= 'tls';                            // Enable encryption, 'SSL' also accepted
			$mail->Port 		= 587;
		//	$mail->From 		= 'app@norman-group.com';
			$mail->From 		= 'app@norman-group.com';
			$mail->FromName 	= 'Norman Group';
			$mail->addAddress($emailData->email);     				// Add a recipient
			/*$mail->AddBCC("app@norman-group.com", "Norman Group");
			$mail->AddBCC("mns.ajaydave@gmail.com", "Norman Group");*/
			$mail->addBCC("app@norman-group.com", "Norman Group");
			//$mail->addBCC("mns.ajaydave@gmail.com", "Norman Group");

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
			$strOut = sprintf('http://%s:%d/',
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
	function verify_session_token($pk_user_id, $mobile_token)
	{
		try
		{
		//initiate db
			$db = getDB();
		//select query
			$sltsql = "SELECT * FROM `nman_user_master` WHERE `pk_user_id`=:pk_user_id  AND `mobile_token`=:mobile_token";
		//prepare select statement
			$stmt = $db->prepare($sltsql);
				$stmt->bindParam("pk_user_id"	, $pk_user_id);
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