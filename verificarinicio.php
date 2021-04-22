<?php
define ("EXP",6000000);
setlocale (LC_CTYPE, 'es_ES');
ini_set ("display_errors","0");
ini_set ("memory_limit","-1");

session_start();
$iniuser = $_SESSION[iniuser];
$login = $_SESSION[login];
$perfilactual = $_SESSION[perfilactual];
$perfil = $_SESSION[perfil];
$imguser = $_SESSION[imguser];
$idcuenta = $_SESSION[idcuenta];
$idcompania = $_SESSION[idcompania];
$idcompaniarel = $_SESSION[idcompaniarel];

if ($iniuser!=""){

	setcookie("iniuser",$iniuser, time() + 86400, "/", $_SERVER['HTTP_HOST']); 
	setcookie("login",$login, time() + 86400, "/", $_SERVER['HTTP_HOST']); 
	setcookie("perfilactual",$perfilactual, time() + 86400, "/", $_SERVER['HTTP_HOST']); 
	setcookie("perfil",$perfil, time() + 86400, "/", $_SERVER['HTTP_HOST']); 
	setcookie("imguser",$imguser, time() + 86400, "/", $_SERVER['HTTP_HOST']); 
	setcookie("idcuenta",$idcuenta, time() + 86400, "/", $_SERVER['HTTP_HOST']); 
	setcookie("idcompania",$idcompania, time() + 86400, "/", $_SERVER['HTTP_HOST']); 
	setcookie("idcompaniarel",$idcompaniarel, time() + 86400, "/", $_SERVER['HTTP_HOST']); 	

	if ($_SESSION[perfil]=="2")	{ // Si es registro principal de un centro
		header("Location: ../gestiongo/admin/panel");		
	}else if ($_SESSION[perfilactual]=="108")	{ // Si es registro principal de un centro
		header("Location: ../gestiongo/admin/adquirirplan?i=1");		
	}else if ($_SESSION[perfilactual]=="5")	{ // Si es registro principal de un centro
		header("Location: ../gestiongo/admin/adquirirplan?i=1");		
	}else{
		header("Location: ../gestiongo/admin/reservar");		
	}	
	
}else{
	header("Location: ../gestiongo/admin/adquirirplan?i=1");		
	//header("Location: admin/adquirirplan");		
}

?>