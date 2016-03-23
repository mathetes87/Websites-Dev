<?php
include_once 'conectarDB.php';
include_once 'mysql_fetch_all.php';
include_once 'infoProfesor.php';
@session_start();
$year = $_SESSION['year'];
$ensayo = $_POST["ensayo"];

// Revisar si ya existe el ensayo
$query = "SELECT Ensayo FROM $tbl_puntajes WHERE `Year` = $year AND Ensayo = '$ensayo'";
$ensayos = mysql_query($query);
if(mysql_num_rows($ensayos) > 0){
	echo "existe";
	return;
}

// Obtener ID Ensayo mas alto hasta ahora
$query0 = "SELECT `ID Ensayo` FROM $tbl_puntajes ORDER BY `ID Ensayo` DESC LIMIT 1";
$idEnsayoMaxQuery = @mysql_fetch_array(mysql_query($query0));
$idEnsayoNuevo = $idEnsayoMaxQuery[0]+1;
//echo $idEnsayoNuevo;

// Lista con todos los RUT de ese año
$query1 = "SELECT DISTINCT RUT FROM $tbl_usuarios WHERE (`Year` = $year OR `Year` IS NULL) AND `RUT` != '$rutProfesor'";
$ruts = mysql_query($query1);
$listaRuts = mysql_fetch_all($ruts);
//echo mysql_num_rows($ruts);

// Guardar para cada RUT en tabla puntajes un puntaje con 0 en todo
for($i = 0; $i < mysql_num_rows($ruts); $i++){
	mysql_query("INSERT INTO $tbl_puntajes (`RUT`,`ID Ensayo`,`Ensayo`,`Puntaje`, Numeros, Algebra, Geometria, Datos,`Year`) VALUES ('". $listaRuts[$i][0] ."','". $idEnsayoNuevo ."','". $ensayo. "','','','','','','". $year ."')") or die(mysql_error());	
}

echo "creado";
?>