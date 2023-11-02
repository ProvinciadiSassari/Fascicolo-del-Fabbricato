<?php

session_start();
require_once('../../conf.inc.php');
include('../../conv.php');

$utility = new Utility();
$utility->connetti();

$ret=0;$val=true;

if (!empty($_POST["inp_email"]))
    $val=$utility->ControlloEmail($_POST["inp_email"]);

if ($val==true) {
        
if ($_POST["id_impresa"]>0) {
    
$query="UPDATE imprese
        SET 	
            Impresa = ".conv_string2sql($_POST["inp_impresa"]).",
            Titolare = ".conv_string2sql($_POST["inp_titolare"]).",
            IDComune = ".$_POST["sel_comune"].", 
            Indirizzo = ".conv_string2sql($_POST["inp_indirizzo"]).",
            Tel1 = ".conv_string2sql($_POST["inp_tel1"]).",
            Tel2 = ".conv_string2sql($_POST["inp_tel2"]).",
            Fax = ".conv_string2sql($_POST["inp_fax"]).",
            CodFisc_PartIVA = '".$_POST["inp_codice_fiscale"]."', 
            email = '".$_POST["inp_email"]."', 
            URL = '".$_POST["inp_sito"]."'  
        WHERE IDImpresa = ".$_POST["id_impresa"];

    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }         
}
else if ($_POST["id_impresa"]==0) {
    
    $query="INSERT INTO imprese
            (Impresa, Titolare,IDComune, Indirizzo, Tel1, Tel2, Fax, CodFisc_PartIVA, email, URL)
            VALUES (".conv_string2sql($_POST["inp_impresa"]).",".conv_string2sql($_POST["inp_titolare"]).",".$_POST["sel_comune"].",".conv_string2sql($_POST["inp_indirizzo"]).",
                    ".conv_string2sql($_POST["inp_tel1"]).",".conv_string2sql($_POST["inp_tel2"]).",".conv_string2sql($_POST["inp_fax"]).",
                    '".$_POST["inp_codice_fiscale"]."','".$_POST["inp_email"]."','".$_POST["inp_sito"]."')";   
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    } 
}
else if ($_POST["id_impresa"]<0) {
    
    $id_impresa=$_POST["id_impresa"]*-1;    
    
    $query="delete from imprese WHERE IDImpresa=$id_impresa";  
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    } 
}
}
else $ret=-1;

echo $ret;

?>


