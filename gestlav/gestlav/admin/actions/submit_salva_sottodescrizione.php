<?php

session_start();
require_once('../../conf.inc.php');
include('../../conv.php');

$utility = new Utility();
$utility->connetti();

$ret=0;

if ($_POST["id_sottodescrizione"]>0) {
    
    $progressivo_ordine_attuale=$utility->getProgressivoOrdine($_POST["id_sottodescrizione"]);
    
    if ($_POST["inp_ordine"]<$progressivo_ordine_attuale) {
        $utility->aggiorna_progressivi_ordine_update_up($_POST["inp_ordine"], $progressivo_ordine_attuale);
    }
    if ($progressivo_ordine_attuale<$_POST["inp_ordine"]) {
        $utility->aggiorna_progressivi_ordine_update_down($progressivo_ordine_attuale,$_POST["inp_ordine"]);
    }
    
    $query="UPDATE sottodescrizioni_quadro
            SET 	
                    desc_sottodescrizione_quadro = ".conv_string2sql($_POST["inp_sottodescrizione"]).",
                    id_descrizione_quadro=".$_POST["id_descrizione"].",
                    percentuale_iva=".$_POST["inp_iva"].",
                    progressivo_ordine=".$_POST["inp_ordine"]."
            WHERE id_sottodescrizione_quadro = ".$_POST["id_sottodescrizione"];

    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
}
else if ($_POST["id_sottodescrizione"]==0) {
       $max_id_sottodescrizione_quadro=$utility->max_id_sottodescrizione_quadro();
       
       $utility->aggiorna_progressivi_ordine_go($_POST["inp_ordine"]);
       
        $query="INSERT INTO sottodescrizioni_quadro
            (id_sottodescrizione_quadro, id_descrizione_quadro, desc_sottodescrizione_quadro, percentuale_iva, progressivo_ordine)
            VALUES ($max_id_sottodescrizione_quadro, ".$_POST["id_descrizione"].", ".conv_string2sql($_POST["inp_sottodescrizione"]).", ".$_POST["inp_iva"].", ".$_POST["inp_ordine"].")"; 
        
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        } 
        
         $utility->aggiorna_progressivi_ordine_back($_POST["inp_ordine"]);
}
else if ($_POST["id_sottodescrizione"]<0) {
    
    $id_sottodescrizione_quadro=$_POST["id_sottodescrizione"]*-1;
    
     $ordine=$utility->getProgressivoOrdine($id_sottodescrizione_quadro);
     $utility->aggiorna_progressivi_ordine_delete($ordine);
    
    $query="delete from sottodescrizioni_quadro where id_sottodescrizione_quadro=$id_sottodescrizione_quadro";
     
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    } 
       
}

echo $ret;

?>


