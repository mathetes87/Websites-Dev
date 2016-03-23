function fancyrise(texto, options, callback){
	// CERRAR OTROS ANTES
	close();
	// BIND TECLA ENTER Y ESC CUANDO SE ABRE
	var callBoo = false;
	if(typeof callback == 'function'){
		callBoo = true;
	}
	
	var html = "<div class='fancyriseWrapper'>";	
	
	if(options && options.header){
		var header = "<h2 class='headerFancyrise'>"+ options.titulo +"</h2>";
		html += header;
	}
		
	if(texto){
		var text = "<p class='textoFancyrise'>"+ texto +"</p>";
		html += text;	
	}
	
	if(options){
		if(options.input){
			html += "<input type='text' class='inputFancyrise' />";
		}
		
		if(options.botones){
			html += "<div class='botonesWrapper'><div style='display: inline-block;'>";
				$.each(options.botones, function(key, val){
					html += "<button class='boton' id='"+ key +"'>"+ val +"</button>";	
				});
			html += "</div></div>";
		}
	}
	
	$('body').on("click", ".fancyriseWrapper button", function(){
		var output;
		if(options && options.input){
			if($(this).attr("id") == "aceptar"){
				output = $(".inputFancyrise").val();
			}
			else{
				output = $(this).attr("id");
			}
		}
		else{
			output = $(this).attr("id");	
		}
		if(callBoo){
			callback(output);
		}
		$.fancybox.close();
	});
	
	html += "</div>";
	$.fancybox.open(html, {closeBtn: false});
	
	function close(){
		$.fancybox.close();
	}	
	
	$(document).on('keyup', ".fancyriseWrapper", function(e) {
		var code = e.keyCode || e.which;
		if(code == 13) { // Enter 
			$.fancybox.close();
			if(callBoo){
				if(options && options.input){
					callback($(".inputFancyrise").val());
				}
				else{
					callback($(".fancyriseWrapper button:first-child").attr("id"));
				}
			}	
		}
		else if(code == 27){ // Esc
			$.fancybox.close();	
		}
	});

	if(options && options.input){
		$('.inputFancyrise').focus();	
	}
	else{
		$('.fancyriseWrapper #aceptar').focus();
	}
}