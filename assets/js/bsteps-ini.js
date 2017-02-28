/*RUTA GLOBAL */
var root = "/"+window.KKATOO_ROOT;
var player;

var arr_diales = new Array();
var frm_filtro = $('#frm-filtro');


/*PLANTILLAS PARA RENDERIZAR*/
var template_agregado = '<tr><td><img data-id="{id}" class="deselect" src="'+root+'/assets/img/ico-x-25px.png" alt="Deseleccionar" /></td><td class="left-align"><img src="'+root+'/assets/img/users/ico-usr-generic-small.png" alt="{name}"/>{name}</td><td>({indi_pais}) {phone}</td></tr>';

var template_contacto = '<tr><td><input data-step="6" data-intro="Si deseas seleccionar algunos de los contactos puedes hacer click en el checkbox" type="checkbox" name="contactos[]" class="contactsch_remove contactsch" value="{id}"></td><td class="left-align"><img src="'+root+'/assets/img/users/ico-usr-generic-small.png" alt="{name}" />{name}</td><td>({indi_pais}) {phone} </td> {credits}';

var sonidoslistados = '<tr><td><input type="radio" class="audioselected" name="audioselected" value="{id}">{name}</td></tr>';
var sonidoslistadosmini = '<tr><td><input type="radio" class="audioselectedmini" name="audioselectedmini" value="{id}">{name}</td></tr>';


var template_numeros = '<li class="{num}" data-num="{num}"><span class="folder" data-num="{num}">{num}</span><ul class="{num}"></ul></li>';
var template_numerosn = '<li class="subnivel"><span class="folder" data-num="{num}">{num}</span></li>';
/* #PLANTILLAS PARA RENDERIZAR*/


var filtro_global = frm_filtro.serializeArray();

