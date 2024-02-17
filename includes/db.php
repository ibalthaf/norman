<?php
//database connection
	function getDB()
	{
	//set-up database configuration
		/*$dbhost	= "52.74.194.29";
		$dbuser	= "norman";
		$dbpass	= "Kjbf78HNvf6hjui";
		$dbname	= "norman";*/
		$dbhost	= "localhost";
		$dbuser	= "norman";
		$dbpass	= "Kjbf78HNvf6hjui";
		$dbname	= "norman";
	//connection establish
		$dbConnection	= new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass); 
		$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//return values
		return $dbConnection;
	}
?>
