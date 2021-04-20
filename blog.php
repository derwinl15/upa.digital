<?php
ini_set ("display_errors","0");
include_once 'lib/config.php';
include_once 'lib/funciones.php';

$SistemaCuentaId = SistemaCuentaId;
$SistemaCompaniaId = SistemaCompaniaId;
$UrlFiles = UrlFiles;
$UrlIcono = UrlIcono;
$UrlStyle = UrlStyle;

$conexion = new ConexionBd();


$arrresultado = $conexion->doSelect("

    blog.blog_id, blog.blog_nombre, blog.blog_descrip, blog.blog_img, blog.cuenta_id, blog.compania_id, 
    blog.blog_activo, blog.blog_eliminado, blog.blog_orden, blog.usuario_idreg, blog_url,
    DATE_FORMAT(blog.blog_fechareg,'%d/%m/%Y %H:%i:%s') as blog_fechareg,
    cuenta.usuario_codigo as cuenta_codigo, cuenta.usuario_nombre as cuenta_nombre,
    cuenta.usuario_apellido as cuenta_apellido, compania_nombre,
    blog.l_categserv_id, servicio.lista_nombre as servicio_nombre,
    blog.l_subcategserv_id, subservicio.lista_nombre as subservicio_nombre
    ",
    "blog                       
        inner join usuario cuenta on cuenta.usuario_id = blog.cuenta_id
        inner join compania on compania.compania_id = blog.compania_id      
        left join lista servicio on servicio.lista_id = blog.l_categserv_id    
        left join lista subservicio on subservicio.lista_id = blog.l_subcategserv_id 
        left join lista on blog.l_categserv_id =  lista.lista_id
    ",
    "blog_activo = '1'  and blog.compania_id = '$SistemaCompaniaId' and blog.cuenta_id = '$SistemaCuentaId' ", null, "blog_id desc");

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


    $bloghtml .="

          <div class='col-lg-4 box; img-thumbnail' style= 'padding: 10px' data-aos='fade-right'>
            <div>
              <a href='".$baseurl."blog/$blog_url'><img src='$imgblog' style = 'max-height: 265px; width: 100%'alt='' class='img-fluid'></a>
            </div>
            <div style='text-align: center; font-size: 30px;'> 
              <a href='".$baseurl."blog/$blog_url'>$blog_nombre</a>
            </div>
            <center>
            <div style = 'height: 100px; overflow: hidden'>
                  $blog_descrip
            </div>
            <br>
            <div>
              <a href='".$baseurl."blog/$blog_url' class='btn btn-success'>Seguir leyendo</a>
            </div>
            </center>
          </div>




    ";

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

if ($bloghtml==""){
  $bloghtml ="
      <div class='col-lg-12' style= 'padding: 10px' data-aos='fade-right'>
        <div class='alert alert-danger' style='text-align: center; font-weight: normal;'>
              <a style='color: #000; font-size: 14px; text-decoration: none;'>
                  Sin Noticias Registradas
              </a><br>
          </div>
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

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <?php echo $base; ?>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $compania_nombre;?> - Blog.</title>
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

    <!-- ======= App Features Section ======= -->
    <section id="hero" class="features">
      <div class="container">

        <div class="section-title">
          <h1>Blog</h1>
          <p></p>
        </div>
        <hr style="border: 1px solid  #002755;"  id="">

  
        <div class="row">

            <!-- blog entry -->
            <?php echo $bloghtml;?>  
            <!-- End blog entry -->


        </div>



      </div>
    </section><!-- End App Features Section -->


  </main><!-- End #main -->



  <!-- ======= Footer ======= -->
  <?php include_once "footer.php" ?>
  <!-- End Footer -->


</body>

</html>