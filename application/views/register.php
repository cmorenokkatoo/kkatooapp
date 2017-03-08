<?php
    $this->load->view('globales/head');
	$this->load->view('globales/mensajes');
    // $this->load->view('globales/navbar_login');
?>
<!-- Login ================================================== -->
<div class="mainsignincontent">
    <div class="titlesignin">
        <h1>Mensajes de Voz</h1>
        <h2>Crea tu cuenta ahora</h2>
    </div>
    <div class="card signin-card" id="signin-card">
        <div id="formframe">
        <?php echo form_open('login/add_register', array('id'=>'register')); ?>
                    <div class="formulario">
                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend">
                                    <input class="input-in-register" value="<?php echo set_value('fullname'); ?>" placeholder="Ingresar nombre completo" type="text" name="fullname" required>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend">
                                    <input class="input-in-register" placeholder="Ingresar tu correo electrónico" type="email" name="email_r" value="<?php echo set_value('email_r'); ?>"  required>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend">
                                    <input class="input-in-register" placeholder="Ingresar tu contraseña" type="password" autocomplete="off" name="password" required>
                                </div>
                            </div>
                        </div>


                        <div class="control-group" style="display: none;">
                            <div class="controls">
                                <div class="input-prepend">
                                 <select id="cbo-country-in-register" name="indi_pais">
                                     <option  disabled selected style='display:none;'>PAIS</option>
                                      <?php
                                            if(!empty($country)):
                                                foreach($country as $pais):
                                         ?>
                                                  <option value="<?php echo $pais->id; ?>" <?php if(set_value('indi_pais')==$pais->id) echo 'selected="selected"' ?>><?php echo $pais->name; ?></option>
                                         <?php
                                                endforeach;
                                            endif;
                                         ?>
                                  </select>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend">
                                    <input class="input-in-register2" placeholder="Ingresa tu teléfono" type="tel" name="phone" value="<?php echo set_value('phone'); ?>">
                                </div>
                            </div>
                        </div>

                        <?php if($this->specialapp->get('uses_special_pines') == 1): ?>
                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend">
                                    <?php $pin = ($this->input->cookie('mycookiepin'))?$this->input->cookie('mycookiepin'):''; ?>
                                    <input class="input-in-register2" placeholder="PIN" type="pin" name="pin" value="<?php echo set_value('pin', $pin); ?>"  required>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="control-group" style="display: none;">
                            <div class="controls">
                                <div class="input-prepend">
                                    <label class="spanLinks checkbox signin-terms" id="label-terms">
                                      <input type="checkbox" name="tyc" value="1" style="width: auto; height: auto;" checked bloqued>
                                      Acepto los <a href="#">T&eacute;rminos y condiciones</a> y las <a href="#" class="pdp link">Pol&iacute;ticas de privacidad.</a>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <input class="btn btn-warning btn-in-register" type="submit" value="Crea tu cuenta">

                    </div>
                    <?php echo form_close(); ?>
            </div>
        </div>
       <div class="spanLinks"> <a href="<?php echo base_url("login/login"); ?>" class="loginlink" id="loginrlink">Inicia sesión</a></div>
</div>
