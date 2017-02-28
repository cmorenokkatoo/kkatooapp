var root = "/"+window.KKATOO_ROOT;

//Modal de terminos y condiciones
$('.tyc').on('click', function(){
	$('.tycmodal').fadeIn();
	return false;
});
$('.tycmodal-back').on('click', function(){
	$('.tycmodal').fadeOut();
	return false;
});
$('.tycmodal a.close_modal').on('click', function(){
	$('.tycmodal').fadeOut();
	return false;
});

//Modal de Políticas de privacidad
$('.pdp').on('click', function(){
	$('.pdpmodal').fadeIn();
	return false;
});
$('.pdpmodal-back').on('click', function(){
	$('.tycmodal').fadeOut();
	return false;
});
$('.pdpmodal a.close_modal').on('click', function(){
	$('.pdpmodal').fadeOut();
	return false;
});

var tpl_contact = '<div data-id="${id}" class="section inlineblock"><img src="'+root+'/assets/img/imagen-rostro.jpg" /><div class="detalles"><h4>${name}</h4><p>Tel: ${phone}<a href="javascript:;" class="circle">x</a></p></div></div>';



$(".cancelar_btn").live("click",function(){
	$(this).parent().parent().parent().remove();	
});

var iframes = new Array();
var last 		= null;

$('#myCarousel').find('.item').each(function(i, d){
	iframes[i] = {item: null, iframe: false};
	iframes[i].item = $(d);
	if($(d).find('iframe').length>0){
		iframes[i].iframe = $(d).find('iframe').eq(0);
	}else{
		iframes[i].iframe = false;
	}
	
});

$('.carousel').carousel({
	interval:false
});

$('#myCarousel').bind('slide', function() {
		last = $('div.active', this).index();

		var next = last + 1;
		if(next == iframes.length) next = 0;
    if(iframes[next].iframe){
    	iframes[next].item.find('.img-item').append(iframes[next].iframe);
    }
});

$('#myCarousel').bind('slid', function() {
    if(iframes[last].iframe){
    	iframes[last].item.find('.img-item').empty();
    }
});

// $('#myCarousel').hover(function () {
// 	$(this).carousel('pause'),

// });

// $(".btn_free_proof").on("click",function(){
// 	alert(hola),
// 	$(location).attr('href',"http://google.com")
// });

$(".cerrar a").on("click",function(){
	$(".mensajes").fadeOut();
});

setTimeout(cerraventana, 10000);
function cerraventana(){
	$(".mensajes").fadeOut();
}




$("a.app_link").live("click",function(){
	$.post(root+"/marketplace/get_by_id_app", { id: $(this).data("id") },
	   function(data) {
		 var obj = jQuery.parseJSON(data);
		 render_app(obj);
		 if($("#sitio-market").css("display") == "none"){
			 $("#sitio-market").slideDown();
		 }
		 	
	});
});

$("button").on("click",function(){
	window.location.href = $(this).data("link");
});

