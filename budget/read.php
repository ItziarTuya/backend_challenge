<?php

	include_once '../config/core.php';

	if ( !empty( $data->email ) )  $budget->email = $data->email;

	// query read budget records
	$read = $budget->readPaging( $from_record_num, $records_per_page );

	if( $read && $read->rowCount() > 0 ){
	  
	    $budgets 			= array();
	    $budgets["records"] = array();
	  
	    while( $row = $read->fetch( PDO::FETCH_ASSOC ) ){

	        extract( $row );
		  
	        $budget_item = array(
	            "id" 			=> $id,
	            "email" 		=> $user_email,
	            "description" 	=> html_entity_decode( $description ),
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
  
	    $utilities->completeStatusCode( $_200, $budgets );

	} else{
  		
	    $utilities->completeStatusCode( $_404, array( "message" => "No budget request has been found.") );
	}

?>