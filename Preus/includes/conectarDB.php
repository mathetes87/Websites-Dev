<?php
        $host="127.0.0.1"; // Host name 
		$username="nachal"; // Mysql username 
		$password="canela123"; // Mysql password 
		$db_name="nachal_db"; // Database name 
		$tbl_usuarios="usuarios"; // Table name 1
		$tbl_puntajes="puntajes"; // Table name 2
		$tbl_asistencia="asistencia"; // Table name 3
		$tbl_controles="controles"; // Table name 4
		$tbl_pagos="pagos"; // Table name 5

		// Connect to server and select database.
		mysql_connect($host, $username, $password)or die(mysql_error()); 
		mysql_select_db($db_name)or die(mysql_error()); 
		mysql_query ("SET NAMES utf8");
?>
