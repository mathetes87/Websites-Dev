<?php include_once('includes/isAdmin.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Asistencia</title>
<link href="css.css" rel="stylesheet" type="text/css" />
<script src="jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="calendar/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="calendar/jquery.ui.datepicker-es.js"></script>
<link rel="stylesheet" href="calendar/css/jquery-ui-1.10.3.custom.min.css" type="text/css" /> 

<script type="text/javascript" src="js.js"></script>
<link rel="stylesheet" href="/fancybox/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
<script type="text/javascript" src="/fancybox/jquery.fancybox.pack.js?v=2.1.4"></script>
<link href="forma.css" rel="stylesheet" type="text/css" /> 
<script type="text/javascript" src="apprise2/apprise2.js"></script>
<link rel="stylesheet" href="apprise2/apprise2.css" type="text/css" /> 
<link rel="stylesheet" href="animate-custom.css" type="text/css" /> 
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
			$query="SELECT $tbl_asistencia.Rut, $tbl_usuarios.Nombres, $tbl_usuarios.Apellidos, `Asistencia`, `Comentario`, `Fecha` FROM $tbl_asistencia LEFT JOIN $tbl_usuarios ON $tbl_usuarios.RUT = $tbl_asistencia.Rut WHERE $tbl_asistencia.Rut != '".$rutProfesor."' AND (year($tbl_asistencia.Fecha) = $year OR year($tbl_asistencia.Fecha) IS NULL) ORDER BY `Fecha` ASC";
			$queryAlumnos="SELECT `RUT`, `Nombres`, `Apellidos`, `Year` FROM $tbl_usuarios WHERE `RUT` != '".$rutProfesor."' AND (`Year` = $year OR `Year` IS NULL) ORDER BY  Grupo ASC, Apellidos ASC";
		}
		else{
			$query="SELECT $tbl_asistencia.Rut, $tbl_usuarios.Nombres, $tbl_usuarios.Apellidos, `Asistencia`, `Comentario`, `Fecha` FROM $tbl_asistencia LEFT JOIN $tbl_usuarios ON $tbl_usuarios.RUT = $tbl_asistencia.Rut WHERE $tbl_asistencia.Rut != '".$rutProfesor."' AND year($tbl_asistencia.Fecha) = $year ORDER BY `Fecha` DESC";
			$queryAlumnos="SELECT `RUT`, `Nombres`, `Apellidos`, `Year` FROM $tbl_usuarios WHERE `RUT` != '".$rutProfesor."' AND `Year` = $year ORDER BY  Grupo ASC, Apellidos ASC";
		}
		$queryFechas = "SELECT day(Fecha), month(Fecha), year(Fecha), Fecha FROM asistencia WHERE year(Fecha) = $year GROUP BY `Fecha` ORDER BY `Fecha` ASC";
		$asistencia = mysql_fetch_all(mysql_query($query));
		$fechas = mysql_fetch_all(mysql_query($queryFechas));
		$alumnos = mysql_fetch_all(mysql_query($queryAlumnos));
		//echo count($fechas);
		$row = 0;
		
		echo "<h1 id='tituloHorario'>Asistencia de los alumnos <a href='#' class='yearClick'>(". $year .")</a></h1>";
		
		//////// IMPRIMIR TABLA ////////
		echo "<div id='datosAsistencia'>";
			if($year == $thisYear){
				echo "<div class='linea'>";
					echo "<button id='botonEditarAsistencia' class='boton'>Editar Asistencia</button>";
					echo "<button id='botonNuevaFecha' class='boton modalbox' href='#fancyCalendario'>Nueva Fecha</button>";
				echo "</div>";	
			}
			echo "<div class='tablesWrapper'>";
				// Primeras columnas estaticas (fixed)
				echo "<table class='noEditando tablaAsistencia tablaAlumnosAsistencia' cellspacing='3' cellpadding='5'>";
					titulosTabla1();
					for($i = 0; $i < count($alumnos); $i++) {
						$row++;
						create_table_1($alumnos[$i]);
					}				
				echo "</table>";
				// Columnas con fechas
				echo "<div id='double-scroll' class='scrollx'>";
					echo "<table class='noEditando tablaAsistencia tablaFechas' cellspacing='3' cellpadding='5'>";
						titulosTabla2();
						for($i = 0; $i < count($alumnos); $i++) {
							$row++;
							create_table_2($alumnos[$i]);
						}				
					echo "</table>";
				echo "</div>";	
				// Columna con porcentajes de asistencia
				echo "<table class='noEditando tablaAsistencia tablaPorcentajeAsistencia' cellspacing='3' cellpadding='5'>";
					titulosTabla3();
					for($i = 0; $i < count($alumnos); $i++) {
						$row++;
						create_table_3($alumnos[$i]);
					}				
				echo "</table>";
			echo "</div>";
			//echo "<div class='spacer'></div>";
		echo "</div>";
		//////// TABLA IMPRESA ////////
		
		// Para agregar nueva fecha con fancybox
		echo "<div id='fancyCalendario'>
			<h3>Elija la nueva fecha</h3>
			<div id='calendario'></div>
			<button id='agregarFecha' class='boton'>Agregar</button>
		</div>";
		
		
		function titulosTabla1(){
			echo "<tr>";
				echo "<th nowrap>N°</th>";
			    echo "<th class='freezeLeft'>Alumno</th>";
		    echo "</tr>";
		}
		function titulosTabla2(){
			global $fechas;
			echo "<tr>";
			    for($i = 0; $i < count($fechas); $i++) {
					echo "<th><span class='fecha'>". $fechas[$i][0] ."/". $fechas[$i][1] ."</span><img src='images/delete.png' class='deleteAsistenciaImg' data-fecha='". $fechas[$i][3] ."'></th>";
				}
		    echo "</tr>";
		}
		function titulosTabla3(){
			echo "<tr>";
				echo "<th></th>";
				echo "<th class='freezeRight'>Total</th>";
		    echo "</tr>";
		}
		
		
		function create_table_1($dataArr) {
			// LLega con fila de lista de alumnos
			global $row, $alumnos, $asistencia, $fechas;
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
		    echo "</tr>";
		}
		function create_table_2($dataArr) {
			// LLega con fila de lista de alumnos
			global $row, $alumnos, $asistencia, $fechas;
		    echo "<tr>";
			    // Asistencias
			    // Recorrer todas las fechas
			    for($i = 0; $i < count($fechas); $i++) {
					// Buscar en asistencias esa fecha para cada alumno
					$celda = "<td><div class='celdaGris circleBase' data-fecha='". $fechas[$i][3] ."' data-rut='". $dataArr[0] ."'></div></td>";
					for($j = 0; $j < count($asistencia); $j++) {
						// Encontrar misma fecha y mismo rut
						if($fechas[$i][3] == $asistencia[$j][5] && $dataArr[0] == $asistencia[$j][0]){
							if($asistencia[$j][3] == "si"){
								$celda = "<td><div class='celdaVerde circleBase' data-fecha='". $fechas[$i][3] ."' data-rut='". $dataArr[0] ."'></div></td>";
							}
							else if($asistencia[$j][3] == "no"){
								$celda = "<td><div class='celdaRoja circleBase' data-fecha='". $fechas[$i][3] ."' data-rut='". $dataArr[0] ."'></div></td>";
							}
						}
					}
					echo $celda;
				}
		    echo "</tr>";
		}
		function create_table_3($dataArr) {
			// LLega con fila de lista de alumnos
			global $row, $alumnos, $asistencia, $fechas;
		    echo "<tr>";
				echo "<td></td>";
				echo "<td class='porcentaje freezeRight'></td>";
		    echo "</tr>";
		}
	?>	
	</div>  

