<?php

include_once 'lib/config.php';
include_once 'lib/funciones.php';
include_once 'lib/mysqlclass.php';
include_once 'lib/phpmailer/libemail.php';


$SistemaCuentaId = SistemaCuentaId;
$SistemaCompaniaId = SistemaCompaniaId;
$UrlFiles = UrlFiles;
$UrlIcono = UrlIcono;
$UrlStyle = UrlStyle;


(isset($_POST['name'])) ? $name=$_POST['name'] :$name='';
(isset($_POST['email'])) ? $email=$_POST['email'] :$email='';
(isset($_POST['phone'])) ? $phone=$_POST['phone'] :$phone='';
(isset($_POST['message'])) ? $message=$_POST['message'] :$message='';
(isset($_POST['f'])) ? $gettipoform=$_POST['f'] :$gettipoform='';

if ($email!=""){ // Boletín

  $fechaactual = formatoFechaHoraBd();

  $conexion = new ConexionBd();

  $l_tipoform_id = 0;

  $arrresultado = $conexion->doSelect("lista_id","lista","lista.lista_cod = '5' and tipolista_id = '58'");
  foreach($arrresultado as $i=>$valor){
    $l_tipoform_id = utf8_encode($valor["lista_id"]);  
  }

  $resultado = $conexion->doInsert("
  formulario
    (form_nombre, form_apellido, form_email, form_telf, form_whatsapp, form_mensaje, cuenta_id, compania_id, 
    form_activo, form_eliminado, form_fechareg, usuario_idreg, l_tipoform_id) 
  ",
  "'', '', '$email', '', '','', '$SistemaCuentaId', '$SistemaCompaniaId',
   '1', '0','$fechaactual', '0','$l_tipoform_id'");


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
                              Hola $name, recibimos tu solicitud para agregarte al boletin de noticias, en breve te responderemos.
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
                              <strong>Email:</strong> $email
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
            <center>
            <div class='alert alert-success' style='text-align: center; font-weight: bold'>
                <a style='color: #002755'>
                    Solicitud Recibida correctamente, agregado al boletín
                </a>
            </div>   
            </center>
              ";

  $asunto = "Solicitud Recibida - $compania_nombre ";

  $resultado = $libemail->enviarcorreo($email, $asunto, $texto, $SistemaCompaniaId);
  $resultado = $libemail->enviarcorreo($compania_email, $asunto, $texto, $SistemaCompaniaId);

}

$arrresultado = ObtenerDatosCompania($SistemaCuentaId, $SistemaCompaniaId);
foreach($arrresultado as $i=>$valor){

    $compania_id = utf8_encode($valor["compania_id"]);
    $compania_nombre = utf8_encode($valor["compania_nombre"]);
    $compania_img = utf8_encode($valor["compania_img"]);
    $compania_imgicono = utf8_encode($valor["compania_imgicono"]);
    $compania_urlweb = utf8_encode($valor["compania_urlweb"]);

    $urlcompaniaimg = $UrlFiles."admin/arch/$compania_img";
    $urlcompaniaimgicono = $UrlFiles."admin/arch/$compania_imgicono";


}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php echo $base; ?>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $compania_nombre;?> - Boletin</title>
  <meta content="Boletin" name="title">
  <meta content="Boletin" name="description">
  <meta content="Boletin" name="keywords">

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

    <!-- ======= App Features Section ======= -->
    <section id="hero" class="features">
      <div class="container">

        <div class="section-title">
          <h1>Boletin</h1>
          <p></p>
        </div>
        <hr style="border: 1px solid  #002755;"  id="">

     <!-- ======= Boletin Section ======= -->
    <section id="blog" class="blog">
      <div class="container">

        <div class="row">
          <div class="col-12">
            <!-- boletin-->
            <?php echo $info;?>  
            <!-- End boletin -->
          </div>

        </div>
      </div>
    </section>
    <!-- End Boletin Section -->
      </div>
    </section>
    <!-- End App Features Section -->
  </main>
  <!-- End #main -->



  <!-- ======= Footer ======= -->
  <?php include_once "footer.php" ?>
  <!-- End Footer -->


</body>

</html>