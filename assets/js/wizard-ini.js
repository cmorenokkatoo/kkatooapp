var elemento_file = '<div><input type="file" name="add-dynamic-audio-num" /></div>';
function enviarwizard(){
	$("#frm-customize-app-data").submit();
}

$(".cerrar a").on("click",function(){
	$(".mensajes").fadeOut();
});

setTimeout(cerraventana, 10000);
function cerraventana(){
	$(".mensajes").fadeOut();
}


function redondear(cantidad, decimales) {
	var cantidad = parseFloat(cantidad);
	var decimales = parseFloat(decimales);
	decimales = (!decimales ? 2 : decimales);
	return Math.round(cantidad * Math.pow(10, decimales)) / Math.pow(10, decimales);0
} 

$("#add-dinamic-field").click(function(){
	//$("#add-dynamic-field .add-element").
	var elemento = $("#add-dynamic-field .add-element").html();
	if($("#render-dynamic-fields").children().length < 10){
		$("#render-dynamic-fields").append(elemento);
	}
	
});

$("#add-dinamic-audios").click(function(){
	if($("#render-dynamic-audios").children().length < 5){
		/*elemento.attr("name","add-dynamic-audio-"+$("#render-dynamic-audios").children().length);
		$("#render-dynamic-audios").append(elemento);*/
		var aux = replaceAll(elemento_file,"num",$("#render-dynamic-audios").children().length+1);
		$("#render-dynamic-audios").append(aux);
	}
});

$("#additional-percent").change(function(){
	var valor = $("#additional-percent").val();
	var total = (valor); 
	var total = redondear(total,0);
	$("#final-prize").val(total);
});

function replaceAll( text, busca, reemplaza ){
  while (text.toString().indexOf(busca) != -1)
      text = text.toString().replace(busca,reemplaza);
  return text;
}
