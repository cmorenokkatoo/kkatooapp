<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Verificación de Cuenta</title>
		<style>
	body
	{
		background: #f5f5f5; 
		margin: 3em auto; 
		text-align: center;
		color: #3e3e3e
	}
	
	section
	{
		background: #fff;
		width: 30%;
		margin: 2em;
		padding: 2em;
	}

	a#btn-confirm
	{
		background: #fcba41;
		padding: 1em 2em;
		border: 1px solid #FCAB14;
		margin: 2em;
		text-decoration: none;
		color: #fff;
		border-radius: 4px;
	}
	a#btn-confirm:hover
	{
		background: #FCAB14;
	}
	p
	{
		font-size: 1.3em;
		margin: 2em;
	}
	article
	{
		font-weight: 900;
		text-align: left;
		margin: 1.3em;
	}

	a.btn-ref
	{
		background: rgba(139,209,243,0.6);
		padding: 1px 3px;
		border-radius: 3px;
		border: 1px solid rgb(47,173,234);
		color: rgb(47,173,234);
		text-decoration: none;
		opacity: .7;
	}
	a.btn-ref:hover
	{
		opacity: 1;
	}


</style>
</head>
<body style="background: #f5f5f5; margin: 3em auto; text-align: center; color: #3e3e3e; font-family: 'Helvetica';">
<div style="background: #fff; width: 60%; margin: 2em auto; padding: 2em; border: 1px solid #e9e9e9;">
	<div style="background: #444; width: 100%; padding: .3em 0;">
		<img src="http://www.kka.to/assets/img/logo_white_header.png" alt="Kkatoo Social Dialing">	
	</div>
	<h1 style="margin: .5em auto;">Genial <?php echo $name; ?></h1>

	<p style="font-size: 1.3em;	margin: 2em;">Sólo falta un paso más para ser parte de nuestra aplicación. <br>
	Valida tu cuenta haciendo click en el siguiente botón:</p>
			
	<p style="font-size: 1.3em;	margin: 2em;"><a style="background: #fcba41; padding: 1em 2em; border: 1px solid #FCAB14; margin: 2em; text-decoration: none; color: #fff; border-radius: 4px;" id="btn-confirm" href="<?php echo base_url('login/verify_new/'.$email.'/'.$token); ?>">Confirmar Correo</a></p>

<div style="font-weight: 900; text-align: left; margin: 1.3em;">
	<b>Equipo de Soporte <br>
	KKATOO :: Social Dialing</b>
</div>
<br>
<!-- <a class="btn-ref" href="http://www.kkatoo.com" target="_blank">Sitio Oficial</a> | <a class="btn-ref" href="http://www.kka.to/blog" target="_blank">Blog</a> | <a class="btn-ref" href="http://kkatoo.uservoice.com" target="_blank">Área de Soporte</a> -->
</div>
	
</body>
</html>