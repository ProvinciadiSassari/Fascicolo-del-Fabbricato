<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();
if ($_POST["tipo_operazione"]=="ins") {

$query="INSERT INTO subappalti
            (
             IDLavoro,
             IDImpresa,
             Descrizione,            
             Note)
        VALUES (".$_POST["id_lavoro"].",
                ".$_POST["sel_imprese"].",
                ".conv_string2sql($_POST["ta_descrizione_sub"]).",
                ".conv_string2sql($_POST["ta_note_sub"]).")";

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}

}
else if ($_POST["tipo_operazione"]=="mod") {
    
    $query="update subappalti set IDImpresa=".$_POST["sel_imprese"].",Descrizione=".conv_string2sql($_POST["ta_descrizione_sub"]).",Note=".conv_string2sql($_POST["ta_note_sub"])." 
        WHERE ID=".$_POST["id_subappalto"]; 
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    
}