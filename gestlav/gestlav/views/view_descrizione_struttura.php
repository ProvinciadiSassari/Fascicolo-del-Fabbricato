<?php
session_start();
require_once('../conf.inc.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST["id_edificio"])) {
    $id_istituto=$_POST["id_edificio"];     
}
else $id_istituto=0;

if (isset($_POST["id_sottostruttura"])) {
    $id_sottostruttura=$_POST["id_sottostruttura"];     
}
else $id_sottostruttura=0;


$query="select desc_struttura_edificio from legame_edifici_sottostrutture where id_edificio=$id_istituto and id_sottostruttura=$id_sottostruttura";
$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}
$desc_struttura_edificio="";
if ($row = mysql_fetch_assoc($result)){
    $desc_struttura_edificio=$row["desc_struttura_edificio"];
} 

echo $desc_struttura_edificio;
