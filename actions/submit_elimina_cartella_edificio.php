<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();


if (isset($_POST['dir'])) {
    $id_fabbricato=$_POST['id_fabbricato'];
    $ret=1;  
    $path=$_POST['dir']; 
    $dir=$_SERVER['DOCUMENT_ROOT'].$path;
    $utility->rrmdir_edificio($dir,$id_fabbricato);
                   
     echo $ret;
}
