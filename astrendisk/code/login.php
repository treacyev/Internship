<?php
	$error = "";
	include("log.php");
	session_start();
	
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$myusername = mysqli_real_escape_string($db,$_POST['username']);
		$mypassword = md5(mysqli_real_escape_string($db,$_POST['password'])); 
		if (preg_match('/^[a-zA-Z]+$/', $myusername)){  
			$sql = "SELECT id FROM $userstable WHERE username = '$myusername' and password = '$mypassword'";
			$result = mysqli_query($db,$sql);
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		  
			$count = mysqli_num_rows($result);
		  
			if($count == 1) {
				$sql = "SELECT activation FROM $userstable WHERE username = '$myusername' and password = '$mypassword'";
				$activation = mysqli_query($db,$sql);
				$row = mysqli_fetch_array($activation);
				$activation = $row['activation'];
				if ($activation == 1){	//activated user 1
					session_register("myusername");
					$_SESSION['login_user'] = $myusername;
					$time = date('H:i:s');
					fwrite($fo, $time);
					fwrite($fo, "\t\t");
					fwrite($fo, $myusername);
					fwrite($fo, " logged in\n\n");
					header("location: search");
				}
				else if ($activation == 0){ //deactivated user 0
					$error = "User not yet activated ";
				}
			}
			else {	//query result is empty
				$error = "Your Login Name or Password is invalid";
			}
		}
		else {	//username is not only letters
			$error = "Your Login Name or Password is invalid";
		}
   }
   
   mysqli_close($db);
?>
<html>
   
   <head>
		<title>asTrendisk</title>
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.6/semantic.css"/>
		<link rel="stylesheet" type="text/css" href="../resources/css/landing.css"/>
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<link rel="shortcut icon" href="http://www.trendmicro.com.ph/favicon.ico" type="image/x-icon"/>
   </head>
   
   <body>
		<div class="ui large raised segment">
			<div class="ui center aligned grid">	
				<div class="column"></div>
				<div class="five wide column">
					<div class="ui basic segment">
						<a href="login"><h1 class="ui image header" style="margin-bottom: 10px">
							<img class="ui tiny circular image" src="../resources/images/trend-micro-mobile.png"/><br/>
							astrendisk
						</h1></a>
						<form action="" method="post" class="ui large form">
							<div class="field">
								<div class="ui left icon input">
									<i class="users icon"></i>
									<input placeholder="Username" type="text" name="username" class="box"/>
								</div>
							</div>
							<div class="field">
								<div class="ui left icon input">
									<i class="lock icon"></i>
									<input placeholder="Password" type="password" name="password" class="box"/>
								</div>
							</div>
							<input type="submit" id="login" value="Login" class="ui fluid large button"/>
							<div class="ui horizontal divider" >Or</div>
							<input name="register" type="submit" value="Register" class="ui fluid large button" id="register"/>
							<?php
								if (isset($_POST['register'])){
									header("Location: register");
								}
							?>
						</form>
					</div>
					<?php 
						if ($error == ""){
							echo '<div class="ui hidden message">'. $error .'</div>'; 
						}
						else {
							echo '<div class="ui red small message"><div class="header">'. $error .'</div></div>';
						}
					?>
				</div>
				<div class="column"></div>
			</div>
		</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.6/semantic.js"></script>
   </body>
   
</html>