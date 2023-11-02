<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

$query="UPDATE subappalti
        SET 
          IDImpresa = ".$_POST["inp_impresa"].",
          Descrizione = ".conv_string2sql($_POST["ta_descrizione_sub"]).",
          Note = ".conv_string2sql($_POST["ta_note_sub"])."
        WHERE ID = ".$_POST["id_subappalto"];

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
} 

//echo $query;

?>


