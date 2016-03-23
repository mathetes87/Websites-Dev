<?php include_once('includes/isAdmin.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Emails</title>
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
<body id="bodyEmails">
 	<?php
        include_once 'includes/menuAdmin.php';
		echo '<div class="wrapperGlobal">';
 		include_once 'includes/botonLogout.php';
       	include_once 'includes/conectarDB.php';
		include_once 'includes/infoProfesor.php';
       	include_once 'includes/mysql_fetch_all.php';
       	include_once 'includes/mailCustom.php';
		include_once 'includes/chooseYear.php';
       	@session_start();
		$year = $_SESSION['year'];
		
		//////// PARA LOS TITULOS EN LA TABLA HTML
		$query="SELECT `Nombres`, `Apellidos`, `Grupo`, `Mail`, `Nombre papa`, `Mail papa`, `Nombre mama`, `Mail mama`, RUT  FROM $tbl_usuarios WHERE `RUT` != '".$rutProfesor."' AND (`Nombres` != '' OR `Apellidos` != '') AND (`Year` = $year OR `Year` IS NULL) ORDER BY  Grupo ASC, Apellidos ASC";
		$res = mysql_query($query);
		$alumnos = mysql_fetch_all($res);
		$numeroColumnas = 6;
		$row = 0;
		
		echo "<h1 class='margenLeft'>Enviar emails de forma masiva <a href='#' class='yearClick'>(". $year .")</a></h1>";
		echo "<h3 id='subtituloEmails'>Si algún alumno o apoderado no se puede seleccionar, es porque debe ingresar antes sus email de contacto</h3>";
		
		//////// IMPRIMIR TABLA ////////
		echo "<div id='datosEmails'>";
			echo "<table class='data_table floatLeft tablaEmails' cellspacing='3' cellpadding='5'>";
				
			titulosTabla();
			for($i = 0; $i < count($alumnos); $i++) {
				$row++;
				create_table($alumnos[$i]);
			}
			echo "<tr class='spacer'></tr>";				
			echo "</table>";
		//////// TABLA IMPRESA ////////
		
		//////// IMPRIMIR CUADRO CON LISTA DE EMAILS PRE HECHOS ////////
			echo "<table class='data_table floatLeft envioMails' cellspacing='3' cellpadding='5'>";
				echo "<tr>";
					echo "<th id='headerEnvioMails'><h3>Elegir email tipo</h3></th>";
				echo "</tr>";
					$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('emails/'));
					$it->next(); // QUITAR SI FALTA EL PRIMER ARCHIVO!!!!!!!!!!!
					while($it->valid()) {
					    if (!$it->isDot()) {
							echo "<tr>";
								// le quita a los archivos los ultimos caracteres (entre 2 y 4, sin contar espacios) despues del ultimo punto
					        	echo "<td><span>". utf8_encode(preg_replace("/\\.[^.\\s]{2,4}$/", "", $it->getSubPathName())) . "</span></br></td>";
							echo "</tr>";
					    }
					    $it->next();
					}
				echo "<tr><td class='celdaInvisible'></td></tr>";
				echo "<tr><td class='celdaInvisible'></td></tr>";
				echo "<tr>";
		        	echo "<td><a class='custom' href='#fancyMail'>Personalizado</a></td>";
				echo "</tr>";
			echo "</table>";
		//////// CUADRO IMPRESO ////////	
		echo "</div>";	
		
		function titulosTabla(){
			echo "<tr>";
		    echo "<th nowrap>N°</th>";
			echo "<th><span style='display:block'>Alumno</span><input type='checkbox' class='checkTrAl'></th>";
			echo "<th><span style='display:block'>Apoderados</span><input type='checkbox' class='checkTrAp'></th>";
		    echo "<th>Nombre</th>";
		    echo "<th>Apellidos</th>";
		    echo "<th>Grupo</th>";
		    echo "</tr>";
		}
		
		function create_table($dataArr) {
			global $row;
		    echo "<tr>";
		    echo "<th class='numero'>$row</th>";
		    if($dataArr[3] != ''){
		    	echo "<th><input type='checkbox' class='checkAl'></th>";	
		    }
		    else{
		    	echo "<th></th>";
		    }
		    if($dataArr[5] != '' || $dataArr[7] != ''){
		    	echo "<th><input type='checkbox' class='checkAp'></th>";	
		    }
		    else{
		    	echo "<th></th>";
		    }
		    for($j = 0; $j < count($dataArr)/2; $j++) {
		    	if($j < 3){
		    		echo "<td>".ucwords($dataArr[$j])."</td>";	
		    	}	
		    }
		    echo "</tr>";
		}
	?>
	</div>  	

