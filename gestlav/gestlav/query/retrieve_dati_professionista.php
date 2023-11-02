<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['id_professionista'])) {
    $ret[0]=$_POST['id_professionista'];   
    $ret[1]="";
    $ret[2]="";
    $ret[3]="";
    $ret[4]="";
    $ret[5]="";
    $ret[6]="";
    $ret[7]="";
    $ret[8]="";
    $ret[9]="";
    
    $query="SELECT Professionista, IDComune, Indirizzo_studio, Tel1, Tel2, Fax, CodFisc_PartIVA, email, URL
            FROM professionisti
            where IDProfessionista=".$_POST['id_professionista'];
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    if ($row = mysql_fetch_assoc($result)){
        $ret[1]=conv_string2html($row["Professionista"]);
        $ret[2]=$row["IDComune"];
        $ret[3]=conv_string2html($row["Indirizzo_studio"]);
        $ret[4]=$row["Tel1"];
        $ret[5]=$row["Tel2"];
        $ret[6]=$row["Fax"];
        $ret[7]=$row["CodFisc_PartIVA"];
        $ret[8]=conv_string2html($row["email"]);
        $ret[9]=$row["URL"];                       
    }  
    
    echo json_encode($ret);
}

?>


