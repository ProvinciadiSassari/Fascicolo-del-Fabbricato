<?php
session_start();
require_once('../conf.inc.php');

$utility = new Utility();
$utility->connetti();

$id_comune=$_GET["id_comune"];
$data_essere=$_GET["data_essere"];

?>
<option value="0">- Tutti -</option>
<?php
         $query_disp="SELECT DISTINCT f.id_fabbricato, f.descrizione_fabbricato
                    FROM fabbricati f, legame_storico_istituti_fabbricati l, istituti i
                    WHERE f.id_fabbricato=l.id_fabbricato AND l.id_istituto=i.a002_ID
                    AND (('$data_essere'>=l.data_inizio AND l.data_fine='0000-00-00') OR ( '$data_essere' BETWEEN l.data_inizio AND l.data_fine AND l.data_fine<>'0000-00-00')) ";                    
         
         if ($_SESSION['idcompetenza']>0) {
             $query_disp.=" and i.a002_Competenza=".$_SESSION['idcompetenza'];
         }
         if ($id_comune>0) {
                 $query_disp.=" and f.id_comune=$id_comune ";                            
         }
        
         $query_disp.=" order by id_fabbricato asc";   
         
         $result_disp = mysql_query($query_disp);
         while($query_data = mysql_fetch_array($result_disp))
         { $desc_edificio = $query_data['id_fabbricato']." - ".$query_data['descrizione_fabbricato'];
         ?>
         <option value="<?php echo $query_data['id_fabbricato']; ?>">
         <?php echo $desc_edificio; ?></option>
         <?php }
         