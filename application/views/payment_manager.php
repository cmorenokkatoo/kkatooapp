<?php
  $this->load->view("globales/head_payment");
  $this->load->view('globales/mensajes');
?>
<style>
*{
  box-sizing: border-box;
  text-decoration: none;
  list-style: none;
  font-family:Helvetica Neue,Helvetica,Arial,sans-serif;
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
#subir-contactos{
                            position: absolute;
                            top: 0px;
                            right: 0px;
                            visibility: visible;
                            display: none;
                            background: #CDF3BA;
                            border-radius: 4px;
                            padding: .5em .7em;
 }
 #subir-contactos a{
                            color: #000;                                
 }
 #pin-control-btn{
                                  position: absolute;
                                  right: 280px;
                                  top: 80px;
                                 }
                                 #advert {
                                  font-size: 12px;
                                  color: red;
                                  margin-top: 25px;
                                  background: rgba(255,204,204,0.6);
                                  padding: .5em;
                                  box-sizing: border-box;
                                  width: 60%;
                                  text-align: center;
                                 }
select{
	/*-webkit-appearance: none;*/
	height: 100px !important;
	width: 100% !important;
	line-height: 10px !important;
	padding: 5px 10px !important;
 	background-color: #f2f2f2 !important;
 	border: 0 !important;
 	border-radius: 0 !important;
 	box-shadow: 0px 0px 0px 0px!important;
 	color: black !important;
 text-transform: uppercase !important;
}
input{
width: 100% !important;
 height: 80px !important;
 line-height: 80px !important;
 border: 0px !important;
 padding: 5px 10px !important;
 background-color: #f2f2f2 !important;
 transition: background-color .5s !important;
 border-radius: 0px !important;
 box-shadow: 0px 0px 0px 0px!important;
 color: black !important;
 text-transform: uppercase !important;
}
::-webkit-input-placeholder { /* Chrome/Opera/Safari */
  font-weight: 300 !important;
  font-size: 12px;
}
::-moz-placeholder { /* Firefox 19+ */
  font-weight: 300 !important;
  font-size: 12px;
}
:-ms-input-placeholder { /* IE 10+ */
  font-weight: 300 !important;
  font-size: 12px;
}
:-moz-placeholder { /* Firefox 18- */
  font-weight: 300 !important;
  font-size: 12px;
}

.form-horizontal{
	margin: 15px auto !important;
}

