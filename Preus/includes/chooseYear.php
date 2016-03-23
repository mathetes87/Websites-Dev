<?php
	include_once 'infoProfesor.php';
	include_once 'mysql_fetch_all.php';
	// Encontrar todos los años con datos
	$queryYear="SELECT `Year` FROM $tbl_usuarios WHERE `RUT` != '".$rutProfesor."' AND Year IS NOT NULL GROUP BY `Year`";
	$years = mysql_fetch_all(mysql_query($queryYear));
	
	@session_start();
	if(!isset($_SESSION['year'])){
		$_SESSION['year']  = date("Y");
	}
	
	// Imprimir lista con años
	echo '<div style="display:none" class="dropYears">';
		echo '<select name="years">';
		echo '<option value="">Cambiar año</option>';
			for($i = 0; $i < count($years); $i++){
				echo '<option value="'. $years[$i][0] .'">'. $years[$i][0] .'</option>';	
			}
		echo '</select>';
	echo '</div>';	
?>