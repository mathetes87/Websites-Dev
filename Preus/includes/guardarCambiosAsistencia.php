<?php
	include_once 'conectarDB.php';
	include_once 'mysql_fetch_all.php';
	$filas = $_POST['filas']; // Fecha, Rut, Boolean'
	
	// Recorrer todas las filas y guardar o sobreescribir
	for($i = 0; $i < sizeof($filas); $i++) {
		// SIEMPRE VA A EXISTIR LA FILA --> ACTUALIZARLA
		// YA EXISTELA FILA -> ACTUALIZAR
		$query = "UPDATE ".$tbl_asistencia." SET `Asistencia` = '".$filas[$i][2]."'	WHERE Fecha = '".$filas[$i][0]."' AND Rut = '".$filas[$i][1]."' ";
		mysql_query($query) or die(mysql_error());
	}
	echo "ok";
?>