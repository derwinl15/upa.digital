<?php
include_once 'lib/mysqlclass.php';
include_once 'lib/config.php';

$SistemaCuentaId = SistemaCuentaId;
$SistemaCompaniaId = SistemaCompaniaId;

function crearSeccion($seccion_nombre=null, $cuenta=null, $compania=null, $orden=null, $encabezado=null, $piedepagina=null, $url=null, $tipomenuweb=null, $img=null, $seccion_descrip=null){

  session_start();

  if($img==""){$img="0.jpg";}

  $conexion = new ConexionBd();  

  $fechaactual = formatoFechaHoraBd(); 

  $resultado = $conexion->doInsert("
      seccion
        (seccion_nombre, seccion_descrip, seccion_img, cuenta_id, compania_id, 
        seccion_activo, seccion_eliminado, seccion_fechareg, seccion_orden, usuario_idreg, seccion_url, l_tiposeccion_id, seccion_footer, l_tipomenuweb_id) 

      ",
      "'$seccion_nombre', '$seccion_descrip', '$img', '$cuenta', '$compania', 
       '1', '0', '$fechaactual','$orden', '$_SESSION[iniuser]','$url', '0','0','$tipomenuweb'");

    $arrresultado2 = $conexion->doSelect("max(seccion_id) as seccion_id","seccion");
    if (count($arrresultado2)>0){
      foreach($arrresultado2 as $i=>$valor){
        $seccion_id = ($valor["seccion_id"]);
      }

      if ($encabezado!=""){
        $resultado = $conexion->doInsert("
          seccionubicacion
            (seccion_id, l_ubicseccion_id, 
            seccionubicacion_activo, seccionubicacion_eliminado, seccionubicacion_fechareg)
          ",
        "'$seccion_id', '$encabezado', 
        '1','0','$fechaactual'");       
      }

      if ($piedepagina!=""){
        $resultado = $conexion->doInsert("
          seccionubicacion
            (seccion_id, l_ubicseccion_id, 
            seccionubicacion_activo, seccionubicacion_eliminado, seccionubicacion_fechareg)
          ",
        "'$seccion_id', '$piedepagina', 
        '1','0','$fechaactual'");       
      }

    }
      

    
}

function GuardarProcesoLista($lista_nombre=null, $lista_nombredos=null, $lista_descrip=null, $lista_img=null, $lista_ppal=null, $lista_orden=null, $tipolista_id=null, $cuenta_id=null, $compania_id=null, $lista_icono=null, $lista_color=null, $lista_idrel=null, $lista_url=null, $fechaactual=null, $lista_mostrarppal=null, $lista_idreldos=null, $lista_cod=null){  


  $resultadoArray = array();

  // GuardoLista en tabla lista
  $resultadolista_id = GuardarLista($lista_nombre, $lista_nombredos, $lista_descrip, $lista_img, $lista_ppal, $lista_orden, $tipolista_id, $cuenta_id, $compania_id, $fechaactual, $lista_icono, $lista_color, $lista_idrel, $lista_url, $lista_mostrarppal, $lista_idreldos, $lista_cod);

  //Guaro en ListaCuenta
  $resultadolistacuenta_id = GuardarListaCuenta($resultadolista_id, $cuenta_id, $compania_id, $lista_nombre, $lista_nombredos, $lista_descrip, $lista_img, $fechaactual, $lista_orden);

  $resultadoArray = array(     
  'lista_id' => $resultadolista_id,
  'listacuenta_id'  => $resultadolistacuenta_id
  );

  return $resultadoArray;

}



function GuardarLista($lista_nombre=null, $lista_nombredos=null, $lista_descrip=null, $lista_img=null, $lista_ppal=null, $lista_orden=null, $tipolista_id=null, $cuenta_id=null, $compania_id=null, $lista_fechareg=null, $lista_icono=null, $lista_color=null, $lista_idrel=null, $lista_url=null, $lista_mostrarppal=null, $lista_idreldos=null, $lista_cod=null){

  if ($lista_ppal==""){$lista_ppal=0;}
  if ($lista_orden==""){$lista_orden=0;}
  if ($lista_idrel==""){$lista_idrel=0;}
  if ($lista_idreldos==""){$lista_idreldos=0;}
  if ($lista_mostrarppal==""){$lista_mostrarppal=0;}

  $conexion = new ConexionBd();  

  $fechaactual = formatoFechaHoraBd(); 

  session_start();
  $_COOKIE[perfil] = $_COOKIE[perfil];
  $_COOKIE[idcuenta] = $_COOKIE[idcuenta];
  $_COOKIE[idcompania] = $_COOKIE[idcompania];
/*
  echo "cuenta_id:".$cuenta_id;
  echo "<br>";
  echo "compania_id:".$compania_id;
  exit();
*/  

  $iniuser = $_COOKIE[iniuser];

  if ($iniuser==""){
    $iniuser = $_SESSION[iniuser];
    if ($iniuser==""){
      $iniuser = 0;
    }
  }

  $resultado = $conexion->doInsert("
      lista
        (lista_nombre, lista_nombredos, lista_descrip, lista_img, lista_ppal, lista_cod,
        lista_orden, tipolista_id, cuenta_id, compania_id, 
        lista_fechareg, lista_activo, lista_eliminado, usuario_idreg, 
        lista_icono, lista_color, lista_idrel, lista_url, lista_mostrarppal, lista_idreldos)
      ",
      "'$lista_nombre', '$lista_nombredos', '$lista_descrip','$lista_img','$lista_ppal', '$lista_cod',
      '$lista_orden','$tipolista_id', '$cuenta_id','$compania_id',
      '$fechaactual', '1','0','$iniuser',
      '$lista_icono', '$lista_color','$lista_idrel','$lista_url','$lista_mostrarppal', '$lista_idreldos'");

  $arrresultado2 = $conexion->doSelect("max(lista_id) as lista_id","lista");
  if (count($arrresultado2)>0){
    foreach($arrresultado2 as $i=>$valor){
      $lista_id = ($valor["lista_id"]);
    }
  }

  return $lista_id;
}



function GuardarListaCuenta($lista_id=null, $cuenta_id=null, $compania_id=null, $listacuenta_nombre=null, $listacuenta_nombredos=null,  $listacuenta_descrip=null, $listacuenta_img=null, $fechaactual=null, $listacuenta_orden=null){

  if ($listacuenta_orden==""){$listacuenta_orden=0;}
  if ($lista_orden==""){$lista_orden=0;}
  if ($lista_idrel==""){$lista_idrel=0;}

  $conexion = new ConexionBd();  

  session_start();
  $_COOKIE[perfil] = $_COOKIE[perfil];
  $_COOKIE[idcuenta] = $_COOKIE[idcuenta];
  $_COOKIE[idcompania] = $_COOKIE[idcompania];

  $iniuser = $_COOKIE[iniuser];

  if ($iniuser==""){
    $iniuser = $_SESSION[iniuser];
    if ($iniuser==""){
      $iniuser = 0;
    }
  }

  $resultado = $conexion->doInsert("
    listacuenta
      (lista_id, cuenta_id, compania_id, listacuenta_nombre, listacuenta_nombredos, listacuenta_descrip, listacuenta_img,
       listacuenta_fechareg, listacuenta_activo, listacuenta_eliminado, usuario_idreg, listacuenta_orden) 
    ",
    "'$lista_id', '$cuenta_id', '$compania_id','$listacuenta_nombre', '$listacuenta_nombredos','$listacuenta_descrip', '$listacuenta_img',
    '$fechaactual', '1','0','$iniuser','$listacuenta_orden'");

  $arrresultado2 = $conexion->doSelect("max(listacuenta_id) as listacuenta_id","listacuenta");
  if (count($arrresultado2)>0){
    foreach($arrresultado2 as $i=>$valor){
      $listacuenta_id = ($valor["listacuenta_id"]);
    }
  }

  return $listacuenta_id;
}


function AsignarRegistrosCreacionCompania($cuenta=null, $compania_id=null, $categoria_id=null){

    $conexion = new ConexionBd();  

    session_start();

    if ($categoria_id==""){$categoria_id=0;}    

    session_start();
    if ($_SESSION[perfil]!="1"){
      $cuenta = $_SESSION[idcuenta];
    }    

    $arrresultado2 = $conexion->doSelect("cuenta_id","compania", "compania_id = '$compania_id'");
      if (count($arrresultado2)>0){
      foreach($arrresultado2 as $i=>$valor){
        $cuenta = ($valor["cuenta_id"]);
      }
    }

    $fechaactual = formatoFechaHoraBd();
    
      // Creo Cliente por Defecto
      $resultado = $conexion->doInsert("
      usuario
        (usuario_nombre, usuario_fechareg, usuario_activo, usuario_eliminado, usuario_img, perfil_id, cuenta_id, compania_id, usuario_defecto, usuario_email) 
      ",
      "'Consumidor Final', '$fechaactual', '1', '0', '1.png', '4','$cuenta','$compania_id','1','cliente@gmail.com'");

    // Creo Sucursal por Defecto
    $resultado = $conexion->doInsert("
      sucursal
        (sucursal_nombre, sucursal_razonsocial, sucursal_img, sucursal_activo, sucursal_eliminado,
        sucursal_fechareg, cuenta_id, usuario_idreg, compania_id, sucursal_ppal) 
      ",
      "'Principal', '','0.jpg','1', '0',
      '$fechaactual', '$cuenta','$_SESSION[iniuser]','$compania_id', '1'");

    $arrresultado2 = $conexion->doSelect("max(sucursal_id) as sucursal_id","sucursal");
      if (count($arrresultado2)>0){
      foreach($arrresultado2 as $i=>$valor){
        $sucursal_id = ($valor["sucursal_id"]);
      }
    }

    
    $obtenerCodigoLista = 1; 
    $obtenerTipoLista = 46;
    $tipobanner = ObtenerIdLista($obtenerCodigoLista, $obtenerTipoLista);

    // Banner por Defecto
    $resultado = $conexion->doInsert("
      banner
        (banner_nombre, banner_textouno, banner_textodos, banner_textotres, banner_botonnombre, 
        banner_botonurl, banner_img, cuenta_id, compania_id, banner_activo, banner_eliminado, 
        banner_fechareg, banner_orden, usuario_idreg, l_tipobanner_id)

      ",
      "'', '', '', '', '', 
      '', '4.jpg', '$cuenta', '$compania_id', '1', '0',
       '$fechaactual','1', '$_SESSION[iniuser]','$tipobanner'");

    $arrresultado2 = $conexion->doSelect("max(banner_id) as banner_id","banner");
    if (count($arrresultado2)>0){
      foreach($arrresultado2 as $i=>$valor){
        $banner_id = ($valor["banner_id"]);
      }
    }


    $obtenerCodigoLista = 1; //Unidad
    $obtenerTipoLista = 3;
    $tipounidad = ObtenerIdLista($obtenerCodigoLista, $obtenerTipoLista);

    $obtenerCodigoLista = 1; //Dolares
    $obtenerTipoLista = 16;
    $moneda = ObtenerIdLista($obtenerCodigoLista, $obtenerTipoLista);

    $obtenerCodigoLista = 1; //Producto Unico
    $obtenerTipoLista = 4;
    $tipoproducto = ObtenerIdLista($obtenerCodigoLista, $obtenerTipoLista);

    $obtenerCodigoLista = 1; //Producto
    $obtenerTipoLista = 7;
    $claseproducto = ObtenerIdLista($obtenerCodigoLista, $obtenerTipoLista);

    $prod_nombre = "Producto General";

    $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
    $repl = array('a', 'e', 'i', 'o', 'u', 'n');
    $prod_nombreurl = str_replace($find, $repl, $prod_nombre);
    $urlamigableprod = urls_amigables($prod_nombreurl);

    $urlamigableprod = substr($urlamigableprod, 0,120);


    $obtenerCodigoLista = 5; 
    $obtenerTipoLista = 161;
    $intervaloproducto = ObtenerIdLista($obtenerCodigoLista, $obtenerTipoLista);

    // Producto General
    $resultado = $conexion->doInsert("
    producto
      (prod_nombre, prod_descrip, prod_img, prod_stockgeneral, prod_codigo,
      l_tipounidad_id, prod_valorunidad, prod_costo, prod_venta, l_moneda_id, l_tipoproducto_id, 
      l_claseproducto_id, prod_idpadre, cuenta_id, compania_id, prod_activo, prod_eliminado, prod_fechareg, usuario_idreg, 
      prod_ganancia, prod_porcganancia, prod_url, l_categ_id, l_intervalo_id) 

    ",
    "'$prod_nombre', '$prod_nombre', '3.jpg', '10', '0', 
    '$tipounidad', '0', '50', '100', '$moneda', '$tipoproducto',
    '$claseproducto', '0', '$cuenta', '$compania_id', '1', '0', '$fechaactual', '$_SESSION[iniuser]',
    '50','100','$urlamigableprod','$categoria_id', '$intervaloproducto'");

    $arrresultado2 = $conexion->doSelect("max(prod_id) as prod_id","producto");
      if (count($arrresultado2)>0){
      foreach($arrresultado2 as $i=>$valor){
        $prod_id = ($valor["prod_id"]);
      }
    }


    $resultado = $conexion->doInsert("
    productostock
      (prod_id, sucursal_id, prodstock_cantidad, prodstock_fechareg, prodstock_activo, prodstock_eliminado, cuenta_id, compania_id)
    ",
    "'$prod_id', '$sucursal_id', '10', '$fechaactual', '1', '0','$cuenta','$compania_id'");



    $obtenerCodigoLista = 0; 
    $obtenerTipoLista = 120;
    $tipopersonalizado = ObtenerIdLista($obtenerCodigoLista, $obtenerTipoLista);


    $obtenerCodigoLista = "nosotros"; 
    $obtenerTipoLista = 120;
    $tipomenunosotros = ObtenerIdLista($obtenerCodigoLista, $obtenerTipoLista);


    $obtenerCodigoLista = "blog"; 
    $obtenerTipoLista = 120;
    $tipomenublog = ObtenerIdLista($obtenerCodigoLista, $obtenerTipoLista);


    $obtenerCodigoLista = "contacto"; 
    $obtenerTipoLista = 120;
    $tipomenucontacto= ObtenerIdLista($obtenerCodigoLista, $obtenerTipoLista);


    $obtenerCodigoLista = "terminos"; 
    $obtenerTipoLista = 120;
    $tipomenuterminos= ObtenerIdLista($obtenerCodigoLista, $obtenerTipoLista);


    $obtenerCodigoLista = "politicas"; 
    $obtenerTipoLista = 120;
    $tipomenupoliticas= ObtenerIdLista($obtenerCodigoLista, $obtenerTipoLista);



    $obtenerCodigoLista = 1; 
    $obtenerTipoLista = 112;
    $menuencabezado = ObtenerIdLista($obtenerCodigoLista, $obtenerTipoLista);

    $obtenerCodigoLista = 2; 
    $obtenerTipoLista = 112;
    $menupiedepagina = ObtenerIdLista($obtenerCodigoLista, $obtenerTipoLista);


    // Seccion Ingresar 
    
    $seccion_nombre="Ingresar"; 
    $seccion_descrip=""; 
    $encabezado = $menuencabezado;
    $piedepagina = $menupiedepagina;
    $url = "ingresar";
    $tipomenuweb = $tipopersonalizado;
    $orden= 7;
    $img = "";
    $seccion_descrip = "";
    crearSeccion($seccion_nombre, $cuenta, $compania_id, $orden, $encabezado, $piedepagina, $url, $tipomenuweb, $img, $seccion_descrip);


    $seccion_nombre="Registrar";
    $encabezado = $menuencabezado;
    $piedepagina = $menupiedepagina;
    $url = "registrar";
    $tipomenuweb = $tipopersonalizado;
    $orden= 8;
    $img = "";
    $seccion_descrip = "";
    crearSeccion($seccion_nombre, $cuenta, $compania_id,$orden, $encabezado, $piedepagina, $url, $tipomenuweb, $img, $seccion_descrip);


    $seccion_nombre="Servicios";
    $encabezado = $menuencabezado;
    $piedepagina = $menupiedepagina;
    $url = "servicios";
    $tipomenuweb = $tipopersonalizado;
    $orden= 1;
    $img = "";
    $seccion_descrip = "";
    crearSeccion($seccion_nombre, $cuenta, $compania_id,$orden, $encabezado, $piedepagina, $url, $tipomenuweb, $img, $seccion_descrip);


    $seccion_nombre="Quienes Somos";
    $encabezado = $menuencabezado;
    $piedepagina = $menupiedepagina;
    $url = "nosotros";
    $tipomenuweb = $tipomenunosotros;
    $orden= 4;
    $img = "2.png";        
    $seccion_descrip = "";
    crearSeccion($seccion_nombre, $cuenta, $compania_id, $orden, $encabezado, $piedepagina, $url, $tipomenuweb, $img, $seccion_descrip);


    $seccion_nombre="Blog";
    $encabezado = $menuencabezado;
    $piedepagina = $menupiedepagina;
    $url = "blog";
    $tipomenuweb = $tipomenublog;
    $orden= 5;
    $img = "";
    $seccion_descrip = "";
    crearSeccion($seccion_nombre, $cuenta, $compania_id, $orden, $encabezado, $piedepagina, $url, $tipomenuweb, $img, $seccion_descrip);


    $seccion_nombre="Contacto";
    $encabezado = $menuencabezado;
    $piedepagina = $menupiedepagina;
    $url = "contacto";
    $tipomenuweb = $tipomenucontacto;
    $orden= 6;
    $img = "";
    $seccion_descrip = "";
    crearSeccion($seccion_nombre, $cuenta, $compania_id, $orden, $encabezado, $piedepagina, $url, $tipomenuweb, $img, $seccion_descrip);


    $seccion_nombre="Terminos y Condiciones";
    $encabezado = "";
    $piedepagina = "";
    $url = "terminos";
    $tipomenuweb = $tipomenuterminos;
    $orden= 0;
    $img = "";
    $seccion_descrip = "";
    crearSeccion($seccion_nombre, $cuenta, $compania_id, $orden, $encabezado, $piedepagina, $url, $tipomenuweb, $img, $seccion_descrip);


    $seccion_nombre="Politicas de Privacidad";
    $encabezado = "";
    $piedepagina = "";
    $url = "politicas";
    $tipomenuweb = $tipomenupoliticas;
    $orden= 0;
    $img = "";
    $seccion_descrip = "";
    crearSeccion($seccion_nombre, $cuenta, $compania_id, $orden, $encabezado, $piedepagina, $url, $tipomenuweb, $img, $seccion_descrip);



}

function urls_amigables($url) {

  $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
  $repl = array('a', 'e', 'i', 'o', 'u', 'n');
  $url = str_replace($find, $repl, $url);

  // Tranformamos todo a minusculas

  $url = strtolower($url);

  //Rememplazamos caracteres especiales latinos

  $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');

  $repl = array('a', 'e', 'i', 'o', 'u', 'n');

  $url = str_replace ($find, $repl, $url);

  // Añadimos los guiones

  $find = array(' ', '&', '\r\n', '\n', '+');
  $url = str_replace ($find, '-', $url);

  // Eliminamos y Reemplazamos otros carácteres especiales

  $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');

  $repl = array('', '-', '');

  $url = preg_replace ($find, $repl, $url);

  return $url;
 
}

function ListarBanners(){

  $SistemaCuentaId = SistemaCuentaId;
  $SistemaCompaniaId = SistemaCompaniaId;
  $UrlFiles = UrlFiles;
  
  $conexion = new ConexionBd();

  $arrresultado = $conexion->doSelect("

      banner.banner_id, banner.banner_nombre, banner.banner_textouno, banner.banner_textodos, 
      banner.banner_textotres, banner.banner_botonnombre, banner.banner_botonurl, banner.banner_img, 
      banner.cuenta_id, banner.compania_id, banner.banner_activo, banner.banner_eliminado, 
      banner.banner_orden, banner.usuario_idreg,
      DATE_FORMAT(banner.banner_fechareg,'%d/%m/%Y %H:%i:%s') as banner_fechareg,   
      cuenta.usuario_codigo as cuenta_codigo, cuenta.usuario_nombre as cuenta_nombre,
      cuenta.usuario_apellido as cuenta_apellido, compania_nombre,
      banner.l_tipobanner_id,
      tipobanner.lista_nombre as tipobanner_nombre
      ",
      "banner           
        inner join usuario cuenta on cuenta.usuario_id = banner.cuenta_id
          inner join compania on compania.compania_id = banner.compania_id    
          inner join lista tipobanner on tipobanner.lista_id = banner.l_tipobanner_id    
      ",
      "banner_activo = '1' and banner.cuenta_id = '$SistemaCuentaId' and tipobanner.lista_cod= '1' and banner.compania_id = '$SistemaCompaniaId' ", null, "banner_orden asc");


    $slideractive = "active";
    $count = 0;

    foreach($arrresultado as $i=>$valor){

      $cuenta_nombre = utf8_encode($valor["cuenta_nombre"]);
      $cuenta_apellido = utf8_encode($valor["cuenta_apellido"]);
      $cuenta_codigo = utf8_encode($valor["cuenta_codigo"]);
      $cuenta = $cuenta_nombre." ".$cuenta_apellido." ";
      $compania_nombre = utf8_encode($valor["compania_nombre"]);

      $banner_id = utf8_encode($valor["banner_id"]);
      $banner_nombre = utf8_encode($valor["banner_nombre"]);
      $banner_textouno = utf8_encode($valor["banner_textouno"]);
      $banner_textodos = utf8_encode($valor["banner_textodos"]);
      $banner_textotres = utf8_encode($valor["banner_textotres"]);
      $banner_botonnombre = utf8_encode($valor["banner_botonnombre"]);
      $banner_botonurl = utf8_encode($valor["banner_botonurl"]);
      $banner_img = utf8_encode($valor["banner_img"]);
      $banner_activo = utf8_encode($valor["banner_activo"]);
      $banner_fechareg = utf8_encode($valor["banner_fechareg"]);
      $l_tipobanner_id = utf8_encode($valor["l_tipobanner_id"]);
      $tipobanner_nombre = utf8_encode($valor["tipobanner_nombre"]);  

      $t_cuenta_id = utf8_encode($valor["cuenta_id"]);
      $t_compania_id = utf8_encode($valor["compania_id"]);

      $titulobanner = "";
      $texto1banner = "";
      $texto2banner = "";
      $bottonbanner = "";

      $urlcolocar = "";

      $urlImgBanner = $UrlFiles."/admin/arch/".$banner_img;

      if ($banner_nombre!=""){
          $titulobanner  = "<h1 class='text-white'>$banner_nombre</span></h1>";
      }

      if ($banner_textouno!=""){
          $texto1banner  = "<p class='margin-30px-bottom md-margin-20px-bottom  center-col'>$banner_textouno</p>";
      }

      if ($banner_botonnombre!=""){
          $bottonbanner  = "<a href='$banner_botonurl' class='butn theme'>
                                  <button type='button' class='btn btn-warning btn-lg' style='padding: 10px 60px; border-radius: 70px;'>$banner_botonnombre</button>
                              </a>";
      }


      if($bannerhtml == ""){
        $slideractive = "active";
      }

     $slider .="
            <li data-target='#carouselExampleIndicators' data-slide-to='$count' class='$slideractive'></li>

    ";


      $bannerhtml .="

                <div class='carousel-item $slideractive'>
                  <img src='$urlImgBanner' style='max-height: 350px; width: 100%; padding-bottom: 10px;'>
                  <div class='carousel-caption'>
                    <h5 style='color: #000'>$banner_nombre</h5>
                    <h2 style='color: #000; margin-bottom: 20px;'>$banner_textouno</h2>
                    $bottonbanner
                  </div>
                </div>

              ";
               $slideractive = "";
               $count = $count + 1;
  }



  $bannerslider = "
          <div id='carouselExampleIndicators' class='carousel slide' data-ride='carousel'>
            <ol class='carousel-indicators'>
              $slider
            </ol>
            <div class='carousel-inner' style='padding-bottom:15px'>
              $bannerhtml
            </div>
            <a class='carousel-control-prev' href='#carouselExampleIndicators' role='button' data-slide='prev'>
              <span class='carousel-control-prev-icon' aria-hidden='true'></span>
              <span class='sr-only'>Previous</span>
            </a>
            <a class='carousel-control-next' href='#carouselExampleIndicators' role='button' data-slide='next'>
              <span class='carousel-control-next-icon' aria-hidden='true'></span>
              <span class='sr-only'>Next</span>
            </a>
          </div>
  ";

  return $bannerslider;

}

function ImagenPaginaPrincipal(){



  $SistemaCuentaId = SistemaCuentaId;
  $SistemaCompaniaId = SistemaCompaniaId;
  $UrlFiles = UrlFiles;
  
  $conexion = new ConexionBd();

  $arrresultado = $conexion->doSelect("

      banner.banner_id, banner.banner_nombre, banner.banner_textouno, banner.banner_textodos, 
      banner.banner_textotres, banner.banner_botonnombre, banner.banner_botonurl, banner.banner_img, 
      banner.cuenta_id, banner.compania_id, banner.banner_activo, banner.banner_eliminado, 
      banner.banner_orden, banner.usuario_idreg,
      DATE_FORMAT(banner.banner_fechareg,'%d/%m/%Y %H:%i:%s') as banner_fechareg,   
      cuenta.usuario_codigo as cuenta_codigo, cuenta.usuario_nombre as cuenta_nombre,
      cuenta.usuario_apellido as cuenta_apellido, compania_nombre,
      banner.l_tipobanner_id,
      tipobanner.lista_nombre as tipobanner_nombre
      ",
      "banner           
        inner join usuario cuenta on cuenta.usuario_id = banner.cuenta_id
          inner join compania on compania.compania_id = banner.compania_id    
          inner join lista tipobanner on tipobanner.lista_id = banner.l_tipobanner_id    
      ",
      "banner_activo = '1' and banner.cuenta_id = '$SistemaCuentaId' and tipobanner.lista_cod= '2' and banner.compania_id = '$SistemaCompaniaId' ", null, "banner_orden asc");


    $bannerverificar = "0";

    foreach($arrresultado as $i=>$valor){

      $cuenta_nombre = utf8_encode($valor["cuenta_nombre"]);
      $cuenta_apellido = utf8_encode($valor["cuenta_apellido"]);
      $cuenta_codigo = utf8_encode($valor["cuenta_codigo"]);
      $cuenta = $cuenta_nombre." ".$cuenta_apellido." ";
      $compania_nombre = utf8_encode($valor["compania_nombre"]);

      $banner_id = utf8_encode($valor["banner_id"]);
      $banner_nombre = utf8_encode($valor["banner_nombre"]);
      $banner_textouno = utf8_encode($valor["banner_textouno"]);
      $banner_textodos = utf8_encode($valor["banner_textodos"]);
      $banner_textotres = utf8_encode($valor["banner_textotres"]);
      $banner_botonnombre = utf8_encode($valor["banner_botonnombre"]);
      $banner_botonurl = utf8_encode($valor["banner_botonurl"]);
      $banner_img = utf8_encode($valor["banner_img"]);
      $banner_activo = utf8_encode($valor["banner_activo"]);
      $banner_fechareg = utf8_encode($valor["banner_fechareg"]);
      $l_tipobanner_id = utf8_encode($valor["l_tipobanner_id"]);
      $tipobanner_nombre = utf8_encode($valor["tipobanner_nombre"]);  

      $t_cuenta_id = utf8_encode($valor["cuenta_id"]);
      $t_compania_id = utf8_encode($valor["compania_id"]);

      $titulobanner = "";
      $texto1banner = "";
      $texto2banner = "";

      $urlcolocar = "";

      $urlImgBanner = $UrlFiles."/admin/arch/".$banner_img;

      if ($banner_nombre!=""){
          $titulobanner  = "<h1 class='text-white'>$banner_nombre</span></h1>";
      }

      if ($banner_textouno!=""){
          $texto1banner  = "<p class='margin-30px-bottom md-margin-20px-bottom  center-col'>$banner_textouno</p>";
      }

      if ($banner_botonnombre!=""){
          $bottonbanner  = "<a href='$bottonbanner' class='butn theme'>
                                  <span class='alt-font'>$bottonbanner</span><i class='fas fa-long-arrow-alt-right font-size16 sm-font-size14 margin-10px-left'></i>
                              </a>";
      }

      $bannerlista .="
      <img src='$urlImgBanner' alt='main slider' title='#htmlcaption$banner_id'  style='max-height: 440px'/>
    ";


    if($bannerverificar == "0"){
        $bannerverificar = "active";
    }else{
        $bannerverificar = "";
    }


      $bannerhtml .="
                    <div class='carousel-item $bannerverificar' style='background-image: url($urlImgBanner)'>
                      <div class='carousel-container'>
                        <div class='container'>
                          <h2 class='animate__animated animate__fadeInDown' style='margin-top: 100px'><span>'$banner_nombre'</span></h2>
                          <p class='animate__animated animate__fadeInUp'><span>'$banner_textouno'</span></p>
                          <a href='registrar.php' class='btn-get-started animate__animated animate__fadeInUp scrollto'>'$banner_botonnombre'</a>
                        </div>
                      </div>
                    </div>
                  ";

  }



  $bannerslider = "
    <div class='slider'>
      <div id='mainSlider' class='nivoSlider slider-image'>
        $bannerlista
      </div>      
      $bannerhtml              
    </div>
  ";

  return $urlImgBanner;

}

function ObtenerListaSubCategorias($subcateg_id=null, $categ_id=null){ 

  $SistemaCuentaId = SistemaCuentaId;
  $SistemaCompaniaId = SistemaCompaniaId;

  $arrayValores = array();

  if ($subcateg_id!=""){
    $wheresubcateg = " and lista.lista_id = '$subcateg_id' ";
  }

  if ($categ_id!=""){
    $wherecateg = " and lista.lista_idrel = '$categ_id' ";
  }

  $where = " and listacuenta.cuenta_id = '$SistemaCuentaId' and listacuenta.compania_id = '$SistemaCompaniaId' ";
  $wherecuenta = " and listacuenta.cuenta_id = '$SistemaCuentaId' ";
  $wherecompania = " and listacuenta.compania_id = '$SistemaCompaniaId' ";   
  $wherelistacuenta = " and listacuenta.cuenta_id = '$SistemaCuentaId' and listacuenta.compania_id = '$SistemaCompaniaId'  ";
  $wherelistaactivo = " and lista.lista_activo = '1' ";

  //echo "SistemaCompaniaId: $SistemaCompaniaId";


  $conexion = new ConexionBd();

  $arrresultado = $conexion->doSelect("lista.lista_id, lista.lista_nombre, 
    lista.lista_url,
    lista.lista_ppal, 
  lista.lista_activo, 
  lista.tipolista_id, 
  cuenta.usuario_codigo as cuenta_codigo, cuenta.usuario_nombre as cuenta_nombre,
  cuenta.usuario_apellido as cuenta_apellido, compania_nombre, lista.cuenta_id, lista.compania_id,
  tipolista_labeluno, tipolista_labeldos, tipolista_labeltres, tipolista_labelconfig,
  listarel.lista_nombre as listarel_nombre, 
  listarel.lista_img as listarel_img, 
  listarel.lista_id as listarel_id, 
  listarel.lista_url as listarel_url, 
  lista.lista_img
",
  "lista 

      inner join lista listarel on listarel.lista_id = lista.lista_idrel

      inner join tipolista on tipolista.tipolista_id = lista.tipolista_id
      inner join usuario cuenta on cuenta.usuario_id = lista.cuenta_id
      inner join compania on compania.compania_id = lista.compania_id

  ",
  "lista.lista_activo = '1' and lista.tipolista_id = '40' and lista.compania_id = '$SistemaCompaniaId' and listarel.compania_id = '$SistemaCompaniaId'  $wheresubcateg $wherecateg  ", null, "listarel.lista_orden, lista.lista_orden asc");

  foreach($arrresultado as $i=>$valor){

    $listarel_url = utf8_encode($valor["listarel_url"]);  
    $listarel_nombre = utf8_encode($valor["listarel_nombre"]);  
    $listarel_id = utf8_encode($valor["listarel_id"]);  
    $listarel_img = utf8_encode($valor["listarel_img"]);  

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
    $lista_url = utf8_encode($valor["lista_url"]);
    $lista_img = utf8_encode($valor["lista_img"]);  
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


    if ($listacuenta_id!=""){
      $lista_nombre = $listacuenta_nombre;
      $lista_orden = $listacuenta_orden;
      $lista_url = $listacuenta_url;
      $lista_img = $listacuenta_img;
      $lista_activo = $listacuenta_activo;
    }

    if ($lista_ppal=="1" && $t_cuenta_id==""){ // Es porque no esta personalizado por la empresa
      $cuenta = $cuentasistema;
      $compania_nombre = $companiasistema_nombre;
    }

  
    if ($lista_activo=="0"){
      $activo = "<i onclick='cambiarestatuslista(\"".$lista_id."\",\"".$t_cuenta_id."\",\"".$t_compania_id."\",1)' title='Deshabilitar' class='fa fa-minus btn-deshabilitar'></i>";
    }else{
      $activo = "<i onclick='cambiarestatuslista(\"".$lista_id."\",\"".$t_cuenta_id."\",\"".$t_compania_id."\",0)' title='Habilitar' class='fa fa-check btn-habilitar'></i>";
    }
    
    $accioneliminar = "<i onclick='eliminarlista(\"".$lista_id."\",\"".$t_cuenta_id."\",\"".$t_compania_id."\",0)' title='Eliminar?' class='fa fa-trash btn-eliminar'></i>";  

    $modificar = "<a href='modificarcategoriaservicio?id=$lista_id&lid=$listacuenta_id'><i title='Ver' class='fa fa-edit btn-modificar'></i></a>";

    $imagen = "<img src='arch/$lista_img' style='height: 80px'";
    

    if (P_Mod!="1"){$modificar = ""; $activo = "";}
    if (P_Eli!="1"){$accioneliminar = ""; $activo = "";}

    $mostrarcolumnacuenta = "<td>$cuenta </td>";
    $mostrarcolumnacompania = "<td>$compania_nombre</td>";

    if ($_SESSION[perfil]=="1"){      
      
    }
    else if ($_SESSION[perfil]=="2"){       
      $mostrarcolumnacuenta = "";
    }
    else {      
      $mostrarcolumnacuenta = "";
      $mostrarcolumnacompania = ""; 
    }    
    

    $textohtml .= "
           <tr style='font-size: 14px'>               
            $mostrarcolumnacuenta
            $mostrarcolumnacompania
            <td style='text-align: center'>$imagen </td>
            <td>$lista_nombre</td>            
            <td style='text-align: center'>$modificar &nbsp $activo &nbsp $accioneliminar</td>
              </tr>
        ";

    $valorUnico = array(     
      'lista_id' => $lista_id,
      'lista_nombre'  => $lista_nombre,
      'lista_url'  => $lista_url,
      'listarel_nombre'  => $listarel_nombre,
      'listarel_url'  => $listarel_url,
      'listarel_id'  => $listarel_id,
      'listarel_img'  => $listarel_img,
      'lista_img'  => $lista_img
    );


    $arrayValores[] = $valorUnico;

  }

  return $arrayValores;

}

function sololetrasynumeros($cadena=null){  
  $valorfinal = "";

  for( $index = 0; $index < strlen($cadena); $index++ )
  {
      if ( (preg_match('/[A-Za-z]+/', $cadena[$index])) || ( is_numeric($cadena[$index]) ) ) {
        $valorfinal .= $cadena[$index];
      }    
  }

  return $valorfinal;


}


function ObtenerIdCodigo($idlista=null, $tipolista=null){  

  $conexion = new ConexionBd(); 

  $SistemaCuentaId = SistemaCuentaId;
  $SistemaCompaniaId = SistemaCompaniaId;

  $lista_cod = 0;

  $arrresultado2 = $conexion->doSelect("lista_cod","lista", "tipolista_id = '$tipolista' and lista_id = '$idlista'");
  if (count($arrresultado2)>0){
    foreach($arrresultado2 as $i=>$valor){
      $lista_cod = $valor["lista_cod"];
    }
  }

  return $lista_cod;

}

function ObtenerIdLista($codigo=null, $tipolista=null){  

  $conexion = new ConexionBd(); 

  $SistemaCuentaId = SistemaCuentaId;
  $SistemaCompaniaId = SistemaCompaniaId;

  $lista_id = 0;

  $arrresultado2 = $conexion->doSelect("lista_id","lista", "tipolista_id = '$tipolista' and lista_cod = '$codigo'");
  if (count($arrresultado2)>0){
    foreach($arrresultado2 as $i=>$valor){
      $lista_id = $valor["lista_id"];
    }
  }

  return $lista_id;

}

function quitar_tildes($cadena) {
  $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
  $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
  $texto = str_replace($no_permitidas, $permitidas ,$cadena);
  return $texto;
}

function ObtenerDatosCompania($cuenta_id=null, $compania_id=null, $getcompaniaalias=null){

  if($getcompaniaalias!=""){
    $where = "and compania_alias = '$getcompaniaalias'";
  }else{
    $where = "and compania.cuenta_id = '$cuenta_id' and compania.compania_id = '$compania_id'";
  }

  $SistemaCuentaId = SistemaCuentaId;
  $SistemaCompaniaId = SistemaCompaniaId;

  $conexion = new ConexionBd();
  $arrresultado = $conexion->doSelect("compania_id, compania_nombre, compania_img, 
    compania_urlweb, compania_imgicono, compania_whatsapp, compania_email, compania_telf, compania_direccion, compania_alias, compania_latitud, compania_longitud",
      "compania",
      "compania_activo = '1' $where");
    foreach($arrresultado as $i=>$valor){

      $compania_id = utf8_encode($valor["compania_id"]);
      $compania_nombre = utf8_encode($valor["compania_nombre"]);
      $compania_whatsapp = utf8_encode($valor["compania_whatsapp"]);
      $compania_email = utf8_encode($valor["compania_email"]);
      $compania_img = utf8_encode($valor["compania_img"]);
      $compania_imgicono = utf8_encode($valor["compania_imgicono"]);
      $compania_urlweb = utf8_encode($valor["compania_urlweb"]);
      $compania_alias = utf8_encode($valor["compania_alias"]);
      $compania_latitud = utf8_encode($valor["compania_latitud"]);
      $compania_longitud = utf8_encode($valor["compania_longitud"]);

      $urlcompaniaimg = $UrlFiles."/admin/arch/$compania_img";
      $urlcompaniaimgicono = $UrlFiles."/admin/arch/$compania_imgicono";


  }

  return $arrresultado;

}


function VerificarUsuarioEstatus($usuario_id=null){

  $SistemaCuentaId = SistemaCuentaId;
  $SistemaCompaniaId = SistemaCompaniaId;

  $conexion = new ConexionBd();  

  $arrresultado = $conexion->doSelect("usuario.l_tipousuarioserv_id, usuario_id, usuario_emailverif",
      "usuario",
      "usuario.usuario_id = '$usuario_id' and usuario.cuenta_id = '$SistemaCuentaId' and usuario.compania_id = '$SistemaCompaniaId'");

  foreach($arrresultado as $i=>$valor){
      $usuario_id = utf8_encode($valor["usuario_id"]);
      $l_tipousuarioserv_id = utf8_encode($valor["l_tipousuarioserv_id"]);
      $usuario_emailverif = utf8_encode($valor["usuario_emailverif"]);
    }

    $arrresultado = $conexion->doSelect("
        requisito.requisito_id, requisito.l_requisitolista_id, requisito_descrip, requisito.l_tipoarchivo_id, requisito_arch, 
        requisito_archnombre, requisito_cantarchivos, requisito.cuenta_id, requisito.compania_id,
        requisito_activo, requisito_eliminado, requisito_fechareg, 
        requisito.usuario_idreg, requisito.l_estatus_id, requisito.usuario_id,
        usuario.usuario_id, usuario.usuario_codigo, usuario.usuario_email, usuario.usuario_nombre, usuario.usuario_apellido, 
        usuario.usuario_telf, usuario.usuario_activo, usuario.usuario_eliminado, 
        usuario.usuario_documento, usuario.usuario_img, usuario.perfil_id, 

        cuenta.usuario_codigo as cuenta_codigo, cuenta.usuario_nombre as cuenta_nombre,
        cuenta.usuario_apellido as cuenta_apellido, compania_nombre, 
        listarequisito.lista_id as requisitolista_id,
        listarequisito.lista_nombre as requisitolista_nombre,
        estatus.lista_nombre as estatus_nombre,
        tipoarchivo.lista_nombre as tipoarchivo_nombre,
        tipoarchivo.lista_img as tipoarchivo_img

        ",
        "lista listarequisito
            inner join listarequisitotipousuarioserv on listarequisitotipousuarioserv.l_requisitolista_id = listarequisito.lista_id
            left join requisito on requisito.l_requisitolista_id = listarequisito.lista_id and requisito.usuario_id = '$usuario_id'
            and requisito.cuenta_id = '$SistemaCuentaId' and requisito.compania_id = '$SistemaCompaniaId'
            left join lista tipoarchivo on tipoarchivo.lista_id = requisito.l_tipoarchivo_id
            left join lista estatus on estatus.lista_id = requisito.l_estatus_id
            left join usuario on usuario.usuario_id = requisito.usuario_id
            left join usuario cuenta on cuenta.usuario_id = requisito.cuenta_id
            left join compania on compania.compania_id = requisito.compania_id

        ",
        "listarequisito.lista_activo = '1' and listarequisitotipousuarioserv.l_tipousuarioserv_id = '$l_tipousuarioserv_id' and listarequisito.tipolista_id  ='49'  ");

    $requisitoenrevision = 0;
    $requisitorechazado = 0;
    $requisitoconfirmado = 0;
    $requisitopendiente = 0;


    foreach($arrresultado as $i=>$valor){
        $requisito_id = utf8_encode($valor["requisito_id"]);
        $l_estatus_id = utf8_encode($valor["l_estatus_id"]);

        if ($l_estatus_id==""){
          $requisitopendiente = 1;
        }

        if ($l_estatus_id==165){
          $requisitoconfirmado = 1;
        } 
        if ($l_estatus_id==166){
          $requisitorechazado = 1;
        } 

        if ($l_estatus_id==167){
          $requisitoenrevision = 1;
        } 

    }

    $estatuscolocar= 189;

    if ($requisitopendiente == 1 ){
      $estatuscolocar= 190;
    } else if ($requisitorechazado == 1 ){
      $estatuscolocar= 190;
    } else if ($requisitoenrevision == 1 ){
      $estatuscolocar= 191;
    } else if ($requisitoconfirmado == 1 ){
      $estatuscolocar= 189;
    }

    $resultado = $conexion->doUpdate("usuario", "
      l_estatus_id = '$estatuscolocar'
      ", "usuario_id='$usuario_id'"); 


}

function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2) {
  // Cálculo de la distancia en grados
  $degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));

  // Conversión de la distancia en grados a la unidad escogida (kilómetros, millas o millas naúticas)
  switch($unit) {
    case 'km':
      $distance = $degrees * 111.13384; // 1 grado = 111.13384 km, basándose en el diametro promedio de la Tierra (12.735 km)
      break;
    case 'mi':
      $distance = $degrees * 69.05482; // 1 grado = 69.05482 millas, basándose en el diametro promedio de la Tierra (7.913,1 millas)
      break;
    case 'nmi':
      $distance =  $degrees * 59.97662; // 1 grado = 59.97662 millas naúticas, basándose en el diametro promedio de la Tierra (6,876.3 millas naúticas)
  }
  return round($distance, $decimals);
}


function InsertarNotificacion($modulo_id=null, $notif_tabla=null, $elemento_id=null, $titulonotificacion=null, 
    $descripcion=null, $usuarioid_origen=null, $usuario_iddestino=null, $cuenta=null, $compania=null, 
    $emailnotificar = null, $telfnotificar=null, $notif_visible=null, $tiponotificacion = null){

    $conexion = new ConexionBd();  

    $SistemaCuentaId = SistemaCuentaId;
    $SistemaCompaniaId = SistemaCompaniaId;

    session_start();
    $_SESSION[iniuser] = $_SESSION[iniuser];
    $_SESSION[perfil] = $_SESSION[perfil];
    $_SESSION[idcuenta] = $_SESSION[idcuenta];

    if ($cuenta==""){
      if ($_SESSION[perfil]!="1"){
        $cuenta = $_SESSION[idcuenta];
      }      
    }

    $cuenta = $SistemaCuentaId;
    $compania = $SistemaCompaniaId;
    

    if ($notif_visible==""){$notif_visible="1";}
    if ($modulo_id==""){$modulo_id="0";}
    if ($elemento_id==""){$elemento_id="0";}
    if ($usuarioid_origen==""){$usuarioid_origen="0";}
    if ($usuario_iddestino==""){$usuario_iddestino="0";}
    if ($cuenta==""){$cuenta="0";}
    if ($compania==""){$compania="0";}    

    $fechaactual = formatoFechaHoraBd();

    $idusuarioidreg = $_SESSION[iniuser];
    if ($idusuarioidreg==""){$idusuarioidreg=$elemento_id;}


    $obtenerCodigoLista = 1; //Notificado en Sistema
    $obtenerTipoLista = 28;
    $estatusid = ObtenerIdLista($obtenerCodigoLista, $obtenerTipoLista);

    $resultado = $conexion->doInsert("
      notificacion
        (notif_tabla, modulo_id, elemento_id, notif_titulo, notif_descrip, notif_fechareg, l_estatus_id, usuario_idorigen, usuario_iddestino, usuario_idreg, cuenta_id, compania_id, 
          notif_activo, notif_eliminado, notif_notificadoemail, notif_leido, notif_visible,
          l_tiponotif_id, notif_email, notif_telf)
      ",
      "'$notif_tabla', '$modulo_id', '$elemento_id', '$titulonotificacion', '$descripcion', '$fechaactual', '$estatusid', '$usuarioid_origen','$usuario_iddestino','$idusuarioidreg','$cuenta','$compania',
        '1','0','0','0', '$notif_visible',
        '$tiponotificacion','$emailnotificar','$telfnotificar'
    ");

   

}



function ObtenerListaNoAgregados($tipolista_id=null, $cuenta=null, $compania=null, $elementoid=null, $ordenar=null, $agregardespues=null, $listaid_rel=null){

  if ($ordenar=="entero"){
    $orderby = "CAST(lista_nombre AS UNSIGNED)";
  }else{
    $orderby = "lista_orden";
  }

  $SistemaCuentaId = SistemaCuentaId;
  $SistemaCompaniaId = SistemaCompaniaId;

  $cuenta = $SistemaCuentaId;
  $compania = $SistemaCompaniaId;

  $conexion = new ConexionBd();  

  session_start();
  $_SESSION[perfil] = $_SESSION[perfil];
  $_SESSION[idcuenta] = $_SESSION[idcuenta];
  $_SESSION[idcompania] = $_SESSION[idcompania];

  $option = "<option value=''>-- Seleccione --</option>";

  if ($listaid_rel!=""){
    $wherelistarel = " and lista.lista_idrel = '$listaid_rel' ";
  }

  if ($cuenta!="" ){
    
      $arrresultado = $conexion->doSelect("lista.lista_id, lista_nombre, listacuenta_id, 
        listacuenta_activo, listacuenta_nombre, listacuenta_nombredos",
            "lista 
                inner join listacuentarel on listacuentarel.lista_id = lista.lista_id and listacuentarel.cuenta_id ='$cuenta' 
                and listacuentarel.compania_id = '$compania' and listacuentarel.tipolista_id = '$tipolista_id' 
                and listacuentarel_activo = '1'

                left join listacuenta on listacuenta.lista_id = lista.lista_id and listacuenta.cuenta_id ='$cuenta' 
                and listacuenta.compania_id = '$compania' 
            ",
            "lista_activo = '1' and lista.tipolista_id = '$tipolista_id' $wherelistarel  and ((lista_ppal = '1') or (lista_ppal = '0' and lista.cuenta_id ='$cuenta' and lista.compania_id = '$compania'))  ", null, "$orderby asc");

      if (count($arrresultado)==0){

         $arrresultado = $conexion->doSelect("lista.lista_id, lista_nombre, listacuenta_id, 
          listacuenta_activo, listacuenta_nombre, listacuenta_nombredos",
            "lista                 
                left join listacuenta on listacuenta.lista_id = lista.lista_id and listacuenta.cuenta_id ='$cuenta' 
                and listacuenta.compania_id = '$compania' 
            ",
            "lista_activo = '1' $wherelistarel and lista.tipolista_id = '$tipolista_id'  and ((lista_ppal = '1') or (lista_ppal = '0' and lista.cuenta_id ='$cuenta' and lista.compania_id = '$compania'))  ", null, "$orderby asc");


      }

    
    foreach($arrresultado as $i=>$valor){

      $lista_id = utf8_encode($valor["lista_id"]);  
      $lista_nombre = utf8_encode($valor["lista_nombre"]);  
      $listacuenta_id = utf8_encode($valor["listacuenta_id"]);  
      $listacuenta_nombre = utf8_encode($valor["listacuenta_nombre"]);  
      $listacuenta_nombredos = utf8_encode($valor["listacuenta_nombredos"]);  
      $listacuenta_activo = utf8_encode($valor["listacuenta_activo"]);  

      if ($listacuenta_id!=""){
        if ($listacuenta_activo=="1"){
          if ($elementoid==$lista_id){
              $option .= "<option selected='selected' value='$lista_id'>$listacuenta_nombre$agregardespues</option>";
          }else{
              $option .= "<option value='$lista_id'>$listacuenta_nombre$agregardespues</option>";
          }    
        }
      }else{
        if ($elementoid==$lista_id){
            $option .= "<option selected='selected' value='$lista_id'>$lista_nombre$agregardespues</option>";
        }else{
            $option .= "<option value='$lista_id'>$lista_nombre$agregardespues</option>";
        }    
      }  
    }
  }
  
  if (count($arrresultado)==0 && $compania==""){
    $option = "<option  value=''>Seleccione Primero la Compañia</option>";
  }else if (count($arrresultado)==0){
    $option = "<option  value=''>NO EXISTEN VALORES</option>";
  }

  if (count($arrresultado)==1){
    $option = "<option selected='selected' value='$lista_id'>$lista_nombre $agregardespues</option>";
  }

 

  /*
  
  $arrresultado = $conexion->doSelect("lista_id, lista_nombre","lista ",
            "lista_activo = '1' and tipolista_id = '$tipolista_id'  and ((lista_ppal = '1') or (lista_ppal = '0' and cuenta_id ='$cuenta' and compania_id = '$compania'))  ", null, "lista_nombre asc");

  $option = "<option value='0'>-- Seleccione --</option>";
  foreach($arrresultado as $i=>$valor){

    $lista_id = utf8_encode($valor["lista_id"]);  
    $lista_nombre = utf8_encode($valor["lista_nombre"]);  

    if ($elementoid==$lista_id){
        $option .= "<option selected='selected' value='$lista_id'>$lista_nombre</option>";
    }else{
        $option .= "<option value='$lista_id'>$lista_nombre</option>";
    }
  }

  if (count($arrresultado)==1){
    $option = "<option selected='selected' value='$lista_id'>$lista_nombre</option>";
  }

  */

  return $option;

}


function ObtenerListaArray($tipolista_id=null, $cuenta=null, $compania=null, $elementoid=null, $ordenar=null, $agregardespues=null, $listaid_rel=null){

  if ($ordenar=="entero"){
    $orderby = "CAST(lista_nombre AS UNSIGNED)";
  }else{
    $orderby = "lista_orden";
  }

  $SistemaCuentaId = SistemaCuentaId;
  $SistemaCompaniaId = SistemaCompaniaId;

  $conexion = new ConexionBd();  

  $cuenta = $SistemaCuentaId;
  $compania = $SistemaCompaniaId;

  session_start();
  $_SESSION[perfil] = $_SESSION[perfil];
  $_SESSION[idcuenta] = $_SESSION[idcuenta];
  $_SESSION[idcompania] = $_SESSION[idcompania];

  $option = "<option value=''>-- Seleccione --</option>";

  if ($listaid_rel!=""){
    $wherelistarel = " and lista.lista_idrel = '$listaid_rel' ";
  }

  if ($cuenta!="" ){
    
      $arrresultado = $conexion->doSelect("lista.lista_id, lista_nombre, listacuenta_id, lista_img,
        listacuenta_activo, listacuenta_nombre, listacuenta_nombredos",
            "lista 
                inner join listacuentarel on listacuentarel.lista_id = lista.lista_id and listacuentarel.cuenta_id ='$cuenta' 
                and listacuentarel.compania_id = '$compania' and listacuentarel.tipolista_id = '$tipolista_id' 
                and listacuentarel_activo = '1'

                left join listacuenta on listacuenta.lista_id = lista.lista_id and listacuenta.cuenta_id ='$cuenta' 
                and listacuenta.compania_id = '$compania' 
            ",
            "lista_activo = '1' and lista.tipolista_id = '$tipolista_id' $wherelistarel  and ((lista_ppal = '1') or (lista_ppal = '0' and lista.cuenta_id ='$cuenta' and lista.compania_id = '$compania'))  ", null, "$orderby asc");

      if (count($arrresultado)==0){

         $arrresultado = $conexion->doSelect("lista.lista_id, lista_nombre, lista_img,  listacuenta_id, 
          listacuenta_activo, listacuenta_nombre, listacuenta_nombredos",
            "lista                 
                left join listacuenta on listacuenta.lista_id = lista.lista_id and listacuenta.cuenta_id ='$cuenta' 
                and listacuenta.compania_id = '$compania' 
            ",
            "lista_activo = '1' $wherelistarel and lista.tipolista_id = '$tipolista_id'  and ((lista_ppal = '1') or (lista_ppal = '0' and lista.cuenta_id ='$cuenta' and lista.compania_id = '$compania'))  ", null, "$orderby asc");


      }

    
    foreach($arrresultado as $i=>$valor){

      $lista_id = utf8_encode($valor["lista_id"]);  
      $lista_nombre = utf8_encode($valor["lista_nombre"]);  
      $lista_img = utf8_encode($valor["lista_img"]);  
      $listacuenta_id = utf8_encode($valor["listacuenta_id"]);  
      $listacuenta_nombre = utf8_encode($valor["listacuenta_nombre"]);  
      $listacuenta_nombredos = utf8_encode($valor["listacuenta_nombredos"]);  
      $listacuenta_activo = utf8_encode($valor["listacuenta_activo"]);  

      if ($listacuenta_id!=""){
        if ($listacuenta_activo=="1"){
          if ($elementoid==$lista_id){
              $option .= "<option selected='selected' value='$lista_id'>$listacuenta_nombre$agregardespues</option>";
          }else{
              $option .= "<option value='$lista_id'>$listacuenta_nombre$agregardespues</option>";
          }    
        }
      }else{
        if ($elementoid==$lista_id){
            $option .= "<option selected='selected' value='$lista_id'>$lista_nombre$agregardespues</option>";
        }else{
            $option .= "<option value='$lista_id'>$lista_nombre$agregardespues</option>";
        }    
      }  
    }
  }



  return $arrresultado;

}


function ObtenerLista($tipolista_id=null, $cuenta=null, $compania=null, $elementoid=null, $ordenar=null, $agregardespues=null, $listaid_rel=null){

  if ($ordenar=="entero"){
    $orderby = "CAST(lista_nombre AS UNSIGNED)";
  }else{
    $orderby = "lista_nombre";
  }

  $SistemaCuentaId = SistemaCuentaId;
  $SistemaCompaniaId = SistemaCompaniaId;

  $conexion = new ConexionBd();  

  $cuenta = $SistemaCuentaId;
  $compania = $SistemaCompaniaId;

  session_start();
  $_SESSION[perfil] = $_SESSION[perfil];
  $_SESSION[idcuenta] = $_SESSION[idcuenta];
  $_SESSION[idcompania] = $_SESSION[idcompania];

  $option = "<option value=''>-- Seleccione --</option>";

  if ($listaid_rel!=""){
    $wherelistarel = " and lista.lista_idrel = '$listaid_rel' ";
  }

  if ($cuenta!="" ){
    
      $arrresultado = $conexion->doSelect("lista.lista_id, lista_nombre, listacuenta_id, 
        listacuenta_activo, listacuenta_nombre, listacuenta_nombredos",
            "lista 
                inner join listacuentarel on listacuentarel.lista_id = lista.lista_id and listacuentarel.cuenta_id ='$cuenta' 
                and listacuentarel.compania_id = '$compania' and listacuentarel.tipolista_id = '$tipolista_id' 
                and listacuentarel_activo = '1'

                left join listacuenta on listacuenta.lista_id = lista.lista_id and listacuenta.cuenta_id ='$cuenta' 
                and listacuenta.compania_id = '$compania' 
            ",
            "lista_activo = '1' and lista.tipolista_id = '$tipolista_id' $wherelistarel  and ((lista_ppal = '1') or (lista_ppal = '0' and lista.cuenta_id ='$cuenta' and lista.compania_id = '$compania'))  ", null, "$orderby asc");

      if (count($arrresultado)==0){

         $arrresultado = $conexion->doSelect("lista.lista_id, lista_nombre, listacuenta_id, 
          listacuenta_activo, listacuenta_nombre, listacuenta_nombredos",
            "lista                 
                left join listacuenta on listacuenta.lista_id = lista.lista_id and listacuenta.cuenta_id ='$cuenta' 
                and listacuenta.compania_id = '$compania' 
            ",
            "lista_activo = '1' $wherelistarel and lista.tipolista_id = '$tipolista_id'  and ((lista_ppal = '1') or (lista_ppal = '0' and lista.cuenta_id ='$cuenta' and lista.compania_id = '$compania'))  ", null, "$orderby asc");


      }

    
    foreach($arrresultado as $i=>$valor){

      $lista_id = utf8_encode($valor["lista_id"]);  
      $lista_nombre = utf8_encode($valor["lista_nombre"]);  
      $listacuenta_id = utf8_encode($valor["listacuenta_id"]);  
      $listacuenta_nombre = utf8_encode($valor["listacuenta_nombre"]);  
      $listacuenta_nombredos = utf8_encode($valor["listacuenta_nombredos"]);  
      $listacuenta_activo = utf8_encode($valor["listacuenta_activo"]);  

      if ($listacuenta_id!=""){
        if ($listacuenta_activo=="1"){
          if ($elementoid==$lista_id){
              $option .= "<option selected='selected' value='$lista_id'>$listacuenta_nombre$agregardespues</option>";
          }else{
              $option .= "<option value='$lista_id'>$listacuenta_nombre$agregardespues</option>";
          }    
        }
      }else{
        if ($elementoid==$lista_id){
            $option .= "<option selected='selected' value='$lista_id'>$lista_nombre$agregardespues</option>";
        }else{
            $option .= "<option value='$lista_id'>$lista_nombre$agregardespues</option>";
        }    
      }  
    }
  }
  
  if (count($arrresultado)==0 && $compania==""){
    $option = "<option  value=''>Seleccione Primero la Compañia</option>";
  }else if (count($arrresultado)==0){
    $option = "<option  value=''>NO EXISTEN VALORES</option>";
  }

  if (count($arrresultado)==1){
    //$option = "<option selected='selected' value='$lista_id'>$lista_nombre $agregardespues</option>";
  }

 

  /*
  
  $arrresultado = $conexion->doSelect("lista_id, lista_nombre","lista ",
            "lista_activo = '1' and tipolista_id = '$tipolista_id'  and ((lista_ppal = '1') or (lista_ppal = '0' and cuenta_id ='$cuenta' and compania_id = '$compania'))  ", null, "lista_nombre asc");

  $option = "<option value='0'>-- Seleccione --</option>";
  foreach($arrresultado as $i=>$valor){

    $lista_id = utf8_encode($valor["lista_id"]);  
    $lista_nombre = utf8_encode($valor["lista_nombre"]);  

    if ($elementoid==$lista_id){
        $option .= "<option selected='selected' value='$lista_id'>$lista_nombre</option>";
    }else{
        $option .= "<option value='$lista_id'>$lista_nombre</option>";
    }
  }

  if (count($arrresultado)==1){
    $option = "<option selected='selected' value='$lista_id'>$lista_nombre</option>";
  }

  */

  return $option;

}



function ObtenerListaCategorias($categ_id=null, $SistemaCuentaIdGet=null, $SistemaCompaniaIdGet=null){ 

  $conexion = new ConexionBd();

  $SistemaCuentaId = SistemaCuentaId;
  $SistemaCompaniaId = SistemaCompaniaId;


  if ($categ_id!=""){
    $wherecateg = " and lista.lista_id = '$categ_id' ";
  } 

  /*



  

  $arrresultado = $conexion->doSelect("lista.lista_id, lista.lista_nombre, lista.lista_ppal, 
lista.lista_activo, listacuenta_id, listacuenta_activo, listacuenta_eliminado, 
lista.tipolista_id, listacuenta_nombre,
cuenta.usuario_codigo as cuenta_codigo, cuenta.usuario_nombre as cuenta_nombre,
cuenta.usuario_apellido as cuenta_apellido, compania_nombre, lista.cuenta_id, lista.compania_id,
tipolista_labeluno, tipolista_labeldos, tipolista_labeltres, tipolista_labelconfig,
listarel.lista_nombre as listarel_nombre, lista.lista_img
",
  "lista 

      left join lista listarel on listarel.lista_id = lista.lista_idrel

      inner join tipolista on tipolista.tipolista_id = lista.tipolista_id
      inner join usuario cuenta on cuenta.usuario_id = lista.cuenta_id
      inner join compania on compania.compania_id = lista.compania_id

      inner join listacuenta on listacuenta.lista_id = lista.lista_id 
      $wherecuenta
      $wherecompania                
  ",
  "lista.lista_activo = '1' and lista.tipolista_id = '39' $wherecateg and ((lista.lista_ppal = '1' $wherelistaactivo) or (lista.lista_ppal = '0' $wherelista $wherecompania ))  ", null, "lista.lista_nombre asc");

  */


  $where = " and listacuenta.cuenta_id = '$SistemaCuentaIdGet' and listacuenta.compania_id = '$SistemaCompaniaIdGet' ";
  $wherecuenta = " and listacuenta.cuenta_id = '$SistemaCuentaIdGet' ";
  $wherecompania = " and listacuenta.compania_id = '$SistemaCompaniaIdGet' ";   
  $wherelistacuenta = " and listacuenta.cuenta_id = '$SistemaCuentaIdGet' and listacuenta.compania_id = '$SistemaCompaniaIdGet'  ";
  $wherelistaactivo = " and lista.lista_activo = '1' ";

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

      left join listacuenta on listacuenta.lista_id = lista.lista_id
      $wherelistacuenta
            
      left join usuario cuenta on cuenta.usuario_id = listacuenta.cuenta_id
        left join compania on compania.compania_id = listacuenta.compania_id              

        $wherecuenta
        $wherecompania

    ",
    "lista.lista_activo = '1' and lista.cuenta_id = '$SistemaCuentaId' and lista.compania_id = '$SistemaCompaniaId' $wherecateg and lista.tipolista_id = '39' ", null, "listacuenta.listacuenta_nombre asc");

  $arrayValores = array();

  foreach($arrresultado as $i=>$valor){

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
    $lista_img = utf8_encode($valor["lista_img"]);  
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


    if ($listacuenta_id!=""){
      $lista_nombre = $listacuenta_nombre;
      $lista_orden = $listacuenta_orden;
      $lista_img = $listacuenta_img;
      $lista_activo = $listacuenta_activo;
    }

    if ($lista_ppal=="1" && $t_cuenta_id==""){ // Es porque no esta personalizado por la empresa
      $cuenta = $cuentasistema;
      $compania_nombre = $companiasistema_nombre;
    }

  
    if ($lista_activo=="0"){
      $activo = "<i onclick='cambiarestatuslista(\"".$lista_id."\",\"".$t_cuenta_id."\",\"".$t_compania_id."\",1)' title='Deshabilitar' class='fa fa-minus btn-deshabilitar'></i>";
    }else{
      $activo = "<i onclick='cambiarestatuslista(\"".$lista_id."\",\"".$t_cuenta_id."\",\"".$t_compania_id."\",0)' title='Habilitar' class='fa fa-check btn-habilitar'></i>";
    }
    
    $accioneliminar = "<i onclick='eliminarlista(\"".$lista_id."\",\"".$t_cuenta_id."\",\"".$t_compania_id."\",0)' title='Eliminar?' class='fa fa-trash btn-eliminar'></i>";  

    $modificar = "<a href='modificarcategoriaservicio?id=$lista_id&lid=$listacuenta_id'><i title='Ver' class='fa fa-edit btn-modificar'></i></a>";

    $imagen = "<img src='arch/$lista_img' style='height: 80px'";
    

    if (P_Mod!="1"){$modificar = ""; $activo = "";}
    if (P_Eli!="1"){$accioneliminar = ""; $activo = "";}

    $mostrarcolumnacuenta = "<td>$cuenta </td>";
    $mostrarcolumnacompania = "<td>$compania_nombre</td>";

    if ($_SESSION[perfil]=="1"){      
      
    }
    else if ($_SESSION[perfil]=="2"){       
      $mostrarcolumnacuenta = "";
    }
    else {      
      $mostrarcolumnacuenta = "";
      $mostrarcolumnacompania = ""; 
    }    
    

    $textohtml .= "
           <tr style='font-size: 14px'>               
            $mostrarcolumnacuenta
            $mostrarcolumnacompania
            <td style='text-align: center'>$imagen </td>
            <td>$lista_nombre</td>            
            <td style='text-align: center'>$modificar &nbsp $activo &nbsp $accioneliminar</td>
              </tr>
        ";

    $valorUnico = array(     
      'lista_id' => $lista_id,
      'lista_nombre'  => $lista_nombre,
      'lista_img'  => $lista_img
    );

    $arrayValores[] = $valorUnico;

  }

  return $arrayValores;

}


function geturlactual() {
    
  $urlpath = $_SERVER['REQUEST_URI'];

  $buscar ="/activos/todofacil/";

  $urldefinitiva = str_replace($buscar, "", $urlpath);

  $urldefinitiva = str_replace("/", "", $urldefinitiva);
  
  //$urldefinitiva = $urlpath."".$antespath;

  return $urldefinitiva;
}

function generateRandomNumber() {
  $length = 4;
  $characters = '0123456789';
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, strlen($characters) - 1)];
  }
  return $randomString;
}

function generateRandomString() {
  $length = 2;
  $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, strlen($characters) - 1)];
  }
  return $randomString;
}

function generarId() {
  $codigo = generateRandomString().generateRandomNumber();
  return $codigo;
}

function formatoFechaHoraBd($sinhora=null, $diasatras=null) {
  //ini_set('date.timezone','UTC');
  date_default_timezone_set('America/Argentina/Buenos_Aires');
  //date_default_timezone_set('America/Mexico_City');

  $fecha_actual = date("Y-m-d");

  $tiempoMod = time();
  if($sinhora=="1"){
    return date("Y-m-d",$tiempoMod);
  }else if($diasatras!=""){
    if ($diasatras=="1"){
      $diasatras = 30;
    }
    return date("Y-m-d",strtotime($fecha_actual."- $diasatras days")); ;
  }else{
    return date("Y-m-d H:i:s",$tiempoMod);
  }
}

?>