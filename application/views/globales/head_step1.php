<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<title>Mensajes de Voz | <?php echo $app->title; ?></title>
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">
        <!-- Compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.min.js"></script>

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">  
        <!-- <link rel="stylesheet" href="<?php echo base_url("assets/css/newbranding/marketplace.css"); ?>"  /> -->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="<?php echo base_url('assets/js/wizard'); ?>/jquery.simplePagination.js"></script>
<script src="<?php echo base_url('assets/js/intro.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo base_url('assets/css/introjs.css')?>">
<!-- STYLES -->
<link href="<?php echo base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
<!-- <link href="<?php //echo base_url('assets/css/bootstrap-responsive.css')?>" rel="stylesheet"> -->
<link href="<?php echo base_url("assets/css/steps.css"); ?>" rel="stylesheet" />
<link href="<?php echo base_url("assets/css/menu.css"); ?>" rel="stylesheet" />
<link href="<?php echo base_url("assets/css/datepicker.css"); ?>" rel="stylesheet" />
<link rel="icon" type="image/png" href="<?php echo base_url("assets/ico/favicon.png"); ?>" />
<!-- Roboto Fonts -->
     <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,600,700,900" rel="stylesheet">
<!-- Awesome Fonts -->
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
</head>


<body>
<?php 
	$this->load->view('resources/olarkchat');
 ?>