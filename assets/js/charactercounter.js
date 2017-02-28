init_contadorTa("taComentario","contadorTaComentario", 160);

function init_contadorTa(idtextarea, idcontador,max)
{
    $("#"+idtextarea).keyup(function()
            {
                updateContadorTa(idtextarea, idcontador,max);
            });
    
    $("#"+idtextarea).change(function()
    {
            updateContadorTa(idtextarea, idcontador,max);
    });
    
}

function updateContadorTa(idtextarea, idcontador,max)
{
    var contador = $("#"+idcontador);
    var ta =     $("#"+idtextarea);
    contador.html("para sms: 0/"+max);
    
    contador.html(ta.val().length+"/"+max);
    if(parseInt(ta.val().length)>max-1)
    {
        $('#contadorTaComentario').css('color', 'red')
    //     ta.val(ta.val().substring(0,max-1));
    //     contador.html(max+"/"+max);
    }else{
        $('#contadorTaComentario').css('color', 'green')
    }

}
