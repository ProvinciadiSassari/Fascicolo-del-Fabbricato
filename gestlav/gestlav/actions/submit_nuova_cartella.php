<?php

session_start();
require_once('../conf.inc.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['dir']) && isset($_POST['new_dir'])) {
    $ret=1;   
    
    $dir=$_POST['dir'];
    $new_dir=$_POST['new_dir'];   
    $path=$_SERVER['DOCUMENT_ROOT'].$dir.$new_dir;
    
    if (is_dir($path)) {
	$ret=2;
    }
    else if (!mkdir($path,0777)) {
        $ret=0;
    }
   
    echo $ret;
}


