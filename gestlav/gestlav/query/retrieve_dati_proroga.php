<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['id_proroga'])) {
    $ret[0]=$_POST['id_proroga'];   
   
    $query="SELECT IDLavoro, Periodo, Data_proroga, IDUnitaTempo FROM proroghe 
            where IDProroghe=".$_POST['id_proroga'];
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    if ($row = mysql_fetch_assoc($result)){
        $ret[1]=$row["IDLavoro"];        
        $ret[2]=$row["Periodo"]; 
        $ret[3]=$utility->convertDateToHTML($row["Data_proroga"]);      
        $ret[4]=$row["IDUnitaTempo"];         
    }  
    
    echo json_encode($ret);
}
