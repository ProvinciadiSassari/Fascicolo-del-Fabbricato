<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['id_finanziamento'])) {
    $ret[0]=$_POST['id_finanziamento'];   
    $ret[1]="";    
    
    $query="SELECT Tipo_Finanziamento
            FROM tipo_finanziamento
            where ID=".$_POST['id_finanziamento'];
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    if ($row = mysql_fetch_assoc($result)){
        $ret[1]=$row["Tipo_Finanziamento"];        
    }  
    
    echo json_encode($ret);
}

?>


