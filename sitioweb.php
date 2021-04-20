<?php
define ("EXP",6000000);
setlocale (LC_CTYPE, 'es_ES');
ini_set ("display_errors","0");
ini_set ("memory_limit","-1");

include_once 'lib/funciones.php';
include_once 'lib/mysqlclass.php';
include_once 'lib/config.php';

(isset($_GET['url'])) ? $geturl=$_GET['url'] :$geturl='';
(isset($_GET['co'])) ? $getcomp=$_GET['co'] :$getcomp='';
(isset($_GET['tipo'])) ? $gettipo=$_GET['tipo'] :$gettipo='';

$SistemaCuentaId = SistemaCuentaId;
$SistemaCompaniaId = SistemaCompaniaId;
$UrlFiles = UrlFiles;


// if ($getcomp!="" && $geturl==""){
//    require_once "blog.php"; 
//    exit();
// }
/*
echo "gettipo:".$gettipo;
echo "<br>";
echo "geturl:".$geturl;
exit();*/

if ($gettipo=="index" && $geturl==""){require_once "serviciodetalle.php"; exit();}
else if ($gettipo=="blog"){require_once "blogdetalle.php"; exit();}
else if ($gettipo=="servicio"){require_once "serviciodetalle.php"; exit();}
else if ($geturl=="blog"){require_once "blog.php"; exit();}
else if ($geturl=="servicio"){require_once "serviciodetalle.php"; exit();}
else if ($geturl=="registrar"){require_once "registrar.php"; exit();}
else if ($geturl=="ingresar"){require_once "ingresar.php"; exit();}
else if ($geturl=="olvidoclave"){require_once "$geturl.php"; exit();}
else if ($geturl=="boletin"){require_once "$geturl.php"; exit();}
else if ($geturl=="contacto"){require_once "$geturl.php"; exit();}
else if ($geturl=="servicios"){require_once "serviciodetalle.php"; exit();}

//else if ($geturl=="index" || $geturl==""){require_once "serviciodetalle.php"; exit();}

else {
   require_once "pagedetalle.php"; 
   exit();
}
