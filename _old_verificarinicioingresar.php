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

	setcookie("iniuser",$iniuser, time() + 86400, "/"); 
	setcookie("login",$login, time() + 86400, "/"); 
	setcookie("perfilactual",$perfilactual, time() + 86400, "/"); 
	setcookie("perfil",$perfil, time() + 86400, "/"); 
	setcookie("imguser",$imguser, time() + 86400, "/"); 
	setcookie("idcuenta",$idcuenta, time() + 86400, "/"); 
	setcookie("idcompania",$idcompania, time() + 86400, "/"); 
	setcookie("idcompaniarel",$idcompaniarel, time() + 86400, "/"); 	


	if ($_SESSION[perfilactual]=="2")	{ // Si es registro principal de un centro
		header("Location: ../gestiongo/admin/panel");		
	}else if ($_SESSION[perfilactual]=="108")	{ // Si es registro principal de un centro
		header("Location: ../gestiongo/admin/adquirirplan?i=1");		
	}else{
		header("Location: ../gestiongo/admin/reservar");		
	}	
	//header("Location: admin/adquirirplan");		
}else{
	header("Location: ../gestiongo/admin/adquirirplan?i=1");		
	//header("Location: admin/adquirirplan");		
}


?>