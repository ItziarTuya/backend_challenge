<?php

// required headers
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and budget file
include_once '../config/core.php';

// make sure data is not empty
if (!empty($data)) {

    // set budget property values
    $budget->title = !empty($data->title) ? $data->title : '';
    $budget->description = $data->description;
    $budget->category = !empty($data->category) ? $data->category : '';
    $budget->email = $data->email;
    $budget->phone = $data->phone;
    $budget->address = $data->address;

    if ($budget->create()) {

        $utilities->completeStatusCode($_201, array("message" => "Budget was created."));
    } else {

        $utilities->completeStatusCode($_503, array("message" => "Unable to create budget."));
    }
} else {

    $utilities->completeStatusCode($_400, array("message" => "Unable to create budget. Data is incomplete."));
}