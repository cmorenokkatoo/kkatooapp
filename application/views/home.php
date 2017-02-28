<?php
    $this->load->view('globales/head');
	$this->load->view('globales/mensajes');
    $this->load->view('globales/navbar');

?>

<!-- Informativo ================================================== -->

<div id="newMainBlock">
    <div id="movile_img">
      <a id="btn_registro_nuevo" href="<?php echo base_url("login/register"); ?>">Crear cuenta</a>
    </div>
    <!-- Beneficios -->
    <div id="benefitsBox">
    <h1>Beneficios</h1>
	    <div id="benefits">
	    	<div class="_benefit">
	    		<h2>TTS o pregrabado</h2>
	    		<ul class="_list_benefit">
	    			<li><i class="material-icons">done</i> Crea mensajes con variables y opciones de marcado</li>
	    			<li><i class="material-icons">done</i> Elige entre los múltiples idiomas disponibles</li>
	    			<li><i class="material-icons">done</i> Graba o sube tus audios mp3 y combínalos con TTS</li>
	    		</ul>
	    	</div>
	    	<div class="_benefit">
	    		<h2>Mensajes de texto (SMS)</h2>
				<ul class="_list_benefit">
	    			<li><i class="material-icons">done</i> Genera SMS con campos personalizados</li>
	    			<li><i class="material-icons">done</i> Envía enlaces con archivos multimedia</li>
	    			<li><i class="material-icons">done</i> Tienes 160 caracteres para usar en tus SMS</li>
	    		</ul>	
	    	</div>
	    	<div class="_benefit">
	    		<h2>API</h2>
				<ul class="_list_benefit">
	    			<li><i class="material-icons">done</i> Conecta tu aplicación con nuestra API</li>
	    			<li><i class="material-icons">done</i> Tan sencillo como un método POST</li>
	    			<li><i class="material-icons">done</i> Una Api en REST completa</li>
	    		</ul>
	    	</div>
	    	<div class="_benefit">
	    		<h2>Automatización de Campañas</h2>
				<ul class="_list_benefit">
	    			<li><i class="material-icons">done</i> Programa tu campaña con anticipación</li>
	    			<li><i class="material-icons">done</i> Guarda tus contactos y audios para usar luego</li>
	    			<li><i class="material-icons">done</i> Obtén informes en tiempo real</li>
	    		</ul>
	    	</div>
	    </div>
	</div>
    <!-- Beneficios -->
    <!-- Usos -->
    <div id="usesBox">
    <h1>Industrias que están usando Mensajes de Voz</h1>
    	<div id="uses">
    		<div class="_useIcon">
    			<i class="material-icons">account_balance</i><br><span>Financiera</span>
    		</div>
    		<div class="_useIcon">
    			<i class="material-icons">airplanemode_active</i><br><span>Aeronáutica</span>
    		</div>
    		<div class="_useIcon">
    			<i class="material-icons">hotel</i><br><span>Hotelera</span>
    		</div>
    		<div class="_useIcon">
    			<i class="material-icons">local_taxi</i><br><span>Transportes</span>
    		</div>
    		<div class="_useIcon">
    			<i class="material-icons">business</i><br><span>Negocios</span>
    		</div>
    		<div class="_useIcon">
    			<i class="material-icons">security</i><br><span>Seguridad</span>
    		</div>
    		<div class="_useIcon">
    			<i class="material-icons">local_hospital</i><br><span>Salud</span>
    		</div>
    		<div class="_useIcon">
    			<i class="material-icons">school</i><br><span>Educación</span>
    		</div>
    	</div>
    </div>
    <!-- Usos -->    
    <div id="flexbox_features" style="display: none;">
      <div class="flexitem_features">
        <i class="material-icons">group_add</i>
        <h3>Añade contactos</h3>
        <p>Crea y añade contactos manualmente o sube tu base de datos en CSV para subir contactos masivos.</p>
      </div>

      <div class="flexitem_features">
        <i class="material-icons">play_circle_outline</i>
        <h3>Elige el contenido</h3>
        <p>Crea audios de texto, sube tu mp3 o grábalo en línea. Arma tu biblioteca de contenidos.</p>
      </div>

      <div class="flexitem_features">
        <i class="material-icons">event</i>
        <h3>Programa fecha y hora</h3>
        <p>Programa con anticipación el envío de tus campañas editando la fecha y la hora en las que serán enviadas.</p>
      </div>
    </div>
</div>
<div id="ivonaBar">
	<h1>8 voces IVONA que cubren 4 idiomas. <a href="https://www.ivona.com/us/about-us/voice-portfolio/" target="_blank">Conócelas</a>
</h1>
</div>
<div id="register_bar">
    <p>Crea tu cuenta gratuita, recarga tu saldo y empieza a eviar campañas de llamadas o SMS <a id="btn_register_bar" href="<?php echo base_url("login/register"); ?>">Crear Cuenta</a></p>
</div>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<!-- Fin  Informativo ================================================== -->
