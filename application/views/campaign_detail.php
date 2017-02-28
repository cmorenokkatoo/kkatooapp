<?php
	$this->load->view("globales/head_detail");
	$this->load->view('globales/mensajes');
?>
 <link rel="stylesheet" href="<?php echo base_url('assets/css/pace.css')?>">
      <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script> -->
      <script type="text/javascript" src="<?php echo base_url('assets/js/pace.min.js')?>"></script>

<style type="text/css">
  body{
    background: #2E80AB !important;
  }
  #app-header{
    color: #303030;
    text-transform: uppercase;
    background: white !important;
    font-weight: 400;
    text-align: center;
    height: inherit !important ;
  }
  #content{
    background: white !important;
    text-align: center;
    padding: 10px;
  }

#boton_container{
  padding: 2rem;
}
.boton_informes{
  padding: 1rem;
  background: #2E80AB;
  color: white;
  margin: 10px;
cursor: pointer;
}
.boton_informes:hover{
  text-decoration: none;
  background: #FFD700;
  color: black;
}

#graph-container{
  width: 100%;
  text-align: center;
  margin: 5px auto;
  display: flex;
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


<!--********************************************************************************* -->
      <!-- CONTAINER DE LA APLICACION -->
      <div id="app-container" class="container">
         
         <!-- HEADER DE LA APLICACION -->
         <div class="row">
            <div class="span12" id="app-header">
               <div class="row">
                
                  <div class="span6" id="title-and-links" style="width: 100%; font-size: 8px;">
                    <style></style>
                    <h3><?php echo "<span style='font-weight: 300; color: #656a71;'>#" .$id_camp . "</span> ". $campaign_name; ?></h3>
                  </div> <!-- #title-and-links -->
               </div> <!-- .row -->
            </div> <!-- #app-header -->
         </div> <!-- .row -->
<!-- CUERPO DE LA APLICACION -->
<div class="row" id="content">
  <!-- <span id="note"><i>esta página se actualiza cada 1 minuto</i></span> -->
          <div id="boton_container" class="">
             <!--  <div class="btn-recall boton_informes" data-id="<?php echo $id_camp; ?>"><i class="fa fa-phone"></i> Relanzar Llamadas Fallidas</div> -->
              <a class="btn-recall boton_informes" data-id="<?php echo $id_camp; ?>"><i class="fa fa-phone"></i> Relanzar Llamadas Fallidas</a>
              <a class="boton_informes boton360" onclick="recargar()" href="javascript:;"><i class="fa fa-refresh"></i> Refrescar resultados</a>
              <a class="boton_informes boton360" id="export-excel" onclick="export_excel()" href="javascript:;"><i class="fa fa-file-excel-o"></i> Exportar a Excel</a>
            </div>
          <div class="span12" id="graph-container">

            <div id="graph-stat-01">
              <div class="gs-header">
                <h4>Llamadas Programadas: <?php echo $total_call; ?></h4>
                <h5>Precio total de la campaña: $<?php echo number_format($price_real->price_real, 0); ?></h5>
              </div>

              <table id='gs-graph-01'>
             
                <thead>
                  <tr>
                    <th></th>
                    <th>Realizadas</th>
                    <th>Pendientes</th>
                  </tr>
                </thead>
                  <tbody>
                  <tr>
                    <td><?php echo $call; ?></td>
                    <td><?php echo ($total_call-$call); ?></td>
                  </tr>
                </tbody>
              </table>

              <div class="gs-footer">
                &nbsp;
              </div>
            </div>

            <div id="graph-stat-02">
              <div class="gs-header">
                <h4>Llamadas realizadas: <?php echo $call; ?></h4>
                <h5>
                	<?php echo $campaign_date; ?>
                </h5>
              </div>
              <table id='gs-graph-02'>
                <thead>
                  <tr>
                    <th></th>
                    <th>Exitosas</th>
                    <th>No Contestadas</th>
                  </tr>
                </thead>
                  <tbody>
                  <tr>
                    <td><?php echo $exito; ?></td>
                    <td><?php echo ($call-$exito); ?></td>
                  </tr>
                </tbody>
              </table>

              <div class="gs-footer">
                &nbsp;
              </div>
            </div>
          </div> <!-- span12 -->

            <!-- AREA DE LISTADO DE ITEMES -->
            <div class="span12" id="details-items">
               <!-- CONTENIDO DEL DETALLE DE CAMPAÑA  -->
               <div id="details-contents">
                  <div id="details-title">
                     <h3>Detalles de la campaña</h3>
                  </div>
                  <div id="resume-contents"  style="">
                     <div id="resume-details">
                        <table  id="MiTabla" class="display" cellspacing="0" width="100%" >
                        <thead>
                           <tr>
                              <th class="left">Nombre</th>
                              <th>Teléfono</th>
                              <th>Tiempo usado</th>
                              <th>Respuesta</th>
                              <th>Fecha Real</th>
                              <th>Hora Real</th>
                              <th>Precio (COP)</th>
                              <th>Estado</th>
                              <th>Observación</th>
                           </tr>
                         </thead>
                         <tbody>
                           	<?php 

                           	if(!empty($detalle)){
                           		foreach($detalle as $deta){ 
                           	?>
	           	
                           <tr>
                              <td><?php echo $deta->name; ?></td>
                              <td>
                              	<?php 
                               		if($deta->area == 0)
                               		{
	                               		echo $deta->pais.$deta->phone;
                               		}
                               		else
                               		{
	                               		echo $deta->pais.$deta->area.$deta->phone;
                               		}
                               	?>
                               </td>
                              <td><?php echo $deta->seg_real.' Seg'; ?></td>
                              <td><?php echo $deta->marcado; ?></td>
                              <td><?php echo $deta->fecha_real; ?></td>
                              <td><?php echo $deta->hora_real.':'.$deta->minuto_real; ?></td>
                              <td><?php echo $deta->price_real; ?></td>
                              <td><?php echo $deta->state_real; ?></td>
                              <td>
                              <?php  
	                          	switch($deta->state)
	                          	{
		                          	case 0:
		                          		echo 'Iniciando campaña';
		                          		break;
		                          	case 1:
		                          		echo 'Pendiente por enviar';
		                          		break;
		                          	case 2:
		                          		echo 'Saldo insuficiente o número mal escrito';
		                          		break;
		                          	case 3:
		                          		echo 'Llamada contestada por el destinatario';
		                          		break;
		                          	case 4:
		                          		echo 'Llamada no contestada por el destinatario';
		                          		break;
                                   case 5:
                                  echo 'Enviado al destinatario';
                                  break;
                                  case 6:
                                    echo 'Número incorrecto o red saturada';
                                  break;
	                          	}    
                              ?></td>
                               </tr>
                            <?php }
                            	}
                             ?>
                </tbody>
                        </table>
                        <?php  
	// $currentPage 	= ($this->uri->segments[count($this->uri->segments)-1]=="pages")?end($this->uri->segments):1;
	// $current_uri 	= $this->uri->uri_string();
	// $uris 				= explode('/', $current_uri);
	// array_pop($uris);
	// $theuri 			= ($this->uri->segments[count($this->uri->segments)-1]=="pages")?implode('/', $uris):$current_uri.'/pages';
	// $theuri				= base_url().$theuri;
	?>

	<!-- PAGINACION (EN CASO DE NECESITARSE) -->
	<!-- <div class="pagination pagination-centered">
	<ul>

	</ul>
	</div>
                     </div> -->
                  </div> <!-- #resume-contents -->
               </div> <!-- #details-contents LISTADO DE ITEMES -->
            </div> <!-- #details-items -->
         </div> <!-- .row  #content -->
