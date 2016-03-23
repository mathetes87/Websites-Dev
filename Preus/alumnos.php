<?php include_once('includes/isAdmin.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Alumnos</title>
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
<body id='bodyAlumnos'>
 	<?php
        include_once 'includes/menuAdmin.php';
		echo '<div class="wrapperGlobal">';
 		include_once 'includes/botonLogout.php';
       	include_once 'includes/conectarDB.php';
		include_once 'includes/infoProfesor.php';
       	include_once 'includes/formaAgregarAlumno.php';
       	include_once 'includes/mysql_fetch_all.php';
       	@session_start();
       	$year = $_SESSION['year'];
       	$thisYear = date("Y");
		// $tbl_usuarios="usuarios"; // Table name 1
		// $tbl_puntajes="puntajes"; // Table name 2
		
		// Si se llego a la pagina por guardar cambios en un alumno con forma
		if (!empty($_POST)){
				$RutAntiguo = $_POST['100'];
				$RUT = $_POST['0'];
				// Purge . and - from RUT
				$RUT = str_replace(".", "", $RUT);
				$RUT = str_replace("-", "", $RUT);
				$Nombres = $_POST['1'];
				$Apellidos = $_POST['2'];
				$Mail = $_POST['3'];
				$Telefono = $_POST['4'];
				$Grupo = $_POST['5'];
				$Colegio = $_POST['6'];
				$Nem = $_POST['7'];
				$Carrera = $_POST['8'];
				$Universidad = $_POST['9'];
				$Year = $_POST['10'];
				
				$query1 = "UPDATE ".$tbl_usuarios." SET RUT = '".$RUT."', Nombres = '".$Nombres."', Apellidos = '".$Apellidos."', 
			Mail = '".$Mail."', Telefono = '".$Telefono."', Grupo = '".$Grupo."',Colegio = '".$Colegio."', Nem = '".$Nem."', Carrera = '".$Carrera."', Universidad = '".$Universidad."', `Year` =  '".$Year."'
			WHERE RUT = '".$RutAntiguo."'";
			
				$query2 = "UPDATE ".$tbl_puntajes." SET RUT = '".$RUT."' WHERE RUT = '".$RutAntiguo."' AND `Year` =  '".$Year."'";
				
				$query3 = "UPDATE ".$tbl_asistencia." SET Rut = '".$RUT."' WHERE Rut = '".$RutAntiguo."' ";
			//echo $query1;
			//echo $query2;
			
			mysql_query($query1)or die(mysql_error());
			mysql_query($query2)or die(mysql_error());
			mysql_query($query3)or die(mysql_error());
		}
		
		// PONER AQUI PARA QUE APAREZCA NUEVO AÑO SIN HACER RELOAD
       	include_once 'includes/chooseYear.php';
		
		//////// PARA LOS TITULOS EN LA TABLA HTML
		if($year == $thisYear){
			$query="SELECT `RUT`, `Nombres`, `Apellidos`, `Mail`, `Telefono`, `Grupo`, `Colegio`, `Nem`, `Carrera`, `Universidad`, `Year` FROM $tbl_usuarios WHERE `RUT` != '".$rutProfesor."' AND (`Year` = $year OR `Year` IS NULL) ORDER BY Apellidos";
		}
		else{
			$query="SELECT `RUT`, `Nombres`, `Apellidos`, `Mail`, `Telefono`, `Grupo`, `Colegio`, `Nem`, `Carrera`, `Universidad`, `Year` FROM $tbl_usuarios WHERE `RUT` != '".$rutProfesor."' AND `Year` = $year ORDER BY Apellidos";
		}
		$res = mysql_query($query);
		$alumnos = mysql_fetch_all($res);
		$numeroColumnas = 13;
		$row = 0;
		
		echo "<h1 id='tituloHorario'>Alumnos inscritos <a href='#' class='yearClick'>(". $year .")</a></h1>";
		
		//////// IMPRIMIR TABLA ////////
		echo "<div id='datosAlumnos'>";
			echo "<button id='botonAgregarAlumno' class='boton modalbox' href='#fancyAlumno'>Agregar Alumno</button>";
			echo "<button id='botonEliminarAlumno' class='boton' href='#'>Eliminar Alumno</button>";
			echo "<table width='100%' class='data_table' cellspacing='3' cellpadding='5'>";
				
			titulosTabla();
			for($i = 0; $i < count($alumnos); $i++) {
				$row++;
				create_table($alumnos[$i]);
			}
			echo "<tr class='spacer'></tr>";				
			echo "</table>";
			echo "<form action='' method='post' style='display: none' id='formaDatosUsuario'>";
		    for($i = 0; $i < 11; $i++) {
		    	echo "<input type='text' name=".$i." id='formaCol".$i."'>";
		    }
		    echo "<input type='text' name='100' id='100'>";
		    echo "</form>";
		echo "</div>";
		//////// TABLA IMPRESA ////////
		
		function titulosTabla(){
			echo "<tr>";
			echo "<th nowrap>N°</th>";
		    echo "<th>Rut</th>";
		    echo "<th>Nombres</th>";
		    echo "<th>Apellidos</th>";
		    echo "<th>Email</th>";
		    $colMail = 4;
		    echo "<th>Teléfono</th>";
		    $colTel = 5;
		    echo "<th>Grupo</th>";
		    echo "<th>Colegio</th>";
		    echo "<th>Nem aprox.</th>";
		    echo "<th>Carrera deseada</th>";
		    echo "<th>Universidad deseada</th>";
		    echo "<th>Año</th>";
		    echo "<th></th>";
		    echo "<th>Editar alumno</th>";
		    echo "</tr>";
		}
		
		function create_table($dataArr) {
			global $row;
		    echo "<tr>";
		    echo "<th class='numero'>$row</th>";
		    for($j = 0; $j < count($dataArr)/2; $j++) {
	        	if($j == 4-1){
	        		echo "<td><a href='mailto:". $dataArr[$j] ."' target='_blank'>". $dataArr[$j] ."</a></td>";
	        	}
	        	else if($j == 5-1){
	        		echo "<td><a href='tel:".ucwords($dataArr[$j])."'>".ucwords($dataArr[$j])."</a></td>";
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
		var editando = false;
		var rutAntiguo;
		var fila;
		var optionsReload = {override: false, buttons:{confirm:{text: 'Aceptar',action:function(){ location.reload(true); }},}};
		
		// CLICK EN EDITAR DATOS DE ALUMNO
		$(".data_table").on("click", '.botonEditar', function(e){
			e.preventDefault();
			fila = $(this).closest('tr');
			if(!editando){
				// Que se pueda editar el texto
				editando = true;
				// Desaparecer botones de agregar y eliminar alumno
				$("#botonAgregarAlumno").css("opacity", "0");
				$("#botonEliminarAlumno").css("opacity", "0");
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
				$('tr:first').removeClass('textoGris');
				// Guardar valor de rut antes de ser editado, para consulta MySQL
				rutAntiguo = fila.children('td:first').text();
				// Copiar a form
				copiarFilaHaciaForm();
				// Cambiar texto a text field, quitar texto, conservar ancho
				fila.children().each(function(i){
					if( i != 0 && i != 13 && i != 12){
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
		
		// CLICK EN ELIMINAR ALUMNO EN GENERAL
		$('#botonEliminarAlumno').click(function(){
			if($('#botonEliminarAlumno').text() == "Eliminar Alumno"){
					$(".botonEditar").replaceWith("<img src='images/delete.png' class='deleteAlumnoImg'>");	
					$('#botonEliminarAlumno').text("Cancelar");	
			}
			else{
				$(".deleteAlumnoImg").replaceWith("<a href='#' class='botonEditar'>Editar</a>");	
				$('#botonEliminarAlumno').text("Eliminar Alumno");
			}
		});
		
		// CLICK EN ELIMINAR ALUMNO ESPECIFICO
		$(".data_table").on("click", '.deleteAlumnoImg', function(){
			fila = $(this).closest('tr');
			var nombre = fila.children("td:nth-child(3)").text();
			nombre += " ";
			nombre += fila.children("td:nth-child(4)").text();
			var rut = fila.children("td:nth-child(2)").text();
			
			var options = { 
				buttons: { 
					confirm: { 
						text: 'Eliminar', 
						action: function() { 
							Apprise('close'); 
							$.ajax({
								url: 'includes/borrarAlumno.php',
								type: 'POST',
								data: ({rut: fila.children("td:nth-child(2)").text()}),
								success: function(data){
									if(data == 'borrado'){
										Apprise("Alumno eliminado con éxito", optionsReload);
									}
									else{
										Apprise("'Ocurrió un error, este fue el mensaje de error:</br>'+ data", optionsReload);
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
			};
			
			Apprise("¿Eliminar a <b>"+ nombre +" ("+ rut +")</b> junto con todos sus datos personales y puntajes asociados?", options);
		});
		
		$('.data_table a, #botonEliminarAlumno').click(function(e){
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
					$("input[name=" + (i) + "]").val($(this).val());
				});
			}
			$("input[name=100]").val(rutAntiguo);
		}
		
		function guardar(){
			// Actualizar datos a form
			copiarFilaHaciaForm(fila);
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