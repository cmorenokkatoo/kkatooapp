<?php
    $this->load->view('globales/head');
	$this->load->view('globales/mensajes');
    $this->load->view('globales/navbar_login');
?>

<?php //if(($this->permissions->get('register_gmail') == TRUE && $this->permissions->get('register_facebook') == TRUE) || $this->specialapp->get('uses_special_pines') == 1): ?>
<!-- <style type="text/css">
	.manual-register {
		vertical-align: top;
		padding: 0;
		text-align: left;
		display: inline-block;
		margin: 20px;
		float: none;
	}
	#login-container .title-login, #register-container .title-register{
		border-bottom: 1px solid #DDD;
		font-family: 'Open Sans', sans-serif;
		font-size: 24px;
		font-weight: 300;
		letter-spacing: 3px;
		padding: 0 0 6px 0;
		width: auto;
		margin: 20px 20px 0 20px;
		text-align: center;
	}
    .after  {
        background: #000;
        color:red;
        font-weight:bold;
        display:box;
        position: absolute;
        display: box;
        top: -100px;
        left: 500px;
        width: 100px;
        height: 100px;

    }
</style> -->
<?php //endif; ?>
<!-- Login ================================================== -->
<div class="mainsignincontent">
    <div class="titlesignin">
        <h1>Crear una nueva cuenta</h1>
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
                                    <input class="input-in-register" placeholder="Ingresar correo electrónico" type="email" name="email_r" value="<?php echo set_value('email_r'); ?>"  required>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend">
                                    <input class="input-in-register" placeholder="Ingresar contraseña" type="password" autocomplete="off" name="password" required>
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

                        <div class="control-group" style="display: none;">
                            <div class="controls">
                                <div class="input-prepend">
                                    <input class="input-in-register2" placeholder="TELEFONO MÓVIL" type="phone" name="phone" value="<?php echo set_value('phone'); ?>">
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

                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend">
                                    <label class="checkbox signin-terms" id="label-terms">
                                      <input type="checkbox" name="tyc" value="1" style="width: auto; height: auto;">
                                      Acepto los <a href="#" class="tyc link">T&eacute;rminos y condiciones</a><br>y las <a href="#" class="pdp link">Pol&iacute;ticas de privacidad.</a>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <input class="btn btn-warning btn-in-register" type="submit" value="REGÍSTRATE">

                    </div>
                    <?php echo form_close(); ?>
            </div>
        </div>
        <a href="<?php echo base_url("login/login"); ?>" class="loginlink" id="loginrlink">Iniciar Sesión</a>
</div>
