<?php
	include_once 'conectarDB.php';
	@session_start();
	$year = $_SESSION['year'];
	$rut = $_POST['rut'];
	
	// Borrar en lista de alumnos
	$query1 = 'DELETE FROM '.$tbl_usuarios.' WHERE RUT = "'. $rut .'" ';
	mysql_query($query1) or die(mysql_error());
	
	// Borrar también todos sus puntajes
	$query2 = 'DELETE FROM '.$tbl_puntajes.' WHERE RUT = "'. $rut .'" ';
	mysql_query($query2) or die(mysql_error());
	
	// Y su asistencia
	$query3 = 'DELETE FROM '.$tbl_asistencia.' WHERE Rut = "'. $rut .'" ';
	mysql_query($query3) or die(mysql_error());
	
	// Y sus controles
	$query4 = 'DELETE FROM '.$tbl_controles.' WHERE Rut = "'. $rut .'" ';
	mysql_query($query4) or die(mysql_error());
	
	// Y los pagos
	$query5 = 'DELETE FROM '.$tbl_pagos.' WHERE Rut = "'. $rut .'" ';
	mysql_query($query5) or die(mysql_error());
	
	echo 'borrado';
?>