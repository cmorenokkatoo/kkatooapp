<?php
	
	$this->load->view("globales/head_contacts");
	$this->load->view('globales/mensajes'); 
	$url_completa = "";
	if($this->uri->segment(3)){
		$url_completa = "/".$this->uri->segment(3);
		
	}

?>
      <link rel="stylesheet" href="<?php echo base_url('assets/css/pace.css')?>">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
      <script type="text/javascript" src="<?php echo base_url('assets/js/pace.min.js')?>"></script>


<style>
.btn{
  height: auto !important;
}
#confirmCSV{
  background: green !important;
}
#confirmCSV:hover{
  background: darkgreen !important;
}
  #header-interno-navbar{
    display: block;
    position: relative;
    box-sizing: border-box;
  }
  #header-interno-navbar div{
    
    position: relative;
    
    display: inline-table;
    box-sizing: border-box;
    vertical-align: middle;
  }
  #brand-container{
    left: 10px;
  }

  #app-header{
    background: white !important;
  }
  #title-and-links{
    margin-left: 2rem !important;
  }
  #items-data{
    width: 100% !important;
    background: white !important;
    text-align: center !important;
    margin: 0 auto !important;
    padding: 2rem !important;
  }
  .span11{
    margin: 0 auto !important;
    width: 90% !important;
    margin-left: 5% !important;
  }
  #cajonflotantebotones{
    position: absolute !important;
    z-index: 9999 !important;
    width: 100%;
    height: 200px !important;
    top: 0px;
    left: 0px;
  }
</style>
     <header id="header-steps">
            <div id="header-content">
            <div id="brand" class="header-element">
                  <?php 
                    // $logo = $this->specialapp->create_logo('logo_principal_mv.jpg');
                   ?>
                  <!-- <a class="brand" href="<?php echo $logo->brand_url; ?>">
                    <img src="<?php echo  $logo->brand_img ?>" alt="Mensajes de Voz"/>
                  </a> -->
                  <h1>Mensajes de Voz</h1>
            </div>
                <!-- Opciones de navegación -->
                <?php
                  if($this->session->userdata('logged_in')):
                ?>
              <div id="username">
              <span id="nombre-usuario"><?php echo ("<b>" . $username . "</b>"); ?></span>
                <i class="material-icons" id="ico-username">settings</i>

              </div>
                <div id="saldo">
                    <i class="material-icons" id="ico-saldo">monetization_on</i>
                    <span id="valor-saldo"><?php echo  number_format($credits,0,",",".");?></span>
                    <span id="texto-saldo">créditos</span>
                </div>
                <?php 
                  endif;
                 ?>
      </div><!-- /header-content -->
</header>
<?php if($this->session->userdata('logged_in')){ ?>
<div class="newNav" id="newNav">
  <ul>
    <li class="item-main-menu"><a href="<?php echo base_url('campaign'); ?>"><i class="material-icons">folder_open</i><span>Campañas</span></a></li>
    <li class="item-main-menu"><a href="<?php echo base_url('contacts/contact_manager'); ?>"><i class="material-icons">account_circle</i><span>Contactos</span></a></li>
   <?php if(!$this->permissions->get('deny_marketplace')):?>
    <li class="item-main-menu"><a href="<?php echo base_url('marketplace'); ?>"><i class="material-icons" id="material-module">view_module</i><span>Aplicaciones</span></a></li>
  <?php endif; ?>
  <li class="item-main-menu"><a href="<?php echo base_url('payment'); ?>"><i class="material-icons">monetization_on</i> <span>Recargar Saldo</span></a></li>
  <?php if($this->session->userdata("user_id")==KKATOO_USER):?>
     <li class="item-main-menu"><a href="<?php echo base_url('user/apps'); ?>"><i class="material-icons">check_circle</i> <span>Admin App</span></a></li>
     <li class="item-main-menu"><a href="<?php echo base_url('admin/recents/'); ?>"><i class="material-icons">check_circle</i> <span>Supe Apps</span></a></li>
     <li class="item-main-menu"><a target="_blank" href="<?php echo base_url("wizard/newapp/subs"); ?>"><i class="material-icons">check_circle</i> <span>App Sus</span></a></li>
     <li class="item-main-menu"><a target="_blank" href="<?php echo base_url("wizard/newapp/dif"); ?>"><i class="material-icons">check_circle</i> <span>App Dif</span></a></li>
    <?php endif; ?>
    <li><a href="<?php echo base_url("login/logout"); ?>"><i class="material-icons">power_settings_new</i> <span>Salir</span></a></li>
    <?php }else{
          ?>
    <li><a href="<?php echo base_url("login/login"); ?>?rtrn=<?php echo str_replace("/".KKATOO_ROOT."/","",$_SERVER['REQUEST_URI']); ?>"><?php echo $this->lang->line('login'); ?></a></li>
    <li><a href="<?php echo base_url("login/register"); ?>"><?php echo $this->lang->line('register'); ?></a></li>
    <?php
      } 
    ?>                                
  </ul>
