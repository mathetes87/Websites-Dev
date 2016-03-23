<?php
	$nombreCarpeta = $_POST['carpeta'];
	$path = $_POST['path'];
	$pathFinal = "../". $path ."". $nombreCarpeta;
	if(!is_dir($pathFinal)){
		mkdir($pathFinal);
		echo "ok";
	}
	else{
		echo "existe";
	}
?>