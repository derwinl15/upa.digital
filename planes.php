<?php
define ("EXP",6000000);
setlocale (LC_CTYPE, 'es_ES');
ini_set ("display_errors","0");
ini_set ("memory_limit","-1");

include_once 'lib/funciones.php';
include_once 'lib/mysqlclass.php';
include_once 'lib/config.php';


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

  listaplan.listaplan_id, listaplan.l_plan_id, listaplan.listaplan_precio,
  listaplan.listaplan_porccomision, listaplan.listaplan_contactos, 
  listaplan.listaplan_diasduracion, listaplan.listaplan_habiltarminutos, 
  listaplan.listaplan_notifwhatsapp, listaplan.cuenta_id, listaplan.compania_id, l_moneda_id,  listaplan.cuenta_id, listaplan.compania_id, 
  listaplan.listaplan_fechareg, listaplan.usuario_idreg,

  plan.lista_id as plan_id, plan.lista_nombre as plan_nombre, plan.lista_img as plan_img,
  plan.lista_orden as plan_orden, plan.lista_activo as plan_activo,

  cuenta.usuario_codigo as cuenta_codigo, cuenta.usuario_nombre as cuenta_nombre,
  cuenta.usuario_apellido as cuenta_apellido, compania_nombre,

  listaplandet_id , listaplandet_descrip, listaplandet_orden, listaplandet_activo,

  moneda.lista_nombredos as moneda_siglas

    ",
  "
  listaplan
    inner join lista plan on plan.lista_id = listaplan.l_plan_id          
    inner join lista moneda on moneda.lista_id = listaplan.l_moneda_id          
    inner join usuario cuenta on listaplan.cuenta_id = cuenta.usuario_id
    inner join compania on compania.compania_id = listaplan.compania_id       
    left join listaplandetalle on listaplandetalle.l_plan_id = plan.lista_id and listaplandet_activo = '1'
  ",
  " plan.lista_activo = '1' and plan.cuenta_id = '$SistemaCuentaId' and plan.compania_id = '$SistemaCompaniaId' ", null, "plan.lista_orden, plan.lista_id, listaplandetalle.listaplandet_orden  asc");

$cont = 0;
foreach($arrresultado as $i=>$valor){

  $plan_id = utf8_encode($valor["plan_id"]);

  if ($plan_id_old!=$plan_id && $cont!="0"){   

    

    $planeshtml .= "      
          <div class='col-lg-4 box' data-aos='fade-right'>
            <h3>$plan_nombre</h3>
            <h4>$divprecio</h4>
            <ul>
              $plandetallehtml
            </ul>
            <a href='".$baseurl."registrar?i=1' class='get-started-btn'>Comprar</a>
          </div>
    ";

     $plandetallehtml ="";

  }

  $listaplandet_id  = utf8_encode($valor["listaplandet_id"]);
  $moneda_siglas = utf8_encode($valor["moneda_siglas"]);
  $listaplandet_descrip = utf8_encode($valor["listaplandet_descrip"]);
  $listaplandet_orden = utf8_encode($valor["listaplandet_orden"]);
  $listaplandet_activo = utf8_encode($valor["listaplandet_activo"]);

  $listaplan_id = utf8_encode($valor["listaplan_id"]);
  $l_plan_id = utf8_encode($valor["l_plan_id"]);
  $listaplan_precio = utf8_encode($valor["listaplan_precio"]);
  $listaplan_porccomision = utf8_encode($valor["listaplan_porccomision"]);
  $listaplan_contactos = utf8_encode($valor["listaplan_contactos"]);
  $listaplan_diasduracion = utf8_encode($valor["listaplan_diasduracion"]);
  $listaplan_habiltarminutos = utf8_encode($valor["listaplan_habiltarminutos"]);
  $listaplan_notifwhatsapp = utf8_encode($valor["listaplan_notifwhatsapp"]);
  $t_cuenta_id = utf8_encode($valor["cuenta_id"]);
  $t_compania_id = utf8_encode($valor["compania_id"]);    
  $listaplan_fechareg = utf8_encode($valor["listaplan_fechareg"]);
  
  $plan_nombre = utf8_encode($valor["plan_nombre"]);

  $plan_img = utf8_encode($valor["plan_img"]);  
  $plan_orden = utf8_encode($valor["plan_orden"]);  
  $plan_activo = utf8_encode($valor["plan_activo"]);  

  $urlimgPlan = $UrlFiles."admin/arch/$plan_img";

  $cuenta_nombre = utf8_encode($valor["cuenta_nombre"]);
  $cuenta_apellido = utf8_encode($valor["cuenta_apellido"]);
  $cuenta_codigo = utf8_encode($valor["cuenta_codigo"]);
  $cuenta = $cuenta_nombre." ".$cuenta_apellido." ";

  $listaplan_precioorig = $listaplan_precio;

  if ($listaplan_precio==""){$listaplan_precio=0;}

  $listaplan_precio = number_format($listaplan_precio,2,",",".");

  if ($listaplan_precioorig=="0" || $listaplan_precioorig==""){
    $divprecio = "
     
    ";
    $divcontratar = "
      
    ";
  }else{
    $divprecio = "
      <h4><sup>$moneda_siglas</sup>$listaplan_precio<span> / $listaplan_diasduracion días</span></h4>      
    ";

    $divcontratar = "
      <a href='".$baseurl."plan-forma-pago?id=$plan_id' class='btn btn-success' style='margin-top: 5px'>Contratar</a>
    ";
  }

  $plandetallehtml .= "
        <li class='my-4'> <i class='fa fa-check'></i> $listaplandet_descrip</li>
    ";

  $plan_id_old = $plan_id;

  $cont = $cont +1;

}


