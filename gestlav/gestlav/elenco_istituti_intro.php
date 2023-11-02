<?php
session_start();
require_once('conf.inc.php');
include('conv.php');

$utility = new Utility();
$utility->connetti();

if (!isset($_SESSION['idlevel']) || ($_SESSION['idlevel']!=2 && $_SESSION['idlevel']!=5 && $_SESSION['idlevel']!=7))
{ //se non passo il controllo ritorno all'index
    header("Location: /gestlav/index.php");
}
$id_utente=$_SESSION["iduser"];

if (isset($_POST['comune_istituti'])) 
    $comune_istituto=$_POST['comune_istituti'];
else 
    $comune_istituto=0;


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Gestione Lavori</title>
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen" />
<link rel="stylesheet" href="css/base.css" type="text/css" />
<link href="css/datatables.min.css" rel="stylesheet" type="text/css"/>
<link href="css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js"></script> 
<script src="js/moment.min.js" type="text/javascript"></script>
<script src="js/datatables.min.js" type="text/javascript"></script>
<script src="js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
<script src="js/four_buttons.js" type="text/javascript"></script>
<script src="js/fontawesome-all.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
    var avviso_eseguito=0;
$(document).ready(function() {                

    var id_utente=<?=$id_utente;?>;
    
    $("#tab_container").load("tables/table_elenco_fabbricati.php?id_comune=0");
    
    $("#comune_istituti").change(function(){
        if ($(this).val()==0 && avviso_eseguito==0) {
            $.ajax({
            type: "post",
            dataType: "json",
            url: "query/retrieve_avvisi.php",                                                 
            data: "id_utente="+id_utente,
            success: function(msg) { 
//                alert(msg);    
                if (msg[0]!="") {
                    var _html="Sono stati aggiornati i seguenti file:<br /><br />";
                    for(var i=0;i<msg.length; i=i+2) {
                        _html+="<b>Fabbricato</b>: "+msg[i]+"<br />";
                        _html+="<b>Filename</b>: "+msg[i+1]+"<br /><hr />";                           
                    }
                    $("#body_avviso").empty();
                    $("#body_avviso").html(_html);
                    $('#dialog_avviso').modal("show"); 
                }
            },
            error: function() {
//                alert ("error");
            }
        });
            avviso_eseguito=1;
        }
        
        $("#tab_container").load("tables/table_elenco_fabbricati.php?id_comune="+$(this).val());
    });
   
    $("#but_salva_lettura_avviso").click(function(e){
        $.ajax({
            type: "post",            
            url: "actions/submit_lettura_avviso.php",                                                 
            data: "id_utente="+id_utente,
            success: function(msg) { 
//                alert(msg);                    
                $('#dialog_avviso').modal("hide"); 
                
            },
            error: function() {
//                alert ("error");
            }
        });
    });
});
</script>

<body>
<?php
include("menu.php");
echo "<br /><br /><br /><br /><br />";
?> 
<div class="container-fluid">        
<div class="form-group row">
       
          
        <label style="margin-left:15px;" class="col-form-label col-lg-1">Comune Edificio:</label>
        <div class="col-lg-3"> 
        <select name='comune_istituti' id='comune_istituti' class="form-control">
            <option value='0'>-- Tutti --</option>
        <?php    
            $query_disp="SELECT DISTINCT i.a002_citta,c.desc_comune FROM istituti i ,comuni c where i.a002_citta=c.id_comune ";
            if ($_SESSION["idcompetenza"]>0) {
                $query_disp.=" and c.id_competenza=".$_SESSION["idcompetenza"];
            }
            $query_disp.=" order by c.desc_comune";
            $result_disp = mysql_query($query_disp);
            while($query_data = mysql_fetch_array($result_disp))
                    {
                        $val=$query_data['a002_citta'];
                        $desc_comune = $query_data['desc_comune'];
                        echo "<option value=\"$val\"";
                        if (isset($comune_istituto))
                            if ($val==$comune_istituto) echo "selected";
                        echo ">";
                        echo utf8_encode($desc_comune)."</option>";
                    }
                    ?>
     </select>
        </div>
      
</div>
<hr />    
 <div class="form-group row"> 
     <div class="col-lg-12" id="tab_container">
         
     </div>
    
</div>             
</div> 
<div id="dialog_avviso" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
<div class="modal-header">        
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5>Avviso</h5>
</div>   
<div class="modal-body" id="body_avviso">


</div> 
 <div class="modal-footer">
        <a href="#" class="btn btn-warning" data-dismiss="modal" style="float:left;">Ricordamelo più avanti</a>
        <a href="#" id="but_salva_lettura_avviso" class="btn btn-success" style="float:right;">Ho preso visione</a>
 </div>  
</div>    
</body>
</html>
