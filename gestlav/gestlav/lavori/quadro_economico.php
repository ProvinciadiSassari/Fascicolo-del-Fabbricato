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

$id_lavoro_origine=$utility->getIDLavoroOrigine($id_lavoro);
$id_fabbricato=$utility->getIDFabbricatoFromIDLavoro($id_lavoro);


$fl_avanzato=$_SESSION['avanzato'];

?>

<script type="text/javascript" charset="utf-8">

$(document).ready(function() {                

    var id_lavoro=<?=$id_lavoro;?>;
    var id_fabbricato=<?=$id_fabbricato?>;
    var fl_avanzato=<?=$fl_avanzato;?>;
    
    $(".numeric_4").inputmask('decimal', { digits: 4, enforceDigitsOnBlur: true, integerDigits: 12,positionCaretOnTab: false }); 
    $(".numeric_0").inputmask('decimal', { digits: 0, enforceDigitsOnBlur: true, integerDigits: 2,positionCaretOnTab: false });
    
     var id_livello=<?=$_SESSION['idlevel'];?>;

      if (id_livello==7) {
          $("#but_nuovo_lavoro_qe").hide();
          $("#but_nuovo_somma_disposizione").hide();
          $("#but_salva_annotazioni").hide();
      }
      
      if (fl_avanzato==0) {
         $("#but_nuovo_lavoro_qe").hide();
         $("#but_nuovo_somma_disposizione").hide();
         $("#but_salva_annotazioni").hide();
      }
     
    $("#inp_sottodescrizione_quadro").hide();
    $("#sel_descrizione_lavoro").show();
    $("#inp_sottodescrizione_quadro2").hide();
    $("#sel_descrizione_lavoro2").show();
    
    $('#tab_quadro_1').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "bInfo": false,
        "bFilter": false,
        "bPaginate": false,    
        "language": {
            "url": 'language/Italian.json'
        } 
    }); 

    $('#tab_quadro_2').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "bInfo": false,
        "bFilter": false,
        "bPaginate": false,    
        "language": {
            "url": 'language/Italian.json'
        } 
    }); 
  
   $('#tab_quadro_3').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "bInfo": false,
        "bFilter": false,
        "bPaginate": false,    
        "language": {
            "url": 'language/Italian.json'
        } 
    }); 

    $("#but_nuovo_lavoro_qe").click(function(){
        $("#hid_tipo_operazione_qe").val("new");
        $("#sel_descrizione_lavoro").show();                
        $("#inp_sottodescrizione_quadro").hide();
        $("#but_elimina_lavoro").hide();
        $("#lab_data_progetto").show();
        $("#lab_data_contratto").show();
        $("#lab_data_perizia").show();
        $("#lab_data_collaudo").show();
        $("#td_data_qe_progetto").show();
        $("#td_data_qe_contratto").show();
        $("#td_data_qe_perizia").show();
        $("#td_data_qe_collaudo").show();
        $("#perc_iva").val(""); 
        $("#imp_qe_progetto").val(""); 
        $("#imp_qe_contratto").val(""); 
        $("#imp_qe_perizia").val(""); 
        $("#imp_qe_collaudo").val("");       
        $("#data_qe_progetto").val("");
        $("#data_qe_contratto").val("");
        $("#data_qe_perizia").val("");
      $("#data_qe_collaudo").val("");   
        $("#hid_progressivo_quadro").val(0);
        $('#dialog_lavoro_qe').modal('show');  
        
//      $.ajax({
//            type: "post",
//             dataType: "json",
//            url: "query/retrieve_dati_quadro_economico.php",                 
//            data: "id_lavoro="+id_lavoro+"&id_sottodescrizione_quadro=1",
//            success: function(msg) { 
//                    alert(msg);
//               
//                $("#data_qe_progetto").val(msg[10]); 
//                $("#data_qe_contratto").val(msg[11]); 
//                $("#data_qe_perizia").val(msg[12]); 
//                $("#data_qe_collaudo").val(msg[13]); 
//                
//               $('#dialog_lavoro_qe').modal('show');  
//                                             
//            },
//            error: function() {
////                alert ("error 211");
//            }
//       });        
    });
    
    $("#but_nuovo_somma_disposizione").click(function(){
        $("#hid_tipo_operazione2").val("new");
        $("#sel_descrizione_lavoro2").show();                
        $("#inp_sottodescrizione_quadro2").hide();
        $("#but_elimina_lavoro2").hide();
         $("#perc_iva2").val(""); 
        $("#imp_qe_progetto2").val(""); 
        $("#imp_qe_contratto2").val(""); 
        $("#imp_qe_perizia2").val(""); 
        $("#imp_qe_collaudo2").val("");       
        $("#hid_progressivo_quadro").val(0);
        $('#dialog_somme_disposizione_qe').modal('show');
    });
    
    $("#but_salva_lavoro").click(function(){
        var progressivo=$("#hid_progressivo_quadro").val();
       var perc_iva=$("#perc_iva").val(); 
       var imp_qe_progetto=$("#imp_qe_progetto").val(); 
       var imp_qe_contratto=$("#imp_qe_contratto").val(); 
       var imp_qe_perizia=$("#imp_qe_perizia").val(); 
       var imp_qe_collaudo=$("#imp_qe_collaudo").val(); 
       var sel_descrizione_lavoro=$("#sel_descrizione_lavoro").val();
       var tipo_operazione=$("#hid_tipo_operazione_qe").val();
       var hid_sottodescrizione_quadro=$("#hid_sottodescrizione_quadro").val();      

       var data_qe_progetto=$("#data_qe_progetto").val();
       var data_qe_contratto=$("#data_qe_contratto").val();
       var data_qe_perizia=$("#data_qe_perizia").val();
       var data_qe_collaudo=$("#data_qe_collaudo").val();       
       
       if (perc_iva=="") perc_iva=0;
       if (imp_qe_progetto=="") imp_qe_progetto=0;
       if (imp_qe_contratto=="") imp_qe_contratto=0;
       if (imp_qe_perizia=="") imp_qe_perizia=0;
       if (imp_qe_collaudo=="") imp_qe_collaudo=0;           
           
           var ajax_data={
               progressivo:progressivo,
               id_lavoro:id_lavoro,
               perc_iva:perc_iva,
               imp_qe_progetto:imp_qe_progetto,
               imp_qe_contratto:imp_qe_contratto,
               imp_qe_perizia:imp_qe_perizia,
               imp_qe_collaudo:imp_qe_collaudo,
               sel_descrizione_lavoro:sel_descrizione_lavoro,
               hid_sottodescrizione_quadro:hid_sottodescrizione_quadro,               
               data_qe_progetto:data_qe_progetto,
               data_qe_contratto:data_qe_contratto,
               data_qe_perizia:data_qe_perizia,
               data_qe_collaudo:data_qe_collaudo,
               tipo_operazione:tipo_operazione
             };
             
            $.ajax({
                type: "post",
                url: "actions/submit_salva_quadro_economico.php",                 
                data: ajax_data,
                success: function(msg) { 
//                    alert(msg);
                  
                  
                  $('#dialog_lavoro_qe').modal('hide'); 
                   swal(
                    'Informazione',
                    'Operazione avventuta con successo.',
                    'success'
                  );
                  $('.modal-backdrop').remove();  
                  $("#div_quadro_economico").load("lavori/quadro_economico.php?id_lavoro="+id_lavoro);
                },
                error: function() {
                    alert ("error 1");
                }
           });  
              
    });
    
    $(document).on("click","#img_mod1",function(e){
        e.stopImmediatePropagation();
        var progressivo=$(this).attr("alt");       
        $("#hid_tipo_operazione_qe").val("mod");

        $("#lab_data_progetto").show();
        $("#lab_data_contratto").show();
        $("#lab_data_perizia").show();
        $("#lab_data_collaudo").show();
        $("#td_data_qe_progetto").show();
        $("#td_data_qe_contratto").show();
        $("#td_data_qe_perizia").show();
        $("#td_data_qe_collaudo").show();
        $.ajax({
            type: "post",
             dataType: "json",
            url: "query/retrieve_dati_quadro_economico.php",                 
            data: "progressivo="+progressivo,
            success: function(msg) { 
//                    alert(msg);
                $("#hid_progressivo_quadro").val(progressivo);
                $("#hid_sottodescrizione_quadro").val(msg[1]);    
                $("#perc_iva").val(msg[2]); 
                $("#imp_qe_progetto").val(msg[3]); 
                $("#imp_qe_contratto").val(msg[4]); 
                $("#imp_qe_perizia").val(msg[5]); 
                $("#imp_qe_collaudo").val(msg[6]); 
                $("#data_qe_progetto").val(msg[10]); 
                $("#data_qe_contratto").val(msg[11]); 
                $("#data_qe_perizia").val(msg[12]); 
                $("#data_qe_collaudo").val(msg[13]); 
                $("#sel_descrizione_lavoro").hide(); 
                $("#inp_sottodescrizione_quadro").val(msg[9]);
                $("#inp_sottodescrizione_quadro").show();                


                $("#but_elimina_lavoro").show();
                $("#hid_data_modifica_qe").val(msg[7]);
               
                $('#dialog_lavoro_qe').modal('show');                               
            },
            error: function() {
                alert ("error 2");
            }
       });
    });
    
    
    $("#sel_descrizione_lavoro2").change(function(){
        
        var id_sel = $(this).val(); 
        
        if (id_sel==32) { //D14 - Economie derivanti dal ribasso                
        
            var qe_progetto = $("#td_q1_totlav_progetto").text();
            var qe_contratto = $("#td_q1_totlav_contratto").text();
            var qe_contratto2=0;
            
            if (qe_progetto=="" || qe_contratto=="") {                
                qe_progetto=0.00;
                qe_contratto=0.00;
                qe_contratto2=0.00;
            }
            else {

                qe_progetto = qe_progetto.replace("€", "").trim(); 
                qe_contratto = qe_contratto.replace("€", "").trim(); 
                qe_progetto = qe_progetto.replace(".", "").trim(); 
                qe_contratto = qe_contratto.replace(".", "").trim(); 
                qe_progetto = qe_progetto.replace(",", ".").trim(); 
                qe_contratto = qe_contratto.replace(",", ".").trim(); 

                qe_contratto2=Number((parseFloat(qe_progetto)-parseFloat(qe_contratto)).toFixed(2));
            }
                        
            $("#imp_qe_contratto2").val(qe_contratto2);
        }
        else {
            $("#imp_qe_contratto2").val("0.00");
        }
    });
    
    
    $("#but_salva_lavoro2").click(function(){
       var perc_iva=$("#perc_iva2").val(); 
       var imp_qe_progetto=$("#imp_qe_progetto2").val(); 
       var imp_qe_contratto=$("#imp_qe_contratto2").val(); 
       var imp_qe_perizia=$("#imp_qe_perizia2").val(); 
       var imp_qe_collaudo=$("#imp_qe_collaudo2").val(); 
       var sel_descrizione_lavoro=$("#sel_descrizione_lavoro2").val();
       var tipo_operazione=$("#hid_tipo_operazione2").val();
       var hid_sottodescrizione_quadro=$("#hid_sottodescrizione_quadro2").val();
       var progressivo=$("#hid_progressivo_quadro").val();
       if (perc_iva=="") perc_iva=0;
       if (imp_qe_progetto=="") imp_qe_progetto=0;
       if (imp_qe_contratto=="") imp_qe_contratto=0;
       if (imp_qe_perizia=="") imp_qe_perizia=0;
       if (imp_qe_collaudo=="") imp_qe_collaudo=0;       
           
           var ajax_data={
               progressivo:progressivo,
               id_lavoro:id_lavoro,
               perc_iva:perc_iva,
               imp_qe_progetto:imp_qe_progetto,
               imp_qe_contratto:imp_qe_contratto,
               imp_qe_perizia:imp_qe_perizia,
               imp_qe_collaudo:imp_qe_collaudo,
               sel_descrizione_lavoro:sel_descrizione_lavoro,
               hid_sottodescrizione_quadro:hid_sottodescrizione_quadro,
               tipo_operazione:tipo_operazione
             };
             
            $.ajax({
                type: "post",
                url: "actions/submit_salva_quadro_economico.php",                 
                data: ajax_data,
                success: function(msg) { 
//                    alert(msg);

                  
                  $('#dialog_somme_disposizione_qe').modal('hide'); 
                   swal(
                    'Informazione',
                    'Operazione avventuta con successo.',
                    'success'
                  );
                  $('.modal-backdrop').remove();  
                  $("#div_quadro_economico").load("lavori/quadro_economico.php?id_lavoro="+id_lavoro);                                       
                },
                error: function() {
                    alert ("error 3");
                }
           });  
          
    });
    
    $(document).on("click","#img_mod2",function(e){
         e.stopImmediatePropagation();
        var progressivo=$(this).attr("alt");
      
        $("#hid_tipo_operazione2").val("mod");
        
        $.ajax({
            type: "post",
            dataType: "json",
            url: "query/retrieve_dati_quadro_economico.php",                 
             data: "progressivo="+progressivo,
            success: function(msg) { 
//                    alert(msg);
                $("#hid_progressivo_quadro").val(progressivo);
                $("#hid_sottodescrizione_quadro2").val(msg[1]);
                $("#perc_iva2").val(msg[2]); 
                $("#imp_qe_progetto2").val(msg[3]); 
                $("#imp_qe_contratto2").val(msg[4]); 
                $("#imp_qe_perizia2").val(msg[5]); 
                $("#imp_qe_collaudo2").val(msg[6]); 
                $("#sel_descrizione_lavoro2").hide(); 
                $("#inp_sottodescrizione_quadro2").val(msg[9]);
                $("#inp_sottodescrizione_quadro2").show();
                
                $("#but_elimina_lavoro2").show();
                $('#dialog_somme_disposizione_qe').modal('show');                               
            },
            error: function() {
                alert ("error 4");
            }
       });
    });
    
    $("#but_elimina_lavoro").click(function(){
        var id_sottodescrizione_quadro=$("#hid_sottodescrizione_quadro").val();
        var data_modifica=$("#hid_data_modifica_qe").val();
        var tipo_operazione="del";
        
        var ajax_data={
               id_lavoro:id_lavoro,
               id_sottodescrizione_quadro:id_sottodescrizione_quadro,
               data_modifica:data_modifica,               
               tipo_operazione:tipo_operazione
             };
       
            $.ajax({
                type: "post",
                url: "actions/submit_salva_quadro_economico.php",                 
                data: ajax_data,
                success: function(msg) { 
//                    alert(msg);                  
                  $('#dialog_lavoro_qe').modal('hide');  
                  $('#alert_success_quadro').modal("show"); 
                  $("#div_quadro_economico").empty();
                  $("#div_quadro_economico").load("lavori/quadro_economico.php?id_lavoro="+id_lavoro); 
                     
                                      
                },
                error: function() {
                    alert ("error 1");
                }
           }); 
        
    });
    
    $("#but_elimina_lavoro2").click(function(){
        var id_sottodescrizione_quadro=$("#hid_sottodescrizione_quadro2").val();
        var tipo_operazione="del";
        var data_modifica = $("#hid_data_modifica2").val();
        
        var ajax_data={
               id_lavoro:id_lavoro,
               id_sottodescrizione_quadro:id_sottodescrizione_quadro,
               data_modifica:data_modifica,
               tipo_operazione:tipo_operazione
             };
       
            $.ajax({
                type: "post",
                url: "actions/submit_salva_quadro_economico.php",                 
                data: ajax_data,
                success: function(msg) { 
//                    alert(msg);                  
                  $('#dialog_somme_disposizione_qe').modal('hide');  
                  $('#alert_success_quadro').modal("show"); 
                  $("#div_quadro_economico").empty();
                  $("#div_quadro_economico").load("lavori/quadro_economico.php?id_lavoro="+id_lavoro); 
                     
                                      
                },
                error: function() {
                    alert ("error 1");
                }
           }); 
        
    });
    
    $("#but_salva_annotazioni").click(function(){
        var ta_annotazioni=$("#ta_annotazioni").val();
        var tipo_operazione="note";
        var ajax_data={
               id_lavoro:id_lavoro,
               ta_annotazioni:ta_annotazioni,               
               tipo_operazione:tipo_operazione
             };
             
           $.ajax({
                type: "post",
                url: "actions/submit_salva_quadro_economico.php",                 
                data: ajax_data,
                success: function(msg) { 
//                    alert(msg);
                  $('#alert_success_quadro').modal("show");                              
                  $("#div_quadro_economico").empty();
                  $("#div_quadro_economico").load("lavori/quadro_economico.php?id_lavoro="+id_lavoro); 
                     
                                      
                },
                error: function() {
                    alert ("error 3");
                }
           });
    });
    
    $("#but_salva_nuovo_lavoro").click(function(e){
        e.preventDefault();
        
        var inp_codice_contratto=$("#inp_codice_contratto").val();
        var inp_data_contratto=$("#data_nl_contratto").val();
        var sel_categoria=$("#sel_categoria").val();
        var sel_tipologia=$("#sel_tipologia").val();
        var ta_descrizione=$("#ta_descrizione").val();
        var ta_note_lavoro=$("#ta_note_lavoro").val();
        var sel_responsabile=$("#sel_responsabile").val();
        var sel_istruttore=$("#sel_istruttore").val();
        var chk_complementare =1;
        var sel_lavoro_origine=<?=$id_lavoro_origine;?>;
        var inp_disponibilita=$("#hid_disponibilita").val();
      
        if (inp_disponibilita=="") inp_disponibilita=0;
       
        var ajax_data="";
        
        ajax_data={  
            id_fabbricato:id_fabbricato,
            inp_codice_contratto:inp_codice_contratto,
            inp_data_contratto:inp_data_contratto,
            sel_categoria:sel_categoria,
            sel_tipologia:sel_tipologia,
            ta_descrizione:ta_descrizione,                            
            ta_note_lavoro:ta_note_lavoro,
            sel_responsabile:sel_responsabile,
            sel_istruttore:sel_istruttore,   
            chk_complementare:chk_complementare,
            sel_lavoro_origine:sel_lavoro_origine,
            inp_disponibilita:inp_disponibilita
        };
        
        $.ajax({
            type: "post",
            url: "actions/submit_salva_nuovo_lavoro.php",                                                 
            data: ajax_data,
            success: function(id_new_lavoro) { 
//                alert(id_new_lavoro);     

                $('#dialog_nuovo_lavoro').modal('hide'); 
                
                swal(
                    'Informazione',
                    'Operazione avventuta con successo.',
                    'success'
                  );
                 
//                $("#div_lavori2").empty();
                $("#div_lavori2").load("dati_lavori.php?id_lavoro="+id_new_lavoro);  
            },
            error: function() {
                alert ("error");
            }
        });
    });
    
    $("#but_nuovo_lavoro_complementare").click(function(e){       
        $('#dialog_nuovo_lavoro').modal('show');
    });
    
    $("#perc_iva2").focusout(function(e){
       calcolaIVA();
    });  
        
     $("#but_stampa_quadro").click(function(e){
        window.location="lavori/stampa_quadro_economico.php?id_lavoro=<?=$id_lavoro;?>";
     });   
});

