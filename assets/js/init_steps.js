$(document).ready(function($){

/*******************************************************************************************************/
/* EFECTO PARA MOSTRARLA SELECCIÓN DE UN CONTACTO (Falta guardarlo en un array de verdad)
/*******************************************************************************************************/
	$(".contact-element").click(function() {
		//AGREGAR POR HTML UN DIV CON LA MISMA POSICIÓN QUE EL $(this)
		//AGREGAR EL ID DEL CONTACTO A UN ARRAY PARA QUE CUANDO 

	});



/*******************************************************************************************************/
/* EFECTOS QUE MUESTRAN y OCULTAN LOS FLOTANTES */
/*******************************************************************************************************/
/*	$("#addcontssact").click(function(event){
	    event.preventDefault();
	    $("#capaefectos").hide("slow");
   });
*/
   $("#addcontact").click(function(event){
	    event.preventDefault();
	    $("#contact-add").slideToggle(500);
	    
	    $("#contact-import-plain").slideUp(500);
	    $("#contact-import-gmail").slideUp(500);
	    
	    if ($('#triangle-up-inn-1').is(':visible')) {
	    	$("#triangle-up-inn-1").delay(300)
	    	$("#triangle-up-inn-1").fadeOut(300);
	    } else {
	    	$("#triangle-up-inn-1").fadeIn();
	    }
	    $("#triangle-up-inn-2").fadeOut();
	    $("#triangle-up-inn-3").fadeOut();

   });

   $("#addplaintext").click(function(event){
	    event.preventDefault();
	    $("#contact-add").slideUp(500);
	    $("#contact-import-plain").slideToggle(500);
	    $("#contact-import-gmail").slideUp(500);

		if ($('#triangle-up-inn-2').is(':visible')) {
	    	$("#triangle-up-inn-2").delay(300)
	    	$("#triangle-up-inn-2").fadeOut(300);
	    } else {
	    	$("#triangle-up-inn-2").fadeIn();
	    }
	    $("#triangle-up-inn-1").fadeOut();
	    $("#triangle-up-inn-3").fadeOut();
	});

   $("#addgmail").click(function(event){
	    event.preventDefault();
	    $("#contact-add").slideUp(500);
	    $("#contact-import-plain").slideUp(500);
	    $("#contact-import-gmail").slideToggle(500);

		if ($('#triangle-up-inn-3').is(':visible')) {
	    	$("#triangle-up-inn-3").delay(300)
	    	$("#triangle-up-inn-3").fadeOut(300);
	    } else {
	    	$("#triangle-up-inn-3").fadeIn();
	    }	    
	    $("#triangle-up-inn-1").fadeOut();
	    $("#triangle-up-inn-2").fadeOut();

   });
/*
   $("#uploadaudio").click(function(event){
	    event.preventDefault();
	    $("#contact-import-plain").slideUp(500);
	    $("#contact-import-gmail").slideToggle(500);
		if ($('#triangle-up-inn-0').is(':visible')) {
	    	$("#triangle-up-inn-0").delay(300)
	    	$("#triangle-up-inn-0").fadeOut(300);
	    } else {
	    	$("#triangle-up-inn-0").fadeIn();
	    }	    
	    $("#triangle-up-inn-1").fadeOut();

   	});

   $("#recordaudio").click(function(event){
	    event.preventDefault();
	    $("#contact-import-plain").slideToggle(500);
	    $("#contact-import-gmail").slideUp(500);
		if ($('#triangle-up-inn-1').is(':visible')) {
	    	$("#triangle-up-inn-1").delay(300)
	    	$("#triangle-up-inn-1").fadeOut(300);
	    } else {
	    	$("#triangle-up-inn-1").fadeIn();
	    }	    
	    $("#triangle-up-inn-0").fadeOut();

   	});

 /*
*/

/*******************************************************************************************************/
/* CONVIERTE EL SELECT DE GRUPOS EN UN CAMPO */
/*******************************************************************************************************/
	//Entre los corchetes va el ID de los elementos que quiero preseleccionados separados por coma.
	//En este caso está preseleccionado por defecto el grupo "Todos", para evitar errores
	$("#cbo-group").val(["001"]).select2({
		/*tags:["red", "green", "blue"]*/
        placeholder: "GRUPO",
        width:370
    });

    $("#cbo-group-plano").val(["001"]).select2({
		/*tags:["red", "green", "blue"]*/
        placeholder: "GRUPO",
        width:370
    });
    $("#cbo-group-gmail").val(["005"]).select2({
		/*tags:["red", "green", "blue"]*/
        placeholder: "GRUPO",
        width:370
    });
    
	$("#cbo-group-gmail").select2("disable");

    //Esto desactiva la edición del campo por si en la etapa inicial no queremos que haya grupos, sólo el "Todos"
    //$("#cbo-group").select2("disable");


/*******************************************************************************************************/
/*******************************************************************************************************/
/* SCROLL PARA EL ÁREA CENTRAL DE CONTACTOS                                                            *
/*******************************************************************************************************/
/*******************************************************************************************************/

/* VARIABLES  *****************/
	var cont_det    = $("#contact-details-container");
	var inner_det   = $("#contact-details-list");
	var h_cont_det  = cont_det.innerHeight();   //altura del contenedor
	var h_inner_det = inner_det.innerHeight();  //altura del contenido

	if (h_inner_det < h_cont_det) {
		 $("#down-contacts-details").css("display", "none");
	}

/* EVENTOS *****************/

	$('#up-contacts-details').click(function(){
		cont_det.stop().scrollTo( '-=150', 500 );
		buttons_check_inup_details();
	});

	$('#down-contacts-details').click(function(){
		//alert ("hola");
		cont_det.stop().scrollTo( '+=150', 500 );
		buttons_check_indown_details();
	});

/* FUNCIONES *****************/

	function buttons_check_indown_details() { 
		var cont    = $("#contact-details-container");
		var inner   = $("#contact-details-list");
		var scroll  = 100;                  //cantidad de desplazamiento de la ventana
		var scrolx2 = scroll * 2;           // *2 para ayudar a desaparecer y aparecer scroll

		var p_cont  = cont.position().top;  //posición del contenedor
		var p_inner = inner.position().top; //posición del contenido
		var h_cont  = cont.innerHeight();   //altura del contenedor
		var h_inner = inner.innerHeight();  //altura del contenido

		//aparecer el botón de arriba
		if (p_cont <= p_inner) {
	        $("#up-contacts-details").css("display", "block");
	    }
	    //desaparecer el botón de abajo
	    var p_actual = (p_cont + h_cont - h_inner );
	    if (p_inner <= p_actual) {
	        $("#down-contacts-details").css("display", "none");
	    }
	};

	function buttons_check_inup_details() { 
		var cont    = $("#contact-details-container");
		var inner   = $("#contact-details-list");
		var scroll  = 100;                  //cantidad de desplazamiento de la ventana
		var scrolx2 = scroll * 2;           // *2 para ayudar a desaparecer y aparecer scroll

		var p_cont  = cont.position().top;  //posición del contenedor
		var p_inner = inner.position().top; //posición del contenido
		var h_cont  = cont.innerHeight();   //altura del contenedor
		var h_inner = inner.innerHeight();  //altura del contenido

		//desaparecer el botón de arriba
		if (p_cont <= p_inner) {
	        $("#up-contacts-details").css("display", "none");
	    }
	    //aparecer el botón de abajo
		var p_actual = (p_cont + h_cont - h_inner + scrolx2);
		if (p_inner <= p_actual) {
	        $("#down-contacts-details").css("display", "block");
	    }
	};











/*******************************************************************************************************/
/*******************************************************************************************************/
/* SCROLL PARA EL ÁREA DE CONTACTOS SELECCIONADOS                                                      */
/*******************************************************************************************************/
/*******************************************************************************************************/

/* VARIABLES *****************/

	var cont_sel    = $("#contact-selected-container");
	var inner_sel   = $("#contact-selected-list");
	var h_cont_sel  = cont_sel.innerHeight();   //altura del contenedor
	var h_inner_sel = inner_sel.innerHeight();  //altura del contenido

	//Validación de la necesidad de que haya scroll
	if (h_inner_sel < h_cont_sel) {
		 $("#down-contacts-selected").css("display", "none");
	}


/* EVENTOS *******************/

	$('#up-contacts-selected').click(function(){
		cont_sel.stop().scrollTo( '-=100', 500 );
		buttons_check_inup();
	});

	$('#down-contacts-selected').click(function(){
		//alert ("hola");
		cont_sel.stop().scrollTo( '+=100', 500 );
		buttons_check_indown();
	});


/* FUNCIONES ******************/	

	function buttons_check_indown() { 
		var cont    = $("#contact-selected-container");
		var inner   = $("#contact-selected-list");
		var scroll  = 100;                  //cantidad de desplazamiento de la ventana
		var scrolx2 = scroll * 2;           // *2 para ayudar a desaparecer y aparecer scroll

		var p_cont  = cont.position().top;  //posición del contenedor
		var p_inner = inner.position().top; //posición del contenido
		var h_cont  = cont.innerHeight();   //altura del contenedor
		var h_inner = inner.innerHeight();  //altura del contenido

		//aparecer el botón de arriba
		if (p_cont < p_inner) {
	        $("#up-contacts-selected").css("display", "block");
	    }
	    //desaparecer el botón de abajo
	    var p_actual = (p_cont + h_cont - h_inner );

	    if (p_inner < p_actual) {
	        $("#down-contacts-selected").css("display", "none");
	    }
	};

	function buttons_check_inup() { 
		var cont    = $("#contact-selected-container");
		var inner   = $("#contact-selected-list");
		var scroll  = 100;                  //cantidad de desplazamiento de la ventana
		var scrolx2 = scroll * 2;           // *2 para ayudar a desaparecer y aparecer scroll

		var p_cont  = cont.position().top;  //posición del contenedor
		var p_inner = inner.position().top; //posición del contenido
		var h_cont  = cont.innerHeight();   //altura del contenedor
		var h_inner = inner.innerHeight();  //altura del contenido

		//desaparecer el botón de arriba
		if (p_cont < p_inner) {
	        $("#up-contacts-selected").css("display", "none");
	    }
	    //aparecer el botón de abajo
		var p_actual = (p_cont + h_cont - h_inner + scrolx2);
		if (p_inner < p_actual) {
	        $("#down-contacts-selected").css("display", "block");
	    }
	};


});






