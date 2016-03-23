<?php
	include_once 'conectarDB.php';
	@session_start();
	$year = $_SESSION['year'];
	$fecha = $_POST['fecha'];
	
	// Borrar en lista de alumnos
	$query1 = 'DELETE FROM '.$tbl_asistencia.' WHERE Fecha = "'. $fecha .'" ';
	mysql_query($query1) or die(mysql_error());
	
	echo 'borrado';
?>