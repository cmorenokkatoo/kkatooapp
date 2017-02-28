<!-- Barra Superior ================================================== -->
<header>
<input type="checkbox" name="" id="movilcheck">
<label for="movilcheck" id="icon-menu-movile"><i class="material-icons">menu</i></label>
<nav id="navegacion_movile">
	        <ul>
	        	<li><a href="<?php echo base_url("documentacion"); ?>" target="_blank">API<?php //echo $this->lang->line('blog'); ?></a></li>
	            <li ><a href="#">Soluciones<?php //echo $this->lang->line('market'); ?></a></li>
	            <li><a href="<?php echo base_url("login/login"); ?>"><i class="material-icons ico-movile">lock</i> Iniciar Sesión<?php //echo $this->lang->line('login'); ?></a></li>
	            <li><a href="<?php echo base_url("login/register"); ?>"><i class="material-icons ico-movile">assignment</i> Crear Cuenta<?php //echo $this->lang->line('register'); ?></a></li>
			</ul>
	</nav>
	<div id="header">
			<?php
				// $logo = $this->specialapp->create_logo('logo_principal_mv.jpg');
			?>
			<!-- <img id="enlace_logo"  src="<?php echo  $logo->brand_img ?>" alt="Mensajes de Voz"/> -->
			<h1>Mensajes de voz</h1>
	    <nav id="navegacion_home">
	        <ul>
	        	<li><a href="<?php echo base_url("documentacion"); ?>" target="_blank">API<?php //echo $this->lang->line('blog'); ?></a></li>
	            <li ><a href="#">Soluciones<?php //echo $this->lang->line('market'); ?></a></li>
	            <li><a href="<?php echo base_url("login/login"); ?>"><i class="material-icons ico-desktop">lock</i>Iniciar Sesión<?php //echo $this->lang->line('login'); ?></a></li>
	            <li><a id="btn_registro_nuevo" href="<?php echo base_url("login/register"); ?>"><i class="material-icons ico-desktop" id="ico-register">assignment</i>Crear Cuenta<?php //echo $this->lang->line('register'); ?></a></li>
			</ul>
	    </nav>
    </div>
		<!-- Sección contenido adicional -->
	<div id="contenedor_cabecera">
		<h1>En sólo 3 pasos</h1>
		<p>Llama o envía mensajes de texto masivamente en instantes y muy fácil</p>
		<a id="btn_registro_nuevo" href="<?php echo base_url("login/register"); ?>">COMIENZA AHORA<?php //echo $this->lang->line('register'); ?></a>
	</div>
</header>

<!-- Fin Barra Superior ================================================== -->
