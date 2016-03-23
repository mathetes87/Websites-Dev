<?php
	include_once 'conectarDB.php';
	include_once 'mysql_fetch_all.php';
	$data = json_decode($_POST['celdas']);
	$ensayo = $_POST['ensayo'];
	$demre = $_POST['demre'];
	$filas = sizeof($data);
	$col = sizeof($data[0]);
	@session_start();
	$year = $_SESSION['year'];
	$idEnsayo = mysql_fetch_all(mysql_query("SELECT `ID Ensayo` FROM ".$tbl_puntajes." WHERE Ensayo = '".$ensayo."' AND Year = '".$year."' LIMIT 1")); 
	
	// Actualizar DEMRE de ensayo
	$queryDemre = "UPDATE ".$tbl_puntajes." SET `Demre` = '".$demre."' WHERE Ensayo = '".$ensayo."' AND Year = '".$year."'";
	//echo $queryDemre;
	mysql_query($queryDemre)or die(mysql_error());
	
	for($i = 0; $i < $filas; $i++) {
		// Revisar si existe y se esta cambiando o si es nuevo
		$query0 = "SELECT * FROM ".$tbl_puntajes." WHERE Ensayo = '".$ensayo."' AND RUT = '".$data[$i][0]."' AND Year = '".$year."' ";
		$res0 = mysql_query($query0)or die(mysql_error());	
		$arr = mysql_fetch_all($res0);
		$puntajeAntiguo = $arr[0][4];
		if(mysql_num_rows($res0) == 0){
			// NO EXISTE TODAVIA EL PAR RUT, ENSAYO, AÑO
			$query = "INSERT INTO ".$tbl_puntajes." (`RUT`,`ID Ensayo`,`Ensayo`,`Puntaje`, Numeros, Algebra, Geometria, Datos,`Year`,`Notificado`) VALUES ('". $data[$i][0] ."','". $idEnsayo[0][0] ."','". $ensayo. "','".$data[$i][1]."','".$data[$i][2]."','".$data[$i][3]."','".$data[$i][4]."','".$data[$i][5]."','". $year ."', '0')";
		}
		else{
			// EXISTE, SOLO ACTUALIZAR LOS DATOS
			$query = "UPDATE ".$tbl_puntajes." SET `Puntaje` = '".$data[$i][1]."', `Numeros` = '".$data[$i][2]."', `Algebra` = '".$data[$i][3]."', `Geometria` = '".$data[$i][4]."', `Datos` = '".$data[$i][5]."'
			WHERE Ensayo = '".$ensayo."' AND RUT = '".$data[$i][0]."' AND Year = '".$year."'";	
			
			// REVISAR SI SE CAMBIÓ EL PUNTAJE, SI SE CAMBIÓ PONER NOTIFICADO = 0 (es el mismo query pero agregado notificado = 0)
			if((int) $puntajeAntiguo != (int) $data[$i][1]){
				$query = "UPDATE ".$tbl_puntajes." SET `Puntaje` = '".$data[$i][1]."', `Numeros` = '".$data[$i][2]."', `Algebra` = '".$data[$i][3]."', `Geometria` = '".$data[$i][4]."', `Datos` = '".$data[$i][5]."', `Notificado` = '0'
			WHERE Ensayo = '".$ensayo."' AND RUT = '".$data[$i][0]."' AND Year = '".$year."'";	
			}
		}
		//echo (int) $puntajeAntiguo ." = ". (int) $data[$i][1]." | ";
		mysql_query($query)or die(mysql_error());
	}
	include_once "mailAutomatico.php";
?>