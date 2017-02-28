<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $app_data->title ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

		<!-- CSS -->
		<?php if(empty($app_data->css_route)){ ?>
			<link href="<?php echo base_url("assets/css/landings/fonocuentos-infantiles.css"); ?>" rel="stylesheet" />
        <?php }else{ ?>
        	<link href="<?php echo base_url("assets/css/landings/".$app_data->css_route); ?>" rel="stylesheet" />
        <?php } ?>
        <link href="<?php echo base_url("assets/css/landings/landing.css"); ?>" rel="stylesheet" />
		
		<!-- FUENTES WEB -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,300,400,600,700" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Bubblegum+Sans' rel='stylesheet' type='text/css'>
		
		<!-- COMPATIBILIDAD CON BROWSER VIEJOS -->
        <script src="<?php echo base_url("assets/js/vendor/modernizr-2.6.2.min.js"); ?>"></script>

		<?php if(empty($app_data->css_route)): ?>
            <!-- ESPECIAL CSS PARA LAS APLICACIONES GENERICAS -->
            <style type="text/css">
                #main-content{
                    background: url("<?php echo base_url('public/img/apps/1000x650.jpg') ?>") no-repeat;
                    padding:55px 40px;
                    width:1000px;
                }
                #img2-container {
					width: auto;
					height:auto;
					position: absolute;
					right: 0px;
					left: auto;
					top: 168px;
				}
                #desc-container{
                    margin:38px 0 0 605px;
                    font-size: 14px;
                    height:305px;
                }
                h3.title{
                    margin: 0;
                    padding: 0;
                    font-size: 55px;
                    line-height: 55px;
                    color: #FBAE16;
                    text-shadow: 0px 0px 5px #292929;
                }
                h4.slogan{
                    margin: 0;
                    padding: 0;
                    font-size: 23px;
                    line-height: 23px;
                    margin-top: 3px;
                    color: #FBAE16;
                    text-shadow: 0px 0px 5px #292929;
                }
                #footer{
                    width: 999px;
					height: 24px;
					background-color: rgba(255,255,255,0.3);
					position: absolute;
					top: 625px;
					left: 175px;
					text-align: right;
					padding-right: 10px;
					font-size: 15px;
					color: #111;
                }
            </style>
        <?php endif; ?>

	</head>
<body>
<?php
	$this->load->view('globales/mensajes');
	$this->lang->load('landing'); 
?>
<!--
 <?php //print_r($fields); ?>
-->

		<div id="main-container">
			<div id="main-content">
                
                <?php if($app_data->special == 1): ?>
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
                            print $app_data->titulo_html;
                        }else{ ?>
                        
                        <?php } ?>
                    </h3>
                    <h4 class="slogan">
                    <?php if(!empty($app_data->slogan_html)){ 
							print $app_data->slogan_html;
						}else{ ?>
						
						<?php } ?>
                    </h4>
				<?php endif; ?>

				<div id="thanks-container">
					<p><?php echo $this->lang->line('thks_message'); ?></p>
					<?php if($where_comes=="consignment"): ?><p><?php echo $this->lang->line('consignacion'); ?></p><?php endif; ?>
                    <?php if($where_comes=="paypal" || $where_comes == "pagosonline"): ?><p><?php echo $this->lang->line('paypal'); ?></p><?php endif; ?>
					<?php if($where_comes=="pin"): ?><p><?php echo $this->lang->line('pin'); ?></p><?php endif; ?>
				</div> <!-- thanks-container -->
				
				<div id="img2-container">
					<?php if(!empty($app_data->css_route)): ?>
						<img src="<?php print base_url('public/'.$app_data->secondary_img_html); ?>" />
					<?php elseif($app_data->special==0): ?>
                    	<img src="<?php print base_url('timthumb.php?src='.base_url('public/'.$app_data->secondary_img_html).'&w=480&h=480&zc=1'); ?>" />
                    <?php endif; ?>
				</div>
			</div> <!-- main-content -->
		</div> <!-- main-container -->
        <?php if($app_data->special == 0): ?>
        	<div id="footer">
				<!--Servicio al cliente: 444-1263 de Medellín, <a href="mailto:info@kkatoo.com">info@kkatoo.com</a-->
            </div>
        <?php endif; ?>

<p id="apagar" style="color:#F00;"></p>
<!-- ALL SESSION DATA -->
<!-- <?php //print_r($this->session->all_userdata()); ?> -->
        <!-- MODAL PARA LOS PAGOS -->
 		<?php if($this->session->flashdata("paymade")) $this->load->view('globales/pay_made'); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript">
	 $('.info_pago').find("a").on('click', function(event){
			$(this).parent().parent().remove();
			event.preventDefault();
		});
</script>
<script src="<?php echo base_url("assets/js/landing.js"); ?>"></script>
</body>
</html>