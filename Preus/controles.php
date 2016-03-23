<?php include_once('includes/isAdmin.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Controles</title>
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
			$query="SELECT $tbl_controles.Rut, $tbl_controles.Control, $tbl_controles.Nota, $tbl_usuarios.Nombres, $tbl_usuarios.Apellidos FROM $tbl_controles INNER JOIN $tbl_usuarios ON $tbl_usuarios.RUT = $tbl_controles.Rut WHERE $tbl_controles.Rut != '".$rutProfesor."' AND ($tbl_controles.Year = $year OR $tbl_controles.Year IS NULL) ORDER BY $tbl_controles.Rut ASC, $tbl_controles.Control ASC";
			$queryAlumnos="SELECT `RUT`, `Nombres`, `Apellidos` FROM $tbl_usuarios WHERE `RUT` != '".$rutProfesor."' AND (`Year` = $year OR `Year` IS NULL) ORDER BY  Grupo ASC, Apellidos ASC";
			$controlesSolo = mysql_fetch_all(mysql_query("SELECT Control FROM $tbl_controles WHERE (`Year` = $year OR `Year` IS NULL)  GROUP BY Control"));
		}
		else{
			$query="SELECT $tbl_controles.Rut, $tbl_controles.Control, $tbl_controles.Nota, $tbl_usuarios.Nombres, $tbl_usuarios.Apellidos FROM $tbl_controles INNER JOIN $tbl_usuarios ON $tbl_usuarios.RUT = $tbl_controles.Rut WHERE $tbl_controles.Rut != '".$rutProfesor."' AND $tbl_controles.Year = $year ORDER BY $tbl_controles.Rut ASC, $tbl_controles.Control ASC";
			$queryAlumnos="SELECT `RUT`, `Nombres`, `Apellidos`, `Year` FROM $tbl_usuarios WHERE `RUT` != '".$rutProfesor."' AND `Year` = $year ORDER BY  Grupo ASC, Apellidos ASC";
			$controlesSolo = mysql_fetch_all(mysql_query("SELECT Control FROM $tbl_controles WHERE `Year` = $year  GROUP BY Control"));
		}
		$nControles = count($controlesSolo);
		$controles = mysql_fetch_all(mysql_query($query));
		$alumnos = mysql_fetch_all(mysql_query($queryAlumnos));
		//echo count($controles);
		$row = 0;
		
		
		echo "<h1 id='tituloHorario'>Resultados de los controles <a href='#' class='yearClick'>(". $year .")</a></h1>";
		
		//////// IMPRIMIR TABLA ////////
		echo "<div class='tableGeneric' id='datosControles'>";
			if($year == $thisYear){
				echo "<div class='linea'>";
					echo "<button id='botonEditarControles' class='boton'>Editar Notas</button>";
				echo "</div>";	
			}
			echo "<div class='tablesWrapper'>";
				// Primeras columnas estaticas (fixed)
				echo "<table class='noEditando tablaControles tablaAlumnosControles tablaNotas flechas tablaPorcentajeControles' cellspacing='3' cellpadding='5'>";
					titulosTabla();
					for($i = 0; $i < count($alumnos); $i++) {
						$row++;
						create_table();
					}				
				echo "</table>";
			echo "</div>";
			echo "<div class='spacer'></div>";
		echo "</div>";
		//////// TABLA IMPRESA ////////
		
		function titulosTabla(){
			global $nControles;
			echo "<tr>";
				echo "<th nowrap>N°</th>";
			    echo "<th class='freezeLeft'>Alumno</th>";
			    for($i = 0; $i < 26; $i++) {
					echo "<th><span class='control'>". ($i+1) ."</span></th>";
				}
				echo "<th></th>";
				echo "<th class='freezeRight'>Puntaje</th>";
		    echo "</tr>";
		}		
		
		function create_table() {
			// LLega con fila de lista de alumnos
			global $row, $alumnos, $asistencia, $fechas, $controles, $nControles;
			$indiceAlumno = ($row-1)*$nControles;
		    echo "<tr>";
			    echo "<th class='numero'>$row</th>";
			    // Primera columna
			    echo "<td align='left' class='freezeLeft'>";
				    if($controles[$indiceAlumno][4] == "" && $controles[$indiceAlumno][3] != ""){
				    	echo  ucwords($controles[$indiceAlumno][3]);
				    }
				    else if($controles[$indiceAlumno][4] == "" && $controles[$indiceAlumno][3] == ""){
				    	echo ucwords($controles[$indiceAlumno][0]);
				    }
				    else{
				    	echo ucwords($controles[$indiceAlumno][4]) .", ". ucwords($controles[$indiceAlumno][3]);
				    }
			    echo "</td>";
			    // Controles
			    $celda = "";
			    for($i = 0; $i < $nControles; $i++) {
			    	// El query ya asegura orden ascendente de Rut y luego n° control
			    	$indiceListadoTotal = ($row-1)*$nControles + $i;
			    	// Revisar si nota del control es null
			    	if($controles[$indiceListadoTotal][2] != 'NULL' && $controles[$indiceListadoTotal][2] != '') {
			    		$celda = "<td class='editable'><div class='conControl' data-rut='". $controles[$indiceAlumno][0] ."' data-control='". ($i+1) ."'>". $controles[$indiceListadoTotal][2] ."</div></td>";
			    	}
			    	else {
			    		$celda = "<td class='editable'><div data-rut='". $controles[$indiceAlumno][0] ."' data-control='". ($i+1) ."'></div></td>";
			    	}

			    	echo $celda;
			    	/*
					$celdaAux = "<td class='editable'><div data-rut='". $dataArr[0] ."' data-control='". ($i+1) ."'></div></td>";
					// Buscar nota en el control $i
					// Encontrar mismo control y mismo rut y control != null
					for($j = 0; $j < count($controles); $j++) {
						if(($i+1) == $controles[$j][1] && $dataArr[0] == $controles[$j][0] && $controles[$j][2] != 'NULL' && $controles[$j][2] != ''){
							$celdaAux = "<td class='editable'><div class='conControl' data-rut='". $dataArr[0] ."' data-control='". ($i+1) ."'>". $controles[$j][2] ."</div></td>";
							break;
						}
					}
					
					
					$celda .= $celdaAux;*/
				}
				//echo $celda;
				echo "<td></td>";
				echo "<td class='puntajeControles freezeRight'></td>";

		    echo "</tr>";
		}
	?>	
	</div>  

