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
    $('#tab_sottodescrizioni').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "language": {
            "url": '../language/Italian.json'
        } 
    });
    
       
    $("#but_nuova_sottodescrizione").click(function(e){
        $("#inp_sottodescrizione").val("");  
        $("#sel_descrizioni").val(1);
        $("#inp_ordine").val(<?=$utility->max_progressivo_ordine();?>);
        $("#inp_iva").val("0.00");
        
        $("#hid_id_sottodescrizione").attr("value", 0);
        
        $("#but_elimina_sottodescrizione").hide();
        
        $("#titolo_dialog").text("Nuova Sottodescrizione");
        $('#dialog_gestione_sottodescrizione').modal('show');
    }); 
    
    
    $(document).on("click", "#img_mod", function(){ 
        
        var id_sottodescrizione=$(this).attr("alt");
        
        $.ajax({
            type: "post",
            dataType: "json",
            url: "../query/retrieve_dati_sottodescrizione.php",                                                 
            data: "id_sottodescrizione="+id_sottodescrizione,
            success: function(msg) { 
//                alert(msg); 
                  $("#sel_descrizioni").val(msg[1]);  
                  $("#inp_sottodescrizione").val(msg[2]);   
                  $("#inp_ordine").val(msg[3]);
                  $("#inp_iva").val(msg[4]);
                  $("#but_elimina_sottodescrizione").show();
                  $("#hid_id_sottodescrizione").attr("value", id_sottodescrizione);
                  
                  $("#titolo_dialog").text("Modifica Sottodescrizione");
                  
                  $('#dialog_gestione_sottodescrizione').modal('show');
            },
            error: function() {
                alert ("error");
            }
        });                
    });
    
    $("#but_elimina_sottodescrizione").click(function(e){
        var id_sottodescrizione = $("#hid_id_sottodescrizione").val()*-1;
      
        var ajax_data="";
        
        ajax_data={
            id_sottodescrizione:id_sottodescrizione          
        };
                      
        $.ajax({
            type: "post",
            url: "actions/submit_salva_sottodescrizione.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);  
                  $('#dialog_gestione_sottodescrizione').modal('hide'); 
                  if (msg==0) { 

                      window.location="sottodescrizioni_quadro.php";

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
    
    $("#but_salva_gestione_sottodescrizione").click(function(e){
    
        var inp_sottodescrizione=$("#inp_sottodescrizione").val();
        var inp_ordine=$("#inp_ordine").val();
        var inp_iva=$("#inp_iva").val();
        
        if (inp_iva=="") inp_iva=0;
        if (inp_ordine=="") inp_ordine=0;
        
        var id_sottodescrizione = $("#hid_id_sottodescrizione").val();
        var id_descrizione = $("#sel_descrizioni").val();
        var ajax_data="";
        
        ajax_data={
            id_sottodescrizione:id_sottodescrizione,
            inp_sottodescrizione:inp_sottodescrizione,
            id_descrizione:id_descrizione,
            inp_iva:inp_iva,
            inp_ordine:inp_ordine
        };
        
        if (inp_sottodescrizione!="" && inp_ordine>0) {
        
            $.ajax({
                type: "post",
                url: "actions/submit_salva_sottodescrizione.php",                                                 
                data: ajax_data,
                success: function(msg) { 
    //                alert(msg);  
                      $('#dialog_gestione_sottodescrizione').modal('hide'); 
                      if (msg==0) { 

                          window.location="sottodescrizioni_quadro.php";

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
        }
        else {
             swal(
                    'Informazione',
                    'Valorizzare Sottodescrizione e Ordine Progressivo.',
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
<h4 style="text-align: center;">ELENCO SOTTODESCRIZIONI QUADRO ECONOMICO</h4><br /><br />  
<div class="container-fluid">  
    <div class="form-group row">
        <div class="col-lg-2" >
        <button id='but_nuova_sottodescrizione' class='btn btn-info' style='margin-bottom:15px;'>Nuova Sottodescrizione</button>
       </div>
    </div> 
    <div class="form-group row">  
<?php
// Assign the query
   $query = "SELECT ss.id_sottodescrizione_quadro,ss.desc_sottodescrizione_quadro,ss.progressivo_ordine,s.desc_descrizione_quadro 
                FROM sottodescrizioni_quadro ss, descrizioni_quadro s 
                where ss.id_descrizione_quadro=s.id_descrizione_quadro"; 
    // Execute the query
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database:222 <br />". mysql_error());
    }
    echo "<table class='table table-striped table-bordered' width='100%' cellspacing='0' id='tab_sottodescrizioni'>";        
    echo "<thead><tr><th>ID</th><th>Sottodescrizione</th><th>Descrizione</th><th>Ordine</th><th>Modifica</th></tr></thead><tbody>";
	
	// Fetch and display the results
	while ($row = mysql_fetch_assoc($result)){
		
		$id_sottodescrizione = $row["id_sottodescrizione_quadro"];
                $desc_sottodescrizione_quadro = $row["desc_sottodescrizione_quadro"];
		$desc_descrizione_quadro = $row["desc_descrizione_quadro"];
                $progressivo_ordine = $row["progressivo_ordine"];
		
		echo "<tr>";
		echo "<td>$id_sottodescrizione</td>";
		echo "<td>".($desc_sottodescrizione_quadro)."</td>";
                echo "<td>".($desc_descrizione_quadro)."</td>";
                echo "<td>$progressivo_ordine</td>";
                echo "<td align='center'><img src='../images/modifica.png' class='hand' id='img_mod' alt='".$id_sottodescrizione."'/></td>"; 
            echo "</tr>";
           
	}
	echo "</tbody></table>";

?>
</div>
</div>
<div class="modal fade" id="dialog_gestione_sottodescrizione" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
  <div class="modal-content">
<div class="modal-header">                
        <h5 id="titolo_dialog"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
</div>           
<div class="modal-body">
<input type="hidden" id="hid_id_sottodescrizione" value=""/>    
<table style="width: 100%;"> 
<tr>
    <td style="width: 130px;">
        <label class="control-label" for="sel_descrizioni">Descrizione</label>     
    </td><td>    
        <select id="sel_descrizioni" class="form-control">
            <?
            $query="SELECT id_descrizione_quadro, desc_descrizione_quadro
                    FROM descrizioni_quadro";
            $result = mysql_query($query);
            if (!$result){
                    die ("Could not query the database: <br />". mysql_error());
            }
            while ($row = mysql_fetch_assoc($result)){
                $id=$row["id_descrizione_quadro"];
                $val=$row["desc_descrizione_quadro"];
                echo "<option value='$id'>$val</option>";
            }
            ?>
        </select> 
    </td>   
</tr>    
<tr>
    <td>
        <label class="control-label" for="inp_sottodescrizione">Sottodescrizione</label>     
    </td><td>    
        <input type="text" id="inp_sottodescrizione" class="form-control" value="" /> 
    </td>   
</tr>
<tr>
    <td>
        <label class="control-label" for="inp_iva">IVA (%)</label>     
    </td><td>    
        <input type="text" id="inp_iva" class="form-control" value="" /> 
    </td>   
</tr>
<tr>
    <td>
        <label class="control-label" for="inp_ordine">Ordine Progressivo</label>     
    </td><td>    
        <input type="text" id="inp_ordine" class="form-control" value="" /> 
    </td>   
</tr>
</table>
</div> 
<div class="modal-footer justify-content-between" >
        <button id='but_salva_gestione_sottodescrizione' class='btn btn-success' ><i class='fa fa-save'> </i> Salva</button>    
        <button id='but_elimina_sottodescrizione' class='btn btn-danger' ><i class='fa fa-times'> </i> Elimina</button>  
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
 </div>        
</div>  
 </div>        
</div>       
</body>
</html>