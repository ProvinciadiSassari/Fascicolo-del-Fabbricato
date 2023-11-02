<?php
session_start();
require_once('../conf.inc.php');
require_once('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_GET['id_lavoro'])) {
    $id_lavoro = $_GET['id_lavoro'];
} else {
    $id_lavoro = 0;
}

?>

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {                

     
      $('#tab_sospensioni').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "language": {
            "url": 'language/Italian.json'
        } 
    });   
    
});
</script>

<table class="table table-striped table-bordered" width="100%" cellspacing="0" id="tab_sospensioni">
       <thead><tr><th align='center'>Data Sospensione</th><th align='center'>Data Ripresa</th><th align='center'>Totale Giorni</th><th align='center'>Modifica</th></tr></thead>
       <tbody>
   <?php 

    $query = "SELECT
            IDSospensioni,            
            Dal,
            Ripresa,
            IDUnitaTempo
        FROM sospensioni
        WHERE IDLavoro=$id_lavoro";

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}

   $tot_s=0;
    while ($row = mysql_fetch_assoc($result)){

        $IDSospensioni=$row["IDSospensioni"];
        if (!empty($row["Ripresa"]))
            $diff=$utility->fDateDiff(strtotime($row["Dal"]), strtotime($row["Ripresa"]));
        else $diff=0; 
        
        $Dal=$utility->convertDateToHTML($row["Dal"]);
        $Ripresa=$utility->convertDateToHTML($row["Ripresa"]);    
        
        
        echo "<tr>";		         
        echo "<td align='center'>".$Dal."</td>";
        echo "<td align='center'>$Ripresa</td>";
        echo "<td align='center'>".$diff."</td>";    
        echo "<td align='center'><img src='images/modifica.png' class='hand' id='img_mod_sospensioni' alt='$IDSospensioni'/></td>";
        echo "</tr>";
        
        $tot_s+=$diff;
    }
    ?>
    </tbody>
    <tfoot>
        <tr style='background: #aa99bb; font-weight: bold;'><td><input type="hidden" id="tot_sospensioni" value="<?=$tot_s;?>"/></td><td>TOTALI</td><td align='center'><?=$tot_s;?></td></tr>
    </tfoot>
   </table>