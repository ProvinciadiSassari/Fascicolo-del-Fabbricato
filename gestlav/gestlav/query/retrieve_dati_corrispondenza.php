<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

$ret="";
    
   
if (isset($_POST["id_corrispondenza"])) {
    $id_corrispondenza=$_POST["id_corrispondenza"];     
}
else $id_corrispondenza=0;

$ret[0]=$id_corrispondenza;   

$query = "SELECT                                    
            c.IDLavoro,
            c.DataProt_UffProt,
            c.NumProt_UffProt,
            c.DataProt_Interno,
            c.NumProt_Interno,           
            c.Oggetto,
            c.Note,
            c.Mittente,
            c.Utente,
            c.Destinatario,
            c.IDAnno,
            c.NomeFileDoc,
            c.UtenteInsArchivio,
            c.DatiSensibili,
            c.DatiComplementari1,
            c.DatiComplementari2,
            c.DatiComplementari3,
            c.DatiComplementari4
        FROM corrispondenza c
        WHERE IDCorrispondenza=$id_corrispondenza";


$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}

if ($row = mysql_fetch_assoc($result)){
    
    $ret[1]=$row["IDLavoro"];
    $ret[2]=$utility->convertDateToHTML($row["DataProt_UffProt"]);
    $ret[3]=$row["NumProt_UffProt"]; 
    $ret[4]=$utility->convertDateToHTML($row["DataProt_Interno"]);
    $ret[5]=$row["NumProt_Interno"];
    $ret[6]=$row["Oggetto"];       
    $ret[7]=$row["Note"]; 
    $ret[8]=$row["Mittente"];
    $ret[9]=$row["Utente"];    
    $ret[10]=$row["Destinatario"];
    $ret[11]=$row["IDAnno"];
    $ret[12]=$row["NomeFileDoc"];   
    $ret[13]=$row["UtenteInsArchivio"]; 
    $ret[14]=$row["DatiSensibili"]; 
    $ret[15]=$row["DatiComplementari1"]; 
    $ret[16]=$row["DatiComplementari2"]; 
    $ret[17]=$row["DatiComplementari3"]; 
    $ret[18]=$row["DatiComplementari4"];    
}

    
    echo json_encode($ret);

