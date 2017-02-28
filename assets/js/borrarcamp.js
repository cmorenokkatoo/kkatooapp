
// var root = "/"+window.KKATOO_ROOT;
// var root = "kka.to";
$(document).ready(function(){ 

  $(".borrarcamp").on("click",function(){

    if(confirm("¿Realmente desea eliminar la campaña? Hacer esto eliminará esta campaña de nuestro sistema y no podrá recuperarse."))
    {
      deleteCamp();
    }
  });

});

/* Function: deleteCamp
 * Guarda los ids de las campañas que se borrarán y las envía al controlador
 *
 * Parameter:
 *  
 * Return:
 *  
 */

function deleteCamp()
{ 
  //Creo el array que tendrá los ids de las campañas
  var campaigns = [];

  //verifico que no haya un procso de borrado actualmente activo. 
  //esto previene errores al darle click varias veces seguidas al botón "eliminar"
  if($(".borrarcamp").attr("data-action") == "free")
  {
    $(".borrarcamp").text("Eliminando...");
    $(".borrarcamp").attr("data-action", "inuse");

    //Obtengo los ids de las campañas seleccionadas
    $(".table input:checkbox:checked").each(function()
    {
      //verifico que haya un id que guardar
      if(this.id != "")
          campaigns.push(this.id);
    });


    //creo array json (string) y lo envío al controller
    var campJson = JSON.stringify(campaigns);

    //verifico que por lo menos se eliminará una campaña
    if(campaigns.length > 0)
    {
      $.post('/camp_functions/ajaxDeleteCamp', {'id_campaign' : campJson}, function(data)
        {
          //console.log(data);
          try
          {
            var resp = JSON.parse(data);
            if(resp.cod == 1)
            {
              //Restablezco el botón "Eliminar"
              $(".borrarcamp").text("Eliminar Campañas");
              $(".borrarcamp").attr("data-action", "free");

              //Borro el html de las campañas seleccionadas
              $.each(campaigns, function(key, value)
              {
                $("#campListed_"  + value).remove();
              });
      
            }
            else
            {
              alert("Ocurrió un error al intentar eliminar las campañas seleccionadas. Por favor inténtelo nuevamente.");
            }

          }catch(err)//Llegó un html.
          {
            $(".borrarcamp").text("Eliminar Campañas");
            $(".borrarcamp").attr("data-action", "free");
              alert("Ocurrió un error al intentar eliminar las campañas seleccionadas. Por favor inténtelo nuevamente.");
          }     
          
        });

      }
      else
      {
        //Restablezco el botón "Eliminar"
        $(".borrarcamp").text("Eliminar Campañas");
        $(".borrarcamp").attr("data-action", "free");
        alert("Es necesario que seleccione como mínimo una campaña para eliminar. Por favor inténtelo nuevamente.");
      }   
  }
  event.preventDefault();
}
