<?php
define ("EXP",6000000);
setlocale (LC_CTYPE, 'es_ES');
ini_set ("display_errors","0");
ini_set ("memory_limit","-1");

include_once 'lib/funciones.php';
include_once 'lib/mysqlclass.php';
include_once 'lib/phpmailer/libemail.php';
include_once 'lib/config.php';

$SistemaCuentaId = SistemaCuentaId;
$SistemaCompaniaId = SistemaCompaniaId;
$UrlFiles = UrlFiles;

$conexion = new ConexionBd();
$arrresultado = ObtenerDatosCompania($SistemaCuentaId, $SistemaCompaniaId);
foreach($arrresultado as $i=>$valor){

    $compania_id = utf8_encode($valor["compania_id"]);
    $compania_nombre = utf8_encode($valor["compania_nombre"]);
    $compania_email = utf8_encode($valor["compania_email"]);
    $compania_whatsapp = utf8_encode($valor["compania_whatsapp"]);
    $compania_img = utf8_encode($valor["compania_img"]);
    $compania_imgicono = utf8_encode($valor["compania_imgicono"]);
    $compania_urlweb = utf8_encode($valor["compania_urlweb"]);
    $compania_direccion = utf8_encode($valor["compania_direccion"]);
    $compania_telf = utf8_encode($valor["compania_telf"]);


    $urlcompaniaimg = $UrlFiles."/admin/arch/$compania_img";
    $urlcompaniaimgicono = $UrlFiles."/admin/arch/$compania_imgicono";

    $titulopagina = "Trabaja con Nosotros - ".$compania_nombre;
    $descripcionpagina = "Trabaja con Nosotros - $compania_nombre ";

}



(isset($_POST['name'])) ? $name=$_POST['name'] :$name='';
(isset($_POST['email'])) ? $email=$_POST['email'] :$email='';
(isset($_POST['message'])) ? $message=$_POST['message'] :$message='';
(isset($_POST['phone'])) ? $phone=$_POST['phone'] :$phone='';


if ($name!=""){

  $libemail = new LibEmail();        

  $texto = "
    <!-- big image section -->
    <table border='0' width='100%' cellpadding='0' cellspacing='0' bgcolor='ffffff' class='bg_color'>

      <tr>
          <td align='center'>
              <table border='0' align='center' width='590' cellpadding='0' cellspacing='0' class='container590'>
                  
                  <tr>
                      <td align='center' style='color: #343434; font-size: 24px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;' class='main-header'>
                          <div style='line-height: 35px'>
                              Hola $name, recibimos tu mensaje de forma correcta, ya en breve te responderemos.
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
                              <strong>Nombre:</strong> $name
                              <br>
                              <strong>Email:</strong> $email
                              <br>
                              <strong>Telefono:</strong> $phone
                              <br>
                              <strong>Mensaje:</strong> $message
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

  $info = "
            <div class='alert alert-success' style='text-align: center; font-weight: bold'>
                <a style='color: #000'>
                    Mensaje recibido correctamente, en breve te responderemos directo a tu email.
                </a>
            </div>     ";

  $asunto = "Recibimos tu Mensaje - $compania_nombre ";

  $resultado = $libemail->enviarcorreo($email, $asunto, $texto, $SistemaCompaniaId);
  $resultado = $libemail->enviarcorreo("info@misistemaweb.com", $asunto, $texto, $SistemaCompaniaId);
   
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
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $compania_nombre;?> - Trabaja con Nosotros</title>
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

        <!-- ======= Respuesta automatica Email ======= -->
        <?php echo $info;?>
        <!-- ======= End espuesta automatica Email ======= -->

        <div class="section-title">
          <h1>Trabaja con Nosotros</h1>
          <p> Contamos contigo para hacer vivo nuestro propósito en cada interacción en nuestra compañía: con nuestros clientes y nuestros proveedores. Te invitamos a conocer nuestras búsquedas activas y a vivir la UpaDigital.</p>
        </div>

        <div class="row">

          <div class="col-lg-6">
            <div class="row">
              <div class="col-lg-6 info" data-aos="fade-up" style="display: none;">
                <i class="bx bx-map"></i>
                <h4>Dirección</h4>
                <p><?php echo $compania_direccion; ?></p>
              </div>
              <div class="col-lg-6 info" data-aos="fade-up" data-aos-delay="100" style="display: none;">
                <i class="bx bx-phone"></i>
                <h4>Telefonos</h4>
                <p><?php echo $compania_telf; ?><br><?php echo $compania_whatsapp; ?></p>
              </div>
              <div class="col-lg-12 info" data-aos="fade-up" data-aos-delay="200" style="padding: 115px 60px;">
                <i class="bx bx-envelope"></i>
                <h4>Email</h4>
                <p><?php echo $compania_email; ?></p>
              </div>
              <div class="col-lg-6 info" data-aos="fade-up" data-aos-delay="300" style="display: none;">
                <i class="bx bx-time-five"></i>
                <h4>Horario</h4>
                <p>Lun - Vie: 9AM - 5PM</p>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <form action="<?php echo $baseurl;?>trabajaconnosotros.php" method="post" class="php-email-form">
              <div class="form-group">
                <input placeholder="Nombre y Apellido" type="text" name="name" class="form-control" id="name" data-rule="" data-msg="" />
                <div class=""></div>
              </div>
              <div class="form-group">
                <input placeholder="Email" type="email" class="form-control" name="email" id="email" data-rule="email" data-msg="Please enter a valid email" />
                <div class=""></div>
              </div>
              <div class="form-group">
                <input placeholder="Telefono" type="text" class="form-control" name="phone" id="phone" data-rule="" data-msg="" />
                <div class=""></div>
              </div>
              <div class="form-group">
                <textarea placeholder="Cuentanos tu experiencia y donde te encuentras" class="form-control" name="message" rows="7" data-rule="required" data-msg=""></textarea>
                <div class=""></div>
              </div>
              <div class="text-center"><button type="submit">Enviar</button></div>
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