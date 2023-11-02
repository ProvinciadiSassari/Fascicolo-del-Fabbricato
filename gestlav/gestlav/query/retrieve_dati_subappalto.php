<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['id_subappalto'])) {
    $ret[0]=$_POST['id_subappalto'];   
   
    $query="SELECT
                IDLavoro, IDImpresa, Descrizione, Direttore_cantiere, Importo, Importo2, Importo3, Note
            FROM
              subappalti 
            where ID=".$_POST['id_subappalto'];
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    if ($row = mysql_fetch_assoc($result)){
        $ret[1]=$row["IDLavoro"];        
        $ret[2]=$row["IDImpresa"]; 
        $ret[3]=$row["Descrizione"]; 
        $ret[4]=$row["Direttore_cantiere"]; 
        $ret[5]=$row["Importo"]; 
        $ret[6]=$row["Importo2"];
        $ret[7]=$row["Importo3"];
        $ret[8]=$row["Note"];       
    }  
    
    echo json_encode($ret);
}
