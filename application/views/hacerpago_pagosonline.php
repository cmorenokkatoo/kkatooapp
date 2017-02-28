<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
  	<meta charset="iso-8859-1">
	<title>Transfiriendo a PayU Latam</title>
</head>
<body>

<?php 
	if(!empty($is_suscriber)){
		if($is_suscriber == TRUE){
?>

<!-- https://gateway.payulatam.com/ppp-web-gateway/ -->
<form name="formTpv" method="post" action="https://gateway.payulatam.com/ppp-web-gateway/">
  <input name="merchantId"    type="hidden"  value="616516"   >
  <input name="accountId"     type="hidden"  value="619258" >
  <input name="description"   type="hidden"  value="Carga de créditos por PayU Latam"  >
  <input name="referenceCode" type="hidden"  value="<?php echo $refVenta; ?>" >
  <input name="amount"        type="hidden"  value="<?php echo $package; ?>"   />
  <input name="tax"           type="hidden"  value="0" >
  <input name="taxReturnBase" type="hidden"  value="0" >
  <input name="currency"      type="hidden"  value="<?php echo PAYMENT_CURRENCY ?>" >
  <input name="signature"     type="hidden"  value="<?php echo $firma; ?>"  >
  <input name="test"          type="hidden"  value="0" >
  <input name="buyerEmail"    type="hidden"  value="cmoreno@kkatoo.com"/>
  <input name="buyerFullName" type="hidden"  value="Cristiam Moreno Posada"/>
  <input name="telephone"     type="hidden"  value="3053668307" />
  <!-- <input name="shippingCountry" type="hidden" value="<?php echo $cod_user_contact ?>" /> -->
  <input name="responseUrl"    type="hidden"  value="http://www.test.com/response" >
  <input name="confirmationUrl"  type="hidden"  value="http://www.test.com/confirmation" >
  <!-- <input name="Submit"        type="submit"  value="Enviar" > -->
</form>
<?php
		}
	}else{
?>
<!-- https://gateway.payulatam.com/ppp-web-gateway/ -->
<form name="formTpv" method="post" action="https://gateway.payulatam.com/ppp-web-gateway/">
  <input name="merchantId"    type="hidden"  value="616516"   >
  <input name="accountId"     type="hidden"  value="619258" >
  <input name="description"   type="hidden"  value="Carga de créditos por PayU Latam"  >
  <input name="referenceCode" type="hidden"  value="<?php echo $refVenta; ?>" >
  <input name="amount"        type="hidden"  value="<?php echo $package; ?>"   />
  <input name="tax"           type="hidden"  value="0" >
  <input name="taxReturnBase" type="hidden"  value="0" >
  <input name="currency"      type="hidden"  value="<?php echo PAYMENT_CURRENCY ?>" >
  <input name="signature"     type="hidden"  value="<?php echo $firma; ?>"  >
  <input name="test"          type="hidden"  value="0" >
  <input name="buyerEmail"    type="hidden"  value="cmoreno@kkatoo.com"/>
  <input name="buyerFullName" type="hidden"  value="Cristiam Moreno Posada"/>
  <input name="telephone"     type="hidden"  value="3053668307" />
  <!-- <input name="shippingCountry" type="hidden" value="<?php echo $cod_user_contact ?>" /> -->
  <input name="responseUrl"    type="hidden"  value="http://www.test.com/response" >
  <input name="confirmationUrl"  type="hidden"  value="http://www.test.com/confirmation" >
  <!-- <input name="Submit"        type="submit"  value="Enviar" > -->
</form>
<?php
	}
?>
<script type='text/javascript'>
	document.formTpv.submit();
</script>
</body>
</html>