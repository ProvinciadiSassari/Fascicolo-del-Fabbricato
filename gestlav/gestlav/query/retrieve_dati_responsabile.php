<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['id_responsabile'])) {
    $ret[0]=$_POST['id_responsabile'];   
    $ret[1]="";    
    
    $query="SELECT Responsabile
            FROM responsabili_procedimento
            where IDResponsabile=".$_POST['id_responsabile'];
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    if ($row = mysql_fetch_assoc($result)){
        $ret[1]=$row["Responsabile"];        
    }  
    
    echo json_encode($ret);
}

?>


