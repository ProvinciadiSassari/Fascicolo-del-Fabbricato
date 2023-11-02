<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['id_tipologia'])) {
    $ret[0]=$_POST['id_tipologia'];   
    $ret[1]="";   
    $ret[2]="";
    
    $query="SELECT Tipologia,IDCategoria
            FROM tipologie
            where IDTipologia=".$_POST['id_tipologia'];
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    if ($row = mysql_fetch_assoc($result)){
        $ret[1]=$row["IDCategoria"];    
        $ret[2]=$row["Tipologia"]; 
    }  
    
    echo json_encode($ret);
}

?>


