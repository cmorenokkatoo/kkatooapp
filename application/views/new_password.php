<?php 
    $this->load->view('globales/head');
	$this->load->view('globales/mensajes');
    // $this->load->view('globales/navbar'); 
    $this->load->view('globales/navbar_login');

?>
<?php 
  
    if($this->uri->segment(3))
    {
        $email = urldecode($this->uri->segment(3));
    }
    if($this->uri->segment(4))
    {
        $token_url = $this->uri->segment(4);
    }
?>
<!-- Login ================================================== -->
<div class="mainsignincontent">
    <div class="titlesignin">
        <h1>Confirma tu nueva contraseña</h1>
    </div>
    <div class="card signin-card" id="signin-card">
        <div id="formframe">
            <?php echo form_open('login/add_new_password', array('id'=>'new_password')); ?> 
                <div class="formulario">
                    <div class="control-group">
                        <div class="controls">
                            <div class="input-prepend">
                                <input class="txt_email" placeholder="CONTRASEÑA" type="password" autocomplete="off" name="password" required>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <div class="input-prepend">
                                <input class="txt_password" placeholder="CORFIRMAR" type="password" name="confirm_password" required>
                                <input type="hidden" name="token" value="<?php echo $token; ?>">
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                <input type="hidden" name="email" value="<?php echo $email; ?>">
                                <input type="hidden" name="token_url" value="<?php echo $token_url; ?>">
                                <input class="btn_login" type="submit" value="Recuperar contraseña">
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
                    
                </div>
                <?php echo form_open('login/add_new_password', array('id'=>'new_password')); ?> 
                <div class="formulario">
                    <div class="control-group">
                        <div class="controls">
                            <div class="input-prepend">
                                <input class="txt_email" placeholder="CONTRASEÑA" type="password" autocomplete="off" name="password" required>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <div class="input-prepend">
                                <input class="txt_password" placeholder="CORFIRMAR" type="password" name="confirm_password" required>
                                <input type="hidden" name="token" value="<?php echo $token; ?>">
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                <input type="hidden" name="email" value="<?php echo $email; ?>">
                                <input type="hidden" name="token_url" value="<?php echo $token_url; ?>">
                                <input class="btn_login" type="submit" value="Recuperar contraseña">
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div> -->
</section>
<!-- Fin Login ================================================== -->
<?php
    // $this->load->view('globales/footer_nav');
    // $this->load->view('globales/footer');
?>