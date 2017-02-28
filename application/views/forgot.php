<?php 
    $this->load->view('globales/head');
	$this->load->view('globales/mensajes');
    // $this->load->view('globales/navbar'); 
    $this->load->view('globales/navbar_login');

?>

<!-- Recuperar ================================================== -->

<div class="mainsignincontent">
    <div class="titlesignin">
        <h1>Recuperar contraseña</h1>
    </div>
    <div class="card signin-card" id="signin-card">
        <div id="formframe">
        	<?php echo form_open('login/add_forgot', array('id'=>'forgot')); ?> 
                <div class="formulario">
                    <div class="control-group">
                        <div class="controls">
                            <div class="input-prepend">
                                <input class="txt_email" placeholder="CORREO ELECTRÓNICO" type="email" name="email" value="<?php echo set_value('email'); ?>" required>
                                <input style="margin: 0 auto; margin-top:5px;" type="submit" value="Recuperar" class="btn btn-warning btn_login">
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
        </div>
    </div>
</div>





<!-- <section id="contenido">
    <div class="ventana modalhome"  id="login">
        <div class="widgetloginhome">
            <div class="manual_login">
                <div class="title">
                    RECUPERAR CONTRASEÑA
                </div>
                <?php echo form_open('login/add_forgot', array('id'=>'forgot')); ?> 
                <div class="formulario">
                    <div class="control-group">
                        <div class="controls">
                            <div class="input-prepend">
                                <input class="txt_email" placeholder="CORREO ELECTRÓNICO" type="email" name="email" value="<?php echo set_value('email'); ?>" required>
                                <input style="margin: 0 auto; margin-top:5px;" type="submit" value="Recuperar" class="btn btn-warning btn_login">
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</section> -->
<!-- Fin Recuperar ================================================== -->
<?php
    // $this->load->view('globales/footer_nav');
    // $this->load->view('globales/footer');
?>