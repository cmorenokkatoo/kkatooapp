<?php  
    //Escribe los errores en un archivo llamado errorsFile.log 
    function errorLog($errorText){ 
        $fd = fopen('errorsFile.log','a'); 
        fwrite($fd,"[".date("r")."] Error: $errorText\n"); 
        fclose($fd); 
    } 
    //Los errores de php los escribe en el .log 
    set_error_handler('errorLog'); 
?>