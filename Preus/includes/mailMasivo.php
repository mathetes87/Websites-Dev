<?php
	include_once 'conectarDB.php';
	include_once 'infoProfesor.php';
	include_once 'mysql_fetch_all.php';
	$tabla = json_decode($_POST['tabla']);
	$forma = $_POST['forma'];
	$asunto = $_POST['asunto'];
	$custom = $_POST['custom'];
	$mensaje = $_POST['msg'];
	parse_str($_POST['deForma'], $unserialized);
	date_default_timezone_set("America/Santiago");
	$firma = 
		"_____________________
		M. Ignacia Lira M.
		Fernández Mira 1005 Las Condes
		22438483- 9.4441593
		liramariaignacia@gmail.com
		Profesora de Matemática y Física
		Pontificia  Universidad Católica de Chile";
	$tablaHorario = 
		"<div style='display:block; content-align: center;'>
			<table style='background-color: #eee;'>
				<tbody>
					<tr style='border-collapse: collapse; border-color: #aaa; border-style: solid; border-width: 2px;'>
						<td style='padding: 15px'>Lunes</td>
						<td style='padding: 15px'>10:00 - 13:00</td>
					</tr>
					<tr style='border-collapse: collapse; border-color: #aaa; border-style: solid; border-width: 2px;'>
						<td style='padding: 15px'>Martes</td>
						<td style='padding: 15px'>14:30 - 17:30</td>
					</tr>
					<tr style='border-collapse: collapse; border-color: #aaa; border-style: solid; border-width: 2px;'>
						<td style='padding: 15px'>Miércoles</td>
						<td style='padding: 15px'>16:30 - 19:30</td>
					</tr>
					<tr style='border-collapse: collapse; border-color: #aaa; border-style: solid; border-width: 2px;'>
						<td style='padding: 15px'>Jueves</td>
						<td style='padding: 15px'>10:00 - 13:00</td>
					</tr>
					<tr style='border-collapse: collapse; border-color: #aaa; border-style: solid; border-width: 2px;'>
						<td style='padding: 15px'>Viernes</td>
						<td style='padding: 15px'>11:00 - 14:00</td>
					</tr>
				</tbody>
			</table>
		</div>";
	
	/*
	tabla[$i][0]	// Nombre alumno
	tabla[$i][1]	// Apellidos alumno
	tabla[$i][2]	// Mail alumno
	tabla[$i][3]	// Nombre papa
	tabla[$i][4]	// Mail papa
	tabla[$i][5]	// Nombre mama
	tabla[$i][6]	// Mail mama
	tabla[$i][7]	// Rut
	tabla[$i][8]	// Enviar a alumno
	tabla[$i][9]	// Enviar a apoderados
	*/
	
	// SI NO ES CUSTOM
	if($custom == "false"){
		// Rellenar datos del mail
		for($i = 0; $i < count($tabla); $i++){
			if($tabla[$i][8] == "true" || $tabla[$i][9] == "true"){
				$fecha = date('Y-m-d H:i:s');
				$textoForma = file_get_contents('../emails/'. $forma .'.txt');
				$nombreAlumno = $tabla[$i][0]; 		// <nombreAlumno>
				$apellidosAlumno = $tabla[$i][1];	// <apellidosAlumno>
				$mailAlumno = $tabla[$i][2];			// <mailAlumno>
				$nombrePapa = $tabla[$i][3];			// <nombrePapa>
				$mailPapa = $tabla[$i][4];			// <mailPapa>
				$nombreMama = $tabla[$i][5];			// <nombreMama>
				$mailMama = $tabla[$i][6];			// <mailMama>
				$rut = $tabla[$i][7];
				$enviarAlumno = $tabla[$i][8];
				$enviarApoderados = $tabla[$i][9];
				$hijohija = "hijo"; $oa = "o"; $elella = "él";
				$year = intval(date('Y'));
				
				$query1="SELECT `Sexo` FROM $tbl_usuarios WHERE `RUT` = '".$rut."' ";
				$sexo = mysql_fetch_all(mysql_query($query1));
				
				$query2 = "SELECT Puntaje FROM $tbl_puntajes WHERE `RUT` = '".$rut."' AND Year = $year ORDER BY `ID Ensayo` ";
				$puntajes = mysql_fetch_all(mysql_query($query2));
				
				$query3 = "SELECT AVG(Nota) FROM $tbl_controles WHERE `RUT` = '".$rut."' ";
				$avgControles = mysql_fetch_all(mysql_query($query3));
				
				$query4 = "SELECT AVG(Puntaje) FROM $tbl_puntajes WHERE `RUT` = '".$rut."' AND Year = $year ";
				$avgPuntajes = mysql_fetch_all(mysql_query($query4));
				
				if(strtoupper($sexo[0][0]) == "F"){
					$hijohija = "hija";
					$oa = "a";
					$elella = "ella";
				}
				
				$porcentajeControles = floor(floatval($avgControles[0][0]) * 10) ."%";
				$puntajePromedio = intval($avgPuntajes[0][0]);
				$nEnsayos = count($puntajes);
				$nEnsayosNoviembre = $nEnsayos - 1;
				$tablaPuntajes = "<table style='border-collapse: collapse; border-color: #aaa; border-width: 2px; background-color: #eee;'><tbody><tr>";
				
				for($j = 0; $j < intval($nEnsayos); $j++){
					if($j == 0){
						// Primer valor en color rojo
						$tablaPuntajes .= "<td style='color: #820000; padding: 15px'>". $puntajes[$j][0] ."</td>";
					}
					else if($j == intval($nEnsayos)-1){
						// Ultimo valor en color verde
						$tablaPuntajes .= "<td style='color: #008006; padding: 15px'>". $puntajes[$j][0] ."</td>";
					}
					else{
						$tablaPuntajes .= "<td style='padding: 15px'>". $puntajes[$j][0] ."</td>";
					}
				}
				$tablaPuntajes .= "</tr></tbody></table>";
				
				// REEMPLAZAR DATOS EN EL MENSAJE
				$textoForma = str_replace("<nombreAlumno>", $nombreAlumno, $textoForma);
				$textoForma = str_replace("<apellidosAlumno>", $apellidosAlumno, $textoForma);
				$textoForma = str_replace("<nombrePapa>", $nombrePapa, $textoForma);
				$textoForma = str_replace("<hijohija>", $hijohija, $textoForma);
				$textoForma = str_replace("<elella>", $elella, $textoForma);
				$textoForma = str_replace("<oa>", $oa, $textoForma);
				$textoForma = str_replace("<nombreMama>", $nombreMama, $textoForma);
				$textoForma = str_replace("<year>", $year, $textoForma);
				$textoForma = str_replace("<nEnsayos>", $nEnsayos, $textoForma);
				$textoForma = str_replace("<nEnsayosNoviembre>", $nEnsayosNoviembre, $textoForma);
				$textoForma = str_replace("<tablaPuntajes>", $tablaPuntajes, $textoForma);
				$textoForma = str_replace("<puntajePromedio>", $puntajePromedio, $textoForma);
				$textoForma = str_replace("<porcentajeControles>", $porcentajeControles, $textoForma);
				$textoForma = str_replace("<tablaHorario>", $tablaHorario, $textoForma);
				$textoForma = str_replace("<firma>", $firma, $textoForma);
				$textoForma = nl2br($textoForma);
				// SI SE ENVIA AL ALUMNO
				if($enviarAlumno == "true"){
					$to = $mailAlumno;
			
					$subject = $asunto;
			
					$headers = "From: " . $mailProfesor . "\r\n";
					$headers .= "Reply-To: ". $mailProfesor . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
					
					$message = '<html><body style="margin: 0 auto;">';
			
					$message .= '<table rules="all" style="border-color: #666;border: 2px; width: 100%; max-width: 550px; margin: 0 auto;" cellpadding="10">';
					$message .= '<tr><td colspan="2" style="background: #1d286f; color: white; text-align: center;"><h2>'.$nombreProfesor.'</h2><h4>'.$tipoPreuniversitario.'</h4></td></tr>';
					$message .= '<tr><td colspan="2"> <p style="color: #010A17, background-color: #ddd; text-align: justify">'. $textoForma . '</p> </td></tr>';
					$message .= "</table>";
					$message .= "</body></html>";
					
					// Enviar
					mail($to, $subject, $message, $headers);
					$nl = $fecha .", ". $apellidosAlumno ." ". $nombreAlumno .", ". $to .", ". $subject ."\r\n";
					file_put_contents("outboxMailMasivo.txt", $nl, FILE_APPEND);
					//echo "alumno";
					//echo "$to, $subject, $message, $headers";	
				}
				if($enviarApoderados == "true"){
					$to = $mailPapa .','. $mailMama;
			
					$subject = $asunto;
			
					$headers = "From: " . $mailProfesor . "\r\n";
					$headers .= "Reply-To: ". $mailProfesor . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
					
					$message = '<html><body style="margin: 0 auto;">';
			
					$message .= '<table rules="all" style="border-color: #666;border: 2px; width: 100%; max-width: 550px; margin: 0 auto" cellpadding="10">';
					$message .= '<tr><td colspan="2" style="background: #1d286f; color: white; text-align: center; font-size: 1.3em"><h2>'.$nombreProfesor.'</h2><h4>'.$tipoPreuniversitario.'</h4></td></tr>';
					$message .= '<tr><td colspan="2"> <p style="color: #010A17, background-color: #ddd; text-align: justify">'. $textoForma . '</p> </td></tr>';
					$message .= "</table>";
					$message .= "</body></html>";
					
					// Enviar
					mail($to, $subject, $message, $headers);
					$nl = $fecha .", ". $apellidosAlumno ." ". $nombreAlumno .", ". $to .", ". $subject ."\r\n";
					file_put_contents("outboxMailMasivo.txt", $nl, FILE_APPEND);
					//echo "apoderados";
					//echo "$to, $subject, $message, $headers";	
				}
			}	
		}
	}
	else{
		// Rellenar datos del mail
		for($i = 0; $i < count($tabla); $i++){
			$fecha = date('Y-m-d H:i:s');
			$textoForma = $unserialized['msg'];
			$nombreAlumno = $tabla[$i][0]; 		// <nombreAlumno>
			$apellidosAlumno = $tabla[$i][1];	// <apellidosAlumno>
			$mailAlumno = $tabla[$i][2];			// <mailAlumno>
			$nombrePapa = $tabla[$i][3];			// <nombrePapa>
			$mailPapa = $tabla[$i][4];			// <mailPapa>
			$nombreMama = $tabla[$i][5];			// <nombreMama>
			$mailMama = $tabla[$i][6];			// <mailMama>
			//$rutAlumno = $tabla[$i][7];
			$enviarAlumno = $tabla[$i][8];
			$enviarApoderados = $tabla[$i][9];
			
			// REEMPLAZAR DATOS EN EL MENSAJE
			$textoForma = str_replace("<nombreAlumno>", $nombreAlumno, $textoForma);
			$textoForma = str_replace("<apellidosAlumno>", $apellidosAlumno, $textoForma);
			$textoForma = str_replace("<nombrePapa>", $nombrePapa, $textoForma);
			$textoForma = str_replace("<nombreMama>", $nombreMama, $textoForma);
			$textoForma = str_replace("<elella>", $elella, $textoForma);
			$textoForma = str_replace("<oa>", $oa, $textoForma);
			$textoForma = str_replace("<firma>", $firma, $textoForma);
			$textoForma = nl2br($textoForma);
			// SI SE ENVIA AL ALUMNO
			if($enviarAlumno == "true"){
				$to = $mailAlumno;
		
				$subject = $unserialized['asunto'];
		
				$headers = "From: " . $mailProfesor . "\r\n";
				$headers .= "Reply-To: ". $mailProfesor . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
				
				$message = '<html><body style="margin: 0 auto;">';
		
				$message .= '<table rules="all" style="border-color: #666;border: 2px; width: 100%; max-width: 550px; margin: 0 auto" cellpadding="10">';
				$message .= '<tr><td colspan="2" style="background: #1d286f; color: white; text-align: center; font-size: 1.3em"><h2>'.$nombreProfesor.'</h2><h4>'.$tipoPreuniversitario.'</h4></td></tr>';
				$message .= '<tr><td colspan="2"> <p style="color: #010A17, background-color: #ddd; text-align: justify">'. $textoForma . '</p> </td></tr>';
				$message .= "</table>";
				$message .= "</body></html>";
				
				// Enviar
				mail($to, $subject, $message, $headers);
				$nl = $fecha .", ". $apellidosAlumno ." ". $nombreAlumno .", ". $to .", ". $subject ."\r\n";
				file_put_contents("outboxMailMasivo.txt", $nl, FILE_APPEND);
				//echo "alumno";
				//echo "$to, $subject, $message, $headers";	
			}
			if($enviarApoderados == "true"){
				$to = $mailPapa .','. $mailMama;
		
				$subject = $unserialized['asunto'];
		
				$headers = "From: " . $mailProfesor . "\r\n";
				$headers .= "Reply-To: ". $mailProfesor . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
				
				$message = '<html><body style="margin: 0 auto;">';
		
				$message .= '<table rules="all" style="border-color: #666;border: 2px; width: 100%; max-width: 550px; margin: 0 auto" cellpadding="10">';
				$message .= '<tr><td colspan="2" style="background: #1d286f; color: white; text-align: center; font-size: 1.3em"><h2>'.$nombreProfesor.'</h2><h4>'.$tipoPreuniversitario.'</h4></td></tr>';
				$message .= '<tr><td colspan="2"> <p style="color: #010A17, background-color: #ddd; text-align: justify">'. $textoForma . '</p> </td></tr>';
				$message .= "</table>";
				$message .= "</body></html>";
				
				// Enviar
				mail($to, $subject, $message, $headers);
				$nl = $fecha .", ". $apellidosAlumno ." ". $nombreAlumno .", ". $to .", ". $subject ."\r\n";
				file_put_contents("outboxMailMasivo.txt", $nl, FILE_APPEND);
				//echo "apoderados";
				//echo "$to, $subject, $message, $headers";	
			}
			
		}
	}
	file_put_contents("outboxMailMasivo.txt", "------------------------------------------------------\r\n", FILE_APPEND);
	echo "ok";
?>