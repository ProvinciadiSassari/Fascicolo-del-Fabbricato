<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

$ret=0;

$data_sospensione=$utility->convertDateToDB($_POST["data_sospensione"]);
$data_ripresa=$utility->convertDateToDB($_POST["data_ripresa"]);

if ($_POST["tipo_operazione"]=="ins") {

    $query="INSERT INTO sospensioni (
            IDLavoro, Dal, Ripresa, IDUnitaTempo
          ) 
          VALUES
            (
              ".$_POST["id_lavoro"].", '$data_sospensione', '$data_ripresa', 1
            ) ";

    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
}
else if ($_POST["tipo_operazione"]=="mod") {
    
    $query="update sospensioni 
                set Dal='$data_sospensione', Ripresa='$data_ripresa', IDUnitaTempo=1
            where IDSospensioni=".$_POST['id_sospensione'];

    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
}

echo $ret;

