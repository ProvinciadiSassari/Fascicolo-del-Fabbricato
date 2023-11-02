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
    $('#tab_descrizione_quadro').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "language": {
            "url": '../language/Italian.json'
        } 
    });
       
    $("#but_nuova_descrizione").click(function(e){
        $("#inp_descrizione").val("");   
      
        $("#hid_id_descrizione").attr("value", 0);
        $("#but_elimina").hide();
        $("#titolo_dialog").text("Nuova Descrizione");
        $('#dialog_gestione_descrizione').modal('show');
    }); 
    
    
    $(document).on("click", "#img_mod", function(){ 
        
        var id_descrizione=$(this).attr("alt");
      
        $.ajax({
            type: "post",
            dataType: "json",
            url: "../query/retrieve_dati_descrizione.php",                                                 
            data: "id_descrizione="+id_descrizione,
            success: function(msg) { 
//                alert(msg);                   
                  $("#inp_descrizione").val(msg[1]);                    
                  $("#sel_gruppi").val(msg[2]);
                  $("#hid_id_descrizione").attr("value", id_descrizione);
                  $("#but_elimina").show();
                  $("#titolo_dialog").text("Modifica Descrizione");
                  
                  $('#dialog_gestione_descrizione').modal('show');
            },
            error: function() {
                alert ("error");
            }
        });                
    });
    
    $("#but_elimina").click(function(e){
        var id_descrizione = $("#hid_id_descrizione").val()*-1;
      
        var ajax_data="";
        
        ajax_data={
            id_descrizione:id_descrizione          
        };
                      
        $.ajax({
            type: "post",
            url: "actions/submit_salva_descrizione.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);  
                  $('#dialog_gestione_descrizione').modal('hide'); 
                  if (msg==0) { 

                      window.location="descrizioni_quadro.php";

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
    
    $("#but_salva_gestione_descrizione").click(function(e){
    
        var inp_descrizione=$("#inp_descrizione").val();
        var sel_gruppo=$("#sel_gruppi").val(); 
        var id_descrizione = $("#hid_id_descrizione").val();
        var ajax_data="";
        
        ajax_data={
            id_descrizione:id_descrizione,
            inp_descrizione:inp_descrizione,
            sel_gruppo:sel_gruppo
        };
        
        $.ajax({
            type: "post",
            url: "actions/submit_salva_descrizione.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);  
                  $('#dialog_gestione_descrizione').modal('hide'); 
                  if (msg==0) { 
                      
                      window.location="descrizioni_quadro.php";
                      
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
<h4 style="text-align: center;">ELENCO DESCRIZIONI QUADRO ECONOMICO</h4><br /><br />  
<div class="container-fluid">  
    <div class="form-group row">
        <div class="col-lg-2" >
        <button id='but_nuova_descrizione' class='btn btn-info' style='margin-bottom:15px;'>Nuova Descrizione</button>
       </div>
    </div> 
    <div class="form-group row">
<?php
// Assign the query
   $query = "SELECT d.id_descrizione_quadro,d.desc_descrizione_quadro,d.id_gruppo_quadro,g.desc_gruppo 
                FROM descrizioni_quadro d,gruppi g where d.id_gruppo_quadro=g.id_gruppo"; 
    // Execute the query
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database:222 <br />". mysql_error());
    }
	         
    echo "<table class='table table-striped table-bordered' width='100%' cellspacing='0' id='tab_descrizione_quadro'>";        
    echo "<thead><tr><th>ID</th><th>Descrizione</th><th>Gruppo</th><th>Modifica</th></tr></thead><tbody>";
	
	// Fetch and display the results
	while ($row = mysql_fetch_assoc($result)){
		
		$id_descrizione = $row["id_descrizione_quadro"];
		$desc_descrizione = $row["desc_descrizione_quadro"];
		$desc_gruppo = $row["desc_gruppo"];
                
		echo "<tr>";
		echo "<td>$id_descrizione</td>";
		echo "<td>".($desc_descrizione)."</td>";	
                echo "<td>$desc_gruppo</td>";	
                echo "<td align='center'><img src='../images/modifica.png' class='hand' id='img_mod' alt='".$id_descrizione."'/></td>"; 
            echo "</tr>";
           
	}
	echo "</tbody></table>";
?>
</div></div>        

<div class="modal fade" id="dialog_gestione_descrizione" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
  <div class="modal-content">
<div class="modal-header">                
        <h5 id="titolo_dialog"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
</div>  
<div class="modal-body">
<input type="hidden" id="hid_id_descrizione" value=""/>    
<table style="width: 100%;"> 
<tr>
    <td style="width: 130px;">
        <label class="control-label" for="sel_gruppi">Gruppo</label>     
    </td><td>    
        <select id="sel_gruppi" class="form-control">
            <?
            $query="SELECT  id_gruppo, desc_gruppo FROM gruppi";
            $result = mysql_query($query);
            if (!$result){
                    die ("Could not query the database: <br />". mysql_error());
            }
            while ($row = mysql_fetch_assoc($result)){
                $id=$row["id_gruppo"];
                $val=$row["desc_gruppo"];
                echo "<option value='$id'>$val</option>";
            }
            ?>
        </select> 
    </td>   
</tr>    
<tr>
    <td>
        <label class="control-label" for="inp_descrizione">Descrizione</label>     
    </td><td>    
        <input type="text" id="inp_descrizione" class="form-control" value="" /> 
    </td>   
</tr>
</table>
</div> 
<div class="modal-footer justify-content-between" >
        <button id='but_salva_gestione_descrizione' class='btn btn-success' ><i class='fa fa-save'> </i> Salva</button>    
        <button id='but_elimina' class='btn btn-danger' ><i class='fa fa-times'> </i> Elimina</button>  
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
 </div>           
</div>
 </div>           
</div>           
</body>
</html>