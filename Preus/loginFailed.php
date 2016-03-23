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
	echo "<div id='centrar'>Usuario o Contraseña inválidos.\n Volviendo a la <a href=\"admin.php\">p&aacute;gina anterior</a> en <span id='segundos'>4</span> segundos.</div>";
?>
</body>
<script>
	$(document).ready(function(){
		setTimeout(function() {
	  		window.location.href = "index.php";
		}, 4000);	
	});
</script>	
</html>