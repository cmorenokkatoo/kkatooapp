<?php
if($this->session->flashdata('exitoso') && $this->session->flashdata("paymade") == false){
?>
<style type="text/css">
	.mensajes{
		width: 100% !important;
		height: auto;
		text-align: center !important;
		padding: 10px;
		font-size: 16px;
		font-weight: 700;
		display: flex;
	}
	.cerrar a{
		color: rgb(60,179,113);
		font-size: 1rem;
		background: white;
		padding: 5px;
		margin: 0px 120px;
	}
	.cerrar a:hover{
		color: rgb(60,179,113);
		text-decoration: none;
	}
	.exito{
		background: rgb(60,179,113);
		color: white;
		text-transform: uppercase;
	}
	.cerrar i{
		vertical-align: middle !important;
	}
</style>
 <div class="mensajes exito" style="display:block; z-index:5000">
    <span class="titulo-mensaje">Genial!</span>
    <span class="mensaje"><?php $mensaje = str_replace('<p>','<span>',$this->session->flashdata('exitoso')); 
								$mensaje = str_replace('</p>','</span>',$mensaje);
								echo $mensaje;
								?>
    </span>
    <span class="cerrar"><a href="javascript:;">cerrar<i class="material-icons">clear</i></a></span>
</div>
<?php
}
?>
<?php
if($this->session->flashdata('error') && $this->session->flashdata("paymade") == false){ 
?>
<style type="text/css">
	.mensajes{
		width: 100% !important;
		height: auto;
		text-align: center !important;
		padding: 10px;
		font-size: 16px;
		font-weight: 700;
		display: flex;
	}
	.cerrar a{
		color: #334443;
		font-size: 1rem;
		background: white;
		padding: 5px;
		margin: 0px 120px;
	}
	.cerrar a:hover{
		color: #334443;
		text-decoration: none;
	}
	.error{
		background: #334443;
		color: white;
		text-transform: uppercase;
	}
	.cerrar i{
		vertical-align: middle !important;
	}

</style>
<div class="mensajes error" style="display:block; z-index:5000">
    <span class="titulo-mensaje">Ups!</span>
    <span class="mensaje"><?php $mensaje = str_replace('<p>','<span>',$this->session->flashdata('error')); 
								$mensaje = str_replace('</p>','</span>',$mensaje);
								echo $mensaje;
						?></span>
    <span class="cerrar"><a href="javascript:;">cerrar<i class="material-icons">clear</i></a></span>
</div>
<?php
}
?>
<?php
if(!empty($error)){
	if($error != ""){
?>
<style type="text/css">
	.mensajes{
		width: 100% !important;
		height: auto;
		text-align: center !important;
		padding: 10px;
		font-size: 16px;
		font-weight: 700;
		display: flex;
	}
	.cerrar a{
		color: #334443;
		font-size: 1rem;
		background: white;
		padding: 5px;
		margin: 0px 120px;
	}
	.cerrar a:hover{
		color:#334443;
		text-decoration: none;
	}
	.error{
		background: #334443;
		color: white;
		text-transform: uppercase;
	}
	.cerrar i{
		vertical-align: middle !important;
	}

</style>
<div class="mensajes error" style="display:block; z-index:5000">
    <span class="titulo-mensaje">Ups!</span>
    <span class="mensaje"><?php $mensaje = str_replace('<p>','<span style="display:block">',$error); 
								$mensaje = str_replace('</p>','</span>',$mensaje);
								echo $mensaje;
						?></span>
    <span class="cerrar"><a href="javascript:;">cerrar<i class="material-icons">clear</i></a></span>
</div>
<?php
		
	}
}
?>
<script type="text/javascript">
	window.KKATOO_ROOT = "<?php echo KKATOO_ROOT ?>";
</script>
<link href="<?php echo base_url("assets/css/mgrs.css"); ?>" rel="stylesheet">
<script src="<?php echo base_url("assets/js/mgrs-ini.js"); ?>"></script>
