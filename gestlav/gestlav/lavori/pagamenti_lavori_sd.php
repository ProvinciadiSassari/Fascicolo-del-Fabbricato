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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" charset="utf-8">
     
$(document).ready(function() {    
    
     var id_lavoro=<?=$id_lavoro;?>;
    $(".numeric_4").inputmask('decimal', { digits: 4, enforceDigitsOnBlur: true, integerDigits: 12,positionCaretOnTab: false }); 
    $(".numeric_0").inputmask('decimal', { digits: 0, enforceDigitsOnBlur: true, integerDigits: 2,positionCaretOnTab: false });    
    
    var id_livello=<?=$_SESSION['idlevel'];?>;

      if (id_livello==7) {
          $("#but_nuovo_pagamento_sd").hide();
          $("#but_salva_nuovo_pagamento_sd").hide();
          
      }
      
    $("#div_pagamenti_sd_tab").load("tables/table_pagamenti_sd.php?id_lavoro="+id_lavoro,function(){
                  
                });
    
    $("#but_nuovo_pagamento_sd").click(function(e){  
         $("#inp_d_certificato2_sd").val("");
        $("#inp_id_proposta_pagamento2_sd").val("");
        $("#inp_importo_certificato2_sd").val(0);       
        $("#inp_aliquota_iva2_sd").val(0);               
        $("#ta_descrizione2_sd").val("");
        $("#ta_note2_sd").val("");
        $("#id_descrizione_quadro2_sd").val(0);
        $("#hid_tipo_operazione_pag_sd").val("ins");
        $("#hid_id_pagamento_sd").val(0);
        $("#inp_somma2_sd").val($("#hid_somma").val());
        $("#h_title_pag_sd").text("Nuovo Pagamento");
         $('#dialog_pagamento_sd').modal('show');
     });  
   
        
     $("#but_salva_nuovo_pagamento_sd").click(function(e) {     
        
        var inp_data_certificato=$("#inp_d_certificato2_sd").val();
        var inp_id_proposta_pagamento=$("#inp_id_proposta_pagamento2_sd").val();
        var inp_importo_certificato=$("#inp_importo_certificato2_sd").val();       
        var inp_aliquota_iva=$("#inp_aliquota_iva2_sd").val();               
        var ta_descrizione=$("#ta_descrizione2_sd").val();
        var ta_note=$("#ta_note2_sd").val();
        var id_descrizione_quadro = $("#id_descrizione_quadro2_sd").val();
               
        if (inp_importo_certificato=="") inp_importo_certificato=0;
        if (inp_aliquota_iva=="") inp_aliquota_iva=0;               
        
        var tipo_operazione=$("#hid_tipo_operazione_pag_sd").val();
        var id_pagamento=$("#hid_id_pagamento_sd").val();
                
        var ajax_data="";
        
        ajax_data={
            id_lavoro:id_lavoro,
            inp_data_certificato:inp_data_certificato,
            inp_id_proposta_pagamento:inp_id_proposta_pagamento,
            inp_importo_certificato:inp_importo_certificato,            
            inp_aliquota_iva:inp_aliquota_iva,                     
            ta_descrizione:ta_descrizione,
            id_descrizione_quadro:id_descrizione_quadro,
            ta_note:ta_note,
            tipo_operazione:tipo_operazione,
            id_pagamento:id_pagamento
        };
        
        $.ajax({
            type: "post",
            url: "actions/submit_salva_dati_pagamento_lavori_sd.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);  
                $("#dialog_pagamento_sd").modal("hide");     
                $("#div_pagamenti_sd_tab").load("tables/table_pagamenti_sd.php?id_lavoro="+id_lavoro,function(){
                  
                });
                swal(
                    'Informazione',
                    'Operazione avventuta con successo.',
                    'success'
                  );                                  
                    $('.modal-backdrop').remove();                  
            },
            error: function() {
                alert ("error");
            }
        });
        
     }); 
     
     $(document).on("click","#img_mod_pagamento_sd",function(){
        
        var id_pagamento=$(this).attr("alt");
          
        $.ajax({
            type: "post",
            dataType: "json",
            url: "query/retrieve_dati_pagamento.php",                                                 
            data: "id_pagamento="+id_pagamento,
            success: function(msg) {   
//                alert(msg);
                $("#hid_id_pagamento_sd").val(id_pagamento);
                
               $("#inp_d_certificato2_sd").val(msg[3]);               
                $("#inp_id_proposta_pagamento2_sd").val(msg[11]);
                $("#inp_importo_certificato2_sd").val(msg[4]);
                $("#id_descrizione_quadro2_sd").val(msg[5]);               
                $("#inp_aliquota_iva2_sd").val(msg[6]);
                $("#ta_descrizione2_sd").val(msg[9]);
                $("#ta_note2_sd").val(msg[10]);               
                $("#inp_aliquota_iva2_sd").trigger("focusout");
                $("#inp_somma2_sd").val($("#hid_somma").val());
               $("#hid_tipo_operazione_pag_sd").val("mod");
              $("#h_title_pag_sd").text("Dettaglio Pagamento");
               $('#dialog_pagamento_sd').modal('show');
            },
            error: function() {
//                        alert ("error");
        }
    });

      }); 
     
     $(document).on("focusout","#inp_aliquota_iva2_sd",function(){
         if ($(this).val()!=null && $(this).val()!=undefined && $(this).val()!="") {
         var inp_aliquota_iva2 =  parseFloat($(this).val()).toFixed(2);
         var imp = parseFloat($("#inp_importo_certificato2_sd").val()).toFixed(2);
         var inp_iva2 =parseFloat(imp*(inp_aliquota_iva2/100)).toFixed(2);
                    
         $("#inp_iva2_sd").val(inp_iva2);
         
         var tot = parseFloat(imp)+parseFloat(inp_iva2);
         
         $("#inp_totale2_sd").val(tot);
        }
                  
     }); 
        
        
});
</script>
<button id="but_nuovo_pagamento_sd" class="btn btn-warning btn-sm" type="button">Nuovo Pagamento</button>
<br /><br />
<div id="div_pagamenti_sd_tab">
    
