<div class="ui menu borderless" style="height: 110px; overflow: auto">
	<div class="item">
		<a href="search"><img class="ui tiny circular image" src="../resources/images/trend-micro-mobile.png"/></a><br/>
		<!-- <h1 style="margin-left: 10px; font-size: 40px">ASTRENDISK</h1> -->
		<h2 style="margin-left: 10px; margin-top: 40px; font-size: 40px">astrendisk</h2>
	</div>
	<div class="right menu">
		<div class="item" style="margin-top: 60px">
			<p style="font-size: 15px"> Hello, <?php echo "'<strong style=\"color: #922B21\">". htmlspecialchars($login_session) ."</strong>'!"; ?> </p>   
			<form action="logout">
				<button style="color: white; background-color: #A93226; margin-left: 30px; margin-right: 10px" class="ui small button"> 
					&nbsp; Sign Out &nbsp; 
					<i class="sign out icon"></i>
				</button>
			</form>
		</div>
	</div>
</div>