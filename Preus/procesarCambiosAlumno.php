<?php
	include 'includes/conectarDB.php';
	@session_start();
	$RUT = $_POST['RUT'];
	// Purge . and - from RUT
	$RUT = str_replace(".", "", $RUT);
	$RUT = str_replace("-", "", $RUT);
	if($_POST['Password'] != null || $_POST['Password'] != ''){
		$Password = md5($_POST['Password']);
		$_COOKIE['upass'] = $Password;
	}
	//echo $RUT;
	$Nombres = $_POST['Nombres'];
	$Apellidos = $_POST['Apellidos'];
	$Mail = $_POST['Mail'];
	$Telefono = $_POST['Telefono'];
	$Colegio = $_POST['Colegio'];
	$Nem = $_POST['Nem'];
	$Carrera = $_POST['Carrera'];
	$Universidad = $_POST['Universidad'];
	$NombrePapa = $_POST['NombrePapa'];
	$MailPapa = $_POST['MailPapa'];
	$NombreMama = $_POST['NombreMama'];
	$MailMama = $_POST['MailMama'];
	$CelularMama = $_POST['CelularMama'];
	$Matem = $_POST['Matem'];
	$Nacimiento = $_POST['Nacimiento'];
	$TelCasa = $_POST['TelCasa'];
	$Direccion = $_POST['Direccion'];
	$Sexo = $_POST['Sexo'];
	$Grupo = $_POST['Grupo'];
	
	// SI YA EXISTE EL RUT VA A SOBREESCRIBIR LA INFORMACION??
	// Cambiar que fuera primera vez
	$query = "UPDATE ".$tbl_usuarios." SET Primera = 0 WHERE RUT = '{$_COOKIE['uname']}'";
	mysql_query($query) or die(mysql_error());
	
	if($Password == null || $Password == ''){
		// No se cambio el Password
		$query = "UPDATE ".$tbl_usuarios." SET RUT = '".$RUT."', Nombres = '".$Nombres."', Apellidos = '".$Apellidos."', 
			Mail = '".$Mail."', Telefono = '".$Telefono."', Colegio = '".$Colegio."', Nem = '".$Nem."', Carrera = '".$Carrera."', Universidad = '".$Universidad."', `Nombre papa` = '".$NombrePapa."', `Mail papa` = '".$MailPapa."', `Nombre mama` = '".$NombreMama."', `Mail mama` = '".$MailMama."', `Celular mama` = '".$CelularMama."', `Promedio Matematica` = '".$Matem."', `Nacimiento` = '".$Nacimiento."', `Telefono Casa` = '".$TelCasa."', `Direccion` = '".$Direccion."', `Sexo` = '".$Sexo."', `Grupo` = '".$Grupo."' 
			WHERE RUT = '{$_COOKIE['uname']}'";
	}
	else{
		$query = "UPDATE ".$tbl_usuarios." SET RUT = '".$RUT."', Nombres = '".$Nombres."', Apellidos = '".$Apellidos."', 
			Mail = '".$Mail."', Telefono = '".$Telefono."', Password = '".$Password."', Colegio = '".$Colegio."', Nem = '".$Nem."', Carrera = '".$Carrera."', Universidad = '".$Universidad."', `Nombre papa` = '".$NombrePapa."', `Mail papa` = '".$MailPapa."', `Nombre mama` = '".$NombreMama."', `Mail mama` = '".$MailMama."', `Celular mama` = '".$CelularMama."', `Promedio Matematica` = '".$Matem."', `Nacimiento` = '".$Nacimiento."', `Telefono Casa` = '".$TelCasa."', `Direccion` = '".$Direccion."', `Sexo` = '".$Sexo."' , `Grupo` = '".$Grupo."'
			WHERE RUT = '{$_COOKIE['uname']}'";
	}
	mysql_query($query) or die(mysql_error());
	
	// Actualizar el Rut en todas las tablas
	$query2 = "UPDATE ".$tbl_puntajes." SET RUT = '".$RUT."' WHERE RUT = '{$_COOKIE['uname']}' ";			
	$query3 = "UPDATE ".$tbl_asistencia." SET Rut = '".$RUT."' WHERE Rut = '{$_COOKIE['uname']}' ";			
	$query4 = "UPDATE ".$tbl_controles." SET Rut = '".$RUT."' WHERE Rut = '{$_COOKIE['uname']}' ";		
	$query5 = "UPDATE pagos SET Rut = '".$RUT."' WHERE Rut = '{$_COOKIE['uname']}' ";

	mysql_query($query2)or die(mysql_error());
	mysql_query($query3)or die(mysql_error());
	mysql_query($query4)or die(mysql_error());
	mysql_query($query5)or die(mysql_error());
	
	
	
	// Actualizar cookie de RUT
	@setcookie("upass", $RUT, time()+60*60*24*100);
	
	include_once "includes/mailAutomatico.php";

	echo "ok";
?>