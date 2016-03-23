$(window).load(function(){
		// Para menu de visitas
		setTimeout(function() {
			$(".menuVisita").css("left", "-10px");
			setTimeout(function() {
      			$(".menuVisita").css("left", "-180px");
			}, 1000);
		}, 500);
		
		// Para menu de alumnos y profesor
		setTimeout(function() {
  			$("#barraLateral").css("left", "-180px");
		}, 500);

		$("#barraLateral").hover(
			function(){
				$(this).css('left', '-10px');
			}, 
			function(){
				$(this).css('left', '-180px');
			});
		
		$('#barraLateral').mouseover(function(){
			$(this).css('left', '-10px');	
		});
		
		$('#barraLateral a').click(function(e) {
			if($("#barraLateral").css("left") == "-180px"){
		    	e.preventDefault();
			}
			if($(this).hasClass("modalbox")){
				if($("#barraLateral").css("left") != "-10px"){
			    	$(this).attr("href", "#").removeClass("modalbox").addClass("sinModalbox");
				}
			}
			else if($(this).hasClass("sinModalbox")){
				if($("#barraLateral").css("left") != "-180px"){
			    	$(this).attr("href", "#fancyContacto").removeClass("sinModalbox").addClass("modalbox");
				}
			}
			setTimeout(function() {
		  		$("#barraLateral").css({"left": "-180px"});
			}, 3000);
			});
	});

// Otro
function validateEmail(email) { 
		var reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return reg.test(email);
	}

