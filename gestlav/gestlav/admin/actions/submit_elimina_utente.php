<?php

session_start();
require_once('../../conf.inc.php');

$utility = new Utility();
$utility->connetti();

$ret=0;

$query="delete from utenti_lav where a006_ID=".$_POST["id_utente"]; 

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}

?>


