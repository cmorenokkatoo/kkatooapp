var root = "/"+window.KKATOO_ROOT;
$(document).ready(function(){ 

  $(".btn-recall").on("click",function(){


  if(confirm("Estás a punto de relanzar las llamadas fallidas. ¿Confirmas esta acción?")) 
  {
    location.reload(true);;
     var id_camp = $(this).attr("data-id");
     $.post("/campaign/relaunch_call", { "id_camp": id_camp }, function(data) {
        var resp = JSON.parse(data);
        
        if(resp.cod==1)
        {
          alert("Tu campaña ha sido relanzada con éxito")
        }
        else
        {
          alert("Ocurrió un error al intentar relanzar esta llamada. Por favor intentelo más tarde.")
        }
        
     });
   }
  });

});