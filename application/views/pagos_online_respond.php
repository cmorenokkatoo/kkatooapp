<html>
<head>
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
</head>

<body>
    <h3>Mensaje:</h3>
    <p>Si imprime: <strong>aprobado</strong>, <strong>no aprobado</strong> o <strong>en espera</strong> es porque pasó el filtro de la firma, de lo contrario no lo pasó. y si imprime este mensaje => <em>"Ingresó a confirm y pasó prueba de firma"</em> en color rojo, es porque entró a <em>"pagosonline_confirm_payment_prueba"</em> y se generó una session confirmando que si entró.</p>
    <p style="color:green">
	<?php 
        echo $mensaje;
    ?>
    </p>
    <p style="color:red">
    <?php 
        if($this->session->userdata('entro_confirm')){
            echo $this->session->userdata('entro_confirm');
        }
    ?>
    </p>
    <h3>Datos por Get</h3>
    <?php print_r($this->input->get(NULL, TRUE)); ?>
    <?php $this->session->sess_destroy(); ?>
</body>
</html>