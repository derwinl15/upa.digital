<?php



class LibEmail{

    

    function enviarcorreo($email = null, $asunto = null, $htmlcuerpo = null,  $compania_id=null){

     

        $conexion = new ConexionBd();



        $arrresultado = $conexion->doSelect("compania_nombre, compania_img, compania_email, compania_whatsapp, compania_urlweb",

            "compania",

            "compania_activo = '1' and compania_id = '$compania_id'");

        foreach($arrresultado as $i=>$valor){

                

            $compania_nombre = utf8_encode($valor["compania_nombre"]);

            $compania_img = utf8_encode($valor["compania_img"]);            

            $compania_email = utf8_encode($valor["compania_email"]);            

            $compania_whatsapp = utf8_encode($valor["compania_whatsapp"]);

            $compania_urlweb = utf8_encode($valor["compania_urlweb"]);



            $existeresultado = 1;



        }



        $arrresultado = $conexion->doSelect("tipolista.tipolista_id, tipolista_nombre, tipolista_descrip,

          lista.lista_id, lista_cod,

          lista.lista_nombre, listaredsocial_nombre, listaredsocial_url, listaredsocial_id



        ",

          "

          tipolista

            inner join lista on lista.tipolista_id = tipolista.tipolista_id

            left join listacuenta on listacuenta.lista_id = lista.lista_id

            and listacuenta_activo = '1' and listacuenta.tipolista_id = '59'    

            inner join listaredsocial on listaredsocial.listacuenta_id = listacuenta.listacuenta_id



          ",

          "tipolista_activo = '1' and tipolista.tipolista_id = '59' and listacuenta.compania_id = '$compania_id' ", null, "lista_orden asc");





        foreach($arrresultado as $i=>$valor){

            

          $tipolista_id = utf8_encode($valor["tipolista_id"]);

          $tipolista_nombre = utf8_encode($valor["tipolista_nombre"]);

          $tipolista_descrip = utf8_encode($valor["tipolista_descrip"]);  

          $lista_id = utf8_encode($valor["lista_id"]);

          $lista_nombre = utf8_encode($valor["lista_nombre"]);

          $lista_cod = utf8_encode($valor["lista_cod"]);

          $listaredsocial_nombre = utf8_encode($valor["listaredsocial_nombre"]);

          $listaredsocial_url = utf8_encode($valor["listaredsocial_url"]);

          $listaredsocial_id = utf8_encode($valor["listaredsocial_id"]);

          $nombrered = strtolower($lista_nombre);



          if ($lista_cod=="1"){

            $nombrefacebook = $listaredsocial_nombre;

            $linkfacebook = $listaredsocial_url;

          }



          if ($lista_cod=="2"){

            $nombreinstagram = $listaredsocial_nombre;

            $linkinstagram = $listaredsocial_url;

          }



        }





        if ($compania_email==""){

            $compania_email = "info@misistemaweb.com";

        }

/*

        $urloriginal = "https://www.gestiongo.com/";

        $urlweb = "https://www.gestiongo.com";

        $urllogo = "https://www.gestiongo.com/assets/img/logo.png";

        $nombrecompania = "GestionGo";

        $urldesuscribir = "https://www.gestiongo.com/desuscribir?email=$email";

        $emailcompania = "info@gestiongo.com";

        $emailcompaniareply = "info@gestiongo.com";

        $facebook ="https://www.facebook.com/gestiongoapp";

        $instagram ="https://www.instagram.com/gestiongoapp";

*/

        if ($existeresultado=="1"){



            $urlweb = $compania_urlweb;

            $urllogo = $compania_urlweb."admin/arch/$compania_img";

            $nombrecompania = $compania_nombre;

            $urldesuscribir = $compania_urlweb."desuscribir?email=$email";

            $emailcompania = $compania_email;

            $emailcompaniareply = $compania_email;

            $facebook = $linkfacebook;

            $instagram = $linkinstagram;



        }



        

        $html = "

        <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>

        <html xmlns:v='urn:schemas-microsoft-com:vml'>

        <head>

            <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

            <meta name='viewport' content='width=device-width; initial-scale=1.0; maximum-scale=1.0;' />

            <meta name='viewport' content='width=600,initial-scale = 2.3,user-scalable=no'>

            <!--[if !mso]><!-- -->

            <link href='https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700' rel='stylesheet'>

            <link href='https://fonts.googleapis.com/css?family=Quicksand:300,400,700' rel='stylesheet'>

            <!-- <![endif]-->



            <title>$nombrecompania</title>



            <style type='text/css'>

                body {

                    width: 100%;

                    background-color: #ffffff;

                    margin: 0;

                    padding: 0;

                    -webkit-font-smoothing: antialiased;

                    mso-margin-top-alt: 0px;

                    mso-margin-bottom-alt: 0px;

                    mso-padding-alt: 0px 0px 0px 0px;

                }



                p,

                h1,

                h2,

                h3,

                h4 {

                    margin-top: 0;

                    margin-bottom: 0;

                    padding-top: 0;

                    padding-bottom: 0;

                }



                span.preheader {

                    display: none;

                    font-size: 1px;

                }



                html {

                    width: 100%;

                }



                table {

                    font-size: 14px;

                    border: 0;

                }

                /* ----------- responsivity ----------- */



                @media only screen and (max-width: 640px) {

                    /*------ top header ------ */

                    .main-header {

                        font-size: 20px !important;

                    }

                    .main-section-header {

                        font-size: 28px !important;

                    }

                    .show {

                        display: block !important;

                    }

                    .hide {

                        display: none !important;

                    }

                    .align-center {

                        text-align: center !important;

                    }

                    .no-bg {

                        background: none !important;

                    }

                    /*----- main image -------*/

                    .main-image img {

                        width: 440px !important;

                        height: auto !important;

                    }

                    /* ====== divider ====== */

                    .divider img {

                        width: 440px !important;

                    }

                    /*-------- container --------*/

                    .container590 {

                        width: 440px !important;

                    }

                    .container580 {

                        width: 400px !important;

                    }

                    .main-button {

                        width: 220px !important;

                    }

                    /*-------- secions ----------*/

                    .section-img img {

                        width: 320px !important;

                        height: auto !important;

                    }

                    .team-img img {

                        width: 100% !important;

                        height: auto !important;

                    }

                }



                @media only screen and (max-width: 479px) {

                    /*------ top header ------ */

                    .main-header {

                        font-size: 18px !important;

                    }

                    .main-section-header {

                        font-size: 26px !important;

                    }

                    /* ====== divider ====== */

                    .divider img {

                        width: 280px !important;

                    }

                    /*-------- container --------*/

                    .container590 {

                        width: 280px !important;

                    }

                    .container590 {

                        width: 280px !important;

                    }

                    .container580 {

                        width: 260px !important;

                    }

                    /*-------- secions ----------*/

                    .section-img img {

                        width: 280px !important;

                        height: auto !important;

                    }

                }

            </style>

            <!-- [if gte mso 9]><style type=”text/css”>

                body {

                font-family: arial, sans-serif!important;

                }

                </style>

            <![endif]-->

        </head>





        <body class='respond' leftmargin='0' topmargin='0' marginwidth='0' marginheight='0'>

            <!-- pre-header -->

            <table style='display:none!important;'>

                <tr>

                    <td>

                        <div style='overflow:hidden;display:none;font-size:1px;color:#ffffff;line-height:1px;font-family:Arial;maxheight:0px;max-width:0px;opacity:0;'>

                            $nombrecompania

                        </div>

                    </td>

                </tr>

            </table>

            <!-- pre-header end -->

            <!-- header -->

            <table border='0' width='100%' cellpadding='0' cellspacing='0' bgcolor='ffffff'>



                <tr>

                    <td align='center'>

                        <table border='0' align='center' width='590' cellpadding='0' cellspacing='0' class='container590'>



                            <tr>

                                <td align='center'>



                                    <table border='0' align='center' width='590' cellpadding='0' cellspacing='0' class='container590'>



                                        <tr>

                                            <td align='center' height='70' style='height:70px;'>

                                                <a href='$urlweb' style='display: block; border-style: none !important; border: 0 !important;'><img width='300' border='0' style='display: block; width: 300px;' src='$urllogo' alt='$nombrecompania' /></a>

                                            </td>

                                        </tr>



                                        <tr  >

                                            <td align='center' style='border-top: 3px solid #0EB521'>

                                               

                                            </td>

                                        </tr>

                                    </table>

                                </td>

                            </tr>



                            <tr>

                                <td height='25' style='font-size: 25px; line-height: 25px;'>&nbsp;</td>

                            </tr>



                        </table>

                    </td>

                </tr>

            </table>

            <!-- end header -->





            $htmlcuerpo







            <!--  50% image -->

            

            <!--  50% image -->

            



            <!-- contact section -->

            <table border='0' width='100%' cellpadding='0' cellspacing='0' bgcolor='000' class='bg_color'>



               





                <tr>

                    <td height='30' style='border-top: 1px solid #000;font-size: 30px; line-height: 30px;'>&nbsp;</td>

                </tr>



                <tr>

                    <td align='center'>

                        <table border='0' align='center' width='590' cellpadding='0' cellspacing='0' class='container590 bg_color'>



                            <tr>

                                <td>

                                    <table border='0' width='590' align='left' cellpadding='0' cellspacing='0' style='border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;' class='container590'>



                                        





                                        <tr>

                                            <td align='left' style='color: #FFF; font-size: 11px; font-family:  Calibri, sans-serif; line-height: 23px; padding-left: 10px' class='text_color'>

                                                <div style='color: #FFF; font-size: 11px; font-family: Calibri, sans-serif; mso-line-height-rule: exactly; line-height: 23px;'>

                                                    Has recibido este email porque te encuentras suscrito al newsletter de $nombrecompania o porque te has registrado en nuestra plataforma.



                                                   



                                                </div>

                                            </td>

                                        </tr>

                                        <tr class='hide'>

                                            <td height='25' style='font-size: 25px; line-height: 25px;'>&nbsp;</td>

                                        </tr>                                        

                                    </table>







                                    <table border='0' width='600' align='right' cellpadding='0' cellspacing='0' style='border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; padding-left: 10px' class='container590'>





                                        <tr>

                                            <td align='left' style='padding-left: 10px'>

                                                <a href='$urlweb' style='display: block; border-style: none !important; border: 0 !important;'><img width='170' border='0' style='display: block; width: 170;' src='$urllogo' alt='$nombrecompania' /></a>

                                                

                                                <div style='color: #333333; font-size: 14px; font-family: Calibri, sans-serif; font-weight: 600; mso-line-height-rule: exactly; line-height: 23px; '>

                                                    

                                                    <a href='mailto:$emailcompania' style='color: #fff; font-size: 14px; font-family: Calibri, Sans-serif; font-weight: 400;'>$emailcompania</a>



                                                </div>

                                            </td>

                                            <td>

                                                <table border='0' align='right' cellpadding='0' cellspacing='0'>

                                                    <tr>

                                                        <td>

                                                            <a href='$instagram' style='display: block; border-style: none !important; border: 0 !important; '><img width='40' border='0' style='display: block;' src='https://www.gestiongo.com/assets/img/facebook.png' alt=''></a>

                                                        </td>

                                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>

                                                        <td>

                                                            <a href='$facebook' style='display: block; border-style: none !important; border: 0 !important;'><img width='40' border='0' style='display: block;' src='https://www.gestiongo.com/assets/img/instagram.png' alt=''></a>

                                                        </td>

                                                        

                                                    </tr>

                                                </table>

                                            </td>

                                        </tr>



                                    </table>

                                </td>

                            </tr>

                        </table>

                    </td>

                </tr>



                <tr>

                    <td height='20' style='font-size: 20px; line-height: 20px;'>&nbsp;</td>

                </tr>



            </table>

            <!-- end section -->



            <!-- footer ====== -->

            



        </body>



        </html>

                ";

    

        $to      = $email;

        $subject = $asunto;

                        

        $message = $html;

        $headers = "MIME-Version: 1.0" . "\r\n";

        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        $headers .= 'From: '.$nombrecompania.' '.$emailcompaniareply.' ' . "\r\n" .

                'Reply-To: '.$nombrecompania.' '.$emailcompaniareply.' ' . "\r\n" .

                'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);

        /*

       



        mail("meneses.rigoberto@gmail.com", $subject, $message, $headers);



        mail($compania_email, $subject, $message, $headers);        
*/
        

        return true;

    

    }

    

        

}

?>