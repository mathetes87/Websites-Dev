<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Apoderados</title>
<link href="css.css" rel="stylesheet" type="text/css" />
<script src="jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js.js"></script>
<link rel="stylesheet" href="/fancybox/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
<script type="text/javascript" src="/fancybox/jquery.fancybox.pack.js?v=2.1.4"></script>
<link href="forma.css" rel="stylesheet" type="text/css" /> 
<script type="text/javascript" src="apprise2/apprise2.js"></script>
<link rel="stylesheet" href="apprise2/apprise2.css" type="text/css" /> 
</head>

<body>
    <?php
        include_once('includes/menuAdmin.php');
		echo '<div class="wrapperGlobal">';
 		include_once('includes/botonLogout.php');
        
       	include_once 'includes/conectarDB.php';
		include_once 'includes/infoProfesor.php';
		// $tbl_usuarios="usuarios"; // Table name 1
		// $tbl_puntajes="puntajes"; // Table name 2
		include_once 'includes/chooseYear.php';
       	@session_start();
       	$year = $_SESSION['year'];
       	$thisYear = date("Y");
		
		// Si se llego a la pagina por guardar cambios en un alumno con forma
		if (!empty($_POST)){
			//////// Para encontrar el rut con el nombre y apellidos
			$nombres = $_POST['0'];
			$apellidos = $_POST['1'];
			$query1="SELECT `RUT` FROM $tbl_usuarios WHERE `Nombres` = '". $nombres ."' AND `Apellidos` = '". $apellidos ."' LIMIT 1";
			$alumnoUnico = mysql_fetch_row(mysql_query($query1));
			$RUT = $alumnoUnico[0];
			// Purge . and - from RUT
			$RUT = str_replace(".", "", $RUT);
			$RUT = str_replace("-", "", $RUT);
			$nombrePapa = $_POST['2'];
			$mailPapa = $_POST['3'];
			$nombreMama = $_POST['4'];
			$mailMama = $_POST['5'];
			$celularMama = $_POST['6'];
			
			$query = "UPDATE ".$tbl_usuarios." SET `Nombre papa` = '". $nombrePapa ."', `Mail papa` = '". $mailPapa ."', `Nombre mama` = '". $nombreMama ."', `Mail mama` = '". $mailMama ."', `Celular mama` = '". $celularMama."' WHERE RUT = '".$RUT."'";
			//echo $query;
			
			mysql_query($query)or die(mysql_error());
			echo "<div style='display:none' class='enviarMailAutomatico'></div>";
		}
		
		//////// PARA LOS TITULOS EN LA TABLA HTML
		if($year == $thisYear){
			$query="SELECT `Nombres`, `Apellidos`, `Nombre papa`, `Mail papa`, `Nombre mama`, `Mail mama`, `Celular mama`  FROM $tbl_usuarios WHERE `RUT` != '".$rutProfesor."' AND (`Nombres` != '' OR `Apellidos` != '')  AND (`Year` = $year OR `Year` IS NULL) ORDER BY  Grupo ASC, Apellidos ASC";
		}
		else{
			$query="SELECT `Nombres`, `Apellidos`, `Nombre papa`, `Mail papa`, `Nombre mama`, `Mail mama`, `Celular mama`  FROM $tbl_usuarios WHERE `RUT` != '".$rutProfesor."' AND (`Nombres` != '' OR `Apellidos` != '') AND `Year` = $year ORDER BY  Grupo ASC, Apellidos ASC";
		}
		$res = mysql_query($query);
		$alumnos = mysql_fetch_all($res);
		$numeroColumnas = 9;
		$row = 0;
		
		echo "<h1 id='tituloHorario'>Apoderados <a href='#' class='yearClick'>(". $year .")</a></h1>";
		
		//////// IMPRIMIR TABLA ////////
		echo "<div id='datosApoderados'>";
			echo "<table width='100%' class='data_table' cellspacing='3' cellpadding='5'>";
				
			titulosTabla();
			for($i = 0; $i < count($alumnos); $i++) {
				$row++;
				create_table($alumnos[$i]);
			}
			echo "<tr class='spacer'></tr>";				
			echo "</table>";
			echo "<form action='' method='post' style='display: none' id='formaDatosUsuario'>";
		    for($i = 0; $i < 7; $i++) {
		    	echo "<input type='text' name=".$i." id='formaCol".$i."'>";
		    }
		    echo "</form>";
		echo "</div>";
		//////// TABLA IMPRESA ////////
		
		function titulosTabla(){
			echo "<tr>";
			echo "<th nowrap>N°</th>";
		    echo "<th>Nombres Alumno</th>";
		    echo "<th>Apellidos Alumno</th>";
		    echo "<th>Nombre Papá</th>";
		    echo "<th>Email Papá</th>";
		    $colMail = 4;
		    echo "<th>Nombre Mamá</th>";
		    $colTel = 5;
		    echo "<th>Email Mamá</th>";
		    echo "<th>Celular Mamá</th>";
		    echo "<th></th>";
		    echo "<th>Editar Información</th>";
		    echo "</tr>";
		}
		
		function create_table($dataArr) {
			global $row;
		    echo "<tr>";
		    echo "<th class='numero'>$row</th>";
		    for($j = 0; $j < count($dataArr)/2; $j++) {
	        	if($j == 4-1){
	        		echo "<td><a href='mailto:".ucwords($dataArr[$j])."' target='_blank' style='text-transform: lowercase'>".ucwords($dataArr[$j])."</a></td>";
	        	}
	        	else if($j == 6-1){
	        		echo "<td><a href='mailto:".ucwords($dataArr[$j])."' target='_blank' style='text-transform: lowercase'>".ucwords($dataArr[$j])."</a></td>";
	        	}
	        	else if($j == 7-1){
	        		if($dataArr[$j] != "0"){
	        			echo "<td><a href='tel:".ucwords($dataArr[$j])."'>".ucwords($dataArr[$j])."</a></td>";
	        		}
	        		else{
	        			echo "<td></td>";
	        		}
	        	}
	        	else{
	        		echo "<td>".ucwords($dataArr[$j])."</td>";	
	        	}
		        
		    }
		    echo "<td></td>";
		    echo "<td><a href='#' class='botonEditar'>Editar</a></td>";
		    echo "</tr>";
		}
	?>	
	</div>  
