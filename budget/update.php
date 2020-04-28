<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	  
	// include database and budget files
	include_once '../objects/budget.php';
	include_once '../config/database.php';
	include_once '../config/core.php';
	
	// get service action
	$uri 	= $_SERVER['REQUEST_URI'];
	$parts  = explode( "/", $uri );
	$action = end( $parts );

	// get database connection
	$database 	= new Database();
	$db 		= $database->getConnection();
	  
	// prepare object budget
	$budget = new Budget($db);

	// get budget id to be edited
	$data = json_decode( file_get_contents( "php://input" ) );

	// set ID property of budget to be edited
	isset( $data ) ? $budget->id = $data->budget_id : '';
	
	/**
	 * Modify a pending budget request
	 */
	if ( $action === "update"){

		// set budget property optional values
		$budget->title 			= !empty( $data->title ) ? $data->title : '';
		$budget->description 	= !empty( $data->description ) ? $data->description : '';
		$budget->category	 	= !empty( $data->category ) ? $data->category : '';
		  
		// update the budget
		if( $budget->update() ){
		  
		    // set response code - 200 ok
		    http_response_code(200);
		  
		    // tell the user
		    echo json_encode(array("message" => "The budget request has been updated."));
		}
		  
		// if unable to update the budget, tell the user
		else{
		  
		    // set response code - 503 service unavailable
		    http_response_code(503);
		  
		    // tell the user
		    echo json_encode(array("message" => "Unable to update budget."));
		}
	
	/**
	 * Post a pending budget request
	 */
	} elseif ( $action === "post"){
		
		if( $budget->post() ){
		  
		    // set response code - 200 ok
		    http_response_code(200);
		  
		    // tell the user
		    echo json_encode(array("message" => "The budget request has been published."));
		}
		  
		// if unable to post the budget, tell the user
		else{
		  
		    // set response code - 503 service unavailable
		    http_response_code(503);
		  
		    // tell the user
		    echo json_encode(array("message" => "Unable to post budget."));
		}

	/**
	 * Discard a budget request
	 */
	} elseif ( $action === "discard"){
		
		if( $budget->discard() ){
		  
		    // set response code - 200 ok
		    http_response_code(200);
		    echo json_encode(array("message" => "The budget request has been discarded."));
		}
		  
		// if unable to discard the budget, tell the user
		else{
		  
		    // set response code - 503 service unavailable
		    http_response_code(503);
		    echo json_encode(array("message" => "Unable to discard budget."));
		}

	/**
	 * Request budget action
	 */
	} elseif ( $action == "update.php" || $action == "" ){
		
		$update['message'] 	= "Please choose one of the following options and enter at least budget_id: ";
		$update['update'] 	= "{$home_url}budget/update.php/update";
		$update['post'] 	= "{$home_url}budget/update.php/post";
		$update['discard'] 	= "{$home_url}budget/update.php/discard";

		echo json_encode( $update );
	}


?>