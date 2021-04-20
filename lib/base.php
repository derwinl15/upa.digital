<?php

define ("EXP",6000000);
setlocale (LC_CTYPE, 'es_ES');
ini_set ("display_errors","0");
ini_set ("memory_limit","-1");
session_start();



if ($_COOKIE["iniuser"] !=""){

	

	$_SESSION["iniuser"] = $_COOKIE["iniuser"] ;

	$_SESSION["login"] = $_COOKIE["login"] ;

	$_SESSION["perfil"] = $_COOKIE["perfil"] ;	



	//echo "existe cookie".$_COOKIE["iniuser"];

}else{

	//echo "no existe cookie";

}



// function base(){

		

// 	//$base = "<base href='http://www.residenteseguro.com/'>";

// 	$base = "<base href='http://localhost/upa.digital/'>";

	

// 	return $base;

	

// }

?>