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
    
     $('#tab_utenti').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "language": {
            "url": '../language/Italian.json'
        } 
    });
  
       
    $("#but_nuovo_utente").click(function(e){
        $("#inp_username").val("");   
        $("#inp_password").val("");
        $("#inp_nominativo").val("");
        $("#sel_livello").val(2);  
        $("#tr_competenza").show();  
        $("#chk_avanzato").prop("checked", false);
        $("#hid_id_utente").val(0);
        
        $("#titolo_dialog").text("Nuovo Utente");
        $('#dialog_gestione_utente').modal('show');
    }); 
    
    $("#sel_livello").change(function(){
        if ($(this).val()==1) {
            $("#tr_competenza").hide(); 
        }
        else {
            $("#tr_competenza").show();
        }
    });
    
    $(document).on("click", "#img_mod", function(){ 
        
        var id_utente=$(this).attr("alt");
      
        $.ajax({
            type: "post",
            dataType: "json",
            url: "../query/retrieve_dati_utente.php",                                                 
            data: "id_utente="+id_utente,
            success: function(msg) { 
//                alert(msg);                   
                  $("#inp_username").val(msg[1]);   
                  $("#inp_password").val(msg[2]);
                  $("#inp_nominativo").val(msg[3]);
                  $("#sel_livello").val(msg[4]);
                    if (msg[4]==1) {
                        $("#tr_competenza").hide(); 
                        $("#id_competenza").val(0);
                    }
                    else {
                        $("#tr_competenza").show();
                        $("#id_competenza").val(msg[6]);
                    }
                  
                  if (msg[5]==1)
                    $("#chk_avanzato").prop("checked", true);
                  else 
                      $("#chk_avanzato").prop("checked", false);
                  
                  $("#sel_livello").val(msg[4]);
                  
                  $("#hid_id_utente").val(id_utente);
                  
                  $("#titolo_dialog").text("Modifica Utente");
                  
                  $('#dialog_gestione_utente').modal('show');
            },
            error: function() {
                alert ("error");
            }
        });                
    });
    
    $(document).on("click", "#img_del", function(){ 
        
        var id_utente=$(this).attr("alt");
        
       $.ajax({
            type: "post",
            dataType: "json",
            url: "../query/retrieve_dati_utente.php",                                                 
            data: "id_utente="+id_utente,
            success: function(msg) { 
//                alert(msg);                                    
                  $("#hid_id_utente_elim").val(id_utente);
                  
                  $("#p_elimina_utente").html("Vuoi eliminare l'utente selezionato? <br />"+"("+msg[1]+")");
                  
                  $('#dialog_elimina_utente').modal('show');
            },
            error: function() {
                alert ("error");
            }
        });             
    });
    
    $("#but_elimina_utente").click(function(e){
        var id_utente = $("#hid_id_utente_elim").val();
        
        $.ajax({
            type: "post",
            url: "actions/submit_elimina_utente.php",                                                 
            data: "id_utente="+id_utente,
            success: function(msg) { 
//                alert(msg);  
                  swal(
                    'Informazione',
                    'Operazione avvenuta con successo.',
                    'success'
                  );
                  window.location="dati_amministratore.php";
            },
            error: function() {
                alert ("error");
            }
        });
    
    });
    
    $("#but_salva_gestione_utente").click(function(e){
    
        var inp_username=$("#inp_username").val();
        var inp_password=$("#inp_password").val();
        var inp_nominativo=$("#inp_nominativo").val();
        var id_competenza=$("#id_competenza").val();
        var sel_livello=$("#sel_livello").val(); 
        if (sel_livello==1) {
            id_competenza=0;
        }
        var chk_avanzato = 0;
        if ( $("#chk_avanzato").is(':checked'))
            chk_avanzato=1;
        var id_utente = $("#hid_id_utente").val();
        var ajax_data="";
        
        ajax_data={
            id_utente:id_utente,
            inp_username:inp_username,
            inp_password:inp_password,
            inp_nominativo:inp_nominativo,
            sel_livello:sel_livello,
            id_competenza:id_competenza,
            chk_avanzato:chk_avanzato
        };
        
        $.ajax({
            type: "post",
            url: "actions/submit_salva_utente.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);  
                  $('#dialog_gestione_utente').modal('hide'); 
                  if (msg==0) { 
                      swal(
                    'Informazione',
                    'Operazione avvenuta con successo.',
                    'success'
                  );
                      location.reload();
                    }
                  else {
                      swal(
                        'Informazione',
                        'Username già presente.',
                        'warning'
                      );
                      return false;
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
<h4 style="text-align: center;">ELENCO UTENTI</h4><br /><br />  
<div class="container-fluid">  
    <div class="form-group row">
        <div class="col-lg-2" >
        <button id='but_nuovo_utente' class='btn btn-info' style='margin-bottom:15px;'>Nuovo Utente</button>
       </div>
    </div> 
    <div class="form-group row">
<?php
// Assign the query
   $query = "SELECT u.a006_ID,u.a006_descrizione,u.nominativo,u.a006_livello,u.fl_avanzato,id_competenza
                FROM utenti_lav u "; 
    // Execute the query
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database:222 <br />". mysql_error());
    }
	         
    echo "<table class='table table-striped table-bordered' width='100%' cellspacing='0' id='tab_utenti'>";        
    echo "<thead><tr><th>ID</th><th>Username</th><th>Nominativo</th><th>Livello</th><th>Competenza</th><th>Avanzato</th><th>Modifica</th><th>Elimina</th></tr></thead><tbody>";
	
	// Fetch and display the results
	while ($row = mysql_fetch_assoc($result)){
		
		$id_utente = $row["a006_ID"];
		$nm_utente = $row["a006_descrizione"];
		$nominativo = $row["nominativo"];
                $id_livello = $row["a006_livello"];
                $liv_utente="AMMINISTRATORE";
                if ($id_livello==5) $liv_utente="Utente Provincia";
                if ($id_livello==7) $liv_utente="Utente Esterno";
                if ($id_livello==2) $liv_utente="Utente MULTISS";
                $fl_avanzato = $row["fl_avanzato"];
                
                if ($fl_avanzato==0) $fl_avanzato="NO";
                else $fl_avanzato="SI";
                $competenza="Tutte";
                if ($row["id_competenza"]==1) {
                    $competenza="Sassari";
                }
                else if ($row["id_competenza"]==2) {
                    $competenza="Olbia";
                }
                
		echo "<tr>";
		echo "<td>$id_utente</td>";
		echo "<td>$nm_utente</td>";
		echo "<td>$nominativo</td>";
                echo "<td>$liv_utente</td>";   
                echo "<td>$competenza</td>";
                echo "<td>$fl_avanzato</td>"; 
                echo "<td align='center'><img src='../images/modifica.png' class='hand' id='img_mod' alt='".$id_utente."'/></td>"; 
                if ($id_livello!=1) {
                echo "<td align='center'><img src='../images/delete_record.png' class='hand' id='img_del' alt='".$id_utente."'/></td>";
                }
                else {
                     echo "<td align='center'></td>";
                }
            echo "</tr>";
           
	}
	echo "</tbody></table>";

?>
</div>
</div>
<div class="modal fade" id="dialog_gestione_utente" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
  <div class="modal-content">
<div class="modal-header">                
        <h5 id="titolo_dialog"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
</div>  
<div class="modal-body container-fluid">
<input type="hidden" id="hid_id_utente" value=""/>    
<table style="width: 100%;">             
<tr>
    <td>
        <label class="control-label" for="inp_username">Username</label>     
    </td><td>    
        <input type="text" id="inp_username" class="form-control" value="" /> 
    </td>
    <td>
        <label class="control-label" for="inp_password">&nbsp;&nbsp;&nbsp;Password</label>     
    </td><td>    
        <input type="text" id="inp_password" class="form-control" value="" /> 
    </td>
</tr>
<tr>
    <td>
        <label class="control-label" for="inp_nominativo">Nominativo</label>     
    </td><td colspan="3">    
        <input type="text" id="inp_nominativo" class="form-control" value="" /> 
    </td>   
</tr>
<tr>              
    <td>
        <label class="control-label" for="sel_livello">Livello</label>     
    </td><td colspan="3">    
        <select id="sel_livello" class="form-control">         
         <?
          $query="SELECT id_livello,descrizione FROM livelli_lav";
          $result = mysql_query($query);
          if (!$result){
                  die ("Could not query the database: <br />". mysql_error());
          }
          while ($row = mysql_fetch_assoc($result)){
              $id=$row["id_livello"];
              $val=$row["descrizione"];
              echo "<option value='$id'>";
              echo $val;
              echo "</option>";                        
          }                    
         ?>   
        </select>
    </td>              
</tr>
<tr id="tr_competenza">              
    <td>
        <label class="control-label" for="id_competenza">Competenza</label>     
    </td><td colspan="3">    
        <select id="id_competenza" class="form-control">  
            <option value="0">- Tutte le competenze</option>
         <?
          $query="SELECT a013_ID,a013_descrizione FROM competenza order by a013_ID";
          $result = mysql_query($query);
          if (!$result){
                  die ("Could not query the database: <br />". mysql_error());
          }
          while ($row = mysql_fetch_assoc($result)){
              $id=$row["a013_ID"];
              $val=$row["a013_descrizione"];
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
        <label class="control-label" for="chk_avanzato">Avanzato</label>     
    </td><td>    
        <input type="checkbox" value="" id="chk_avanzato" class="form-control" style="margin-bottom:4px;"/>
    </td>    
</tr>
</table> 
</div> 
 <div class="modal-footer justify-content-between" >
        <button id='but_salva_gestione_utente' class='btn btn-success' ><i class='fa fa-save'> </i> Salva</button>        
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
 </div> 
</div> 
</div>
</div>

<div class="modal fade" id="dialog_elimina_utente" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
  <div class="modal-content">
<div class="modal-header">                
        <h5 id="titolo_elim_dialog">Cancellazione Utente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
</div>   
<div class="modal-body">
<input type="hidden" id="hid_id_utente_elim" value=""/>    
<p id="p_elimina_utente"></p>
</div>
  <div class="modal-footer justify-content-between" >
        <button id='but_elimina_utente' class='btn btn-success' ><i class='fa fa-save'> </i> Elimina</button>        
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
 </div>    
</div> 
</div>
</div>             
       
</body>
</html>