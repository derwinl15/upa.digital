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
    $compania_latitud = utf8_encode($valor["compania_latitud"]);
    $compania_longitud = utf8_encode($valor["compania_longitud"]);



    $urlcompaniaimg = $UrlFiles."/admin/arch/$compania_img";
    $urlcompaniaimgicono = $UrlFiles."/admin/arch/$compania_imgicono";

    $titulopagina = "Contacto - ".$compania_nombre;
    $descripcionpagina = "Contacto - $compania_nombre ";

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

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php echo $base; ?>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $compania_nombre;?> - Contacto</title>
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
    <section id="contact" class="contact">
      <div class="container">

        <!-- ======= Respuesta automatica Email ======= -->
        <?php echo $info;?>
        <!-- ======= End espuesta automatica Email ======= -->

       

        <div class="section-title">
          <h2>Contacto</h2>
          <p></p>
          <hr style="border: 1px solid  #002755;"  id="">
        </div>

        <div class="row">

          <div class="col-lg-6">
            <div class="row">
              <div class="col-lg-12 info" data-aos="fade-up">
                <i class="bx bx-map"></i>
                <h4>Dirección</h4>
                <p><?php echo $compania_direccion; ?></p>
              </div>
              <div class="col-lg-6 info" data-aos="fade-up" data-aos-delay="100" style="display: none">
                <i class="bx bx-phone"></i>
                <h4>Telefonos</h4>
                <p><?php echo $compania_telf; ?><br><?php echo $compania_whatsapp; ?></p>
              </div>
              <div class="col-lg-12 info" data-aos="fade-up" data-aos-delay="200">
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
            <form action="<?php echo $baseurl;?>contacto.php" method="post" role="form" class="php-email-form" data-aos="fade-up">
              <div class="form-group">
                <input placeholder="Nombre" type="text" name="name" class="form-control" id="name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
                <div class=""></div>
              </div>
              <div class="form-group">
                <input placeholder="Email" type="email" class="form-control" name="email" id="email" data-rule="email" data-msg="Please enter a valid email" />
                <div class=""></div>
              </div>
              <div class="form-group">
                <input placeholder="Asunto" type="text" class="form-control" name="subject" id="subject" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" />
                <div class=""></div>
              </div>
              <div class="form-group">
                <textarea placeholder="Mensaje" class="form-control" name="message" rows="5" data-rule="required" data-msg="Please write something for us"></textarea>
                <div class=""></div>
              </div>
              <div class="text-center"><button type="submit">Enviar</button></div>
            </form>
          </div>

        </div>
         <div style="margin-bottom: 15px">
          <iframe style="border:0; width: 100%; height: 270px;" src="https://maps.google.com/maps?q=<?php echo $compania_latitud;?>,<?php echo $compania_longitud;?>&hl=es;z=14&amp;output=embed" frameborder="0" allowfullscreen></iframe>
        </div>

      </div>
    </section><!-- End Contact Section -->

  </main><!-- End #main -->



  <!-- ======= Footer ======= -->
  <?php include_once "footer.php" ?>
  <!-- End Footer -->


</body>

</html>