function calcolaIVA() {
    var val = $("#perc_iva2").val(); 
       var id_sottodescrizione=$("#sel_descrizione_lavoro2").val();       
       
       if (id_sottodescrizione==30 || id_sottodescrizione==26 || id_sottodescrizione==301) {
           var B=$("#B").html();
           B=B.replace("€","").trim();
           B=B.replace(new RegExp(",","gm"),"").trim();  
           var perc=val/100;
           var imp=B*perc;
           $("#imp_qe_progetto2").val(imp.toFixed(2));          
       }

       if (id_sottodescrizione==15) {
           var TSG=$("#hid_tot_spese_generali").val();
           var perc=val/100;
           var imp=TSG*perc;
           $("#imp_qe_progetto2").val(imp.toFixed(2)); 
       }
       
       if (id_sottodescrizione==302) {
           var TLE=$("#hid_tot_lavori_economia").val();
           var perc=val/100;
           var imp=TLE*perc;
           $("#imp_qe_progetto2").val(imp.toFixed(2)); 
       }
       
       if (id_sottodescrizione==303) {
           var TSG1=$("#hid_tot_spese_generali").val();
           var TSG2=$("#hid_tot_cnpaia").val();
           var TSG=TSG1+TSG2;
           var perc=val/100;
           var imp=TSG*perc;
           $("#imp_qe_progetto2").val(imp.toFixed(2)); 
       }
       
       if (id_sottodescrizione==304) {
           var TIG=$("#hid_tot_indagini_generali").val();
           var perc=val/100;
           var imp=TIG*perc;
           $("#imp_qe_progetto2").val(imp.toFixed(2)); 
       }
       
       if (id_sottodescrizione==305) {
           var TRUP=$("#hid_tot_spese_RUP").val();
           var perc=val/100;
           var imp=TRUP*perc;
           $("#imp_qe_progetto2").val(imp.toFixed(2)); 
       }
}
</script>

