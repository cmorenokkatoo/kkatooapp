<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Suscripción para: <?php echo $app_data->title ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

		<!-- CSS >
        <link href="<?php echo base_url("assets/css/landings/landing.css"); ?>" rel="stylesheet" />
		<?php if(empty($app_data->css_route)){ ?>
			<link href="<?php echo base_url("assets/css/landings/fonocuentos-infantiles.css"); ?>" rel="stylesheet" />
        <?php }else{ ?>
        	<link href="<?php echo base_url("assets/css/landings/".$app_data->css_route); ?>" rel="stylesheet" />
        <?php } ?>
		<link href="<?php echo base_url("assets/css/dd.css"); ?>" rel="stylesheet" /-->
		
		<!-- FUENTES WEB -->
		<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed|Roboto' rel='stylesheet' type='text/css'>
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">

		<!-- COMPATIBILIDAD CON BROWSER VIEJOS -->
        <script src="<?php echo base_url("assets/js/vendor/modernizr-2.6.2.min.js"); ?>"></script>
		<style type="text/css">
        	.form-row-dyn{
				display:none;
			}
			#apagar{
				color: #6D0D0D;
				font-weight: bold;
				display: block;
				padding: 5px;
				margin: 7px 0px;
			}
        </style>
        
        <?php if(empty($app_data->css_route)): ?>
            <style type="text/css">
            /*
            font-family: 'Roboto Condensed', sans-serif;
            font-family: 'Roboto', sans-serif;
            */
            *
            {
            	margin: 0 auto;
            }
            body
            {
            	background: #f5f5f5;
            	font-family: 'Roboto', sans-serif;
            }

            #main-container
            {
            	width: 340px;
            	background: #fff;
            	-webkit-box-shadow: 1px 1px 10px 1px #C7C7C7;
				box-shadow: 1px 1px 10px 1px #C7C7C7;
				margin-top: 20px;
				padding: 10px 0px;
				border-top: 5px solid #333;
            }

            hr
            {
            	height: 0px;
            	border: 2px dashed #f0f0f0;
            	width: 80%;
            	margin: 15px auto;
            }

            #extra-data-container, #available-kredits
            {
            	text-align: justify;
            	padding: 15px;
            }

            .txt-field, .combo-field, .dyn-combo-field
            {
            	margin: 10px;
            	padding: 10px 5px;
            	width: 90%;
				border: 1px solid #e8e8e8;
				-webkit-border-radius: 2px;
				border-radius: 2px;
				font-family: 'Roboto', sans-serif;
				color: #777;

            }

            .subtitulo
            {
            	margin-bottom: 10px;
            }

            .combo-field, .dyn-combo-field
            {
            	width: 93%;
            }

            #susc-paypal, #susc-pin
            {
            	margin-top: 10px;
            	padding: 15px;
            	border: 0px;
            	background: #FCBA41;
            	-webkit-border-radius: 5px;
				border-radius: 5px;
            }

            #susc-paypal:hover, #susc-pin:hover
            {
            	background: #DBA901;
            	cursor: pointer;
            }

            #available-kredits
            {
            	text-align: left;
            }

            .HowManyCredits
            {
            	background: #f4f4f4;
            	padding: 8px;
            	color: green;
            	width: 15px;
            	font-weight: 900;
            	-webkit-border-radius: 5px;
				border-radius: 5px;
            }

            .mensajes
            {
            	width: 100%;
            }

            .exito
            {
            	background: #fff;
            	padding: 25px 0px;
            	color: green;

            }

            .titulo-mensaje, .mensaje
            {
            	padding: 0px 10px;
            }



            </style>
        <?php endif; ?>
        
	</head>
<body>
<?php
	$this->load->view('globales/mensajes'); 
?>
<!--
 <?php //print_r($fields); ?>
