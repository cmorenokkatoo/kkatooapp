<h3>Gestion de Ganancias!.</h3>
<p>Hola <strong><?php echo $name ?></strong>!, ud a realizado una gestión de pago de ganancias de <strong>USD $<?php echo $amount ?></strong>, si el metodo elegido fue por <strong>Transacción Bancaria</strong>, pronto estaremos en contacto con usted para realizar la transacción y actualizar el estado de este pago de <strong>Pendiente</strong> a <strong>Pagado</strong></p>

<h3>Detalle del Pago</h3>
<p><strong>ID:</strong> <?php echo $id ?></p>
<p><strong>Tipo:</strong> <?php echo $tipo ?></p>
<?php if($tipo_real == TA || $tipo_real == TU): ?>
<p><strong>Entidad:</strong> <?php echo $entidad ?></p>
<p><strong>Tipo de Cuenta:</strong> <?php echo $tipo_de_cuenta ?></p>
<p><strong>Número de Cuenta:</strong> <?php echo $numero_de_cuenta ?></p>
<?php endif; ?>
<p><strong>Valor Redimido:</strong> <?php echo $amount ?></p>
<p><strong>Estado:</strong> <?php echo $estado ?></p>
<p><strong>Fecha:</strong> <?php echo $date ?></p>