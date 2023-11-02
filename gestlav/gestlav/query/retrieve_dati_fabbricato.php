<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['id_fabbricato'])) {
    $ret[0]=$_POST['id_fabbricato'];   
    $ret[1]="";
    $ret[2]="";
    $ret[3]="";
    $ret[4]="";
  
    $query="SELECT descrizione_fabbricato, indirizzo_fabbricato, cap_fabbricato, id_comune
            FROM fabbricati
            where id_fabbricato=".$_POST['id_fabbricato'];
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    if ($row = mysql_fetch_assoc($result)){
        $ret[1]=$row["descrizione_fabbricato"];
        $ret[2]=$row["indirizzo_fabbricato"];
        $ret[3]=$row["cap_fabbricato"];
        $ret[4]=$row["id_comune"];                 
    }  
    
    echo json_encode($ret);
}


