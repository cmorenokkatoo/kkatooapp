<?php
  // [MENTION]session[/MENTION]_start();
    if($_GET['adm']=="" && $_SESSION['pepe']!="1"){
        die();
    }else{
        $_SESSION['pepe']=1;
    }

    include('db.php');

    if($_GET['del']!="") mysql_query("DELETE FROM acortador WHERE `char`='".$_GET['del']."'");

    if($_GET['order']==""){
        $SQL = mysql_query("SELECT * FROM acortador WHERE 1");
    }else{
        $SQL = mysql_query("SELECT * FROM acortador WHERE 1 ORDER BY stat DESC");
    }
    echo '<ul>';
    while($ROW = mysql_fetch_array($SQL)){
      echo "<table style='text-align:center;'><tbody><tr><td style='border: 1px solid #555; padding: 2em;'>URL Corta</td><td style='border: 1px solid #555; padding: 2em;'>URL Larga</td><td style='border: 1px solid #555; padding: 2em;'>Eliminar URL</td></tr>";
        echo '<tr><td style="border: 1px solid #555; padding: 2em;">'.$ROW['char'].'</td><td style="border: 1px solid #555; padding: 2em;"><a href="'.$ROW['url'].'" target="_blank">'.$ROW['url'].'</a></td><td style="border: 1px solid #555; padding: 2em;"><a href="?del='.$ROW['char'].'">DEL</a></td></tr></tbody></table>';
    }
?>
