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
          $("#but_nuova_corrispondenza_in").hide();
          $("#but_salva_nuova_corrispondenza_in").hide();
      }
      
     $("#div_table_corrispondenza_entrata").load("tables/table_corrispondenza_entrata.php?id_lavoro="+id_lavoro); 
     
      if (fl_avanzato==0) {
         $("#but_nuova_corrispondenza_in").hide();
     } 
    
    $("#but_nuova_corrispondenza_in").click(function(e){
        e.preventDefault();
        $("#hid_tipo_operazione").val("ins");
        $("#hid_id_corrispondenza").val(0);
        $("#ta_mittente").val("");
        $("#ta_destinatario").val("");
        $("#ta_oggetto").val("");
        $("#ta_note").val(""); 
        $("#inp_data_uff_prot").val("");
        $("#inp_num_uff_prot").val("");
        $("#inp_data_docum").val(""); 
        $("#inp_num_docum").val("");        
         $("#h_title_corr_in").html("Nuova Corrispondenza in Entrata");
         $('#dialog_nuova_corrispondenza_in').modal('show');
      });
      
      
      $(document).on("click","#img_mod_corrispondenza_in",function(e){
          e.stopImmediatePropagation();          
         
          var id_corrispondenza=$(this).attr("alt");
          
          $.ajax({
            type: "post",
            dataType: "json",
            url: "query/retrieve_dati_corrispondenza.php",                                                 
            data: "id_corrispondenza="+id_corrispondenza,
            success: function(msg) {   
//                alert(msg);
                $("#hid_id_corrispondenza").val(id_corrispondenza);
               $("#ta_mittente").val(msg[8]);
                $("#ta_destinatario").val(msg[10]);
                $("#ta_oggetto").val(msg[6]);
                $("#ta_note").val(msg[7]); 
                $("#inp_data_uff_prot").val(msg[2]);
                $("#inp_num_uff_prot").val(msg[3]);
                $("#inp_data_docum").val(msg[4]); 
                $("#inp_num_docum").val(msg[5]);        
                $("#hid_tipo_operazione").val("mod");
                $("#h_title_corr_in").html("Dettaglio Corrispondenza in Entrata");
               $('#dialog_nuova_corrispondenza_in').modal('show');
            },
            error: function() {
//                        alert ("error");
            }
        });
      });
      
      $("#but_salva_nuova_corrispondenza_in").click(function(e) {
        e.preventDefault();
        var id_corrispondenza=$("#hid_id_corrispondenza").val();
        var ta_mittente=$("#ta_mittente").val();
        var ta_destinatario=$("#ta_destinatario").val();
        var ta_oggetto=$("#ta_oggetto").val();
        var ta_note=$("#ta_note").val(); 
        var inp_data_uff_prot=$("#inp_data_uff_prot").val();
        var inp_num_uff_prot=$("#inp_num_uff_prot").val();
        var inp_data_docum=$("#inp_data_docum").val(); 
        var inp_num_docum=$("#inp_num_docum").val();        
        var tipo_operazione=$("#hid_tipo_operazione").val();
        if (inp_num_docum=="") inp_num_docum=0;
        if (inp_num_uff_prot=="") inp_num_uff_prot=0;
        
        var tipo_corrispondenza=1;
        
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
                  $('#dialog_nuova_corrispondenza_in').modal('hide');
                   $("#div_table_corrispondenza_entrata").load("tables/table_corrispondenza_entrata.php?id_lavoro="+id_lavoro);  
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
<button id="but_nuova_corrispondenza_in" class="btn btn-warning btn-sm" type="button">Nuova Corrispondenza in Entrata</button>
<br /><br />
<div class="container-fluid" id="div_table_corrispondenza_entrata">
    
</div>

<div id="dialog_nuova_corrispondenza_in" class="modal fade"  role="dialog">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
<div class="modal-content">
<div class="modal-header">   
    <h5 id="h_title_corr_in"></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
</div>   
<div class="modal-body">
<input type="hidden" id="hid_tipo_operazione" value=""/>    
<input type="hidden" id="hid_id_corrispondenza" value="0"/>    
<table>             
<tr>
    <td>
    <label class="control-label" for="ta_mittente">Mittente</label>     
    </td><td colspan="3"> 
    <textarea type="text" id="ta_mittente" rows="2" class="form-control" value="" ></textarea> 
    </td>
</tr>
<tr>
    <td>
    <label class="control-label" for="ta_destinatario">Destinatario</label>     
    </td><td colspan="3"> 
    <textarea type="text" id="ta_destinatario" rows="2" class="form-control" value="" ></textarea> 
    </td>
</tr>
<tr>
    <td>
    <label class="control-label" for="ta_oggetto">Oggetto/Descrizione</label>     
    </td><td colspan="3"> 
    <textarea type="text" id="ta_oggetto" rows="3" class="form-control" value="" ></textarea> 
    </td>
</tr>
<tr>
    <td>
    <label class="control-label" for="ta_note">Note</label>     
    </td><td colspan="3"> 
    <textarea type="text" id="ta_note" rows="3" class="form-control" value="" ></textarea> 
    </td>
</tr>
<tr>
    <td>
        <label class="control-label" for="inp_data_uff_prot">Data Uff. Prot.</label>     
    </td><td>           
        <div class="input-group date" id="inp_d_uff_prot" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#inp_d_uff_prot" id="inp_data_uff_prot" value=""/>
                <div class="input-group-append" data-target="#inp_d_uff_prot" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#inp_d_uff_prot').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
    </td>
    <td>
        <label class="control-label" for="inp_num_uff_prot">Num. Uff. Prot.</label>     
    </td><td>    
        <input type="text" id="inp_num_uff_prot" class="form-control numeric_0" value="" /> 
    </td>
</tr>
<tr>
    <td>
        <label class="control-label" for="inp_data_docum">Data Docum.</label>     
    </td><td>          
         <div class="input-group date" id="inp_d_docum" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#inp_d_docum" id="inp_data_docum" value=""/>
                <div class="input-group-append" data-target="#inp_d_docum" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#inp_d_docum').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
    </td>
    <td>
        <label class="control-label" for="inp_num_docum">Num. Docum.</label>     
    </td><td>    
        <input type="text" id="inp_num_docum" class="form-control numeric_0" value="" /> 
    </td>
</tr>
</table> 
</div> 
<div class="modal-footer justify-content-between">
       <button id='but_salva_nuova_corrispondenza_in' class='btn btn-success' > Salva</button>        
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
</div>    
</div>

    

