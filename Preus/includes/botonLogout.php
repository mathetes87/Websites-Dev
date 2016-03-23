<?php
	@session_start();
	if(isset($_COOKIE['uname']) && isset($_COOKIE['upass'])){
		echo "<div class='containerLogout'><button id='logout' class='boton'>Logout</button></div>";
	}
?>