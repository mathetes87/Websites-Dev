<?php
	include_once 'conectarDB.php';
	include_once 'mysql_fetch_all.php';
	$comentario = $_POST['comentario']; 
	$rut = $_POST['rut'];
	$mes = $_POST['mes']; 
	@session_start();
	$year = $_SESSION['year'];
	// echo $comentario .", ". $rut .", ". $mes;
	
	$query = "UPDATE ".$tbl_pagos." SET `Tooltip` = '".$comentario."'	WHERE Rut = '".$rut."' AND idMes = ".$mes." AND Year = ". $year ." ";
	// echo $query;
	mysql_query($query) or die(mysql_error());
	
	echo "ok";
?>