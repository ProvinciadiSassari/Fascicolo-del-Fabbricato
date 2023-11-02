<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['old_dir']) && isset($_POST['new_dir'])) {
    $ret=1;   
    
    $old_dir_simple = $_POST['old_dir'];
    $new_dir_simple = $_POST['new_dir'];
    $old_dir=$_SERVER['DOCUMENT_ROOT'].$_POST['old_dir'];
    $new_dir=$_SERVER['DOCUMENT_ROOT'].$_POST['new_dir'];  
        
    
    if (is_dir($new_dir)) {
	$ret=2;
    }
    else if (!rename($old_dir,$new_dir)) {
        $ret=0;
    }
   
    //RINOMINARE ANCHE IL PERCORSO NELLA TABELLA
    $query="update descrizione_files_edifici 
            set Percorso_completo=REPLACE(Percorso_completo,'$old_dir_simple','$new_dir_simple')
            where Percorso_completo like '$old_dir_simple%'";
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    
    echo $ret;
}