-->

		<div id="main-container">
			<div id="main-content">
                
                <!--<?php if($app_data->special == 1): ?>
                	<?php if(!empty($app_data->titulo_html)){ 
                        print $app_data->titulo_html;
                    }else{ ?>
                    
                    <?php } ?>
                    <?php if(!empty($app_data->slogan_html)){ 
                        print $app_data->slogan_html;
                    }else{ ?>
                    
                    <?php } ?>
                <?php else: ?>
										<h3 class="title">
                    	<?php if(!empty($app_data->titulo_html)){ 
                           //print $app_data->titulo_html;
                        }else{ ?>
                        
                        <?php } ?>
                    </h3>
                    <h4 class="slogan">
                    <?php if(!empty($app_data->slogan_html)){ 
							print $app_data->slogan_html;
						}else{ ?>
						
						<?php } ?>
                    </h4>
				<?php endif; ?>-->

				<div id="extra-data-container">
					<p class="subtitulo">Para hacer efectiva tu suscripción, necesitamos algunos datos adicionales:</p>

						<?php 
                            $hidden = array('app' => $app_data->id);
                            $attributes = array('name' => 'landing_edit_extra_data', 'id' => 'landing_edit_extra_data');
                            echo form_open('landing/save_contact_extra_data', $attributes, $hidden);
							$attlabel = array('class' => 'dyn-label');
							if(!empty($fields)){
								foreach($fields as $field):
								?>
								<div class="form-row-dyn">
								<?php
									echo form_label($field->fields->name,$field->fields->name_fields, $attlabel);
									switch ($field->fields->tipo) {
										case 1:
											echo form_input(array('type'=> 'text', 'name'=> $field->fields->name_fields, 'id'=> $field->fields->name_fields,'value'=> $field->contact_fields->valor, 'class'=>'dyn-number-field', 'end'=>':'));
										break;
										case 2:
											echo form_input(array('type'=> 'text', 'name'=> $field->fields->name_fields, 'id'=> $field->fields->name_fields,'value'=> $field->contact_fields->valor, 'class'=>'dyn-txt-field', 'end'=>':'));
										break;
										case 3:
											echo form_input(array('type'=> 'text', 'name'=> $field->fields->name_fields, 'id'=> $field->fields->name_fields,'value'=> $field->contact_fields->valor, 'class'=>'dyn-date-field', 'end'=>':'));
										break;
										case 4:
											echo form_dropdown($field->fields->name_fields, json_decode($field->fields->default), $field->contact_fields->valor, 'id="'.$field->fields->name_fields.'" class="dyn-combo-field"');
										break;
									}
									echo "</div>";
								endforeach;
							}
                            //PAQUETES
                            $packages = form_dropdown('cbo_packages_pp', $packages, '', 'class="dyn-combo-field"');
                        ?>
 
                        <div class='form-row'><br>
                            <label for="txt-name-pp"><strong><i class="fa fa-share-square-o"></i> Nombre Completo:</strong></label><br>
                            <input type="text" name="txt_name_pp" id="txt-name-pp" class="txt-field" value="<?php echo $contact_data->name_payment; ?>">
                        </div>
						<input type="hidden" name="app" value="<?php echo $app_data->id ?>">
						<div class='form-row'>
								<label for="txt-mail-pp"><strong><i class="fa fa-share-square-o"></i> Correo Electrónico:</strong></label><br>
								<input type="text" name="txt_mail_pp" id="txt-mail-pp" class="txt-field" value="<?php echo $contact_data->email_payment; ?>">
						</div>
						<hr>
						<p class="subtitulo"><strong><i class="fa fa-share-square-o"></i> DATOS DE PAGO:</strong></p>
						
						<div class='form-row'>
							<label for="cbo-payment-pp" class="space"><i class="fa fa-angle-double-right"></i> Método</label><br>
							<select name="cbo_payment_pp" id="cbo-payment-pp" class="combo-field">
								<option value="0" selected="selected">-- Através de --</option>
								<option value="paypal">PAYPAL</option>
								<option value="pagosonline">PAYU LATAM</option>
                                <option value="pin">NÚMERO PIN</option>
							</select>
						</div>
						<div class="form-row-dyn"><!-- ESTE ES EL DIV PARA ELEGIR PAQUETE -->
                        <?php
                            echo form_label('<i class="fa fa-angle-double-right"></i> Paquete<br>', 'cbo_packages_pp', $attlabel);
                            echo $packages;
                        ?>
                            <span id="apagar"><!--USD $<?php //echo $precio_1_paquete; ?>--></span>                        
                       	</div><!-- FIN DIV PARA ELEGIR PAQUETE -->

						<div id="paypal_options" style="display:" class='payment-options paypal_options pagosonline_options'>
							<!--<div class='form-row'>
								<label for="txt-mail-pp">Email</label>
								<input type="text" name="txt_mail_pp" id="txt-mail-pp" class="txt-field" value="<?php echo $contact_data->email_payment; ?>">
							</div>-->
							<div class='form-row'>
								<label for="txt-address-pp"><i class="fa fa-angle-double-right"></i> Dirección</label><br>
								<input type="text" name="txt_address_pp" id="txt-address-pp" class="txt-field" value="<?php echo $contact_data->address_payment; ?>">
							</div>
							<div class='form-row'>
								<label for="txt-phone-pp"><i class="fa fa-angle-double-right"></i> Teléfono</label><br>
								<input type="text" name="txt_phone_pp" id="txt-phone-pp" class="txt-field" value="<?php echo $contact_data->phone_payment; ?>">
							</div>
							<div class='form-row'>
								<label for="cbo-country-pp"><i class="fa fa-angle-double-right"></i> País</label><br>
								<?
								$country = form_dropdown('cbo_country_pp', $country, $contact_data->country_payment, 'id="cbo_country_pp" class="combo-field"');
								echo $country;
								?>
							</div>
							<div class='form-row'>
								<label for="cbo-city-pp"><i class="fa fa-angle-double-right"></i> Ciudad</label><br>
								<?
								$city = form_dropdown('cbo_city_pp', $city, $contact_data->city_payment, 'id="cbo_city_pp" class="combo-field"');
								echo $city;
								?>
							</div>
							<div class='form-row'>
								<input type="submit" name="mysubmit" value="FINALIZAR SUSCRIPCIÓN" id="susc-paypal" >
							</div>
	 					</div> <!-- paypal-options -->

	 					<!--div id="consignacion_options"  style="display:"  class='payment-options consignacion_options'>
	 						<p>Instrucciones para suscribirse vía consignación bancaria: </p>
	 						<ol>
		 						<li>Consigne o transfiera la cifra del paquete escogido a la cuenta de ahorros # 004-82696341 de Bancolombia, a nombre de Fonomarketing S.A.S.</li>
								<li>Scanee el documento de consignación.</li>
								<li>Envíe un correo electrónico a info@kkatoo.com adjuntando la imagen del comprobante de pago.</li>
								<li>Llame al teléfono 57(4) 444-1623 y solicite la activación de su suscripción.</li>
								<li>Si desea inscribir la cuenta para realizar transferencias vía web, nuestro NIT es 900.519.795-0</li>
							</ol>
							<div class='form-row'>
								<input type="submit" name="mysubmit" value="FINALIZAR SUSCRIPCIÓN" id="susc-consignacion" >
							</div>
	 					</div-->

	 					<div id="pin_options"  style="display:"  class='payment-options pin_options'>
	 						<p>Por favor, escribe el número de PIN que aparece en tu tarjeta: </p>
	 						<div class='form-row'>
								<!--label for="txt-name-pp">PIN</label-->
								<input type="text" name="txt_pin" id="txt-pin" class="txt-field" value="" placeholder="NzdUX1xt">
							</div>

							<div class='form-row'>
								<input type="submit" name="mysubmit" value="FINALIZAR SUSCRIPCIÓN" id="susc-pin" >
							</div>
	 					</div>

						<input name="id_contact" type="hidden" value="<?php echo $contact_data->id ?>" />
                        <input name="id_wapp" type="hidden" value="<?php echo  $app_data->id ?>" />
						<?
						echo form_close();
						?>
                        
				</div> <!-- extra-data-container -->
				<div id="available-kredits" style="display:">
					Actualmente tienes <span class="HowManyCredits"><?php echo $contact_data->credits ?></span> créditos disponibles.
				</div>			
				<div id="img2-container">
					<?php if(!empty($app_data->css_route) && !empty($app_data->secondary_img_html)): ?>
						<img src="<?php print base_url('public/'.$app_data->secondary_img_html); ?>" />
					<?php elseif($app_data->special==0 && !empty($app_data->secondary_img_html)): ?>
            <img src="<?php print base_url('timthumb.php?src='.base_url('public/'.$app_data->secondary_img_html).'&w=480&h=480&zc=1'); ?>" />
          <?php endif; ?>
				</div>
			</div> <!-- main-content -->
		</div> <!-- main-container -->
		<!-- OCULTO
        <?php if($app_data->special == 1): ?>
            <div id="serviciocliente">
                Servicio al cliente: 444-1263 de Medellín, <a href="mailto:info@kkatoo.com">info@kkatoo.com</a>
            </div>
        <?php else: ?>
        	<div id="footer">
				Servicio al cliente: 444-1263 de Medellín, <a href="mailto:info@kkatoo.com">info@kkatoo.com</a>
            </div>
        <?php endif; ?>
		-->
        <!-- MODAL PARA LOS PAGOS -->
 		<?php if($this->session->flashdata("paymade")) $this->load->view('globales/pay_made'); ?>
        
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="<?php echo base_url("assets/js/landing.js"); ?>"></script>

<script>
 $('.info_pago').find("a").on('click', function(event){
			$(this).parent().parent().remove();
			event.preventDefault();
		});

 <?php if($contact_data->country_payment != 0 && $contact_data->city_payment != 0): ?>
 cambiarCiudadEditar(<?php echo $contact_data->country_payment; ?>,<?php echo $contact_data->city_payment; ?>);
 $("#cbo_country_pp").val(<?php echo $contact_data->country_payment; ?>);
<?php else: ?>
	cambiarCiudad($('#cbo_country_pp').val());
<?php endif; ?>
</script>

</body>
</html>