<?php 
    $this->load->view('globales/head_market');
	$this->load->view('globales/mensajes'); 
    $this->load->view('globales/navbar_market');
    // $this->load->view('globales/newnavbar');
?>

 <div id='main'>
 
        <!-- Market area
        ================================================== -->
            <section id="market-area">
                <!-- <h1>Realiza llamadas automáticas o envía mensajes de texto en instantes.</h1> -->
                <!-- <h1>Elige qué aplicación deseas usar</h1> -->
                <!-- <span>Tel. +57 4 444 1263 - 57 3187072211 - <a href="mailto:info@kkatoo.com?subject=Desde Marketplace">info@kkatoo.com</a></span>
            	<h3>Market de Aplicaciones</h3> -->

                <div id="mkt-app-list" class="seachlisting">
                <?php if(!empty($apps)): ?> 
                <?php foreach ($apps as $item) { ?>
                 <div class="mkt-app-ficha">
                 		<a class="app_link" href="<?php echo base_url('apps/'.$item->uri); ?>">
                        <!--<a class="app_link" href="javascript:;" data-id="<?php echo $item->id; ?>"></a>-->
                         <img src="<?php echo base_url("public/".$item->image); ?>" class="mkt-app-foto">
                        <div class="mkt-app-datos">
                            <div class="mkt-app-overlay"></div>
                            <!-- <div class="mkt-app-numcomments"><?php echo $item->total_comentario; ?> <i class="material-icons">chat_bubble</i></div> -->
                            <div class="mkt-app-rating"><?php echo $item->cuantos != 0 ?  round($item->points/$item->cuantos,0): ""; ?><i class="material-icons">star</i></div>
                        </div>
                        <h4 style="text-align: left !important;"><?php echo $item->title ?></h4>
                        </a>
                    </div>
    			<?php }  ?>
    			<?php endif; ?>
                   
                </div>
            </section>
            <?php 
	        $query = $this->input->get("q");
	        if(!$this->input->get("p")){
		        $siguiente = 1;
	        }else{
		        $siguiente = $this->input->get("p")+1;
	        }
	        
        ?>
 </div>
