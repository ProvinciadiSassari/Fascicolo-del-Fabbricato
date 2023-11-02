<?php
session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (!isset($_SESSION["idlevel"]) || $_SESSION["idlevel"]!=1)
    header("Location: /gestlav/index.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Gestione Lavori</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />
<link href="../css/bootstrap.min.css" rel="stylesheet" media="screen" />
<link rel="stylesheet" href="../css/base.css" type="text/css" />
<link href="../css/datatables.min.css" rel="stylesheet" type="text/css"/>
<link href="../css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
<link href="../css/sweetalert2.min.css" rel="stylesheet" type="text/css"/>
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/bootstrap.min.js"></script> 
<script src="../js/moment.min.js" type="text/javascript"></script>
<script src="../js/datatables.min.js" type="text/javascript"></script>
<script src="../js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
<script src="../js/fontawesome-all.js" type="text/javascript"></script>
<script src="../js/sweetalert2.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready( function(){
    $('#tab_professionisti').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "language": {
            "url": '../language/Italian.json'
        } 
    });
       
    $("#but_nuova_professionista").click(function(e){
        $("#inp_professionista").val("");   
        $("#sel_comune").val(0); 
        $("#inp_indirizzo").val("");
        $("#inp_tel1").val("");
        $("#inp_tel2").val("");
        $("#inp_fax").val("");
        $("#inp_codice_fiscale").val("");
        $("#inp_email").val("");
        $("#inp_sito").val(""); 
        
        $("#hid_id_professionista").attr("value", 0);
         $("#but_elimina").hide();
        $("#titolo_dialog").text("Nuovo Professionista");
        $('#dialog_gestione_professionista').modal('show');
    }); 
    
    
    $(document).on("click", "#img_mod", function(){ 
        
        var id_professionista=$(this).attr("alt");

        $.ajax({
            type: "post",
            dataType: "json",
            url: "../query/retrieve_dati_professionista.php",                                                 
            data: "id_professionista="+id_professionista,
            success: function(msg) { 
//                alert(msg);                   
                  $("#inp_professionista").val(msg[1]);  
                  $("#sel_comune").val(msg[2]); 
                  $("#inp_indirizzo").val(msg[3]);
                  $("#inp_tel1").val(msg[4]);
                  $("#inp_tel2").val(msg[5]);
                  $("#inp_fax").val(msg[6]);
                  $("#inp_codice_fiscale").val(msg[7]);
                  $("#inp_email").val(msg[8]);
                  $("#inp_sito").val(msg[9]);                  
                  
                  $("#hid_id_professionista").attr("value", id_professionista);
                  $("#but_elimina").show();
                  $("#titolo_dialog").text("Modifica Professionista");
                  
                  $('#dialog_gestione_professionista').modal('show');
            },
            error: function() {
                alert ("error");
            }
        });                
    });
    
    $("#but_elimina").click(function(e){
        var id_professionista = $("#hid_id_professionista").val()*-1;
      
        var ajax_data="";
        
        ajax_data={
            id_professionista:id_professionista          
        };
                      
        $.ajax({
            type: "post",
            url: "actions/submit_salva_professionista.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);  
                  $('#dialog_gestione_professionista').modal('hide'); 
                  if (msg==0) { 

                      window.location="professionisti_incaricati.php";

                     swal(
                    'Informazione',
                    'Operazione avvenuta con successo.',
                    'success'
                  );
                    }                 
            },
            error: function() {
                alert ("error");
            }
        });               
    });
    
    $("#but_salva_gestione_professionista").click(function(e){
    
        var inp_professionista=$("#inp_professionista").val();
        var sel_comune=$("#sel_comune").val();
        var inp_indirizzo=$("#inp_indirizzo").val();
        var inp_tel1=$("#inp_tel1").val();
        var inp_tel2=$("#inp_tel2").val();
        var inp_fax=$("#inp_fax").val();
        var inp_codice_fiscale=$("#inp_codice_fiscale").val();
        var inp_email=$("#inp_email").val();
        var inp_sito=$("#inp_sito").val();        
        
        var id_professionista = $("#hid_id_professionista").val();
        var ajax_data="";
        
        ajax_data={
            id_professionista:id_professionista,
            inp_professionista:inp_professionista,
            sel_comune:sel_comune,
            inp_indirizzo:inp_indirizzo,
            inp_tel1:inp_tel1,
            inp_tel2:inp_tel2,
            inp_fax:inp_fax,
            inp_codice_fiscale:inp_codice_fiscale,
            inp_email:inp_email,
            inp_sito:inp_sito
        };
        
        if (inp_professionista!="") {
            $.ajax({
                type: "post",
                url: "actions/submit_salva_professionista.php",                                                 
                data: ajax_data,
                success: function(msg) { 
    //                alert(msg);  
                      $('#dialog_gestione_professionista').modal('hide'); 
                      if (msg==0) {                       
                          window.location="professionisti_incaricati.php";

                          swal(
                    'Informazione',
                    'Operazione avvenuta con successo.',
                    'success'
                  );
                      }
                      else {                      
                           swal(
                    'Informazione',
                    'Email non valida.',
                    'warning'
                  );
                      }

                },
                error: function() {
                    alert ("error");
                }
            });
        }
        else {
            swal(
                    'Informazione',
                    'Professionista non valorizzato.',
                    'warning'
                  );
        }
    });
});
</script>

