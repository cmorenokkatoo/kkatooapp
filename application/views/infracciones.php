<?php 
    $this->load->view('globales/head_uploadify');
?>
<!-- Finaliza header -->
	<section id="uploadify-content">
		<h1>Subir Archivos</h1>
		<form>
			<div id="queue"></div>
			<input id="userfile" name="userfile" type="file" multiple="true"/>
			<a href="javascript:$('#userfile').uploadify('upload')">Subir archivo</a>
		</form>
		<div id="mensaje"></div>
		<div id="respuesta"></div>

		<script type="text/javascript">
		$(function() {
			get_imagenes();
			$('#userfile').uploadify({
				/*decimos que es por metodo post*/
				'method' : 'post',
				/*para que no cargue automaticamente el archivo pones false*/
				'auto' : false,
				/*la ruta donde verifica si el archivo existe o no (opcional))*/
				'checkExisting' : '<?= base_url("public/uploadify/archivos")?>',
				/*tamaño máximo de subida*/
				'fileSizeLimit' : '2048KB',
				/*tipo de archivos permitidos*/
				'fileTypeExts' : '*.xls; *.xlsx',
				/*tipo de subida tambien existe en porcentaje*/
				'progressData' : 'speed',
				/*numero maximo en cola de subida*/
				'queueSizeLimit' : 1,
				/*parametros opcionales via post*/
				'formData'     : {
					'upload' : 'si'
				},
				/*cargamos el archivo flash*/
				'swf'      : '<?= base_url("public/uploadify")?>/uploadify.swf',
				/*ruta donde hace la subida del archivo*/
				'uploader' : '<?= base_url("public/uploadify/archivos")?>', 
				/*respuesta del servidor*/
				'onUploadSuccess' : function(file, data, response) {
				/*mostramos el mensaje*/
				$("#mensaje").html('El archivo ' + file.name + ' devolvió una respuesta de ' + response + ':' + data);
				/*mostramos el div (estuvo oculto)*/
				$("#mensaje").css('display','block');
				/*mosramos las imagenes via ajax*/
				get_imagenes();
				/*ocultamos el div mensaje en 5 seg*/
				$("#mensaje").delay(5000).hide(600);
				}
			});
		});

/*funcion que se encarga de mostrar las imagenes via ajax*/
function get_imagenes()
{
	$.ajax({
		type: 'post',
		url :  '<?= base_url("uploadify/archivos")?>',
		success: function(data){
			$("#respuesta").html(data);
		}
	});           
}
</script>
</section>
</body>
<!-- Finaliza el Footer -->