<?php include_once('includes/isAdmin.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Pagos</title>
<link href="css.css" rel="stylesheet" type="text/css" />
<script src="jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="calendar/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="calendar/jquery.ui.datepicker-es.js"></script>
<link rel="stylesheet" href="calendar/css/jquery-ui-1.10.3.custom.min.css" type="text/css" /> 

<script type="text/javascript" src="js.js"></script>
<link rel="stylesheet" href="/fancybox/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
<script type="text/javascript" src="/fancybox/jquery.fancybox.pack.js?v=2.1.4"></script>
    <script type="text/javascript" src="tooltipster/jquery.tooltipster.min.js"></script>
<link href="forma.css" rel="stylesheet" type="text/css" /> 
<script type="text/javascript" src="apprise2/apprise2.js"></script>
<link rel="stylesheet" href="apprise2/apprise2.css" type="text/css" /> 
<link rel="stylesheet" href="animate-custom.css" type="text/css" /> 
<link rel="stylesheet" type="text/css" href="tooltipster/tooltipster.css" />
</head>
<body id='bodyAlumnos'>
 	<?php
        include_once 'includes/menuAdmin.php';
		echo '<div class="wrapperGlobal">';
 		include_once 'includes/botonLogout.php';
       	include_once 'includes/conectarDB.php';
		include_once 'includes/infoProfesor.php';
       	include_once 'includes/mysql_fetch_all.php';
       	include_once 'includes/chooseYear.php';
       	@session_start();
       	$year = $_SESSION['year'];
       	$thisYear = date("Y");
		// $tbl_usuarios="usuarios"; // Table name 1
		// $tbl_puntajes="puntajes"; // Table name 2
		
		//////// PARA LOS TITULOS EN LA TABLA HTML
		if($year == $thisYear){
			$query="SELECT $tbl_pagos.Rut, $tbl_pagos.idMes, $tbl_pagos.Pago, $tbl_pagos.Tooltip, $tbl_usuarios.Nombres, $tbl_usuarios.Apellidos FROM $tbl_pagos LEFT JOIN $tbl_usuarios ON $tbl_usuarios.RUT = $tbl_pagos.Rut WHERE $tbl_pagos.Rut != '".$rutProfesor."' ORDER BY `idMes` ASC";
			$queryAlumnos="SELECT `RUT`, `Nombres`, `Apellidos`, `Mail mama`, `Nombre mama` FROM $tbl_usuarios WHERE `RUT` != '".$rutProfesor."' AND (`Year` = $year OR `Year` IS NULL) ORDER BY  Grupo ASC, Apellidos ASC";
			$controlesSolo = mysql_fetch_all(mysql_query("SELECT Mes FROM $tbl_pagos WHERE (`Year` = $year OR `Year` IS NULL)  GROUP BY idMes"));
		}
		else{
			$query="SELECT $tbl_pagos.Rut, $tbl_pagos.Mes, $tbl_pagos.Pago, $tbl_usuarios.Nombres, $tbl_usuarios.Apellidos FROM $tbl_pagos LEFT JOIN $tbl_usuarios ON $tbl_usuarios.RUT = $tbl_pagos.Rut WHERE $tbl_pagos.Rut != '".$rutProfesor."' ORDER BY `idMes` ASC";
			$queryAlumnos="SELECT `RUT`, `Nombres`, `Apellidos`,  `Mail mama`, `Nombre mama`, `Year` FROM $tbl_usuarios WHERE `RUT` != '".$rutProfesor."' AND `Year` = $year ORDER BY  Grupo ASC, Apellidos ASC";
			$controlesSolo = mysql_fetch_all(mysql_query("SELECT Mes FROM $tbl_pagos WHERE `Year` = $year GROUP BY idMes"));
		}
		$nControles = count($controlesSolo);
		$pagos = mysql_fetch_all(mysql_query($query));
		$alumnos = mysql_fetch_all(mysql_query($queryAlumnos));
		//echo count($fechas);
		$row = 0;
		$meses = array("Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre");
		
		echo "<h1 id='tituloHorario'>Resultados de los pagos <a href='#' class='yearClick'>(". $year .")</a></h1>";
		
		//////// IMPRIMIR TABLA ////////
		echo "<div class='tableGeneric' id='datosControles'>";
			if($year == $thisYear){
				echo "<div class='linea'>";
					echo "<button id='botonEditarPagos' class='boton'>Editar Pagos</button>";
					echo "<button id='botonEnviarMailPagos' class='boton'>Mail por no pago</button>";
				echo "</div>";	
			}
			echo "<div class='tablesWrapper'>";
				// Primeras columnas estaticas (fixed)
				echo "<table class='noEditando tablaControles tablaAlumnosControles tablaPagados' cellspacing='3' cellpadding='5'>";
					titulosTabla();
					for($i = 0; $i < count($alumnos); $i++) {
						$row++;
						create_table($alumnos[$i]);
					}				
				echo "</table>";
			echo "</div>";
			echo "<div class='spacer'></div>";
		echo "</div>";
		//////// TABLA IMPRESA ////////
		
		function titulosTabla(){
			global $nControles, $meses, $pagos;
			echo "<tr>";
				echo "<th nowrap>N°</th>";
			    echo "<th class='freezeLeft'>Alumno</th>";
			    for($i = 0; $i < $nControles; $i++) {
					echo "<th><input class='mesElegidoPago' type='radio' name='mesElegido'><span class='mes'>". $meses[$i] ."</span></th>";
				}
		    echo "</tr>";
		}
		
		function create_table($dataArr) {
			// LLega con fila de lista de alumnos
			global $row, $alumnos, $asistencia, $fechas, $pagos, $nControles;
		    echo "<tr>";
			    echo "<th class='numero'>$row</th>";
			    // Primera columna
			    echo "<td align='left' class='freezeLeft'>";
				    if($dataArr[2] == "" && $dataArr[1] != ""){
				    	echo  ucwords($dataArr[1]);
				    }
				    else if($dataArr[2] == "" && $dataArr[1] == ""){
				    	echo ucwords($dataArr[0]);
				    }
				    else{
				    	echo ucwords($dataArr[2]) .", ". ucwords($dataArr[1]);
				    }
			    echo "</td>";
			    // Pagos
			    for($i = 0; $i < $nControles; $i++) {
					// Buscar en pagos ese fecha para cada alumno
					$celda = "<td class='squareBase'><div class='celdaGris circleBase' data-mes='". ($i+1) ."' data-rut='". $dataArr[0] ."' data-mailmama='". $dataArr[3] ."'  data-nombremama='". $dataArr[4] ."'><span class='noMostrar'>+</span></div></td>";
					for($j = 0; $j < count($pagos); $j++) {
						// Encontrar mismo idMes y mismo rut
						if( ($i + 1) == $pagos[$j][1] && $dataArr[0] == $pagos[$j][0]){
							if($pagos[$j][2] == "si"){
								// Ver si tiene algún comentario tooltip
								if($pagos[$j][3] != ""){
									$celda = "<td><div class='celdaVerde circleBase' data-mes='". ($i+1) ."' data-rut='". $dataArr[0] ."' data-mailmama='". $dataArr[3] ."'  data-nombremama='". $dataArr[4] ."'><span class='mostrar' data-tooltip='". $pagos[$j][3] ."'>!</span></div></td>";
								}
								else{
									$celda = "<td><div class='celdaVerde circleBase' data-mes='". ($i+1) ."' data-rut='". $dataArr[0] ."' data-mailmama='". $dataArr[3] ."'  data-nombremama='". $dataArr[4] ."'><span class='noMostrar'>+</span></div></td>";
								}
							}
							else if($pagos[$j][2] == "no"){
								// Ver si tiene algún comentario tooltip
								if($pagos[$j][3] != ""){
									$celda = "<td><div class='celdaRoja circleBase' data-mes='". ($i+1) ."' data-rut='". $dataArr[0] ."' data-mailmama='". $dataArr[3] ."'  data-nombremama='". $dataArr[4] ."'><span class='mostrar' data-tooltip='". $pagos[$j][3] ."'>!</span></div></td>";
								}
								else{
									$celda = "<td><div class='celdaRoja circleBase' data-mes='". ($i+1) ."' data-rut='". $dataArr[0] ."' data-mailmama='". $dataArr[3] ."'  data-nombremama='". $dataArr[4] ."'><span class='noMostrar'>+</span></div></td>";
								}
							}
							// No tiene pago o no-pago pero puede tener tooltip igual
							else if($pagos[$j][3] != ""){
								$celda = "<td class='squareBase'><div class='celdaGris circleBase' data-mes='". ($i+1) ."' data-rut='". $dataArr[0] ."' data-mailmama='". $dataArr[3] ."'  data-nombremama='". $dataArr[4] ."'><span class='mostrar' data-tooltip='". $pagos[$j][3] ."'>!</span></div></td>";
							}
						}
					}
					echo $celda;
				}
		    echo "</tr>";
		}
	?>	
	</div>  

</body>
<script>
$(document).ready(function(){
	var optionsReload = {override: false, buttons:{confirm:{text: 'Aceptar',action:function(){ location.reload(true); }},}};
	var optionsSimple ={override: false,buttons:{confirm:{text: 'Aceptar',action:function(){ Apprise('close'); }},}};
	var editando = false;
	var editandoPagos = false;
	var editandoTooltips = false;
	var editandoMesElegido = false;
	var cambios = new Array();
	$.fn.tooltipster('setDefaults', {
		animation: 'fade',
		touchDevices: true,
		trigger: 'hover',
		interactive: true,
		contentAsHTML: true
	});
	$(".mostrar").tooltipster();
	// Agregar contenido a los tooltip
	$(".mostrar").each(function(){
		$(this).tooltipster('content', '<span class="tooltipLink" data-rut="'+ $(this).parent().data("rut") +'" data-mes="'+ $(this).parent().data("mes") +'">'+ $(this).data("tooltip") +'</span>');
	});
	$('.noMostrar').tooltipster();
    // Agregar contenido a estos otros tooltip
	$(".noMostrar").each(function(){
		$(this).tooltipster('content', '<span class="tooltipLink"  data-rut="'+ $(this).parent().data("rut") +'" data-mes="'+ $(this).parent().data("mes") +'">Ingresar comentario</span>');
	});
	
	// PODER EDITAR PAGOS SOLO CUANDO SE ESTA EDITANDO
	$('.circleBase').click(function(e){
		if(!editandoTooltips){
			if(!editandoPagos){
				$('#botonEditarPagos').addClass("wobble");
				var wait = window.setTimeout( function(){
					$('#botonEditarPagos').removeClass("wobble")},
					1300
				);	
			}
			else{
				var fila;
				if($(this).hasClass("celdaVerde")){
					$(this).removeClass("celdaVerde").addClass("celdaRoja");
					fila = [$(this).data("mes"), $(this).data("rut"), "no"];
				}
				else if($(this).hasClass("celdaRoja")){
					$(this).removeClass("celdaRoja").addClass("celdaGris");
					fila = [$(this).data("mes"), $(this).data("rut"),""];
				}
				else{
					$(this).removeClass("celdaGris").addClass("celdaVerde");
					fila = [$(this).data("mes"), $(this).data("rut"),"si"];
				}
				// Borrar elemento en arreglo con este rut y fecha			
				for(var i = 0; i < cambios.length; i++){
					if(cambios[i][0] == fila[0] && cambios[i][1] == fila[1]){
						cambios.splice(i,1);
						break;
					}
				}
				cambios.push(fila);
			}
		}
	});

	// EDITAR Y GUARDAR PAGOS
	$("#botonEditarPagos").click(function(){
		if(!editandoPagos){
			$('.noMostrar').fadeOut();
			editandoTooltips = false;
			editandoPagos = true;
			// Cambiar colores
			$("#tablaAsistencia").removeClass("noEditando").addClass("editando");
			// Cambiar texto de boton			
			$("#botonEditarTooltips").text("Editar Comentarios");
			$("#botonEditarPagos").text("Guardar Pagos");
		}
		else{
			$.ajax({
				url: 'includes/guardarCambiosPagos.php',
				type: 'POST',
				data: ({filas: cambios}),
				success: function(data){
					if(data == 'ok'){
						Apprise("Cambios guardados con éxito", optionsReload);
					}
					else{
						Apprise("'Ocurrió un error, este fue el mensaje de error:</br>'"+ data, optionsReload);
					}
				}
			});
		}
	});

	// MOSTRAR RADIO BUTTON PARA ELEGIR MES
	$('#botonEnviarMailPagos').click(function(){
		if(editandoMesElegido) {
			// SOLO SI HA ELEGIDO NINGÚN MES SEGUIR
			if($("input[name='mesElegido']:checked").val()){
				// ALERTAR LOS QUE NO TIENE MAIL REGISTRADO LA MAMÁ
				//indice del mes
				var indice = $("input[name='mesElegido']:checked").parent().index();
				//encontrar cantidad de alumnos sin mail de mama
				var sinMail = "";
				var conMail = [];
				var result = <?php echo json_encode($alumnos); ?>;
				$('.tablaPagados tr').each(function(){
					$div = $(this).find('td:eq('+ indice +') div');
					// Si la mamá no tiene mail resgistrado y no ha pagado alertar a profesor
					if($div.data('mailmama') == ""){
						jQuery.each(result , function(index, value){
							if(value.RUT == $div.data('rut') && value.Nombres != '' && $div.hasClass("celdaRoja")){
								sinMail += '- '+ (value.Nombres +' '+ value.Apellidos) + "<br>";
							}
						});		
					}
					// Guardar información para mandar mail si no ha pagado
					else {
						// Si no ha pagado
						if($div.hasClass("celdaRoja")) {
							// Buscar nombre del alumno
							var nombre = "";
							jQuery.each(result , function(index, value){
								if(value.RUT == $div.data('rut') && value.Nombres != ''){
									nombre = value.Nombres;
								}
							});	
							// Agregar al array
							conMail.push({
								rut: $div.data('rut'),
								nombre: nombre,
								mailMama: $div.data('mailmama'),
								nombreMama: $div.data('nombremama')
							});
						}	
					}
				});
				//alertar de una cuales faltan
				var options = { 
					buttons: { 
						confirm: { 
							text: 'Enviar', 
							action: function() { 
								Apprise('close'); 
								$.ajax({
									url: 'includes/mailPorPagosPendientes.php',
									type: 'POST',
									data: ({
										conMail: JSON.stringify(conMail),
										mesPagos: $("input[name='mesElegido']:checked").parent().children('span').text()
									}),
									success: function(data){
										Apprise(data, optionsReload);
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
					input: false,
				};
				var mensaje = "¿Envair email por no pago del mes de <b>"+ $("input[name='mesElegido']:checked").parent().children('span').text() +"</b>?";
				if(sinMail.length > 0) {
					mensaje = "Las mamás de los siguientes alumnos no tienen su email registrado:<br><br>"+ sinMail +"<br>Enviar de todas formas?"
				}
				Apprise(mensaje, options);
				//opcion de enviar a restantes o cancelar
			}
			else {
				Apprise("Debe seleccionar un mes", optionsSimple);
			}
		}
		else {
			editandoMesElegido = true;
			$('.mesElegidoPago').fadeIn();
			$(this).text("Enviar mail");
		}	
	});


	function getNombreConRut(rut){
		
	}


	// EDITAR Y GUARDAR TOOLTIPS
	$("#botonEditarTooltips").click(function(){
		if(!editandoTooltips){
			$('.noMostrar').fadeIn();
			editandoPagos = false;
			editandoTooltips = true;
			// Cambiar colores
			$("#tablaAsistencia").removeClass("noEditando").addClass("editando");
			// Cambiar texto de botones
			$("#botonEditarPagos").text("Editar Pagos");			
			$("#botonEditarTooltips").text("Comentarios listos");
		}
		else{
			$('.noMostrar').fadeOut();
			editandoTooltips = false;
			editandoPagos = true;
			// Cambiar colores
			$("#tablaAsistencia").removeClass("editando").addClass("noEditando");
			// Cambiar texto de boton			
			$("#botonEditarTooltips").text("Editar Comentarios");
			$("#botonEditarPagos").text("Editar Pagos");
		}
	});
	
	// GUARDAR CAMBIOS EN TOOLTIPS DE A UNO CON AJAX
	$("body").on("click", '.tooltipLink', function(e){
		editarTooltip($(this));	
	});
	
	function editarTooltip(esto){
		var rut = esto.data("rut");
		var mes = esto.data("mes");
		Apprise('Ingrese comentario', { 
			buttons: { 
				confirm: { 
					text: 'Guardar', 
					action: function(comentario) {
						Apprise('close');
						if(comentario.input != ""){
							$.ajax({
								url: 'includes/guardarTooltip.php',
								type: 'POST',
								data: ({
									comentario: comentario.input,
									rut: rut,
									mes: mes
								}),
								success: function(data){
									if(data == "ok"){
										Apprise( "Cambios guardados con éxito", optionsReload );
									}
									else{
										Apprise("Hubo un error:</br>"+ data, {
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
								}
							});
						}
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
	}
});
</script>
</html>