<?php
define ("EXP",6000000);
setlocale (LC_CTYPE, 'es_ES');
ini_set ("display_errors","0");
ini_set ("memory_limit","-1"); //

include_once 'lib/funciones.php';
include_once 'lib/mysqlclass.php';
include_once 'lib/config.php';

(isset($_GET['s'])) ? $getsalir=$_GET['s'] :$getsalir='';

if($getsalir=="1"){
  session_start();
  session_destroy();

  setcookie("iniuser","", time() - 86400, "/", $_SERVER['HTTP_HOST']); 
  setcookie("login","", time() - 86400, "/", $_SERVER['HTTP_HOST']); 
  setcookie("perfilactual","", time() - 86400, "/", $_SERVER['HTTP_HOST']); 
  setcookie("perfil","", time() - 86400, "/", $_SERVER['HTTP_HOST']); 
  setcookie("imguser","", time() - 86400, "/", $_SERVER['HTTP_HOST']); 
  setcookie("idcuenta","", time() - 86400, "/", $_SERVER['HTTP_HOST']); 
  setcookie("idcompania","", time() - 86400, "/", $_SERVER['HTTP_HOST']);  
}

$SistemaCuentaId = SistemaCuentaId;
$SistemaCompaniaId = SistemaCompaniaId;
$UrlFiles = UrlFiles;


