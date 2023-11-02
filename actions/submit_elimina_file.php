<?php

session_start();
require_once('../conf.inc.php');
require_once('../conv.php');
$utility = new Utility();
$utility->connetti();

if (isset($_POST['dir'])) {
   
    
    $path=$_POST['dir'];

    //prima cancello l'eventuale record per lo stesso file
    $query="delete from descrizione_files_edifici where Percorso_completo='$path'";
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }   
    
    $path=$_SERVER["DOCUMENT_ROOT"].$path;
    
    if (!file_exists($path)) {
        unlink($path);
    }
    
}


