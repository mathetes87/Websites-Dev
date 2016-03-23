<body class="paddingBody">
    <?php
        include_once 'includes/mysql_fetch_all.php';
       	include_once 'includes/conectarDB.php';
		// $tbl_usuarios="usuarios"; // Table name 1
		// $tbl_puntajes="puntajes"; // Table name 2

		
		$queryUsuarios="SELECT * FROM $tbl_usuarios WHERE RUT=$rutDesdeFuera ";
		$resUsuarios = mysql_query($queryUsuarios);
		$datosUsuario = @mysql_fetch_array($resUsuarios, MYSQL_NUM);

		$queryPuntajes="SELECT Ensayo, Puntaje, Demre, Numeros as Cone, Algebra as Plan, Geometria as Voca, Datos as Comp FROM $tbl_puntajes WHERE RUT=$rutDesdeFuera  ORDER BY `ID Ensayo`";
		$resPuntajes = mysql_query($queryPuntajes);
		//echo $queryPuntajes;
		$allPuntajes = mysql_fetch_all($resPuntajes);
		
		$queryPromedios = "SELECT AVG(NULLIF(Numeros ,0))/11 as Cone, AVG(NULLIF(Algebra ,0))/31 as Plan, AVG(NULLIF(Geometria ,0))/22 as Voca, AVG(NULLIF(Datos ,0))/11 as Comp FROM $tbl_puntajes WHERE Puntaje IS NOT NULL AND PUNTAJE != 0 AND PUNTAJE != ''  AND RUT = $rutDesdeFuera";
		$resPromedios = mysql_fetch_all(mysql_query($queryPromedios));
		$promedioMin = 1;
		$iMin = -1;
		$nombreMin = array("Numeros", "Álgebra", "Geometría", "Datos y azar");
		
		
		// GUARDAR PROMEDIO MAS BAJO ENTRE SECCIONES
		for($i = 0; $i < count($resPromedios[0])/2; $i++){
			if($resPromedios[0][$i] < $promedioMin){
				$promedioMin = $resPromedios[0][$i];
				$iMin = $i;
			}			
		}
		
		echo "<h1 class='margenLeft'>Puntajes</h1>";
		
		// Estructura arreglo: [0] RUT, [1] Password, [2]Nombres, [3]Apellidos, [4]Mail, [5]Telefono, [6]Año Ingreso, [7]Grupo
		// SI SE AGREGO DESDE ALUMNO
		if($desdeAlumno){
			include_once 'includes/isLogueado.php';
		    include_once 'includes/menu.php';
			include_once 'includes/formaContacto.php';
			include_once 'includes/formaLogin.php';
		 	include_once 'includes/botonLogout.php';
			if(mysql_num_rows($resPuntajes) == 0){
				echo( "<h2>Hola ".$datosUsuario[2].", todavía no están ingresados los resultados de tus ensayos:</h2>");	
			}	
			else{
				echo( "<h2>Hola ".$datosUsuario[2].", estos son los resultados de tus ensayos:</h2>");	
			}
			if($promedioMin < 0.9 && $promedioMin > 0){
				echo "<h3>Estás muy débil en ". $nombreMin[$iMin] .", puedes encontrar material de apoyo <a href='materialAlumno.php'>acá</a></h3>";
				$_SESSION["material"] = str_replace(' ', '_', $nombreMin[$iMin]);	
			}
			
		}
		else{
			if(mysql_num_rows($resPuntajes) == 0){
				echo( "<h2>Todavía no están ingresados los resultados de los ensayos de ".$datosUsuario[2].":</h2>");	
			}	
			else{
				echo( "<h2>Hola, estos son los resultados hasta ahora de ".$datosUsuario[2].":</h2>");	
			}
		}
		
	?>
	
	<div id="containerGraficoAlumno">	
		<div id="grafico"></div>
	</div>
	<div id="choices" style="display: ;"></div>	
	
	<?php
		//////// IMPRIMIR TABLA ////////
		echo "<a id='mas' href='#'>Mostrar tabla</a><div class='spacer'></div>";
		echo "<span id='tablaOculta' style='display: none;'>";
		echo "<div id='datosUsuario'>";
			echo "<table width='100%' class='data_table' cellspacing='3' cellpadding='5'>";
				
			titulosTabla();
			for($i = 0; $i < count($allPuntajes); $i++) {
				create_table($allPuntajes[$i]);
			}
			echo "<tr class='spacer'></tr>";	
			echo "</table>";
		echo "</div>";		
		echo "</span>";
		//////// TABLA IMPRESA ////////
		
		function titulosTabla(){
			echo "<tr>";
		    echo "<td>Número</td>";
		    echo "<td>Ensayo</td>";
		    echo "<td>Puntaje</td>";
		    echo "<td>Números y Proporcionalidad</td>";
		    echo "<td>Álgebra y Funciones</td>";
		    echo "<td>Geometría</td>";
		    echo "<td>Datos y Azar</td>";
		    echo "</tr>";
		}

		function create_table($dataArr) {
		    echo "<tr>";
		    echo "<td></td>";
		    $nohecho = false;
		    for($j = 0; $j < count($dataArr)/2; $j++) {
		    	if($j == 1 && $dataArr[$j] == 0){
		    		echo "<td>---</td>";
		    		$nohecho = true;
		    	}
		    	else if($j != 2 && !$nohecho){
		        	echo "<td>".ucwords($dataArr[$j])."</td>";
		    	}
		    	else if($j != 2 && $nohecho){
		    		echo "<td>".ucwords("---")."</td>";
		    	}
		    }
		    echo "</tr>";
		}
	?>
