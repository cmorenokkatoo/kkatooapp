<!-- Producción -->
<?php
  $this->load->view('globales/head_step1.php');
  $this->load->view('globales/mensajes');
?>
<style>
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
          background: rgb(0,191,120);
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
        #frm-filtro, #frm-add-contact{
                            background: #FFF3D8;
                            box-shadow: none;
                          }
                          #titulofiltros{
                            font-size: 20px;
                            font-weight: 600;
                          }
                          label{
                            cursor: default;
                          }
                          #frm-filtro select{
                              width: 150px;
                              font-size: 12px;
                          }
                          ./*txt-filtro, input[type="text"]{
                            background: #fff;
                            vertical-align: middle;
                            border: 0px;
                            border-radius: 0px;
                            display: inline-block;
                            padding: .5em;
                            font-size: 12px;
                          }*/
                          .boton-para-filtro{
                            display: inline-block;
                            vertical-align: top;
                            background: #FF6347;
                            padding: .3em .5em;
                            color: #fff;
                            border-radius: 2px;
                            border: 2px solid #FF6347;
                          }
                          .boton-para-filtro:hover{
                            background: #BF2D13;
                            border: 2px solid #7A1604;
                          }
                          #subtitulofiltros{
                            color: #555;
                            font-size: 12px;
                          }
                          #campo-filtro{
                              width: 600px;
                              margin: 0 auto;
                              text-align: center;
                          }

                          .frm-legend{
                            background: transparent;
                          border-bottom: 0px;
                          }
    </style>
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
    <h3>Paso1</h3>
  </div>
  <div id="RunTour2">
  <p>Aprende rápidamente cómo comenzar a usar kkatoo. Haz click en el botón Iniciar Tour para guiarte paso a paso</p>
  <button id="IniciarTour">Iniciar Tour</button>
</div>
</div>


      <!-- CONTAINER DE LA APLICACION -->
      <div id="app-container" class="container">
