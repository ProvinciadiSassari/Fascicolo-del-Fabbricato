<?php
session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();


if (isset($_GET["id_lavoro"])) {
    $id_lavoro=$_GET["id_lavoro"];     
}
else $id_lavoro=0;
?>

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {                

    
     $('#tab_quadro_1_pre').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "bInfo": false,
        "bFilter": false,
        "bPaginate": false,    
        "language": {
            "url": 'language/Italian.json'
        } 
    });   
    
    $('#tab_quadro_2_pre').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "bInfo": false,
        "bFilter": false,
        "bPaginate": false,    
        "language": {
            "url": 'language/Italian.json'
        } 
    });  
      
    $('#tab_quadro_3_pre').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "bInfo": false,
        "bFilter": false,
        "bPaginate": false,    
        "language": {
            "url": 'language/Italian.json'
        } 
    });     
    
    $("#but_stampa_quadro").click(function(e){
        window.location="lavori/stampa_quadro_economico_old.php?id_lavoro=<?=$id_lavoro;?>";
     }); 
        
});
</script>

<div id="div_pagamenti">
    <button id="but_stampa_quadro" class="btn btn-warning btn-sm" type="button" style="margin-left:15px;">Stampa Quadro Economico</button>
    <hr />
    <fieldset>
        <legend>Lavori</legend>
    <table class="table table-striped table-bordered" width="100%" cellspacing="0" id="tab_quadro_1_pre" >
       <thead><tr><th>Descrizione</th><th>Progetto</th><th>Aggiudicazione</th><th>Ultimo Q.E. disp.</th><th>Finale</th><th>Disponibilità</th></tr></thead>
       <tbody>
	
	<?
        $query = "SELECT
                    IDQuadroEcon, 
                    Descrizione,
                    Progetto,
                    Aggiudicazione,
                    Ultimo_QE_Disponibile,
                    Finale,
                    Disponibilita  
                FROM quadro_economico
                WHERE IDLavoro=$id_lavoro AND IDGruppo=1";
        
	// Execute the query
	$result = mysql_query($query);
	if (!$result){
		die ("Could not query the database: <br />". mysql_error());
	} 
        $t1=0;$t2=0;$t3=0;$t4=0;$t5=0;
	while ($row = mysql_fetch_assoc($result)){
				
		$IDQuadroEcon = $row["IDQuadroEcon"];                					           
                $Descrizione = $row["Descrizione"];
                $Progetto = $row["Progetto"];
                $Aggiudicazione = $row["Aggiudicazione"];
                $Ultimo_QE_Disponibile = $row["Ultimo_QE_Disponibile"];
                $Finale = $row["Finale"];
		$Disponibilita = $row["Disponibilita"];
                
		echo "<tr>";		         
                echo "<td>".utf8_encode($Descrizione)."</td>";
                echo "<td align='right'>$Progetto</td>";
		echo "<td align='right'>".$Aggiudicazione."</td>";     
                echo "<td align='right'>$Ultimo_QE_Disponibile</td>";
		echo "<td align='right'>".$Finale."</td>";     
                echo "<td align='right'>$Disponibilita</td>";		   
		echo "</tr>";  
                
                $t1+=$Progetto;
                $t2+=$Aggiudicazione;
                $t3+=$Ultimo_QE_Disponibile;
                $t4+=$Finale;
                $t5+=$Disponibilita;
	}
        ?>
	</tbody>    
        <tfoot>
            <tr style='background: #aa99bb; font-weight: bold;'><td>TOTALI</td><td align='right'><?=round($t1,2);?></td><td align='right'><?=round($t2,2);?></td><td align='right'><?=round($t3,2)?></td><td align='right'><?=round($t4,2);?></td><td align='right'><?=round($t5,2);?></td></tr>
        </tfoot>
    </table>
    </fieldset> 
    <fieldset>
        <legend>Somme a disposizione</legend>
    <table class="table table-striped table-bordered" width="100%" cellspacing="0" id="tab_quadro_2_pre">
       <thead><tr><th>Descrizione</th><th>Progetto</th><th>Aggiudicazione</th><th>Ultimo Q.E. disp.</th><th>Finale</th><th>Disponibilità</th></tr></thead>
       <tbody>
	
	<?
        $query = "SELECT
                    IDQuadroEcon, 
                    Descrizione,
                    Progetto,
                    Aggiudicazione,
                    Ultimo_QE_Disponibile,
                    Finale,
                    Disponibilita  
                FROM quadro_economico
                WHERE IDLavoro=$id_lavoro AND IDGruppo=2";
        
	// Execute the query
	$result = mysql_query($query);
	if (!$result){
		die ("Could not query the database: <br />". mysql_error());
	} 
        $t11=0;$t22=0;$t33=0;$t44=0;$t55=0;
	while ($row = mysql_fetch_assoc($result)){
				
		$IDQuadroEcon = $row["IDQuadroEcon"];                					           
                $Descrizione = $row["Descrizione"];
                $Progetto = $row["Progetto"];
                $Aggiudicazione = $row["Aggiudicazione"];
                $Ultimo_QE_Disponibile = $row["Ultimo_QE_Disponibile"];
                $Finale = $row["Finale"];
		$Disponibilita = $row["Disponibilita"];
                
		echo "<tr>";		         
                echo "<td>".utf8_encode($Descrizione)."</td>";
                echo "<td align='right'>$Progetto</td>";
		echo "<td align='right'>".$Aggiudicazione."</td>";     
                echo "<td align='right'>$Ultimo_QE_Disponibile</td>";
		echo "<td align='right'>".$Finale."</td>";     
                echo "<td align='right'>$Disponibilita</td>";		   
		echo "</tr>";  
                
                $t11+=$Progetto;
                $t22+=$Aggiudicazione;
                $t33+=$Ultimo_QE_Disponibile;
                $t44+=$Finale;
                $t55+=$Disponibilita;
	}
        ?>
	</tbody>    
        <tfoot>
            <tr style='background: #aa99bb; font-weight: bold;'><td>TOTALI</td><td align='right'><?=round($t11,2);?></td><td align='right'><?=round($t22,2);?></td><td align='right'><?=round($t33,2)?></td><td align='right'><?=round($t44,2);?></td><td align='right'><?=round($t55,2);?></td></tr>
        </tfoot>
    </table>
    </fieldset>
    <fieldset>
        <legend>Somme dei totali parziali</legend>
    <table class="table table-striped table-bordered" width="100%" cellspacing="0" id="tab_quadro_3_pre" >
       <thead><tr><th></th><th>Progetto</th><th>Aggiudicazione</th><th>Ultimo Q.E. disp.</th><th>Finale</th><th>Disponibilità</th></tr></thead>                
            <tr style='background: #aa99bb; font-weight: bold;'><td>TOTALI</td><td align='right'><?=round(($t1+$t11),2);?></td><td align='right'><?=round(($t2+$t22),2);?></td><td align='right'><?=round(($t3+$t33),2);?></td><td align='right'><?=round(($t4+$t44),2);?></td><td align='right'><?=round(($t5+$t55),2);?></td></tr>        
    </table>
    </fieldset>

<? 
$query="select Quadro_Economico from note where IDLavoro=$id_lavoro";
$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
} 
$annotazioni="";
while ($row = mysql_fetch_assoc($result)){
    $annotazioni.=$row["Quadro_Economico"];
}
?>
<br />    
<label class="control-label" for="ta_annotazioni">Annotazioni</label>     
<textarea type="text" id="ta_annotazioni" rows="8" class="form-control" value="" ><?=utf8_encode($annotazioni);?></textarea>     
    
</div>
<div id="div_dettaglio_pagamento">
    
</div>
    