</body>
<script>
	$(document).ready(function(){
		// ENVIAR MAIL AUTOMATICOS A APODERADOS
		if($('.enviarMailAutomatico').length > 0){
			$.ajax({        
				type: "POST",
				url: "includes/mailAutomatico.php",
				success: function(data) {
					Apprise(data, {
						override: false, 
						buttons:{
							confirm:{
								text: 'Aceptar',
								action:function(){ 
									Apprise('close', location.reload(true) ); 
								}
							},
						}
					});
					
		      	}
		    });
		}
		
		var editando = false;
		var fila;
		$('.botonEditar').click(function(e){
			e.preventDefault();
			fila = $(this).closest('tr');
			if(!editando){
				// Que se pueda editar el texto
				editando = true;
				// Poner clase en boton apretado para identificarlo despues
				$(this).addClass('apretado');
				// Quitar color a todos los otros 'editar', y cambiar el actual
				$('.botonEditar').addClass('linkSinGracia');
				$(this).removeClass('linkSinGracia').addClass('textoRojo').text('Guardar');
				// Todas las otras filas ponerlas con texto gris, menos la actual y la primera
				$('tr').addClass('textoGris');
				$('tr a').addClass('linkSinGracia');
				fila.removeClass('textoGris');
				fila.find('a').removeClass('linkSinGracia');
				fila.find('td:nth-child(8) a').addClass('linkSinGracia');
				$('tr:first').removeClass('textoGris');
				// Copiar a form
				copiarFilaHaciaForm();
				// Cambiar texto a text field, quitar texto, conservar ancho
				fila.children("td").each(function(i){
					if( i != 1 && i != 0 && i != 7 && i != 9 && i != 8){
						var celda = $(this);
						var anchoCelda = celda.width();
						var texto = $(this).text();
						celda.text('');
						celda.append('<input type="text" name="celda'+ i +'" id="celda'+ i +'">');
						celda.find('input').css('width', anchoCelda-4 + 'px').val(texto);
					}	
				});
				// Poner clase para resaltar
				fila.css({"padding": "0"});
				enter();
			}
			else{
				if($(this).hasClass('apretado')){
					editando = false;
					guardar();	
				}	
			}		
		});
		
		$('.data_table a').click(function(e){
			if(editando){
				e.preventDefault();
			}
		})
		
		function copiarFilaHaciaForm(){
			fila.children("td").each(function(i){
				$("input[name=" + (i) + "]").val($(this).text());
			});	
			
			if(!editando){
				fila.find('input').each(function(i){
					$("input[name=" + (i+2) + "]").val($(this).val());
				});
			}
		}
		function guardar(){
			// Actualizar datos a form
			copiarFilaHaciaForm();
			// Guardar en DB, submit form
			$('#formaDatosUsuario').submit();
		}
		
		// AL APRETAR ENTER SE GUARDA LA LINEA
		function enter() {
			$(window).bind('keypress', function(e) {
				var code = e.keyCode || e.which;
				if(code == 13) { //Enter keycode
					editando = false;
					guardar();
				}
			});
		}
	});
</script>
</html>