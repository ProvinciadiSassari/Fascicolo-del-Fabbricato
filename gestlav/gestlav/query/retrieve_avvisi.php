<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['id_utente'])) {
    $ret[0]="";  
    
    $query="SELECT id_fabbricato, filename
            FROM avvisi
            where id_utente=".$_POST['id_utente']." and fl_letto=0";
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    $i=0;
    while ($row = mysql_fetch_assoc($result)){
        $ret[$i]=$utility->getDescFabbricato($row["id_fabbricato"]); 
        $i++;
        $ret[$i]=$row["filename"]; 
        $i++;
    }  
    
    echo json_encode($ret);
}

?>


