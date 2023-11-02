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

$id_fabbricato=$utility->getIDFabbricatoFromIDLavoro($id_lavoro);

$fl_avanzato=$_SESSION['avanzato'];

?>

<script type="text/javascript">
$(document).ready( function(){
    
      var id_fabbricato=<?=$id_fabbricato;?>;
      var id_lavoro=<?=$id_lavoro;?>;    
      var fl_avanzato=<?=$fl_avanzato;?>;   
      
      var id_livello=<?=$_SESSION['idlevel'];?>;
      
      if (id_livello==7) {
          $("#but_salva_dati_lavoro").hide();
      }
      
      $.ajax({
            type: "post",
            dataType: "json",
            url: "query/retrieve_dati_lavoro.php",                                                 
            data: "id_lavoro="+id_lavoro,
            success: function(msg) {   
//                alert(msg);
               $("#inp_codice_contratto").val(msg[2]); 
               $("#inp_d_contratto").val(msg[3]);
               $("#inp_id_lavoro").val(msg[0]);
               if (msg[11]==1) {
                   $("#chk_complementare").prop("checked",true);
                    $("#sel_lavoro_origine").prop("disabled",false);
                    $("#inp_disponibilita").prop("readonly",false);
                }
                else {   
                    $("#chk_complementare").prop("checked",false);
                    $("#sel_lavoro_origine").prop("disabled",true);
                    $("#inp_disponibilita").prop("readonly",true);            
                }                 
               $("#sel_lavoro_origine").val(msg[12]);
               $("#inp_disponibilita").val(msg[13]);
               if (msg[13]==0) {
                   $("#inp_disponibilita").val("");
               }
               $("#sel_categoria").val(msg[4]);
               $("#td_tipologie").load("views/view_select_tipologie.php?id_categoria="+msg[4],function(){
                    $("#sel_tipologia").val(msg[5]);
               });
               $("#ta_descrizione").val(msg[1]);
               $("#ta_note_lavoro").val(msg[10]);
               $("#sel_responsabile").val(msg[8]);
               $("#sel_istruttore").val(msg[9]);
               if (msg[6]==0) {
                    $("#div_stato_lavoro").text("Aperto");
                    $("#div_stato_lavoro").css({"text-align":"left","color":"green","font-weight":"bold"});
                    $("#chk_lavoro_chiuso").prop("checked",false);
                }
                else {
                    $("#div_stato_lavoro").text("Chiuso");
                    $("#div_stato_lavoro").css({"text-align":"left","color":"red","font-weight":"bold"});   
                    $("#chk_lavoro_chiuso").prop("checked",true);
                }
            },
            error: function() {
//                        alert ("error");
            }
        });
      
      $(".numeric_2").inputmask('decimal', { digits: 2, enforceDigitsOnBlur: true, integerDigits: 9,positionCaretOnTab: false }); 
      
      if (fl_avanzato==0) {
         $("#but_salva_dati_lavoro").hide();
      }    

      $("#but_salva_dati_lavoro").click(function(e) {
      
        var inp_codice_contratto=$("#inp_codice_contratto").val();
        var inp_data_contratto=$("#inp_d_contratto").val();
        var sel_categoria=$("#sel_categoria").val();
        var sel_tipologia=$("#sel_tipologia").val();
        if (sel_tipologia==null || sel_tipologia==undefined || sel_tipologia=="") {
            sel_tipologia=0;
        }
        var ta_descrizione=$("#ta_descrizione").val();
        var ta_note_lavoro=$("#ta_note_lavoro").val();
        var sel_responsabile=$("#sel_responsabile").val();
        var sel_istruttore=$("#sel_istruttore").val();
        var chk_lavoro_chiuso = 0;
        if ( $("#chk_lavoro_chiuso").is(':checked'))
            chk_lavoro_chiuso=1;
                
        var chk_complementare =0;
        var sel_lavoro_origine=0;
        var inp_disponibilita=0;
        if ($("#chk_complementare").prop('checked')) {
            chk_complementare=1;
            sel_lavoro_origine=$("#sel_lavoro_origine").val();
            inp_disponibilita=$("#inp_disponibilita").val();
        } 
        
        if (inp_disponibilita=="") inp_disponibilita=0;
        var ajax_data=""; 
      
        
        ajax_data={
            id_fabbricato:id_fabbricato,
            id_lavoro:id_lavoro,
            inp_codice_contratto:inp_codice_contratto,
            inp_data_contratto:inp_data_contratto,
            sel_categoria:sel_categoria,
            sel_tipologia:sel_tipologia,
            ta_descrizione:ta_descrizione,                            
            ta_note_lavoro:ta_note_lavoro,
            sel_responsabile:sel_responsabile,
            sel_istruttore:sel_istruttore,
            chk_lavoro_chiuso:chk_lavoro_chiuso,   
            chk_complementare:chk_complementare,
            sel_lavoro_origine:sel_lavoro_origine,
            inp_disponibilita:inp_disponibilita
        };
        
        $.ajax({
            type: "post",
            url: "actions/submit_salva_dati_lavoro.php",                                                 
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
//                alert ("error");
            }
        });
        
     });     
      
     $("#sel_categoria").change(function(){
        var id_categoria=$(this).val();
       $("#td_tipologie").load("views/view_select_tipologie.php?id_categoria="+id_categoria);
    });
        
    $("#chk_complementare").click(function(){
        if ($(this).is(':checked')) {
            $("#sel_lavoro_origine").prop("disabled",false);
            $("#inp_disponibilita").prop("readonly",false);
        }
        else {            
            $("#sel_lavoro_origine").prop("disabled",true);
            $("#inp_disponibilita").prop("readonly",true);            
        }
    });    
          
                
});
</script>
<div class="container-fluid">
  <div class="form-group row">       
        <label class="control-label col-lg-1" for="inp_codice_contratto">Codice Contratto</label>     
        <div class="col-lg-2">    
         <input type="text" id="inp_codice_contratto" class="form-control" value="" /> 
        </div>
         <label class="control-label col-lg-1" for="inp_data_contratto">Data Contratto</label>     
        <div class="col-lg-3">    
         <div class="input-group date" id="inp_data_contratto" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" data-target="#inp_data_contratto" id="inp_d_contratto" value=""/>
                    <div class="input-group-append" data-target="#inp_data_contratto" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
                <script type="text/javascript">
                $(function () {
                    $('#inp_data_contratto').datetimepicker({
                        locale: 'it',
                        format: 'L'
                    });
                });
            </script>  
            <style>
    .ui-datepicker {
        display: none;
    }
