<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="author" content="Troj.org web media">
<meta name="Keywords" content="preuniversitario,psu,lenguaje,santiago,chile,preu,jesus,gonzalez" />
<meta name="description" content="Preuniversitario PSU de lenguaje de Jesús González en Santiago, Chile. Aquí puedes obtener información relevante de horarios, plan de trabajo, etc.">
<title>Preuniversitario</title>
<link href="css.css" rel="stylesheet" type="text/css" />
<script src="jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="jquery.backstretch.min.js"></script>
<script type="text/javascript" src="js.js"></script>
<link rel="stylesheet" href="/fancybox/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
<script type="text/javascript" src="/fancybox/jquery.fancybox.pack.js?v=2.1.4"></script>
<link href="forma.css" rel="stylesheet" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Courgette' rel='stylesheet' type='text/css'>

</head>
<body id="bodyIndex">
    <?php
    	include 'includes/analytics.php';
		include_once 'includes/infoProfesor.php';
    	echo "<div id='imgPreu'><span>Preuniversitario de Matemáticas</span></div>	
    		<div id='imgNombre'><span>María Ignacia Lira</span></div>";
    	$linkClass = "class='modalbox' href='#fancyLogin'";
		@session_start();
		if(isset($_COOKIE['uname']) && isset($_COOKIE['upass'])){
			if($_COOKIE['uname'] == $rutProfesor){
				$linkClass = "href='admin.php'";
			}
			else{
				$linkClass = "href='login_success.php'";
			}
		}
		echo "<nav id='barraHorizontal'>
		    	<ul id='menuIndex'>
		            <li><a id='primerLink' href='info.php'>información</a></li>
		            <li><a ".$linkClass.">mi puntaje</a></li>
		       		<li><a  class='modalbox' href='#fancyContacto'>contacto</a></li>
		   		</ul>
			</nav>";
		include_once('includes/formaContacto.php');
		include_once('includes/formaLogin.php');
	?>
</body>
<script>
$(document).ready(function(){
	$.backstretch([
      		"images/index/1.jpg"
    		, "images/index/2.jpg"
    		, "images/index/3.jpg"
    		, "images/index/4.jpg"
  	], {duration: 3000, fade: 750});
	$('#botonLogin').click(function(e){
		e.preventDefault();
		var username = $("#myusername").val();
		var password = $("#mypassword").val();
		$.ajax({        
			type: "POST",
			url: "checklogin.php",
			data: {myusername: username, mypassword: password},
			success: function(data) {
				if(data != 'error'){
					window.location.href = data;
				}
				else{
					$("#botonLogin").after("<span style='display:inline; margin-left: 3px;vertical-align: middle;' class='textoRojo'>Usuario o contraseña equivocado</span>");
				}
	      	}
	    }); 
	});	
});
</script>
</html>