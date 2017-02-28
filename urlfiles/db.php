<?php

    $dbhost = "kka.to";
    $dbuser = "kkatoo_produser";
    $dbpass = "0yWS6DqBBS7c";
    $dbname = "kkatoo_principal";
    // $Conexion = new PDO("mysql:host=kka.to;dbname=kkatoo_produccion","kkatoo_produser","0yWS6DqBBS7c");
    $dbh = mysql_connect($dbhost,$dbuser, $dbpass);
    mysql_select_db("$dbname") or die ("Could not connect to database");
?>
