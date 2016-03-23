<?php
  	include_once "includes/analytics.php";	
	$linkClass = "class='modalbox' href='#fancyLogin'";
	@session_start();
	if(isset($_COOKIE['uname']) && isset($_COOKIE['upass'])){
		$linkClass = "href='login_success.php'";
	}
	echo("<nav id='barraArriba'>"); // barraArriba o barraLateral
	    	echo("<ul id='menu'>");
	            echo("<li><a href='admin.php'>ensayos</a></li>");
	            echo("<li><a href='controles.php'>controles</a></li>");
	            echo("<li><a href='asistencia.php'>asistencia</a></li>");
	            echo("<li><a href='estadisticas.php'>gráficos</a></li>");
	        	echo("<li><a href='alumnos.php'>alumnos</a></li>");
	        	echo("<li><a href='apoderados.php' style='letter-spacing:0px;'>apoderados</a></li>");
	        	echo("<li><a href='pagos.php'>pagos</a></li>");
	            echo("<li><a href='emails.php'>emails</a></li>");
	            echo("<li><a href='material.php'>material</a></li>");
	        echo("</ul>");
	echo("</nav>");
?>	