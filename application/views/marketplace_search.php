<?php 
    $this->load->view('globales/head_market');
	$this->load->view('globales/mensajes'); 
    $this->load->view('globales/navbar_market');
?>

 <div id='main'>
		
        <!-- Market area
        ================================================== -->
            <section id="market-area">
                <div id="mkt-app-list"  class="seachlisting" id="seachlisting">
                <?php if(isset($apps)): ?>
                <?php foreach ($apps as $item) { ?>
                  <article class="mkt-app-ficha">
                  		<a class="app_link" href="<?php echo base_url('landing/'.$item->uri); ?>">
                        <!--<a class="app_link" href="javascript:;" data-id="<?php echo $item->id; ?>"></a>-->
                        <img src="<?php echo base_url("public/".$item->image); ?>" class="mkt-app-foto">
                        <!--<div class="mkt-app-datos">
                            <div class="mkt-app-overlay"></div>
                            <div class="mkt-app-numcomments"><?php echo $item->total_comentario; ?> <i class="icon-comment"></i></div>
                            <div class="mkt-app-rating"><?php echo $item->cuantos != 0 ?  round($item->points/$item->cuantos,2): ""; ?> <i class="icon-star"></i></div>
                        </div>-->
                        <h4><?php echo $item->title ?></h4>
                        </a>
                    </article><!-- /#mkt-app-ficha -->
			<?php } ?>
			<?php endif; ?>
                   
                </div><!-- /#mkt-app-list -->
            </section><!-- /#market-area -->
         <?php 
	        //calculo del siguiente
	        $query = $this->input->get("q");
	        if(!$this->input->get("p")){
		        $siguiente = 1;
	        }else{
		        $siguiente = $this->input->get("p")+1;
	        }
	        
        ?>
        <div class="navigation" id="navigation_id">
        	<div class="next-posts alignleft">
        	<?php if(isset($apps)): ?> 
				<a href="<?php echo base_url("marketplace/search?q=".$query."&p=".$siguiente); ?>" >&laquo; Older Entries</a>
			<?php endif; ?>
			</div>
		</div>
        </div><!-- /#main -->
       

<!-- Fin  Informativo ================================================== -->
<?php
    $this->load->view('globales/footer_nav');
    $this->load->view('globales/footer_market');
?>
