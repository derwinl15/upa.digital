<?php
define ("EXP",6000000);
setlocale (LC_CTYPE, 'es_ES');
ini_set ("display_errors","0");
ini_set ("memory_limit","-1");

(isset($_GET['co'])) ? $getcompaniaalias=$_GET['co'] :$getcompaniaalias='';

if($getcompaniaalias!=""){


	$displaynoneservicios2 = "style='display: none;'";

	$conexion = new ConexionBd();
	$arrresultado = $conexion->doSelect("compania_id, cuenta_id",
	  "compania",
	  "compania_activo = '1' and compania_alias = '$getcompaniaalias'");
	foreach($arrresultado as $i=>$valor){
	  $cuenta_id = utf8_encode($valor["cuenta_id"]);
	  $compania_id = utf8_encode($valor["compania_id"]);
	  
		define ("SistemaCuentaId","$cuenta_id"); 
		define ("SistemaCompaniaId","$compania_id"); 
		define ("GoogleAnalytics","");	
		define ("style","style.css");

		$baseurl = "sitio/$getcompaniaalias/";
	}

}else{

	$displaynoneservicios = "style='display: none;'";

		define ("SistemaCuentaId","1766"); 
		define ("SistemaCompaniaId","292"); 
		define ("GoogleAnalytics","");	
		define ("style","style.css");

		$baseurl = "";
		//$base = "<base href='http://localhost/upa.digital/'>";

}


//$base = "<base href='https://www.misistemaweb.com/portfolio/upa.digital/'>";
$base = "<base href='http://localhost/upa.digital/'>";


/*



	define ("SistemaCuentaId","473"); 
	define ("SistemaCompaniaId","75"); 

	define ("SistemaCuentaId","1214"); 
	define ("SistemaCompaniaId","199"); 
	define ("GoogleAnalytics","");	
	define ("style","style.css");
*/

	//define ("UrlFiles","https://www.misistemaweb.com/portfolio/upa.digital/"); 
	define ("UrlFiles","http://localhost/gestiongo/"); 

	$urlpath = $_SERVER[HTTP_HOST];


	$link = $_SERVER['PHP_SELF'];
	$link_array = explode('/',$link);
	$geturlpage = end($link_array);
	$geturlpage = str_replace(".php","",$geturlpage);

?>