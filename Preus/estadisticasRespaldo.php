<?php include_once('includes/isAdmin.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Gráficos</title>
	<link href="css.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="jquery.min.js"></script>
	<script type="text/javascript" src="js.js"></script>
	<script type="text/javascript" src="jquery.flot.js"></script>
	<script type="text/javascript" src="jquery.flot.resize.js"></script>
	<script type="text/javascript" src="jquery.flot.canvas.js"></script>
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="excanvas.min.js"></script><![endif]-->
	<link rel="stylesheet" href="/fancybox/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
	<script type="text/javascript" src="/fancybox/jquery.fancybox.pack.js?v=2.1.4"></script>
	<link href="forma.css" rel="stylesheet" type="text/css" /> 
	<script type="text/javascript" src="apprise2/apprise2.js"></script>
	<link rel="stylesheet" href="apprise2/apprise2.css" type="text/css" />
</head>
<body> 
    <?php
        include_once 'includes/menuAdmin.php';
 		include_once 'includes/botonLogout.php';
        include_once 'includes/mysql_fetch_all.php';
       	include_once 'includes/conectarDB.php';
		include_once 'includes/infoProfesor.php';
		// $tbl_usuarios="usuarios"; // Table name 1
		// $tbl_puntajes="puntajes"; // Table name 2
       	include_once 'includes/chooseYear.php';
       	@session_start();
       	$year = $_SESSION['year'];
       	$thisYear = date("Y");
       	
       	$queryPuntajes;
       	$queryAlumnos;
       	$querySoloAlumnos;
       	$queryTodosPuntajes;
       	$secciones = array("Contenido", "Conectores", "Plan Redacción", "Vocabulario Contextual", "Comprensión Lectora");
       	if($year == $thisYear){
       		//////// PARA LOS TITULOS EN LA TABLA HTML
			$queryPuntajes="SELECT Ensayo, AVG(NULLIF(Puntaje ,0)), COUNT(*) as Cantidad, AVG(NULLIF(Contenido ,0)), AVG(NULLIF(Conectores ,0)), AVG(NULLIF(`Plan de Redacción` ,0)), AVG(NULLIF(`Vocabulario Contextual` ,0)), AVG(NULLIF(`Comprensión Lectora` ,0))  FROM $tbl_puntajes WHERE Puntaje IS NOT NULL AND PUNTAJE != 0 AND PUNTAJE != '' AND (`Year` = $year OR `Year` IS NULL) AND RUT != '$rutProfesor' GROUP BY `ID Ensayo` ORDER BY `ID Ensayo`";
			
			//////// PARA LOS PUNTAJES DE TODOS LOS ALUMNOS Y SUS NOMBRES
			$queryAlumnos="SELECT RUT, `ID Ensayo` as idEnsayo, Ensayo, Puntaje, Demre, Contenido, Conectores, `Plan de Redacción` as Plan, `Vocabulario Contextual` as Vocabulario,  `Comprensión Lectora` as Comprension FROM $tbl_puntajes WHERE (`Year` = $year OR `Year` IS NULL) AND RUT != '$rutProfesor' ORDER BY `ID Ensayo`";
			
			//////// LISTADO DE TODOS LOS ALUMNOS, PARA DROP DOWN
			$querySoloAlumnos="SELECT RUT, Nombres, Apellidos FROM $tbl_usuarios WHERE (`Year` = $year OR `Year` IS NULL) AND RUT != '$rutProfesor' GROUP BY RUT ORDER BY Apellidos ASC";
			
			//////// PARA PROMEDIO POR ENSAYO Y SECCION DE TODOS LOS ALUMNOS 
			$queryTodosPuntajes="SELECT AVG(NULLIF(Puntaje ,0)) as Promedio, COUNT(*) as Cantidad, Ensayo, AVG(NULLIF(Contenido ,0)) as Cont, AVG(NULLIF(Conectores ,0)) as Cone, AVG(NULLIF(`Plan de Redacción` ,0)) as Plan, AVG(NULLIF(`Vocabulario Contextual` ,0)) as Voca, AVG(NULLIF(`Comprensión Lectora` ,0)) as Comp, Demre FROM $tbl_puntajes WHERE Puntaje IS NOT NULL AND PUNTAJE != 0 AND PUNTAJE != ''  AND (`Year` = $year OR `Year` IS NULL) AND RUT != '$rutProfesor' GROUP BY `ID Ensayo` ORDER BY `ID Ensayo`";
       	}
       	
       	else{
       		//////// PARA LOS TITULOS EN LA TABLA HTML
			$queryPuntajes="SELECT Ensayo, AVG(NULLIF(Puntaje ,0)), COUNT(*) as Cantidad, AVG(NULLIF(Contenido ,0)), AVG(NULLIF(Conectores ,0)), AVG(NULLIF(`Plan de Redacción` ,0)), AVG(NULLIF(`Vocabulario Contextual` ,0)), AVG(NULLIF(`Comprensión Lectora` ,0))  FROM $tbl_puntajes WHERE Puntaje IS NOT NULL AND PUNTAJE != 0 AND PUNTAJE != '' AND `Year` = $year AND RUT != '$rutProfesor' GROUP BY `ID Ensayo` ORDER BY `ID Ensayo`";
			
			//////// PARA LOS PUNTAJES DE TODOS LOS ALUMNOS Y SUS NOMBRES
			$queryAlumnos="SELECT RUT, `ID Ensayo` as idEnsayo, Ensayo, Puntaje, Demre, Contenido, Conectores, `Plan de Redacción` as Plan, `Vocabulario Contextual` as Vocabulario,  `Comprensión Lectora` as Comprension FROM $tbl_puntajes WHERE `Year` = $year AND RUT != '$rutProfesor' ORDER BY `ID Ensayo`";
			
			//////// LISTADO DE TODOS LOS ALUMNOS, PARA DROP DOWN
			$querySoloAlumnos="SELECT RUT, Nombres, Apellidos FROM $tbl_usuarios WHERE `Year` = $year AND RUT != '$rutProfesor' GROUP BY RUT ORDER BY Apellidos ASC";
			
			//////// PARA PROMEDIO POR ENSAYO Y SECCION DE TODOS LOS ALUMNOS 
			$queryTodosPuntajes="SELECT AVG(NULLIF(Puntaje ,0)) as Promedio, COUNT(*) as Cantidad, Ensayo, AVG(NULLIF(Contenido ,0)) as Cont, AVG(NULLIF(Conectores ,0)) as Cone, AVG(NULLIF(`Plan de Redacción` ,0)) as Plan, AVG(NULLIF(`Vocabulario Contextual` ,0)) as Voca, AVG(NULLIF(`Comprensión Lectora` ,0)) as Comp, Demre FROM $tbl_puntajes WHERE Puntaje IS NOT NULL AND PUNTAJE != 0 AND PUNTAJE != '' AND `Year` = $year AND RUT != '$rutProfesor' GROUP BY `ID Ensayo` ORDER BY `ID Ensayo`";
       	}

		//////// PARA LOS TITULOS EN LA TABLA HTML
		$resPuntajes = mysql_query($queryPuntajes);
		$allPuntajes = mysql_fetch_all($resPuntajes);
		
		//////// PARA LOS PUNTAJES DE TODOS LOS ALUMNOS Y SUS NOMBRES
		$resAlumnos = mysql_query($queryAlumnos);
		$listaPuntajes = mysql_fetch_all($resAlumnos);
		
		//////// LISTADO DE TODOS LOS ALUMNOS, PARA DROP DOWN
		$resSoloAlumnos = mysql_query($querySoloAlumnos);
		$listaSoloAlumnos = mysql_fetch_all($resSoloAlumnos);

		//////// PARA PROMEDIO POR ENSAYO Y SECCION DE TODOS LOS ALUMNOS
		$puntajesCurso = mysql_query($queryTodosPuntajes);
		$promedioCurso = mysql_fetch_all($puntajesCurso);
				
		// Estructura arreglo: [0] RUT, [1] Password, [2]Nombres, [3]Apellidos, [4]Mail, [5]Telefono, [6]Año Ingreso, [7]Grupo...
			
		echo "<h1 class='margenLeft'>Puntajes <a href='#' class='yearClick'>(". $year .")</a></h1>";
		echo "<h3>Éstos son los resultados de los ensayos realizados hasta ahora:</h3>";
		echo "<div id='containerGrafico'>";
			echo"<div id='grafico'></div>";
			
		echo "</div>";
		echo "<div id='choicesAdmin' style='display: ;'>";
				// Pasar con JSON todo lo que sigue
				$listado = array();
				$listadoAux;
					$listado[0] = "<div style='display: none' class='radio choiceParent'><input class='dropdown checkMultiple' type='checkbox' name='";
					$listado[1] = "' id='id";
					$listado[2] = "'></input>";
					$listado[3] = "<label for='id";
					$listado[4] = "'>";
					$listado[5] = "</label><input type='radio' name='tipo";
					$listado[6] = "' value='puntaje' checked='checked'>Ptj<input type='radio' name='tipo";
					$listado[7] = "' value='seccion' class='radioSec'>Sec
									<img class='delete' src='images/delete.png'></img>"; 
						$listadoAux .= "<br/><select class='dropdown' name='Alumnos'>";
							$listadoAux .= "<option value=''>Seleccionar</option>";
							    for($j = 0; $j < count($listaSoloAlumnos); $j++) {
							    	// Si no tiene Apellido registrado mostrar RUT
							    	if($listaSoloAlumnos[$j][2] == "" || $listaSoloAlumnos[$j][2] == null){
							    		$listadoAux .= '<option class="sinNombre" value="'. $listaSoloAlumnos[$j][0] .'">'. $listaSoloAlumnos[$j][0] .'</option>';	 
							    	}
							    	else{
							    		$listadoAux .= '<option class="conNombre" value="'. $listaSoloAlumnos[$j][2] .', '. $listaSoloAlumnos[$j][1] .'">'. $listaSoloAlumnos[$j][2] .', '. $listaSoloAlumnos[$j][1] .'</option>';	 
							    }
							}
						$listadoAux .= "</select>";
					$listadoAux .= "</input></div>";
					$listado[8] = $listadoAux;
		?>			
		<div id="choicesSegundaLinea"></div>			
		</div>
		
		<?php
		
		//////// IMPRIMIR TABLAS ////////		
		echo "<a id='mas' href='#tablaOculta'>Mostrar tablas</a><div class='spacer'></div>";
		echo "<span id='tablaOculta' style='display: none;'>";
		echo "<div id='tendenciasWrapper'>";
			echo "<table style='display:none' width='100%' class='data_table tendenciasTabla' cellspacing='3' cellpadding='5'>";
				
			titulosTablaTendencias();
			// TENDENCIA
			echo "<tr><th>Tendencia</th>";
				$y = array();
				$x = array();
				for($j = 0; $j < count($promedioCurso); $j++){
					if($promedioCurso[$j][0] > 0){
						array_push($y, floatval($promedioCurso[$j][0]));
						array_push($x, count($x)+1);
					}	
				}
				echo "<td>". tendencia($x, $y) ."</td>";
				
				for($i = 0; $i < count($secciones); $i++) {
					$y = array();
					$x = array();
					for($j = 0; $j < count($promedioCurso); $j++){
						if($promedioCurso[$j][$i+3] > 0){
							array_push($y, floatval($promedioCurso[$j][$i+3]));
							array_push($x, count($x)+1);
						}
					}
					echo "<td>". tendencia($x, $y) ."</td>";
					
				}
			echo "</tr>";
			// R2
			echo "<tr><th>R2</th>";
				$y = array();
				$x = array();
				for($j = 0; $j < count($promedioCurso); $j++){
					if($promedioCurso[$j][0] > 0){
						array_push($y, floatval($promedioCurso[$j][0]));
						array_push($x, count($x)+1);
					}	
				}
				echo "<td>". r2($x, $y) ."</td>";
				
				for($i = 0; $i < count($secciones); $i++) {
					$y = array();
					$x = array();
					for($j = 0; $j < count($promedioCurso); $j++){
						if($promedioCurso[$j][$i+3] > 0){
							array_push($y, floatval($promedioCurso[$j][$i+3]));
							array_push($x, count($x)+1);
						}
					}
					echo "<td>". r2($x, $y) ."</td>";
					
				}
			echo "</tr>";
			echo "<tr class='spacer'></tr>";				
			echo "</table>";
		echo "</div>";
		echo "<div id='datosUsuario'>";
			echo "<table width='100%' class='data_table puntajes' cellspacing='3' cellpadding='5'>";
				
			titulosTabla();
			for($i = 0; $i < count($allPuntajes); $i++) {
				create_table($allPuntajes[$i]);
			}
			echo "<tr class='spacer'></tr>";				
			echo "</table>";
		echo "</div>";	
		echo "</span>";
		//////// TABLAS IMPRESAS ////////
		
		function titulosTablaTendencias(){
			global $secciones;
			echo "<tr>";
		    echo "<th></th>";
		    echo "<th>Puntaje</th>";
		    for($i = 0; $i < count($secciones); $i++){
		    	echo "<th>". $secciones[$i] ."</th>";
		    }
		    echo "</tr>";
		}
		
		function titulosTabla(){
			global $secciones;
			echo "<tr>";
		    echo "<td>Número</td>";
		    echo "<td class='alinearIzq'>Ensayo</td>";
		    echo "<td>Puntaje</td>";
		    echo "<td>Alumnos</td>";
		    for($i = 0; $i < count($secciones); $i++){
		    	echo "<td>". $secciones[$i] ."</td>";
		    }
		    echo "</tr>";
		}

		function create_table($dataArr) {
		    echo "<tr>";
		    echo "<td></td>";
		    for($j = 0; $j < count($dataArr)/2; $j++) {
		        if(is_numeric($dataArr[$j])){
		        	echo "<td>".round($dataArr[$j], 1)."</td>";
		        }
		        else{
		        	echo "<td>".ucwords($dataArr[$j])."</td>";	
		        }
		    }
		    echo "</tr>";
		}
		
		function r2($x, $y){
			$n = count($x);
			if($n == 0){
				return "0";
			}
			$term1 = 0; $term2 = 0; $term21 = 0; $term22 = 0; $term3 = 0; $term31 = 0; $term32 = 0; $term41 = 0; $term42 = 0;
			// b = (n*term1 - term2)/(n*term3 - term4)
			// term2 = term21*term22
			for($i = 0; $i < $n; $i++){
				$term1 += $x[$i] * $y[$i];
			}
			$term1 = $n * $term1;
			for($i = 0; $i < $n; $i++){
				$term21 += $x[$i];
				$term22 += $y[$i];
			}
			$term2 = $term21 * $term22;
			$num = pow($term1 - $term2, 2);
			
			for($i = 0; $i < $n; $i++){
				$term31 += pow($x[$i], 2);
			}
			$term31 = $n * $term31;
			$term32 = ($n * pow($term21, 2)); 
			$term3 = $term31 - $term32;
			for($i = 0; $i < $n; $i++){
				$term41 += pow($y[$i], 2);
			}
			$term41 = $n * $term41;
			$term42 = ($n * pow($term22, 2)); 
			$term4 = $term41 - $term42;
			$denom = $term3 * $term4;
			$retorno = $num/$denom;
			
			return round($retorno, 5);
		}
		
		function tendencia($x, $y){
			$n = count($x);
			if($n == 0){
				return "-";
			}
			$alpha = alpha($x, $y);
			for($i = 0; $i < $n; $i++){
				$x[$i] = $x[$i] * $alpha;
			}
			$term1 = 0; $term2 = 0; $term21 = 0; $term22 = 0; $term3 = 0; $term4 = 0;
			// b = (n*term1 - term2)/(n*term3 - term4)
			// term2 = term21*term22
			for($i = 0; $i < $n; $i++){
				$term1 += $x[$i] * $y[$i];
			}
			for($i = 0; $i < $n; $i++){
				$term21 += $x[$i];
				$term22 += $y[$i];
			}
			$term2 = $term21 * $term22;
			for($i = 0; $i < $n; $i++){
				$term3 += pow($x[$i], 2);
			}
			$term4 = pow($term21, 2);
			
			$tendencia = round(($n*$term1 - $term2)/($n*$term3 - $term4), 2);
			if($tendencia > 0){
				return "<span class='textoVerde'>". $tendencia * 100 ."%</span>";
			}
			else if($tendencia <= 0.1 && $tendencia >= -0.1){
				return "<span class='textoNaranjo'>". $tendencia * 100 ."%</span>";
			}
			else{
				//return "<span class='textoRojo'>". ($tendencia) ."%</span>";
				return  "<span class='textoRojo'>". $tendencia * 100 ."%</span>";
			}
		}
		
		function alpha($x, $y){
			$xBarra = 0; $yBarra = 0;
			$n = count($x);
			if($n == 0){
				return "-";
			}
			for($i = 0; $i < count($x); $i++){
				$xBarra += $x[$i];
				$yBarra += $y[$i];
			}
			$xBarra = $xBarra/$n;
			$yBarra = $yBarra/$n;
			$term1 = 0; $term2 = 0; $term21 = 0; $term22 = 0; $term3 = 0; $term4 = 0;
			// b = (n*term1 - term2)/(n*term3 - term4)
			// term2 = term21*term22
			for($i = 0; $i < $n; $i++){
				$term1 += $x[$i] * $y[$i];
			}
			for($i = 0; $i < $n; $i++){
				$term21 += $x[$i];
				$term22 += $y[$i];
			}
			$term2 = $term21 * $term22;
			for($i = 0; $i < $n; $i++){
				$term3 += pow($x[$i], 2);
			}
			$term4 = pow($term21, 2);
			
			$tendencia = round(($n*$term1 - $term2)/($n*$term3 - $term4), 2);
			$alpha = $yBarra - ($tendencia * $xBarra);
			return $alpha;
		}
	?>
</body>
<script>
$(document).ready(function(){
	var count = 0;
	// Mostrar dropdowns
	$("#choicesAdmin").on("click", '#botonAlumno', function(){
		if($('#idalumno1').parent().css('display') == 'none'){
			$('#idalumno1').parent().animate({width: 'toggle'});
			count++;
		}
		else if($('#idalumno2').parent().css('display') == 'none'){
			$('#idalumno2').parent().animate({width: 'toggle'});
			count++;
		}
		else if($('#idalumno3').parent().css('display') == 'none'){
			$('#idalumno3').parent().animate({width: 'toggle'});
			count++;
		}
		if(count == 3){
			$("#botonAlumno").fadeOut();
		}
	});
	
	// Quitar dropdown
	$("#choicesAdmin").on("click", '.delete', function(){
		$(this).parent().children(".dropdown").attr('checked', false);
		$(this).parent().animate({width: 'toggle'});	
		plotAccordingTochoicesAdmin();
		count--;
		$("#botonAlumno").fadeIn();
	});
	
	$.each( $('.puntajes tr'), function( key, value ) {
		if($(value).children().first().text() == ''){
			$(value).children().first().text(key);		
		}
	});
	var puntajes = <?php echo json_encode($allPuntajes); ?>;
	var promedioCurso = <?php echo json_encode($promedioCurso); ?>;
	var alumnos = <?php echo json_encode($listaSoloAlumnos); ?>;
	var alumnosConPuntajes = <?php echo json_encode($listaPuntajes); ?>;
	var listado = <?php echo json_encode($listado); ?>;
	var datosAlumno = [];
	var demrePromedio = [];
	var alumnoTodo1Ptj = [], alumnoTodo2Ptj = [], alumnoTodo3Ptj = [];
	var datosCurso = [], datosSec1 = [], datosSec2 = [], datosSec3 = [], datosSec4 = [], datosSec5 = [];
	var datosRepresentativos = [];
	var rut;
	var idAlumno;
	var seccion = false;
	var choiceContainer = $("#choicesAdmin");
	var significativos = parseInt(alumnos.length * 0.7);
	var plot;

	// Armar arrays para graficar
	jQuery.each(alumnos , function(index, value){
		if(parseInt(value.Puntaje) == 0){
			
		}
		else{
			datosAlumno.push([ index+1, parseInt(value.Puntaje) ]);
		}
	});	
	jQuery.each(promedioCurso , function(index, value){
			datosCurso.push([ index+1, Math.round(value.Promedio) ]);
			if(parseInt(value.Demre) == 1){
				demrePromedio.push([ index+1, parseInt(value.Promedio) ]);
			}
			if(parseInt(value.Cantidad) > significativos){
				datosRepresentativos.push([ index+1, Math.round(value.Promedio) ]);				
			}
	});
	function rellenarSeccionPromedio(){
		datosSec1.length = 0, datosSec2.length = 0, datosSec3.length = 0, datosSec4.length = 0, datosSec5.length = 0;
		jQuery.each(promedioCurso , function(index, value){
			if(value.Cont)
			datosSec1.push([ index+1, (value.Cont/15) ]);
			if(value.Cone)
			datosSec2.push([ index+1, (value.Cone/5) ]);
			if(value.Plan)
			datosSec3.push([ index+1, (value.Plan/10) ]);
			if(value.Voca)
			datosSec4.push([ index+1, (value.Voca/15) ]);
			if(value.Comp)
			datosSec5.push([ index+1, (value.Comp/35) ]);
		});	
	}
	rellenarSeccionPromedio();
	
	$("#choicesAdmin").on("change", 'select', function() {
		// Es RUT?
		if($("option:selected", this).hasClass("sinNombre")){
			rut = $("option:selected", this).text();
		}
		// Tiene nombre entonces? Conseguir RUT...
		else if($("option:selected", this).hasClass("conNombre")){
			var apellidos = $("option:selected", this).text();
			jQuery.each(alumnos , function(index, value){
				if((value.Apellidos +", "+ value.Nombres) == apellidos){
					rut = value.RUT;
				}
			});		
		}
		else{
			// No graficar nada
			rut = null;
		}
		
		switch($("option:selected", this).closest("div").children("label").text()){
			case "Alumno 1":
				alumnoTodo1Ptj.length = 0;
				alumnoTodo1Ptj = dropdownToPuntajes(alumnoTodo1Ptj).sort(SortById);
				$(this).parent().children(".dropdown").attr('checked', 'checked');
				break;
			case "Alumno 2":
				alumnoTodo2Ptj.length = 0;
				alumnoTodo2Ptj = dropdownToPuntajes(alumnoTodo2Ptj).sort(SortById);
				$(this).parent().children(".dropdown").attr('checked', 'checked');
				break;
			case "Alumno 3":
				alumnoTodo3Ptj.length = 0;
				alumnoTodo3Ptj = dropdownToPuntajes(alumnoTodo3Ptj).sort(SortById);
				$(this).parent().children(".dropdown").attr('checked', 'checked');
				break;	
		}
		dropdownToSeccion();
		procesarGraficar($(this));
	});
	
	
	function dropdownToPuntajes(arreglo){
		// Recorre tabla de puntajes mostrados y tabla alumno
		// Buscar ensayos iguales y rut
		jQuery.each(alumnosConPuntajes , function(indexAl, valueAl){
			jQuery.each(promedioCurso , function(indexCu, valueCu){
				if(valueCu.Ensayo == valueAl.Ensayo && valueAl.RUT == rut){
					if(valueAl.Puntaje == 0){
						
					}
					else{
						arreglo.push([ indexCu+1, parseInt(valueAl.Puntaje) ]);
					}
				}
			});
		});
		return arreglo;
	}
	
	function dropdownToSeccion(){
		datosSec1.length = 0, datosSec2.length = 0, datosSec3.length = 0, datosSec4.length = 0, datosSec5.length = 0;
		// Recorre tabla de secciones mostrados y tabla alumno
		// Buscar ensayos iguales y rut
		jQuery.each(alumnosConPuntajes , function(indexAl, valueAl){
			jQuery.each(promedioCurso , function(indexCu, valueCu){
				if(valueCu.Ensayo == valueAl.Ensayo && valueAl.RUT == rut){
					if(valueAl.Puntaje == 0){
						
					}
					else{
						if(valueAl[5]!=0 || valueAl[6]!=0 || valueAl[7]!=0 || valueAl[8]!=0 || valueAl[9]!=0){
							datosSec1.push([ indexCu+1, valueAl[5]/15 ]);
							datosSec2.push([ indexCu+1, valueAl[6]/5 ]);
							datosSec3.push([ indexCu+1, valueAl[7]/10 ]);
							datosSec4.push([ indexCu+1, valueAl[8]/15 ]);
							datosSec5.push([ indexCu+1, valueAl[9]/35 ]);	
						}
					}
				}
			});
		});
		datosSec1 = datosSec1.sort(SortById);
		datosSec2 = datosSec2.sort(SortById);
		datosSec3 = datosSec3.sort(SortById);
		datosSec4 = datosSec4.sort(SortById);
		datosSec5 = datosSec5.sort(SortById);
	}
	
	function SortById(a, b){
	  var aId = a[0];
	  var bId = b[0]; 
	  return ((aId < bId) ? -1 : ((aId > bId) ? 1 : 0));
	}
	
	
	var datasSec = {
		"datosSec5": {
        	label: "Comprensión Lectora",
        	data: datosSec5
        },
		"datosSec1": {
        	label: "Contenido",
        	data: datosSec1		
        },
		"datosSec3": {
        	label: "Plan de Redacción",
        	data: datosSec3		
        },
		"datosSec4": {
        	label: "Vocabulario Contextual",
        	data: datosSec4		
        },
        "datosSec2": {
        	label: "Conectores",
        	data: datosSec2		
        }        
    };
    
	var datasets = {
		"alumno3": {
        	label: "Alumno 3",
        	data: alumnoTodo3Ptj		
        },
        "alumno2": {
        	label: "Alumno 2",
        	data: alumnoTodo2Ptj		
        },
		"alumno1": {
        	label: "Alumno 1",
        	data: alumnoTodo1Ptj		
        },
		"representativos": {
        	label: "Alumnos > "+ significativos,
        	data: datosRepresentativos		
        },
        "demrePromedio": {
        	label: "Promedio Ensayos Demre",
        	data: demrePromedio
        },
        "curso": {
        	label: "Promedio Puntajes",
        	data: datosCurso
        }
    };
	
	
    var options = {
    	series: {
	        lines: { show: true, fill: false },
	        points: { show: true },
	    },
	    grid: {
		    clickable: true,
		    hoverable: true,
		    autoHighlight: true,
		    backgroundColor: "#F9F9F9"
		},
		legend: {
		    show: true,
		    position: "se"
		},
		xaxis: {
			tickSize: 1,
			tickDecimals: 0,
			tickFormatter: function(val, axis) { 
		    	if(val >= axis.max){
		    		return "Ensayo";
		    	}
		    	else{
		    		return Math.round(val);
		    	}
	    	}
		},
		yaxis: {
		    max: 900,
		    tickFormatter: function(val, axis) { 
		    	if(val >= axis.max && !seccion){
		    		return "Puntaje";
		    	}
		    	else{
		    		return Math.round(val);
		    	}
	    	}
		},
		canvas: true
    };
    var optionsSec = {
    	series: {
    		stack: false,
	        lines: { show: true, fill: false },
	        points: { show: true },
	    },
	    grid: {
		    clickable: true,
		    hoverable: true,
		    autoHighlight: true,
		    backgroundColor: "#F9F9F9"
		},
		legend: {
		    show: true,
		    position: "se"
		},
		xaxis: {
			tickSize: 1,
			tickDecimals: 0,
			tickFormatter: function(val, axis) { 
		    	if(val >= axis.max){
		    		return "Ensayo";
		    	}
		    	else{
		    		return Math.round(val);
		    	}
	    	}
		},
		yaxis: {
		    tickFormatter: function(val, axis) {
		    	if(val >= axis.max && seccion){
		    		return "Correctas";
		    	}
		    	else{
		    		return Math.round(val*100)+"%";
		    	}
	    	}
		},
		canvas: true
    };    

    // Para que no cambien de color
    var i = 0;
	$.each(datasets, function(key, val) {
		val.color = i;
		++i;
	});	
    
    // insert checkboxes 
	$.each(datasets, function(key, val) {
		if(val.label.indexOf("Alumno ") == -1){
			choiceContainer.prepend("<div class='choiceParent'><input type='checkbox' name='" + key +
				"' checked='checked' id='id" + key + "'></input>" +
				"<label for='id" + key + "'>"
				+ val.label + "</label></div>");
		}
		else{
			$('#choicesSegundaLinea').prepend(listado[0] + key +
				listado[1] + key + listado[2] +
				listado[3] + key + listado[4]
				+ val.label + listado[5] + key +
				listado[6] + key + listado[7] + listado[8]);
		}
	});
	choiceContainer.prepend("<div class='choiceParent'><input type='checkbox' name='promediosecciones' id='idpromediosecciones' class='seccion'></input><label for='idpromediosecciones'>Promedio secciones</label></div>");
	$('#choicesSegundaLinea').append("<button id='botonAlumno' class='boton'>Alumno</button>");

	function plotAccordingTochoicesAdmin() {
		var data = [];
		if(!seccion){
			data.length = 0;
			choiceContainer.find("input:checked").each(function () {
				var key = $(this).attr("name");
				if (key && datasets[key]) {
					data.push(datasets[key]);
				}
			});
			if (data.length > 0) {
				//$.plot("#grafico", data, options);
				plot = $.plot("#grafico", data, options);
			}
		}
		else{
			data.length = 0;
			$.each( datasSec, function( key, value ) {
			  data.push(datasSec[key]);
			});
			if (data.length > 0) {
				//$.plot("#grafico", data, optionsSec);
				plot = $.plot("#grafico", data, optionsSec);
			}
		}
	}
	
	$('#containerGrafico').click(function(){
		downloadCanvas();	
	});
	
	function downloadCanvas(){
		var myCanvas = plot.getCanvas();
		var cs = new CanvasSaver('includes/saveme.php');
		cs.savePNG(myCanvas, 'image');
		
		function CanvasSaver(url) {
		  this.url = url;
		  this.savePNG = function(cnvs, fname) {
		  if(!cnvs || !url) return;
		    fname = fname || 'picture';
		
		    var data = cnvs.toDataURL("image/png");
		    data = data.substr(data.indexOf(',') + 1).toString();
		    var dataInput = document.createElement("input") ;
		    dataInput.setAttribute("name", 'imgdata') ;
		    dataInput.setAttribute("value", data);
		
		    var nameInput = document.createElement("input") ;
		    nameInput.setAttribute("name", 'name') ;
		    nameInput.setAttribute("value", fname + '.png');
		
		    var myForm = document.createElement("form");
		    myForm.method = 'post';
		    myForm.action = url;
		    myForm.appendChild(dataInput);
		    myForm.appendChild(nameInput);
		
		    document.body.appendChild(myForm) ;
		    myForm.submit() ;
		    document.body.removeChild(myForm) ;
		  };
		}	
	}

	function showTooltip(x, y, contents) {
		$("<div id='tooltip'>" + contents + "</div>").css({
			position: "absolute",
			display: "none",
			top: y + 5,
			left: x + 5,
			border: "1px solid #fdd",
			padding: "2px",
			"background-color": "white",
			opacity: 0.80
		}).appendTo("body").fadeIn(200);
	}

	var previousPoint = null;
	$("#grafico").bind("plothover", function (event, pos, item) {
		if (true) { // Si se quiere deshabilitar tooltip poner false
			if (item) {
				if (previousPoint != item.dataIndex) {

					previousPoint = item.dataIndex;

					$("#tooltip").remove();
					var x = item.datapoint[0].toFixed(2),
					y = item.datapoint[1].toFixed(2);
					
					if(seccion){
						y5 = Math.round(datosSec1[item.dataIndex][1]*15);
						y4 = Math.round(datosSec2[item.dataIndex][1]*5);
						y3 = Math.round(datosSec3[item.dataIndex][1]*10);
						y2 = Math.round(datosSec4[item.dataIndex][1]*15);
						y1 = Math.round(datosSec5[item.dataIndex][1]*35);
						showTooltip(item.pageX, item.pageY,
					    "<strong> "+ puntajes[Math.round(x)-1][0] +"</strong></br>" +
					    "Comprensión Lectora: " + y1 + "</br>" + 
					    "Contenido: " + y5 + "</br>" + 
					    "Plan de Redacción: " + y3 + "</br>" + 
					    "Vocabulario Contextual: " + y2 + "</br>" + 
					    "Conectores: " + y4
					    );
					}
					else{
						showTooltip(item.pageX, item.pageY,
					    //item.series.label + " of " + x + " = " + y);
						puntajes[Math.round(x)-1][0] +": <b>" + Math.round(y) + " puntos</b>");	
					}
				}
			} else {
				$("#tooltip").remove();
				previousPoint = null;            
			}
		}
	});
	
	function cambiarRut(parent){
		var selected = parent.find('select option:selected');
		// Es RUT?
		if(selected.hasClass("sinNombre")){
			rut = selected.text();
		}
		// Tiene nombre entonces? Conseguir RUT...
		else if(selected.hasClass("conNombre")){
			var apellidos = selected.text();
			jQuery.each(alumnos , function(index, value){
				if((value.Apellidos +", "+ value.Nombres) == apellidos){
					rut = value.RUT;
				}
			});		
		}
		else{
			// No graficar nada
			rut = null;
		}
	}
	
	function isNumber(n) {
	  return !isNaN(parseFloat(n)) && isFinite(n);
	}
		
	function tickets(dejarle){
		choiceContainer.find("input:checked").not(dejarle).each(function () {
			if($(this).prop('type') != "radio"){
				$(this).removeAttr("checked");
			}
		});
	}
	
	function procesarGraficar(clickeado){
		// Variables
		var parent, checkbox;
		
		if(clickeado.hasClass('choiceParent')){
			parent = clickeado;	
		}	
		else{
			parent = clickeado.closest('.choiceParent'); 	
		}
		parent.find('input').each(function(){
				if($(this).attr('type') == 'checkbox'){
					checkbox = $(this);	
				}
		});
		
		// Si hizo click en radio button, marcar tambien a parent
		if(clickeado.attr('type') == "radio"){
			parent.children(".dropdown").prop('checked', 'checked');
		}
		
		// Si (no) voy a graficar una seccion
		if(!(clickeado.parent().children('input').attr('id') == 'idpromediosecciones' || (parent.children('.checkMultiple:checked').length + parent.children('.radioSec:checked').length == 2)) ){
			// Grafico puntajes: si antes estaba en seccion sacar tickets
			if(seccion){
				tickets(checkbox);
			}
			seccion = false;
			plotAccordingTochoicesAdmin();	
		}
		// Voy a graficar alguna seccion
		else{
			seccion = true;			
			// Quitar ticks en todos los otros
			tickets(checkbox);
			// Rellenar datasSec segun corresponda, tengo rut y llamar funcion. rellenarSeccionPromedio para promedio, dropDownToSeccion para alumno 
			if(parent.hasClass('radio')){
				// Es un alumno
				cambiarRut(parent);
				dropdownToSeccion();
			}
			else{
				// Es el promedio
				rellenarSeccionPromedio();
			}
			// Graficar
			plotAccordingTochoicesAdmin();
		}
	}
	
	
	// Fade leyenda
	$("#containerGrafico").on("mouseenter", ".legend", function(){
		$(this).css("opacity","0");
		setTimeout(function(){$(".legend").css("display","none");},1000);
	});
	$("#containerGrafico").mouseleave(function(){
		$(".legend").css("display","inline");
		$(".legend").css("opacity","1");	
	});
	
	choiceContainer.find("input").click(function(){
		procesarGraficar($(this));
	});

	plotAccordingTochoicesAdmin();
	$('#grafico').resize(function(){});
});
</script>
</html>
