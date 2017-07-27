<?php

	date_default_timezone_set('Asia/Manila');
	
	$server = 'localhost';
	$user = 'root';
	$pass = '';
	$database = 'example2';
	$table = 'monthlyreport';
	$userstable = 'users';
	$time = date('Y-m-d');
	$outputpath = "C:\\wamp\\www\\astrendisk\\Log\\";
	$outputfile = $time . ".txt";
	
	$db = mysqli_connect($server, $user, $pass, $database) or die('Error connecting to MySQL server.'); 

?>