<?php
	@session_start();
	@session_destroy();
	if(isset($_COOKIE['uname']) && isset($_COOKIE['upass'])){
		setcookie("uname", "", time()-60*60*24*100);
		setcookie("upass", "", time()-60*60*24*100);
	}
	header("Location:/index.php");
?>