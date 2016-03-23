<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Perfil</title>
<link href="css.css" rel="stylesheet" type="text/css" />
<script src="jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js.js"></script>
<link rel="stylesheet" href="/fancybox/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
<script type="text/javascript" src="/fancybox/jquery.fancybox.pack.js?v=2.1.4"></script>
<link href="forma.css" rel="stylesheet" type="text/css" /> 
<script type="text/javascript" src="apprise2/apprise2.js"></script>
<link rel="stylesheet" href="apprise2/apprise2.css" type="text/css" /> 
</head>

<body class="paddingBody">
	<?php
		include_once 'includes/formaContacto.php';
		include_once 'includes/formaLogin.php'; 
		include_once 'includes/botonLogout.php';
		include_once 'includes/isLogueado.php';
	 		
		include_once 'includes/conectarDB.php';
	 		
		// Get informacion usuario logueado
		$queryUsuario="SELECT RUT, Apellidos, Nombres, Mail, Telefono, Colegio, Nem, Carrera, Universidad, `Nombre papa`, `Mail papa`, `Nombre mama`, `Mail mama`, `Celular mama`, `Promedio Matematica`, `Nacimiento`, `Telefono Casa`, `Direccion`, `Sexo`, Grupo, Primera FROM $tbl_usuarios WHERE `RUT`='{$_COOKIE['uname']}'";
		$usuario = mysql_query($queryUsuario)or die(mysql_error());
		$datosUsuario = mysql_fetch_array($usuario, MYSQL_NUM);
	?>
	<div id="divPerfil">
		<?php
			if($datosUsuario[20] == '0'){
		    	echo "<h1 id='tituloPerfil'>Mi perfil</h1>";
		    	include_once('includes/menu.php');
			}
			else{
				echo "<h1 id='tituloPerfil'>Antes de continuar rellena tus datos</h1>";
			}
		?>
	    <div id="containerBordePerfil">
	    	<h3 id="descripcionPerfil">Aquí puedes actualizar tus datos:</h3>
			<form id="perfil" name="form2" class="wufoo topLabel page" autocomplete="off" enctype="multipart/form-data" method="post" novalidate="" action="procesarCambiosAlumno.php">
				<ul>
					<li id="foli1">
						<label class="desc" id="title1" for="Field1">
							RUT <span class="req"></span>
						</label>
						<div>
							<input id="Field1" name="RUT" type="text" class="field requerido text small" value="" maxlength="15" nkeyup="validateRange(1, 'character');" required="">
						</div>
					</li>
					<li id="foli2">
						<label class="desc" id="title2" for="Field2">
							Contraseña <span class="req"></span>
						</label>
						<div>
							<input id="Field2" name="Password" type="password" class="field requerido text medium password" value="" maxlength="32" nkeyup="validateRange(2, 'character');">
						</div>
					</li>
					<li id="foli3">
						<label class="desc" id="title3" for="Field3">
							Repetir contraseña <span class="req"></span>
						</label>
						<div>
							<input id="Field3" name="repPassword" type="password" class="field requerido text medium repPassword" value="" maxlength="32" nkeyup="validateRange(3, 'character');">
						</div>
					</li>
					<li id="foli5">
						<label class="desc" id="title5" for="Field5">
							Nombre <span class="req"></span>
						</label>
						<div>
							<input id="Field5" name="Nombres" type="text" class="field requerido text medium" value="" maxlength="40" nkeyup="validateRange(5, 'character');">
						</div>
					</li>
					<li id="foli4">
						<label class="desc" id="title4" for="Field4">
							Apellido <span class="req"></span>
						</label>
						<div>
							<input id="Field4" name="Apellidos" type="text" class="field requerido text medium" value="" maxlength="40" nkeyup="validateRange(4, 'character');">
						</div>
					</li>
					<li id="foli10">
						<label class="desc" id="title10" for="Field6">
							Mail <span class="req"></span>
						</label>
						<div>
							<input id="Field6" name="Mail" type="email" spellcheck="false" class="field requerido text small" value="" maxlength="255" 
						</div>
					</li>
					<li id="foli11">
						<label class="desc" id="title11" for="Field7">
							Celular <span class="req"></span>
						</label>
						<div>
							<input id="Field7" class="field requerido text small" name="Telefono" ype="tel" maxlength="255" value=""> 
						</div>
					</li>
									<li>
						<label class="desc" id="title13" for="Field8">
							Colegio
						</label>
						<div>
							<input id="Field8" class="field requerido text small" name="Colegio" maxlength="255" value=""> 
						</div>
					</li>
					<li>
						<label class="desc" id="title13" for="Field9">
							NEM
						</label>
						<div>
							<input id="Field9" class="field requerido text small" name="Nem" maxlength="255" value=""> 
						</div>
					</li>
					<li>
						<label class="desc" id="title13" for="Field10">
							Carrera deseada
						</label>
						<div>
							<input id="Field10" class="field requerido text small" name="Carrera" maxlength="255" value=""> 
						</div>
					</li>
					<li>
						<label class="desc" id="title13" for="Field11">
							Universidad deseada
						</label>
						<div>
							<input id="Field11" class="field requerido text small" name="Universidad" maxlength="255" value=""> 
						</div>
					</li>
					<li>
						<label class="desc" id="title13" for="Field12">
							Nombre Papá
						</label>
						<div>
							<input id="Field12" class="field requerido text small" name="NombrePapa" maxlength="255" value=""> 
						</div>
					</li>
					<li>
						<label class="desc" id="title13" for="Field13">
							Mail Papá
						</label>
						<div>
							<input id="Field13" class="field requerido text small" name="MailPapa" maxlength="255" value=""> 
						</div>
					</li>
					<li>
						<label class="desc" id="title13" for="Field14">
							Nombre Mamá
						</label>
						<div>
							<input id="Field14" class="field requerido text small" name="NombreMama" maxlength="255" value=""> 
						</div>
					</li>
					<li>
						<label class="desc" id="title13" for="Field15">
							Mail Mamá
						</label>
						<div>
							<input id="Field15" class="field requerido text small" name="MailMama" maxlength="255" value=""> 
						</div>
					</li>
					<li>
						<label class="desc" id="title13" for="Field16">
							Celular Mamá
						</label>
						<div>
							<input id="Field16" class="field requerido text small" name="CelularMama" maxlength="255" value=""> 
						</div>
					</li>
					<li>
						<label class="desc" id="title13" for="Field17">
							Promedio Matemáticas en el Colegio
						</label>
						<div>
							<input id="Field17" class="field requerido text small" name="Matem" maxlength="255" value=""> 
						</div>
					</li>
					<li>
						<label class="desc" id="title13" for="Field18">
							Fecha de Nacimiento
						</label>
						<div>
							<input id="Field18" class="field requerido text small" name="Nacimiento" maxlength="255" value=""> 
						</div>
					</li>
					<li>
						<label class="desc" id="title13" for="Field19">
							Teléfono Fijo
						</label>
						<div>
							<input id="Field19" class="field text small" name="TelCasa" maxlength="255" value=""> 
						</div>
					</li>
					<li>
						<label class="desc" id="title13" for="Field20">
							Dirección Casa
						</label>
						<div>
							<input id="Field20" class="field requerido text small" name="Direccion" maxlength="255" value=""> 
						</div>
					</li>
					<li>
						<label class="desc" id="title13" for="Field21">
							Sexo (M o F)
						</label>
						<div>
							<input id="Field21" class="field requerido text small" name="Sexo" maxlength="1" value=""> 
						</div>
					</li>
					<li>
						<label class="desc" id="title13" for="Field22">
							Grupo (Número)
						</label>
						<div>
							<input id="Field22" class="field requerido text small" name="Grupo" maxlength="2" value=""> 
						</div>
					</li>
					<li class="buttons ">
						<div>
							<input class="btTxt submit boton" type="submit" value="Guardar">
						</div>
					</li>
				</ul>
			</form>
		</div>	
	</div>
    <div id="espacioBlanco"></div>	
