<?php

session_start();
require_once('../../conf.inc.php');
include('../../conv.php');

$utility = new Utility();
$utility->connetti();

$ret=0;

if ($_POST["id_tipologia"]>0) {
    
    $query="UPDATE tipologie
            SET 	
                    Tipologia = ".conv_string2sql($_POST["inp_tipologia"]).",
                    IDCategoria=".$_POST["id_categoria"]."     
            WHERE IDTipologia = ".$_POST["id_tipologia"];

    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
}
else if ($_POST["id_tipologia"]==0) {
       
        $query="INSERT INTO tipologie
            (Tipologia,IDCategoria)
            VALUES (".conv_string2sql($_POST["inp_tipologia"]).",".$_POST["id_categoria"].")"; 
        
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }    
}
else if ($_POST["id_tipologia"]<0) {
     $id_tipologia=$_POST["id_tipologia"]*-1;  
     
        $query="delete from tipologie WHERE IDTipologia=$id_tipologia"; 
        
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }    
}

echo $ret;

?>