function render_app(obj){
	var imagen = $("#sitio-market .imagen img");
	var titulo = $("#sitio-market h2.titulo");
	var descripcion = $("#descripcion .the_tab_descripcion");
	var comentario = $("#comentarios .the_tab_content2");
	var puntos 		= $(".inforating span");
	var ocultocomentario = $("#hiddencomment");
	var button = $("button");
	
	
	descripcion.html(obj.description);
	titulo.text(obj.title);
	imagen.attr("src",root+"/assets/"+obj.image);
	
	appactual = obj.id;
	ocultocomentario.val(appactual);
	if(obj.tipo == 1){
		if(obj.url_landing){
			button.data("link",obj.url_landing);
		}else{
			button.data("link",root+"/landing/"+obj.uri);
		}
		button.html("SUSCRIBIRSE");
	}else{
		button.data("link",root+"/landing/"+obj.uri);
		button.html("USAR APLICACIÓN");
	}
	
	comentario.html("");
	puntos.text(Math.round((obj.points/obj.cuantos)*100)/100);
	
	if(typeof(obj.comments) != "undefined" && obj.comments !== null) {
		jQuery.each(obj.comments,function(){
			comentario.append('<div class="comentario"><h3>'+this.fullname+'</h3><p>'+this.comentario+'</p><p class="p-divider-dotted"></p></div>')
		});
	}
	
	var high = 0;
	$('body,html').animate({
			scrollTop: 0
	}, 800,function(){
		if(high == 0){
			$( "#sitio-market" ).effect("highlight", {}, 1000);
			high ++;
		}else{
			high = 0;
		}
		
	});
	
	return false;
}



 $("form#enviar-comentario").submit(function(){
	$.ajax({
		type: 'POST',
		url: $(this).attr('action'),
		data: $(this).serialize(),
		success: function(data) {
			var obj = jQuery.parseJSON(data);
			if(obj.cod == 1){
				$('#comentarios .the_tab_content').append('<div class="comentario"><h3>'+obj.fullname+'</h3><p class="the_tab_descripcion">'+obj.comentario+'</p></div>');
				$("textarea#comment").val("");
				
			}
		    
		}
	});      
   return false;
  });
  
  
  
  jQuery.ias({
	  container 	: ".seachlisting",
	  item		: "article.mkt-app-ficha",
	  pagination	: "#navigation_id",
	  next		: ".next-posts a",
	  loader	: root+"/assets/img/loader.gif",
	  history:false
});
/*
Comentariado por un posible error en IE8, editado por alesjandro
////
crear_lista();
function crear_lista(){
	$(".listado-llamar").live("mouseover",function(){
		$(this).find("a").removeClass("hide").addClass("show");
	});
	$(".listado-llamar").live("mouseout",function(){
		$(this).find("a").removeClass("show").addClass("hide");
	});
	$( ".interna2-int .section" ).draggable({  helper: "clone",revert: "valid",iframeFix: true});
    $( ".internat3-content" ).droppable({
            drop: function( event, ui ) {
             
              $.post(root+"/apps/add_contact_campaign", { id_contact: ui.draggable.data("id"),id_campaign:id_campaign_var },
			   function(data) {
			    var obj = jQuery.parseJSON(data);
			    if(obj.cod == 1){
					  var id_drop = ui.draggable.data("id");
		              var name_drop = ui.draggable.find("h4").text();
		              $(".internat3-content").prepend('<p data-id="'+id_drop+'" class="listado-llamar"><a href="javascript:;" class="hide circle">x</a>'+name_drop+'</p>');
			          ui.draggable.remove();
		          }				 	
			});

              
              
            }
    });	
}
*/

$('audio').mediaelementplayer({features: ['playpause'],audioWidth: 27});
$('.interna2-int-texto').hide();

$('.activar-audio').on('click',function(){
	$('.interna2-int-texto').hide();
	$('.interna2-int').show();
	
});
$('.activar-texto').on('click',function(){
	$('.interna2-int-texto').show();
	$('.interna2-int').hide();
});

$('.enviartexto').submit(function(){
	
	$.post(root+'/apps/add_audio_campaign',$(this).serialize(),function(data){
		var obj  = jQuery.parseJSON(data);
		if(obj.cod == 1){
			$('.audiovista').hide();
			$('.textovista').show();
			
			$('.textovista p').text($('textarea').val());
		}
	});
	return false;
});

$('.interna2-int-step2 .section').on('click',function(){
	
	var nombre = $(this).find('h4').text();
	var duracionaudio = $(this).find('p').text();
	var source = $(this).data('source');
	
	$.post(root+'/apps/add_audio_campaign',{audio_text:1,id_audio:$(this).data('id'),id_campaign:id_campaign_var},function(data){
		var obj  = jQuery.parseJSON(data);
		if(obj.cod == 1){
			$('.nombreaudio').text(nombre);
			$('.duracionaudio').text(duracionaudio);
			changeAudio(root+'/public/audios/'+source);
			$('.audiovista').show();
			$('.textovista').hide();
			
		}
	});
});


function changeAudio(sourceUrl) {
    var audio = $("audio");      
    audio.attr("src", sourceUrl);
    /****************/
    audio[0].pause();
    audio[0].load();//suspends and restores all audio element
    audio[0].play();
    /****************/
}

$('.cancelcsv').on('click',function(){
	$('.csvdescription').fadeOut();
	return false;
});
var datapick = $( "#datepicker" ).datepicker({ dayNamesMin: [ "DOM", "LUN", "MAR", "MIE", "JUE", "VIE", "SAB" ],
								onSelect: function(dateText, inst) {
										      $(".ocultofecha").val(dateText);
										  },
								 dateFormat: 'yy-mm-dd'
});

function redireccionar(url){
	location.href=url;
}
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!

var yyyy = today.getFullYear();
if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = yyyy+'-'+mm+'-'+dd;

 $(".ocultofecha").val(today);
/*$('.confirmarcsv').submit(function(){
	return false;
});*/

//formulariofinal
$(".enviarform").on("click",function(){
	$(".formulariofinal").submit();
});