</body>
<script>
	$(document).ready(function(){
		var alumno = <?php echo json_encode($datosUsuario); ?>;
		// [0] RUT, [1] Password, [2]Nombres, [3]Apellidos, [4]Mail, [5]Telefono, [6]Año Ingreso, [7]Grupo, [8] Colegio, [9] Nem, [10] Carrera, [11] Universidad, [12] Primera
		
		jQuery.each(alumno , function(index, value){
			switch(index)
			{
			case 0:
				var chars = value.length;
				var aux = value.substr(0, chars-7) + "." + value.substr(chars-7,3) + "." + value.substr(chars-4);
				var output = aux.substr(0, aux.length-1) + "-" + aux.substr(aux.length-1);
				$("#Field1").val(output);
				break;				
			default:
			  	$("#Field"+(index+3)).val(value);
			  	break;
			}
		});
		
		var repetidos = true;
		
		$(".repPassword").focusout(function(){
			// Si se escribió algo en repPassword
			if($(this).val() != ""){
				// Si es igual a Password
				if($(this).val() == $(".password").val()){
					$("#title3").text("Contraseñas coinciden").addClass("textoVerde").removeClass("textoRojo");
					$("#Field3").addClass("bordeVerde").removeClass("bordeRojo");
					repetidos = true;
				}
				// Si no son iguales
				else{
					$("#title3").text("Contraseñas no coinciden").addClass("textoRojo").removeClass("textoVerde");
					$("#Field3").addClass("bordeRojo").removeClass("bordeVerde");
					Apprise("Contraseñas no coinciden!", {
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
					repetidos = false;
				}
			}
			// Si no hay nada escrito en Repetir Password
			else{
				// Si hay algo escrito en password
				if($(".password").val() != ""){
					$("#title3").text("Repetir nueva contraseña *").removeClass("textoVerde textoRojo");  // Label
					$("#Field3").removeClass("bordeVerde bordeRojo"); // div
					repetidos = true;	
				}
			}
		});
		
		// ENVIAR FORMA POR AJAX PARA GUARDAR CAMBIOS
		$(".submit").click(function(e){
			e.preventDefault();
			$('#Field21').val (function () {
			    return this.value.toUpperCase();
			})
			if(!repetidos){
				Apprise("ERROR: Contraseñas no coinciden", {
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
				return;
			}
			else if($('#Field1').val() == ""){
				Apprise("ERROR: Debe ingresar algún Rut", {
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
				return;
			}
			else if($('#Field21').val() != "" && $('#Field21').val() != "M" && $('#Field21').val() != "F" ){
				Apprise("ERROR: Ingresar sólo M o F en campo 'Sexo'", {
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
				return;
			}
			else if($('.requerido').val() == ""){
				Apprise("ERROR: debes llenar todos los campos", {
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
				return;
			}
			var data = $('#perfil').serialize();
			$.ajax({        
				type: "POST",
				url: "procesarCambiosAlumno.php",
				data: data,
				success: function(data) {
					if(data == 'ok'){
						Apprise("Cambios guardados con éxito", {
							override: false, 
							buttons:{
								confirm:{
									text: 'Aceptar',
									action:function(){ 
										location.reload(true); 
									}
								},
							}
						});
					}
					else{
						alert("Error: "+data);
					}
		      	}
		    });
		});
		
		var requeridos = false;
		
		$("#perfil").submit(function(e){
			if($datosUsuario[11] == 0){
				return;
			}
			requeridos = true;
			
		    if( !repetidos || !requeridos){
		    	return false;
		    }
		});
	});
</script>
</html>