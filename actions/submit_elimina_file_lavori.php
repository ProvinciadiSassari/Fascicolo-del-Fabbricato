<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['dir']) && isset($_POST['file'])) {
   
    
    $dir=$_POST['dir'];
    $file=$_POST['file'];    
    $path=$dir.$file;
    
    unlink($_SERVER["DOCUMENT_ROOT"].$path);
    
    //prima cancello l'eventuale record per lo stesso file
    $query="delete from descrizione_files where Percorso_completo='$path'";
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }        
}

?>


