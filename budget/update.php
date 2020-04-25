<?php
	// required headers
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	  
	// include database and budget files
	include_once '../config/database.php';
	include_once '../objects/budget.php';
	  
	// get database connection
	$database 	= new Database();
	$db 		= $database->getConnection();
	  
	// prepare object budget
	$budget = new Budget($db);
	  
	// get id of budget to be edited
	$data = json_decode(file_get_contents("php://input"));
	  
	// set ID property of budget to be edited
	$budget->id = $data->budget_id;
	  
	// set budget property optional values
	$budget->title 			= !empty( $data->title ) ? $data->title : '';
	$budget->description 	= !empty( $data->description ) ? $data->description : '';
	$budget->category	 	= !empty( $data->category ) ? $data->category : '';
	  
	// update the budget
	if( $budget->update() ){
	  
	    // set response code - 200 ok
	    http_response_code(200);
	  
	    // tell the user
	    echo json_encode(array("message" => "Budget was updated."));
	}
	  
	// if unable to update the budget, tell the user
	else{
	  
	    // set response code - 503 service unavailable
	    http_response_code(503);
	  
	    // tell the user
	    echo json_encode(array("message" => "Unable to update budget."));
	}
?>