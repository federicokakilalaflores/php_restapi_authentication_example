<?php
	// HEADERS
	header( "Access-Control-Allow-Origin: http://localhost/backend-exercises/
	php_restapi_authentication_with_JWT/" );
	header( "Content-Type: application/json; CHARSET=UTF-8" );
	header( "Access-Control-Allow-Methods: POST" ); 
	header( "Access-Control-Max-Age: 3600" );
	header( "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, 
	Authorization, X-Requested-With" );

	// INCLUDES PHP FILES NEEDED 
	include_once( 'config/core.php' ); 
	include_once( 'config/Database.php' );
	include_once( 'classes/User.php' ); 

	$database = new Database();
	$conn = $database->connect();

	$userObj = new User($conn);

	$data = json_decode( file_get_contents( "php://input" ) );

	$userObj->email = $data->email;
	$isEmailExist = $userObj->isEmailExist();

	// JWT FILES
	include_once( "libs/php-jwt/src/BeforeValidException.php" );
	include_once( "libs/php-jwt/src/ExpiredException.php" );
	include_once( "libs/php-jwt/src/JWK.php" );
	include_once( "libs/php-jwt/src/JWT.php" );
	include_once( "libs/php-jwt/src/SignatureInvalidException.php" );
	use \Firebase\JWT\JWT;


	if( $isEmailExist && password_verify($data->password, $userObj->password) ){

		$payload = array(
			"iss" => $iss,
			"aud" => $aud,
			"iat" => $iat,
			"nbf" => $nbf,
			"data" => array(
				"id" => $userObj->id,
				"lastname" => $userObj->lastname,
				"firstname" => $userObj->firstname,
				"email" => $userObj->email
			)
		);

		$jwt = JWT::encode($payload, $key);

		// response code 200 - ok
		http_response_code(200);
		echo json_encode( 
			array( 
				"message" => "Login success.",
				"jwt" => $jwt 
			) 
		);  


	}else{

		// response code 401 - unauthorized
		http_response_code(401);
		echo json_encode( array( "message" => "Unable to Login." ) );

	}


	

?>