<?php

define ("EXP",6000000);
setlocale (LC_CTYPE, 'es_ES');
ini_set ("display_errors","0");
ini_set ("memory_limit","-1");

include_once 'lib/xajax_0.2.4/xajax.inc.php';
include_once 'lib/funciones.php';
include_once 'lib/mysqlclass.php';
include_once 'lib/config.php';
include_once 'lib/phpmailer/libemail.php';

$SistemaCuentaId = SistemaCuentaId;
$SistemaCompaniaId = SistemaCompaniaId;
$UrlFiles = UrlFiles;

$xajax = new xajax('lib/ajx_fnci.php');
$xajax->registerFunction('registrarusuario');


$conexion = new ConexionBd();

$arrresultado = ObtenerDatosCompania($SistemaCuentaId, $SistemaCompaniaId);
foreach($arrresultado as $i=>$valor){

    $compania_id = utf8_encode($valor["compania_id"]);
    $compania_nombre = utf8_encode($valor["compania_nombre"]);
    $compania_img = utf8_encode($valor["compania_img"]);
    $compania_email = utf8_encode($valor["compania_email"]);
    $compania_imgicono = utf8_encode($valor["compania_imgicono"]);
    $compania_urlweb = utf8_encode($valor["compania_urlweb"]);

    $urlcompaniaimg = $UrlFiles."admin/arch/$compania_img";
    $urlcompaniaimgicono = $UrlFiles."admin/arch/$compania_imgicono";

    $titulopagina = $compania_nombre." ";
    $descripcionpagina = "$compania_nombre";

}

(isset($_GET['i'])) ? $getinfo=$_GET['i'] : $getinfo='';
(isset($_GET['r'])) ? $getinforeserva=$_GET['r'] : $getinforeserva='';


if ($getinfo=="1"){// Planes

  $info = "
      <div class='alert alert-danger' style='text-align: center; font-weight: normal;'>
          <a style='color: #002755; font-size: 14px; text-decoration: none;'>
              Para continuar con la compra del plan debe registrarse
          </a><br>
      </div>
  ";

}


if ($getinforeserva=="1"){// Reserva

  $info2 = "
      <div class='alert alert-danger' style='text-align: center; font-weight: normal;'>
          <a style='color: #002755; font-size: 14px; text-decoration: none;'>
              Para continuar con la reserva debe registrarse
          </a><br>
      </div>
  ";

}


$arrresultado = $conexion->doSelect("

    seccion.seccion_id, seccion.seccion_nombre, seccion.seccion_descrip, seccion.seccion_img, seccion.cuenta_id, seccion.compania_id, seccionmeta.meta_id, seccionmeta.seccionmeta_valor,
    seccion.seccion_activo, seccion.seccion_eliminado, seccion.seccion_orden, seccion.usuario_idreg,
    DATE_FORMAT(seccion.seccion_fechareg,'%d/%m/%Y %H:%i:%s') as seccion_fechareg,
    cuenta.usuario_codigo as cuenta_codigo, cuenta.usuario_nombre as cuenta_nombre,
    cuenta.usuario_apellido as cuenta_apellido, compania_nombre,
    seccion.l_tiposeccion_id
    
    ",
    "seccion                        
        inner join usuario cuenta on cuenta.usuario_id = seccion.cuenta_id
        inner join compania on compania.compania_id = seccion.compania_id       
        inner join lista tipomenu on tipomenu.lista_id = seccion.l_tipomenuweb_id
        inner join seccionmeta on seccionmeta.seccion_id = seccion.seccion_id
    ",
    "seccion_activo = '1' and seccion.seccion_url = '$geturlpage' and seccion.cuenta_id = '$SistemaCuentaId' and seccion.compania_id = '$SistemaCompaniaId' ");
    foreach($arrresultado as $i=>$valor){

    $seccion_id = utf8_encode($valor["seccion_id"]);
    $seccion_nombre = utf8_encode($valor["seccion_nombre"]);
    $seccion_descrip = utf8_encode($valor["seccion_descrip"]);
    $meta_id = utf8_encode($valor["meta_id"]);
    $seccionmeta_valor = utf8_encode($valor["seccionmeta_valor"]);
  

    if($meta_id == "2"){
       $seccionmeta_valortitle = $seccionmeta_valor;

    }elseif($meta_id == "1"){
       $seccionmeta_valordescrip = $seccionmeta_valor;
    }else{
       $seccionmeta_valorkeywords= $seccionmeta_valor;   
    }

    

    $titulopagina = "$seccion_nombre - $compania_nombre ";
    $descripcionpagina = "$seccion_descrip - $compania_nombre ";

  }


  session_start();
  $getreferido = $_COOKIE["referido"];
  if ($getreferido!=""){
    $divreferido = "
      <div class='form-group'>
          <span style='font-weight: 700'>Referido por:</span> $getreferido
          <div class=''></div>
      </div>
    ";
  }




