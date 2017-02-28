/*RUTA GLOBAL */
var root = "/"+window.KKATOO_ROOT;
var offset;
var id_abierto;
var id_abierto_view;

$(document).ready(function($){

	$(".cerrar a").on("click",function(){
		$(".mensajes").fadeOut();
	});
	
	setTimeout(cerraventana, 10000);
	function cerraventana(){
		$(".mensajes").fadeOut();
	}

	/****************************************************
	* FUNCION PARA IGUALAR LAS ALTURA DE LAS COLUMNAS
	****************************************************/
	jQuery.fn.balanceColumns = function(parent, child) {
		var h_parent = $(parent).innerHeight();
		$(child).height(h_parent)
	};

	$().balanceColumns("#content", "#groups");



	/****************************************************
	* SCRIPT PARA QUE FUNCIONEN LOS CHECKBOXES BIEN
	****************************************************/
    var selecteds = 0;
    $(".contact-select").click(function(event) { 
        if($(this).is(":checked")) {
        	$(".mass-btn").css('display','')
        	selecteds += 1;
        	if (selecteds > 1) {
        		$(".del-btn").css('display','')	
	        }
        } else {
		    //console.log(selecteds);
        	selecteds -= 1;
        	if (selecteds <= 1) {
        		$(".del-btn").css('display','none')	
	        }
        	$(".check-all").removeAttr("checked");
        	if (selecteds <= 0) {
        		$(".mass-btn").css('display','none')	
        	} 
        }  
    });

    // CHECKBOX PARA SELECCIONAR TODO
	$(".check-all").click(function(event){
		if($(this).is(":checked")) {
			$(".mass-btn").css('display','');
			$(".contact-select:checkbox:not(:checked)").attr("checked", "checked");
			selecteds = $('input:checkbox:checked').size() - 1; 
			$(".del-btn").css('display','');
			$(".delall-btn").css('display', '');
		}else{
			$(".mass-btn").css('display','none');
		 	$(".contact-select:checkbox:checked").removeAttr("checked");
			selecteds = 0;
			$(".del-btn").css('display','none');
			$(".delall-btn").css('display', 'none');
		}
	});



	/****************************************************
	* MOSTRAR/OCULTAR EL LINK PARA CAMBIAR DE IMAGEN EN ADD-ITEM
	****************************************************/
	$("#item-img").bind('mouseenter', function() {
		$("#overlay-change-photo").css('display','')
		//alert('El ratón ha entrado o salido del elemento "foo."');
	});
	$("#item-img").bind('mouseleave', function() {
		$("#overlay-change-photo").css('display','none')
		//alert('El ratón ha entrado o salido del elemento "foo."');
	});

	$("#overlay-change-photo").bind('click', function() {
		
	});

	
	/****************************************************
	* MOSTRAR / OCULTAR FLOTANTES GRANDES
	****************************************************/	
 	$("#add-ico").bind('click', function(event){
	    event.preventDefault();
	    $("#frm-add-contact").slideToggle(500);
	    $("#import-csv").slideUp(500);
	    $("#import-gmail").slideUp(500);

	    // if ($('#triangle-up-inn-1').is(':visible')) {
	    // 	$("#triangle-up-inn-1").delay(300)
	    // 	$("#triangle-up-inn-1").fadeOut(300);
	    // } else {
	    // 	$("#triangle-up-inn-1").fadeIn();
	    // }
	    // $("#triangle-up-inn-2").fadeOut();
	    // $("#triangle-up-inn-3").fadeOut();
   	});
   	$("#import-csv-ico").bind('click', function(event){
	    event.preventDefault();
	    $("#import-csv").slideToggle(500);
	    $("#frm-add-contact").slideUp(500);
	    $("#import-gmail").slideUp(500);

	    // if ($('#triangle-up-inn-1').is(':visible')) {
	    // 	$("#triangle-up-inn-1").delay(300)
	    // 	$("#triangle-up-inn-1").fadeOut(300);
	    // } else {
	    // 	$("#triangle-up-inn-1").fadeIn();
	    // }
	    // $("#triangle-up-inn-2").fadeOut();
	    // $("#triangle-up-inn-3").fadeOut();
   	});
	
	$("#import-gmail-ico").bind('click', function(event){
	    event.preventDefault();
	    $("#import-gmail").slideToggle(500);
	    $("#import-csv").slideUp(500);
	    $("#frm-add-contact").slideUp(500);

	    // if ($('#triangle-up-inn-1').is(':visible')) {
	    // 	$("#triangle-up-inn-1").delay(300)
	    // 	$("#triangle-up-inn-1").fadeOut(300);
	    // } else {
	    // 	$("#triangle-up-inn-1").fadeIn();
	    // }
	    // $("#triangle-up-inn-2").fadeOut();
	    // $("#triangle-up-inn-3").fadeOut();
   	});

   	$("#groups-add-ico").bind('click', function(event){
	    event.preventDefault();
	    $("#add-group").slideToggle(500);
	    // $("#import-csv").slideUp(500);
	    // $("#frm-add-contact").slideUp(500);

	    // if ($('#triangle-up-inn-1').is(':visible')) {
	    // 	$("#triangle-up-inn-1").delay(300)
	    // 	$("#triangle-up-inn-1").fadeOut(300);
	    // } else {
	    // 	$("#triangle-up-inn-1").fadeIn();
	    // }
	    // $("#triangle-up-inn-2").fadeOut();
	    // $("#triangle-up-inn-3").fadeOut();
   	});
	

	/****************************************************
	* MOSTRAR / OCULTAR FLOTANTES PEQUEÑOS (items)
	****************************************************/
	
	$(".item-edit-ico").bind('click', function(event){
		var id_actual = $(this).find("a").data("id");
		if(id_actual != id_abierto){ 
			id_abierto = id_actual;
		    event.preventDefault();
		    
		    var i = $(this).parent().parent();
		    var contenido = $(".edit-item").html();
		    
		    $(".edit-item").remove();
		    var contenidainsertar = '<tr class="edit-item" style="display:none">'+contenido+'</tr>';
				$(contenidainsertar).insertAfter(i);
		   /* if($(".contactostbody tr").length == 2){
			    $(contenidainsertar).appendTo(".contactostbody");
		    }else{
		    	if( i > $(".contactostbody tr").length ){
			    	$(contenidainsertar).appendTo(".contactostbody");

		    	}else{
		    		$(contenidainsertar).insertBefore(".contactostbody tr:nth-child(" + i + ")");
		    	}	    	
		    }*/
		    
		   
			$(".edit-item #txt-name").val($("#name_"+id_actual).text());
			$(".edit-item #txt-phone").val($("#phone_"+id_actual).text());
			
			$(".edit-item .id_contact").val(id_actual);
			
			for(var i = 0; i < arr_campos_dinamicos.length;i ++){
				var obj = $(".edit-item .editar-"+arr_campos_dinamicos[i][0]).val($("."+arr_campos_dinamicos[i][0]+"_"+id_actual).text());
			}
			
			$.post("/contacts/get_country_contacts", { "id_contact": id_actual },
			  function(data){
			  	if(data.cod != 0){
			  		$(".edit-item #cbo-country").val(data.country.id);
			  		cambiarCiudadEditar(data.country.id,data.city.id);
				  	
			  	}
			  }, "json");
			  
			   $(".view-item").fadeOut(0);
			   $(".edit-item").fadeIn(500);
		 }else{
		 	$(".view-item").fadeOut(0);
			$(".edit-item").fadeOut(500);
			id_abierto = "";
			 
		 }
		 jQuery('.calendario_edit').removeClass('hasDatepicker').datepicker({dateFormat: 'dd/mm/yy'});
	});
	
	/****************************************************
	* CAMBIAR DE SECCION LATERAL
	****************************************************/
	$("#reload-link").bind('click', function() {
		event.preventDefault();
	    $("#reload-contents").fadeIn(500);
	    $("#resume-contents").fadeOut(0);
	    $("#simulator-contents").fadeOut(0);

	    $("#reload-link").addClass('selected')
	    $("#resume-link").removeClass('selected')
	    $("#simulator-link").removeClass('selected')
	});

	$("#resume-link").bind('click', function() {
		event.preventDefault();
	    $("#reload-contents").fadeOut(0);
	    $("#resume-contents").fadeIn(500);
	    $("#simulator-contents").fadeOut(0);

	    $("#reload-link").removeClass('selected')
	    $("#resume-link").addClass('selected')
	    $("#simulator-link").removeClass('selected')
	});

	$("#simulator-link").bind('click', function() {
		event.preventDefault();
	    $("#reload-contents").fadeOut(0);
	    $("#resume-contents").fadeOut(0);
	    $("#simulator-contents").fadeIn(500);

	    $("#reload-link").removeClass('selected')
	    $("#resume-link").removeClass('selected')
	    $("#simulator-link").addClass('selected')
	});
	
	
	
	
   	$(".item-view-ico").bind('click', function(event){
	    event.preventDefault();
	    var id_actual = $(this).find("a").data("id");
	    
	    if(id_actual != id_abierto_view){ 
	    	id_abierto_view = id_actual;
		    
		    
		    var i = $(this).parent().parent();//.index()+2;
		    var contenido = $(".view-item").html();
		    $(".view-item").remove();
		    var contenidoainsertar = '<tr class="view-item" style="display:none">'+contenido+'</tr>';
				$(contenidoainsertar).insertAfter(i);
		    /*if($(".contactostbody tr").length == 2){
			    $(contenidoainsertar).appendTo(".contactostbody");
		    }else{
		    	if( i > $(".contactostbody tr").length ){
		    		$(contenidoainsertar).appendTo(".contactostbody");
		    	}else{
		    		$(contenidoainsertar).insertBefore(".contactostbody tr:nth-child(" + i + ")");
		    	}
		    }*/
		    
		    
		    
		   
		   
			$(".view-item .edit-nombre").text($("#name_"+id_actual).text());
			$(".view-item .edit-phone").text($("#phone_"+id_actual).text()); 
			
			for(var i = 0; i < arr_campos_dinamicos.length;i ++){
				$(".view-item .edit-"+arr_campos_dinamicos[i][0]).text(arr_campos_dinamicos[i][1]+": "+$("."+arr_campos_dinamicos[i][0]+"_"+id_actual).text());
			}
			
			$.post("/contacts/get_country_contacts", { "id_contact": id_actual },
			  function(data){
			  	if(data.cod != 0){
			  		$(".view-item .edit-country-city").text(data.country.name+"/"+data.city.name);
				  	
			  	}
			  }, "json");
			  $(".edit-item").fadeOut(0);
			  $(".view-item").fadeIn(500);
		  }else{
		  	id_abierto_view = 0;
		  	$(".edit-item").fadeOut(0);
		  	$(".view-item").fadeOut(500);
			  
		  }
		

   	});

   	/****************************************************
	* MOSTRAR / OCULTAR overlay imagen en pequeños
	****************************************************/

   	$(".item-img").bind('mouseenter', function() {
		$(".overlay-change-photo").css('display','')
		//alert('El ratón ha entrado o salido del elemento "foo."');
	});
	$(".item-img").bind('mouseleave', function() {
		$(".overlay-change-photo").css('display','none')
		//alert('El ratón ha entrado o salido del elemento "foo."');
	});

	$(".overlay-change-photo").bind('click', function() {
		alert('Aquí se programa el ajax encontrado en \n\nhttp://www.miguelmanchego.com/2010/jquery-subir-archivos-usando-ajax/\n\n Para subir la imagen');
	});
	
	$(".cerrar a").click(function(){
		$("p.mensajes").fadeOut();
		
	});
	
	/****************************************************
	* CAMBIO DE PAIS EN LOS PAGOS
	****************************************************/
	
	//get_city
	//id_country
	$('.cbo-country-pp').change(function(){
		cambiarCiudad($(this).val());
	});
	


	/*************************************************************
	* CAMBIA EL DIV SEGUN LA ACCION ESCOGIDA EN EL CONTACT MANAGER
	**************************************************************/
	$(".action-options").hide();
	$('#cbo-accion').change(function(){
		cambiarAccion($(this).val());
	});



	

});


