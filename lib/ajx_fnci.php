<?php
include_once '../lib/configinc_in.php';
include_once '../lib/mysqlclass.php';
include_once '../lib/xajax_0.2.4/xajax.inc.php';
include_once '../lib/funciones.php';
include_once '../lib/phpmailer/libemail.php';
include_once '../lib/config.php';


$GLOBALS[SistemaCuentaId] = SistemaCuentaId;
$GLOBALS[SistemaCompaniaId] = SistemaCompaniaId;

$xajax = new xajax('ajx_fnci.php');
$xajax->registerFunction('registrarusuario');
$xajax->registerFunction('ingresarusuario');

session_start();

function ingresarusuario($usuario=null, $password=null, $tipo=null, $compania_id=null){
	
	$objResponse = new xajaxResponse();

	$SistemaCuentaId = $GLOBALS[SistemaCuentaId];
	$SistemaCompaniaId = $GLOBALS[SistemaCompaniaId];

	if ($compania_id!="" && $compania_id!=$SistemaCompaniaId){
		$compania_idcolocar = $compania_id;
		$clienteexterno = 1;
	}else{
		$compania_idcolocar = $SistemaCompaniaId;
	}

	$conexion = new ConexionBd();

	$usuario = trim(utf8_decode($usuario));	
	$password = trim(utf8_decode($password));	

	
	$arrresultado = $conexion->doSelect("
			usuario.usuario_id,  usuario.usuario_clave, usuario.usuario_img,
			usuario.usuario_activo, usuario.usuario_eliminado, usuario.perfil_id, 
			usuario.usuario_nombre, usuario.usuario_email, usuario.cuenta_id, usuario.compania_id, usuario.usuario_whatsapp,
			perfil.perfil_idorig, compania.compania_idrel
		",
			"usuario				
				left join perfil on usuario.perfil_id = perfil.perfil_id
				inner join compania on compania.compania_id = usuario.compania_id
			",
			"usuario_activo ='1' and ( usuario.cuenta_id = '$SistemaCuentaId'  and usuario_email = '$usuario' and usuario_clave = '$password' )");

	if (count($arrresultado)>0){
		foreach($arrresultado as $i=>$valor){

			$usuario_id = utf8_encode($valor["usuario_id"]);			
			$perfil_id = utf8_encode($valor["perfil_id"]);
			$perfil_idorig = utf8_encode($valor["perfil_idorig"]);
			$cuenta_id = utf8_encode($valor["cuenta_id"]);
			$compania_id = utf8_encode($valor["compania_id"]);
			$compania_idrel = utf8_encode($valor["compania_idrel"]);
			$usuario_nombre = utf8_encode($valor["usuario_nombre"]);
			$usuario_img = utf8_encode($valor["usuario_img"]);
			$usuario_email = utf8_encode($valor["usuario_email"]);
			$usuario_activo = utf8_encode($valor["usuario_activo"]);
			$usuario_whatsapp = utf8_encode($valor["usuario_whatsapp"]);

			if ($usuario_activo=="1"){
				
				$_SESSION[iniuser] = $usuario_id;
				
				if ($usuario_nombre!=""){
					$_SESSION[login] = $usuario_nombre;	
				}else if ($usuario_whatsapp!=""){
					$_SESSION[login] = $usuario_whatsapp;	
				}

				$_SESSION[perfilactual] = $perfil_id;	

				if ($perfil_idorig!=""){
					$perfil_id  = $perfil_idorig;
				}

				$_SESSION[perfil] = $perfil_id;	
				
				$_SESSION["email"] = $usuario_email;

				if ($usuario_img==""){
					$usuario_img = "1.png";
				}

				if ($compania_id==""){$compania_id=0;}

				$_SESSION[imguser] = $usuario_img;	
				$_SESSION[idcuenta] = $cuenta_id;	
				$_SESSION[idcompania] = $compania_id;
				$_SESSION[idcompaniarel] = $compania_idrel;		

				
				$objResponse->addScript("window.location = 'verificarinicio';");
				

				
				
			}else{
				$texresp ="Usuario Inactivo, por favor enviar un email a administrador del sistema para solicitar la activacion";
				$objResponse->addAlert($texresp);
			}

			

		}
	}else{
		$texresp ="Error, de ingreso al sistema, verifique su usuario y clave.";
		$objResponse->addAlert($texresp);
	}

	return $objResponse;
}


