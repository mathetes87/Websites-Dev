<?php
	//Get the table.
	$tabla = $_POST['tabla'];
	// Write the thing
	@session_start();
	$thisYear = date("Y");
	if($_POST['who'] == 'horarioThis'){
		$f = fopen('tablaHorario.html', 'w');	
	}
	else if($_POST['who'] == 'horarioNext'){
		$f = fopen('tablaHorarioProx.html', 'w');
	}
	else{
		echo 'Ocurrió un error';
		return;
	}
	fwrite($f, $tabla);
	// Close file
	fclose($f);
	echo "ok";
?>