$(document).ready(function($){ 


$(".cerrar a").on("click",function(){
	$(".mensajes").fadeOut();
});

setTimeout(cerraventana, 10000);
function cerraventana(){
	$(".mensajes").fadeOut();
}


jQuery.fn.reset = function () {
  $(this).each (function() { this.reset(); });
}

//Guardar nombre de la campaña
name_guardar_btn 	= $('button[name="btn-campaign-name"]');
name_guardar_text = $('input[name="txt-campaign-name"]');
updated_timeout 	= null;

function saveCampaignName(_name){
	if(updated_timeout) clearTimeout(updated_timeout);

	name_guardar_btn.prop('disabled', true).css('opacity', 0.6);
	name_guardar_text.prop('disabled', true).css('opacity', 0.6);
	//setTimeout(function(){
	$.post(root+"/apps/add_name_campaign",{'name':_name,'id_campaign':id_campaign_var},function(data){
		var obj = jQuery.parseJSON(data);
		if(obj.cod == 1){
			//name_guardar_text.val();
			name_guardar_text.addClass('updated');
		}else{
			name_guardar_text.addClass('no-updated');
			$('.mensajesdeerror').text(obj.messa);
			$('#mensajes').modal('show');
		}
		updated_timeout = setTimeout(function(){
			name_guardar_text.removeClass('updated no-updated');
		}, 2000);
		name_guardar_btn.prop('disabled', false).css('opacity', 1);
		name_guardar_text.prop('disabled', false).css('opacity', 1);
	});
	//}, 1000);
}

name_guardar_text.keyup(function(eventObj){
    if ( eventObj.which == 13 )
    {
       saveCampaignName($(this).val());
    }
});

name_guardar_btn.on('click', function(){
	if(name_guardar_text.val() != '')
	{
		saveCampaignName(name_guardar_text.val());
	}
	return false;
});

$('#frm-campaign-name').on('submit', function(){
	return false;
})

/***FUNCIONES AJAX PARA ENVIO DE DATOS**/
/*$('input[name="txt-campaign-name"]').blur(function(){
	if($(this).val() != ""){
		saveCampaignName($(this).val());
	}
});*/

	
	$(".contactsch").live("change",function(){
		var actual = $(this).parent().parent();
		$.post(root+"/apps/add_contact_campaign", { id_contact: $(this).val(),id_campaign:id_campaign_var },
			   function(data) {
			    var obj = jQuery.parseJSON(data);
			    if(obj.cod == 1){
			    	actual.remove();
			    	get_contacts_campaign(true);
		        }else{
			        
			        show_error(obj);

			        
		        }				 	
		});
	});
	
	$(".deselect").live("click",function(){
		var actual = $(this).parent().parent();
		$.post(root+"/apps/delete_contact_campaign", { id_contact: $(this).data("id"),id_campaign:id_campaign_var },
			   function(data) {
			    var obj = jQuery.parseJSON(data);
			    if(obj.cod == 1){
			    	var cantidadactual = $("#contacts-selected-badge .number").text();
			    	cantidadactual --;
			    	$("#contacts-selected-badge .number").text(cantidadactual);
			    	actual.remove();
			    	get_contacts(true);
		        }else{
		        	show_error(obj);
		        }				 	
		});
		
	});
	
	$(".btn-filter").on("click",function(event){
		
		window.page = 1;
		
		get_contacts(true);
		event.preventDefault();
		
	});
/*** #FUNCIONES AJAX PARA ENVIO DE DATOS**/
	
	
	
	/****FUNCIONES VARIAS PARA MOSTRAR Y OCULTAR ELEMENTOS **/

	$('#steps-tabs a:first').tab('show');

	$("#contacts-filter-ico").click(function(event){
		frm_filtro.slideToggle(500);
	});

	$("#messages-simulation-ico").click(function(event){
		$("#simulation").slideToggle(500);
	});

	//MUESTRA LOS SELECCIONADOS
	$("#contacts-selected-badge").click(function(event){
		$("#selected-data").fadeToggle(0);
		$("#contacts-header").fadeToggle(00);
		
		$("#contacts-data").fadeToggle(500);
		$("#contacts-header2").fadeToggle(500);
		
		
		// $("#contacts-filter-ico").toggle('fast');
	});
	//REGRESA A SELECCIONAR CONTACTOS
	$("#contacts-selected-badge2").click(function(event){
		$("#selected-data").fadeToggle(500);
		$("#contacts-header").fadeToggle(500);
		
		$("#contacts-data").fadeToggle(00);
		$("#contacts-header2").fadeToggle(00);
		
		// $("#contacts-filter-ico").toggle('fast');
	});

	$("#contacts-add-ico").click(function(event){
		$("#frm-add-contact").slideToggle(500);
	});
	/**** FIN FUNCIONES VARIAS PARA MOSTRAR Y OCULTAR ELEMENTOS **/


	/****VENTANAS MODALES EN LAS PETICIONES AJAX **/
	/*$("body").on({
	    ajaxStart: function() { 
	        $('#myModal').modal({show: true});		    
	       },
	    ajaxStop: function() { 
	        $('#myModal').modal('hide');
	    }    
	});*/
	
	/*$(document).ajaxError(function() {
	   $('#myModal').modal('hide');
	   $('.mensajesdeerror').text(" Error al enviar los datos");
	   $('#mensajes').modal('show');
	});*/
	
	/**** #VENTANAS MODALES EN LAS PETICIONES AJAX **/
	$('#frm-campaign-name').submit(function(event){
		event.preventDefault();
	});
	
	$("#selectall").on("click",function(){
		if($(this).is(':checked')){
			$(".contactsch").attr('checked',true);
			var contacts_to = Array();
			$(".contactsch").each(function(){
				contacts_to.push($(this).val());
			});
			$.post(root+"/apps/batch_contact_campaign",{'id_campaign':id_campaign_var,'valores':contacts_to,'page':window.pageS},function(data){
				var obj_int = jQuery.parseJSON(data);
				
				if(obj_int.cod == 0){	
					show_error(obj_int);
		    }else if(obj_int.cod == 1){
					window.location.reload(true);
		    }
		 });
			
		}else{
			$(".contactsch").attr('checked', false);
		}
		
	});
	
	$(".limpiarfiltro").on("click",function(){
		frm_filtro.reset();
		window.page = 1;
		get_contacts(true);
	});
	
	
	$("#show-additional-options").click(function(event){
		$("#buttons").slideToggle(500);
		$("#msg-additional-options").slideToggle(500);
		$("#add_content_tree").slideToggle(500);
	});
		

});
/***FUNCION  PARA TRAER LOS CONTACTOS SELECCIONADOS DE UNA CAMPAÑA *****/
function get_contacts_campaign(redraw){
	
	$.post(root+"/apps/get_contacts_campaign",{'id_campaign':id_campaign_var, 'page': window.pageS},function(data){
		var obj_int = jQuery.parseJSON(data);
		window.totalPagesS = obj_int.totalCC;
		
		$("#contacts-selected-badge .number").text(obj_int.totalCC);
		$("#table-selected tbody").html("");
		$.each(obj_int.contacts, function(i, item) {
			var aux = replaceAll(template_agregado,"{name}",item.name);
			var aux = replaceAll(aux,"{id}",item.id);
			var aux = replaceAll(aux,"{indi_pais}",item.indi_pais);
			var aux = replaceAll(aux,"{phone}",item.phone);
			
			$("#table-selected tbody").append(aux);
			
			if(redraw) redraw_paginationS();
			
		});
		
	});
		
}


