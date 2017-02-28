<style type="text/css">
	.info_pago{
		width:100%;
		height:100%;
		position:fixed;
		top:0;
		left:0;
		z-index:99999;
	}
	.background{
		width:100%;
		height:100%;
		position:absolute;
		background:rgba(0,0,0,0.6);
	}
	.datos_pago_realizado{
		position: absolute;
		top: 50%;
		left: 50%;
		width: 600px;
		background: #fff;
		padding: 20px;
		margin-left: -300px;
		margin-top: -200px;
	}
	.datos_pago_realizado p{
		margin:0;
	}
	.datos_pago_realizado a{
		position: absolute;
		color:#666666;
		right: 15px;
		top: 10px;
		font-weight: bolder;
		text-decoration:none;
	}
	.datos_pago_realizado a:hover{
		color: #EDB13B;
	}
	.datos_pago_realizado p.message{
		margin: 15px 0px;
	}
</style>
<div class="info_pago">
    <div class="background"></div>
    <div class="datos_pago_realizado">
        <a href="#">X</a>
        <p class="message"><?php if($this->session->flashdata("exitoso")) echo $this->session->flashdata("exitoso"); ?></p>
        <p class="message"><?php if($this->session->flashdata("error")) echo $this->session->flashdata("error"); ?></p>
        <?php if($this->input->get("usuario_id")==PAGOSONLINE_USER_ID): ?>
            <h4>DATOS DE LA TRANSACCION</h4>
            <p><strong>Numero de Trasaccion: </strong><?php echo $this->input->get("ref_pol"); ?></p>
            <p><strong>Estado: </strong><?php echo $this->input->get("estado_pago"); ?></p>
            <p><strong>Referencia: </strong><?php echo $this->input->get("ref_venta"); ?></p>
            <p><strong>Valor: </strong><?php echo $this->input->get("valor"); ?></p>
            <p><strong>Moneda: </strong><?php echo $this->input->get("moneda"); ?></p>
            <p><strong>Fecha: </strong><?php echo $this->input->get("fecha_procesamiento"); ?></p>
    	<?php endif; ?>
    </div>
</div>