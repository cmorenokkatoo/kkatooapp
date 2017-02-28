<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $app_data->title ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
                <!-- CSS -->
        <?php if(empty($app_data->css_route)){ ?>
                <link href="<?php echo base_url("assets/css/landings/fonocartera.css"); ?>" rel="stylesheet" />
        <?php }else{ ?>
            <link href="<?php echo base_url("assets/css/landings/".$app_data->css_route); ?>" rel="stylesheet" />
        <?php } ?>
        <link href="<?php echo base_url("assets/css/landings/landing.css"); ?>" rel="stylesheet" />
        <link href="<?php echo base_url("assets/css/colorbox.css"); ?>" rel="stylesheet" />
        <link href="<?php echo base_url("assets/css/dd.css"); ?>" rel="stylesheet" />
    
        <!-- FUENTES WEB -->
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">

        <!-- PARA EL SELECT DE LAS BANDERAS -->
        <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/base/jquery-ui.css" />
        <link href="<?php echo base_url("assets/css/jquery.selectBoxIt.css"); ?>" rel="stylesheet"  />

                <!-- COMPATIBILIDAD CON BROWSER VIEJOS -->
        <script src="<?php echo base_url("assets/js/vendor/modernizr-2.6.2.min.js"); ?>"></script>
        <?php if(empty($app_data->css_route)): ?>
            <!-- ESPECIAL CSS PARA LAS APLICACIONES GENERICAS -->
            <style type="text/css">
                #main-content{background:url("<?php echo base_url('public/'.$app_data->fondo_html); ?>") no-repeat;padding:55px 40px;width:1000px}
            </style>
            <link href="<?php echo base_url("assets/css/landings/generic_landing.css"); ?>" rel="stylesheet" />
        <?php endif; ?>
        <style>
        #video-container{
            cursor: default;
        }

        #video-youtube-bloque{
            cursor: pointer;
        }
        </style>
    </head>
    <body>
        <?php $this->load->view('globales/mensajes'); ?>
        <div id="main-container">
            <div id="main-content">                 
        <!-- OCULTO LOS ESTILOS DEL CONTENEDOR DE DESCRIPCIÓN -->
        <?php if(empty($app_data->description_html)){ ?>
        <style type="text/css">
            #desc-container{background: none !important; border:none !important;}
            .fa-youtube-play{font-size: 32px; vertical-align: middle; color: #DC2725;}
        </style>
<!-- Condicional para agregar elementos si es Fonocobranza -->
        <?php if($app_data->uri=='fono-cobranza'): ?>
        <!--Start of Tawk.to Script-->
<script type="text/javascript">
var $_Tawk_API={},$_Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/54cfd25f922e9e380ed878fe/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
<!-- Analytics para Fonocobranza -->
<script>
              (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
              (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
              m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
              })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
              ga('create', 'UA-57791438-1', 'auto');
              ga('require', 'linkid', 'linkid.js');
              ga('require', 'displayfeatures');
              ga('send', 'pageview');
</script>
<!-- Fin Analytics para Fonocobranza -->
<!-- Estilos para Fonocobranza -->
        <style>.button_contact{
            background: #086A87;
            float: right;
            border: 0px;
            padding: 20px;
            border-radius: 2px;
            color: #fff;
            cursor: pointer;
            margin-top: -1.5em;
            }

            .overlay-container {
             display: none;
             content: " ";
             height: 100%;
             width: 100%;
             position: absolute;
             left: 0;
             top: 0;
             background: rgba(0,0,0,.8);
             z-index: 800;
             }

             .window-container {
             display: block;
             background: #fcfcfc;
             margin: 1em auto;
             width: 550px;
             padding: 20px;
             text-align: left;
             z-index: 1000;
             border-radius: 3px;
             box-shadow: 0px 0px 30px rgba(0,0,0,0.2);
             -webkit-transition: 0.4s ease-out;
             -moz-transition: 0.4s ease-out;
             -ms-transition: 0.4s ease-out;
             -o-transition: 0.4s ease-out;
             transition: 0.4s ease-out;
             opacity: 0;
             height: auto;
             }

             .zoomin {
             -webkit-transform: scale(1.2);
             -moz-transform: scale(1.2);
             -ms-transform: scale(1.2);
             transform: scale(1.2);
             }

             .close {
             float: right;
             background: red;
             color: #fff;
             padding: 3px;
             border-radius: 2px;
             cursor: pointer;
             position: relative;
             margin-top: -1em;
             }

             .close:hover {
             
             }

             .close:active {
             
             }

            .zoomout {
            -webkit-transform: scale(0.7);
            -moz-transform: scale(0.7);
            -ms-transform: scale(0.7);
            transform: scale(0.7);
            }

            .window-container-visible {
            -webkit-transform: scale(1);
            -moz-transform: scale(1);
            -ms-transform: scale(1);
            transform: scale(1);
            opacity: 1;
            }

            /**/
            .form-contact-input{
                width: 100%;
                border: 1px solid #f0f0f0;
                padding: 5px;
                border-radius: 2px;
            }

            #form-contact-submit{
                padding: 5px;
                border-radius: 3px;
                font-size: 16px;
            }
            </style>
            <!-- Cierra Estilos para Conocobranza -->
        <input type="button" value="Contáctenos" class="button_contact" data-type="zoomin" />
            <div class="overlay-container">
                <div class="window-container zoomin">
                    <form id="formulario_fonocobranza" action="//fonocobranza.com/formulario/enviar_formulario.php" method="post" target="">
                        <label for="">Nombre Completo</label><br>
                        <input class="form-contact-input" type="text" required name="nombre"><br>
                        <label for="">Teléfono</label><br>
                        <input class="form-contact-input" type="tel" required name="telefono"><br>
                        <label for="">Email</label><br>
                        <input class="form-contact-input" type="email" required name="email"><br>
                        <label for="">Empresa (opcional)</label><br>
                        <input class="form-contact-input" type="text" name="empresa"><br>
                        <label for="">Mensaje</label><br>
                        <textarea class="form-contact-input" cols="30" rows="10" required name="mensaje"></textarea><br>
                        <input id="form-contact-submit" type="submit" value="Enviar" name="enviar">
                    </form>
                    <span class="close">Cerrar</span>
                </div>
            </div>
        <?php endif; ?>
<!-- Cierra Condicional si es fonocobranza -->
        <?php } ?>
                
                <div id="desc-container">
                    <!--<?php if(!empty($app_data->description_html)){ 
                        print $app_data->description_html;
                     }else{ ?>-->
                     
                     <?php } ?>
                    <!--ESTO VA PARA LA BASE DE DATOS  -->
                    <?php if($app_data->uri=='fonocartera-ins' or $app_data->uri=='fonoencuestas-ins' or $app_data->uri=='fonomarketing-ins' ): ?>
                </div>
                <?php endif; ?> 
                <!-- HASTA AQUI ESTO VA PARA LA BASE DE DATOS  -->
                 
                <?php if(!empty($app_data->video_html)){ ?>
                    <?php if($app_data->special == 1): ?>
                        <div id="video-container">
                            <?php if($app_data->uri=='leonisa'): ?>
                                <div id="video" style="display:">
                                    <iframe width="700" height="430" src="http://www.youtube.com/embed/<?php echo end((explode("/", $app_data->video_html)));?>" frameborder="0" allowfullscreen></iframe> 
                                </div>
                            <?php else: ?>
                                <a class="video-link">
                                    <div class="text" id="video-youtube-bloque">Ver video de demostración <i class="fa fa-youtube-play"></i></div>
                                </a>
                            <?php endif; ?>
                        </div> <!-- video-container -->
                    <?php else: ?>
                        <div id="video-container">
                            <a class="video-link">
                                <div class="text" id="video-youtube-bloque">Ver video de demostración <i class="fa fa-youtube-play"></i></div>
                            </a>
                        </div> <!-- video-container -->
                    <?php endif; ?>
                <?php  } ?>
                
                <?php if($app_data->tipo==1): ?>
                    <div id="susc-container">
                        <button id="susc-launch">SUSCRÍBETE AHORA</button>
                        <div id="form-container" style="display:"> 
                            <!--<form action="landing/save_contact_suscription" class="landing_suscribe" id="landing-suscribe">-->
                            <?php 
                                $hidden = array('app' => $app_data->id);
                                $attributes = array('name' => 'landing_suscribe', 'id' => 'landing_suscribe', 'class'=>'landing_suscribe');
                                echo form_open('landing/save_contact_suscription', $attributes, $hidden);
                            ?>
                                <input type="hidden" name="app" value="<?php echo $app_data->id;?>">
                                <input type="text" id="email" name="txt_mail_pp" id="txt-mail-pp" class="txt-field" value="" placeholder="E-MAIL">
                                <select name="indi_pais" id="indi-pais">
                                    <?php foreach($country as $county)
                                    {
                                        if((int)$county->id == 47)
                                        { ?>
                                            <option  value="<?php echo $county->id ?>" data-iconurl="<?php echo base_url("assets/img/flags/".$county->iso3.".png"); ?>" selected><?php echo $county->name; ?></option>
                                <?php   }
                                        else
                                        { ?>
                                            <option  value="<?php echo $county->id ?>" data-iconurl="<?php echo base_url("assets/img/flags/".$county->iso3.".png"); ?>"><?php echo $county->name; ?></option>
                                <?php   }                                    
                                     } ?>
                                </select>
                                <input type="text" name="phone" value="" id="phone" maxlength="10" size="10" placeholder="TELEFONO MOVIL">
                                <input type="submit" name="susc_btn" value="&gt;" id="susc-btn">
                            </form>
                        </div> <!-- form-container -->
                    </div> <!-- susc-container -->
                <?php else: ?>
                    <div id="susc-container">
                        <?php if($app_data->uri =='pymesplus'){?>
                          <a class="button btn-<?php echo $app_data->uri; ?>" href="<?php echo base_url("payment?prtrn=apps/".$app_data->uri); ?>">Recargar Saldo</a>
                          <a class="button btn-<?php echo $app_data->uri; ?>" style="margin-top: 5px;" href="<?php echo base_url("campaign"); ?>">Informes</a>
                        <?php }elseif($this->session->userdata('logged_in')){ ?>
                            <a class="button btn-<?php echo $app_data->uri; ?>" href="<?php echo base_url("apps/".$app_data->uri); ?>">Usar aplicación</a>
                        <?php }else{ ?>
                                <a class="button" href="<?php echo base_url("login/login?rtrn=/apps/".$app_data->uri); ?>">Usar aplicación</a>
                        <?php } ?>
                        <?php if($app_data->uri!='fonocartera-ins' or $app_data->uri !='fonoencuestas-ins' or $app_data->uri !='fonomarketing-ins'): ?>
                            <?php if($app_data->uri!='leonisa'): ?><a class="inline simulator_link" href="#simulator" >SIMULADOR DE PRECIOS</a><?php endif; ?>
                        <?php endif; ?>  
                    </div>
                <?php endif; ?>
                
                <div id="img2-container">
                    <?php if(($app_data->uri!='fonocartera' && $app_data->uri!='cobros-fonomarketing') && !empty($app_data->css_route) && !empty($app_data->secondary_img_html)): ?>
                        <img src="<?php print base_url('public/'.$app_data->secondary_img_html); ?>" />
                    <?php elseif($app_data->special==0 && !empty($app_data->secondary_img_html)): ?>
                        <img src="<?php print base_url('timthumb.php?src='.base_url('public/'.$app_data->secondary_img_html).'&w=480&h=480&zc=1'); ?>" />
                    <?php endif; ?>

                    <?php if($app_data->uri=='fonocartera-ins' or $app_data->uri=='fonoencuestas-ins' or $app_data->uri=='fonomarketing-ins' ): ?>
                        <!-- ESTE TEXTO SÓLO APARECERÁ EN LA APLICACION DE FONOCARTERA-INS, FONOENCUENTAS-INS Y FONOMARKETING-INS -->
                        <div id="secondary-img-footer">Aplican restricciones - Los costos de los minutos varían dependiendo del destino. <a href="#simulator" class="simulator_link_inline">Más información</a>
                        <!-- HASTA AQUÍ --> 
                    <?php endif; ?>

                    </div>
                </div>
                
                <div id="footer">
                    <?php if(!empty($app_data->footer_html)){ 
                        print $app_data->footer_html;
                     }else{ ?>
                     
                     <?php } ?>
                </div>
            </div> <!-- main-content -->
            <?php if($this->session->userdata("user_id")==KKATOO_USER OR $this->session->userdata("user_id")==KKATOO_TESTER) : ?>
                <link href="<?php echo base_url("assets/css/landings/special_user.css"); ?>" rel="stylesheet" />

                <div class="data_landing_user_kkatoo">
                    <div class="blo-k">
                       <div class="titulos"><i class="fa fa-check-square-o"></i> Título:</div>
                        <p><i class="fa fa-forward"></i> <?php echo $app_data->title ?></p>
                    </div>
                    <div class="blo-k">   
                        <div class="titulos"><i class="fa fa-check-square-o"></i> ID de usuario:</div>
                        <p><i class="fa fa-forward"></i> <?php echo $app_data->user_id ?></p>
                    </div>      
                    <hr>
                    <div class="blo-k">
                       <div class="titulos"><i class="fa fa-check-square-o"></i> Fecha de Actualización:</div>
                        <p><i class="fa fa-forward"></i> <?php echo $app_data->created ?></p>
                    </div>

                    <div class="blo-k">
                       <div class="titulos"><i class="fa fa-check-square-o"></i> Privacidad:</div>
                        <p><i class="fa fa-forward"></i> <?php echo ($app_data->private == 1)?"Privada":"Pública"; ?></p>
                    </div>

                    <div class="blo-k">
                        <div class="titulos"><i class="fa fa-check-square-o"></i> Dominio:</div>
                        <p><i class="fa fa-forward"></i> <?php echo (!empty($app_data->url_landing))?$app_data->url_landing:base_url("landing/".$app_data->uri); ?></p>
                    </div>

                    <div class="blo-k">
                        <div class="titulos"><i class="fa fa-check-square-o"></i> Ganancias:</div>
                        <p><i class="fa fa-forward"></i> <?php echo (!empty($app_data->price))?$app_data->price:"No asignado todavía" ?></p>
                    </div>
                    <div class="blo-k">
                        <div class="titulos"><i class="fa fa-check-square-o"></i> Categoría</div>
                    <p><i class="fa fa-forward"></i> 
                                        <?php echo "Categoría"; ?>
                    </p>
                    </div>
                    
                    <div class="blo-k">
                        <?php if($app_data->tipo == 1): ?>
                           <div class="titulos"><i class="fa fa-check-square-o"></i> Paquetes</div>
                           <?php if(!empty($packages)): ?>
                            <?php foreach($packages as $package): ?>
                               <span class="package"><?php echo $package->amount; ?></span>
                            <?php endforeach;?>
                            <?php endif;?>
                        <?php endif; ?>
                    </div>

                    <div class="blo-k">
                        <div class="titulos"><i class="fa fa-check-square-o"></i> Campos Dinámicos</div>
                            <table class="table" id="Tabla">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                          <th>Tipo</th>
                                          <th>Opciones</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                                            <?php if(!empty($dynamic)): ?>
                                    <?php foreach($dynamic as $dy): ?>
                                    <tr>
                                        <td><?php echo $dy->name ?></td>
                                          <td>
                                                                                <?php 
                                              switch($dy->tipo):
                                                case 1:
                                                  echo "Númerico";
                                                break;
                                                case 2:
                                                  echo "Texto";
                                                break;
                                                case 3:
                                                  echo "Fecha";
                                                break;
                                                case 4:
                                                  echo "Múltiple con una selección";
                                                break;
                                                case 5:
                                                  echo "Múltiple con múltiples selecciones";
                                                break;
                                              endswitch;
                                            ?>
                                            </td>
                                            <td><?php echo $dy->default ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                    </div>
                    <div class="blo-k">
                        <div class="titulos"><i class="fa fa-check-square-o"></i> Librería de contenido</div>
                        <div class="span7 left-column" id="content-library-list">
                            <table class="table table-striped">
                                <thead>
                                  <tr>
                                    <th>Nombre</th>
                                    <th class="center">Acción</th>
                                  </tr>
                                </thead>
                      <tbody class="topaginate_wiz topaginate">
                          <!-- ESTA PARTE ES LA QUE SE REPITE EN UN CICLO -->
                          <?php if(!empty($library)): ?>
                          
                          <?php foreach($library as $content): ?>
                          <?php if($content->content_tipo == "text"): ?>
                            <?php $text = $content; ?>
                              <tr class="content_resume content_resume_text_<?php echo $text->text_id; ?>">
                                  <td><?php echo $text->text_name; ?></td>
                                  <td class="center">
                                      <a href="javascript:;" class="item-view-ico" data-id="<?php echo $text->text_id; ?>" data-tipo="text"></a>
                                  </td>
                              </tr>
                              <tr class="content_view content_view_text_<?php echo $text->text_id; ?>" style="display: none;">
                                  <td colspan="3">
                                      <h4><?php echo $this->lang->line('nombre'); ?></h4>
                                      <p class="name_field"><?php echo $text->text_name; ?></p>
                                      <h4><?php echo $this->lang->line('the_message'); ?></h4>
                                      <p class="message"><?php echo $text->text_text; ?></p>
                                      <h4><?php echo $this->lang->line('voicena'); ?></h4>
                                      <p class="voice" data-voice-id="<?php echo $text->text_voice_id ?>"><?php echo $text->voice_name; ?> - <?php echo $text->idioma; ?></p>
                                  
                                  </td>
                              </tr>
                          <?php elseif($content->content_tipo == "audio"): ?>
                            <?php $audio = $content; ?>
                            <tr class="content_resume content_resume_audio_<?php echo $audio->audio_id; ?>">
                                  <td><?php echo $audio->audio_name; ?></td>
                                  <td class="center">
                                      <a href="javascript:;" class="item-view-ico" data-id="<?php echo $audio->audio_id; ?>" data-tipo="audio"></a>
                                  </td>
                              </tr>
                              <tr class="content_view content_view_audio_<?php echo $audio->audio_id; ?>" style="display: none;">
                                  <td colspan="3">
                                      <h4><?php echo $this->lang->line('nombre'); ?></h4>
                                      <p class="name_field"><?php echo $audio->audio_name; ?></p>
                                      <audio id="player2" src="<?php  echo base_url('public/audios') ?>/<?php echo $audio->path; ?>" type="audio/mp3" controls></audio>
                                  </td>
                              </tr>
                          <?php endif; ?>
                          <?php endforeach; ?> 
                          <?php endif; ?>
                        </tbody>
                      </table>
                      <div class="pagination">
                        <ul>
                          
                        </ul>
                      </div>
                    </div><!-- span 7 #content-library-list -->
                </div><!--Cierra el .blo-k de librería de contenidos -->
                    
                    <div class="nota blo-k">
                        <form name="observaciones" id="observaciones">
                            <div class="titulos"><i class="fa fa-check-square-o"></i> Observaciones</div>
                            <textarea name="message" placeholder="Escribe tus observaciones..." class="observaciones"></textarea>
                            <input type="hidden" name="user_id" value="<?php echo $app_data->user_id ?>" />
                            <input type="hidden" name="app_title" value="<?php echo $app_data->title ?>" />
                            <input type="hidden" name="id_app" value="<?php echo $app_data->id ?>" />
                            <input type="submit" value="Enviar" />
                        </form>
                        <div class="clearfix"></div>
                    </div>
                    <form name="make_aprove" id="make_aprove">
                        <label><i class="fa fa-forward"></i>  <strong>¿Aprobada?</strong></label>
                        <input type="checkbox" name="aprobada" id="aprobada" value="1" <?php if($app_data->aproved == 1) echo 'checked="checked"'; ?> />
                        <input type="hidden" name="user_id" value="<?php echo $app_data->user_id ?>" />
                        <input type="hidden" name="app_title" value="<?php echo $app_data->title ?>" />
                        <input type="hidden" name="id_app" value="<?php echo $app_data->id ?>" />
                        <input type="submit" style="float:none;" value="Marcar" />
                    </form>
                    <div class="clearfix"></div>
                </div>
            <?php endif; ?>
            
        </div> <!-- main-container -->

        <div id="hidden_container" style="display:none" style="width: 650px !important;">
            <?php if($app_data->tipo!=1): ?>
                <div id="simulator">
                    <h2>SIMULADOR DE PRECIOS</h2>
                      <ul>
                        <li>Elije el tipo de campaña que deseas enviar SMS o LLamada. </li>
                        <li>Escribe la cantidad de envíos que deseas realizar.</li>
                      </ul>
                    <style>
                    #midiv{
                        margin: 0em;
                        box-sizing: border-box;
                        text-align: center;
                        padding: 1em 0em;
                        outline: 1px solid #f5f5f5;
                      }
                    #midiv select, #midiv input, #midiv button, #midiv div{
                        margin: 5px 0px;
                      }
                    #valor, #midiv select{
                      padding: .424em !important;
                      border: 1px solid #000 !important;
                      position: relative !important;
                    }
                    #midiv .tipo, #midiv .numero, #midiv .precio{
                        display: inline-block;
                        padding: .1em .5em;
                        background: #f2f2f2;
                        margin: 0px 2px;
                        box-sizing: border-box;
                    }
                    #midiv span{
                                margin: 0px 10px;
                      }
                    </style>
                    <!-- <p><strong>Recuerda:</strong> El valor de llamada es por minuto.</p> -->
                    <div id="midiv">
                      <select id="dropdown">
                        <option id="calls" value="llamadas">Llamadas</option>
                        <option id="sms" value="sms">SMS</option>
                      </select>
                      <input id="valor" placeholder="" onkeyup="simulator()"/>
                      <br>
                     <div class="precio">Valor:<span id="precio"></span></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="<?php echo base_url("assets/js/numeral.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/landing.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/contactpopup.js"); ?>"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script src="<?php echo base_url("assets/js/jquery.colorbox-min.js"); ?>"></script>
    <?php if($app_data->tipo==1): ?><script src="<?php echo base_url("assets/js/selectBoxIt.js"); ?>"></script><?php endif; ?>
    <script type="text/javascript">
                  $("#dropdown").on('change', function(){
                         if($(this).val() == "sms")
                         {
                             mensajes();
                         }
                         else
                         {
                             llamadas();
                         }
                      });

                      function simulator() {
                         
                         if($("#dropdown").val() == "sms")
                         {
                             mensajes();
                         }
                         else
                         {
                             llamadas();
                         }
                      }

                      function mensajes()
                      {
                         var v = parseInt(document.getElementById("valor").value);
                         // Condicionales
                        
                         if(v >= 1 && v <= 5000){
                             var vt = v * 90;
                         }
                         if(v >= 5001 && v <= 10000){
                             var vt = v * 80;
                         }
                         if(v >= 10001 && v <= 20000){
                             var vt = v * 70;
                         }
                         if(v >= 20001){
                             var vt = v * 60;
                         }
                         
                         // document.getElementById("numero").innerHTML = v;
                         document.getElementById("precio").innerHTML = numeral(vt).format('$0,0.00') + " " + "COP";
                      }

                      function llamadas()
                      {
                          var v = parseInt(document.getElementById("valor").value);
                         // Condicionales
                        
                         if(v >= 1 && v <= 5000){
                             var vt = v * 200;
                         }
                         if(v >= 5001 && v <= 10000){
                             var vt = v * 190;
                         }
                         if(v >= 10001 && v <= 20000){
                             var vt = v * 180;
                         }
                         if(v >= 20001 && v <= 30000){
                             var vt = v * 175;
                         }
                         if(v >= 30001 && v <= 40000){
                             var vt = v * 170;
                         }
                         if(v >= 40001){
                             var vt = v * 165;
                         }
                         
                         // document.getElementById("numero").innerHTML = v;
                         document.getElementById("precio").innerHTML = numeral(vt).format('$0,0.00') + " " + "COP";
                      }
                </script>
    <script>
        $(document).ready(function(){
            <?php if($app_data->tipo==1): ?>
            var selectBox = $("#indi-pais").selectBoxIt()
            <?php endif; ?>
    
    
              $('#susc-launch').click(function(){
                $("#form-container").slideToggle();
              });
            <?php if(!empty($app_data->video_html)){ ?>
                $("a.video-link").colorbox({html:'<iframe width="700" height="430" src="http://www.youtube.com/embed/<?php echo str_replace("watch?v=","", end((explode("/", $app_data->video_html)))); ?>?autoplay=1&rel=0&wmode=transparent&modesbranding=0&showinfo=0" frameborder="0" allowfullscreen></iframe>', width:705, scrolling:false, height:460});
            <?php } ?>
    
            <?php if($app_data->tipo!=1) { ?>
                $("a.simulator_link").colorbox({inline:true, href:"#simulator" ,width:500});
                $('form[name="form_simulador"]').on('submit', function(event){
                    var _form = $(this);
                    $.post(_form.attr('action'), _form.serialize(), 
                    function(data){
                        var dat = $.parseJSON(data);
                        
                        if(dat.cod == 1){
                            $('.simulated_val').html('USD $'+dat.messa);
                            // $('#valor_simulado').html('Una llamada al destino seleccionado costaría:');
                            
                        }
                    });
                    event.preventDefault();
                });
                    
            <?php } ?>
        });
    </script>
    <?php if($this->session->userdata("user_id")==KKATOO_USER): ?>
    <script type="text/javascript" src="<?php echo base_url('assets/js/wizard'); ?>/jquery.simplePagination.js"></script>
    <script type="text/javascript">
    
        $('form[name="observaciones"]').on('submit', function(event){
            var submit = $(this).children('input[type="submit"]');
            submit.attr('disabled', true);
            submit.css('opacity', 0.7);
            $.post('<?php echo base_url('landing/ajax_send_opservations') ?>', $(this).serializeArray(), 
            function(data){
                var dat = $.parseJSON(data);
                if(dat.cod == 1){
                    $('textarea[name="message"]').val('');
                    alert("Mensaje enviado");
                }else{
                    alert("El mensaje no fue enviado");
                }
                
                submit.attr('disabled', false);
                submit.css('opacity', 1);
            });
            return false;
        });
        
        $('form[name="make_aprove"]').on('submit', function(event){
            var submit = $(this).children('input[type="submit"]');
            var checkbox = $(this).children('input[name="aprobada"]');
            
            submit.attr('disabled', true);
            submit.css('opacity', 0.7);
            
            var data =  $(this).serializeArray();
            $.post('<?php echo base_url('landing/ajax_change_status') ?>', data, 
            function(data){
                var dat = $.parseJSON(data);
                if(dat.cod == 1){
                    if(dat.messa==1)
                        checkbox.attr('checked', true);
                    else
                        checkbox.attr('checked', false);
                    var estado = (dat.messa == 1)?"Aprobada":"No Aprobada";
                    alert("Estado cambiado, nuevo estado: "+estado);
                }else{
                    alert("No se pudo actualizar el estado, por favor refresque e intentelo nuevamente. ");
                }
                submit.attr('disabled', false);
                submit.css('opacity', 1);
            });
            return false;
        });
            
        
        //VARIABLES GLOBALES QUE SERÁN UTILIZADAS EN EL JS
        var $line_nombre        = 'Nombre';
        var $line_the_message   = 'El Mensaje';
        var $line_voicena       = 'Voz para la narración:';
        var $app_data_id        = '<?php echo $app_data->id ?>';
        var $sure_delete_library= 'Seguro desea eliminar el contenido?';
        var $name_not_empty     = 'El campo Nombre no puede estar vacio.';      
        
        /**
            PAGINACIÓN PARA LA LIBRERÍA DE CONTENIDOS
        **/
        
        var pagination_ul = $('.pagination ul');
        var items = 'tbody.topaginate_wiz tr.content_resume';
        var numItemsToShow = 4;
        var numItems = $(items).length;
        var numPages = Math.ceil(numItems/numItemsToShow);
        
        
        function redraw_pagination(){
            $(items).hide();
            $(items).slice(0, numItemsToShow).fadeIn(); 
            redraw_one();
        }
        
        function redraw_one(){
            pagination_ul.pagination('destroy');
            made_pagination();
        }
        
        $(items).hide();
        $(items).slice(0, numItemsToShow).fadeIn(); 
        
        function show_elements(page){
            var beginItem = (page -1) * numItemsToShow; 
            $(items).hide();
            $('tr.content_view').hide();
            $(items).slice(beginItem, beginItem + numItemsToShow).fadeIn(); 
        }
        
        function made_pagination(){
            numItems = $(items).length;
            numPages = Math.ceil(numItems/numItemsToShow);
            pagination_ul.pagination({
                items: numItems,
                itemsOnPage: numItemsToShow,
                onPageClick: function(pageNumber, event) {
                    show_elements(pageNumber);
                }
            });
        }
        
        made_pagination();
    </script>
    <script src="<?php echo base_url('assets/js/wizard/libreria.js') ?>"></script>
    <?php endif; ?>
    </body>
</html>