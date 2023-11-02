<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['id_sottodescrizione'])) {
    $ret[0]=$_POST['id_sottodescrizione'];   
    $ret[1]="";   
    $ret[2]="";
    $ret[3]="";
    $ret[4]="";
    
    $query="SELECT id_descrizione_quadro, desc_sottodescrizione_quadro, percentuale_iva, progressivo_ordine
            FROM sottodescrizioni_quadro
            where id_sottodescrizione_quadro=".$_POST['id_sottodescrizione'];
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    if ($row = mysql_fetch_assoc($result)){
        $ret[1]=$row["id_descrizione_quadro"];    
        $ret[2]=$row["desc_sottodescrizione_quadro"]; 
        $ret[3]=$row["progressivo_ordine"]; 
        $ret[4]=$row["percentuale_iva"]; 
    }  
    
    echo json_encode($ret);
}

?>


