<?php
    //Verificamos que el referer sea de nuestro sitio
    if(stripos($_SERVER['HTTP_REFERER'], 'kka.to/a/')===false){
        die('url no valida!');
    }
    @set_time_limit();
    //Agregamos db.php para el acceso a la BD
    include('db.php');

    //Verificamos que la url recibida no esté vacía
    if(trim($_REQUEST['url'])=="") die();

    //Esta funcion lo que hace es verificar si ya existe el sitio en nuestra BD, si existe nos regresa el hash, sino llama a la funcion _new para crear uno nuevo
    function _check($U){
        $SQL = @mysql_query("SELECT * FROM `acortador` WHERE url='".$U."'");
        $ROW = @mysql_fetch_array($SQL);

        if($ROW['char']!="") return $ROW['char']; else return _generateRandomString($U);
    }

    //Esta funcion creara un hash nuevo.
    //Lo que hacemos es codificar en MD5 la url (tenemos un hash unico) y luego obtenemos lugares casi al azar
    //para darle mas aletoriedad al hash

    function _generateRandomString($U) {
    $length = 4;
    $domain = 'kka.to/a/';
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    $randomString2 = $domain.$randomString;
    @mysql_query("INSERT INTO `acortador` VALUES('".$randomString2."','".$U."','0','".$_SERVER['REMOTE_ADDR']."');");
    return $randomString;
}
    // function _new($U){
    //   $domain = 'kka.to/a/';
    //     $Z = md5($U);
    //     $A = substr($Z,0,2); //Obtenemos los primeros 2 caracteres del hash
    //     $B = substr($Z,16,2); //Obtenemos 2 caracteres a partir del caracter 16
    //     $C = substr($Z,30,2); //Obtenemos 2 caracteres a partir del caracter 30
    //     $D = substr($Z,23,1); //Obtenemos 1 caracter a partir del caracter 23
    //     $zCode = $A.$B.$C.$D; //Juntamos todo
    //     $zCode2 = $domain.$zCode;
    //
    //     //Guardamos el codigo, la url, la cantiad de visitas y la IP (por si necesitamos banear IP)
    //     @mysql_query("INSERT INTO `acortador` VALUES('".$zCode2."','".$U."','0','".$_SERVER['REMOTE_ADDR']."');");
    //
    //     return $zCode;
    // }

    //Verificamos que no existe la url de nuestro sitio en la url que quieren acortar (evitamos un anidado de url)
    if(!stristr($_REQUEST['url'],'http://kka.to/a/')){
        if(validateURL($_REQUEST['url'])){ echo 'http://kka.to/a/'._check(trim($_REQUEST['url'])); }else{ echo 'url no valida!'; }
    }else
        echo $_REQUEST['url']; //Si encontramos nuestra url en la url para acortar, le regresamos la url :)


    //Verificamos que sea una url valida :) (otro chekeo aparte del js)
    function validateURL($url){
        $pattern = "|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i";
        return preg_match($pattern, $url);
    }
?>