$conexion = new ConexionBd();

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
    "seccion_activo = '1' and tipomenu.lista_cod = 'index' and seccion.cuenta_id = '$SistemaCuentaId' and seccion.compania_id = '$SistemaCompaniaId' ");
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




  $arrresultado = $conexion->doSelect("

    lista.lista_id, lista.lista_nombre, lista.lista_img, lista.lista_orden, lista.lista_activo, lista.lista_ppal,     
    lista.cuenta_id as cuenta_idsistema, lista.compania_id as compania_idsistema, lista_descrip,
        
    cuenta.usuario_codigo as cuenta_codigo, cuenta.usuario_nombre as cuenta_nombre,
      cuenta.usuario_apellido as cuenta_apellido, compania.compania_nombre as compania_nombre,
      cuenta.usuario_codigo as cuentasistema_codigo, cuenta.usuario_nombre as cuentasistema_nombre,
      cuenta.usuario_apellido as cuentasistema_apellido, companiasistema.compania_nombre as companiasistema_nombre,

      listacuenta.cuenta_id, listacuenta.compania_id,
      listacuenta.listacuenta_id, listacuenta.listacuenta_activo, listacuenta.listacuenta_eliminado, 
      listacuenta.listacuenta_img, listacuenta.listacuenta_orden,
    listacuenta.listacuenta_nombre,
    lista.tipolista_id
      ",
    "
    lista 

      inner join usuario cuentasistema on cuentasistema.usuario_id = lista.cuenta_id
        inner join compania companiasistema on companiasistema.compania_id = lista.compania_id              

      inner join listacuenta on listacuenta.lista_id = lista.lista_id
      $wherelistacuenta
            
      inner join usuario cuenta on cuenta.usuario_id = listacuenta.cuenta_id
        inner join compania on compania.compania_id = listacuenta.compania_id             

        $wherecuenta
        $wherecompania

        

    ",
    "lista.lista_eliminado = '0' $wherecuenta and lista.tipolista_id = '166'  and ((lista.lista_ppal = '1' $wherelistaactivo) or (lista.lista_ppal = '0' ))  ", null, "lista.lista_orden asc");


    foreach($arrresultado as $i=>$valor){
     
      $listacuenta_img = utf8_encode($valor["listacuenta_img"]); 
      $lista_nombre = utf8_encode($valor["lista_nombre"]);
      $lista_descrip = utf8_encode($valor["lista_descrip"]); 
      $lista_img = $listacuenta_img;
      $imagen = "<img src='".$UrlFiles."admin/arch/$lista_img' class='testimonial-img' style='height: 80px'>";

      if ($count == 0 ) {
        $color= "style='background-color: #002755'";
        $count = 1;
      }else {
        $color= "style='background-color: #59A618'";
        $count = 0;
      }

      $testimonios .="

          <div class='testimonial-wrap'>
            <div class='testimonial-item' $color>
              $imagen
              <h3>$lista_nombre</h3>
              <p>
                <i class='bx bxs-quote-alt-left quote-icon-left'></i>
                  $lista_descrip
                <i class='bx bxs-quote-alt-right quote-icon-right'></i>
              </p>
            </div>
          </div> 

      ";

    }



    $arrresultado = $conexion->doSelect("

    lista.lista_id, lista.lista_nombre, lista.lista_img, lista.lista_orden, lista.lista_activo, lista.lista_ppal,     
    lista.cuenta_id as cuenta_idsistema, lista.compania_id as compania_idsistema,
        
    cuenta.usuario_codigo as cuenta_codigo, cuenta.usuario_nombre as cuenta_nombre,
      cuenta.usuario_apellido as cuenta_apellido, compania.compania_nombre as compania_nombre,
      cuenta.usuario_codigo as cuentasistema_codigo, cuenta.usuario_nombre as cuentasistema_nombre,
      cuenta.usuario_apellido as cuentasistema_apellido, companiasistema.compania_nombre as companiasistema_nombre,

      listacuenta.cuenta_id, listacuenta.compania_id,
      listacuenta.listacuenta_id, listacuenta.listacuenta_activo, listacuenta.listacuenta_eliminado, 
      listacuenta.listacuenta_img, listacuenta.listacuenta_orden,
    listacuenta.listacuenta_nombre,
    lista.tipolista_id
      ",
    "
    lista 

      inner join usuario cuentasistema on cuentasistema.usuario_id = lista.cuenta_id
        inner join compania companiasistema on companiasistema.compania_id = lista.compania_id              

      inner join listacuenta on listacuenta.lista_id = lista.lista_id
      $wherelistacuenta
            
      inner join usuario cuenta on cuenta.usuario_id = listacuenta.cuenta_id
        inner join compania on compania.compania_id = listacuenta.compania_id             

        $wherecuenta
        $wherecompania  

    ",
    "lista.lista_eliminado = '0' $wherecuenta and lista.tipolista_id = '163'  and ((lista.lista_ppal = '1' $wherelistaactivo) or (lista.lista_ppal = '0' ))  ", null, "lista.lista_orden asc");

  $count = 0;
  $countitems = 0;

  $slideractive = "active";

  foreach($arrresultado as $i=>$valor){

    $lista_nombre = utf8_encode($valor["lista_nombre"]);
    $lista_img = utf8_encode($valor["lista_img"]);  
    $imagen = "<img src='".$UrlFiles."admin/arch/$lista_img' style='height: 80px'>";

    

    $carouselitems .="
                      <div class='col-md-4'>
                        $imagen
                        <div class='carousel-caption'>
                          <h3></h3>
                        </div>
                      </div>

    ";

    $countitems = $countitems + 1;

    if($countitems == 3){

      if($clientes == ""){
        $slideractive = "active";
      }

      $slider .="
            <li data-target='#carouselExampleIndicators' data-slide-to='$count' class='$slideractive'></li>

    ";
      
      $clientes .="
                    <div class='carousel-item $slideractive'>
                      <div class='row'>
                        $carouselitems
                      </div>
                    </div>

       "; 

       $slideractive = "";

       $countitems = 0;
       $carouselitems = "";
       $count = $count + 1;
    }
    
  }

    if($countitems > 0){

      $slider .="
            <li data-target='#carouselExampleIndicators' data-slide-to='$count' class='$slideractive'></li>

    ";

        $clientes .="
                    <div class='carousel-item $slideractive'>
                      <div class='row'>
                        $carouselitems
                      </div>
                    </div>

   "; 
  }

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php echo $base; ?>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $compania_nombre;?> - Inicio</title>
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

 <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex align-items-center" style="padding-top: 140px;">

    <div class="container">
      <div class="row">
          <!-- ======= Banner ======= -->

        
        <div class="col-md-12">
         <?php include_once "include_bannerslide.php"; ?>  
        </div>
        
          <!-- ======= End Banner ======= -->
      </div>
    </div>

  </section>
  <!-- End Hero -->

  <main id="main">

    <!-- ======= Details Section ======= -->
    <section id="details" class="details">
      <div class="container">

        <div class="row content">
          <div class="col-md-4" data-aos="fade-right">
            <img src="assets/img/details-1.png" class="img-fluid" alt="">
          </div>
          <div class="col-md-8 pt-4" data-aos="fade-up">
            <h3>Ten la gestión de tu negocio bajo control.</h3>
            <p class="font-italic">
              Con el Sistema para peluquerías, estéticas, barberias UpaDigital puedes tener bajo control la gestión de tu negocio. Controla tu stock de productos; las cuentas de tu negocio… Y todo ello desde donde quieras: solo necesitas un dispositivo con conexión a Internet.
            </p>
            <ul>
              <li><i class="icofont-check"></i> Control de empleados .</li>
              <li><i class="icofont-check"></i> Estadísticas e informes financieros con tan solo un click.</li>
              <li><i class="icofont-check"></i> Control de inventario de producto.</li>
            </ul>
          </div>
        </div>

        <div class="row content">
          <div class="col-md-4 order-1 order-md-2" data-aos="fade-left">
            <img src="assets/img/details-2.png" class="img-fluid" alt="">
          </div>
          <div class="col-md-8 pt-5 order-2 order-md-1" data-aos="fade-up">
            <h3>Ahorra tiempo a diario</h3>
            <p class="font-italic">
              Implementa un software de gestión y empieza a ahorrar tiempo en las labores de administración. UpaDigital — el mejor sistema para centros de estética peluquerías, barberias — te ayudará a agilizar tus tareas diarias de forma muy fácil y te permitirá tener más tiempo para tus clientes y para ti.
            </p>
            <ul>
              <li><i class="icofont-check"></i> Agenda digital fácil de usar.</li>
              <li><i class="icofont-check"></i> Página web gratis de reservas online.</li>
              <li><i class="icofont-check"></i> Recordatorios automáticos de cita.</li>
              <li><i class="icofont-check"></i> Pagina Web propia con los servicios de tu empresa.</li>
            </ul>
          </div>
        </div>

        <div class="row content">
          <div class="col-md-4" data-aos="fade-right">
            <img src="assets/img/details-3.png" class="img-fluid" alt="">
          </div>
          <div class="col-md-8 pt-5" data-aos="fade-up">
            <h3>Atrae más clientes a tu negocio</h3>
            <p>Las funciones de marketing automatizado de UpaDigital serán tu as bajo la manga para una rápida y eficaz comunicación con tus clientes. Gracias a ellas, te ganarás su fidelidad hacia tu negocio.  Empieza a utilizar el marketing automatizado y comprueba cómo tu agenda se llena rápidamente de citas.</p>
            <ul>
              <li><i class="icofont-check"></i> Reservas online 24/7</li>
              <li><i class="icofont-check"></i> Campañas de email.</li>
              <li><i class="icofont-check"></i> Programa de fidelización de clientes</li>
            </ul>
          </div>
        </div>

      </div>
    </section>
    <!-- End Details Section -->

    <!-- ======= Testimonios Section ======= -->
    <section id="testimonials" class="testimonials section-bg">
      <div class="container">

        <div class="section-title">
          <h2>Testimonios</h2>
          <p></p>
        </div>

        <div class="owl-carousel testimonials-carousel" data-aos="fade-up">

            <?php echo $testimonios; ?>

        </div>
      </div>
    </section>
    <!-- End Testimonios Section -->


    <!-- ======= Nuestros Clientes Section ======= -->
    <section id="" class="clientes">
      <div class="container">
        <div class="section-title">
          <h2>Nuestros Clientes</h2>
          <p></p>
        </div>
        <div class="row">
          <div class="col-md-1">
            
          </div>
          <div class="col-md-10">
            <div id="carouselExampleIndicators" class="carousel slide carousel-multi-item" data-ride="carousel">
              <ol class="carousel-indicators">
                <?php echo $slider; ?>

              </ol>
              <div class="carousel-inner">

                <?php echo $clientes; ?>

              </div>
              <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only" >Previous</span>
              </a>
              <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Nuestros Clientes Section -->




  </main><!-- End #main -->



  <!-- ======= Footer ======= -->
  <?php include_once "footer.php" ?>
  <!-- End Footer -->


</body>

</html>