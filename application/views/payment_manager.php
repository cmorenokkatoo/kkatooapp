<?php
  $this->load->view("globales/head_payment");
  $this->load->view('globales/mensajes');

  $Usuario = $this->session->userdata('user_id');

  if ($Usuario != 570) {  
?>
<div style="margin: 0 auto; text-align: center; width: 100%;">
  <p>Mantenimiento Preventivo</p>
</div>
<?php }else{ ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/newpayment.css"); ?>">
      <!-- HEADER DE LA PAGINA -->
      <div id="brand-header" class="navbar navbar-fixed-top">
         <div class="navbar-inner">
            <!-- AVISO DE CREDITOS DISPONIBLES -->
            <div id="navbar-container" class="container">               
              <div class="row">
                  <!-- Para logo -->
                  <div class="span6">
                     <?php 
            $logo = $this->specialapp->create_logo('logo-main-header.png');?>
                  <a href="<?php echo $logo->brand_url; ?>">
                    <img src="<?php echo  $logo->brand_img ?>" alt="<?php echo $logo->brand_title ?>" style="height:60px; margin-top: 2px;" />
                  </a>
                  </div>
                  
                 <?php $this->load->view('utils/user_dropdown2') ?>

               </div> <!-- .row -->
            </div> <!-- #navbar-container -->
         </div>
      </div> <!-- #brand-header -->


<!--********************************************************************************* -->
      <!-- CONTAINER DE LA APLICACION -->
      <div id="app-container" class="container">
      <style>
      .currency-small {
    font-size: 10px;
    font-weight: 400;
    display: inline-block;
    /*line-height: 36px;*/
    vertical-align: middle;
    padding: 0em .3em;

  }
  .number {
    /*min-width: 60px;*/
    /*float: left;*/
    font-size: 20px;
    font-weight: 700;
    line-height: 55px;
    /*margin-left: 25px;*/
    /*text-align: right;*/
    /*letter-spacing: -2px;*/
  }
  .legend {
    /*float: left;*/
    font-size: 12px;
    line-height: 18px;
    /*width: 70px;*/
    /*margin-top: 9px;*/
    display: inline-block;
  }
  label{
    padding: 5px 0px;

  }
  input, select{
    border: 1px solid #e5e5e5 !important;
    box-shadow: 0px !important;
  }
  input[type=email]
  {
    border: 1px solid #e5e5e5 !important;
    box-shadow: 0px !important;

  }
  </style>
         
         <!-- HEADER DE LA APLICACION -->
         <div class="row">
            <div class="span12" id="app-header">
               <div class="row">
                  <div class="span6" id="title-and-links">
                     <h2>Administrador de Pagos</h2>
                  </div> <!-- #title-and-links -->
                  <div class="span6 right-for-kredits">
                     <div id="available-kredits">
                        <div id="content">
                           <span class="legend">
                            Saldo
                          </span>
                          <span class="number"><?php echo number_format($credits,0,",","."); ?><span class="currency-small"> COP</span></span>
                        </div>
                     </div> <!-- #available-kredits -->
                  </div>

               </div> <!-- .row -->
            </div> <!-- #app-header -->
         </div> <!-- .row -->





         <!-- CUERPO DE LA APLICACION -->
         <div class="row" id="content">
            <!-- AREA DE OPTIONS -->
            <div class="span3" id="options">
               <div id="options-list">
                  <div class="group-contact-item">
                     <div id="reload-ico"><a id="reload-link" class="selected"></a></div>
                     <!--div id="reload-name">Recargar</div-->
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
                        <li class="<?php if($this->input->get('pin')!=1) echo 'active'; ?>">
                           <a href="#tab1" data-toggle="tab">
                            <img src="<?php echo base_url("assets/img/logo-pagosonline.png"); ?> " width="60" >
                           </a>
                        </li>
                        <li >
                           <a href="#tab2" data-toggle="tab">
                              <img src="<?php echo base_url("assets/img/logo-paypal-to-tabs.png"); ?>">
                           </a>
                        </li>
                        <li class="<?php if($this->input->get('pin')==1) echo 'active'; ?>">
                           <a href="#tab3" data-toggle="tab">
                            PIN
                           </a>
                        </li>
                     </ul>
          
                     <!-- contenido de los tabs -->
                     <div class="tab-content">
                     <!-- <p style="">Debes hacer una recarga mínima de $20.000 COP</p> -->
                        <!--h5 style="font-weight:300; padding-left:10px;">Los campos marcados con * son obligatorios</h5-->
                    <!-- segundo tab -->
                        <div class="tab-pane <?php if($this->input->get('pin')!=1) echo 'active'; ?>" id="tab1">
                           <!--PAGOSONLINE!!-->
                           
            <?php
              $attributes = array('method'=>'post','class'=>'form-horizontal');
              echo form_open('payment/ini_pay_pagosonline', $attributes);
                        ?>
                         <?php 
                
               if($this->permissions->get('nit_or_id') == TRUE){ ?>
                             <div class="control-group">
                                 <label class="control-label" for="txt_name_pp">Nit o Cedula:</label><br>
                                 <div class="controls">
                                    <input type="text" id="nit_or_id" name="nit_or_id" value="<?php echo $user->nit_or_id; ?>" />
                                    
                                 </div>
                              </div>
                              <?php } ?>
                              <div class="control-group">
                                 <label class="control-label" for="txt_name_pp">Nombre:</label><br>
                                 <div class="controls">
                                    <input type="text" id="txt-name-pp" name="txt_name_pp" value="<?php echo $user->fullname; ?>">
                                    
                                 </div>
                              </div>
                              <div class="control-group">
                                 <label class="control-label" for="txt_lastname_pp">E-mail:</label><br>
                                 <div class="controls">
                                    <input type="email" id="txt-mail-pp" name="txt_mail_pp" value="<?php echo $user->email; ?>">
                                    
                                 </div>
                              </div>
                              <div class="control-group">
                                 <label class="control-label" for="txt_phone_pp">Telefono:</label><br>
                                 <div class="controls">
                                    <input type="text" id="txt-phone-pp" name="txt_phone_pp" value="<?php echo $user->phone; ?>">
                                    
                                 </div>
                              </div>
                              <div class="control-group">
                                 <label class="control-label" for="txt_address_pp">Dirección:</label><br>
                                 <div class="controls">
                                    <input type="text" id="txt-address-pp" value="<?php echo $user->address; ?>" name="txt_address_pp">
                                    
                                 </div>
                              </div>
                              <div class="control-group">
                                 <label class="control-label" for="cbo_country_pp">Pais:</label><br>
                                 <div class="controls">
                                    <select id="cbo-country-pp" name="cbo_country_pp" class="cbo-country-pp">
                                   <option value="" disabled selected style='display:none;'>Pais</option>
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
                                    
                                 </div>
                              </div>
                              <div class="control-group">
                                 <label class="control-label" for="cbo_city_pp">Ciudad:</label><br>
                                 <div class="controls">
                                    <select id="cbo-city" name="cbo_city_pp" class="cbo-city">
                                    </select>
                                    
                                 </div>
                              </div>
                              
                              <div class="control-group">
                                 <label class="control-label" for="cbo_package_pp">Valor:</label><br>
                                 <div class="controls">
                                    <input type="text" onkeyup="payUFunction()" min="20000" required  style="font-weight:300;" id="cbo-package-pu" name="cbo_package_pp"> COP
                                  </div>
                              </div>
                              <section class="resumen-de-compra">
                              <div id="interno-resumen-de-compra">
                                <p><b>Resumen de la Transacción</b></p><br>
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
                        <div class="tab-pane" id="tab2" >
                         <?php
                  $attributes = array('method'=>'post','class'=>'form-horizontal');
                  echo form_open('payment/ini_pay', $attributes);
                 ?>
                         <?php 
                
               if($this->permissions->get('nit_or_id') == TRUE){ ?>
                             <div class="control-group">
                                 <label class="control-label" for="txt_name_pp">Nit o Cedula:</label>
                                 <div class="controls">
                                    <input type="text" id="nit_or_id" name="nit_or_id" value="<?php echo $user->nit_or_id; ?>" />
                                 </div>
                              </div>
                              <?php } ?>
                              <div class="control-group">
                                 <label class="control-label" for="txt_name_pp">Nombre:</label><br>
                                 <div class="controls">
                                    <input type="text" id="txt-name-pp" name="txt_name_pp" value="<?php echo $user->fullname; ?>">
                                 </div>
                              </div>
                              <div class="control-group">
                                 <label class="control-label" for="txt_lastname_pp">E-mail:</label><br>
                                 <div class="controls">
                                    <input type="text" id="txt-mail-pp" name="txt_mail_pp" value="<?php echo $user->email; ?>">
                                 </div>
                              </div>
                              <div class="control-group">
                                 <label class="control-label" for="txt_phone_pp">Telefono:</label><br>
                                 <div class="controls">
                                    <input type="text" id="txt-phone-pp" name="txt_phone_pp" value="<?php echo $user->phone; ?>">
                                 </div>
                              </div>
                              <div class="control-group">
                                 <label class="control-label" for="txt_address_pp">Dirección:</label><br>
                                 <div class="controls">
                                    <input type="text" id="txt-address-pp" value="<?php echo $user->address; ?>" name="txt_address_pp">
                                 </div>
                              </div>
                              <div class="control-group">
                                 <label class="control-label" for="cbo_country_pp">Pais:</label><br>
                                 <div class="controls">
                                    <select id="cbo-country-pp" name="cbo_country_pp" class="cbo-country-pp">
                                   <option value="" disabled selected style='display:none;'>Pais</option>
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
                                    
                                 </div>
                              </div>
                              <div class="control-group">
                                 <label class="control-label" for="cbo_city_pp">Ciudad:</label><br>
                                 <div class="controls">
                                    <select id="cbo-city" name="cbo_city_pp" class="cbo-city">
                                    </select>
                                    
                                 </div>
                              </div>
                              
                              <div class="control-group">
                                 <label class="control-label" for="cbo_package_pp">Valor:</label><br>
                                 <div class="controls">
                                    <input type="text" onkeyup="payPalFunction()" required min="20000" style="font-weight:300;" id="cbo-package-pp" class="paypal-package-pp" name="cbo_package_pp" > COP
                                 </div>
                              </div>
                              <section class="resumen-de-compra">
                                <div id="interno-resumen-de-compra">
                                  <p><b>Resumen de la Transacción</b></p><br>
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
                              
                              <p class="nota">Debes hacer una recarga mínima de $20.000 pesos. Todos los campos son obligatorios. <b>Fonomarketing S.A.S</b> garantiza a traves de PayPal y 
                              PayU que sus datos bancarios o de tarjetas de crédito no son almacenados en nuestras bases de datos para darle una total seguridad en la transacción.</p>
                              <input type="hidden" name="payment" value="paypal"  />
                           </form>                         
                        </div>
                        
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
                                 <style type="text/css">

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
                                 </style>
                              </div>
                              
                            
                        </div>
                        <style>
                        #subir-contactos{
                            position: absolute;
                            top: 0px;
                            right: 0px;
                            visibility: visible;
                            display: table;
                            background: #CDF3BA;
                            border-radius: 4px;
                            padding: .5em .7em;
                          }
                          #subir-contactos a{
                            color: #000;                                
                          }
                        </style>
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
<?php } ?>
</html>