</div>


<!--********************************************************************************* -->
      <!-- CONTAINER DE LA APLICACION -->
      <div id="app-container" class="container">
         
         <!-- HEADER DE LA APLICACION -->
         <div class="row">
            <div class="span12" id="app-header">
               <div class="row">
                  <div class="span6" id="title-and-links">
                     <h2>Contactos a importar</h2>
                     
                     
                  </div> <!-- #title-and-links -->

                  

               </div> <!-- .row -->
            </div> <!-- #app-header -->
         </div> <!-- .row -->




         

         <!-- CUERPO DE LA APLICACION -->
         <div class="row" id="content">
            

            <!-- AREA DE LISTADO DE ITEMES -->
            <!-- FORMULARIO PARA ELIMINAR EN MASA -->
            

            <div class="span12" id="items-data">
               <div id="items-title">
                  <h3>Vista previa primeros registros (Se encontraron en total <?php echo count($data); ?> registros) </h3>
                  
               </div>
              <br />
              <div style="clear:both;"></div>
              <div class="span11">
                  <table class="table table-bordered table-hover">
                  <tr>
                  	<th>Nombre</th>
                  	<th>Telefono</th>
                  	<?php
                     		//if($ref_app):
                               if(!empty($fields)):
                                 	foreach($fields as $field):
                                 		echo "<th>".$field->name."</th>";
                                 	endforeach;
                             	endif;
                           // endif;
                         ?>
                  </tr>
                  <?php foreach($data as $csv_contact): ?>
                  	<tr>
                  		<td><?php echo utf8_encode($csv_contact["nombre"]); ?></td>
                  		<td>(<?php echo $csv_contact["indipais"] ?>) <?php echo $csv_contact["telefono"]; ?></td>
                  		<?php
                  		 if(!empty($fields)):
	                     	foreach($fields as $field):
								$fieldSaved = "";
								if(!empty($csv_contact[$field->name_fields])) $fieldSaved = $csv_contact[$field->name_fields]; 
	                     		
	                     		
	                     		if($fieldSaved == ""): 
	                     			$fieldSaved = '&nbsp';
	                     		endif;
	                     		
	                     		echo "<td>".utf8_encode($fieldSaved)."</td>";
	                     	endforeach;	
	                 	  endif;
	                 	  ?>
                  	</tr>
                  <?php endforeach; ?>
				</table>

				<?php
                      		
	         	         $attributes = array('method'=>'post');
	         	         echo form_open('contacts/import_csv_contact', $attributes);
	                        ?>
					<?php if(!empty($id_wapp)): ?>
	                	<input type="hidden" name="id_wapp" value="<?php echo $id_wapp; ?>" />
	                <?php endif; ?>
	                <input type="hidden" name="id_group" value="<?php if($id_group){ echo $id_group; } ?>" />
	                <input type="hidden" name="name_file" value="<?php echo $name_file; ?>" />
	                
    	                        <input type="submit" id="confirmCSV" value="CONFIRMAR IMPORTACIÓN" class ="btn">
                                 <a href="<?php echo base_url('contacts/contact_manager'); ?>" class="btn">Cancelar Importación</a>
				</form>
				 <br /><br />
              </div>

            </div> <!-- #items-data LISTADO DE ITEMES -->

         </div> <!-- .row  #content -->

      </div> <!-- #app-container -->


      <!-- Le javascript -->
      <script src="<?php echo base_url('assets/js/bootstrap.js')?>"></script>
      <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.jeditable.mini.js')?>"></script>
      <!-- <script type="text/javascript" src="<?php //echo base_url('assets/js/jquery.uploadify.js')?>"></script> -->
      <script src="<?php echo base_url('assets/js/mgrs-ini.js')?>"></script>



      <script>
      	var arr_campos_dinamicos = new Array();
      	
      	
      	<?php
			foreach($fields as $field):
        ?>
        	arr_campos_dinamicos.push(Array("<?php echo $field->name_fields; ?>","<?php echo $field->name ; ?>"));
        <?php endforeach; ?>
        
        
      	
     
      </script>
   </body> 

</html>