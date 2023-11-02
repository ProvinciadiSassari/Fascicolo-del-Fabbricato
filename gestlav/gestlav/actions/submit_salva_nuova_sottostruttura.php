<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

if (isset($_POST["id_edificio"]) && isset($_POST["id_sottostruttura"])) {
    
$utility = new Utility();
$utility->connetti();

$ret=0;

$query="select count(*) as conta from legame_edifici_sottostrutture where id_edificio=".$_POST["id_edificio"]." and id_sottostruttura=".$_POST["id_sottostruttura"];
$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}
$conta=0;
if ($row = mysql_fetch_assoc($result)){
    $conta=$row["conta"];
}
if ($conta==0) {
   
    $query="insert into legame_edifici_sottostrutture (id_edificio,id_sottostruttura,desc_struttura_edificio) 
            values (".$_POST["id_edificio"].",".$_POST["id_sottostruttura"].",'Descrizione Sottostruttura da definire')";
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    $ret=1;
}
else $ret=0;
 

echo $ret;
}
?>


