
var root = "/"+window.KKATOO_ROOT;
$(document).ready(function(){ 

  $(".borrarapp").on("click",function(){

  if(confirm("No podrá recuperar esta aplicación una vez se elimine. ¿Realmente desea eliminarla?"))
  {
     var appID = $(this).attr("data-id");
     $.post("/apps_functions/ajaxDeleteApp", { "appId": appID }, function(data) {
        var resp = JSON.parse(data);
        if(resp.cod==1)
        {
          $("#appListed_" + appID).remove();
        }
        else
        {
          alert("Ocurrió un error al intentar borrar la aplicación. Por favor intentelo más tarde.")
        }
        
     });
   }
  });

});