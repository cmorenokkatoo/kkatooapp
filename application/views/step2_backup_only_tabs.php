                       
                        <div id="msg-tabs-container" class="tabbable tabs-left">
                           <ul class="nav nav-tabs" id="msg-initial-tabs">
                           <?php if($app->text_speech == 1){ ?>
                              <li>
                                 <a href="#text-2-speech" data-toggle="tab">
                                    <img src="<?php echo base_url()?>assets/img/ico-text-2-speech-24.png" alt="" />
                                 </a>
                              </li>
                           <?php } ?>
                           <?php if($app->upload_audio == 1){ ?>
                              <li>
                                 <a href="#upload-audio" data-toggle="tab">
                                    <img src="<?php echo base_url()?>assets/img/ico-upload-audio-24.png" alt="" />
                                 </a>
                              </li>
                            <?php } ?>
                            <?php if($app->record_audio == 1){ ?>
                              <li>
                                 <a href="#record-audio" data-toggle="tab">
                                    <img src="<?php echo base_url()?>assets/img/ico-rec-audio-24.png" alt="" />
                                 </a>
                              </li>
                            <?php } ?>
                            <?php if($app->use_audio == 1){ ?>
                              <li>
                                 <a href="#choose-audio" data-toggle="tab">
                                    <img src="<?php echo base_url()?>assets/img/ico-choose-audio-24.png" alt="" />
                                 </a>
                              </li>
                            <?php } ?>
                           </ul>

                           <div class="tab-content" id="tab-content">
                              <!-- PANELES QUE SE DESPLIEGAN POR LOS TABS -->
                              <?php if($app->text_speech == 1){ ?>
                              <div class="tab-pane active" id="text-2-speech" >
                                 <div id="t2s-msg">
                                    <?php echo $this->lang->line('messagena'); ?>
                                    <textarea name="txt_msg_to_speech" id="txt-msg-to-speech"><?php
	                                    if(isset($textcampaign)){
			                           		if(isset($textcampaign->text_speech)){
			                           			echo $textcampaign->text_speech;
			                           		}
			                           	}
	                                    ?></textarea>
                                    <input  type="button" class="guardarprogreso" value="GUARDAR MENSAJE INICIAL" />
                                 </div>
                                 <div id="t2s-options">
                                 <?php if(!empty($fields)){ ?>
                                    <?php echo $this->lang->line('datomessage'); ?><br />
                                    <select id="cbo_datos" name="cbo-datos">
                                       <option value="dato" disabled selected ><?php echo $this->lang->line('dato'); ?></option>
                                       <option value="name"><?php echo $this->lang->line('name'); ?></option>
                                       <?php foreach($fields as $fiel){ ?>  
                                       <option value="<?php echo $fiel->name_fields; ?>"><?php echo $fiel->name; ?></option>
                                       <?php } ?>
                                    </select><br />
                                  <?php } ?>
                                    <?php echo $this->lang->line('voicena'); ?><br />
                                    <select id="cbo_voz" name="cbo-voz">
                                       <option value="0" disabled selected><?php echo $this->lang->line('voice'); ?></option>
                                       <?php foreach($voice as $voi){ ?> 
                                       <option value="<?php echo $voi->id; ?>"><?php echo str_replace("IVONA 2 ","",$voi->name).' '.$voi->idioma; ?></option> 
                                       <?php } ?>
                                    </select><br />
                                    <a class="ifancybox" href="<?php echo base_url('apps/voice_view'); ?>">Ejemplo de las voces</a>
                                 </div>
                              </div> <!-- text-2-speech -->
                              <?php } ?>
                              <?php if($app->upload_audio == 1){ ?>
                              <div class="tab-pane" id="upload-audio" >
                                 <div id="dropdiv">
                                    	<?php
					                     	$attributes = array();
					                     	echo form_open_multipart('apps/upload_audio_campaign_ini', $attributes);
					                     ?>
                                         <div class="initial-audio-upload">
                                            <a href="javascript:void(0);">
                                            <label class="cabinet"> 
                                                <input type="file" class="file"  name="Filedata" data-context="dropdiv" accept="audio/mp3" />
                                            </label>
                                            </a>
									                       </div>
                                         
                                         <div class="fakeupload">
                                        	
                                         	<img src="<?php echo base_url('assets/img/ico-audio-generic-medium.png')?>" />
                                            <p><a href="javascript:void(0)"></a><?php echo $this->lang->line('remplaceaudio'); ?> :<span></span></p>
                                            <input type="submit" id="subir-audio" class="btn" value="SUBIR AUDIO" />
                                         </div>
					                     <!-- <input type="file" name="Filedata" /> -->
					                     <input type="hidden" name="id_campaign" class="id_campaign" value="<?php echo $id_campaign; ?>" />
                                    </form>
                                       <span class="msg" id="msg-upload-1"><?php echo $this->lang->line('fileupload'); ?></span>
                                 </div>
                                 <?php echo $this->lang->line('moreinfofile'); ?>                   
                              </div> <!-- upload-audio -->
                              <?php } ?>
                              <?php if($app->record_audio == 1){ ?>
								              <?php 
                                    $the_url_record = base_url('apps/add_audio_record');
                                    $the_url_record = urlencode($the_url_record);
                                ?>
                              <div class="tab-pane" id="record-audio">
                                 <object width="670" height="170" type="application/x-shockwave-flash" data="<?php echo base_url()?>assets/swf/kkatoo-audio-recorder.swf?saveurl=<?php echo $the_url_record ?>">
                                    <param name="movie" value="<?php echo base_url()?>assets/swf/kkatoo-audio-recorder.swf?saveurl=<?php echo $the_url_record ?>" />
                                    <param name="flashvars" value="saveurl=<?php echo $the_url_record ?>">
                                    <param name="quality" value="high" />
                                    <param name="wmode" value="" />
                                 </object>
                              </div> <!-- record-audio -->
                              <?php } ?>
                              <?php if($app->use_audio == 1){ ?>
                              <div class="tab-pane" id="choose-audio" >
                                 <div id="chau-list">
                                    <h4><?php echo $this->lang->line('audioavailable'); ?></h4>
                                    <table class="table table-striped tabladesonidos">
                                    <?php 
                                    	if(!empty($audios)){ 
                                    	foreach($audios as $aud){ ?>
                                       <tr>
                                          <td>
                                          	<?php //print_r($aud); ?>
                                             <input data-path="<?php echo base_url('public/audios').'/'. $aud->path; ?>" type="radio" class="audioselected" name="audioselected" value="<?php echo $aud->id; ?>">
                                             <?php echo $aud->name; ?>
                                          </td>
                                       </tr>
                                    <?php }
                                    	} ?>
                                    </table>

                                    <!-- PAGINACION (EN CASO DE NECESITARSE) -->
                                    <div class="pagination pagination-centered paginacionaudios">
                                      <ul>
                                        <li><a href="javascript:;" data-num="0">Primero</a></li>
                                        <?php for($i = 0; $i < $total; $i++): ?>
                                        <li <?php if($i == 0): ?>class="active" <?php endif; ?>><a href="javascript:;" data-num="<?php echo $i; ?>"><?php echo $i+1; ?></a></li>
                                        <?php endfor; ?>
                                        <li><a href="javascript:;" data-num="<?php echo $total-1; ?>">Ultimo</a></li>
                                        
                                      </ul>
                                    </div>

                                 </div> <!-- #chau-list -->
                                 <div id="chau-info-audio">
                                    <h4>Información del audio</h4>
                                    <div id="info-audio">
			                        	<audio id="player-listado" src="" type="audio/mp3"></audio>
                                       <span class="title-audio">
                                          
                                       </span>
                                       <span class="metadata-audio">
                                          Duración: <span class="duracion-audio"></span> <br>
                                          Tamaño: <span class="tamano-audio"></span> <br>
                                       </span>
                                    </div> <!-- info-audio -->
                                 </div> <!-- chau-info-audio -->
                              </div> <!-- #choose-audio -->
                           <?php } ?>
                           </div> <!-- tab-content -->
                           <div id="buttons">
                              <input onclick="window.location='<?php echo base_url('apps/'.$app->uri.'/3'); ?>'" class="btn" type="button" value="<?php echo $this->lang->line('continue'); ?>"> 
							  <?php if($app->aditional_options): ?>
							  
							  	<?php echo $this->lang->line('or'); ?> 
                             
                              	<input class="btn" type="button" value="<?php echo $this->lang->line('optionsadd'); ?>" id="show-additional-options"> 
                              <?php endif; ?>
                           </div> <!-- buttons -->
                        </div> <!-- msg-tabs-container -->


                     </div> <!-- msg-initial -->
                     <!-- FIN GRUPO DE TABS DE MENSAJE INICIAL -->


