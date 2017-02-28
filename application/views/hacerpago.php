<html>
<head>
<title>Transfiriendo a Paypal...</title>
</head>
<body>
<?php
	if(!empty($is_suscriber)){
		if($is_suscriber == TRUE){
?>
	<form name='formTpv' method='post' action='https://www.paypal.com/cgi-bin/webscr' target="_top">
	<input type='hidden' name='cmd' value='_xclick'>
    <!-- valeriasalazar123@gmail.com -->
	<input type='hidden' name='business' value='juancorchuelo@mensajesdevoz.co'>
	<input type='hidden' name='item_name' value='Carga de saldo en linea'>
	<input type='hidden' name='item_number' value='<?php echo $id_venta; ?>'>
	<input type='hidden' name='amount' value='<?php echo $package; ?>'>
	<input type='hidden' name='page_style' value='primary'>
	<input type="hidden" name="quantity" value="1" />
	<input type='hidden' name='no_shipping' value='1'>
	<input type='hidden' name='return' value='<?php echo base_url('payment/confirm_payment_suscription'); ?>'>
	<input type='hidden' name='rm' value='2'>
	<input type='hidden' name='cancel_return' value='<?php echo base_url('payment/confirm_payment_suscription'); ?>'>
	<input type='hidden' name='no_note' value='1'>
	<input type='hidden' name='currency_code' value='USD'>
	<input type='hidden' name='cn' value='PP-BuyNowBF'>
	<input type='hidden' name='custom' value=''>
	<input type='hidden' name='first_name' value='<?php echo $user->name_payment ?>'>
	<input type='hidden' name='last_name' value=''>
	<input type='hidden' name='address1' value='<?php echo $this->input->post('txt_address_pp') ?>'>
	<input type='hidden' name='city' value='<?php echo $city->name; ?>'>
	<input type='hidden' name='zip' value=''>
	<input type='hidden' name='night_phone_a' value='<?php echo $this->input->post('txt_phone_pp') ?>'>
	<input type='hidden' name='night_phone_b' value=''>
	<input type='hidden' name='night_phone_c' value=''>
	<input type='hidden' name='lc' value='es'>
	<input type='hidden' name='country' value='ES'>
</form>
<?php
		}
	}else{
?>
<form name='formTpv' method='post' action='https://www.paypal.com/cgi-bin/webscr' target="_top">
	<input type='hidden' name='cmd' value='_xclick'>
	<input type='hidden' name='business' value='juancorchuelo@mensajesdevoz.co'>
	<input type='hidden' name='item_name' value='Carga de saldo en linea'>
	<input type='hidden' name='item_number' value='<?php echo $id_venta; ?>'>
	<input type='hidden' name='amount' value='<?php echo $package; ?>'>
	<input type='hidden' name='page_style' value='primary'>
	<input type="hidden" name="quantity" value="1" />
	<input type='hidden' name='no_shipping' value='1'>
	<input type='hidden' name='return' value='<?php echo base_url('payment/confirm_payment'); ?>'>
	<input type='hidden' name='rm' value='2'>
	<input type='hidden' name='cancel_return' value='<?php echo base_url('payment/confirm_payment'); ?>'>
	<input type='hidden' name='no_note' value='1'>
	<input type='hidden' name='currency_code' value='USD'>
	<input type='hidden' name='cn' value='PP-BuyNowBF'>
	<input type='hidden' name='custom' value=''>
	<input type='hidden' name='first_name' value='<?php echo $user->fullname ?>'>
	<input type='hidden' name='last_name' value=''>
	<input type='hidden' name='address1' value='<?php echo $this->input->post('txt_address_pp') ?>'>
	<input type='hidden' name='city' value='<?php echo $city->name; ?>'>
	<input type='hidden' name='zip' value=''>
	<input type='hidden' name='night_phone_a' value='<?php echo $this->input->post('txt_phone_pp') ?>'>
	<input type='hidden' name='night_phone_b' value=''>
	<input type='hidden' name='night_phone_c' value=''>
	<input type='hidden' name='lc' value='es'>
	<input type='hidden' name='country' value='ES'>
</form>
<?php
	}
?>
<script type='text/javascript'>
	document.formTpv.submit();
</script>
</body>
</html>
