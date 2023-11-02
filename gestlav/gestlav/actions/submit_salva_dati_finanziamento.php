<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if ($_POST["tipo_operazione"]=="mod") {

$query="UPDATE finanziamenti
        SET   
          IDTipoFinanz = ".$_POST["sel_fonti_finanziamento"]." ,
          Importo = ".$_POST["inp_somma_assegnata"]." ,
          Somma_erogata = ".$_POST["inp_somma_erogata"]." ,
          Note = ".conv_string2sql($_POST["ta_note_finanziamento"])."
        WHERE ID = ".$_POST["id_finanziamento"];

        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        } 
}
else if ($_POST["tipo_operazione"]=="ins") {
    
    $query="INSERT INTO finanziamenti (IDLavoro, IDTipoFinanz, Importo, Somma_erogata, Note)
        VALUES  (  ".$_POST["id_lavoro"].", ".$_POST["sel_fonti_finanziamento"].", ".$_POST["inp_somma_assegnata"].", ".$_POST["inp_somma_erogata"].", ".conv_string2sql($_POST["ta_note_finanziamento"])."  )";
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    
}
