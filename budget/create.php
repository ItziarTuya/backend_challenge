<?php
	
	// required headers
	header( "Access-Control-Allow-Origin: *" );
	header( "Content-Type: application/json; charset=UTF-8" );
	header( "Access-Control-Allow-Methods: POST" );
	header( "Access-Control-Max-Age: 3600" );
	header( "Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With" );
	  
	// include database and budget file
	include_once '../config/database.php';
	include_once '../objects/budget.php';
	  
	// instantiate database and budget object
	$database 	= new Database();
	$db 		= $database->getConnection();
	  
	// initialize object
	$budget = new Budget( $db );
	  
	// get posted data
	$data = json_decode(file_get_contents("php://input"));

	// make sure data is not empty
	if( !empty( $data ) ){
	  
	    // set budget property values
	    $budget->title 			= !empty( $data->title ) ? $data->title : '';
	    $budget->description 	= $data->description;
	    $budget->category 		= !empty( $data->category ) ? $data->category : '';
	    $budget->email 			= $data->email;
	    $budget->phone 			= $data->phone;
	    $budget->address 		= $data->address;
	  
	    // create the budget
	    if( $budget->create() ){
	  
	        // set response code - 201 created
	        http_response_code( 201 );
	  
	        // tell the user
	        echo json_encode( array( "message" => "budget was created." ) );
	    }
	  
	    // if unable to create the budget, tell the user
	    else{
	  
	        // set response code - 503 service unavailable
	        http_response_code( 503 );
	  
	        // tell the user
	        echo json_encode( array( "message" => "Unable to create budget." ) );

	    }
	}
	  
	// tell the user data is incomplete
	else{
	  
	    // set response code - 400 bad request
	    http_response_code( 400 );
	  
	    // tell the user
	    echo json_encode( array( "message" => "Unable to create budget. Data is incomplete." ) );
	}
	?>