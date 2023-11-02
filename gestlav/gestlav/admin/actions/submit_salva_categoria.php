<?php

session_start();
require_once('../../conf.inc.php');
include('../../conv.php');

$utility = new Utility();
$utility->connetti();

$ret=0;

if ($_POST["id_categoria"]>0) {
    
    $query="UPDATE categorie
            SET 	
                    Categoria = ".conv_string2sql($_POST["inp_categoria"])."
            WHERE IDCategoria = ".$_POST["id_categoria"];

    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
}
else if ($_POST["id_categoria"]==0) {
       
        $query="INSERT INTO categorie
            (Categoria)
            VALUES (".conv_string2sql($_POST["inp_categoria"]).")"; 
        
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }    
}
else if ($_POST["id_categoria"]<0) {
       
    $id_categoria=$_POST["id_categoria"]*-1;
    
        $query="delete from categorie WHERE IDCategoria=$id_categoria"; 
        
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }    
}
echo $ret;

?>


