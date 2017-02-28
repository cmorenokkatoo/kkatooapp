 <?php if($this->session->userdata('logged_in')): ?>
      <style>
      #header-mkt{
        width: auto;
        box-sizing: border-box;
        margin: 0px;
        float: right;
      }
        #recargar-saldo{
          margin: 5px;
          padding: .5em 1em;
          box-sizing: border-box;
          background: #fff;
          border-radius: 3px;
          display: inline-block;
          border: 1px solid #fcba41;
          cursor: pointer;
          vertical-align: middle;
          top: 10px;
          right: 100px;
          float: left;
        }
        /* para comenzar a hacer llamadas y sms a través de las aplicaciones debes tener saldo activo en tu cuenta. recarga haciendo click aquí.*/
        #recargar-saldo a{
          color: #000;
        }
        </style>
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <div id="header-mkt">
    <div class="usr-menu">
     <!-- MENU DEL USUARIO -->
      <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
          <img src="<?php echo base_url("assets/img/users/ico-usr-generic-small.png"); ?>" alt="<?php echo $this->session->userdata('fullname'); ?>" class="user-photo"> 
          <span class="user-name"><?php echo $this->session->userdata('fullname'); ?></span> 
          <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" style="visibility: visible;">
        	<?php 
  			if(empty($prtrn)){
  				$prtrn = '';
  				if($this->specialapp->get('special')) $prtrn = 'apps/'.$this->specialapp->get('uri');
  			}
  		?>
          <li class="item-main-menu"><a href="<?php echo base_url('payment?prtrn='.$prtrn); ?>"><i class="fa fa-angle-right"></i> Recargar Saldo</a></li>
          <li class="item-main-menu"><a href="<?php echo base_url('campaign'); ?>"><i class="fa fa-angle-right"></i> Campañas</a></li>
          <li class="item-main-menu"><a href="<?php echo base_url('contacts/contact_manager'); ?>"><i class="fa fa-angle-right"></i> Contactos</a></li>
  		<?php if(!$this->permissions->get('deny_marketplace')):?>
          	<li class="item-main-menu"><a href="<?php echo base_url('marketplace'); ?>"><i class="fa fa-angle-right"></i> Marketplace</a></li>
          <?php endif; ?>
          <?php if($this->session->userdata("user_id")==KKATOO_USER):?>
              <li class="item-main-menu"><a href="<?php echo base_url('user/apps'); ?>"><i class="fa fa-angle-right"></i> Aplicaciones</a></li>
              <li class="item-main-menu"><a href="<?php echo base_url('admin/recents/'); ?>"><i class="fa fa-angle-right"></i> Nuevas Aplicaciones</a></li>
          <?php endif; ?>
          <li class="divider"></li>
          <li><a href="<?php echo base_url("login/logout"); ?>"><i class="fa fa-sign-out"></i> Cerrar Sesión</a></li>
        </ul>
      </div>      
    </div> <!-- .span3 -->
    <!-- <div id="recargar-saldo">
      <a href="<?php echo base_url('payment?prtrn='.$prtrn); ?>">Recargar Saldo</a>
      </div> -->
    </div>
  <?php else: ?>
  <div class="opciones-add nologged">
    <ul class="dd-menu filter">
      <li><a href="<?php echo base_url("login/login"); ?>?rtrn=<?php echo str_replace("/".KKATOO_ROOT."/","",$_SERVER['REQUEST_URI']); ?>"><?php echo $this->lang->line('login'); ?></a></li>
      <li><a href="<?php echo base_url("login/register"); ?>"><?php echo $this->lang->line('register'); ?></a></li>
    </ul>
  </div>
<?php endif; ?>