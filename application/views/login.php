<?php
    $this->load->view('globales/head');
	$this->load->view('globales/mensajes');
    $this->load->view('globales/navbar_login');
?>

<!-- Login ================================================== -->
<div class="mainsignincontent">
    <div class="titlesignin">
        <h1>Acceder para usar la plataforma</h1>
    </div>
    <div class="card signin-card" id="signin-card">
        <div id="formframe">
            <?php echo form_open('login/signin', array('id'=>'signin')); ?>
                    <div class="formulario frm_nbr">
                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend">
                                    <input class="txt_email" placeholder="Ingresa tu dirección de correo electrónico" type="text" name="email" value="<?php echo set_value('email'); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <div class="input-prepend">
                                    <input class="txt_password" placeholder="Ingresa tu contraseña" type="password" name="password" autocomplete="off" required>
                                    <input class=" btn btn-warning btn_login" type="submit" value="Siguiente" >
                                </div>
                            </div>
                        </div>
                    </div>
            <?php echo form_close(); ?>
            <a href="<?php echo base_url("login/forgot"); ?>" class="loginlink" alt="¿Has olvidado tu contraseña?">Recordar contraseña</a>
        </div>
    </div>
    <a href="<?php echo base_url("login/register"); ?>" class="loginlink" id="registerlink" alt="Crea una nueva cuenta">Crear cuenta</a>
    <p>Todos los datos almacenados son protegidos y no son enviados a terceros.</p>
</div>
