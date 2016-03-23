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
</head>

<body class="paddingBody">
	<?php 
		include_once('includes/botonLogout.php');
        include_once('includes/menuVisita.php'); 
		include_once('includes/formaContacto.php');
		include_once('includes/formaLogin.php');        
    ?>
	<h1 class='margenLeft tituloHorario'>Horario <?php echo date("Y") ?></h1>
    <h3 id="descripcionHorario">Las celdas verdes indican los bloques disponibles, los números indican los cupos disponibles en dicho bloque:</h3>
    <div id="botonesCambiarYear">
    	<span id="palabraYear">Año:</span>
    	<div class="botonCambiarApretado">
	    	<label for="cambiarYear1"><?php echo "<span id='Year1Check'>". date("Y") ."</span>"?></label>
			<input type="radio" name="Year" value="color" id="cambiarYear1" checked="checked">
		</div>
		<div>
			<label for="cambiarYear2"><?php echo "<span id='Year2Check'>". (date("Y")+1) ."</span>"?></label>
			<input type="radio" name="Year" value="cupos" id="cambiarYear2">
		</div>
    </div>
    <div id="containerHorarios">
    </div>
    <?php
        include_once('includes/tablaHorario.html'); 
        include_once('includes/tablaHorarioProx.html');    
    ?>
</body>
<script>
$(document).ready(function(){
	$('.horarioNext').css('display', 'none');
	$('.cupos').attr('contentEditable',false);
	$('#botonesCambiarYear div').click(function(){
		// Guardar valor de año actual
		var thisYear = $('#Year1Check').text();
		// Quitar clase de seleccionado a todos
		$('.botonCambiarApretado').removeClass("botonCambiarApretado");
		// Poner clase seleccionado a este
		$(this).addClass('botonCambiarApretado');
		// Quitar tabla correspondiente e inyectar la otra, cambiar año en titulo
		if($(this).find('label').text() != thisYear){
			$('.tituloHorario').text("Horario "+ (parseInt(thisYear) +1));
			$('.horarioThis').fadeOut(function(){ $('.horarioNext').fadeIn(); });
		}
		else{
			$('.tituloHorario').text("Horario "+ (parseInt(thisYear)));
			$('.horarioNext').fadeOut(function(){ $('.horarioThis').fadeIn(); });
		}
	});	
});
</script>
</html>
