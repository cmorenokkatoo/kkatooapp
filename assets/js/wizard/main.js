// JavaScript Document
var root = "/"+window.KKATOO_ROOT;


/**
	PAGINACIÓN PARA LA LIBRERÍA DE CONTENIDOS
**/

var pagination_ul = $('.pagination ul');
var items = 'tbody.topaginate_wiz tr.content_resume';
var numItemsToShow = 4;
var numItems = $(items).length;
var numPages = Math.ceil(numItems/numItemsToShow);


function redraw_pagination(){
	$(items).hide();
	$(items).slice(0, numItemsToShow).fadeIn();
	redraw_one();
}

function redraw_one(){
	pagination_ul.pagination('destroy');
	made_pagination();
}

$(items).hide();
$(items).slice(0, numItemsToShow).fadeIn();

function show_elements(page){
	var beginItem = (page -1) * numItemsToShow;
	$(items).hide();
	$('tr.content_view').hide();
	$(items).slice(beginItem, beginItem + numItemsToShow).fadeIn();
}

function made_pagination(){
	numItems = $(items).length;
	numPages = Math.ceil(numItems/numItemsToShow);

	pagination_ul.pagination({
		items: numItems,
		itemsOnPage: numItemsToShow,
		onPageClick: function(pageNumber, event) {
			show_elements(pageNumber);
		}
	});
}

made_pagination();


/** PAQUETES! **/

$(document).on('click', '.packages a', function(e){
	var _this = $(this);
	var id_package = _this.parent().attr('data-id-package');
	$.post('/wizard/ajax_remove_package', {'id_package':id_package, 'id_wapp': $app_data_id}, function(data){
		var dat = $.parseJSON(data);
		if(dat.cod == 1){
			_this.parent().fadeOut(400, function(){
				$(this).remove();
			});
		}else{
			show_error(dat);
		}
	});

	e.preventDefault();
});

$(document).on('keypress', '#package', function (e){
	if(e.which == 13) {
		var _this = $(this);
		_this.trigger('blur');
	}
});

var PACKAGES_UL = $('.packages ul');

function ordenar_lis_packages(){

	var items = [];
	var new_lis   = [];
	$('.packages ul li').each(function(){
	   items.push(parseInt($(this).attr('data-amount'), 10));
	})

	items.sort(function(a,b){return a - b});

	$(items).each(function(index, val){
		new_lis.push($('.amount_'+val).hide());
	});

	PACKAGES_UL.empty();
	$(new_lis).each(function(index, val){
		PACKAGES_UL.append(new_lis[index]);
		new_lis[index].fadeIn();
		//console.log(new_lis[index]);
	});

}

var doing = false;
$("#package").on('blur', function(event){
	var _this = $(this);
	var nro_packages = _this.val();
	if(nro_packages){
		if(!doing){
			doing = true;
			$.post('/wizard/ajax_add_package', {'nro_package':nro_packages, 'id_wapp': $app_data_id}, function(data){
				var dat = $.parseJSON(data);
				if(dat.cod == 1){

					var a  = $('<a>', {href:'#'}).append('x');
					var li = $('<li>', {'class':'package_del amount_'+nro_packages}).attr('data-amount', nro_packages).attr('data-id-package', dat.messa).append(a).append(nro_packages);//.hide();

					PACKAGES_UL.append(li);

					_this.val('');
					_this.focus();

					ordenar_lis_packages();
				}else{
					show_error(dat);
				}
				doing = false;
			});
		}
	}
});


/**
	CAMPOS DINAMICOS!
*/
function show_error(data){
	if(typeof data != 'object'){
		data = jQuery.parseJSON(data);
	}
	if(typeof data.cod != 'undefined'){
		if(data.cod == 0){
			$('.mensajesdeerror').html(data.messa);
			$('#mensajes').modal('show');
		}
	}
}

var CAMPOS_DYNAMICOS = $('.campos_dinamicos');

function check_and_show_messa(){
	if($('.content_field').length>0){
		$('.btn_dynamic').html($('.btn_dynamic').data('mas'));
	}else{
		$('.btn_dynamic').html($('.btn_dynamic').data('normal'));
	}
}


