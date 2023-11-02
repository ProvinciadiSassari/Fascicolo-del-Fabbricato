<?php
session_start();
require_once('../conf.inc.php');
include('../conv.php');

header("Content-Type: application/msword");
header("Content-Disposition: attachment;Filename=quadro_economico.doc");

$utility = new Utility();
$utility->connetti();



if (isset($_GET["id_lavoro"])) {
    $id_lavoro=$_GET["id_lavoro"];     
}
else $id_lavoro=0;

$id_lavoro_origine=$utility->getIDLavoroOrigine($id_lavoro);
$id_fabbricato=$utility->getIDFabbricatoFromIDLavoro($id_lavoro);

?>

<div id="div_quadro">   
    <div>
        <h2>QUADRO ECONOMICO</h2>        
    </div>
    <b>Fabbricato: <?=$utility->getDescFabbricato($id_fabbricato);?></b><br /><br />    
Lavoro N.<?=$id_lavoro;?><br /><br />    
<?=$utility->getDescLavoro($id_lavoro);?>
        <h3>LAVORI</h3> 
    <table cellpadding="1" cellspacing="0" border="1" id="tab_quadro_1" name="tab_quadro_1" >
       <thead style="font-size:10pt;"><tr><th>Descrizione</th><th>Perc (%)</th><th>Q.E. Progetto</th><th>Q.E. Contratto</th><th>Q.E. Perizia</th><th>Q.E. Collaudo</th></tr>
              
       </thead>
       <tbody style="font-size:10pt;">
	<?
        $query="SELECT data_qe_progetto, data_qe_contratto, data_qe_perizia, data_qe_collaudo
            FROM legame_lavori_quadri_economici where id_lavoro=".$id_lavoro;
    
            $result = mysql_query($query);
            if (!$result){
                    die ("Could not query the database: <br />". mysql_error());
            }
            if ($row = mysql_fetch_assoc($result)){ 
                echo "<tr style='font-size:8pt; font-weight:bold;'><td></td><td></td><td>Data ".$utility->convertDateToHTML($row["data_qe_progetto"])."</td><td>Data ".$utility->convertDateToHTML($row["data_qe_contratto"])."</td><td>Data ".$utility->convertDateToHTML($row["data_qe_perizia"])."</td><td>Data ".$utility->convertDateToHTML($row["data_qe_collaudo"])."</td></tr>";               
            } 
    ?>
           
	<?
        $query = "SELECT
                    q.id_sottodescrizione_quadro,s.desc_sottodescrizione_quadro,q.perc_iva,q.imp_qe_progetto,q.imp_qe_contratto,q.imp_qe_perizia,q.imp_qe_collaudo
                FROM quadro_economico_generale q, sottodescrizioni_quadro s
                WHERE q.id_lavoro=$id_lavoro AND q.id_sottodescrizione_quadro<=3 and q.id_sottodescrizione_quadro=s.id_sottodescrizione_quadro";
        
	// Execute the query
	$result = mysql_query($query);
	if (!$result){
		die ("Could not query the database: <br />". mysql_error());
	} 
        $t1=0;$t2=0;$t3=0;$t4=0;$t5=0;$entrato=false;
	while ($row = mysql_fetch_assoc($result)) {
		$entrato=true;				            					           
                $Descrizione = $row["desc_sottodescrizione_quadro"];
                $perc_iva = $row["perc_iva"];
                $imp_qe_progetto = $row["imp_qe_progetto"];
                $imp_qe_contratto = $row["imp_qe_contratto"];
                $imp_qe_perizia = $row["imp_qe_perizia"];
		$imp_qe_collaudo = $row["imp_qe_collaudo"];
                $id_sottodescrizione_quadro = $row["id_sottodescrizione_quadro"];
                
                if ($perc_iva==0) $perc_iva="";
                
		echo "<tr>";		         
                echo "<td>".($Descrizione)."</td>";
                echo "<td align='right'>$perc_iva</td>";
		echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_progetto,2)."</td>";     
                echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_contratto,2)."</td>";
		echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_perizia,2)."</td>";     
                echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_collaudo,2)."</td>";                
		echo "</tr>";  
                
                $t1+=$imp_qe_progetto;
                $t2+=$imp_qe_contratto;
                $t3+=$imp_qe_perizia;
                $t4+=$imp_qe_collaudo;               
	}
        if ($entrato==true) {       
        ?>
         <tr style='background: #aaaaaa; font-weight: bold;'><td>TOTALE LAVORI</td><td></td><td align='right'><?=$utility->FormatNumber($t1,2);?></td><td align='right'><?=$utility->FormatNumber($t2,2);?></td><td align='right'><?=$utility->FormatNumber($t3,2)?></td><td align='right'><?=$utility->FormatNumber($t4,2);?></td></tr>  
         <?
        }
        $query = "SELECT
                    q.id_sottodescrizione_quadro,s.desc_sottodescrizione_quadro,q.perc_iva,q.imp_qe_progetto,q.imp_qe_contratto,q.imp_qe_perizia,q.imp_qe_collaudo
                FROM quadro_economico_generale q, sottodescrizioni_quadro s
                WHERE q.id_lavoro=$id_lavoro AND q.id_sottodescrizione_quadro=4 and q.id_sottodescrizione_quadro=s.id_sottodescrizione_quadro";
        
	// Execute the query
	$result = mysql_query($query);
	if (!$result){
		die ("Could not query the database: <br />". mysql_error());
	} 
        $entrato=false;
	while ($row = mysql_fetch_assoc($result)) {
		$entrato=true;				            					           
                $Descrizione = $row["desc_sottodescrizione_quadro"];
                $perc_iva = $row["perc_iva"];
                $imp_qe_progetto = $row["imp_qe_progetto"];
                $imp_qe_contratto = $row["imp_qe_contratto"];
                $imp_qe_perizia = $row["imp_qe_perizia"];
		$imp_qe_collaudo = $row["imp_qe_collaudo"];
                $id_sottodescrizione_quadro = $row["id_sottodescrizione_quadro"];
                
                if ($perc_iva==0) $perc_iva="";
                
		echo "<tr>";		         
                echo "<td>".($Descrizione)."</td>";
                echo "<td align='right'>$perc_iva</td>";
		echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_progetto,2)."</td>";     
                echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_contratto,2)."</td>";
		echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_perizia,2)."</td>";     
                echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_collaudo,2)."</td>";                
		echo "</tr>";  
                
                $t1+=$imp_qe_progetto;
                $t2+=$imp_qe_contratto;
                $t3+=$imp_qe_perizia;
                $t4+=$imp_qe_collaudo;               
	}
        if ($entrato==true) {         
        ?> 
         <tr style='background: #aaaaaa; font-weight: bold;'><td>TOTALE LAVORI ED ONERI</td><td></td><td align='right' id="B"><?=$utility->FormatNumber($t1,2);?></td><td align='right'><?=$utility->FormatNumber($t2,2);?></td><td align='right'><?=$utility->FormatNumber($t3,2)?></td><td align='right'><?=$utility->FormatNumber($t4,2);?></td></tr>
         <?
        }
        $query = "SELECT
                    q.id_sottodescrizione_quadro,s.desc_sottodescrizione_quadro,q.perc_iva,q.imp_qe_progetto,q.imp_qe_contratto,q.imp_qe_perizia,q.imp_qe_collaudo
                FROM quadro_economico_generale q, sottodescrizioni_quadro s
                WHERE q.id_lavoro=$id_lavoro AND q.id_sottodescrizione_quadro=5 and q.id_sottodescrizione_quadro=s.id_sottodescrizione_quadro";
        
	// Execute the query
	$result = mysql_query($query);
	if (!$result){
		die ("Could not query the database: <br />". mysql_error());
	} 
        $entrato=false;$t1_r=0;$t2_r=0;$t3_r=0;$t4_r=0;
	if ($row = mysql_fetch_assoc($result)) {
		$entrato=true;				            					           
                $Descrizione = $row["desc_sottodescrizione_quadro"];
                $perc_iva = $row["perc_iva"];
                $imp_qe_progetto = $row["imp_qe_progetto"];
                $imp_qe_contratto = $row["imp_qe_contratto"];
                $imp_qe_perizia = $row["imp_qe_perizia"];
		$imp_qe_collaudo = $row["imp_qe_collaudo"];
                $id_sottodescrizione_quadro = $row["id_sottodescrizione_quadro"];
                
                if ($perc_iva==0) $perc_iva="";
                
		echo "<tr>";		         
                echo "<td>".($Descrizione)."</td>";
                echo "<td align='right'>$perc_iva</td>";
		echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_progetto,2)."</td>";     
                echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_contratto,2)."</td>";
		echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_perizia,2)."</td>";     
                echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_collaudo,2)."</td>";               
		echo "</tr>";  
                