.resumen-de-compra{
	margin: 1rem auto !important;
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
      <!-- CONTAINER DE LA APLICACION -->
      <div id="app-container" class="container">         
         <div class="row" id="content">
            <!-- AREA DE OPTIONS -->
            <div class="span3" id="options">
               <div id="options-list">
                  <div class="group-contact-item">
                     <div id="reload-ico"><a id="reload-link" class="selected"></a></div>
                     <!-- <div id="reload-name">Recargar</div> -->
                  </div>
               </div> <!-- #options-list -->
            </div> <!-- #options -->

            <!-- AREA DE LISTADO DE ITEMES -->
            <div class="span10" id="kredit-mgr-items">
               <!-- CONTENIDO DE LA SECCION 1 *RECARGA*  -->
               <div id="reload-contents">
                    <div id="reload-title">
                      <h4>Recargue su saldo</h4>
                    </div>
                  <div id="reload-tabs">
                     <!-- pestañas -->
                     <ul class="nav nav-tabs" id="myTabcito">
                        <li class="<?php if($this->input->get('pin')!=1) echo 'active'; ?>" >
                           <a href="#tab1" data-toggle="tab">
                              <img src="<?php echo base_url("assets/img/logo-paypal-to-tabs.png"); ?>">
                           </a>

                        </li>
                        <li  style="display: none;">
                           <a href="#tab2" data-toggle="tab">
                            <img src="<?php echo base_url("assets/img/logo-pagosonline.png"); ?> "  >
                           </a>
                        </li>
                        <li class="<?php if($this->input->get('pin')==1) echo 'active'; ?>"  style="display: none;" >
                           <a href="#tab3" data-toggle="tab">
                            PIN
                           </a>
                        </li>
                     </ul>
          
                     <!-- contenido de los tabs -->
                     <div class="tab-content">
                     <div class="tab-pane <?php if($this->input->get('pin')!=1) echo 'active'; ?>" id="tab1" >
                         <?php
                  $attributes = array('method'=>'post','class'=>'form-horizontal');
                  echo form_open('payment/ini_pay', $attributes);
                 ?>
                         <?php 
                
               if($this->permissions->get('nit_or_id') == TRUE){ ?>
                                    <input type="text" placeholder="Nit o ID" id="nit_or_id" name="nit_or_id" value="<?php echo $user->nit_or_id; ?>" />
                              <?php } ?>
                                    <input type="text" placeholder="nombre" id="txt-name-pp" name="txt_name_pp" value="<?php echo $user->fullname; ?>">
                                    <input type="text" placeholder="email" id="txt-mail-pp" name="txt_mail_pp" value="<?php echo $user->email; ?>">
                                    <input type="text" placeholder="teléfono" id="txt-phone-pp" name="txt_phone_pp" value="<?php echo $user->phone; ?>">
                                    <input type="text" id="txt-address-pp" placeholder="dirección" value="<?php echo $user->address; ?>" name="txt_address_pp">
                                    <select id="cbo-country-pp" name="cbo_country_pp" class="cbo-country-pp">
                                   <option value="" disabled selected>Pais</option>
                                 <?php
                                  if(!empty($country)):
                                    foreach($country as $pais):
                                    //id_country
                                 ?>
                                  <option value="<?php echo $pais->id; ?>" ><?php echo $pais->name; ?></option>
                                 <?php
                                    endforeach;
                                  endif;
                                 ?>
                                    </select>
                                    <select id="cbo-city" name="cbo_city_pp" class="cbo-city">
                                    </select>                                    
                                    <input type="numeral" placeholder="valor" onkeyup="payPalFunction()" required min="20000" style="font-weight:300;" id="cbo-package-pp" class="paypal-package-pp" name="cbo_package_pp" > 
                              <section class="resumen-de-compra">
                                <div id="interno-resumen-de-compra">
                                  <p><b>Resumen de la Transacción Paypal</b></p><br>
                                  <div><p style="display: inline-block;">Recarga</p><p id="valor-recarga" style="float: right; display: inline-block;"></p></div>
                                  <div><p style="display: inline-block;">IVA</p><p style="float: right; display: inline-block;">16%</p></div>
                                  <hr>
                                  <div><p style="display: inline-block;"><b>Total a pagar</b></p><p style="float: right; display: inline-block; font-weight: 700;" id="total-a-pagar"></p></div>
                                  <script type="text/javascript">
                                  function payPalFunction(){   
                                    var v = parseInt(document.getElementById("cbo-package-pp").value); 
                                    var i = v * 0.16; 
                                    var vi = v + i;
                                    var c = ((vi * 0.054) + 0.30); 
                                    var vp = vi + c;
                                    var cf = (vp * 0.054) + 0.30;
                                    var t = parseInt(vi);
                                    var tu = t / 2553;
                                    if(v <= 19999){
                                  document.getElementById("btn-reload2").style.visibility = "hidden";
                                    }
                                  if(v >= 20000){
                                  document.getElementById("btn-reload2").style.visibility = "visible";
                                    }
                                    document.getElementById("valor-recarga").innerHTML = v + " " + "COP";
                                    // document.getElementById("comi").innerHTML = c.toFixed(0) + " " + "COP";
                                    document.getElementById("total-a-pagar").innerHTML = t.toFixed(0) + " " + "COP";
                                  }                  

                                           
                                  </script>
                                    <div class="control-group">
                                       <div class="controls">
                                          <button type="submit" class="payment-btn" id="btn-reload2" style="visibility:hidden;">Pagar Ahora</button>
                                       </div>
                                    </div>
                                </div>
                              </section>
                              
                              <p class="nota">Debes hacer una recarga mínima de $20.000 pesos. Todos los campos son obligatorios. <b>Grupo Mensajes de Voz S.A.S</b> garantiza a traves de PayPal y 
                              PayU que sus datos bancarios o de tarjetas de crédito no son almacenados en nuestras bases de datos para darle una total seguridad en la transacción.</p>
                              <input type="hidden" name="payment" value="paypal"  />
                           </form>                         
                        </div>
        <div class="tab-pane " id="tab2">
                           <!--Payu -->
                           
            <?php
              $attributes = array('method'=>'post','class'=>'form-horizontal');
              echo form_open('payment/ini_pay_pagosonline', $attributes);
                        ?>
                         <?php 
                
               if($this->permissions->get('nit_or_id') == TRUE){ ?>

                                    <input type="text" id="nit_or_id" placeholder="Nit o ID" name="nit_or_id" value="<?php echo $user->nit_or_id; ?>" />

                              <?php } ?>

                                    <input type="text" id="txt-name-pp" placeholder="nombre" name="txt_name_pp" value="<?php echo $user->fullname; ?>">
                                    <input type="email" id="txt-mail-pp" placeholder="email" name="txt_mail_pp" value="<?php echo $user->email; ?>">
                                    <input type="text" id="txt-phone-pp" placeholder="teléfono" name="txt_phone_pp" value="<?php echo $user->phone; ?>">
                                    <input type="text" id="txt-address-pp" placeholder="dirección" value="<?php echo $user->address; ?>" name="txt_address_pp">
                                    <select id="cbo-country-pp" name="cbo_country_pp" class="cbo-country-pp">
                                   <option value="" disabled selected>Pais</option>
                                 <?php
                                  if(!empty($country)):
                                    foreach($country as $pais):
                                    //id_country
                                 ?>
                                  <option value="<?php echo $pais->id; ?>" ><?php echo $pais->name; ?></option>
                                 <?php
                                    endforeach;
                                  endif;
                                 ?>
                                    </select>
                                    <select id="cbo-city" name="cbo_city_pp" class="cbo-city">
                                    </select>
                                    <input type="text" placeholder="Valor" onkeyup="payUFunction()" min="20000" required  id="cbo-package-pu" name="cbo_package_pp">
                            <section class="resumen-de-compra">
                              <div id="interno-resumen-de-compra">
                                <p><b>Resumen de la Transacción PayU</b></p><br>
                                <div><p style="display: inline-block;">Recarga</p><p id="valor-recarga2" style="float: right; display: inline-block;"></p></div>
                                <div><p style="display: inline-block;">IVA</p><p style="float: right; display: inline-block;">+16%</p></div>
                                <!-- <div><p style="display: inline-block;">Comisión PayU Latam</p><p id="comi2" style="float: right; display: inline-block;">+</p></div> -->
                                <hr>
                                <div><p style="display: inline-block;"><b>Total a pagar</b></p><p style="float: right; display: inline-block;" id="total-a-pagar2"></p></div>
                                <script type="text/javascript">
                                function payUFunction(){
                                  var v = parseInt(document.getElementById("cbo-package-pu").value);
                                  var i = v * 0.16; 
                                  var vi = v + i;
                                  var c = ((vi * 0.034) + 0.37); 
                                  var vp = vi + c;
                                  var cf = (vp * 0.034) + 0.37;
                                  var t = (parseInt(vi));
                                  if(v <= 19999){
                                  document.getElementById("btn-reload").style.visibility = "hidden";
                                    }
                                  if(v >= 20000){
                                  document.getElementById("btn-reload").style.visibility = "visible";
                                    }
                                  document.getElementById("valor-recarga2").innerHTML = v + " " + "COP";
                                  // document.getElementById("comi2").innerHTML = c.toFixed(0) + " " + "COP";
                                  document.getElementById("total-a-pagar2").innerHTML = t.toFixed(0) + " " + "COP";

                                }
                                </script>
                                  <div class="control-group">
                                     <div class="controls">
                                        <button type="submit" class="payment-btn" id="btn-reload" style="visibility: hidden;">Pagar Ahora</button>
                                     </div>
                                  </div>
                                </div>
                              </section>
                              <p class="nota">Debes hacer una recarga mínima de $20.000 pesos. Todos los campos son obligatorios. <b>Fonomarketing S.A.S</b> garantiza a traves de PayPal y 
                              PayU que sus datos bancarios o de tarjetas de crédito no son almacenados en nuestras bases de datos para darle una total seguridad en la transacción.</p>
                              <input type="hidden" name="payment" value="paypal"  />
                           </form>
                        </div>

                        <!-- PAYPAL -->
                        
                        
                        <div class="tab-pane <?php if($this->input->get('pin')==1) echo 'active'; ?>" id="tab3">
                          <?php
                $attributes = array('method'=>'post','class'=>'form-horizontal', 'name'=>'form-pin', 'id'=>'form-pin');
                echo form_open('payment/pin_app_assocciation', $attributes);
               ?>
                              <div class="control-group">

                                 <p>
                                  <!-- Ingresa tu <strong>PIN</strong> para recargar créditos a tu cuenta -->
                                 </p>
                                 <label class="control-label"  for="txt-pin-pp" style="width: auto; margin-right: 20px;"><b>CÓDIGO PIN</b>:</label><br>
                                 <div class="controls pin-control">
                                  <?php $pin = ($this->input->cookie('mycookiepin'))?$this->input->cookie('mycookiepin'):''; ?>
                                    <input type="text" id="txt-pin-pp" name="txt-pin-pp" placeholder="Ingresa tu pin" value="<?php echo $pin; ?>" />
                                    
                                 </div>
                              </div>
                              <div class="control-group">
                                 <div class="controls" id="pin-control-btn">
                                    <button type="submit"  class="payment-btn"  id="btn-reload3" >Usar PIN</button>
                                 </div>
                                 <div id="advert">Recuerda, si tu PIN tiene símbolos o mayúsculas, así mismo deberás escribirlo.</div>
                              </div>
                              
                            
                        </div>
                        <?php if ($credits != 0 AND  $app_data->uri != 'pymesplus') {
                               
                              ?>
                                <div id="subir-contactos"><a href="<?php echo base_url('apps/'); ?>">Comienza a subir contactos</a></div>
                              <?php 
                                }
                              ?>
                              
                            <?php echo form_close(); ?>
                     </div> <!-- .tab-content -->
                  </div> <!-- .reload-tabs -->

               </div> <!-- #reload-contents -->

            </div> <!-- #items-data LISTADO DE ITEMES -->

         </div> <!-- .row  #content -->

      </div> <!-- #app-container -->


    <?php if($this->session->flashdata("paymade")) $this->load->view('globales/pay_made'); ?>

      <!-- Le javascript -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
      <script src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
      <script src="<?php echo base_url("assets/js/jquery.jeditable.mini.js"); ?>"></script>
      <script src="<?php echo base_url("assets/js/mgrs-ini.js"); ?>"></script>
      <script>
      $('.info_pago').find("a").on('click', function(event){
      $(this).parent().parent().remove();
      event.preventDefault();
    });
    
    
    <?php if($user->id_country != 0 && $user->id_city != 0): ?>
    cambiarCiudadEditar(<?php echo $user->id_country; ?>,<?php echo $user->id_city; ?>);
    $('select[name="cbo_country_pp"]').val(<?php echo $user->id_country; ?>);
    var cbo = $('select[name="cbo_package_pp"]');
    cbo.on('change', function(){
    $.post('<?php echo site_url('payment/show_commission_for_kredits') ?>', 
      function (data){
        data = $.parseJSON(data);
        $("#apagar").empty();
        if(data.cod == 1){
          $("#apagar").html(+data["commission"] );
        }
      });
    });
    cbo.trigger('change');
        <?php endif; ?>    
      </script>
   </body> 
</html>