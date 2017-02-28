<?php
	$this->load->view('globales/head_step3.php');
	$this->load->view('globales/mensajes');
	//echo var_dump($marcado->hijos);
?>
<style>
#app-container{
    padding: 3rem 0rem;
   }
h1#app-name{
    font-size: 22px;
  }
#datecfg-initial h4{
   font-size: 24px !important;
   padding: 20px 0px !important;
}
#content-tools-checkbox{
   width: 100%;
   text-align: center;
   margin: 0 auto;
}

.checkbox-tools-check, .checkbox-tools-label{
   display: inline-block;
   vertical-align: text-top;
}

.sub-content-tools-checkbox{
   display: inline-block;
   margin: 10px 30px;
   background: #f2f2f2;
   padding: 1em;
   border-radius: 3px;
   vertical-align: baseline;

}

#content-tools-checkbox h3{
   background-color: #e8e8e8;
   padding: 5px 0 5px 15px;
   font-weight: 300;
   margin: 20px 0;
   letter-spacing: -1px;
   text-align: left;
}

#buzonmsj, #smsmsj{
   position: relative;
}

#buzonmsj:hover::after{
   background: #fff;
   width: 300px;
   height: auto;
   padding: .6em;
   content: 'Toma en cuenta que: • Esta alternativa funcionará si la persona deja timbrar el teléfono pero no contesta. • En caso de que el celular este apagado o las llamadas sean rechazadas, el mensaje  SI quedará en el buzón de voz.';
   position: absolute;
   border: dotted 1px green;
   border-radius: 4px;
   top: -110px;
   left: 50px;
   z-index: 999;
   font-size: 12px;
   /*-webkit-box-shadow: 1px 1px 1px 1px rgba(140,140,140,0.2);
   box-shadow: 1px 1px 1px 1px rgba(140,140,140,0.2);*/
}

