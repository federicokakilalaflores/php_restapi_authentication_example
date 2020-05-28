<?php
	// HEADERS
	header( "Access-Control-Allow-Origin: http://localhost/backend-exercises/
	php_restapi_authentication_with_JWT/" );
	header( "Content-Type: application/json; CHARSET=UTF-8" );
	header( "Access-Control-Allow-Methods: POST" );
	header( "Access-Control-Max-Age: 3600" );
	header( "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers,
	Authorization, X-Requested-With" );


	// JWT FILES
	include_once( "config/core.php" );
	include_once( "libs/php-jwt/src/BeforeValidException.php" );
	include_once( "libs/php-jwt/src/ExpiredException.php" );
	include_once( "libs/php-jwt/src/JWK.php" );
	include_once( "libs/php-jwt/src/JWT.php" );
	include_once( "libs/php-jwt/src/SignatureInvalidException.php" );
	use \Firebase\JWT\JWT;

	$data = json_decode( file_get_contents( "php://input" ) );

	$jwt = isset( $data->jwt ) ? $data->jwt : "";

	if( $jwt ){    


		try{

			// decode token
			$decoded = JWT::decode($jwt, $key, array('HS256'));

			// set response code - ok
			http_response_code(200);

			echo json_encode(
				array(
					"message" => "Access granted.",
					"data" => $decoded->data 
				)
			);  
 

		}catch(Exception $e){ 

			// set response code - unauthorized
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


?>