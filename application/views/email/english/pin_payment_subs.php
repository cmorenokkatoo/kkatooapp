<h3>Gracias!.</h3>
<h4><?php echo $contact_name; ?></h4>
<p>Haz realizado una recarga de Kréditos para la suscripción a la aplicación <strong><?php echo $app_name ?></strong></p>
<p>Código de Pin <?php echo $pin_code ?></p>
<p>Fecha de compra: <?php echo $pin_date ?></p>

<table cellpadding="4" cellspacing="4">
	<tr>
    	<th>Suscripción</th>
        <th>Precio</th>
    </tr>
    <tr>
    	<td><?php echo $app_name ?></td>
        <td>USD $<?php echo $pin_value ?></td>
    </tr>
</table>
<p>La recarga de Kréditos ha sido exitosa, desde ahora puede disfrutar del servicio.</p>
