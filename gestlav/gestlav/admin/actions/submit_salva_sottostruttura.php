<?php

session_start();
require_once('../../conf.inc.php');
include('../../conv.php');

$utility = new Utility();
$utility->connetti();

$ret=0;

if ($_POST["id_sottostruttura"]>0) {
    
    $query="UPDATE sottostrutture
            SET 	
                    desc_sottostruttura = ".conv_string2sql($_POST["inp_sottostruttura"]).",
                    id_struttura=".$_POST["id_struttura"]."     
            WHERE id_sottostruttura = ".$_POST["id_sottostruttura"];

    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
}
else if ($_POST["id_sottostruttura"]==0) {
       
        $query="INSERT INTO sottostrutture
            (desc_sottostruttura,id_struttura)
            VALUES (".conv_string2sql($_POST["inp_sottostruttura"]).",".$_POST["id_struttura"].")"; 
        
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }    
}
else if ($_POST["id_sottostruttura"]<0) {
    $id_sottostruttura=$_POST["id_sottostruttura"]*-1;
    
    $query="delete from sottostrutture where id_sottostruttura=$id_sottostruttura";
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
}
echo $ret;

?>


