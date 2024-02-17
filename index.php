<?php
											/******************************************************************
												project description:

												Project Name 	: Norman Group
												Created By		: Veerakumar (PDF Generation & PHP) & Rabert (IOS)
												Started On		: 20-12-2016
												Updated On		: 01-11-2018
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

//*********** POST method ***********//
//user signup and login
	$app->POST('/user_signup'		, 'user_signup');
	$app->POST('/user_login'		, 'user_login');
	$app->POST('/user_visibility'	, 'user_visibility');
	$app->POST('/login_session'		, 'login_session');

//profile edit, email edit, profile image edit, edit user password, edit user signature
	$app->POST('/update_profile'	, 'update_profile');
	$app->POST('/update_email'		, 'update_email');
	$app->POST('/update_img'		, 'update_img');
	$app->POST('/update_password'	, 'update_password');
	$app->POST('/reset_password'	, 'reset_password');
	$app->POST('/update_signature'	, 'update_signature');

//send OTP code
	$app->POST('/send_otp'			, 'send_otp');
	$app->POST('/reset_otp'			, 'reset_otp');
	$app->POST('/verify_otp'		, 'verify_otp');

//create user create, edit
	$app->POST('/create_user_type'	, 'create_user_type');
	$app->POST('/update_user_type'	, 'update_user_type');

//site create, edit, delete, assignment
	$app->POST('/site_create'		, 'site_create');
	$app->POST('/site_update'		, 'site_update');
	$app->POST('/site_visibility'	, 'site_visibility');
	$app->POST('/site_assignment'	, 'site_assignment');
	$app->POST('/site_reassignment'	, 'site_reassignment');
	$app->POST('/site_form_assignment', 'site_form_assignment');
	$app->POST('/site_signature'	, 'site_signature');
	$app->POST('/upload_site_doc'	, 'upload_site_doc');
	$app->POST('/remove_site_doc'	, 'remove_site_doc');
	$app->POST('/get_user_assigned_forms'	, 'get_user_assigned_forms');

//site supervisor form craete, edit
	$app->POST('/new_supervisor_form'		, 'new_supervisor_form');

//handover form create, edit
	$app->POST('/new_handover_certificate'	, 'new_handover_certificate');

//method form create, edit
	$app->POST('/new_method_statement'		, 'new_method_statement');

//inspection form create, edit
	$app->POST('/new_inspection_form'		, 'new_inspection_form');

//health assessment form create, edit
	$app->POST('/new_health_assessment'		, 'new_health_assessment');

//health assessment form create, edit
	$app->POST('/new_tool_boxtalk_form'		, 'new_tool_boxtalk_form');

//daywork sheet form create, edit
	$app->POST('/new_daywork_sheet_form'	, 'new_daywork_sheet_form');

//request for information form create, edit
	$app->POST('/new_request_information_form', 'new_request_information_form');

//new starter form create, edit
	$app->POST('/create_new_starter_form'	, 'create_new_starter_form');

//new starter form create, edit
	$app->POST('/add_harness_details'		, 'add_harness_details');
	$app->POST('/update_harness_details'	, 'update_harness_details');
	$app->POST('/visibility_harness_details', 'visibility_harness_details');
	$app->POST('/view_harness_list'			, 'view_harness_list');
	$app->POST('/create_safety_harness_inspection_forms', 'create_safety_harness_inspection_forms');
	$app->POST('/generate_pdf_file'			, 'generate_pdf_file');

//new work equipment inspection form create, edit
	$app->POST('/new_work_equipment_inspection', 'new_work_equipment_inspection');

//get assigned forms
	$app->POST('/get_assigned_forms'		, 'get_assigned_forms');

//update PDF notification
	$app->POST('/update_pdf_notification'	, 'update_pdf_notification');

//image upload
	$app->POST('/image_upload'				, 'image_upload');
	$app->POST('/upload_slider_images'		, 'upload_slider_images');
	
//get other pdf files
	$app->POST('/get_other_pdf_reports'		, 'get_other_pdf_reports');

//***********GET method***********//
	$app->GET('/create_pdf', 'create_pdf');
	$app->GET('/approve_user/{userid}'			, 'approve_user');
	$app->GET('/get_app_info'					, 'get_app_info');
	$app->GET('/get_all_users/{usertype}/{status}', 'get_all_users');
	$app->GET('/get_users_list/{usertype}'		, 'get_users_list');
	$app->GET('/user_info/{user_id}'			, 'user_info');
	$app->GET('/user_types'						, 'user_types');
	$app->GET('/user_types_status/{id}/{status}', 'user_types_status');
	$app->GET('/activation/{user_otp}'			, 'activation');
	$app->GET('/resetpassword/{user_otp}'		, 'resetpassword');
	$app->GET('/reset_cust_password/{site_otp}'	, 'reset_cust_password');
	$app->GET('/get_all_forms/{visible}[/{worktype}]', 'get_all_forms');
	$app->GET('/get_pdf_forms/{visible}/{formtype}', 'get_pdf_forms');
	$app->GET('/get_form_inputs/{form_id}'		, 'get_form_inputs');
	$app->GET('/get_all_sites/{userid}'			, 'get_all_sites');
	$app->GET('/get_unassigned_sites/{userid}/{usertype}/{worktype}', 'get_unassigned_sites');
	$app->GET('/get_assigned_sites/{userid}'	, 'get_assigned_sites');
	$app->GET('/get_assignment_info/{siteid}'	, 'get_assignment_info');
	$app->GET('/get_form_sites/{userid}/{formid}/{worktype}', 'get_form_sites');
	$app->GET('/get_new_sites/{userid}'			, 'get_new_sites');
	$app->GET('/get_sites_id/{siteid}'			, 'get_sites_id');
	$app->GET('/get_location'					, 'get_location');
	$app->GET('/get_toolbox_title/{work_type}/{visibility}'	, 'get_toolbox_title');
	$app->GET('/get_toolbox_topic/{work_type}/{visibility}'	, 'get_toolbox_topic');
	$app->GET('/get_sites_pdf/{siteid}/{formid}/{worktype}', 'get_sites_pdf');
	$app->GET('/get_sites_documents/{siteid}'	, 'get_sites_documents');
	$app->GET('/get_other_pdf/{userid}/{usertype}', 'get_other_pdf');
	$app->GET('/get_slider_images/{visibility}'	, 'get_slider_images');
	$app->GET('/remove_slider_images/{id}'		, 'remove_slider_images');
	$app->GET('/get_user_notification/{userid}'	, 'get_user_notification');
	$app->GET('/get_pdf_notification/{userid}'	, 'get_pdf_notification');
	$app->GET('/remove_site_image/{imgid}'		, 'remove_site_image');
	$app->GET('/check_mail', 'check_mail');
	$app->run();

//user registration
	function create_pdf()
	{
		$dompdf = new Dompdf($options);
	//read html content
		$html 	= '<table><tr><td>Hello World</td></tr></table>';

		
	    $dompdf->loadHtml($html);
	//(Optional) Setup the paper size and orientation
		$dompdf->setPaper($paper, $view);
	//Render the HTML as PDF
		$dompdf->render();
	//Output the generated PDF (1 = download and 0 = preview)
		$output = $dompdf->output();
		$filename="reports/test.pdf";
		 file_put_contents($filename,$output);
		 chmod($filename,0777);
	}
	function check_mail($request, $response)
	{				
				$status=0;
				$statuscode=1;
				$resultData=array();
				$resultData->message='';
				try {
		    	$emailData=new stdClass();
					//prepare email
						$emailData->Subject		= "E-mail Confirmation";
						$emailData->email		= 'mns.ajaydave@gmail.com';
						$emailData->user_otp	= '0014590';
						$username				='Ajay'." ".'dave';
						$verificationLink 		= "index.php/activation/". $user_otp;
					//get email contents
						$mailcontent			= file_get_contents('email_templates/account-verify.html');
						$emailData->body		= str_replace('{{verification-link}}', $verificationLink, $mailcontent);
					//callback send email function
						$mailCheck	= send_email_test($emailData);
						
						$statuscode='Successfully';
				}
				catch(PDOException $e) {
				$resultData->message= $e->getMessage();
			  }

			  	$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resturnData);
		
			//return result
				echo json_encode($response);

	}
	function user_signup($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->empid)?$reqData->empid:$error[]					= "User Employee id";
		isset($reqData->userfirstname)?$reqData->userfirstname:$error[]	= "User First Name";
		isset($reqData->userlastname)?$reqData->userlastname:$error[]	= "User Last Name";
		isset($reqData->useremail)?$reqData->useremail:$error[]			= "User Email";
		isset($reqData->userphone)?$reqData->userphone:$error[]			= "User Phone";
		isset($reqData->usertype)?$reqData->usertype:$error[]			= "User type";
		isset($reqData->worktype)?$reqData->worktype:$error[]			= "Work type";
		isset($reqData->userpassword)?$reqData->userpassword:$error[]	= "User Password";
		isset($reqData->userimg)?$reqData->userimg:$error[]				= "User Image";
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
				$selectsql 	= "SELECT * FROM `nman_user_master` WHERE `user_emp_id`=:user_emp_id OR `user_email`=:user_email OR `user_phone`=:user_phone";
			//prepare statement
				$sltstmt 	= $db->prepare($selectsql);
					$sltstmt->bindParam("user_emp_id"	, $reqData->empid);
					$sltstmt->bindParam("user_email"	, $reqData->useremail);
					$sltstmt->bindParam("user_phone"	, $reqData->userphone);
				$sltstmt->execute();
			//check email/phone duplication
				if($sltstmt->rowCount() == 0):
				//SQL query insert data
					$sql = "INSERT INTO `nman_user_master` (`user_emp_id`, `user_first_name`, `user_last_name`, `user_email`, `user_phone`, `user_password`, `user_img`, `fk_type_id`, `work_type`, `user_otp`, `user_created_on`, `notify_token`) VALUES (:user_emp_id, :user_first_name, :user_last_name, :user_email, :user_phone, :user_password, :user_img, :fk_type_id, :work_type, :user_otp, :user_created_on, :notify_token)";
				//prepare statement
					$stmt 	= $db->prepare($sql);
						$stmt->bindParam("user_emp_id"		, $reqData->empid);
						$stmt->bindParam("user_first_name"	, $reqData->userfirstname);
						$stmt->bindParam("user_last_name"	, $reqData->userlastname);
						$stmt->bindParam("user_email"		, $reqData->useremail);
						$stmt->bindParam("user_phone"		, $reqData->userphone);
						$stmt->bindParam("fk_type_id"		, $reqData->usertype);
						$stmt->bindParam("work_type"		, $reqData->worktype);
						$stmt->bindParam("user_password"	, md5(md5($reqData->userpassword)));
						$stmt->bindParam("user_img"			, $reqData->userimg);
						$stmt->bindParam("user_otp"			, $user_otp);
						$stmt->bindParam("user_created_on"	, date('Y-m-d H:i:s'));
						$stmt->bindParam("notify_token"		, $reqData->notificationtoken);
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
						$emailData->email		= $reqData->useremail;
						$emailData->user_otp	= $user_otp;
						$username				= $reqData->userfirstname." ".$reqData->userlastname;
						$verificationLink 		= ConstantURL()."index.php/activation/". $user_otp;
					//get email contents
						$mailcontent			= file_get_contents('email_templates/account-verify.html');
						$emailData->body		= str_replace('{{verification-link}}', $verificationLink, $mailcontent);
					//callback send email function
						$mailCheck	= send_email($emailData);
					//notification
						$sltsql 	= "SELECT * FROM `nman_user_master` WHERE `fk_type_id`=0 AND `user_visibility`=1";
					//prepare select statement
						$getstmt	= $db->prepare($sltsql);
						$getstmt->execute();
					//fetch data
						$fetchData	= $getstmt->fetch(PDO::FETCH_ASSOC);
					//send notification
						$usertype 	= ($reqData->usertype==1 ? "MANAGER" : "SUPERVISOR");
						$worktype 	= ($reqData->worktype==1 ? "SCAFFOLDING" : "BRICK WORK");
						$txtmessage = ($reqData->usertype==1 ? $reqData->userfirstname." ".$reqData->userlastname." registered as ".$usertype : $reqData->userfirstname." ".$reqData->userlastname." registered as ".$usertype." for ".$worktype);
					//send
						send_notification($fetchData["pk_user_id"], "New user", $txtmessage, "signup");
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
		//push values to array
			array_push($resturnData, $resultData);
		//prepare return string
			$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resturnData);
		endif;
	//return result
		echo json_encode($response);
	}

//user login
	function user_login($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
		isset($reqData->useremail)?$reqData->useremail:$error[]					= "User Email";
		isset($reqData->userpassword)?$reqData->userpassword:$error[]			= "User Password";
		isset($reqData->notificationtoken)?$reqData->notificationtoken:$error[]	= "Notification token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
			$user_email		= stripslashes($reqData->useremail);
			$user_password	= stripslashes(md5(md5($reqData->userpassword)));
			$mobile_token	= generate_mobile_token($reqData->useremail);
			$token_expired	= date("Y-m-d H:i:s", strtotime("+1 days"));
			try {
				$db	  = getDB();
			//select data
				$sql	= "SELECT *,DATE_FORMAT(user_dob, '%m/%d/%Y') AS user_dob FROM `nman_user_master` WHERE `user_email`=:user_email AND `user_password`=:user_password";
			//prepare statement
				$stmt = $db->prepare($sql);
					$stmt->bindParam("user_email"	, $user_email);
					$stmt->bindParam("user_password", $user_password);
				$stmt->execute();
			//fetch data
				$fetchData	= $stmt->fetch(PDO::FETCH_OBJ);
			//variable declaration
				$resultDetail= new stdClass();
				$resultData	= array();

			//check condition
				if($stmt->rowCount() > 0):
				//check user active/inactive
					if($fetchData->user_is_active == 1 && $fetchData->user_visibility == 1 && $fetchData->is_approved == 1):
						$resultDetail->userid		= $fetchData->pk_user_id;
						$resultDetail->userfirstname= $fetchData->user_first_name;
						$resultDetail->userlastname	= $fetchData->user_last_name;
						$resultDetail->useremail	= $fetchData->user_email;
						$resultDetail->emailverified= $fetchData->user_email_verified;
						$resultDetail->userphone	= $fetchData->user_phone;
						$resultDetail->userdob		= $fetchData->user_dob;
						$resultDetail->userimg		= $fetchData->user_img;
						$resultDetail->usertype		= $fetchData->fk_type_id;
						$resultDetail->worktype		= $fetchData->work_type;
						$resultDetail->usersignature= $fetchData->user_signature;
						$resultDetail->mobiletoken	= $mobile_token;
						$resultDetail->isapproved	= $fetchData->is_approved;
						$resultDetail->uservisibility= $fetchData->user_visibility;
						$status		= "true";
						$statuscode	= "200";
					//update mobile token
						$updsql = "UPDATE `nman_user_master` SET `mobile_token`=:mobile_token, `mobile_token_expired`=:mobile_token_expired, `notify_token`=:notify_token WHERE `pk_user_id`=:pk_user_id";
					//prepare statement
						$updstmt = $db->prepare($updsql);
							$updstmt->bindParam("mobile_token"			, $mobile_token);
							$updstmt->bindParam("mobile_token_expired"	, $token_expired);
							$updstmt->bindParam("notify_token"			, $reqData->notificationtoken);
							$updstmt->bindParam("pk_user_id" 			, $fetchData->pk_user_id);
						$updstmt->execute();
					elseif($fetchData->user_is_active	==	0):
						$resultDetail->message	= "User email not verified";
					elseif($fetchData->is_approved 		== 0):
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
		//push values to array
			array_push($resultData, $resultDetail);
		//clear database
			$db	= null;
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
		endif;
	//return result
		echo json_encode($response);
	}

//update user visibility
	function user_visibility($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->rmuserid)?$reqData->rmuserid:$error[]			= "Remove User Name";
		isset($reqData->visibility)?$reqData->visibility:$error[]		= "User visibility";
		isset($reqData->userid)?$reqData->userid:$error[]				= "User id";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]		= "User token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//try block
			try {
			//verify user token
				$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
			//check token
				if($checktoken > 0):
				//database initialization
					$db	= getDB();
				//SQL query insert data
					$sql= "UPDATE `nman_user_master` SET `user_visibility`=:user_visibility, `mobile_token`='' WHERE `pk_user_id`=:pk_user_id";
				//prepare update statement
					$stmt= $db->prepare($sql);
						$stmt->bindParam("user_visibility"	, $reqData->visibility);
						$stmt->bindParam("pk_user_id"		, $reqData->rmuserid);
					$stmt->execute();
				//select query
					$sltsql = "SELECT `pk_user_id` AS userid, `user_emp_id` AS empid, `user_first_name` AS firstname, `user_last_name` AS lastname, `user_email` AS useremail, `user_phone` AS userphone, `work_type` AS worktype, `user_img` AS userimg, `user_visibility` AS uservisibility, `is_approved` AS isapproved, user_email_verified as emailverified FROM `nman_user_master` WHERE `pk_user_id`=:pk_user_id";
				//prepare select statement
					$sltstmt= $db->prepare($sltsql);
						$sltstmt->bindParam("pk_user_id"	, $reqData->rmuserid);
					$sltstmt->execute();
				//fetch data
					$resultData = $sltstmt->fetch(PDO::FETCH_OBJ);
					$status		= "true";
					$statuscode	= "200";
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

//user login session
	function login_session($request, $response)
	{
		$reqData	= json_decode($request->getBody());
		$status		= "false";
		$statuscode	= "201";
		$resultData	= array();
		$resultDetail= new stdClass();
		isset($reqData->userid)?$reqData->userid:$error[]			= "User id";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]	= "User token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage"=> "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
			try
			{
				$db = getDB();
			//select query
				$sltsql	= "SELECT `pk_user_id`,`mobile_token` FROM `nman_user_master` WHERE `pk_user_id`=:pk_user_id AND `mobile_token`=:mobile_token";
			//prepare statement
				$sltstmt= $db->prepare($sltsql);
					$sltstmt->bindParam("pk_user_id"	, $reqData->userid);
					$sltstmt->bindParam("mobile_token"	, $reqData->mobiletoken);
				$sltstmt->execute();
			//fetch data
				$fetchData = $sltstmt->fetch(PDO::FETCH_OBJ);
			//check OTP valid
				if($sltstmt->rowCount() > 0):
					$resultDetail->userid		= $fetchData->pk_user_id;
					$resultDetail->mobiletoken	= $fetchData->mobile_token;
					$status 	= "true";
					$statuscode	= "200";
				else:
					$statuscode	= "202";
					$resultDetail->message = "Your session expired. You will be redirected to the login page.";
				endif;
			} catch(PDOException $e) {
				$resultDetail->message = $e->getMessage();
			}
		//push values to array
			array_push($resultData, $resultDetail);
		//result
			$db = null;
		//prepare return string
			$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
		endif;
	//return result
		echo json_encode($response);
	}

