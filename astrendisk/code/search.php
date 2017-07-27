<?php
	include('session.php');
	include('log.php');
	
	//FUNCTION EXPORTING TO CSV 
	function exportCSV($query){
		ob_clean();
		include('connect-db.php');
		$time2 = date('Y-m-d H-i-s');
		$csvfile = $time2 . ".csv";
		
		$result = mysqli_query($db, $query);
		$fields_cnt = mysqli_num_fields($result);
		$users = array();
		$length = mysqli_num_rows($result);
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
			$users[] = $row;
		}
		header('Content-Description: File Transfer');
		header("Content-type: text/x-csv");
		header("Content-Disposition: attachment; filename=$csvfile");
		$output = fopen('php://output', 'w');
		fputcsv($output, array('Date', 'SHA1', 'Engineer', 'ImpHASH', 'Family', 'TrendX', 'Undetection Reason', 'On test day', 'Intellitrap', 'MIP', '1:many Detection - New Smart', 'New Smart?', 'NO Smart?', 'Reason for No Smart', 'Comment for smartables', 'Packer/Compiler', 'DE4DOT', 'CBAQ rule', 'MS Detection', 'Nod32', 'Bitdefender', 'Kaspersky', 'Sophos', 'Symantec', 'K7AntiVirus', 'URL/Email', 'ID'));
		if (count($users) > 0){
			foreach ($users as $row){
				fputcsv($output, $row);
			}
		}
		ob_flush();
		exit;
	}
	//FUNCTION EXPORTING TO CSV
?>