</section>
      <!-- Le javascript -->
      <script src="<?php //echo base_url('assets/js/wizard/jquery.simplePagination.js'); ?>"></script>
      <script src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
      <script src="<?php echo base_url("assets/js/jquery.jeditable.mini.js"); ?>"></script>
      <script src="<?php echo base_url("assets/js/mgrs-ini.js"); ?>"></script>
      <script src="<?php echo base_url("assets/js/steps-ini.js"); ?>"></script>
      
      <!-- Le javascript para los charts-->
      <script type="text/javascript" src="http://www.google.com/jsapi"></script>
      <script type="text/javascript" src="<?php echo base_url("assets/js/jquery.gvChart-1.1.min.js"); ?>"></script>
      <script type="text/javascript">gvChartInit();</script>
      <script type="text/javascript">
      	$(document).ready(function(){
    $('#MiTabla').DataTable();
});
      </script>
      <script type="text/javascript">
        $('#gs-graph-01').gvChart({
          chartType: 'PieChart',
          gvSettings: {
            vAxis: {title: 'No of players'},
            hAxis: {title: 'Month'},
            width: 380,
            height: 200,
            backgroundColor: 'transparent',
            left: 0,
            top:0,
            fontName:'Open sans',
            is3D: true,
            chartArea:{left:'10%',top:'10%',width:"80%",height:"80%"},
            legend:{position: 'bottom', textStyle: {color: 'blue', fontSize: 12}}
          },
        });
          $('#gs-graph-02').gvChart({
          chartType: 'PieChart',
          gvSettings: {
            vAxis: {title: 'No of players'},
            hAxis: {title: 'Month'},
            width: 380,
            height: 200,
            backgroundColor: 'transparent',
            left: 0,
            top:0,
            fontName:'Open sans',
            is3D: true,
            chartArea:{left:'10%',top:'10%',width:"80%",height:"80%"},
            legend:{position: 'bottom', textStyle: {color: 'blue', fontSize: 12}}
          },
        });
      </script>
      <script>
      		/*$(document).on('ready', timedRefresh);*/
			function recargar() 
			{
				location.reload(true);
			}
      </script>
      <script>
      function export_excel(){

    var file = {
    worksheets: [[]], // worksheets has one empty worksheet (array)
    activeWorksheet: 0
  }, w = file.worksheets[0]; // cache current worksheet
  w.name = $("#content").text();
  $('#details-items').find('tr').each(function() {
    var r = w.push([]) - 1; // index of current row
    $(this).find('th').each(function() { w[r].push($(this).context.textContent); });
    $(this).find('td').each(function() { w[r].push($(this).context.textContent); });
  });

  window.location = xlsx(file).href();

}
      </script>

   </body> 

</html>