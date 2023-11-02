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
    
    $('#tab_fabbricati').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "language": {
            "url": '../language/Italian.json'
        } 
    });
       
    $("#but_nuova_fabbricato").click(function(e){
        $("#inp_fabbricato").val(""); 
        $("#inp_id_fabbricato").val(""); 
        $("#inp_id_fabbricato").attr("readonly",false);
        $("#inp_cap_fabbricato").val("");
        $("#sel_comune").val(0); 
        $("#inp_indirizzo").val("");       
        
        $("#hid_id_fabbricato").attr("value", 0);
        
        $("#titolo_dialog").text("Nuovo Fabbricato");
        $('#dialog_gestione_fabbricato').modal('show');
    }); 
    
    
    $(document).on("click", "#img_mod", function(){ 
        
        var id_fabbricato=$(this).attr("alt");
        
        $.ajax({
            type: "post",
            dataType: "json",
            url: "../query/retrieve_dati_fabbricato.php",                                                 
            data: "id_fabbricato="+id_fabbricato,
            success: function(msg) { 
//                alert(msg[1]);
                  $("#inp_id_fabbricato").val(msg[0]);
                  $("#inp_id_fabbricato").attr("readonly",true);
                  $("#inp_fabbricato").val(msg[1]);  
                  $("#inp_indirizzo").val(msg[2]);
                  $("#inp_cap_fabbricato").val(msg[3]);
                  $("#sel_comune").val(msg[4]);                       
                  
                  $("#hid_id_fabbricato").attr("value", id_fabbricato);
                  
                  $("#titolo_dialog").text("Modifica Fabbricato");
                  
                  $('#dialog_gestione_fabbricato').modal('show');
            },
            error: function() {
                alert ("error");
            }
        });                
    });
    
    $("#but_salva_gestione_fabbricato").click(function(e){
        
        var inp_id_fabbricato=$("#inp_id_fabbricato").val();
        var inp_fabbricato=$("#inp_fabbricato").val();
        var inp_indirizzo=$("#inp_indirizzo").val();
        var sel_comune=$("#sel_comune").val();
        var inp_cap_fabbricato=$("#inp_cap_fabbricato").val();       
        
        var id_fabbricato = $("#hid_id_fabbricato").val();
        var ajax_data="";
        
        ajax_data={
            id_fabbricato:id_fabbricato,
            inp_id_fabbricato:inp_id_fabbricato,
            inp_fabbricato:inp_fabbricato,
            inp_cap_fabbricato:inp_cap_fabbricato,
            sel_comune:sel_comune,
            inp_indirizzo:inp_indirizzo
        };
        
        if (inp_fabbricato!="" && inp_id_fabbricato!="") {
            $.ajax({
                type: "post",
                url: "actions/submit_salva_fabbricato.php",                                                 
                data: ajax_data,
                success: function(msg) { 
//                    alert(msg);  
                      $('#dialog_gestione_fabbricato').modal('hide'); 
                      if (msg==0) {                       
                          window.location="fabbricati.php";

                         swal(
                            'Informazione',
                            'Operazione avvenuta con successo.',
                            'success'
                          );
                      }
                      else {                      
                          swal(
                                'Informazione',
                                'ID Fabbricato già utilizzato.',
                                'warning'
                              );
                        return false;
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
                    'ID Fabbricato o Fabbricato non valorizzato.',
                    'warning'
                  );
          
          return false;
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
<h4 style="text-align: center;">ELENCO FABBRICATI</h4><br /><br />  
<div class="container-fluid">  
    <div class="form-group row">
        <div class="col-lg-2" >
        <button id='but_nuova_fabbricato' class='btn btn-info' style='margin-bottom:15px;'>Nuovo Fabbricato</button>
       </div>
    </div> 
    <div class="form-group row">
<?php
// Assign the query
   $query = "SELECT f.id_fabbricato, f.descrizione_fabbricato, f.indirizzo_fabbricato, f.cap_fabbricato, f.id_comune,c.desc_comune
              FROM fabbricati f,comuni c where f.id_comune=c.id_comune"; 
    // Execute the query
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database:222 <br />". mysql_error());
    }
	         
    echo "<table class='table table-striped table-bordered' width='100%' cellspacing='0' id='tab_fabbricati'>";        
    echo "<thead><tr><th>ID</th><th>Fabbricato</th><th>Indirizzo</th><th>CAP</th><th>Comune</th><th>Modifica</th></tr></thead><tbody>";
	
	// Fetch and display the results
	while ($row = mysql_fetch_assoc($result)){
		
		$id_fabbricato = $row["id_fabbricato"];
		$desc_fabbricato = $row["descrizione_fabbricato"];
                $indirizzo_fabbricato = $row["indirizzo_fabbricato"];
		$cap_fabbricato = $row["cap_fabbricato"];
                $desc_comune = $row["desc_comune"];		
		
		echo "<tr>";
		echo "<td>$id_fabbricato</td>";
		echo "<td>$desc_fabbricato</td>";	
                echo "<td>$indirizzo_fabbricato</td>";
		echo "<td>$cap_fabbricato</td>";	
                echo "<td>$desc_comune</td>";		
                echo "<td align='center'><img src='../images/modifica.png' class='hand' id='img_mod' alt='".$id_fabbricato."'/></td>"; 
            echo "</tr>";
           
	}
	echo "</tbody></table>";

?>
</div>
</div>
<div class="modal fade" id="dialog_gestione_fabbricato" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
  <div class="modal-content">
<div class="modal-header">                
        <h5 id="titolo_dialog"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
</div>  
<div class="modal-body">
<input type="hidden" id="hid_id_fabbricato" value=""/>    
<table style="width: 100%;">
    <tr>
        <td style="width: 150px;">
            <label class="control-label" for="inp_id_fabbricato">ID Fabbricato</label>     
        </td><td colspan="3">                      
            <input type="text" id="inp_id_fabbricato" class="form-control" value="" />
        </td>
    </tr>
    <tr>
        <td>
            <label class="control-label" for="inp_fabbricato">Fabbricato</label>     
        </td><td colspan="3">                      
            <input type="text" id="inp_fabbricato" class="form-control" value="" />
        </td>
    </tr> 
    <tr>
        <td>
            <label class="control-label" for="inp_indirizzo">Indirizzo</label>     
        </td><td colspan="3">                      
            <input type="text" id="inp_indirizzo" class="form-control" value="" />
        </td>
    </tr>
    <tr>
        <td>
            <label class="control-label" for="inp_cap_fabbricato">CAP</label>     
        </td><td colspan="3">                      
            <input type="text" id="inp_cap_fabbricato" class="form-control" value="" />
        </td>
    </tr>
    <tr>
        <td>
            <label class="control-label" for="sel_comune">Comune</label>     
        </td><td colspan="3">    
            <select id="sel_comune" class="form-control">
                <option value="0">- Seleziona -</option>
             <?
              $query="SELECT id_comune,desc_comune FROM comuni order by desc_comune";
              $result = mysql_query($query);
              if (!$result){
                      die ("Could not query the database: <br />". mysql_error());
              }
              while ($row = mysql_fetch_assoc($result)){
                  $id=$row["id_comune"];
                  $val=$row["desc_comune"];
                  echo "<option value='$id'>";
                  echo $val;
                  echo "</option>";                        
              }                    
             ?>   
            </select> 
        </td>
      </tr>                
</table> 
</div> 
 <div class="modal-footer justify-content-between" >
        <button id='but_salva_gestione_fabbricato' class='btn btn-success' ><i class='fa fa-save'> </i> Salva</button>        
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
 </div>        
</div> 
</div>
</div>             
</body>
</html>