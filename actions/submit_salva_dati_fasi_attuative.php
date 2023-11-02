<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

$query="DELETE
        FROM gestman.fasi_attuattive_lavori
        WHERE id_lavoro = ".$_POST["id_lavoro"];

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
} 

$query="INSERT INTO fasi_attuattive_lavori
            (id_lavoro, data_affidamento_incarico_servivi_arch_ing, 
            data_contratto_convenzione_progettista, data_consegna_progetto_preliminare, 
            data_proposta_approvazione_gp, data_approvazione_progetto_preliminare, 
            data_consegna_progetto_definitivo, data_proposta_progetto_definitivo, 
            data_approvazione_progetto_definitivo, data_consegna_progetto_esecutivo, 
            data_proposta_approvazione_progetto_esecutivo, data_approvazione_progetto_esecutivo, 
            data_completamento_reperimento_pareri_enti_terzi, data_determina_contrarre, 
            data_procedure_appalto, data_contratto_lavori, 
            data_consegna_lavori, data_collaudo_lavori, data_modifica, id_utente)
        VALUES (".$_POST["id_lavoro"].",'".$utility->convertDateToDB($_POST["data_affidamento_incarico_servivi_arch_ing"])."','"
        .$utility->convertDateToDB($_POST["data_contratto_convenzione_progettista"])."','".$utility->convertDateToDB($_POST["data_consegna_progetto_preliminare"])."','"
        .$utility->convertDateToDB($_POST["data_proposta_approvazione_gp"])."','".$utility->convertDateToDB($_POST["data_approvazione_progetto_preliminare"])."','"
        .$utility->convertDateToDB($_POST["data_consegna_progetto_definitivo"])."','".$utility->convertDateToDB($_POST["data_proposta_progetto_definitivo"])."','"
        .$utility->convertDateToDB($_POST["data_approvazione_progetto_definitivo"])."','".$utility->convertDateToDB($_POST["data_consegna_progetto_esecutivo"])."','"
        .$utility->convertDateToDB($_POST["data_proposta_approvazione_progetto_esecutivo"])."','".$utility->convertDateToDB($_POST["data_approvazione_progetto_esecutivo"])."','"
        .$utility->convertDateToDB($_POST["data_completamento_reperimento_pareri_enti_terzi"])."','".$utility->convertDateToDB($_POST["data_determina_contrarre"])."','"
        .$utility->convertDateToDB($_POST["data_procedure_appalto"])."','".$utility->convertDateToDB($_POST["data_contratto_lavori"])."','"
        .$utility->convertDateToDB($_POST["data_consegna_lavori"])."','".$utility->convertDateToDB($_POST["data_collaudo_lavori"])."',now(),".$_SESSION["iduser"].")";
        
$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}