?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php echo $base; ?>
  <?php $xajax->printJavascript('lib/'); ?>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $compania_nombre;?> - Registrarse</title>
  <meta content="<?php echo $seccionmeta_valortitle;?>" name="title">
  <meta content="<?php echo $seccionmeta_valordescrip;?>" name="description">
  <meta content="<?php echo $seccionmeta_valorkeywords;?>" name="keywords">

  <!-- Favicons -->
  <link href="<?php echo $urlcompaniaimgicono;?>" rel="icon">
  <link href="<?php echo $urlcompaniaimgicono;?>" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- ======= CSS ======= -->
  <?php include_once "includecss.php"; ?>
  <!-- ======= End CSS ======= -->

</head>

<body>


 <!-- ======= Header ======= -->
 <?php include_once "header.php" ?>
 <!-- End Header -->



  <main id="main">

    <!-- ======= Contact Section ======= -->
    <section id="hero" class="contact">
      <div class="container">

        <?php echo $info; ?>
        <?php echo $info2; ?>

        <div class="section-title">
          <h1>Registrarse</h1>
          <hr style="border: 1px solid  #002755;"  id="">
        </div>

        <div class="row">
          <div class="col-lg-4">
          </div>
          <div class="col-lg-4">
            <form action="javascript:registrarusuario()" method="post" role="form" class="php-email-form" >
              <div class="form-group">
                <input placeholder="Nombre" type="text" name="name" class="form-control" id="name" required="required" />
                <div class=""></div>
              </div>
              <div class="form-group">
                <input placeholder="Apellido" type="text" name="lastname" class="form-control" id="lastname" required="required" />
                <div class=""></div>
              </div>
              <div class="form-group">
                <input placeholder="Email" type="text" class="form-control" name="email" id="email" required="required" />
                <div class=""></div>
              </div>
              <div class="form-group">
                  <input type="password" name="password" class="form-control" id="password" required="required" placeholder="Ingrese su Contraseña"  />
                  <div class=""></div>
              </div>
              <div class="form-group">
                  <input type="password" name="password2" class="form-control" id="password2" required="required" placeholder="Repita su Contraseña" />
                  <div class=""></div>
              </div>
              <div class="form-group">
                <input type="checkbox" id="terminos" value="terminos" required="required" title="Se debe aceptar los Términos y Condiciones para continuar con el registro"> 
                <label for="terminos">Acepta los <a href="<?php echo $baseurl; ?>terminos">Términos y Condiciones</a></label>
              </div>
              <?php echo $divreferido;?>
              <div class="text-center"><button type="submit">Registrarse</button>
               <br><br>
                <a href="<?php echo $baseurl;?>ingresar">
                  Ya tienes cuenta? Ingresa aquí
                </a>
              </div>

                <input type="hidden" class="form-control" name="compania_id" id="compania_id" value="<?php echo $compania_id;?>">
                
            </form>
          </div>

        </div>

      </div>
    </section><!-- End Contact Section -->

  </main><!-- End #main -->



  <!-- ======= Footer ======= -->
  <?php include_once "footer.php" ?>
  <!-- End Footer -->


</body>

</html>