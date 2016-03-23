<?php
include_once 'conectarDB.php';
include_once 'mysql_fetch_all.php';
include_once 'infoProfesor.php';
@session_start();
$year = $_SESSION['year'];
$fecha = $_POST["fecha"];
$thisYear = date("Y");

if($year == $thisYear){
	$queryAlumnos="SELECT `RUT`, `Nombres`, `Apellidos`, `Year` FROM $tbl_usuarios WHERE `RUT` != '".$rutProfesor."' AND (`Year` = $year OR `Year` IS NULL) ORDER BY Apellidos";
}
else{
	$queryAlumnos="SELECT `RUT`, `Nombres`, `Apellidos`, `Year` FROM $tbl_usuarios WHERE `RUT` != '".$rutProfesor."' AND `Year` = $year ORDER BY Apellidos";
}
$alumnos = mysql_fetch_all(mysql_query($queryAlumnos));

// REVISAR QUE NO EXISTA LA FECHA
$query = "SELECT * FROM $tbl_asistencia WHERE Fecha = ". $fecha ."";
$fechas = mysql_fetch_all(mysql_query($query));
if(count($fechas) > 0){
	echo "existe";
	return;
}

for($i = 0; $i < count($alumnos); $i++){
	mysql_query("INSERT INTO $tbl_asistencia (`RUT`,`Fecha`) VALUES ('". $alumnos[$i][0] ."','". $fecha ."')") or die(mysql_error());	
}

echo "ok";
?>