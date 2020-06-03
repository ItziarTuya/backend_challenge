<?php

// required headers
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include files
include_once '../config/core.php';

// get service action
$uri = $_SERVER['REQUEST_URI'];
$parts = explode("/", $uri);
$action = end($parts);

// set ID property of budget to be edited
isset($data) ? $budget->id = $data->budget_id : '';

/**
 * Modify a pending budget request
 */
if ($action === "update") {

    // set budget property optional values
    $budget->title = !empty($data->title) ? $data->title : '';
    $budget->description = !empty($data->description) ? $data->description : '';
    $budget->category = !empty($data->category) ? $data->category : '';

    // update the budget and inform the user
    if ($budget->update()) {

        $utilities->completeStatusCode($_200, array("message" => "The budget request has been updated."));
    } else {

        $utilities->completeStatusCode($_503, array("message" => "Unable to update budget."));
    }

    /**
     * Post a pending budget request
     */
} elseif ($action === "post") {

    if ($budget->post()) {

        $utilities->completeStatusCode($_200, array("message" => "The budget request has been published."));
    } else {

        $utilities->completeStatusCode($_503, array("message" => "Unable to post budget."));
    }

    /**
     * Discard a budget request
     */
} elseif ($action === "discard") {

    if ($budget->discard()) {

        $utilities->completeStatusCode($_200, array("message" => "The budget request has been discarded."));
    } else {

        $utilities->completeStatusCode($_503, array("message" => "Unable to discard budget."));
    }

    /**
     * Request budget action information
     */
} elseif ($action == "update.php" || $action == "") {

    $update['message'] = "Please choose one of the following options and enter at least budget_id: ";
    $update['update'] = "{$home_url}budget/update.php/update";
    $update['post'] = "{$home_url}budget/update.php/post";
    $update['discard'] = "{$home_url}budget/update.php/discard";

    echo json_encode($update);
}