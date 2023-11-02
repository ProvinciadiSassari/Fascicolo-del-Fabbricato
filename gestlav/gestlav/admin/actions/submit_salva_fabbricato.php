<?php

session_start();
require_once('../../conf.inc.php');
include('../../conv.php');

$utility = new Utility();
$utility->connetti();

$ret=0;$val=true;
        
if ($_POST["id_fabbricato"]>0) {
    
$query="UPDATE fabbricati
        SET 	
            descrizione_fabbricato = ".conv_string2sql($_POST["inp_fabbricato"]).",
            indirizzo_fabbricato = ".conv_string2sql($_POST["inp_indirizzo"]).",
            id_comune = ".$_POST["sel_comune"].",            
            cap_fabbricato = '".$_POST["inp_cap_fabbricato"]."' 
        WHERE id_fabbricato = ".$_POST["id_fabbricato"];

    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }         
}
else if ($_POST["id_fabbricato"]==0) {
    
    $query="INSERT INTO fabbricati
            (id_fabbricato, descrizione_fabbricato,indirizzo_fabbricato, cap_fabbricato, id_comune)
            VALUES (".$_POST["inp_id_fabbricato"].",".conv_string2sql($_POST["inp_fabbricato"]).",".conv_string2sql($_POST["inp_indirizzo"]).",'".$_POST["inp_cap_fabbricato"]."',".$_POST["sel_comune"].")";   
    $result = mysql_query($query);
    if (!$result){
            $ret=-1;
    } 
}

echo $ret;

?>


