<?php
	$this->load->view('globales/head_step2.php');
	$this->load->view('globales/mensajes');
	//echo var_dump($marcado->hijos);
	$id		 = $app->id;
?>

	 <style type="text/css">
 /*  #app-container{
    padding: 3rem 0rem;
   }*/
	 	form{
			margin:0;
		}
    h1#app-name{
      font-size: 22px;
    }
    #header-interno-navbar{
    display: block;
    position: relative;
    box-sizing: border-box;
  }
  #header-interno-navbar div{

    position: relative;

    display: inline-table;
    box-sizing: border-box;
    vertical-align: middle;
  }
  #brand-container{
    left: 10px;
  }
  #imagen-paso{
          display: inline-table;
          padding: 0em 2em;
        }
        #imagen-paso i, #imagen-paso h3{
          display: inline-table;
          vertical-align: middle;
          color: white;
        }
        #RunTour2{
          display: inline-table;
          width: 580px;
          margin: 10px auto;
          background: rgba(255,255,255,.4);
          padding: 1em;
          box-sizing: border-box;
          text-align: justify;
          border-radius: 5px;
          position: relative;
          vertical-align: middle;
        }
        #RunTour2 button{
          border: 0px;
          background: rgb(190,1,119);
          color: #fff;
          padding: .3em .7em;
          box-sizing: border-box;
          border-radius: 3px;
          position: absolute;
          right: 15px;
          top: 45px;}

        .doble{
          margin: 10px auto;
          position: relative
          outline: 1px solid red;
          position: table;
          width: 800px;
        }

        .btn-guardar-narracion{
          width: 125px !important;
          margin: 0 auto;
          padding: 8px;
          background: green !important;
        }
        .btn-guardar-narracion:hover{
          background: darkgreen !important;
        }
        #taComentario{
          border-radius: 0px;
          border: 3px solid rgb(220,220,220);
          width: 90%;
          height: 150px;
        }

        .nav-tabs i{
          cursor: pointer;
        }

        .nav-tabs i:hover{
          color: gray;
        }
        #mainpop{
  position: fixed;
  background: rgba(0,0,0,.7);
  width: 100%;
  height: 100%;
  top: 0px;
  left: 0px;
  z-index: 999999;
  transition: all 2s ease-in;
}
#PopUpOut{
  background: #fff;
  width: 30%;
  box-sizing: border-box;
  height: 400px;
  padding: 3em;
  border-radius: 3px;
  box-shadow: 1px 1px 4px 1px rgba(100,100,100,.4);
}
.cerrar{
    color: white;
    font-size: 1rem;
    /*background: rgb(60,179,113);*/
    padding: 5px;
    margin: 0px 120px;
  }
  .cerrar:hover{
    color: white;
    text-decoration: none;
  }
.cerrar i{
    vertical-align: middle !important;
  }
#cbo_datos, #cbo_vozmini{
  width: 90%;
  border-radius: 0px;
  margin: 15px auto;
}
.inicialdata:hover{
  color: steelblue;
  vertical-align: middle;
}
	 </style>
   <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<header id="header-steps">
            <div id="header-content">
            <div id="brand" class="header-element">
                  <?php 
                    // $logo = $this->specialapp->create_logo('logo_principal_mv.jpg');
                   ?>
                  <!-- <a class="brand" href="<?php echo $logo->brand_url; ?>">
                    <img src="<?php echo  $logo->brand_img ?>" alt="Mensajes de Voz"/>
                  </a> -->
                  <h1>Mensajes de Voz</h1>
            </div>
                <!-- Opciones de navegación -->
                <?php
                  if($this->session->userdata('logged_in')):
                ?>
              <div id="username">
              <span id="nombre-usuario"><?php echo ("<b>" . $username . "</b>"); ?></span>
                <i class="material-icons" id="ico-username">settings</i>

              </div>
                <div id="saldo">
                    <i class="material-icons" id="ico-saldo">monetization_on</i>
                    <span id="valor-saldo"><?php echo  number_format($credits,0,",",".");?></span>
                    <span id="texto-saldo">créditos</span>
                </div>
                <?php 
                  endif;
                 ?>
      </div><!-- /header-content -->
