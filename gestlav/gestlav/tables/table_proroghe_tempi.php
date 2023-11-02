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

      $('#tab_proroghe').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "language": {
            "url": 'language/Italian.json'
        } 
    });   
});
</script>

    <table class="table table-striped table-bordered" width="100%" cellspacing="0" id="tab_proroghe">
       <thead><tr><th align='center'>Data Proroga</th><th align='center'>Periodo Proroga</th><th align='center'>Modifica</th></tr></thead>
       <tbody>
   <?php    
   
   $query = "SELECT
            IDProroghe,           
            Periodo,
            Data_proroga,
            IDUnitaTempo
        FROM proroghe
        WHERE IDLavoro=$id_lavoro";

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}
   $tot_pg=0;$tot_pm=0;
    while ($row = mysql_fetch_assoc($result)){
        
        $IDProroghe=$row["IDProroghe"];
        $Periodo=$row["Periodo"];
        $Data_proroga=$utility->convertDateToHTML($row["Data_proroga"]);    
        $IDUnitaTempo_p=$row["IDUnitaTempo"];  
        $desc_unitatempo=$utility->getDescrizioneUnitaTempo($IDUnitaTempo_p);
        
        $desc_periodo=$Periodo;
        if ($Periodo) {            
            $desc_periodo.="  ".$desc_unitatempo;
            if ($IDUnitaTempo_p==1) //giorni
               $tot_pg+=$Periodo; 
            if ($IDUnitaTempo_p==2) //mesi
               $tot_pm+=$Periodo;
        }
        
        echo "<tr>";		         
        echo "<td align='center'>".$Data_proroga."</td>";
        echo "<td align='center'>$desc_periodo</td>";    
        echo "<td align='center'><img src='images/modifica.png' class='hand' id='img_mod_proroga' alt='$IDProroghe'/></td>";
        echo "</tr>";
                
    }
    ?>
    </tbody>
    <tfoot>
        <?php
        $desc="";
        if ($tot_pm>0 && $tot_pg>0) {
            $desc="$tot_pm mesi e $tot_pg giorni";
        }
        else if ($tot_pm>0) {
            $desc="$tot_pm mesi";
        }
        else if ($tot_pg>0) {
            $desc="$tot_pg giorni";
        }
        ?>
        <tr style='background: #aa99bb; font-weight: bold;'><td>TOTALI</td><td align='center'><?=$desc;?></td></tr>
    </tfoot>
   </table>