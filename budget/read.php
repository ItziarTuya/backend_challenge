<?php

	// required headers
	header( "Access-Control-Allow-Origin: *" );
	header( "Content-Type: application/json; charset=UTF-8" );

	// include database and budget file
	include_once '../config/database.php';
	include_once '../objects/budget.php';
	  
	// instantiate database and budget object
	$database 	= new Database();
	$db 		= $database->getConnection();
	  
	// initialize object
	$budget = new Budget( $db );

	// query budgets
	$read 	= $budget->read();
	$n_row 	= $read->rowCount();
	  
	// check if more than 0 record found
	if( $n_row > 0 ){
	  
	    // budgets array
	    $budgets 			= array();
	    $budgets["records"] = array();
	  
	    // retrieve budgets table content
	    while( $row = $read->fetch( PDO::FETCH_ASSOC ) ){
	        // extract row
	        // this will make $row['name'] to
	        // just $name only
	        extract( $row );
	  
	        $budget_item = array(
						            "id" 			=> $id,
						            "title" 		=> $title,
						            "description" 	=> html_entity_decode($description),
						            "category_id" 	=> $category_id,
						            "category_name" => $category_name,
						            "user_id" 		=> $user_id,
						            "user_name"		=> $user_name,
						            "status_id" 	=> $status_id,
						            "status_name" 	=> $status_name
						        );
	  
	        array_push( $budgets["records"], $budget_item );

	    }
	  
	    // set response code - 200 OK
	    http_response_code( 200 );
	  
	    // show budgets data in json format
	    echo json_encode( $budgets );

	} else{
  
	    // set response code - 404 Not found
	    http_response_code( 404 );
	  
	    // tell the user no budgets found
	    echo json_encode( array("message" => "No products found.") );

	}