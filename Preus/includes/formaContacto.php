<?php include_once 'includes/infoProfesor.php'; ?>
<div id="fancyContacto">
	<div id="linea1">
		<div id="cajaWoman">
			<img id="woman" src="/images/woman.png"/>
		</div>
		<div id="cajaNombre">
			<span id="nombre"><?php echo $nombreProfesor;?></span>
		</div>
	</div>
	<div id="linea2">
		<div id="cajaWoman">
			<img id="woman" src="/images/phone.png"/>
		</div>
		<div id="cajaNombre">
			<span id="nombre"><?php echo $telefonoProfesor;?></span>
		</div>
	</div>
	<form id="contact" action="#" method="post" name="contact">
		<div class="bloque">
			<label for="email">Tu E-mail</label>
			<input id="email" class="txt" type="email" name="email" />
		</div>	
	
		<div class="bloque">
			<label for="msg">Tu mensaje</label>
			<textarea id="msg" class="txtarea" name="msg"></textarea>
		</div>
		
		<button id="send" class="boton">Enviar</button></form>
</div>