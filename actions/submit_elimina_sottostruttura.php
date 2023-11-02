<?php


session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST["id_edificio"]) && isset($_POST["id_sottostruttura"])) {
    
$ret=1;

$query="delete from legame_edifici_sottostrutture where id_edificio=".$_POST["id_edificio"]." and id_sottostruttura=".$_POST["id_sottostruttura"];

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}

echo $ret;
}
