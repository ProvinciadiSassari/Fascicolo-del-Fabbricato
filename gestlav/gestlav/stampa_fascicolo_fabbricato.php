<?php

session_start();
require_once('conf.inc.php');
include('conv.php');

header("Content-Type: application/msword");
header("Content-Disposition: attachment;Filename=fascicolo_fabbricato.doc");

$utility = new Utility();
$utility->connetti();

//echo "<pre>";
//print_r($_GET);
//echo "</pre>";
?>
<style>
    table {
        font-size:11pt;        
    }
    
</style>
<?
if (isset($_GET['id_fabbricato'])) {
    $id_fabbricato=$_GET['id_fabbricato'];
}
else $id_fabbricato=0;

$query="SELECT l.id_sottostruttura, l.desc_struttura_edificio,s.desc_struttura
        FROM legame_edifici_sottostrutture l,sottostrutture ss, strutture s
        where l.id_edificio=$id_fabbricato and l.id_sottostruttura=ss.id_sottostruttura and ss.id_struttura=s.id_struttura order by s.desc_struttura";

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}
$i=0;$desc_struttura_prec="";
while ($row = mysql_fetch_assoc($result)){
    $id_sottostruttura=$row["id_sottostruttura"];
    $desc_struttura_edificio=$row["desc_struttura_edificio"];
    $desc_struttura=$row["desc_struttura"];
    if ($desc_struttura!=$desc_struttura_prec) {
        $desc_struttura_prec=$desc_struttura;
        echo "<h2>$desc_struttura</h2>";
    }    
    echo utf8_decode($desc_struttura_edificio);
    echo "<br clear=all style='mso-special-character:line-break;page-break-before:always' />";
}

?>


<!--page break-->
<!--<br clear=all style='mso-special-character:line-break;page-break-before:always' />-->


