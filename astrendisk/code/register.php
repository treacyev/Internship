<html>
	<head>
		<title>asTrendisk</title>
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.6/semantic.css"/>
		<link rel="stylesheet" type="text/css" href="../resources/css/landing.css"/>
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<link rel="shortcut icon" href="http://www.trendmicro.com.ph/favicon.ico" type="image/x-icon"/>
		<script type="text/javascript" src="../resources/javascript/main.js"></script>
	</head>
	
	<body>
		<div class="ui large raised segment">	
			<form class="ui large form" name="reg" action="exec" onsubmit="return validateForm()" method="post">
				<div class="ui center aligned grid">
					<div class="column"></div>
					<div class="five wide column">
						<div class="ui basic segment">
							<a href="login"><h1 class="ui image header" style="margin-bottom: 10px">
								<img class="ui tiny circular image" src="../resources/images/trend-micro-mobile.png"/><br/>
								astrendisk
							</h1></a>
							<div class="field">
								<div class="ui left icon input">
									<i class="users icon"></i>
									<input type="text" name="username" placeholder="Username" />
								</div>
							</div>
							<div class="field">
								<div class="ui left icon input">
									<i class="lock icon"></i>
									<input type="password" name="password" placeholder="Password" />
								</div>
							</div>
							<div class="field">
								<div class="ui left icon input">
									<i class="lock icon"></i>
									<input type="password" name="passwordcon" placeholder="Confirm Password" />
								</div>
							</div>
							<input name="signup" type="submit" value="Register" class="ui fluid large button" id="register2" />
						</div>
						<?php
							$error;
							if (!isset($_GET['remarks'])){	//successful registration
								$remarks = "";
							}
							else {
								$remarks = $_GET['remarks'];
								if ($remarks == "failed"){
									echo '<div class="ui red small message"><div class="header">Passwords are different</div></div>';
								}
								else if ($remarks == "userexist"){
									echo '<div class="ui red small message"><div class="header">User already exists</div></div>';
								}
								else if ($remarks == "passwdshort"){
									echo '<div class="ui red small message"><div class="header">Password must be at least 6 characters long</div></div>';
								}
								else if ($remarks == "invalid"){
									echo '<div class="ui red small message"><div class="header">Invalid username</div></div>';
								}
							}
						?>
					</div>
					<div class="column"></div>			
				</div>
			</form>
		</div>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.6/semantic.js"></script>
	</body>
</html>