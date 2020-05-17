<?php
	// HEADERS
	header( "Access-Control-Allow-Origin: http://localhost/backend-exercises/
	php_restapi_authentication_with_JWT/api" );
	header( "Content-Type: application/json; CHARSET=UTF-8" );
	header( "Access-Control-Allow-Method: POST" );
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

	if(
		!empty( $data->lastname ) &&
		!empty( $data->firstname ) &&
		!empty( $data->email ) &&
		!empty( $data->password ) 
	){

		$userObj->lastname = $data->lastname;
		$userObj->firstname = $data->firstname;
		$userObj->email = $data->email;
		$userObj->password = $data->password;
		$userObj->created = date('Y-m-d H:i:s');

		if( $userObj->create() ){

			// created response
			http_response_code(200);
			echo json_encode( array( "message" => "User was created" ) );


		}else{

			//service unavailable
			http_response_code(503);
			echo json_encode( array( "message" => "Unable to create user. Service unavailable" ) );

		}


	}else{

		// bad request
		http_response_code( 400);
		echo json_encode( array( "message" => "Unable to create. Incomplete data" ) );

	}

?>