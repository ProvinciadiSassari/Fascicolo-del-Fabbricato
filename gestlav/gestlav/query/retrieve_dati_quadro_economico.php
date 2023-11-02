<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();
$ret="";

if (isset($_POST['progressivo'])) {
    
    $query="SELECT id_lavoro,id_sottodescrizione_quadro,perc_iva, imp_qe_progetto, imp_qe_contratto, imp_qe_perizia, imp_qe_collaudo, data_modifica, utente_modifica
            FROM quadro_economico_generale
            where progressivo=".$_POST['progressivo'];
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    if ($row = mysql_fetch_assoc($result)){  
        $ret[0]=$row["id_lavoro"];
        $ret[1]=$row["id_sottodescrizione_quadro"];
        $ret[2]=$row["perc_iva"];
        $ret[3]=$row["imp_qe_progetto"];
        $ret[4]=$row["imp_qe_contratto"];
        $ret[5]=$row["imp_qe_perizia"];
        $ret[6]=$row["imp_qe_collaudo"];
        $ret[7]=$row["data_modifica"];
        $ret[8]=$row["utente_modifica"];         
    }
    
    $ret[9]=utf8_encode($utility->getDescSottodescrizioneQuadro($ret[1]));
    
    $query="SELECT data_qe_progetto, data_qe_contratto, data_qe_perizia, data_qe_collaudo
            FROM legame_lavori_quadri_economici where id_lavoro=".$ret[0];
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    if ($row = mysql_fetch_assoc($result)){        
        $ret[10]=$utility->convertDateToHTML($row["data_qe_progetto"]);
        $ret[11]=$utility->convertDateToHTML($row["data_qe_contratto"]);
        $ret[12]=$utility->convertDateToHTML($row["data_qe_perizia"]);
        $ret[13]=$utility->convertDateToHTML($row["data_qe_collaudo"]);        
    }  
    
    echo json_encode($ret);
//    echo $utility->getDescSottodescrizioneQuadro($_POST['id_sottodescrizione_quadro']);
}

