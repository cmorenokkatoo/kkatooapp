<?php
   $this->load->view("globales/head_payment");
   $this->load->view('globales/mensajes');
?>
<style>
*{
  box-sizing: border-box;
  text-decoration: none;
  list-style: none;
}
  body{
    background: #2E80AB !important;
  }
  #content{
    width: 80% !important;
    background: white;
    text-align: center;
    margin: 0 auto;
  }
  #kredit-mgr-items{
    width: 100%;
    padding: 2rem;
  }
  tr{
    text-align: center !important;
    height: 3.5rem;
    border-bottom: 1px solid #D0D0D0;
  }
  tr:nth-child(even){
    background: #f9f9f9;
  }
  tr a{
    color: black;
    text-decoration: none;
  }
  tr:hover{
    background: #ECF8E0;
    color: green;
  }
  tr a:hover{
    color: green;
  }
  #name-camp{
    text-align: left !important;
  }
  tr a i, th i, tr a span{
    vertical-align: middle;
  }
  th{
    font-weight: 600;
  }
.borrarcamp{
  background: red;
  color: white;
  float: right;
  padding: .5rem;
  margin: 5px;
}
.pagination li.active{
  background-color: lightgray !important;
  color: #303030 !important;
  padding: 1rem !important;
  vertical-align: middle;
  height: auto;
  margin: 5px;
}
.light-theme, .simple-pagination{
  height: 50px !important;
}
#name-camp{
  text-transform: uppercase;
}
</style>
<header id="header-steps">
            <div id="header-content">
            <div id="brand" class="header-element">
                  <?php 
                    // $logo = $this->specialapp->create_logo('logo_principal_mv.jpg');
                   ?>
                  <!-- <a class="brand" href="<?php //echo $logo->brand_url; ?>">
                    <img src="<?php //echo  $logo->brand_img ?>" alt="Mensajes de Voz"/>
                  </a> -->
                  <h1>Mensajes de Voz</h1>
            </div>
                <!-- Opciones de navegación -->
                <?php
                 if($this->session->userdata('logged_in')):

                    $credits = $this->marketplace_model->get_user_credits();
                    $username = $this->marketplace_model->get_user_name();
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
<div id="app-container" class="container">
   <div class="row" id="content">
      <div class="span3" id="options">
         <div id="options-list">
            <div class="group-contact-item">
               <div id="resume-ico"><a  id="resume-link" class="selected"></a></div>
            </div>
         </div> 
      </div> 
      <div class="span10" id="kredit-mgr-items">
         <div id="resume-contents">
            <div id="resume-items">
               <table class="table table-striped">
                  <tr>
                     <th class="camp-name">Nombre Campaña</th>
                     <th><i class="material-icons">insert_invitation</i>Fecha Programada</th>
                     <th><i class="material-icons">access_time</i> Hora Programada</th>
                     <!-- <th>Envíos Programados</th> -->
                     <!-- <th>Envíos Exitosos</th> -->
                     <th><i class="material-icons group-actions">delete_forever</i> Eliminar campaña</th>
                     <!-- <th><i class="material-icons">cached</i>Estado Campaña</th> -->

                  </tr>
                  <?php if(!empty($campaign)){ 
                     foreach($campaign as $camp){
                        ?>
                        <tr id="campListed_<?php echo $camp["id"]; ?>" class="style-tr content-resume" style="text-align: center;">
                           <td class="camp-name" id="name-camp">
                            <a title="<?php echo $camp["name"]; ?>" href="<?php echo base_url('campaign/detail_campaign/'.$camp["id"]); ?>">
                             <i class="material-icons">label_outline</i> <span><?php if($camp["name"]){ echo $camp["name"]; }else{ echo "Sin Nombre"; } ?></span>
                          </a>
                       </td>
                       <td><?php echo $camp["fecha"]; ?></td>
                      <td><?php echo $camp["hora"].':'.$camp["minuto"]; ?></td>
                       <td><input class="checkbox" id="<?php echo $camp["id"]; ?>" type="checkbox" class="borrarcamp"></td>
                    </td>
                    <?php 
                 }
              }
              ?>
           </tr>
        </table>
     </div>
     <a class="borrarcamp" data-action="free"><i class="material-icons group-actions">delete_forever</i> Eliminar</a>
  </div>
  <div class="pagination_wiz pagination" style="text-align: center;">
   <ul></ul>
</div>
</div>
</div> 
</div>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
<script src="<?php echo base_url("assets/js/jquery.jeditable.mini.js"); ?>"></script>
<script src="<?php echo base_url("assets/js/mgrs-ini.js"); ?>"></script>   
<script src="<?php echo base_url('assets/js/wizard/jquery.simplePagination.js'); ?>"></script>
<!--  -->
      
      <script type="text/javascript">
         // Agregado libreria de contenidos
      /**
         PAGINACIÓN PARA LA LIBRERÍA DE CONTENIDOS
      **/
      
      var pagination_ul = $('.pagination_wiz ul');
      var items = 'div#resume-items tr.content-resume';
      var numItemsToShow = 25;
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
      
      //VARIABLES GLOBALES QUE SERÁN UTILIZADAS EN EL JS
      var $fecha       = '<?php echo $camp["fecha"]; ?>';
      var $hora   = '<?php echo $camp["hora"].':'.$camp["minuto"]; ?>';
      var $borrcarcamp  = '<input class="checkbox" id="<?php echo $camp["id"]; ?>" type="checkbox" class="borrarcamp">';
      
      </script>
   </body> 

</html>