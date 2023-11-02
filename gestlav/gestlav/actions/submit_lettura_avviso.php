<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

$query="UPDATE avvisi
        SET   
          fl_letto = 1 
        WHERE id_utente = ".$_POST["id_utente"];

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
} 

//echo $query;
?>


