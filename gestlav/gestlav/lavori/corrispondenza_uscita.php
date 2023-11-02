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
<style>
    .ui-datepicker-trigger {
        display: none;
    }
</style>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {                

     var id_lavoro=<?=$id_lavoro;?>;
     var fl_avanzato=<?=$fl_avanzato;?>;
     $(".numeric_0").inputmask('decimal', { digits: 0, enforceDigitsOnBlur: true, integerDigits: 10,positionCaretOnTab: false });
     
     var id_livello=<?=$_SESSION['idlevel'];?>;

      if (id_livello==7) {
          $("#but_nuova_corrispondenza_out").hide();
          $("#but_salva_nuova_corrispondenza_out").hide();
      }
      
     $("#div_table_corrispondenza_uscita").load("tables/table_corrispondenza_uscita.php?id_lavoro="+id_lavoro); 
      
      if (fl_avanzato==0)
         $("#but_nuova_corrispondenza_out").hide();        
    
    $("#but_nuova_corrispondenza_out").click(function(e){
         e.preventDefault();
          $("#hid_tipo_operazione_out").val("ins");
         $("#hid_id_corrispondenza_out").val(0);
          $("#ta_mittente_out").val("");
        $("#ta_destinatario_out").val("");
        $("#ta_oggetto_out").val("");
        $("#ta_note_out").val(""); 
        $("#inp_data_uff_prot_out").val("");
        $("#inp_num_uff_prot_out").val("");
        $("#inp_data_docum_out").val(""); 
        $("#inp_num_docum_out").val("");        
         $("#h_title_corr_out").html("Nuova Corrispondenza in Uscita");
         $('#dialog_nuova_corrispondenza_out').modal('show');
      });
      
      $(document).on("click","#img_mod_corrispondenza_us",function(e){
          e.stopImmediatePropagation();          
         
          var id_corrispondenza=$(this).attr("alt");
          
          $.ajax({
            type: "post",
            dataType: "json",
            url: "query/retrieve_dati_corrispondenza.php",                                                 
            data: "id_corrispondenza="+id_corrispondenza,
            success: function(msg) {   
//                alert(msg);
                $("#hid_id_corrispondenza_out").val(id_corrispondenza);
               $("#ta_mittente_out").val(msg[8]);
                $("#ta_destinatario_out").val(msg[10]);
                $("#ta_oggetto_out").val(msg[6]);
                $("#ta_note_out").val(msg[7]); 
                $("#inp_data_uff_prot_out").val(msg[2]);
                $("#inp_num_uff_prot_out").val(msg[3]);
                $("#inp_data_docum_out").val(msg[4]); 
                $("#inp_num_docum_out").val(msg[5]);        
                $("#hid_tipo_operazione_out").val("mod");
                $("#h_title_corr_out").html("Dettaglio Corrispondenza in Uscita");
               $('#dialog_nuova_corrispondenza_out').modal('show');
            },
            error: function() {
//                        alert ("error");
            }
        });
      });
      
      $("#but_salva_nuova_corrispondenza_out").click(function(e) {
        e.preventDefault();
        var id_corrispondenza=$("#hid_id_corrispondenza_out").val();
        var ta_mittente=$("#ta_mittente_out").val();
        var ta_destinatario=$("#ta_destinatario_out").val();
        var ta_oggetto=$("#ta_oggetto_out").val();
        var ta_note=$("#ta_note_out").val(); 
        var inp_data_uff_prot=$("#inp_data_uff_prot_out").val();
        var inp_num_uff_prot=$("#inp_num_uff_prot_out").val();
        var inp_data_docum=$("#inp_data_docum_out").val(); 
        var inp_num_docum=$("#inp_num_docum_out").val();        
        var tipo_operazione=$("#hid_tipo_operazione_out").val();
        if (inp_num_docum=="") inp_num_docum=0;
        if (inp_num_uff_prot=="") inp_num_uff_prot=0;
        
        var tipo_corrispondenza=2;
        
        var ajax_data="";
        
        ajax_data={ 
            id_corrispondenza:id_corrispondenza,
            tipo_operazione:tipo_operazione,
            tipo_corrispondenza:tipo_corrispondenza,
            id_lavoro:id_lavoro,
            ta_mittente:ta_mittente,
            ta_destinatario:ta_destinatario,
            ta_oggetto:ta_oggetto,
            ta_note:ta_note,
            inp_data_uff_prot:inp_data_uff_prot,
            inp_num_uff_prot:inp_num_uff_prot,
            inp_data_docum:inp_data_docum,
            inp_num_docum:inp_num_docum                       
        };
        
        $.ajax({
            type: "post",
            url: "actions/submit_salva_nuova_corrispondenza.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);    
                   $('#dialog_nuova_corrispondenza_out').modal('hide');
                  $("#div_table_corrispondenza_uscita").load("tables/table_corrispondenza_uscita.php?id_lavoro="+id_lavoro);   
                   swal(
                    'Informazione',
                    'Operazione avventuta con successo.',
                    'success'
                  );   
            },
            error: function() {
                alert ("error");
            }
        });
        
     });

});
</script>
<button id="but_nuova_corrispondenza_out" class="btn btn-warning btn-sm" type="button">Nuova Corrispondenza in Uscita</button>
<br /><br />
<div class="container-fluid" id="div_table_corrispondenza_uscita">    

