<?php
    include_once 'includes/conectarDB.php';
	include_once 'includes/infoProfesor.php';

	// username and md5 password sent from form 
	$myusername = $_POST['myusername'];
	$mypassword = md5($_POST['mypassword']);
	// Purge . and - from RUT
	$myusername = str_replace(".", "", $myusername);
	$myusername = str_replace("-", "", $myusername);

	// To protect MySQL injection (more detail about MySQL injection)
	$myusername = stripslashes($myusername);
	$mypassword = stripslashes($mypassword);
	$myusername = mysql_real_escape_string($myusername);
	$mypassword = mysql_real_escape_string($mypassword);
	$verificarUsuario=mysql_query("SELECT * FROM ".$tbl_usuarios." WHERE RUT='".$myusername."' AND Password='".$mypassword."'") or die(mysql_error());
	$datosUsuario = mysql_fetch_array($verificarUsuario, MYSQL_NUM);
	

	// Mysql_num_row is counting table row
	$count=mysql_num_rows($verificarUsuario);

	// If result matched $myusername and $mypassword, table row must be 1 row
	if($count==1){
		// Register $myusername, $mypassword and redirect to file "login_success.php"
		$_SESSION['username']=$myusername;
		$_SESSION['password']=$mypassword;
		@setcookie("uname", $_SESSION['username'], time()+60*60*24*300);
		@setcookie("upass", $_SESSION['password'], time()+60*60*24*300);
		if($myusername == $rutProfesor){
			echo ("admin.php");
			exit;
		}
		else{
			// Si es primera vez que entra o no
			if($datosUsuario[11] == '0'){
				echo ("login_success.php");
				exit;
			}
			else{
				echo ("perfil.php");
				exit;
			}
		}	
	}
	else {
		echo ("error");
	}
?>