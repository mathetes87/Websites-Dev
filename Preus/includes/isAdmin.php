<?php
	include 'analytics.php';
	//error_reporting(E_ERROR | E_PARSE);
	include_once 'infoProfesor.php';
	@session_start();
	if(isset($_COOKIE['uname']) && isset($_COOKIE['upass']) && $_COOKIE['uname']=='.$rutProfesor.'){
		$_SESSION['username'] = $_COOKIE['uname'];
		$_SESSION['password'] = $_COOKIE['upass'];
	}
	if(!($_SESSION['username'] = $_COOKIE['uname'] && $_SESSION['password'] = $_COOKIE['upass'])){
		header("location:index.php");
	}
?>