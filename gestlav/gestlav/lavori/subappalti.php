<?php
session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();



if (isset($_GET["id_lavoro"])) {
    $id_lavoro=$_GET["id_lavoro"];     
}
else $id_lavoro=0;

$fl_avanzato=$_SESSION['avanzato'];

?>

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {                

    var id_lavoro=<?=$id_lavoro;?>;
    var fl_avanzato=<?=$fl_avanzato;?>;
    var id_livello=<?=$_SESSION['idlevel'];?>;

     
      if (id_livello==7) {
          $("#but_nuovo_subappalto").hide();
          $("#but_salva_nuovo_subappalto").hide();
          
      }
        
     $('.selectpicker').selectpicker();
     
      if (fl_avanzato==0)
         $("#but_nuovo_subappalto").hide();
     
     $('#tab_subappalti').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "language": {
            "url": 'language/Italian.json'
        } 
    });
    
    $("#but_nuovo_subappalto").click(function(e){  
        $("#hid_tipo_operazione_sub").val("ins");
        $("#h_title_sub").text("Nuovo Subappalto");
        $("#sel_imprese_sub").val(0);               
        $("#ta_descrizione_sub").val("");
        $("#ta_note_sub").val("");
        $("#inp_titolare_sub").val("");
        $("#inp_indirizzo_sub").val("");
        $("#inp_tel1_sub").val("");
        $("#inp_fax_sub").val("");
        $("#sel_comune_sub").val(0);
        $("#hid_id_subappalto").val(0);
        $('.selectpicker').selectpicker('refresh');
         $('#dialog_subappalto').modal('show');
      });
      
      $(document).on("click","#img_mod_subappalto",function(){
          var id_subappalto=$(this).attr("alt");
          
          $.ajax({
            type: "post",
            dataType: "json",
            url: "query/retrieve_dati_subappalto.php",                                                 
            data: "id_subappalto="+id_subappalto,
            success: function(msg) {   
//                alert(msg);
                $("#hid_id_subappalto").val(id_subappalto);
               $("#sel_imprese_sub").val(msg[2]);               
                $("#ta_descrizione_sub").val(msg[3]);
                $("#ta_note_sub").val(msg[8]);
               $('.selectpicker').selectpicker('refresh');
               $("#sel_imprese_sub").trigger("change");
               $("#hid_tipo_operazione_sub").val("mod");
              $("#h_title_sub").text("Dettaglio Subappalto");
               $('#dialog_subappalto').modal('show');
            },
            error: function() {
//                        alert ("error");
            }
        });

      });
      
      $("#sel_imprese_sub").change(function(e){
    
        var id_impresa=$(this).val();
       
        $.ajax({
            type: "post",
            dataType: "json",
            url: "query/retrieve_dati_impresa.php",                                                 
            data: "id_impresa="+id_impresa,
            success: function(msg) { 
//                alert(msg);                   
                  $("#inp_titolare_sub").val(msg[2]);   
                  $("#sel_comune_sub").val(msg[3]);
                  $("#inp_indirizzo_sub").val(msg[4]);
                  $("#inp_tel1_sub").val(msg[5]);
                  $("#inp_fax_sub").val(msg[7]);
            },
            error: function() {
//                alert ("error");
            }
        });
     }); 
      
     $("#but_salva_nuovo_subappalto").click(function(e) {
        e.preventDefault();
        
        var ta_descrizione_sub=$("#ta_descrizione_sub").val();
        var ta_note_sub=$("#ta_note_sub").val();
        var sel_imprese=$("#sel_imprese_sub").val();
        var tipo_operazione=$("#hid_tipo_operazione_sub").val();
        var id_subappalto=$("#hid_id_subappalto").val();             
        var ajax_data="";
        
        ajax_data={            
            id_lavoro:id_lavoro,
            ta_descrizione_sub:ta_descrizione_sub,
            ta_note_sub:ta_note_sub,
            sel_imprese:sel_imprese,
            id_subappalto:id_subappalto,
            tipo_operazione:tipo_operazione
        };
        
        $.ajax({
            type: "post",
            url: "actions/submit_salva_nuovo_subappalto.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);  
               
               swal(
                    'Informazione',
                    'Operazione avventuta con successo.',
                    'success'
                  );
                  $("#div_subappalti").load("lavori/subappalti.php?id_lavoro="+id_lavoro); 
                $('#dialog_subappalto').modal('hide');
                $('.modal-backdrop').remove();
                
              
            },
            error: function() {
                alert ("error");
            }
        });
        
     });   
        
});
</script>
<button id="but_nuovo_subappalto" class="btn btn-warning btn-sm" type="button">Nuovo Subappalto</button>
<br /><br />
<div id="div_subappalti">
    <table class="table table-striped table-bordered" width="100%" cellspacing="0" id="tab_subappalti" >
       <thead><tr><th align='center'>ID</th><th>Descrizione</th><th>Dettaglio</th></tr></thead>
       <tbody>
	
	<?php
        $query = "SELECT
                    s.ID,                                       
                    s.Descrizione                                      
                    FROM subappalti s
                WHERE s.IDLavoro=$id_lavoro";
        
	// Execute the query
	$result = mysql_query($query);
	if (!$result){
		die ("Could not query the database: <br />". mysql_error());
	}
	while ($row = mysql_fetch_assoc($result)) {
				
		$ID = $row["ID"];                			
		$Descrizione = $row["Descrizione"];				               		
		
		echo "<tr>";
		echo "<td align='center'>$ID</td>";
		echo "<td width='620'>".$Descrizione."</td>";	
                echo "<td align='center'><img src='images/document.png' class='hand' id='img_mod_subappalto' style='width:24px;' alt='".$ID."'/></td>"; 
		echo "</tr>";		
	}
        ?>
	</tbody>
    </table>
