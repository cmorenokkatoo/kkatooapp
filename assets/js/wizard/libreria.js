// JavaScript Document
var root = "/"+window.KKATOO_ROOT;

//TEMPLATES 
var TEMPLATE_TEXTSPEACH = '<tr class="content_resume content_resume_text_{id}"><td><input type="checkbox" name="check_content[]" value="{id}_text" class="chk-select"></td><td>{name_field}</td><td class="center"><a href="javascript:;" class="item-edit-ico" data-id="{id}" data-tipo="{tipo}"></a><a href="javascript:;" class="item-view-ico" data-id="{id}" data-tipo="{tipo}"></a><a href="#" class="item-delete-ico" data-id="{id}" data-tipo="{tipo}"></a></td></tr><tr class="content_view content_view_text_{id}"><td colspan="3"><h4>'+$line_nombre+': </h4><p class="name_field">{name_field}</p><h4>'+$line_the_message+'</h4><p class="message">{message}</p><h4>'+$line_voicena+'</h4><p class="voice" data-voice-id={voice_id}>{voice}</p></td></tr>';

var TEMPLATE_AUDIO = '<tr class="content_resume content_resume_audio_{id}"><td><input type="checkbox" name="check_content[]" value="{id}_audio" class="chk-select"></td><td>{name_field}</td><td class="center"><a href="javascript:;" class="item-edit-ico" data-id="{id}" data-tipo="audio"></a><a href="javascript:;" class="item-view-ico" data-id="{id}" data-tipo="audio"></a><a href="javascript:;" class="item-delete-ico" data-id="{id}" data-tipo="audio"></a></td></tr><tr class="content_view content_view_audio_{id}" style="display: none;"><td colspan="3"><h4>'+$line_nombre+'</h4><p class="name_field">{name_field}</p><audio id="player2" src="{url_audio}" type="audio/mp3" controls="controls"></audio</td>';

/** CLEAR FORM **/
function resetForm($form) {
	$form.find('input:text, input:password, input:file, select, textarea').val('');
	$form.find('input:radio, input:checkbox')
		 .removeAttr('checked').removeAttr('selected');
}

/***FUNCION AUXILIAR PARA REEMPLAZAR, YA QUE LA DE JAVASCRIPT SOLO REEMPLAZA EL PRIMERO *****/
function replaceAll( text, busca, reemplaza ){
  while (text.toString().indexOf(busca) != -1)
	  text = text.toString().replace(busca,reemplaza);
  return text;
}


/**
* PAGINACIÓN Y LIBRERÍA DE CONTENIDOS
*/


/**
* PERMITIR GRABAR AUDIO INICIAL Y PERMITIR GRABAR AUDIO FINAL
*/

var grabar_intro_out = $('input[name="grabar_intro_out"]');

grabar_intro_out.on('change', function(event){
	grabar_intro_cierre($(this));
});

function grabar_intro_cierre(_this){
	var grabar = 0;
	_this.attr("disabled", true);
	if(_this.is(':checked')) grabar = 1;
	$.post('/wizard/ajax_intro_cierre_update', {resp:grabar, id_wapp: $app_data_id, action:_this.val()}, function(data){
		
		var dat = $.parseJSON(data);
		if(dat.cod==1){
			if(dat.messa == 1){
				_this.attr('checked',true);
			}else{
				_this.attr('checked',false);
			}
		}else{
			show_error(dat);
		}
		_this.attr("disabled", false);
		_this.focus();
		
	});	
}

/**
* SELECCIONAR VARIOS CONTENIDOS, APARECE UN BOTÓN PARA ELIMINAR
*/
var delete_btn = $('input[name="delete-btn"]');

delete_btn.on('click', function(){
	if(confirm($sure_delete_library)){
		$.post('/wizard/ajax_delete_batch', $('form[name="delete_contents"]').serializeArray(), function(data){
			var dat = $.parseJSON(data);
			if(dat.cod == 1){
				if(typeof dat.messa.audio != 'undefined'){
					for(i = 0; i<dat.messa.audio.length; i++){
						$('.content_resume_audio_'+dat.messa.audio[i]).remove();
						$('.content_view_audio_'+dat.messa.audio[i]).remove();
					}
				}
				if(typeof dat.messa.text_speech != 'undefined'){
					for(j = 0; j<dat.messa.text_speech.length; j++){
						$('.content_resume_text_'+dat.messa.text_speech[j]).remove();
						$('.content_view_text_'+dat.messa.text_speech[j]).remove();
					}
				}
				hide_delete_btn();
				redraw_pagination();
			}else{
				show_error(dat);
			}
		});
	}
});

