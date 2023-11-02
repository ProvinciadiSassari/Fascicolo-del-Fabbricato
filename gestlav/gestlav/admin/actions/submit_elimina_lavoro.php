<?php

session_start();
require_once('../../conf.inc.php');
include('../../conv.php');

$utility = new Utility();
$utility->connetti();

$ret=0;

$query="delete from lavori where IDLavoro=".$_POST["inp_id_lavoro"]; 

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}

echo $_POST["inp_id_lavoro"];

?>


