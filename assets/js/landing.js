// JavaScript Document
/*RUTA GLOBAL */
var root = "/"+window.KKATOO_ROOT;
$(".payment-options").hide();


	/****************************************************
	* CONVIRTIENDO EL SELECT EN UNO CON BANDERAS (Pag1)
	****************************************************/



	/****************************************************
	* CAMBIA EL DIV SEGUN EL METODO DE PAGO ESCOGIDO (Pag2)
	****************************************************/
	$('#cbo-payment-pp').change(function(){
		cambiarMetodoPago($(this).val());
	});



	/****************************************************
	* CAMBIO DE PAIS EN LOS PAGOS (Pag2)
	****************************************************/
	
	//get_city
	//id_country
	$('#cbo_country_pp').change(function(){
		cambiarCiudad($(this).val());
		//alert ("hola " + $(this).val());
	});



/****************************************************
* COLECCION DE FUNCIONES UTILES EN LA PAGINA
****************************************************/

/***FUNCION PARA CAMBIAR CIUDAD EN EL COMBO BOX SEGUN PAIS *****/
function cambiarCiudad(id_p){
	$.post(root+"/landing/get_city",{id_pais:id_p},function(data){
		var obj_int = jQuery.parseJSON(data);
		var opciones = "";
		if(obj_int){
			$.each(obj_int, function(i, item) {	
				//console.log(item);
				opciones += '<option value="'+item.id+'">'+item.name+'</option>';
			});

		}	
		$("#cbo_city_pp").html(opciones);
	});
}

/***FUNCION PARA CAMBIAR CIUDAD EN EL COMBO BOX SEGUN PAIS *****/
function cambiarCiudadEditar(id_p, seleccionado){
	$.post(root+"/landing/get_city",{id_pais:id_p},function(data){
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
		$(".cbo_city_pp").html(opciones);
	});
}

/***FUNCION PARA CAMBIAR EL DIV DE LAS OPCIONES DE PAGO SEGUN LA SELECCION DEL COMBO *****/
function cambiarMetodoPago(metodo){
	$(".payment-options").hide();
	$("."+ metodo + "_options").show();
	if(metodo == "pin"){
		$('.form-row-dyn').hide();
	}else if(metodo == "0"){
		$('.form-row-dyn').hide();
	}else{
		$('.form-row-dyn').show();
	}
	if(metodo=='paypal' || metodo=='pagosonline'){
		$('select[name="cbo_packages_pp"]').focus();
		if(jQuery.isNumeric($('select[name="cbo_packages_pp"]').val())){ get_commission(); }
	}
}


// FUNCIÓN PARA ESCONDER EL MENSAJE

$(".cerrar a").on("click",function(){
	cerraventana();
});

setTimeout(cerraventana, 10000);
function cerraventana(){
	$(".mensajes").fadeOut();
}

// FUNCION PARA OPTENER LA COMISIÓN
$('select[name="cbo_packages_pp"]').on('change', function(){
	this_val = $('select[name="cbo_payment_pp"]').val();
	if((this_val == 'paypal' || this_val == 'pagosonline') && $.isNumeric($(this).val()))
		get_commission();
});

function get_commission(){
	$.post(root+'/landing/create_price_by_package_suscription', 
	{
		id_package:$('select[name="cbo_packages_pp"]').val(), 
		id_app: $('input[name="id_wapp"]').val(), 
		id_contact: $('input[name="id_contact"]').val(), 
		'payment': $('select[name="cbo_payment_pp"]').val()
	}, 
	function (data){
		data = $.parseJSON(data);
		$("#apagar").empty();
		if(data.cod == 1){
			$("#apagar").html("USD $"+data["the_pay"]+ " + Comisión de pago "+$('select[name="cbo_payment_pp"]').val()+": $"+data["commission"] );
		}
	});
}
