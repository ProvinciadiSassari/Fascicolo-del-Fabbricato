<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['id_lavoro'])) {   
    $query = "SELECT            
            l.Descrizione,
            l.Codice_contratto,
            l.data_contratto,
            l.IDCategoria,
            l.IDTipologia,                    
            l.LavoroChiuso,
            l.IDEdificio,
            l.IDResponsabile,
            l.IDIstruttore,
            l.Note,
            fl_complementare,
            id_lavoro_origine,
            importo_disponibile
            FROM lavori l
        WHERE l.IDLavoro=".$_POST['id_lavoro'];

    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    $ret[0]=$_POST['id_lavoro'];
    if ($row = mysql_fetch_assoc($result)){
        $ret[1]=$row["Descrizione"];
        $ret[2]=($row["Codice_contratto"]);
        $ret[3]=$utility->convertDateToHTML($row["data_contratto"]);
        $ret[4]=($row["IDCategoria"]);
        $ret[5]=$row["IDTipologia"];
        $ret[6]=$row["LavoroChiuso"];
        $ret[7]=$row["IDEdificio"];
        $ret[8]=$row["IDResponsabile"];
        $ret[9]=($row["IDIstruttore"]);
        $ret[10]=$row["Note"];    
        $ret[11]=$row["fl_complementare"]; 
        $ret[12]=$row["id_lavoro_origine"]; 
        $ret[13]=$row["importo_disponibile"]; 
    }  
    
    echo json_encode($ret);
}
