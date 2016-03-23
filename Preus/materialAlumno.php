<?php include_once('includes/isLogueado.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Material</title>
<link href="css.css" rel="stylesheet" type="text/css" />
<script src="jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js.js"></script>
<link rel="stylesheet" href="/fancybox/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
<script type="text/javascript" src="/fancybox/jquery.fancybox.pack.js?v=2.1.4"></script>
<link href="forma.css" rel="stylesheet" type="text/css" /> 
<script type="text/javascript" src="apprise2/apprise2.js"></script>
<link rel="stylesheet" href="apprise2/apprise2.css" type="text/css" /> 
<link rel="stylesheet" href="animate-custom.css" type="text/css" />
</head>
<body class="paddingBody">
<?php
	//header('Content-Type: text/html;charset=UTF-8');
    include_once 'includes/menu.php';
 	include_once 'includes/botonLogout.php';
	include_once 'includes/formaContacto.php';
	include_once 'includes/formaLogin.php';
 	
	$dir = 'material/';
	$rootFolder = "material";
	$subdir = array();
	$results = array();
	if (is_dir($dir)) {
	    $iterator = new RecursiveDirectoryIterator($dir);
	    foreach ( new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST) as $file ) {
	        if ($file->isFile()) {
	            $thispath = str_replace('\\', '/', $file);
	            $thisfile = $file->getFilename();
	            $results = array_merge_recursive($results, pathToArray($thispath));
	        }
	        elseif(is_dir($file)){
	        	$thispath = str_replace('\\', '/', $file);
	            $thisfile = $file->getFilename();
	            if(strpos($thisfile, '.') === false){
	            	array_push($subdir, $thisfile);
	            }
	        }
	    }
	}
	
	
	echo "<h1 id='tituloMaterial' class='margenLeft'>Material complementario</h1>";
	echo "<h3 class='subtituloMaterial'>Aquí puedes descargar material para reforzar tu prueba</h3>";
		
	// IMPRIMIR INDICE DE TABLAS OCULTAS
	echo "<table class='data_table tablaMaterial listadoMaterial' cellspacing='3' cellpadding='5'>";
		echo "<tr><th>Elige una sección de material</th></tr>";
		echo "<tr><td> <br>";
	foreach($subdir as $key=>$carpeta){ 
		if($_SESSION["material"] == str_replace(' ', '_', $carpeta)){
			echo "<span class='pseudolink elegido'>". $carpeta ."</span><br><br>";
		}
		else{
			echo "<span class='pseudolink'>". $carpeta ."</span><br><br>";
		}	
	}
		echo "</td></tr>";
	echo "</table>";
	
	echo '<div class="containerTablasMaterialAlumno"></div>';
	
	// IMPRIMIR TABLAS OCULTAS
	foreach($subdir as $key=>$carpeta){ // Para la primera fila
		$carpeta = str_replace(')', '', $carpeta);
		echo "<table class='data_table tablaMaterial materialesSubdir ". str_replace(' ', '_', $carpeta) ."' cellspacing='3' cellpadding='5'>";
			echo "<tr><th>". $carpeta ."</th></tr>";
			$url = $dir ."". $carpeta ."/". $archivo;
			for($i = 0; $i < count($results[$rootFolder][$subdir[$key]]); $i++){
				echo "<tr><td><a href='". $url ."". $results[$rootFolder][$subdir[$key]][$i] ."' target='blank'>". $results[$rootFolder][$subdir[$key]][$i] ."</a></td></tr>";
			}
		echo "</table>";
	}
	
	function maxFilas(){
		global $results, $subdir, $rootFolder;
		$max = 0;
		for($j = 0; $j < count($subdir); $j++){ // $j < 5
			$localMax = count($results[$rootFolder][$subdir[$j]]);
			if($localMax > $max){
				$max = $localMax;
			}
		}
		return $max;
	}
	
	function pathToArray($path , $separator = '/') {
    if (($pos = strpos($path, $separator)) === false) {
        return array($path);
    }
    return array(substr($path, 0, $pos) => pathToArray(substr($path, $pos + 1)));
}
?>
<script>
$(document).ready(function(){
	// Esconder todos
	$('.materialesSubdir').hide();
	// Existe algun span con clase elegido? Sino elegir el primero
	if($('.elegido').length > 0){
		$('.elegido').show();	
	}
	else{
		$('.listadoMaterial span').eq(0).addClass('elegido');
	}
	mostrarTablaElegida();
	
	// SI HACE CLICK EN OTRO SPAN DE SUBDIR
	$('.listadoMaterial span').click(function(){
		$('.elegido').removeClass('elegido');
		$(this).addClass("elegido");
		mostrarTablaElegida();
	});
	
	
	function mostrarTablaElegida(){
		
		var nombre = $('.elegido').text();
		nombre = nombre.replace(/\ /g, "_").replace(")", "");
		$('.containerTablasMaterialAlumno').html($('.'+ nombre).clone().fadeIn());
		
	}
});
</script>
</body>
</html>