<?php
	if($_POST['carpeta']){
		$nombreCarpeta = $_POST['carpeta'];
		$path = $_POST['path'];
		delTree("../". $path ."". $nombreCarpeta);
		//rmdir("../". $path ."". $nombreCarpeta);
		echo "ok";
	}
	elseif($_POST['archivo']){
		$nombreArchivo = $_POST['archivo'];
		$subdir = $_POST['subdir'];
		$path = $_POST['path'];
		unlink("../". $path ."". $subdir ."/". $nombreArchivo);
		echo 'ok';
	}
	else{
		echo 'Not ok';
	}
	
	function delTree($dir) { 
	   if (is_dir($dir)) { 
	     $objects = scandir($dir); 
	     foreach ($objects as $object) { 
	       if ($object != "." && $object != "..") { 
	         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
	       } 
	     } 
	     reset($objects); 
	     rmdir($dir); 
	   } 
	 } 
?>