//update user profile
	function update_profile($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->userfirstname)?$reqData->userfirstname:$error[]	= "User First Name";
		isset($reqData->userlastname)?$reqData->userlastname:$error[]	= "User Last Name";
		isset($reqData->userphone)?$reqData->userphone:$error[]			= "User Phone";
		isset($reqData->userid)?$reqData->userid:$error[]				= "User id";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]		= "User token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//try block
			try {
			//verify user token
				$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
			//check token
				if($checktoken > 0):
				//database initialization
					$db 		= getDB();
				//SQL query insert data
					$sql = "UPDATE `nman_user_master` SET `user_first_name`=:user_first_name, `user_last_name`=:user_last_name, `user_phone`=:user_phone WHERE `pk_user_id`=:pk_user_id";
				//prepare statement
					$stmt= $db->prepare($sql);
						$stmt->bindParam("user_first_name"	, $reqData->userfirstname);
						$stmt->bindParam("user_last_name"	, $reqData->userlastname);
						$stmt->bindParam("user_phone"		, $reqData->userphone);
						$stmt->bindParam("pk_user_id"		, $reqData->userid);
					$stmt->execute();
				//fetch last inserted data
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "Profile updated successfully";
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

//update user email address
	function update_email($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->useremail)?$reqData->useremail:$error[]	= "User Email";
		isset($reqData->userotp)?$reqData->userotp:$error[]		= "User OTP";
		isset($reqData->userid)?$reqData->userid:$error[]		= "User id";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//try block
			try {
			//database initialization
				$db 		= getDB();
			//SQL query to check email and mobile number exist
				$selectsql 	= "SELECT * FROM `nman_user_master` WHERE `pk_user_id`=:pk_user_id AND `user_otp`=:user_otp";
			//prepare statement
				$sltstmt 	= $db->prepare($selectsql);
					$sltstmt->bindParam("pk_user_id", $reqData->userid);
					$sltstmt->bindParam("user_otp"	, $reqData->userotp);
				$sltstmt->execute();
			//check email/phone duplication
				if($sltstmt->rowCount() > 0):
				//SQL query insert data
					$sql = "UPDATE `nman_user_master` SET `user_email`=:user_email WHERE `pk_user_id`=:pk_user_id";
				//prepare statement
					$stmt 	= $db->prepare($sql);
						$stmt->bindParam("user_email", $reqData->useremail);
						$stmt->bindParam("pk_user_id", $reqData->userid);
					$stmt->execute();
				//fetch last inserted data
					$status				= "true";
					$statuscode			= "200";
					$resultData->message= "Email address updated successfully";
				else:
					$resultData->message= "Invalid OTP code";
				endif;
			//clear database initialization
				$db = null;
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

//update user profile image
	function update_img($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->userimg)?$reqData->userimg:$error[]			= "User image";
		isset($reqData->userid)?$reqData->userid:$error[]			= "User id";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]	= "User token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//try block
			try {
			//database initialization
				$db 		= getDB();
			//verify user token
				$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
			//check token
				if($checktoken > 0):
				//SQL query insert data
					$sql = "UPDATE `nman_user_master` SET `user_img`=:user_img WHERE `pk_user_id`=:pk_user_id";
				//prepare statement
					$stmt 	= $db->prepare($sql);
						$stmt->bindParam("user_img"		, $reqData->userimg);
						$stmt->bindParam("pk_user_id"	, $reqData->userid);
					$stmt->execute();
				//fetch last inserted data
					$status				= "true";
					$statuscode			= "200";
					$resultData->message= "Profile image updated successfully";
				else:
					$statuscode	= "202";
					$resultData->message= "Your session expired. You will be redirected to the login page.";
				endif;
			//clear database initialization
				$db = null;
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

//update user password
	function update_password($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->useroldpassword)?$reqData->useroldpassword:$error[]	= "User old password";
		isset($reqData->usernewpassword)?$reqData->usernewpassword:$error[]	= "User new password";
		isset($reqData->userid)?$reqData->userid:$error[]					= "User id";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//try block
			try {
			//database initialization
				$db 		= getDB();
			//SQL query to check email and mobile number exist
				$selectsql 	= "SELECT * FROM `nman_user_master` WHERE `pk_user_id`=:pk_user_id AND `user_password`=:user_password";
			//prepare statement
				$sltstmt 	= $db->prepare($selectsql);
					$sltstmt->bindParam("pk_user_id", $reqData->userid);
					$sltstmt->bindParam("user_password"	, md5(md5($reqData->useroldpassword)));
				$sltstmt->execute();
			//check email/phone duplication
				if($sltstmt->rowCount() > 0):
				//SQL query insert data
					$sql = "UPDATE `nman_user_master` SET `user_password`=:user_password WHERE `pk_user_id`=:pk_user_id";
				//prepare statement
					$stmt 	= $db->prepare($sql);
						$stmt->bindParam("user_password", md5(md5($reqData->usernewpassword)));
						$stmt->bindParam("pk_user_id"	, $reqData->userid);
					$stmt->execute();
				//fetch last inserted data
					$status				= "true";
					$statuscode			= "200";
					$resultData->message= "Password updated successfully";
				else:
					$resultData->message= "Old password does not match";
				endif;
			//clear database initialization
				$db = null;
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

//reset user password
	function reset_password($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->userpassword)?$reqData->userpassword:$error[]	= "User password";
		isset($reqData->useremail)?$reqData->useremail:$error[]			= "User email";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//try block
			try {
			//database initialization
				$db 		= getDB();
			//SQL query insert data
				$sql = "UPDATE `nman_user_master` SET `user_password`=:user_password WHERE `user_email`=:user_email";
			//prepare statement
				$stmt 	= $db->prepare($sql);
					$stmt->bindParam("user_password", md5(md5($reqData->userpassword)));
					$stmt->bindParam("user_email"	, $reqData->useremail);
				$stmt->execute();
			//fetch last inserted data
				$status				= "true";
				$statuscode			= "200";
				$resultData->message= "Password updated successfully";
			//clear database initialization
				$db = null;
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

//send OTP code
	function send_otp($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->useremail)?$reqData->useremail:$error[]	= "User Email";
		isset($reqData->userid)?$reqData->userid:$error[]		= "User id";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//try block
			try {
			//database initialization
				$db 			= getDB();
				$user_otp 		= generateOTP();
				$otp_expired	= date("Y-m-d H:i:s", strtotime("+15 minutes"));
			//SQL query to check email and mobile number exist
				$selectsql 	= "SELECT * FROM `nman_user_master` WHERE `user_email`=:user_email";
			//prepare statement
				$sltstmt 	= $db->prepare($selectsql);
					$sltstmt->bindParam("user_email", $reqData->useremail);
				$sltstmt->execute();
			//check email/phone duplication
				if($sltstmt->rowCount() == 0):
				//SQL query insert data
					$sql = "UPDATE `nman_user_master` SET `user_otp`=:user_otp, `user_otp_expired`=:user_otp_expired WHERE `pk_user_id`=:pk_user_id";
				//prepare statement
					$stmt 	= $db->prepare($sql);
						$stmt->bindParam("user_otp"			, $user_otp);
						$stmt->bindParam("user_otp_expired"	, $otp_expired);
						$stmt->bindParam("pk_user_id"		, $reqData->userid);
					$stmt->execute();
				//fetch last inserted data
					$status					= "true";
					$statuscode				= "200";
					$resultData->message	= "OTP mailed successfully";
				//check prepare email
					if($stmt->rowCount() > 0):
						$emailData=new stdClass();
					//prepare email
						$emailData->Subject	= "E-mail Confirmation";
						$emailData->email	= $reqData->useremail;
						$emailData->user_otp= $user_otp;
					//get email contents
						$mailcontent		= file_get_contents('email_templates/email-otp.html');
						$replacements 		= array('({{OTP-CODE}})' 	=> $user_otp,'({{user-email}})' => $reqData->useremail);
						$emailData->body 	= preg_replace(array_keys($replacements), array_values($replacements), $mailcontent);
					//callback send email function
						$mailCheck		= send_email($emailData);
					endif;
				else:
					$resultData->message= "Email already exist!";
				endif;
			//clear database initialization
				$db = null;
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

//send reset password OTP code
	function reset_otp($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->useremail)?$reqData->useremail:$error[]	= "User Email";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//try block
			try {
			//database initialization
				$db 		= getDB();
				$user_otp 	= generateOTP();
				$otp_expired= date("Y-m-d H:i:s", strtotime("+15 minutes"));
			//SQL query to check email and mobile number exist
				$selectsql 	= "SELECT * FROM `nman_user_master` WHERE `user_email`=:user_email";
			//prepare statement
				$sltstmt 	= $db->prepare($selectsql);
					$sltstmt->bindParam("user_email", $reqData->useremail);
				$sltstmt->execute();
			//check email/phone duplication
				if($sltstmt->rowCount() > 0):
				//SQL query insert data
					$sql 	= "UPDATE `nman_user_master` SET `user_otp`=:user_otp, `user_otp_expired`=:user_otp_expired WHERE `user_email`=:user_email";
				//prepare statement
					$stmt 	= $db->prepare($sql);
						$stmt->bindParam("user_otp"			, $user_otp);
						$stmt->bindParam("user_otp_expired"	, $otp_expired);
						$stmt->bindParam("user_email"		, $reqData->useremail);
					$stmt->execute();
				//fetch last inserted data
					$status					= "true";
					$statuscode				= "200";
					$resultData->email		= $reqData->useremail;
					$resultData->message	= "OTP mailed successfully";
				//check prepare email
					if($stmt->rowCount() > 0):
						$emailData=new stdClass();
					//prepare email
						$emailData->Subject	= "E-mail Confirmation";
						$emailData->email	= $reqData->useremail;
						$emailData->user_otp= $user_otp;
					//get email contents
						$mailcontent		= file_get_contents('email_templates/email-otp.html');
						$replacements 		= array('({{OTP-CODE}})' 	=> $user_otp,'({{user-email}})' => $reqData->useremail);
						$emailData->body 	= preg_replace(array_keys($replacements), array_values($replacements), $mailcontent);
					//callback send email function
						$mailCheck		= send_email($emailData);
					endif;
				else:
					$resultData->message= "Not Registered Email";
				endif;
			//clear database initialization
				$db = null;
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

//verify OTP code
	function verify_otp($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->otpcode)?$reqData->otpcode:$error[]		= "OTP Code";
		isset($reqData->useremail)?$reqData->useremail:$error[]	= "User id";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//try block
			try {
			//database initialization
				$db 	= getDB();
			//SQL query insert data
				$sql 	= "SELECT * FROM `nman_user_master` WHERE `user_otp`=:user_otp AND `user_email`=:user_email";
			//prepare statement
				$stmt 	= $db->prepare($sql);
					$stmt->bindParam("user_otp"		, $reqData->otpcode);
					$stmt->bindParam("user_email"	, $reqData->useremail);
				$stmt->execute();
			//fetch data
				$fetchData	= $stmt->fetch(PDO::FETCH_ASSOC);
			//check email/phone duplication
				if($stmt->rowCount() > 0):
				//verify OTP expired
					if($fetchData["user_otp_expired"] > date("Y-m-d H:i:s")):
						$status					= "true";
						$statuscode				= "200";
						$resultData->useremail	= $reqData->useremail;
						$resultData->message	= "OTP verified successfully";
					else:
						$resultData->message= "OTP Code Expired";
					endif;
				else:
					$resultData->message= "Invlid Email/OTP Code";
				endif;
			//clear database initialization
				$db = null;
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

//update user signature
	function update_signature($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->usersignature)?$reqData->usersignature:$error[]	= "User Signature";
		isset($reqData->userid)?$reqData->userid:$error[]				= "User Last Name";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]		= "User token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db	 = getDB();
				//SQL query insert data
					$sql = "UPDATE `nman_user_master` SET `user_signature`=:user_signature WHERE `pk_user_id`=:pk_user_id";
				//prepare statement
					$stmt= $db->prepare($sql);
						$stmt->bindParam("user_signature"	, $reqData->usersignature);
						$stmt->bindParam("pk_user_id"		, $reqData->userid);
					$stmt->execute();
				//fetch last inserted data
					$status		= "true";
					$statuscode	= "200";
					$resultData->usersignature	= $reqData->usersignature;
					$resultData->message		= "Signature updated successfully";
				//clear database initialization
					$db = null;
				}
				catch(PDOException $e) {
					$resultData->message= $e->getMessage();
				}
			//push values to array
				array_push($resturnData, $resultData);
			else:
				$statuscode	= "202";
				$resultData->message= "Your session expired. You will be redirected to the login page.";
			endif;
		//prepare return string
			$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resturnData);
		endif;
	//return result
		echo json_encode($response);
	}

//create new user types
	function create_user_type($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->typename)?$reqData->typename:$error[] = "User type name";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//try block
			try {
			//database initialization
				$db 		= getDB();
			//SQL query to check email and mobile number exist
				$selectsql 	= "SELECT * FROM `nman_user_types` WHERE `type_name`=:type_name";
			//prepare statement
				$sltstmt 	= $db->prepare($selectsql);
					$sltstmt->bindParam("type_name", $reqData->typename);
				$sltstmt->execute();
			//check email/phone duplication
				if($sltstmt->rowCount() == 0):
				//SQL query insert data
					$sql = "INSERT INTO `nman_user_types` (`type_name`, `type_created_on`) VALUES (:type_name, :type_created_on)";
				//prepare statement
					$stmt 	= $db->prepare($sql);
						$stmt->bindParam("type_name"		, $reqData->typename);
						$stmt->bindParam("type_created_on"	, date('Y-m-d H:i:s'));
					$stmt->execute();
				//fetch last inserted data
					$status		= "true";
					$statuscode	= "200";
					$resultData->typeid	= $db->lastInsertId();
					$resultData->message= "User type created successfully";
				else:
					$resultData->message= "User type already exist!";
				endif;
			//clear database initialization
				$db = null;
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

//create new user types
	function update_user_type($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->typename)?$reqData->typename:$error[] = "User type name";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//try block
			try {
			//database initialization
				$db 		= getDB();
			//SQL query to check email and mobile number exist
				$selectsql 	= "SELECT * FROM `nman_user_types` WHERE `type_name`=:type_name";
			//prepare statement
				$sltstmt 	= $db->prepare($selectsql);
					$sltstmt->bindParam("type_name", $reqData->typename);
				$sltstmt->execute();
			//check email/phone duplication
				if($sltstmt->rowCount() == 0):
				//SQL query insert data
					$sql = "UPDATE `nman_user_types` SET `type_name`=:type_name WHERE `pk_type_id`=:pk_type_id";
				//prepare statement
					$stmt 	= $db->prepare($sql);
						$stmt->bindParam("type_name"	, $reqData->typename);
						$stmt->bindParam("pk_type_id"	, $reqData->id);
					$stmt->execute();
				//fetch last inserted data
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "User type updated successfully";
				else:
					$resultData->message= "User type already exist!";
				endif;
			//clear database initialization
				$db = null;
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

//create new site
	function site_create($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->sitename)?$reqData->sitename:$error[]			= "Site name";
		isset($reqData->siteownername)?$reqData->siteownername:$error[]	= "Site owner name";
		isset($reqData->siteemail)?$reqData->siteemail:$error[]			= "Site email";
		isset($reqData->sitephone)?$reqData->sitephone:$error[]			= "Site phone";
		isset($reqData->siteplotno)?$reqData->siteplotno:$error[]		= "Site plot no";
		isset($reqData->siteaddress)?$reqData->siteaddress:$error[]		= "Site Address";
		isset($reqData->sitemanager)?$reqData->sitemanager:$error[]		= "Manager Name";
		isset($reqData->sitestatus)?$reqData->sitestatus:$error[]		= "Site Status";
		isset($reqData->userid)?$reqData->userid:$error[]				= "User name";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]		= "User token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "Validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
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
						$sltstmt->bindParam("site_name"			, $reqData->sitename);
						$sltstmt->bindParam("site_owner_name"	, $reqData->siteownername);
						$sltstmt->bindParam("site_address"		, $reqData->siteaddress);
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
							$stmt->bindParam("site_name"		, $reqData->sitename);
							$stmt->bindParam("site_owner_name"	, $reqData->siteownername);
							$stmt->bindParam("site_email"		, $reqData->siteemail);
							$stmt->bindParam("site_phone"		, $reqData->sitephone);
							$stmt->bindParam("site_plot_no"		, $reqData->siteplotno);
							$stmt->bindParam("site_manager"		, $reqData->sitemanager);
							$stmt->bindParam("site_address"		, $reqData->siteaddress);
							$stmt->bindParam("site_status"		, $reqData->sitestatus);
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
						$emailData->email		= $reqData->siteemail;
					//get email contents
						$mailcontent			= file_get_contents('email_templates/user-verify.html');
						$replacements 			= array('({{user-name}})'=> $reqData->siteemail, '({{user-password}})'=>$password_s);
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

//update site information
	function site_update($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->siteid)?$reqData->siteid:$error[]				= "Site id";
		isset($reqData->sitename)?$reqData->sitename:$error[]			= "Site name";
		isset($reqData->siteownername)?$reqData->siteownername:$error[]	= "Site owner name";
		isset($reqData->siteemail)?$reqData->siteemail:$error[]			= "Site email";
		isset($reqData->sitephone)?$reqData->sitephone:$error[]			= "Site phone";
		isset($reqData->siteaddress)?$reqData->siteaddress:$error[]		= "Site Address";
		isset($reqData->sitestatus)?$reqData->sitestatus:$error[]		= "Site Status";
		isset($reqData->sitemanager)?$reqData->sitemanager:$error[]		= "Manager Name";
		isset($reqData->userid)?$reqData->userid:$error[]				= "User name";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]		= "User token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
			//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db 	= getDB();
				//SQL query to check email and mobile number exist
					$sltsql = "SELECT * FROM `nman_site_master` WHERE `site_name`=:site_name AND `pk_site_id`!=:pk_site_id";
				//prepare statement
					$sltstmt= $db->prepare($sltsql);
						$sltstmt->bindParam("site_name"	, $reqData->sitename);
						$sltstmt->bindParam("pk_site_id", $reqData->siteid);
					$sltstmt->execute();
				//check site name duplication
					if($sltstmt->rowCount() == 0):
					//SQL query update data
						$sql = "UPDATE `nman_site_master` SET `site_name`=:site_name, `site_owner_name`=:site_owner_name, `site_phone`=:site_phone, `site_plot_no`=:site_plot_no, `site_address`=:site_address, `site_manager`=:site_manager, `site_status`=:site_status WHERE `pk_site_id`=:pk_site_id";
					//prepare statement
						$stmt 	= $db->prepare($sql);
							$stmt->bindParam("site_name"		, $reqData->sitename);
							$stmt->bindParam("site_owner_name"	, $reqData->siteownername);
							$stmt->bindParam("site_phone"		, $reqData->sitephone);
							$stmt->bindParam("site_plot_no"		, $reqData->siteplotno);
							$stmt->bindParam("site_address"		, $reqData->siteaddress);
							$stmt->bindParam("site_manager"		, $reqData->sitemanager);
							$stmt->bindParam("site_status"		, $reqData->sitestatus);
							$stmt->bindParam("pk_site_id"		, $reqData->siteid);
						$stmt->execute();
					//SQL query update data
						$updsql = "UPDATE `nman_site_master` SET `site_email`=:site_email WHERE `pk_site_id`=:pk_site_id";
					//prepare statement
						$updstmt 	= $db->prepare($updsql);
							$updstmt->bindParam("site_email"		, $reqData->siteemail);
							$updstmt->bindParam("pk_site_id"		, $reqData->siteid);
						$updstmt->execute();
					//check update success/failed
						if($updstmt->rowCount()>0):
							$password_s	= "NORMAN".date("d");
							$password	= md5(md5($password_s));
						//send email
							$emailData=new stdClass();
						//prepare email
							$emailData->Subject		= "Norman Group";
							$emailData->email		= $reqData->siteemail;
						//get email contents
							$mailcontent			= file_get_contents('email_templates/user-verify.html');
							$replacements 			= array('({{user-name}})'=> $reqData->siteemail, '({{user-password}})'=>$password_s);
							$emailData->body 		= preg_replace(array_keys($replacements), array_values($replacements), $mailcontent);
						//callback send email function
							$mailCheck	= send_email($emailData);
						endif;
					//fetch last inserted data
						$resultData->message= "Site information updated successfully";
						$status		= "true";
						$statuscode	= "200";
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
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
		endif;
	//return result
		echo json_encode($response);
	}

//update site visibility
	function site_visibility($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->siteid)?$reqData->siteid:$error[]			= "Site id";
		isset($reqData->visibility)?$reqData->visibility:$error[]	= "Site Status";
		isset($reqData->userid)?$reqData->userid:$error[]			= "User name";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]	= "User token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
			//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db 		= getDB();
				//SQL query to check email and mobile number exist
					$selectsql 	= "UPDATE `nman_site_master` SET `site_visibility`=:site_visibility WHERE `pk_site_id`=:pk_site_id";
				//prepare statement
					$sltstmt 	= $db->prepare($selectsql);
						$sltstmt->bindParam("site_visibility"	, $reqData->visibility);
						$sltstmt->bindParam("pk_site_id"		, $reqData->siteid);
					$sltstmt->execute();
				//return data
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "Site deleted successfully";
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

//site assignment
	function site_assignment_bkp_09102020($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->siteid)?$reqData->siteid:$error[]			= "Site name";
		isset($reqData->usertype)?$reqData->usertype:$error[]		= "User type";
		isset($reqData->worktype)?$reqData->worktype:$error[]		= "Work type";
		isset($reqData->assignid)?$reqData->assignid:$error[]		= "Assigned user";
		isset($reqData->userid)?$reqData->userid:$error[]			= "User name";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]	= "User token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db 		= getDB();
					$siteArray	= explode(";#;", $reqData->siteid);
					//SQL query insert data
						$sql = "INSERT INTO `nman_site_assignment` (`fk_site_id`, `fk_user_id`, `user_type`, `work_type`, `assigned_by`) VALUES (:fk_site_id, :fk_user_id, :user_type, :work_type, :assigned_by)";
					//prepare statement
						//loop start
						foreach($siteArray as $key=>$site):
							$stmt 	= $db->prepare($sql);
								$stmt->bindParam("fk_site_id"	, $site);
								$stmt->bindParam("fk_user_id"	, $reqData->assignid);
								$stmt->bindParam("user_type"	, $reqData->usertype);
								$stmt->bindParam("work_type"	, $reqData->worktype);
								$stmt->bindParam("assigned_by"	, $reqData->userid);
							$stmt->execute();
						endforeach;
						//loop end
					//fetch last inserted data
						$status		= "true";
						$statuscode	= "200";
						$resultData->message= "Site assigned successfully";
					//send notification
						send_notification($reqData->assignid, "New Site", "You have (".sizeof($siteArray).") new site assignment", "site_assignment");
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
	function site_assignment($request, $response)
	{
		//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData = array();
		$status		= "false";
		$statuscode	= "201";
		//JSON values
		isset($reqData->siteid) ? $reqData->siteid : $error[]			= "Site name";
		isset($reqData->usertype) ? $reqData->usertype : $error[]		= "User type";
		isset($reqData->worktype) ? $reqData->worktype : $error[]		= "Work type";
		isset($reqData->assignid) ? $reqData->assignid : $error[]		= "Assigned user";
		isset($reqData->userid) ? $reqData->userid : $error[]			= "User name";
		isset($reqData->mobiletoken) ? $reqData->mobiletoken : $error[]	= "User token";
		//check condition
		if (isset($error) && sizeof($error)) :
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error) . " Parameter Required");
		else :
			//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
			//check token
			if ($checktoken > 0) :
				//try block
				try {
					//database initialization
					$db 		= getDB();
					$siteArray	= explode(";#;", $reqData->siteid);
					$assignidArray	= $reqData->assignid;
					if(!is_array($assignidArray)){
						$assignidArray = array($assignidArray);
					}
					//SQL query insert data
					$sql = "INSERT INTO `nman_site_assignment` (`fk_site_id`, `fk_user_id`, `user_type`, `work_type`, `assigned_by`) VALUES (:fk_site_id, :fk_user_id, :user_type, :work_type, :assigned_by)";
					//prepare statement
					//loop start
					foreach ($assignidArray as $key => $assign) :
						$stmt 	= $db->prepare($sql);
						$stmt->bindParam("fk_site_id", $reqData->siteid);
						$stmt->bindParam("fk_user_id", $assign);
						$stmt->bindParam("user_type", $reqData->usertype);
						$stmt->bindParam("work_type", $reqData->worktype);
						$stmt->bindParam("assigned_by", $reqData->userid);
						$stmt->execute();
					endforeach;
					//loop end
					//fetch last inserted data
					$status		= "true";
					$statuscode	= "200";
					$resultData->message = "Site assigned successfully";
					//send notification
					send_notification($reqData->assignid, "New Site", "You have (" . sizeof($siteArray) . ") new site assignment", "site_assignment");
					//clear database initialization
					$db = null;
				} catch (PDOException $e) {
					$resultData->message = $e->getMessage();
				}
			else :
				$statuscode	= "202";
				$resultData->message = "Your session expired. You will be redirected to the login page.";
			endif;
			//push values to array
			array_push($resturnData, $resultData);
			//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resturnData);
		endif;
		//return result
		echo json_encode($response);
	}
