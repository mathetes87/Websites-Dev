<?php
	@session_start();
	$yearCambiado = $_POST['year'];
	if($_POST['horario']){
		$_SESSION['horarioYear'] = $yearCambiado;
	}
	else{
		$_SESSION['year'] = $yearCambiado;	
	}
	echo "ok";
?>