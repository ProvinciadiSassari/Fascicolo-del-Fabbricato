<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

$query="";
$ret=0;

for ($i=0;$i<sizeof($_POST["names"]);$i++) {

   $filename=$_POST["names"][$i];
   $dir=$_POST['dire2'].$filename;

    $query="INSERT INTO descrizione_files_edifici (
            IDEdificio, Percorso_completo, file, Descrizione, UtenteArchiv, Catalogazione, data_scadenza_documento, fl_rinnovato, data_ultima_modifica
          )
          VALUES
            (
              ".$_POST["id_fabbricato"].", ".conv_string2sql($dir).", ".conv_string2sql($filename).", ".conv_string2sql($filename).", '".$_SESSION["username"]."', null, '0000-00-00', 0, now()
            )";
    
    $result = mysql_query($query);
    if (!$result){
            die ($query."Could not query the database: ins <br />". mysql_error());
    } 
}

echo $ret;