if ($plandetallehtml!=""){
  $planeshtml .= "

          <div class='col-lg-4 box' data-aos='fade-up'>
            <h3>$plan_nombre</h3>
            <h4>$divprecio</h4>
            <ul>
              $plandetallehtml
            </ul>
            <a href='".$baseurl."registrar?i=1' class='get-started-btn'>Comprar</a>
          </div>

    ";
}



//Preguntas Frecuentes

$arrresultado = $conexion->doSelect("
    pregunta.preg_id, pregunta.preg_nombre, pregunta.preg_respuesta, pregunta.preg_img, 
    pregunta.preg_videourl, pregunta.preg_videocodigo, pregunta.preg_url, 
    pregunta.l_tiposeccion_id, pregunta.cuenta_id, pregunta.compania_id, 
    pregunta.preg_activo, pregunta.preg_eliminado, 
    pregunta.preg_orden, pregunta.usuario_idreg,
    tiposeccion.lista_nombre as tiposeccion_nombre,
    tiposeccion.lista_img as tiposeccion_img,
    tiposeccion.lista_nombredos as tiposeccion_nombredos,
    tiposeccion.lista_icono as tiposeccion_icono,
    tiposeccion.lista_orden as tiposeccion_orden,
    DATE_FORMAT(pregunta.preg_fechareg,'%d/%m/%Y %H:%i:%s') as preg_fechareg,       
    cuenta.usuario_codigo as cuenta_codigo, cuenta.usuario_nombre as cuenta_nombre,
    cuenta.usuario_apellido as cuenta_apellido, compania_nombre
    ",
    "pregunta           
        inner join usuario cuenta on cuenta.usuario_id = pregunta.cuenta_id
        inner join compania on compania.compania_id = pregunta.compania_id        
        left join lista tiposeccion on tiposeccion.lista_id = pregunta.l_tiposeccion_id  
    ",
    "preg_activo = '1'  and pregunta.compania_id = '$SistemaCompaniaId' and pregunta.cuenta_id = '$SistemaCuentaId' ", null, "tiposeccion_orden, pregunta.l_tiposeccion_id desc");

  $contpreguntas = 0;
  foreach($arrresultado as $i=>$valor){

    $preg_id = utf8_encode($valor["preg_id"]);
    $preg_nombre = utf8_encode($valor["preg_nombre"]);
    $preg_respuesta = utf8_encode($valor["preg_respuesta"]);
    $preg_videocodigo = utf8_encode($valor["preg_videocodigo"]);

    $contpreguntas = $contpreguntas + 1;

    $videoiframe = "";

    if ($preg_videocodigo!=""){
        $videoiframe = "
          <div class='row'>
            <div class='col-md-8'>
              <iframe allowfullscreen='' frameborder='0' width='100%' height='420px' src='https://www.youtube.com/embed/$preg_videocodigo'></iframe>
              </div>
            </div>
        ";
    }


    $preguntasfrecuenteshtml .="

            <li data-aos='fade-up'>
              <i class='bx bx-help-circle icon-help'></i> <a data-toggle='collapse' class='collapsed' href='#accordion-$preg_id'><span class='number'>$contpreguntas .-</span>$preg_nombre<i class='bx bx-chevron-down icon-show'></i><i class='bx bx-chevron-up icon-close'></i></a>
              <div id='accordion-$preg_id' class='collapse' data-parent='.accordion-list'>
                <p>
                  $preg_respuesta
                </p>
              </div>
            </li>

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
<html lang="en">

<head>
  <?php echo $base; ?>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $compania_nombre;?> - Planes</title>
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

    <!-- ======= Pricing Section ======= -->
    <section id="hero" class="pricing">
      <div class="container">

        <div class="section-title">
          <h1>Precios</h1>
          <p>Tenemos el plan ideal para tu negocio.</p>
        </div>
        <hr style="border: 1px solid  #002755;"  id="">

        <div class="row no-gutters">

          <?php echo $planeshtml;?>

        </div>

        <h3 style="color: #59A618; font-weight: bold;">Si desea obtener algun plan personalizado pulse Aqui --><a href="contacto" style="padding: 12px 50px; margin-left: -45px;"><button class="btn btn-warning btn-lg">Contacto</button></a></h3>

      </div>
    </section><!-- End Pricing Section -->

  </main><!-- End #main -->



  <!-- ======= Footer ======= -->
  <?php include_once "footer.php" ?>
  <!-- End Footer -->

</body>

</html>