<?php include_once('includes/isAdmin.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Horarios</title>
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
		include_once('includes/botonLogout.php'); 
		include_once('includes/menuAdmin.php');
		@session_start();
		$thisYear = date("Y");
       	$año = $_SESSION['horarioYear'];
       	if(!isset($_SESSION['horarioYear'])){
       		$año = $thisYear;
       		$_SESSION['horarioYear'] = $thisYear;
       	}
?>
	<div style="display:none" class="dropYearsHorario">
		<select name="years">
		<option value="">Cambiar año</option>
			<?php
				echo '<option value="'. $thisYear .'">'. $thisYear .'</option>';
				echo '<option value="'. ($thisYear+1) .'">'. ($thisYear+1) .'</option>';
			?>
		</select>
	</div>
	<?php
		echo '<h1 class="margenLeft">Horario (<a href="#" class="yearClick">'. $año .'</a>)</h1>';
	?>
    <h3>Verde: Horario disponible, Número: Cupos disponibles.</h3>
    <div id="botonesCambiar">
		<button id="botonHorarioAdmin" class="boton">Guardar Cambios</button>
    	<div class="botonCambiarApretado">
	    	<label for="cambiarColor">Cambiar color</label>
			<input type="radio" name="cuposColor" value="color" id="cambiarColor" checked="checked">
		</div>
		<div>
			<label for="cambiarCupos">Cambiar cupos</label>
			<input type="radio" name="cuposColor" value="cupos" id="cambiarCupos">
		</div>
    </div>
    <?php
    	if($_SESSION['horarioYear'] == $thisYear){
			include_once('includes/tablaHorario.html');
    	}
    	else{
    		include_once('includes/tablaHorarioProx.html');
    	}
	?>
	<div class="spacer"></div>
</body>

<script>
	$(document).ready(function(){
		var color = true;
		var cupos = false;
		$('.cupos').attr('contentEditable',false);
		
		// Guardar tabla con AJAX
		$('#botonHorarioAdmin').click(function(e){
			e.preventDefault();
			$('.cupos').attr('contentEditable',false);
			var tabla = $('#tablaHorario').prop('outerHTML');
			var year = $('.yearClick').text();
			var who = 'noone';
			if(new Date().getFullYear() == year){
				who = 'horarioThis';
			}
			else{
				who = 'horarioNext';
			}
			$.ajax({
				url: 'includes/procesarTabla.php',
				type: 'POST',
				data: {
					'tabla': tabla,
					'who': who
				},
				success: function(data){
					if(data == "ok"){
						Apprise("Cambios guardados con éxito", {
							override: true, 
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
						Apprise('Ocurrió un error, este fue el mensaje de error:</br>'+ data, {
							override: true, 
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
			})
		});
		
		function redibujarTabla(){
			// Guardar numero de celdas y columnas (th != tr  !!!!!!!!)
			var fil = $('tbody').children().length - 1;
			var col = $('#tablaHorario tr:first-child').children().length - 1;
			// Pasar clase verde a arreglo tridimensional [fila][columna][verde, juntadas, raya]
			var celdas = new Array(fil);
			for (var i = 1; i <= fil; i++) {
				celdas[i] = new Array(col);
		 	}
		 	for (var i = 1; i <= fil; i++) {
				for (var j = 1; j <= col; j++) {
					celdas[i][j] = new Array(3);
			 	}
		 	}
		 	$("#tablaHorario tr").each(function(i){
			  	$(this).children('td').each(function(j){
			  		j = j+1;
			  		if($(this).hasClass('verde')){
			  			celdas[i][j][0] = 1;
			  		}
			  		else{
			  			celdas[i][j][0] = 0;
			  		}
			  		if($(this).hasClass('juntadas')){
			  			celdas[i][j][1] = 1;
			  		}
			  		else{
			  			celdas[i][j][1] = 0;
			  		}
			  	});
			});
			// Buscar en columnas tres celdas verdes adyacentes y agregar clase juntadas
			for(var i=1; i<=fil-2; i++){
				for(var j=1; j<=col; j++){
					// Buscar si es verde y no tiene clase juntadas
					if(celdas[i][j][0] == 1 && celdas[i][j][1] == 0){
						// Si las dos siguientes filas son verdes
						if(celdas[i+1][j][0] == 1 && celdas[i+2][j][0] == 1){
							// Clase raya
							celdas[i][j][2] = 1;
							celdas[i+1][j][2] = 1;
							// Clase juntadas
							celdas[i][j][1] = 1;
							celdas[i+1][j][1] = 1;
							celdas[i+2][j][1] = 1;
						}
					}
				}
			}
			// Poner clase raya en dos primeras celdas y juntadas en las tres
			$("#tablaHorario tr").each(function(i){
			  	$(this).children('td').each(function(j){
			  		j = j+1;
		  			// Si tiene clase raya
		  			if(celdas[i][j][2] == 1){
		  				$(this).addClass('raya');
		  			}
		  			else{
		  				$(this).removeClass('raya');
		  			}
			  	});
			});
		}
		
		$("#tablaHorario td").click(function(){
			if(color){
				$(this).toggleClass("verde");
			}
			else if(cupos){
				$(this).children("span").focus();
			}
			redibujarTabla();
			
			// Guardar tabla en string auxiliar
			$texto = $('#tablaHorario').clone().wrap('<p>').parent().html();
			$('textarea').text($texto);
		});
		$(".cupos").keydown(function() {
			setTimeout(function() {
		      	$texto = $('#tablaHorario').clone().wrap('<p>').parent().html();
		      	$('textarea').text($texto);
			}, 200);
		});
		
		// Dropdown años Horario cambia
		$('.dropYearsHorario').change(function(){
			var year = $("option:selected", this).text();
			if(year == "Cambiar año"){
				return;
			}
			
			// Guardar este nuevo año en la sesión y reload
			$.ajax({
				type:  'post',
			   	url: 'includes/changeYear.php',
			   	data: { 
			        'year': year,
			        'horario': true
			    },
			    success:  function (response) {
	                location.reload(); 
	            },
			    error: function (msg, status, error) {
			        Apprise("Status: "+ status +", Error: "+ error, {
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
			});
		});
		
		
		$('#botonesCambiar div').click(function(){
			$('#botonesCambiar div').removeClass("botonCambiarApretado");
			$(this).addClass("botonCambiarApretado");
			
			if($(this).children('label').text() == "Cambiar color"){
				color = true;
				cupos = false;
				$('.cupos').attr('contentEditable',false);
			}
			else{
				color = false;
				cupos = true;
				$('.cupos').attr('contentEditable',true);		
			}
		});
		
		redibujarTabla();

	});
</script>
</html>

