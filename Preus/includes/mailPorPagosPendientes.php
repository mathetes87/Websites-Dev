<?php
	include_once 'infoProfesor.php';
	date_default_timezone_set("America/Santiago");
	$fecha = date('Y-m-d H:i:s');
	$mes = $_POST['mesPagos'];
	$decoded = json_decode($_POST['conMail'], true);
	foreach($decoded as $datos) {
		$nombreAlumno = $datos['nombre'];
		$to = $datos['mailMama'];
		$nombreMama = $datos['nombreMama'];
			
		$subject = "Recordatorio pago de preuniversitario matemáticas";

		$headers = "From: " . $mailProfesor . "\r\n";
		$headers .= "Reply-To: ". $mailProfesor . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		
		$message = '<html><body style="margin: 0 auto;">';

		$message .= '<table rules="all" style="border-color: #666;border: 2px; width: 100%; max-width: 550px; margin: 0 auto;" cellpadding="10">';
		$message .= '<tr><td colspan="2" style="background: #1d286f; color: white; text-align: center;"><h2>'.$nombreProfesor.'</h2><h4>'.$tipoPreuniversitario.'</h4></td></tr>';
		$message .= '<tr><td colspan="2"><p style="color: #010A17, background-color: #ddd; text-align: justify">
			Estimada '. $nombreMama . ':<br><br>
			Te recuerdo que está pendiente el pago del preuniversitario de '. $nombreAlumno .' del mes de '. $mes .'.<br>
			Son '. $montoMensual .' mensuales. Te dejo mis datos para una transferencia electrónica si te acomoda:<br>
			<br>
			María Ignacia Lira<br>
			Banco Santander<br>
			Cuenta: 62777451<br>
			C. I. 6379771-5<br>
			Mail: liramariaignacia@gmail.com<br>
			<br>
			Muchas gracias
						
		</p> </td></tr>';
		$message .= "</table>";
		$message .= "</body></html>";

		// Enviar
		mail($to, $subject, $message, $headers);
		$nl = $fecha .", ". $apellidosAlumno ." ". $nombreAlumno .", ". $to .", ". $subject ."\r\n";
		file_put_contents("outboxMailMasivo.txt", $nl, FILE_APPEND);
	}
	
	echo "Se enviaron ". count($decoded) ." email";
?>