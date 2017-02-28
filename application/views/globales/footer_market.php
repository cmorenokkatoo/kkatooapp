 		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.3.min.js"><\/script>')</script>

        <script src="<?php echo base_url("assets/build/mediaelement-and-player.min.js"); ?>"></script>
        <link rel="stylesheet" href="<?php echo base_url("assets/build/mediaelementplayer.min.css"); ?>" />

        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>

        <script src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>

        <script src="<?php echo base_url("assets/js/carousel.js"); ?>"></script>
        <script src="<?php echo base_url("assets/js/plugins.js"); ?>"></script>
        <script src="<?php echo base_url("assets/js/main.js"); ?>"></script>
		<script src="<?php echo base_url("assets/js/select2.js"); ?>"></script>
		<script src="<?php echo base_url("assets/js/init_steps.js"); ?>"></script>
		<script src="<?php echo base_url("assets/js/jquery.scrollTo-min.js"); ?>"></script>


        <script type="text/javascript">
			$(function(){
				$("#menu-tab-search").tabify();
			<?php

			if(isset($ini)): ?>
				$("#menu-tab").tabify();
				// $('#slim-scroll').slimScroll({
				// 	height: '120px',
				// 	railVisible: true,
    //                 alwaysVisible: true
				// });

			<?php else: ?>
				// $('#slim-scroll2').slimScroll({
				// 	height: '414px',
				// 	railVisible: true,
    //                 alwaysVisible: true
				// });

				// $('.interna2-int').slimScroll({
				// 	height: '390px',
				// 	railVisible: true,
    //                 alwaysVisible: false
				// });
			<?php endif; ?>

			$("#btn-add-app").tooltip("toogle");


			});

        </script>        
    </body>
</html>
