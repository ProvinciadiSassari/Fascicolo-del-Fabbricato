<?php
session_start();
require_once('../conf.inc.php');
require_once('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_GET['id_comune'])) {
    $comune_istituto = $_GET['id_comune'];
} else {
    $comune_istituto = 0;
}

?>

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {                

       $('#tab_istituti').dataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "pagingType": "simple",
        "language": {
            "url": 'language/Italian.json'
        } 
    }); 
    
     $(document).on("click","#img_mod",function(evt){
        var id_fabbricato=$(this).attr("alt");
        window.location="scheda_istituto.php?id_fabbricato="+id_fabbricato;
    });
    
   
});
</script>

<table class="table table-striped table-bordered" width="100%" cellspacing="0" id="tab_istituti">
       <thead><tr><th align='center'>ID</th><th>Fabbricato</th><th>Indirizzo</th><th>Comune</th><th align='center'>Scheda</th></tr></thead><tbody>
	
	<?php
        $query = "SELECT f.id_fabbricato,f.descrizione_fabbricato,f.indirizzo_fabbricato,c.desc_comune
                    FROM fabbricati f,comuni c 
                    where f.id_comune=c.id_comune ";
        if ($_SESSION["idcompetenza"]>0) {
                $query.=" and c.id_competenza=".$_SESSION["idcompetenza"];
            }
            
        if ($comune_istituto>0)
            $query.=" and f.id_comune=$comune_istituto";

	$result = mysql_query($query);
	if (!$result){
		die ("Could not query the database: <br />". mysql_error());
	}
	while ($row = mysql_fetch_assoc($result)){
		
                $id_fabbricato = $row["id_fabbricato"];   
		$fabbricato = $row["descrizione_fabbricato"];                
		$indirizzo = $row["indirizzo_fabbricato"]; 		
		$comune = $row["desc_comune"];				

		echo "<tr>";
		echo "<td align='center'>$id_fabbricato</td>";
		echo "<td>".conv_string2html($fabbricato)."</td>";
		echo "<td>".conv_string2html($indirizzo)."</td>";		
		echo "<td>".conv_string2html($comune)."</td>";
	
                echo "<td align='center'><img src='images/document.png' class='hand' id='img_mod' style='width:24px;' alt='$id_fabbricato'/></td>"; 
		echo "</tr>";		
	}      
        ?>
	</tbody>
    </table>