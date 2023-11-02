<?php
session_start();
require_once('../conf.inc.php');
include('../conv.php');


header("Content-Type: application/msword");
header("Content-Disposition: attachment;Filename=quadro_economico_old.doc");

$utility = new Utility();
$utility->connetti();



if (isset($_GET["id_lavoro"])) {
    $id_lavoro=$_GET["id_lavoro"];     
}
else $id_lavoro=0;

$id_lavoro_origine=$utility->getIDLavoroOrigine($id_lavoro);
$id_fabbricato=$utility->getIDFabbricatoFromIDLavoro($id_lavoro);

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div id="div_quadro_old">
        <div>
        <h2>QUADRO ECONOMICO</h2>        
    </div>
    <b>Fabbricato: <?=$utility->getDescFabbricato($id_fabbricato);?></b><br /><br />    
Lavoro N.<?=$id_lavoro;?><br /><br />    
<?=  utf8_decode($utility->getDescLavoro($id_lavoro));?>

   <h3>LAVORI</h3> 
    <table cellpadding="0" cellspacing="0" border="1" id="tab_quadro_1" name="tab_quadro_1">
       <thead style="font-size:10pt;"><tr><th>Descrizione</th><th>Progetto</th><th>Aggiudicazione</th><th>Ultimo Q.E. disp.</th><th>Finale</th><th>Disponibilit&agrave;</th></tr></thead>
       <tbody style="font-size:10pt;">
	
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
                echo "<td align='right' width='100'>$Progetto</td>";
		echo "<td align='right' width='100'>".$Aggiudicazione."</td>";     
                echo "<td align='right' width='100'>$Ultimo_QE_Disponibile</td>";
		echo "<td align='right' width='100'>".$Finale."</td>";     
                echo "<td align='right' width='100'>$Disponibilita</td>";		   
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

    <h3>Somme a disposizione</h3>    
    <table cellpadding="0" cellspacing="0" border="1" id="tab_quadro_2" name="tab_quadro_2">
       <thead style="font-size:10pt;"><tr><th>Descrizione</th><th>Progetto</th><th>Aggiudicazione</th><th>Ultimo Q.E. disp.</th><th>Finale</th><th>Disponibilit&agrave;</th></tr></thead>
       <tbody style="font-size:10pt;">
	
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
                echo "<td align='right' width='100'>$Progetto</td>";
		echo "<td align='right' width='100'>".$Aggiudicazione."</td>";     
                echo "<td align='right' width='100'>$Ultimo_QE_Disponibile</td>";
		echo "<td align='right' width='100'>".$Finale."</td>";     
                echo "<td align='right' width='100'>$Disponibilita</td>";		   
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
 
    <h3>Somme dei totali parziali</h3>      
    <table cellpadding="0" cellspacing="0" border="1" id="tab_quadro_3" name="tab_quadro_3">
       <thead><tr><th></th><th>Progetto</th><th>Aggiudicazione</th><th>Ultimo Q.E. disp.</th><th>Finale</th><th>Disponibilit&agrave;</th></tr></thead>                
            <tr style='background: #aa99bb; font-weight: bold;'><td>TOTALI</td><td align='right' width='100'><?=round(($t1+$t11),2);?></td><td align='right' width='100'><?=round(($t2+$t22),2);?></td><td align='right' width='100'><?=round(($t3+$t33),2);?></td><td align='right' width='100'><?=round(($t4+$t44),2);?></td><td align='right' width='100'><?=round(($t5+$t55),2);?></td></tr>        
    </table>    

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
<br /> <br /><br /> 
<h4>Annotazioni</h4> 
<?=($annotazioni);?>
    
</div>
