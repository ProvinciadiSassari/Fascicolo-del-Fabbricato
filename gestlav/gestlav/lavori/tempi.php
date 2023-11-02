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

$query = "SELECT
            ID,           
            Consegna_parziale,
            Consegna_definitiva,
            Tempo_utile,
            IDUnitaTempo,
            Scadenza_tempo_utile,
            Scadenza_definitiva,
            Ultimazione,
            Certificata,
            Stato_finale_entro_gg,
            Stato_finale_emesso,
            Collaudo_entro_gg,
            Certif_collaudo_emesso,
            Riserve
        FROM tempi_esecuzione
        WHERE IDLavoro=$id_lavoro";

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}

if ($row = mysql_fetch_assoc($result)){
    
    $ID_tempo_esecuzione=$row["ID"];
    $Consegna_parziale=$utility->convertDateToHTML($row["Consegna_parziale"]);
    $Consegna_definitiva=$utility->convertDateToHTML($row["Consegna_definitiva"]);
    $Tempo_utile=$row["Tempo_utile"];
    $IDUnitaTempo=$row["IDUnitaTempo"];
    $Scadenza_tempo_utile=$utility->convertDateToHTML($row["Scadenza_tempo_utile"]);  
    $Scadenza_definitiva=$utility->convertDateToHTML($row["Scadenza_definitiva"]);  
    $Ultimazione=$utility->convertDateToHTML($row["Ultimazione"]);
    $Certificata=$utility->convertDateToHTML($row["Certificata"]);
    $Stato_finale_entro_gg=$row["Stato_finale_entro_gg"];
    $Stato_finale_emesso=$utility->convertDateToHTML($row["Stato_finale_emesso"]);
    $Collaudo_entro_gg=$row["Collaudo_entro_gg"];
    $Certif_collaudo_emesso=$utility->convertDateToHTML($row["Certif_collaudo_emesso"]);  
    $Riserve=$row["Riserve"];
}

$fl_avanzato=$_SESSION['avanzato'];

?>