//site assignment
	function site_reassignment_bkp_09102020($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->assignedid)?$reqData->assignedid:$error[]	= "Assigment id";
		isset($reqData->siteid)?$reqData->siteid:$error[]			= "Site name";
		isset($reqData->usertype)?$reqData->usertype:$error[]		= "User type";
		isset($reqData->worktype)?$reqData->worktype:$error[]		= "Work type";
		isset($reqData->assignid)?$reqData->assignid:$error[]		= "Assigned user";
		isset($reqData->userid)?$reqData->userid:$error[]			= "User name";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]	= "User token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db 		= getDB();
					$siteArray	= explode(";#;", $reqData->siteid);
					//SQL query update data
						$sql = "UPDATE `nman_site_assignment` SET `fk_site_id`=:fk_site_id, `fk_user_id`=:fk_user_id, `user_type`=:user_type, `work_type`=:work_type, `assigned_by`=:assigned_by WHERE `pk_assign_id`=:pk_assign_id";
					//prepare statement
						//loop start
						foreach($siteArray as $key=>$site):
							$stmt 	= $db->prepare($sql);
								$stmt->bindParam("fk_site_id"	, $site);
								$stmt->bindParam("fk_user_id"	, $reqData->assignid);
								$stmt->bindParam("user_type"	, $reqData->usertype);
								$stmt->bindParam("work_type"	, $reqData->worktype);
								$stmt->bindParam("assigned_by"	, $reqData->userid);
								$stmt->bindParam("pk_assign_id"	, $reqData->assignedid);
							$stmt->execute();
						endforeach;
						//loop end
					//fetch last inserted data
						$status		= "true";
						$statuscode	= "200";
						$resultData->message= "Site re-assigned successfully";
					//send notification
						send_notification($reqData->assignid, "New Site", "You have (".sizeof($siteArray).") new site assignment", "site_assignment");
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
	function site_reassignment($request, $response)
	{
		//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData = array();
		$status		= "false";
		$statuscode	= "201";
		//JSON values
		isset($reqData->assignedid) ? $reqData->assignedid : $error[]	= "Assigment id";
		isset($reqData->siteid) ? $reqData->siteid : $error[]			= "Site name";
		isset($reqData->usertype) ? $reqData->usertype : $error[]		= "User type";
		isset($reqData->worktype) ? $reqData->worktype : $error[]		= "Work type";
		isset($reqData->assignid) ? $reqData->assignid : $error[]		= "Assigned user";
		isset($reqData->userid) ? $reqData->userid : $error[]			= "User name";
		isset($reqData->mobiletoken) ? $reqData->mobiletoken : $error[]	= "User token";
		//check condition
		if (isset($error) && sizeof($error)) :
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error) . " Parameter Required");
		else :
			//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
			//check token
			if ($checktoken > 0) :
				//try block
				try {
					//database initialization
					$db 		= getDB();
					$siteArray	= explode(";#;", $reqData->siteid);
					$assignidArray	= $reqData->assignid;
					if(!is_array($assignidArray)){
						$assignidArray = array($assignidArray);
					}
					//loop start
					foreach ($assignidArray as $key => $assign) :
	
						$sltsql = "SELECT * FROM `nman_site_assignment` WHERE `fk_site_id`=:fk_site_id AND fk_user_id=:fk_user_id";
						//prepare statement
						$sltstmt 	= $db->prepare($sltsql);
						$sltstmt->bindParam("fk_site_id", $reqData->siteid);
						$sltstmt->bindParam("fk_user_id", $assign);
						$sltstmt->execute();
						//check duplicate site entry
						if ($sltstmt->rowCount() == 0) :
							//SQL query insert data
							$sql = "INSERT INTO `nman_site_assignment` (`fk_site_id`, `fk_user_id`, `user_type`, `work_type`, `assigned_by`) VALUES (:fk_site_id, :fk_user_id, :user_type, :work_type, :assigned_by)";
							//prepare statement
							$stmt 	= $db->prepare($sql);
							$stmt->bindParam("fk_site_id", $reqData->siteid);
							$stmt->bindParam("fk_user_id", $assign);
							$stmt->bindParam("user_type", $reqData->usertype);
							$stmt->bindParam("work_type", $reqData->worktype);
							$stmt->bindParam("assigned_by", $reqData->userid);
							$stmt->execute();
						/*else :
							//SQL query update data
							$sql = "UPDATE `nman_site_assignment` SET `fk_site_id`=:fk_site_id, `fk_user_id`=:fk_user_id, `user_type`=:user_type, `work_type`=:work_type, `assigned_by`=:assigned_by WHERE `pk_assign_id`=:pk_assign_id";
							//prepare statement
							$stmt 	= $db->prepare($sql);
							$stmt->bindParam("fk_site_id", $reqData->siteid);
							$stmt->bindParam("fk_user_id", $assign);
							$stmt->bindParam("user_type", $reqData->usertype);
							$stmt->bindParam("work_type", $reqData->worktype);
							$stmt->bindParam("assigned_by", $reqData->userid);
							//$stmt->bindParam("pk_assign_id", $reqData->assignedid);
							$stmt->execute();*/
						endif;
	
					endforeach;
					//loop end
					//fetch last inserted data
					$status		= "true";
					$statuscode	= "200";
					$resultData->message = "Site re-assigned successfully";
					//send notification
					send_notification($reqData->assignid, "New Site", "You have (" . sizeof($siteArray) . ") new site assignment", "site_assignment");
					//clear database initialization
					$db = null;
				} catch (PDOException $e) {
					$resultData->message = $e->getMessage();
				}
			else :
				$statuscode	= "202";
				$resultData->message = "Your session expired. You will be redirected to the login page.";
			endif;
			//push values to array
			array_push($resturnData, $resultData);
			//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resturnData);
		endif;
		//return result
		echo json_encode($response);
	}
//assign forms to sites
	function site_form_assignment($request, $response)
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
		isset($reqData->mobiletoken)? $reqData->mobiletoken	: $error[]	= "Mobile token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
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

//site user signature
	function site_signature($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->sitesignature)?$reqData->sitesignature:$error[]	= "Site user Signature";
		isset($reqData->siteid)?$reqData->siteid:$error[]				= "Site Name";
		isset($reqData->userid)?$reqData->userid:$error[]				= "User Last Name";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]		= "User token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db	 = getDB();
				//SQL query insert data
					$sql = "UPDATE `nman_site_master` SET `site_signature`=:site_signature WHERE `pk_site_id`=:pk_site_id";
				//prepare statement
					$stmt= $db->prepare($sql);
						$stmt->bindParam("site_signature"	, $reqData->sitesignature);
						$stmt->bindParam("pk_site_id"		, $reqData->siteid);
					$stmt->execute();
				//fetch last inserted data
					$status		= "true";
					$statuscode	= "200";
					$resultData->sitesignature	= $reqData->sitesignature;
					$resultData->message		= "Signature updated successfully";
				//clear database initialization
					$db = null;
				}
				catch(PDOException $e) {
					$resultData->message= $e->getMessage();
				}
			//push values to array
				array_push($resturnData, $resultData);
			else:
				$statuscode	= "202";
				$resultData->message= "Your session expired. You will be redirected to the login page.";
			endif;
		//prepare return string
			$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resturnData);
		endif;
	//return result
		echo json_encode($response);
	}

//upload site document
	function upload_site_doc($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($_POST["docdescription"])	? $_POST["docdescription"]	: $error[]	= "Document description";
		isset($_POST["doctype"])		? $_POST["doctype"]			: $error[]	= "Document type";
		isset($_POST["siteid"])			? $_POST["siteid"]			: $error[]	= "Site Name";
		isset($_POST["userid"])			? $_POST["userid"]			: $error[]	= "User id";
		isset($_POST["mobiletoken"])	? $_POST["mobiletoken"]		: $error[]	= "User token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//try block
			try {
			//database initialization
				$db 		= getDB();
			//verify user token
				$checktoken = verify_session_token($_POST["userid"], $_POST["mobiletoken"]);
			//check token
				if($checktoken > 0):
					$docname= preg_replace('/\s+/', '', $_FILES["sitedocument"]["name"]);
					$sitedoc= "reports/".$_POST["siteid"]."/".$docname;
				//upload image
					if(move_uploaded_file($_FILES["sitedocument"]["tmp_name"], $sitedoc)):
						$docSize = filesize($sitedoc);
						chmod($sitedoc, 0777);
					//SQL query insert data
						$sql = "INSERT INTO `nman_site_documents` (`fk_site_id`, `fk_user_id`, `doc_name`, `site_document`, `doc_description`, `doc_type`, `doc_size`) VALUES (:fk_site_id, :fk_user_id, :doc_name, :site_document, :doc_description, :doc_type, :doc_size)";
					//prepare statement
						$stmt= $db->prepare($sql);
							$stmt->bindParam("fk_site_id"		, $_POST["siteid"]);
							$stmt->bindParam("fk_user_id"		, $_POST["userid"]);
							$stmt->bindParam("doc_name"			, $_FILES["sitedocument"]["name"]);
							$stmt->bindParam("site_document"	, $sitedoc);
							$stmt->bindParam("doc_description"	, $_POST["docdescription"]);
							$stmt->bindParam("doc_type"			, $_POST["doctype"]);
							$stmt->bindParam("doc_size"			, $docSize);
						$stmt->execute();
					//fetch last inserted data
						$status				= "true";
						$statuscode			= "200";
						$resultData->message= "Document uploaded successfully";
					else:
						$resultData->message= "Failed to upload document";
					endif;
				else:
					$statuscode	= "202";
					$resultData->message= "Your session expired. You will be redirected to the login page.";
				endif;
			//clear database initialization
				$db = null;
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

//remove site documents
	function remove_site_doc($request, $response)
	{
		//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->docid)?$reqData->docid:$error[]					= "Document Name";
		isset($reqData->userid)?$reqData->userid:$error[]				= "User Last Name";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]		= "User token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db 	= getDB();
				//select query
					$updsql = "UPDATE `nman_site_documents` SET `doc_visibility`=0 WHERE `pk_doc_id`=:pk_doc_id";
				//prepare select statement
					$updstmt= $db->prepare($updsql);
						$updstmt->bindParam("pk_doc_id", $reqData->docid);
					$updstmt->execute();
				//return status
					$resultData->message = "Docdument removed successfully";
					$status 	= "true";
					$statuscode	= "200";
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

//create new site supervisor form
	function new_supervisor_form($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->siteid)		? $reqData->siteid		: $error[]	= "Site name";
		isset($reqData->formid)		? $reqData->formid		: $error[]	= "Form name";
		isset($reqData->worktype)	? $reqData->worktype	: $error[]	= "Work type";
		isset($reqData->imgkey)		? $reqData->imgkey		: $error[]	= "Image key";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "User id";
		isset($reqData->mobiletoken)? $reqData->mobiletoken	: $error[]	= "Mobile token";
		//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//update query
					$updsql = "UPDATE `nman_supervisor_form` SET `is_new`=0 WHERE `fk_site_id`=:fk_site_id AND `fk_user_id`=:fk_user_id";
				//prepare update statement
					$updstmt 	= $db->prepare($updsql);
						$updstmt->bindParam("fk_site_id"	, $reqData->siteid);
						$updstmt->bindParam("fk_user_id"	, $reqData->userid);
					$updstmt->execute();
				//pdf key
					$pdfkey = generate_mobile_token($reqData->userid);
				//insert data
					$sql = "INSERT INTO `nman_supervisor_form` (`fk_site_id`, `fk_user_id`, `input_1`, `comment_1`, `input_2`, `comment_2`, `input_3`, `comment_3`, `input_4`, `comment_4`, `input_5`, `comment_5`, `input_6`, `comment_6`, `input_7`, `comment_7`, `input_8`, `comment_8`, `input_9`, `comment_9`, `input_10`, `comment_10`, `input_11`, `comment_11`, `input_12`, `comment_12`, `input_13`, `comment_13`, `input_14`, `comment_14`, `input_15`, `comment_15`, `input_16`, `comment_16`, `input_17`, `comment_17`, `input_18`, `comment_18`, `input_19`, `comment_19`, `input_20`, `comment_20`, `work_type`, `is_new`, `img_key`, `pdf_key`, `sform_createdon`) VALUES (:fk_site_id, :fk_user_id, :input_1, :comment_1, :input_2, :comment_2, :input_3, :comment_3, :input_4, :comment_4, :input_5, :comment_5, :input_6, :comment_6, :input_7, :comment_7, :input_8, :comment_8, :input_9, :comment_9, :input_10, :comment_10, :input_11, :comment_11, :input_12, :comment_12, :input_13, :comment_13, :input_14, :comment_14, :input_15, :comment_15, :input_16, :comment_16, :input_17, :comment_17, :input_18, :comment_18, :input_19, :comment_19, :input_20, :comment_20, :work_type, 1, :img_key, :pdf_key, :sform_createdon)";
				//prepare statement
					$stmt 	= $db->prepare($sql);
						$stmt->bindParam("fk_site_id"		, $reqData->siteid);
						$stmt->bindParam("fk_user_id"		, $reqData->userid);
						$stmt->bindParam("input_1"			, $reqData->input_1);
						$stmt->bindParam("comment_1"		, $reqData->comment_1);
						$stmt->bindParam("input_2"			, $reqData->input_2);
						$stmt->bindParam("comment_2"		, $reqData->comment_2);
						$stmt->bindParam("input_3"			, $reqData->input_3);
						$stmt->bindParam("comment_3"		, $reqData->comment_3);
						$stmt->bindParam("input_4"			, $reqData->input_4);
						$stmt->bindParam("comment_4"		, $reqData->comment_4);
						$stmt->bindParam("input_5"			, $reqData->input_5);
						$stmt->bindParam("comment_5"		, $reqData->comment_5);
						$stmt->bindParam("input_6"			, $reqData->input_6);
						$stmt->bindParam("comment_6"		, $reqData->comment_6);
						$stmt->bindParam("input_7"			, $reqData->input_7);
						$stmt->bindParam("comment_7"		, $reqData->comment_7);
						$stmt->bindParam("input_8"			, $reqData->input_8);
						$stmt->bindParam("comment_8"		, $reqData->comment_8);
						$stmt->bindParam("input_9"			, $reqData->input_9);
						$stmt->bindParam("comment_9"		, $reqData->comment_9);
						$stmt->bindParam("input_10"			, $reqData->input_10);
						$stmt->bindParam("comment_10"		, $reqData->comment_10);
						$stmt->bindParam("input_11"			, $reqData->input_11);
						$stmt->bindParam("comment_11"		, $reqData->comment_11);
						$stmt->bindParam("input_12"			, $reqData->input_12);
						$stmt->bindParam("comment_12"		, $reqData->comment_12);
						$stmt->bindParam("input_13"			, $reqData->input_13);
						$stmt->bindParam("comment_13"		, $reqData->comment_13);
						$stmt->bindParam("input_14"			, $reqData->input_14);
						$stmt->bindParam("comment_14"		, $reqData->comment_14);
						$stmt->bindParam("input_15"			, $reqData->input_15);
						$stmt->bindParam("comment_15"		, $reqData->comment_15);
						$stmt->bindParam("input_16"			, $reqData->input_16);
						$stmt->bindParam("comment_16"		, $reqData->comment_16);
						$stmt->bindParam("input_17"			, $reqData->input_17);
						$stmt->bindParam("comment_17"		, $reqData->comment_17);
						$stmt->bindParam("input_18"			, $reqData->input_18);
						$stmt->bindParam("comment_18"		, $reqData->comment_18);
						$stmt->bindParam("input_19"			, $reqData->input_19);
						$stmt->bindParam("comment_19"		, $reqData->comment_19);
						$stmt->bindParam("input_20"			, $reqData->input_20);
						$stmt->bindParam("comment_20"		, $reqData->comment_20);
						$stmt->bindParam("work_type"		, $reqData->worktype);
						$stmt->bindParam("img_key"			, $reqData->imgkey);
						$stmt->bindParam("pdf_key"			, $pdfkey);
						$stmt->bindParam("sform_createdon"	, date('Y-m-d H:i:s'));
					$stmt->execute();
				//callback function generate pdf file
					$resultData->pdffile= prepare_pdf_file($reqData->siteid, $reqData->formid, $reqData->userid, $reqData->worktype, $pdfkey, 0, 'A3', 'portrait');
				//fetch result
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "Site information created successfully";
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

//create new handover certificate
	function new_handover_certificate($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
		$datetime	= date('Y-m-d H:i:s');
	//JSON values
		isset($reqData->siteid)?$reqData->siteid:$error[]			= "Site name";
		isset($reqData->formid)?$reqData->formid:$error[]			= "Form name";
		isset($reqData->worktype)?$reqData->worktype:$error[]		= "Work type";
		isset($reqData->imgkey)?$reqData->imgkey:$error[]			= "Image key";
		isset($reqData->userid)?$reqData->userid:$error[]			= "User id";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]	= "Mobile token";
		//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//update query
					$updqry	= "UPDATE `nman_handover_certificate` SET `is_new`=0 WHERE `fk_site_id`=:fk_site_id AND `fk_user_id`=:fk_user_id";
				//prepare update statement
					$updstmt= $db->prepare($updqry);
						$updstmt->bindParam("fk_site_id", $reqData->siteid);
						$updstmt->bindParam("fk_user_id", $reqData->userid);
					$updstmt->execute();
				//pdf key
					$pdfkey = generate_mobile_token($reqData->userid);
				//insert data
					$sql = "INSERT INTO `nman_handover_certificate` (`fk_site_id`, `fk_user_id`, `input_1`, `input_2`, `input_3`, `input_4`, `input_5`, `input_6`, `input_7`, `input_8`, `input_9`, `input_10`, `input_11`, `input_12`, `input_13`, `work_type`, `is_new`, `img_key`, `pdf_key`, `hform_createdon`) VALUES (:fk_site_id, :fk_user_id, :input_1, :input_2, :input_3, :input_4, :input_5, :input_6, :input_7, :input_8, :input_9, :input_10, :input_11, :input_12, :input_13, :work_type, 1, :img_key, :pdf_key, :hform_createdon)";
				//prepare statement
					$stmt 	= $db->prepare($sql);
						$stmt->bindParam("fk_site_id"		, $reqData->siteid);
						$stmt->bindParam("fk_user_id"		, $reqData->userid);
						$stmt->bindParam("input_1"			, $reqData->input_1);
						$stmt->bindParam("input_2"			, $reqData->input_2);
						$stmt->bindParam("input_3"			, $reqData->input_3);
						$stmt->bindParam("input_4"			, $reqData->input_4);
						$stmt->bindParam("input_5"			, $reqData->input_5);
						$stmt->bindParam("input_6"			, $reqData->input_6);
						$stmt->bindParam("input_7"			, $reqData->input_7);
						$stmt->bindParam("input_8"			, $reqData->input_8);
						$stmt->bindParam("input_9"			, $reqData->input_9);
						$stmt->bindParam("input_10"			, $reqData->input_10);
						$stmt->bindParam("input_11"			, $reqData->input_11);
						$stmt->bindParam("input_12"			, $reqData->input_12);
						$stmt->bindParam("input_13"			, $reqData->input_13);
						$stmt->bindParam("work_type"		, $reqData->worktype);
						$stmt->bindParam("img_key"			, $reqData->imgkey);
						$stmt->bindParam("pdf_key"			, $pdfkey);
						$stmt->bindParam("hform_createdon"	, $datetime);
					$stmt->execute();
				//callback function generate pdf file
					$resultData->pdffile= prepare_pdf_file($reqData->siteid, $reqData->formid, $reqData->userid, $reqData->worktype, $pdfkey, 0, 'A4', 'portrait');
				//fetch result
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "Handover Certificate created successfully";
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

//create new method statement form
	function new_method_statement($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->siteid)?$reqData->siteid:$error[]				= "Site name";
		isset($reqData->formid)?$reqData->formid:$error[]				= "Form name";
		isset($reqData->field_inputs)?$reqData->field_inputs:$error[]	= "Form inputs";
		isset($reqData->imgkey)?$reqData->imgkey:$error[]				= "Image key";
		isset($reqData->worktype)?$reqData->worktype:$error[]			= "Work type";
		isset($reqData->userid)?$reqData->userid:$error[]				= "User id";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]		= "Mobile token";
		$datetime	= date("Y-m-d H:i:s");
		$dateArray  = explode(" ", $datetime);
		$date 		= $dateArray[0];
		$isnew 		= 1;
		//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//update query
					$updqry = "UPDATE `nman_method_statement` SET `is_new`=0 WHERE `fk_site_id`=:fk_site_id AND `fk_user_id`=:fk_user_id";
					$updstmt= $db->prepare($updqry);
						$updstmt->bindParam("fk_site_id", $reqData->siteid);
						$updstmt->bindParam("fk_user_id", $reqData->userid);
					$updstmt->execute();
				//pdf key
					$pdfkey = generate_mobile_token($reqData->userid);
				//insert data
					$sql = "INSERT INTO `nman_method_statement` (`fk_site_id`, `fk_user_id`, `input_1`, `input_2`, `input_3`, `input_4`, `work_type`, `is_new`, `img_key`, `pdf_key`, `mform_createdon`) VALUES (:fk_site_id, :fk_user_id, :input_1, :input_2, :input_3, :input_4, :work_type, :is_new, :img_key, :pdf_key, :mform_createdon)";
				//foreach loop
					foreach($reqData->field_inputs as $key=>$values):
					//prepare statement
						$stmt 	= $db->prepare($sql);
							$stmt->bindParam("fk_site_id"		, $reqData->siteid);
							$stmt->bindParam("fk_user_id"		, $reqData->userid);
							$stmt->bindParam("input_1"			, $values->input_1);
							$stmt->bindParam("input_2"			, $values->input_2);
							$stmt->bindParam("input_3"			, $values->input_3);
							$stmt->bindParam("input_4"			, $date);
							$stmt->bindParam("work_type"		, $reqData->worktype);
							$stmt->bindParam("is_new"			, $isnew);
							$stmt->bindParam("img_key"			, $reqData->imgkey);
							$stmt->bindParam("pdf_key"			, $pdfkey);
							$stmt->bindParam("mform_createdon"	, $datetime);
						$stmt->execute();
					endforeach;
				//callback function generate pdf file
					$resultData->pdffile= prepare_pdf_file($reqData->siteid, $reqData->formid, $reqData->userid, $reqData->worktype, $pdfkey, 0, 'A4', 'landscape');
				//fetch result
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "Method statement record added successfully";
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

//create new Scaffolding Inspection Form
	function new_inspection_form($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->siteid)?$reqData->siteid:$error[]				= "Site name";
		isset($reqData->formid)?$reqData->formid:$error[]				= "Form name";
		isset($reqData->field_inputs)?$reqData->field_inputs:$error[]	= "Form inputs";
		isset($reqData->worktype)?$reqData->worktype:$error[]			= "Work type";
		isset($reqData->imgkey)?$reqData->imgkey:$error[]				= "Image key";
		isset($reqData->userid)?$reqData->userid:$error[]				= "User id";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]		= "Mobile token";
		//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//update query
					$updsql	 = "UPDATE `nman_inspection_form` SET `is_new`=0 WHERE `fk_site_id`=:fk_site_id AND `fk_user_id`=:fk_user_id";
				//prepare update statement
					$updstmt = $db->prepare($updsql);
						$updstmt->bindParam("fk_site_id", $reqData->siteid);
						$updstmt->bindParam("fk_user_id", $reqData->userid);
					$updstmt->execute();
				//pdf key
					$pdfkey = generate_mobile_token($reqData->userid);
				//insert data
					$sql = "INSERT INTO `nman_inspection_form` (`fk_site_id`, `fk_user_id`, `input_1`, `input_2`, `input_3`, `input_4`, `work_type`, `is_new`, `img_key`, `pdf_key`, `iform_createdon`) VALUES (:fk_site_id, :fk_user_id, :input_1, :input_2, :input_3, :input_4, :work_type, 1, :img_key, :pdf_key, :iform_createdon)";
				//prepare statement
					//loop start
					foreach($reqData->field_inputs as $key=>$values):
						$stmt 	= $db->prepare($sql);
							$stmt->bindParam("fk_site_id"		, $reqData->siteid);
							$stmt->bindParam("fk_user_id"		, $reqData->userid);
							$stmt->bindParam("input_1"			, $values->input_1);
							$stmt->bindParam("input_2"			, $values->input_2);
							$stmt->bindParam("input_3"			, $values->input_3);
							$stmt->bindParam("input_4"			, $values->input_4);
							$stmt->bindParam("work_type"		, $reqData->worktype);
							$stmt->bindParam("img_key"			, $reqData->imgkey);
							$stmt->bindParam("pdf_key"			, $pdfkey);
							$stmt->bindParam("iform_createdon"	, date('Y-m-d H:i:s'));
						$stmt->execute();
					endforeach;
					//loop end
				//callback function generate pdf file
					$resultData->pdffile= prepare_pdf_file($reqData->siteid, $reqData->formid, $reqData->userid, $reqData->worktype, $pdfkey, 0, 'A3', 'landscape');
				//fetch result
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "Inspection form created successfully";
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

