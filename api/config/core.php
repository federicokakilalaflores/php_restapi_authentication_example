<?php
	
	ini_set("display_errors", true);
	ini_set("display_startup_errors", true);
	error_reporting(E_ALL);	  

	date_default_timezone_set("Asia/Manila");

	$home_url = "http://localhost/backend-exercises/php_restapi_authentication_with_JWT";

	// variables used for jwt
	$key = "secret"; 
	$iss = "http://example.org";
	$aud = "http://example.com";
	$iat = 1356999524;
	$nbf = 1357000000;
  
?>