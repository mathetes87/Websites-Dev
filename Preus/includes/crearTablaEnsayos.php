<?php 
	include_once 'conectarDB.php';
	include_once 'infoProfesor.php';
	include_once 'mysql_fetch_all.php';
	@session_start();
	$year = $_SESSION['year'];
    $thisYear = date("Y");
	$ensayo = $_POST['ensayo'];
	
	$query1; $query2;
	
	if($year == $thisYear){
		// Para rellenar tabla
		$query1 = "SELECT RUT, Puntaje, Demre, Numeros, Algebra, Geometria, Datos FROM $tbl_puntajes WHERE (`Year` = $year OR `Year` IS NULL) AND Ensayo = '$ensayo' AND RUT != '$rutProfesor' AND RUT != '' ORDER BY RUT";
		
		// Para cantidad de alumnos
		$query2 = "SELECT RUT, Nombres, Apellidos FROM $tbl_usuarios WHERE (`Year` = $year OR `Year` IS NULL) AND RUT != '$rutProfesor' AND RUT != '' ORDER BY Grupo ASC, Apellidos ASC";
	}
	else{
		// Para rellenar tabla
		$query1 = "SELECT RUT, Puntaje, Demre, Numeros, Algebra, Geometria, Datos FROM $tbl_puntajes WHERE `Year` = $year AND Ensayo = '$ensayo' AND RUT != '$rutProfesor' AND RUT != '' ORDER BY RUT";
		
		// Para cantidad de alumnos y buscar nombres
		$query2 = "SELECT RUT, Nombres, Apellidos FROM $tbl_usuarios WHERE `Year` = $year AND RUT != '$rutProfesor' AND RUT != '' ORDER BY Grupo ASC, Apellidos ASC";	
	}
		
	// Para rellenar tabla
	$filasTabla = mysql_fetch_all(mysql_query($query1));
	//echo $query1 ."</br>";
	//echo "Filas: ". count($filasTabla) ."</br>";
	
	// Para cantidad de alumnos y buscar nombres
	$listaRuts = mysql_fetch_all(mysql_query($query2));
	$nRows = count($listaRuts);
	//echo $query2 ."</br>";
	//echo "   Alumnos: ". count($listaRuts) ."</br>";
	
	// Variables
	$row = 0; $col = 0;
	// Demre o no
	$demre = $filasTabla[0][2];
	
	//////// IMPRIMIR TABLA ////////
	echo "<table width='100%' class='data_table' cellspacing='3' cellpadding='5' data-demre='". $demre ."'>";
		
	titulosTabla();
	for($i = 0; $i < count($listaRuts); $i++) {
		$row++;
		create_table($listaRuts[$i]);
	}
	echo "<tr class='spacer'></tr>";				
	echo "</table>";
	//////// TABLA IMPRESA ////////
	
	function titulosTabla(){
		echo "<tr>
		<th>N°</th>
	    <th>Nombre</th>
	    <th>Puntaje</th>
	    <th>Números y Proporcionalidad</th>
	    <th>Álgebra y Funciones</th>
	    <th>Geometría</th>
	    <th>Datos y Azar</th>
	    </tr>";
	}
	
	function create_table($listaRuts) {
	    global $row, $col, $nRows, $filasTabla;
	    echo "<tr>";
    	// N° fila
    	echo "<td class='numero'>".$row."</td>";
	    
	    for($j = 0; $j < count($filasTabla[0])/2; $j++) {
	    	// Buscar el indice del rut actual en la lista de puntajes
	    	$found = false;
    		for($x = 0; $x < count($filasTabla); $x++){
    			if($listaRuts[0] == $filasTabla[$x][0]){
    				$found = true;
    				$i = $x;
    			}
    		}
        	if($j == 2){
        		
        	}
        	else if($j == 0){
        		// PARA COLUMNA DE NOMBRE Y TAMBIEN RUT OCULTO
				if(($listaRuts[2] != "" || $listaRuts[1] != "") && $found){
					// TIENE NOMBRE O APELLIDO
					$col++;
					echo "<td data-row='$row' data-col='$col' class='nombre' align='left'>".ucwords($listaRuts[2] .", ". $listaRuts[1])."</td>";
				}
				else if($found){
					// NO TIENE NOMBRE NI APELLIDO
					$col++;
					echo "<td data-row='$row' data-col='$col' class='nombre'>".ucwords($listaRuts[0])."</td>";	
				}
        			
        		if(!$found){
        			// No encontrado: imprimir error y en columna oculta el RUT, sumar a col con cada una
        			$col++;
        			if($listaRuts[2] != "" || $listaRuts[1] != ""){ // Tiene nombre o apellido
        				echo "<td data-row='$row' data-col='$col' class='nombre' align='left'><b>Sin puntaje</b> (". $listaRuts[2] .", ". $listaRuts[1] .")</td>";	
        			}
        			else{
        				echo "<td data-row='$row' data-col='$col' class='nombre' align='left'><b>Sin puntaje</b> (". $listaRuts[0] .")</td>";	
        			}
        			$col++;
        			echo "<td data-row='$row' data-col='$col' style='display: none;'><span>". $listaRuts[0] ."</span></td>";
        		}
        		else{
        			// SEGUNDA COLUMNA OCULTA CON RUTS, SI SE ENCONTRO EL RUT
		       		$col++;
			    	echo "<td data-row='$row' data-col='$col' style='display: none;'><span>". $listaRuts[0] ."</span></td>";	
        		}	
        	}
        	
        	else{
	        	$col++;
        		if($found){
	        		echo "<td data-row='$row' data-col='$col'><span>".ucwords($filasTabla[$i][$j])."</span></td>";
        		}
        		else{
	        		echo "<td data-row='$row' data-col='$col'><span></span></td>";
        		}
        	}        	
	    }
	    echo "</tr>";
	    $col = 0;
	}
?>