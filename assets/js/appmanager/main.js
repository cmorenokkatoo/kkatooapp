// JavaScript Document
var root = "/"+window.KKATOO_ROOT;

// JavaScript Document
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
	PAGINACIÓN PARA LA LIBRERÍA DE CONTENIDOS
**/

var pagination_ul = $('.pagination_wiz ul');
var items = 'tbody.topaginate_wiz tr.content_resume';
var numItemsToShow = 20;
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

/** PAGINACIÓN TABLA DE SUSCRIPCIÓN **/
var pagination_ul_subs = $('.pagination_subs ul');
var items_subs = 'tbody.topaginate_subs tr';
var numItemsToShow_subs = 20;
var numItems_subs = $(items_subs).length;
var numPages_subs = Math.ceil(numItems_subs/numItemsToShow_subs);

$(items_subs).hide();
$(items_subs).slice(0, numItemsToShow_subs).fadeIn();	

function show_elements_subs(page){
	var beginItem = (page -1) * numItemsToShow_subs;	
	$(items_subs).hide();
	$(items_subs).slice(beginItem, beginItem + numItemsToShow_subs).fadeIn(); 
}

function made_pagination_subs(){
	numItems_subs = $(items_subs).length;
	numPages_subs = Math.ceil(numItems_subs/numItemsToShow_subs);
	
	pagination_ul_subs.pagination({
		items: numItems_subs,
		itemsOnPage: numItemsToShow_subs,
		onPageClick: function(pageNumber, event) {
			show_elements_subs(pageNumber);
		}
	});
}

made_pagination_subs();
/** /PAGINACIÓN TABLA DE SUSCRIPCIÓN **/