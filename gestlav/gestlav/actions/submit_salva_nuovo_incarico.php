<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();


$query="select count(*) as conta from incaricati where IDLavoro=".$_POST["id_lavoro"]." and IDIncarico=".$_POST["sel_incarico"];
$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}
$conta=0;
if ($row = mysql_fetch_assoc($result)){
    $conta=$row["conta"];
}

if ($conta==0) {    

$query="INSERT INTO incaricati
            (
             IDLavoro,
             IDIncarico,
             IDProfess_incaricato,             
             Numero_delibera,
             Data_delibera,
             Numero_convenzione,
             Data_convenzione,
             Note)
        VALUES (".$_POST["id_lavoro"].",
                ".$_POST["sel_incarico"].",
                ".$_POST["sel_incaricato"].",
                ".conv_string2sql($_POST["inp_num_delibera"]).",
                '".$utility->convertDateToDB($_POST["inp_data_delibera"])."', 
                ".conv_string2sql($_POST["inp_num_convenzione"]).",  
                '".$utility->convertDateToDB($_POST["inp_data_convenzione"])."',                
                ".conv_string2sql($_POST["ta_note_incarichi"]).")";

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}

}

echo $conta;