function renderizar_dinamicos_para_textspeach(){
	var cbo_datos = $('#cbo_datos');
	cbo_datos.empty();
	cbo_datos.append('<option value="dato" disabled selected >'+$dato+'</option>');
	cbo_datos.append('<option value="name">'+$name+'</option>');
	$('input[name="dynamic_name[]"]', CAMPOS_DYNAMICOS).each(function(){
		var val = $(this).val();
		if(val){
			//$(this).parent().prev('h3').empty().append($('<a>', {href:"#", 'class':"delete_field"}).text('X')).append(val);
			$.post('/wizard/sanitize_text', {text: val}, function(data){
				var dat = $.parseJSON(data);
				if(dat.cod==1){
					var cbo_option = $("#cbo_datos option[value='"+dat.messa+"']");
					if(cbo_option.length > 0){
						cbo_option.val(dat.messa);
					}else{
						cbo_datos.append('<option value="'+dat.messa+'">'+val+'</option>');
					}

				}
			});
		}
	});
}

renderizar_dinamicos_para_textspeach();
check_and_show_messa();

CAMPOS_DYNAMICOS.on('click', 'a.delete_field_database', function(event){
	if(confirm($sure_delete_dynamic)){
		var _this = $(this);
		_dynamic_id = _this.data('dynamic-id');
		_app_id		= _this.data('app-id');
		if(_dynamic_id && _app_id)
		$.post('/wizard/delete_dynamic', {dinamic_id: _dynamic_id, app_id: _app_id}, function(data){
			var dat = $.parseJSON(data);
			if(dat.cod==1){
				remover_campo(_this);
			}else{
				show_error(dat);
			}
		});
	}
	event.preventDefault();
});
CAMPOS_DYNAMICOS.on('click', 'a.delete_field', function(event){
	remover_campo($(this));
	event.preventDefault();
});
CAMPOS_DYNAMICOS.on('click', 'h3', function(event){
	$(this).next().slideToggle();
});
CAMPOS_DYNAMICOS.on('blur', '.dynamic_name', function(event){
	var val = $(this).val();
	h3 = $(this).parent().prev('h3');
	var tipo_clase = "";
	//console.log(h3.data("dynamic"));
	if(h3.data("dynamic")==false){
		tipo_clase = "delete_field_database";
	}else{
		tipo_clase = "delete_field";
	}
	if(val){
		h3.empty().append($('<a>', {'href':"#", 'class':tipo_clase}).text('X')).append(val);
	}else{
		h3.empty().append($('<a>', {'href':"#", 'class':tipo_clase}).text('X')).append($campo);
	}
	renderizar_dinamicos_para_textspeach();
});
CAMPOS_DYNAMICOS.on('change', 'select', function(event){
	var _parent = $(this).parent();
	var default_label = $('.default_label', _parent);
	var default_input = $('.default_input', _parent);
	var val = $(this).val();
	//console.log(val);
	if(val) {
		switch(val){
			case "1":
			case "2":
			case "3":
			case "4":
				default_label.fadeOut();
				default_input.fadeOut();
			break;
		}

	}
});
function remover_campo(campo){
	var h3 = campo.parent();
	var fields = h3.next();
	fields.fadeOut(400, function(){
		$(this).remove();
	});
	h3.fadeOut(400, function(){
		$(this).remove();
		check_and_show_messa();
	});
}
function add_dynamic_field(){

	var h3 				= $('<h3>',{}).data("dynamic", "true").append($('<a>', {href:"#", 'class':"delete_field"}).text('X')).append($campo);
	var content_field 	= $('<div>', {'class':'content_field'}).css('display', 'none');
	content_field.append($('<label>', {}).text($nombre));
	content_field.append($('<input>', {name:'dynamic_name[]', type:"text", 'class':"input-block-level input-medium dynamic_name", value:""}));
	content_field.append($('<label>', {}).text($tipo));
	var select_tipo 	= $('<select>', {name:'dynamic_type[]', 'class':'input-block-level'});
	select_tipo.append('<option value="1">Número</option>');
	select_tipo.append('<option value="2">Texto</option>');
	select_tipo.append('<option value="3">Fecha</option>');
	select_tipo.append('<option value="4">Audio MP3</option>');
	content_field.append(select_tipo);
	content_field.append($('<label>', {'class':"default_label"}).text($valor));
	content_field.append($('<textarea>',{name:'dynamic_default[]', 'class':"default_input input-block-level texarea"}));
	content_field.append($('<input>',{name:'dynamic_id[]', type:"hidden", value:""}));


	CAMPOS_DYNAMICOS.append(h3);
	CAMPOS_DYNAMICOS.append(content_field);

	check_and_show_messa();

	content_field.slideDown(600);
}

