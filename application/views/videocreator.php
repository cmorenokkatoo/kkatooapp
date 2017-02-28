<?php
	$this->load->view("globales/head_wizard");
	$this->load->view('globales/mensajes');
	$this->lang->load('wizard');
?>
<?php 
 	// INFO PARA INICIAR EL WIZARD
	$app_exits = (!empty($app_data))?TRUE:FALSE;
	$nombre_app = "";
	$slogan = "";
	// $descripcion = "";
	$video = "";
	$dominio = "";
	$percent = "";
	$id = "";
	$privacidad = "";
	$categoria 	= "";
	
	if($app_exits){
		$nombre_app = $app_data->title;
		$slogan = strip_tags($app_data->slogan_html);
		// $descripcion = $app_data->description;
		$video = $app_data->video_html;
		$dominio = $app_data->url_landing;
		$percent = $app_data->price;
		$id		 = $app_data->id;
		$privacidad = $app_data->private;
		$categoria 	= $app_data->category;
	}
?>
	<div id="wrapper">
		  <!-- HEADER DE LA PAGINA -->
          <div id="brand-header" class="navbar navbar-fixed-top">
             <div class="navbar-inner">
                <!-- AVISO DE CREDITOS DISPONIBLES -->
                <div id="navbar-container" class="container">               
                    <div class="row" style="margin-left:0;">
                      <!-- Para logo -->
                      <div class="span6">
                        <?php 
                            $logo = $this->specialapp->create_logo('logo-main-header.png');
                        ?>
                        <a class="brand" href="<?php echo $logo->brand_url; ?>">
                            <img src="<?php echo  $logo->brand_img ?>" alt="<?php echo $logo->brand_title ?>" style="height:60px;" />
                        </a>
                      </div>
    
                     <?php $this->load->view('utils/user_dropdown') ?>
    
                   </div> <!-- .row -->
                </div> <!-- #navbar-container -->
             </div>
          </div> <!-- #brand-header -->

			<!-- <marquee behavior="slide" direction="left" id="marquee"></marquee> -->
			<style>
			#marquee
			{
				padding: .5em;
				background: #FEEECF;
				width: 55%;
				position: relative;
				left: 0%;
				top: 2em;
			}
			</style>	
		<!-- CUERPO APP -->
		<div class="container" id="body_container">
			<header>
				<h1>Creador de aplicaciones</h1>
                <h4>
					<?php if($app_data->tipo == 1): ?>
                    	Aplicación por Suscripción
                    <?php elseif($app_data->tipo == 2): ?>
                    	Aplicación por Difusión
                    <?php endif; ?>
                </h4>
			</header>
			
            <?php 
			$attributes = array('class' => 'form-wizard-personalization', 'id' => 'form-wizard-personalization');
			echo form_open_multipart('wizard/save_info_app', $attributes);
			?>
			<!-- SECCION 1 - PERSONALIZACION VISUAL -->		
			<section id="visual-customize">
				<h2>Personalización visual</h2>
        
        <h4>Los campos marcados con * son obligatorios.</h4>
        
				<div class="row section-content">
					<!-- 1a COLUMNA -->
					<div class="span6 left-column">
						<div class="control-group">
						
							<div class="control-group">
								<label class="control-label"><strong>*Título:</strong></label>
								<div class="controls">
									<input type="text" class="input-medium input-block-level" name="nombre_app" id="nombre_app" value="<?php echo set_value('nombre_app', $nombre_app); ?>"> 
								</div>
								<hr>
							</div>
							<div class="control-group">
								<label class="control-label"><strong>*Diseño:</strong></label>
								<div class="controls">
                                    <input class="input-block-level input-medium fileupload" name="img_fondo" id="img_fondo" type="file" data-form-data='{"type": "img_fondo", "wapp":"<?php echo $app_data->id ?>", "user":"<?php echo $app_data->user_id ?>"}'  required/>									
                                    <?php if(!empty($app_data->fondo_html)):  ?>
                                    	<a href="<?php echo base_url('public/'.$app_data->fondo_html); ?>" class="green imagen_app">
                                        	<?php echo $this->lang->line('imagen_exits'); ?>
                                        </a>
                                    <?php endif; ?>
								</div>
								<label class="control-label">
                  						<br />
                  					<p>Sube una imagen para usar como fondo de tu aplicación. Guíate por nuestra imagen ubicada a la derecha. Dimensiones: 1000px ancho x 650px alto</p></label>								
							</div>
							<hr>
							<!--div class="control-group">                                                              //** CAMPOS SLOGAN, LOGOTIPO, DESCRIPCIÓN **//
								<label class="control-label">Logotipo (340px ancho x 340px de alto):</label>
								<div class="controls">
                                    <input name="img_logotipo" id="img_logotipo" class="input-block-level input-medium fileupload" type="file" data-form-data='{"type": "img_logotipo", "wapp":"<?php echo $app_data->id ?>", "user":"<?php echo $app_data->user_id ?>"}' />
                                    <?php if(!empty($app_data->logo)):  ?>
                                    	<a href="<?php echo base_url('public/'.$app_data->logo); ?>" class="green imagen_app">
                                        	<?php echo $this->lang->line('imagen_exits'); ?>
                                        </a>
                                    <?php endif; ?>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">* Slogan:</label>
								<div class="controls">
									<input type="text" class="input-block-level input-medium" name="slogan" id="slogan" value="<?php echo set_value('slogan', $slogan); ?>" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">* Descripción de la aplicación:</label>
								<div class="controls">
									<textarea class="input-block-level textarea" name="descripcion" id="descripcion"><?php echo set_value('descripcion', $descripcion); ?></textarea>
								</div>
							</div-->
							<div class="control-group">
								<label class="control-label"><strong>Video (opcional):</strong></label>
								<div class="controls">
									<input type="text" class="input-block-level input-medium" name="video" id="video" value="<?php echo set_value('video', $video); ?>" /> 
								</div>
								<label class="control-label">Puedes agregar la URL de tu video explicativo de la aplicación, nosotros lo enlazaremos con el botón para reproducirlo dentro de la landing page. </label>
							</div>
							<hr>
							<!--div class="control-group">                                                                             //** CAMPO IMAGEN SECUNDARIA **//
								<label class="control-label">Imagen secundaria (480px ancho x 480px de alto):</label>
								<div class="controls">
                                    <input class="input-medium input-block-level fileupload" type="file" data-form-data='{"type": "img_secundaria", "wapp":"<?php echo $app_data->id ?>", "user":"<?php echo $app_data->user_id ?>"}' name="img_secundaria" id="img_secundaria" />
                                    <?php if(!empty($app_data->secondary_img_html)):  ?>
                                    	<a href="<?php echo base_url('public/'.$app_data->secondary_img_html); ?>" class="green imagen_app">
                                        	<?php echo $this->lang->line('imagen_exits'); ?>
                                        </a>
                                    <?php endif; ?>
								</div>
							</div-->
							<div class="control-group">
								<label class="control-label"><strong>*Marketplace:</strong></label>								
								<div class="controls">
                                    <input class="input-medium input-block-level fileupload" type="file" data-form-data='{"type": "img_para_market", "wapp":"<?php echo $app_data->id ?>", "user":"<?php echo $app_data->user_id ?>"}' name="img_para_market" id="img_para_market" required/>
                                    <?php if(!empty($app_data->image)):  ?>
                                    	<a href="<?php echo base_url('public/'.$app_data->image); ?>" class="green imagen_app">
                                        	<?php echo $this->lang->line('imagen_exits'); ?>
                                        </a>
                                    <?php endif; ?>
								</div>
								<label class="control-label">Debes subir una imagen reducida del diseño original de tu aplicación (medidas: 202px de ancho X 152px de alto), esta imagen será usada en la tienda de aplicaciones de kkatoo. </label>
							</div>
						</div>
					</div> <!-- #span6 1a columna de personalizacion visual-->
					<!-- 2a COLUMNA -->
					<div class="span6 right-column">
						<div id="img-container" class="hidden-phone"></div>
						<div id="img-container-mask" class="hidden-phone" style="display:none"></div><br>
						<div id="img-containermarket" class="hidden-phone"></div>
						<div id="img-container-mask" class="hidden-phone" style="display:none"></div>
					</div>
				</div> <!-- .row -->
			</section> <!-- #visual-customize -->
			<!-- SECCIÓN 2 - PERSONALIZACIÓN AVANZADA -->
			<section id="advanced-customize">
				<h2>Personalización avanzada</h2>
				<div class="row">

					<!-- 1a COLUMNA -->
					<div class="span6 left-column">
						<div class="control-group">
							<label class="control-label"><strong>*Categoría:</strong></label>
							<div class="controls">
                                <select class="input-block-level categorizador" name="categorias" id="categorias">
								<?php  foreach($category as $cat): ?>
                                    <option value="<?php echo $cat->id ?>" <?php echo set_select('categorias', $cat->id); ?> <?php if($categoria==$cat->id) echo "selected" ?>><?php echo $cat->name; ?></option>
                                <?php endforeach; ?>
                                </select><hr>
                                <div class="control-group">
							<label class="control-label"><strong>*Tipo:</strong></label>
							<div class="controls">
								<label class="radio" for="radio">
									<input type="radio" id="privada" name="privacidad" checked="checked" value="0" <?php echo set_radio('privacidad', '1'); ?> <?php if($privacidad=="0") echo "checked" ?> />
									<span>Pública</span> 
								</label>
								<label class="radio" for="radio">
									<input type="radio" id="publica" name="privacidad" value="1" <?php echo set_radio('privacidad', '0'); ?> <?php if($privacidad=="1") echo "checked" ?> />
									<span>Privada</span> 
								</label>
								<label class="control-label">
									Una aplicación es pública cuando aparece en el <strong>Marketplace de kkatoo</strong> con nuestra marca. La aplicación es privada cuando no aparece en el <strong>Marketplace y es 'Marca Blanca'.</strong>
								</label>
								<!--label class="pull-left">Dominio propio:&nbsp;</label>
								<input type="text" class="pull-left input-xlarge" placeholder="http://www.midominio.com" name="dominio" id="dominio" value="<?php echo set_value('dominio', $dominio); ?>" /--> 
							</div>
						</div>
							</div>
						</div>
					</div>

					<!-- 2a COLUMNA -->
					<div class="span6 right-column columa-derecha-puntos">
						<div class="control-group">
                                	<br>
                                	<label class="control-label"><strong>Campos Personalizados (Opcional):</strong></label>
                                	<p>Las aplicaciones por defecto piden para cada contacto el nombre y teléfono, los campos dinámicos son datos adicionales que los contactos  necesitan tener para el correcto uso de la aplicación,  por ejemplo si la aplicación es de cumpleaños, necesitamos un dato que sería fecha de cumpleaños.</p>
																	<a class="link btn_dynamic" style="cursor: pointer;margin-bottom: 1em;display: block;" data-mas="Agregar <strong>otro</strong> campo dinámico" data-normal="Agregar campos dinámicos">Agregar campos dinámicos</a>
																</div>
																<div class="campos_dinamicos">
                                    <?php if(!empty($dynamic)): ?>
                                    	<?php foreach($dynamic as $dyn): ?>
                                        	<h3 data-dynamic="false"><a href="#" class="delete_field_database" data-dynamic-id="<?php echo $dyn->id ?>" data-app-id="<?php echo $app_data->id; ?>">X</a><?php echo $dyn->name ?></h3>
                                          <div class="content_field">
                                              <label><?php echo $this->lang->line('nombre') ?></label>
                                              <input name="dynamic_name[]" type="text" class="input-block-level input-medium dynamic_name" value="<?php echo $dyn->name ?>">
                                              <label><?php echo $this->lang->line('tipo') ?></label>
                                              <?php 
																								$el_tipo = "";
																								$show_default = FALSE;
																								$default_es = array();
																								switch($dyn->tipo){
																									case 1:
																										$el_tipo = "Número";
																										$show_default = FALSE;
																									break;
																									case 2:
																										$el_tipo = "Texto";
																										$show_default = FALSE;
																									break;
																									case 3:
																										$el_tipo = "Fecha";
																										$show_default = FALSE;
																									break;
																									case 4:
																										$el_tipo = "Multiples valores, una selección";
																										$show_default = TRUE;
																										$default_es = json_decode($dyn->default);
																									break;
																									case 5:
																										$el_tipo = "Multiples valores, varias selecciones";
																										$show_default = TRUE;
																										$default_es = json_decode($dyn->default);
																									break;
																								}
																							?>
                                              <input type="text" name="fake_type[]" class="input-block-level input-medium" readonly="readonly" value="<?php echo $el_tipo; ?>" />
                                              <input type="hidden" name="dynamic_type[]" class="input-block-level input-medium" value="<?php echo $dyn->tipo; ?>" />
                                              <label <?php if($show_default) echo 'style="display:block;"'; ?> class="default_label"><?php echo $this->lang->line('valor') ?></label>
                                              <textarea <?php if($show_default) echo 'style="display:block;"'; ?> name="dynamic_default[]" class="default_input input-block-level texarea"><?php
																							if($show_default){ 
																								if(!empty($default_es)):
																									if(is_array($default_es)):
																										foreach($default_es as $def):
																											echo trim($def)."\n";
																										endforeach;
																									endif;
																								endif;
																							}
																							?>
																							</textarea>
                                              <input name="dynamic_id[]" type="hidden" value="<?php echo $dyn->id ?>">
                                          </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <input type="hidden" name="id_wapp" id="id_wapp" value="<?php echo set_value('id_wapp', $id); ?>" />
                                </div>
						
					</div>

				</div>
			</section>


			<!-- SECCION 3 - PERSONALIZAR PAGO -->
			<section id="payment-customize">
				<h2>Personalizar pago</h2>
				<div class="one-column">