</header>
<?php if($this->session->userdata('logged_in')){ ?>
<div class="newNav" id="newNav">
  <ul>
    <li class="item-main-menu"><a href="<?php echo base_url('campaign'); ?>"><i class="material-icons">folder_open</i><span>Campañas</span></a></li>
    <li class="item-main-menu"><a href="<?php echo base_url('contacts/contact_manager'); ?>"><i class="material-icons">account_circle</i><span>Contactos</span></a></li>
   <?php if(!$this->permissions->get('deny_marketplace')):?>
    <li class="item-main-menu"><a href="<?php echo base_url('marketplace'); ?>"><i class="material-icons" id="material-module">view_module</i><span>Aplicaciones</span></a></li>
  <?php endif; ?>
  <li class="item-main-menu"><a href="<?php echo base_url('payment'); ?>"><i class="material-icons">monetization_on</i> <span>Recargar Saldo</span></a></li>
  <?php if($this->session->userdata("user_id")==KKATOO_USER):?>
     <li class="item-main-menu"><a href="<?php echo base_url('user/apps'); ?>"><i class="material-icons">check_circle</i> <span>Admin App</span></a></li>
     <li class="item-main-menu"><a href="<?php echo base_url('admin/recents/'); ?>"><i class="material-icons">check_circle</i> <span>Supe Apps</span></a></li>
     <li class="item-main-menu"><a target="_blank" href="<?php echo base_url("wizard/newapp/subs"); ?>"><i class="material-icons">check_circle</i> <span>App Sus</span></a></li>
     <li class="item-main-menu"><a target="_blank" href="<?php echo base_url("wizard/newapp/dif"); ?>"><i class="material-icons">check_circle</i> <span>App Dif</span></a></li>
    <?php endif; ?>
    <li><a href="<?php echo base_url("login/logout"); ?>"><i class="material-icons">power_settings_new</i> <span>Salir</span></a></li>
    <!-- <li class="color" data-color="yellow"><a href="#" style="width: 24px; height: 24px; border: 1px solid white; background: yellow;"></a></li>
    <li class="color" data-color="#681508"><a href="#" style="width: 24px; height: 24px; border: 1px solid white; background: #681508;"></a></li> -->
    <?php }else{
          ?>
    <li><a href="<?php echo base_url("login/login"); ?>?rtrn=<?php echo str_replace("/".KKATOO_ROOT."/","",$_SERVER['REQUEST_URI']); ?>"><?php echo $this->lang->line('login'); ?></a></li>
    <li><a href="<?php echo base_url("login/register"); ?>"><?php echo $this->lang->line('register'); ?></a></li>
    <?php
      } 
    ?>                                
  </ul>
</div>
<div class="doble">
  <div id="imagen-paso">
    <h3>Paso2</h3>
  </div>

  <div id="RunTour2">
    <p>Aprende rápidamente cómo comenzar a configurar tu mensaje. Haz click en el botón Iniciar Tour para guiarte paso a paso</p>
    <button id="IniciarTour">Iniciar Tour</button>
  </div>
