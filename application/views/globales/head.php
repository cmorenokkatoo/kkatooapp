<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <?php
		$special_title = "Llamadas masivas automáticas, TTS y envío de mensajes de texto | Grupo Mensajes de Voz";
		if($this->specialapp->get('special')){
			$special_title = $this->specialapp->get('title');
		}
    header('Access-Control-Allow-Origin:*');
	?>
	<title><?php echo $special_title; ?></title>
	<meta name="description" content="Mensajes de Voz te permite programar llamadas masivas para enviar a través de internet a cualquier teléfono fijo o celular. Exporta tus contactos, crea sube o graba tu mensaje y programa fecha y hora de tu campaña. El Social dialing llegó a Colombia con Mensajes de Voz.">
  <meta name="keywords" content="Llamadas robóticas, llamadas automáticas, llamadas masivas por internet, llamadas masivas, plataforma de llamadas automáticas, Medellín, llamadas a celular, llamadas programadas, llamadas a fijos, llamadas, Colombia, Argentina, Brasil, Perú, Venezuela, Ecuador, Uruguay, Chile, Panamá, Costa Rica, Nicaragua, El Salvador, México, Estados Unidos, España, Paraguay, Llamadas por Internet, Internet, Llamadas, Telemarketing, Telemercadeo, Landingpage, Suscripción Móvil">
  <meta name="revisit-after" content="2 days">
  <meta name="copyright" content="mensajesdevoz.co">
  <meta name="publisher" content="mensajesdevoz.co">
  <meta name="distribution" content="Global">
  <meta name="city" content="Bogotá">
  <meta name="country" content="Colombia">
  <meta content="INDEX, FOLLOW" name="ROBOTS">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<!-- Favicon -->
	<link rel="apple-touch-icon" sizes="57x57" href="/assets/ico/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/assets/ico/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/assets/ico/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/assets/ico/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/assets/ico/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/assets/ico/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/assets/ico/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/assets/ico/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/assets/ico/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="/assets/ico/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/assets/ico/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/assets/ico/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/assets/ico/favicon-16x16.png">
	<link rel="manifest" href="/assets/ico/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/assets/ico/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="<?php echo base_url("assets/css/newbranding/main.css"); ?>">
	<!-- <link rel="stylesheet" href="<?php echo base_url("assets/css/normalize.css"); ?>"> -->
	<!-- <link rel="stylesheet" href="<?php echo base_url("assets/css/carousel.css"); ?>"> -->
</head>
<body>
<?php 
	$this->load->view('resources/olarkchat');
 ?>