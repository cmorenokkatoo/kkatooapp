<?php
	$this->load->view("globales/head_app_manager");
	$this->load->view('globales/mensajes');
?>

      <!-- HEADER DE LA PAGINA -->
      <div id="brand-header" class="navbar navbar-fixed-top">
         <div class="navbar-inner">
            <!-- AVISO DE CREDITOS DISPONIBLES -->
            <div id="navbar-container" class="container">               
            	<div class="row">
                  <!-- Para logo -->
                  <div class="span6">
					<?php 
						$logo = $this->specialapp->create_logo('logo-main-header.png');
					?>
                	<a class="brand" href="<?php echo $logo->brand_url; ?>">
                		<img src="<?php echo  $logo->brand_img ?>" alt="<?php echo $logo->brand_title ?>" style="height:60px; margin-top: 2px;" />
                	</a>
                  </div>

                 <?php $this->load->view('utils/user_dropdown') ?>

               </div> <!-- .row -->
            </div> <!-- #navbar-container -->
         </div>
      </div> <!-- #brand-header -->

<!--********************************************************************************* -->
      <!-- CONTAINER DE LA APLICACION -->
      <div id="app-container" class="container">
         
         <!-- HEADER DE LA APLICACION -->
         <div class="row">
            <div class="span12" id="app-header">
               <div class="row">
                  <div class="span6" id="title-and-links">
                     <h2>Gestor de aplicaciones</h2>
                  </div> <!-- #title-and-links -->

                  <div class="span6" id="available-kredits">
                     <div id="content">
                        <h3></h3>
                        <div id="txt">
                           <span id="kredits">CRÉDITOS</span>
                           <span id="available">DISPONIBLES</span>
                        </div>
                     </div>
                  </div> <!-- #search -->

               </div> <!-- .row -->
            </div> <!-- #app-header -->
         </div> <!-- .row -->





         <!-- CUERPO DE LA APLICACION -->
         <div class="row" id="content">
            <!-- AREA DE OPTIONS -->
            <div class="span3" id="options">
               <div id="options-list">

                  <!-- <div class="group-contact-item">
                     <div id="resume-ico"><a href="#" id="resume-link" class="selected"></a></div>
                     <div id="resume-name">Resumen consumo</div>
                  </div> -->
               </div> <!-- #options-list -->

            </div> <!-- #options -->

            <!-- AREA DE LISTADO DE ITEMES -->
            <div class="span10" id="kredit-mgr-items">
               <!-- CONTENIDO DE LA SECCION 1 *RECARGA*  -->
 


               <div id="resume-contents">
                  <div id="resume-title">
                     <h4>Aplicaciones creadas por ti</h4>
                  </div>
                  <div id="resume-items">
                     <table class="table table-striped">
                        <tr>
                           <th class="left camp-name">Nombre Aplicación</th>
                           <th>Acciones</th>
                        </tr>
                        <?php if(!empty($apps)){
							foreach($apps as $app){
						?>
                        	<tr id="appListed_<?php echo $app->id;?>">
                               <td class="left camp-name">
                                  <?php echo $app->title ?>
                               </td>
                               <td>
                                  <a class="accionGestor" href="<?php echo base_url("apps/".$app->uri); ?>"><i class="fa fa-bullhorn"></i> Operar</a>
                                  <a class="accionGestor" href="<?php echo base_url("wizard/".$app->id); ?>"><i class="fa fa-pencil-square-o"></i> Editar</a>
                                  <a class="accionGestor" href="<?php echo base_url("appmanager/".$app->id); ?>"><i class="fa fa-bar-chart-o"></i> Administrar</a>
                                  <a class="accionGestor borrarapp" data-id="<?php echo $app->id;?>"><i class="fa fa-times"></i> Eliminar</a>
                               </td>
                            </tr>
                        <?php
							}
						}
						?>
                     </table>
                  </div>
               </div> <!-- #resume-contents -->


     
               

            </div> <!-- #items-data LISTADO DE ITEMES -->

         </div> <!-- .row  #content -->

      </div> <!-- #app-container -->
      <style type="text/css">
      .accionGestor{margin: 0px 10px; cursor: pointer;}
      </style>

      <!-- Le javascript -->
      <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
      <script src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
      <script src="<?php echo base_url("assets/js/jquery.jeditable.mini.js"); ?>"></script>
      <script src="<?php echo base_url("assets/js/mgrs-ini.js"); ?>"></script>   
      <script>
      	<?php if($user->id_country != 0 && $user->id_city != 0): ?>
	      cambiarCiudadEditar(<?php echo $user->id_country; ?>,<?php echo $user->id_city; ?>);
	      $("#cbo-country-pp").val(<?php echo $user->id_country; ?>);
	    <?php endif; ?>
      </script>
   </body> 

</html>