</div>

      <!-- CONTAINER DE LA APLICACION -->
      <div id="app-container" class="container">
         <!-- HEADER DE LA APLICACION -->
         <div class="row">
            <div class="span12" id="app-header">
               <div class="row">
                  <!-- COLUMA 1 Imagen, nombre y descripción de la aplicación -->
                  <div class="span6" id="app-name-and-desc">
                        <div class="app-image">
                            <img width="60" id="app-logo" src="<?php print base_url("public/".$app->image); ?>" alt="" >
                        </div>
                        <h1 id="app-name"><?php echo $app->title; ?></h1>
                        <!-- <div id="app-description">
                           <?php echo $app->description; ?>
                        </div>  -->
                  </div> <!-- #app-name-and-desc -->

                  <!-- COLUMA 2 Formulario de nombre de la campaña -->
                  <div class="span6">
                     <div id="frm-campaign-name" style="text-transform: uppercase;">
                        <div class="control-group">
                           <label for="txt-campaign-name">
                           <?php echo "<span style='font-weight: 300; color: #656a71;'>#" .$id_campaign . "</span> ". $name_campaign; ?>
                           </label>
                        </div>
                     </div>
                  </div> <!-- .span6 -->

               </div> <!-- .row -->
            </div> <!-- #app-header -->
         </div> <!-- .row -->


         <!-- PESTAÑAS PASOS -->
         <div class="row" id="tabs">
            <div class="span12">
               <!-- PESTAÑAS -->
               <ul class="nav nav-tabs" id="steps-tabs">
                  <li><a href="<?php echo base_url('apps/'.$app->uri); ?>" ><?php echo $this->lang->line('step1'); ?></a></li>
                  <li><a href="#message-config" id="segundo" data-toggle="tab"><?php echo $this->lang->line('step2'); ?></a></li>
                  <li><a data-step="10" data-intro="Ve al siguiente paso para configurar la fecha y hora de tu campaña" href="<?php echo base_url('apps/'.$app->uri.'/3'); ?>"><?php echo $this->lang->line('step3'); ?></a></li>
               </ul>
               <!-- CONTENIDOS PESTAÑAS -->
               <div class="tab-content" id="tab-content">
                  <!-- PESTAÑA 1 *************************************************-->
                  <div class="tab-pane active" id="message-config" >
                  		 <?php if(!empty($intro)){ ?>
                          <div class="preview-content" id="pre-cont-au"  style="font-weight:400">
                              <strong>Mensaje de Introducción.</strong><br />
                              <audio id="player_intro" src="<?php echo $intro->path; ?>" type="audio/*" controls="controls" style="margin-top:10px;"></audio>
                          </div>
                        <?php } ?>

                        <!-- MENSAJE CONFIGURADO (SI APLICA) -->
                        <?php if($audiocampaign){ ?>
                          <div class="preview-content" id="pre-cont-au">
                              <strong>Mensaje principal</strong><br />
                              <audio id="player2" src="<?php echo base_url('public/audios');if(!empty($audiocampaign)): echo '/'.$audiocampaign->path; endif;?>" type="audio/*" controls="controls" style="margin-top:10px;"></audio>
                          </div>
                        <?php } ?>

                        <?php
                          if(isset($textcampaign)){
                            if(isset($textcampaign->text_speech)){
                                echo '<div class="preview-content" id="pre-cont-text">';
                                echo 'El mensaje actualmente configurado para narrar es:<br><div class="texto"><strong>"' . $textcampaign->text_speech.'"</strong></div>';
                                echo '</div>';
                            } else {
                                // echo '<div class="preview-content" id="pre-cont-text">';
                                // echo '<div class="texto"></div>';
                                // echo '</div>';
                            }
                          } else {
                            // echo '<div class="preview-content" id="pre-cont-text">';
                            // echo '<div class="texto"></div>';
                            // echo '</div>';
                          } ?>


                     <?php if(!empty($cierre)){ ?>
                      <div class="preview-content" id="pre-cont-au">
                          <strong>Mensaje de Cierre</strong><br />
                          <audio id="player_cierre" src="<?php echo $cierre->path; ?>" type="audio/*" controls="controls" style="margin-top:10px;"></audio>
                      </div>
                    <?php } ?>




                     <!-- HEADER PESTANA 1-->
                     <div class="row" id="messages-header">
                        <div class="span6"><h2><?php echo $this->lang->line('configmessage'); ?></h2></div>

                        <div class="span5" id="messages-links">
                           <!-- <div id="messages-admin-ico"><a href="###ADMIN AUDIOS"></a></div>
                           <div id="messages-simulation-ico"><a href="#HACER SIMULACIÓN"></a></div> -->
                        </div>
                     </div> <!-- .row -->

                      <!-- SECCION DE GRABACION DE INTRO -->
                      <?php if($app->intro==1): ?>
                      <div id="intro-recording" style="display:;">
                         <h3>Grabar intro</h3>
                         <p style="margin: 0 40px;">Puedes grabar un mensaje de un máximo de 10 segundos para invitar a tus contactos a que escuchen el mensaje pregrabado de la aplicación.</p>
                          <div style="overflow:hidden;margin: 5px 40px;">
                            <?php
								$the_url_record = base_url('apps/save_intro');
								$the_url_record = urlencode($the_url_record);
							?>
                            <object width="100%" height="250" type="application/x-shockwave-flash" data="<?php echo base_url('assets/swf') ?>/kkatoo-audio-recorder.swf?saveurl=<?php echo $the_url_record ?>&id_wapp=<?php echo $app->id ?>&cache=<?php echo time(); ?>&function=real_redirect">
                                <param name="movie" value="<?php echo base_url('assets/swf') ?>/kkatoo-audio-recorder.swf?saveurl=<?php echo $the_url_record ?>&id_wapp=<?php echo $app->id ?>&cache=<?php echo time(); ?>&function=real_redirect" />
                                <param name="flashvars" value="saveurl=<?php echo $the_url_record ?>&id_wapp=<?php echo $app->id ?>&function=real_redirect">
                                <param name="quality" value="high" />
                                <param name="wmode" value="" />
                            </object>
                          </div>
                      </div>
                      <?php endif; ?>

					<div class="clearfix"></div>

                     <!-- GRUPO DE TABS DE MENSAJE INICIAL -->
                     <div id="msg-initial">
                        <h3>Mensaje a emitir en la llamada</h3>

                        <?php if($this->permissions->get('request_special_audio')): ?>
                            <!--PEDIR AUDIO-->
                            <div class="request_audio">
                                <p><?php echo $this->lang->line('request_audio_text'); ?></p>
                                <input type="button" id="request_audio" class="btn" name="request_audio" data-iduser="<?php echo $this->session->userdata('user_id') ?>" data-idapp="<?php echo $app->id ?>" value="<?php echo $this->lang->line('request_audio_button'); ?>" />
                                <div class="clearfix"></div>
                            </div>
                             <!--/PEDIR AUDIO-->
                        <?php endif; ?>


                        <!-- BIBLIOTECA DE CONTENIDOS -->
                        <div id="biblioteca" class="a_section" style="padding: 0px 20px;">
                            <div class="row-fluid">
                                <!-- 1a COLUMNA -->
                                <div class="<?php if($app->text_speech == 1 || $app->record_audio == 1 || $app->upload_audio == 1) echo 'span7' ?> left-column" id="content-library-list" data-step="6" data-intro="Cada audio o texto que generes se listará en este espacio y estará siempre activo para futuras campañas a menos que desees borrarlo.">

                                    <p class="subtitulo">
                                        Biblioteca de Contenidos
                                    </p>

                                    <div class="clearfix"></div>

                                    <form name="admin_contents" id="admin_contents" method="post" action="">
                                        <table class="table table-striped" >
                                            <thead>
                                                <tr>
                                                  <!--<th><input type="checkbox" class="check-all" name="check-all"></th>-->
                                                  <th>Nombre</th>
                                                  <th class="center">Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody class="topaginate topaginate_wiz">
                                                <!-- ESTA PARTE ES LA QUE SE REPITE EN UN CICLO -->
                                                <?php if($app->use_audio == 1): ?>

													                        <?php if(!empty($library)): ?>

                                                    <?php foreach($library as $content): ?>
                                                    <?php if($content->content_tipo == "text"): ?>
                                                        <?php $text = $content; ?>
                                                        <tr class="content_resume content_resume_text_<?php echo $text->text_id; ?>">
                                                            <td class="center" style="display:none; visibility">
                                                                <input type="hidden" name="check_content" value="<?php echo $text->text_id; ?>_<?php echo $text->content_tipo ?>" class="chk-select">
                                                            </td>
                                                            <td>
                                                            <?php echo $text->text_name; ?>
                                                            </td>
                                                            <td class="center">
                                                            <a href="javascript:;" title="<?php echo $this->lang->line('add_content'); ?>" data-step="7" data-intro="Para seleccionar el mensaje que usarás en tu campaña haz click en este botón" class="telefono" data-content="<?php echo $text->text_id; ?>_<?php echo $text->content_tipo ?>"><i class="material-icons">dialpad</i></a><a href="javascript:;" title="Editar mensaje de audio" class="item-edit-ico" data-id="<?php echo $text->text_id; ?>" data-tipo="text"><i class="material-icons">mode_edit</i></a><a href="javascript:;" title="Reproducir audio" class="item-view-ico" data-id="<?php echo $text->text_id; ?>" data-tipo="text"><i class="material-icons">play_arrow</i></a><a href="#" class="item-delete-ico" title="Eliminar audio" data-id="<?php echo $text->text_id; ?>" data-tipo="text"><i class="material-icons">delete_forever</i></a>
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
                                                            <td class="center" style="display:none; visibility">
                                                                <input type="hidden" name="check_content" value="<?php echo $audio->audio_id; ?>_<?php echo $audio->content_tipo ?>" class="chk-select">
                                                            </td>
                                                            <td><?php echo $audio->audio_name; ?></td>
                                                            <td class="center">
                                                            	<a href="javascript:;" class="telefono" title="<?php echo $this->lang->line('add_content'); ?>" data-content="<?php echo $audio->audio_id; ?>_<?php echo $audio->content_tipo ?>"><i class="material-icons">dialpad</i></a><a title="Editar mensaje de audio" href="javascript:;" class="item-edit-ico" data-id="<?php echo $audio->audio_id; ?>" data-tipo="audio"><i class="material-icons">mode_edit</i></a><a title="Reproducir audio" href="javascript:;" class="item-view-ico" data-id="<?php echo $audio->audio_id; ?>" data-tipo="audio"><i class="material-icons">play_arrow</i></a><a href="javascript:;" class="item-delete-ico" title="Eliminar audio" data-id="<?php echo $audio->audio_id; ?>" data-tipo="audio"><i class="material-icons">delete_forever</i></a>
                                                           </td>
                                                        </tr>
                                                        <tr class="content_view content_view_audio_<?php echo $audio->audio_id; ?>" style="display: none;">
                                                            <td colspan="3">
                                                                <h4><?php echo $this->lang->line('nombre'); ?></h4>
                                                                <p class="name_field"><?php echo $audio->audio_name; ?></p>
                                                                <audio id="player2" src="<?php  echo base_url('public/audios') ?>/<?php echo $audio->path; ?>" type="audio/*" controls></audio>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                    <?php endforeach; ?>
                                                    <?php endif; ?>
                                            	<?php endif; ?>
                                            </tbody>
                                        </table>
                                        <input type="hidden" name="check_content" value="" />
                                        <input type="hidden" name="id_campaign" class="id_campaign" value="<?php echo $id_campaign; ?>" />
                                        <input type="hidden" name="arbol" value="" class="uploadsmall-arbol">
                                    </form>
                                    <div class="pagination_wiz pagination" style="text-align: center;">
                                        <ul>
                                           <!--Pagination-->
                                        </ul>
                                    </div>
                                </div><!-- span 7 #content-library-list -->


                                <?php if($app->text_speech == 1 || $app->record_audio == 1 || $app->upload_audio == 1): ?>
                                <!-- 2a COLUMNA -->
                                <div class="span5 right-column" id="content-library-tabs">
                                    <p class="subtitulo">Grabar nuevos contenidos</p>
                                    <div class="tabbable tabbable-bordered tabs-left">
                                        <ul class="nav nav-tabs" style="position: relative !important;">
                                            <?php if($app->text_speech==1): ?>
                                            <li class="active"><a href="#narrar"  data-toggle="tab" id="tab-narrar" title="Configurar mensaje de audio" ><i class="material-icons">chat</i></a></li>
                                            <?php endif; ?>
                                            <?php if($app->upload_audio==1): ?>
                                            <li <?php if($app->text_speech==0) echo 'class="active"' ?>><a title="Subir audio" href="#subir" data-toggle="tab" id="tab-subir" ><img data-step="4" data-intro="Desde aquí puedes subir un audio que tengas ya grabado en tus archivos" src="<?php echo base_url('assets/img') ?>/ico-upload-audio-24.png" alt="Narrar"></a></li>
                                            <?php endif; ?>
                                            <?php if($app->record_audio==1): ?>
                                            <li <?php if($app->text_speech==0 && $app->upload_audio==0) echo 'class="active"' ?>><a title="Grabar audio" href="#grabar" data-toggle="tab" id="tab-grabar" ><img data-step="5" data-intro="Desde aquí puedes grabar un audio en línea" src="<?php echo base_url('assets/img') ?>/ico-rec-audio-24.png" alt="Narrar"></a></li>
                                            <?php endif; ?>
                                            <!-- <li><a href="#seleccionar" data-toggle="tab" id="tab-seleccionar" ><img src="../<?php echo base_url('assets/img') ?>/ico-choose-audio-24.png" alt="Narrar"></a></li> -->
                                        </ul>
                                        <div class="tab-content">
                                            <!-- TAB NARRAR -->
                                            <?php if($app->text_speech==1): ?>
                                            <div class="tab-pane active" id="narrar">
                                                <?php
                                                    //OPEN TEXT SPEECH FORM
                                                    $attributes = array('name' => 'form-text-speach', 'id' => 'form-text-speach');
                                                    echo form_open('wizard/ajax_save_text_speach', $attributes);
                                                ?>
                                                    <p><?php echo $this->lang->line("messagena"); ?></p>
																										<?php if($app->id =='327'){?>
<style>
    #TarjetaFlotante{
        background: white;
        width: 460px;
        height: height;
        color: #303030;
        box-sizing: border-box;
        padding: 1.3rem;
        position: fixed;
        bottom: 25px;
        left: 5px;
        text-align: left;
        box-shadow: rgba(0,0,0,.2) 1px 1px 1px 1px;
        border-top: 4px solid #8FD7EA;
        z-index: 999 !important;
    }
    #TarjetaFlotante h4{
      font-weight: 300;
      font-size: 32px;
      color: silver;
    }
    #TarjetaFlotante hr{
      border-bottom: 1px solid silver;
      border-top: 0px;
      border-right: 0px;
      border-left: 0px;
      padding: .5rem;
      height: 0px;
      width: 100%;
      margin: .5rem auto;
    }
    #CerrarTarjeta{
      color: black;
      text-decoration: none;
      cursor: pointer;
      text-align: right;
      position: absolute;
      right: 30px;
    }
    #CerrarTarjeta:hover{
      text-decoration: none;
      cursor: pointer;
      color: lightblue;
      
    }
    #BotonTarjeta{
      width: auto;
      padding: 1rem;
      background: gold;
      color: white;
      text-transform: uppercase;
      position: fixed;
      bottom: 50px;
      left: 5px;
      text-decoration: none;
      cursor: pointer;
      color: black;
    }
    #BotonTarjeta:hover{
      text-decoration: none;

    }
