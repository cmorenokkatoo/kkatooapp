<?php include("../inc/inc.php");
include("../inc/conn.php"); 

$bd=new BDmysql;

$bd->conectar("$basedatos", "$host", "$user", "$pass");

$sql="SELECT * FROM paises order by pais asc";

$bd->consulta($sql,0);
?>
  <ul>
<?php 
while($for=$bd->matriz()){
?>

    <li><?php echo $for['pais'] ?> - <?php echo $for['Id'] ?> -  <img src="<?php echo $for['cod2']?>.png" alt="<?php echo $for['pais'] ?>" longdesc="<?php echo $for['pais'] ?>">
    </li>
<?php }
?>
  </ul>