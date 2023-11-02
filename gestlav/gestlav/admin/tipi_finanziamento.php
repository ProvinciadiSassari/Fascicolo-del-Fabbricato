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
<link href="../css/bootstrap.min.css" rel="stylesheet" media="screen" />
<link href="../css/base.css" rel="stylesheet" media="screen" />
<link rel="stylesheet" href="../css/redmond/jquery-ui.css" type="text/css" media="all" />
<link rel="stylesheet" href="../css/demo_table_jui.css" type="text/css" media="all" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/jquery-ui.js" type="text/javascript"></script>
<script src="../js/bootstrap.min.js"></script> 
<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
<script type="text/javascript">
$(document).ready( function(){
      var oTable = $('#tab_finanziamenti').dataTable({
        "bJQueryUI": true,
        "aaSorting": [[0, "asc"]],
        "iDisplayLength": 25,        
        "sPaginationType": "full_numbers"
    });

    /* Add a click handler to the rows - this could be used as a callback */
    $("#tab_finanziamenti tbody").click(function(event) {
        $(oTable.fnSettings().aoData).each(function (){
                $(this.nTr).removeClass('row_selected');
        });
        $(event.target.parentNode).addClass('row_selected');
    });     
       
    $("#but_nuovo_finanziamento").click(function(e){
        $("#inp_finanziamento").val("");   
      
        $("#hid_id_finanziamento").attr("value", 0);
        
        $("#titolo_dialog").text("Nuovo Tipo Finanziamento");
        $('#dialog_gestione_finanziamento').modal('show');
    }); 
    
    
    $(document).on("click", "#img_mod", function(){ 
        
        var id_finanziamento=$(this).attr("alt");
      
        $.ajax({
            type: "post",
            dataType: "json",
            url: "../query/retrieve_dati_tipo_finanziamento.php",                                                 
            data: "id_finanziamento="+id_finanziamento,
            success: function(msg) { 
//                alert(msg);                   
                  $("#inp_finanziamento").val(msg[1]);                    
                  
                  $("#hid_id_finanziamento").attr("value", id_finanziamento);
                  
                  $("#titolo_dialog").text("Modifica Tipo Finanziamento");
                  
                  $('#dialog_gestione_finanziamento').modal('show');
            },
            error: function() {
                alert ("error");
            }
        });                
    });
    
    $("#but_salva_gestione_finanziamento").click(function(e){
    
        var inp_finanziamento=$("#inp_finanziamento").val();
        
        var id_finanziamento = $("#hid_id_finanziamento").val();
        var ajax_data="";
        
        ajax_data={
            id_finanziamento:id_finanziamento,
            inp_finanziamento:inp_finanziamento
        };
        
        $.ajax({
            type: "post",
            url: "actions/submit_salva_tipo_finanziamento.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);  
                  $('#dialog_gestione_finanziamento').modal('hide'); 
                  if (msg==0) { 
                      
                      window.location="tipi_finanziamento.php";
                      
                      $('#alert_success_finanziamento').modal("show");
                    }                 
            },
            error: function() {
                alert ("error");
            }
        });
    });
});
</script>
<style>
#dialog_gestione_finanziamento .modal-body {
    max-height: 700px;
}
#dialog_gestione_finanziamento {
    width: 550px; 
    margin: -280px 0 0 -350px; 
}
</style>
</head>
<body>
<? 
include("header.php");
include('menu.php');
?>
    
<div class='title'>ELENCO TIPI FINANZIAMENTO</div><br /><br />  
<div class="container-fluid">        
<div class="row-fluid">
    <div class="span12" id="container_gestione_finanziamenti">   
<?php
// Assign the query
   $query = "SELECT ID, Tipo_Finanziamento
                FROM tipo_finanziamento"; 
   
    // Execute the query
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database:222 <br />". mysql_error());
    }
	         
    echo "<button id='but_nuovo_finanziamento' class='btn' style='margin-bottom:15px;'>Nuovo Tipo Finanziamento</button>"; 
    echo "<table class=\"display\" id='tab_finanziamenti'>";        
    echo "<thead><tr><th>ID</th><th>Tipo Finanziamento</th><th>Modifica</th></tr></thead><tbody>";
	
	// Fetch and display the results
	while ($row = mysql_fetch_assoc($result)){
		
		$id_tipo_finanziamento = $row["ID"];
		$desc_tipo_finanziamento= $row["Tipo_Finanziamento"];
		
		echo "<tr>";
		echo "<td>$id_tipo_finanziamento</td>";
		echo "<td>$desc_tipo_finanziamento</td>";		
                echo "<td align='center'><img src='../images/modifica.png' class='hand' id='img_mod' alt='".$id_tipo_finanziamento."'/></td>"; 
            echo "</tr>";
           
	}
	echo "</tbody></table>";
echo "</div></div>";

?>
<div id="dialog_gestione_finanziamento" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
<div class="modal-header">        
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5 id="titolo_dialog"></h5>
</div>   
<div class="modal-body">
<input type="hidden" id="hid_id_finanziamento" value=""/>    
<table>             
<tr>
    <td>
        <label class="control-label" for="inp_finanziamento">Tipo Finanziamento</label>     
    </td><td>    
        <input type="text" id="inp_finanziamento" class="input-xlarge" value="" /> 
    </td>   
</tr>
</table>
</div> 
 <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Chiudi</a>
        <a href="#" id="but_salva_gestione_finanziamento" class="btn btn-success">Salva</a>
 </div>  
</div>  
<div class="alert alert-success hide modal fade" id="alert_success_finanziamento">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> 
   <span>Operazione avvenuta con successo!</span>
</div>        
</body>
</html>