/***FUNCION  PARA TRAER LOS CONTACTOS DE UN USUARIO *****/
function get_contacts(redraw){
	filtro_global = frm_filtro.serializeArray();
	filtro_global.push({'name': 'page', 'value': window.page});
	
	$.post(root+"/apps/get_contacts",filtro_global,function(data){
		var obj_int = jQuery.parseJSON(data);
		window.totalPages = obj_int.totalC;
		//var cantidad = obj_int.length;
		//console.log(obj_int[cantidad-1]);
		$("#table-cartera tbody").html("");
		$.each(obj_int.contacts, function(i, item) {		
			//if(i != (cantidad-1)){					
				var aux = replaceAll(template_contacto,"{name}",item.name);
				var aux = replaceAll(aux,"{id}",item.id);
				var aux = replaceAll(aux,"{indi_pais}",item.indi_pais);
				var aux = replaceAll(aux,"{phone}",item.phone);
				 
				if(item.credits){
					var aux = replaceAll(aux,"{credits}","<td>"+item.credits+"</td>");
				}
				 
				/*var extras = obj_int[cantidad-1];
				var complemento = "";
				$.each(extras, function(i, itemex) {
					var test = eval("item."+itemex.name_fields);
					complemento += "<td>"+test+"</td>";
				});
				var aux = replaceAll(aux,"{extrafields}",complemento);*/
				
				$("#table-cartera tbody").append(aux);
				
				
			//}
		});
		if(redraw) redraw_pagination();
	});	
	
}


//********* FUNCION PARA AGREGAR TODOS LOS CONTACTOS ACTUALES A LA CAMPAÑA **********/
$('form[name="form-add-all"]').on('submit', function(event){
	var form = $(this);
	//var form = $('<form>', {'action': root+'/apps/', 'method': 'post', 'id':'form-add-all'});
	if(filtro_global.length){
		for(i=0; i<filtro_global.length; i++){
			form.append($('<input>', {'type': 'hidden', 'name': filtro_global[i].name}).val(filtro_global[i].value));
		}
	}else{
		return false;
	}
});

/***FUNCION PARA CAMBIAR CIUDAD EN EL COMBO BOX SEGUN PAIS *****/
function cambiarCiudad(id_p){
	$.post(root+"/apps/get_city",{id_pais:id_p},function(data){
		var obj_int = jQuery.parseJSON(data);
		var opciones = "";
		if(obj_int){
			$.each(obj_int, function(i, item) {	
				//console.log(item);
				opciones += '<option value="'+item.id+'">'+item.name+'</option>';
			});

		}	
		$("#cbo-city").html(opciones);
		
		
	});
}