//create new health assessment form
	function new_health_assessment($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->formid)?$reqData->formid:$error[]				= "Form name";
		isset($reqData->userid)?$reqData->userid:$error[]				= "User id";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]		= "Mobile token";
		//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//update query
					$updsql	 = "UPDATE `nman_health_assessment` SET `is_new`=0 WHERE `fk_user_id`=:fk_user_id";
				//prepare update statement
					$updstmt = $db->prepare($updsql);
						$updstmt->bindParam("fk_user_id", $reqData->userid);
					$updstmt->execute();
				//pdf key
					$pdfkey = generate_mobile_token($reqData->userid);
				//insert data
					$sql = "INSERT INTO `nman_health_assessment` (`fk_user_id`, `input_1`, `comment_1`, `input_2`, `comment_2`, `input_3`, `comment_3`, `input_4`, `comment_4`, `input_5`, `comment_5`, `input_6`, `comment_6`, `input_7`, `comment_7`, `input_8`, `comment_8`, `input_9`, `comment_9`, `input_10`, `comment_10`, `input_11`, `comment_11`, `input_12`, `comment_12`, `input_13`, `comment_13`, `input_14`, `comment_14`, `input_15`, `comment_15`, `input_16`, `comment_16`, `input_17`, `comment_17`, `input_18`, `input_19`, `input_20`, `work_type`, `pdf_key`, `is_new`, `hform_createdon`) VALUES (:fk_user_id, :input_1, :comment_1, :input_2, :comment_2, :input_3, :comment_3, :input_4, :comment_4, :input_5, :comment_5, :input_6, :comment_6, :input_7, :comment_7, :input_8, :comment_8, :input_9, :comment_9, :input_10, :comment_10, :input_11, :comment_11, :input_12, :comment_12, :input_13, :comment_13, :input_14, :comment_14, :input_15, :comment_15, :input_16, :comment_16, :input_17, :comment_17, :input_18, :input_19, :input_20, :work_type, :pdf_key, 1, :hform_createdon)";
				//prepare statement
					$stmt 	= $db->prepare($sql);
						$stmt->bindParam("fk_user_id"		, $reqData->userid);
						$stmt->bindParam("input_1"			, $reqData->input_1);
						$stmt->bindParam("comment_1"		, $reqData->comment_1);
						$stmt->bindParam("input_2"			, $reqData->input_2);
						$stmt->bindParam("comment_2"		, $reqData->comment_2);
						$stmt->bindParam("input_3"			, $reqData->input_3);
						$stmt->bindParam("comment_3"		, $reqData->comment_3);
						$stmt->bindParam("input_4"			, $reqData->input_4);
						$stmt->bindParam("comment_4"		, $reqData->comment_4);
						$stmt->bindParam("input_5"			, $reqData->input_5);
						$stmt->bindParam("comment_5"		, $reqData->comment_5);
						$stmt->bindParam("input_6"			, $reqData->input_6);
						$stmt->bindParam("comment_6"		, $reqData->comment_6);
						$stmt->bindParam("input_7"			, $reqData->input_7);
						$stmt->bindParam("comment_7"		, $reqData->comment_7);
						$stmt->bindParam("input_8"			, $reqData->input_8);
						$stmt->bindParam("comment_8"		, $reqData->comment_8);
						$stmt->bindParam("input_9"			, $reqData->input_9);
						$stmt->bindParam("comment_9"		, $reqData->comment_9);
						$stmt->bindParam("input_10"			, $reqData->input_10);
						$stmt->bindParam("comment_10"		, $reqData->comment_10);
						$stmt->bindParam("input_11"			, $reqData->input_11);
						$stmt->bindParam("comment_11"		, $reqData->comment_11);
						$stmt->bindParam("input_12"			, $reqData->input_12);
						$stmt->bindParam("comment_12"		, $reqData->comment_12);
						$stmt->bindParam("input_13"			, $reqData->input_13);
						$stmt->bindParam("comment_13"		, $reqData->comment_13);
						$stmt->bindParam("input_14"			, $reqData->input_14);
						$stmt->bindParam("comment_14"		, $reqData->comment_14);
						$stmt->bindParam("input_15"			, $reqData->input_15);
						$stmt->bindParam("comment_15"		, $reqData->comment_15);
						$stmt->bindParam("input_16"			, $reqData->input_16);
						$stmt->bindParam("comment_16"		, $reqData->comment_16);
						$stmt->bindParam("input_17"			, $reqData->input_17);
						$stmt->bindParam("comment_17"		, $reqData->comment_17);
						$stmt->bindParam("input_18"			, $reqData->input_18);
						$stmt->bindParam("input_19"			, $reqData->input_19);
						$stmt->bindParam("input_20"			, $reqData->input_20);
						$stmt->bindParam("work_type"		, $reqData->worktype);
						$stmt->bindParam("pdf_key"			, $pdfkey);
						$stmt->bindParam("hform_createdon"	, date('Y-m-d H:i:s'));
					$stmt->execute();
				//callback function generate pdf file
					$resultData->pdffile= prepare_pdf_file("temp", $reqData->formid, $reqData->userid, $reqData->worktype, $pdfkey, 0, 'A4', 'portrait');
				//fetch result
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "Site information created successfully";
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

//create new tool box talk form
	function new_tool_boxtalk_form($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
		$datetime	= date('Y-m-d H:i:s');
	//JSON values
		isset($reqData->siteid)?$reqData->siteid:$error[]				= "Site name";
		isset($reqData->formid)?$reqData->formid:$error[]				= "Form name";
		isset($reqData->field_inputs)?$reqData->field_inputs:$error[]	= "Form inputs";
		isset($reqData->imgkey)?$reqData->imgkey:$error[]				= "Image key";
		isset($reqData->userid)?$reqData->userid:$error[]				= "User id";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]		= "Mobile token";
		//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//update query
					$updsql	= "UPDATE `nman_tool_boxtalk_master` SET `is_new`=0 WHERE `fk_site_id`=:fk_site_id AND `fk_user_id`=:fk_user_id";
				//prepare update statement
					$updstmt= $db->prepare($updsql);
						$updstmt->bindParam("fk_site_id", $reqData->siteid);
						$updstmt->bindParam("fk_user_id", $reqData->userid);
					$updstmt->execute();
				//pdf key
					$pdfkey = generate_mobile_token($reqData->userid);
				//insert master data
					$sql = "INSERT INTO `nman_tool_boxtalk_master` (`fk_site_id`, `fk_user_id`, `work_type`, `is_new`, `topic`, `citp_gt`, `duration`, `other_issues`, `img_key`, `pdf_key`, `mtform_createdon`) VALUES (:fk_site_id, :fk_user_id, :work_type, 1, :topic, :citp_gt, :duration, :other_issues, :img_key, :pdf_key, :mtform_createdon)";
				//prepare statement
					$stmt 	= $db->prepare($sql);
						$stmt->bindParam("fk_site_id"		, $reqData->siteid);
						$stmt->bindParam("fk_user_id"		, $reqData->userid);
						$stmt->bindParam("work_type"		, $reqData->worktype);
						$stmt->bindParam("topic"			, $reqData->topic);
						$stmt->bindParam("citp_gt"			, $reqData->citpgt);
						$stmt->bindParam("duration"			, $reqData->duration);
						$stmt->bindParam("other_issues"		, $reqData->otherissues);
						$stmt->bindParam("img_key"			, $reqData->imgkey);
						$stmt->bindParam("pdf_key"			, $pdfkey);
						$stmt->bindParam("mtform_createdon"	, $datetime);
					$stmt->execute();

				//last inserted data
					$fk_mtform_id	= $db->lastInsertId();

				//insert data
					$sql = "INSERT INTO `nman_tool_boxtalk_form` (`fk_mtform_id`, `work_type`, `input_1`, `input_2`, `tform_createdon`) VALUES (:fk_mtform_id, :work_type, :input_1, :input_2, :tform_createdon)";
				//prepare statement
					//loop start
					foreach($reqData->field_inputs as $key=>$values):
						$stmt 	= $db->prepare($sql);
							$stmt->bindParam("fk_mtform_id"		, $fk_mtform_id);
							$stmt->bindParam("work_type"		, $reqData->worktype);
							$stmt->bindParam("input_1"			, $values->input_1);
							$stmt->bindParam("input_2"			, $values->input_2);
							$stmt->bindParam("tform_createdon"	, $datetime);
						$stmt->execute();
					endforeach;
					//loop end
				//callback function generate pdf file
					$resultData->pdffile= prepare_pdf_file($reqData->siteid, $reqData->formid, $reqData->userid, $reqData->worktype, $pdfkey, 0, 'A4', 'landscape');
				//fetch result
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "Toolbox Talk created successfully";
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

//create new daywork sheet talk form
	function new_daywork_sheet_form($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
		$datetime	= date('Y-m-d H:i:s');
	//JSON values
		isset($reqData->siteid)?$reqData->siteid:$error[]				= "Site name";
		isset($reqData->formid)?$reqData->formid:$error[]				= "Form name";
		isset($reqData->userid)?$reqData->userid:$error[]				= "User id";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]		= "Mobile token";
		//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//pdf key
					$pdfkey = generate_mobile_token($reqData->userid);
				//insert master data
					$sql = "INSERT INTO `nman_daywork_sheet_form` (`fk_site_id`, `fk_user_id`, `work_type`,`input_1`, `input_2`, `input_3`, `site_representative`,`site_signature`, `user_signature`, `pdf_key`, `dform_createdon`) VALUES (:fk_site_id, :fk_user_id, :work_type, :input_1, :input_2, :input_3, :site_representative, :site_signature, :user_signature,  :pdf_key, :dform_createdon)";
				//prepare statement
					$stmt 	= $db->prepare($sql);
						$stmt->bindParam("fk_site_id"			, $reqData->siteid);
						$stmt->bindParam("fk_user_id"			, $reqData->userid);
						$stmt->bindParam("work_type"			, $reqData->worktype);
						$stmt->bindParam("input_1"				, $reqData->input_1);
						$stmt->bindParam("input_2"				, $reqData->input_2);
						$stmt->bindParam("input_3"				, $reqData->input_3);
						$stmt->bindParam("site_representative"	, $reqData->site_representative);
						$stmt->bindParam("site_signature"		, $reqData->site_signature);
						$stmt->bindParam("user_signature"		, $reqData->user_signature);
						$stmt->bindParam("pdf_key"				, $pdfkey);
						$stmt->bindParam("dform_createdon"		, $datetime);
					$stmt->execute();

				//last inserted data
					$fk_dsform_id	= $db->lastInsertId();
					
				//update query
						$job_number = "DWS".sprintf("%04d", $fk_dsform_id);
						$updqry = "UPDATE `nman_daywork_sheet_form` SET `daywork_ref`=:daywork_ref WHERE `pk_dform_id`=:pk_dform_id";
						$updstmt = $db->prepare($updqry);
							$updstmt->bindParam("daywork_ref"	, $job_number);
							$updstmt->bindParam("pk_dform_id"		, $fk_dsform_id);
						$updstmt->execute();
					
				//images upload
					$imgsql = "INSERT INTO `nman_daywork_sheet_images` (`fk_dsform_id`, `dsf_image`) VALUES (:fk_dsform_id, :dsf_image)";
				//prepare image statement
					if(isset($reqData->images) && !empty($reqData->images)):
					//looping start
						foreach($reqData->images as $key=>$img):
						//convert base64 image to JPG image file
							$data 		= str_replace('data:image/png;base64,', '', $img->image);
							$data 		= str_replace(' ', '+', $data);
							$data 		= base64_decode($data);
							$imgname 	= uniqid('IMG').".jpg";
							$siteimg 	= "reports/temp/".$imgname;
							$imagefile 	= file_put_contents($siteimg, $data);
						//insert query
							$istmt 	= $db->prepare($imgsql);
								$istmt->bindParam("fk_dsform_id", $fk_dsform_id);
								$istmt->bindParam("dsf_image"	, $siteimg);
							$istmt->execute();
						endforeach;
					//loop end
					endif;

				//callback function generate pdf file
					$resultData->pdffile= prepare_pdf_file($reqData->siteid, $reqData->formid, $reqData->userid, $reqData->worktype, $pdfkey, 0, 'A4', 'portrait');
				//fetch result
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "Daywork Sheet created successfully";
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

//create new request information form
	function new_request_information_form($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
		$datetime	= date('Y-m-d H:i:s');
	//JSON values
		isset($reqData->siteid)?$reqData->siteid:$error[]				= "Site name";
		isset($reqData->formid)?$reqData->formid:$error[]				= "Form name";
		isset($reqData->signature)?$reqData->signature:$error[]			= "Signature";
		isset($reqData->userid)?$reqData->userid:$error[]				= "User id";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]		= "Mobile token";
		//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//pdf key
					$pdfkey = generate_mobile_token($reqData->userid);
				//insert master data
					$sql = "INSERT INTO `nman_request_information_form` (`fk_site_id`, `fk_user_id`, `work_type`, `input_1`, `input_2`, `input_3`, `signature`, `pdf_key`, `rform_createdon`) VALUES (:fk_site_id, :fk_user_id, :work_type, :input_1, :input_2, :input_3, :signature, :pdf_key, :rform_createdon)";
				//prepare statement
					$stmt 	= $db->prepare($sql);
						$stmt->bindParam("fk_site_id"		, $reqData->siteid);
						$stmt->bindParam("fk_user_id"		, $reqData->userid);
						$stmt->bindParam("work_type"		, $reqData->worktype);
						$stmt->bindParam("input_1"			, $reqData->input_1);
						$stmt->bindParam("input_2"			, $reqData->input_2);
						$stmt->bindParam("input_3"			, $reqData->input_3);
						$stmt->bindParam("signature"		, $reqData->signature);
						$stmt->bindParam("pdf_key"			, $pdfkey);
						$stmt->bindParam("rform_createdon"	, $datetime);
					$stmt->execute();

				//last inserted data
					$fk_rsform_id	= $db->lastInsertId();
				
				//update query
						$job_rfi_number = "JRFI".sprintf("%04d", $fk_rsform_id);
						$nbl_rfi_number = "NBLRFI".sprintf("%04d", $fk_rsform_id);
						$updqry = "UPDATE `nman_request_information_form` SET `job_rfi_no`=:job_rfi_no,`nbl_rfi_no`=:nbl_rfi_no WHERE `pk_rform_id`=:pk_rform_id";
						$updstmt = $db->prepare($updqry);
							$updstmt->bindParam("job_rfi_no"	, $job_rfi_number);
							$updstmt->bindParam("nbl_rfi_no"	, $nbl_rfi_number);
							$updstmt->bindParam("pk_rform_id"	, $fk_rsform_id);
						$updstmt->execute();
					
				//images upload
					$imgsql = "INSERT INTO `nman_request_information_images` (`fk_rform_id`, `rsf_image`) VALUES (:fk_rform_id, :rsf_image)";
				//prepare image statement
					if(isset($reqData->images) && !empty($reqData->images)):
					//looping start
						foreach($reqData->images as $key=>$img):
						//convert base64 image to JPG image file
							$data 		= str_replace('data:image/png;base64,', '', $img->image);
							$data 		= str_replace(' ', '+', $data);
							$data 		= base64_decode($data);
							$imgname 	= uniqid('IMG').".jpg";
							$siteimg 	= "reports/temp/".$imgname;
							$imagefile 	= file_put_contents($siteimg, $data);
						//insert query
							$istmt 	= $db->prepare($imgsql);
								$istmt->bindParam("fk_rform_id", $fk_rsform_id);
								$istmt->bindParam("rsf_image"	, $siteimg);
							$istmt->execute();
						endforeach;
					//loop end
					endif;


				//callback function generate pdf file
					$resultData->pdffile= prepare_pdf_file($reqData->siteid, $reqData->formid, $reqData->userid, $reqData->worktype, $pdfkey, 0, 'A4', 'portrait');
				//fetch result
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "Request For Information Form created successfully";
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