<section id="subContainerApp">
         <!-- HEADER DE LA APLICACION -->
         <div class="row subrow">
            <div class="span12" id="app-header">
               <div class="">
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
                  <?php  if($this->session->userdata('logged_in')): 

                  ?>
                     <form id="frm-campaign-name" class="form-inline">
                      <!-- <button id="IniciarTour">Iniciar Tour</button> -->
                        <div class="control-group"  data-intro='Dale un nombre a tu campaña y haz click en el botón guardar, así encontrarás fácilmente esta campaña en tu lista de informes.'>
                           <input class="txt-campaign-name input-block-level nombrecampana" name="txt-campaign-name" type="text" id="txt-campaign-name" placeholder="nombre de campaña" value="<?php echo $name_campaign; ?>">
                           <button type="button" class="btn" name="btn-campaign-name" style="padding: 6px 10px;opacity: 1;margin: 0;">Guardar</button>
                        </div>
                     </form>
                   <?php endif; ?>
                  </div> <!-- .span6 -->

               </div> <!-- .row -->
            </div> <!-- #app-header -->
         </div> <!-- .row -->

         <!-- PESTAÑAS PASOS -->
         <div class="row subrow" id="tabs">
            <div class="span12">
            <?php  if($this->session->userdata('logged_in')): ?>
               <!-- PESTAÑAS -->
               <ul class="nav nav-tabs" id="steps-tabs">
                  <li><a href="#contacts-select" data-toggle="tab"><?php echo $this->lang->line('step1'); ?></a></li>
                  <li data-step="8" data-intro='Ve al siguiente paso para configurar tu mensaje. Asegurate de tener los contactos en campaña y el nombre de la campaña, de lo contrario no podrás avanzar'><a href="<?php echo base_url('apps/'.$app->uri.'/2'); ?>"><?php echo $this->lang->line('step2'); ?></a></li>
                  <li><a href="<?php echo base_url('apps/'.$app->uri.'/3'); ?>"><?php echo $this->lang->line('step3'); ?></a></li>
               </ul>
               <!-- CONTENIDOS PESTAÑAS -->
            <?php endif; ?>
            <?php  if($this->session->userdata('logged_in')): ?>
               <div class="tab-content" id="tab-content">

                  <!-- PESTAÑA 1 *************************************************-->
                  <div class="tab-pane active" id="contacts-select" >
                     <!-- HEADER PESTANA 1-->
                     <div class="row" id="contacts-header">
                        <div data-step="7" data-intro="En este espacio están los contactos que tienes seleccionados para llamar o enviar un SMS. Puedes ingresar, verificar que son los correctos y si es necesario eliminar el que necesites." id="contacts-selected-badge">
                           <a href="javascript:;">
                            <span class="number"><?php echo $contacts_to; ?></span>
                              <span class="legend"><?php echo $this->lang->line('contactscampaign'); ?></span>
                           </a>
                        </div>


                        <div class="span6"><h2>Seleccionar contactos</h2></div>

                        <div class="span5" id="contacts-links">
                           <div style="display: inline-table;">
                             <div id="contacts-add-ico" data-step='2' data-intro='Añade contactos 1 a 1, haz click en el botón y se desplegará el formulario para agregar tu contacto'><a href="javascript:;" title="Agregar nuevo contacto"></a></div>
                             <div id="contacts-admin-ico" data-step='3' data-intro='Haciendo click en este ícono ingresarás al administrador de contactos. Desde allí podrás importar bases de datos desde excel, borrar o editar tus contactos.'><a href="<?php echo base_url('contacts/contact_manager'); ?>" title="Administrador de contactos"></a></div>
                             <div id="contacts-filter-ico" data-step='4' data-intro='Si tienes varios grupos de contactos o quieres llamar a alguien en específico, puedes usar el filtro haciendo click en la lupa.'><a href="javascript:;" title="Filtrar contactos"></a></div>
                           </div>
                        </div>
                     </div> <!-- .row -->

                     <!-- HEADER OCULTO QUE SE MUESTRA CUANDO SE CAMBIA A SELECCIONADOS -->
                     <div class="row" id="contacts-header2" style="display:none">
                        <div id="contacts-selected-badge2">
                           <a href="javascript:;">
                           <!-- <span class="number"><?php echo $contacts_to; ?></span> -->
                              <span class="legend"><?php //echo $this->lang->line('contactscampaign'); ?>Regresar</span>
                           </a>
                        </div>

                        <div class="span6"><h2>Contactos seleccionados</h2></div>

                        <div class="span5" id="contacts-links">
                           <div id="contacts-add-ico"><a href="javascript:;"></a></div>
                           <div id="contacts-admin-ico"><a href="<?php echo base_url('contacts/contact_manager'); ?>"></a></div>
                        </div>
                     </div> <!-- .row -->

                     <!-- AGREGAR CONTACTO -->
                     <?php
                      $attributes = array('class' => 'form-horizontal', 'id' => 'frm-add-contact', 'style' => 'display:none;');
                      echo form_open('apps/add_contact', $attributes);
                     ?>
                        <div class="frm-legend"><?php echo $this->lang->line('datebasic'); ?></div>
                        <div class="control-group">
                            <label class="control-label" for="txt-name" ><?php echo $this->lang->line('namefull'); ?>: </label>
                            <div class="controls">
                              <input value="<?php echo set_value('name', ''); ?>" required class="span4" type="text" id="txt-name" name="name" placeholder="<?php echo $this->lang->line('namefull'); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="cbo-groups" ><?php echo $this->lang->line('countrycity'); ?>:</label>
                            <div class="controls">
                              <select class="span2" id="cbo-groups" name="indi_pais" required>
                                 <option value="" disabled selected style='display:none;'><?php echo $this->lang->line('country'); ?></option>
                                 <?php
                                 //echo var_dump($country);
                                  if(!empty($country)):
                                    foreach($country as $pais):
                                 ?>
                                  <option value="<?php echo $pais->id; ?>"><?php echo $pais->name; ?></option>
                                 <?php
                                    endforeach;
                                  endif;
                                 ?>
                              </select> <!--  /
                              <input type="text" name="indi_area" placeholder="Indi Area" />-->
                                <!--  <select class="span2" id="cbo-city" name="indi_area">
                                 <option value="" disabled selected style='display:none;'><?php echo $this->lang->line('city'); ?></option>
                             </select> -->
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="txt-phone"><?php echo $this->lang->line('phone'); ?>: </label>
                            <div class="controls">
                              <input  class="span4" type="text" id="txt-phone" required name="phone" placeholder="<?php echo $this->lang->line('phone'); ?>">
                           </div>
                        </div>

                        <?php if(!empty($fields)): ?><div class="frm-legend"><?php echo $this->lang->line('dateadditional'); ?> <span class="frm-sub-legend"><?php echo $this->lang->line('necessaryapp'); ?></span></div><?php endif; ?>

                        <?php
                          if(!empty($fields)):
                              foreach($fields as $field):
                                switch ($field->tipo) {
                            case 1:
                                $tipo = 'number';
                                break;
                            case 2:
                                $tipo = 'text';
                                break;
                            case 3:
                                $tipo = 'date';
                                break;
                          case 4:
                            $tipo = 'text';
                          break;
                        }

                        ?>

                          <div class="control-group">
                               <label class="control-label" for="<?php echo $field->name_fields; ?>" ><?php echo $field->name; ?>: </label>
                               <div class="controls">
                                    <div class="input-append">
                                      <?php
                                        if($tipo == 'dropdown'){
                                          echo $dropdown;
                                        }elseif($tipo == 'date'){
                                        ?>
                                        <input class="span4 calendario" type="text" name="<?php echo $field->name_fields; ?>" placeholder="dd/mm/yy" />
                                                            <?php
                                        }else{
                                        ?>
                                        <input class="span4 <?php echo $tipo; ?>" id="<?php echo $tipo; ?>" type="text" id="txt-due-date" name="<?php echo $field->name_fields; ?>" placeholder="" />
                                        <?php
                                                            }
                                        ?>

                                        <?php if($tipo == 'date'): ?>
                                       <!-- <span class="add-on"><i class="icon-th"></i></span>-->
                                        <?php endif; ?>
                                    </div>
                               </div>
                          </div>

                        <?php
                              endforeach;
                          endif;
                         ?>


                        <div class="control-group">
                           <label class="control-label" for="btn-save" ></label>
                           <div class="controls">
                              <input class="btn" type="submit" name="btn-save" value="<?php echo $this->lang->line('save'); ?>">
                           </div>
                        </div>
                        <input type="hidden" name="id_campaign" value="<?php echo isset($id_campaign)? $id_campaign : ""; ?>" />
                     </form>
                     <!-- FILTRO -->

                     <?php
                      $attributes = array('class' => '', 'id' => 'frm-filtro', 'style' => 'display:none;');
                      echo form_open('apps/get_filter', $attributes);
                     ?>

                        <label id="titulofiltros">Filtrar Contactos</label>
                        <p id="subtitulofiltros">buscar por nombre, teléfono, grupo o campo dinámico</p>

                        <select class="txt-filtro" id="cbo-groups" name="cbo-groups">
                           <option value=""  disabled>- Grupo -</option>
                           <option value="0" selected><?php echo $this->lang->line('all'); ?></option>
                           <?php
                           if(!empty($groups)):
                            foreach($groups as $group):
                              echo '<option value="'.$group->id.'">'.$group->name.'</option>';

                            endforeach;
                           endif;
                           ?>

                        </select>

                        <select class="txt-filtro" id="cbo-field" name="cbo-field">
                          <option value=""   disabled selected>- Datos Básicos -</option>
                            <option value="name_contact">Nombre</option>
                            <option value="phone_contact">Teléfono</option>
                           <option value=""  disabled>- Datos Dinámicos -</option>
                          <?php
                           if(!empty($fields)):
                            foreach($fields as $field):
                              echo '<option value="'.$field->id.'">'.$field->name.'</option>';

                            endforeach;
                           endif;
                           ?>
                           <?php if($app->tipo == 1): ?>
                           <?php endif; ?>
                        </select>

                        <select class="txt-filtro" id="cbo-operator" name="cbo-operator">
                          <option value="" selected disabled>- Rango -</option>
                           <option value="=" ><?php echo $this->lang->line('equalto'); ?></option>
                           <option value=">"><?php echo $this->lang->line('higherto'); ?></option>
                           <option value="<"><?php echo $this->lang->line('lessto'); ?></option>
                           <option value="<>"><?php echo $this->lang->line('differentto'); ?></option>
                        </select>

                          <input  class="txt-filtro" type="text" name="txt-criterion"  placeholder="Criterio de búsqueda...">
                          <input class="btn-filter boton-para-filtro" type="button" value="Filtrar"  data-loading-text="Cargando...">
                          <input class="limpiarfiltro boton-para-filtro" type="button" value="Limpiar">
                          <input type="hidden" name="id_campaign" value="<?php echo isset($id_campaign)? $id_campaign : ""; ?>" />
                          <input type="hidden" name="filtro_action" value="1" />
                          <input type="hidden" name="id_wapp" value="<?php echo (!empty($id_wapp))?$id_wapp:'' ?>" />
                          <input type="hidden" name="slug" value="<?php echo $this->uri->segment(2) ?>" />
                     </form>

                     <!-- DATOS DEL RESULTADO -->
                     <div id="contacts-data">
                        <div class="row" style="text-align:center">
                        <?php
                          $attributes = array('class' => 'form-inline', 'method'=>'post', 'name'=>'form-add-all');
                          echo form_open('apps/add_all_contacts_to_campaing', $attributes);
                        ?>
                            <button type="submit" name="select_all_btn" class="btn btn-small" style="margin: 15px;" data-step="5" data-intro="Si ya realizaste tu filtro y en la parte inferior están todos los contactos a los que deseas llamar, simplemente da click a este botón.">Agregar Todos</button>
                            <?php echo form_close(); ?>
                        </div>
                        <table class="table-striped table-hover" id="table-cartera">
                           <thead>
                              <tr style="text-align: center !important; width: 100% !important;">
                                 <th><input type="checkbox" id="selectall"></th>
                                 <th><?php echo $this->lang->line('name'); ?></th>
                                 <th><?php echo $this->lang->line('phonegrid'); ?></th>
                                 <?php if($app->tipo == 1){ ?><th><?php echo $this->lang->line('subscribecredits'); ?></th> <?php  } ?>
                                 <!--
                                 <?php
                                   if(!empty($fields)):
                                    foreach($fields as $field):
                                      echo "<th>".$field->name."</th>";
                                    endforeach;
                                  endif;
                                 ?>
                                 -->
                              </tr>
                           </thead>
                           <tbody class="contactstoselect">
                             <tr>
                                <td colspan="3"><h4 style="font-weight:300;">Estamos cargando los contactos, espera un momento por favor.</h4></td>
                             </tr>
                           </tbody>
                        </table>

                        <!-- PAGINACION (EN CASO DE NECESITARSE) -->
                        <div class="pagination pagination_no_selected pagination-centered">
                         <ul>
                           <!-- pagination content -->
                         </ul>
                        </div>

                     </div> <!-- #contacts-data -->


                     <!-- DIV OCULTO DE LOS SELECCIONADOS -->
                     <div id="selected-data" style="display:none">
                        <!-- DATOS DEL RESULTADO -->

                        <table class="table-striped table-hover" id="table-selected">
                           <thead>
                              <tr>
                                <td>
                                  <a href="<?php echo base_url('apps/delete_all_campaign/'.$id_campaign); ?>"><img src="<?php echo base_url("assets/img/ico-x-25px.png"); ?>" alt="Deseleccionar" /></a>
                                </td>
                                 <th><?php echo $this->lang->line('name'); ?></th>
                                 <th><?php echo $this->lang->line('phonegrid'); ?></th>
                              </tr>
                           </thead>
                           <tbody class="contactsselected">
                           <?php
                            //listado de contactos seleccionados (si los hay)
                            if(!empty($contacts_campaign)):
                                foreach($contacts_campaign as $con):
                          ?>
                              <tr>
                                 <td><img data-id="<?php echo $con->id; ?>" class="deselect" src="<?php echo base_url("assets/img/ico-x-25px.png"); ?>" alt="Deseleccionar" /></td>
                                 <td class="left-align">
                                    <img src="<?php echo base_url("assets/img/users/ico-usr-generic-small.png"); ?>" alt="<?php echo $con->name; ?>"/><span><?php echo trim($con->name); ?></span>
                                    </td>
                                 <td>(<?php echo $con->indi_pais; ?>) <?php echo $con->phone; ?></td>
                              </tr>
                              <?php
                                endforeach;
                            endif;
                            ?>
                           </tbody>
                        </table>

                        <!-- PAGINACION (EN CASO DE NECESITARSE) -->
                        <div class="pagination pagination_selected pagination-centered">
                         <ul>
                           <!-- pagination content -->
                         </ul>
                        </div>

                     </div> <!-- #selected-data -->

                     <div id="btn-next-step" >
                        <a class="btn btn-large" href="<?php echo $app->uri; ?>/2"><?php echo $this->lang->line('next_step'); ?>
                          <i class="material-icons" style="vertical-align: middle;">chevron_right</i>
                        </a>
                     </div>

                  </div> <!-- paso1 (#contacts-select) -->
                  <!-- fin PESTAÑA 1 *************************************************-->


               </div> <!-- #tab-content -->
               <?php else:
                    echo "<div id='connect-msg'>Debes estar conectado para poder continuar <br>REGISTRATE ahora mismo o INGRESA si ya estás registrado </div>";
                endif; ?>
            </div> <!-- .span12 -->
         </div> <!-- .row -->
</section>
      </div> <!-- #app-container -->
      <div class="blank_space"></div>
<script>
$(document).ready(function(){
    $( '#RunTour2').on('click', function(){
    javascript:introJs().start();
    });
});

</script>
<?php
  $this->load->view('globales/footer_step1.php');
?>
