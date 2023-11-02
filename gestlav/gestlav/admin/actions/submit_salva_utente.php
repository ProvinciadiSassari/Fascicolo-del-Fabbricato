<?php

session_start();
require_once('../../conf.inc.php');
include('../../conv.php');

$utility = new Utility();
$utility->connetti();

$ret=0;

if ($_POST["id_utente"]>0) {
    
    $query="UPDATE utenti_lav
            SET 	
                    a006_descrizione = ".conv_string2sql($_POST["inp_username"]).", 
                    a006_password = ".conv_string2sql($utility->convert($_POST["inp_password"])).", 
                    nominativo = ".conv_string2sql($_POST["inp_nominativo"]).", 
                    a006_livello = ".$_POST["sel_livello"].", 
                    id_competenza = ".$_POST["id_competenza"].", 
                    fl_avanzato = ".$_POST["chk_avanzato"]." 
            WHERE a006_ID = ".$_POST["id_utente"];

    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
}
else if ($_POST["id_utente"]==0) {
    
    //controllo se lo stesso utente esiste gia
    $query="select * from utenti_lav where a006_descrizione=".conv_string2sql($_POST["inp_username"]);
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    $numrows= mysql_num_rows($result);
    
    if ($numrows==0) {
        $query="INSERT INTO utenti_lav
            (a006_descrizione, a006_password, nominativo, a006_livello, fl_avanzato,id_competenza)
            VALUES (".conv_string2sql($_POST["inp_username"]).",".conv_string2sql($utility->convert($_POST["inp_password"])).",
                ".conv_string2sql($_POST["inp_nominativo"]).",".$_POST["sel_livello"].",".$_POST["chk_avanzato"].",".$_POST["id_competenza"].")"; 
        
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }
    }
    else $ret=-1;
}

echo $ret;