function paso2(){
	
	arbol_dial();
	manipulate_audio();
	$('#cbo_datos').change(function(){
	    var tav    = $('#taComentario').val(),
	        strPos = $('#taComentario')[0].selectionStart;
	        front  = (tav).substring(0,strPos),
	        back   = (tav).substring(strPos,tav.length); 
	
	    $('#txt-msg-to-speech').val(front + '{' + $(this).val() + '}' + back);
	    $('#cbo_datos').val('dato');
    });
    
    $("#browser").treeview({animated: "medium",unique: true});
    
    $(".guardarprogreso").click(function(){
    	
    	$.post(root+"/apps/add_audio_campaign", { id_campaign: $(".id_campaign").val(), audio_text: "0",
    										text_speech: $("#txt-msg-to-speech").val(), voice: $("#cbo_voz").val() })
		.done(function(data) {
			$("#pre-cont-text").html("El mensaje configurado actualmente es:<br /><div class='texto'>"+$("#txt-msg-to-speech").val()+"</div>");
			$("#pre-cont-text").fadeIn();
			$("#pre-cont-au").fadeOut();
		});
	    
    });
    
    
    $(".enviartextmini").click(function(){
    	//arr_diales[$(".clicked").data("num")
    	var arbolenviar = "";
    	if($(".clicked").parent().hasClass("subnivel")){
	    	arbolenviar = $(".clicked").parent().parent().parent().data("num")+","+$(".clicked").data("num");
    	}else{
	    	arbolenviar = $(".clicked").data("num");
    	}
    	 //alert(arbolenviar);
    	
    	$.post(root+"/apps/add_tree_audio_text", { id_campaign: $(".id_campaign").val(), audio_text: "0",
    			text_speech: $("#txt-msg-to-speech-small").val(), voice: $("#cbo_vozmini").val(),arbol: arbolenviar},function(data) {
    			if(data.cod == 1){
		 			$('#prueba').text(data.messa).fadeIn();
		 			setTimeout( "$('#prueba').fadeOut();",3000 );
		 		}
		 		//$('html, body').animate({scrollTop:500}, 'slow');
		},"json");   
	});
    
    
    $(".audioselectedmini").live("change",function(){
    
    	 $.post(root+"/apps/add_tree_audio_text", { id_campaign: $(".id_campaign").val(), audio_text: "1", id_audio: $(".audioselectedmini:checked").val(), arbol:get_dial_selected() })
		.done(function(data) {

		});
		
		$.post(root+"/apps/get_audio",{id_audio: $(".audioselectedmini:checked").val(),arbol:get_dial_selected()},function(data){
			if(data.cod == 1){
		 		$('#prueba').text(data.messa);
		 	}
			$(".title-audiomini").text(data.name);
			$(".duracion-audiomini").text(data.duration);
			$(".tamano-audiomini").text(data.size);
			cambiaraudios(data.path);
			
		}, "json");
    });
    
    
    
    $(".audioselected").live("change",function(){
    	
	    $.post(root+"/apps/add_audio_campaign", { id_campaign: $(".audioselected:checked").data('campaign'), audio_text: "1", id_audio: $(".audioselected:checked").val() })
		.done(function(data) {
		  //alert("Data Loaded: " + data);
		});
		
		$.post(root+"/apps/get_audio",{id_audio: $(".audioselected:checked").val()},function(data){
		
			show_error(data);
			$(".title-audio").text(data.name);
			$(".duracion-audio").text(data.duration);
			$(".tamano-audio").text(data.size);
			cambiaraudios(data.path);
			
		}, "json");
	    
    });
    
    $(".paginacionaudios li a").click(function(){
	    cambiarpaginacion($(this));
    });
    
    $(".paginacionaudiosmini li a").click(function(){
	    cambiarpaginacionmini($(this));
    });
    
    
  var selected_text 	= $(".selected_text");
	var selected_audio 	= $(".selected_audio");
	var delete_selected = $('.delete_selected');
	$('.filetree span.folder').live("click",function() {
		if($(this).hasClass('clicked')){
			$(this).removeClass('clicked');
			$(".uploadsmall-arbol").val("");
		}else{
			$('.clicked').removeClass('clicked');
			$(this).addClass('clicked');
			$(".uploadsmall-arbol").val(get_dial_selected()); 
		
			//get_number_tree
			$.post(root+"/apps/get_number_tree",{arbol: get_dial_selected(),id_campaign: $(".id_campaign").val()},function(data){
				//console.log(data);
				if(data.cod != 1){
					show_error(data);
					return false;
				}
				
				delete_selected.fadeOut();
				if(selected_text.is(':visible')){
					selected_text.fadeOut(function(){
						show_selected_message(data);
					});
				}else{
					selected_audio.fadeOut(function(){
						show_selected_message(data);
					});
				}
			}, "json");
		}
	});
	
	function show_selected_message(data){
		if(typeof data.messa.tipo != "undefined"){
			if(data.messa.tipo==1){
				selected_text.children('p').html(data.messa.mensaje);
				selected_text.fadeIn();
			}else{
				selected_audio.children('h5').html(data.messa.audio_name);
				selected_audio.children('audio').attr('src', data.messa.mensaje);
				selected_audio.fadeIn();
			}

			var number = get_dial_selected();
			$('a', delete_selected).attr('data-delete', number);
			$('a strong', delete_selected).text(String(number).slice(-1));
			delete_selected.fadeIn();
		}
	}

	$('div.delete_selected a').live('click',function(){
		var id_campaign = $(this).attr('data-campaign');
		var arbol       = $(this).attr('data-delete');
		if(confirm('¿Esta seguro que desea eliminar la opción '+ String(arbol).slice(-1)+' seleccionada?')){
			$.post(root+'/apps/delete_content_from_tree_campaign', {'id_campaign': id_campaign, 'arbol': arbol}, function(data){
				var dat = $.parseJSON(data);
				if(dat.cod == 0){
					show_error(dat);
				}else if(dat.cod == 1){
					window.location = window.location.href.split('#')[0] + '#msg-additional-options'; 
					window.location.reload(true);
				}
			});
		}
		return false;
	});
}