<html>
	<head>
		<title>asTrendisk - Search</title>
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.6/semantic.css"/>
		<link rel="stylesheet" type="text/css" href="../resources/css/search.css"/>
		<link rel="shortcut icon" href="http://www.trendmicro.com.ph/favicon.ico" type="image/x-icon"/>
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
	</head>
	
	<body>
		<div class="ui large raised segment">
			<?php			
				require('header.php');
			?>
			<div align="center">
			<?php
				if ($admin_check != 1){
				//USER PAGE
					$date = addcslashes(mysqli_real_escape_string($db, "%something_"), "%_");
					$sha1 = addcslashes(mysqli_real_escape_string($db, "%something_"), "%_");
					$filterengg = 0;
					$error = 0;
					$nodate = 1;
					$nosha1 = 1;
			?>
					<form action="search" method="post" style="margin-top: 30px" class="searchbar">
						<input type="radio" name="filter" checked="yes" value="unfiltered"/><strong id="labelcheck"> Unfilter Engineer</strong>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="filter" value="filtered"/><strong id="labelcheck"> Filter Engineer</strong>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="searchcolumn" checked="yes" value="date"/><strong id="labelcheck"> Date</strong>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="searchcolumn" value="sha1"/><strong id="labelcheck"> SHA1</strong>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<div class="ui action input">
						  <input type="text" name="search" placeholder="Search..."/>
						  <button class="ui button" type="submit" name="submit" value="Search" id="search"><i class="search icon"></i></button>
						</div>
					</form>
					<?php 
						$query = "SELECT * FROM $table WHERE `Date` LIKE '%%' ORDER BY STR_TO_DATE(`Date`, '%m/%Y') DESC LIMIT 100";
						//SEARCH
						if (isset($_POST['submit'])){
							//CHECKBOX
							if (isset($_POST['searchcolumn'])){
								//SEARCH DATE
								if ($_POST['searchcolumn'] == "date"){
									$date = mysqli_real_escape_string($db, $_POST['search']);
									if ((preg_match('/^[0-9][0-9\W]*[0-9]+$/i', $date)) or (preg_match('/^$/', $date)) or ($date == '/')){
										$nodate = 0;
										if(isset($_POST['filter'])){
											if ($_POST['filter'] == "filtered"){
												$filterengg = 1;
											}
										}
										echo '<h2 style="text-align: center">'.'Results for date \''. htmlspecialchars($date) .'\''.' '. $_POST['filter'] .'</h2>';
									}
									else {
										echo '<h2 style="text-align: center; color: red">Please input date format: (mm) or (yyyy) or (mm/yyyy)</h2>';
										$error = 1;
									}
								}
								//SEARCH DATE 
								//SEARCH SHA1
								else if ($_POST['searchcolumn'] == "sha1"){	
									$sha1 = mysqli_real_escape_string($db, $_POST['search']);
									if ((preg_match('/^[A-Za-z0-9\s]+$/', $sha1)) or (preg_match('/^$/', $sha1))){
										$nosha1 = 0;
										if(isset($_POST['filter'])){
											if ($_POST['filter'] == "filtered"){
												$filterengg = 1;
											}
										}
										echo '<h2 style="text-align: center">'.'Results for sha1 \''. htmlspecialchars($sha1) .'\''.' '. $_POST['filter'] .'</h2>';
									}
									else {
										echo '<h2 style="text-align: center; color: red">Please input SHA1 format: (alphanumeric)</h2>';
										$error = 1;
									}
								}
								//SEARCH SHA1
							}
							//CHECKBOX
					?>
							<form action="" target="_blank" method="post">
								<button class="ui circular icon button" <?php if ($error == 1){echo 'disabled';} ?> name="export" type="submit" id="export"><i class="download icon"></i><!-- &nbsp;Download --></button>
							</form>
					<?php
						}
						//SEARCH
					?>
						<div align="center" id="database">
						<?php 
							echo '<form target="_blank" action="edit" method="post">';
								echo '<table class="ui selectable single line padded table">';
									echo '<thead>';
										echo '<tr>';
											echo '<th>';
												echo '<button class="ui circular icon button" id="edit" type="submit" name="edit"><i class="edit icon"></i></button>';
											echo '</th>';
											$querycolumns = "SHOW COLUMNS FROM $table";
											$resultcolumns = mysqli_query($db, $querycolumns);
											while($row = mysqli_fetch_array($resultcolumns)){
												if ($row['Field'] == 'one_many_detection'){
													echo '<th class="center aligned">1:many Detection - New Smart</th>';
												}
												else if($row['Field'] == 'new_smart'){
													echo '<th class="center aligned">New Smart</th>';
												}
												else if($row['Field'] == 'no_smart'){
													echo '<th class="center aligned">NO Smart</th>';
												}
												else if($row['Field'] == 'reason_no_smart'){
													echo '<th class="center aligned">Reason for No Smart</th>';
												}
												else if($row['Field'] == 'comment_smartables'){
													echo '<th class="center aligned">Comment for Smartables</th>';
												}
												else if ($row['Field'] != 'id'){
													echo '<th class="center aligned">'. htmlspecialchars($row['Field']) .'</th>';
												}
											}	
										echo '</tr>';
									echo '</thead>';		
									echo '<tbody>';
										if ($error == 0){
											if ($nodate == 0){	//SEARCH DATE
												if ($filterengg == 1){	//FILTER DATE WITH EMPTY ENGINEERS ONLY
													$query = "SELECT * FROM $table WHERE `Date` LIKE '%{$date}%' AND `Engineer`='' ORDER BY `SHA1`";
													$_SESSION['query'] = $query;
												}
												else {	//SHOW DATE WITH EMPTY AND NON-EMPTY ENGINEER FIELDS
													$query = "SELECT * FROM $table WHERE `Date` LIKE '%{$date}%' ORDER BY `SHA1`";
													$_SESSION['query'] = $query;
												}
											}	//SEARCH DATE
											if ($nosha1 == 0){	//SEARCH SHA1
												if ($filterengg == 1){	//FILTER SHA1 WITH EMPTY ENGINEERS ONLY
													$query = "SELECT * FROM $table WHERE `SHA1` LIKE '%{$sha1}%' AND `Engineer`='' ORDER BY `SHA1`";
													$_SESSION['query'] = $query;
												}
												else {	//SHOW SHA1 WITH EMPTY AND NON-EMPTY ENGINEER FIELDS
													$query = "SELECT * FROM $table WHERE `SHA1` LIKE '%{$sha1}%' ORDER BY `SHA1`";	
													$_SESSION['query'] = $query;
												}
											}	//SEARCH SHA1
											$result = mysqli_query($db, $query);
											while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
												echo '<tr>'.'<td class="center aligned">';
						?>
												<input type="checkbox" name="selector[]" value="<?php echo $row['id']; ?>" />
						<?php
												echo '</td>'.
												'<td class="center aligned">'. htmlspecialchars($row['Date']) .'</td>'.
												'<td class="center aligned"><div id="sha" title="'. htmlspecialchars($row['SHA1']) .'"><span>'. htmlspecialchars($row['SHA1']) .'</span><span>'. htmlspecialchars($row['SHA1'][35]) . htmlspecialchars($row['SHA1'][36]) . htmlspecialchars($row['SHA1'][37]) . htmlspecialchars($row['SHA1'][38]) . htmlspecialchars($row['SHA1'][39]) .'</span></div></td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['Engineer']) .'">'. htmlspecialchars($row['Engineer']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['ImpHASH']) .'">'. htmlspecialchars($row['ImpHASH']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['Family']) .'">'. htmlspecialchars($row['Family']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['TrendX']) .'">'. htmlspecialchars($row['TrendX']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['Undetection Reason']) .'">'. htmlspecialchars($row['Undetection Reason']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['On test day']) .'">'. htmlspecialchars($row['On test day']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['Intellitrap']) .'">'. htmlspecialchars($row['Intellitrap']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['MIP']) .'">'. htmlspecialchars($row['MIP']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['one_many_detection']) .'">'. htmlspecialchars($row['one_many_detection']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['new_smart']) .'">'. htmlspecialchars($row['new_smart']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['no_smart']) .'">'. htmlspecialchars($row['no_smart']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['reason_no_smart']) .'">'. htmlspecialchars($row['reason_no_smart']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['comment_smartables']) .'">'. htmlspecialchars($row['comment_smartables']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['Packer/Compiler']) .'">'. htmlspecialchars($row['Packer/Compiler']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['DE4DOT']) .'">'. htmlspecialchars($row['DE4DOT']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['CBAQ rule']) .'">'. htmlspecialchars($row['CBAQ rule']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['MS Detection']) .'">'. htmlspecialchars($row['MS Detection']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['Nod32']) .'">'. htmlspecialchars($row['Nod32']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['Bitdefender']) .'">'. htmlspecialchars($row['Bitdefender']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['Kaspersky']) .'">'. htmlspecialchars($row['Kaspersky']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['Sophos']) .'">'. htmlspecialchars($row['Sophos']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['Symantec']) .'">'. htmlspecialchars($row['Symantec']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['K7AntiVirus']) .'">'. htmlspecialchars($row['K7AntiVirus']) .'</td>'.
												'<td class="center aligned" title="'. htmlspecialchars($row['URL/Email']) .'">'. htmlspecialchars($row['URL/Email']) .'</td>'.
												'</tr>';
											}
										}
									echo '</tbody>';
								echo '</table>';
							echo '</form>';	
							//EXPORT QUERY TO CSV
							if (isset($_POST['export'])){
								$current_time = date('H:i:s');
								$querysave = $_SESSION['query'];
								fwrite($fo, $current_time ."\t\t". $login_session ." exported CSV file"."\n\n");
								exportCSV($querysave);
							}
							//EXPORT QUERY TO CSV
							mysqli_close($db);
						?>
						</div>
			<?php
				//USER PAGE
				}
				else if ($admin_check == 1) {
				//ADMIN PAGE
			?>
					<form action="search" method="post" style="margin-top: 30px">
						<div class="ui action input">
							<input type="text" name ="search" placeholder="Search..."/>
							<button class="ui button" type="submit" name="submit" value="Search" id="search"><i class="search icon"></i></button>
						</div>
					</form>
					<?php
						$search = 0;
						$error = 0;
						if (isset($_POST['submit'])){
							$username = mysqli_real_escape_string($db, $_POST['search']);
							if ((preg_match('/^[A-Za-z]+$/', $username))){
								$search = 1;
								echo '<h2>Search results for \''. htmlspecialchars($username) .'\'</h2>';
							}
							else {
								$error = 1;
							}
						}
					?>
					<div align="center" id="database2">
						<?php
							echo '<table class="ui selectable table" id="accounts">';
								echo '<thead>'.
									'<tr>'.
										'<th class="center aligned"><h4>User</h4></th>'.
										'<th class="center aligned"><h4>Status</h4></th>'.
										'<th class="center aligned"><h4>Actions</h4></th>'.
										'<th class="center aligned"><h4>Last Login</h4></th>'.
									'</tr>'.
								'</thead>';
								if ($error == 0){
									if ($search == 0){
										$query = "SELECT * FROM $userstable WHERE `admin` is NULL ORDER BY `activation`, `username`";
									}
									else {
										$query = "SELECT * FROM $userstable WHERE `username`='$username'";
									}
									echo '<tbody>';
										$result = mysqli_query($db, $query);
										while ($row = mysqli_fetch_array($result)){
											echo '<tr>'.
												'<td class="center aligned"><h4 style="font-size: 18px">'. htmlspecialchars($row['username']) .'</h4></td>';
												if ($row['activation'] == 1){
													echo '<td class="center aligned" style="font-size: 15px">Active &nbsp; <i class="icon checkmark" style="color: green"></i></td>';
												}
												else {
													echo '<td class="center aligned" style="font-size: 15px">Deactivated &nbsp; </td>';
												}
												echo '<td class="center aligned">'.
													'<form action="activate?id='. htmlspecialchars($row['id']) .'" method="post">';
														if ($row['activation'] == 0){
															echo '<input class="ui small button" type="submit" name="active" id="active" value="Activate"/>'.
															'&nbsp;&nbsp;&nbsp;';
														}
														else {
															echo '<input class="ui small button" type="submit" name="deactive" id="deactive" value="Deactivate"/>';
														}
													echo '</form>';
												'</td>';
											echo '<td class="center aligned" style="font-size: 15px">'.$row['login'].'</td>';
											echo '</tr>';	
										}
									echo '</tbody>';
								}
							echo '</table>';
							mysqli_close($db);
						?>
					</div>
			<?php
				//ADMIN PAGE
				}
			?>
			</div>
		</div>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.6/semantic.js"></script>
		<script type="text/javascript" src="../resources/javascript/main.js"></script>
	</body>
</html>