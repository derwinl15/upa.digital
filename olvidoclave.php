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

(isset($_POST['email'])) ? $email=$_POST['email'] :$email='';

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


if ($email!=""){

  $infonotificacion  = "
        <div class='alert alert-danger' style='text-align: center; font-weight: normal;'>
            <a style='color: #000; font-size: 14px; text-decoration: none;'>
                Correo $email no existe en el sistema, por favor verifique
            </a><br>
        </div>
  ";
  

  $arrresultado = $conexion->doSelect("
    usuario_id, usuario_nombre, usuario_apellido, usuario_clave, usuario_telf, usuario_email
  ","usuario
  ", " usuario_email = '$email' and usuario_activo = '1' and cuenta_id = '$SistemaCuentaId' and compania_id = '$SistemaCompaniaId'");

  if (count($arrresultado)>0){
    foreach($arrresultado as $m=>$valor){

      $usuario_id = utf8_encode($valor["usuario_id"]);
      $usuario_nombre = utf8_encode($valor["usuario_nombre"]);
      $usuario_apellido = utf8_encode($valor["usuario_apellido"]);
      $usuario_clave = utf8_encode($valor["usuario_clave"]);
      $usuario_email = utf8_encode($valor["usuario_email"]);
      $usuario_telf = utf8_encode($valor["usuario_telf"]);

      if ($usuario_email!=""){$incluiremail = "<br>Email: ".$usuario_email;}
      if ($usuario_telf!=""){$incluirtef = "<br>Telefono: ".$usuario_telf;}     

      $uniqid = uniqid();      

      if ($usuario_clave==""){
        $resultado = $conexion->doUpdate("usuario", "   
      usuario_clave='$uniqid'
      ",
    "usuario_id='$usuario_id'");
      }

      
      $libemail2 = new LibEmail();

      $texto = "
      <!-- big image section -->
      <table border='0' width='100%' cellpadding='0' cellspacing='0' bgcolor='ffffff' class='bg_color'>

        <tr>
            <td align='center'>
                <table border='0' align='center' width='590' cellpadding='0' cellspacing='0' class='container590'>
                    
                    <tr>
                        <td align='center' style='color: #343434; font-size: 24px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;' class='main-header'>
                            <div style='line-height: 35px'>
                                ".$lang["olvidoclave_msjecorreo"]." $compania_nombre
                            </div>
                        </td>
                    </tr>


                    <tr>
                        <td height='10' style='font-size: 10px; line-height: 10px;'>&nbsp;</td>
                    </tr>

                    <tr>
                        <td align='center'>
                            <table border='0' width='40' align='center' cellpadding='0' cellspacing='0' bgcolor='eeeeee'>
                                <tr>
                                    <td height='2' style='font-size: 2px; line-height: 2px;'>&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td height='20' style='font-size: 20px; line-height: 20px;'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='left' style='color: #343434; font-size: 17px; font-family: Quicksand, Calibri, sans-serif; font-weight:normal;letter-spacing: 3px; line-height: 35px;' class='main-header'>
                            <div style='line-height: 26px;'>
                                <strong>".$lang["form_nombre"].":</strong> $usuario_nombre
                                <br>
                                <strong>".$lang["form_email"].":</strong> $usuario_email
                                <br>
                                <strong>".$lang["form_clave"].":</strong> $usuario_clave                                
                                <br>
                            </div>
                        </td>
                    </tr>

                 
                </table>

            </td>
        </tr>
        <tr class='hide'>
            <td height='25' style='font-size: 25px; line-height: 25px;'>&nbsp;</td>
        </tr>
        <tr>
            <td height='20' style='font-size: 20px; line-height: 20px;'>&nbsp;</td>
        </tr>


    </table>
    <!-- end section -->
    ";

      $asunto = " ".$lang["olvidoclave_msjeasunto"]." - $compania_nombre";

      $resultado = $libemail2->enviarcorreo($email, $asunto, $texto, $SistemaCompaniaId);
      $resultado = $libemail2->enviarcorreo("info@misistemaweb.com", $asunto, $texto, $SistemaCompaniaId);


      $infonotificacion = "
        <div class='alert alert-success' style='text-align: center; font-weight: normal;'>
            <a style='color: #000; font-size: 14px; text-decoration: none;'>
                Por favor verifique su correo le enviamos su clave.
            </a><br>
        </div>
      ";

      

    }
  } 
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php echo $base; ?>
  <?php $xajax->printJavascript('lib/'); ?>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $compania_nombre;?> - Olvido Clave</title>
  <meta content="Olvido Clave" name="description">
  <meta content="Olvido Clave" name="keywords">

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

        <!-- ======= Mensaje notificaciond envio de correo ======= -->
        <?php echo $infonotificacion; ?>
        <!-- ======= End Mensaje notificaciond envio de correo ======= -->

        <div class="section-title">
          <h2 style="color: #59A618">Ingrese con su email para recuperar su contraseña</h2>
          <hr style="border: 1px solid  #002755;"  id="">
        </div>

        <div class="row">
          <div class="col-lg-4">
          </div>
          <div class="col-lg-4">
            <form action="<?php echo $baseurl;?>olvidoclave" method="post" role="form" class="php-email-form">
              <div class="form-group">
                <input placeholder="Email" type="text" class="form-control" name="email" id="email" data-rule="email" data-msg="Por favor ingrese un email valido" />
                <div class=""></div>
              </div>
              <div class="text-center"><button type="submit">Recuperar</button></div>
              
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