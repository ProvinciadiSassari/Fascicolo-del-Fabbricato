<?php
session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();


if (isset($_GET["id_struttura"])) {
    $id_struttura=$_GET["id_struttura"];     
}
else $id_struttura=0;

$query="select id_sottostruttura,desc_sottostruttura from sottostrutture where id_struttura=$id_struttura";
$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}
echo "Sottostruttura: <select id='sel_sottostrutture' class='form-control'>";
while ($row = mysql_fetch_assoc($result)){
    $id=$row["id_sottostruttura"];
    $val=$row["desc_sottostruttura"];
    echo "<option value='$id'>$val</option>";    
} 
echo "</select>"; 
