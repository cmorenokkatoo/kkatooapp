<meta charset="utf-8">
<style type="text/css">
#error_php{
	border:1px solid #e0e0e0;  
	margin:2em;
	padding: 1em;
	background: #f4f4f4;
	font-size: 11px;
	width: 50%;
}

</style>
<div id="error_php">

<h5>Parece que hay un error</h5>

<p>Importancia: <?php echo $severity; ?></p>
<p>Mensaje:  <?php echo $message; ?></p>
<p>Archivo: <?php echo $filepath; ?></p>
<p>Línea: <?php echo $line; ?></p>
<p>Por favor reporta este error haciendo click <a href="http://soporte.kka.to/" target="_blank">aquí</a>, indicando qué estabas haciendo cuando sucedió.</p>

</div>