</body>
<script>
$(document).ready(function(){
	porcentaje();
	windowResize();
	var optionsReload = {override: false, buttons:{confirm:{text: 'Aceptar',action:function(){ location.reload(true); }},}};
	var optionsSimple ={override: false,buttons:{confirm:{text: 'Aceptar',action:function(){ Apprise('close'); }},}};
	var editando = false;
	var cambios = new Array();
	$("#calendario").datepicker({
		inline: true,
		dateFormat: "yy-mm-dd",
		altFormat: "yy-mm-dd",
		regional: "es"
	});
	
	// Llamar a doublescroll
	$('#double-scroll').doubleScroll();
	
	// Width de tabla con fechas
	$(window).resize(function() {
	    windowResize();
	});
	
	function windowResize(){
		var tabla1 = $('.tablaAlumnosAsistencia').outerWidth();
		var tabla3 = $('.tablaPorcentajeAsistencia').outerWidth();
		var body = $('body').width();
		var ancho = body - tabla1 - tabla3;
		if(ancho > $('.tablaFechas tbody').outerWidth() + 2){
			ancho = $('.tablaFechas tbody').outerWidth() + 2;
		}
		$('.scrollx').width(ancho);
	}
	
	// Agregar fecha
	$("#agregarFecha").click(function(){
		$.fancybox.close();
		$.ajax({
			url: 'includes/crearFechaAsistencia.php',
			type: 'POST',
			data: ({fecha: $("#calendario").datepicker().val()}),
			success: function(data){
				if(data == 'ok'){
					location.reload(true);
				}
				else if(data == 'existe'){
					Apprise("Esa fecha ya fue ingresada anteriormente", optionsSimple);
				}
				else{
					Apprise("'Ocurrió un error, este fue el mensaje de error:</br>'"+ data, optionsReload);
				}
			}
		});
	});
	
	// Borrar fecha
	$('.deleteAsistenciaImg').click(function(){
		var fecha = $(this).data("fecha");
		var options = { 
				buttons: { 
					confirm: { 
						text: 'Eliminar', 
						action: function() { 
							Apprise('close'); 
							$.ajax({
								url: 'includes/borrarFechaAsistencia.php',
								type: 'POST',
								data: ({fecha: fecha}),
								success: function(data){
									if(data == 'borrado'){
										location.reload(true);
									}
									else{
										Apprise("'Ocurrió un error, este fue el mensaje de error:</br>'"+ data, optionsReload);
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
				input: false,
				override: false
			};
			Apprise("¿Eliminar la fecha <b>"+ fecha +"</b> junto con todas sus asistencias asociadas?", options);	
	});
	
	// Porcentaje de asistencia por alumno
	function porcentaje(){
		$(".tablaPorcentajeAsistencia tr").each(function(){
			var i = $(this).index();
			var row = $('.tablaFechas').find('tr').eq(i);
			// Contar verdes y rojas
			var verdes = row.find(".celdaVerde").length;
			var rojas = row.find(".celdaRoja").length;
			var total = verdes + rojas;
			if(total > 0){
				$(this).find(".porcentaje").text(Math.round((verdes/total)*100) +"%");
			}
			else{
				$(this).find(".porcentaje").text("---");
			}	
		});	
	}
	
	// PODER EDITAR ASISTENCIA SOLO CUANDO SE ESTA EDITANDO
	$('.circleBase').click(function(e){
		if(!editando){
			$('#botonEditarAsistencia').addClass("wobble");
			var wait = window.setTimeout( function(){
				$('#botonEditarAsistencia').removeClass("wobble")},
				1300
			);	
		}
		else{
			var fila;
			if($(this).hasClass("celdaVerde")){
				$(this).removeClass("celdaVerde").addClass("celdaRoja");
				fila = [$(this).data("fecha"), $(this).data("rut"), "no"];
			}
			else if($(this).hasClass("celdaRoja")){
				$(this).removeClass("celdaRoja").addClass("celdaGris");
				fila = [$(this).data("fecha"), $(this).data("rut"),""];
			}
			else{
				$(this).removeClass("celdaGris").addClass("celdaVerde");
				fila = [$(this).data("fecha"), $(this).data("rut"),"si"];
			}
			// Borrar elemento en arreglo con este rut y fecha			
			for(var i = 0; i < cambios.length; i++){
				if(cambios[i][0] == fila[0] && cambios[i][1] == fila[1]){
					cambios.splice(i,1);
					break;
				}
			}
			cambios.push(fila);
			porcentaje();
		}
	});

	// EDITAR Y GUARDAR ASISTENCIA
	$("#botonEditarAsistencia").click(function(){
		if(!editando){
			editando = true;
			// Cambiar colores
			$("#tablaAsistencia").removeClass("noEditando").addClass("editando");
			// Agregar cruces para borrar
			$('.deleteAsistenciaImg').fadeIn();
			// Cambiar altura de primera fila en las 3 tablas
			var altura = $('.deleteAsistenciaImg').parent().outerHeight();
			$('.tablaAsistencia tr:first-child').height(altura);
			// Cambiar texto de boton
			$("#botonEditarAsistencia").text("Guardar Cambios");
		}
		else{
			$.ajax({
				url: 'includes/guardarCambiosAsistencia.php',
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
});

// Double Scroll plugin

(function($){
    $.widget("suwala.doubleScroll", {
		options: {
            contentElement: undefined, // Widest element, if not specified first child element will be used
			topScrollBarMarkup: '<div class="suwala-doubleScroll-scroll-wrapper" style="height: 20px;"><div class="suwala-doubleScroll-scroll" style="height: 20px;"></div></div>',
			topScrollBarInnerSelector: '.suwala-doubleScroll-scroll',			
			scrollCss: {                
				'overflow-x': 'scroll',
				'overflow-y':'hidden'
            },
			contentCss: {
				'overflow-x': 'scroll',
				'overflow-y':'hidden'
			}
        },		
        _create : function() {
            var self = this;
			var contentElement;

            // add div that will act as an upper scroll
			var topScrollBar = $($(self.options.topScrollBarMarkup));
            self.element.before(topScrollBar);

            // find the content element (should be the widest one)			
            if (self.options.contentElement !== undefined && self.element.find(self.options.contentElement).length !== 0) {
                contentElement = self.element.find(self.options.contentElement);
            }
            else {
                contentElement = self.element.find('>:first-child');
            }

            // bind upper scroll to bottom scroll
            topScrollBar.scroll(function(){
                self.element.scrollLeft(topScrollBar.scrollLeft());
            });

            // bind bottom scroll to upper scroll
            self.element.scroll(function(){
                topScrollBar.scrollLeft(self.element.scrollLeft());
            });

            // apply css
            topScrollBar.css(self.options.scrollCss);
            self.element.css(self.options.contentCss);

            // set the width of the wrappers
            $(self.options.topScrollBarInnerSelector, topScrollBar).width(contentElement.outerWidth());
            topScrollBar.width(self.element.width());
        }
    });
})(jQuery);
</script>
</html>