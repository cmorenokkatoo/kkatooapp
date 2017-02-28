<?php
	$this->load->view("globales/head_app_manager");
	$this->load->view('globales/mensajes');
?>

<link href="<?php echo base_url('assets/css/appmanager/OnOFFswitch.css')?>" rel="stylesheet">
<style type="text/css">
	.green{
		color:green;
	}
	.red{
		color:#C00;
	}
	.table{font-size:13px;}
	
	#someidentifier {
    position: fixed;
    z-index: 100; 
    bottom: 0; 
    right: 0;
	float: right;
    width: 100%;
	       right: 100px;
          float: right;
}
.rcorners2 {
    border-radius: 15px 50px 30px;
    border: 2px solid #73AD21;
    padding: 20px;  
    width: 240px;
    height: 100%; 
}
</style>

      <!-- HEADER DE LA PAGINA -->
      <div id="brand-header" class="navbar navbar-fixed-top">
         <div class="navbar-inner">
            <!-- AVISO DE CREDITOS DISPONIBLES -->
            <div id="navbar-container" class="container">
            	<div class="row">
                  <!-- Para logo -->
                  <div class="span6">
					<?php
						$logo = $this->specialapp->create_logo('logo-main-header.png');
					?>
                	<a class="brand" href="<?php echo $logo->brand_url; ?>">
                		<img src="<?php echo  $logo->brand_img ?>" alt="<?php echo $logo->brand_title ?>" style="height:60px; margin-top: 2px;" />
                	</a>
                  </div>

            <?php 
$this->load->view('utils/user_dropdown') 
?>


               </div> <!-- .row -->
            </div> <!-- #navbar-container -->
         </div>
      </div> <!-- #brand-header -->

<!--********************************************************************************* -->
      <!-- CONTAINER DE LA APLICACION -->
      <div id="app-container" class="container" style="width:1000px!important">

         <!-- HEADER DE LA APLICACION -->
         <div class="row">
            <div class="span12" id="app-header" style="width:1000px">
               <div class="row">
                  <div class="span6" id="title-and-links">
                     <h2>Gestor de aplicaciones</h2>
                  </div> <!-- #title-and-links -->

                  <div class="span6" id="available-kredits">
                     <div id="content">
                        <h3></h3>
                        <div id="txt">
                           <span id="kredits">CRÉDITOS</span>
                           <span id="available">DISPONIBLES</span>
                        </div>
                     </div>
                  </div> <!-- #search -->

               </div> <!-- .row -->
            </div> <!-- #app-header -->
         </div> <!-- .row -->





         <!-- CUERPO DE LA APLICACION -->
         <div class="row" id="content">
            <!-- AREA DE OPTIONS -->
            <div class="span3" id="options" style="width:70px">
               <div id="options-list">

               </div> <!-- #options-list -->

            </div> <!-- #options -->

            <!-- AREA DE LISTADO DE ITEMES -->
            <div class="span10" id="kredit-mgr-items">
               <!-- CONTENIDO DE LA SECCION 1 *RECARGA*  -->

               <div id="resume-contents" style="width:880px">
                  <div id="resume-title">
				  <table width=100% >
					<tr>
                      <td><h4>Usuarios</h4></td>
					  <td align="right">
					  <table><tr><td>
					  <h4>
					  <div align="center">Editar Creditos
						<div class="onoffswitch" align="center">
									<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch">
									<label class="onoffswitch-label" for="myonoffswitch">
										<span class="onoffswitch-inner"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</div>
						</div>
								</h4>
								</td></tr></table>
						</td>
						</tr>
					</table>
                     <!-- a href="< ?php echo base_url('admin/users/'); ?>" class="btn btn-primary btn-small">Ver todos</a-->
                  </div>
                  <br  />
                  <div id="resume-items">
                     <table class="table table-striped">
                        <tr>
                           <th style="min-width:10px">Email</th>
                           <th>Nombre</th>
                           <th>Id o NIT</th>
													 <th>Dirección</th>
													 <th>Teléfono</th>
                           <th>Crédito</th>
						   <th id="modify_title" style="display: none;">Modificar Crédito<br>
						   
						   </th>
                        </tr>
                        <?php if(!empty($users)){
							
					
													foreach($users as $user){?>
                        	<tr class="<?php echo ($user->verified==0)?"red":"green"; ?>">
                               <td>
                                  <a href="<?php echo base_url("admin/users/".$user->id) ?>"><?php echo $user->email; ?></a>
                               </td>
                               <td>
                                  <?php echo $user->fullname; ?>
                               </td>
                               <td>
                                  <?php echo $user->nit_or_id; ?>
                               </td>
                               <td>
																 <?php echo $user->address; ?>
                               </td>
															 <td>
																 <?php echo $user->phone; ?>
                               </td>
															 <td >
																<div id='user_value_<?php echo $user->id; ?>'> <?php echo $user->credits; ?>&nbsp;<?php
																if(isset($users_credtis_logs[$user->id])) {
																	?><img  class="langflag " id="<?=$user->id?>" src="<?php echo base_url("assets/img/flags/"); ?>/grade_modified_3.gif"/>
																	<?php
																}
																?></div><div class="rcorners2" id="select-lang_<?=$user->id?>" style="display:none;">
																<table><tr><th>Admin</th><th>Antes</th><th>Asigno</th></tr><?php
																foreach($users_credtis_logs[$user->id] as $row_log){
																	
																	echo "<tr><th>".$row_log['admin']."</th>";
																	echo "    <th>".$row_log['credits_before']."</th>";
																	echo "    <th>".$row_log['credits_after']."</th></tr>";
																	
																}
																?></table></div>
                               </td>
							     </td>
															 <td >
																 <input style="max-width:70px;" pattern="(^\\$?(([1-9](\\d*|\\d{0,2}(,\\d{3})*))|0)(\\.\\d{1,2})?$)" class="form-control" value="<?php echo $user->credits; ?>" onchange="updateCredit(<?php echo $user->id; ?>,this.value,<?php echo $admin_id; ?>)" type="Hidden"></input>
                               </td>
                            </tr>
                        <?php
							}
						}
						?>
                     </table>
                  </div>
               </div> <!-- #resume-contents -->

            </div> <!-- #items-data LISTADO DE ITEMES -->

         </div> <!-- .row  #content -->
				 <div class="row">
				 	<div style="width:100%;height:100px;">
				 	</div>
				 </div> <!-- .row -->
      </div> <!-- #app-container -->
