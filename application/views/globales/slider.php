<style>
    #mensaje-vacaciones{
       /* background: rgba(255,61,61, 0.8);
        width: 100%;
        padding: .5em 0em;
        color: #fff;
        text-align: center;
        font-size: 13px;*/
        display: none;
    }

    #slider{
      	background: #C61B21 !important;
    }
    .boton-nuevo{
      background: white;
      color: black;
      display: block;
      padding: 1em;
      text-decoration: none;
    }
</style>
<section id="mensaje-vacaciones">
    <p>Nuestro servicio de soporte técnico estará disponible entre los días <b>18 de diciembre y 5 de enero de 10:00am a 2:00pm.</b></p>
    <p>Nuestras líneas estarán cerradas los días <b>24, 25, 31 de diciembre y 1 de enero.</b></p>
</section>
 <!-- Slider ================================================== -->
<section id="slider">
    <div id="myCarousel" class="carousel slide">
        <!-- Carousel items -->
        <div class="carousel-inner">

            <div class="item"><!-- Slide -->
                <div class="interna-item">
                    <div class="img-item">
                        <img src="<?php echo base_url("assets/img/home-3-pasos.png"); ?>">
                    </div>
                    <div class="text-item">
                        <h3 class="subtitle">&nbsp;De manera fácil puedes</h3>
                        <h2 class="title">programar</h2>
                        <h3 class="details">tus llamadas</h3>
                        <p>Con <span class="kkatoo">Mensajes de Voz</span> puedes hacer llamadas en 3 sencillos pasos</p><br>
                        <a href="<?php echo base_url("login/register"); ?>" class="boton-nuevo" onclick="document.location.href ='http://www.google.com'" >Crear cuenta</a>
                    </div>
                </div>
            </div> <!-- Slide -->

           <!-- Slide <div class="item active">
            	<div class="interna-item">
                	<div class="img-item">
                    	<img src="<?php echo base_url("assets/img/logoBootcamp.png"); ?>">
                    </div>
                    <div class="text-item">
                    	<h3 class="subtitle">Participa en el próximo</h3>
						<h2 class="title">KKATOO BOOTCAMP</h2>
						<p>Para ti que tienes una idea de negocio usando kkatoo, asiste a nuestro bootcamp.  Inscríbete y te contaremos cuando es el próximo en tu ciudad.</p><br>
						<a href="http://www.kka.to/bootcamp/primerbootcamp.php" target="_blank" class="btn btn-large btn-warning btn_free_proof">Inscríbete Ahora <span class="action_arrow"> &gt; </span></a>
                    </div>
                </div>
            </div> Slide -->

            <!-- Slide -->
            <!-- <div class="item" style="display:none;">
                <div class="interna-item">
                    <div class="img-item">
                        <img src="<?php echo base_url("assets/img/logoBootcamp.png"); ?>">
                    </div>
                    <div class="text-item">
                        <h3 class="subtitle">Espera Nuestro Próximo</h3>
                        <h2 class="title">Kkatoo Bootcamp</h2>
                        <p>Emprendendor: aprende a crear una idea de negocio desde nuestra plataforma usando tecnología VoIP. Convierte tus ideas en aplicaciones y comienza a ganar dinero. </p><br>
                        <a class="btn btn-large btn-warning btn_free_proof" style="cursor: none;">Muy Pronto <span class="action_arrow"> &gt; </span></a>
                    </div>
                </div>
            </div> -->
            <!-- Slide -->

            <div class="item active"><!-- Slide -->
            	<div class="interna-item">
                	<div class="img-item">
                        <iframe width="450" height="335" src="http://www.youtube.com/embed/H7MXxMzvzH8?rel=0&wmode=transparent&fs=0&modestbranding=2&showinfo=0" frameborder="0"></iframe>
                    </div>
                    <div class="text-item">
                    	<h2 class="title">kkatoo</h2>
						<h3 class="details">Llama por ti</h3>
						<p>Con <span class="kkatoo">kkatoo</span> puedes programar llamadas telefónicas automáticas  de una manera rápida, fácil y divertida. Encontrarás muchas aplicaciones con contenidos e ideas creativas para llamar.</p><br>
						<a href="<?php echo base_url("login/register"); ?>"class="btn btn-large btn-warning btn_free_proof">regístrate ahora <span class="action_arrow"> &gt; </span></a>
                    </div>
                </div>
            </div><!-- Slide -->

     	</div>
        <!-- Carousel nav -->
        <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
        <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
    </div>
</section>
<!-- TERMINA SLIDER -->
