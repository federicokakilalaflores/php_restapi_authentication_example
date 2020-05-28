<?php
	// HEADERS
	header( "Access-Control-Allow-Origin: http://localhost/backend-exercises/
	php_restapi_authentication_with_JWT/" );	
	header( "Content-Type: application/json; CHARSET=UTF-8" );	
	header( "Access-Control-Allow-Methods: POST" );	
	header( "Access-Control-Max-Age: 3600" );	
	header( "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers,
	Authorization, X-Requested-With" );	

	// CLASSES FILE & CONFIG FILE
	include_once( 'config/core.php' );
	include_once( 'config/Database.php' );
	include_once( 'classes/User.php' );

	// JWT LIBRARY FILES
	include_once( "libs/php-jwt/src/BeforeValidException.php" );
	include_once( "libs/php-jwt/src/ExpiredException.php" );
	include_once( "libs/php-jwt/src/JWK.php" );
	include_once( "libs/php-jwt/src/JWT.php" );
	include_once( "libs/php-jwt/src/SignatureInvalidException.php" );
	use \Firebase\JWT\JWT;

	// CREATES OBJECT
	$database = new Database();
	$conn = $database->connect();

	$userObj = new User($conn);

	// DECODE DATA FROM A CLIENT SIDE
	$data = json_decode( file_get_contents("php://input") );

	// JWT - JSON WEB TOKEN 
	$jwt = isset($data->jwt) ? $data->jwt : "";

	if( $jwt ){

		try{

			$decoded = JWT::decode($jwt, $key, array('HS256'));

			//SET OBJECT PROPERTY
			$userObj->id = $decoded->data->id;
			$userObj->lastname = $data->lastname;
			$userObj->firstname = $data->firstname;
			$userObj->email = $data->email;
			$userObj->password = $data->password;

			if( $userObj->update() ) {

				// regenerate JWT
				$payload =  array(
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

				$jwt = JWT::encode($jwt, $key);

				// response code  - ok
				http_response_code(200); 
				echo json_encode( 
					array(
				 		"message" => "Users credentials updated successfully.",
				 		"jwt" => $jwt 
					) 
				);

			}else{
				// response code - unauthorized
				http_response_code(401);
				echo json_encode( array( "message" => "Unable to update users credentials." ) );
			}

		}catch(Exception $e){

			// response code - unauthorized
			http_response_code(401);
			echo json_encode(
				array(
					"message" => "Access denied.",
					"error" => $e->getMessage()
				)
			);

		}

	}else{

		// response code - unauthorized
		http_response_code(401);
		echo json_encode( array( "message" => "Access denied." ) );

	}