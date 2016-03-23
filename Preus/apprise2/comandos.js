<script type="text/javascript" src="apprise2/apprise2.js"></script>
<link rel="stylesheet" href="apprise2/apprise2.css" type="text/css" /> 

// UN BOTON
var optionsSimple = 
	{
		override: false, 
		buttons:{
			confirm:{
				text: 'Aceptar',
				action:function(){ 
					Apprise('close'); 
				}
			},
		}
	};

// UN BOTON, RELOAD CUANDO SE CIERRA
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

// DOS BOTONES CON FUNCIONES (CON APPRISE EN FUNCIONES)
var options = 
	{ 
		buttons: { 
			confirm: { 
				text: 'Eliminar', 
				action: function() { 
					Apprise('close'); 
					$.ajax({
						url: 'includes/borrarAlumno.php',
						type: 'POST',
						data: ({rut: fila.children("td:nth-child(2)").text()}),
						success: function(data){
							if(data == 'borrado'){
								Apprise("Alumno eliminado con éxito", optionsReload);
							}
							else{
								Apprise("'Ocurrió un error, este fue el mensaje de error:</br>'+ data", optionsReload);
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
	};