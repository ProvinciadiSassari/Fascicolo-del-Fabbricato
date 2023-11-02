<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

$query="UPDATE edifici 
        SET	
                IDReferente = ".$_POST["sel_referente"]." , 	
                Dirigente_responsabile = ".conv_string2sql($_POST["inp_responsabile"])." , 
                Titolo_possesso = ".conv_string2sql($_POST["ta_titolo_possesso"])." , 
                Plessi = ".conv_string2sql($_POST["ta_plessi"])." , 
                Locali = ".conv_string2sql($_POST["ta_locali"]).", 
                N_Piani = ".conv_string2sql($_POST["ta_piani"])." , 
                Superficie_cubatura = ".conv_string2sql($_POST["ta_superficie_cubatura"])." , 
                Certif_agibilita = ".conv_string2sql($_POST["ta_certif_agibilita"])." , 
                Situaz_catast = ".conv_string2sql($_POST["ta_situazione_catastale"]).", 
                Certif_collaudo = ".conv_string2sql($_POST["ta_certif_collaudo"])." , 
                CPI = ".conv_string2sql($_POST["ta_cpi"])."  	
        WHERE
        ID = ".$_POST["id_istituto"];

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}   

?>


