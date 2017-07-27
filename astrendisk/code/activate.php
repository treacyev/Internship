<?php	
	include('log.php');
	if (isset($_GET['id'])){
		$id = $_GET['id'];
		$time = date('H:i:s');
		if (isset($_POST['active'])){
			mysqli_query($db, "UPDATE $userstable SET `activation`=1 WHERE id='$id'");
			$user = mysqli_query($db, "SELECT username FROM $userstable WHERE id='$id'");
			$user = mysqli_fetch_array($user);
			fwrite($fo, $time . "\t\tadmin activated " . $user['username'] . "\n\n");
			header('location: search');
		}
		if (isset($_POST['deactive'])){	
			mysqli_query($db, "UPDATE $userstable SET `activation`=0 WHERE id='$id'");
			$user = mysqli_query($db, "SELECT username FROM $userstable WHERE id='$id'");
			$user = mysqli_fetch_array($user);
			fwrite($fo, $time . "\t\tadmin deactivated " . $user['username'] . "\n\n");
			header('location: search');
		}
	}
?>