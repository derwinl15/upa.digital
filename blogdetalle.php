<?php
ini_set ("display_errors","0");
include_once 'lib/config.php';
include_once 'lib/funciones.php';

$SistemaCuentaId = SistemaCuentaId;
$SistemaCompaniaId = SistemaCompaniaId;
$UrlFiles = UrlFiles;
$UrlIcono = UrlIcono;
$UrlStyle = UrlStyle;

(isset($_GET['url'])) ? $getblogurl=$_GET['url'] :$getblogurl='';

$conexion = new ConexionBd();


$arrresultado = $conexion->doSelect("

    blog.blog_id, blog.blog_nombre, blog.blog_descrip, blog.blog_img, blog.cuenta_id, blog.compania_id, 
    blog.blog_activo, blog.blog_eliminado, blog.blog_orden, blog.usuario_idreg, blog_url,
    DATE_FORMAT(blog.blog_fechareg,'%d/%m/%Y %H:%i:%s') as blog_fechareg,
    cuenta.usuario_codigo as cuenta_codigo, cuenta.usuario_nombre as cuenta_nombre,
    cuenta.usuario_apellido as cuenta_apellido, compania_nombre,
    blog.l_categserv_id, servicio.lista_nombre as servicio_nombre,
    blog.l_subcategserv_id, subservicio.lista_nombre as subservicio_nombre, blog_videocodigo
    ",
    "blog                       
        inner join usuario cuenta on cuenta.usuario_id = blog.cuenta_id
        inner join compania on compania.compania_id = blog.compania_id      
        left join lista servicio on servicio.lista_id = blog.l_categserv_id    
        left join lista subservicio on subservicio.lista_id = blog.l_subcategserv_id    
    ",
    "blog_activo = '1'  and blog.compania_id = '$SistemaCompaniaId' and blog.cuenta_id = '$SistemaCuentaId' and blog_url = '$getblogurl' ", null, "blog_id desc");


$cont = 1;

foreach($arrresultado as $i=>$valor){

    $l_categserv_id = utf8_encode($valor["l_categserv_id"]);
    $servicio_nombre = utf8_encode($valor["servicio_nombre"]);
    $l_subcategserv_id = utf8_encode($valor["l_subcategserv_id"]);
    $subservicio_nombre = utf8_encode($valor["subservicio_nombre"]);

    $blog_url = utf8_encode($valor["blog_url"]);

    $cuenta_nombre = utf8_encode($valor["cuenta_nombre"]);
    $cuenta_apellido = utf8_encode($valor["cuenta_apellido"]);
    $cuenta_codigo = utf8_encode($valor["cuenta_codigo"]);
    $cuenta = $cuenta_nombre." ".$cuenta_apellido." ";
    $compania_nombre = utf8_encode($valor["compania_nombre"]);

    $l_categblog_id = utf8_encode($valor["l_categblog_id"]);
    $categblog_nombre = utf8_encode($valor["categblog_nombre"]);

    $blog_id = utf8_encode($valor["blog_id"]);
    $blog_nombre = utf8_encode($valor["blog_nombre"]);
    $blog_descrip = utf8_encode($valor["blog_descrip"]);
    $blog_img = utf8_encode($valor["blog_img"]);
    $blog_activo = utf8_encode($valor["blog_activo"]);
    $blog_orden = utf8_encode($valor["blog_orden"]);
    $blog_fechareg = utf8_encode($valor["blog_fechareg"]);      

    $t_cuenta_id = utf8_encode($valor["cuenta_id"]);
    $t_compania_id = utf8_encode($valor["compania_id"]);

    $imgblog = $UrlFiles."/admin/arch/$blog_img";

    $blog_videocodigo = utf8_encode($valor["blog_videocodigo"]);

    if ($blog_videocodigo!=""){
        $videoiframe = "
            <iframe allowfullscreen='' frameborder='0' width='100%' height='420px' src='https://www.youtube.com/embed/$blog_videocodigo'></iframe>
        ";
    }

    $mostrartitulocategsubcateg="";
    $mostrartitulocategblog="";
    
    if ($subservicio_nombre!=""){
        $mostrartitulocategblog = "$subservicio_nombre ";
        $mostrartitulocategsubcateg = $mostrartitulocategblog;
    }else{
        $mostrartitulocategblog = "$servicio_nombre ";
        $mostrartitulocategsubcateg = $mostrartitulocategblog;
    }


    if($cont == 1){
        $posicion = "wow fadeInLeft";
        $cont = 0;
    }else{
        $posicion = "wow timeline-inverted fadeInRight";
        $cont = 1;
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

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php echo $base; ?>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $compania_nombre;?> - Blog Detale</title>
  <meta content="<?php echo $blog_nombre;?>" name="title">
  <meta content="<?php echo $blog_descrip;?>" name="description">
  <meta content="<?php echo $blog_nombre;?>" name="keywords">

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


        <!-- ======= Details Section ======= -->
    <section id="hero" class="details">
      <div class="container">
        <div class="row content">
          <div class="col-md-4" data-aos="fade-right">
            <img src="<?php echo $imgblog; ?>" class="img-fluid" alt="">

          </div>
          <div class="col-md-8 pt-4" data-aos="fade-up">
            <h3><?php echo $blog_nombre; ?></h3>
            <p class="font-italic">
              <?php echo $blog_descrip; ?>
            </p>
          </div>
        </div>
      </div>
    </section><!-- End Details Section -->


  </main><!-- End #main -->



  <!-- ======= Footer ======= -->
  <?php include_once "footer.php" ?>
  <!-- End Footer -->


</body>

</html>