//create new starter form
	function create_new_starter_form($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->formid)?$reqData->formid:$error[]			= "Form name";
		isset($reqData->worktype)?$reqData->worktype:$error[]		= "Work name";
		isset($reqData->userid)?$reqData->userid:$error[]			= "User id";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]	= "Mobile token";
		//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//pdf key
					$pdfkey = generate_mobile_token($reqData->userid);
				//insert master data
					$sql = "INSERT INTO `nman_new_starter_form` (`fk_user_id`, `work_type`, `surname`, `forenames`, `address`, `date_of_birth`, `home_no`, `utr_no`, `next_of_kin`, `emp_type`, `next_of_kin_address`, `next_of_kin_contact_no`, `mobile_no`, `email`, `ni_no`, `relationship`, `bank_name`, `account_name`, `account_no`, `sort_code`, `ac_roll_no`, `cisrs_card`, `card_no`, `card_expiry_date`, `grade`, `other_training`, `trained_tg`, `trained_sg`, `signature`, `pdf_key`, `date`) VALUES (:fk_user_id, :work_type, :surname, :forenames, :address, :date_of_birth, :home_no, :utr_no, :next_of_kin, :emp_type, :next_of_kin_address, :next_of_kin_contact_no, :mobile_no, :email, :ni_no, :relationship, :bank_name, :account_name, :account_no, :sort_code, :ac_roll_no, :cisrs_card, :card_no, :card_expiry_date, :grade, :other_training, :trained_tg, :trained_sg, :signature, :pdf_key, :date)";
				//prepare statement						
					$stmt 	= $db->prepare($sql);
						$stmt->bindParam("fk_user_id"		, $reqData->userid);
						$stmt->bindParam("work_type"		, $reqData->worktype);
						$stmt->bindParam("surname"			, $reqData->surname);
						$stmt->bindParam("forenames"		, $reqData->forenames);
						$stmt->bindParam("address"			, $reqData->address);
						$stmt->bindParam("date_of_birth"	, date("Y-m-d", strtotime(str_replace('/','-', $reqData->dateofbirth))));
						$stmt->bindParam("home_no"			, $reqData->homeno);
						$stmt->bindParam("utr_no"			, $reqData->utrno);
						$stmt->bindParam("next_of_kin"		, $reqData->nextofkin);
						$stmt->bindParam("next_of_kin_address"	, $reqData->nextofkinaddress);
						$stmt->bindParam("next_of_kin_contact_no", $reqData->nextofkincontactno);
						$stmt->bindParam("emp_type"			, $reqData->emptype);
						$stmt->bindParam("mobile_no"		, $reqData->mobileno);
						$stmt->bindParam("email"			, $reqData->email);
						$stmt->bindParam("ni_no"			, $reqData->nino);
						$stmt->bindParam("relationship"		, $reqData->relationship);
						$stmt->bindParam("bank_name"		, $reqData->bankname);
						$stmt->bindParam("account_name"		, $reqData->accountname);
						$stmt->bindParam("account_no"		, $reqData->accountno);
						$stmt->bindParam("sort_code"		, $reqData->sortcode);
						$stmt->bindParam("ac_roll_no"		, $reqData->acrollno);
						$stmt->bindParam("cisrs_card"		, $reqData->cisrscard);
						$stmt->bindParam("card_no"			, $reqData->cardno);
						$stmt->bindParam("card_expiry_date"	, date("Y-m-d", strtotime(str_replace('/','-', $reqData->cardexpirydate))));
						$stmt->bindParam("grade"			, $reqData->grade);
						$stmt->bindParam("other_training"	, $reqData->othertraining);
						$stmt->bindParam("trained_tg"		, $reqData->trainedtg);
						$stmt->bindParam("trained_sg"		, $reqData->trainedsg);
						$stmt->bindParam("signature"		, $reqData->signature);
						$stmt->bindParam("pdf_key"			, $pdfkey);
						$stmt->bindParam("date"				, date('Y-m-d'));
					$stmt->execute();

				//last inserted data
					$pk_nsform_id	= $db->lastInsertId();

				//insert data
					$csql = "INSERT INTO `nman_new_starter_form_course` (`fk_nsform_id`, `course`, `course_date`) VALUES (:fk_nsform_id, :course, :course_date)";
				//prepare statement
					if(isset($reqData->courses) && !empty($reqData->courses)):
					//loop start
						foreach($reqData->courses as $key=>$values):
							$cstmt 	= $db->prepare($csql);
								$cstmt->bindParam("fk_nsform_id", $pk_nsform_id);
								$cstmt->bindParam("course"		, $values->course);
								$cstmt->bindParam("course_date"	, date("Y-m-d", strtotime(str_replace('/','-', $values->coursedate))));
							$cstmt->execute();
						endforeach;
					//loop end
					endif;

				//images upload
					$imgsql = "INSERT INTO `nman_new_starter_images` (`fk_nsform_id`, `nsf_image`) VALUES (:fk_nsform_id, :nsf_image)";
				//prepare image statement
					if(isset($reqData->images) && !empty($reqData->images)):
					//looping start
						foreach($reqData->images as $key=>$img):
						//convert base64 image to JPG image file
							$data 		= str_replace('data:image/png;base64,', '', $img->image);
							$data 		= str_replace(' ', '+', $data);
							$data 		= base64_decode($data);
							$imgname 	= uniqid('IMG').".jpg";
							$siteimg 	= "reports/temp/".$imgname;
							$imagefile 	= file_put_contents($siteimg, $data);
						//insert query
							$istmt 	= $db->prepare($imgsql);
								$istmt->bindParam("fk_nsform_id", $pk_nsform_id);
								$istmt->bindParam("nsf_image"	, $siteimg);
							$istmt->execute();
						endforeach;
					//loop end
					endif;

				//clear db
					$db 	= null;

				//callback function generate pdf file
					$resultData->pdffile= prepare_pdf_file("temp", $reqData->formid, $reqData->userid, $reqData->worktype, $pdfkey, $pk_nsform_id, 'A4', 'portrait');
				//fetch result
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "New starter form created successfully";
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

//add safty harness details
	function add_harness_details($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->userid)?$reqData->userid:$error[]			= "User id";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]	= "Mobile token";
		//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
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
						$sltstmt->bindParam("id_no"		, $reqData->idno);
					$sltstmt->execute();
				//check duplicate site entry
					if($sltstmt->rowCount() == 0):
					//insert master data
						$sql = "INSERT INTO `nman_safety_harness_details` (`id_no`, `make`, `model`, `serial_no`, `date_of_manufacture`, `purchase_date`, `owner`, `inspection_frequency`) VALUES (:id_no, :make, :model, :serial_no, :date_of_manufacture, :purchase_date, :owner, :inspection_frequency)";
					//prepare statement						
						$stmt 	= $db->prepare($sql);
							$stmt->bindParam("id_no"				, $reqData->idno);
							$stmt->bindParam("make"					, $reqData->make);
							$stmt->bindParam("model"				, $reqData->model);
							$stmt->bindParam("serial_no"			, $reqData->serial);
							$stmt->bindParam("date_of_manufacture"	, date("Y-m-d", strtotime($reqData->dateofmanufacture)));
							$stmt->bindParam("purchase_date"		, date("Y-m-d", strtotime($reqData->purchasedate)));
							$stmt->bindParam("owner"				, $reqData->owner);
							$stmt->bindParam("inspection_frequency"	, $reqData->inspectionfrequency);
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

//update safty harness details
	function update_harness_details($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->userid)?$reqData->userid:$error[]			= "User id";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]	= "Mobile token";
		//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
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
						$sltstmt->bindParam("pk_sdform_id"	, $reqData->sdformid);
						$sltstmt->bindParam("id_no"			, $reqData->idno);
					$sltstmt->execute();
				//check duplicate site entry
					if($sltstmt->rowCount() == 0):
					//insert master data
						$updsql = "UPDATE `nman_safety_harness_details` SET `id_no`=:id_no, `make`=:make, `model`=:model, `serial_no`=:serial_no, `date_of_manufacture`=:date_of_manufacture, `purchase_date`=:purchase_date, `owner`=:owner, `inspection_frequency`=:inspection_frequency  WHERE `pk_sdform_id`=:pk_sdform_id";
					//prepare statement
						$stmt 	= $db->prepare($updsql);
							$stmt->bindParam("pk_sdform_id"			, $reqData->sdformid);
							$stmt->bindParam("id_no"				, $reqData->idno);
							$stmt->bindParam("make"					, $reqData->make);
							$stmt->bindParam("model"				, $reqData->model);
							$stmt->bindParam("serial_no"			, $reqData->serial);
							$stmt->bindParam("date_of_manufacture"	, date("Y-m-d", strtotime($reqData->dateofmanufacture)));
							$stmt->bindParam("purchase_date"		, date("Y-m-d", strtotime($reqData->purchasedate)));
							$stmt->bindParam("owner"				, $reqData->owner);
							$stmt->bindParam("inspection_frequency"	, $reqData->inspectionfrequency);
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
	
//delete safty harness details
	function visibility_harness_details($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->sdformid)?$reqData->sdformid:$error[]		= "ID No";
		isset($reqData->visibility)?$reqData->visibility:$error[]	= "Update Status";
		isset($reqData->userid)?$reqData->userid:$error[]			= "User id";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]	= "Mobile token";
		//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//update data
					$updsql = "UPDATE `nman_safety_harness_details` SET `sdform_visibility`=:sdform_visibility WHERE `pk_sdform_id`=:pk_sdform_id";
				//prepare update statement
					$updstmt 	= $db->prepare($updsql);
						$updstmt->bindParam("sdform_visibility"	, $reqData->visibility);
						$updstmt->bindParam("pk_sdform_id"		, $reqData->sdformid);
					$updstmt->execute();
				//check duplicate site entry
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "Harness details status updated successfully";
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

//view all harness detsils
	function view_harness_list($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->visibility)?$reqData->visibility:$error[]	= "Harness status";
		isset($reqData->userid)?$reqData->userid:$error[]			= "User id";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]	= "Mobile token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//select data
					$sltsql = "SELECT `pk_sdform_id` AS sdformid, `id_no` AS idno, `make`, `model`, `serial_no` AS serial, `date_of_manufacture` AS dateofmanufacture, `purchase_date` AS purchasedate, `owner`, `inspection_frequency` AS inspectionfrequency, `sdform_visibility` AS visibility  FROM `nman_safety_harness_details` WHERE `sdform_visibility`=:sdform_visibility";
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

//create new create_safety harness inspection forms
	function create_safety_harness_inspection_forms($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->formid)?$reqData->formid:$error[]			= "Form name";
		isset($reqData->worktype)?$reqData->worktype:$error[]		= "Work name";
		isset($reqData->userid)?$reqData->userid:$error[]			= "User id";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]	= "Mobile token";
		//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//pdf key
					$pdfkey = generate_mobile_token($reqData->userid);
				//insert master data
					$sql = "INSERT INTO `nman_safety_harness_inspection_forms` (`fk_user_id`, `work_type`, `make`, `model`, `serial`, `date_of_manufacture`, `purchase_date`, `owner`, `inspection_frequency`, `location`, `state`, `inspected_on`, `inspected_by`, `burrs`, `corrosion`, `cracks`, `damaged`, `distorted`, `sharp_edges`, `abrasions`, `burns`, `cuts`, `discolouraction`, `excessive_soiling`, `frays`, `tears`, `cut_stiches`, `pulled_stiches`, `not_legible`, `not_secure`, `next_inspection_due`, `pdf_key`) VALUES (:fk_user_id, :work_type, :make, :model, :serial, :date_of_manufacture, :purchase_date, :owner, :inspection_frequency, :location, :state, :inspected_on, :inspected_by, :burrs, :corrosion, :cracks, :damaged, :distorted, :sharp_edges, :abrasions, :burns, :cuts, :discolouraction, :excessive_soiling, :frays, :tears, :cut_stiches, :pulled_stiches, :not_legible, :not_secure, :next_inspection_due, :pdf_key)";
				//prepare statement
					$stmt 	= $db->prepare($sql);
						$stmt->bindParam("fk_user_id"		, $reqData->userid);
						$stmt->bindParam("work_type"		, $reqData->worktype);
						$stmt->bindParam("make"				, $reqData->make);
						$stmt->bindParam("model"			, $reqData->model);
						$stmt->bindParam("serial"			, $reqData->serial);
						$stmt->bindParam("date_of_manufacture", date("Y-m-d", strtotime($reqData->dateofmanufacture)));
						$stmt->bindParam("purchase_date"	, date("Y-m-d", strtotime($reqData->purchasedate)));
						$stmt->bindParam("owner"			, $reqData->owner);
						$stmt->bindParam("inspection_frequency", $reqData->inspectionfrequency);
						$stmt->bindParam("location"			, $reqData->location);
						$stmt->bindParam("state"			, $reqData->state);
						$stmt->bindParam("inspected_on"		, date("Y-m-d"));
						$stmt->bindParam("inspected_by"		, $reqData->inspectedby);
						$stmt->bindParam("burrs"			, $reqData->burrs);
						$stmt->bindParam("corrosion"		, $reqData->corrosion);
						$stmt->bindParam("cracks"			, $reqData->cracks);
						$stmt->bindParam("damaged"			, $reqData->damaged);
						$stmt->bindParam("distorted"		, $reqData->distorted);
						$stmt->bindParam("sharp_edges"		, $reqData->sharpedges);
						$stmt->bindParam("abrasions"		, $reqData->abrasions);
						$stmt->bindParam("burns"			, $reqData->burns);
						$stmt->bindParam("cuts"				, $reqData->cuts);
						$stmt->bindParam("discolouraction"	, $reqData->discolouraction);
						$stmt->bindParam("excessive_soiling", $reqData->excessivesoiling);
						$stmt->bindParam("frays"			, $reqData->frays);
						$stmt->bindParam("tears"			, $reqData->tears);
						$stmt->bindParam("cut_stiches"		, $reqData->cutstiches);
						$stmt->bindParam("pulled_stiches"	, $reqData->pulledstiches);
						$stmt->bindParam("not_legible"		, $reqData->notlegible);
						$stmt->bindParam("not_secure"		, $reqData->notsecure);
						$stmt->bindParam("next_inspection_due", date("Y-m-d", strtotime($reqData->nextinspectiondue)));
						$stmt->bindParam("pdf_key"			, $pdfkey);
					$stmt->execute();

				//last inserted data
					$pk_shiform_id	= $db->lastInsertId();

				//clear db
					$db 	= null;
				//fetch result
					$status		= "true";
					$statuscode	= "200";
					$resultData->message = "Record added successfully";
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

//generate pdf file
	function generate_pdf_file($request, $response)
	{
		$paper 		= array(1=>array(0=>"A3", 1=>"portrait"), 3=>array(0=>"A4", 1=>"landscape"), 4=>array(0=>"A3", 1=>"landscape"), 5=>array(0=>"A3", 1=>"landscape"), 6=>array(0=>"A3", 1=>"landscape"), 8=>array(0=>"A4", 1=>"portrait"), 9=>array(0=>"A4", 1=>"portrait"), 10=>array(0=>"A4", 1=>"portrait"), 11=>array(0=>"A4", 1=>"landscape"), 12=>array(0=>"A3", 1=>"portrait"), 13=>array(0=>"A3", 1=>"portrait"), 14=>array(0=>"A4", 1=>"landscape"), 15=>array(0=>"A4", 1=>"portrait"), 16=>array(0=>"A3", 1=>"landscape"), 17=>array(0=>"A4", 1=>"landscape"), 18=>array(0=>"A3", 1=>"portrait"), 19=>array(0=>"A3", 1=>"portrait"));
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->siteid)		? $reqData->siteid		: $error[]	= "Site name";
		isset($reqData->formid)		? $reqData->formid		: $error[]	= "Form name";
		isset($reqData->worktype)	? $reqData->worktype	: $error[]	= "Work name";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "User id";
		isset($reqData->mobiletoken)? $reqData->mobiletoken : $error[]	= "Mobile token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//pdf key
				$pdfkey = generate_mobile_token($reqData->userid);
			//create PDF file
				$resultData->pdffile = prepare_pdf_file($reqData->siteid, $reqData->formid, $reqData->userid, $reqData->worktype, $pdfkey, 1, $paper[$reqData->formid][0], $paper[$reqData->formid][1]);
			//fetch result
				$status		= "true";
				$statuscode	= "200";
				$resultData->message= "New starter form created successfully";
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

//create new method statement form
	function new_work_equipment_inspection($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->siteid)?$reqData->siteid:$error[]				= "Site name";
		isset($reqData->formid)?$reqData->formid:$error[]				= "Form name";
		isset($reqData->field_inputs)?$reqData->field_inputs:$error[]	= "Form inputs";
		isset($reqData->worktype)?$reqData->worktype:$error[]			= "Work type";
		isset($reqData->imgkey)?$reqData->imgkey:$error[]				= "Image key";
		isset($reqData->userid)?$reqData->userid:$error[]				= "User id";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]		= "Mobile token";
		//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//update query
					$updqry = "UPDATE `nman_work_equipment_inspection` SET `is_new`=0 WHERE `fk_site_id`=:fk_site_id AND `fk_user_id`=:fk_user_id";
				//prepare update statement
					$updstmt 	= $db->prepare($updqry);
						$updstmt->bindParam("fk_site_id", $reqData->siteid);
						$updstmt->bindParam("fk_user_id", $reqData->userid);
					$updstmt->execute();
				//pdf key
					$pdfkey = generate_mobile_token($reqData->userid);
				//insert data
					$sql = "INSERT INTO `nman_work_equipment_inspection` (`fk_site_id`, `fk_user_id`, `work_type`, `input_1`, `input_2`, `input_3`, `input_4`, `input_5`, `is_new`, `img_key`, `pdf_key`) VALUES (:fk_site_id, :fk_user_id, :work_type, :input_1, :input_2, :input_3, :input_4, :input_5, 1, :img_key, :pdf_key)";
				//prepare statement
					foreach($reqData->field_inputs as $key=>$values):
						$stmt 	= $db->prepare($sql);
							$stmt->bindParam("fk_site_id"	, $reqData->siteid);
							$stmt->bindParam("fk_user_id"	, $reqData->userid);
							$stmt->bindParam("work_type"	, $reqData->worktype);
							$stmt->bindParam("input_1"		, $values->input_1);
							$stmt->bindParam("input_2"		, $values->input_2);
							$stmt->bindParam("input_3"		, $values->input_3);
							$stmt->bindParam("input_4"		, $values->input_4);
							$stmt->bindParam("input_5"		, $values->input_5);
							$stmt->bindParam("img_key"		, $reqData->imgkey);
							$stmt->bindParam("pdf_key"		, $pdfkey);
						$stmt->execute();
					endforeach;
				//callback function generate pdf file
					$resultData->pdffile= prepare_pdf_file($reqData->siteid, $reqData->formid, $reqData->userid, $reqData->worktype, $pdfkey, 0, 'A4', 'landscape');
				//fetch result
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "Work Equipment Inspection From Created Successfully";
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

