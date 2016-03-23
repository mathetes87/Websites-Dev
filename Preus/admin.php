<?php include_once 'includes/isAdmin.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Ensayos</title>
<link href="css.css" rel="stylesheet" type="text/css" />
<script src="jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="fancybox/jquery.fancybox.js"></script>
<link rel="stylesheet" href="/fancybox/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
<script type="text/javascript" src="js.js"></script>
<link href="forma.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="apprise2/apprise2.js"></script>
<link rel="stylesheet" href="apprise2/apprise2.css" type="text/css" /> 
<link rel="stylesheet" href="animate-custom.css" type="text/css" /> 
</head>

<body>
<?php 
	include_once 'includes/botonLogout.php';
	include_once 'includes/menuAdmin.php';
	echo '<div class="wrapperGlobal">';
	include_once 'includes/conectarDB.php';
	include_once 'includes/chooseYear.php';
	include_once 'includes/mysql_fetch_all.php';
	@session_start();
	$year = $_SESSION['year'];
	
	// Para combo box
	$query3 = "SELECT `ID Ensayo`, Ensayo FROM $tbl_puntajes WHERE (`Year` = $year OR `Year` IS NULL) GROUP BY `ID Ensayo` ORDER BY `ID Ensayo` DESC";
	$ensayos = mysql_fetch_all(mysql_query($query3));
	//echo $query3."</br>";
	//echo "Ensayos: ". count($ensayos);

	echo "<h1 class='margenLeft'>Puntajes del ensayo <span id='nombreEnsayo'></span> <a href='#' class='yearClick'>(". $year .")</a></h1>";
		
		// Combo box con listado de ensayos y boton de agregar nuevo ensayo
	echo "<div id='opcionesEnsayos'>";
		echo "<button id='botonEditarEnsayos' class='boton'>Editar Datos</button>";
		echo "<button id='botonNuevoEnsayo' class='boton'>Nuevo ensayo</button>";
		echo '<select id="selectEnsayos" name="ensayos">';
			for($i = 0; $i < count($ensayos); $i++){
				echo '<option value="'. $ensayos[$i][1] .'">'. ucwords($ensayos[$i][1]) .'</option>';	
			}
		echo '</select>';
		echo '<img src="images/delete.png" id="borrarEnsayo"> ';
		echo '<div id="checkDemre" style="cursor: pointer;">Demre<input type="checkbox" class="check"></div>';
	echo "</div>";
	
	//////////////// EN ESTE DIV SE INYECTA LA TABLA CON JQUERY DESPUES DE PEDIRLA CON AJAX DE includes/crearTablaEnsayos.php ////////////////
	echo "<div id='datosEnsayos'></div>";
?>
</div>
    
