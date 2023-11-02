<?php

session_start();
require_once('../../conf.inc.php');
include('../../conv.php');

$utility = new Utility();
$utility->connetti();

$ret=0;

if ($_POST["id_responsabile"]>0) {
    
    $query="UPDATE responsabili_procedimento
            SET 	
                    Responsabile = ".conv_string2sql($_POST["inp_responsabile"])."
            WHERE IDResponsabile = ".$_POST["id_responsabile"];

    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
}
else if ($_POST["id_responsabile"]==0) {
       
        $query="INSERT INTO responsabili_procedimento
            (Responsabile)
            VALUES (".conv_string2sql($_POST["inp_responsabile"]).")"; 
        
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }    
}
else if ($_POST["id_responsabile"]<0) {
    
       $id_responsabile=$_POST["id_responsabile"]*-1;
       
        $query="delete from responsabili_procedimento WHERE IDResponsabile=$id_responsabile"; 
        
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }    
}

echo $ret;

?>