//update PDF viewed status
	function update_pdf_notification($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->pdfid)?$reqData->pdfid:$error[]				= "PDF name";
		isset($reqData->userid)?$reqData->userid:$error[]			= "User id";
		isset($reqData->mobiletoken)?$reqData->mobiletoken:$error[]	= "Mobile token";
		//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//verify user token
			$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
		//check token
			if($checktoken > 0):
			//try block
				try {
				//database initialization
					$db = getDB();
				//update query
					$updqry = "UPDATE `nman_pdf_master` SET `is_viewed`=1 WHERE `pk_pdf_id`=:pk_pdf_id";
				//prepare update statement
					$updstmt 	= $db->prepare($updqry);
						$updstmt->bindParam("pk_pdf_id", $reqData->pdfid);
					$updstmt->execute();
				//fetch result
					$status		= "true";
					$statuscode	= "200";
					$resultData->message= "PDF status updated successfully";
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

//image upload
	function image_upload($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//get 
		isset($_POST["siteid"])?$_POST["siteid"]:$error[]			= "Site name";
		isset($_POST["formid"])?$_POST["formid"]:$error[]			= "Form name";
		isset($_POST["worktype"])?$_POST["worktype"]:$error[]		= "Work type";
		isset($_POST["imgkey"])?$_POST["imgkey"]:$error[]			= "Image key";
		isset($_POST["userid"])?$_POST["userid"]:$error[]			= "User id";
		isset($_POST["mobiletoken"])?$_POST["mobiletoken"]:$error[]	= "Mobile token";
		//check condition
		if(isset($error) && sizeof($error)):
			$response	= array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
			$sql = "UPDATE tbl_user_master SET ni_user_img=:ni_user_img WHERE ni_pk_user_id=:ni_pk_user_id";
			try{
				$db 	= getDB();
			//verify user token
				$checktoken	= verify_session_token($_POST["userid"], $_POST["mobiletoken"]);
			//check token
				if($checktoken > 0):
					$imgname 	= uniqid('SITE').".jpg";
					$siteimg 	= "reports/".$_POST["siteid"]."/".$imgname;
				//upload image
					if(move_uploaded_file($_FILES["file"]["tmp_name"], $siteimg)):
						chmod($siteimg, 0777);
					//update sql
						$sql = "INSERT INTO `nman_site_images` (`fk_site_id`, `fk_form_id`, `work_type`, `site_image`, `img_key`) VALUES (:fk_site_id, :fk_form_id, :work_type, :site_image, :img_key)";
					//prepare statement
						$stmt 	= $db->prepare($sql);
							$stmt->bindParam("fk_site_id"	, $_POST["siteid"]);
							$stmt->bindParam("fk_form_id"	, $_POST["formid"]);
							$stmt->bindParam("work_type"	, $_POST["worktype"]);
							$stmt->bindParam("site_image"	, $siteimg);
							$stmt->bindParam("img_key"		, $_POST["imgkey"]);
						$stmt->execute();
					//get last inserted id
						$resultData->imageid= $db->lastInsertId();
					//fetch result
						$status		= "true";
						$statuscode	= "200";
						$resultData->message= "Image uploaded successfully";
					endif;
				else:
					$statuscode	= "202";
					$resultData->message= "Your session expired. You will be redirected to the login page.";
				endif;
				$db 	= null;
			}catch(PDOException $e){
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		//push values to array
			array_push($resturnData, $resultData);
		//prepare return string
			$response = array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resturnData);
		endif;
	//return result
		echo json_encode($response);
	}

//upload slider image
	function upload_slider_images($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= array();
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->sliderimg)	? $reqData->sliderimg	: $error[]	= "User id";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "User id";
		isset($reqData->mobiletoken)? $reqData->mobiletoken : $error[]	= "User token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//try block
			try {
			//verify user token
				$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
			//check token
				if($checktoken > 0):
				//database initialization
					$db 		= getDB();
				//SQL query insert data
					$sql = "INSERT INTO `nman_slider_images` (`slider_image`) VALUES (:slider_image)";
				//prepare statement
					foreach($reqData->sliderimg AS $key=>$image):
						$stmt= $db->prepare($sql);
							$stmt->bindParam("slider_image"	, $image);
						$stmt->execute();
					endforeach;
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
	
//get other pdf reports
	function get_other_pdf_reports($request, $response)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= new stdClass();
		$resturnData= NULL;
		$status		= "false";
		$statuscode	= "201";
	//JSON values
		isset($reqData->usertype)	? $reqData->usertype	: $error[]	= "User Type";
		isset($reqData->userid)		? $reqData->userid		: $error[]	= "User id";
		isset($reqData->formid)		? $reqData->formid 		: $error[]	= "Form Id";
		isset($reqData->worktype)	? $reqData->worktype 	: $error[]	= "Work Type";
		isset($reqData->mobiletoken)? $reqData->mobiletoken : $error[]	= "Mobile Token";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//try block
			try {
			//verify user token
				$checktoken = verify_session_token($reqData->userid, $reqData->mobiletoken);
			//check token
				if($checktoken > 0):
				//database initialization
					$db 	= getDB();
				//select PDF file query
					if($reqData->usertype==1 || $reqData->usertype==0):
					//select query
						$sltsql = "SELECT PdfMaster.fk_site_id AS siteid, PdfMaster.work_type AS worktype, FormMaster.pk_form_id AS formid, FormMaster.form_name, PdfMaster.display_name AS pdfname, PdfMaster.pdf_name AS pdffile, DATE_FORMAT(PdfMaster.pdf_created_on, '%m/%d/%Y %h:%i %p') AS createdon, UserMaster.user_first_name AS firstname, UserMaster.user_last_name AS lastname FROM `nman_form_master` AS FormMaster INNER JOIN `nman_pdf_master` AS PdfMaster ON PdfMaster.`fk_form_id`=FormMaster.`pk_form_id` AND PdfMaster.`work_type`=:work_type INNER JOIN `nman_user_master` AS UserMaster ON UserMaster.`pk_user_id`=PdfMaster.`fk_user_id` WHERE FormMaster.`pk_form_id`=:pk_form_id";
					//prepare select statement
						$sltstmt= $db->prepare($sltsql);
							$sltstmt->bindParam("pk_form_id", $reqData->formid);
							$sltstmt->bindParam("work_type"	, $reqData->worktype);
						$sltstmt->execute();
					else:
					//select query
						$sltsql = "SELECT PdfMaster.fk_site_id AS siteid, PdfMaster.work_type AS worktype, FormMaster.pk_form_id AS formid, FormMaster.form_name, PdfMaster.display_name AS pdfname, PdfMaster.pdf_name AS pdffile, DATE_FORMAT(PdfMaster.pdf_created_on, '%m/%d/%Y %h:%i %p') AS createdon, UserMaster.user_first_name AS firstname, UserMaster.user_last_name AS lastname FROM `nman_form_master` AS FormMaster INNER JOIN `nman_pdf_master` AS PdfMaster ON PdfMaster.`fk_form_id`=FormMaster.`pk_form_id` AND PdfMaster.`work_type`=:work_type AND PdfMaster.`fk_user_id`=:fk_user_id INNER JOIN `nman_user_master` AS UserMaster ON UserMaster.`pk_user_id`=PdfMaster.`fk_user_id` WHERE FormMaster.`pk_form_id`=:pk_form_id";
					//prepare select statement
						$sltstmt= $db->prepare($sltsql);
							$sltstmt->bindParam("pk_form_id", $reqData->formid);
							$sltstmt->bindParam("work_type"	, $reqData->worktype);
							$sltstmt->bindParam("fk_user_id", $reqData->userid);
						$sltstmt->execute();
					endif;
				//fetch data
					$resultData = $sltstmt->fetchAll(PDO::FETCH_OBJ);
				//check OTP valid
					if($sltstmt->rowCount() > 0):
					//return data
						$resturnData 	= $resultData;
						$status 		= "true";
						$statuscode		= "200";
					else:
						$resultData["message"] = "Records not found";
					endif;
				else:
					$statuscode	= "202";
					$resultData->message= "Your session expired. You will be redirected to the login page.";
				endif;
			}
			catch(PDOException $e) {
				$resultData->message= $e->getMessage();
			}
		//prepare return string
			$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resturnData);
		endif;
	//return result
		echo json_encode($response);
	}

//get norman application details
	function get_app_info($request, $response, $args)
	{
	//variable declaration
		$resultData	= new stdClass();
	//clear database
		$db = null;
	//prepare return string
		$resultData->app_name 			= "Norman Group";
		$resultData->app_version 		= "1.0.2";
		$resultData->app_release_date 	= "20/07/2018";
		$resultData->app_developers 	= "Veerakumar , Karthik , Manoj Kumar & Rabert";
		$response 	= array("HTTP_StatusCode" => "200", "HTTP_StatusMessage" => "true", "data" => $resultData);
	//return result
		echo json_encode($response);
	}

//approv user
	function approve_user($request, $response, $args)
	{
	//variable declaration
		$resultData	= array();
		$status		= "false";
		$statuscode	= "201";
	//check requested values
		isset($args["userid"])?$args["userid"]: $error[] = "User Name";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
			try
			{
			//db initiation
				$db 	= getDB();
			//select query
				$updsql	= "UPDATE `nman_user_master` SET `is_approved`=1 WHERE `pk_user_id`=:pk_user_id";
			//prepare update statement
				$updstmt= $db->prepare($updsql);
					$updstmt->bindParam("pk_user_id", $args["userid"]);
				$updstmt->execute();
			//select SQL
				$sltsql = "SELECT * FROM `nman_user_master` WHERE `pk_user_id`=:pk_user_id";
			//prepare select statement
				$sltstmt= $db->prepare($sltsql);
					$sltstmt->bindParam("pk_user_id", $args["userid"]);
				$sltstmt->execute();
			//fetch data
				$fecthData = $sltstmt->fetch(PDO::FETCH_ASSOC);
			//fetch data
				$resultData["message"]= "User approval success";
			//return result
				$status		= "true";
				$statuscode	= "200";
			//send notification
				send_notification($args["userid"], "Login Approved", "Welcome ".$fecthData["user_first_name"]."".$fecthData["user_last_name"].", Your login has approved by Norman Group", "login_approval");
			} catch(PDOException $e) {
				$resultData["message"]= $e->getMessage();
			}
		//clear database
			$db = null;
		//prepare return string
			$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
		endif;
	//return result
		echo json_encode($response);
	}

//get personal information
	function get_all_users($request, $response, $args)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= "";
		$status		= "false";
		$statuscode	= "201";
	//check requested values
		isset($args["usertype"])?$args["usertype"]: $error[] = "User type";
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
					$sltstmt->bindParam("fk_type_id"		, $args["usertype"]);
					$sltstmt->bindParam("user_visibility"	, $args["status"]);
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

//get active user list
	function get_users_list($request, $response, $args)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= "";
		$status		= "false";
		$statuscode	= "201";
	//check requested values
		isset($args["usertype"])?$args["usertype"]: $error[] = "User type";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
			try
			{
			//db initiation
				$db 	= getDB();
			//select query
				$sltsql	= "SELECT `pk_user_id` AS userid, `user_emp_id` AS empid, `user_first_name` AS firstname, `user_last_name` AS lastname, `user_email` AS useremail, user_email_verified as emailverified, `user_phone` AS userphone, `fk_type_id` AS usertype, `work_type` AS worktype, `user_img` AS userimg, `is_approved` AS isapproved, `user_visibility` AS uservisibility FROM `nman_user_master` WHERE `fk_type_id`=:fk_type_id";
			//prepare statement
				$sltstmt= $db->prepare($sltsql);
					$sltstmt->bindParam("fk_type_id", $args["usertype"]);
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

//get personal information
	function user_info($request, $response, $args)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= "";
		$status		= "false";
		$statuscode	= "201";
	//check requested values
		isset($args["user_id"])?$args["user_id"]: $error[] = "user id";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
			try
			{
			//initiate db
				$db 	= getDB();
			//select query
				$sltsql	= "SELECT pk_user_id AS userid, `user_emp_id` AS empid, user_first_name as firstname, user_last_name as lastname, user_email as useremail, user_email_verified as emailverified, user_phone as userphone, user_phone_verified as phoneverified, user_dob as userdob, user_img as userimg, `is_approved` AS isapproved, fk_type_id as usertype, `user_visibility` AS uservisibility FROM `nman_user_master` WHERE `pk_user_id`=:pk_user_id";
			//prepare statement
				$sltstmt= $db->prepare($sltsql);
					$sltstmt->bindParam("pk_user_id", $args["user_id"]);
				$sltstmt->execute();
			//check data exists
				if($sltstmt->rowCount() > 0):
					$status		= "true";
					$statuscode	= "200";
				//fetch data
					$resultData = $sltstmt->fetch(PDO::FETCH_OBJ);
				else:
					$resultData["message"] = "Invalid user";
				endif;
			} catch(PDOException $e) {
				$resultData["message"] = $e->getMessage();
			}
		//destroy db
			$db = null;
		//prepare return string
			$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
		endif;
	//return result
		echo json_encode($response);
	}

//get all user types
	function user_types($request, $response, $args)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= "";
		$status		= "false";
		$statuscode	= "201";
	//try section
		try
		{
		//initiate db
			$db 	= getDB();
		//select query
			$sltsql	= "SELECT pk_type_id AS usertype, type_name AS typename FROM `nman_user_types` WHERE `type_visibility`=1";
		//prepare statement
			$sltstmt= $db->prepare($sltsql);
			$sltstmt->execute();
		//check data exists
			if($sltstmt->rowCount() > 0):
				$status		= "true";
				$statuscode	= "200";
			//fetch data
				$resultData = $sltstmt->fetchAll(PDO::FETCH_OBJ);
			else:
				$resultData["message"] = "Records not found";
			endif;
		} catch(PDOException $e) {
			$resultData["message"] = $e->getMessage();
		}
	//destroy db
		$db = null;
	//prepare return string
		$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
	//return result
		echo json_encode($response);
	}

//get all user types
	function user_types_status($request, $response, $args)
	{
	//variable declaration
		$reqData	= json_decode($request->getBody());
		$resultData	= "";
		$status		= "false";
		$statuscode	= "201";
	//check requested values
		isset($args["id"])?$args["id"]: $error[] 		 = "User type";
		isset($args["status"])?$args["status"]: $error[] = "User type status";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
			try
			{
			//initiate db
				$db 	= getDB();
			//select query
				$sltsql	= "UPDATE `nman_user_types` SET `type_visibility`=:type_visibility SET `pk_type_id`=:pk_type_id";
			//prepare statement
				$sltstmt= $db->prepare($sltsql);
					$sltstmt->bindParam("pk_type_id"		, $args["id"]);
					$sltstmt->bindParam("type_visibility"	, $args["status"]);
				$sltstmt->execute();
			//return status
				$status		= "true";
				$statuscode	= "200";
			//fetch data
				$resultData = $sltstmt->fetchAll(PDO::FETCH_OBJ);
			} catch(PDOException $e) {
				$resultData->message= $e->getMessage();
			}
		//destroy db
			$db = null;
		//prepare return string
			$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
		endif;
	//return result
		echo json_encode($response);
	}

//account verification
	function activation($request, $response, $args)
	{
		$returnArray 	= array();
		$user_is_active = 0;
	//post data
		isset($args["user_otp"])?$args["user_otp"]: $error[] = "Access Code";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
			try
			{
			//initiate db
				$db = getDB();
			//select query
				$sltsql	= "SELECT * FROM `nman_user_master` WHERE `user_otp`=:user_otp";
			//prepare select statement
				$sltstmt= $db->prepare($sltsql);
					$sltstmt->bindParam("user_otp", $args["user_otp"]);
				$sltstmt->execute();
			//fetch data
				$fetchResult = $sltstmt->fetch(PDO::FETCH_ASSOC);
			//check OTP valid
				if($sltstmt->rowCount() > 0):
				//check user account activation
					if($fetchResult["user_is_active"] == 1):
						$returnArray["status"]	= "false";
						$returnArray["message"]	= "Already Verified";
					else:
					//update query
						$updsql	 = "UPDATE `nman_user_master` SET `user_is_active`=1, `user_email_verified`=1 WHERE `user_otp`=:user_otp";
					//prepare update statement
						$updstmt = $db->prepare($updsql);
							$updstmt->bindParam("user_otp", $args["user_otp"]);
						$updstmt->execute();
					//check condition
						if($updstmt->rowCount() > 0):
							$returnArray["status"]	= "true";
							$returnArray["message"]	= "Account Verified";
						endif;
					endif;
				else:
					$returnArray["status"]	= "false";
					$resultData->message = "Records not found";
				endif;
			} catch(PDOException $e) {
				$verificationDetails->status=$e->getMessage();
			}
		//destroy db
			$db = null;
		//redirect page
			$redirectURL = ConstantURL()."/account_activation.php?status=".$returnArray["status"]."&approved=".$fetchResult["is_approved"]."&name=".$fetchResult["user_first_name"]." ".$fetchResult["user_last_name"];
			header('Location: '.$redirectURL);
			exit;
		endif;
	//return result
		echo json_encode($response);
	}

//reset password page
	function resetpassword($request, $response, $args)
	{
		$returnArray 	= array();
		$user_is_active = 0;
	//post data
		isset($args["user_otp"])?$args["user_otp"]: $error[] = "Access Code";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
			try
			{
			//initiate db
				$db = getDB();
			//select query
				$sltsql	= "SELECT * FROM `nman_user_master` WHERE `user_otp`=:user_otp";
			//prepare select statement
				$sltstmt= $db->prepare($sltsql);
					$sltstmt->bindParam("user_otp", $args["user_otp"]);
				$sltstmt->execute();
			//fetch data
				$fetchResult = $sltstmt->fetch(PDO::FETCH_ASSOC);
			} catch(PDOException $e) {
				$verificationDetails->status=$e->getMessage();
			}
		//destroy db
			$db = null;
		//redirect page
			$redirectURL = ConstantURL()."norman-admin/reset-password.php?id=".$fetchResult["pk_user_id"];
			header('Location: '.$redirectURL);
			exit;
		endif;
	//return result
		echo json_encode($response);
	}

