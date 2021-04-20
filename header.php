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

// Categorias

$header_arrresultado = $conexion->doSelect("

    lista.lista_id, lista.lista_nombre, lista.lista_url, 

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
    listasubcateg.lista_url as listarel_url, 

    listasubcateg.lista_id as lista_idsubcateg, listasubcateg.lista_nombre as listarel_nombre
      ",
    "
    lista 

      inner join usuario cuentasistema on cuentasistema.usuario_id = lista.cuenta_id
        inner join compania companiasistema on companiasistema.compania_id = lista.compania_id              

      inner join listacuenta on listacuenta.lista_id = lista.lista_id
      $wherelistacuenta
            
      inner join usuario cuenta on cuenta.usuario_id = listacuenta.cuenta_id
        inner join compania on compania.compania_id = listacuenta.compania_id 

        left join lista listasubcateg on listasubcateg.lista_idrel = lista.lista_id           
        and listasubcateg.lista_eliminado = '0'


        $wherecuenta
        $wherecompania

    ",
    "lista.lista_eliminado = '0' and lista.cuenta_id = '$SistemaCuentaId' and lista.compania_id = '$SistemaCompaniaId' and lista.tipolista_id = '39'  and ((lista.lista_ppal = '1' $wherelistaactivo) or (lista.lista_ppal = '0' ))  ", null, "lista.lista_id asc");

  $header_cont = 0;
  
  foreach($header_arrresultado as $header_i=>$header_valor){

    $header_lista_idcateg = utf8_encode($header_valor["lista_id"]);  // categoria

    if ($header_lista_idcateg!=$header_lista_idcategold && $header_cont>0){

      $ulsubcateg = "";
      $urlcateg = "$baseurl"."servicio/$header_lista_url";

      if($header_subcateg!=""){
        $ulsubcateg = "
          <ul class='submenu dropdown-menu'>
            $header_subcateg
          </ul>
        ";
        $urlcateg  = "#";
      }

      
      $serviciodetallecat .= "

        <a href='$urlcateg' class='btn btn-success' style='margin-top: 5px;'><i class='fas fa-briefcase'></i></i> $header_lista_nombre</a>

      ";


      $serviciosfooter .= "
        <ul>
          <li><i class='bx bx-chevron-right'></i> <a href='$urlcateg'>$header_lista_nombre</a></li>
        </ul>
      ";



      $header_divcategorias .= "
              <li>
                <a class='dropdown-item' href='$urlcateg'>
                  $header_lista_nombre 
                </a>
                $ulsubcateg
              </li>    
                ";
        $header_subcateg = "";
    }

   
    $header_cont = $header_cont + 1;
    

    $header_listarel_nombre = utf8_encode($header_valor["listarel_nombre"]); 
    $header_listarel_url = utf8_encode($header_valor["listarel_url"]);  

    $header_cuenta_idsistema = utf8_encode($header_valor["cuenta_idsistema"]);  
    $header_compania_idsistema = utf8_encode($header_valor["compania_idsistema"]);      

    $header_listacuenta_id = utf8_encode($header_valor["listacuenta_id"]);  
    $header_listacuenta_nombre = utf8_encode($header_valor["listacuenta_nombre"]);  
    $header_listacuenta_activo = utf8_encode($header_valor["listacuenta_activo"]);  
    $header_listacuenta_eliminado = utf8_encode($header_valor["listacuenta_eliminado"]);  
    $header_listacuenta_orden = utf8_encode($header_valor["listacuenta_orden"]); 
    $header_listacuenta_img = utf8_encode($header_valor["listacuenta_img"]); 

    if ($header_listacuenta_eliminado=="1"){
      continue;
    }

    $header_t_cuenta_id = utf8_encode($header_valor["cuenta_id"]);
    $header_t_compania_id = utf8_encode($header_valor["compania_id"]);    
    
    $header_lista_id = utf8_encode($header_valor["lista_id"]);
    $header_lista_nombre = utf8_encode($header_valor["lista_nombre"]);
    $header_lista_url = utf8_encode($header_valor["lista_url"]);

    
    $header_lista_img = utf8_encode($header_valor["lista_img"]);  
    $header_lista_orden = utf8_encode($header_valor["lista_orden"]);  
    $header_lista_activo = utf8_encode($header_valor["lista_activo"]);      
    $header_lista_ppal = utf8_encode($header_valor["lista_ppal"]);      

    $header_cuenta_nombre = utf8_encode($header_valor["cuenta_nombre"]);
    $header_cuenta_apellido = utf8_encode($header_valor["cuenta_apellido"]);
    $header_cuenta_codigo = utf8_encode($header_valor["cuenta_codigo"]);
    $header_cuenta = $header_cuenta_nombre." ".$header_cuenta_apellido." ";
    $header_compania_nombre = utf8_encode($header_valor["compania_nombre"]);


    $header_cuentasistema_nombre = utf8_encode($header_valor["cuentasistema_nombre"]);
    $header_cuentasistema_apellido = utf8_encode($header_valor["cuentasistema_apellido"]);
    $header_cuentasistema_codigo = utf8_encode($header_valor["cuentasistema_codigo"]);
    $header_cuentasistema = $header_cuentasistema_nombre." ".$header_cuentasistema_apellido." ";
    $header_companiasistema_nombre = utf8_encode($header_valor["companiasistema_nombre"]);

    $header_lista_activooriginal = $header_lista_activo;


    if ($header_listacuenta_id!=""){
      $header_lista_nombre = $header_listacuenta_nombre;
      $header_lista_orden = $header_listacuenta_orden;
      $header_lista_img = $header_listacuenta_img;
      $header_lista_activo = $header_listacuenta_activo;
    }

    if ($header_listarel_nombre!=""){
      $header_subcateg .= "
                <li><a class='dropdown-item' href='$baseurl"."solucion/$header_listarel_url'> $header_listarel_nombre</a></li>
      ";
    }
  

      /*
    $header_subcategorias .= "
          <li>
            <a class='dropdown-item' href='#'> Bienestar </a>
            <ul class'='submenu dropdown-menu'>
              <li><a class='dropdown-item' href=''> Third level 1</a></li>
              <li><a class='dropdown-item' href=''> Third level 2</a></li>
              <li><a class='dropdown-item' href=''> Second level  4</a></li>
              <li><a class='dropdown-item' href=''> Second level  5</a></li>
           </ul>
          </li>
        ";

        */

    $header_lista_idcategold = $header_lista_idcateg;   

  }

