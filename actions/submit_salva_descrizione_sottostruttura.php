<?php
session_start();
require_once('../conf.inc.php');
include('../conv.php');

$ret=0;
    
$utility = new Utility();
$utility->connetti();

$query="update legame_edifici_sottostrutture "
        . " set desc_struttura_edificio=".conv_string2sql(urldecode($_POST["desc"]))." "
        . " where id_edificio=".$_POST["id_edificio"]." and id_sottostruttura=".$_POST["id_sottostruttura"];

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}


echo $ret;

