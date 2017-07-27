<?php
	include('session.php');
	include('log.php');
	
	if (isset($_POST['submit']) && $_POST['submit']!=""){ 
		$time = date('H:i:s');
		fwrite($fo, $time . "\t\t". $login_session ." updated SHA1: ");
		$listcount = count($_POST["Engineer"]);
		for ($i=0; $i < $listcount; $i++){
			if (isset($_POST["new_smart"][$i])){
				if ($_POST["new_smart"][$i] == 1){
					$new_smart = "Yes";
				}
			}
			if (isset($_POST["no_smart"][$i])){
				if ($_POST["no_smart"][$i] == 1){
					$no_smart = "Yes";
				}
			}
			if(preg_match("/^[a-zA-Z\s]+$/", $_POST["Engineer"][$i])){
				$engg = mysqli_real_escape_string($db, $_POST["Engineer"][$i]);
			}
			else {
				$engg = "";
			}
			$onemany = mysqli_real_escape_string($db, $_POST["one_many_detection"][$i]);
			$reason = mysqli_real_escape_string($db, $_POST["reason_no_smart"][$i]);
			$comment = mysqli_real_escape_string($db, $_POST["comment_smartables"][$i]);
			mysqli_query($db, "UPDATE $table SET `Engineer`='". $engg ."', `one_many_detection`='". $onemany ."', `new_smart`='". $new_smart ."', `no_smart`='". $no_smart ."', `reason_no_smart`='". $reason ."', `comment_smartables`='". $comment ."' WHERE id='". $_POST["id"][$i] ."'");
			$sha1 = mysqli_query($db, "SELECT SHA1 FROM $table WHERE id='". $_POST["id"][$i]. "'");
			$sha1 = mysqli_fetch_array($sha1);
			fwrite($fo, $sha1['SHA1'] . " ");
			$new_smart = "";
			$no_smart = "";
		}
		fwrite($fo, "\n\n");
		header("Location: search");
	}
?>

