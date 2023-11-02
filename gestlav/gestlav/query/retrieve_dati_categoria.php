<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['id_categoria'])) {
    $ret[0]=$_POST['id_categoria'];   
    $ret[1]="";    
    
    $query="SELECT Categoria
            FROM categorie
            where IDCategoria=".$_POST['id_categoria'];
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    if ($row = mysql_fetch_assoc($result)){
        $ret[1]=$row["Categoria"];        
    }  
    
    echo json_encode($ret);
}
