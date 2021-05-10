<?php


ini_set ("display_errors","0");
include_once 'lib/config.php';
include_once 'lib/funciones.php';

$SistemaCuentaId = SistemaCuentaId;
$SistemaCompaniaId = SistemaCompaniaId;
$UrlFiles = UrlFiles;
$UrlIcono = UrlIcono;
$UrlStyle = UrlStyle;

(isset($_GET['url'])) ? $getcategurl=$_GET['url'] :$getcategurl='';




$conexion = new ConexionBd();

// Servicios
$where = " and producto.cuenta_id = '$SistemaCuentaId' and producto.compania_id = '$SistemaCompaniaId'  ";

if($getcategurl != "servicios" && $getcategurl != ""){
  $where2 = "and listaurl.lista_url = '$getcategurl'";
}


$arrresultado = $conexion->doSelect("prod_id, prod_nombre, prod_descrip, prod_stockgeneral, prod_activo, prod_eliminado, 
    prod_fechareg, producto.prod_codigo, prod_costo, 
    prod_venta, prod_img, producto.cuenta_id, producto.compania_id,
    producto.l_tipoproducto_id, producto.l_claseproducto_id, producto.l_tipounidad_id,
    cuenta.usuario_codigo as cuenta_codigo, cuenta.usuario_nombre as cuenta_nombre,
    cuenta.usuario_apellido as cuenta_apellido, compania_nombre, prod_ganancia,
    moneda.lista_nombre as moneda_nombre, moneda.lista_nombredos as moneda_siglas,
    tipoproducto.lista_nombre as tipoproducto_nombre, l_intervalo_id, intervalo.lista_nombre as intervalo_nombre
    ",
    "producto           
      inner join usuario cuenta on cuenta.usuario_id = producto.cuenta_id
      inner join lista claseproducto on claseproducto.lista_id = producto.l_claseproducto_id    
        inner join compania on compania.compania_id = producto.compania_id
        left join lista tipoproducto on tipoproducto.lista_id = producto.l_tipoproducto_id
        left join lista moneda on moneda.lista_id = producto.l_moneda_id  
        left join lista intervalo on  intervalo.lista_id = producto.l_intervalo_id
        left join lista listaurl on listaurl.lista_id = producto.l_categ_id
    ",
    "prod_eliminado = '0'  and claseproducto.lista_cod = '2' $where2 $where ", null, "prod_id desc");

  foreach($arrresultado as $i=>$valor){


    $cuenta_nombre = utf8_encode($valor["cuenta_nombre"]);
    $cuenta_apellido = utf8_encode($valor["cuenta_apellido"]);
    $cuenta_codigo = utf8_encode($valor["cuenta_codigo"]);

    $cuenta = $cuenta_nombre." ".$cuenta_apellido." ";

    $compania_nombre = utf8_encode($valor["compania_nombre"]);

    $prod_ganancia = utf8_encode($valor["prod_ganancia"]);
    $prod_id = utf8_encode($valor["prod_id"]);
    $prod_nombre = utf8_encode($valor["prod_nombre"]);
    $tipoproducto_nombre = utf8_encode($valor["tipoproducto_nombre"]);
    $prod_descrip = utf8_encode($valor["prod_descrip"]);
    $prod_stockgeneral = utf8_encode($valor["prod_stockgeneral"]);
    $prod_activo = utf8_encode($valor["prod_activo"]);
    $prod_fechareg = utf8_encode($valor["prod_fechareg"]);
    $t_prod_fechareg = utf8_encode($valor["prod_fechareg"]);
    $prod_codigo = utf8_encode($valor["prod_codigo"]);
    $prod_costo = utf8_encode($valor["prod_costo"]);
    $prod_venta = utf8_encode($valor["prod_venta"]);    
    $t_proveedor_id = utf8_encode($valor["proveedor_id"]);    
    $prod_tienda = utf8_encode($valor["prod_tienda"]);    
    $proveedor_nombre = utf8_encode($valor["proveedor_nombre"]);
    $prod_img = utf8_encode($valor["prod_img"]);      
    $l_tipoproducto_id = utf8_encode($valor["l_tipoproducto_id"]);
    $l_claseproducto_id = utf8_encode($valor["l_claseproducto_id"]);
    $l_tipounidad_id = utf8_encode($valor["l_tipounidad_id"]);
    $l_intervalo_id = utf8_encode($valor["intervalo_nombre"]);
    

    $moneda_nombre = utf8_encode($valor["moneda_nombre"]);
    $moneda_siglas = utf8_encode($valor["moneda_siglas"]);

    $t_cuenta_id = utf8_encode($valor["cuenta_id"]);
    $t_compania_id = utf8_encode($valor["compania_id"]);

    $urlservicioimg = $UrlFiles."/admin/arch/$prod_img";



      $arrresultado2 = $conexion->doSelect("prodsucursal_id, prod_id, productosucursal.sucursal_id, prodsucursal_activo, prodsucursal_eliminado, prodsucursal_fechareg, sucursal_nombre
      ",
      "productosucursal

        left join sucursal on sucursal.sucursal_id = productosucursal.sucursal_id
      ",
      "prodsucursal_eliminado = '0' and prod_id = '$prod_id'", null, "prodsucursal_id desc");

      $sucursalproducto = "";

       foreach($arrresultado2 as $e=>$valor2){

        $prodsucursal_id = utf8_encode($valor2["prodsucursal_id"]);
        $sucursal_nombre = utf8_encode($valor2["sucursal_nombre"]);
        $prod_id = utf8_encode($valor2["prod_id"]);
        $sucursal_id = utf8_encode($valor2["sucursal_id"]);
        $prodsucursal_activo = utf8_encode($valor2["prodsucursal_activo"]);
        $prodsucursal_eliminado = utf8_encode($valor2["prodsucursal_eliminado"]);
        $prodsucursal_fechareg = utf8_encode($valor2["prodsucursal_fechareg"]);


        $sucursalproducto .= "

            <a class='btn btn-info' style=''><i class='fas fa-store-alt'></i> $sucursal_nombre</a>

        ";

      }

    if($_COOKIE[iniuser] == ""){

         $reservar = "

            <a href='".$baseurl."registrar?r=1' class='btn btn-primary' style='background-color: #5777ba;'><i class='far fa-calendar-alt'></i> Reservar</a>
        ";

    }else{

          $reservar = "

            <a href='$UrlFiles"."admin/reservar' class='btn btn-primary' style='background-color: #5777ba;'><i class='far fa-calendar-alt'></i> Reservar</a>
        ";
    }



 


    $sucursalservicio .= "

        <div class='row content'>
          <div class='col-md-4' data-aos='fade-right'>
            <img src='$urlservicioimg' class='img-fluid' alt=''>
          </div>
          <div class='col-md-8 pt-4' data-aos='fade-up'>
            <h3><a href='#'>$prod_nombre</a></h3>    
              $sucursalproducto
            <p class='font-italic'>
            <br>
              $prod_descrip
            </p>
            <p><b><i class='far fa-clock'></i> $l_intervalo_id</b></p>
            <p style='font-size: 20px'><b>Precio: $prod_venta $moneda_siglas </b></p>
            $reservar
          </div>
        </div>



    ";

}

if ($sucursalservicio==""){
  $sucursalservicio ="
  <div class='row content'>
      <div class='col-lg-12' style= 'padding: 10px' data-aos='fade-right'>
        <div class='alert alert-danger' style='text-align: center; font-weight: normal;'>
              <a style='color: #000; font-size: 14px; text-decoration: none;'>
                  No existen servicios creados
              </a><br>
          </div>
      </div>
  </div>
  ";
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

  <title><?php echo $compania_nombre;?> - Soluciones Detalles</title>
  <meta content="Productos y Servicios" name="title">
  <meta content="Productos y Servicios" name="description">
  <meta content="Productos y Servicios" name="keywords">

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

        <div class="section-title" style="padding-bottom: 0px;">
          <h1>Servicios</h1>
        </div>
        <center> <?php echo $serviciodetallecat; ?></center>
        <hr style="border: 1px solid  #002755;"  id="">


        <?php echo $sucursalservicio; ?>

      </div>
    </section><!-- End Details Section -->


  </main><!-- End #main -->



  <!-- ======= Footer ======= -->
  <?php include_once "footer.php" ?>
  <!-- End Footer -->


</body>

</html>