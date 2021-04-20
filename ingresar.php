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
$xajax->registerFunction('ingresarusuario');

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php echo $base; ?>
  <?php $xajax->printJavascript('lib/'); ?>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $compania_nombre;?> - Ingresar</title>
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

        <div class="section-title">
          <h1>Ingresar</h1>
          <hr style="border: 1px solid  #002755;" >
        </div>

        <div class="row">
          <div class="col-lg-4">
          </div>
          <div class="col-lg-4">
            <form action="javascript:ingresarusuario()" role="form" class="php-email-form">
              <div class="form-group">
                <input placeholder="Email" type="text" class="form-control" name="email" id="email" />
                <div class="validate"></div>
              </div>
              <div class="form-group">
                <input placeholder="Clave" type="password" name="password" class="form-control" id="password"  />
                <div class="validate"></div>
              </div>
              <div class="text-center"><button type="submit">Ingresar</button>
              <br><br>
                <a href="<?php echo $baseurl;?>olvidoclave">
                  Olvido su contraseña? Ingresa aquí
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