</div>

    <div id="dialog_nuova_corrispondenza_out" class="modal fade"  role="dialog">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
<div class="modal-content">
<div class="modal-header">        
        <h5 id="h_title_corr_out"></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
</div>   
<div class="modal-body">
<input type="hidden" id="hid_tipo_operazione_out" value=""/>    
<input type="hidden" id="hid_id_corrispondenza_out" value="0"/>    
<table>             
<tr>
    <td>
    <label class="control-label" for="ta_mittente_out">Mittente</label>     
    </td><td colspan="3"> 
    <textarea type="text" id="ta_mittente_out" rows="2" class="form-control" value="" ></textarea> 
    </td>
</tr>
<tr>
    <td>
    <label class="control-label" for="ta_destinatario_out">Destinatario</label>     
    </td><td colspan="3"> 
    <textarea type="text" id="ta_destinatario_out" rows="2" class="form-control" value="" ></textarea> 
    </td>
</tr>
<tr>
    <td>
    <label class="control-label" for="ta_oggetto_out">Oggetto/Descrizione</label>     
    </td><td colspan="3"> 
    <textarea type="text" id="ta_oggetto_out" rows="3" class="form-control" value="" ></textarea> 
    </td>
</tr>
<tr>
    <td>
    <label class="control-label" for="ta_note_out">Note</label>     
    </td><td colspan="3"> 
    <textarea type="text" id="ta_note_out" rows="3" class="form-control" value="" ></textarea> 
    </td>
</tr>
<tr>
    <td>
        <label class="control-label" for="inp_data_uff_prot_out">Data Uff. Prot.</label>     
    </td><td>           
        <div class="input-group date" id="inp_d_uff_prot_out" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#inp_d_uff_prot_out" id="inp_data_uff_prot_out" value=""/>
                <div class="input-group-append" data-target="#inp_d_uff_prot_out" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#inp_d_uff_prot_out').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
    </td>
    <td>
        <label class="control-label" for="inp_num_uff_prot_out">Num. Uff. Prot.</label>     
    </td><td>    
        <input type="text" id="inp_num_uff_prot_out" class="form-control numeric_0" value="" /> 
    </td>
</tr>
<tr>
    <td>
        <label class="control-label" for="inp_data_docum_out">Data Docum.</label>     
    </td><td>          
         <div class="input-group date" id="inp_d_docum_out" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#inp_d_docum_out" id="inp_data_docum_out" value=""/>
                <div class="input-group-append" data-target="#inp_d_docum_out" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#inp_d_docum_out').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
    </td>
    <td>
        <label class="control-label" for="inp_num_docum_out">Num. Docum.</label>     
    </td><td>    
        <input type="text" id="inp_num_docum_out" class="form-control numeric_0" value="" /> 
    </td>
</tr>
</table> 
</div>
<div class="modal-footer justify-content-between">
       <button id='but_salva_nuova_corrispondenza_out' class='btn btn-success' > Salva</button>        
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
</div>    
</div>
</div>   
</div>
