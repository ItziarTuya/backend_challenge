<?php

	// required headers
	header( "Access-Control-Allow-Origin: *" );
	header( "Content-Type: application/json; charset=UTF-8" );

	// include database and budget file
	include_once '../config/core.php';
	include_once '../config/database.php';
	include_once '../objects/budget.php';
	include_once '../shared/utilities.php';
	
	// utilities
	$utilities = new Utilities();

	// instantiate database and budget object
	$database 	= new Database();
	$db 		= $database->getConnection();
	  
	// initialize object
	$budget = new Budget( $db );

	// get posted data
	$data = json_decode( file_get_contents( "php://input" ) );
	
	if ( !empty( $data->email ) ) {
		
		$budget->email = $data->email;
	}

	// query read budget records
	$read = $budget->readPaging( $from_record_num, $records_per_page );

	// check if more than 0 record found
	if( $read && $read->rowCount() > 0 ){
	  
	    // budgets array
	    $budgets 			= array();
	    $budgets["records"] = array();
	  
	    // retrieve budgets table content
	    while( $row = $read->fetch( PDO::FETCH_ASSOC ) ){

	        extract( $row );
		  
	        $budget_item = array(
	            "id" 			=> $id,
	            "email" 		=> $user_email,
	            "description" 	=> html_entity_decode($description),
	            "category_name" => $category_name,
	            "status_name" 	=> $status_name,
	            "created"		=> $created
	        );
	  
	        array_push( $budgets["records"], $budget_item );

	    }


        // include paging
	    $total_rows 		= $budget->count();
	    $page_url 			= "{$home_url}budget/read.php?";
	    $paging 			= $utilities->getPaging( $page, $total_rows, $records_per_page, $page_url );
	    $budgets["paging"] 	= $paging;
  
	    // set response code - 200 OK
	    http_response_code( 200 );
	  
	    // show budgets data in json format
	    echo json_encode( $budgets );

	} else{
  
	    // set response code - 404 Not found
	    http_response_code( 404 );
	  
	    // tell the user no budget found
	    echo json_encode( array("message" => "No budget request has been found.") );

	}