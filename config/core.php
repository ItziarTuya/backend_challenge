<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'database.php';
include_once '../objects/budget.php';
include_once '../shared/utilities.php';

// instantiate database and objects
$database = new Database();
$db = $database->getConnection();
$budget = new Budget($db);
$utilities = new Utilities();

// get service resource data
$data = json_decode(file_get_contents("php://input"));


// show error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// home page url
$home_url = "http://localhost/habitissimo_api/";

// page given in URL parameter, default page is one
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// set number of records per page
$records_per_page = 5;

// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;

/* status codes list: */
$_200 = 200; // OK - The request was successfully completed.
$_201 = 201; // Created - A new resource was successfully created.
$_400 = 400; // Bad Request - The request was invalid.
$_401 = 401; // Unauthorized - The request did not include an authentication token or it was expired.
$_403 = 403; // Forbidden - The client did not have permission to access the requested resource.
$_404 = 404; // Not Found - The requested resource was not found.
$_405 = 405; // Method Not Allowed - The HTTP method in the request was not supported by the resource. 
$_409 = 409; // Conflict - The request could not be completed due to a conflict. 
$_500 = 500; // Internal Server Error - The request was not completed due to an internal server error.
$_503 = 503; // Service Unavailable - The server was unavailable.