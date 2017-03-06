<?php 
	$this->load->view('globales/mensajes');
	header("Refresh:60");
 ?>
<!DOCTYPE html>
<html lang="es" class="js">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<script src="//code.jquery.com/jquery-1.12.4.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/pdfmake-0.1.18/dt-1.10.13/af-2.1.3/b-1.2.4/b-colvis-1.2.4/b-html5-1.2.4/r-2.1.1/datatables.min.css"/>
 	<script type="text/javascript" src="https://cdn.datatables.net/v/dt/pdfmake-0.1.18/dt-1.10.13/af-2.1.3/b-1.2.4/b-colvis-1.2.4/b-html5-1.2.4/r-2.1.1/datatables.min.js"></script>
      	<!-- Compiled and minified JavaScript -->
        	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.min.js"></script>
        	<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">  
    	<link href="<?php echo base_url("assets/css/steps.css"); ?>" rel="stylesheet">
	<link href="<?php echo base_url("assets/css/mgrs.css"); ?>" rel="stylesheet">
	<link href="<?php echo base_url("assets/css/wizard.css"); ?>" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css') ?>">
	<script type="text/javascript" src="<?php echo base_url("assets/js/recall.js"); ?>"></script>
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,600,700,900" rel="stylesheet">
	<title><?php echo  "#". $id_camp . " - " .  $campaign_name; ?></title>
	<style>
	*{
		box-sizing: border-box;
	}
		body{
			background: #1E6DB1 !important;
		}
		#GraphTable{
			display: flex;
			width: 100%;
			margin: 2rem auto;
			flex-wrap: wrap;
		}
		.js div#preloader { position: fixed; 
			left: 0; top: 0; 
			z-index: 999; width: 100%; height: 100%; 
			overflow: visible; 
			background: #1E6DB1 url('https://s-media-cache-ak0.pinimg.com/originals/32/4a/88/324a88a767e53bb415076d3bceb382ee.gif') no-repeat center center; }
		.js div#preloader h1{
			text-align: center;
			margin:  2rem auto;
			color: white;
			font-size: 20px;
			font-family:'Helvetica Neue';
			font-weight: 300;
		}
.graph-stat-row {
	    width: 350px;
	    /* border-radius: 5px; */
	    background-color: #eee;
	    font-family: 'Roboto', sans-serif;
	    border: 1px solid #ddd;
	    text-align: center;
	    /* padding: 8px 8px 8px 8px; */
	    /* float: left; */
	    margin: 2px auto;
	    flex: auto;
}
		#app-container{
			background: white !important;
			padding: 2rem  !important;
			width: 90% !important;
			max-width: none !important;
			margin: 2rem auto !important;
		}
		.btn-recall, .buttons-csv, .buttons-page-length{
			padding: .5rem !important;
			background: black !important;
			color: white !important;
			border: 0px !important;
			border-radius: 3px !important;
			cursor: pointer;
		}
		h1{
			font-size: 2rem;
		}
		.btn-recall{
			background: green !important;
		}
		#header-detail-campaign{
			width: 100%; font-size: 16px; display: flex;
		}
		h3{
			text-transform: uppercase;
		}
		
	</style>
</head>
<script>
jQuery(document).ready(function($) {  
// site preloader -- also uncomment the div in the header and the css style for #preloader
$(window).load(function(){
	$('#preloader').fadeOut('slow',function(){$(this).remove();});
});

});
</script>
<body>
<div id="preloader">
	<h1>¡Paciencia! estamos cargando la información...</h1>
</div>
<?php 
  $this->load->view('resources/olarkchat');
 ?>
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
<!-- Contenedor -->

