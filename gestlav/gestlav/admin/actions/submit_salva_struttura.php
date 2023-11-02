<?php

session_start();
require_once('../../conf.inc.php');
include('../../conv.php');

$utility = new Utility();
$utility->connetti();

$ret=0;

if ($_POST["id_struttura"]>0) {
    
    $query="UPDATE strutture
            SET 	
                    desc_struttura = ".conv_string2sql($_POST["inp_struttura"])."
            WHERE id_struttura = ".$_POST["id_struttura"];

    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
}
else if ($_POST["id_struttura"]==0) {
       
        $query="INSERT INTO strutture
            (desc_struttura)
            VALUES (".conv_string2sql($_POST["inp_struttura"]).")"; 
        
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }    
}
else if ($_POST["id_struttura"]<0) {
    $id_struttura=$_POST["id_struttura"]*-1;
    
    $query="delete from strutture where id_struttura=$id_struttura";
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    } 
}
echo $ret;

?>


