var root = "/"+window.KKATOO_ROOT;

$(document).ready(function($){

	$('#steps-tabs a:first').tab('show');

	$("#contacts-filter-ico").click(function(event){
		$("#frm-filtro").slideToggle(500);
	});

	$("#contacts-selected-badge").click(function(event){
		$("#selected-data").slideToggle(500);
		$("#contacts-data").slideToggle(500);
	});
	
	$("#selectall").on("click",function(){
		if($(this).is(':checked')){
			$(".contactsch").attr('checked',true);
		}else{
			$(".contactsch").attr('checked', false);
		}
		
	});
	
	  
	  
	  function get_contacts(){
			$.post(root+"/apps/get_contacts",{id_campaign:id_campaign_var},function(data){
						var obj_int = jQuery.parseJSON(data);
						$(".interna2-int").html("");
						$.each(obj_int, function(i, item) {
						});
						
					});
		
		}
		
		$(".contactsch").on("change",function(){
			var actual = $(this).parent().parent();
			$.post(root+"/apps/add_contact_campaign", { id_contact: $(this).val(),id_campaign:id_campaign_var },
				   function(data) {
				    var obj = jQuery.parseJSON(data);
				    if(obj.cod == 1){
				    	actual.remove();
				    	alert(obj.messa);
			        }else{
			        	alert(obj.messa);
				        
			        }				 	
			});
		});
		
		
		$(".contactsch_remove").on("change",function(){
			var actual = $(this).parent().parent();
			$.post(root+"/apps/delete_contact_campaign", { id_contact: $(this).val(),id_campaign:id_campaign_var },
				   function(data) {
				    var obj = jQuery.parseJSON(data);
				    if(obj.cod == 1){
				    	actual.remove();
				    	alert(obj.messa);
			        }else{
			        	alert(obj.messa);
				        
			        }				 	
			});
			if(typeof redraw_pagination == 'function') redraw_pagination();
			if(typeof redraw_paginationS == 'function') redraw_paginationS();
		});
		
		
		
});
