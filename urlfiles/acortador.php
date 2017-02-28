<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script type="text/javascript">

	//Esta funcion es la encargada de ver que sea una url valida.
	function validateUrl(d){
        	matches = d.match(regex);
		if (!matches) return null;
		return matches
	}

    	var regex = RegExp("((?:(https?|ftp|itms)://)?(?:(?:[^\\s\\!\"\\#\\$\\%\\&\\'\\(\\)\\*\\+\\,\\.\\/\\:\\;\\<\\=\\>\\?\\@\\\\[\\]\\^\\_`\\{\\|\\}\\~]+\\.)+(?:aero|arpa|asia|biz|cat|com|coop|edu|gov|info|int|jobs|mil|mobi|museum|name|net|org|pro|tel|travel|local|example|invalid|test|mp3|\u0645\u0635\u0631|\u0440\u0444|\u0627\u0644\u0633\u0639\u0648\u062f\u064a\u0629|\u0627\u0645\u0627\u0631\u0627\u062a|xn--wgbh1c|xn--p1ai|xn--mgberp4a5d4ar|xn--mgbaam7a8h|\u4e2d\u56fd|\u4e2d\u570b|\u9999\u6e2f|\u0627\u0644\u0627\u0631\u062f\u0646|\u0641\u0644\u0633\u0637\u064a\u0646|\u0642\u0637\u0631|\u0dbd\u0d82\u0d9a\u0dcf|\u0b87\u0bb2\u0b99\u0bcd\u0b95\u0bc8|\u53f0\u7063|\u53f0\u6e7e|\u0e44\u0e17\u0e22|\u062a\u0648\u0646\u0633|xn--fiqs8S|xn--fiqz9S|xn--j6w193g|xn--mgbayh7gpa|xn--ygbi2ammx|xn--wgbl6a|xn--fzc2c9e2c|xn--xkc2al3hye2a|xn--kpry57d|xn--kprw13d|xn--o3cw4h|xn--pgbs0dh|\u0625\u062e\u062a\u0628\u0627\u0631|\u0622\u0632\u0645\u0627\u06cc\u0634\u06cc|\u6d4b\u8bd5|\u6e2c\u8a66|\u0438\u0441\u043f\u044b\u0442\u0430\u043d\u0438\u0435|\u092a\u0930\u0940\u0915\u094d\u0937\u093e|\u03b4\u03bf\u03ba\u03b9\u03bc\u03ae|\ud14c\uc2a4\ud2b8|\u05d8\u05e2\u05e1\u05d8|\u30c6\u30b9\u30c8|\u0baa\u0bb0\u0bbf\u0b9f\u0bcd\u0b9a\u0bc8|xn--kgbechtv|xn--hgbk6aj7f53bba|xn--0zwm56d|xn--g6w251d|xn--80akhbyknj4f|xn--11b5bs3a9aj6g|xn--jxalpdlp|xn--9t4b11yi5a|xn--deba0ad|xn--zckzah|xn--hlcj6aya9esc7a|[a-z]{2})(?::[0-9]+)?|(?:[0-9]{1,3}\\.){3}(?:[0-9]{1,3}))(?:\\/?[\\S]+)?)", "gi");



	function Save(){
		//esta funcion es llamada cuando el user hace click en el boton crear
	   if($('#url').val()!=""){ //verificamos que no este vacio el campo
		var _U = $('#url').val();
		if(validateUrl(_U)==_U){ //verificamos que sea una url valida
		//enviamos por ajax la url y acortador.php nos regresara un texto que la url no es valida o el hash acortador
		$.post("save.php", { url: _U },
			function(data){
			 $('#url').val(data);
			});
		}else{
			alert('url no valida!');
		}
	  }
	}
	</script>
	<style>
		/*.back{
			background: tomato;
			padding: .5em 2em;
			border-radius: 2px;
			color: white;
			margin: 1em;
		}*/
	</style>
	<center>
		<font size="2">Pega la URL del audio que deseas recortar:</font><br/>
		<textarea id="url"></textarea><br/>
		<a class="back" href="javascript:void(0);" onclick="Save();return false;">Generar nueva URL</a>
	</center>
