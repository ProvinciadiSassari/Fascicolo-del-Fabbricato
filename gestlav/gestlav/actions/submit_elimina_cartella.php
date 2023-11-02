<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['dir'])) {
    $ret=1;   
    
    $dir=$_POST['dir'];    
    $path=$_SERVER['DOCUMENT_ROOT'].$dir;
    
    if (is_dir($path)) {
        if (!rmdir($path)) {
            $ret = 0;
        }
    } else {
        $ret = 2;
    }

    echo $ret;
}



