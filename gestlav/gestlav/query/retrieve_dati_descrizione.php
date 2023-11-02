<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['id_descrizione'])) {
    $ret[0]=$_POST['id_descrizione'];   
    $ret[1]="";  
    $ret[2]=""; 
    
    $query="SELECT desc_descrizione_quadro,id_gruppo_quadro
            FROM descrizioni_quadro
            where id_descrizione_quadro=".$_POST['id_descrizione'];
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    if ($row = mysql_fetch_assoc($result)){
        $ret[1]=$row["desc_descrizione_quadro"];     
        $ret[2]=$row["id_gruppo_quadro"];   
    }  
    
    echo json_encode($ret);
}

?>