//                $t1_r=$t1-$imp_qe_progetto;
//                $t1_r=$t2-$imp_qe_contratto;
//                $t1_r=$t3-$imp_qe_perizia;
//                $t1_r=$t4-$imp_qe_collaudo;               
	}
        if ($entrato==true) {         
        ?> 
         <tr style='background: #aaaaaa; font-weight: bold;'><td>IMPORTO DI CONTRATTO</td><td></td><td align='right'><?=$utility->FormatNumber($t1,2);?></td><td align='right'><?=$utility->FormatNumber($t2,2);?></td><td align='right'><?=$utility->FormatNumber($t3,2)?></td><td align='right'><?=$utility->FormatNumber($t4,2);?></td></tr>
         <?
        }
        ?>                 
        </tbody>        
    </table>

    <br /><br />    
        <h3>SOMME A DISPOSIZIONE DELL'AMMINISTRAZIONE</h3>
    <table cellpadding="0" cellspacing="0" border="1" id="tab_quadro_2" name="tab_quadro_2">
        <thead style="font-size:10pt;"><tr><th>Descrizione</th><th>Perc (%)</th><th>Q.E. Progetto</th><th>Q.E. Contratto</th><th>Q.E. Perizia</th><th>Q.E. Collaudo</th></tr></thead>
       <tbody style="font-size:10pt;">	
	<?
       $query = "SELECT
                    q.id_sottodescrizione_quadro,s.desc_sottodescrizione_quadro,q.perc_iva,q.imp_qe_progetto,q.imp_qe_contratto,q.imp_qe_perizia,q.imp_qe_collaudo
                FROM quadro_economico_generale q, sottodescrizioni_quadro s
                WHERE q.id_lavoro=$id_lavoro AND q.id_sottodescrizione_quadro>5 and q.id_sottodescrizione_quadro<300 and q.id_sottodescrizione_quadro=s.id_sottodescrizione_quadro order by s.progressivo_ordine";
        
	// Execute the query
	$result = mysql_query($query);
	if (!$result){
		die ("Could not query the database: <br />". mysql_error());
	} 
        $t11=0;$t22=0;$t33=0;$t44=0;$t55=0;$entrato=false;
	while ($row = mysql_fetch_assoc($result)){
		$entrato=true;		
		$Descrizione = $row["desc_sottodescrizione_quadro"];
                $perc_iva = $row["perc_iva"];
                $imp_qe_progetto = $row["imp_qe_progetto"];
                $imp_qe_contratto = $row["imp_qe_contratto"];
                $imp_qe_perizia = $row["imp_qe_perizia"];
		$imp_qe_collaudo = $row["imp_qe_collaudo"];
                $id_sottodescrizione_quadro = $row["id_sottodescrizione_quadro"];
                
                if ($perc_iva==0) $perc_iva="";
                
		echo "<tr>";		         
                echo "<td>".($Descrizione)."</td>";
                echo "<td align='right'>$perc_iva</td>";
		echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_progetto,2)."</td>";     
                echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_contratto,2)."</td>";
		echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_perizia,2)."</td>";     
                echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_collaudo,2)."</td>";                
		echo "</tr>";  
                
                $t11+=$imp_qe_progetto;
                $t22+=$imp_qe_contratto;
                $t33+=$imp_qe_perizia;
                $t44+=$imp_qe_collaudo;                                  
	}
        if ($entrato==true) {         
        ?> 
         <tr style='background: #cccccc; font-weight: bold;'><td>TOTALE SOMME DISP.</td><td></td><td align='right'><?=$utility->FormatNumber($t11,2);?></td><td align='right'><?=$utility->FormatNumber($t22,2);?></td><td align='right'><?=$utility->FormatNumber($t33,2)?></td><td align='right'><?=$utility->FormatNumber($t44,2);?></td></tr>
         <tr style='background: #aaaaaa; font-weight: bold;'><td>TOTALE FINANZIAMENTO</td><td></td><td align='right'><?=$utility->FormatNumber($t11+$t1,2);?></td><td align='right'><?=$utility->FormatNumber($t22+$t2,2);?></td><td align='right'><?=$utility->FormatNumber($t33+$t3,2)?></td><td align='right'><?=$utility->FormatNumber($t44+$t4,2);?></td></tr>
         <?
        }
        $query = "SELECT
                    q.id_sottodescrizione_quadro,s.desc_sottodescrizione_quadro,q.perc_iva,q.imp_qe_progetto,q.imp_qe_contratto,q.imp_qe_perizia,q.imp_qe_collaudo
                FROM quadro_economico_generale q, sottodescrizioni_quadro s
                WHERE q.id_lavoro=$id_lavoro AND q.id_sottodescrizione_quadro>=300 and q.id_sottodescrizione_quadro=s.id_sottodescrizione_quadro order by s.progressivo_ordine";
        
	// Execute the query
	$result = mysql_query($query);
	if (!$result){
		die ("Could not query the database: <br />". mysql_error());
	} 
        $entrato=false;
	while ($row = mysql_fetch_assoc($result)){
		$entrato=true;		
		$Descrizione = $row["desc_sottodescrizione_quadro"];
                $perc_iva = $row["perc_iva"];
                $imp_qe_progetto = $row["imp_qe_progetto"];
                $imp_qe_contratto = $row["imp_qe_contratto"];
                $imp_qe_perizia = $row["imp_qe_perizia"];
		$imp_qe_collaudo = $row["imp_qe_collaudo"];
                $id_sottodescrizione_quadro = $row["id_sottodescrizione_quadro"];
                
                if ($perc_iva==0) $perc_iva="";
                
		echo "<tr>";		         
                echo "<td>".($Descrizione)."</td>";
                echo "<td align='right'>$perc_iva</td>";
		echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_progetto,2)."</td>";     
                echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_contratto,2)."</td>";
		echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_perizia,2)."</td>";     
                echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_collaudo,2)."</td>";                
		echo "</tr>";  
                
                $t11+=$imp_qe_progetto;
                $t22+=$imp_qe_contratto;
                $t33+=$imp_qe_perizia;
                $t44+=$imp_qe_collaudo;                                  
	}
        if ($entrato==true) {         
        ?> 
         <tr style='background: #cccccc; font-weight: bold;'><td>TOTALE SOMME DISP.+IVA</td><td></td><td align='right'><?=$utility->FormatNumber($t11,2);?></td><td align='right'><?=$utility->FormatNumber($t22,2);?></td><td align='right'><?=$utility->FormatNumber($t33,2)?></td><td align='right'><?=$utility->FormatNumber($t44,2);?></td></tr>
         <tr style='background: #aaaaaa; font-weight: bold;'><td>TOTALE FINANZ.+IVA</td><td></td><td align='right'><?=$utility->FormatNumber($t11+$t1,2);?></td><td align='right'><?=$utility->FormatNumber($t22+$t2,2);?></td><td align='right'><?=$utility->FormatNumber($t33+$t3,2)?></td><td align='right'><?=$utility->FormatNumber($t44+$t4,2);?></td></tr>
         <?
        }
        ?> 
	</tbody>           
    </table>

    <br /><br />
    
        <h3>Disponibilit&agrave;</h3>        
        <?
        if ($t44>0) {  //sec'è il collaudo faccio collaudo-progetto
          $T=$t44-$t11;  
        }
        else if ($t33>0) {  //sec'è la perizia faccio
          $T=$t33-$t11;  
        }
        else if ($t22>0) {  //sec'è il contrato faccio
          $T=$t22-$t11;  
        }
//       echo $t44."_".$t33."_".$t22."_".$t11;
       
        $style="";
        if ($T>0)
            $style="style='font-size:12pt;font-weight: bold;color:green;'";
        if ($T<0)
            $style="style='font-size:12pt;font-weight: bold;color:red;'";
        if ($T==0)
            $style="style='font-size:12pt;font-weight: bold;color:blue;'";
        echo "<label $style>";
            echo $utility->FormatNumber($T,2);
        echo "</label>";       
    
$query="select note_quadro from legame_lavori_quadri_economici where id_lavoro=$id_lavoro";
$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
} 
$annotazioni="";
while ($row = mysql_fetch_assoc($result)){
    $annotazioni=$row["note_quadro"];
}
?>
<br /> <br /><br />   
<h4>Annotazioni</h4>     
<?=($annotazioni);?>  
</div>
