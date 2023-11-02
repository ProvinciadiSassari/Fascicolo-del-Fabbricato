<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['id_finanziamento'])) {
    $ret[0]=$_POST['id_finanziamento'];   
   
    $query="SELECT IDLavoro, IDTipoFinanz, Importo, Somma_erogata, Note
            FROM finanziamenti
            where ID=".$_POST['id_finanziamento'];
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    if ($row = mysql_fetch_assoc($result)){
        $ret[1]=$row["IDLavoro"];        
        $ret[2]=$row["IDTipoFinanz"]; 
        $ret[3]=$row["Importo"]; 
        $ret[4]=$row["Somma_erogata"]; 
        $ret[5]=$row["Note"];        
    }  
    
    echo json_encode($ret);
}
