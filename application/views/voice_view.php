<html>
	<head>
		<link href="<?php echo base_url('assets/build/mediaelementplayer.min.css')?>" rel="stylesheet">
		<link href="<?php echo base_url('assets/css/mediaelement/voices.css')?>" rel="stylesheet">
	</head>
	<body>
		<table  id="voices-main-content">
			<tr>
				<th>Voz</th>
				<th>Idioma</th>
				<th>Play para escuchar</th>
			</tr>
			<?php
				function retornarStringValido($cadena) 
				{ 
				    $final = $cadena;
				    $b     = array("á","é","í","ó","ú","ä","ë","ï","ö","ü","à","è","ì","ò","ù","ñ"," ",",",".",";",":","¡","!","¿","?",'"'); 
				    $c     = array("a","e","i","o","u","a","e","i","o","u","a","e","i","o","u","n","","","","","","","","","",''); 
				    $final = str_replace($b,$c,$final); 
				    return $final; 
				}  

				foreach($voice as $voi)
				{
			?>
					<tr>
						<td><?php echo str_replace("IVONA 2 ","",$voi->name); ?></td>
						<td class="center"><?php echo str_replace("IVONA 2 ","",$voi->idioma); ?></td>
						<td><audio src="<?php  echo base_url('public/audios/voices/') .'/'. retornarStringValido(str_replace('IVONA 2 ','',$voi->name)). '_'.str_replace('IVONA 2 ','',$voi->idioma).'.mp3'; ?>"></audio></td>
		

						<!-- <td><?php //echo str_replace("IVONA 2 ","",$voi->url); ?></td> -->
						
					</tr>
			<?php
				
				}
			?>
		</table>	

     	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
      	<script src="<?php echo base_url('assets/build/mediaelement-and-player.min.js')?>"></script>
      	<script>
			// using jQuery
			$('audio').mediaelementplayer({
				audioWidth: 300,
				features: ['playpause','progress']

			});
		</script>
	
	</body>
</html>

