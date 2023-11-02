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

       $('#tab_pagamenti_sd').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "language": {
            "url": 'language/Italian.json'
        } 
    }); 
});
</script>

<table class="table table-striped table-bordered" width="100%" cellspacing="0" id="tab_pagamenti_sd" >
       <thead><tr><th align='center'>ID</th><th align='center'>Data</th><th>Descrizione</th><th>Dettaglio</th></tr></thead>
       <tbody>
	
	<?
        $query = "SELECT
                    IDPagamenti,  
                    Data_certificato,
                    Import_certificato,
                    IDQuadroEconom_Importo,
                    Aliquota_IVA,
                    IDQuadroEconom_AliqIVA,
                    Valuta,
                    Descrizione,
                    Note_mandato,
                    IDPropostaPagamento
                    FROM pagamenti                   
                WHERE IDLavoro=$id_lavoro AND IDCategPag=2";
        
	$result = mysql_query($query);
	if (!$result){
		die ("Could not query the database: <br />". mysql_error());
	}   
        $somma=0;
	while ($row = mysql_fetch_assoc($result)){				
            $IDPagamenti = $row["IDPagamenti"];                			
            $Data_certificato = $utility->convertDateToHTML($row["Data_certificato"]);                
            $Descrizione = $row["Descrizione"];
            $imp = $row["Import_certificato"];
                $iva = $row["Aliquota_IVA"];
                
            echo "<tr>";
            echo "<td align='center'>$IDPagamenti</td>";
            echo "<td align='center'>".$Data_certificato."</td>";              
            echo "<td width='550'>".$Descrizione."</td>";
            echo "<td align='center'><img src='images/document.png' class='hand' id='img_mod_pagamento_sd' style='width:24px;' alt='".$IDPagamenti."'/></td>"; 
            echo "</tr>";
            
            $somma+=$imp*(1+($iva/100));
	}
        ?>
	</tbody>    
    </table>
<input type="hidden" id="hid_somma" value="<?=$somma;?>"/>