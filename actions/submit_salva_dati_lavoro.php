<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();


$query="UPDATE lavori
        SET 
            Descrizione = ".conv_string2sql($_POST["ta_descrizione"]).",
            Codice_contratto = ".conv_string2sql($_POST["inp_codice_contratto"]).",
            IDCategoria = ".$_POST["sel_categoria"].",
            IDTipologia = ".$_POST["sel_tipologia"].",
            IDEdificio = ".$_POST["id_fabbricato"].",            
            IDResponsabile = ".$_POST["sel_responsabile"].",
            IDIstruttore = ".$_POST["sel_istruttore"].",
            data_contratto = '".$utility->convertDateToDB($_POST["inp_data_contratto"])."',
            Note = ".conv_string2sql($_POST["ta_note_lavoro"]).",                       
            LavoroChiuso = ".$_POST["chk_lavoro_chiuso"].",
            fl_complementare = ".$_POST["chk_complementare"].",
            id_lavoro_origine = ".$_POST["sel_lavoro_origine"].",
            importo_disponibile = ".$_POST["inp_disponibilita"]."
        WHERE IDLavoro = ".$_POST["id_lavoro"];

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}   

