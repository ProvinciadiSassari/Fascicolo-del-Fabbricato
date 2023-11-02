<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();
$query1="";$query2="";$query="";$query3="";

if ($_POST["tipo_operazione"]=="new") {

$query="INSERT INTO quadro_economico_generale
        (id_lavoro, id_sottodescrizione_quadro, perc_iva, imp_qe_progetto, imp_qe_contratto, imp_qe_perizia, imp_qe_collaudo, data_modifica, utente_modifica)
VALUES (".$_POST["id_lavoro"].",".$_POST["sel_descrizione_lavoro"].",".$_POST["perc_iva"].", ".$_POST["imp_qe_progetto"].", ".$_POST["imp_qe_contratto"].", 
        ".$_POST["imp_qe_perizia"].", ".$_POST["imp_qe_collaudo"].", now(), '".$_SESSION["username"]."')";
       
}
else 
if ($_POST["tipo_operazione"]=="mod") {
  
    $query="UPDATE quadro_economico_generale
            SET perc_iva = ".$_POST["perc_iva"].", imp_qe_progetto = ".$_POST["imp_qe_progetto"].", imp_qe_contratto = ".$_POST["imp_qe_contratto"].", imp_qe_perizia = ".$_POST["imp_qe_perizia"].", imp_qe_collaudo = ".$_POST["imp_qe_collaudo"].", data_modifica = now(), utente_modifica = '".$_SESSION["username"]."' 
            WHERE progressivo = ".$_POST["progressivo"];    
        
}

if (!empty($query)) {
    $result = mysql_query($query);
    if (!$result){
        die ("Could not query the database: <br />". mysql_error());
    } 
}

$query="select * from legame_lavori_quadri_economici where id_lavoro=".$_POST["id_lavoro"];
$result = mysql_query($query);

$conta=  mysql_num_rows($result);

if ($conta==0) {
    if (isset($_POST["data_qe_progetto"])) {
        $query1="insert into legame_lavori_quadri_economici
                (id_lavoro, data_qe_progetto, data_qe_contratto, data_qe_perizia, data_qe_collaudo)
                values (".$_POST["id_lavoro"].", '".$utility->convertDateToDB($_POST["data_qe_progetto"])."', '".$utility->convertDateToDB($_POST["data_qe_contratto"])."', '".$utility->convertDateToDB($_POST["data_qe_perizia"])."', '".$utility->convertDateToDB($_POST["data_qe_collaudo"])."')";
    }
        
}
else if ($conta>0) {
    if (isset($_POST["data_qe_progetto"])) {
            $query1="UPDATE legame_lavori_quadri_economici
                    SET data_qe_progetto = '".$utility->convertDateToDB($_POST["data_qe_progetto"])."', 
                        data_qe_contratto = '".$utility->convertDateToDB($_POST["data_qe_contratto"])."', 
                        data_qe_perizia = '".$utility->convertDateToDB($_POST["data_qe_perizia"])."', 
                        data_qe_collaudo = '".$utility->convertDateToDB($_POST["data_qe_collaudo"])."'
                    WHERE id_lavoro = ".$_POST["id_lavoro"];
    }
}

if (!empty($query1)) {
    $result1 = mysql_query($query1);
    if (!$result1){
        die ("Could not query the database: <br />". mysql_error());
    }
}

if ($_POST["tipo_operazione"]=="note") {
    $query2="update legame_lavori_quadri_economici set note_quadro=".conv_string2sql($_POST["ta_annotazioni"])." WHERE id_lavoro = ".$_POST["id_lavoro"];
}

if (!empty($query2)) {
    $result2 = mysql_query($query2);
    if (!$result2){
        die ("Could not query the database: <br />". mysql_error());
    }
}

if ($_POST["tipo_operazione"]=="del") {
    $query3="delete from quadro_economico_generale WHERE id_lavoro = ".$_POST["id_lavoro"]." AND id_sottodescrizione_quadro = ".$_POST["id_sottodescrizione_quadro"]." AND data_modifica = '".$_POST["data_modifica"]."'";
}

if (!empty($query3)) {
    $result3 = mysql_query($query3);
    if (!$result3){
        die ("Could not query the database: <br />". mysql_error());
    }
}
//echo $conta;


