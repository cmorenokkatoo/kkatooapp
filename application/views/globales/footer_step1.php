      	<!-- Producción -->
      	<!-- Modal para esperar -->
		<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
		    <h3 id="myModalLabel">Cargando Datos</h3>
		  </div>
		  <div class="modal-body">
		    <p>Por favor espere?</p>
		  </div>
		  <div class="modal-footer">
		    <!--<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>-->
		  </div>
		</div>
		<!-- #Modal para esperar -->
		
		<!-- Modal para mensajes -->
		<div id="mensajes" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="mensajesLabel">Error</h3>
		  </div>
		  <div class="modal-body">
		    <p><div class="alert alert-error mensajesdeerror">
			 Error al enviar los datos
			</div></p>
		  </div>
		  <div class="modal-footer">
		    <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
		  </div>
		</div>
		<!-- #Modal para mensajes -->
        
        <!-- MODAL PARA LOS PAGOS -->
 		<?php if($this->session->flashdata("paymade")) $this->load->view('globales/pay_made'); ?>
	
      <!-- Le javascript -->
      

      
      <script type="text/javascript">
				window.totalPages = <?php echo (!empty($totalC))?$totalC:1; ?>;
      	window.page = (window.location.hash!='')?String(window.location.hash).replace('#page-', ''): 1;;
      
      	<!-- PAGIONATIONS -->
				var pagination_ul = $('.pagination_no_selected ul');
				var numItemsToShow = <?php echo PAGINATION; ?>;
				var numItems = window.totalPages;
				var numPages = Math.ceil(numItems/numItemsToShow);
				
				function redraw_pagination(){
					numItemsToShow = <?php echo PAGINATION; ?>;
					numItems = window.totalPages;
					numPages = Math.ceil(numItems/numItemsToShow);
					
					redraw_one();
				}
				
				function redraw_one(){
					pagination_ul.pagination('destroy');
					made_pagination();
				}
				
				function made_pagination(){
					
					pagination_ul.pagination({
						items: numItems,
						itemsOnPage: numItemsToShow,
						currentPage: window.page,
						onPageClick: function(pageNumber, event) {
							window.page = pageNumber;
							get_contacts(true);
						}
					});
				}
				
				made_pagination();
				var interval = setInterval(function(){
					if(typeof get_contacts == 'function'){
						get_contacts(false);
						clearInterval(interval);
					}
				}, 200);
	
				
				window.totalPagesS = <?php echo (!empty($totalCC))?$totalCC:1; ?>;
      	window.pageS = 1;
				//PAGINATION PARA LOS CONTACTOS.
			
				var pagination_ul_selected = $('.pagination_selected ul');
				var numItemsToShowS = <?php echo PAGINATION; ?>;;
				var numItemsS = window.totalPagesS;
				var numPagesS = Math.ceil(numItems/numItemsToShow);
				
				
				function redraw_paginationS(){
					numItemsToShowS = <?php echo PAGINATION; ?>;
					numItemsS = window.totalPagesS;
					numPagesS = Math.ceil(numItemsS/numItemsToShowS);
					
					redraw_oneS();
				}
				
				function redraw_oneS(){
					pagination_ul_selected.pagination('destroy');
					made_paginationS();
				}
				
				function made_paginationS(){
					pagination_ul_selected.pagination({
						items: numItemsS,
						itemsOnPage: numItemsToShowS,
						currentPage: window.pageS,
						onPageClick: function(pageNumber, event) {
							window.pageS = pageNumber;
							get_contacts_campaign(true);
						}
					});
				}
				
				made_paginationS();
      </script>
      <script src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
      <script src="<?php echo base_url("assets/js/plugins.js"); ?>"></script>
      <script src="<?php echo base_url("assets/js/steps-ini.js"); ?>"></script>
      <script src="<?php echo base_url("assets/js/bootstrap-datepicker.js"); ?>"></script>
      <script>
				$('.info_pago').find("a").on('click', function(event){
					$(this).parent().parent().remove();
					event.preventDefault();
				});
				
				 $( ".calendario" ).datepicker({ format: "dd/mm/yyyy" });
					 /*$('#begin-date').datepicker().on('changeDate', function(ev){
						 $('#begin-date').datepicker('hide');
					});   */   	
				var id_campaign_var = <?php echo isset($id_campaign)? $id_campaign : ""; ?>;
      </script>
   </body> 

</html>