<div id="div_quadro">
    <button id="but_stampa_quadro" class="btn btn-warning btn-sm" type="button" style="margin-left:15px;">Stampa Quadro Economico</button>

        <br /><hr />
    <fieldset>
        <legend>LAVORI <button id="but_nuovo_lavoro_qe" class="btn btn-success btn-sm" type="button" style="margin-left:40px;">Nuovo</button></legend> 
    <table class="table table-striped table-bordered" width="100%" cellspacing="0" id="tab_quadro_1" >
       <thead><tr><th>Descrizione</th><th>Perc (%)</th><th>Q.E. Progetto</th><th>Q.E. Contratto</th><th>Q.E. Perizia</th><th>Q.E. Collaudo</th><th>Mod.</th></tr>
              
       </thead>
       <tbody>
	<?
        
        $DISPONIBILITA=0;
//        $query="SELECT data_qe_progetto, data_qe_contratto, data_qe_perizia, data_qe_collaudo
//            FROM legame_lavori_quadri_economici where id_lavoro=".$id_lavoro;
//    
//            $result = mysql_query($query);
//            if (!$result){
//                    die ("Could not query the database: <br />". mysql_error());
//            }
//            if ($row = mysql_fetch_assoc($result)){ 
//                echo "<tr style='font-size:8pt; font-weight:bold;'><td></td><td></td><td>Data ".$utility->convertDateToHTML($row["data_qe_progetto"])."</td><td>Data ".$utility->convertDateToHTML($row["data_qe_contratto"])."</td><td>Data ".$utility->convertDateToHTML($row["data_qe_perizia"])."</td><td>Data ".$utility->convertDateToHTML($row["data_qe_collaudo"])."</td><td></td></tr>";               
//            } 
  
        $query = "SELECT
                   q.progressivo, q.id_sottodescrizione_quadro,s.desc_sottodescrizione_quadro,q.perc_iva,q.imp_qe_progetto,q.imp_qe_contratto,q.imp_qe_perizia,q.imp_qe_collaudo,q.data_modifica
                FROM quadro_economico_generale q, sottodescrizioni_quadro s
                WHERE q.id_lavoro=$id_lavoro AND q.id_sottodescrizione_quadro<=3 and q.id_sottodescrizione_quadro=s.id_sottodescrizione_quadro";
        
	// Execute the query
	$result = mysql_query($query);
	if (!$result){
		die ("Could not query the database: <br />". mysql_error());
	} 
        $t1=0;$t2=0;$t3=0;$t4=0;$t5=0;$entrato=false;$t1_l=0;$t2_l=0;$t3_l=0;$t4_l=0;
	while ($row = mysql_fetch_assoc($result)) {
		$entrato=true;				            					           
                $Descrizione = $row["desc_sottodescrizione_quadro"];
                $perc_iva = $row["perc_iva"];
                $progressivo = $row["progressivo"];
                $imp_qe_progetto = $row["imp_qe_progetto"];
                $imp_qe_contratto = $row["imp_qe_contratto"];
                $imp_qe_perizia = $row["imp_qe_perizia"];
		$imp_qe_collaudo = $row["imp_qe_collaudo"];
                $id_sottodescrizione_quadro = $row["id_sottodescrizione_quadro"];
                $data_modifica = $row["data_modifica"];

                if ($perc_iva==0) $perc_iva="";
                
		echo "<tr>";		         
                echo "<td>".utf8_encode($Descrizione)."</td>";
                echo "<td align='right'>$perc_iva</td>";
		echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_progetto,2)."</td>";     
                echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_contratto,2)."</td>";
		echo "<td align='right' width='80'>".$utility->FormatNumber($imp_qe_perizia,2)."</td>";     
                echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_collaudo,2)."</td>";
                echo "<td align='center'><img src='images/modifica.png' class='hand' id='img_mod1' alt='$progressivo'/></td>";
		echo "</tr>";  
                
                $t1+=$imp_qe_progetto;
                $t2+=$imp_qe_contratto;
                $t3+=$imp_qe_perizia;
                $t4+=$imp_qe_collaudo;               
	}
        if ($entrato==true) {
            $t1_l=$t1;$t2_l=$t2;$t3_l=$t3;$t4_l=$t4;
            
            $query1="select count(*) as conta from quadro_economico_generale where id_lavoro=$id_lavoro and id_sottodescrizione_quadro=32";
            
            $result1 = mysql_query($query1);
            if (!$result1){
                    die ("Could not query the database: <br />". mysql_error());
            }
            $conta=0;
            if ($row1 = mysql_fetch_assoc($result1)) {
                $conta=$row1["conta"];                
            }
            if ($conta>0) {
                $query1="update quadro_economico_generale set imp_qe_contratto=$t1-$t2 where id_lavoro=$id_lavoro and id_sottodescrizione_quadro=32";
                $result1 = mysql_query($query1);
                if (!$result1){
                        die ("Could not query the database: <br />". mysql_error());
                }
            }
            
        ?>
         <tr id="tr_totale_lavori" style='background: #aa99bb; font-weight: bold;'><td>TOTALE LAVORI</td><td></td><td id="td_q1_totlav_progetto" align='right'><?=$utility->FormatNumber($t1,2);?></td><td id="td_q1_totlav_contratto" align='right'><?=$utility->FormatNumber($t2,2);?></td><td align='right'><?=$utility->FormatNumber($t3,2)?></td><td align='right'><?=$utility->FormatNumber($t4,2);?></td><td></td></tr>  
         <?
        }
        $query = "SELECT
                    q.progressivo,q.id_sottodescrizione_quadro,s.desc_sottodescrizione_quadro,q.perc_iva,q.imp_qe_progetto,q.imp_qe_contratto,q.imp_qe_perizia,q.imp_qe_collaudo,q.data_modifica
                FROM quadro_economico_generale q, sottodescrizioni_quadro s
                WHERE q.id_lavoro=$id_lavoro AND q.id_sottodescrizione_quadro=4 and q.id_sottodescrizione_quadro=s.id_sottodescrizione_quadro";
        	
	$result = mysql_query($query);
	if (!$result){
		die ("Could not query the database: <br />". mysql_error());
	} 
        $entrato=false;
	while ($row = mysql_fetch_assoc($result)) {
		$entrato=true;				            					           
                $Descrizione = $row["desc_sottodescrizione_quadro"];
                $perc_iva = $row["perc_iva"];
                $progressivo = $row["progressivo"];
                $imp_qe_progetto = $row["imp_qe_progetto"];
                $imp_qe_contratto = $row["imp_qe_contratto"];
                $imp_qe_perizia = $row["imp_qe_perizia"];
		$imp_qe_collaudo = $row["imp_qe_collaudo"];
                $id_sottodescrizione_quadro = $row["id_sottodescrizione_quadro"];
                $data_modifica = $row["data_modifica"];

                if ($perc_iva==0) $perc_iva="";
                
		echo "<tr>";		         
                echo "<td>".utf8_encode($Descrizione)."</td>";
                echo "<td align='right'>$perc_iva</td>";
		echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_progetto,2)."</td>";     
                echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_contratto,2)."</td>";
		echo "<td align='right' width='80'>".$utility->FormatNumber($imp_qe_perizia,2)."</td>";     
                echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_collaudo,2)."</td>";
                echo "<td align='center'><img src='images/modifica.png' class='hand' id='img_mod1' alt='$progressivo'/></td>";
		echo "</tr>";  
                
                $t1+=$imp_qe_progetto;
                $t2+=$imp_qe_contratto;
                $t3+=$imp_qe_perizia;
                $t4+=$imp_qe_collaudo;               
	}
        if ($entrato==true) {         
        ?> 
         <tr style='background: #aa99bb; font-weight: bold;'><td>TOTALE LAVORI ED ONERI</td><td></td><td align='right' id="B"><?=$utility->FormatNumber($t1,2);?></td><td align='right'><?=$utility->FormatNumber($t2,2);?></td><td align='right'><?=$utility->FormatNumber($t3,2)?></td><td align='right'><?=$utility->FormatNumber($t4,2);?></td><td></td></tr>
         <?
        }
        $query = "SELECT
                    q.progressivo,q.id_sottodescrizione_quadro,s.desc_sottodescrizione_quadro,q.perc_iva,q.imp_qe_progetto,q.imp_qe_contratto,q.imp_qe_perizia,q.imp_qe_collaudo,q.data_modifica
                FROM quadro_economico_generale q, sottodescrizioni_quadro s
                WHERE q.id_lavoro=$id_lavoro AND q.id_sottodescrizione_quadro=5 and q.id_sottodescrizione_quadro=s.id_sottodescrizione_quadro";
        
	// Execute the query
	$result = mysql_query($query);
	if (!$result){
		die ("Could not query the database: <br />". mysql_error());
	} 
        $entrato=false;$t1_r=0;$t2_r=0;$t3_r=0;$t4_r=0;
	if ($row = mysql_fetch_assoc($result)) {
		$entrato=true;				            					           
                $Descrizione = $row["desc_sottodescrizione_quadro"];
                $perc_iva = $row["perc_iva"];
                $progressivo = $row["progressivo"];
                $imp_qe_progetto = $row["imp_qe_progetto"];
                $imp_qe_contratto = $row["imp_qe_contratto"];
                $imp_qe_perizia = $row["imp_qe_perizia"];
		$imp_qe_collaudo = $row["imp_qe_collaudo"];
                $id_sottodescrizione_quadro = $row["id_sottodescrizione_quadro"];
                $data_modifica = $row["data_modifica"];

                if ($perc_iva==0) $perc_iva="";
                
		echo "<tr>";		         
                echo "<td>".utf8_encode($Descrizione)."</td>";
                echo "<td align='right'>$perc_iva</td>";
		echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_progetto,2)."</td>";     
                echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_contratto,2)."</td>";
		echo "<td align='right' width='80'>".$utility->FormatNumber($imp_qe_perizia,2)."</td>";     
                echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_collaudo,2)."</td>";
                echo "<td align='center'><img src='images/modifica.png' class='hand' id='img_mod1' alt='$progressivo'/></td>";
		echo "</tr>";  
                
