<html>

<head>

<title>Ejemplo de pago mediante la API de PayPal</title>

</head>

<body>

<?php
	echo form_open('pago/ini_pay');
?>
	<input type='text' name='first_name' value='Juan'>
	<input type='text' name='last_name' value='Lopez'>
	<input type='text' name='address1' value='cll 59 a'>
	<input type='text' name='city' value='medellin'>
	<input type='text' name='zip' value='00000'>
	<input type='text' name='night_phone_a' value='3569900'>
	<select name="paquete">
		<option value="1">5 Creditos 1USD</option>
		<option value="5">25 Creditos 5USD</option>
		<option value="10">50 Creditos 10USD</option>
		<option value="20">100 Creditos 20USD</option>
		<option value="30">150 Creditos 30USD</option>
	</select>
	<input type="submit" value="Enviar" />
<?php
	echo form_close();
?>


</body>

</html>