function cambiarpaginacion(este){
	
	este.parent().addClass("active").siblings().removeClass("active");
	$.post(root+"/apps/get_audios_all",{pos: este.data("num"),id_campaign: $(".id_campaign").val()},function(data){
			console.log(data);
			$(".tabladesonidos").html("");
			$.each(data, function(i, item) {	
				//tabladesonidos
				
				var aux = sonidoslistados;
				aux = replaceAll(aux,"{name}",item.name);
				aux = replaceAll(aux,"{id}",item.id);
				$(".tabladesonidos").append(aux);
			});
			
	}, "json");
}

function cambiarpaginacionmini(este){
	
	este.parent().addClass("active").siblings().removeClass("active");
	$.post(root+"/apps/get_audios_all",{pos: este.data("num"),id_campaign: $(".id_campaign").val()},function(data){
			$(".tabladesonidosmini").html("");
			$.each(data, function(i, item) {
				var aux = sonidoslistadosmini;
				aux = replaceAll(aux,"{name}",item.name);
				aux = replaceAll(aux,"{id}",item.id);
				$(".tabladesonidosmini").append(aux);
			});
			
	}, "json");
}



function manipulate_audio(){

	//$('audio').mediaelementplayer({features: ['playpause'],audioWidth:30});
	//player = new MediaElementPlayer('audio', {features: ['playpause'],audioWidth:30});
}
function cambiaraudios(audionuevo){
	$("#pre-cont-au audio").attr("src",root+"/public/audios/"+audionuevo);
	$("#pre-cont-text").fadeOut();
	$("#pre-cont-au").fadeIn();
	$("#player2").css("display","inline-block");
	
}

function arbol_dial(){
	$(".dial-button").on("click", function(){
		var numeromarcado = $(this).data("num");
		//&& $(".clicked").length == 0
		if(typeof arr_diales[numeromarcado] == 'undefined' && ($(".clicked").length == 0 || $(".clicked").data("num") == "inicial")){
			arr_diales[numeromarcado] = new Array();
			
			var aux = template_numeros;
			aux = replaceAll(aux,"{num}",$(this).data("num"));
			var adicionar = $("#browser .inicial > ul").append(aux);
			
			$("#browser").treeview({
				add: adicionar,
				unique: true
			});
			
			
		}	
		
		if($(".clicked").length > 0 && $(".clicked").data("num")!= "inicial"){
			if(typeof arr_diales[$(".clicked").data("num")][numeromarcado] == 'undefined' && !$(".clicked").parent().hasClass("subnivel")){
				arr_diales[$(".clicked").data("num")][numeromarcado] = new Array();
				
				var aux = template_numerosn;
				aux = replaceAll(aux,"{num}",$(this).data("num"));
				var adicionar = $("#browser .inicial ."+$(".clicked").data("num")+" ul").append(aux);
				
				$("#browser").treeview({
					add: adicionar,
					unique: true
				});
			}
			
		}
	});
}

