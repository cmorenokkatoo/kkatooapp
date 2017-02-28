<?php
#le pedimos el archivo de conexion
require_once('connect.php');

#obtenemos el url a acortar
$url = $_GET["url"];

#creamos clave irrepetible
function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0) return $min;
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1;
        $bits = (int) $log + 1;
        $filter = (int) (1 << $bits) - 1;
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter;
        } while ($rnd >= $range);
        return $min + $rnd;
}

#generamos coodigo  a partir de la clave secreta
function getToken($length=32){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    for($i=0;$i<$length;$i++){
        $token .= $codeAlphabet[crypto_rand_secure(0,strlen($codeAlphabet))];
    }
    return $token;
}

$code = getToken(5);

#lo insertamos el url y el codigo en la tabla y lo imprimos el url
mysql_query("INSERT INTO redirects(code, url) VALUES('{$code}', '{$url}')", $con);
echo 'http://kka.to/b/?r='. $code;
