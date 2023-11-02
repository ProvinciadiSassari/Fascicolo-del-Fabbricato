<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

$id_responsabile=0;$id_istruttore=0;
if (!is_numeric($_POST["sel_responsabile"])) {
    $query="INSERT INTO responsabili_procedimento
            (Responsabile)
            VALUES (".conv_string2sql($_POST["sel_responsabile"]).")"; 
        
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }
        $id_responsabile =  mysql_insert_id();
}
else $id_responsabile = $_POST["sel_responsabile"];

if (!is_numeric($_POST["sel_istruttore"])) {
    $query="INSERT INTO istruttori_pratica
            (Istruttore)
            VALUES (".conv_string2sql($_POST["sel_istruttore"]).")"; 
        
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }
        $id_istruttore =  mysql_insert_id();
}
else $id_istruttore = $_POST["sel_istruttore"];

$query="INSERT INTO lavori
            (Descrizione,
             Codice_contratto,
             IDCategoria,
             IDTipologia,
             IDEdificio,             
             IDResponsabile,
             IDIstruttore,
             data_contratto,
             Note,
             Utente,             
             LavoroChiuso,
             fl_complementare,
             id_lavoro_origine,
             importo_disponibile)
        VALUES (".conv_string2sql($_POST["ta_descrizione"]).",
            ".conv_string2sql($_POST["inp_codice_contratto"]).",
                ".$_POST["sel_categoria"].",
                ".$_POST["sel_tipologia"].",  
                ".$_POST["id_fabbricato"].",  
                ".$id_responsabile.", 
                ".$id_istruttore.",    
                '".$utility->convertDateToDB($_POST["inp_data_contratto"])."', 
                ".conv_string2sql($_POST["ta_note_lavoro"]).",
                ".$_SESSION["iduser"].", 
                0,
                ".$_POST["chk_complementare"].",  
                ".$_POST["sel_lavoro_origine"].", 
                ".$_POST["inp_disponibilita"].")";

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
} 

$id_lavoro=  mysql_insert_id();

$query="INSERT INTO imprese_incaricate (IDLavoro)
VALUES ($id_lavoro)";

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}

$query="INSERT INTO tempi_esecuzione (IDLavoro)
VALUES ($id_lavoro)";

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}

$path_root_lavoro='/public/gestlavori/Archivio Lavori/L'.$id_lavoro."/";
$path=$_SERVER['DOCUMENT_ROOT'].$path_root_lavoro;
mkdir($path,0777);

$path1=$path."Atti Amministrativi/";
mkdir($path1,0777);

$path1=$path."Progetto/";
mkdir($path1,0777);

$path1=$path."Atti Amministrativi/Atti vari/";
mkdir($path1,0777);

$path1=$path."Atti Amministrativi/Delibere/";
mkdir($path1,0777);

$path1=$path."Atti Amministrativi/Determinazioni/";
mkdir($path1,0777);

$path1=$path."Progetto/Disegni/";
mkdir($path1,0777);

$path1=$path."Progetto/Elaborati amministrativi/";
mkdir($path1,0777);

$path1=$path."Progetto/Elaborati contabili/";
mkdir($path1,0777);

$path1=$path."Progetto/Piano della sicurezza/";
mkdir($path1,0777);

$path1=$path."Atti Amministrativi/Atti vari/Verbali/";
mkdir($path1,0777);


echo $id_lavoro;

