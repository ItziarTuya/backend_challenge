<?php
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

	// status codes list:
	$_200 = 200;		// OK - The request was successfully completed.
	$_201 = 201;		// Created - A new resource was successfully created.
	$_400 = 400;		// Bad Request - The request was invalid.
	$_401 = 401;		// Unauthorized - The request did not include an authentication token or the authentication token was expired.
	$_403 = 403;		// Forbidden - The client did not have permission to access the requested resource.
	$_404 = 404;		// Not Found - The requested resource was not found.
	$_405 = 405;		// Method Not Allowed - The HTTP method in the request was not supported by the resource. For example, the DELETE method cannot be used with the Agent API.
	$_409 = 409;		// Conflict - The request could not be completed due to a conflict. For example,  POST ContentStore Folder API cannot complete if the given file or folder name already exists in the parent location.
	$_500 = 500;		// Internal Server Error - The request was not completed due to an internal error on the server side.
	$_503 = 503;		// Service Unavailable - The server was unavailable.

?>