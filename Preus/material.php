<?php include_once('includes/isAdmin.php'); ?>

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
<body>
<?php
	include_once 'includes/menuAdmin.php';
	echo '<div class="wrapperGlobal">';
 	include_once 'includes/botonLogout.php';
 	
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
	echo "<div id='botonesMaterial'>";
		echo "<button id='botonAgregarCarpeta' class='boton'>Nuevo Eje</button>";
		echo "<button id='botonEliminarMaterial' class='boton'>Eliminar Material</button>";
		
		// Imprimir lista con secciones
		echo '<div id="seccionesMaterial">';
			echo '<select name="seccion">';
			echo '<option value="">Subir nuevo archivo a...</option>';
				for($i = 0; $i < count($subdir); $i++){
					echo '<option value="'. $subdir[$i] .'">'. $subdir[$i] .'</option>';	
				}
			echo '</select>';
		echo '</div>';
	?>
	<div id="wrapperFormaMaterial" class="touch">
		<form enctype="multipart/form-data" action="#" method="POST" id="formaMaterial">
		    <input name="file" type="file" id="subirArchivo"/>
		    <input style="display: none" name="subdir" id="subdir" type="text">
		    <input style="display: none" name="path" id="path" type="text">
		</form>
	</div>
	<?php
	echo "</div>";
	
	// IMPRIMIR TABLA
	echo "<table class='data_table tablaMaterial' cellspacing='3' cellpadding='5'>";
		echo "<tr>";
			foreach($subdir as $key=>$carpeta){ // Para la primera fila
					echo "<th>". $carpeta ."<img src='images/delete.png' class='deleteMaterialImg'></th>";	
			}
		echo "</tr>";
		for($i = 0; $i < maxFilas(); $i++){ // Cantidad de filas sin contar header
			echo "<tr>";
				for($j = 0; $j < count($subdir); $j++){ // $j < 5
					$archivo = $results[$rootFolder][$subdir[$j]][$i];
					$url = $dir ."". $subdir[$j] ."/". $archivo;
					if($archivo != ''){
						echo "<td><a href='". $url ."' target='blank'>". $archivo ."</a><img src='images/delete.png' class='deleteMaterialImg'></td>";		
					}
					else{
						echo "<td></td>";		

					}
				}
			echo "</tr>";	
		}
	echo "</table>";
	
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
	</div>  

</body>
<script>
$(document).ready(function(){
	var path = <?php echo json_encode($dir); ?>;
	var optionsReload = 
	{
		override: false, 
		buttons:{
			confirm:{
				text: 'Aceptar',
				action:function(){ 
					Apprise('close', location.reload(true) ); 
				}
			},
		}
	};
	// Si es touch, que se vea la forma para subir archivos (no funciona o sino)
	if(is_touch_device()){
		$('#formaMaterial').removeAttr('id');
	}
	
	function is_touch_device() { //http://stackoverflow.com/questions/4817029/whats-the-best-way-to-detect-a-touch-screen-device-using-javascript
	  	return !!('ontouchstart' in window) // works on most browsers 
	      || !!('onmsgesturechange' in window); // works on ie10
	};
	
	// SPINNER GIF
	$('body').append('<div class="loadingDiv"></div>');
	$(document).on({
	    ajaxStart: function() { 
	        $('body').addClass("loading"); 
	    },
	    ajaxStop: function() {
	        $('body').removeClass("loading"); 
	    }    
	});
	
	// CAMBIA DROPDOWN PARA AGREGAR ARCHIVO
	$('#seccionesMaterial select').change(function(){
		if($(this).find('option:selected').index() == 0){
			return;
		}
		var subdir = $(this).find('option:selected').text();
		// Guardar datos en forma
		$('#subdir').val(subdir);
		$('#path').val(path);
		// Pedir archivo al ususario
		$('#subirArchivo').click();
	});
	
	// ENVIAR FORMA CON EL ARCHIVO SELECCIONADO
	$("#wrapperFormaMaterial input:file").change(function (){    	
    	var formData = new FormData($('#wrapperFormaMaterial form')[0]);
		$.ajax({
			url:'includes/guardarArchivo.php',
			type: 'POST',
			data: formData,
			success: function (data) {
				location.reload();
			},
			cache: false,
			contentType: false,
			processData: false
		});
    });
    
	// MOSTRAR BOTONES DE ELIMINAR MATERIAL
	$('#botonEliminarMaterial').click(function(){
		$('.deleteMaterialImg').fadeIn();	
	});
	// ELIMINAR CARPETA
	$('th .deleteMaterialImg').click(function(){
		var nombre = $(this).parent().text();
		Apprise("¿Borrar definitivamente la sección <b>"+ nombre +"</b> y todo el material que contiene?", { 
			override: false,
			buttons: { 
				confirm: { 
					text: 'Borrar', 
					action: function(r) { 
						Apprise('close'); 
						$.ajax({
							url: 'includes/borrarMaterial.php',
							type: 'POST',
							data: ({carpeta: nombre, path: path}),
							success: function(data){
								if(data == 'ok'){
									Apprise('close', location.reload(true) ); 
								}
								else{
									Apprise("Ocurrió un error, este fue el mensaje de error:</br>"+ data, optionsReload);
								}
							}
						});
					} 
				},
				cancel:{
					text: 'Cancelar', 
					action: function() {
						Apprise('close'); 
					} 
				}
			}
		});		
	});
	// ELIMINAR ARCHIVO
	$('td .deleteMaterialImg').click(function(){
		var nombre = $(this).parent().text();
		var subdir = $(this).closest('tbody').find('tr:first th').eq($(this).parent().index()).text();
		Apprise("¿Borrar definitivamente el archivo <b>"+ nombre +"</b>?", { 
			override: false,
			buttons: { 
				confirm: { 
					text: 'Borrar', 
					action: function(r) { 
						Apprise('close'); 
						$.ajax({
							url: 'includes/borrarMaterial.php',
							type: 'POST',
							data: ({archivo: nombre, subdir: subdir, path: path}),
							success: function(data){
								if(data == 'ok'){
									Apprise('close', location.reload(true) ); 
								}
								else{
									Apprise("Ocurrió un error, este fue el mensaje de error:</br>"+ data, optionsReload);
								}
							}
						});
					} 
				},
				cancel:{
					text: 'Cancelar', 
					action: function() {
						Apprise('close'); 
					} 
				}
			}
		});		
	});
	
	// PARA CREAR NUEVA CARPETA
	$('#botonAgregarCarpeta').click(function(){
		Apprise("Ingrese el nombre de la nueva sección", { 
			override: false,
			buttons: { 
				confirm: { 
					text: 'Crear', 
					action: function(r) { 
						Apprise('close'); 
						$.ajax({
							url: 'includes/crearCarpeta.php',
							type: 'POST',
							data: ({carpeta: r.input, path: path}),
							success: function(data){
								if(data == 'ok'){
									Apprise('close', location.reload(true) ); 
								}
								else if(data == 'existe'){
									Apprise("Ya existe una sección con ése nombre", {
										override: false, 
										buttons:{
											confirm:{
												text: 'Aceptar',
												action:function(){ 
													Apprise('close'); 
												}
											},
										}
									});
								}
								else{
									Apprise("Ocurrió un error, este fue el mensaje de error:</br>"+ data, optionsReload);
								}
							}
						});
					} 
				},
				cancel:{
					text: 'Cancelar', 
					action: function() {
						Apprise('close'); 
					} 
				}
			},
			input: true,
		});	
	});
});
</script>
</html>