</body>
<script>
$(document).ready(function(){
	puntaje();
	var optionsReload = {override: false, buttons:{confirm:{text: 'Aceptar',action:function(){ location.reload(true); }},}};
	var optionsSimple ={override: false,buttons:{confirm:{text: 'Aceptar',action:function(){ Apprise('close'); }},}};
	var editando = false;
	var cambios = new Array();
	
	// Puntaje de controles por alumno
	function puntaje(){
		
		$(".tablaPorcentajeControles tr").each(function(){
			var i = $(this).index();
			var row = $('.tablaNotas').find('tr').eq(i);
			// Contar total de controles y suma de controles
			var totalControles = row.find(".conControl").length;
			// y = 70x + 150
			var sumaControles = totalControles*150;
			row.find(".conControl").each(function(){
				sumaControles += 70*parseInt($(this).text());
			});
			if(sumaControles != 150){
				$(this).find(".puntajeControles").text(Math.round(sumaControles/totalControles));	
			}	
		});	
	}

	// REGISTRAR CELDAS CON INPUT QUE TIENEN FOCUS EN ALGUN MOMENTO
	$('.editable').on('change', 'input', function(){
		$(this).parent().addClass('focused');
	});

	// EDITAR Y GUARDAR ASISTENCIA
	$("#botonEditarControles").click(function(){
		if(!editando){
			editando = true;
			
			$(".editable").each(function(i){
				var celda = $(this);
				var anchoCelda = celda.width();
				var texto = $(this).children('div').text();
				var dataRut = celda.find("div").data("rut");
				var dataControl = celda.find("div").data("control");
				celda.text('');
				celda.append('<input type="text" data-rut="'+ dataRut +'" data-control="'+ dataControl +'">');
				celda.find('input').css('width', anchoCelda-4 + 'px').val(texto);	
			});
			
			// Cambiar colores
			$(".tablaControles").removeClass("noEditando").addClass("editando");
			// Cambiar texto de boton
			$("#botonEditarControles").text("Guardar Cambios");
		}
		else{
			var celdas = new Array();
			$(".tablaNotas").find('.focused').each(function(){
				var celda = [$(this).find("input").data("rut"), $(this).find("input").data("control"), $(this).find('input').val()];
				celdas.push(celda);
			});

			//console.log(celdas);
			//console.log($('.yearClick').text().replace("(","").replace(")",""));
			
			$.ajax({
				url: 'includes/guardarCambiosControles.php',
				type: 'POST',
				data: ({
					celdas: celdas, 
					year: $('.yearClick').text().replace("(","").replace(")","")
				}),
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
</script>
</html>