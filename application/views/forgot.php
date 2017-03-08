<?php 
    $this->load->view('globales/head');
    $this->load->view('globales/mensajes');

?>

<!-- Recuperar ================================================== -->

<div class="mainsignincontent">
    <div class="titlesignin">
        <h1>Mensajes de Voz</h1>
        <h2>Recupera tu contraseña</h2>
    </div>
    <div class="card signin-card" id="signin-card">
        <div id="formframe">
        	<?php echo form_open('login/add_forgot', array('id'=>'forgot')); ?> 
                <div class="formulario">
                    <div class="control-group">
                        <div class="controls">
                            <div class="input-prepend">
                                <input class="txt_email" placeholder="dirección electrónica" type="email" name="email" value="<?php echo set_value('email'); ?>" required>
                                <input style="margin: 0 auto; margin-top:5px;" type="submit" value="Recuperar" class="btn btn-warning btn_login">
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
        </div>
    </div>
</div>