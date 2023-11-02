<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['id_utente'])) {
    $ret[0]=$_POST['id_utente'];   
    $ret[1]="";
    $ret[2]="";
    $ret[3]="";
    $ret[4]="";   
    $ret[5]="";   
    
    $query="SELECT a006_descrizione, a006_password, nominativo, a006_livello,fl_avanzato,id_competenza 
            FROM utenti_lav
            where a006_ID=".$_POST['id_utente'];
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    if ($row = mysql_fetch_assoc($result)){
        $ret[1]=$row["a006_descrizione"];
        $ret[2]=$utility->convert($row["a006_password"]);
        $ret[3]=$row["nominativo"];
        $ret[4]=$row["a006_livello"];  
        $ret[5]=$row["fl_avanzato"]; 
        $ret[6]=$row["id_competenza"]; 
    }  
    
    echo json_encode($ret);
}



