<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['id_impresa'])) {
    $ret[0]=$_POST['id_impresa'];   
    $ret[1]="";
    $ret[2]="";
    $ret[3]="";
    $ret[4]="";
    $ret[5]="";
    $ret[6]="";
    $ret[7]="";
    $ret[8]="";
    $ret[9]="";
    $ret[10]="";   
    $query="SELECT                
                Impresa,
                Titolare,
                IDComune,
                Indirizzo,
                Tel1,
                Tel2,
                Fax,
                CodFisc_PartIVA,
                email,
                URL
            FROM imprese
            where IDImpresa=".$_POST['id_impresa'];
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    if ($row = mysql_fetch_assoc($result)){
        $ret[1]=utf8_encode($row["Impresa"]);
        $ret[2]=utf8_encode($row["Titolare"]);
        $ret[3]=$row["IDComune"];
        $ret[4]= utf8_encode($row["Indirizzo"]);
        $ret[5]=$row["Tel1"];
        $ret[6]=$row["Tel2"];
        $ret[7]=$row["Fax"];
        $ret[8]=$row["CodFisc_PartIVA"];
        $ret[9]=utf8_encode($row["email"]);
        $ret[10]=$row["URL"];                  
    }  
    
    echo json_encode($ret);
}