<div id="someidentifier">
algo aca
</div>

      <!-- Le javascript -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
      <script src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
      <script src="<?php echo base_url("assets/js/jquery.jeditable.mini.js"); ?>"></script>
      <script src="<?php echo base_url("assets/js/mgrs-ini.js"); ?>"></script>
<script>

$( ".langflag " ).on('click', function() { 

   $( "#select-lang_"+this.id ).slideToggle( 400, function() {
	   console.log("despliga opciones");
       // Animation complete.
    });
});

//keep it open if mouse is within #langflag, hide if mouse leaves both

/*
$( "#select-lang_"+this.id+", .langflag" ).on('mouseleave', function() {
   if ($(this).attr('id') != 'langflag') {
      $( "#select-lang_"+this.id ).slideToggle( 400, function() {
         // Animation complete.
      });
   }
});*/



//keep it open if mouse is within #langflag, hide if mouse leaves both
$( ".select-lang, #langflag" ).on('mouseleave', function() {
   if ($(this).attr('id') != 'langflag') {
      $( ".select-lang" ).slideToggle( 400, function() {
         // Animation complete.
      });
   }
});

function updateCredit(id,credits,admin_id){
	$.get(window.KKATOO_ROOT+"/../admin/update_user_credits/"+id+"/"+credits+"/"+admin_id,function(){
		console.log("updated by "+admin_id);
		
		
		  var RecordInstructions = document.getElementById('user_value_'+id);
		RecordInstructions.innerHTML=credits;
		
	});
}

function DeployUpdateCreditFields(option){
	var v_type;
	if(option=="display"){
		v_type="text"
		$("#modify_title").show(); 
		
		$logs=$( ".rcorners2" );
		$logs.hide();
	}else{
		$("#modify_title").hide(); 
		v_type="hidden";
		
		$logs=$( ".rcorners2" );
		$logs.hide();
	}
	
	$('.form-control').each(function(index,element){
		
			element.type=v_type;

		});

	


	
	
	
}
document.querySelector('#myonoffswitch').onclick = function() {
	       
			if(this.checked) {
				DeployUpdateCreditFields('display');
			}else{
				DeployUpdateCreditFields('hide');
				window.location.href="<?php echo base_url('/admin/users/'); ?>"
			}
		
	    };
		

</script>
   </body>

</html>
