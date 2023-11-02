<?php
session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_GET["id_edificio"])) {
    $id_fabbricato=$_GET["id_edificio"];     
}
else $id_fabbricato=0;
?>

<ul>
        <?
            $query="select distinct s.id_struttura, s.desc_struttura from strutture s, sottostrutture ss, legame_edifici_sottostrutture l where s.id_struttura=ss.id_struttura and ss.id_sottostruttura=l.id_sottostruttura and l.id_edificio=$id_fabbricato  
                    order by s.desc_struttura";
            $result = mysql_query($query);
            if (!$result){
                    die ("Could not query the database: <br />". mysql_error());
            }
            while ($row = mysql_fetch_assoc($result)){
                echo "<li class='jstree-open' dir='".$row['id_struttura']."#0'>";
                echo "<a href='#'>".$row['desc_struttura']."</a>";
                echo "<ul>";
                $query1="select ss.id_sottostruttura, ss.desc_sottostruttura from sottostrutture ss,legame_edifici_sottostrutture l where ss.id_struttura=".$row['id_struttura']." and ss.id_sottostruttura=l.id_sottostruttura and l.id_edificio=$id_fabbricato order by ss.desc_sottostruttura";
                $result1 = mysql_query($query1);
                if (!$result1){
                        die ("Could not query the database: <br />". mysql_error());
                }
                while ($row1 = mysql_fetch_assoc($result1)){
                    echo "<li id='".$row['id_struttura']."#".$row1['id_sottostruttura']."'>";
                    echo "<a href='#'>".$row1['desc_sottostruttura']."</a>";
                    echo "</li>";
                }
                echo "</ul>";
                echo "</li>";                        
            }

        ?>		
</ul>