/***FUNCION PARA CAMBIAR CIUDAD EN EL COMBO BOX SEGUN PAIS *****/
function cambiarCiudad(id_p){
	$.post("/contacts/get_city",{id_pais:id_p},function(data){
		var obj_int = jQuery.parseJSON(data);
		var opciones = "";
		if(obj_int){
			$.each(obj_int, function(i, item) {	
				//console.log(item);
				opciones += '<option value="'+item.id+'">'+item.name+'</option>';
			});

		}	
		$(".cbo-city").html(opciones);
		
		
	});
}

/***FUNCION PARA CAMBIAR CIUDAD EN EL COMBO BOX SEGUN PAIS *****/
function cambiarCiudadEditar(id_p, seleccionado){
	$.post("/contacts/get_city",{id_pais:id_p},function(data){
		var obj_int = jQuery.parseJSON(data);
		var opciones = "";
		if(obj_int){
			$.each(obj_int, function(i, item) {
				var sel = "";
				if(item.id == seleccionado){
					sel = "selected";
				}
				opciones += '<option value="'+item.id+'" '+sel+'>'+item.name+'</option>';
			});

		}	
		$(".cbo-city").html(opciones);
		
		
	});
}


/***FUNCION AUXILIAR PARA REEMPLAZAR, YA QUE LA DE JAVASCRIPT SOLO REEMPLAZA EL PRIMERO *****/
function replaceAll( text, busca, reemplaza ){
  while (text.toString().indexOf(busca) != -1)
      text = text.toString().replace(busca,reemplaza);
  return text;
}


/***FUNCION PARA CAMBIAR EL DIV DE LAS OPCIONES DE CONTACTOS EN EL CONTACT-MANAGER *****/
function cambiarAccion(metodo){
	$(".action-options").hide();
	$("#"+ metodo + "_options").show();
}