</body>
<script>
	$(window).load(function(){
		$nFilas = 0;
		$.each( $('tr'), function( key, value ) {
			if($(value).children().first().text() == ''){
				$(value).children().first().text(key);		
			}
		});
		var puntajes = <?php echo json_encode($allPuntajes); ?>;
		var datosAlumno = [];
		var datosDemre = [];
		var datosSec = [];
		var datosSec1 = [], datosSec2 = [], datosSec3 = [], datosSec4 = [];
		var seccion = false;

		jQuery.each(puntajes , function(index, value){
			$nFilas++;
			if(parseInt(value.Puntaje) == 0){
				value.Puntaje = null;	
			}
			else{
				//Si es demre
				if(parseInt(value.Demre) == 1){
					datosDemre.push([ index+1, parseInt(value.Puntaje) ]);	
				}
				datosAlumno.push([ index+1, parseInt(value.Puntaje) ]);
				if(value.Cone != null || value.Plan != null || value.Voca != null || value.Comp){
					datosSec1.push([ index+1, (value.Cone/11) ]);
					datosSec2.push([ index+1, (value.Plan/31) ]);
					datosSec3.push([ index+1, (value.Voca/22) ]);
					datosSec4.push([ index+1, (value.Comp/11) ]);
				}
				//alert(value.Cone +", "+ value.Plan +", "+ value.Voca +", "+ value.Comp);
			}
		});
		
		var datasets = {
			"alumno": {
              	label: "Todos mis ensayos",
              	data: datosAlumno
            },
            "demre": {
              	label: "Ensayos Demre",
              	data: datosDemre
            }
        };
        var datasSec = {
        	"datosSec4": {
	        	label: "Datos y Azar",
	        	data: datosSec4
	        },
			"datosSec2": {
	        	label: "Álgebra y Funciones",
	        	data: datosSec2	
	        },
			"datosSec3": {
	        	label: "Geometría",
	        	data: datosSec3	
	        },
	        "datosSec1": {
	        	label: "Números y Proporcionalidad",
	        	data: datosSec1	
	        }	        
        };
        var options = {
        	series: {
		        lines: { show: true, fill:false },
		        points: { show: true },
		    },
		    grid: {
			    clickable: true,
			    hoverable: true,
			    autoHighlight: true,
			    backgroundColor: "#F9F9F9"
			},
			legend: {
			    show: true,
			    position: "se"
			},
			xaxis: {
				tickSize: 1,
				tickDecimals: 0,
				tickFormatter: function(val, axis) { 
			    	if(val >= axis.max){
			    		return "Ensayo";
			    	}
			    	else{
			    		return Math.round(val);
			    	}
		    	}
			},
			yaxis: {
			    max: 900,
			    tickFormatter: function(val, axis) { 
			    	if(val >= axis.max && !seccion){
			    		return "Puntaje";
			    	}
			    	else{
			    		return Math.round(val);
			    	}
		    	}
			}	
        };
        
        var optionsSec = {
	    	series: {
	    		stack: false,
		        lines: { show: true, fill: false },
		        points: { show: true },
		    },
		    grid: {
			    clickable: true,
			    hoverable: true,
			    autoHighlight: true,
			    backgroundColor: "#F9F9F9"
			},
			legend: {
			    show: true,
			    position: "se"
			},
			xaxis: {
				tickSize: 1,
				tickDecimals: 0,
				tickFormatter: function(val, axis) { 
			    	if(val >= axis.max && !seccion){
			    		return "Ensayo";
			    	}
			    	else{
			    		return Math.round(val);
			    	}
		    	}
			},
			yaxis: {
			    tickFormatter: function(val, axis) {
			    	if(val >= axis.max && seccion){
			    		return "Correctas";
			    	}
			    	else{
			    		return Math.round(val*100)+"%";
			    	}
		    	}
			}	
   		};
   		
		function quitarDecimales(val, axis, string){
		
			if((val/2) >= axis.max){
				return string;
			}
			else{
			
			}
		}

        // Para que no cambien de color
        var i = 0;
		$.each(datasets, function(key, val) {
			val.color = i;
			++i;
		});	
        
        // insert checkboxes 
		var choiceContainer = $("#choices");
		$.each(datasets, function(key, val) {
			if(val.label.indexOf("Secciones") == -1){
				choiceContainer.append("<div class='puntaje'><input type='checkbox' name='" + key +
				"' checked='checked' id='id" + key + "'></input>" +
				"<label for='id" + key + "'>"
				+ val.label + "</label></div>");
			}
		});
		choiceContainer.append("<div class='seccion'><input type='checkbox' name='secciones' id='idsecciones'></input><label for='idsecciones'>Secciones</label></div>");

		function plotAccordingToChoices() {
			
			var data = [];
			
			if(!seccion){
				data.length = 0;
				choiceContainer.find("input:checked").each(function () {
					var key = $(this).attr("name");
					if (key && datasets[key]) {
						data.push(datasets[key]);
					}
				});
				if (data.length > 0) {
					$.plot("#grafico", data, options);
				}	
			}
			else{
				data.length = 0;
				$.each( datasSec, function( key, value ) {
				  data.push(datasSec[key]);
				});
				if (data.length > 0) {
					$.plot("#grafico", data, optionsSec);
				}
			}
		}
		
		function tickets(){
			choiceContainer.find("input").each(function () {
				if(seccion){
					if($(this).parent().hasClass('puntaje')){
						$(this).attr('checked', false);
					}	
				}
				else{
					if($(this).parent().hasClass('seccion')){
						$(this).attr('checked', false);
					}
				}
				
			});
		}

		function showTooltip(x, y, contents) {
			$("<div id='tooltip'>" + contents + "</div>").css({
				position: "absolute",
				display: "none",
				top: y + 5,
				left: x + 5,
				border: "1px solid #fdd",
				padding: "2px",
				"background-color": "white",
				opacity: 0.80
			}).appendTo("body").fadeIn(200);
		}
		
		function capitaliseFirstLetter(string){
		    return string.charAt(0).toUpperCase() + string.slice(1);
		}

		var previousPoint = null;
		$("#grafico").bind("plothover", function (event, pos, item) {
			if (true) { // Si se quiere deshabilitar tooltip poner false
				if (item) {
					if (previousPoint != item.dataIndex) {
	
						previousPoint = item.dataIndex;
	
						$("#tooltip").remove();
						var x = item.datapoint[0].toFixed(2),
						y = item.datapoint[1].toFixed(2);
						
						if(seccion){
							y4 = Math.round(datosSec1[item.dataIndex][1]*11);
							y3 = Math.round(datosSec2[item.dataIndex][1]*31);
							y2 = Math.round(datosSec3[item.dataIndex][1]*22);
							y1 = Math.round(datosSec4[item.dataIndex][1]*11);
							showTooltip(item.pageX, item.pageY,
						    "<strong> "+ capitaliseFirstLetter(puntajes[Math.round(x)-1][0]) +"</strong></br>" +
						    "Datos y Azar: " + y1 + "</br>" + 
						    "Álgebra y Funciones: " + y3 + "</br>" + 
						    "Geometría: " + y2 + "</br>" + 
						    "Números y Proporcionalidad: " + y4
						    );
						}
						else{
							showTooltip(item.pageX, item.pageY,
						    //item.series.label + " of " + x + " = " + y);
							capitaliseFirstLetter(puntajes[Math.round(x)-1][0]) +": <b>" + Math.round(y) + " puntos</b>");	
						}
					}
				} else {
					$("#tooltip").remove();
					previousPoint = null;            
				}
			}
		});
		
		// Fade leyenda con mouse over
		$("#containerGraficoAlumno").on("mouseenter", ".legend", function(){
			$(this).css("opacity","0");
			setTimeout(function(){$(".legend").css("display","none");},1000);
		});
		$("#containerGraficoAlumno").mouseleave(function(){
			$(".legend").css("display","inline");
			$(".legend").css("opacity","1");	
		});
			
		choiceContainer.find("input").click(function(){
			var parent = $(this).parent();
			if(parent.hasClass('puntaje')){
				// Si estaba en seccion borrar tickets
				seccion = false;
				tickets($(this));
			}
			else{
				seccion = true;
				tickets($(this));
			}
			plotAccordingToChoices();
		});

		plotAccordingToChoices();

		
		$('#grafico').resize(function(){});
	});
</script>
</html>