<?php

session_start();
require_once('../../conf.inc.php');
include('../../conv.php');

$utility = new Utility();
$utility->connetti();

$ret=0;

if ($_POST["id_istruttore"]>0) {
    
    $query="UPDATE istruttori_pratica
            SET 	
                    Istruttore = ".conv_string2sql($_POST["inp_istruttore"])."
            WHERE IDIstruttore = ".$_POST["id_istruttore"];

    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
}
else if ($_POST["id_istruttore"]==0) {
       
        $query="INSERT INTO istruttori_pratica
            (Istruttore)
            VALUES (".conv_string2sql($_POST["inp_istruttore"]).")"; 
        
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }    
}
else if ($_POST["id_istruttore"]<0) {
       $id_istruttore=$_POST["id_istruttore"]*-1;
       
        $query="delete from istruttori_pratica WHERE IDIstruttore = $id_istruttore"; 
        
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }    
}

echo $ret;

?>


