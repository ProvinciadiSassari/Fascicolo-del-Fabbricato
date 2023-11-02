<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();


$query="UPDATE imprese_incaricate
        SET 
          IDImpresa = ".$_POST["inp_impresa"].",
          Numero_delibera = ".conv_string2sql($_POST["inp_gp_det"]).",
          Data_delibera = '".$utility->convertDateToDB($_POST["inp_data_gp_det"])."',
          Legale_rappresentante = ".conv_string2sql($_POST["inp_rappresentante_legale"]).",
          Direttore_cantiere = ".conv_string2sql($_POST["inp_direttore"]).",
          Responsabile_sicurezza = ".conv_string2sql($_POST["inp_responsabile_sicurezza"]).",
          Contratto_Rep = ".conv_string2sql($_POST["inp_rep"]).",
          Numero_contratto = ".conv_string2sql($_POST["inp_num_contratto"]).",
          Data_contratto = '".$utility->convertDateToDB($_POST["inp_data_contratto"])."',
          Base_asta = ".$_POST["inp_base_asta"].",
          Ribasso = ".$_POST["inp_ribasso"].",
          Oneri_sicurezza = ".$_POST["inp_oneri_sicurezza"].",
          Importo_netto = ".$_POST["inp_importo_netto"].",
          Note = ".conv_string2sql($_POST["ta_note_impresa"])." 
        WHERE IDLavoro = ".$_POST["id_lavoro"];

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
} 

//echo $query;