//reset password page
	function reset_cust_password($request, $response, $args)
	{
		
		$returnArray 	= array();
		$user_is_active = 0;
	//post data
		isset($args["site_otp"])?$args["site_otp"]: $error[] = "Access Code";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage" => "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
			try
			{
			//initiate db
				$db = getDB();
			//select query
				$sltsql	= "SELECT * FROM `nman_site_master` WHERE `site_otp`=:site_otp";
			//prepare select statement
				$sltstmt= $db->prepare($sltsql);
					$sltstmt->bindParam("site_otp", $args["site_otp"]);
				$sltstmt->execute();
			//fetch data
				$fetchResult = $sltstmt->fetch(PDO::FETCH_ASSOC);
			} catch(PDOException $e) {
				$verificationDetails->status=$e->getMessage();
			}
		//destroy db
			$db = null;
		//redirect page

			 $redirectURL = ConstantURL()."norman-user/reset-password.php?id=".$fetchResult["pk_site_id"];
			header('Location: '.$redirectURL);
			exit;
		endif;
	//return result
		echo json_encode($response);
	}

	//get all site form
		function get_all_forms($request, $response, $args)
		{
			$status 	= "false";
			$statuscode	= "201";
			isset($args["visible"])?$args["visible"]: $error[] = "Form visible";
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
						$sltsql	= "SELECT * FROM `nman_form_master` WHERE `form_visibility`=1 AND `work_type`=:worktype ORDER BY `form_name` ASC";
					else:
						$sltsql	= "SELECT * FROM `nman_form_master` WHERE `is_visible`!=1 AND `form_visibility`=1 AND `work_type`=:worktype ORDER BY `form_name` ASC";
					endif;
					$worktype	= $args["worktype"];
				//prepare statement
					$sltstmt= $db->prepare($sltsql);
					$sltstmt->bindParam("worktype", $worktype);
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

//get site form inputs
	function get_form_inputs($request, $response, $args)
	{
		$status		= "false";
		$statuscode	= "201";
		isset($args["form_id"])?$args["form_id"]: $error[] = "Select Form";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage"=> "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
			try
			{
			//initiate db
				$db = getDB();
			//select query
				$sltsql	= "SELECT FORM.pk_form_id, FORM.form_name, FORM.form_sets, FIELDS.pk_field_id, FIELDS.fk_form_id, FORM.work_type, FIELDS.field_name, FIELDS.field_order, FIELDS.field_set FROM `nman_form_master` AS FORM INNER JOIN `nman_form_fields` AS FIELDS ON FIELDS.fk_form_id=FORM.pk_form_id AND FIELDS.field_visibility=1 WHERE `pk_form_id`=:pk_form_id";
			//prepare statement
				$sltstmt= $db->prepare($sltsql);
					$sltstmt->bindParam("pk_form_id", $args["form_id"]);
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
			$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
		endif;
	//return result
		echo json_encode($response);
	}

//get all created sites
	function get_all_sites($request, $response, $args)
	{
		$status		= "false";
		$statuscode	= "201";
	//try block
		try
		{
		//initiate db
			$db		= getDB();
		//select query
			$sltsql	= "SELECT SITES.pk_site_id AS siteid, SITES.fk_user_id AS assignid, SITES.site_name AS sitename, SITES.site_owner_name AS siteownername, SITES.site_address AS siteaddress, SITES.site_email AS siteemail, SITES.site_phone AS sitephone, SITES.site_plot_no AS siteplotno, SITES.site_signature AS sitesignature, SITES.site_manager AS sitemanager, SITES.site_job_number AS jobnumber, SITES.site_status AS sitestatus, DATE_FORMAT(SITES.site_created_on, '%d %b,%Y') AS createdon, ASSIGN.pk_assign_id AS assignid, USERS.user_first_name AS assignfirstname, USERS.user_last_name AS assignlastname FROM `nman_site_master` AS SITES LEFT JOIN `nman_site_assignment` AS ASSIGN ON ASSIGN.fk_site_id=SITES.pk_site_id AND ASSIGN.assigned_by=:fk_user_id LEFT JOIN `nman_user_master` AS USERS ON USERS.pk_user_id=ASSIGN.fk_user_id WHERE SITES.site_visibility=1 GROUP BY SITES.pk_site_id ORDER BY SITES.pk_site_id DESC";
		//prepare statement
			$sltstmt= $db->prepare($sltsql);
				$sltstmt->bindParam("fk_user_id", $args["userid"]);
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
		//destroy db
			$db = null;
		} catch(PDOException $e) {
			$resultData["message"] = $e->getMessage();
		}
	//prepare return string
		$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
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
	//JSON values
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
						$menustmt->bindParam("fk_user_id"		, $reqData->userid);
						$menustmt->bindParam("assign_visibility", $reqData->visibility);
					$menustmt->execute();
				//fetch menu
					$resultMenu = $menustmt->fetch(PDO::FETCH_OBJ);
				//check menu assigned
					if($menustmt->rowCount() > 0):
					//select data
						$sltsql = "SELECT `pk_form_id` AS pk_form_id, `form_name` AS form_name, `form_visibility` AS status FROM `nman_form_master` WHERE `pk_form_id` IN (".$resultMenu->fk_form_id.") AND `form_visibility`=:form_visibility";
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
	                    $sltsql = "SELECT `pk_form_id`, `form_name`, `form_type`, `work_type`, `form_visibility` AS status FROM `nman_form_master` WHERE `pk_form_id` IN (".$resultMenu->fk_form_id.") AND `form_visibility`=:form_visibility";
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

//get all un assigned site information
	function get_unassigned_sites($request, $response, $args)
	{
		$status		= "false";
		$statuscode	= "201";
		$resultData = "";
		$sltsql	= "";
	//post data
		isset($args["userid"])?$args["userid"]: $error[]	= "User name";
		isset($args["usertype"])?$args["usertype"]: $error[]= "User type";
		isset($args["worktype"])?$args["worktype"]: $error[]= "Work type";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage"=> "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
		//try block
			try
			{
			//initiate db
				$db		= getDB();
			//select query
				if($args["usertype"]==0):
					$sltsql	= "SELECT pk_site_id AS siteid, fk_user_id AS assignid, site_name AS sitename, site_owner_name AS siteownername, site_address AS siteaddress, site_email AS siteemail, site_phone AS sitephone, site_plot_no AS siteplotno, site_signature AS sitesignature, site_manager AS sitemanager, site_job_number AS jobnumber, site_status AS sitestatus, DATE_FORMAT(site_created_on, '%d %b,%Y') AS createdon FROM `nman_site_master` WHERE `site_visibility`=1 AND `pk_site_id` NOT IN (SELECT `fk_site_id` FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_user_master` AS USERS ON USERS.fk_type_id=1 WHERE ASSIGN.fk_site_id=nman_site_master.pk_site_id) GROUP BY pk_site_id ORDER BY pk_site_id DESC";
				//prepare statement
					$sltstmt= $db->prepare($sltsql);
						$sltstmt->bindParam("fk_type_id", $args["type"]);
					$sltstmt->execute();
				//fetch data
					$resultData = $sltstmt->fetchAll(PDO::FETCH_OBJ);
				else:
					$sltsql	= "SELECT SITE.pk_site_id AS siteid, SITE.fk_user_id AS assignid, SITE.site_name AS sitename, SITE.site_owner_name AS siteownername, SITE.site_address AS siteaddress, SITE.site_email AS siteemail, SITE.site_phone AS sitephone, SITE.site_plot_no AS siteplotno, SITE.site_signature AS sitesignature, SITE.site_manager AS sitemanager, SITE.site_job_number AS jobnumber, SITE.site_status AS sitestatus, DATE_FORMAT(SITE.site_created_on, '%d %b,%Y') AS createdon FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITE ON SITE.pk_site_id=ASSIGN.fk_site_id AND SITE.site_visibility=1 WHERE ASSIGN.fk_user_id=:fk_user_id AND ASSIGN.fk_site_id NOT IN (SELECT fk_site_id FROM nman_site_assignment WHERE fk_site_id=SITE.pk_site_id AND `work_type`=:work_type AND fk_user_id!=ASSIGN.fk_user_id) ORDER BY SITE.pk_site_id DESC";
				//prepare statement
					$sltstmt= $db->prepare($sltsql);
						$sltstmt->bindParam("fk_user_id", $args["userid"]);
						$sltstmt->bindParam("work_type"	, $args["worktype"]);
					$sltstmt->execute();
				//fetch data
					$resultData = $sltstmt->fetchAll(PDO::FETCH_OBJ);
				endif;
			//check OTP valid
				if($sltstmt->rowCount() > 0):
					$status 	= "true";
					$statuscode	= "200";
				else:
					$resultData["message"] = "Records not found";
				endif;
			//destroy db
				$db = null;
			//prepare return string
				$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
			} catch(PDOException $e) {
				$resultData["message"] = $e->getMessage();
			}
		endif;
	//return result
		echo json_encode($response);
	}

//get superviso assigned sites information
	function get_assigned_sites($request, $response, $args)
	{
		$status		= "false";
		$statuscode	= "201";
	//try block
		try
		{
		//initiate db
			$db = getDB();
		//select query
			$sltsql = "SELECT SITES.pk_site_id AS siteid, SITES.fk_user_id AS assignid, SITES.site_name AS sitename, SITES.site_owner_name AS siteownername, SITES.site_email AS siteemail, SITES.site_phone AS sitephone, SITES.site_plot_no AS siteplotno, site_signature AS sitesignature, SITES.site_job_number AS jobnumber, SITES.site_manager AS sitemanager, SITES.site_address AS siteaddress, SITES.site_status AS sitestatus, DATE_FORMAT(SITES.site_created_on, '%d %b,%Y') AS createdon, ASSIGNED.user_first_name AS assignfirstname, ASSIGNED.user_last_name AS assignlastname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES INNER JOIN `nman_user_master` AS ASSIGNED ON SITES.pk_site_id=ASSIGN.fk_site_id AND SITES.site_visibility=1 AND ASSIGNED.pk_user_id=ASSIGN.assigned_by WHERE ASSIGN.fk_user_id=:fk_user_id GROUP BY SITES.pk_site_id  ORDER BY SITES.pk_site_id DESC";
		//prepare statement
			$sltstmt= $db->prepare($sltsql);
				$sltstmt->bindParam("fk_user_id", $args["userid"]);
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
		$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
	//return result
		echo json_encode($response);
	}

//get superviso assignment information
	function get_assignment_info($request, $response, $args)
	{
		$status		= "false";
		$statuscode	= "201";
	//try block
		try
		{
		//initiate db
			$db = getDB();
		//select query
			$sltsql = "SELECT ASSIGN.work_type AS worktype, USERS.pk_user_id AS userid, USERS.user_first_name AS userfirstname, USERS.user_last_name AS userlastname,  USERS.user_email AS useremail FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_user_master` AS USERS ON USERS.pk_user_id=ASSIGN.fk_user_id WHERE ASSIGN.fk_site_id=:fk_site_id";
		//prepare statement
			$sltstmt= $db->prepare($sltsql);
				$sltstmt->bindParam("fk_site_id", $args["siteid"]);
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
		$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
	//return result
		echo json_encode($response);
	}

//get superviso assigned sites information
	function get_form_sites($request, $response, $args)
	{
		$status		= "false";
		$statuscode	= "201";
	//try block
		try
		{
		//initiate db
			$db 	= getDB();
		//select query
			$sltsql = "SELECT SITES.pk_site_id AS siteid, SITES.fk_user_id AS assignid, SITES.site_name AS sitename, SITES.site_owner_name AS siteownername, SITES.site_email AS siteemail, SITES.site_phone AS sitephone, SITES.site_plot_no AS siteplotno, site_signature AS sitesignature, SITES.site_job_number AS jobnumber, SITES.site_manager AS sitemanager, SITES.site_address AS siteaddress, SITES.site_status AS sitestatus, DATE_FORMAT(SITES.site_created_on, '%d %b,%Y') AS createdon, ASSIGNED.user_first_name AS assignfirstname, ASSIGNED.user_last_name AS assignlastname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES INNER JOIN `nman_user_master` AS ASSIGNED ON SITES.pk_site_id=ASSIGN.fk_site_id AND ASSIGNED.pk_user_id=ASSIGN.assigned_by WHERE ASSIGN.fk_user_id=:fk_user_id AND SITES.site_visibility=1 AND SITES.pk_site_id NOT IN (SELECT fk_site_id FROM `nman_pdf_master` WHERE `fk_form_id`=:fk_form_id AND `work_type`=:work_type AND `fk_site_id`=SITES.pk_site_id)";
		//prepare statement
			$sltstmt= $db->prepare($sltsql);
				$sltstmt->bindParam("fk_user_id", $args["userid"]);
				$sltstmt->bindParam("fk_form_id", $args["formid"]);
				$sltstmt->bindParam("work_type"	, $args["worktype"]);
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
		$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
	//return result
		echo json_encode($response);
	}

//get new assigned site
	function get_new_sites($request, $response, $args)
	{
		$status		= "false";
		$statuscode	= "201";
	//try block
		try
		{
		//initiate db
			$db 	= getDB();
		//select query
			$sltsql = "SELECT SITES.pk_site_id AS siteid, SITES.fk_user_id AS assignid, SITES.site_name AS sitename, SITES.site_owner_name AS siteownername, SITES.site_email AS siteemail, SITES.site_phone AS sitephone, SITES.site_plot_no AS siteplotno, site_signature AS sitesignature, SITES.site_job_number AS jobnumber, SITES.site_manager AS sitemanager, SITES.site_address AS siteaddress, SITES.site_status AS sitestatus, DATE_FORMAT(SITES.site_created_on, '%d %b,%Y') AS createdon, DATE_FORMAT(SITES.site_created_on, '%d/%b/%Y') AS createddate, DATE_FORMAT(SITES.site_created_on, '%h:%i %p') AS createdtime, ASSIGNED.user_first_name AS assignfirstname, ASSIGNED.user_last_name AS assignlastname FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_site_master` AS SITES INNER JOIN `nman_user_master` AS ASSIGNED ON SITES.pk_site_id=ASSIGN.fk_site_id AND ASSIGNED.pk_user_id=ASSIGN.assigned_by WHERE ASSIGN.fk_user_id=:fk_user_id AND DATE(ASSIGN.assign_createdon) = CURDATE()";
		//prepare statement
			$sltstmt= $db->prepare($sltsql);
				$sltstmt->bindParam("fk_user_id", $args["userid"]);
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
		$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
	//return result
		echo json_encode($response);
	}

//get site by id
	function get_sites_id($request, $response, $args)
	{
		$status		= "false";
		$statuscode	= "201";
	//try block
		try
		{
		//initiate db
			$db 	= getDB();
		//select query
			$sltsql = "SELECT SITES.pk_site_id AS siteid, SITES.fk_user_id AS assignid, SITES.site_name AS sitename, SITES.site_owner_name AS siteownername, SITES.site_email AS siteemail, SITES.site_phone AS sitephone, SITES.site_plot_no AS siteplotno, site_signature AS sitesignature, SITES.site_job_number AS jobnumber, SITES.site_manager AS sitemanager, SITES.site_address AS siteaddress, SITES.site_status AS sitestatus, DATE_FORMAT(SITES.site_created_on, '%d %b,%Y') AS createdon, USERS.user_first_name AS assignfirstname, USERS.user_last_name AS assignlastname FROM `nman_site_master` AS SITES INNER JOIN `nman_site_assignment` AS ASSIGN INNER JOIN `nman_user_master` AS USERS ON ASSIGN.fk_site_id=SITES.pk_site_id AND USERS.pk_user_id=ASSIGN.assigned_by WHERE SITES.pk_site_id=:pk_site_id";
		//prepare statement
			$sltstmt= $db->prepare($sltsql);
				$sltstmt->bindParam("pk_site_id", $args["siteid"]);
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
		$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
	//return result
		echo json_encode($response);
	}

//get location details
	function get_location($request, $response, $args)
	{
		$status		= "false";
		$statuscode	= "201";
	//try block
		try
		{
		//initiate db
			$db 	= getDB();
		//select query
			$sltsql = "SELECT `site_address` AS location FROM `nman_site_master` WHERE `site_visibility`=1 GROUP BY `site_address`";
		//prepare statement
			$sltstmt= $db->prepare($sltsql);
				$sltstmt->bindParam("pk_site_id", $args["siteid"]);
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
		$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
	//return result
		echo json_encode($response);
	}

//get toolbox title
	function get_toolbox_title($request, $response, $args)
	{
		$status		= "false";
		$statuscode	= "201";
	//try block
		try
		{
		//initiate db
			$db 	= getDB();
		//select query
			$sltsql = "SELECT * FROM `nman_toolbox_topic_master` WHERE `title_visibility`=:title_visibility AND `work_type`=:work_type";
		//prepare select statement
			$sltstmt= $db->prepare($sltsql);
				$sltstmt->bindParam("title_visibility", $args["visibility"]);
				$sltstmt->bindParam("work_type", $args["work_type"]);
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
		$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
	//return result
		echo json_encode($response);
	}

//get toolbox topic
	function get_toolbox_topic($request, $response, $args)
	{
		$status		= "false";
		$statuscode	= "201";
	//try block
		try
		{
		//initiate db
			$db 	= getDB();
		//select query
			$sltsql = "SELECT * FROM `nman_toolbox_topic_master` AS TITLE inner join nman_toolbox_topic AS TOPIC ON TOPIC.fk_title_id=TITLE.pk_title_id WHERE  TITLE.work_type=:work_type AND TITLE.title_visibility=:title_visibility";
		//prepare select statement
			$sltstmt= $db->prepare($sltsql);
				$sltstmt->bindParam("title_visibility"	, $args["visibility"]);
				$sltstmt->bindParam("work_type"			, $args["work_type"]);
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
		$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
	//return result
		echo json_encode($response);
	}

//get site created forms PDF
	function get_sites_pdf($request, $response, $args)
	{
		$status		= "false";
		$statuscode	= "201";
	//try block
		try
		{
		//initiate db
			$db 	= getDB();
		//select query
			$sltsql = "SELECT PDF.fk_site_id AS siteid, PDF.work_type AS worktype, FORMS.pk_form_id AS formid, FORMS.form_name, PDF.display_name AS pdfname, PDF.pdf_name AS pdffile, DATE_FORMAT(PDF.pdf_created_on, '%m/%d/%Y %h:%i %p') AS createdon, USERS.user_first_name AS firstname, USERS.user_last_name AS lastname FROM `nman_pdf_master` AS PDF INNER JOIN `nman_form_master` AS FORMS ON FORMS.pk_form_id=PDF.fk_form_id INNER JOIN `nman_user_master` AS USERS ON USERS.pk_user_id=PDF.fk_user_id WHERE PDF.fk_site_id=:fk_site_id AND PDF.fk_form_id=:fk_form_id AND PDF.`work_type`=:work_type ORDER BY PDF.pk_pdf_id DESC";
		//prepare select statement
			$sltstmt= $db->prepare($sltsql);
				$sltstmt->bindParam("work_type"	, $args["worktype"]);
				$sltstmt->bindParam("fk_site_id", $args["siteid"]);
				$sltstmt->bindParam("fk_form_id", $args["formid"]);
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
		$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
	//return result
		echo json_encode($response);
	}

//get site uploaded documents
	function get_sites_documents($request, $response, $args)
	{
		$status		= "false";
		$statuscode	= "201";
	//try block
		try
		{
		//initiate db
			$db 	= getDB();
		//select query
			$sltsql = "SELECT DOC.pk_doc_id AS docid, DOC.doc_name AS docname, DOC.site_document AS sitedocument, DOC.doc_description AS docdescription, DOC.doc_type AS doctype, doc_size AS docsize, SITE.site_name AS sitename, USERS.user_first_name AS firstname, USERS.user_last_name AS lastname FROM `nman_site_documents` AS DOC INNER JOIN `nman_site_master` AS SITE INNER JOIN `nman_user_master` AS USERS ON SITE.pk_site_id=DOC.fk_site_id AND USERS.pk_user_id=DOC.fk_user_id WHERE DOC.fk_site_id=:fk_site_id AND `doc_visibility`=1 ORDER BY DOC.doc_type ASC";
		//prepare select statement
			$sltstmt= $db->prepare($sltsql);
				$sltstmt->bindParam("fk_site_id", $args["siteid"]);
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
		$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
	//return result
		echo json_encode($response);
	}

//get site created forms PDF
	function get_other_pdf($request, $response, $args)
	{
		$status		= "false";
		$statuscode	= "201";
		$sltsql = "";
	//try block
		try
		{
		//initiate db
			$db 	= getDB();
		//select PDF file query
			if($args["usertype"]==1 || $args["usertype"]==0):
				$sltsql = "SELECT PDF.fk_site_id AS siteid, PDF.work_type AS worktype, FORMS.pk_form_id AS formid, FORMS.form_name, PDF.display_name AS pdfname, PDF.pdf_name AS pdffile, DATE_FORMAT(PDF.pdf_created_on, '%m/%d/%Y %h:%i %p') AS createdon, USERS.user_first_name AS firstname, USERS.user_last_name AS lastname FROM `nman_pdf_master` AS PDF INNER JOIN `nman_form_master` AS FORMS INNER JOIN `nman_user_master` AS USERS ON FORMS.pk_form_id=PDF.fk_form_id AND USERS.pk_user_id=PDF.fk_user_id WHERE PDF.fk_site_id=0 ORDER BY PDF.pk_pdf_id DESC";
			//prepare select statement
				$sltstmt= $db->prepare($sltsql);					
				$sltstmt->execute();
			else:
				$sltsql = "SELECT PDF.fk_site_id AS siteid, PDF.work_type AS worktype, FORMS.pk_form_id AS formid, FORMS.form_name, PDF.display_name AS pdfname, PDF.pdf_name AS pdffile, DATE_FORMAT(PDF.pdf_created_on, '%m/%d/%Y %h:%i %p') AS createdon, USERS.user_first_name AS firstname, USERS.user_last_name AS lastname FROM `nman_pdf_master` AS PDF INNER JOIN `nman_form_master` AS FORMS INNER JOIN `nman_user_master` AS USERS ON FORMS.pk_form_id=PDF.fk_form_id AND USERS.pk_user_id=PDF.fk_user_id WHERE PDF.fk_site_id=0 AND PDF.fk_user_id=:fk_user_id ORDER BY PDF.pk_pdf_id DESC";
			//prepare select statement
				$sltstmt= $db->prepare($sltsql);
					$sltstmt->bindParam("fk_user_id", $args["userid"]);
				$sltstmt->execute();
			endif;
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
		$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
	//return result
		echo json_encode($response);
	}

//get app slider images
	function get_slider_images($request, $response, $args)
	{
		$status		= "false";
		$statuscode	= "201";
		$sltsql = "";
	//try block
		try
		{
		//initiate db
			$db 	= getDB();
		//select query
			$sltsql = "SELECT * FROM `nman_slider_images` WHERE `is_visible`=:is_visible";
		//prepare select statement
			$sltstmt= $db->prepare($sltsql);
				$sltstmt->bindParam("is_visible", $args["visibility"]);
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
		$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
	//return result
		echo json_encode($response);
	}

//remove slider images
	function remove_slider_images($request, $response, $args)
	{
		$status		= "false";
		$statuscode	= "201";
		$sltsql = "";
	//try block
		try
		{
		//initiate db
			$db 	= getDB();
		//select query
			$updsql = "UPDATE `nman_slider_images` SET `is_visible`=0 WHERE `pk_slider_id`=:pk_slider_id";
		//prepare select statement
			$updstmt= $db->prepare($updsql);
				$updstmt->bindParam("pk_slider_id", $args["id"]);
			$updstmt->execute();
		//return status
			$resultData["message"] = "Slider image removed successfully";
			$status 	= "true";
			$statuscode	= "200";
		} catch(PDOException $e) {
			$resultData["message"] = $e->getMessage();
		}
	//destroy db
		$db = null;
	//prepare return string
		$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
	//return result
		echo json_encode($response);
	}

//get new user notification
	function get_user_notification($request, $response, $args)
	{
		$status		= "false";
		$statuscode	= "201";
	//try block
		try
		{
		//initiate db
			$db 	= getDB();
		//select PDF file query
			$sltsql = "SELECT `pk_user_id` AS userid, `user_emp_id` AS empid, `user_first_name` as firstname, `user_last_name` as lastname, `user_email` as useremail, `user_email_verified` as emailverified, `user_phone` as userphone, `user_dob` as userdob, `user_img` as userimg, `fk_type_id` as usertype, `work_type` AS worktype, `is_approved` AS isapproved, `user_visibility` AS uservisibility FROM `nman_user_master` WHERE `is_approved`=0 AND `user_visibility`=1";
		//prepare select statement
			$sltstmt= $db->prepare($sltsql);
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
		$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
	//return result
		echo json_encode($response);
	}

//get PDF notification
	function get_pdf_notification($request, $response, $args)
	{
		$status		= "false";
		$statuscode	= "201";
	//try block
		try
		{
		//initiate db
			$db 	= getDB();
		//select PDF file query
			$sltsql = "SELECT PDF.pk_pdf_id AS pdfid, PDF.fk_site_id AS siteid, PDF.fk_form_id AS formid, PDF.is_viewed AS isviewed, PDF.pdf_name AS pdfname, SITE.site_name AS sitename, FORMS.form_name AS formname, USERS.user_first_name AS firstname, USERS.user_last_name AS lastname, DATE_FORMAT(PDF.pdf_created_on, '%d/%b/%Y') AS createddate, DATE_FORMAT(PDF.pdf_created_on, '%h:%i %p') AS createdtime FROM `nman_site_assignment` AS ASSIGN INNER JOIN `nman_pdf_master` AS PDF INNER JOIN `nman_site_master` AS SITE INNER JOIN `nman_form_master` AS FORMS INNER JOIN `nman_user_master` AS USERS ON PDF.fk_site_id=ASSIGN.fk_site_id AND PDF.is_viewed=0 AND SITE.pk_site_id=PDF.fk_site_id AND FORMS.pk_form_id=PDF.fk_form_id AND USERS.pk_user_id=PDF.fk_user_id WHERE ASSIGN.fk_user_id=:fk_user_id ORDER BY PDF.pk_pdf_id DESC";
		//prepare select statement
			$sltstmt= $db->prepare($sltsql);
				$sltstmt->bindParam("fk_user_id", $args["userid"]);
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
		$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
	//return result
		echo json_encode($response);
	}

//get site form information
	function remove_site_image($request, $response, $args)
	{
		$status		= "false";
		$statuscode	= "201";
		$resultData	= "";
		isset($args["imgid"])?$args["imgid"]: $error[] = "Select site name";
	//check condition
		if(isset($error) && sizeof($error)):
			$response = array("HTTP_StatusCode" => "404", "HTTP_StatusMessage"=> "validation error", "data" => implode(",", $error)." Parameter Required");
		else:
			try
			{
			//initiate db
				$db = getDB();
			//select query
				$sltsql	= "UPDATE `nman_site_images` SET `image_visibility`=0 WHERE `pk_site_img_id`=:pk_site_img_id";
			//prepare statement
				$sltstmt= $db->prepare($sltsql);
					$sltstmt->bindParam("pk_site_img_id", $args["imgid"]);
				$sltstmt->execute();
			//return result
				$status 	= "true";
				$statuscode	= "200";
				$resultData["message"] = "Image removed successfully";
			} catch(PDOException $e) {
				$resultData["message"] = $e->getMessage();
			}
		//destroy db
			$db = null;
		//prepare return string
			$response 	= array("HTTP_StatusCode" => $statuscode, "HTTP_StatusMessage" => $status, "data" => $resultData);
		endif;
	//return result
		echo json_encode($response);
	}

//create/generate pdf file
	function prepare_pdf_file($siteid, $formid, $userid, $worktype, $pdfkey, $others, $paper, $view)
	{
		
	//initiate db
		$db = getDB();
		$template = array(1=>"site-supervisor", 2=>"", 3=>"method-statement", 4=>"tool-box-talk-register", 5=>"safety-harness-inspection", 6=>"inspection-form", 7=>"", 8=>"handover-certificate", 9=>"new-starter-form", 10=>"health-medical-self-certification", 11=>"work_equipment_inspection",12=>"health-medical-self-certification",13=>"site-supervisor", 14=>"method-statement", 15=>"new-starter-form", 16=>"tool-box-talk-register",  17=>"work_equipment_inspection", 18=>"daywork-sheet", 19=>"request-for-information");
	//instantiate and use the dompdf class
		$options= new Options();
		$options->set('isRemoteEnabled', TRUE);
		$options->set('isHtml5ParserEnabled', true);
		$dompdf = new Dompdf($options);
	//read html content
		$html 	= file_get_contents(ConstantURL()."/templates/".$template[$formid].".php?siteid=".$siteid."&formid=".$formid."&userid=".$userid."&worktype=".$worktype."&others=".$others);

		
	    $dompdf->loadHtml($html);
	//(Optional) Setup the paper size and orientation
		$dompdf->setPaper($paper, $view);
	//Render the HTML as PDF
		$dompdf->render();
	//Output the generated PDF (1 = download and 0 = preview)
		$output = $dompdf->output();
		

	//check site exist or not
		if($siteid!='temp'):

			$sltsql	= "SELECT * FROM `nman_site_master` WHERE `pk_site_id`=:pk_site_id";
		//prepare statement
			$sltstmt= $db->prepare($sltsql);
				$sltstmt->bindParam("pk_site_id", $siteid);
			$sltstmt->execute();
		//fetch data
			$fetchData	= $sltstmt->fetch(PDO::FETCH_ASSOC);
			$tmpName	= strtoupper($fetchData["site_name"]);
			$pdfname	= preg_replace('/\s+/', '', $tmpName)."_[".date("d-m-Y_h-i-A").'].pdf';
			$filename	= 'reports/'.$siteid.'/'.$pdfname;

		//diplay name
			$displayname= str_replace('-',' ', $tmpName);
			$insqry = "INSERT INTO `nman_pdf_master` (`fk_user_id`, `fk_site_id`, `fk_form_id`, `pdf_name`, `display_name`, `work_type`, `pdf_key`) VALUES (:fk_user_id, :fk_site_id, :fk_form_id, :pdf_name, :display_name, :work_type, :pdf_key)";
		//prepare insert statement
			$insstmt= $db->prepare($insqry);
				$insstmt->bindParam("fk_user_id"	, $userid);
				$insstmt->bindParam("fk_site_id"	, $siteid);
				$insstmt->bindParam("fk_form_id"	, $formid);
				$insstmt->bindParam("pdf_name"		, $filename);
				$insstmt->bindParam("display_name"	, $displayname);
				$insstmt->bindParam("work_type"		, $worktype);
				$insstmt->bindParam("pdf_key"		, $pdfkey);
			$insstmt->execute();
		else:
			$tmpName	= strtoupper($template[$formid]);
			$pdfname	= preg_replace('/\s+/', '', $tmpName)."_[".date("d-m-Y_h-i-A").'].pdf';
			$filename	= 'reports/temp/'.$pdfname;
			//diplay name
			$displayname= str_replace('-',' ', $tmpName);
			$insqry = "INSERT INTO `nman_pdf_master` (`fk_user_id`, `fk_form_id`, `pdf_name`, `display_name`, `work_type`, `pdf_key`) VALUES (:fk_user_id, :fk_form_id, :pdf_name, :display_name, :work_type, :pdf_key)";
		//prepare insert statement
			$insstmt= $db->prepare($insqry);
				$insstmt->bindParam("fk_user_id"	, $userid);
				$insstmt->bindParam("fk_form_id"	, $formid);
				$insstmt->bindParam("pdf_name"		, $filename);
				$insstmt->bindParam("display_name"	, $displayname);
				$insstmt->bindParam("work_type"		, $worktype);
				$insstmt->bindParam("pdf_key"		, $pdfkey);
			$insstmt->execute();
		endif;
	//output file
		file_put_contents($filename, $output);
		chmod($filename,0777);
	//send PDF mail
		if($siteid=="temp"):
			send_other_pdf($siteid, $formid, $userid, $filename);
		else:
			send_site_pdf($siteid, $formid, $userid, $filename);
		endif;
	//destroy db
		$db = null;
	//return PDF file name
		return $filename;
	}

    function  send_email_test($emailData)
    {

		try
		{
			$mail = new PHPMailer;
			$mail->isSMTP();                                      	// Set mailer to use SMTP  
			//$mail->Host 		= 'smtp.gmail.com';  				// Specify main and backup SMTP servers
			//$mail->Host 		= 'smtp.norman-group.com';  				// Specify main and backup SMTP 
			//$mail->Host = 'tls://smtp.gmail.com:587';
			$mail->Host = 'smtp.livemail.co.uk';
			$mail->Port 		= 587;
			$mail->SMTPAuth 	= true;  
			$mail->SMTPDebug =2;
			
			   // Enable SMTP authentication
			//$mail->Username 	= 'equatortestmail@gmail.com';      // SMTP user name
			 //$mail->Password 	= 'Equator@6466';                   // SMTP password
			//$mail->Username 	= 'app@norman-group.com';      // SMTP user name
			//$mail->Password 	= 'Normangroupapp9';                   // SMTP password
			//$mail->Username 	= 'mns.darshantank@gmail.com';      // SMTP user name
		//		$mail->Password 	= 'MNS@#info804';                   // SMTP password
 		  /* ------------- New SMTP USER Detail ---------------  */
	   	  $mail->Username 	='app@norman-group.com';      // SMTP user name
		  $mail->Password 	='Normangroupapp9'; 

 		  /* ------------- ---------------  */
			//	$mail->Username 	= 'equatortestmail@gmail.com';      // SMTP user name
				//$mail->Password 	= 'Equator@6466';   
  			
			 	
			
			$mail->SMTPSecure 	= 'tls';                            // Enable encryption, 'SSL' also accepted
		
			$mail->From 		= 'app@norman-group.com';
			//$mail->From 		= 'mns.ajaydave@gmail.com';
			$mail->FromName 	= 'Norman Group';
		
			$mail->addAddress($emailData->email,'Receiver Name');     				// Add a recipient

			
			$mail->addBCC("mns.darshantank@gmail.com", "Norman Group");  
			$mail->WordWrap 	= 100000;                              // Set word wrap to 50 characters
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
//send email
	function send_email($emailData)
	{
		try
		{
			$mail = new PHPMailer;
			$mail->isSMTP();                                      	// Set mailer to use SMTP
		//	$mail->Host 		= 'smtp.gmail.com';  				// Specify main and backup SMTP servers

			$mail->Host 		= 'smtp.livemail.co.uk';  				// Specify main and backup SMTP servers

			
			$mail->SMTPAuth 	= true;                             // Enable SMTP authentication
			//$mail->Username 	= 'equatortestmail@gmail.com';      // SMTP user name
			 //$mail->Password 	= 'Equator@6466';                   // SMTP password
			//$mail->Username 	= 'app@norman-group.com';      // SMTP user name
			//$mail->Password 	= 'Normangroupapp9';                   // SMTP password
			$mail->Username 	= 'app@norman-group.com';      // SMTP user name
			$mail->Password 	= 'Normangroupapp9';                   // SMTP password
			
			$mail->SMTPSecure 	= 'tls';                            // Enable encryption, 'SSL' also accepted
			$mail->Port 		= 587;
			//$mail->From 		= 'app@norman-group.com';
			$mail->From 		= 'app@norman-group.com';
			$mail->FromName 	= 'Norman Group';
			$mail->addAddress($emailData->email);     				// Add a recipient
			//$mail->AddBCC("app@norman-group.com", "Norman Group");  

			$mail->addBCC("app@norman-group.com", "Norman Group"); 
		

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

//send pdf email
	function send_site_pdf($siteid, $formid, $userid, $pdffile)
	{
	//form name
	/*	$form = array(1=>"MANAGER / SUPERVISOR SITE VISIT REPORT", 2=>"", 3=>"METHOD STATEMENT & RISK ASSESSMENT REGISTER", 4=>"TOOLBOX TALK REGISTER", 5=>"3 MONTHLY HARNESS INSPECTION", 6=>"SCAFFOLDING INSPECTION FORM", 7=>"", 8=>"SCAFFOLDING HANDING OVER CERTIFICATE", 9=>"NEW STARTER FORM", 10=>"HEALTH & MEDICAL SELF CERTIFICATION", 11=>"WORK EQUIPMENT INSPECTION FORM",12=>"HEALTH & MEDICAL SELF CERTIFICATION", 13=>"MANAGER / SUPERVISOR SITE VISIT REPORT", 14=>"METHOD STATEMENT & RISK ASSESSMENT REGISTER",16=>"TOOLBOX TALK REGISTER", 15=>"NEW STARTER FORM",  17=>"WORK EQUIPMENT INSPECTION FORM", 18=>"DAYWORK SHEET FORM", 19=>"REQUEST FOR INSPECTION (FRI) FORM"); */

	$form = array(1=>"MANAGER / SUPERVISOR SITE VISIT REPORT", 2=>"", 3=>"METHOD STATEMENT & RISK ASSESSMENT REGISTER", 4=>"TOOLBOX TALK REGISTER", 5=>"3 MONTHLY HARNESS INSPECTION", 6=>"SCAFFOLDING INSPECTION FORM", 7=>"", 8=>"SCAFFOLDING HANDING OVER CERTIFICATE", 9=>"NEW STARTER FORM", 10=>"HEALTH & MEDICAL SELF CERTIFICATION", 11=>"WORK EQUIPMENT INSPECTION FORM",12=>"HEALTH & MEDICAL SELF CERTIFICATION", 13=>"MANAGER / SUPERVISOR SITE VISIT REPORT", 14=>"METHOD STATEMENT & RISK ASSESSMENT REGISTER",16=>"TOOLBOX TALK REGISTER", 15=>"NEW STARTER FORM",  17=>"WORK EQUIPMENT INSPECTION FORM", 18=>"DAYWORK SHEET FORM", 19=>"REQUEST FOR INFORMATION (RFI) FORM");

	//try block
		try
		{
		//initiate db variable
			$db = getDB();
		//select query
			$sltsql	= "SELECT * FROM `nman_site_master` AS SITE INNER JOIN `nman_site_assignment` AS ASSIGN INNER JOIN `nman_user_master` AS USERS ON USERS.pk_user_id=ASSIGN.assigned_by AND ASSIGN.fk_site_id=SITE.pk_site_id AND ASSIGN.fk_user_id=:fk_user_id WHERE SITE.pk_site_id=:pk_site_id";
		//prepare statement
			$sltstmt= $db->prepare($sltsql);
				$sltstmt->bindParam("fk_user_id", $userid);
				$sltstmt->bindParam("pk_site_id", $siteid);
			$sltstmt->execute();
		//fetch data
			$fetchData	= $sltstmt->fetch(PDO::FETCH_ASSOC);
		//prepare email
			$mail = new PHPMailer;
			$mail->isSMTP();                                      	// Set mailer to use SMTP
		//	$mail->Host 		= 'smtp.gmail.com';  				// Specify main and backup SMTP servers
			$mail->Host 		= 'smtp.livemail.co.uk';  				// Specify main and backup SMTP servers
			
			$mail->SMTPAuth 	= true;                             // Enable SMTP authentication
			//$mail->Username 	= 'equatortestmail@gmail.com';      // SMTP user name
			//$mail->Password 	= 'Equator@6466';                   // SMTP password
		//	$mail->Username 	= 'app@norman-group.com';      // SMTP user name
		//	$mail->Password 	= 'Normangroupapp9';                   // SMTP password
		    $mail->Username 	= 'app@norman-group.com';      // SMTP user name
		    $mail->Password 	= 'Normangroupapp9';                   // SMTP password
			$mail->SMTPSecure 	= 'tls';                            // Enable encryption, 'SSL' also accepted
			$mail->Port 		= 587;
			//$mail->From 		= 'app@norman-group.com';
			$mail->From 		= 'app@norman-group.com';
			
			$mail->FromName 	= 'Norman Group';  
			$mail->addAddress($fetchData["user_email"]);  			// Add a recipient
			$mail->addAddress($fetchData["site_email"]);
			$mail->addBCC("app@norman-group.com", "Norman Group");  

			
			//$mail->AddCC("veerakumar@equatortek.in");
			$mail->WordWrap 	= 100;                              // Set word wrap to 50 characters
			$mail->isHTML(true);                                  	// Set email format to HTML
			$mail->Subject 		= "Norman Group - ".$fetchData["site_name"]." - ".$form[$formid];
		//Replace the codetags with the message contents
			$mailcontent		= file_get_contents('email_templates/send_pdf.html');
			$replacements 		= array('({{form-name}})'=> $form[$formid], '({{site-name}})'=>$fetchData["site_name"], '({{owner-name}})'=> $fetchData["site_owner_name"], '({{location}})'=>$fetchData["site_address"]);
			$mail->Body 		= preg_replace(array_keys($replacements), array_values($replacements), $mailcontent);
			$mail->AddAttachment($pdffile);
		//send email
			$mail->send();
		//send notification
			send_notification($fetchData["pk_user_id"], "New form received", "You have received a new form ".$form[$formid]." for ".$fetchData["site_name"], "send_pdf");
		//destroy db
			$db = null;
		}
		catch (phpmailerException $pe)
		{
			$status=$pe->errorMessage();
		}
	}

//send other PDF email
	function send_other_pdf($siteid, $formid, $userid, $pdffile)
	{
	//form name
		/* $form = array(1=>"MANAGER / SUPERVISOR SITE VISIT REPORT", 2=>"", 3=>"METHOD STATEMENT & RISK ASSESSMENT REGISTER", 4=>"TOOLBOX TALK REGISTER", 5=>"3 MONTHLY HARNESS INSPECTION", 6=>"SCAFFOLDING INSPECTION FORM", 7=>"", 8=>"SCAFFOLDING HANDING OVER CERTIFICATE", 9=>"NEW STARTER FORM", 10=>"HEALTH & MEDICAL SELF CERTIFICATION", 11=>"WORK EQUIPMENT INSPECTION FORM",12=>"HEALTH & MEDICAL SELF CERTIFICATION", 13=>"MANAGER / SUPERVISOR SITE VISIT REPORT", 14=>"METHOD STATEMENT & RISK ASSESSMENT REGISTER",16=>"TOOLBOX TALK REGISTER", 15=>"NEW STARTER FORM",  17=>"WORK EQUIPMENT INSPECTION FORM", 18=>"DAYWORK SHEET FORM", 19=>"REQUEST FOR INSPECTION (FRI) FORM"); 
		 */
		$form = array(1=>"MANAGER / SUPERVISOR SITE VISIT REPORT", 2=>"", 3=>"METHOD STATEMENT & RISK ASSESSMENT REGISTER", 4=>"TOOLBOX TALK REGISTER", 5=>"3 MONTHLY HARNESS INSPECTION", 6=>"SCAFFOLDING INSPECTION FORM", 7=>"", 8=>"SCAFFOLDING HANDING OVER CERTIFICATE", 9=>"NEW STARTER FORM", 10=>"HEALTH & MEDICAL SELF CERTIFICATION", 11=>"WORK EQUIPMENT INSPECTION FORM",12=>"HEALTH & MEDICAL SELF CERTIFICATION", 13=>"MANAGER / SUPERVISOR SITE VISIT REPORT", 14=>"METHOD STATEMENT & RISK ASSESSMENT REGISTER",16=>"TOOLBOX TALK REGISTER", 15=>"NEW STARTER FORM",  17=>"WORK EQUIPMENT INSPECTION FORM", 18=>"DAYWORK SHEET FORM", 19=>"REQUEST FOR INFORMATION (RFI) FORM"); 
		
		$usertype = 0;
	//try block
		try
		{
		//initiate db variable
			$db = getDB();
		//select query
			$sltsql	= "SELECT * FROM `nman_user_master` WHERE `fk_type_id`=:fk_type_id";
		//prepare statement
			$sltstmt= $db->prepare($sltsql);
				$sltstmt->bindParam("fk_type_id", $usertype);
			$sltstmt->execute();
		//fetch data
			$fetchData	= $sltstmt->fetch(PDO::FETCH_ASSOC);
		//prepare email
			$mail = new PHPMailer;
			$mail->isSMTP();                                      	// Set mailer to use SMTP
			//$mail->Host 		= 'smtp.gmail.com';  				// Specify main and backup SMTP servers
			$mail->Host 		= 'smtp.livemail.co.uk';  				// Specify main and backup SMTP servers

			$mail->SMTPAuth 	= true;                             // Enable SMTP authentication
			//$mail->Username 	= 'equatortestmail@gmail.com';      // SMTP user name
			//$mail->Password 	= 'Equator@6466';                   // SMTP password
			//$mail->Username 	= 'app@norman-group.com';      // SMTP user name
			//$mail->Password 	= 'Normangroupapp9';                   // SMTP password
			$mail->Username 	= 'app@norman-group.com';      // SMTP user name
			$mail->Password 	= 'Normangroupapp9';                   // SMTP password
			$mail->SMTPSecure 	= 'tls';                            // Enable encryption, 'SSL' also accepted
			$mail->Port 		= 587;
			$mail->From 		= 'app@norman-group.com';
		
			$mail->FromName 	= 'Norman Group';
			$mail->addAddress($fetchData["user_email"]);  			// Add a recipient
		    $mail->addBCC("app@norman-group.com", "Norman Group");  
			$mail->WordWrap 	= 100;                              // Set word wrap to 50 characters
			$mail->isHTML(true);                                  	// Set email format to HTML
			$mail->Subject 		= "Norman Group - ".$form[$formid];
		//Replace the codetags with the message contents
			$mailcontent		= file_get_contents('email_templates/send_other_pdf.html');
			$replacements 		= array('({{form-name}})'=> $form[$formid]);
			$mail->Body 		= preg_replace(array_keys($replacements), array_values($replacements), $mailcontent);
			$mail->AddAttachment($pdffile);
		//send email
			$mail->send();
		//send notification
			send_notification($fetchData["pk_user_id"], "New form received", "You have received a new form ".$form[$formid], "send_pdf");
		//destroy db
			$db = null;
		}
		catch (phpmailerException $pe)
		{
			$status=$pe->errorMessage();
		}
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
			/*$strOut = sprintf('http://%s:%d/',
				$_SERVER['SERVER_NAME'],
				$_SERVER['SERVER_PORT']);*/
				$strOut = sprintf('http://%s:/',
				$_SERVER['SERVER_NAME']);
		} else {
			/*$strOut = sprintf('https://%s:%d/',
				$_SERVER['SERVER_NAME'],
				$_SERVER['SERVER_PORT']);*/
				$strOut = sprintf('https://%s:/',
				$_SERVER['SERVER_NAME']);

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
