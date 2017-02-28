<!doctype html>
<html>

<head>
	<title>Administrador de Aplicaciones</title>
	<!-- LE METAs -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- <meta content="text/html; charset=UTF-8" http-equiv="Content-Type"> -->

	<!-- LE CSS -->
	<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap-responsive.css') ?>">
    <!--<link rel="stylesheet" href="<?php echo base_url('assets/css/wizard/wizard-v3.css') ?>">-->
	<link rel="stylesheet" href="<?php echo base_url('assets/css/appmanager/gestor-aplicaciones.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/colorbox.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/roboto-fonts.css') ?>">
      <!-- Roboto Fonts -->
      <link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,300|Roboto:400,300,700|Roboto:400,300,700' rel='stylesheet' type='text/css'>
      <!-- Awesome Fonts -->
      <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">

</head>

<body>
	<?php
		// INFO PARA INICIAR EL WIZARD

		$id		 = $app_data->id;

		$this->load->view('globales/mensajes');
		$this->lang->load('appmanager');
	?>

	<div id="wrapper">
		<!-- HEADER DE LA PAGINA -->
        <div id="brand-header" class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <!-- AVISO DE CREDITOS DISPONIBLES -->
                <div id="navbar-container" class="container">
                    <div class="row">
	                    <!-- Para logo -->
	                    <div>
		                    <?php
													$logo = $this->specialapp->create_logo('logo-main-header.png');
		                    ?>
		                    <a class="brand" href="<?php echo $logo->brand_url; ?>">
		                    	<img src="<?php echo  $logo->brand_img ?>" alt="<?php echo $logo->brand_title ?>" style="height:60px;" />
		                    </a>
	                    </div>


											<?php
                        if($this->session->userdata('logged_in')):
                      ?>
	                    <div class="avail-credits-badge">
		                    <a href="<?php echo base_url('payment'); ?>" class="link-payment" >
			                    <span class="legend">
				                    <span id="credito">CRÉDITO</span>
				                    <span id="disponible">DISPONIBLE</span>
			                    </span>
			                    <span class="number"><span class="currency-small">$</span><?php echo number_format($credits); ?> COP</span>
		                    </a>
	                    </div>
	                    <?php
	                    	endif;
	                    ?>

	                    <?php $this->load->view('utils/user_dropdown') ?>

                   </div> <!-- .row -->
                </div> <!-- #navbar-container -->
            </div>
        </div> <!-- #brand-header -->

		<!-- CUERPO APP -->
		<div class="container" id="body_container">

			<header>
				<h1><i class="fa fa-bar-chart-o"></i> Administrador de aplicaciones</h1>
				<a class="btn btn-warning" href="<?php echo base_url('user/apps'); ?>">Regresar a mis aplicaciones</a>
			</header>

			<div class="row-fluid" id="app_container">
				<div class="span2" id="nav_container">
					<nav>
						<ul>
							<li><a href="#" class="oneline_btn" id="btn_estadisticas"></a></li>
							<li><a href="#" class="oneline_btn" id="btn_suscriptores"></a></li>
							<li><a href="#" class="twoline_btn" id="btn_biblioteca"></a></li>
							<li><a href="<?php echo base_url('apps/'.$app_data->uri); ?>" class="twoline_btn"	id="btn_operar"></a></li>
							<li><a href="<?php echo base_url('wizard/'.$app_data->id); ?>" class="twoline_btn" id="btn_editar"></a></li>
							<li><a href="LINK A FONOMARKETING CON ID DE ESTA APP" class="oneline_btn" id="btn_publicitar"></a></li>
							<li><a href="#" class="twoline_btn" id="btn_pagos"></a></li>
						</ul>
					</nav>
				</div>  <!-- section_tabs -->

				<div class="span10" id="content_container">

                    <?php if($app_data->tipo == 1): ?>
                    <!--************** ******************************** ****************-->
                    <!--************** SECCION ESTADISTICAS-SUSCRIPCION ****************-->
					<div id="estadisticas_suscripcion" class="showestadisticas a_section">
						<h3 class="titulo_seccion">Estadísticas de la aplicación <?php echo $app_data->title; ?></h3>
						<div class="row-fluid">
							<div class="span3 cuadro_info"><span class="numero_grande"><?php echo (!empty($nro_crated_campaigns))?$nro_crated_campaigns->cuenta:0; ?></span>Campañas creadas</div>
							<div class="span3 cuadro_info"><span class="numero_grande"><?php echo (!empty($maden_calls))?$maden_calls->cuenta:0; ?></span>Llamadas realizadas</div>
							<div class="span3 cuadro_info"><span class="numero_grande"><?php echo (!empty($maden_sms))?$maden_sms->cuenta:0; ?></span>SMS enviados</div>
							<div class="span3 cuadro_info"><span class="numero_grande naranja"><?php echo (!empty($get_user_earnings))?$get_user_earnings->cuenta:0; ?></span><strong>US$ ganados</strong></div>
						</div>
						<hr>
						<div class="row-fluid">
							<!-- COLUMNA 1 -->
							<div class="span6">
								<div class="cuadro">
									<h4 class="titulo_cuadro">Evolucion de suscriptores de la aplicación<a href="#estadisticas_ampliadas" class="btn btn-info btn_ver_mas">VER +</a></h4>

									<table id="evolucion_suscriptores">
										<?php if(!empty ($suscribers_evolution)): ?>

                                        <thead>
											<tr>
												<th>Día</th>
                                                <? foreach($suscribers_evolution as $se): ?>
												<th><?php echo date('M d', strtotime($se->fecha)); ?></th>
                                                <?php endforeach; ?>

											</tr>
										</thead>

										<tbody>
											<tr>
												<th>Suscriptores</th>
												<? foreach($suscribers_evolution as $se): ?>
												<td><?php echo $se->cuenta ?></td>
                                                <?php endforeach; ?>
											</tr>
										</tbody>
                                         <?php endif; ?>
									</table>
								</div> <!-- grafico -->
							</div> <!-- span6 -->

							<!-- COLUMNA 2 -->
							<div class="span6" >
								<div class="cuadro ultimas_campanas_box">
									<h4 class="titulo_cuadro">Ultimas campañas operadas<a href="#listado_campañas_operadas" class="btn btn-info btn_ver_mas">VER +</a></h4>

									<table id='ultimas_campanas' class="table table-striped">
										<thead class="titulo_tabla">
											<tr>
												<th>FECHA LLAMADA</th>
												<th><span class="center"># LLAMADAS</span></th>
												<th><span class="center">FALLIDAS</span></th>
											</tr>
										</thead>
										<tbody>
                                        	<?php if(!empty($last_worked_campaing_subs)): ?>
                                            	<?php foreach($last_worked_campaing_subs as $lwcs): ?>
                                            	<tr>
                                                    <td><a target="_blank" href="<?php echo base_url('campaign/detail_campaign/'.$lwcs->id); ?>"><?php echo (!empty($lwcs->name))?$lwcs->name:$lwcs->fecha; ?></a></td>
                                                    <td><span class="center"><?php echo $lwcs->CUENTA3 ?></span></td>
                                                    <td><span class="center"><?php echo ($lwcs->CUENTA0+$lwcs->CUENTA1+$lwcs->CUENTA2+$lwcs->CUENTA4) ?></span></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
										</tbody>
									</table>
								</div> <!-- ultimas_campanas_box -->
							</div> <!-- span6 -->
						</div> <!-- row-fluid -->
					</div> <!-- estadisticas_suscripcion -->
					<?php endif; ?>
					<?php if($app_data->tipo != 1): ?>
					<!--************** ***************************** ****************-->
					<!--************** SECCION ESTADISTICAS-DIFUSION ****************-->
					<div id="estadisticas_difusion" class="showestadisticas a_section">
						<h3 class="titulo_seccion">Estadísticas de la aplicación "<?php echo $app_data->title; ?>"</h3>

						<div class="row-fluid">
							<div class="span3 cuadro_info"><span class="numero_grande"><?php echo (!empty($nro_crated_campaigns))?$nro_crated_campaigns->cuenta:0; ?></span>Campañas creadas</div>
							<div class="span3 cuadro_info"><span class="numero_grande"><?php echo (!empty($maden_calls))?$maden_calls->cuenta:0; ?></span>Llamadas realizadas</div>
							<div class="span3 cuadro_info"><span class="numero_grande"><?php echo (!empty($maden_sms))?$maden_sms->cuenta:0; ?></span>SMS enviados</div>
							<div class="span3 cuadro_info"><span class="numero_grande naranja"><?php echo (!empty($get_user_earnings))?$get_user_earnings->cuenta:0; ?></span><strong>US$ ganados</strong></div>
						</div>
						<hr>
						<div class="row-fluid">
							<!-- COLUMNA 1 -->
							<div class="span6">
								<div class="cuadro">
									<h4 class="titulo_cuadro">Evolucion del uso de aplicación<a href="#estadisticas_ampliadas" class="btn btn-info btn_ver_mas">VER +</a></h4>

									<table id='evolucion_uso_aplicacion'>
										<?php if(!empty ($aplication_uses)): ?>

                                        <thead>
                                            <tr>
                                                <th>Día</th>
                                                <? foreach($aplication_uses as $au): ?>
                                                <th><?php echo date('M d', strtotime($au->fecha)); ?></th>
                                                <?php endforeach; ?>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <th>Usos</th>
                                                <? foreach($aplication_uses as $au): ?>
                                                <td><?php echo $au->cuenta ?></td>
                                                <?php endforeach; ?>
                                            </tr>
                                        </tbody>
                                        <?php endif; ?>
									</table>
								</div> <!-- grafico -->
							</div> <!-- span6 -->

							<!-- COLUMNA 2 -->
							<div class="span6" >
								<div class="cuadro" id="contenido_mas_usado_box">
									<h4 class="titulo_cuadro">Contenido más usado<a href="#estadisticas_uso_contenido" class="btn btn-info btn_ver_mas">VER +</a></h4>
									<table id='ultimas_campanas' class="table table-striped">
										<thead class="titulo_tabla">
											<tr>
												<th>NOMBRE</th>
												<th><span class="center">USOS</span></th>
												<th><span class="center">ÚLTIMO USO</span></th>
											</tr>
										</thead>
										<tbody>
                                        	<?php if(!empty($more_used_content)): ?>
												<?php foreach($more_used_content as $muc): ?>
                                                	<?php if(!empty($muc->last_date)): ?>
                                                        <tr>
                                                            <td><?php echo (!empty($muc->text_name))?$muc->text_name:$muc->audio_name; ?></td>
                                                            <td><span class="center"><?php echo $muc->count_this; ?></span></td>
                                                            <td><span class="center"><?php echo $muc->last_date; ?></span></td>
                                                        </tr>
                                                	<?php endif; ?>
                                                <?php endforeach; ?>
                                        	<?php endif; ?>
										</tbody>
									</table>
								</div> <!-- ultimas_campanas_box -->
							</div> <!-- span6 -->
						</div> <!-- row-fluid -->
					</div> <!-- estadisticas_suscripcion -->
					<?php endif; ?>

                    <!--************** ************************** ****************-->
                    <!--************** SECCION SUSCRIPTORES-AMBAS ****************-->
					<div id="suscriptores" class="a_section">
						<div class="span12">
							<h3 class="titulo_seccion">Suscriptores de la aplicación "<?php echo $app_data->title ?>" <a href="#" class="btn_filtro"></a></h3>
                            <div id="filtro" style="display:none">
                                <?php
                                    $attributes = array();
                                    $formopen = '';
                                    $attributes = array('class' => 'form-inline', 'id' => 'formfilters', 'name'=>'formfilters');
                                    $formopen = form_open('appmanager/apply_filter', $attributes);
                                ?>
                                <?php echo (!empty($formopen))?$formopen:'<form>'; ?>
                                    Seleccionar suscriptores que <?php echo ($app_data->tipo == 2 && $app_data->uses_special_pines == 0)? 'tengan un número de campañas realizadas: ' : 'tengan créditos';  ?>:
                                    <select name="cbo_operador_creditos" id="cbo_cantidad_creditos" class="input-small">
                                        <option value="=" selected>Igual a</option>
                                        <option value="<">Menor que</option>
                                        <option value=">">Mayor que</option>
                                    </select>
                                    <input type="text" name="cantidad_creditos" placeholder="# <?php echo ($app_data->tipo == 2 && $app_data->uses_special_pines == 0)? 'usos' : 'créditos';  ?>" class="input-small" style="height:30px;">
                                    <input type="hidden" name="id_wapp" value="<?php echo $app_data->id ?>" />
                                    <input type="submit" value="FILTRAR"  class="btn" id="btn-filtrar">
                                    <a href="<?php echo base_url('appmanager/'.$app_data->id.'/reset'); ?>" class="btn" id="reset-subscribers">Limpiar</a>
                                </form>
                            </div>
							<form name="suscribers" method="post" id="suscribers" action="">
                                <?php if(!($app_data->uses_special_pines == 0 && $app_data->tipo==2)): ?>
                                <div id="barra_acciones" style="display:none">
                                    <label>USD $</label><input type="number" name="recargar" value="0" />
                                    <a href="#" class="btn btn-warning recargar">RECARGAR SELECCIONADOS</a>
                                    <?php if($app_data->tipo==2): ?><a href="#" class="btn btn-danger borrar">BORRAR SELECCIONADOS</a><?php endif; ?>
                                </div>
                                <?php endif; ?>
                                <table class="table table-striped tabla_cerrada_abajo suscribers">
                                    <thead class="titulo_tabla">
                                        <tr>
                                            <?php if(!($app_data->uses_special_pines == 0 && $app_data->tipo==2)): ?>
                                            <th class="center">
                                                <input type="checkbox" value="" name="select_all" class="chk-select-all">
                                            </th>
                                            <?php endif; ?>
                                            <th>NOMBRE</th>
                                            <th><span class="center">TELÉFONO MÓVIL</span></th>
                                            <th><span class="center">CORREO ELECTRÓNICO</span></th>
                                            <th><span class="center"><?php echo ($app_data->tipo == 2 && $app_data->uses_special_pines == 0)? 'NRO DE CAMPAÑAS' : 'CRÉD. DISP.';  ?></span></th>
                                        </tr>
                                    </thead>
                                    <tbody class="topaginate_subs">
                                    	<?php if($app_data->tipo == 1): ?>
                                            <?php if(!empty($suscribers)): ?>
                                                <?php foreach($suscribers as $subs): ?>
                                                <tr>
                                                    <td><input type="checkbox" value="<?php echo $subs->id_contact ?>" class="chk-select" name="input_subscriber[]"></td>
                                                    <td><?php echo $subs->name ?></td>
                                                    <td style="text-align:center">(<?php echo $subs->phonecode ?>) <?php echo $subs->phone ?></td>
                                                    <td style="text-align:center"><?php echo $subs->email ?></td>
                                                    <td style="text-align:center"><?php echo $subs->credits ?></td>
                                                </tr>
                                                <?php endforeach; ?>

                                            <?php endif; ?>
                                       <?php endif; ?>


                                        <?php if($app_data->tipo == 2): ?>
                                            <?php if($app_data->uses_special_pines==1 && !empty($difusion_suscribers_pin)): ?>
                                                <?php foreach($difusion_suscribers_pin as $dif): ?>
                                                <tr>
                                                    <td><input type="checkbox" value="<?php echo $dif->id_user ?>" class="chk-select" name="input_subscriber[]"></td>
                                                    <td><?php echo $dif->fullname ?></td>
                                                    <td style="text-align:center">(<?php echo $dif->phonecode ?>) <?php echo $dif->phone ?></td>
                                                    <td style="text-align:center"><?php echo $dif->email ?></td>
                                                    <td style="text-align:center"><?php echo $dif->credits ?></td>
                                                </tr>
                                                <?php endforeach; ?>

                                            <?php elseif($app_data->uses_special_pines==0 && !empty($uses)): ?>
                                            	<?php foreach($uses as $dif): ?>
                                                <tr>
                                                    <td><?php echo $dif->fullname ?></td>
                                                    <td style="text-align:center">(<?php echo $dif->phonecode ?>) <?php echo $dif->phone ?></td>
                                                    <td style="text-align:center"><?php echo $dif->email ?></td>
                                                    <td style="text-align:center"><?php echo $dif->nro_usos ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                       <?php endif; ?>
                                    </tbody>
                                </table>
                                <input type="hidden" name="id_wapp" value="<?php echo $app_data->id ?>" />
                            </form>
							<div class="pagination pagination_subs pagination-centered">
							  <ul>

							  </ul>
							</div> <!-- pagination -->
						</div> <!-- span12 -->
					</div> <!-- suscriptores -->



                    <!--************** ***************************** ****************-->
                    <!--************** BIBLIOTECA DE CONTENIDOS-AMBAS ***************-->
					<div id="biblioteca" class="a_section">
						<h3 class="titulo_seccion">Biblioteca de contenidos para "<?php echo $app_data->title ?>"</h3>
						<div class="row-fluid">
							<!-- 1a COLUMNA -->
							<div class="span7 left-column" id="content-library-list">

								<p class="subtitulo">
		                        	Contenidos que tendrá disponible el usuario
		                        </p>
		                        <input type="button" class="btn delete-btn" name="delete-btn" value="Eliminar" style="display:none;" />
		                        <div class="clearfix"></div>

		                        <form name="delete_contents" id="delete_contents" method="post" action="">
									<table class="table table-striped">
										<thead>
			                                <tr>
			                                  <th><input type="checkbox" class="check-all" name="check-all"></th>
			                                  <th>Nombre</th>
			                                  <th class="center">Acción</th>
			                                </tr>
			                            </thead>
			                            <tbody class="topaginate topaginate_wiz">
			                                <!-- ESTA PARTE ES LA QUE SE REPITE EN UN CICLO -->
			                                <?php if(!empty($library)): ?>

			                                <?php foreach($library as $content): ?>
			                                <?php if($content->content_tipo == "text"): ?>
			                                	<?php $text = $content; ?>
			                                    <tr class="content_resume content_resume_text_<?php echo $text->text_id; ?>">
			                                        <td>
			                                            <input type="checkbox" name="check_content[]" value="<?php echo $text->text_id; ?>_<?php echo $text->content_tipo ?>" class="chk-select">
			                                        </td>
			                                        <td><?php echo $text->text_name; ?></td>
			                                        <td class="center">
			                                            <a href="javascript:;" class="item-edit-ico" title="Editar mensaje de audio" data-id="<?php echo $text->text_id; ?>" data-tipo="text"></a>
			                                            <a href="javascript:;" class="item-view-ico" title="Reproducir audio" data-id="<?php echo $text->text_id; ?>" data-tipo="text"></a>
			                                            <a href="#" class="item-delete-ico" title="Eliminar audio" data-id="<?php echo $text->text_id; ?>" data-tipo="text"></a>
			                                        </td>
			                                    </tr>
			                                    <tr class="content_view content_view_text_<?php echo $text->text_id; ?>" style="display: none;">
			                                        <td colspan="3">
			                                            <h4><?php echo $this->lang->line('nombre'); ?></h4>
			                                            <p class="name_field"><?php echo $text->text_name; ?></p>
			                                            <h4><?php echo $this->lang->line('the_message'); ?></h4>
			                                            <p class="message"><?php echo $text->text_text; ?></p>
			                                            <h4><?php echo $this->lang->line('voicena'); ?></h4>
			                                            <p class="voice" data-voice-id="<?php echo $text->text_voice_id ?>"><?php echo $text->voice_name; ?> - <?php echo $text->idioma; ?></p>

			                                        </td>
			                                    </tr>
			                                <?php elseif($content->content_tipo == "audio"): ?>
			                                	<?php $audio = $content; ?>
			                                	<tr class="content_resume content_resume_audio_<?php echo $audio->audio_id; ?>">
			                                        <td>
			                                            <input type="checkbox" name="check_content[]" value="<?php echo $audio->audio_id; ?>_<?php echo $audio->content_tipo ?>" class="chk-select">
			                                        </td>
			                                        <td><?php echo $audio->audio_name; ?></td>
			                                        <td class="center">
			                                            <a href="javascript:;" class="item-edit-ico" title="Editar mensaje de audio" data-id="<?php echo $audio->audio_id; ?>" data-tipo="audio"></a>
			                                            <a href="javascript:;" class="item-view-ico" title="Reproducir audio" data-id="<?php echo $audio->audio_id; ?>" data-tipo="audio"></a>
			                                            <a href="javascript:;" class="item-delete-ico" title="Eliminar audio" data-id="<?php echo $audio->audio_id; ?>" data-tipo="audio"></a>
			                                        </td>
			                                    </tr>
			                                    <tr class="content_view content_view_audio_<?php echo $audio->audio_id; ?>" style="display: none;">
			                                        <td colspan="3">
			                                            <h4><?php echo $this->lang->line('nombre'); ?></h4>
			                                            <p class="name_field"><?php echo $audio->audio_name; ?></p>
			                                            <audio id="player2" src="<?php  echo base_url('public/audios') ?>/<?php echo $audio->path; ?>" type="audio/mp3" controls></audio>
			                                        </td>
			                                    </tr>
			                                <?php endif; ?>
			                                <?php endforeach; ?>
			                               	<?php endif; ?>
			                            </tbody>
									</table>
								</form>
								<div class="pagination_wiz pagination">
									<ul>
										<li><a href="#">Prev</a></li>
										<li><a href="#">1</a></li>
										<li><a href="#">2</a></li>
										<li><a href="#">3</a></li>
										<li><a href="#">4</a></li>
										<li><a href="#">Next</a></li>
									</ul>
								</div>
								<?php if($app_data->tipo != 1): ?>
                		<label for="grabar_intro">
                        <input type="radio" style="margin-top: -4px;" value="intro" id="grabar_intro" name="grabar_intro_out" <?php if($app_data->intro==1) echo 'checked="checked"' ?>> Permitir a los usuarios grabar un intro
                    </label>
                    <label for="grabar_cierre">
                        <input type="radio" style="margin-top: -4px;" value="cierre" id="grabar_cierre" name="grabar_intro_out" <?php if($app_data->cierre==1) echo 'checked="checked"' ?>> Permitir a los usuarios grabar un cierre
                    </label>
                    <label for="no_intro_cierre">
                        <input type="radio" style="margin-top: -4px;" value="none" id="no_intro_cierre" name="grabar_intro_out" <?php if($app_data->cierre==0 && $app_data->intro==0) echo 'checked="checked"' ?>> No permitir intro ni cierre.
                    </label>
								<?php endif; ?>
							</div><!-- span 7 #content-library-list -->

							<!-- 2a COLUMNA -->
							<div class="span5 right-column" id="content-library-tabs">
								<p class="subtitulo">Grabar nuevos contenidos</p>
								<div class="tabbable tabbable-bordered tabs-left">
								  	<ul class="nav nav-tabs">
								    	<li class="active"><a href="#narrar" data-toggle="tab" id="tab-narrar" title="Configurar mensaje de audio"><img src="<?php echo base_url('assets/img') ?>/ico-text-2-speech-24.png" alt="Narrar"></a></li>
								    	<li><a href="#subir" data-toggle="tab" id="tab-subir" title="Subir audio"><img src="<?php echo base_url('assets/img') ?>/ico-upload-audio-24.png" alt="Narrar"></a></li>
								    	<li><a href="#grabar" data-toggle="tab" id="tab-grabar" title="Grabar audio"><img src="<?php echo base_url('assets/img') ?>/ico-rec-audio-24.png" alt="Narrar"></a></li>
								    	<!-- <li><a href="#seleccionar" data-toggle="tab" id="tab-seleccionar" ><img src="../<?php echo base_url('assets/img') ?>/ico-choose-audio-24.png" alt="Narrar"></a></li> -->
								  	</ul>
								  	<div class="tab-content">
								  		<!-- TAB NARRAR -->
		                                <div class="tab-pane active" id="narrar">
		                                	<?php
												//OPEN TEXT SPEECH FORM
												$attributes = array('name' => 'form-text-speach', 'id' => 'form-text-speach');
		                     					echo form_open('wizard/ajax_save_text_speach', $attributes);
											?>
		                                        <p><?php echo $this->lang->line("messagena"); ?></p>
		                                        <textarea name="txt_msg_to_speech" id="txt_narrar_small"></textarea>
		                                        <br />

                                            <p><?php echo $this->lang->line('datomessage'); ?></p>
                                            <select id="cbo_datos" name="cbo-datos" class="input-block-level">
                                               <option value="dato" disabled selected ><?php echo $this->lang->line('dato'); ?></option>
                                               <option value="name"><?php echo $this->lang->line('name'); ?></option>
                                               <?php if(!empty($dynamic)){ ?>
	                                               <?php foreach($dynamic as $fiel){ ?>
	                                               	<option value="<?php echo $fiel->name_fields; ?>"><?php echo $fiel->name; ?></option>
	                                               <?php } ?>
                                               <?php } ?>
                                            </select>

		                                        <br />
		                                        <p><?php echo $this->lang->line('voicena'); ?></p>
		                                        <select id="cbo_vozmini" name="cbo-vozmini" class="input-block-level">
		                                            <option value="0" disabled selected><?php echo $this->lang->line('voice'); ?></option>
		                                            <?php foreach($voice as $voi){ ?>
		                                            <option value="<?php echo $voi->id; ?>"><?php echo str_replace("IVONA 2 ","",$voi->name).' '.$voi->idioma; ?></option>
		                                            <?php } ?>
		                                        </select>

		                                        <a class="iframe voice_link" href="<?php echo base_url('apps/voice_view'); ?>">Ejemplo de las voces</a>
		                                        <input type="hidden" name="id_wapp" id="id_wapp" value="<?php echo set_value('id_wapp', $id); ?>" />
		                                        <input type="hidden" name="id_content_text" id="id_content_text" value="" />

		                                        <div style="text-align:center">
		                                            <input type="submit" value="Guardar narración" class="btn btn-block btn-guardar-narracion" />
                                                    <input type="reset" style="display:none;" value="Limpiar formulario" class="btn btn-block btn-guardar-narracion" />
		                                        </div>
		                                <?php echo form_close(); ?>
								    	</div>

								    	<!-- TAB SUBIR -->
										<div class="tab-pane" id="subir">
											<div id="dropdiv-small">
		                                	<div class="initial-audio-upload">
		                                       <?php
													//OPEN TEXT SPEECH FORM
													$attributes = array('name' => 'form-audio', 'id' => 'form-audio');
													echo form_open('wizard/upload_audio', $attributes);
												?>
		                                        <label><?php echo $this->lang->line('nombre') ?></label>
		                                        <input name="audio_name" type="text" class="input-block-level input-medium dynamic_name" value="">
		                                        <a href="javascript:void(0);">
		                                            <label class="cabinet">
		                                                <input type="file" class="file"  name="upload_audio" data-context="dropdiv-small" accept="audio/mp3" data-form-data='{"wapp":"<?php echo $app_data->id ?>", "user":"<?php echo $app_data->user_id ?>"}' />
		                                            </label>
		                                        </a>
		                                        <?php echo form_close(); ?>
		                                        <div id="progress_bar_audio">
		                                            <div class="bar" style="width: 0%;"></div>
		                                        </div>
								     		</div>

		                                	<!-- <div class="fakeupload">
		                                		<img src="../<?php echo base_url('assets/img') ?>/ico-audio-generic-medium.png" />
		                                		<p><a href="javascript:void(0)"></a><?php echo $this->lang->line('remplaceaudio'); ?>:<span></span></p>
		                                		<input type="submit" id="subir-audio" class="btn" value="SUBIR AUDIO" />
		                                	</div> -->

		                           	 	<!-- <input type="file" name="Filedata" /> -->

		                              <span class="msg" id="msg-upload">Selecciona un archivo de audio mp3 para subir, no debe pesar más de 2Mbs.</span>
		                           </div> <!-- dropdiv-small -->
		                           <div id="upload-audio">
		                           	Debes ser propietario de los derechos de autor o tener permiso de usar el contenido que subas. <a href="#MAS INFORMACION">Más información</a>
		                           </div>
										</div>


										<!-- TAB GRABAR -->
		                                <?php
											$the_url_record = base_url('wizard/add_audio_record');
											$the_url_record = urlencode($the_url_record);
										?>
										<div class="tab-pane" id="grabar">
		                                    <object width="100%" height="100%" type="application/x-shockwave-flash" data="<?php echo base_url('assets/swf') ?>/kkatoo-audio-recorder_small.swf?saveurl=<?php echo $the_url_record ?>&id_wapp=<?php echo $app_data->id ?>">
		                                    <param name="movie" value="<?php echo base_url('assets/swf') ?>/kkatoo-audio-recorder_small.swf?saveurl=<?php echo $the_url_record ?>&id_wapp=<?php echo $app_data->id ?>" />
		                                    <param name="flashvars" value="saveurl=<?php echo $the_url_record ?>&id_wapp=<?php echo $app_data->id ?>">
		                                    <param name="quality" value="high" />
		                                    <param name="wmode" value="" />
		                                    </object>
										</div>

										<!-- TAB SELECCIONAR
										<div class="tab-pane" id="seleccionar">
											<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et, sequi ullam nihil sapiente accusamus ipsum perspiciatis facere quae veritatis necessitatibus facilis ex odio eaque magni minus reiciendis porro dolorum temporibus!</p>
										</div>-->
								  	</div>
								</div>
							</div><!-- .span5 #content-library-tabs -->
						</div><!-- .row -->
					</div> <!-- biblioteca -->


					<!--************** ***************************** ***************-->
					<!--************** OPERAR APLICACION-SUSCRIPCION ***************-->
					<div id="operar">
							<!-- LINK DIRECTO AL PASO 1/2/3 DE ESTA APLICACION -->
					</div>

					<!--************** ***************************** ***************-->
					<!--************** EDITAR APLICACION-SUSCRIPCION ***************-->
					<div id="editar">
							<!-- LINK DIRECTO AL WIZARD DE ESTA APLICACION -->
					</div>

					<!--************** ********************************* ***************-->
					<!--************** PUBLICITAR APLICACION-SUSCRIPCION ***************-->
					<div id="publicitar" class="a_section">
						<p style="padding-top:40px;">Estamos trabajando en esta sección para apoyarte en la promoción de tu aplicación.</p>
					</div>

					<!--************** ******************************************** ***************-->
					<!--************** GESTIONAR PAGOS DE LA APLICACION-SUSCRIPCION ***************-->
					<div id="gestionar_pagos" class="a_section">
						<h3 class="titulo_seccion">Gestionar pagos para "<?php echo $app_data->title ?>"</h3>
						<p>Actualmente, por el uso de esta aplicación tienes un saldo pendiente por redimir de <strong>US$<?php echo (!empty($get_user_earnings))?$get_user_earnings->cuenta:0; ?></strong>. Como acumulado de todas las aplicaciones de las cuales eres dueño, tienes <strong>US$<?php echo (!empty($get_user_earnings_by_userid))?$get_user_earnings_by_userid->cuenta:0; ?></strong> pendientes por redimir. Puedes hacer una de dos cosas con este saldo:</p>
						<ol>
							<li>Convertirlo en créditos para ser usado en tus aplicaciones. Tanto las que creas como las que usas (de todas formas siempre podrás redimirlos cuando quieras).</li>
							<li>Redimirlo para que te sea consignado en una cuenta a tu nombre (todos los costos generados corren por tu cuenta).</li>
						</ol>
						<p>A continuación selecciona cuál de las dos anteriores acciones deseas hacer:</p>
						<p>El saldo <strong>mínimo</strong> para redimir debe ser de <strong>USD $<?php echo MINIMUN_EARNINGS_TO_PAY; ?></strong></p>
						<!-- ACORDEON PARA LA PARTE DE REDIMIR PAGOS -->
						<div class="accordion" id="accordion2">
							<div class="accordion-group">
								<div class="accordion-heading">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
										Sumar saldo a mis créditos
									</a>
								</div>
								<div id="collapseOne" class="accordion-body collapse">
									<div class="accordion-inner">
										<?php
											$attributes = array('name' => 'sum_credits_to_me', 'id' => 'sum_credits_to_me');
											echo form_open('appmanager/add_earnings_to_my_credits', $attributes);
										?>
                                        	<input type="submit" name="sum_by_app" class="btn sumar-uno input-block-level span6" value="Sumar US$<?php echo (!empty($get_user_earnings))?$get_user_earnings->cuenta:0; ?>" />


                                        	<input type="submit" name="sum_by_user" class="btn sumar-todo span6" value="Sumar US$<?php echo (!empty($get_user_earnings_by_userid))?$get_user_earnings_by_userid->cuenta:0; ?>" />
                                        	<input type="hidden" name="id_wapp" value="<?php echo $app_data->id ?>" />
                                        </form>
									</div>
								</div>
							</div>

							<div class="accordion-group">
								<div class="accordion-heading">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
										Redimir saldo a mi cuenta bancaria
									</a>
								</div>
								<div id="collapseTwo" class="accordion-body collapse">
									<div class="accordion-inner">
										<p>Por favor selecciona el banco al cual pertenece tu cuenta y a continuación escribe el número de la misma. Recuerda que cualquier cargo que la entidad bancaria aplique por cualquier concepto, debe ser asumido por ti.</p>
										<?php
											$attributes = array('name' => 'sum_credits_to_me', 'id' => 'sum_credits_to_me', 'class'=>'form-inline');
											echo form_open('appmanager/init_redeem_earnings_by_transaction', $attributes);
										?>
											<select name="bancos" id="bancos">
												<option value="bancolombia">Bancolombia</option>
												<option value="citibank">Citi Bank</option>
												<option value="grupoaval">Grupo Aval</option>
												<option value="bancoagrario">Banco Agrario</option>
												<option value="davivienda">Davivienda</option>
											</select>
                                            <select name="tipo_cuenta" id="tipo_cuenta">
												<option>Ahorros</option>
                                                <option>Corriente</option>
											</select>
											<input type="text" id="num-cuenta" name="num_cuenta" placeholder="# DE CUENTA" style="height:30px;">
											<select name="type_redeem" id="a-redimir">
												<option value="sum_by_app">US$ <?php echo (!empty($get_user_earnings))?$get_user_earnings->cuenta:0; ?></option>
												<option value="sum_by_user">US$ <?php echo (!empty($get_user_earnings_by_userid))?$get_user_earnings_by_userid->cuenta:0; ?></option>
											</select>
											<input type="hidden" name="id_wapp" value="<?php echo $app_data->id ?>" />
											<input type="submit" class="btn redimir" value="REDIMIR A MI CUENTA" style="margin:20px auto; display:block;"  />
                                        </form>
									</div>
								</div> <!-- collapseTwo -->
							</div>  <!-- accordion-group -->
						</div>  <!-- accordion2 -->
                        <div class="tabla_saldos_redimidos">
                        	<h3>Ultimas ganancias redimidas.</h3>
                        	<table class="table">
                              <thead>
                              	<tr>
                                	<th>Fecha</th>
                                    <th>Método</th>
                                    <th>Entidad</th>
                                    <th>Tipo Cuenta</th>
                                    <th>Nro. Cuenta</th>
                                    <th>Valor</th>
                                    <th>Estado</th>
                                    <th>Fecha Pago</th>
                                </tr>
                              </thead>
                              <tbody>
                              	<?php if(!empty($get_redeemed_all)): ?>
                                	<?php foreach($get_redeemed_all as $gra): ?>
                              		<tr>
                                    	<td><?php echo $gra->date ?></td>
                                        <td><?php echo $gra->type ?></td>
                                        <td><?php echo $gra->entidad ?></td>
                                        <td><?php echo $gra->tipo_de_cuenta ?></td>
                                        <td><?php echo $gra->nro_cuenta ?></td>
                                        <td>USD $<?php echo $gra->valor_redimido ?></td>
                                        <td><?php echo strtoupper($gra->state); ?></td>
                                        <td><?php echo $gra->date_payment ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                              	<?php endif; ?>
                              </tbody>
                            </table>
                        </div>
					</div>	<!-- gestionar_pagos -->
				</div>
			</div>
		</div>
	</div>

	<!-- Modal para mensajes -->
	<div id="mensajes" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="mensajesLabel">Error</h3>
		</div>
		<div class="modal-body">
			<p>
				<div class="alert alert-error mensajesdeerror">
					Error al enviar los datos
				</div>
			</p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
		</div>
	</div>
	<!-- #Modal para mensajes -->

	<!-- LE JAVASCRIPT -->
	<script src="<?php echo base_url('assets/js/vendor/jquery-1.8.3.min.js') ?>"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="<?php echo base_url('assets/fileupload'); ?>/jquery.iframe-transport.js"></script>
    <script src="<?php echo base_url('assets/fileupload'); ?>/jquery.fileupload.js"></script>
    <script src="<?php echo base_url('assets/js/wizard'); ?>/jquery.simplePagination.js"></script>
	<script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/appmanager/jquery.gvChart-1.1.min.js') ?>"></script>
	<script src="<?php echo base_url('assets/js/si.files.js') ?>"></script> <!-- para la subida de archivos -->
	<script type="text/javascript">
		//VARIABLES GLOBALES QUE SERÁN UTILIZADAS EN EL JS
    	var $line_nombre  		= '<?php echo $this->lang->line('nombre') ?>';
		var $line_the_message 	= '<?php echo $this->lang->line('the_message') ?>';
		var $line_voicena 		= '<?php echo $this->lang->line('voicena') ?>';
		var $app_data_id		= '<?php echo $app_data->id ?>';
		var $sure_delete_library= '<?php echo $this->lang->line('sure_delete_library'); ?>';
		var $name_not_empty 	= '<?php echo $this->lang->line('name_not_empty'); ?>';

		// LOAD THE SWF OBJECT DYNAMICALY
		var createSwfObject = function() {

			var src= '<?php echo base_url('assets/swf/kkatoo-audio-recorder_small.swf') ?>?saveurl=<?php echo $the_url_record ?>&id_wapp=<?php echo $app_data->id ?>';
			var attributes = {id: 'myid', 'class': 'myclass', width: '100%', height: '100%'};
			var parameters = {wmode: '', flashvars:"saveurl=<?php echo $the_url_record ?>&id_wapp=<?php echo $app_data->id ?>"};

			var i, html, div, obj, attr = attributes || {}, param = parameters || {};
			attr.type = 'application/x-shockwave-flash';
			if (window.ActiveXObject) {
				attr.classid = 'clsid:d27cdb6e-ae6d-11cf-96b8-444553540000';
				param.movie = src;
			}
			else {
				attr.data = src;
			}
			html = '<object';
			for (i in attr) {
				html += ' ' + i + '="' + attr[i] + '"';
			}
			html += '>';
			for (i in param) {
				html += '<param name="' + i + '" value="' + param[i] + '" />';
			}
			html += '</object>';
			div = document.createElement('div');
			div.innerHTML = html;
			obj = div.firstChild;
			div.removeChild(obj);
			return obj;
		};

	</script>
    <script src="<?php echo base_url('assets/js/appmanager/main.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/wizard/libreria.js') ?>"></script>

	<script>
		//INICIALIZACION GRAFICOS ESTADISTICOS
		gvChartInit();
 	</script>

   	<script>
		//INICIALIZACION GESTOR DE UPLOADS
		SI.Files.stylizeAll();

		/********************************************************
		** SCRIPTS PARA PERSONALIZAR LOS GRÁFICOS ESTADÍSTICOS
		********************************************************/
     	<?php if($app_data->tipo == 1): ?>
		$('#evolucion_suscriptores').gvChart({
			chartType: 'ColumnChart',
			gvSettings: {
				vAxis: {title: 'Suscriptores'},
				// hAxis: {title: 'Mes'},
				width: '100%',
				height: 300,
				backgroundColor: 'transparent',
				left: 300,
				top: 0,
				fontName:'Open sans',
				chartArea:{left:'13%',top:'13%',width:"80%",height:"75%"},
				legend:{position: 'none'},
				colors:['#ffad31','#494949', '#747474', '#c7c7c7']
			},
		});
		<?php endif; ?>

		<?php if($app_data->tipo != 1): ?>
		$('#evolucion_uso_aplicacion').gvChart({
			chartType: 'ColumnChart',
			gvSettings: {
				vAxis: {title: 'Usos de la aplicación'},
				// hAxis: {title: 'Mes'},
				width: '100%',
				height: 300,
				backgroundColor: 'transparent',
				left: 300,
				top: 0,
				fontName:'Open sans',
				chartArea:{left:'13%',top:'13%',width:"80%",height:"75%"},
				legend:{position: 'none'},
				colors:['#ffad31','#494949', '#747474', '#c7c7c7']
			},
		});
	<?php endif; ?>

	//SUCRIPTORES
	var check_all = $('input[name="select_all"]');

	check_all.on('change', function(){
		if($(this).is(':checked')){
			$('input[name="input_subscriber[]"]').attr('checked', true);
			if( $('input[name="input_subscriber[]"]:checked').length > 0){
				show_actions_btns();
			}else{
				hide_actions_btns();
			}
		}else{
			$('input[name="input_subscriber[]"]').attr('checked', false);
			hide_actions_btns();
		}

		background();
	});

	$('form[name="suscribers"]').on('change', 'input[name="input_subscriber[]"]', function(){

		if( $('input[name="input_subscriber[]"]:checked').length > 0){
			show_actions_btns();
		}else{
			hide_actions_btns();
		}

		background();

	});

	function show_actions_btns(){
		$('#barra_acciones').stop().fadeIn();
	}

	function hide_actions_btns(){
		$('#barra_acciones').stop().fadeOut();
	}

	function background(){
		$('input[name="input_subscriber[]"]:checked').parent().addClass('selected').siblings().addClass('selected');
		$('input[name="input_subscriber[]"]').not(':checked').parent().removeClass('selected').siblings().removeClass('selected');
	}

	//CUANDO SE TERMINE DE CARGAR LA PÁGINA
	$(document).ready(function($){
		function show_section(_this){
			$('.a_section').hide();
			$(_this).show();
		}

		/********************************************************
		** SCRIPTS PARA NAVEGAR EN LAS SECCIONES DE LA PAGINA
		********************************************************/
		$("#btn_estadisticas").on('click', function(event){
			show_section($('.showestadisticas'));
			event.preventDefault();
		});

		$("#btn_suscriptores").on('click', function(event){
			show_section($('#suscriptores'));
			event.preventDefault();
		});
		$("#btn_biblioteca").on('click', function(event){
			show_section($('#biblioteca'));
			event.preventDefault();
		});

		$("#btn_pagos").on('click', function(event){
			show_section($('#gestionar_pagos'));
			event.preventDefault();
		});

		$("#btn_publicitar").on('click', function(event){
			show_section($('#publicitar'));
			event.preventDefault();
		});

		<?php if(!empty($load_subscribe)): ?>
			show_section($('#suscriptores'));
		<?php else: ?>
			show_section($('.showestadisticas'));
		<?php endif; ?>

		/********************************************************
		** SCRIPT PARA PRENDER Y APAGAR EL FILTRO
		********************************************************/

		$(".btn_filtro").on('click', function(event){
			$("#filtro").stop().fadeToggle();
		});

		//Funciones de suscripcion, RECARGAR Y BORRAR
		$('.recargar').on('click', function(event){
			var form 		= $('form[name="suscribers"]');
			form.attr('action', '<?php echo base_url('appmanager/add_credits'); ?>');
			form.submit();
			event.preventDefault();
		});

		$('.borrar').on('click', function(event){
			if(confirm("¿Seguro desea retirar a los usuarios seleccionados?")){
				var form 		= $('form[name="suscribers"]');
				form.attr('action', '<?php echo base_url('appmanager/remove_user_from_diffusion_pin'); ?>');
				form.submit();
			}
			event.preventDefault();
		});

	});


	</script>
</body>

</html>
