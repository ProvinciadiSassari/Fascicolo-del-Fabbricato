<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

$query="UPDATE corrispondenza
        SET   
          DataProt_UffProt = '".$utility->convertDateToDB($_POST["inp_data_uff_prot"])."',
          NumProt_UffProt = ".$_POST["inp_num_uff_prot"].",
          DataProt_Interno = '".$utility->convertDateToDB($_POST["inp_data_docum"])."',
          NumProt_Interno = ".$_POST["inp_num_docum"].",
          Tipo = ".$_POST["tipo_corrispondenza"].",
          Oggetto = ".conv_string2sql($_POST["ta_oggetto"]).",
          Note = ".conv_string2sql($_POST["ta_note"]).",
          Mittente = ".conv_string2sql($_POST["ta_mittente"]).",
          Utente = ".conv_string2sql($_POST["inp_operatore_ultima_registrazione"]).",
          Destinatario = ".conv_string2sql($_POST["ta_destinatario"])."         
        WHERE IDCorrispondenza = ".$_POST["id_corrispondenza"];

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
} 

?>


