<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if ($_POST["tipo_operazione"]=="ins") {

$query="INSERT INTO corrispondenza
            (IDLavoro, 
            DataProt_UffProt, 
            NumProt_UffProt, 
            DataProt_Interno, 
            NumProt_Interno, 
            Tipo, 
            Oggetto, 
            Note, 
            Mittente, 
            Utente, 
            Destinatario, 
            IDanno)
        values (
            ".$_POST["id_lavoro"].",
            '".$utility->convertDateToDB($_POST["inp_data_uff_prot"])."',
            ".$_POST["inp_num_uff_prot"].", 
            '".$utility->convertDateToDB($_POST["inp_data_docum"])."', 
            ".$_POST["inp_num_docum"].",  
            ".$_POST["tipo_corrispondenza"].",
            ".conv_string2sql($_POST["ta_oggetto"]).",
            ".conv_string2sql($_POST["ta_note"]).",
            ".conv_string2sql($_POST["ta_mittente"]).",
            ".$_SESSION["iduser"].",
            ".conv_string2sql($_POST["ta_destinatario"]).",
            ".date("Y")."    
            )";

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
} 

}
else if ($_POST["tipo_operazione"]=="mod") {
    
    $query="update corrispondenza set DataProt_UffProt='".$utility->convertDateToDB($_POST["inp_data_uff_prot"])."',NumProt_UffProt=".$_POST["inp_num_uff_prot"].","
            . "DataProt_Interno='".$utility->convertDateToDB($_POST["inp_data_docum"])."', NumProt_Interno=".$_POST["inp_num_docum"].", "
            . "Oggetto=".conv_string2sql($_POST["ta_oggetto"]).",Note=".conv_string2sql($_POST["ta_note"]).",Mittente=".conv_string2sql($_POST["ta_mittente"]).","
            . "Utente=".$_SESSION["iduser"].", Destinatario=".conv_string2sql($_POST["ta_destinatario"])."
                 WHERE IDCorrispondenza=".$_POST["id_corrispondenza"];
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    } 

}

