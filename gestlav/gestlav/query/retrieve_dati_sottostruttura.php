<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['id_sottostruttura'])) {
    $ret[0]=$_POST['id_sottostruttura'];   
    $ret[1]="";   
    $ret[2]="";
    
    $query="SELECT desc_sottostruttura,id_struttura
            FROM sottostrutture
            where id_sottostruttura=".$_POST['id_sottostruttura'];
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    if ($row = mysql_fetch_assoc($result)){
        $ret[1]=$row["id_struttura"];    
        $ret[2]=$row["desc_sottostruttura"]; 
    }  
    
    echo json_encode($ret);
}

?>


