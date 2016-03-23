<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Información</title>
<link href="css.css" rel="stylesheet" type="text/css" />
<script src="jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js.js"></script>
<link rel="stylesheet" href="/fancybox/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
<script type="text/javascript" src="/fancybox/jquery.fancybox.pack.js?v=2.1.4"></script>
<link href="forma.css" rel="stylesheet" type="text/css" /> 
</head>

<body class="paddingBody">
    <?php
        include_once('includes/menuVisita.php');
		include_once('includes/formaContacto.php');
		include_once('includes/formaLogin.php');
 		include_once('includes/botonLogout.php');   
		include_once 'includes/infoProfesor.php';     
    ?>
	<h1 class='margenLeft tituloInfo'>Preuniversitario de Matemática <?php echo date("Y"); ?></h1>
	<div id="informacion">
		<h3>María Ignacia Lira Martínez<br>
		Profesora de Matemáticas y Física U.C.</h3>
		
		<h2>I.- Sistema de trabajo:</h2>
		
		<h4>Grupo semanal</h4>
			<p>
				-	Grupos de máximo 5 alumnos ( por niveles ).<br>
				-	Una hora y treinta minutos (1 1/2 hora ) semanal.<br>
				-	Guías de  materias con ejercitación de preguntas de selección múltiple. <br>
				-	Abundante material de ejercitación tipo PSU.<br>
				-	Clases  semanales donde se trabaja un contenido, se ejercita y resuelven dudas de la guía anterior. En cada clase se realiza un control  con 10 preguntas acumulativas con tiempo. Tarea semanal.<br>
				-	Posibilidad de recuperar, en caso de enfermedad.<br>
				-	Mes de Julio, 2 semanas de vacaciones invierno (igual a los colegios), y material de trabajo para las vacaciones. <br>
				-	Mes de Septiembre, 1 semana de vacaciones, (igual a los colegios), y material de trabajo para las vacaciones. <br>
				-	Mes de Noviembre  INTENSIVO de Ensayos Generales presénciales (3 a la semana) Tarea para los días que no da ensayo. <br>
			</p>
		
		<h2>II.- Sistema de pago</h2>
		
			<p>
				-	<?php echo $montoMensual ?> mensual, a pagar en la primera semana del mes (9 meses , de Marzo a  Noviembre incluido).
			</p>
		
		
		
		<h2>III.-  Profesora y dirección</h2>
			<p>
				María Ignacia Lira M. <br>
				Fernández Mira 1005 , Las Condes<br>			
				22438483 – 0-94441593<br>                
				liramariaignacia@gmail.com
			</p>
		
		
		<h2>IV.- Fechas </h2>
			<p>
				-	Fecha de inicio : 2ª semana de Marzo<br>
				-	Fecha de término :  Jueves  anterior a la PSU
			</p>
			

	</div>
</body>
</html>
