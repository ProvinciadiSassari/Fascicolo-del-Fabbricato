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
    
     $('#tab_strutture').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "language": {
            "url": '../language/Italian.json'
        } 
    });
    
   
    $("#but_nuova_struttura").click(function(e){
        $("#inp_struttura").val("");   
      
        $("#hid_id_struttura").attr("value", 0);
        $("#but_elimina").hide();
        $("#titolo_dialog").text("Nuova Struttura");
        $('#dialog_gestione_struttura').modal('show');
    }); 
    
    
    $(document).on("click", "#img_mod", function(){ 
        
        var id_struttura=$(this).attr("alt");
      
        $.ajax({
            type: "post",
            dataType: "json",
            url: "../query/retrieve_dati_struttura.php",                                                 
            data: "id_struttura="+id_struttura,
            success: function(msg) { 
//                alert(msg);                   
                  $("#inp_struttura").val(msg[1]);                    
                  
                  $("#hid_id_struttura").attr("value", id_struttura);
                  $("#but_elimina").show();
                  $("#titolo_dialog").text("Modifica Struttura");
                  
                  $('#dialog_gestione_struttura').modal('show');
            },
            error: function() {
                alert ("error");
            }
        });                
    });
    
    $("#but_elimina").click(function(e){
        var id_struttura = $("#hid_id_struttura").val()*-1;
      
        var ajax_data="";
        
        ajax_data={
            id_struttura:id_struttura          
        };
                      
        $.ajax({
            type: "post",
            url: "actions/submit_salva_struttura.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);  
                  $('#dialog_gestione_struttura').modal('hide'); 
                  if (msg==0) { 

                      window.location="strutture.php";

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
    
    $("#but_salva_gestione_struttura").click(function(e){
    
        var inp_struttura=$("#inp_struttura").val();
        
        var id_struttura = $("#hid_id_struttura").val();
        var ajax_data="";
        
        ajax_data={
            id_struttura:id_struttura,
            inp_struttura:inp_struttura
        };
        
        $.ajax({
            type: "post",
            url: "actions/submit_salva_struttura.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);  
                  $('#dialog_gestione_struttura').modal('hide'); 
                  if (msg==0) { 
                      
                      window.location="strutture.php";
                      
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
<h4 style="text-align: center;">ELENCO STRUTTURE</h4><br /><br />  
<div class="container-fluid">  
    <div class="form-group row">
        <div class="col-lg-2" >
        <button id='but_nuova_struttura' class='btn btn-info' style='margin-bottom:15px;'>Nuova Struttura</button>
       </div>
    </div> 
    <div class="form-group row">
<?php
// Assign the query
   $query = "SELECT id_struttura,desc_struttura 
                FROM strutture"; 
    // Execute the query
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database:222 <br />". mysql_error());
    }

    echo "<table class='table table-striped table-bordered' width='100%' cellspacing='0' id='tab_strutture'>";        
    echo "<thead><tr><th>ID</th><th>Struttura</th><th>Modifica</th></tr></thead><tbody>";
	
	// Fetch and display the results
	while ($row = mysql_fetch_assoc($result)){
		
		$id_struttura = $row["id_struttura"];
		$desc_struttura = $row["desc_struttura"];
		
		echo "<tr>";
		echo "<td>$id_struttura</td>";
		echo "<td>$desc_struttura</td>";		
                echo "<td align='center'><img src='../images/modifica.png' class='hand' id='img_mod' alt='".$id_struttura."'/></td>"; 
            echo "</tr>";
           
	}
	echo "</tbody></table>";
?>
</div>
</div>        
    <div class="modal fade" id="dialog_gestione_struttura" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
  <div class="modal-content">
<div class="modal-header">                
        <h5 id="titolo_dialog"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
</div>    
<div class="modal-body">
<input type="hidden" id="hid_id_struttura" value=""/>    
<table style="width: 100%;">             
<tr>
    <td style="width: 130px;">
        <label class="control-label" for="inp_struttura">Struttura</label>     
    </td><td>    
        <input type="text" id="inp_struttura" class="form-control" value="" /> 
    </td>   
</tr>
</table>
</div> 
 <div class="modal-footer justify-content-between" >
        <button id='but_salva_gestione_struttura' class='btn btn-success' ><i class='fa fa-save'> </i> Salva</button>    
        <button id='but_elimina' class='btn btn-danger' ><i class='fa fa-times'> </i> Elimina</button>  
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
 </div>        
</div>  
</div>
</div>        
       
</body>
</html>