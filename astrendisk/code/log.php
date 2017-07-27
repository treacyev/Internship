<?php
	include('connect-db.php');
	if (!is_dir($outputpath)){
		mkdir($outputpath);
	}
	$fo = fopen($outputpath . $outputfile, 'a') or die('Cannot open file: '.$outputpath . $outputfile);
?>	