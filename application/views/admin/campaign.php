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
	
	.rcorners2 {
    border-radius: 15px 50px 30px;
    border: 2px solid #73AD21;
    padding: 20px;  
    width: 240px;
    height: 100%; 
}
   
 #regresar_menu{
          margin: 65px;
          padding: .5em 1em;
          box-sizing: border-box;
          background: #fff;
          border-radius: 3px;
          display: inline-block;
          border: 1px solid #fcba41;
          cursor: pointer;
          vertical-align: middle;
          top: 60px;
          right: 100px;
          float: right;
        }
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
				<div id="regresar_menu" >
      <a href="<?php echo base_url('admin/users/'); ?>">Volver a Usuarios</a>
    </div>

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
                  <h4>Resumen general de llamadas </h4>   
                     <!-- a href="< ?php echo base_url('admin/users/'); ?>" class="btn btn-primary btn-small">Ver todos</a-->
					 
										 <div class="input-group">
										 <input type="date" id="from" name="from" value="2015-01-01" placeholder="Ingrese la fecha desde" min= "2014-01-01" max="2018-01-01" width="50px" >
											 <!--<input type="text" class="input-small form-control" id="from" name="from" required/>-->
											 <span class="input-group-addon">to</span>
											 <input type="date" id="to" name="to" value="<?=date('Y-m-d')?>" placeholder="Ingrese la Fecha Hasta" min= "2014-01-01" max="2018-01-01" width="50px" >
											 <!--<input type="text" class="input-small form-control" id="to" name="to" required/>-->
											 &nbsp; &nbsp; <input type="button" class="button" value="Filtrar" style="moz-border-radius: 15px;" onclick="filter_data(<?=$user->id?>);"></input>
										 </div>
						
										 
                  </div>
				  
				  
                  <br  />
                  <div id="resume-items">
                     <table class="table table-striped">
                        <tr>
                           <th>Estado</th>
													 <th>Número de llamadas</th>
                        </tr>
                        <?php if(!empty($campaign)){
													foreach($campaign as $key => $result){?>
                        	<tr>
                               <td>
                                  <?php echo $key; ?>
                               </td>
                               <td>
                                  <?php echo $result->count; ?>
                               </td>
                            </tr>
                        <?php
							}
						}
						?>
                     </table>
                  </div>
               </div> <!-- #resume-contents -->
			     <!-- CONTENIDO DE LA SECCION 2 *REPORTE DE CAMPANAS*  -->

               <div id="resume-contents">
                  <div id="resume-title">
				  <hr/>
                     <h4>Resumen de Campañas para el usuario <?=$user->fullname?> /  <?=$user->email?> </h4>
                     <!-- a href="< ?php echo base_url('admin/users/'); ?>" class="btn btn-primary btn-small">Ver todos</a-->
										<!-- <div class="input-group">
											 <input type="text" class="input-small form-control" id="from" name="from" required/>
											 <span class="input-group-addon">to</span>
											 <input type="text" class="input-small form-control" id="to" name="to" required/>
										 </div>
										 <input type="button" class="button" value="Filtrar"></input>-->
                  </div>
                  <br  />
                  <div id="resume-items">
                     <table class="table table-striped">
                        <tr>
                           <th>Id</th>
							<th>Nombre Campaña</th>
							<th align="center">Resumen</th>
                        </tr>
                        <?php if(!empty($campaign_consolidated)){
								foreach($campaign_consolidated as $key=>$v_campaign ){
									
									
									echo "<tr align='center'><th>".$key."</th>";
									echo "<th>".$v_campaign['name']."</th>";
									echo "<th ><div class='rcorners2'>
											<table border='0' >";
									foreach($v_campaign as $child_key=>$value){
										switch($child_key){
											case "1" : $state="En espera"; break;
											case "2" : $state="Pendiente Saldo Agotado"; break;
											case "3" : $state="Llamada conectada"; break;
											case "4" : $state="Canceladas/No contesta"; break;
											case "5" : $state="Mensaje enviado"; break;
											case "6" : $state="Mensaje no conectado"; break;
											default : $state="";
										}
										
										if($child_key!="name"){ 
											echo "<tr><th>".$state."</th>";
											echo "<th>".$value."</th></tr>";
										}
										
									}
									echo "</table></div></th></tr>";
									
                        
							}
						}
						?>
                     </table>
                  </div>
               </div> <!-- #resume-contents -->

            </div> <!-- #items-data LISTADO DE ITEMES -->

         </div> <!-- .row  #content -->
				 <div class="row">
				 	<div style="width:100%;height:100px;">
				 	</div>
				 </div> <!-- .row -->
      </div> <!-- #app-container -->


      <!-- Le javascript -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
      <script src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
      <script src="<?php echo base_url("assets/js/jquery.jeditable.mini.js"); ?>"></script>
			<script src="<?php echo base_url("assets/js/bootstrap-datepicker.js"); ?>"></script>
      <script src="<?php echo base_url("assets/js/mgrs-ini.js"); ?>"></script>
<script>
function updateCredit(id,credits){
	$.get(window.KKATOO_ROOT+"/admin/update_user_credits/"+id+"/"+credits,function(){
		console.log("updated");
	});
}
function filter_data(id){
	var from = $("#from").val();
	var to = $("#to").val();
	
	window.location.href="<?php echo base_url("/admin/users/"); ?>/"+id+"/"+from+"/"+to;
	
}

</script>
   </body>

</html>
