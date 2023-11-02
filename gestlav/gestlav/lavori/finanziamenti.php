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

?>

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {                
    
    var id_lavoro=<?=$id_lavoro;?>;
    $(".numeric_4").inputmask('decimal', { digits: 4, enforceDigitsOnBlur: true, integerDigits: 12,positionCaretOnTab: false });
    
    var id_livello=<?=$_SESSION['idlevel'];?>;

      if (id_livello==7) {
          $("#but_nuovo_finanziamento").hide();
          $("#but_salva_nuovo_finanziamento").hide();
          
      }
    
    $("#div_finanziamenti_tab").load("tables/table_finanziamenti.php?id_lavoro="+id_lavoro);

     $("#but_nuovo_finanziamento").click(function(e){
        $("#hid_tipo_operazione_fin").val("ins");
        $("#h_title_fin").text("Nuovo Finanziamento");
        $("#inp_somma_assegnata").val("");
        $("#inp_somma_erogata").val("");
        $("#ta_note_finanziamento").val(""); 
        $("#hid_id_finanziamento").val(0);
        $('#dialog_finanziamento').modal('show');
     });
     
     $(document).on("click","#img_mod_finanziamento",function(){
         var id_finanziamento=$(this).attr("alt");
          
          $.ajax({
            type: "post",
            dataType: "json",
            url: "query/retrieve_dati_finanziamento.php",                                                 
            data: "id_finanziamento="+id_finanziamento,
            success: function(msg) {   
//                alert(msg);
                $("#hid_id_finanziamento").val(id_finanziamento);
                $("#inp_somma_assegnata").val(msg[3]);
                $("#inp_somma_erogata").val(msg[4]);
                $("#ta_note_finanziamento").val(msg[5]); 
                $("#sel_fonti_finanziamento").val(msg[2]);
               $("#hid_tipo_operazione_fin").val("mod");
              $("#h_title_fin").text("Dettaglio Finanziamento");
               $('#dialog_finanziamento').modal('show');
            },
            error: function() {
//                        alert ("error");
            }
        });
     });
     
     $("#but_salva_nuovo_finanziamento").click(function(e) {
        
        var sel_fonti_finanziamento=$("#sel_fonti_finanziamento").val();
        var inp_somma_assegnata=$("#inp_somma_assegnata").val();
        var inp_somma_erogata=$("#inp_somma_erogata").val();
        var ta_note_finanziamento=$("#ta_note_finanziamento").val(); 
        
        if (inp_somma_assegnata=="") inp_somma_assegnata=0;
        if (inp_somma_erogata=="") inp_somma_erogata=0;
        var tipo_operazione=$("#hid_tipo_operazione_fin").val();
        var id_finanziamento=$("#hid_id_finanziamento").val();
        
        var ajax_data="";
        
        ajax_data={
            id_lavoro:id_lavoro,
            sel_fonti_finanziamento:sel_fonti_finanziamento,
            inp_somma_assegnata:inp_somma_assegnata,
            inp_somma_erogata:inp_somma_erogata,
            ta_note_finanziamento:ta_note_finanziamento,
            id_finanziamento:id_finanziamento,
            tipo_operazione:tipo_operazione
        };
        
        $.ajax({
            type: "post",
            url: "actions/submit_salva_nuovo_finanziamento.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);  
                swal(
                    'Informazione',
                    'Operazione avventuta con successo.',
                    'success'
                );
                
                $("#div_finanziamenti_tab").load("tables/table_finanziamenti.php?id_lavoro="+id_lavoro,function(){
                  
                });
                $('#dialog_finanziamento').modal('hide');  
                ('.modal-backdrop').remove();     
                        
            },
            error: function() {
                alert ("error");
            }
        });
        
     });
     
     
});
</script>
<button id="but_nuovo_finanziamento" class="btn btn-warning btn-sm" type="button">Nuovo Finanziamento</button>
<br /><br />
<div id="div_finanziamenti_tab">
 
    
</div>
<div id="dialog_finanziamento" class="modal fade"  role="dialog">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
<div class="modal-header">        
        <h5 id="h_title_fin"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
</div>   
<div class="modal-body">
     <input type="hidden" id="hid_tipo_operazione_fin" value="" />   
 <input type="hidden" id="hid_id_finanziamento" value="0" /> 
<div class="container-fluid">            
<div class="form-group row">
        <label class="control-label col-lg-2" for="sel_fonti_finanziamento">Fonti di Finanziamento</label>     
    <div class="col-lg-5">     
        <select id="sel_fonti_finanziamento" class="form-control">
        <?php
        $query="SELECT
                    ID,
                    Tipo_Finanziamento
                FROM tipo_finanziamento";
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }
        while ($row = mysql_fetch_assoc($result)){
            $id=$row["ID"];
            $val=$row["Tipo_Finanziamento"];
            echo "<option value='$id'>";
            echo $val;
            echo "</option>";                        
        }                    
        ?>   
        </select> 
    </div>
</div>
<div class="form-group row">
        <label class="control-label col-lg-2" for="inp_somma_assegnata">Somma assegnata</label>     
    <div class="col-lg-3">    
        <input type="text" id="inp_somma_assegnata" class="form-control numeric_4" value="" /> 
    </div>
        <label class="control-label col-lg-2" for="inp_somma_erogata">Somma erogata</label>     
    <div class="col-lg-3">    
        <input type="text" id="inp_somma_erogata" class="form-control numeric_4" value="" /> 
    </div>              
</div>
<div class="form-group row">
    <label class="control-label col-lg-1" for="ta_note_finanziamento">Note</label>     
    <div class="col-lg-10">  
    <textarea type="text" id="ta_note_finanziamento" rows="2" class="form-control" value="" ></textarea> 
    </div>
</div>   
</div> 
</div> 
<div class="modal-footer justify-content-between">
       <button id='but_salva_nuovo_finanziamento' class='btn btn-success' > Salva</button>        
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
</div>        
</div>
</div>    
</div>
