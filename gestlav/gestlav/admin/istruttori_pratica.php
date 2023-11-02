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
    
    $('#tab_istruttori').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "language": {
            "url": '../language/Italian.json'
        } 
    });
    
    $("#but_nuova_istruttore").click(function(e){
        $("#inp_istruttore").val("");   
      
        $("#hid_id_istruttore").attr("value", 0);
        $("#but_elimina").hide();
        $("#titolo_dialog").text("Nuovo Istruttore");
        $('#dialog_gestione_istruttore').modal('show');
    }); 
    
    
    $(document).on("click", "#img_mod", function(){ 
        
        var id_istruttore=$(this).attr("alt");
      
        $.ajax({
            type: "post",
            dataType: "json",
            url: "../query/retrieve_dati_istruttore.php",                                                 
            data: "id_istruttore="+id_istruttore,
            success: function(msg) { 
//                alert(msg);                   
                  $("#inp_istruttore").val(msg[1]);                    
                  
                  $("#hid_id_istruttore").attr("value", id_istruttore);
                  $("#but_elimina").show();
                  $("#titolo_dialog").text("Modifica Istruttore");
                  
                  $('#dialog_gestione_istruttore').modal('show');
            },
            error: function() {
                alert ("error");
            }
        });                
    });
    
     $("#but_elimina").click(function(e){
        var id_istruttore = $("#hid_id_istruttore").val()*-1;
      
        var ajax_data="";
        
        ajax_data={
            id_istruttore:id_istruttore          
        };
                      
        $.ajax({
            type: "post",
            url: "actions/submit_salva_istruttore.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);  
                  $('#dialog_gestione_istruttore').modal('hide'); 
                  if (msg==0) { 

                      window.location="istruttori_pratica.php";

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
    
    $("#but_salva_gestione_istruttore").click(function(e){
    
        var inp_istruttore=$("#inp_istruttore").val();
        
        var id_istruttore = $("#hid_id_istruttore").val();
        var ajax_data="";
        
        ajax_data={
            id_istruttore:id_istruttore,
            inp_istruttore:inp_istruttore
        };
        
        $.ajax({
            type: "post",
            url: "actions/submit_salva_istruttore.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);  
                  $('#dialog_gestione_istruttore').modal('hide'); 
                  if (msg==0) { 
                      
                      window.location="istruttori_pratica.php";
                      
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
<h4 style="text-align: center;">ELENCO ISTRUTTORI PRATICA</h4><br /><br />  
<div class="container-fluid">  
    <div class="form-group row">
        <div class="col-lg-2" >
        <button id='but_nuova_istruttore' class='btn btn-info' style='margin-bottom:15px;'>Nuovo Istruttore</button>
       </div>
    </div> 
    <div class="form-group row">
<?php
// Assign the query
   $query = "SELECT IDIstruttore,Istruttore 
                FROM istruttori_pratica"; 
    // Execute the query
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database:222 <br />". mysql_error());
    }
	        
    echo "<table class='table table-striped table-bordered' width='100%' cellspacing='0' id='tab_istruttori'>";        
    echo "<thead><tr><th>ID</th><th>Istruttore</th><th>Modifica</th></tr></thead><tbody>";
	
	// Fetch and display the results
	while ($row = mysql_fetch_assoc($result)){
		
		$id_istruttore = $row["IDIstruttore"];
		$desc_istruttore = $row["Istruttore"];
		
		echo "<tr>";
		echo "<td>$id_istruttore</td>";
		echo "<td>$desc_istruttore</td>";		
                echo "<td align='center'><img src='../images/modifica.png' class='hand' id='img_mod' alt='".$id_istruttore."'/></td>"; 
            echo "</tr>";
           
	}
	echo "</tbody></table>";
?>
</div></div>

<div class="modal fade" id="dialog_gestione_istruttore" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
  <div class="modal-content">
<div class="modal-header">                
        <h5 id="titolo_dialog"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
</div>    
<div class="modal-body">
<input type="hidden" id="hid_id_istruttore" value=""/>    
<table style="width: 100%;"> 
<tr>
    <td style="width: 130px;">
        <label class="control-label" for="inp_istruttore">Istruttore</label>     
    </td><td>    
        <input type="text" id="inp_istruttore" class="form-control" value="" /> 
    </td>   
</tr>
</table>
</div>
<div class="modal-footer justify-content-between" >
        <button id='but_salva_gestione_istruttore' class='btn btn-success' ><i class='fa fa-save'> </i> Salva</button>    
        <button id='but_elimina' class='btn btn-danger' ><i class='fa fa-times'> </i> Elimina</button>  
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
 </div>        
</div>
 </div>        
</div>            
</body>
</html>