</head>
<body>
<?php 
include('menu.php');
?>
<br /><br /><br /><br />    
<h4 style="text-align: center;">ELENCO PROFESSIONISTI INCARICATI</h4><br /><br />  
<div class="container-fluid">  
    <div class="form-group row">
        <div class="col-lg-2" >
        <button id='but_nuova_professionista' class='btn btn-info' style='margin-bottom:15px;'>Nuovo Professionista</button>
       </div>
    </div> 
    <div class="form-group row">
<?php
// Assign the query
   $query = "SELECT IDProfessionista,Professionista,Tel1,Tel2,CodFisc_PartIVA,email 
                FROM professionisti"; 
    // Execute the query
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database:222 <br />". mysql_error());
    }
	         
    echo "<table class='table table-striped table-bordered' width='100%' cellspacing='0' id='tab_professionisti'>";        
    echo "<thead><tr><th>ID</th><th>Professionista</th><th>Codice Fiscale</th><th>Recapiti</th><th>EMail</th><th>Modifica</th></tr></thead><tbody>";
	
	// Fetch and display the results
	while ($row = mysql_fetch_assoc($result)){
		
		$id_professionista = $row["IDProfessionista"];
		$desc_professionista = $row["Professionista"];
                $CodFisc_PartIVA = $row["CodFisc_PartIVA"];
		$recapiti = $row["Tel1"]." -- ".$row["Tel2"];
                $email = $row["email"];		
		
		echo "<tr>";
		echo "<td>$id_professionista</td>";
		echo "<td>$desc_professionista</td>";	
                echo "<td>$CodFisc_PartIVA</td>";
		echo "<td>$recapiti</td>";	
                echo "<td>$email</td>";		
                echo "<td align='center'><img src='../images/modifica.png' class='hand' id='img_mod' alt='".$id_professionista."'/></td>"; 
            echo "</tr>";
           
	}
	echo "</tbody></table>";

?>
</div></div>        
    
<div class="modal fade" id="dialog_gestione_professionista" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
  <div class="modal-content">
<div class="modal-header">                
        <h5 id="titolo_dialog"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
</div>     
<div class="modal-body">
<input type="hidden" id="hid_id_professionista" value=""/>    
<table style="width: 100%;"> 
<tr>
    <td style="width: 130px;">
            <label class="control-label" for="inp_professionista">Professionista</label>     
        </td><td colspan="3">                      
            <input type="text" id="inp_professionista" class="form-control" value="" />
        </td>
    </tr>    
    <tr>
        <td>
            <label class="control-label" for="sel_comune">Comune</label>     
        </td><td colspan="3">    
            <select id="sel_comune" class="form-control">
                <option value="0">- Seleziona -</option>
             <?
              $query="SELECT IDComune,NomeComune,Cap FROM comuni_lav";
              $result = mysql_query($query);
              if (!$result){
                      die ("Could not query the database: <br />". mysql_error());
              }
              while ($row = mysql_fetch_assoc($result)){
                  $id=$row["IDComune"];
                  $val=$row["NomeComune"]. "  -  ".$row["Cap"];
                  echo "<option value='$id'>";
                  echo $val;
                  echo "</option>";                        
              }                    
             ?>   
            </select> 
        </td>
      </tr>
      <tr>  
        <td>
            <label class="control-label" for="inp_indirizzo">Indirizzo</label>     
        </td><td colspan="3">    
            <input type="text" id="inp_indirizzo" class="form-control" value=""/> 
        </td>              
    </tr>
    <tr>  
        <td>
            <label class="control-label">Recapiti</label>  
        </td><td>      
            <input type="text" id="inp_tel1" class="form-control" value=""/>
            <input type="text" id="inp_tel2" class="form-control" value=""/>
        </td><td>    
             <label class="control-label" for="inp_fax">&nbsp;&nbsp;&nbsp;Fax</label> 
        </td><td>       
            <input type="text" id="inp_fax" class="form-control" value=""/>
        </td>              
    </tr>
    <tr>  
        <td>
            <label class="control-label" for="inp_codice_fiscale">Cod. Fiscale</label>     
        </td><td colspan="3">    
            <input type="text" id="inp_codice_fiscale" class="form-control" value=""/> 
        </td>              
    </tr>
    <tr>  
        <td>
            <label class="control-label" for="inp_email">EMail</label>  
        </td><td>      
            <input type="text" id="inp_email" class="form-control" value=""/>            
        </td><td>    
             <label class="control-label" for="inp_sito">Sito Web</label> 
        </td><td>       
            <input type="text" id="inp_sito" class="form-control" value=""/>
        </td>              
    </tr>
</table> 
</div> 
 <div class="modal-footer justify-content-between" >
        <button id='but_salva_gestione_professionista' class='btn btn-success' ><i class='fa fa-save'> </i> Salva</button>    
        <button id='but_elimina' class='btn btn-danger' ><i class='fa fa-times'> </i> Elimina</button>  
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
 </div>            
</div> 
 </div>            
</div>     
       
</body>
</html>