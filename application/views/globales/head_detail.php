<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title><?php echo($campaign_name)?></title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="Mensajes de voz te permite programar llamadas masivas para enviar a través de internet a cualquier teléfono fijo o celular. Exporta tus contactos, crea sube o graba tu mensaje y programa fecha y hora de tu campaña. El Social dialing llegó a Colombia con kkatoo.">
        <meta name="keywords" content="Llamadas robóticas, llamadas automáticas, llamadas masivas por internet, llamadas masivas, plataforma de llamadas automáticas, Medellín, llamadas a celular, llamadas programadas, llamadas a fijos, llamadas, Colombia, Argentina, Brasil, Perú, Venezuela, Ecuador, Uruguay, Chile, Panamá, Costa Rica, Nicaragua, El Salvador, México, Estados Unidos, España, Paraguay, Llamadas por Internet, Internet, Llamadas, Telemarketing, Telemercadeo, Landingpage, Suscripción Móvil">
        <meta name="revisit-after" content="2 days">
        <meta name="copyright" content="mensajesdevoz.co">
        <meta name="publisher" content="mensajesdevoz.co">
        <meta name="distribution" content="Global">
        <meta name="city" content="Bogotá">
        <meta name="country" content="Colombia">
        <!-- Compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.min.js"></script>

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">  
      <script src="<?php  echo base_url("assets/js/jquery.js"); ?>"></script>
      <script src="<?php echo base_url("assets/js/borrarcamp.js"); ?>"></script>
      <script type="text/javascript" src="<?php echo base_url("assets/js/recall.js"); ?>"></script>

      <!-- scripts para exportar a excell -->
        <script type="text/javascript" src="<?php echo base_url("assets/js/xlsx.js"); ?>"></script>
        <script type="text/javascript" src="http://yourjavascript.com/152186169/jszip.js"></script>
        <script type="text/javascript" src="http://yourjavascript.com/621719612/jszip-load.js"></script>
        <script type="text/javascript" src="http://yourjavascript.com/421961176/jszip-inflate.js"></script>
        <script type="text/javascript" src="http://yourjavascript.com/611912518/jszip-deflate.js"></script>


      <!-- STYLES -->
      <link href="<?php echo base_url("assets/css/bootstrap.min.css"); ?>" rel="stylesheet">
      <link href="<?php echo base_url("assets/css/steps.css"); ?>" rel="stylesheet">
      <link href="<?php echo base_url("assets/css/mgrs.css"); ?>" rel="stylesheet">
      <link href="<?php echo base_url("assets/css/wizard.css"); ?>" rel="stylesheet">
      <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css') ?>">
     <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,600,700,900" rel="stylesheet">
      <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
      <link rel="icon" type="image/png" href="<?php echo base_url("assets/ico/favicon.png"); ?>" />



			<style>
	#nav-options {
		margin-left: -273px;
		margin-top:15px;
	}
        .borrarcamp
      {
         background: #fcba41;
         padding: .8em;
         border-radius: 3px;
         color: #000;
         margin-left: 65%;
         z-index: 1000;
         cursor: pointer;
         top: -3em;
         position: relative;
      }
      .borrarcamp:hover
      {
         background: #DE9203;
      }
      .borrarcamp a
      {
         text-decoration: none;
         color: #000;
      }

      a.borrarcamp:hover
      {
         text-decoration: none;
         color: #fff;
      }
      .camp-name a
      {
         color: #0BBAF4;
      }
      .camp-name a:hover
      {
         text-decoration: none;
      }
      .style-tr:hover
      {
         background: rgba(102,180,242,0.0352941176471);
      }
      </style>
   </head>
<body>
<?php 
  $this->load->view('resources/olarkchat');
 ?>