<div id="app-container" class="container">
	<div  id="header-detail-campaign">
                	<h3><?php echo "<span style='font-weight: 300; color: #656a71;'>#" .$id_camp . "</span> ". $campaign_name; ?></h3>
                	<a class="btn-recall" data-id="<?php echo $id_camp; ?>"><i class="fa fa-phone"></i> Reenviar no contestadas</a>
	</div> 
	<div id="GraphTable">
		<div class="graph-stat-row">
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
	            <div class="graph-stat-row">
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
		                    <th>No contestadas</th>
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
	             <div class="graph-stat-row">
		              <div class="gs-header">
		                <h4>Interacciones: <?php echo $marcado; ?></h4>
		                <h5>
		                	<?php echo "Contestadas: " . $exito; ?>
		                </h5>
		              </div>
		              <table id='gs-graph-03'>
		                <thead>
		                  <tr>
		                    <th></th>
		                    <th>Marcadas</th>
		                    <th>No Marcadas</th>
		                  </tr>
		                </thead>
		                  <tbody>
		                  <tr>
		                    <td><?php echo $marcado; ?></td>
		                    <td><?php echo ($exito - $marcado); ?></td>
		                  </tr>
		                </tbody>
		              </table>

		              <div class="gs-footer">
		                &nbsp;
		              </div>
	            </div>
	</div>
	<table  id="detailCampaign" class="display" cellspacing="0" width="100%" >
	 	<thead>
	 		<tr>
	 			<th class="left">Nombre</th>
	 			<th>Teléfono</th>
	 			<th>Tiempo usado</th>
	 			<th>Respuesta</th>
	 			<th>Fecha Real</th>
	 			<th>Hora Real</th>
	 			<!-- <th>Precio (COP)</th> -->
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
	 					<!-- <td><?php //echo $deta->price_real; ?></td> -->
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
	 							echo 'Llamada contestada';
	 							break;
	 							case 4:
	 							echo 'Llamada no contestada o rechazada';
	 							break;
	 							case 5:
	 							echo 'SMS entregado';
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
</div>
<script src="<?php echo base_url("assets/js/mgrs-ini.js"); ?>"></script>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
      <script type="text/javascript" src="<?php echo base_url("assets/js/jquery.gvChart-1.1.min.js"); ?>"></script>
      <script type="text/javascript">gvChartInit();</script>
      <script type="text/javascript">
        $('#gs-graph-01').gvChart({
          chartType: 'PieChart',
          gvSettings: {
             width: 400,
            height: 250,
            backgroundColor: 'transparent',
            left: 0,
            top:0,
            fontName:'Open sans',
            is3D: false,
            chartArea:{left:'10%',top:'10%',width:"70%",height:"70%"},
            legend:{position: 'bottom', textStyle: {color: 'black' ,fontSize:'12px'}}
          },
        });
          $('#gs-graph-02').gvChart({
          chartType: 'PieChart',
          gvSettings: {
             width: 400,
            height: 250,
            backgroundColor: 'transparent',
            left: 0,
            top:0,
            fontName:'Open sans',
            is3D: false,
            chartArea:{left:'10%',top:'10%',width:"70%",height:"70%"},
            legend:{position: 'bottom', textStyle: {color: 'black' ,fontSize:'12px'}}
          },
        });
          $('#gs-graph-03').gvChart({
          chartType: 'PieChart',
          gvSettings: {
            width: 400,
            height: 250,
            backgroundColor: 'transparent',
            left: 0,
            top:0,
            fontName:'Open sans',
            is3D: false,
            chartArea:{left:'10%',top:'10%',width:"70%",height:"70%"},
            legend:{position: 'bottom', textStyle: {color: 'black' ,fontSize:'12px'}}
          },
        });
      </script>
      <script>
$(document).ready(function() {
	var table = $("#detailCampaign").DataTable(
        {
        "deferRender": true,
        responsive: true,
       language: {
       	buttons:{
       		pageLength: 'Mostrar %d registros'
       	},
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
},
       "iDisplayLength": 10,        
     dom: 'Bfrtip',
     lengthMenu: [
            [ 10, 50, 100, -1 ],
            [ '10 registros', '50 registros', '100 registros', 'Todo' ]
        ],
         buttons: [
              'pageLength',     
       {
           extend: 'csv'  
       },
       {
           extend: 'excel'
       }
         ]
   });
});
</script>
</body>
</html>