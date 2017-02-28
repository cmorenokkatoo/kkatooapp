<?php
    @set_time_limit();
    include('db.php');
    include('noin.php');

    $SEC = new secure();
    $SEC->secureGlobals();

    //verificamos si el hash existe en nuestra base de datos
    $SQL = @mysql_query("SELECT * FROM `acortador` WHERE `char`='".trim($_GET['id'])."'");
    $ROW = @mysql_fetch_array($SQL);

    //Si existe redireccionamos
    if($ROW['url']!=""){
        _suma($_GET['id']);
        header ('HTTP/1.1 301 Moved Permanently'); //esta cabecera si queremos la agregamos sino no hay problema :)
        header('location: '.$ROW['url']);
        die();
    }else{
        //sino existe el hash en nuestra BD redireccionamos al index de nuestro sitio
        header('location: http://kka.to/a/');
        die();
    }

    //funcion encargada de sumar una visita al hash
    function _suma($U){
        mysql_query("UPDATE `acortador` SET stat=(stat+1) WHERE `char`='".$U."'");
    }
?>
