<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['dir'])) {
    $ret[0]=""; 
    $ret[1]="";
    $ret[2]="";
    $ret[3]="";
    $ret[4]="";
    $ret[5]=""; 
    $ret[6]="ins";
    $ret[7]=""; 
    
    $dir=$_POST['dir'];

    
    $directory=$_SERVER["DOCUMENT_ROOT"].$dir;
   
    if (is_dir($directory)) {
        
        $ret[0]=-1;        
    }
    else {
        $path=$dir;
        
        $query="select Descrizione,UtenteArchiv,Catalogazione,data_scadenza_documento,fl_rinnovato,data_ultima_modifica from descrizione_files where Percorso_completo='$path'";
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }
        $ret[7]=$query; 
        if ($row = mysql_fetch_assoc($result)){
            $ret[0]=$row["Descrizione"];
            $ret[1]=$row["UtenteArchiv"];
            $ret[2]=$row["Catalogazione"];
            $ret[3]=$utility->convertDateToHTML($row["data_scadenza_documento"]);
            $ret[4]=$utility->convertDateTimeToHTML($row["data_ultima_modifica"]); 
            $ret[5]=$row["fl_rinnovato"];
            $ret[6]="mod";
        }                  
    }
    
    echo json_encode($ret);
    
}
