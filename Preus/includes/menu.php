<?php
  	include_once "includes/analytics.php";
	$linkClass = "class='modalbox' href='#fancyLogin'";
	@session_start();
	if(isset($_COOKIE['uname']) && isset($_COOKIE['upass'])){
		$linkClass = "href='login_success.php'";
	}
	echo("<nav id='barraLateral'>");
    	echo("<ul id='menu'>");
        	echo("<li><a href='index.php'>inicio</a></li>");
            echo("<li><a ".$linkClass." style='letter-spacing:0px;'>mi puntaje</a></li>");
            echo("<li><a style='letter-spacing:0px;' href='perfil.php' >mi perfil</a></li>");
	       	echo("<li><a href='materialAlumno.php'>material</a></li>");
            echo("<li><a class='modalbox' href='#fancyContacto'>contacto</a></li>");
        echo("</ul>");
    echo("</nav>");  
?>    
        