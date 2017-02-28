<html lang="en">
<head>
<meta charset="utf-8">
	<title>jQuery File Upload Demo - Basic version</title>
    <style type="text/css">
    	label{display:block;}
		textarea{width:400;height:150px;}
    </style>
</head>
<body>
	<?php 
		echo $this->session->flashdata("error");
		$app_exits = (!empty($app_data))?TRUE:FALSE;
		$nombre_app = "";
		$slogan = "";
		$descripcion = "";
		$video = "";
		$dominio = "";
		$percent = "";
		$id = "";
		$privacidad = "";
		$categoria 	= "";
		
		if($app_exits){
			$nombre_app = $app_data->title;
			$slogan = $app_data->slogan_html;
			$descripcion = $app_data->description;
			$video = $app_data->video_html;
			$dominio = $app_data->url_landing;
			$percent = $app_data->price;
			$id		 = $app_data->id;
			$privacidad = $app_data->private;
			$categoria 	= $app_data->category;
		}
			
		$attributes = array('class' => 'form-wizard-personalization', 'id' => 'form-wizard-personalization');
     	echo form_open_multipart('wizard/save_info_app', $attributes);
	?>
		<label>Nombre de la aplicación</label>
       
        <input type="text" name="nombre_app" id="nombre_app" value="<?php echo set_value('nombre_app', $nombre_app); ?>" />
        <div class="uplaod-img">
        	<label>Imagen de fondo</label>
            <input id="fileupload" class="fileupload" type="file" data-form-data='{"type": "fondo", "wapp":"<?php echo $app_data->id ?>", "user":"<?php echo $app_data->user_id ?>"}' name="fondo" />
            <div class="img">
                
            </div>
        </div>
        <div class="uplaod-img">
        	<label>Logotipo</label>
            <input id="fileupload" class="fileupload" type="file" data-form-data='{"type": "logo", "wapp":"<?php echo $app_data->id ?>", "user":"<?php echo $app_data->user_id ?>"}' name="logo" />
            <div class="img">
                
            </div>
        </div>
        <label>Slogan</label>
        <input type="text" name="slogan" id="slogan" value="<?php echo set_value('slogan', $slogan); ?>" />
        <label>Descripcion</label>
        <textarea name="descripcion" id="descripcion"><?php echo set_value('descripcion', $descripcion); ?></textarea>
        <label>Video</label>
        <input type="text" name="video" id="video" value="<?php echo set_value('video', $video); ?>" />
        <div class="uplaod-img">
       		<label>Imagen market</label>
            <input id="fileupload" class="fileupload" type="file" data-form-data='{"type": "market", "wapp":"<?php echo $app_data->id ?>", "user":"<?php echo $app_data->user_id ?>"}' name="market" />
            <div class="img">
                
            </div>
        </div>
        <div class="uplaod-img">
       		<label>Imagen secundaria</label>
            <input id="fileupload" class="fileupload" type="file" data-form-data='{"type": "secundaria", "wapp":"<?php echo $app_data->id ?>", "user":"<?php echo $app_data->user_id ?>"}' name="secundaria" />
            <div class="img">
                
            </div>
        </div>
        <label>Categorías</label>
        <select name="categorias" id="categorias">
        <?php  foreach($category as $cat): ?>
        	<option value="<?php echo $cat->id ?>" <?php echo set_select('categorias', $cat->id); ?> <?php if($categoria==$cat->id) echo "selected" ?>><?php echo $cat->name; ?></option>
        <?php endforeach; ?>
        </select>
        <label>Privacidad de la aplicación</label>
        <label style="display:inline-block" for="privada">Privada</label><input type="radio" name="privacidad" id="privada" value="1" <?php echo set_radio('privacidad', '1'); ?> <?php if($privacidad=="1") echo "checked" ?> />
        <label style="display:inline-block" for="publica">Publica</label><input type="radio" name="privacidad" id="publica" value="0" <?php echo set_radio('privacidad', '0'); ?> <?php if($privacidad=="0") echo "checked" ?> />
        <label>Dominio propio</label>
        <input type="text" name="dominio" id="dominio" value="<?php echo set_value('dominio', $dominio); ?>" />
        <label>Porcentaje de ganancia</label>
        <input type="text" name="percent" id="percent" value="<?php echo set_value('percent', $percent); ?>" />
        <input type="hidden" name="id_wapp" id="id_wapp" value="<?php echo set_value('id_wapp', $id); ?>" />
        <input type="submit" name="enviar" id="enviar" value="Actualizar" />
    </form>
</body>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="<?php echo base_url('assets/fileupload'); ?>/jquery.iframe-transport.js"></script>
<script src="<?php echo base_url('assets/fileupload'); ?>/jquery.fileupload.js"></script>
<script src="<?php echo base_url('assets/js/sisyphus.min.js'); ?>"></script>
<script type="text/javascript">
	$('#form-wizard-personalization').sisyphus();

	$(function () {
		'use strict';
		var url = "<?php echo base_url('wizard/upload_img'); ?>";
		$('.fileupload').fileupload({
			url: url,
			dataType: 'json',
			done: function (e, data) {
				/*var _this = $(this);
				var img = $('<img>', {width:100});
				img.attr('src', data.result.files[0].url);
				_this.siblings('.img').empty().append(img);*/
			}
		});
	});
</script>
</html>