<!--					<p><strong>El modelo de costeo funciona así:</strong></p>-->
					
					<!-- <p>Nosotros te damos un precio base por minuto de $100 a cualquier destino fijo y celular, este precio lo pagamos a nuestros proveedores. A partir de este  tú decides que precio establecer para el usuario final. 
					De la diferencia entre el precio base  ($100) y el precio que tu establezcas, kkatoo por el uso de la aplicación toma  un 30%. 
					Por ejemplo: si decides cobrar $200 por la llamada, kkatoo toma $130 ($100 de costo y $30 de comisión) los $70 restantes serían tu ganancia. </p> -->
<!--					<hr>-->
					<label class="control-label"><strong>*Porcentaje de Ganancia:</strong></label>
					<input type="text" class="input-mini" name="percent" id="percent" value="<?php echo (set_value('percent', $percent)); ?>" placeholder="" requried/> <span class="percent"><strong>%</strong></span>                  
                    <!-- <br><a href="/conversor.php" target="_blank" onClick="window.open(this.href, this.target, 'width=440,height=270'); return false;">Usar Conversor</a> -->
                    <?php if($app_data->tipo == 1): ?>
                    <div class="packages">
                    	<label for="package">Ingresa los paquetes de minutos que pueden comprar tus usuarios finales, cada que ingreses un número <strong>Presiona enter</strong></label>
                        <input type="text" value="0" maxlength="3" name="package" id="package" />
                        <br><br>
                        <p><strong>Tienes estos paquetes para tu aplicación de suscripción: </strong></p>
                        <ul>
                        	<?php if(!empty($packages)): ?>
                            <?php foreach($packages as $package): ?>
                            <li class="package_del amount_<?php echo $package->amount; ?>" data-amount="<?php echo $package->amount; ?>" data-id-package="<?php echo $package->id; ?>">
                        		<a href="#">x</a><?php echo $package->amount; ?>
                        	</li>
                            <?php endforeach; ?>
                        	<?php endif; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
				</div> <!-- .one-column -->
			</section>
			</form>

			<!-- SECCION 4 - LIBRERIA DE CONTENIDOS -->
			<section id="content-library">
				<h2>Agregar contenidos</h2>
				<div class="row">

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
                            <tbody class="topaginate_wiz topaginate">
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
                                            <audio id="player2" src="<?php  echo base_url('public/audios') ?>/<?php echo $audio->path; ?>" type="audio/mp3" controls="controls"></audio>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <?php endforeach; ?> 
                               	<?php endif; ?>
								
                            </tbody>
						</table>
						</form>
						<div class="pagination">
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
                  <!--label for="grabar_intro">
                  	<input type="radio" style="margin-top: -4px;" value="intro" id="grabar_intro" name="grabar_intro_out" <?php if($app_data->intro==1) echo 'checked="checked"' ?>> Permitir a los usuarios grabar un intro
                  </label>
                  <label for="grabar_cierre">
                  	<input type="radio" style="margin-top: -4px;" value="cierre" id="grabar_cierre" name="grabar_intro_out" <?php if($app_data->cierre==1) echo 'checked="checked"' ?>> Permitir a los usuarios grabar un cierre
                  </label>
                  <label for="no_intro_cierre">
                      <input type="radio" style="margin-top: -4px;" value="none" id="no_intro_cierre" name="grabar_intro_out" <?php if($app_data->cierre==0 && $app_data->intro==0) echo 'checked="checked"' ?>> No permitir intro ni cierre.
                  </label-->
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
                                        <?php //if(!empty($dynamic)){ ?>
                                            <p><?php echo $this->lang->line('datomessage'); ?></p>
                                            <select id="cbo_datos" name="cbo-datos" class="input-block-level">
                                               <option value="dato" disabled selected ><?php echo $this->lang->line('dato'); ?></option>
                                               <option value="name"><?php echo $this->lang->line('name'); ?></option>
                                               <!--
                                               <?php foreach($dynamic as $fiel){ ?>  
                                               <option value="<?php echo $fiel->name_fields; ?>"><?php echo $fiel->name; ?></option>
                                               <?php } ?>-->
                                            </select>
                                          <?php //} ?>
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
                                    <object width="100%" height="100%" type="application/x-shockwave-flash" data="<?php echo base_url('assets/swf') ?>/kkatoo-audio-recorder_small.swf?saveurl=<?php echo $the_url_record ?>&id_wapp=<?php echo $app_data->id ?>&cache=<?php echo time(); ?>">
                                    <param name="movie" value="<?php echo base_url('assets/swf') ?>/kkatoo-audio-recorder_small.swf?saveurl=<?php echo $the_url_record ?>&id_wapp=<?php echo $app_data->id ?>&cache=<?php echo time(); ?>" />
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
			</section> <!-- #content-library -->

			<div id="btn_container">			
				 <button class="btn btn-warning btn-large btn-save-app">SOLICITAR APROBACIÓN</button>
			</div>
		</div><!-- #container -->
	</div><!-- #wrapper -->
    
    <!-- Modal para mensajes -->
    <div id="mensajes" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="mensajesLabel">Error</h3>
      </div>
      <div class="modal-body">
        <p><div class="alert alert-error mensajesdeerror">
         Error al enviar los datos
        </div></p>
      </div>
      <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
      </div>
    </div>
    <!-- #Modal para mensajes -->


    <!-- LE JAVASCRIPT -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="<?php echo base_url('assets/fileupload'); ?>/jquery.iframe-transport.js"></script>
    <script src="<?php echo base_url('assets/fileupload'); ?>/jquery.fileupload.js"></script>
    <script src="<?php echo base_url('assets/js/wizard'); ?>/jquery.simplePagination.js"></script>
    
    <script src="<?php echo base_url('assets/js') ?>/bootstrap.min.js"></script>
    <script src="<?php echo base_url('assets/js') ?>/fancybox/jquery.fancybox-1.3.4.js"></script> <!-- para las voces -->
    <script src="<?php echo base_url('assets/js') ?>/jquery.colorbox-min.js"></script><!-- para el simulador -->
    <script src="<?php echo base_url('assets/js') ?>/si.files.js"></script> <!-- para la subida de archivos -->    
        
    <script src="<?php echo base_url('assets/js/sisyphus.min.js'); ?>"></script>
	<script type="text/javascript">
		//VARIABLES GLOBALES QUE SERÁN UTILIZADAS EN EL JS
    	var $line_nombre  		= '<?php echo $this->lang->line('nombre') ?>';
		var $line_the_message 	= '<?php echo $this->lang->line('the_message') ?>';
		var $line_voicena 		= '<?php echo $this->lang->line('voicena') ?>';
		var $app_data_id		= '<?php echo $app_data->id ?>';
		var $sure_delete_library= '<?php echo $this->lang->line('sure_delete_library'); ?>';
		var $name_not_empty 	= '<?php echo $this->lang->line('name_not_empty'); ?>';
		var $dato 				= '<?php echo $this->lang->line('dato'); ?>';
		var $name 				= '<?php echo $this->lang->line('name'); ?>';
		var $sure_delete_dynamic= '<?php echo $this->lang->line('sure_delete_dynamic'); ?>';
		var $campo 				= "<?php echo $this->lang->line('campo'); ?>";
		var $nombre				= '<?php echo $this->lang->line('nombre'); ?>';
		var $tipo 				= '<?php echo $this->lang->line('tipo'); ?>';
		var $valor				= '<?php echo $this->lang->line('valor'); ?>';
		var $maximun_dynamic_fields		= '<?php echo $this->lang->line('maximun_dynamic_fields'); ?>';
		var $img_uploaded_succesfull	= '<?php echo $this->lang->line('img_uploaded_succesfull'); ?>';
		var $img_uploaded_error			= '<?php echo $this->lang->line('img_uploaded_error'); ?>';
		
		
		/**
		* PLUGIN PARA ADMINISTRAR EL TEMA DE LOCAL STORAGE
		*/
		var form_wizard = $('#form-wizard-personalization');
		<?php if($this->session->flashdata('release')=='TRUE'): ?>
			form_wizard.sisyphus().manuallyReleaseData();
		<?php endif; ?>
		form_wizard.sisyphus({excludeFields: $('input, select, textarea', '.campos_dinamicos'), autoRelease:false});

		
    </script>
    <script src="<?php echo base_url('assets/js/wizard/main.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/wizard/libreria.js') ?>"></script>
	<script type="text/javascript">
		
		// LOAD THE SWF OBJECT DYNAMICALY
		var createSwfObject = function() {
			
			var src= '<?php echo base_url('assets/swf/kkatoo-audio-recorder_small.swf') ?>?saveurl=<?php echo $the_url_record ?>&id_wapp=<?php echo $app_data->id ?>&uniqid=<?php echo uniqid(); ?>';
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
</body>

</html>