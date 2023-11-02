<?php
session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();


if (isset($_GET["id_categoria"])) {
    $id_categoria=$_GET["id_categoria"];     
}
else $id_categoria=0;

$query="select IDTipologia,Tipologia from tipologie where IDCategoria=$id_categoria";
$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}
echo "<select id='sel_tipologia' class='form-control'>";
echo "<option>- Seleziona -</option>";
while ($row = mysql_fetch_assoc($result)){
    $id=$row["IDTipologia"];
    $val=$row["Tipologia"];
    echo "<option value='$id'>$val</option>";    
} 
echo "</select>"; 
