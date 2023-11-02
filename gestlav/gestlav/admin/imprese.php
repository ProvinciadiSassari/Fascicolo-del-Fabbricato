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
    
    $('#tab_imprese').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "language": {
            "url": '../language/Italian.json'
        } 
    });
    
    $("#but_nuova_impresa").click(function(e){
        $("#inp_impresa").val(""); 
        $("#inp_titolare").val("");
        $("#sel_comune").val(0); 
        $("#inp_indirizzo").val("");
        $("#inp_tel1").val("");
        $("#inp_tel2").val("");
        $("#inp_fax").val("");
        $("#inp_codice_fiscale").val("");
        $("#inp_email").val("");
        $("#inp_sito").val(""); 
        
        $("#hid_id_impresa").attr("value", 0);
        $("#but_elimina").hide();
        $("#titolo_dialog").text("Nuova Impresa");
        $('#dialog_gestione_impresa').modal('show');
    }); 
    
    
    $(document).on("click", "#img_mod", function(){ 
        
        var id_impresa=$(this).attr("alt");

        $.ajax({
            type: "post",
            dataType: "json",
            url: "../query/retrieve_dati_impresa.php",                                                 
            data: "id_impresa="+id_impresa,
            success: function(msg) { 
//                alert(msg);                   
                  $("#inp_impresa").val(msg[1]);  
                  $("#inp_titolare").val(msg[2]);
                  $("#sel_comune").val(msg[3]); 
                  $("#inp_indirizzo").val(msg[4]);
                  $("#inp_tel1").val(msg[5]);
                  $("#inp_tel2").val(msg[6]);
                  $("#inp_fax").val(msg[7]);
                  $("#inp_codice_fiscale").val(msg[8]);
                  $("#inp_email").val(msg[9]);
                  $("#inp_sito").val(msg[10]);                  
                  
                  $("#hid_id_impresa").attr("value", id_impresa);
                  $("#but_elimina").show();
                  $("#titolo_dialog").text("Modifica Impresa");
                  
                  $('#dialog_gestione_impresa').modal('show');
            },
            error: function() {
                alert ("error");
            }
        });                
    });
    
    $("#but_elimina").click(function(e){
        var id_impresa = $("#hid_id_impresa").val()*-1;
      
        var ajax_data="";
        
        ajax_data={
            id_impresa:id_impresa          
        };
                      
        $.ajax({
            type: "post",
            url: "actions/submit_salva_impresa.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);  
                  $('#dialog_gestione_impresa').modal('hide'); 
                  if (msg==0) { 

                      window.location="imprese.php";

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
    
    $("#but_salva_gestione_impresa").click(function(e){
    
        var inp_impresa=$("#inp_impresa").val();
         var inp_titolare=$("#inp_titolare").val();
        var sel_comune=$("#sel_comune").val();
        var inp_indirizzo=$("#inp_indirizzo").val();
        var inp_tel1=$("#inp_tel1").val();
        var inp_tel2=$("#inp_tel2").val();
        var inp_fax=$("#inp_fax").val();
        var inp_codice_fiscale=$("#inp_codice_fiscale").val();
        var inp_email=$("#inp_email").val();
        var inp_sito=$("#inp_sito").val();        
        
        var id_impresa = $("#hid_id_impresa").val();
        var ajax_data="";
        
        ajax_data={
            id_impresa:id_impresa,
            inp_impresa:inp_impresa,
            inp_titolare:inp_titolare,
            sel_comune:sel_comune,
            inp_indirizzo:inp_indirizzo,
            inp_tel1:inp_tel1,
            inp_tel2:inp_tel2,
            inp_fax:inp_fax,
            inp_codice_fiscale:inp_codice_fiscale,
            inp_email:inp_email,
            inp_sito:inp_sito
        };
        
        if (inp_impresa!="") {
            $.ajax({
                type: "post",
                url: "actions/submit_salva_impresa.php",                                                 
                data: ajax_data,
                success: function(msg) { 
//                    alert(msg);  
                      $('#dialog_gestione_impresa').modal('hide'); 
                      if (msg==0) {                       
                          window.location="imprese.php";

                          swal(
                    'Informazione',
                    'Operazione avvenuta con successo.',
                    'success'
                  );
                      }
                      else {                      
                          swal(
                    'Informazione',
                    'EMail non valida.',
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
                    'Impresa non valorizzata.',
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
<h4 style="text-align: center;">ELENCO IMPRESE</h4><br /><br />  
<div class="container-fluid">  
    <div class="form-group row">
        <div class="col-lg-2" >
        <button id='but_nuova_impresa' class='btn btn-info' style='margin-bottom:15px;'>Nuova Impresa</button>
       </div>
    </div> 
    <div class="form-group row">  
<?php
// Assign the query
   $query = "SELECT IDImpresa, Impresa, Tel1, Tel2, CodFisc_PartIVA, email
                FROM imprese"; 
    // Execute the query
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database:222 <br />". mysql_error());
    }
	        
    echo "<table class='table table-striped table-bordered' width='100%' cellspacing='0' id='tab_imprese'>";        
    echo "<thead><tr><th>ID</th><th>Impresa</th><th>Codice Fiscale</th><th>Recapiti</th><th>EMail</th><th>Modifica</th></tr></thead><tbody>";
	
	// Fetch and display the results
	while ($row = mysql_fetch_assoc($result)){
		
		$id_impresa = $row["IDImpresa"];
		$desc_impresa = $row["Impresa"];
                $CodFisc_PartIVA = $row["CodFisc_PartIVA"];
		$recapiti = $row["Tel1"]." -- ".$row["Tel2"];
                $email = $row["email"];		
		
		echo "<tr>";
		echo "<td>$id_impresa</td>";
		echo "<td>$desc_impresa</td>";	
                echo "<td>$CodFisc_PartIVA</td>";
		echo "<td>$recapiti</td>";	
                echo "<td>$email</td>";		
                echo "<td align='center'><img src='../images/modifica.png' class='hand' id='img_mod' alt='".$id_impresa."'/></td>"; 
            echo "</tr>";
           
	}
	echo "</tbody></table>";
?>
</div></div>   

    <div class="modal fade" id="dialog_gestione_impresa" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
  <div class="modal-content">
<div class="modal-header">                
        <h5 id="titolo_dialog"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
</div>     
<div class="modal-body">
<input type="hidden" id="hid_id_impresa" value=""/>    
<table style="width: 100%;"> 
<tr>
    <td style="width: 130px;">
            <label class="control-label" for="inp_impresa">Impresa</label>     
        </td><td colspan="3">                      
            <input type="text" id="inp_impresa" class="form-control" value="" />
        </td>
    </tr> 
    <tr>
        <td>
            <label class="control-label" for="inp_titolare">Titolare</label>     
        </td><td colspan="3">                      
            <input type="text" id="inp_titolare" class="form-control" value="" />
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
        <button id='but_salva_gestione_impresa' class='btn btn-success' ><i class='fa fa-save'> </i> Salva</button>    
        <button id='but_elimina' class='btn btn-danger' ><i class='fa fa-times'> </i> Elimina</button>  
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
 </div>        
</div>  
 </div>        
</div>      
       
</body>
</html>