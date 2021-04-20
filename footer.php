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

    $titulopagina = "Contacto - ".$compania_nombre;
    $descripcionpagina = "Contacto - $compania_nombre ";

}

$arrresultado = $conexion->doSelect("tipolista.tipolista_id, tipolista_nombre, tipolista_descrip,
  lista.lista_id, lista_cod, lista_img,
  lista.lista_nombre, listaredsocial_nombre, listaredsocial_url, listaredsocial_id

",
  "
  tipolista
    inner join lista on lista.tipolista_id = tipolista.tipolista_id
    left join listacuenta on listacuenta.lista_id = lista.lista_id
    and listacuenta_activo = '1' and listacuenta.tipolista_id = '59'    
    inner join listaredsocial on listaredsocial.listacuenta_id = listacuenta.listacuenta_id

  ",
  "tipolista_activo = '1' and tipolista.tipolista_id = '59' and listacuenta.cuenta_id = '$SistemaCuentaId' and listacuenta.compania_id = '$SistemaCompaniaId' ", null, "lista_orden asc");


  foreach($arrresultado as $i=>$valor){
              
  $red_lista_cod = utf8_encode($valor["lista_cod"]);
  $red_listaredsocial_nombre = utf8_encode($valor["listaredsocial_nombre"]);
  $red_listaredsocial_url = utf8_encode($valor["listaredsocial_url"]);
  $red_listaredsocial_id = utf8_encode($valor["listaredsocial_id"]);
  $red_redsocial_nombre = utf8_encode($valor["lista_nombre"]); 
  $lista_img = utf8_encode($valor["lista_img"]); 

  $urlimgredsocial = $UrlFiles."/admin/arch/$lista_img";

    $agregarredes .="

      <a href='$red_listaredsocial_url' target='_blank' title='$red_redsocial_nombre $red_listaredsocial_nombre'>                    
        <img src='$urlimgredsocial' style='height: 30px'>
      </a>  
    ";

}

  $arrresultado = $conexion->doSelect("

    seccion.seccion_id, seccion.seccion_nombre, seccion.seccion_descrip, seccion.seccion_img, seccion.cuenta_id, seccion.compania_id, seccion_url,
    seccion.seccion_activo, seccion.seccion_eliminado, seccion.seccion_orden, seccion.usuario_idreg,
    DATE_FORMAT(seccion.seccion_fechareg,'%d/%m/%Y %H:%i:%s') as seccion_fechareg,
    cuenta.usuario_codigo as cuenta_codigo, cuenta.usuario_nombre as cuenta_nombre,
    cuenta.usuario_apellido as cuenta_apellido, compania_nombre,
    seccion.l_tiposeccion_id, tipomenu.lista_cod
    
    ",
    "seccion                        
        inner join usuario cuenta on cuenta.usuario_id = seccion.cuenta_id
        inner join compania on compania.compania_id = seccion.compania_id       
        inner join lista tipomenu on tipomenu.lista_id = seccion.l_tipomenuweb_id
        inner join seccionubicacion on seccionubicacion.seccion_id = seccion.seccion_id and seccionubicacion_activo = '1'
        inner join lista listaseccion on listaseccion.lista_id = seccionubicacion.l_ubicseccion_id 
    ",
    "seccion_activo = '1' and listaseccion.lista_cod = '2' and seccion.cuenta_id = '$SistemaCuentaId' and seccion.compania_id = '$SistemaCompaniaId'", null, "seccion_orden asc ");
    foreach($arrresultado as $i=>$valor){


    $footer_seccion_id = utf8_encode($valor["seccion_id"]);
    $footer_seccion_nombre = utf8_encode($valor["seccion_nombre"]);
    $footer_seccion_descrip = utf8_encode($valor["seccion_descrip"]);
    $footer_lista_cod = utf8_encode($valor["lista_cod"]);
    $footer_seccion_url = utf8_encode($valor["seccion_url"]);

    if($footer_lista_cod == "0"){
        $footer_lista_cod = $footer_seccion_url;
    }



    $footer_titulopagina = "$footer_seccion_nombre - $compania_nombre ";
    $footer_descripcionpagina = "$footer_seccion_descrip - $compania_nombre ";



    $menufooter .="

              <li><i class='bx bx-chevron-right'></i> <a href='$baseurl$footer_lista_cod'>$footer_seccion_nombre</a></li>
          ";



  }







