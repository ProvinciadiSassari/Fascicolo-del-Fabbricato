<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['dir']) && isset($_POST['file']) && isset($_POST['desc_file']) && isset($_POST['id_lavoro'])) {
    $ret=0;   
    
    $dir=$_POST['dir'];
    $file=$_POST['file'];
    $desc_file=$_POST["desc_file"];
    $path=$dir.$file;
    
    //prima cancello l'eventuale record per lo stesso file
    $query="delete from descrizione_files where Percorso_completo='$path'";
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    
    //ora inserisco il file
    $query="INSERT INTO descrizione_files
            (
             IDLavoro,
             Percorso_completo,
             FILE,
             Descrizione,
             UtenteArchiv,
             data_ultima_modifica)
    VALUES (
        ".$_POST['id_lavoro'].",
        '$path',
        ".conv_string2sql($file).",
        ".conv_string2sql($desc_file).",
        '".$_SESSION['username']."',now())";
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    else $ret=1;
    
    echo $ret;
}

