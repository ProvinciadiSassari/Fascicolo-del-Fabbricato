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

      $('#tab_finanziamenti').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "language": {
            "url": 'language/Italian.json'
        } 
    });
});
</script>

<table class="table table-striped table-bordered" width="100%" cellspacing="0" id="tab_finanziamenti" >
       <thead><tr><th align='center'>ID</th><th>Tipo finanziamento</th><th>Importo</th><th>Somma Erogata</th><th>Note</th><th>Dettaglio</th></tr></thead>
       <tbody>
	
	<?php
        $query = "SELECT                    
                    s.ID,                   
                    t.Tipo_Finanziamento,
                    s.Importo,
                    s.Somma_erogata,
                    s.Note
                    FROM finanziamenti s left join tipo_finanziamento t on (s.IDTipoFinanz=t.ID)
                WHERE s.IDLavoro=$id_lavoro";
        
	// Execute the query
	$result = mysql_query($query);
	if (!$result){
		die ("Could not query the database: <br />". mysql_error());
	}
        $TOT1=0;$TOT2=0;
	while ($row = mysql_fetch_assoc($result)){
				
		$ID = $row["ID"];                			
		$Tipo_Finanziamento = $row["Tipo_Finanziamento"];
                $Importo = $row["Importo"];
                $Somma_erogata = $row["Somma_erogata"];
                $Note = $row["Note"];
		
		echo "<tr>";
		echo "<td align='center'>$ID</td>";
		echo "<td>".$Tipo_Finanziamento."</td>";
                echo "<td align='right'>".round($Importo,2)."</td>";
                echo "<td align='right'>".round($Somma_erogata,2)."</td>";
                echo "<td width='250'>".utf8_encode($Note)."</td>";
                echo "<td align='center'><img src='images/document.png' class='hand' id='img_mod_finanziamento' style='width:24px;' alt='".$ID."'/></td>"; 
		echo "</tr>";
                $TOT1+=$Importo;
                $TOT2+=$Somma_erogata;
	}
        ?>
	</tbody>
        <tfoot>
            <tr style='background: #aa99bb; font-weight: bold;'>
                <td></td><td>TOTALI</td><td align='right'><?=round($TOT1,2);?></td><td align='right'><?=round($TOT2,2);?></td><td></td><td></td>
            </tr>
        </tfoot>
    </table>