$(document).ready(function() {	
	// Navegacion en tablas con flechas
	$(".flechas").on("keydown", 'input', function(e) {
		var code = e.keyCode || e.which;
		var fila = $(this).closest("tr").index();
		var col = $(this).closest("td").index();
		if(code == 37) {
			// Izquierda
			$(".flechas").find("tr").eq(fila).find("td").eq(col - 1).find("input").focus();
			console.log("izquierda");
		}
		else if(code == 38){
			// Arriba
			$(".flechas").find("tr").eq(fila - 1).find("td").eq(col).find("input").focus();
		}
		else if(code == 39){
			// Derecha
			$(".flechas").find("tr").eq(fila).find("td").eq(col + 1).find("input").focus();
		}
		else if(code == 40){
			// Abajo
			$(".flechas").find("tr").eq(fila + 1).find("td").eq(col).find("input").focus();
		}
	});
	
	// Barra arriba
	if($('#barraArriba').length > 0){
		$('body').addClass("conBarraArriba");
	}
	
	// Dropdown años cambia
	$('.dropYears').change(function(){
		var year = $("option:selected", this).text();
		if(year == "Cambiar año"){
			return;
		}
		
		// Guardar este nuevo año en la sesión y reload
		$.ajax({
			type:  'post',
		   	url: 'includes/changeYear.php',
		   	data: { 
		        'year': year
		    },
		    success:  function (response) {
                location.reload(); 
            },
		    error: function (msg, status, error) {
		        Apprise("Status: "+ status +", Error: "+ error, {
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
		});
	});
	
	// Se quiere cambiar de año
	$(".yearClick").click(function(){
		$(".dropYears").fadeIn('slow');
		$(".dropYearsHorario").fadeIn('slow');
	});
	// Barra lateral
	var porcentajeAltura = 0.38;
	$(window).resize(function() {
	    windowResize();
	});
	
	function windowResize(){
		// Barra arriba
		var widthArriba = $(window).width();
		$('#barraArriba').css({'width':widthArriba});
		
		// Barra lateral
		var height = $(window).height()*porcentajeAltura*1.2; // Le sumo un 20% por padding
	    $('#barraLateral #menu').css('height', height);
	    scrolling();
	}
	var distanceTop = $(document).scrollTop();
	var scrollTimer = null;
	function scrolling(){
		distanceTop = $(document).scrollTop();;
		$('#barraLateral').css('top', ((($(window).height() - $('#barraLateral').height())/2) + distanceTop + 'px'));
	}
	
	var textoOriginal = $('#mas').text();
	$('#mas').on('click',function(e){
		$('#tablaOculta').slideToggle('fast');
		if($('#tablaOculta').is(':visible')){
		  $('#tablaOculta').css('display','');
		}
		if($('#mas').text() == textoOriginal){
		  $('#mas').text("Esconder tablas");
		}	 
		else{
		  $('#mas').text(textoOriginal);
		} 	
	});
	
	
	// Leyenda grafico profesor
	/**
	$("#containerGrafico").on("mouseenter", ".legend", function(){
		$(this).toggleClass("moverLeyenda");
		var anchoContainer = $("#containerGrafico").width();
		var anchoVentana = $(window).width();
		var anchoLeyenda = $(".legend div").width();
		if($(this).hasClass("moverLeyenda")){
			// Mover a la izquierda con right
			$(this).children().css("right", anchoContainer - anchoLeyenda - 55);
		}
		else{
			// Mover a la derecha con right 13px
			$(this).children().css("right", 13);
		}
	});
	
	// Leyenda grafico alumno
	$("#containerGraficoAlumno").on("mouseenter", ".legend", function(){
		$(this).toggleClass("moverLeyenda");
		var anchoContainer = $("#containerGraficoAlumno").width();
		var anchoVentana = $(window).width();
		var anchoLeyenda = $(".legend div").width();
		if($(this).hasClass("moverLeyenda")){
			// Mover a la izquierda con right
			$(this).children().css("right", anchoContainer - anchoLeyenda - 65);
		}
		else{
			// Mover a la derecha con right 13px
			$(this).children().css("right", 13);
		}
	});
	**/
	
	// Barra lateral
	var subMenus = $("#barraLateral #menu li").length;
	$('#barraLateral #menu li').css('height', (100/subMenus)+'%');
	windowResize();
	$('#barraLateral').css('top', (1-($('#barraLateral').height()/$(window).height()))/2*100+'%');
	$(window).scroll(function(){
		if (scrollTimer) {
	        clearTimeout(scrollTimer);   // clear any previous pending timer
	    }
	    scrollTimer = setTimeout(scrolling, 100);   // set new timer	
	});
	
	// Logout
	$(".containerLogout").on("click", function(){
		console.log("Logout");
		window.location.replace('logout.php');	
	});
	if($(".modalbox").size() > 0){
		$(".modalbox").fancybox({closeBtn: false});
	}
	$("#contact").submit(function() { return false; });
	
	// Forma de agregar alumno
	$("#saveForm").on("click", function(){
		$('#wrapperFormaAlumno').fadeOut(function() {  
			$("#fancyAlumno").append("<p id='mensaje'>Guardando...</p>");
			$.fancybox.update();
		});
		$.ajax({
			type: 'POST',
			url: 'includes/agregarAlumno.php',
			data: $("#perfil").serialize(),
			success: function(data) {
				if(data == "true") {
					$("#fancyAlumno").replaceWith("<p><strong>El alumno se ha guardado con éxito</strong></p>");
					setTimeout(function(){
						location.reload(true);
					}, 2000);
				}
				else if(data == "existe"){
					$("#fancyAlumno").replaceWith("<p class='textoRojo'><strong>ERROR: El RUT ingresado ya existe</strong></p>");
					setTimeout(function(){
						location.reload(true);
					}, 3000);
					
					/*$("#mensaje").replaceWith("<p><strong>El RUT ingresado ya existe</strong></p>");
					$("#saveForm").css("display", "");*/
				}
				else{
					$("#fancyAlumno").replaceWith("<p class='textoRojo'><strong>ERROR: "+ data +"</strong></p>");
					setTimeout(function(){
						location.reload(true);
					}, 30000);
				}
			}
		});
		
	});
	
	// Forma de contacto
	$("#send").on("click", function(){
		var emailval  = $("#email").val();
		var msgval    = $("#msg").val();
		var msglen    = msgval.length;
		var mailvalid = validateEmail(emailval);
		
		if(mailvalid == false) {
			$("#email").addClass("error");
		}
		else if(mailvalid == true){
			$("#email").removeClass("error");
		}
		
		if(msglen < 4) {
			$("#msg").addClass("error");
		}
		else if(msglen >= 4){
			$("#msg").removeClass("error");
		}
		
		if(mailvalid == true && msglen >= 4) {
			// if both validate we attempt to send the e-mail
			// first we hide the submit btn so the user doesnt click twice
			$("#send").replaceWith("<em>enviando...</em>");
			
			$.ajax({
				type: 'POST',
				url: 'includes/sendmessage.php',
				data: $("#contact").serialize(),
				success: function(data) {
					if(data == "true") {
						$("#contact").fadeOut("fast", function(){
							$(this).before("<p><strong>Su mensaje ha sido enviado. Muchas gracias.</strong></p>");
							setTimeout("$.fancybox.close()", 1000);
						});
					}
				}
			});
		}
	});	
});