</style>
<div id="TarjetaFlotante">

    <h4>Caracteres permitidos <a href="#" id="CerrarTarjeta"><i class="material-icons">clear</i></a></h4>
    <hr>
    <p>Para asegurar que todos los teléfonos celulares reciban correctamente los mensajes, los caracteres que actualmente soportamos son los siguientes:
    <p>Letras: a-z A-Z (sin tildes, ñ o Ñ)</p>
  <p>Números: 0-9</p>
  <p>Símbolos:$ “ % ! ? & / \ ( ) < > = @ # + * _ - : ; , .</p>
</div>
<a id="BotonTarjeta">Mostrar Notificación</a>
																											<textarea placeholder="" id="taComentario" maxlength="160"  name="txt_msg_to_speech" id="txt_narrar_small" onKeyDown="cuenta()" onKeyUp="cuenta()"></textarea>
																											<p style="color:steelblue; font-size: 16px; font-weight: 300;">Límite de caracteres para SMS: <span id="contadorTaComentario" style="color:green; font-weight: 400; text-align: center;">0/160</span></p>
																											<?php } ?>
																											<?php if($app->id !='327'){?>
                                                        <textarea id="taComentario"  name="txt_msg_to_speech" id="txt_narrar_small" onKeyDown="cuenta()" onKeyUp="cuenta()"></textarea>
  																										<?php } ?>
                                                    <br />
                                                    <!--  -->

                                                    <p>Usar campos personalizados:<?php //echo $this->lang->line('datomessage'); ?></p>
                                                    <select data-step="2" data-intro="Al configurar tu mensaje puedes personalizarlo, insertando los campos que aparecen en esta lista." id="cbo_datos" name="cbo-datos" class="input-block-level">
                                                       <option value="dato" disabled selected ><?php echo $this->lang->line('dato'); ?></option>
                                                       <option value="name"><?php echo $this->lang->line('name'); ?></option>
                                                       <?php if(!empty($dynamic)){ ?>
                                                         <?php foreach($dynamic as $fiel){ ?>
                                                         <option value="<?php echo $fiel->name_fields; ?>"><?php echo $fiel->name; ?></option>
                                                         <?php } ?>
                                                       <?php } ?>
                                                    </select>

                                                    <!-- <br /> -->
                                                    <?php if($app->id !='327'){?>
                                                    <p><?php echo $this->lang->line('voicena'); ?></p>
                                                    <select data-step="3" data-intro="En caso de que tu campaña sea en llamada, elige la voz con la que quieres que el mensaje sea narrado. Puedes elegir entre masculino y femenino." id="cbo_vozmini" name="cbo-vozmini" class="input-block-level">
                                                        <option value="0" disabled selected><?php echo $this->lang->line('voice'); ?></option>
                                                        <?php foreach($voice as $voi){ ?>
                                                        <option value="<?php echo $voi->id; ?>"><?php echo str_replace("IVONA 2 ","",$voi->name).' '.$voi->idioma; ?></option>
                                                        <?php } ?>
                                                    </select>



                                                    <a class="iframe voice_link" id="PopUp" href="javascript:;">Escuchar Voces DEMO</a>

                                                    <div id="mainpop" style="display:none;">
                                                    	<iframe id="PopUpOut" src="<?php echo base_url('apps/voice_view'); ?>" frameborder="0"></iframe><br>
                                                    	<a href="javascript:;" id="cerrar" class="cerrar">cerrar<i class="material-icons">clear</i></a>
                                                    </div>
                                                    <?php } ?>
                                                    <input type="hidden" name="id_wapp" id="id_wapp" value="<?php echo set_value('id_wapp', $id); ?>" />
                                                    <input type="hidden" name="id_content_text" id="id_content_text" value="" />

                                                    <div style="text-align:center">
                                                        <input type="submit"  value="Guardar Mensaje" class="btn btn-block btn-guardar-narracion" />
                                                        <input type="reset" style="display:none;" value="Limpiar formulario" class="btn btn-block btn-guardar-narracion" />
                                                    </div>
                                            <?php echo form_close(); ?>
                                            </div>
                                            <?php endif; ?>

                                            <!-- TAB SUBIR -->
                                            <?php if($app->upload_audio==1): ?>
                                            <div class="tab-pane<?php if($app->text_speech==0) echo 'active'; ?>" id="subir">
                                                <div id="dropdiv-small">
                                                <div class="initial-audio-upload">
                                                   <?php
                                                        //OPEN TEXT SPEECH FORM
                                                        $attributes = array('name' => 'form-audio', 'id' => 'form-audio');
                                                        echo form_open('wizard/upload_audio', $attributes);
                                                    ?>
                                                    <label><?php echo $this->lang->line('nombre') ?></label>
                                                    <input name="audio_name" type="text" class="input-block-level input-medium dynamic_name" value="">
                                                    <a href="javascript:void(0);">
                                                        <label class="cabinet">
                                                            <input type="file" class="file"  name="upload_audio" data-context="dropdiv-small" accept="audio/*" data-form-data='{"wapp":"<?php echo $app->id ?>", "user":"<?php echo $app->user_id ?>"}' />
                                                        </label>
                                                    </a>
                                                    <?php echo form_close(); ?>
                                                    <div id="progress_bar_audio">
                                                        <div class="bar" style="width: 0%;"></div>
                                                    </div>
                                                </div>

                                                <!-- <div class="fakeupload">
                                                    <img src="../<?php echo base_url('assets/img') ?>/ico-audio-generic-medium.png" />
                                                    <p><a href="javascript:void(0)"></a><?php echo $this->lang->line('remplaceaudio'); ?>:<span></span></p>
                                                    <input type="submit" id="subir-audio" class="btn" value="SUBIR AUDIO" />
                                                </div> -->

                                            <!-- <input type="file" name="Filedata" /> -->

                                                  <span class="msg" id="msg-upload">Selecciona un archivo de audio mp3 para subir, no debe pesar más de 2Mbs.</span>
                                               </div> <!-- dropdiv-small -->
                                               <div id="upload-audio">
                                                Debes ser propietario de los derechos de autor o tener permiso de usar el contenido que subas. <a href="#MAS INFORMACION">Más información</a>
                                               </div>
                                            </div>
                                            <?php endif; ?>


                                            <!-- TAB GRABAR -->
                                            <?php if($app->record_audio==1): ?>
                                            <?php
                                                $the_url_record = base_url('wizard/add_audio_record');
                                                $the_url_record = urlencode($the_url_record);
                                            ?>
                                            <div class="tab-pane <?php if($app->text_speech==0 && $app->upload_audio==0) echo 'active'; ?>" id="grabar">
                                                <object width="100%" height="100%" type="application/x-shockwave-flash" data="<?php echo base_url('assets/swf') ?>/kkatoo-audio-recorder_small.swf?saveurl=<?php echo $the_url_record ?>&id_wapp=<?php echo $app->id ?>">
                                                <param name="movie" value="<?php echo base_url('assets/swf') ?>/kkatoo-audio-recorder_small.swf?saveurl=<?php echo $the_url_record ?>&id_wapp=<?php echo $app->id ?>" />
                                                <param name="flashvars" value="saveurl=<?php echo $the_url_record ?>&id_wapp=<?php echo $app->id ?>">
                                                <param name="quality" value="high" />
                                                <param name="wmode" value="" />
                                                </object>
                                            </div>
											<?php endif; ?>
                                            <!-- TAB SELECCIONAR
                                            <div class="tab-pane" id="seleccionar">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, sequi ullam nihil sapiente accusamus ipsum perspiciatis facere quae veritatis necessitatibus facilis ex odio eaque magni minus reiciendis porro dolorum temporibus!</p>
                                            </div>-->
                                        </div>
                                    </div>
                                </div><!-- .span5 #content-library-tabs -->
                                <?php endif; ?>
