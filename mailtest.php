<?php
$from = "shannon@hivelocity.net";
$subject = "Email Test";
$message .= "This is a test";
mail('shannon@hivelocity.net',$subject,$message,$from," -f shannon@hivelocity.net");
?>