function show_delete_btn(){
	delete_btn.fadeIn();
}

function hide_delete_btn(){
	delete_btn.fadeOut();
}

/*var content_checkboxes = $('input[name="check_content[]"]');*/
var check_all 		   = $('input[name="check-all"]');

check_all.on('change', function(){
	if($(this).is(':checked')){
		$('input[name="check_content[]"]').attr('checked', true);
		if( $('input[name="check_content[]"]:checked').length > 0){
			show_delete_btn();
		}else{
			hide_delete_btn();
		}
	}else{
		$('input[name="check_content[]"]').attr('checked', false);
		hide_delete_btn();
	}
});

$('form[name="delete_contents"]').on('change', 'input[name="check_content[]"]', function(){
	
	if( $('input[name="check_content[]"]:checked').length > 0){
		show_delete_btn();
	}else{
		hide_delete_btn();
	}
	
});


$(document).on('click', '.item-view-ico', function(event){
	$(this).parent().parent().next().fadeToggle();
	event.preventDefault();
});


$(document).on('click', '.item-edit-ico', function(event){
	var _this = $(this);
	switch(_this.data('tipo')){
		case "text":
			edit_text_speach(_this);
		break;
		case "audio":
			edit_audio(_this);
		break;
	}
	event.preventDefault();
});

$(document).on('click', '.item-delete-ico', function(event){
	if(confirm($sure_delete_library)){
		var _this = $(this);
		switch(_this.data('tipo')){
			case "text":
				delete_text_speach(_this);
			break;
			case "audio":
				delete_audio(_this);
			break;
		}
	}
	event.preventDefault();
});

/**
	TEXTO SPEECH
*/

function edit_text_speach(_this){
	var the_parent = _this.parent().parent();
	var voice_id = the_parent.next().find('.voice').data('voice-id');
	
	var text_area = $('textarea[name="txt_msg_to_speech"]');
	text_area.val(the_parent.next().find('.message').text());
	$('input[name="id_content_text"]').val(_this.data('id'));
	
	$('select[name="cbo-vozmini"] option').filter(function() {
		return $(this).val() == voice_id;
	}).prop('selected', true);
	$('#tab-narrar').trigger('click');
	text_area.focus();
}

function delete_text_speach(_this){
	$.post('/wizard/ajax_delete_text_speech', 
		{id_content:$(_this).data('id')}, 
		function(data){
			var dat = $.parseJSON(data);
			if(dat.cod == 1){
				$('.content_resume_text_'+dat.messa).remove();
				$('.content_view_text_'+dat.messa).remove();
				redraw_pagination();
			}else{
				show_error(dat);
			}
		}
	);
}

$('form[name="form-text-speach"] input[type="reset"]').on('click', function(){
	$('input[name="id_content_text"]').val('');
});

$('form[name="form-text-speach"]').on('submit', function(event){
	var _this = $(this);
	$.post('/wizard/ajax_save_text_speach', 
		_this.serializeArray(), 
		function(data){
			var dat = $.parseJSON(data);
			if(dat.cod == 1){
				add_text_speach_to_list(dat.messa);
				$('form[name="form-text-speach"] input[type="reset"]').trigger('click');
				$('input[name="id_content_text"]').val('');
			}else{
				show_error(dat);
			}
		}
	);
	return false;
});

function add_text_speach_to_list(data){
	if(typeof data != 'object'){
		data = jQuery.parseJSON(data);
	}
	
	var exists_resume 	= $('.content_resume_text_'+data.id);
	var exists_view 	= $('.content_view_text_'+data.id);
	
	if(exists_resume.length > 0){
		exists_resume.children('td:nth-child(2)').text(data.name);
		exists_view.find('.name_field').text(data.name);
		exists_view.find('.message').text(data.text);
		exists_view.find('.voice').text( data.voice_name+' - '+data.idioma);
		exists_view.find('.voice').data('voice-id', data.voice_id);
	}else{
		var aux = replaceAll(TEMPLATE_TEXTSPEACH,"{name_field}", data.name);
		var aux = replaceAll(aux,"{id}", data.id);
		var aux = replaceAll(aux,"{tipo}", "text");
		var aux = replaceAll(aux,"{message}", data.text);
		var aux = replaceAll(aux,"{voice}", data.voice_name+' - '+data.idioma);
		var aux = replaceAll(aux, "{voice_id}", data.voice_id);
	
		$('tbody.topaginate').prepend(aux);			
	}
	
	redraw_pagination();
}


