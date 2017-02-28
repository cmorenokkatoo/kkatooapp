<?php
#si existe el codigo para redireccionar
if(isset($_GET['r']))
{
#pedimos el archivo de conexion
require_once("connect.php");

#obtenemos el codigo
$code = $_GET["r"];

#lo buscamos en la base de datos
$result = mysql_query("SELECT * FROM redirects WHERE code='$code'", $con);

#si esta lo redireccionamos hacia al url de lo contrario lo mandamos al index
if($result) {
    while($row = mysql_fetch_array($result)) {
        header("Location: " . $row['url']);
    }
}
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kkatoo Acortador de URLs</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <meta charset="utf-8">
</head>
<style media="screen">
body{
text-align: center;
  background: #f7f7f7;
  overflow: hidden;
}
.form{
    margin: 0 auto;
}
  input[type=text]{
    margin: 2em;
    padding: .5em 2em;
    width: 350px;
    border: 1px solid #f2f2f2;
  }
  input[type=submit]{
    margin: .2em;
    background: steelblue;
    color: white;
    padding: .5em 2em;
    border: 0px;

  }
  table, th, td {
    border: 1px solid black;
}
.barra{
    min-width: 200px;
    text-align: left;
    color: black;
    margin: .3em;
}

#centro{
  margin: 10%;
}
</style>
<body>
  <div id="centro">
        <img src="http://kka.to/assets/img/logo_kkatoo_header.png" alt="Kkatoo Social Dialing">
      <div class="form">
          <form id="acortar" method="get" action="create_redirect.php">
           <input type="text" id="url" placeholder="Inserta la url a acortar" name="url" required/>
           <input type="submit" value="Generar" />
          </form>

<?php
// require_once("connect.php");
// $result2 = mysql_query("SELECT code , url FROM redirects", $con);
// if ($result2) {
//
// while($row = mysql_fetch_array($result2)) {
//     echo "<div class='barra'><b>CÓDIGO: </b>" . $row["code"]. "| <b>URL: </b>" . $row["url"]. "<br></div>";
// }
// } else {
// echo "0 results";
// }
 ?>
    <script type="text/javascript">
    $('#acortar').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'get',
            data: $(this).serialize(),
            beforeSend: function(){
                $('#url').val('Acortando...');
            },
            success: function(data){
                $('#url').val(data);
            }
        });
    });
    </script>
</div>
</div>
</body>
</html>