</div>
<div id="dialog_pagamento_sd" class="modal fade"  role="dialog">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
<div class="modal-header">        
        <h5 id="h_title_pag_sd"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
</div>   
<div class="modal-body">
     <input type="hidden" id="hid_tipo_operazione_pag_sd" value="" />   
    <input type="hidden" id="hid_id_pagamento_sd" value="0" />  
<div class="container-fluid">            
<div class="form-group row">
        <label class="control-label col-lg-1" for="inp_data_certificato2_sd">Data</label>     
        <div class="col-lg-3">    
         <div class="input-group date" id="inp_data_certificato2_sd" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" data-target="#inp_data_certificato2_sd" id="inp_d_certificato2_sd" value=""/>
                    <div class="input-group-append" data-target="#inp_data_certificato2_sd" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
                <script type="text/javascript">
                $(function () {
                    $('#inp_data_certificato2_sd').datetimepicker({
                        locale: 'it',
                        format: 'L'
                    });
                });
            </script>                  
        </div>               
        <label class="control-label col-lg-3" for="inp_id_proposta_pagamento2_sd">ID proposta pagamento</label>     
     <div class="col-lg-3"> 
        <input type="text" id="inp_id_proposta_pagamento2_sd" class="form-control" value="" /> 
    </div>
</div>
<div class="form-group row">
        <label class="control-label col-lg-1" for="inp_importo_certificato2_sd">Importo</label>     
    <div class="col-lg-3">     
        <input type="text" id="inp_importo_certificato2_sd" class="form-control numeric_4" value="" /> 
    </div>
    <div class="col-lg-7">    
        <select id="id_descrizione_quadro2_sd" class="form-control">
            <option value="0">- Seleziona -</option>
            <?php
                $query="SELECT  id_descrizione_quadro,desc_descrizione_quadro FROM descrizioni_quadro WHERE id_gruppo_quadro=1";
                $result = mysql_query($query);
                if (!$result){
                        die ("Could not query the database: <br />". mysql_error());
                }        
                while ($row = mysql_fetch_assoc($result)){
                    $id=$row["id_descrizione_quadro"];
                    $desc=$row["desc_descrizione_quadro"];
                    
                    echo "<option value='$id'>$desc</option>";
                }
            ?>
        </select> 
    </div>
</div>
<div class="form-group row">
        <label class="control-label col-lg-1_sd" for="inp_aliquota_iva2_sd">IVA(%)</label>     
    <div class="col-lg-2">    
        <input type="text" id="inp_aliquota_iva2_sd" class="form-control numeric_0" value="" /> 
    </div>                
        <label class="control-label col-lg-2" for="inp_iva2_sd">Importo IVA</label>     
    <div class="col-lg-3">
        <input type="text" id="inp_iva2_sd" class="form-control" value="" readonly/> 
    </div>
   
</div>
<div class="form-group row">
        <label class="control-label col-lg-1" for="inp_totale2_sd">Totale</label>     
    <div class="col-lg-4">
        <input type="text" id="inp_totale2_sd" class="form-control" value="" readonly/> 
    </div>
        <label class="control-label col-lg-1" for="inp_somma2_sd">Somma</label>     
    <div class="col-lg-4">   
        <input type="text" id="inp_somma2_sd" class="form-control" value="" readonly /> 
    </div>
</div>
<div class="form-group row">
    <label class="control-label col-lg-1" for="ta_descrizione_sd">Descrizione</label>     
    <div class="col-lg-10">
    <textarea type="text" id="ta_descrizione2_sd" rows="2" class="form-control" value="" ></textarea> 
    </div>
</div>
<div class="form-group row">
    <label class="control-label col-lg-1" for="ta_note2_sd">Note</label>     
    <div class="col-lg-10">
    <textarea type="text" id="ta_note2_sd" rows="2" class="form-control" value="" ></textarea> 
    </div>
</div>
</div> 
</div> 
<div class="modal-footer justify-content-between">
       <button id='but_salva_nuovo_pagamento_sd' class='btn btn-success' > Salva</button>        
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
</div> 
</div>
 </div>
</div> 