</body>
<script>
$(document).ready(function(){
	var datos = <?php echo json_encode($alumnos); ?>;
	var celda;
	// PARA CHECKBOXES QUE MARCAN TODA LA COLUMNA
	$('.checkTrAl').click(function(){
		if($('.checkTrAl:checked').length > 0){
			$('.checkAl').prop("checked", "checked");
		}	
		else{
			$('.checkAl').removeAttr("checked");
		}
	});
	$('.checkTrAp').click(function(){
		if($('.checkTrAp:checked').length > 0){
			$('.checkAp').prop("checked", "checked");
		}	
		else{
			$('.checkAp').removeAttr("checked");
		}
	});	
	
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
	
	function revisarAlgunCheck(){
		if($('.checkAl:checked').length + $('.checkAp:checked').length == 0){
			Apprise("<span class='textoRojo'>ERROR: Debe seleccionar al menos un alumno o apoderados</span>", {
				override: false, 
				buttons:{
					confirm:{
						text: 'Aceptar',
						action:function(){ 
							Apprise('close'); 
							return false;
						}
					},
				}
			});
		}
		else{
			return true;
		}
	}
	
	// CLICK EN MANDAR EMAIL PRE HECHO CON SPAN, NO LINK
	$('.envioMails span').click(function(){
		if(!revisarAlgunCheck()){
			return;
		}
		var forma = $(this).text();
		var arreglo = formarArreglo();
		var stringed = JSON.stringify(arreglo);
		Apprise("Ingrese el <b>asunto</b> del email que se enviará", { 
			buttons: { 
				confirm: { 
					text: 'Enviar', 
					action: function(e) { 
						Apprise('close'); 
						$.ajax({
							url: 'includes/mailMasivo.php',
							type: 'POST',
							data: ({forma: forma, tabla: stringed, asunto: e.input, custom: "false"}),
							success: function(data){
								if(data == 'ok'){
									Apprise("Los email fueron enviados con éxito", optionsReload);
								}
								else{
									Apprise("Ocurrió un error, este fue el mensaje de salida:</br>"+ data, optionsReload);
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
	
	// CLICK EN NOMBRES CLAVE DE CUSTOM MAIL
	$(".palabrasClave span").click(function(){
		var data = $('.txtarea').val();
	    $('.txtarea').focus().val('').val(data + $(this).data("clave"));
	});
	
	// BOTONES DE CUSTOM MAIL
	$('.envioMails .custom').click(function(){
		if(!revisarAlgunCheck()){
			return;
		}
		$.fancybox.open({
	        href : '#fancyMail',
	        closeBtn: false
	    });
	});
	$('#sendCustomMail').click(function(e){
		e.preventDefault();
		var arreglo = formarArreglo();
		var stringed = JSON.stringify(arreglo);
		$.fancybox.close();
		$.ajax({
			url: 'includes/mailMasivo.php',
			type: 'POST',
			data: ({tabla: stringed, custom: "true", deForma: $('#redactarMail').serialize()}),
			success: function(data){
				if(data == 'ok'){
					Apprise("Los email fueron enviados con éxito", optionsReload);
				}
				else{
					Apprise("Ocurrió un error, este fue el mensaje de salida:</br>"+ data, optionsReload);
				}
			}
		});
	});
	$('#cancelCustomMail').click(function(e){
		e.preventDefault();
		$.fancybox.close();
	});
	
	function formarArreglo(){
		// FORMAR ARREGLO BIDIMENSIONAL CON PORTE ADECUADO
		var filas = $('.tablaEmails tr').not(':first').not(':last').length;
		celda = new Array(filas);
		for (var i = 0; i < filas; i++) {
			celda[i] = new Array(10);
		}
		// PASAR FILAS AL ARREGLO
		$('.tablaEmails tr').not(':first').not(':last').each(function(i){
			celda[i][0] = datos[i][0]; 	// Nombre alumno
			celda[i][1] = datos[i][1];	// Apellidos alumno
			celda[i][2] = datos[i][3];	// Mail alumno
			celda[i][3] = datos[i][4];	// Nombre papa
			celda[i][4] = datos[i][5];	// Mail papa
			celda[i][5] = datos[i][6];	// Nombre mama
			celda[i][6] = datos[i][7];	// Mail mama
			celda[i][7] = datos[i][8];	// Rut
			
			// Check alumno
			if($(this).find("input:checked").hasClass("checkAl")){
				celda[i][8] = "true";
			}
			else{
				celda[i][8] = "false";
			}
			// Check apoderados
			if($(this).find("input:checked").hasClass("checkAp")){
				celda[i][9] = "true";
			}
			else{
				celda[i][9] = "false";
			}
		});
		return celda;
	}
});
</script>
</html>