#smsmsj:hover::after{
   background: #fff;
   width: 300px;
   height: auto;
   padding: .6em;
   content: 'Recuerda que: • 160 es el límite de caracteres, si tu mensaje los supera, éste llegará incompleto. • Esta alternativa sólo está disponible para teléfonos móviles y para mensajes escritos.';
   position: absolute;
   border: dotted 1px green;
   border-radius: 4px;
   top: -110px;
   left: 50px;
   z-index: 999;
   font-size: 12px;
   /*-webkit-box-shadow: 1px 1px 1px 1px rgba(140,140,140,0.2);
   box-shadow: 1px 1px 1px 1px rgba(140,140,140,0.2);*/
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
          background: rgb(0,124,191);
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
    <h3>Paso3</h3>
  </div>
  <div id="RunTour2">
    <p>Aprende rápidamente cómo comenzar a configurar la fecha y hora de tu llamada. Haz click en el botón Iniciar Tour para guiarte paso a paso</p>
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
                        </div>   -->
                  </div> <!-- #app-name-and-desc -->

                  <!-- COLUMA 2 Formulario de nombre de la campaña -->
                  <div class="span6">
                     <form id="frm-campaign-name" style="text-transform: uppercase;">
                        <div class="control-group">
                           <label for="txt-campaign-name">
                           <?php echo "<span style='font-weight: 300; color: #656a71;'>#" .$id_campaign . "</span> ". $name_campaign; ?>
                           </label>
                        </div>
                     </form>
                  </div> <!-- .span6 -->

               </div> <!-- .row -->
            </div> <!-- #app-header -->
         </div> <!-- .row -->


         <!-- PESTAÑAS PASOS -->
         <div class="row" id="tabs">
            <div class="span12">
               <!-- PESTAÑAS -->
               <ul class="nav nav-tabs" id="steps-tabs">
                  <li><a href="<?php echo base_url('apps/'.$app->uri); ?>">PASO 1</a></li>
                  <li><a href="<?php echo base_url('apps/'.$app->uri.'/2'); ?>">PASO 2</a></li>
                  <li><a href="#date-config" data-toggle="tab">PASO 3</a></li>
               </ul>
               <!-- CONTENIDOS PESTAÑAS -->
               <div class="tab-content" id="tab-content">

                  <!-- OTROS PASOS-->
                  <div class="tab-pane" id="contacts-select">Este es el paso 1</div>
                  <div class="tab-pane" id="message-config">Este es el paso 2</div>


                  <!-- PESTAÑA 3 *************************************************-->
                  <div class="tab-pane active" id="date-config" >
                  <!-- formulario lopez -->
                  <?php
                     	$attributes = array("id"=>"form_final");
                     	echo form_open('apps/add_date_campaign', $attributes);
                     ?>
                     <!-- HEADER PESTANA 1-->
                     <div class="row" id="datecfg-header">
                        <div class="span9"><h2>Configurar fechas y horas de llamada</h2></div>
                     </div> <!-- .row -->


                     <!-- GRUPO DE TABS DE MENSAJE INICIAL -->
                     <div id="datecfg-initial">
                        <h3>Período de ejecución de la campaña</h3>
                        <?php if($app->id !='327'){?>
                        <h4>La campaña durará <b><?php echo $duration; ?> segundos</b>, y tiene un valor estimado de <b>$<?php echo $total_price; ?> COP</b></h4>
                        <?php } ?>
                        <div id="content-initial">
                           <div class="row">
                              <div class="span4" style="margin: 0 auto;float: none;" >

                                    <div class="control-group date-cfg-range">
                                       <div class="controls">
                                       <?php $time = time(); ?>
                                       <span class="date-title-group">fecha</span>
                                            <div class="input-append date" id="begin-date" data-date="<?php echo mdate('%Y-%m-%d', $time); ?>" data-date-format="yyyy-mm-dd">
                                            <input data-intro="Elige la fecha en la que será enviada tu campaña" name="date" class="span2" size="16" type="text" value="<?php echo mdate('%Y-%m-%d', $time); ?>">
                                            <span class="add-on"><i class="material-icons">date_range</i></span>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="control-group date-cfg-range">
                                       <div class="controls">
                                       <span class="date-title-group">hora</span>
                                          <select data-step="2" data-intro="Selecciona la hora a la que enviarás tu campaña." name="hour" id="begin-time">
                                             <?php for($j = 7; $j<= 22;$j ++): ?>
                                             <option value='<?php echo $j; ?>'><?php echo str_pad($j, 2, '0', STR_PAD_LEFT); ?></option>
                                          <?php endfor; ?>
                                          </select>
                                          <select name="minu" id="begin-time">
                                          <?php for($j = 0; $j<= 59;$j ++): ?>
                                             <option value='<?php echo $j; ?>'><?php echo str_pad($j, 2, '0', STR_PAD_LEFT); ?></option>
                                          <?php endfor; ?>
                                          </select>
                                       </div>
                                    </div>
                                    <style>
                                        #timeZone select{
                                          width: 250px;
                                        }
                                    </style>
                                    <div class="control-group date-cfg-range">
                                       <div class="controls">
                                        <span class="date-title-group">zona</span>
                                          <div id="timeZone" data-step="3" data-intro="Selecciona la zona horaria con la que enviarás tu campaña, para colombia es GMT -5, Bogotá, Lima, Quito.">
                                    	   <?php  echo timezone_menu('UM5'); ?>
                                        </div>
                                      </div>
                                    </div>
                                    <input type="hidden" name="campaign" class="id_campaign" value="<?php echo $id_campaign; ?>" />

                              </div> <!-- .span5 offset1 -->

                           </div> <!-- .row -->
                        </div>
                     </div> <!-- msg-initial -->
                     <!-- FIN GRUPO DE TABS DE MENSAJE INICIAL -->

                     <!-- Tools sms y buzón de mensajes -->

                     <section id="content-tools-checkbox">
                        <h3>Configuración Avanzada </h3>
                        <!-- <div id="new-float">nuevo</div> -->
                        <?php if($app->id !='327'){?>
                        <div class="sub-content-tools-checkbox" data-step="4" data-intro="Toma en cuenta que: • Esta alternativa funcionará si la persona deja timbrar el teléfono pero no contesta. • En caso de que el celular este apagado o las llamadas sean rechazadas, el mensaje  SI quedará en el buzón de voz.">
                           <input name ="checkboxBuzon" id="buzonmsj" type="checkbox" value="1" class="checkbox-tools-check">
                           <label  for="buzonmsj"  class="checkbox-tools-label">Evitar que la llamada quede en el buzón de mensajes.</label>

                        </div>
                        <?php } ?>

                        <?php if($app->id =='327'){?>
                        <div class="sub-content-tools-checkbox" data-step="5" data-intro="Recuerda que: • 160 es el límite de caracteres, si tu mensaje los supera, éste llegará incompleto. • Esta alternativa sólo está disponible para teléfonos móviles y para mensajes escritos.">
                           <input name ="checkboxSMS" id="smsmsj" type="checkbox" value="1" class="checkbox-tools-check" checked>
                           <label  for="smsmsj"  class="checkbox-tools-label">Enviar como mensaje de texto (SMS)</label>
                        </div>
                        <?//php } else { ?>
<!--
                        <div class="sub-content-tools-checkbox" data-step="5" data-intro="Recuerda que: • 160 es el límite de caracteres, si tu mensaje los supera, éste llegará incompleto. • Esta alternativa sólo está disponible para teléfonos móviles y para mensajes escritos.">
                           <input name ="checkboxSMS" id="smsmsj" type="checkbox" value="1" class="checkbox-tools-check">
                           <label  for="smsmsj"  class="checkbox-tools-label">Enviar como mensaje de texto (SMS)</label>
                        </div>
-->
                        <?php } ?>
                     </section>
                     <br>

                     <div id="btn-next-step"  style="display:">
                        <a class="btn btn-large btn-warning" href="javascript:;" onclick="enviarformfinal()" id="btnNotification" data-step="6" data-intro="Al hacer click en este botón se empezará a ejecutar tu campaña en la fecha y hora seleccionados">GUARDAR Y EJECUTAR CAMPAÑA<i class="icon-chevron-right"></i> </a>
                     </div>

                     	<!-- fin formulario lopez -->
                  </div> <!-- paso1 (#contacts-select) -->

                  <!-- fin PESTAÑA 1 *************************************************-->


               </div> <!-- #tab-content -->
            </div> <!-- .span12 -->
         </div> <!-- .row -->

      </div> <!-- #app-container -->


      <!-- Le javascript -->
      <script src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
      <script src="<?php echo base_url("assets/js/steps-ini.js"); ?>"></script>
      <script src="<?php echo base_url("assets/js/bootstrap-datepicker.js"); ?>"></script>
      <script>
      $(document).ready(function(){
    $( '#RunTour2').on('click', function(){
    javascript:introJs().start();
    });
});
      $(document).ready(function($){
         $('#steps-tabs a:last').tab('show');
      });

      $('#begin-date').datepicker().on('changeDate', function(ev){
	       $('#begin-date').datepicker('hide');
      });
      </script>
   </body>

</html>