</div>
<div id="dialog_subappalto" class="modal fade"  role="dialog">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
<div class="modal-header">                
        <h5 id="h_title_sub"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
</div>   
<div class="modal-body">
 <input type="hidden" id="hid_tipo_operazione_sub" value="" />   
 <input type="hidden" id="hid_id_subappalto" value="0" />   
    <div class="container-fluid"> 
<div class="form-group row">
    <label class="control-label col-lg-1" for="ta_descrizione_sub">Descrizione</label>     
   <div class="col-lg-10">  
    <textarea type="text" id="ta_descrizione_sub" rows="2" class="form-control" value="" ></textarea> 
    </div>
</div>
<div class="form-group row">
    <label class="control-label col-lg-1" for="ta_note_sub">Note</label>     
    <div class="col-lg-10">  
    <textarea type="text" id="ta_note_sub" rows="2" class="form-control" value="" ></textarea> 
    </div>
</div>
<div class="form-group row">
    <h5>Dati impresa</h5>
</div>    
<div class="form-group row">
        <label class="control-label col-lg-1" for="sel_imprese_sub">Impresa</label>     
    <div class="col-lg-10">                      
        <select id="sel_imprese_sub" class="form-control selectpicker" data-container="body" data-live-search="true" >
            <option value="0">- Seleziona -</option>
          <?php 
          $query="select IDImpresa,Impresa FROM imprese order by Impresa";
          $result = mysql_query($query);
          if (!$result){
                  die ("Could not query the database: <br />". mysql_error());
          }
          while ($row = mysql_fetch_assoc($result)){
              $id=$row["IDImpresa"];
              $val=$row["Impresa"];
              echo "<option value='$id'>";
              echo $val;
              echo "</option>";                        
          }                    
         ?>
        </select>
   </div>
</div>
<div class="form-group row">
                  <label class="control-label col-lg-1" for="inp_titolare_sub">Titolare</label>     
              <div class="col-lg-10">   
                  <input type="text" id="inp_titolare_sub" class="form-control" value="" readonly/> 
              </div>              
</div>
<div class="form-group row">
                  <label class="control-label col-lg-1" for="sel_comune_sub">Comune</label>     
              <div class="col-lg-10">    
                  <select id="sel_comune_sub" class="form-control" disabled>
                      <option value="0"></option>
                   <?php
                    $query="SELECT IDComune,NomeComune FROM comuni_lav";
                    $result = mysql_query($query);
                    if (!$result){
                            die ("Could not query the database: <br />". mysql_error());
                    }
                    while ($row = mysql_fetch_assoc($result)){
                        $id=$row["IDComune"];
                        $val=$row["NomeComune"];
                        echo "<option value='$id'>";
                        echo $val;
                        echo "</option>";                        
                    }                    
                   ?>   
                  </select> 
              </div>
</div>
<div class="form-group row">           
                  <label class="control-label col-lg-1" for="inp_indirizzo_sub">Indirizzo</label>     
              <div class="col-lg-10">   
                  <input type="text" id="inp_indirizzo_sub" class="form-control" value="" readonly/> 
              </div>             
</div>
<div class="form-group row">            
                  <label class="control-label col-lg-1" for="inp_tel1_sub">Tel.</label>  
              <div class="col-lg-5">    
                  <input type="text" id="inp_tel1_sub" class="form-control" value="" readonly/>
              </div>   
                   <label class="control-label col-lg-1" for="inp_fax_sub">Fax</label> 
              <div class="col-lg-4">       
                  <input type="text" id="inp_fax_sub" class="form-control" value="" readonly/>
              </div>
</div> 
</div>          
</div> 
<div class="modal-footer justify-content-between">
       <button id='but_salva_nuovo_subappalto' class='btn btn-success' > Salva</button>        
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
</div>  
</div>
    </div>
    </div>


   

