<?php

session_start();
require_once('../../conf.inc.php');
include('../../conv.php');

$utility = new Utility();
$utility->connetti();

$ret=0;

if ($_POST["id_referente"]>0) {
    
    $query="UPDATE referenti_edificio
            SET 	
                    Referente = ".conv_string2sql($_POST["inp_referente"])."
            WHERE IDReferente = ".$_POST["id_referente"];

    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
}
else if ($_POST["id_referente"]==0) {
       
        $query="INSERT INTO referenti_edificio
            (Referente)
            VALUES (".conv_string2sql($_POST["inp_referente"]).")"; 
        
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }    
}
else if ($_POST["id_referente"]<0) {
    $id_referente=$_POST["id_referente"]*-1;
    
    $query="delete from referenti_edificio where IDReferente=$id_referente";
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
}

echo $ret;

?>


