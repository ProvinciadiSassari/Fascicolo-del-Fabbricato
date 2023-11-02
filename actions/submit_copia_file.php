<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['source']) && isset($_POST['dest']) && isset($_POST['file']) && isset($_POST['desc_file']) && isset($_POST['id_fabbricato'])) {
    $ret=0;   
    
    $dir=trim($_POST['dest']);
    $file=trim($_POST['file']);
    $desc_file=trim($_POST["desc_file"]);
    $path=$dir.$file;
    
    //prima cancello l'eventuale record per lo stesso file
    $query="delete from descrizione_files_edifici where Percorso_completo='$path'";
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    
    //ora inserisco il file
    $query="INSERT INTO descrizione_files_edifici
            (
             IDEdificio,
             Percorso_completo,
             FILE,
             Descrizione,
             UtenteArchiv,
             data_ultima_modifica)
    VALUES (
        ".$_POST['id_fabbricato'].",
        '$path',
        '$file',
        ".conv_string2sql($desc_file).",
        '".$_SESSION['username']."',now())";
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    
    $ret=1;
    
    $source=$_SERVER["DOCUMENT_ROOT"].$_POST['source'].$file;
    $destination=$_SERVER["DOCUMENT_ROOT"].$_POST['dest'].$file;
    
    if (!copy($source, $destination)) {
        $ret=2;
    }
    
    $query="SELECT a006_ID FROM utenti_lav WHERE a006_livello=2";
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    
    while ($row = mysql_fetch_assoc($result)){
        $id_utente=$row["a006_ID"];
        
        if ($id_utente==$_SESSION["iduser"]) continue;
        
        $query1="INSERT INTO avvisi (id_utente, id_fabbricato, filename)
                VALUES ($id_utente, ".$_POST['id_fabbricato'].", '$file')";
        $result1 = mysql_query($query1);
        if (!$result1){
                die ("Could not query the database: <br />". mysql_error());
        }
    }        
    
    echo $ret;
}

?>