$ulsubcateg = "";
$urlcateg = "$baseurl"."servicio/$header_lista_url";

if($header_subcateg!=""){
  $ulsubcateg = "
    <ul class='submenu dropdown-menu'>
      $header_subcateg
    </ul>
  ";
  $urlcateg  = "#";
}



$serviciodetallecat .= "

    <a href='$urlcateg' class='btn btn-success' style='margin-top: 5px;'><i class='fas fa-briefcase'></i> $header_lista_nombre</a>

";

$serviciosfooter .= "
  <ul>
    <li><i class='bx bx-chevron-right'></i> <a href='$urlcateg'>$header_lista_nombre</a></li>
  </ul>

";


$header_divcategorias .= "
      <li>
        <a class='dropdown-item' href='$urlcateg'>
          $header_lista_nombre 
        </a>
         $ulsubcateg
      </li>    
        ";



$arrresultado = $conexion->doSelect("

    seccion.seccion_id, seccion.seccion_nombre, seccion.seccion_descrip, seccion.seccion_img, seccion.cuenta_id, seccion.compania_id, seccion.seccion_url,
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
    "seccion_activo = '1' and listaseccion.lista_cod = '1' and seccion.cuenta_id = '$SistemaCuentaId' and seccion.compania_id = '$SistemaCompaniaId'", null, "seccion_orden asc ");
    foreach($arrresultado as $i=>$valor){

    
    $header_seccion_id = utf8_encode($valor["seccion_id"]);
    $header_seccion_nombre = utf8_encode($valor["seccion_nombre"]);
    $header_seccion_descrip = utf8_encode($valor["seccion_descrip"]);
    $header_lista_cod = utf8_encode($valor["lista_cod"]);
    $header_seccion_url = utf8_encode($valor["seccion_url"]);

    if($header_lista_cod == "0"){
        $header_lista_cod = $header_seccion_url;
    }

    $header_titulopagina = "$header_seccion_nombre - $compania_nombre ";
    $header_descripcionpagina = "$header_seccion_descrip - $compania_nombre ";


    if($header_seccion_nombre == "Soluciones" || $header_seccion_nombre == "Servicios"){

      $menuheader .="
          <li class='nav-item dropdown'>
            <a class='nav-link dropdown-toggle' href='#' data-toggle='dropdown'>  $header_seccion_nombre </a>
              <ul class='dropdown-menu'>
                  $header_divcategorias
              </ul>
          </li> 
          ";

    }elseif ($header_seccion_nombre == "Funciones") {
      
       $menuheader .="
          <li class='nav-item '>
            <a class='nav-link dropdown-toggle' href='#' data-toggle='dropdown'>  Funciones </a>
              <ul class='dropdown-menu'>
                <li><a class='dropdown-item' href='funciones'> Calendario de Reservas </a>
                <li><a class='dropdown-item' href='funciones'> Venta de Productos </a>
                <li><a class='dropdown-item' href='funciones'> Recordatorio de Reservas </a>
                <li><a class='dropdown-item' href='funciones'> Aplicación Propia de Reservas </a>
                <li><a class='dropdown-item' href='funciones'> Envio de Promociones </a>
                <li><a class='dropdown-item' href='funciones'> Registro de Clientes </a>
                </li>
            </ul>
          </li>
       ";

    }elseif($header_seccion_nombre == "Registrar"){

      if($_COOKIE[iniuser] != ""){
          $menuheader .="
            <li class='get-started'><a href='$UrlFiles"."admin/reservar'>Mi cuenta</a></li>
            ";

      }else{

          $menuheader .="
             <li class='get-started'><a href='$baseurl$header_lista_cod'>$header_seccion_nombre</a></li>
            ";
      }

    }else if($header_seccion_nombre == "Ingresar"){

      if($_COOKIE[iniuser] == ""){

        $menuheader .="
             <li class='$baseurl$header_lista_cod'><a href='$baseurl$header_lista_cod'>$header_seccion_nombre</a></li>
            ";
        }

    }


    else{

      $menuheader .="

              <li><a href='$baseurl$header_lista_cod'>$header_seccion_nombre</a></li>
          ";
      }



  }











?>
  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top  header-transparent ">
    <div class="container d-flex align-items-center">

      <div class="logo mr-auto">
         <a href="<?php echo $baseurl;?>"><img src="<?php echo $urlcompaniaimg; ?>" alt="" class="img-fluid"></a>
      </div>

      <nav class="nav-menu d-none d-lg-block">
        <ul>
          <?php echo $menuheader; ?>

        </ul>
      </nav>
      <!-- .nav-menu -->

    </div>
  </header>
  <!-- End Header -->

