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
    
    $('#tab_referenti').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "language": {
            "url": '../language/Italian.json'
        } 
    });
  
       
    $("#but_nuovo_referente").click(function(e){
        $("#inp_referente").val("");   
      
        $("#hid_id_referente").attr("value", 0);
        $("#but_elimina").hide();
        
        $("#titolo_dialog").text("Nuovo Referente");
        $('#dialog_gestione_referente').modal('show');
    }); 
    
    
    $(document).on("click", "#img_mod", function(){ 
        
        var id_referente=$(this).attr("alt");
      
        $.ajax({
            type: "post",
            dataType: "json",
            url: "../query/retrieve_dati_referente.php",                                                 
            data: "id_referente="+id_referente,
            success: function(msg) { 
//                alert(msg);                   
                  $("#inp_referente").val(msg[1]);                    
                  
                  $("#hid_id_referente").attr("value", id_referente);
                  $("#but_elimina").show();
                  $("#titolo_dialog").text("Modifica Referente");
                  
                  $('#dialog_gestione_referente').modal('show');
            },
            error: function() {
                alert ("error");
            }
        });                
    });
    
    $("#but_elimina").click(function(e){
        var id_referente = $("#hid_id_referente").val()*-1;
      
        var ajax_data="";
        
        ajax_data={
            id_referente:id_referente          
        };
                      
        $.ajax({
            type: "post",
            url: "actions/submit_salva_referente.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);  
                  $('#dialog_gestione_referente').modal('hide'); 
                  if (msg==0) { 

                      window.location="referenti_tecnici.php";

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
    
    $("#but_salva_gestione_referente").click(function(e){
    
        var inp_referente=$("#inp_referente").val();
        
        var id_referente = $("#hid_id_referente").val();
        var ajax_data="";
        
        ajax_data={
            id_referente:id_referente,
            inp_referente:inp_referente
        };
        
        $.ajax({
            type: "post",
            url: "actions/submit_salva_referente.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);  
                  $('#dialog_gestione_referente').modal('hide'); 
                  if (msg==0) { 
                      
                      window.location="referenti_tecnici.php";
                      
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
});
</script>

</head>
<body>
<?php 
include('menu.php');
?>
<br /><br /><br /><br />    
<h4 style="text-align: center;">ELENCO REFERENTI TECNICI</h4><br /><br />  
<div class="container-fluid">  
    <div class="form-group row">
        <div class="col-lg-2" >
        <button id='but_nuovo_referente' class='btn btn-info' style='margin-bottom:15px;'>Nuovo Referente</button>
       </div>
    </div> 
    <div class="form-group row">
<?php
// Assign the query
   $query = "SELECT IDReferente,Referente 
                FROM referenti_edificio"; 
    // Execute the query
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database:222 <br />". mysql_error());
    }
	         
    echo "<table class='table table-striped table-bordered' width='100%' cellspacing='0' id='tab_referenti'>";        
    echo "<thead><tr><th>ID</th><th>Referente</th><th>Modifica</th></tr></thead><tbody>";
	
	// Fetch and display the results
	while ($row = mysql_fetch_assoc($result)){
		
		$IDReferente = $row["IDReferente"];
		$Referente = $row["Referente"];
		
		echo "<tr>";
		echo "<td>$IDReferente</td>";
		echo "<td>$Referente</td>";		
                echo "<td align='center'><img src='../images/modifica.png' class='hand' id='img_mod' alt='".$IDReferente."'/></td>"; 
            echo "</tr>";
           
	}
	echo "</tbody></table>";

?>
</div>
</div>

 <div class="modal fade" id="dialog_gestione_referente" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
  <div class="modal-content">
<div class="modal-header">                
        <h5 id="titolo_dialog"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
</div>  
<div class="modal-body">
<input type="hidden" id="hid_id_referente" value=""/>    
<table style="width: 100%;">             
<tr>
    <td>
        <label class="control-label" for="inp_referente">Referente</label>     
    </td><td>    
        <input type="text" id="inp_referente" class="form-control" value="" /> 
    </td>   
</tr>
</table>
</div> 
 <div class="modal-footer justify-content-between" >
        <button id='but_salva_gestione_referente' class='btn btn-success' ><i class='fa fa-save'> </i> Salva</button>    
        <button id='but_elimina' class='btn btn-danger' ><i class='fa fa-times'> </i> Elimina</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
 </div>       
</div>  
</div>  
</div>       
      
</body>
</html>