</style>
        </div> 
          <label class="control-label col-lg-1" for="inp_id_lavoro">ID Lavoro</label>    
        <div class="col-lg-2">    
         <input type="text" id="inp_id_lavoro" class="form-control" value="" readonly />
        </div>        
  </div> 
<div class="form-group row"> 
    <label class="control-label col-lg-1" for="chk_complementare">Complementare</label></td>
     <div class="col-lg-1">          
    <div class="custom-control custom-checkbox " style="margin-left:20px;margin-bottom:7px;">           
        <input type="checkbox" class="custom-control-input" id="chk_complementare" style=""/>
         <label class="custom-control-label" for="chk_complementare" style="color: green;"></label>  
     </div>
  </div>
    <label class="control-label col-lg-2" for="sel_lavoro_origine" style="max-width: 130px;">Lavoro iniziale</label>
    <div class="col-lg-4"> 
            <select id="sel_lavoro_origine" class="form-control"  >
                <?php
                  $query="SELECT
                      l.IDLavoro
                      FROM lavori l
                  WHERE l.IDEdificio=$id_fabbricato and l.fl_complementare=0";

                  $result = mysql_query($query);
                  if (!$result){
                          die ("Could not query the database: <br />". mysql_error());
                  }
                  echo "<option value='0'>- Seleziona -</option>";
                  while ($row = mysql_fetch_assoc($result)){
                      $val=$row["IDLavoro"];                           
                      echo "<option value='$val'>$val</option>";
                  }
                ?>
            </select> </div>