//                $t1_r=$t1-$imp_qe_progetto;
//                $t1_r=$t2-$imp_qe_contratto;
//                $t1_r=$t3-$imp_qe_perizia;
//                $t1_r=$t4-$imp_qe_collaudo;               
	}
        if ($entrato==true) {         
        ?> 
         <tr style='background: #aa99bb; font-weight: bold;'><td>IMPORTO DI CONTRATTO</td><td></td><td align='right'><?=$utility->FormatNumber($t1,2);?></td><td align='right'><?=$utility->FormatNumber($t2,2);?></td><td align='right'><?=$utility->FormatNumber($t3,2)?></td><td align='right'><?=$utility->FormatNumber($t4,2);?></td><td></td></tr>
         <?
        }
        ?>                 
        </tbody>        
    </table>
    </fieldset> 
    <fieldset style="margin-top:20px;">
        <legend>SOMME A DISPOSIZIONE DELL'AMMINISTRAZIONE <button id="but_nuovo_somma_disposizione" class="btn btn-success btn-sm" type="button" style="margin-left:40px;">Nuovo</button></legend>
    <table class="table table-striped table-bordered" width="100%" cellspacing="0" id="tab_quadro_2" >
        <thead><tr><th>Descrizione</th><th>Perc (%)</th><th>Q.E. Progetto</th><th>Q.E. Contratto</th><th>Q.E. Perizia</th><th>Q.E. Collaudo</th><th>Mod.</th></tr></thead>
       <tbody>	
	<?
        $query = "SELECT
                    q.progressivo,q.id_sottodescrizione_quadro,s.desc_sottodescrizione_quadro,q.perc_iva,q.imp_qe_progetto,q.imp_qe_contratto,q.imp_qe_perizia,q.imp_qe_collaudo,q.data_modifica
                FROM quadro_economico_generale q, sottodescrizioni_quadro s
                WHERE q.id_lavoro=$id_lavoro AND q.id_sottodescrizione_quadro>5 and q.id_sottodescrizione_quadro<300 and q.id_sottodescrizione_quadro=s.id_sottodescrizione_quadro order by s.progressivo_ordine";
        
	// Execute the query
	$result = mysql_query($query);
	if (!$result){
		die ("Could not query the database: <br />". mysql_error());
	} 
        $t11=0;$t22=0;$t33=0;$t44=0;$t55=0;$entrato=false;
	while ($row = mysql_fetch_assoc($result)){
		$entrato=true;		
		$Descrizione = $row["desc_sottodescrizione_quadro"];
                $perc_iva = $row["perc_iva"];
                $progressivo = $row["progressivo"];
                $imp_qe_progetto = $row["imp_qe_progetto"];
                $imp_qe_contratto = $row["imp_qe_contratto"];
                $imp_qe_perizia = $row["imp_qe_perizia"];
		$imp_qe_collaudo = $row["imp_qe_collaudo"];
                $id_sottodescrizione_quadro = $row["id_sottodescrizione_quadro"];
                $data_modifica = $row["data_modifica"];
                
                if ($perc_iva==0) $perc_iva="";
                
		echo "<tr>";		         
                echo "<td>".utf8_encode($Descrizione)."</td>";
                echo "<td align='right'>$perc_iva</td>";
		echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_progetto,2)."</td>";     
                echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_contratto,2)."</td>";
		echo "<td align='right' width='70'>".$utility->FormatNumber($imp_qe_perizia,2)."</td>";     
                echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_collaudo,2)."</td>";
                echo "<td align='center'><img src='images/modifica.png' class='hand' id='img_mod2' alt='".$progressivo."'/></td>";
		echo "</tr>";  
                
                $t11+=$imp_qe_progetto;
                $t22+=$imp_qe_contratto;
                $t33+=$imp_qe_perizia;
                $t44+=$imp_qe_collaudo;                                  
	}
        if ($entrato==true) {         
        ?> 
         <tr style='background: #aa99bb; font-weight: bold;'><td>TOTALE SOMME DISP.</td><td></td><td align='right'><?=$utility->FormatNumber($t11,2);?></td><td align='right'><?=$utility->FormatNumber($t22,2);?></td><td align='right'><?=$utility->FormatNumber($t33,2)?></td><td align='right'><?=$utility->FormatNumber($t44,2);?></td><td></td></tr>
         <tr style='background: #ccbbdd; font-weight: bold;'><td>TOTALE FINANZIAMENTO</td><td></td><td align='right'><?=$utility->FormatNumber($t11+$t1,2);?></td><td align='right'><?=$utility->FormatNumber($t22+$t2,2);?></td><td align='right'><?=$utility->FormatNumber($t33+$t3,2)?></td><td align='right'><?=$utility->FormatNumber($t44+$t4,2);?></td><td></td></tr>
         <?
        }
        $query = "SELECT
                    q.progressivo,q.id_sottodescrizione_quadro,s.desc_sottodescrizione_quadro,q.perc_iva,q.imp_qe_progetto,q.imp_qe_contratto,q.imp_qe_perizia,q.imp_qe_collaudo,q.data_modifica
                FROM quadro_economico_generale q, sottodescrizioni_quadro s
                WHERE q.id_lavoro=$id_lavoro AND q.id_sottodescrizione_quadro>=300 and q.id_sottodescrizione_quadro=s.id_sottodescrizione_quadro order by s.progressivo_ordine";
        
	// Execute the query
	$result = mysql_query($query);
	if (!$result){
		die ("Could not query the database: <br />". mysql_error());
	} 
        $entrato=false;
	while ($row = mysql_fetch_assoc($result)){
		$entrato=true;		
		$Descrizione = $row["desc_sottodescrizione_quadro"];
                $perc_iva = $row["perc_iva"];
                $progressivo = $row["progressivo"];
                $imp_qe_progetto = $row["imp_qe_progetto"];
                $imp_qe_contratto = $row["imp_qe_contratto"];
                $imp_qe_perizia = $row["imp_qe_perizia"];
		$imp_qe_collaudo = $row["imp_qe_collaudo"];
                $id_sottodescrizione_quadro = $row["id_sottodescrizione_quadro"];
                $data_modifica = $row["data_modifica"];
                
                if ($perc_iva==0) $perc_iva="";
                
		echo "<tr>";		         
                echo "<td>".utf8_encode($Descrizione)."</td>";
                echo "<td align='right'>$perc_iva</td>";
		echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_progetto,2)."</td>";     
                echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_contratto,2)."</td>";
		echo "<td align='right' width='70'>".$utility->FormatNumber($imp_qe_perizia,2)."</td>";     
                echo "<td align='right' width='100'>".$utility->FormatNumber($imp_qe_collaudo,2)."</td>";
                echo "<td align='center'><img src='images/modifica.png' class='hand' id='img_mod2' alt='".$progressivo."'/></td>";
		echo "</tr>";  
                
                $t11+=$imp_qe_progetto;
                $t22+=$imp_qe_contratto;
                $t33+=$imp_qe_perizia;
                $t44+=$imp_qe_collaudo;                                  
	}
        if ($entrato==true) {         
        ?> 
         <tr style='background: #aa99bb; font-weight: bold;'><td>TOTALE SOMME DISP.+IVA</td><td></td><td align='right'><?=$utility->FormatNumber($t11,2);?></td><td align='right'><?=$utility->FormatNumber($t22,2);?></td><td align='right'><?=$utility->FormatNumber($t33,2)?></td><td align='right'><?=$utility->FormatNumber($t44,2);?></td><td></td></tr>
         <tr style='background: #ccbbdd; font-weight: bold;'><td>TOTALE FINANZ.+IVA</td><td></td><td align='right'><?=$utility->FormatNumber($t11+$t1,2);?></td><td align='right'><?=$utility->FormatNumber($t22+$t2,2);?></td><td align='right'><?=$utility->FormatNumber($t33+$t3,2)?></td><td align='right'><?=$utility->FormatNumber($t44+$t4,2);?></td><td></td></tr>
         <?
        }
        ?> 
	</tbody>           
    </table>
    </fieldset>  
    <fieldset style="margin-top:20px;">
        <legend>Disponibilità</legend>        
        <?
        $T=0;
        if ($t44>0) {  //sec'è il collaudo faccio collaudo-progetto
          $T=$t44-$t11;  
        }
        else if ($t33>0) {  //sec'è la perizia faccio
          $T=$t33-$t11;  
        }
        else if ($t22>0) {  //sec'è il contrato faccio
          $T=$t22-$t11;  
        }
