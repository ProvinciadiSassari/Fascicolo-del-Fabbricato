<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

$ret="";


$id_fabbricato=$_GET['id_fabbricato'];
$lavori_soglia = date('Y-m-d', strtotime(date("Y-m-d"). ' + '.$_SESSION['soglia_doc_lavori_gg'].' days'));
$fabbricati_soglia = date('Y-m-d', strtotime(date("Y-m-d"). ' + '.$_SESSION['soglia_doc_fabbricati_gg'].' days'));    
    
?>

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {                

      $('#tab_documenti_scadenza').DataTable({
        responsive: true,
        "aoColumns": [                                  
                        null,                        
                        null, 
                        null,                
                        null, 
                        {"iDataSort": 5},
                        {"bVisible": false},
                        null
                ],
        "aaSorting": [[ 5, "asc" ]],
        "bDestroy": true,
        "language": {
            "url": 'language/Italian.json'
        } 
    });  
    
    $(document).on("click","#img_open_file",function(e){
        e.stopImmediatePropagation();
        var _path=$(this).attr("alt");
        window.open(encodeURI(_path),"_blank");
    }); 
});
</script>

<?php
    
    $query="SELECT IDDescrizione, 0 as IDLavoro, Percorso_completo, file, Descrizione, UtenteArchiv, Catalogazione, fl_rinnovato, data_scadenza_documento, data_ultima_modifica
        FROM descrizione_files_edifici
        WHERE IDEdificio=$id_fabbricato AND fl_rinnovato=0 and data_scadenza_documento IS NOT NULL AND data_scadenza_documento>0 AND data_scadenza_documento<'$fabbricati_soglia' ";
    
    $query.=" UNION SELECT d.IDDescrizione, d.IDLavoro,d.Percorso_completo, d.file, d.Descrizione, d.UtenteArchiv, d.Catalogazione, d.fl_rinnovato, d.data_scadenza_documento, d.data_ultima_modifica
        FROM descrizione_files d, lavori l 
        WHERE l.IDEdificio=$id_fabbricato and d.IDLavoro=l.IDLavoro AND d.fl_rinnovato=0 and d.data_scadenza_documento IS NOT NULL AND d.data_scadenza_documento>0 AND d.data_scadenza_documento<'$lavori_soglia' ORDER BY data_scadenza_documento desc";

    $result=  mysql_query($query);
    if (!$result){
        die (mysql_error());
    }
    
    
//    echo $query;
    $i=mysql_num_rows($result);
?>    
<input type="hidden" id="hid_num_doc_scadenza" value="<?=$i;?>">

 <table class="table table-striped table-bordered" width="100%" cellspacing="0" id="tab_documenti_scadenza">
       <thead><tr><th align='center'>ID Doc</th><th align='center'>ID Lavoro</th><th>Percorso File</th><th>Descrizione</th><th align='center'>Data scadenza</th><th>DS</th><th align='center'>File</th></tr></thead>
       <tbody>
<?php
    
//     echo "<tr><td colspan='6'>$query</td></tr>";
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        
        $data_scadenza_documento=$utility->convertDateToHTML($row['data_scadenza_documento']); 
        $id_lavoro=$row['IDLavoro'];
        $id_documento=$row['IDDescrizione'];
        $path=$row['Percorso_completo'];
        $path= str_replace("/public/gestlavori/Archivio Lavori/", "", $path);
        $path= str_replace("/public/gestlavori/ArchDocEdifici/", "", $path);
       
        echo "<tr>";		         
        echo "<td align='center'>".$id_documento."</td>";
        echo "<td align='center'>".$id_lavoro."</td>";  
        echo "<td>".$path."</td>";  
        echo "<td>".$row['Descrizione']."</td>";  
        echo "<td align='center'>".$data_scadenza_documento."</td>"; 
        echo "<td>".$row['data_scadenza_documento']."</td>";  
        echo "<td align='center'>";
        if ($_SESSION['idlevel']!=7) {
        echo "<img src='images/document.png' class='hand img_open_file' width='24' id='img_open_file' alt='".$row['Percorso_completo']."'/>";
        }
        echo "</td>";
        echo "</tr>";
      
    }


?>
</tbody>
</table>
