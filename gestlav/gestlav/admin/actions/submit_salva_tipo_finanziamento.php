<?php

session_start();
require_once('../../conf.inc.php');
include('../../conv.php');

$utility = new Utility();
$utility->connetti();

$ret=0;

if ($_POST["id_finanziamento"]>0) {
    
    $query="UPDATE tipo_finanziamento
            SET 	
                    Tipo_Finanziamento = ".conv_string2sql($_POST["inp_finanziamento"])."
            WHERE ID = ".$_POST["id_finanziamento"];

    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
}
else if ($_POST["id_finanziamento"]==0) {
       
        $query="INSERT INTO tipo_finanziamento
            (Tipo_Finanziamento)
            VALUES (".conv_string2sql($_POST["inp_finanziamento"]).")"; 
        
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }    
}

echo $ret;

?>