//       echo $t44."_".$t33."_".$t22."_".$t11;
       
        $style="";
        if ($T>0)
            $style="style='font-size:12pt;font-weight: bold;color:green;'";
        if ($T<0)
            $style="style='font-size:12pt;font-weight: bold;color:red;'";
        if ($T==0)
            $style="style='font-size:12pt;font-weight: bold;color:blue;'";
        echo "<label $style>";
            echo $utility->FormatNumber($T,2);
        echo "</label>";
        if ($T>0) {
            echo "<br /><br /><button id='but_nuovo_lavoro_complementare' class='btn btn-success' type='button'>Nuovo Lavoro Complementare</button>";
        }
        ?> 
     <input type="hidden" id="hid_disponibilita" value="<?=$T;?>"/>   
    </fieldset>
<? 
$query="select note_quadro from legame_lavori_quadri_economici where id_lavoro=$id_lavoro";
$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
} 
$annotazioni="";
while ($row = mysql_fetch_assoc($result)){
    $annotazioni=$row["note_quadro"];
}
?>
<br />    
<label class="control-label" for="ta_annotazioni">Annotazioni</label>     
<textarea type="text" id="ta_annotazioni" rows="8" class="form-control" value="" ><?=($annotazioni);?></textarea>  
<br /> 
<button id="but_salva_annotazioni" class="btn btn-primary" type="button">Salva</button>    
</div>

