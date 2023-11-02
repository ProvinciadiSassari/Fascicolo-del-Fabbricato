<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['id_sospensione'])) {
    $ret[0]=$_POST['id_sospensione'];   
   
    $query="SELECT IDLavoro, Dal, Ripresa, IDUnitaTempo FROM sospensioni 
            where IDSospensioni=".$_POST['id_sospensione'];
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    if ($row = mysql_fetch_assoc($result)){
        $ret[1]=$row["IDLavoro"];        
        $ret[2]=$utility->convertDateToHTML($row["Dal"]);      
        $ret[3]=$utility->convertDateToHTML($row["Ripresa"]);      
        $ret[4]=$row["IDUnitaTempo"];  
        if ($row["Ripresa"]>0)
            $diff=$utility->fDateDiff(strtotime($row["Dal"]), strtotime($row["Ripresa"]));
        else $diff=0; 
        $ret[5]=$diff;
    }  
    
    echo json_encode($ret);
}
