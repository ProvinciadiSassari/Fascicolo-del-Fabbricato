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

      $('#tab_corrispondenza_us').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "language": {
            "url": 'language/Italian.json'
        } 
    });
});
</script>

<table class="table table-striped table-bordered" width="100%" cellspacing="0" id="tab_corrispondenza_us">
       <thead><tr><th align='center'>ID</th><th align='center'>Data Protocollo Interno</th><th align='center'>N. Protocollo Interno</th><th>Mittente</th><th>Destinatario</th><th>Dettaglio</th></tr></thead>
       <tbody>
	
	<?php
        $query = "SELECT
                    IDCorrispondenza,                   
                    DataProt_Interno,
                    NumProt_Interno,                                                  
                    Mittente,                   
                    Destinatario
                FROM corrispondenza                   
                WHERE IDLavoro=$id_lavoro AND Tipo=2";
        
	// Execute the query
	$result = mysql_query($query);
	if (!$result){
		die ("Could not query the database: <br />". mysql_error());
	}        
	while ($row = mysql_fetch_assoc($result)){
				
		$IDCorrispondenza = $row["IDCorrispondenza"];                			
		$DataProt_Interno = $utility->convertDateToHTML($row["DataProt_Interno"]); 
                $NumProt_Interno = $row["NumProt_Interno"];                
                $Mittente = $row["Mittente"];
                $Destinatario = $row["Destinatario"];
		
		echo "<tr>";
		echo "<td align='center'>$IDCorrispondenza</td>";
		echo "<td align='center'>".$DataProt_Interno."</td>";  
                echo "<td align='center'>".$NumProt_Interno."</td>";
                echo "<td>".utf8_encode($Mittente)."</td>";
                echo "<td>".utf8_encode($Destinatario)."</td>";
                 echo "<td align='center'><img src='images/document.png' class='hand' id='img_mod_corrispondenza_us' style='width:24px;' alt='".$IDCorrispondenza."'/></td>"; 
		echo "</tr>";              
	}
        ?>
	</tbody>    
    </table>