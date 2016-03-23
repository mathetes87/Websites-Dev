<?php
	include_once 'conectarDB.php';
	include_once 'mysql_fetch_all.php';
	$queryFechas = "SELECT Fecha FROM asistencia WHERE year(Fecha) = ". date("Y") ." GROUP BY `Fecha` ORDER BY `Fecha` ASC";
	$fechas = mysql_fetch_all(mysql_query($queryFechas));
	$RUT = $_POST['RUT'];
	// Purge . and - from RUT
	$RUT = str_replace(".", "", $RUT);
	$RUT = str_replace("-", "", $RUT);
	$Nombres = $_POST['Nombres'];
	$Apellidos = $_POST['Apellidos'];
	$Mail = $_POST['Mail'];
	$Telefono = $_POST['Telefono'];
	$Grupo = $_POST['Grupo'];
	$Anio = date("Y");
	$Colegio = $_POST['Colegio'];
	$Nem = $_POST['PromGeneral'];
	if($Nem == ""){
		$Nem = null;
	}
	$Carrera = $_POST['Carrera'];
	$Universidad = $_POST['Universidad'];
	$NombrePapa = $_POST['NombrePapa'];
	$MailPapa = $_POST['MailPapa'];
	$NombreMama = $_POST['NombreMama'];
	$MailMama = $_POST['MailMama'];
	$CelularMama = $_POST['CelularMama'];
	$Matem = $_POST['Matem'];
	$Diagnostico = $_POST['Diagnostico'];
	$Nacimiento = $_POST['Nacimiento'];
	$TelCasa = $_POST['TelCasa'];
	$Direccion = $_POST['Direccion'];
	$Sexo = $_POST['Sexo'];
	$Password = md5("0000");
	
	//Search RUT in table 'usuario' and return number of columns to check if RUT exists in the table
	$checkrut = mysql_query("SELECT * FROM `usuarios` WHERE `RUT` = '".$RUT."'");
	if(mysql_num_rows($checkrut) > 0){
		echo "existe";
	}
	else{
		mysql_query("INSERT INTO $tbl_usuarios(`RUT`,`Password`,`Nombres`,`Apellidos`, `Mail`, `Telefono`, `Grupo`, `Year`, `Colegio`, `Nem`, `Carrera`, `Universidad`, `Nombre papa`, `Mail papa`, `Nombre mama`, `Mail mama`, `Celular mama`, `Promedio Matematica`, `Diagnostico`, `Nacimiento`, `Telefono Casa`, `Direccion`, `Sexo`) 
		VALUES('".$RUT."','".$Password."','".$Nombres."','".$Apellidos."','".$Mail."','".$Telefono."','".$Grupo."','".$Anio."','".$Colegio."','".$Nem."','".$Carrera."','".$Universidad."','".$NombrePapa."','".$MailPapa."','".$NombreMama."','".$MailMama."','".$CelularMama."','".$Matem."','".$Diagnostico."','".$Nacimiento."','".$TelCasa."','".$Direccion."','".$Sexo."')");
		
		// Agregarlo en tabla asistencia
		for($i=0; $i < count($fechas); $i++){
			mysql_query("INSERT INTO $tbl_asistencia (`Rut`,`Fecha`) VALUES ('". $RUT ."','". $fechas[$i][0] ."')") or die(mysql_error());
		}
		
		// Agregarlo en tabla controles
		for($i=1; $i < 27; $i++){
			mysql_query("INSERT INTO $tbl_controles (`Rut`,`Control`, `Year`) VALUES ('". $RUT ."','". $i ."','". date("Y") ."')") or die(mysql_error());
		}
		
		// Agregarlo en tabal pagos
		$meses = array("Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre");
		for($i=0; $i < count($meses); $i++){
			mysql_query("INSERT INTO $tbl_pagos (`Rut`,`Mes`, `Pago`, `idMes`, `Year`) VALUES ('". $RUT ."','". $meses[$i] ."', 'null' ,'". ($i+1) ."','". date("Y") ."')") or die(mysql_error());
		}
		echo "true";
	}	
?>