<script type="text/javascript">
$(document).ready( function(){
          
      var id_lavoro=<?=$id_lavoro;?>;
      var id_tempo_esecuzione=<?=$ID_tempo_esecuzione;?>;
      var fl_avanzato=<?=$fl_avanzato;?>;
      var id_livello=<?=$_SESSION['idlevel'];?>;

      if (id_livello==7) {
          $("#but_salva_dati_tempi").hide();
          $("#but_nuova_sospensione").hide();
          $("#but_nuova_proroga").hide();
      }
     
      if (fl_avanzato==0)
         $("#but_salva_dati_tempi").hide();
     
     $("#div_table_sospensioni").load("tables/table_sospensioni_tempi.php?id_lavoro="+id_lavoro); 
     $("#div_table_proroghe").load("tables/table_proroghe_tempi.php?id_lavoro="+id_lavoro); 
     
      $("#but_salva_dati_tempi").click(function(e) {
      
        
        var inp_consegna_parziale_lavori=$("#data_consegna_parziale_lavori").val();       
        var inp_consegna_definitiva_lavori=$("#data_consegna_definitiva_lavori").val();
        var inp_tempo_utile=$("#inp_tempo_utile").val();
        if (inp_tempo_utile=="") inp_tempo_utile=0;
        var sel_unita_tempo=$("#sel_unita_tempo").val();
        var inp_ultimazione_lavori=$("#data_ultimazione_lavori").val();
        var inp_certificata_data=$("#data_certificata_data").val();        
        var inp_stato_finale_gg=$("#inp_stato_finale_gg").val();
        if (inp_stato_finale_gg=="") inp_stato_finale_gg=0;
        var inp_stato_finale_emesso=$("#data_stato_finale_emesso").val();
        var inp_collaudo_entro_gg=$("#inp_collaudo_entro_gg").val();
        if (inp_collaudo_entro_gg=="") inp_collaudo_entro_gg=0;
        var inp_certiticato_collaudo_emesso=$("#data_certiticato_collaudo_emesso").val();        
        var inp_scadenza_tempo_utile=$("#inp_scadenza_tempo_utile").val();   
        var inp_scadenza_definitiva=$("#inp_scadenza_definitiva").val();
        var ta_riserve=$("#ta_riserve").val();       
                     
        var ajax_data="";
        
        ajax_data={
            id_tempo_esecuzione:id_tempo_esecuzione,
            id_lavoro:id_lavoro,
            inp_consegna_parziale_lavori:inp_consegna_parziale_lavori,
            inp_consegna_definitiva_lavori:inp_consegna_definitiva_lavori,
            inp_tempo_utile:inp_tempo_utile,
            sel_unita_tempo:sel_unita_tempo,
            inp_ultimazione_lavori:inp_ultimazione_lavori,                            
            inp_certificata_data:inp_certificata_data,
            inp_stato_finale_gg:inp_stato_finale_gg,
            inp_stato_finale_emesso:inp_stato_finale_emesso,
            inp_collaudo_entro_gg:inp_collaudo_entro_gg,                            
            inp_certiticato_collaudo_emesso:inp_certiticato_collaudo_emesso,
            inp_scadenza_tempo_utile:inp_scadenza_tempo_utile,
            inp_scadenza_definitiva:inp_scadenza_definitiva,
            ta_riserve:ta_riserve
        };
        
        $.ajax({
            type: "post",            
            url: "actions/submit_salva_dati_tempi.php",                                                 
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
     
     $("#inp_tempo_utile").focusout(function(e){
        var parm = parseInt($("#sel_unita_tempo").val()); 
        if (parm==2) parm=30;
        var vals = parseInt($(this).val());
        setScadenzaData(vals,parm);        
     });
     
     $("#sel_unita_tempo").change(function(e){
        var parm = parseInt($(this).val()); 
        if (parm==2) parm=30;
        var vals = parseInt($("#inp_tempo_utile").val());
        setScadenzaData(vals,parm);        
     });         
       
     
     $(document).on("click","#img_mod_sospensioni",function(){
         var id_sospensione=$(this).attr("alt");
         $("#hid_id_sospensione").val(id_sospensione);
         $("#hid_tipo_operazione").val("mod");
         
         $.ajax({
            type: "post",
            dataType: "json",
            url: "query/retrieve_dati_sospensione.php",                                                 
            data: "id_sospensione="+id_sospensione,
            success: function(msg) { 
//                alert(msg);                   
                  $("#data_sospensione").val(msg[2]);                    
                  
                  $("#data_ripresa").val(msg[3]);  
                  $("#totale_giorni").val(msg[5]); 
                  $("#h_sospensione").text("Modifica Sospensione");
                  
                  $('#dialog_sospensione').modal('show');
            },
            error: function() {
//                alert ("error");
            }
        }); 
     });
     
     $(document).on("click","#img_mod_proroga",function(){
         var id_proroga=$(this).attr("alt");
         $("#hid_id_proroga").val(id_proroga);
         $("#hid_tipo_operazione").val("mod");
         
         $.ajax({
            type: "post",
            dataType: "json",
            url: "query/retrieve_dati_proroga.php",                                                 
            data: "id_proroga="+id_proroga,
            success: function(msg) { 
//                alert(msg);                   
                  $("#data_proroga").val(msg[3]);                    
                  $("#periodo_proroga").val(msg[2]);
                  $("#id_unita_tempo").val(msg[4]);
                 
                  $("#h_proroga").text("Modifica Proroga");
                  
                  $('#dialog_proroga').modal('show');
            },
            error: function() {
//                alert ("error");
            }
        }); 
     });
     
     $("#but_salva_sospensione").click(function(){
         var data_sospensione = $("#data_sospensione").val();
         var data_ripresa = $("#data_ripresa").val();
         var totale_giorni = $("#totale_giorni").val();
         var tipo_operazione = $("#hid_tipo_operazione").val();    
         var id_sospensione = $("#hid_id_sospensione").val();
         
          var ajax_data={  
            id_sospensione:id_sospensione,
            id_lavoro:id_lavoro,
            data_sospensione:data_sospensione,
            data_ripresa:data_ripresa,
            totale_giorni:totale_giorni,
            tipo_operazione:tipo_operazione
        };
        
        $.ajax({
            type: "post",            
            url: "actions/submit_salva_sospensione.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);    
                 $('#dialog_sospensione').modal('hide'); 
                 $('.modal-backdrop').remove();  
                  swal(
                    'Informazione',
                    'Operazione avventuta con successo.',
                    'success'
                  );                 
                $("#div_table_sospensioni").load("tables/table_sospensioni_tempi.php?id_lavoro="+id_lavoro); 
//                $("#tabs_dati_lavoro a[href='#div_tempi']").trigger('click');
                                           
            },
            error: function() {
//                alert ("error");
            }
        });
         
     });
     
     $("#but_salva_proroga").click(function(){
         var data_proroga = $("#data_proroga").val();
         var periodo_proroga = $("#periodo_proroga").val();
         var id_unita_tempo = $("#id_unita_tempo").val();
         var tipo_operazione = $("#hid_tipo_operazione").val();    
         var id_proroga = $("#hid_id_proroga").val();
         
          var ajax_data={  
            id_proroga:id_proroga,
            id_lavoro:id_lavoro,
            data_proroga:data_proroga,
            periodo_proroga:periodo_proroga,
            id_unita_tempo:id_unita_tempo,
            tipo_operazione:tipo_operazione
        };
        
        $.ajax({
            type: "post",            
            url: "actions/submit_salva_proroga.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);                      
                $('#dialog_proroga').modal('hide');   
                $("#div_table_proroghe").load("tables/table_proroghe_tempi.php?id_lavoro="+id_lavoro); 
            },
            error: function() {
//                alert ("error");
            }
        });
         
     });
     
     $("#but_nuova_sospensione").click(function(){
         $("#hid_tipo_operazione").val("ins");
         $("#hid_id_sospensione").val(0);
         $("#data_sospensione").val("");
         $("#data_ripresa").val("");
         $("#totale_giorni").val("");
         $("#h_sospensione").text("Nuova Sospensione");
         $('#dialog_sospensione').modal('show');        
     });
     
      $("#but_nuova_proroga").click(function(){
         $("#hid_tipo_operazione").val("ins");
         $("#hid_id_proroga").val(0);
         $("#data_proroga").val("");         
         $("#periodo_proroga").val("");
         $("#id_unita_tempo").val(1);
         $("#h_proroga").text("Nuova Proroga");
         $('#dialog_proroga').modal('show');        
     });

});

function setScadenzaData(vals,parm) {

    var val1=vals*parm; 
    var date1=$("#data_consegna_definitiva_lavori").val();
    if (date1!="") {
    var n=date1.split("/");
    date1=new Date(n[1]+"/"+n[0]+"/"+n[2]);             
    date1.setDate(date1.getDate() + val1);
    var dd = date1.getDate(); 
    var mm = date1.getMonth()+1;//January is 0! 
    var yyyy = date1.getFullYear(); 
    if(dd<10){dd='0'+dd} 
    if(mm<10){mm='0'+mm} 
    var date2=dd+"/"+mm+"/"+yyyy;       
    $('#inp_scadenza_tempo_utile').val(date2);  
    var sosp=parseInt($("#tot_sospensioni").val());
    var date3=$('#inp_scadenza_tempo_utile').val();
    n=date3.split("/");    
    date3=new Date(n[1]+"/"+n[0]+"/"+n[2]);  
    
    if (sosp>0)
        date3.setDate(date3.getDate() + sosp);
    else 
        date3.setDate(date3.getDate());
    
    var dd = date3.getDate(); 
    var mm = date3.getMonth()+1;//January is 0! 
    var yyyy = date3.getFullYear(); 
    if(dd<10){dd='0'+dd} 
    if(mm<10){mm='0'+mm} 
    var date4=dd+"/"+mm+"/"+yyyy;
    $("#inp_scadenza_definitiva").val(date4);
    }
    else {
        $('#inp_scadenza_tempo_utile').val("");
        $("#inp_scadenza_definitiva").val("");
    }
}
</script>
<div class="container-fluid">
    <div class="form-group row">
    <h5>Tempi di Esecuzione</h5>
    </div>   
 <div class="form-group row">  
        <label class="control-label col-lg-2" for="inp_consegna_parziale_lavori">Consegna Parziale Lavori: </label>     
        <div class="col-lg-2">    
         <div class="input-group date" id="inp_consegna_parziale_lavori" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" data-target="#inp_consegna_parziale_lavori" id="data_consegna_parziale_lavori" value="<?=$Consegna_parziale;?>"/>
                    <div class="input-group-append" data-target="#inp_consegna_parziale_lavori" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
                <script type="text/javascript">
                $(function () {
                    $('#inp_consegna_parziale_lavori').datetimepicker({
                        locale: 'it',
                        format: 'L'
                    });
                });
            </script>  
        </div> 
        <label class="control-label col-lg-2" for="inp_consegna_definitiva_lavori">Consegna Definitiva Lavori: </label>
        <div class="col-lg-2">    
         <div class="input-group date" id="inp_consegna_definitiva_lavori" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" data-target="#inp_consegna_definitiva_lavori" id="data_consegna_definitiva_lavori" value="<?=$Consegna_definitiva;?>"/>
                    <div class="input-group-append" data-target="#inp_consegna_definitiva_lavori" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
                <script type="text/javascript">
                $(function () {
                    $('#inp_consegna_definitiva_lavori').datetimepicker({
                        locale: 'it',
                        format: 'L'
                    });
                });
            </script>  
        </div>
        <label class="control-label col-lg-1" for="inp_tempo_utile">Tempo Utile: </label> 
        <div class="col-lg-1">
            <input type="text" id="inp_tempo_utile" class="form-control" value="<?=$Tempo_utile?>" /> 
        </div>
        <div class="col-lg-1">
        <select id="sel_unita_tempo" class="form-control">
                   <?php
                    $query="SELECT
                        IDUnitaTempo,
                        Unita_tempo
                    FROM unitatempo";
                    $result = mysql_query($query);
                    if (!$result){
                            die ("Could not query the database: <br />". mysql_error());
                    }
                    while ($row = mysql_fetch_assoc($result)){
                        $id=$row["IDUnitaTempo"];
                        $val=$row["Unita_tempo"];
                        echo "<option value='$id'";
                        if ($id==$IDUnitaTempo) echo " selected";
                        echo ">";
                        echo $val;
                        echo "</option>";                        
                    }                    
                   ?>   
                  </select> 
         </div>    
 </div>
 <div class="form-group row">
            <label class="control-label col-lg-2" for="inp_ultimazione_lavori">Ultimazione Lavori</label>     
            <div class="col-lg-2">    
            <div class="input-group date" id="inp_ultimazione_lavori" data-target-input="nearest">
                       <input type="text" class="form-control datetimepicker-input" data-target="#inp_ultimazione_lavori" id="data_ultimazione_lavori" value="<?=$Ultimazione;?>"/>
                       <div class="input-group-append" data-target="#inp_ultimazione_lavori" data-toggle="datetimepicker">
                           <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                       </div>
                   </div>
                   <script type="text/javascript">
                   $(function () {
                       $('#inp_ultimazione_lavori').datetimepicker({
                           locale: 'it',
                           format: 'L'
                       });
                   });
               </script>  
           </div>
                  <label class="control-label col-lg-2" for="inp_certificata_data">Certificata in data</label>     
            <div class="col-lg-2">    
            <div class="input-group date" id="inp_certificata_data" data-target-input="nearest">
                       <input type="text" class="form-control datetimepicker-input" data-target="#inp_certificata_data" id="data_certificata_data" value="<?=$Certificata;?>"/>
                       <div class="input-group-append" data-target="#inp_certificata_data" data-toggle="datetimepicker">
                           <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                       </div>
                   </div>
                   <script type="text/javascript">
                   $(function () {
                       $('#inp_certificata_data').datetimepicker({
                           locale: 'it',
                           format: 'L'
                       });
                   });
               </script>  
           </div>    
</div>
 <div class="form-group row">
                  <label class="control-label col-lg-2" for="inp_stato_finale_gg">Stato finale entro gg.</label>     
             <div class="col-lg-1">    
                  <input type="text" id="inp_stato_finale_gg" class="form-control" value="<?=$Stato_finale_entro_gg?>" /> 
              </div>          
                  <label class="control-label col-lg-2" for="inp_stato_finale_emesso">Stato finale emesso il</label>     
             <div class="col-lg-2">    
            <div class="input-group date" id="inp_stato_finale_emesso" data-target-input="nearest">
                       <input type="text" class="form-control datetimepicker-input" data-target="#inp_stato_finale_emesso" id="data_stato_finale_emesso" value="<?=$Stato_finale_emesso;?>"/>
                       <div class="input-group-append" data-target="#inp_stato_finale_emesso" data-toggle="datetimepicker">
                           <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                       </div>
                   </div>
                   <script type="text/javascript">
                   $(function () {
                       $('#inp_stato_finale_emesso').datetimepicker({
                           locale: 'it',
                           format: 'L'
                       });
                   });
               </script>  
           </div>           
</div>
 <div class="form-group row">
                  <label class="control-label col-lg-2" for="inp_collaudo_entro_gg">Collaudo entro gg.</label>     
              <div class="col-lg-1">    
                  <input type="text" id="inp_collaudo_entro_gg" class="form-control" value="<?=$Collaudo_entro_gg?>" /> 
              </div>
                  <label class="control-label col-lg-2" for="inp_certiticato_collaudo_emesso">Certificato collaudo emesso il</label>     
             <div class="col-lg-2">    
            <div class="input-group date" id="inp_certiticato_collaudo_emesso" data-target-input="nearest">
                       <input type="text" class="form-control datetimepicker-input" data-target="#inp_certiticato_collaudo_emesso" id="data_certiticato_collaudo_emesso" value="<?=$Certif_collaudo_emesso;?>"/>
                       <div class="input-group-append" data-target="#inp_certiticato_collaudo_emesso" data-toggle="datetimepicker">
                           <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                       </div>
                   </div>
                   <script type="text/javascript">
                   $(function () {
                       $('#inp_certiticato_collaudo_emesso').datetimepicker({
                           locale: 'it',
                           format: 'L'
                       });
                   });
               </script>  
           </div>             
</div>
<div class="form-group row">
    <h5>Scadenze</h5>
</div>      
<div class="form-group row">
        <label class="control-label col-lg-2" for="inp_scadenza_tempo_utile">Scadenza tempo utile</label>     
        <div class="col-lg-2">   
            <input type="text" id="inp_scadenza_tempo_utile" class="form-control" value="<?=$Scadenza_tempo_utile?>" readonly/> 
        </div>
        <td>
            <label class="control-label col-lg-2" for="inp_scadenza_definitiva">Scadenza definitiva</label>     
        <div class="col-lg-2">  
            <input type="text" id="inp_scadenza_definitiva" class="form-control" value="<?=$Scadenza_definitiva;?>" readonly /> 
        </div>             
</div>
<div class="form-group row">
<label class="control-label col-lg-2" >Riserve Impresa</label>
<div class="col-lg-9">
<textarea type="text" id="ta_riserve" rows="3" class="form-control" value="" ><?=utf8_encode($Riserve);?></textarea> 
</div>
</div>
<hr />
<div class="form-group row col-lg-2">
<button id="but_salva_dati_tempi" class="btn btn-primary" type="button">Salva</button> 
</div>
</div>
<br /><br />

<div class="container-fluid">
  <div class="form-group row col-lg_2">
    <h5>Sospensioni</h5>
</div>     
<div class="form-group row col-lg_2">
    <button id="but_nuova_sospensione" class="btn btn-sm btn-info">Nuova Sospensione</button>
</div>       
<div class="form-group row col-lg_11" id="div_table_sospensioni">        
    
 </div> 

<div class="form-group row col-lg_2" >
    <h5>Proroghe</h5>
</div>     
<div class="form-group row col-lg_2">
    <button id="but_nuova_proroga" class="btn btn-sm btn-info">Nuova Proroga</button>
</div>       
<div class="form-group row col-lg_11" id="div_table_proroghe">         

</div>    
</div> 
<div id="dialog_sospensione" class="modal fade"  role="dialog">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
<div class="modal-header"> 
      <h5 id="h_sospensione"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <input type="hidden" id="hid_tipo_operazione" value=""/>
        <input type="hidden" id="hid_id_sospensione" value=""/>
        
</div>   
<div class="modal-body">
<table>             
<tr>
    <td>
        <label class="control-label" for="data_sospensione">Data Sospensione</label>     
    </td><td >    
         <div class="input-group date" id="d_sospensione" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#d_sospensione" id="data_sospensione" value=""/>
                <div class="input-group-append" data-target="#d_sospensione" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#d_sospensione').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script> 

    </td>
    <td>
        <label class="control-label" for="data_ripresa">Data Ripresa</label>     
    </td><td >    
         <div class="input-group date" id="d_ripresa" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#d_ripresa" id="data_ripresa" value=""/>
                <div class="input-group-append" data-target="#d_ripresa" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#d_ripresa').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script> 
        
    </td>
</tr>
<tr>
    <td>
    <label class="control-label" for="totale_giorni">Totale giorni</label>     
    </td><td> 
    <input type="text" id="totale_giorni" class="form-control" value="" /> 
    </td>
</tr>    
</table> 
</div> 
<div class="modal-footer justify-content-between">
       <button id='but_salva_sospensione' class='btn btn-success' > Salva</button>        
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
</div>         
</div>
</div>
</div>
<div id="dialog_proroga" class="modal fade"  role="dialog">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
<div class="modal-header">  
        <h5 id="h_proroga"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
       
        <input type="hidden" id="hid_id_proroga" value=""/>
        
</div>   
<div class="modal-body">
<table>             
<tr>
    <td>
        <label class="control-label" for="data_proroga">Data Proroga</label>     
    </td><td colspan="3">    
        <div class="input-group date" id="d_proroga" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#d_proroga" id="data_proroga" value=""/>
                <div class="input-group-append" data-target="#d_proroga" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#d_proroga').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
      
    </td>   
</tr>
<tr>
    <td>
    <label class="control-label" for="periodo_proroga">Periodo</label>     
    </td><td > 
    <input type="text" id="periodo_proroga" class="form-control" value="" /> 
    </td>
     <td colspan="2">    
         <select id="id_unita_tempo" class="form-control">
             <?php
                $query="SELECT IDUnitaTempo, Unita_tempo FROM unitatempo ";
                $result = mysql_query($query);
                if (!$result){
                        die ("Could not query the database: <br />". mysql_error());
                }
                while ($row = mysql_fetch_assoc($result)){
                    $id=$row["IDUnitaTempo"];
                    $desc=$row["Unita_tempo"];
                    
                    echo "<option value='$id'>$desc</option>";
                }
             ?>
         </select>
    </td>
</tr>    
</table> 
</div>
<div class="modal-footer justify-content-between">
       <button id='but_salva_proroga' class='btn btn-success' > Salva</button>        
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
</div>        
</div>
</div>
</div>
