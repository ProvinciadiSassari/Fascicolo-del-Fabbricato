<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

$dir=$_POST['dire2'];
$tipo_operazione=$_POST['tipo_operazione'];
$query="";
$ret=0;

if ($tipo_operazione=="mod") {

        $query="UPDATE descrizione_files
                SET 
                    Descrizione = ".conv_string2sql($_POST["ta_descrizione_file_dett"]).",
                    Catalogazione = ".conv_string2sql($_POST["ta_catalogazione_file_dett"]).",           
                    data_scadenza_documento = '".$utility->convertDateToDB($_POST["data_scadenza_documento"])."',
                    fl_rinnovato=".$_POST["fl_rinnovato"].",  
                    UtenteArchiv = '".$_SESSION["username"]."',                       
                    data_ultima_modifica = now() 
                WHERE Percorso_completo='$dir'";

        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }   
}
else if ($tipo_operazione=="ins") {
    
    $query="INSERT INTO descrizione_files (
            IDLavoro, Percorso_completo, file, Descrizione, UtenteArchiv, Catalogazione, data_scadenza_documento, fl_rinnovato, data_ultima_modifica
          )
          VALUES
            (
              ".$_POST["id_lavoro"].", ".conv_string2sql($dir).", ".conv_string2sql($_POST["file_solo"]).", ".conv_string2sql($_POST["ta_descrizione_file_dett"]).", '".$_SESSION["username"]."', ".conv_string2sql($_POST["ta_catalogazione_file_dett"]).", '".$utility->convertDateToDB($_POST["data_scadenza_documento"])."', ".$_POST["fl_rinnovato"].", now()
            )";
    
    $result = mysql_query($query);
    if (!$result){
            die ($query."Could not query the database: ins <br />". mysql_error());
    } 
}

echo $ret;
