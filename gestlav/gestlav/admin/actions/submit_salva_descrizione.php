<?php

session_start();
require_once('../../conf.inc.php');
include('../../conv.php');

$utility = new Utility();
$utility->connetti();

$ret=0;

if ($_POST["id_descrizione"]>0) {
    
    $query="UPDATE descrizioni_quadro
            SET 	
                    desc_descrizione_quadro = ".conv_string2sql($_POST["inp_descrizione"]).",
                    id_gruppo_quadro = ".$_POST["sel_gruppo"]."     
            WHERE id_descrizione_quadro = ".$_POST["id_descrizione"];

    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
}
else if ($_POST["id_descrizione"]==0) {
       
        $query="INSERT INTO descrizioni_quadro
            (desc_descrizione_quadro,id_gruppo_quadro)
            VALUES (".conv_string2sql($_POST["inp_descrizione"]).",".$_POST["sel_gruppo"].")"; 
        
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }    
}
else if ($_POST["id_descrizione"]<0) {
       $id_descrizione=$_POST["id_descrizione"]*-1;
       
        $query="delete from descrizioni_quadro WHERE id_descrizione_quadro = $id_descrizione"; 
        
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }    
}
echo $ret;

?>


