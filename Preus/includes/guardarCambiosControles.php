<?php
	include_once 'conectarDB.php';
	include_once 'mysql_fetch_all.php';
	$celdas = $_POST['celdas']; // Rut, Control, Nota
	$year = date("Y");
	//print_r($celdas);
	
	// Recorrer todas las filas y guardar o sobreescribir
	for($i = 0; $i < sizeof($celdas); $i++) {
		// SI EXISTE EL CONTROL, REEMPLAZA EL VALOR
		$query = "INSERT INTO ".$tbl_controles." (`Nota`, `Rut`, `Control`, `Year`) VALUES (".$celdas[$i][2].", '".$celdas[$i][0]."', ".$celdas[$i][1].", ". $year .") ON DUPLICATE KEY UPDATE `Nota` = ".$celdas[$i][2]." ";	
		if($celdas[$i][2] == '') {
			$query = "INSERT INTO ".$tbl_controles." (`Nota`, `Rut`, `Control`, `Year`) VALUES (NULL, '".$celdas[$i][0]."', ".$celdas[$i][1].", ". $year .") ON DUPLICATE KEY UPDATE `Nota` = NULL ";	
		}
		//echo $query;
		mysql_query($query) or die(mysql_error());
	}
	echo "ok";
?>