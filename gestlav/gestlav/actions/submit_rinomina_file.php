<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['dir']) && isset($_POST['file'])) {
   
    
    $dir=$_POST['dir'];
    $file=$_POST['file'];    
    $nuovo_file=$_POST['nuovo_file'];    
    $path_old=$dir.$file;
    $path_new=$dir.$nuovo_file;
    
    rename($_SERVER["DOCUMENT_ROOT"].$path_old,$_SERVER["DOCUMENT_ROOT"].$path_new);
    
    //prima cancello l'eventuale record per lo stesso file
    $query="update descrizione_files_edifici set Percorso_completo='$path_new', File='$nuovo_file' where Percorso_completo='$path_old'";
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }        
}