</div>
<div class="form-group row"> 
            <label class="control-label col-lg-1" for="inp_disponibilita" style="">Disponibilità</label>
            <div class="col-lg-2">     
            <input type="text" id="inp_disponibilita" class="form-control numeric_2" value=""/> 
            </div>         
            <label class="control-label col-lg-1" for="sel_categoria">Categoria</label>  
            <div class="col-lg-3"> 
                  <select id="sel_categoria" class="form-control">
                      <option value="0">- Seleziona -</option>
                   <?php
                    $query="SELECT IDCategoria,Categoria FROM categorie";
                    $result = mysql_query($query);
                    if (!$result){
                            die ("Could not query the database: <br />". mysql_error());
                    }
                    while ($row = mysql_fetch_assoc($result)){
                        $id=$row["IDCategoria"];
                        $val=$row["Categoria"];
                        echo "<option value='$id'>";
                        echo $val;
                        echo "</option>";                        
                    }                    
                   ?>   
                  </select>
            </div>              
            <label class="control-label col-lg-1" for="sel_tipologia">Tipologia</label>  
            <div class="col-lg-3" id="td_tipologie"> 

            </div>
</div>
<div class="form-group row">
   
    <label class="control-label col-lg-1" for="ta_descrizione">Descrizione</label>     
    <div class="col-lg-10" id=""> 
    <textarea type="text" id="ta_descrizione" rows="3" class="form-control" value="" ></textarea> 
    </div>
</div>
<div class="form-group row">
    <label class="control-label col-lg-1" for="ta_note_lavoro">Note</label>     
    <div class="col-lg-10" id="">  
    <textarea type="text" id="ta_note_lavoro" rows="3" class="form-control" value="" ></textarea> 
    </div>
</div>
<div class="form-group row">
    <h5>Procedimento</h5>
</div> 
    <div class="form-group row">
        <label class="control-label col-lg-1" for="sel_responsabile">Responsabile</label>
        <div class="col-lg-4" id="">
            <select  id="sel_responsabile" class="combobox form-control" name="sel_responsabile">
                        <option value="0">- Seleziona -</option>
                   <?php
                    $query="SELECT IDResponsabile,Responsabile FROM responsabili_procedimento";
                    $result = mysql_query($query);
                    if (!$result){
                            die ("Could not query the database: <br />". mysql_error());
                    }
                    while ($row = mysql_fetch_assoc($result)){
                        $id=$row["IDResponsabile"];
                        $val=$row["Responsabile"];
                        echo "<option value='$id'>";
                        echo $val;
                        echo "</option>";                        
                    }                    
                   ?>   
            </select>
        </div>  
        <label class="control-label col-lg-2" for="sel_istruttore" style="max-width: 160px;">Istruttore Pratica</label>
        <div class="col-lg-4" id="">
        <select id="sel_istruttore" class="form-control" name="sel_istruttore">
                        <option value="0">- Seleziona -</option>
                   <?php
                    $query="SELECT IDIstruttore,Istruttore FROM istruttori_pratica";
                    $result = mysql_query($query);
                    if (!$result){
                            die ("Could not query the database: <br />". mysql_error());
                    }
                    while ($row = mysql_fetch_assoc($result)){
                        $id=$row["IDIstruttore"];
                        $val=$row["Istruttore"];
                        echo "<option value='$id'>";
                        echo $val;
                        echo "</option>";                        
                    }                    
                   ?>   
                  </select>
            </div>
    </div>    
    <div class="form-group row">
        <h5>Stato Lavoro</h5>
        <?="<div class='col-lg-1' id='div_stato_lavoro'></div>"; ?>
    </div> 
    <div class="form-group row">
         <label class="control-label col-lg-2" style="max-width: 120px;margin-top: 7px;">Lavoro chiuso</label>
        <div class="col-lg-1" style="max-width: 20px;">                       
             <input type="checkbox" value="" class="form-control" id="chk_lavoro_chiuso" style=""/>
        </div>            
    </div> 

<hr />
<button id="but_salva_dati_lavoro" class="btn btn-primary" type="button">Salva</button> 
</div>