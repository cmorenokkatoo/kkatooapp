<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript">
            $.post(
                        "http://mmv.kka.to/api/makeCall/format/json",
                        // Crear campaña /createCampaign/format/json
                        // {'email':'cfmoreno@kkatoo.com','password':'','id_wapp':'107','user_id':'570','fecha':'2016-11-29','hora':'13','minuto':'40','name':'Prueba API 2016','SMS':'0'},
                        // Validar que usuario existe /validateUser/format/json
                        // {'email':'','password':'','uri_app':'eventos'},
                        // Crear llamada /makeCall/format/json
                        {'email':'cmoreno@kkatoo.com','password':'12345','uri_app':'publicidad','mensaje':'Esta es una llamada desde el API de la plataforma','SMS':'0','timeout':'35', 'id_campaign':'5965','fecha':'2017-02-21','hora':'14','minuto':'55','voice':'IVONA 2 Miguel','phone':'3134327230','pais':'57','user_id':'570','id_wapp':'107'},
                        // Entrar a pasarela de pagos /viewPayment/format/json
                        // {'email':'cfmoreno@kkatoo.com','password':'','uri_app':'pymesplus'},
                        //Ver el detalle de la campaña
                        // {'email':'','password':'','user_id':'','id_campaign':''},
                        function(data)
                        {
                            console.log(data);
                        },
                        "json");
</script>
