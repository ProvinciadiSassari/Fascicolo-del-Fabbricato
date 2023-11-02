<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

$ret="";

if (isset($_POST['id_lavoro'])) {
    $query = "SELECT  
            c.IDImpresa,
            c.Numero_delibera,
            c.Data_delibera,
            c.Legale_rappresentante,
            c.Direttore_cantiere,
            c.Responsabile_sicurezza,
            c.Contratto_Rep,
            c.Numero_contratto,
            c.Data_contratto,
            c.Base_asta,
            c.Ribasso,
            c.Oneri_sicurezza,
            c.Importo_netto,
            c.Note,
            i.Impresa,
            i.Titolare,
            i.IDComune,
            i.Indirizzo,
            i.Tel1,
            i.Tel2,
            i.Fax,
            i.CodFisc_PartIVA,
            i.email,
            i.URL
        FROM imprese_incaricate c,imprese i
        WHERE c.IDLavoro=".$_POST['id_lavoro']." AND c.IDImpresa=i.IDImpresa";
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    if ($row = mysql_fetch_assoc($result)){
        $ret[1]=$row["IDImpresa"];
        $ret[2]=$row["Numero_delibera"];
        $ret[3]=$utility->convertDateToHTML($row["Data_delibera"]);
        $ret[4]=$row["Legale_rappresentante"];
        $ret[5]=$row["Direttore_cantiere"];
        $ret[6]=$row["Responsabile_sicurezza"];
        $ret[7]=$row["Contratto_Rep"];
        $ret[8]=$row["Numero_contratto"];
        $ret[9]=$utility->convertDateToHTML($row["Data_contratto"]);
        $ret[10]=$row["Base_asta"];
        $ret[11]=$row["Ribasso"];
        $ret[12]=$row["Oneri_sicurezza"];
        $ret[13]=$row["Importo_netto"];
        $ret[14]=$row["Note"];
        $ret[15]=($row["Impresa"]);
        $ret[16]=($row["Titolare"]);
        $ret[17]=$row["IDComune"];
        $ret[18]=($row["Indirizzo"]);
        $ret[19]=$row["Tel1"];
        $ret[20]=$row["Tel2"];
        $ret[21]=$row["Fax"];
        $ret[22]=$row["CodFisc_PartIVA"];
        $ret[23]=($row["email"]);
        $ret[24]=$row["URL"];                  
    }  
    
    echo json_encode($ret);
}



