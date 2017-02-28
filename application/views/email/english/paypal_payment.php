<h3>Gracias!.</h3>
<h4><?php echo $pay_name; ?></h4>
<p>Haz realizado una recarga de Créditos</p>
<p><strong>Código de compra:</strong> <?php echo $id_pay ?></p>
<p><strong>Fecha de compra:</strong> <?php echo $date ?></p>
<p><strong>Estado: </strong><?php echo ($state==1)? "Transaccion exitosa":"La transacción no se completó"; ?></p>
<p><strong>Valor: </strong><?php echo $valor; ?></p>
<p><strong>Moneda: </strong><?php echo $moneda; ?></p>
<?php if($state == 1): ?>
<p>La recarga de Kréditos ha sido exitosa, desde ahora puede disfrutar de el servicio.</p>
<?php endif; ?>