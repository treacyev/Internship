<?php
	//session_start();
	include("log.php");
	$username = mysqli_real_escape_string($db, $_POST['username']);
	$password = mysqli_real_escape_string($db, $_POST['password']);
	$passwordcon = mysqli_real_escape_string($db, $_POST['passwordcon']);
	if (preg_match('/^[a-zA-Z]+$/', $username)){
		if ($password == $passwordcon){
			if (strlen($password) >= 6){
				$password = md5($password);
				$userchecker = mysqli_query($db,"SELECT * FROM $userstable WHERE username = '$username'");
				if (mysqli_fetch_row($userchecker)){
					header ("location: register?remarks=userexist");
				}
				else {
					$activation = 0; //0 is false and 1 is true
					mysqli_query($db, "INSERT INTO $userstable(username, password, activation)VALUES('$username', '$password', '$activation')");
					$sql = "SELECT id FROM $userstable WHERE username = '$username' and password = '$password'";
					$result = mysqli_query($db,$sql);
					$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
					
					$count = mysqli_num_rows($result);
					  
					if($count == 1) {
						//session_register("username");
						//$_SESSION['login_user'] = $username;
						$time = date('H:i:s');
						fwrite($fo, $time);
						fwrite($fo, "\t\t");
						fwrite($fo, $username);
						fwrite($fo, " registered\n\n");
						header("location: login");
						//header("location: search.php");
					}
				}
			}
			else {
				header("location: register?remarks=passwdshort");
			}
		}
		else {
			header("location: register?remarks=failed");
		}
	}
	else {
		header("location: register?remarks=invalid");
	}
	
	mysqli_close($db);
	
?>