$('.btn_dynamic').on('click', function(){
	$('.content_field').slideUp(600);
	if($('.content_field').length>9){
		$('.mensajesdeerror').html($maximun_dynamic_fields);
		$('#mensajes').modal('show');
		return false;
	}
	add_dynamic_field();
});

$('.content_field').slideUp();

/**
	/CAMPOS DINAMICOS!
*/

/**
* CERRAR MENSAJES EN LA PARTE SUPERIOR
*/
$(".cerrar a").on("click",function(){
	$(".mensajes").fadeOut();
});

setTimeout(cerraventana, 10000);
function cerraventana(){
	$(".mensajes").fadeOut();
}

/**
* SUBMIT DEL FORMULARIO
*/

$('.btn-save-app').on('click', function(event){
	$('#form-wizard-personalization').submit();
});


/**
* FANCY BOX PARA MOSTAR LA IMAGEN QUE SE SUBIÓ.
*/
$(document).ready(function($){
	$("a.imagen_app").fancybox({
		'titleShow'     : false
	});


	$(".ifancybox").fancybox({
		'autoDimensions': false,
		'autoScale' : false,
		'transitionIn' : 'elastic',
		'transitionOut' : 'elastic',
		'type' : 'iframe',
		'scrolling': 'auto'
	});
});

/**
* PARTE VIKTOR DE LAS IMÁGENES
*/

var img_fondo = $("#img_fondo");
var img_container = $("#img-container");

img_fondo.on('click', function(event) {
	img_container.css("background-position", "0px 0px")
})

var img_container_mask = $("#img-container-mask");
var nombre_app = $("#nombre_app");

nombre_app.focus(function(event) {
	img_container.css("background-position", "0px 0px");
	img_container_mask.css("display", "");
	img_container_mask.css("background-position", "0px 0px");
})

nombre_app.focusout(function(event) {
	img_container_mask.css("display", "none");
})

var img_logotipo = $("#img_logotipo");
img_logotipo.on('click', function(event) {
	img_container.css("background-position", "0px 0px");
	img_container_mask.css("display", "");
	img_container_mask.css("background-position", "0px 0px");
})

var slogan =  $("#slogan")
slogan.focus(function(event) {
	img_container.css("background-position", "0px 0px");
	img_container_mask.css("display", "");
	img_container_mask.css("background-position", "0px 0px");
})
slogan.focusout(function(event) {
	img_container_mask.css("display", "none");
})

var descripcion = $("#descripcion");
descripcion.focus(function(event) {
	img_container.css("background-position", "0px 0px");
	img_container_mask.css("display", "");
	img_container_mask.css("background-position", "0px 0px");
})
descripcion.focusout(function(event) {
	img_container_mask.css("display", "none")
})

var video = $("#video");
video.focus(function(event) {
	img_container.css("background-position", "0px 0px");
	img_container_mask.css("display", "");
	img_container_mask.css("background-position", "0px 0px");
})
video.focusout(function(event) {
	img_container_mask.css("display", "none");
})

var img_secundaria =  $("#img_secundaria");
img_secundaria.on('click', function(event) {
	img_container.css("background-position", "0px 0px");
	img_container_mask.css("display", "")
	img_container_mask.css("background-position", "0px 0px")
})

SI.Files.stylizeAll();

$("a.simulator_link").colorbox({inline:true, href:"#simulator" ,width:500});
$("a.voice_link").colorbox({iframe:true, width:500, height:500});

/* ACTIVAR LA SELECCIÓN DE TODAS LAS FILAS UNA POR UNA */
// $(".check-all").click(function(event){
//     resaltar_all_filas(this);
// });


/**
* UPLOAD IMAGENES AJAX FILEUPLOAD
*/
$(function () {
	'use strict';
	var url = '/wizard/upload_img';
	$('.fileupload').fileupload({
		url: url,
		dataType: 'json',
		done: function (e, data) {
			var _this 		= $(this);

			if(data.result != null){
				_this.parent().find('.red, .green, .loading').remove();
				_this.parent().append($('<a>', {'class':'green imagen_app'}).attr('href', data.result.files[0].url).html($img_uploaded_succesfull).fancybox({'titleShow': false}));
			}else{
				_this.parent().find('.red, .green, .loading').remove();
				_this.parent().append($('<p>', {'class':'red'}).html($img_uploaded_error));
			}
		}
	})
	.bind('fileuploadsubmit', function (e, data) {
		var _this 		= $(this);
		_this.parent().find('.red, .green, .loading').remove();
		_this.parent().append($('<img>', {src:'/assets/img/loading.gif', 'class':'loading', 'width':26}));
	});
});
