<?php
	// CREAR PAGINA PARA TODOS LOS APODERADOS
	// QUERY PARA RUT DE TODOS LOS ALUMNOS
	include_once 'conectarDB.php';
	include_once 'infoProfesor.php';
    include_once 'mysql_fetch_all.php';
	@session_start();
   	$year = $_SESSION['year'];
   	$thisYear = date("Y");
   	if($year == $thisYear){
		$query="SELECT `RUT` FROM $tbl_usuarios WHERE `RUT` != '".$rutProfesor."' AND (`Year` = $year OR `Year` IS NULL) ORDER BY Apellidos";
	}
	else{
		//$query="SELECT `RUT` FROM $tbl_usuarios WHERE `RUT` != '".$rutProfesor."' AND `Year` = $year ORDER BY Apellidos";
		$query="SELECT `RUT` FROM $tbl_usuarios WHERE `RUT` != '".$rutProfesor."' AND `Year` = 2013 ORDER BY Apellidos";
	}
	$res = mysql_query($query);
	$alumnos = mysql_fetch_all($res);
	//echo count($alumnos). "<br>";
	// CREAR PAGINA CON VARIABLE DE RUT UNICO Y CON INCLUDE PARA ESTADISTICAS DE ALUMNO, PERO OCUPANDO ESTA VARIABLE DE RUT
	for($i = 0; $i < count($alumnos); $i++){
		$nombrePagina = md5($alumnos[$i][0]);
		$rutAlumno = $alumnos[$i][0];
		$content = '
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<title>Puntajes</title>
			<link href="../css.css" rel="stylesheet" type="text/css" />
			<script src="../jquery.min.js" type="text/javascript"></script>
			<script type="text/javascript" src="../js.js"></script>
			<script type="text/javascript" src="../jquery.flot.js"></script>
			<script type="text/javascript" src="../jquery.flot.resize.js"></script>
			<script type="text/javascript" src="../jquery.flot.stack.min.js"></script>
			<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../excanvas.min.js"></script><![endif]-->
			<link rel="stylesheet" href="../fancybox/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
			<script type="text/javascript" src="../fancybox/jquery.fancybox.pack.js?v=2.1.4"></script>
			<link href="../forma.css" rel="stylesheet" type="text/css" /> 
			</head>
			<?php
			 	$rutDesdeFuera = '. $rutAlumno .';
			 	$desdeAlumno = false;
				include_once "../alumnoComun.php";
			?>'
		;
		$file = @fopen("../apoderados/". $nombrePagina .".php","x"); // CON X EN VEZ DE W FALLA SI YA EXISTE
		if($file){
		    fwrite($file, $content); 
		    fclose($file); 
		}
	}

	/*//Get the table.
	$tabla = $_POST['tabla'];
	// Write the thing
	@session_start();
	$thisYear = date("Y");
	if($_SESSION['yearHorario'] == $thisYear){
		$f = fopen('tablaHorario.html', 'w');	
	}
	else{
		$f = fopen('tablaHorarioProx.html', 'w');
	}
	fwrite($f, $tabla);
	// Close file
	fclose($f);
	
	// PARA CREAR SOLO SI NO EXISTE TODAVIA
	$file = @fopen("test.txt","x"); // CON X EN VEZ DE W FALLA SI YA EXISTE
	if($file)
	{
	    echo fwrite($file,"Some Code Here"); 
	    fclose($file); 
	}*/
?>