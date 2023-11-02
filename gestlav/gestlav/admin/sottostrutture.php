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
    $('#tab_sottostrutture').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "language": {
            "url": '../language/Italian.json'
        } 
    });
    
       
    $("#but_nuova_sottostruttura").click(function(e){
        $("#inp_sottostruttura").val("");  
        $("#sel_strutture").val(1);
      
        $("#hid_id_sottostruttura").attr("value", 0);
        $("#but_elimina").hide();
        $("#titolo_dialog").text("Nuova Sottostruttura");
        $('#dialog_gestione_sottostruttura').modal('show');
    }); 
    
    
    $(document).on("click", "#img_mod", function(){ 
        
        var id_sottostruttura=$(this).attr("alt");
        
        $.ajax({
            type: "post",
            dataType: "json",
            url: "../query/retrieve_dati_sottostruttura.php",                                                 
            data: "id_sottostruttura="+id_sottostruttura,
            success: function(msg) { 
//                alert(msg); 
                  $("#sel_strutture").val(msg[1]);  
                  $("#inp_sottostruttura").val(msg[2]);                    
                  
                  $("#hid_id_sottostruttura").attr("value", id_sottostruttura);
                  $("#but_elimina").show();
                  $("#titolo_dialog").text("Modifica Sottostruttura");
                  
                  $('#dialog_gestione_sottostruttura').modal('show');
            },
            error: function() {
                alert ("error");
            }
        });                
    });
    
    $("#but_elimina").click(function(e){
        var id_sottostruttura = $("#hid_id_sottostruttura").val()*-1;
      
        var ajax_data="";
        
        ajax_data={
            id_sottostruttura:id_sottostruttura          
        };
                      
        $.ajax({
            type: "post",
            url: "actions/submit_salva_sottostruttura.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);  
                  $('#dialog_gestione_sottostruttura').modal('hide'); 
                  if (msg==0) { 

                      window.location="sottostrutture.php";

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
    
    $("#but_salva_gestione_sottostruttura").click(function(e){
    
        var inp_sottostruttura=$("#inp_sottostruttura").val();
        
        var id_sottostruttura = $("#hid_id_sottostruttura").val();
        var id_struttura = $("#sel_strutture").val();
        var ajax_data="";
        
        ajax_data={
            id_sottostruttura:id_sottostruttura,
            inp_sottostruttura:inp_sottostruttura,
            id_struttura:id_struttura
        };
        
        $.ajax({
            type: "post",
            url: "actions/submit_salva_sottostruttura.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);  
                  $('#dialog_gestione_sottostruttura').modal('hide'); 
                  if (msg==0) { 
                      
                      window.location="sottostrutture.php";
                      
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
<h4 style="text-align: center;">ELENCO SOTTOSTRUTTURE</h4><br /><br />  
<div class="container-fluid">  
    <div class="form-group row">
        <div class="col-lg-2" >
        <button id='but_nuova_sottostruttura' class='btn btn-info' style='margin-bottom:15px;'>Nuova Sottostruttura</button>
       </div>
    </div> 
    <div class="form-group row">
<?php
// Assign the query
   $query = "SELECT ss.id_sottostruttura,ss.desc_sottostruttura,s.desc_struttura 
                FROM sottostrutture ss, strutture s 
                where ss.id_struttura=s.id_struttura"; 
    // Execute the query
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database:222 <br />". mysql_error());
    }

    echo "<table class='table table-striped table-bordered' width='100%' cellspacing='0' id='tab_sottostrutture'>";        
    echo "<thead><tr><th>ID</th><th>Struttura</th><th>Sottostruttura</th><th>Modifica</th></tr></thead><tbody>";
	
	// Fetch and display the results
	while ($row = mysql_fetch_assoc($result)){
		
		$id_sottostruttura = $row["id_sottostruttura"];
		$desc_struttura = $row["desc_struttura"];
                $desc_sottostruttura = $row["desc_sottostruttura"];
		
		echo "<tr>";
		echo "<td>$id_sottostruttura</td>";
		echo "<td>$desc_struttura</td>";
                echo "<td>$desc_sottostruttura</td>";
                echo "<td align='center'><img src='../images/modifica.png' class='hand' id='img_mod' alt='".$id_sottostruttura."'/></td>"; 
            echo "</tr>";
           
	}
	echo "</tbody></table>";

?>
</div>
</div>

<div class="modal fade" id="dialog_gestione_sottostruttura" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
  <div class="modal-content">
<div class="modal-header">                
        <h5 id="titolo_dialog"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
</div>  
<div class="modal-body">
<input type="hidden" id="hid_id_sottostruttura" value=""/>    
<table style="width: 100%;"> 
<tr>
    <td style="width: 130px;">
        <label class="control-label" for="sel_strutture">Struttura</label>     
    </td><td>    
        <select id="sel_strutture" class="form-control">
            <?
            $query="SELECT  id_struttura, desc_struttura FROM strutture";
            $result = mysql_query($query);
            if (!$result){
                    die ("Could not query the database: <br />". mysql_error());
            }
            while ($row = mysql_fetch_assoc($result)){
                $id=$row["id_struttura"];
                $val=$row["desc_struttura"];
                echo "<option value='$id'>$val</option>";
            }
            ?>
        </select> 
    </td>   
</tr>    
<tr>
    <td>
        <label class="control-label" for="inp_sottostruttura">Sottostruttura</label>     
    </td><td>    
        <input type="text" id="inp_sottostruttura" class="form-control" value="" /> 
    </td>   
</tr>
</table>
</div>
  <div class="modal-footer justify-content-between" >
        <button id='but_salva_gestione_sottostruttura' class='btn btn-success' ><i class='fa fa-save'> </i> Salva</button>    
        <button id='but_elimina' class='btn btn-danger' ><i class='fa fa-times'> </i> Elimina</button>  
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
 </div>      
</div>
</div>
</div>
</body>
</html>