<?php if($app->id !='327'){?>
                                <?php if($app->aditional_options == 1): ?>
                                	<!--<div id="buttons">
                                		<button class="btn" type="button" id="show-additional-options"><?php echo $this->lang->line('optionsadd'); ?></button>
                                	</div> --><!-- buttons -->
                                <?php endif; ?>
                            </div><!-- .row -->
                        </div>

                        <?php if($app->aditional_options==1): ?>
				      <!-- OPCIONES ADICIONALES -->
                      <div id="msg-additional-options">
                        <h3 style="text-align:left;"><?php echo $this->lang->line('options'); ?></h3>
                        <div id="msg-additional-dialpad">
                           <h4><?php echo $this->lang->line('numberoption'); ?></h4>
                           <div id="dialpad">
                              <button class="dial-button" type="button" data-num="1" data-step="8" data-intro="Si tu llamada tiene opciones de marcación, selecciona el número en el teclado...">1</button>
                              <button class="dial-button" type="button" data-num="2">2</button>
                              <button class="dial-button" type="button" data-num="3">3</button><br>
                              <button class="dial-button" type="button" data-num="4">4</button>
                              <button class="dial-button" type="button" data-num="5">5</button>
                              <button class="dial-button" type="button" data-num="6">6</button><br>
                              <button class="dial-button" type="button" data-num="7">7</button>
                              <button class="dial-button" type="button" data-num="8">8</button>
                              <button class="dial-button" type="button" data-num="9">9</button><br>
                              <button class="dial-button" type="button" data-num="*">*</button>
                              <button class="dial-button" type="button" data-num="0">0</button>
                              <button class="dial-button" type="button" data-num="#">#</button>
                           </div>
                        </div> <!-- msg-additional-dialpad -->

                        <div id="tree-options">
                           <ul id="browser" class="filetree">
                           		<li data-step="9" data-intro="Haz click en la carpeta que se genera y selecciona el audio en tu biblioteca haciendo click en el ícono en forma de teléfono" class="inicial expandable"><span class="folder inicialdata" data-num="inicial">Inicial</span>
                           			<ul>
                           				<?php
                           				if(isset($marcado->hijos)){
          												foreach($marcado->hijos as $marc)
          												{
          													if(isset($marc->digito))
          													{
          														echo '<li class="'.$marc->digito.'" data-num="'.$marc->digito.'"><span class="folder" data-num="'.$marc->digito.'">'.$marc->digito.'</span><ul class="'.$marc->digito.'">';

          														foreach($marc->hijos as $hja)
          														{
          															if(isset($hja->digito))
          															{
          																	echo '<li class="subnivel"><span class="folder" data-num="'.$hja->digito.'">'.$hja->digito.'</span></li>';;
          															}
          														}
          														echo '</ul></li>';
          										     		}
          										     	}
          									     	}
          									     ?>
                           			</ul>

                           		</li>
            							</li>
            						</ul>
                        </div>
                        <div class="show-which-message">
                          <h4><?php echo $this->lang->line('selected_message'); ?></h4>
                          <div class="selected-message">
                          	 <div class="selected_audio" style="display:none;">
                             	<h5>Audio Name</h5>
                             	<audio src="<?php echo base_url('public/audios');if(!empty($audiocampaign)): echo '/'.$audiocampaign->path; endif;?>" type="audio/*" controls="controls"></audio>
                             </div>
                          	 <div class="selected_text" style="display:none;">
                             	<h5><?php echo $this->lang->line('text_to_speech'); ?></h5>
                             	<p></p>
                             </div>
                          </div>
                          <div class="text-center delete_selected" style="display:none;">
                            <a href="javascript:void(0);" class="btn" data-delete="" data-campaign="<?php echo $id_campaign; ?>">Eliminar Selecionado <strong>#</strong></a>
                          </div>
                        </div>
                     </div> <!-- msg-additional-options -->
                     <!-- FIN OPCIONES ADICIONALES -->
                     <?php endif; ?>
                     <?php } ?>

            		  <!-- SECCION DE GRABACION DE CIERRE -->
                      <?php if($app->cierre==1): ?>
                      <div id="intro-recording" style="display:; ">
                         <h3>Grabar cierre</h3>
                         <p style="margin: 0 40px;">Si lo deseas también puedes grabar un mensaje de 10 segundos para despedirte de tus contactos.</p>
                          <div style="overflow:hidden;margin: 5px 40px;">
                            <?php
              								$the_url_record = base_url('apps/save_close');
              								$the_url_record = urlencode($the_url_record);
              							?>
                            <object width="100%" height="250" type="application/x-shockwave-flash" data="<?php echo base_url('assets/swf') ?>/kkatoo-audio-recorder.swf?saveurl=<?php echo $the_url_record ?>&id_wapp=<?php echo $app->id ?>&cache=<?php echo time(); ?>&function=real_redirect">
                                <param name="movie" value="<?php echo base_url('assets/swf') ?>/kkatoo-audio-recorder.swf?saveurl=<?php echo $the_url_record ?>&id_wapp=<?php echo $app->id ?>&cache=<?php echo time(); ?>&function=real_redirect" />
                                <param name="flashvars" value="saveurl=<?php echo $the_url_record ?>&id_wapp=<?php echo $app->id ?>&function=real_redirect">
                                <param name="quality" value="high" />
                                <param name="wmode" value="" />
                            </object>
                          </div>
                      </div>
          					  <?php endif; ?>
          					 <br />
                     <div id="btn-next-step"  style="display:">
                        <a class="btn btn-large" href="<?php echo base_url('apps/'.$app->uri.'/3'); ?>"><?php echo $this->lang->line('next_step'); ?> <i class="icon-chevron-right"></i> </a>
                     </div>

                  </div> <!-- paso1 (#contacts-select) -->

                  <!-- fin PESTAÑA 1 *************************************************-->

               </div> <!-- #tab-content -->
            </div> <!-- .span12 -->
         </div> <!-- .row -->

      </div> <!-- #app-container -->

<!-- Modal para esperar -->
		<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="myModalLabel">Cargando Datos</h3>
		  </div>
		  <div class="modal-body">
		    <p>Por favor espere?</p>
		  </div>
		  <div class="modal-footer">
		    <!--<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>-->
		  </div>
		</div>
		<!-- #Modal para esperar -->

		<!-- Modal para mensajes -->
		<div id="mensajes" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="mensajesLabel">Error</h3>
		  </div>
		  <div class="modal-body">
		    <p><div class="alert alert-error mensajesdeerror">
			 Error al enviar los datos
			</div></p>
		  </div>
		  <div class="modal-footer">
		    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		  </div>
		</div>
		<!-- #Modal para mensajes -->

      <!-- Le javascript -->
      <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script> -->
      <script src="<?php echo base_url('assets/js/bootstrap.js')?>"></script>
      <script src="<?php echo base_url('assets/js/plugins.js')?>"></script>
      <script src="<?php echo base_url('assets/build/mediaelement-and-player.min.js')?>"></script>
      <script src="<?php echo base_url('assets/js/steps-ini.js')?>"></script>
      <script src="<?php echo base_url('assets/js/fancybox/jquery.fancybox-1.3.4.pack.js')?>"></script>
      <script src="<?php echo base_url('assets/js/si.files.js'); ?>"></script>
      <script src="<?php echo base_url('assets/js/wizard'); ?>/jquery.simplePagination.js"></script>
      <script src="<?php echo base_url('assets/js/charactercounter.js')?>"></script>

      <script type="text/javascript">
        $(document).ready(function(){
    $( '#RunTour2').on('click', function(){
    javascript:introJs().start();
    });
});
        $("#PopUp").click(function(){
   $('#mainpop').show();
});
$("#cerrar").click(function(){
   $('#mainpop').hide();
});
      	// Agregado libreria de contenidos
		/**
			PAGINACIÓN PARA LA LIBRERÍA DE CONTENIDOS
		**/

		var pagination_ul = $('.pagination_wiz ul');
		var items = 'tbody.topaginate_wiz tr.content_resume';
		var numItemsToShow = 5;
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

		//VARIABLES GLOBALES QUE SERÁN UTILIZADAS EN EL JS
    var $line_nombre  		= '<?php echo $this->lang->line('nombre') ?>';
		var $line_the_message 	= '<?php echo $this->lang->line('the_message') ?>';
		var $line_voicena 		= '<?php echo $this->lang->line('voicena') ?>';
		var $app_data_id		= '<?php echo $app->id ?>';
		var $sure_delete_library= '<?php echo $this->lang->line('sure_delete_library'); ?>';
		var $name_not_empty 	= '<?php echo $this->lang->line('name_not_empty'); ?>';

      </script>
      <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
      <script src="<?php echo base_url('assets/fileupload'); ?>/jquery.iframe-transport.js"></script>
      <script src="<?php echo base_url('assets/fileupload'); ?>/jquery.fileupload.js"></script>
 	  <script src="<?php echo base_url('assets/js/wizard/libreria_radios.js') ?>"></script>


      <script type="text/javascript">
      $(document).ready(function($){
         $('#steps-tabs a:#segundo').tab('show');
         $('#msg-initial-tabs a:first').tab('show');
         $('#msg-individual-tabs a:first').tab('show');
		    $(".ifancybox").fancybox({
              'autoDimensions': false,
              //'centerOnScroll': true,
		          //'width' : '40%',
		          //'height' : '50%',
		          'autoScale' : false,
		          'transitionIn' : 'elastic',
		          'transitionOut' : 'elastic',
		          'type' : 'iframe',
              'scrolling': 'auto'
		     });
         paso2();
      });

		<?php
			if(isset($marcado->hijos)){
				foreach($marcado->hijos as $marc)
				{
					if(isset($marc->digito))
					{
						echo 'arr_diales["'.$marc->digito.'"] = new Array();';

						foreach($marc->hijos as $hja)
						{
							if(isset($hja->digito))
							{
									echo 'arr_diales["'.$marc->digito.'"]["'.$hja->digito.'"] = new Array();';
							}
						}
		     		}
		     	}
	     	}
		?>

        SI.Files.stylizeAll();

      </script>

		<script type="text/javascript">
			function real_redirect(url){
				location.href=url;
			}

			// LOAD THE SWF OBJECT DYNAMICALY
			var createSwfObject = function() {

				var src= '<?php echo base_url('assets/swf/kkatoo-audio-recorder_small.swf') ?>?saveurl=<?php echo $the_url_record ?>&id_wapp=<?php echo $app->id ?>';
				var attributes = {id: 'myid', 'class': 'myclass', width: '100%', height: '100%'};
				var parameters = {wmode: '', flashvars:"saveurl=<?php echo $the_url_record ?>&id_wapp=<?php echo $app->id ?>"};

				var i, html, div, obj, attr = attributes || {}, param = parameters || {};
				attr.type = 'application/x-shockwave-flash';
				if (window.ActiveXObject) {
					attr.classid = 'clsid:d27cdb6e-ae6d-11cf-96b8-444553540000';
					param.movie = src;
				}
				else {
					attr.data = src;
				}
				html = '<object';
				for (i in attr) {
					html += ' ' + i + '="' + attr[i] + '"';
				}
				html += '>';
				for (i in param) {
					html += '<param name="' + i + '" value="' + param[i] + '" />';
				}
				html += '</object>';
				div = document.createElement('div');
				div.innerHTML = html;
				obj = div.firstChild;
				div.removeChild(obj);
				return obj;
			};
        </script>
<script type="text/javascript">
$(document).ready(function(){
  $( "#BotonTarjeta" ).click(function() {
    $( "#BotonTarjeta" ).slideUp( "slow" );
    $( "#TarjetaFlotante" ).slideDown( "slow" );
  }).slideUp("slow");

  $( "#CerrarTarjeta" ).click(function() {
    $( "#TarjetaFlotante" ).slideUp( "slow" );
    $( "#BotonTarjeta" ).slideDown( "slow" );
  });


  // $( ".color" ).click(function() {
  //   $('body').addClass($(this).attr("data-myclass")).removeClass("color_2");
  // });


});
        </script>
   </body>

</html>