<div id="dialog_lavoro_qe" class="modal fade"  role="dialog">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
<div class="modal-content">    
<div class="modal-header">   
        <h5 id="">LAVORI</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>        
</div>
<div class="modal-body">
<div class="container-fluid">     
<input type="hidden" id="hid_tipo_operazione_qe" value="" />
<input type="hidden" id="hid_sottodescrizione_quadro" value="" />
<input type="hidden" id="hid_progressivo_quadro" value="0" />
<input type="hidden" id="hid_data_modifica_qe" value="" />
<div class="form-group row">
        <label class="control-label col-lg-2" for="sel_descrizione_lavoro">Descrizione</label>     
    <div class="col-lg-9">  
        <input type='text' id='inp_sottodescrizione_quadro' class='form-control' value='' readonly/>
        <select id="sel_descrizione_lavoro" class="form-control">
        <?
        $query="SELECT
                    id_sottodescrizione_quadro,desc_sottodescrizione_quadro 
                FROM sottodescrizioni_quadro
                WHERE id_sottodescrizione_quadro<=5 order by progressivo_ordine asc";
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }
        while ($row = mysql_fetch_assoc($result)){
            $id=$row["id_sottodescrizione_quadro"];
            $val=$row["desc_sottodescrizione_quadro"];
            echo "<option value='$id'>";
            echo utf8_encode($val);
            echo "</option>";                        
        }                    
        ?>   
        </select> 
    </div>
