<?php

session_start();
require_once('../conf.inc.php');

$utility = new Utility();
$utility->connetti();
  
$ret="";
$query="SELECT  c.id_fabbricato,c.longitudine,c.latitudine,f.descrizione_fabbricato,f.indirizzo_fabbricato,f.cap_fabbricato,m.desc_comune
         from coordinate_fabbricati c, fabbricati f, comuni m where c.id_fabbricato=f.id_fabbricato and f.id_comune=m.id_comune ";
if ($_POST["id_competenza"]>0) {
    $query.=" and m.id_competenza=".$_POST["id_competenza"];
}
if ($_POST["id_comune"]>0) {
    $query.=" and m.id_comune=".$_POST["id_comune"];
}
if ($_POST["id_fabbricato"]>0) {
    $query.=" and c.id_fabbricato=".$_POST["id_fabbricato"];
}
$query.=" order by id_fabbricato";

$result = mysql_query($query);
if (!$result) {
    die("Could not query the database: 3<br />" . mysql_error());
}
$i=0;
while ($row = mysql_fetch_assoc($result)){
    $ret[$i]=$row["id_fabbricato"];$i++;
    $ret[$i]=utf8_encode($row["descrizione_fabbricato"]);$i++;
    $ret[$i]=$row["latitudine"];$i++;
    $ret[$i]=$row["longitudine"];$i++;

    $ret[$i]=utf8_encode($row["descrizione_fabbricato"])."<br /><br />".utf8_encode($row["indirizzo_fabbricato"])."<br /><br />".$row["cap_fabbricato"]." ".utf8_encode($row["desc_comune"]);$i++;
} 


echo json_encode($ret);