function registrarusuario($nombre=null, $apellido=null, $email=null, $clave=null, $compania_id=null){

	$objResponse = new xajaxResponse();

	$SistemaCuentaId = $GLOBALS[SistemaCuentaId];
	$SistemaCompaniaId = $GLOBALS[SistemaCompaniaId];

	$usuario_nombre = utf8_decode($nombre);	
	$usuario_apellido = utf8_decode($apellido);		
	$usuario_email = utf8_decode($email);
	$usuario_clave = utf8_decode($clave);	
	$compania_id = utf8_decode($compania_id);
	$noinformado = "(No Informado)";
	
	$cadena_buscada   = '@';
	$posicion_coincidencia = strpos($email, $cadena_buscada);
	$usuario_alias = substr($email, 0, $posicion_coincidencia);
	if ($usuario_alias==""){$usuario_alias=$email;}

	if ($compania_id!="" && $compania_id!=$SistemaCompaniaId ){
		$compania_idcolocar = $compania_id;
		$clienteexterno = 1;
		$perfil="109";
	}else{
		$compania_idcolocar = $SistemaCompaniaId;
		$perfil="108";
	}

	$fechaactual = formatoFechaHoraBd();



	session_start();		
	$getreferido = $_COOKIE["referido"];


	$conexion = new ConexionBd();

	$arrresultado = $conexion->doSelect("usuario_email","usuario",
			"usuario_eliminado ='0' and usuario_email = '$usuario_email' and cuenta_id = '$SistemaCuentaId' and compania_id = '$compania_idcolocar' ");

	if (count($arrresultado)>0){
		foreach($arrresultado as $i=>$valor){

			$usuario_telf = utf8_encode($valor["usuario_telf"]);						
			$usuario_emailencontrado = utf8_encode($valor["usuario_email"]);

			if ($usuario_emailencontrado==$usuario_email){
				$texresp ="Error: El email ingresado ya se encuentra registrado en el sistema, por favor verificar que ya no te cuente con una cuenta abierta";	

				$objResponse->addAlert($texresp);

				return $objResponse;
			}


		}
	}



	$arrresultado2 = $conexion->doSelect("compania_urlweb","compania", "compania_id = '$SistemaCompaniaId'");
	if (count($arrresultado2)>0){
		foreach($arrresultado2 as $i=>$valor){
			$compania_urlweboriginal = $valor["compania_urlweb"];
		}
	}

	$usuario_idreferido = 0;

	$arrresultado2 = $conexion->doSelect("usuario_id","usuario", "usuario_codigo = '$getreferido' and perfil_id = '5' and cuenta_id = '$SistemaCuentaId' ");
	if (count($arrresultado2)>0){
		foreach($arrresultado2 as $i=>$valor){
			$usuario_idreferido = $valor["usuario_id"];
		}	
	}


	$codigocliente = generarId();
	$codverificar = uniqid();	

	$obtenerCodigoLista = 4;
	$obtenerTipoLista = 98;
	$estatusidregistrousuario = ObtenerIdLista($obtenerCodigoLista, $obtenerTipoLista);


	$codverif = uniqid();

	$resultado = $conexion->doInsert("
	usuario
		(usuario_email, usuario_clave, usuario_nombre, usuario_apellido, usuario_telf,
		usuario_fechareg, usuario_activo, usuario_eliminado, usuario_img, 
		perfil_id, 
		cuenta_id, compania_id, usuario_codverif, usuario_emailverif, l_estatus_id, 
		usuario_documento, usuario_direccion, usuario_imgdoc, usuario_alias, usuario_idreferido
		) 
	",
	"'$usuario_email', '$usuario_clave', '$usuario_nombre', '$usuario_apellido', '$usuario_telf',
	'$fechaactual', '1', '0',  '1.png', 
	'$perfil',
	'$SistemaCuentaId','$compania_idcolocar','$codverif','0','$estatusidregistrousuario',
	'$usuario_documento','$usuario_direccion','2.png', '$usuario_alias','$usuario_idreferido'

	");

	$arrresultado2 = $conexion->doSelect("max(usuario_id) as usuario_id","usuario");
	if (count($arrresultado2)>0){
		foreach($arrresultado2 as $i=>$valor){
			$usuario_id = $valor["usuario_id"];
		}	



		$compania_urlweb = "";

		$_SESSION[iniuser] = $usuario_id;	

		if ($clienteexterno!="1"){ // Si es registro principal de un centro

			$compania_urlweboriginalcentro = $compania_urlweboriginal."sitio/$compania_alias";

			$resultado = $conexion->doInsert("
			compania
				(compania_nombre, compania_razonsocial, compania_img, compania_activo, compania_eliminado,
				compania_fechareg, cuenta_id, compania_cedula, compania_email, compania_telf, compania_direccion,
				compania_terminos, compania_tipo, compania_utilizar, usuario_idreg,  
				compania_whatsapp, compania_imgicono, compania_alias, compania_idrel, compania_urlweb) 
			",
			"'$usuario_alias', '$usuario_alias','2.png','1', '0',
			'$fechaactual', '$SistemaCuentaId','','$usuario_email', '$noinformado','$noinformado',
			'','',null,'$usuario_id','$noinformado','2.png','$usuario_alias','$SistemaCompaniaId','$compania_urlweboriginalcentro'");

			$arrresultado2 = $conexion->doSelect("max(compania_id) as compania_id","compania");
			if (count($arrresultado2)>0){
				foreach($arrresultado2 as $i=>$valor){
					$compania_id = $valor["compania_id"];
				}

				$resultado = $conexion->doUpdate("usuario", "						
					compania_id ='$compania_id'
				",
				"usuario_id='$usuario_id'");


				$lista_nombre = "Categoria 1";
				$lista_nombredos = "Categoria 1";
				$lista_img = "5.jpg";
				$lista_ppal = 0;  
				$lista_orden = 1; 
				$tipolista_id = "39"; 
				$lista_idrel = 0;
				$lista_nombre = trim($lista_nombre);
				$lista_url = urls_amigables($lista_nombre);
				$fechaactual = $fechaactual;
				$lista_mostrarppal=1;

				$resultadoGuardarLista = GuardarProcesoLista($lista_nombre, $lista_nombredos, $lista_descrip, $lista_img, $lista_ppal, $lista_orden, $tipolista_id, $SistemaCuentaId, $compania_id, $lista_icono, $lista_color, $lista_idrel, $lista_url, $fechaactual, $lista_mostrarppal);

				foreach ($resultadoGuardarLista as $key => $value) {
					$categoria_id = $resultadoGuardarLista["lista_id"];		
				}


				AsignarRegistrosCreacionCompania($SistemaCuentaId, $compania_id, $categoria_id);
			}	

		}

	}	


	$arrresultado = $conexion->doSelect("usuario.usuario_id, usuario.usuario_nombre, usuario.usuario_apellido, 
      usuario.usuario_email, usuario.usuario_telf, compania.compania_nombre, usuario.usuario_clave, 
      usuario.cuenta_id, usuario.compania_id, compania_urlweb, compania.compania_idrel",
      "usuario
        inner join compania on usuario.compania_id = compania.compania_id
      ",
      "usuario.usuario_activo ='1' and usuario.usuario_id = '$usuario_id'");

    if (count($arrresultado)>0){
	    foreach($arrresultado as $i=>$valor){
	        $resul_usuario_id = utf8_encode($valor["usuario_id"]);      
	        $resul_cuenta_id = utf8_encode($valor["cuenta_id"]);      
	        $resul_compania_id = utf8_encode($valor["compania_id"]);    
	        $resul_compania_idrel = utf8_encode($valor["compania_idrel"]);      
	        $resul_usuario_nombre = utf8_encode($valor["usuario_nombre"]);
	        $resul_usuario_apellido = utf8_encode($valor["usuario_apellido"]);
	        $resul_usuario_email = utf8_encode($valor["usuario_email"]);
	        $resul_usuario_telf = utf8_encode($valor["usuario_telf"]);
	        $resul_usuario_clave = utf8_encode($valor["usuario_clave"]);

	        $resul_compania_nombre = utf8_encode($valor["compania_nombre"]);
	        $resul_compania_urlweb = utf8_encode($valor["compania_urlweb"]);

	        $resul_usuario = $resul_usuario_nombre." ".$resul_usuario_apellido;
	      }

	      $resul_titulonotificacion = "Nuevo Registro - $resul_compania_nombre";

	      $linkconfirmar  = $resul_compania_urlweb."iniciar-sesion?cod=$codverif";	   

	      $texto = "
        
          <table border='0' width='100%' cellpadding='0' cellspacing='0' bgcolor='ffffff' class='bg_color'>

              <tr>
                  <td align='center'>
                      <table border='0' align='center' width='590' cellpadding='0' cellspacing='0' class='container590'>
                          
                          <tr>
                              <td align='left' style='color: #343434; font-size: 16px; font-family: Calibri, sans-serif; font-weight:normal;letter-spacing: 2px; line-height: 35px;' class='main-header'>
                                  <div style='line-height: 35px'>

                                      Hola, $resul_usuario, Bienvenido a $resul_compania_nombre

                                  </div>
                              </td>
                          </tr>

                          

                      </table>

                  </td>
              </tr>         
          </table>
          
              </td>
          </tr>

      </table>
      <!-- end section -->

      
      ";

      	$notif_visible = 0;
		$tiponotificacion = 103; // 103 = Email  104 = Sistema       

		if ($tiponotificacion=="103"){ // Email, enviar de una vez

			$libemail = new LibEmail();

			$resultado = $libemail->enviarcorreo($resul_usuario_email, $resul_titulonotificacion, $texto, $resul_compania_id);
			//$resultado = $libemail->enviarcorreo("meneses.rigoberto@gmail.com", $asunto, $texto, $compania);

		}

		      
		$resul_descripcionnotificacion = utf8_decode($texto);

		$resul_descripcionnotificacion = addslashes($resul_descripcionnotificacion);

		   

		InsertarNotificacion("32", "usuario", $resul_usuario_id, $resul_titulonotificacion, $resul_descripcionnotificacion, $resul_usuario_id, $resul_usuario_id, $resul_cuenta_id, $resul_compania_id, $resul_usuario_email, $resul_usuario_telf, $notif_visible, $tiponotificacion);

		if ($clienteexterno!="1"){ // Si es registro principal de un centro
			$_SESSION[iniuser] = $resul_usuario_id;		
			$_SESSION[login] = $resul_usuario_nombre;			
			$_SESSION[perfil] = "3";
			$_SESSION[perfilactual] = $perfil;	
			$_SESSION[imguser] = "1.png";	
			$_SESSION[idcuenta] = $resul_cuenta_id;	
			$_SESSION[idcompania] = $resul_compania_id;
			$_SESSION[idcompaniarel] = $resul_compania_idrel;	
		}else{
			$_SESSION[iniuser] = $resul_usuario_id;		
			$_SESSION[login] = $resul_usuario_nombre;			
			$_SESSION[perfil] = $perfil;
			$_SESSION[perfilactual] = $perfil;	
			$_SESSION[imguser] = "1.png";	
			$_SESSION[idcuenta] = $resul_cuenta_id;	
			$_SESSION[idcompania] = $resul_compania_id;
			$_SESSION[idcompaniarel] = $resul_compania_idrel;	
		}					

		$texresp ="Registrado Correctamente";	
		//$objResponse->addAlert($texresp);				

		$objResponse->addScript("window.location = 'verificarinicio';");

		return $objResponse;

	}




	if (!$resultado){
		$texresp ="Error registrando el usuario; por favor intente de nuevo o repórtelo al email en formulario de contacto";
		$objResponse->addAlert($texresp);
		$objResponse->addScript("window.location.reload();");
	}

	return $objResponse;
}


$xajax->processRequests();
?>