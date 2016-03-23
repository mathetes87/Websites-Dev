<?php
	// NO OCUPA MAIL AUTOMATICO
	include_once 'conectarDB.php';
	include_once 'infoProfesor.php';
	include_once 'mysql_fetch_all.php';
	include_once 'crearPaginasApoderados.php';
	$mails = 0;
	
	// Para los ensayos no reportados
	$query="SELECT `RUT`, `Ensayo`, `ID Ensayo` as idEnsayo, `Puntaje`, `Numeros`, `Algebra`, `Geometria`, `Datos`  FROM $tbl_puntajes WHERE `RUT` != '".$rutProfesor."' AND `Notificado` = 0 AND `Puntaje` != 0 AND `Puntaje` IS NOT NULL ORDER BY `ID Ensayo`";
	$ensayos = mysql_fetch_all(mysql_query($query));
	//echo "Ensayos no reportados: ".count($ensayos);
	
	// Para nombres y apellidos, y mails
	$querySoloAlumnos="SELECT `RUT`, `Nombres`, `Apellidos`, `Nombre papa`, `Mail papa`, `Nombre mama`, `Mail mama`, Mail FROM $tbl_usuarios GROUP BY RUT";
	$alumnos = mysql_fetch_all(mysql_query($querySoloAlumnos));
	//echo "alumnos: ".count($alumnos);
	
	// Para promedio del curso
	$queryTodosPuntajes="SELECT AVG(Puntaje) as Promedio, `ID Ensayo` as idEnsayo FROM $tbl_puntajes WHERE Puntaje IS NOT NULL AND Puntaje != 0 GROUP BY Ensayo ORDER BY `ID Ensayo`";
	$promedioCursoArr = mysql_fetch_all(mysql_query($queryTodosPuntajes));
	//echo "promedios curso: ".count($promedioCurso);
	
	// Para promedio de alumnos
	$queryTodosPuntajes="SELECT RUT, AVG(Puntaje) as Promedio FROM $tbl_puntajes WHERE Puntaje IS NOT NULL AND Puntaje != 0 GROUP BY RUT";
	$promedioAlumnosArr = mysql_fetch_all(mysql_query($queryTodosPuntajes));
	//echo "promedios alumno: ".count($promedioAlumnos);
	
	// Recorrer todos los ensayos no reportados
	for($i = 0; $i < count($ensayos); $i++) {
	//for($i = 0; $i < 3; $i++) {	
		// Guardar datos a reportar
		// RUT, Puntaje  y nombre de ensayo, mails y nombres, promedio curso, promedio alumno
		$fecha = date('Y-m-d H:i:s');
		$rut = $ensayos[$i][0];
		$nombreEnsayo = $ensayos[$i][1];
		$idEnsayo = $ensayos[$i][2];
		$puntaje = $ensayos[$i][3];
		$nombreAlumno = "";
		$apellidosAlumno = "";
		$mailAlumno = "";
		$nombrePapa = "";
		$mailPapa = "";
		$nombreMama = "";
		$mailMama = "";
		$promedioCurso = "";
		$promedioAlumno = "";
		$variacionAlumno = 0;
		$variacionCurso = 0;
		
		// Recorrer ahora tabla usuarios y encontrar rut
		for($j = 0; $j < count($alumnos); $j++){
			//echo $rut." == ".$alumnos[$j][0]."</br>";
			if($alumnos[$j][0] == $rut){
				//echo $alumnos[$j][0];
				$nombreAlumno = $alumnos[$j][1];
				$apellidosAlumno = $alumnos[$j][2];
				$nombrePapa = $alumnos[$j][3];
				$mailPapa = $alumnos[$j][4];
				$nombreMama = $alumnos[$j][5];
				$mailMama = $alumnos[$j][6];
				$mailAlumno = $alumnos[$j][7];
			}
		}
		
		if($nombreAlumno == "" || $puntaje == 0){
			//echo "continues";
			continue;
		}
		
		// Buscar promedio de curso para ese ID Ensayo
		for($j = 0; $j < count($promedioCursoArr); $j++){
			if($promedioCursoArr[$j][1] == $idEnsayo){
				$promedioCurso = round($promedioCursoArr[$j][0]);
			}
		}
		
		// Buscar promedio de alumno para ese RTU
		for($j = 0; $j < count($promedioAlumnosArr); $j++){
			if($promedioAlumnosArr[$j][0] == $rut){
				$promedioAlumno = round($promedioAlumnosArr[$j][1]);
			}
		}
		
		if($promedioAlumno != 0){
			$variacionAlumno = round(($puntaje/$promedioAlumno - 1) * 100, 0); // +-25	
		}
		if($promedioCurso != 0){
			$variacionCurso = round(($puntaje/$promedioCurso - 1) * 100, 0);
		}	
	
		// Rellenar datos del mail
		// http://css-tricks.com/sending-nice-html-email-with-php/
		$to = $mailAlumno.",".$mailPapa.",".$mailMama."";
		//$to = "juanrhonorato@gmail.com,bgonzalex@gmail.com";

		$subject = "Reporte de ensayo ".$nombreEnsayo."";

		$headers = "From: " . $mailProfesor . "\r\n";
		$headers .= "Reply-To: ". $mailProfesor . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		
		$message = '<html><body style="margin: 0 auto;">';

		$message .= '<table rules="all" style="border-color: #666;border: 2px; width: 100%; max-width: 550px; margin: 0 auto" cellpadding="10">';
		$message .= '<tr><td colspan="2" style="background: #1d286f; color: white; text-align: center; font-size: 1.3em"><h2>'.$nombreProfesor.'</h2><h4>'.$tipoPreuniversitario.'</h4></td></tr>';
		// Si solo hay un mail de apoderado
		if($mailMama == "" && $mailPapa != ""){
			$message .= '<tr><td colspan="2"><h3 style="color: #010A17">Estimado '.$nombrePapa.':</h3></br>';
		}
		else if($mailMama != "" && $mailPapa == ""){
			$message .= '<tr><td colspan="2"><h3 style="color: #010A17">Estimada '.$nombreMama.':</h3></br>';
		}
		else if($mailMama == "" && $mailPapa == ""){
			continue;
		}
		else{
			$message .= '<tr><td colspan="2"><h3 style="color: #010A17">Estimados '.$nombrePapa.' y '.$nombreMama.':</h3></br>';
		}
		$message .= '<p style="color: #010A17">Estos son los resultados del último ensayo de '.$nombreAlumno.'</p></td></tr>';
		$message .= "<tr style='background: #cdcdcd; text-transform:capitalize;'><td> <strong>Ensayo:</strong> </td><td>" . $nombreEnsayo . "</td></tr>";
		$message .= "<tr style='background-color: #ddd'><td><strong>Puntaje obtenido:</strong> </td><td>" . $puntaje . "</td></tr>";
		if($variacionCurso > 0){
			$message .= "<tr style='background-color: #ddd'><td><strong>Promedio del curso en este ensayo:</strong> </td><td>" . $promedioCurso . "</td></tr>";
		}
		else{
			$message .= "<tr style='background-color: #ddd'><td><strong>Promedio del curso en este ensayo:</strong> </td><td>" . $promedioCurso . "</td></tr>";
		}
		if($variacionAlumno > 0){
			$message .= "<tr style='background-color: #ddd'><td><strong>Promedio histórico de ". $nombreAlumno .":</strong> </td><td>" . $promedioAlumno . "</td></tr>";
		}
		else{
			$message .= "<tr style='background-color: #ddd'><td><strong>Promedio histórico de ". $nombreAlumno .":</strong> </td><td>" . $promedioAlumno . "</td></tr>";
		}
		$message .= "<tr><td colspan='2'><p style='color: #010A17'>Para ver información más detallada pueden entrar a <a href='".$urlPagina."/apoderados/". md5($rut) .".php' style='text-decoration: none; color: blue'>su propia sección</a> en mi página web.</p></td></tr>";
		$message .= "</table>";
		$message .= "</body></html>";
		
		// Enviar
		mail($to, $subject, $message, $headers);
		$mails++;
		$nl = $fecha .", ". $nombreAlumno ." ". $apellidosAlumno .", ". $to .", ". $subject ."\r\n";
		file_put_contents("outboxMailReporte.txt", $nl, FILE_APPEND);
		//echo $message;
		
		// Marcar el ensayo como reportado
		$query = "UPDATE ".$tbl_puntajes." SET `Notificado` = 1 WHERE RUT = '".$rut."' AND `ID Ensayo` = '".$idEnsayo."'";
		//echo $query;
			
		mysql_query($query)or die(mysql_error());
		
	}
	file_put_contents("outboxMailReporte.txt", "------------------------------------------------------\r\n", FILE_APPEND);
	if($mails > 1){
		echo "Se enviaron ". $mails. " nuevos email de reporte";	
	}
	else if($mails == 1){
		echo "Se envió ". $mails. " nuevo email de reporte";
	}
?>