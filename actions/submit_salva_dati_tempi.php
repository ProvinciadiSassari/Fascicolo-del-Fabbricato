<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

$query="UPDATE tempi_esecuzione
        SET   
          Consegna_parziale = '".$utility->convertDateToDB($_POST["inp_consegna_parziale_lavori"])."',
          Consegna_definitiva = '".$utility->convertDateToDB($_POST["inp_consegna_definitiva_lavori"])."',
          Tempo_utile = ".$_POST["inp_tempo_utile"].",
          IDUnitaTempo = ".$_POST["sel_unita_tempo"].",
          Scadenza_tempo_utile = '".$utility->convertDateToDB($_POST["inp_scadenza_tempo_utile"])."',
          Scadenza_definitiva = '".$utility->convertDateToDB($_POST["inp_scadenza_definitiva"])."',
          Ultimazione = '".$utility->convertDateToDB($_POST["inp_ultimazione_lavori"])."',
          Certificata = '".$utility->convertDateToDB($_POST["inp_certificata_data"])."',
          Stato_finale_entro_gg = ".$_POST["inp_stato_finale_gg"].",
          Stato_finale_emesso = '".$utility->convertDateToDB($_POST["inp_stato_finale_emesso"])."',
          Collaudo_entro_gg = ".$_POST["inp_collaudo_entro_gg"].",
          Certif_collaudo_emesso = '".$utility->convertDateToDB($_POST["inp_certiticato_collaudo_emesso"])."',
          Riserve = ".conv_string2sql($_POST["ta_riserve"])."
        WHERE ID = ".$_POST["id_tempo_esecuzione"];

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
} 

//echo $query;


