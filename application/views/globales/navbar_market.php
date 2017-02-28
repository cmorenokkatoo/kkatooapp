<style type="text/css">
*{
  list-style: none;
  box-sizing: border-box;
  text-decoration: none;
}
ul, menu, dir {
    display: block;
    list-style-type: disc;
    -webkit-margin-before: 0em !important;
    -webkit-margin-after: 0em !important;
    -webkit-margin-start: 0px;
    -webkit-margin-end: 0px;
    -webkit-padding-start: 00px;
}
  .newNav{
      position: fixed;
      bottom: 0;
      width: 100%;
      height: auto;
      background: #303030;
      z-index: 99999 !important;
      text-align: center;
      margin: 0 auto;
      box-shadow: rgba(0,0,0,.3) 1px 1px 1px 1px;
      padding: .6rem 0;

  }
  .newNav ul{
    margin: 0 auto;
    display: flex;
    width: 100%;
    margin-left: 2rem;

  }
.newNav ul li{
  height: auto;
  margin: 0px;
  /*line-height: 40px;*/
  /*flex: auto;*/
}

.newNav ul li a, .button-collapse{
    color: white;
    /*display: block;*/
    transition: all 50ms ease-out;
    /*padding: .4rem .5rem;*/
    border-radius: 3px;
    position: relative;
    padding: .3rem;
}

.newNav ul li a:hover{
  text-decoration: none;
  color: tomato;
  /*background: white;*/
  transition: all 50ms ease-in;
  cursor: pointer;
  width: 100%;
}
.newNav ul li i, .newNav ul li span{
  vertical-align: middle;
}
.newNav ul li span{
  padding: 0px 5px;
}
.material-icons{
      font-size: 1.5rem !important;
}
</style>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<header id="header-mkt">
            <div id="header-content">
            <div id="brand" class="header-element">
                	<?php
                    // $logo = $this->specialapp->create_logo('logo_principal_mv.jpg');
                  ?>
                  <!-- <img id="enlace_logo"  src="<?php echo  $logo->brand_img ?>" alt="Mensajes de Voz"/> -->
                  <h1>Mensajes de voz</h1>
            </div>
                <!-- Opciones de navegación -->
                <?php
                  if($this->session->userdata('logged_in')):
                ?>
              <div id="username">
              <span id="nombre-usuario"><?php echo ("<i id='username-welcome'>Hola</i> <b>" . $username . "</b>"); ?></span>
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
     <li class="item-main-menu"><a href="<?php echo base_url('user/apps'); ?>"><i class="material-icons">check_circle</i> <span>Administrador</span></a></li>
     <li class="item-main-menu"><a href="<?php echo base_url('admin/recents/'); ?>"><i class="material-icons">check_circle</i> <span>Supervisar</span></a></li>
     <li class="item-main-menu"><a target="_blank" href="<?php echo base_url("wizard/newapp/subs"); ?>"><i class="material-icons">build</i> <span>Creador1</span></a></li>
     <li class="item-main-menu"><a target="_blank" href="<?php echo base_url("wizard/newapp/dif"); ?>"><i class="material-icons">build</i> <span>Creador2</span></a></li>
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
<script type="text/javascript">
$('#ham-menu-button').on("click", function(){
  $('.newNav').css("display","none;");
});
</script>



























<!--                                        Wizard-->
<!--
                                        <div id="btn-add-app"   style="position:relative;">
                                             <a href="#" class="link-add-app" title="Crear nueva aplicación">Crear Aplicación <span class="fa fa-chevron-circle-right"></span></a>

                                             <div id="menu-app-type">
                                               <div class="app-type">
                                                <a href="<?php echo base_url("wizard/newapp/subs"); ?>"></a>
                                                <img class ="ico-app-type"src="<?php echo base_url('assets/img/ico-suscripcion.png'); ?>">
                                                 <h6>CREAR APP DE SUSCRIPCIÓN</h6>
                                                 <p>Orientada a creadores que quieren hacer llamadas periódicas a personas que se suscriban a su aplicación únicamente.</p>
                                               </div>
                                               <div class="app-type">
                                                 <a href="<?php echo base_url("wizard/newapp/dif"); ?>"></a>
                                                 <img class ="ico-app-type"src="<?php echo base_url('assets/img/ico-difusion.png'); ?>">
                                                 <h6>CREAR APP DE DIFUSIÓN</h6>
                                                 <p>Orientada a creadores que deseen permitir que otros usuarios usen su aplicación para llamar.</p>
                                               </div>
                                             </div>

                                          </div>
-->
<!--                                          Fin Wizard-->