<html>

	<head>
		<title>asTrendisk - Update Information</title>
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.6/semantic.css"/>
		<link rel="stylesheet" type="text/css" href="../resources/css/search.css"/>
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<link rel="shortcut icon" href="http://www.trendmicro.com.ph/favicon.ico" type="image/x-icon"  />
	</head>

	<body>
		<div class="ui large raised segment">	
			<?php 
				require('header.php');
			?>
			<div align="center" style="margin-top: 30px">
			<?php
				if ($admin_check != 1){
					if (isset($_POST['selector'])){
						echo '<h2>Update Information</h2><br/>';
						$rowCount = count($_POST['selector']);
						for ($i=0; $i<$rowCount; $i++){
							$result = mysqli_query($db, "SELECT * FROM $table WHERE id='". $_POST['selector'][$i] ."'");
							$row[$i] = mysqli_fetch_array($result);
			?>
							<form class="ui form" action="" method="post" id="editform">
								<div class="ui horizontal divider"><a class="ui big circular label" name="id[]" ><?php echo $i+1; ?></a></div>
								<div class="ui left aligned raised segment">
									<input type="hidden" name="id[]" value="<?php echo $row[$i]['id']; ?>"/>
									<div class="two fields">
										<div class="field">
											<strong id="editable">Engineer</strong> 
											<?php 
												if ($row[$i]['Engineer'] == "" or preg_match('/\s/', htmlspecialchars($row[$i]['Engineer']))){
													echo '<input type="text" id="enggfields" name="Engineer[]" value="'. htmlspecialchars($login_session) .'"/>';
												}
												else{ 
													echo '<input type="text" id="editablefields" name="Engineer[]" value="'. htmlspecialchars($row[$i]['Engineer']) .'"/>';	
												}
											?>
										</div>
										<div class="field"><strong id="editable">1:many Detection - New Smart</strong> <input type="text" id="editablefields" name="one_many_detection[]" value="<?php echo htmlspecialchars($row[$i]['one_many_detection']); ?>"/></div>
									</div>
									<div class="two fields">
										<div class="field"><strong id="editable" style="font-size: 15px">New Smart?</strong> 
											<div class="ui form">
												<select name="new_smart[]" id="editablefields" class="ui fluid dropdown" style="font-size: 15px">
													<option value=""> </option>
													<?php 
														if ($row[$i]["new_smart"] == "Yes"){
															echo '<option value="1" selected>Yes</option>';
														}
														else {
															echo '<option value="1">Yes</option>';
														}
													?>
												</select>
											</div>
										</div>
										<div class="field"><strong id="editable" style="font-size: 15px">No Smart?</strong>
											<div class="ui form"> 
												<select name="no_smart[]" id="editablefields" class="ui fluid dropdown" style="font-size: 15px">
													<option value=""> </option>
													<?php 
														if ($row[$i]["no_smart"] == "Yes"){
															echo '<option value="1" selected>Yes</option>';
														}
														else {
															echo '<option value="1">Yes</option>';
														}
													?>
												</select>
											</div>
										</div>
									</div>
									<div class="two fields">
										<div class="field"><strong id="editable">Reason for No Smart</strong> <input type="text" id="editablefields" name="reason_no_smart[]" value="<?php echo htmlspecialchars($row[$i]['reason_no_smart']); ?>"/></div>
										<div class="field"><strong id="editable">Comment for smartables</strong> <input type="text" id="editablefields" name="comment_smartables[]" value="<?php echo htmlspecialchars($row[$i]['comment_smartables']); ?>"/></div>
									</div>
									<div class="two fields">	
										<div class="field"><strong>SHA1</strong>: <u><?php echo htmlspecialchars($row[$i]['SHA1']); ?></u></div>
										<div class="field"><strong>ImpHASH</strong>: <u><?php echo htmlspecialchars($row[$i]['ImpHASH']); ?></u></div>
									</div>
									<div class="two fields">
										<div class="field"><strong>Family</strong>: <u><?php echo htmlspecialchars($row[$i]['Family']); ?></u></div>
										<div class="field"><strong>Undetection Reason</strong>: <u><?php echo htmlspecialchars($row[$i]['Undetection Reason']); ?></u></div>
									</div>	
									<div class="two fields">
										<div class="field"><strong>On test day</strong>: <u><?php echo htmlspecialchars($row[$i]['On test day']); ?></u></div>
										<div class="field"><strong>Intellitrap</strong>: <u><?php echo htmlspecialchars($row[$i]['Intellitrap']); ?></u></div>
									</div>	
									<div class="two fields">
										<div class="field"><strong>MIP</strong>: <u><?php echo htmlspecialchars($row[$i]['MIP']); ?></u></div>
										<div class="field"><strong>Packer/Compiler</strong>: <u><?php echo htmlspecialchars($row[$i]['Packer/Compiler']); ?></u></div>
									</div>
									<div class="two fields">
										<div class="field"><strong>DE4DOT</strong>: <u><?php echo htmlspecialchars($row[$i]['DE4DOT']); ?></u></div>
										<div class="field"><strong>CBAQ rule</strong>: <u><?php echo htmlspecialchars($row[$i]['CBAQ rule']); ?></u></div>
									</div>
									<div class="two fields">
										<div class="field"><strong>MS Detection</strong>: <u><?php echo htmlspecialchars($row[$i]['MS Detection']); ?></u></div>
										<div class="field"><strong>Nod32</strong>: <u><?php echo htmlspecialchars($row[$i]['Nod32']); ?></u></div>
									</div>
									<div class="two fields">
										<div class="field"><strong>Bitdefender</strong>: <u><?php echo htmlspecialchars($row[$i]['Bitdefender']); ?></u></div>
										<div class="field"><strong>Kaspersky</strong>: <u><?php echo htmlspecialchars($row[$i]['Kaspersky']); ?></u></div>
									</div>
									<div class="two fields">
										<div class="field"><strong>Sophos</strong>: <u><?php echo htmlspecialchars($row[$i]['Sophos']); ?></u></div>
										<div class="field"><strong>Symantec</strong>: <u><?php echo htmlspecialchars($row[$i]['Symantec']); ?></u></div>
									</div>
									<div class="two fields">
										<div class="field"><strong>K7AntiVirus</strong>: <u><?php echo htmlspecialchars($row[$i]['K7AntiVirus']); ?></u></div>
										<div class="field"><strong>URL/Email</strong>: <u><?php echo htmlspecialchars($row[$i]['URL/Email']); ?></u></div>
									</div>
								</div><br/><br/><br/>
					<?php
						}
					?>
								<input class="ui large button" type="submit" name="submit" value="Update" id="update"/>
							</form>
			<?php
					}
					else {
						echo '<div class="ui horizontal divider" align="center" id="errormessage">No row selected</div>';
					}
						
					mysqli_close($db);
				}
				else {
					echo '<h1 style="margin-top: 300px">Page Not Found</h1>';
				}
			?>
			</div>
		</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.6/semantic.js"></script>
	</body>

</html>