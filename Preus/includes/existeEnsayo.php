<?php
include_once 'conectarDB.php';
@session_start();
$year = $_SESSION['year'];
$ensayo = $_POST["ensayo"];
$query = "SELECT Ensayo FROM $tbl_puntajes WHERE `Year` = $year AND Ensayo = '$ensayo'";
$ensayos = mysql_query($query);

if(mysql_num_rows($ensayos) > 0){
	echo "existe";
}
else{
	echo "no existe";
}
?>