<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['id_struttura'])) {
    $ret[0]=$_POST['id_struttura'];   
    $ret[1]="";    
    
    $query="SELECT desc_struttura
            FROM strutture
            where id_struttura=".$_POST['id_struttura'];
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    if ($row = mysql_fetch_assoc($result)){
        $ret[1]=$row["desc_struttura"];        
    }  
    
    echo json_encode($ret);
}

?>


