<?php
	include_once 'conectarDB.php';
	@session_start();
	$year = $_SESSION['year'];
	$ensayo = $_POST['ensayo'];
	
	$query = 'DELETE FROM '.$tbl_puntajes.' WHERE Ensayo = "'. $ensayo .'" AND Year = '. $year .'';
	mysql_query($query) or die(mysql_error());
	
	echo 'borrado';
?>