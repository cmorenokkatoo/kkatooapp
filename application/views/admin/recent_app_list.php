<?php
	$this->load->view("globales/head_app_manager");
	$this->load->view('globales/mensajes');
?>
<style type="text/css">
	.green{
		color:green;
	}
	.red{
		color:#C00;
	}
	.table{font-size:13px;}
</style>

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
					
               </div> <!-- #options-list -->

            </div> <!-- #options -->

            <!-- AREA DE LISTADO DE ITEMES -->
            <div class="span10" id="kredit-mgr-items">
               <!-- CONTENIDO DE LA SECCION 1 *RECARGA*  -->
 


               <div id="resume-contents">
                  <div id="resume-title">
                     <h4>Aplicaciones creadas</h4>
                     <a href="<?php echo base_url('admin/recents/noaproved'); ?>" class="btn btn-primary btn-small">Ver no aprobadas</a>
                     <a href="<?php echo base_url('admin/recents/aproved'); ?>" class="btn btn-primary btn-small">Ver aprobadas</a>
                     <a href="<?php echo base_url('admin/recents/'); ?>" class="btn btn-primary btn-small">Ver todas</a>
                  </div>
                  <br  />
                  <div id="resume-items">
                     <table class="table table-striped">
                        <tr>
                           <th>Fecha Ult. Actualización</th>
                           <th>Nombre Aplicación</th>
                           <th>Descripción</th>
                           <th></th>
                        </tr>
                        <?php if(!empty($apps)){
							foreach($apps as $app){
						?>
                        	<tr class="<?php echo ($app->aproved==0)?"red":"green"; ?>">
                               <td>
                                  <?php echo date("d/m/Y", strtotime($app->created)); ?>
                               </td>
                               <td>
                                  <?php echo $app->title; ?>
                               </td>
                               <td>
                                  <?php echo $app->description; ?>
                               </td>
                               <td>
                                  <a href="<?php echo base_url("landing/".$app->uri); ?>" target="_blank">Revisar App</a>
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


      <!-- Le javascript -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
      <script src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
      <script src="<?php echo base_url("assets/js/jquery.jeditable.mini.js"); ?>"></script>
      <script src="<?php echo base_url("assets/js/mgrs-ini.js"); ?>"></script>   
      
   </body> 

</html>