$('#cbo_datos').change(function(){
	var tav    = $('textarea[name="txt_msg_to_speech"]').val(),
		strPos = $('textarea[name="txt_msg_to_speech"]')[0].selectionStart;
		front  = (tav).substring(0,strPos),
		back   = (tav).substring(strPos,tav.length); 

	$('textarea[name="txt_msg_to_speech"]').val(front + '{' + $(this).val() + '}' + back);
	$('#cbo_datos').val('dato');
});

//AUDIO UPLOAD

/**
* UPLOAD AUDIO AJAX FILEUPLOAD
*/

function edit_audio(_this){
	
	var td = _this.parent().parent().children(":nth-child(2)");
	var content = td.text();
	var input =  $('<input>', {'class': 'input-block-level input-medium audio-edit-input', 'value' : content, 'type':'text'}).data('id-content', _this.data('id')).hide();
	
	td.empty();
	
	td.append(input);
	
	input.fadeIn();
	input.focus();
	
}

$(document).on('keypress', '.audio-edit-input', function (e) {
	if(e.which == 13) {
		var _this = $(this);
		_this.trigger('blur');
	}
});

$(document).on('blur', '.audio-edit-input', function(){
	var _this = $(this);
	edit_the_audio(_this);
});

function edit_the_audio(_this){
	_this.attr('enabled', false);
	$.post('/wizard/ajax_update_audio_name', {nombre:_this.val(), id_content:_this.data('id-content')}, 
	function(data){
		var dat = $.parseJSON(data);
		if(dat.cod == 1){
			var td = _this.parent();
			var content = _this.val();
			td.empty();
			td.text(content)
		}else{
			show_error(dat);
		}
	});
}

function delete_audio(_this){
	$.post('/wizard/ajax_delete_audio', 
		{id_content:$(_this).data('id')}, 
		function(data){
			var dat = $.parseJSON(data);
			if(dat.cod == 1){
				$('.content_resume_audio_'+dat.messa).remove();
				$('.content_view_audio_'+dat.messa).remove();
				redraw_pagination();
			}else{
				show_error(dat);
			}
		}
	);
}

function add_audio_to_list(data){
	
	var aux = replaceAll(TEMPLATE_AUDIO,"{name_field}", data.audio_data.name);
	var aux = replaceAll(aux,"{id}", data.audio_data.id_audio);
	var aux = replaceAll(aux,"{tipo}", "audio");
	var aux = replaceAll(aux,"{url_audio}", data.url);

	$('tbody.topaginate').prepend(aux);
	
	redraw_pagination();	
}

if($.blueimp){
	$(function () {
		'use strict';
		var url = "/wizard/upload_audio";
		$('input[name="upload_audio"]').fileupload({
			url: url,
			dataType: 'json',
			done: function (e, data) {
				if(data.result.cod == 0){
					show_error(data.result)
				}else{
					add_audio_to_list(data.result);
					resetForm($('form[name="form-audio"]'));
				}
				$('#progress_bar_audio .bar').css('width', '0%');
			},
			progressall: function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#progress_bar_audio .bar').css(
					'width',
					progress + '%'
				);
			}
		})
		.bind('fileuploadsubmit', function (e, data) {
			// The example input, doesn't have to be part of the upload form:
			var input = $('input[name="audio_name"]');
			data.formData = {'nombre': $.trim(input.val()), 'id_wapp':$app_data_id};
			if (!data.formData.nombre) {
			  input.focus();
			  var dat = new Array();
			  dat.cod = 0;
			  dat.messa = $name_not_empty;
			  show_error(dat)
			  return false;
			}
		})
	});
}

/**
* AUDIO RECORD
*/
function redireccionar(id){
	//$('#tab-grabar').trigger('click');
	if(typeof id != 'undefined'){
		$.post('/wizard/ajax_get_audio_recorded_data', 
		{id_wapp:$app_data_id, id_content:id}, 
		function(data){
			var dat = $.parseJSON(data);
			if(dat.cod==1){
				add_audio_to_list(dat);					
				
				var swf = createSwfObject();
				$('#grabar').empty();
				$('#grabar').append(swf);/*.fadeOut(400, function(){
					$(this).empty();
					$(this).append(swf);
					$(this).fadeIn();
				});*/
			}else{
				show_error(dat);
			}
		});
	}else{
		
	}
}