function get_dial_selected(){
	var arbolenviar = "";
	if($(".clicked").parent().hasClass("subnivel")){
    	arbolenviar = $(".clicked").parent().parent().parent().data("num")+","+$(".clicked").data("num");
	}else{
    	arbolenviar = $(".clicked").data("num");
	}
	if(typeof arbolenviar == 'undefined'){
		arbolenviar = "";
	}
	
	return arbolenviar;
}

function enviarformfinal(){
	$("#form_final").submit();
}


/***FUNCION AUXILIAR PARA REEMPLAZAR, YA QUE LA DE JAVASCRIPT SOLO REEMPLAZA EL PRIMERO *****/
function replaceAll( text, busca, reemplaza ){
  while (text.toString().indexOf(busca) != -1)
      text = text.toString().replace(busca,reemplaza);
  return text;
}


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

/*******************************  POCHO CODIGO ********************************/
$("input[name='Filedata']").on('change', function(){
	loadFileData(this);
});
$('.fakeupload p', "#dropdiv-small").on('click', function(){
	$("input[name='Filedata']","#dropdiv-small").trigger('click');
});
$('.fakeupload p', "#dropdiv").on('click', function(){
	$("input[name='Filedata']","#dropdiv").trigger('click');
});
/**
* Función para cargar el icono cuando se va a subir el audio, 
* para mostrar que ya tiene algo seleccionado.
* @param _this el elemento de tipo file que activa la función onchange
* @return no retorna nada.
*/
function  loadFileData(_this){
	var filename = $(_this).val().replace(/C:\\fakepath\\/i, '');
	var context = $('#'+$(_this).data('context'));
	$('.initial-audio-upload', context).hide();
	$('.fakeupload', context).hide();
	$(".msg", context).hide();
	$(".fakeupload", context).children('p').children('span').html(filename);
	$(".fakeupload", context).fadeIn();
}

//FUNCIÓN PARA PEDIR EL AUDIO ESPECIAL EN PASO 2
$('#request_audio').on('click', function(){
	$(this).prop('disabled', true);
	$.post(root+"/apps/request_profesional_audio",{id_user: $(this).data('iduser'), id_app: $(this).data('idapp')},function(data){
		var obj = $.parseJSON(data);
		if(obj.cod == 1){
			$('.request_audio').empty().html('<p style="color: #5ca05b;">'+obj.messa+'</p>');
		}else{
			$(this).prop('disabled', false);
			show_error(obj);
		}
	});
});


//FUNCIONES AGREGADAS PARA EL PASO DOS NUEVO CON LIBRERÍA DE CONTENIDOS

$('.clean_select').on('click', function(){
	$('input[name="check_content"]').attr('checked', false);
});

can_submit = false;

$('form[name="admin_contents"]').on('submit', function(){
	if(!can_submit){
		return false;
	}else{
		can_submit = false;
		//Keep submiting
	}
});

//EVENTO PARA AGREGAR EL CONTENIDO AL PRINCIPAL O LA OPICIÓN SELECCIONADA.
$(document).on('click', '.telefono',function(event){
	$('input[name="check_content"]').val($(this).data('content'));
	if($('input[name="arbol"]').val()!=""){
		var data = $('form[name="admin_contents"]').attr('action', root+"/apps/add_content_to_tree_campaign");
	}else{
		var data = $('form[name="admin_contents"]').attr('action', root+"/apps/add_content_to_main_campaign");
	}
	can_submit = true;
	data.submit();
	event.preventDefault();
});