</div>
 <div class="form-group row">
        <label class="control-label col-lg-2" for="perc_iva">Percentuale(%)</label> 
        <div class="col-lg-2"> 
            <input type="text" id="perc_iva" value="" class='form-control numeric_0'/>
        </div>
</div>
<div class="form-group row">
        <label class="control-label col-lg-2" for="imp_qe_progetto">Q.E. Progetto</label>
        <div class="col-lg-3"> 
        <input type="text" id="imp_qe_progetto" value="" class='form-control numeric_4'/>
        </div>
        <label id="lab_data_progetto" class="control-label col-lg-2">Data Progetto</label>
        <div class="col-lg-3">    
         <div class="input-group date" id="d_qe_progetto" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" data-target="#d_qe_progetto" id="data_qe_progetto" value=""/>
                    <div class="input-group-append" data-target="#d_qe_progetto" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
                <script type="text/javascript">
                $(function () {
                    $('#d_qe_progetto').datetimepicker({
                        locale: 'it',
                        format: 'L'
                    });
                });
            </script>  
        </div>
</div>
<div class="form-group row">
        <label class="control-label col-lg-2" for="imp_qe_contratto">Q.E. Contratto</label>
        <div class="col-lg-3"> 
        <input type="text" id="imp_qe_contratto" value="" class='form-control numeric_4'/>
        </div>
        <label id="lab_data_contratto" class="control-label col-lg-2">Data Contratto</label>
        <div class="col-lg-3">    
         <div class="input-group date" id="d_qe_contratto" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" data-target="#d_qe_contratto" id="data_qe_contratto" value=""/>
                    <div class="input-group-append" data-target="#d_qe_contratto" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
                <script type="text/javascript">
                $(function () {
                    $('#d_qe_contratto').datetimepicker({
                        locale: 'it',
                        format: 'L'
                    });
                });
            </script>  
        </div>        
</div>
 <div class="form-group row">
        <label class="control-label col-lg-2" for="imp_qe_perizia">Q.E. Perizia</label>
        <div class="col-lg-3"> 
            <input type="text" id="imp_qe_perizia" value="" class='form-control numeric_4'/>
        </div> 
        <label id="lab_data_perizia" class="control-label col-lg-2">Data Perizia</label>
        <div class="col-lg-3">    
        <div class="input-group date" id="d_qe_perizia" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" data-target="#d_qe_perizia" id="data_qe_perizia" value=""/>
                    <div class="input-group-append" data-target="#d_qe_perizia" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
                <script type="text/javascript">
                $(function () {
                    $('#d_qe_perizia').datetimepicker({
                        locale: 'it',
                        format: 'L'
                    });
                });
            </script>  
        </div>        
</div>
 <div class="form-group row">
        <label class="control-label col-lg-2" for="imp_qe_collaudo">Q.E. Collaudo</label>
        <div class="col-lg-3"> 
            <input type="text" id="imp_qe_collaudo" value="" class='form-control numeric_4'/>
        </DIV>
          <label id="lab_data_collaudo" class="control-label col-lg-2">Data Collaudo</label>
        <div class="col-lg-3">    
         <div class="input-group date" id="d_qe_collaudo" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" data-target="#d_qe_collaudo" id="data_qe_collaudo" value=""/>
                    <div class="input-group-append" data-target="#d_qe_collaudo" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
                <script type="text/javascript">
                $(function () {
                    $('#d_qe_collaudo').datetimepicker({
                        locale: 'it',
                        format: 'L'
                    });
                });
            </script>  
        </div>             
</div>
</div>
</div>
<div class="modal-footer justify-content-between">     
       <button id='but_salva_lavoro' class='btn btn-success' > Salva</button>    
       <button id='but_elimina_lavoro' class='btn btn-danger' > Elimina</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
</div>        
</div>
</div>
</div>

<div id="dialog_somme_disposizione_qe" class="modal fade"  role="dialog">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
<div class="modal-content">       
<input type="hidden" id="hid_tipo_operazione2" value="" />
<input type="hidden" id="hid_sottodescrizione_quadro2" value="" />
<input type="hidden" id="hid_data_modifica2" value="" />
<input type="hidden" id="hid_tot_spese_generali" value="<?=$utility->getTOTSpeseGeneraliProgetto($id_lavoro);?>" />
<input type="hidden" id="hid_tot_lavori_economia" value="<?=$utility->getTOTLavoriEconomiaProgetto($id_lavoro);?>" />
<input type="hidden" id="hid_tot_cnpaia" value="<?=$utility->getTOTCNPAIAProgetto($id_lavoro);?>" />
<input type="hidden" id="hid_tot_indagini_generali" value="<?=$utility->getTOTIndaginiGeneraliProgetto($id_lavoro);?>" />
<input type="hidden" id="hid_tot_spese_RUP" value="<?=$utility->getTOTSpeseRUPProgetto($id_lavoro);?>" />
<div class="modal-header">                
        <h5 id="">SOMME A DISPOSIZIONE DELL'AMMINISTRAZIONE</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
