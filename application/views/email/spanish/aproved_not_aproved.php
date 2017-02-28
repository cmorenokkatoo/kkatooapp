Hola <?php echo $name; ?>!
<p>El estado de su aplicación <strong><a href="<?php echo base_url('wizard/'.$id); ?>"><?php echo $app_title ?></a></strong> ha cambiado</p>
<?php if($state == 1): ?>
<p>Ahora se encuentra en estado <strong>Aprobada</strong>, desde ahora puede hacer uso de esta.</p>
<?php else: ?>
<p>Se encuentra en estado <strong>No Aprobada</strong>, no podrá hacer uso de esta hasta que no esté aprobada.</p>
<?php endif; ?>