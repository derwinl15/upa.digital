<?php

ini_set ("display_errors","0");
include_once 'lib/config.php';
include_once 'lib/funciones.php';

$SistemaCuentaId = SistemaCuentaId;
$SistemaCompaniaId = SistemaCompaniaId;
$UrlFiles = UrlFiles;
$UrlIcono = UrlIcono;
$UrlStyle = UrlStyle;

(isset($_GET['url'])) ? $getsubcategurl=$_GET['url'] :$getsubcategurl='';

$conexion = new ConexionBd();

// Subcategorias

$arrresultado = $conexion->doSelect("

    lista.lista_id, lista.lista_nombre, lista.lista_url, lista.lista_descrip,

    lista.lista_img, lista.lista_orden, lista.lista_activo, lista.lista_ppal,     
    lista.cuenta_id as cuenta_idsistema, lista.compania_id as compania_idsistema,
        
    cuenta.usuario_codigo as cuenta_codigo, cuenta.usuario_nombre as cuenta_nombre,
      cuenta.usuario_apellido as cuenta_apellido, compania.compania_nombre as compania_nombre,
      cuenta.usuario_codigo as cuentasistema_codigo, cuenta.usuario_nombre as cuentasistema_nombre,
      cuenta.usuario_apellido as cuentasistema_apellido, companiasistema.compania_nombre as companiasistema_nombre,

      listacuenta.cuenta_id, listacuenta.compania_id,
      listacuenta.listacuenta_id, listacuenta.listacuenta_activo, listacuenta.listacuenta_eliminado, 
      listacuenta.listacuenta_img, listacuenta.listacuenta_orden,
    listacuenta.listacuenta_nombre,
    lista.tipolista_id,

    listarel.lista_id as lista_idcateg, listarel.lista_nombre as listarel_nombre
      ",
    "
    lista 

      inner join usuario cuentasistema on cuentasistema.usuario_id = lista.cuenta_id
        inner join compania companiasistema on companiasistema.compania_id = lista.compania_id              

      inner join listacuenta on listacuenta.lista_id = lista.lista_id
      $wherelistacuenta
            
      inner join usuario cuenta on cuenta.usuario_id = listacuenta.cuenta_id
        inner join compania on compania.compania_id = listacuenta.compania_id 

        inner join lista listarel on listarel.lista_id = lista.lista_idrel            
        and listarel.lista_eliminado = '0'


        $wherecuenta
        $wherecompania

    ",
    "lista.lista_eliminado = '0'  and lista.cuenta_id = '$SistemaCuentaId' and lista.compania_id = '$SistemaCompaniaId' and lista.tipolista_id = '40' and lista.lista_url = '$getsubcategurl' and ((lista.lista_ppal = '1' $wherelistaactivo) or (lista.lista_ppal = '0' ))  ", null, "listarel.lista_id asc");

  $cont = 0;
  
  foreach($arrresultado as $i=>$valor){

    $lista_idcateg = utf8_encode($valor["lista_idcateg"]);  // categoria

   
    $cont = $cont + 1;
    

    $listarel_nombre = utf8_encode($valor["listarel_nombre"]);  

    $cuenta_idsistema = utf8_encode($valor["cuenta_idsistema"]);  
    $compania_idsistema = utf8_encode($valor["compania_idsistema"]);      

    $listacuenta_id = utf8_encode($valor["listacuenta_id"]);  
    $listacuenta_nombre = utf8_encode($valor["listacuenta_nombre"]);  
    $listacuenta_activo = utf8_encode($valor["listacuenta_activo"]);  
    $listacuenta_eliminado = utf8_encode($valor["listacuenta_eliminado"]);  
    $listacuenta_orden = utf8_encode($valor["listacuenta_orden"]); 
    $listacuenta_img = utf8_encode($valor["listacuenta_img"]); 

    if ($listacuenta_eliminado=="1"){
      continue;
    }

    $t_cuenta_id = utf8_encode($valor["cuenta_id"]);
    $t_compania_id = utf8_encode($valor["compania_id"]);    
    
    $lista_id = utf8_encode($valor["lista_id"]);
    $lista_nombre = utf8_encode($valor["lista_nombre"]);
    $lista_descrip = utf8_encode($valor["lista_descrip"]);

    $lista_url = utf8_encode($valor["lista_url"]);

    
    $lista_img = utf8_encode($valor["lista_img"]);  
    $imgsubcateg = $UrlFiles."/admin/arch/$lista_img";
    $lista_orden = utf8_encode($valor["lista_orden"]);  
    $lista_activo = utf8_encode($valor["lista_activo"]);      
    $lista_ppal = utf8_encode($valor["lista_ppal"]);      

    $cuenta_nombre = utf8_encode($valor["cuenta_nombre"]);
    $cuenta_apellido = utf8_encode($valor["cuenta_apellido"]);
    $cuenta_codigo = utf8_encode($valor["cuenta_codigo"]);
    $cuenta = $cuenta_nombre." ".$cuenta_apellido." ";
    $compania_nombre = utf8_encode($valor["compania_nombre"]);


    $cuentasistema_nombre = utf8_encode($valor["cuentasistema_nombre"]);
    $cuentasistema_apellido = utf8_encode($valor["cuentasistema_apellido"]);
    $cuentasistema_codigo = utf8_encode($valor["cuentasistema_codigo"]);
    $cuentasistema = $cuentasistema_nombre." ".$cuentasistema_apellido." ";
    $companiasistema_nombre = utf8_encode($valor["companiasistema_nombre"]);

    $lista_activooriginal = $lista_activo;


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


        <!-- ======= Details Section ======= -->
    <section id="hero" class="details">
      <div class="container">
        <div class="row content">
          <div class="col-md-4" data-aos="fade-right">
            <img src="<?php echo $imgsubcateg; ?>" class="img-fluid" alt="">

          </div>
          <div class="col-md-8 pt-4" data-aos="fade-up">
            <h3><a href='<?php echo $baseurl; ?>soluciondetalle'><?php echo $lista_nombre; ?></a></h3>
            <p class="font-italic">
              <?php echo $lista_descrip; ?>
            </p>

              <a href='<?php echo $baseurl; ?>demo' class='btn btn-primary' style="background-color: #5777ba;">Si deseas obtener un demo presiona aquí</a>
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