</div>
<div class="modal-body">
 <div class="form-group row">
        <label class="control-label col-lg-2" for="sel_descrizione_lavoro2">Descrizione</label>     
    <div class="col-lg-9">
        <input type='text' id='inp_sottodescrizione_quadro2' class='form-control' value='' readonly/>
        <select id="sel_descrizione_lavoro2" class="form-control">
        <?
        $query="SELECT
                    id_sottodescrizione_quadro,desc_sottodescrizione_quadro 
                FROM sottodescrizioni_quadro
                WHERE id_sottodescrizione_quadro>5 and id_sottodescrizione_quadro not in (select id_sottodescrizione_quadro from quadro_economico_generale where id_lavoro=$id_lavoro) order by progressivo_ordine asc";
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }
        while ($row = mysql_fetch_assoc($result)){
            $id=$row["id_sottodescrizione_quadro"];
            $val=$row["desc_sottodescrizione_quadro"];
            echo "<option value='$id'>";
            echo utf8_encode($val);
            echo "</option>";                        
        }                    
        ?>   
        </select> 
    </div>
</div>
 <div class="form-group row">
        <label class="control-label col-lg-2" for="perc_iva2">Percentuale (%)</label>
        <div class="col-lg-3">
            <input type="text" id="perc_iva2" value="" class='form-control numeric_0'/>
        </div>
</div>
 <div class="form-group row">
        <label class="control-label col-lg-2" for="imp_qe_progetto2">Q.E. Progetto</label> 
        <div class="col-lg-3">
            <input type="text" id="imp_qe_progetto2" value="" class='form-control numeric_4'/>
        </div>
</div>
 <div class="form-group row">
       <label class="control-label col-lg-2" for="imp_qe_contratto2">Q.E. Contratto</label> 
      <div class="col-lg-3">
           <input type="text" id="imp_qe_contratto2" value="" class='form-control numeric_4'/>
       </div>
</div>
 <div class="form-group row">
        <label class="control-label col-lg-2" for="imp_qe_perizia2">Q.E. Perizia</label> 
        <div class="col-lg-3">
            <input type="text" id="imp_qe_perizia2" value="" class='form-control numeric_4'/>
        </div>
</div>
 <div class="form-group row">
       <label class="control-label col-lg-2" for="imp_qe_collaudo2">Q.E. Collaudo</label> 
       <div class="col-lg-3">
           <input type="text" id="imp_qe_collaudo2" value="" class='form-control numeric_4'/>
      </div>
</div>
</div> 
<div class="modal-footer justify-content-between">     
       <button id='but_salva_lavoro2' class='btn btn-success' > Salva</button>    
       <button id='but_elimina_lavoro2' class='btn btn-danger' > Elimina</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
</div>    
</div>
</div>
</div>    
<div id="dialog_nuovo_lavoro" class="modal fade"  role="dialog">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
<div class="modal-content">
    <div class="modal-header">        
        <h5>Nuovo Lavoro Complementare</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
</div>   
<div class="modal-body">    
<table>
          <tr>
              <td>
                  <label class="control-label" for="inp_codice_contratto">Codice Contratto</label>     
              </td><td>    
                  <input type="text" id="inp_codice_contratto" class="form-control" value="" /> 
              </td>
              <td>
                  <label class="control-label" for="d_nl_contratto">Data Contratto</label>     
              </td><td>    
                  
                    <div class="input-group date" id="d_nl_contratto" data-target-input="nearest">
                               <input type="text" class="form-control datetimepicker-input" data-target="#d_nl_contratto" id="data_nl_contratto" value=""/>
                               <div class="input-group-append" data-target="#d_nl_contratto" data-toggle="datetimepicker">
                                   <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                               </div>
                           </div>
                           <script type="text/javascript">
                           $(function () {
                               $('#d_nl_contratto').datetimepicker({
                                   locale: 'it',
                                   format: 'L'
                               });
                           });
                       </script>                   
              </td>              
          </tr> 
          <tr>
              <td>
                  <label class="control-label" for="inp_id_lavoro">ID Lavoro</label>     
              </td><td>    
                  <input type="text" id="inp_id_lavoro" class="form-control" value="<?=$utility->max_id_lavoro();?>" readonly /> 
              </td>
              <td>
                  <label class="control-label" for="inp_id_edificio">ID Fabbricato</label>     
              </td><td>    
                  <input type="text" id="inp_id_edificio" class="form-control" value="<?=$id_fabbricato;?>" readonly /> 
              </td>              
          </tr>         
          <tr>              
              <td>
                  <label class="control-label" for="sel_categoria">Categoria</label>     
              </td><td>    
                  <select id="sel_categoria" class="form-control">                     

                   <?
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
              </td>
              <td>
                  <label class="control-label" for="sel_tipologia">Tipologia</label>     
              </td><td>    
                  <select id="sel_tipologia" class="form-control">                   

                   <?
                    $query="SELECT IDTipologia,Tipologia FROM tipologie";
                    $result = mysql_query($query);
                    if (!$result){
                            die ("Could not query the database: <br />". mysql_error());
                    }
                    while ($row = mysql_fetch_assoc($result)){
                        $id=$row["IDTipologia"];
                        $val=$row["Tipologia"];
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
            <label class="control-label" for="ta_descrizione">Descrizione</label>     
            </td><td colspan="3"> 
            <textarea type="text" id="ta_descrizione" rows="3" class="form-control" value="" ></textarea> 
            </td>
        </tr>
        <tr>
            <td>
            <label class="control-label" for="ta_note_lavoro">Note</label>     
            </td><td colspan="3"> 
            <textarea type="text" id="ta_note_lavoro" rows="3" class="form-control" value="" ></textarea> 
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <fieldset>
                    <legend>Procedimento</legend>
                    <table>
                    <tr><td>    
                    <label class="control-label" for="sel_responsabile">Responsabile</label>
                    </td><td>
                    <select id="sel_responsabile" class="form-control">
                        <option value="0">- Seleziona -</option>
                   <?
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
                  </td><td>
                  <label class="control-label" for="sel_istruttore">Istruttore Pratica</label>
                  </td><td>
                    <select id="sel_istruttore" class="form-control">
                        <option value="0">- Seleziona -</option>
                   <?
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
                  </td></tr>
                  </table>
                </fieldset>
            </td>
        </tr>        
      </table>  
 </div> 
 <div class="modal-footer justify-content-between">     
       <button id='but_salva_nuovo_lavoro' class='btn btn-success' > Salva</button>    
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>
</div>    
</div>
</div>
</div>
    

