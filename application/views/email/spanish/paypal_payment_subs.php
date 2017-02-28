<h3>Gracias!.</h3>
<h4><?php echo $pay_name; ?></h4>
<p>Haz realizado una recarga de Créditos para la suscripción a la aplicación <strong><?php echo $app_name ?></strong></p>
<p>Código de compra: <?php echo $id_pay ?></p>
<p>Fecha de compra: <?php echo $pay_date ?></p>

<table cellpadding="4" cellspacing="4">
	<tr>
    	<th>Suscripción</th>
        <th>Precio</th>
    </tr>
    <tr>
    	<td><?php echo $app_name ?></td>
        <td>USD $<?php echo $package_price ?></td>
    </tr>
</table>

<?php if($pay_state == 1): ?>
<p>La recarga de Créditos ha sido exitosa, desde ahora puede disfrutar del servicio.</p>
<?php elseif($pay_state == 2): ?>
<p>Gracias por su recarga, en las proximas 48 revisaremos su compra. y comenzará a disfrutar del servicio.</p>
<?php endif; ?>