</body>
<script>
$(document).ready(function(){
	var optionsReload = 
	{
		override: false, 
		buttons:{
			confirm:{
				text: 'Aceptar',
				action:function(){ 
					Apprise('close', location.reload(true) ); 
				}
			},
		}
	};
	
	var editando = false;
	// EDITAR DATOS DE PUNTAJES Y GUARDAR CAMBIOS
	$('#botonEditarEnsayos').click(function(){
		// EDITAR DATOS
		if(!editando){
			editando = true;
			// Deshabilitar dropdown de elegir ensayos
			$('#selectEnsayos').attr("disabled", "disabled");
			// Escuchar si hace enter
			enter();
			// Cambiar texto del boton
			$(this).text("Guardar cambios");
			// Poner en gris los Rut
			$('#datosEnsayos .nombre, #datosEnsayos .numero').each(function(){
				$(this).addClass('textoGris');	
			});
			// Insertar textarea y poner texto
			$('table span').each(function(i){
				var celda = $(this);
				var anchoCelda = celda.closest("td").width()-4;
				var texto = $(this).text();
				celda.html('<input type="text" name="celda'+ i +'" class="inputCelda" id="celda'+ i +'" style="text-align: center">');
				celda.find('input').val(texto);	
			});
		}
		
		// GUARDAR LOS CAMBIOS
		else{
			guardar();			
		}
	});
	
	// FUNCION PARA GUARDAR ENSAYOS
	function guardar(){
		editando = false;
		// Crear arreglo bidimensional del porte necesario
		var filas = $('#datosEnsayos tr').size() - 2; // Por filas header y class="spacer"
		var col = $('#datosEnsayos th').size(); // Por columna con nombres esta oculta, no hay que restar
		// ESTRUCTURA ARREGLO: CELDA[FILA][COL]
		var celda = new Array(filas);
		  for (var i = 0; i < filas; i++) {
		    celda[i] = new Array(col);
		}
		// Pasar a arreglo
		for(var i = 1; i <= filas; i++){
			for(var j = 1; j <= col; j++){
				var valor = $("td[data-row='"+ i +"'][data-col='"+ (j+1) +"']").find('input').val();
				celda[i-1][j-1] = valor;
			}
		}
		
		// Guardar datos
		var demre = $(".check:checked").length;
		var ensayoCambiado = $("option:selected", '#selectEnsayos').attr('value');
		var stringed = JSON.stringify(celda);
		$.ajax({        
			type: "POST",
			url: "includes/guardarTablaEnsayos.php",
			data: {celdas: stringed, ensayo: ensayoCambiado, demre: demre},
			success: function(data) {
				Apprise('Cambios guardados con éxito<br>'+ data, {
					override: false, 
					buttons:{
						confirm:{
							text: 'Aceptar',
							action:function(){ 
								Apprise('close', location.reload(true) ); 
							}
						},
					}
				});
				
	      	}
	    });
	}
	
	// PODER PONER DEMRE SOLO CUANDO SE ESTA EDITANDO
	$('#checkDemre').click(function(e){
		comportamientoCheckDemre();
	});
	$(".check").click(function(event){
		this.checked=!this.checked;
	});
	
	function comportamientoCheckDemre(){
		// Resaltar boton editar
		if(!editando){
			$('#botonEditarEnsayos').addClass("wobble");
			var wait = window.setTimeout( function(){
				$('#botonEditarEnsayos').removeClass("wobble")},
				1300
			);	
		}
		else if($(".check:not(:checked)").length){
			$(".check:not(:checked)").prop('checked','checked');	
		}
		else{
			$(".check:checked").removeAttr('checked');	
		}
	}
	
	
	// CHECK DEMRE CUANDO LO ES (LLAMAR CUANDO SE INSERTA LA TABLA)
	function checkDemre(){
		var demre = $('body', window.parent.document).find(".data_table").data("demre");
		if(demre == '1'){
			$(".check:not(:checked)").prop('checked','checked');
		}
		else{
			$(".check:checked").removeAttr('checked');
		}
	}
	
	// RESALTAR NOMBRE DE ALUMNO Y NUMERO CUANDO TIENE FOCUS SU FILA
	$("#datosEnsayos").on('focus', 'input', function(){
		// Poner en gris todos los Rut
		$("#datosEnsayos .nombre, #datosEnsayos .numero").addClass('textoGris')
		// Quitarselo al de la fila actual
		$(this).closest('tr').children().removeClass('textoGris');
	});
	
	// POBLAR POR DEFECTO LA TABLA CON PRIMER ENSAYO EN LA LISTA
	var primerEnsayo = $('#selectEnsayos option:first').attr('value');
	$.ajax({
            url: 'includes/crearTablaEnsayos.php',
            type: "POST",
            data: ({ensayo: primerEnsayo}),
            success: function(data){
            	$('#nombreEnsayo').text(primerEnsayo);
                $("#datosEnsayos").html(data);
                checkDemre();
            }
	});
	
	// SE CAMBIA EL COMBO BOX
	$("#selectEnsayos").change(function(){
		var ensayoCambiado = $("option:selected", this).attr('value');
		$.ajax({
            url: 'includes/crearTablaEnsayos.php',
            type: "POST",
            data: ({ensayo: ensayoCambiado}),
            beforeSend: function(){
            	$('#nombreEnsayo').text(ensayoCambiado);
            	$("#datosEnsayos td").children('span').fadeOut('fast');
            },
            success: function(data){
                $("#datosEnsayos").html(data);
                // Verificar  si es DEMRE o no y cambiar checkbox
                checkDemre();
                
            }
        });
	});
	
	// CREAR NUEVO ENSAYO
	$('#botonNuevoEnsayo').click(function(){
		Apprise('Ingrese el nombre del nuevo ensayo', { 
			buttons: { 
				confirm: { 
					text: 'Crear', 
					action: function(ensayoNuevo) { 
						Apprise('close');
						if(ensayoNuevo.input != ""){
							$.ajax({
								url: 'includes/crearEnsayoNuevo.php',
								type: 'POST',
								data: ({ensayo: ensayoNuevo.input}),
								success: function(data){
									if(data == "existe"){
										Apprise("Ya existe un ensayo con este nombre", {
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
									else if(data == "creado"){
										Apprise("Ensayo creado con éxito", {
											override: false, 
											buttons:{
												confirm:{
													text: 'Aceptar',
													action:function(){ 
														Apprise('close', location.reload(true) ); 
													}
												},
											}
										});
									}
									else{
										Apprise("Hubo un error:</br>"+ data, {
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
								}
							});
						}
					} 
				},
				cancel:{
					text: 'Cancelar', 
					action: function() {
						Apprise('close'); 
					} 
				}
			},
			input: true,
		});	
	});
	
	// CLICK EN BORRAR ENSAYO
	$('#borrarEnsayo').click(function(){
		// CONFIRMAR QUE LO QUIERE BORRAR
		var nombreEnsayo = $("option:selected", '#selectEnsayos').attr('value');
		Apprise('¿Borrar completamente el ensayo <b>'+ nombreEnsayo +'</b>, y todos sus puntajes asociados?', { 
			buttons: { 
				confirm: { 
					text: 'Borrar', 
					action: function() { 
						Apprise('close'); 
						$.ajax({
							url: 'includes/borrarEnsayo.php',
							type: 'POST',
							data: ({ensayo: $("option:selected", '#selectEnsayos').attr('value')}),
							success: function(data){
								if(data == 'borrado'){
									Apprise("Ensayo eliminado con éxito", optionsReload);
								}
								else{
									Apprise('Ocurrió un error, este fue el mensaje de error:</br>'+ data, optionsReload);
								}
							}
						});
					} 
				},
				cancel:{
					text: 'Cancelar', 
					action: function() {
						Apprise('close'); 
					} 
				}
			},
			input: false,
		});
	});
	
	// AL APRETAR ENTER SE GUARDA LA LINEA
	function enter() {
		$(window).bind('keypress', function(e) {
			var code = e.keyCode || e.which;
			if(code == 13) { //Enter keycode
				editando = false;
				guardar();
			}
		});
	}
	
});
</script>
</html>