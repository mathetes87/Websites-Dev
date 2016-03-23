<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Preuniversitario</title>
<link href="css.css" rel="stylesheet" type="text/css" />
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
</head>

<body id="bodyIndex">
<?php
	//Get the table.
	$tabla = $_POST['tablaForm'];
	// Write the thing
	$f = fopen('includes/tablaHorario.html', 'w');
	fwrite($f, "$tabla");
	// Close file
	fclose($f);
	echo "<div id='centrar'>Cambios guardados exitosamente.\n Volviendo a la <a href=\"admin.php\">p&aacute;gina de administrador</a> en <span id='segundos'>2</span> segundos.</div>";
?>
</body>
<script>
	$(document).ready(function(){
		setTimeout(function() {
	  		window.location.href = "admin.php";
		}, 2000);	
	});
</script>	
</html>