?>

  <!-- ======= Footer ======= -->
  <footer id="footer">

    <div class="footer-newsletter" data-aos="fade-up">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6">
            <h4>Subscribete a Nuestro Newsletter</h4>
            <p>Suscribete para que estes al dia con de nuestras noticias y promociones</p>
            <form action="<?php echo $baseurl;?>boletin" method="post">
              <input type="email" name="email" required="required" placeholder="Email"><input type="submit" value="Subscribirse" >
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6 footer-contact" data-aos="fade-up">
            <a href="<?php echo $baseurl;?>"><img src="<?php echo $urlcompaniaimg; ?>" class="img-fluid" style= "margin-left: 0px"></a>
            <p style="text-align: center;"><a href="mailto:contacto@upa.digital" ><?php echo $compania_email; ?><br></a> </p>
            <p style="display: none;">
              <span><?php echo $compania_direccion; ?></span><br><br>
              <strong>Telefono:</strong><a href="https://api.whatsapp.com/send?phone=<?php echo $compania_telf; ?>" style="color: #8a8c95" target = "_blank"> <?php echo $compania_telf; ?></a><br>
              <strong>Whatsapp:</strong><a href="https://api.whatsapp.com/send?phone= <?php echo $compania_whatsapp; ?>" style="color: #8a8c95" target = "_blank"> <?php echo $compania_whatsapp; ?></a><br>
              <strong>Email:</strong> <?php echo $compania_email; ?><br>
            </p>
          </div>

          <div class="col-lg-3 col-md-6 footer-links" data-aos="fade-up" data-aos-delay="100">
            <h4>Compañia</h4>
            <ul>
              <?php echo $menufooter; ?>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links" data-aos="fade-up" data-aos-delay="200">
            <div <?php echo $displaynoneservicios;?> >
            <h4>Nuestros Servicios</h4>

              <?php echo $serviciosfooter;?>
            
          </div>
          <div <?php echo $displaynoneservicios2;?> >
            <h4>Nuestros Servicios</h4>
            <ul>
                <li><i class="bx bx-chevron-right"></i> <a href="<?php echo $baseurl?>funciones">Calendario de Reservas</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="<?php echo $baseurl?>funciones">Recordatorio de Reservas</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="<?php echo $baseurl?>funciones">Envio de Promociones</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="<?php echo $baseurl?>funciones">Venta de Productos</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="<?php echo $baseurl?>funciones">Aplicacion de Reservas</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="<?php echo $baseurl?>funciones">Registro de Clientes</a></li>
              </ul>
          </div>
          </div>

          <div class="col-lg-3 col-md-6 footer-links" data-aos="fade-up" data-aos-delay="300">
            <h4>Nuestras Redes Sociales</h4>
            <p></p>
            <div class="social-links mt-3" style="margin-left: 30px;">
              <?php echo $agregarredes; ?>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="container py-4">
      <div class="copyright">
        &copy; Copyright <strong><span>Mi Sistema Web</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/free-bootstrap-app-landing-page-template/ -->
        Designed by <a href="https://www.misistemaweb.com/">Mi Sistema Web</a>
      </div>

      <body>
        <div id="btn-whatsapp"></div>
      </body>


      
    <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
    <script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
    <script src="assets/vendor/venobox/venobox.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script type="text/javascript" src="assets/js/floating-wpp.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/func.js"></script>

    <script type="text/javascript">
      $(function () {
          $('#btn-whatsapp').floatingWhatsApp({
              phone: '<?php echo $compania_whatsapp; ?>',
              popupMessage: 'Hola, En que podriamos ayudarte?',
              message: "Quiero información acerca del servicio",
              showPopup: true,
              showOnIE: false,
              headerTitle: 'Bienvenido!',
              headerColor: 'green',
              backgroundColor: 'green',
              buttonImage: '<img src="assets/img/whatsapp.svg" />'
          });


          $('.carousel').carousel({
            interval: 2000
          })

      });
    </script>

      </div>
    </footer>
  <!-- End Footer -->