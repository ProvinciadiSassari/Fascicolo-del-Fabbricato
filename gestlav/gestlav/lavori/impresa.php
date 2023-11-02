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
    .control-label {
        margin-left:10px;
        font-size:9pt;
    }
</style>
<script type="text/javascript">
$(document).ready( function(){
          
      var id_lavoro=<?=$id_lavoro;?>;
      var fl_avanzato=<?=$fl_avanzato;?>;
      var id_livello=<?=$_SESSION['idlevel'];?>;

     
      if (id_livello==7) {
          $("#but_salva_dati_impresa").hide();
          
      }
      
      $('.selectpicker').selectpicker();
     
     $.ajax({
            type: "post",
            dataType: "json",
            url: "query/retrieve_dati_impresa_incaricata.php",                                                 
            data: "id_lavoro="+id_lavoro,
            success: function(msg) {   
//                alert(msg);
               $("#sel_imprese").val(msg[1]); 
               $("#inp_titolare").val(msg[16]);
               $("#sel_comune").val(msg[17]);
               $("#inp_indirizzo").val(msg[18]);
               $("#inp_tel1").val(msg[19]);
               $("#inp_fax").val(msg[21]);
               $("#inp_gp_det").val(msg[2]);
               $("#inp_d_gp_det").val(msg[3]);
                             
               $("#inp_rappresentante_legale").val(msg[4]);
               $("#inp_direttore").val(msg[5]);               
               $("#inp_responsabile_sicurezza").val(msg[6]);
               $("#inp_rep").val(msg[7]);
               $("#inp_num_contratto").val(msg[8]);
               $("#inp_d_contratto_imp").val(msg[9]);
                $("#inp_base_asta").val(msg[10]);
               $("#inp_ribasso").val(msg[11]);               
               $("#inp_importo_netto").val(msg[13]);
               $("#inp_oneri_sicurezza").val(msg[12]);
               $("#ta_note_impresa").val(msg[14]);              
               $('.selectpicker').selectpicker('refresh');
            },
            error: function() {
//                        alert ("error");
            }
        });
      
       $(".numeric_4").inputmask('decimal', { digits: 4, enforceDigitsOnBlur: true, integerDigits: 12,positionCaretOnTab: false });
      
      if (fl_avanzato==0)
         $("#but_salva_dati_impresa").hide();
     
     
     
      $("#but_salva_dati_impresa").click(function(e) {           
        
        var inp_impresa=$("#sel_imprese").val();
       
        var inp_gp_det=$("#inp_gp_det").val();
        var inp_data_gp_det=$("#inp_d_gp_det").val();
        var inp_rappresentante_legale=$("#inp_rappresentante_legale").val();
        var inp_direttore=$("#inp_direttore").val();
        var inp_responsabile_sicurezza=$("#inp_responsabile_sicurezza").val();
        
        var inp_rep=$("#inp_rep").val();
        var inp_num_contratto=$("#inp_num_contratto").val();
        var inp_data_contratto=$("#inp_d_contratto_imp").val();
        var inp_base_asta=$("#inp_base_asta").val();
        if (inp_base_asta=="") inp_base_asta=0;        
        
        var inp_ribasso=$("#inp_ribasso").val();
        if (inp_ribasso=="") inp_ribasso=0;
        var inp_importo_netto=$("#inp_importo_netto").val();
        if (inp_importo_netto=="") inp_importo_netto=0;
        var inp_oneri_sicurezza=$("#inp_oneri_sicurezza").val();
        if (inp_oneri_sicurezza=="") inp_oneri_sicurezza=0;
        var ta_note_impresa=$("#ta_note_impresa").val();
                     
        var ajax_data="";
        
        ajax_data={
            inp_impresa:inp_impresa,
            id_lavoro:id_lavoro,
            inp_gp_det:inp_gp_det,
            inp_data_gp_det:inp_data_gp_det,
            inp_rappresentante_legale:inp_rappresentante_legale,
            inp_direttore:inp_direttore,
            inp_responsabile_sicurezza:inp_responsabile_sicurezza,                            
            inp_rep:inp_rep,
            inp_num_contratto:inp_num_contratto,
            inp_data_contratto:inp_data_contratto,
            inp_base_asta:inp_base_asta,                            
            inp_ribasso:inp_ribasso,
            inp_importo_netto:inp_importo_netto,
            inp_oneri_sicurezza:inp_oneri_sicurezza,
            ta_note_impresa:ta_note_impresa
        };
        
        $.ajax({
            type: "post",            
            url: "actions/submit_salva_dati_impresa.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);               
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
     
     $("#sel_imprese").change(function(e){
    
        var id_impresa=$(this).val();
        
        $.ajax({
            type: "post",
            dataType: "json",
            url: "query/retrieve_dati_impresa.php",                                                 
            data: "id_impresa="+id_impresa,
            success: function(msg) { 
//                alert(msg);                   
                  $("#inp_titolare").val(msg[2]);   
                  $("#sel_comune").val(msg[3]);
                  $("#inp_indirizzo").val(msg[4]);
                  $("#inp_tel1").val(msg[5]);
                  $("#inp_fax").val(msg[7]);
            },
            error: function() {
//                alert ("error");
            }
        });
     }); 

});

</script>
<div class="container-fluid">
<div class="form-group row">
    <h5>Dati impresa</h5>
</div>    
 <div class="form-group row">
        <label class="control-label col-lg-1" for="sel_imprese">Impresa</label>     
          <div class="col-lg-8">                     
        <select id="sel_imprese" class="form-control selectpicker" data-container="body" data-live-search="true" name="sel_imprese">
            <option value="0">- Seleziona-</option>
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
    <label class="control-label col-lg-1" for="inp_titolare">Titolare</label>     
    <div class="col-lg-6">  
      <input type="text" id="inp_titolare" class="form-control" value="" readonly/> 
   </div>              
</div>
<div class="form-group row">
    <label class="control-label col-lg-1" for="sel_comune">Comune</label>     
    <div class="col-lg-4">     
    <select id="sel_comune" disabled class="form-control">
        <option value="0">- Seleziona -</option>
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
    <label class="control-label col-lg-1" for="inp_indirizzo">Indirizzo</label>     
    <div class="col-lg-5">    
    <input type="text" id="inp_indirizzo" class="form-control" value="" readonly/> 
    </div>              
</div>
<div class="form-group row">
    <label class="control-label col-lg-1" for="inp_tel1">Tel.</label>  
  <div class="col-lg-4">     
      <input type="text" id="inp_tel1" class="form-control" value="" readonly/>
  </div>    
       <label class="control-label col-lg-1" for="inp_fax">Fax</label> 
  <div class="col-lg-4">      
      <input type="text" id="inp_fax" class="form-control" value="" readonly/>
  </div>              
</div>
<div class="form-group row">
    <h5>Aggiudicazione</h5>
</div>    
<div class="form-group row">
        <label class="control-label col-lg-1" for="inp_gp_det">G.P. / Det.</label>  
      <div class="col-lg-4">    
          <input type="text" id="inp_gp_det" class="form-control" value="" />
      </div>
           <label class="control-label col-lg-1" for="inp_data_gp_det">del</label> 
      <div class="col-lg-3">    
            <div class="input-group date" id="inp_data_gp_det" data-target-input="nearest">
                     <input type="text" class="form-control datetimepicker-input" data-target="#inp_data_gp_det" id="inp_d_gp_det" value=""/>
                     <div class="input-group-append" data-target="#inp_data_gp_det" data-toggle="datetimepicker">
                         <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                     </div>
                 </div>
                 <script type="text/javascript">
                 $(function () {
                     $('#inp_data_gp_det').datetimepicker({
                         locale: 'it',
                         format: 'L'
                     });
                 });
             </script>                  
         </div>            
</div>
<hr />
 <div class="form-group row">
    <label class="control-label col-lg-2" for="inp_rappresentante_legale">Rappresentante Legale</label>     
     <div class="col-lg-4"> 
     <input type="text" id="inp_rappresentante_legale" class="form-control" value="" />
    </div>
</div>
  <div class="form-group row">
    <h5>Cantiere</h5>
</div>    
<div class="form-group row">
<label class="control-label col-lg-1" for="inp_direttore">Direttore</label>     
<div class="col-lg-4"> 
    <input type="text" id="inp_direttore" class="form-control" value="" /> 
</div>
    <label class="control-label col-lg-2" for="inp_responsabile_sicurezza">Responsabile Sicurezza</label>     
<div class="col-lg-4">    
    <input type="text" id="inp_responsabile_sicurezza" class="form-control" value="" /> 
</div>              
</div>
<div class="form-group row">
    <h5>Contratto</h5>
</div>    
 <div class="form-group row">
        <label class="control-label col-lg-1" for="inp_rep">Rep.</label>  
    <div class="col-lg-2">       
        <input type="text" id="inp_rep" class="form-control" value="" />
   </div>     
         <label class="control-label col-lg-1" for="inp_num_contratto">N.</label> 
    <div class="col-lg-3">         
        <input type="text" id="inp_num_contratto" class="form-control" value="" />
   </div>  
         <label class="control-label col-lg-1" for="inp_data_contratto">Data</label> 
     <div class="col-lg-3">    
            <div class="input-group date" id="inp_data_contratto_imp" data-target-input="nearest">
                     <input type="text" class="form-control datetimepicker-input" data-target="#inp_data_contratto_imp" id="inp_d_contratto_imp" value=""/>
                     <div class="input-group-append" data-target="#inp_data_contratto_imp" data-toggle="datetimepicker">
                         <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                     </div>
                 </div>
                 <script type="text/javascript">
                 $(function () {
                     $('#inp_data_contratto_imp').datetimepicker({
                         locale: 'it',
                         format: 'L'
                     });
                 });
             </script>                  
         </div>                 
</div>  
<div class="form-group row">
    <h5>Importi</h5>
</div>
<div class="form-group row">
                  <label class="control-label col-lg-2" for="inp_base_asta" style="max-width: 120px;">Base d'asta</label>  
              <div class="col-lg-2">    
                  <input type="text" id="inp_base_asta" class="form-control numeric_4" value="" />
              </div>
                   <label class="control-label col-lg-2" for="inp_ribasso" style="max-width: 110px;">Ribasso (%)</label> 
             <div class="col-lg-2">       
                  <input type="text" id="inp_ribasso" class="form-control numeric_4" value="" />
             </div>   
                   <label class="control-label col-lg-2" for="inp_importo_netto" style="max-width: 120px;">Importo netto</label> 
              <div class="col-lg-2">      
                  <input type="text" id="inp_importo_netto" class="form-control numeric_4" value="" />
             </div>
</div>                   
<div class="form-group row">
                   <label class="control-label col-lg-2" for="inp_oneri_sicurezza" style="max-width: 120px;">Oneri Sicurezza</label> 
              <div class="col-lg-2">       
                  <input type="text" id="inp_oneri_sicurezza" class="form-control numeric_4" value="" />
              </div>            
</div>
<div class="form-group row">
            <label class="control-label col-lg-1" for="ta_note_impresa">Note</label>     
           <div class="col-lg-10">  
            <textarea type="text" id="ta_note_impresa" rows="3" class="form-control" value="" ></textarea> 
            </div>
</div>
</div>
<hr />
<button id="but_salva_dati_impresa" class="btn btn-primary" type="button">Salva</button> 
