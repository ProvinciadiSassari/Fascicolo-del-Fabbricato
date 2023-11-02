<?php
session_start();
require_once('conf.inc.php');
include('conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_GET["id_fabbricato"])) {
    $id_fabbricato=$_GET["id_fabbricato"];     
}
else $id_fabbricato=0;
$data_essere=date("Y-m-d");
?>
<div class="container-fluid">
     
<div class="form-group row">
      <div class="col-lg-3" style="font-size: 9pt;">
    <?php
          echo "<h6 style='font-size: 9pt;font-weight: bold;'>Fabbricato (ID: ".$id_fabbricato.")</h6>";  
          $desc_fabbricato=$utility->getDescFabbricato($id_fabbricato);
          if (!empty($desc_fabbricato)) echo utf8_encode($desc_fabbricato);       
     ?> 
    </div>  
    <?php
        $query1="SELECT  i.a002_ID
                    FROM legame_storico_istituti_fabbricati l, istituti i
                    WHERE l.id_istituto=i.a002_ID and i.id_zona<>7 
                    AND (('$data_essere'>=l.data_inizio AND l.data_fine='0000-00-00') OR ( '$data_essere' BETWEEN l.data_inizio AND l.data_fine AND l.data_fine<>'0000-00-00'))
                     and l.id_fabbricato=$id_fabbricato ";
        
      
        $result1 = mysql_query($query1);
	if (!$result1){
		die ("Could not query the database: <br />". mysql_error());
	}
	while ($row1 = mysql_fetch_assoc($result1)){
            
            echo "<div class='col-lg-2'>";
            echo "<h6 style='font-size: 9pt;font-weight: bold;'>Istituto Scolastico</h6>";
            $id_istituto=$row1["a002_ID"];
        
            $query="select i.a002_descrizione, i.a002_indirizzo,i.a002_cap,c.desc_comune,i.a002_telefonofax,i.a002_email,i.a002_web
                from istituti i, comuni c
                where i.a002_ID='$id_istituto' and i.a002_citta=c.id_comune"; 
            $result = mysql_query($query);
             if (!$result){
                     die ("Could not query the database: <br />". mysql_error());
             }
             if ($row = mysql_fetch_assoc($result)){
                 $desc_edificio=$row["a002_descrizione"];
                 $indirizzo=$row["a002_indirizzo"];
                 $cap=$row["a002_cap"];
                 $desc_comune=$row["desc_comune"];  
                 $tel_fax=$row["a002_telefonofax"];
                 $email=$row["a002_email"];
                 $web=$row["a002_web"];                 
                 ?>
                    <p style="font-size: 9pt;" class="col-lg-12">
                        <?php
                         echo conv_string2html($desc_edificio)."<br />";
                         echo conv_string2html($indirizzo)." - ".$cap." - ".$desc_comune."<br />"; 
                         if (!empty($tel_fax)) echo "Tel/Fax: ".$tel_fax."<br />";
                         if (!empty($email)) echo "<i class='icon-envelope'></i><a href='mailto:$email'>$email</a>"."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                         if (!empty($web)) echo "<br /><i class='icon-globe'></i><a href='$web' target='_blank'>$web</a>";

                        ?>
                    </p>
         <?php
             }        
             else echo "<h5 style='color:red;'>Il fabbricato non è associato ad alcun istituto.</h5>";
             
            echo "</div>"; 
        }      
    ?>   
</div> 

</div>     