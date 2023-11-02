<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

$query="UPDATE incaricati
        SET 
            IDProfess_incaricato = ".$_POST["sel_incaricato"].",            
            Numero_delibera = ".conv_string2sql($_POST["inp_num_delibera"]).",
            Data_delibera = '".$utility->convertDateToDB($_POST["inp_data_delibera"])."',
            Numero_convenzione = ".conv_string2sql($_POST["inp_num_convenzione"]).",
            Data_convenzione = '".$utility->convertDateToDB($_POST["inp_data_convenzione"])."',
            Note = ".conv_string2sql($_POST["ta_note_incarichi"])."
        WHERE IDLavoro = ".$_POST["id_lavoro"]." and IDIncarico=".$_POST["id_incarico"];

$result = mysql_query($query);
if (!$result){
        die ($query."Could not query the database: <br />". mysql_error());
}   

