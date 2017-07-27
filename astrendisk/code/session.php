<?php
	include('connect-db.php');
	session_start();
   
	$user_check = $_SESSION['login_user'];
   
	$ses_sql = mysqli_query($db,"select username, admin from $userstable where username = '$user_check' ");
   
	$row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
	$login_session = $row['username'];
	$admin_check = $row['admin'];
	
	$time = date('Y-m-d H:i:s');
	if ($admin_check != 1){	
		mysqli_query($db, "UPDATE $userstable SET `login`='". $time ."' WHERE username='". $login_session ."'");
	}
	
	if(!isset($_SESSION['login_user'])){
		header("location:login");
	}
?>