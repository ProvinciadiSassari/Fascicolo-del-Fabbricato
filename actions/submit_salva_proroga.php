<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

$ret=0;

$data_proroga=$utility->convertDateToDB($_POST["data_proroga"]);

if ($_POST["tipo_operazione"]=="ins") {

    $query="INSERT INTO proroghe (
            IDLavoro, Periodo, Data_proroga, IDUnitaTempo
          ) 
          VALUES
            (
              ".$_POST["id_lavoro"].", ".$_POST["periodo_proroga"].", '$data_proroga', ".$_POST["id_unita_tempo"]."
            ) ";

    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
}
else if ($_POST["tipo_operazione"]=="mod") {
    
    $query="update proroghe 
                set Periodo=".$_POST["periodo_proroga"].", Data_proroga='$data_proroga', IDUnitaTempo=".$_POST["id_unita_tempo"]."
            where IDProroghe=".$_POST['id_proroga'];

    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
}

echo $ret;

