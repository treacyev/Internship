<?php	
	include('log.php');
	session_start();
	
	$time = date('H:i:s');
	fwrite($fo, $time);
	fwrite($fo, "\t\t");
	fwrite($fo, $_SESSION['login_user']);
	fwrite($fo, " logged out\n\n");
	fclose($fo);
	
	if(session_destroy()) {
		header("Location: login");
	}
?>