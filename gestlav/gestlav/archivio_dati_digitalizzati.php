<?php
session_start();
require_once('conf.inc.php');
include('conv.php');

$utility = new Utility();
$utility->connetti();

if (!isset($_SESSION['idlevel']) || ($_SESSION['idlevel']!=2  && $_SESSION['idlevel']!=5))
{ //se non passo il controllo ritorno all'index
    header("Location: /gestlav/index.php");
}

if (isset($_GET["id_fabbricato"])) {
    $id_fabbricato=$_GET["id_fabbricato"];     
}
else $id_fabbricato=0;

$fl_avanzato=$_SESSION['avanzato'];
$livello=$_SESSION['idlevel'];

?>
<style>
#dialog_dettaglio_file_d .modal-body {
    max-height: 600px;
}
#dialog_dettaglio_file_d {
    width: 700px; 
    margin: -280px 0 0 -350px; 
}
#dialog_ricerca_file_d {
    width: 1000px; 
    margin: -280px 0 0 -510px; 
}
.stop-scrolling {
  max-height: 600px;
  overflow: hidden;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/sweetalert.css" rel="stylesheet" type="text/css"/>
<script src="js/sweetalert.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready( function(){
    
      var id_fabbricato=<?=$id_fabbricato;?>;
      var cartella ="E"+id_fabbricato+"/Archivio Dati Digitalizzati/";
     
      var fl_avanzato=<?=$fl_avanzato;?>;
       var livello=<?=$livello;?>;
   
      if (fl_avanzato==1 && livello==2) {
          
      }        
      else {
        $("#but_rinomina_cartella_d").hide();
        $("#but_elimina_cartella_d").hide();
        $("#but_elimina_file_d").hide();
      } 
      
      $("#hid_directory_selezionata_d").val('/public/gestlavori/ArchDocEdifici/'+cartella);
      $("#but_upload_file_d").hide();
      $("#lab_descrizione_file_d").hide();
      $("#ta_descrizione_file_d").hide();                  

           
       $("#but_nuova_cartella_d").click(function(e){
           var dir = $("#hid_directory_selezionata_d").val();
           if (dir==""){
               dir='/public/gestlavori/ArchDocEdifici/'+cartella;
           }
           $("#p_nuova_cartella_d").html("Crea una nuova cartella in \n"+dir); 
           
           $("#dialog_nuova_cartella_d").modal("show");
       }); 
       
       $("#but_rinomina_cartella_d").click(function(e){
           var dir = $("#lab_directory_selezionata_d").text();
           if (dir==""){
               $('#alert_select_cartella_d').modal("show");               
               
           }
           else {
           $("#p_rinomina_cartella_d").html("Cartella da rinominare: "+dir);
           $("#inp_rinomina_cartella_d").val(dir.trim());
           }
           
       }); 
       
       $("#but_elimina_cartella_d").click(function(e){
           var dir = $("#lab_directory_selezionata_d").text();
           if (dir==""){
               $('#alert_select_cartella_d').modal("show");               
               return false;
           }
           else {
            dir = $("#hid_directory_selezionata_d").val();   
            var bValid = dir!="";

            if ( bValid ) {
                
                     swal({
                            title: "Eliminazione cartella",
                            text: $("#lab_directory_selezionata_d").text()+"\nTutti i files al suo interno verranno eliminati definitivamente.\nVuoi proseguire?",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Elimina",
                            cancelButtonText: "Chiudi",
                            closeOnConfirm: true,
                            closeOnCancel: true
                          },
                          function(isConfirm){
                            if (isConfirm) {
                                
                                $.ajax({
                                        type: "post",
                                        url: "actions/submit_elimina_cartella_edificio.php",                                                 
                                        data: "id_fabbricato="+id_fabbricato+"&dir="+dir,
                                        success: function(msg) { 
//                                            alert(msg);
                                            if (msg==1) {
                                                $('#alert_success_d').modal("show"); 
                                                
                                                $("#hid_directory_selezionata_d").val('/public/gestlavori/ArchDocEdifici/'+cartella);
                                                loadFileTree();
                                            }
                                            if (msg.indexOf("Warning") !== -1) {
                                                $('#alert_direcoty_notempty_d').modal("show");
                                            } 
                                            if (msg==2) {
                                                $("#alert_not_directory_d").modal("show");
                                            }
                                        },
                                        error: function() {
//                                            alert ("error");
                                        }
                                    });                               
                            } 
                          });                                                             
                }
            }           
       }); 
       
       
       $("#but_reset_d").click(function(){
            $("#lab_directory_selezionata_d").text("");
            $("#hid_directory_selezionata_d").attr("value",'/public/gestlavori/ArchDocEdifici/'+cartella); 
            
             loadFileTree();
       });
       
       
       
       //****** MODAL *************
       //**************************
       
       $('#dialog_nuova_cartella_d').modal({
            show: false
       });
       
       $('#dialog_rinomina_cartella_d').modal({
            show: false
       });
            
       //***     
       $("#but_crea_cartella_d").click(function(e) {
          
           var dir = $("#hid_directory_selezionata_d").val();
           var new_dir = $("#inp_nuova_cartella_d").val();
         
           var bValid = dir!="" && new_dir!="";
           
           if ( bValid ) { 
                $.ajax({
                    type: "post",
                    url: "actions/submit_nuova_cartella.php",                                                 
                    data: "dir="+dir+"&new_dir="+new_dir,
                    success: function(msg) { 
//                        alert(msg);
                        $('#dialog_nuova_cartella_d').modal('hide');
                        if (msg==1) {
                            $('#alert_success_d').modal("show");
                            
                            $("#inp_nuova_cartella_d").val("");
                           
                            $("#hid_directory_selezionata_d").val('/public/gestlavori/ArchDocEdifici/'+cartella);
                            loadFileTree();
                        }
                        if (msg==0) {
                            $('#alert_errore_cartella_d').modal("show");
                        }
                        if (msg==2) {
                            $('#alert_gia_presente_d').modal("show");
                        }
                    },
                    error: function() {
//                        alert ("error");
                    }
                });                                          
            }
       });
       
       $("#but_rinomina_cartella2_d").click(function(e){
           
           var dir = $("#hid_directory_selezionata_d").val();
           var new_dir2 = $("#inp_rinomina_cartella_d").val();
           
           var dire2 = dir.substr(0,dir.length-1);
           var dir_idx = dire2.lastIndexOf("/");            
           var new_dir = dire2.substr(0,dir_idx)+"/"+new_dir2+"/";                      
                                        
           var bValid = dir!="" && new_dir!="";
           
           
           
           if ( bValid ) {
               
                $.ajax({
                    type: "post",
                    url: "actions/submit_rinomina_cartella.php",                                                 
                    data: "old_dir="+escape(dir)+"&new_dir="+new_dir,
                    success: function(msg) { 
//                        alert(msg);
                        if (msg==1) {
                            $('#alert_success_d').modal("show");
                            $('#dialog_rinomina_cartella_d').modal('hide');
                            $("#inp_rinomina_cartella_d").val("");
                           
                            $("#hid_directory_selezionata_d").val('/public/gestlavori/ArchDocEdifici/'+cartella);
                           loadFileTree();
                        }
                        if (msg==0) {
                            $('#alert_errore_cartella_d').modal("show");
                        }
                        if (msg==2) {
                            $('#alert_gia_presente_d').modal("show");
                        }
                    },
                    error: function() {
//                        alert ("error");
                    }
                });                                          
            }
       });
       
       
      $('#file_upload_d').uploadify({
        'auto'     : false,
        'buttonText' : 'Sfoglia...', 
        'checkExisting' : 'uploadify/check-exists.php',
        'swf'      : 'uploadify/uploadify.swf',
        'uploader' : 'uploadify/uploadify.php',
        'method'        : 'post',
//        'formData'      : { 'folder_path' : 'notSet'},
        'onCancel' : function(file) {
            var num_file = $("#hid_num_file_in_coda_d").val();
            num_file--;
            $("#hid_num_file_in_coda_d").val(num_file);
            if (num_file==0) {
                $("#but_upload_file_d").hide();
                $("#lab_descrizione_file_d").hide();
                $("#ta_descrizione_file_d").hide();
                $("#hid_num_file_in_coda_d").val(0);
            }
        },
        'onDialogClose'  : function(queueData) {            
              var num_file = $("#hid_num_file_in_coda_d").val();
              var new_num_file = num_file+queueData.filesQueued;
              $("#hid_num_file_in_coda_d").val(new_num_file);
        },
        'onSelect' : function(file) {
            var lab = $("#lab_directory_selezionata_d").text();
            if (lab!=""){
                $("#but_upload_file_d").show();
                $("#lab_descrizione_file_d").show();
                $("#ta_descrizione_file_d").show();
            }
            else {
                $('#alert_select_cartella_d').modal("show"); 
                $('#file_upload_d').uploadify('cancel');
                $("#but_upload_file_d").hide();
                $("#lab_descrizione_file_d").hide();
                $("#ta_descrizione_file_d").hide();
                
            }
        },
        'onUploadStart' : function(file) {
            
            var dir = $("#hid_directory_selezionata_d").val();             
            var dire2 = dir.substr(0,dir.length-1);
            
            $("#file_upload_d").uploadify('settings', 'formData',{ 'folder_path' : dire2});                                                                                                                        
        }, 
        'onUploadSuccess' : function(file, data, response) {
            var dir = $("#hid_directory_selezionata_d").val();                         
            var desc_file = $("#ta_descrizione_file_d").val();
            
             $.ajax({
                type: "post",
                url: "actions/submit_aggiorna_db_files.php",                                                 
                data: "dir="+dir+"&file="+file.name+"&desc_file="+desc_file+"&id_fabbricato="+id_fabbricato,
                success: function(msg) {
//                        alert(msg);
                    if (msg==1) {
                        loadFileTree();
                    }                        
                    else {
                        $('#alert_db_error_d').modal("show");               
                       
                    }

                },
                error: function() {
                    alert ("error");
                }
            });  
        },
        'onQueueComplete' : function(queueData) {
              $("#hid_num_file_in_coda_d").val(0);  
              $("#but_upload_file_d").hide();
              $("#lab_descrizione_file_d").hide();
              $("#ta_descrizione_file_d").hide();
              loadFileTree();
        },
        'onUploadError' : function(file, errorCode, errorMsg, errorString) {
//            alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
        }
     });
     
     $("#but_upload_file_d").click(function(e){
        
         var lab = $("#lab_directory_selezionata_d").text();
         if (lab!=""){             
            $('#file_upload_d').uploadify('upload','*');
         }
         else {
                $('#alert_select_cartella_d').modal("show");  
                $('#file_upload_d').uploadify('cancel');
                $("#but_upload_file_d").hide();
                $("#lab_descrizione_file_d").hide();
                $("#ta_descrizione_file_d").hide();              
            }
     });
     
     $("#but_apri_file_d").click(function(e){
        
         if ($("#lab_file_selezionato_d").text()!="") {
             openFile($("#hid_file_selezionato_d").val());
         }
         else {
             $('#alert_select_file_d').modal("show");
             
         }
     });
     
     $("#but_elimina_file_d").click(function(e){
//         e.preventDefault();
         if ($("#lab_file_selezionato_d").text()=="") {                     
             $('#alert_select_file_d').modal("show");
            
         }
         
     });
     
     $("#but_dettaglio_file_d").click(function(e){
//         e.preventDefault();
         if ($("#lab_file_selezionato_d").text()=="") {                     
             $('#alert_select_file_d').modal("show");             
         }
         else {
             recuperaDatiFile();
             $('#dialog_dettaglio_file_d').modal("show");
         }
         
     });
     
     $("#but_rinomina_file_d").click(function(e){
//         e.preventDefault();
         if ($("#lab_file_selezionato_d").text()=="") {                     
             $('#alert_select_file_d').modal("show");             
         }
         else {
             var file_solo =$("#lab_file_selezionato_d").text().trim();
              var dire2 =$("#hid_directory_selezionata_d").val();
              $("#p_rinomina_file_d").html("File da rinominare: "+file_solo);
              $("#hid_vecchio_file_d").val(file_solo);
              $("#hid_cartella_file_d").val(dire2);
             $('#dialog_rinomina_file_d').modal("show");
         }
         
     });
     
     $("#but_rinomina_file2_d").click(function(e){
         var file_solo =$("#hid_vecchio_file_d").val();
         var dire2 =$("#hid_cartella_file_d").val();
         var nuovo_file = $("#inp_rinomina_file_d").val();
         
         $.ajax({
            type: "post",
            url: "actions/submit_rinomina_file.php",                                                 
            data: "dir="+dire2+"&file="+file_solo+"&nuovo_file="+nuovo_file,
            success: function(msg) { 
//                alert(msg);
                $("#dialog_rinomina_file_d").modal('hide');
                $('#alert_success_d').modal("show");  
                $("#lab_file_selezionato_d").html("");
                $("#hid_file_selezionato_d").val("");
                $("#lab_descrizione_file_selezionato_d").html("");
                loadFileTree();
            },
            error: function() {
//                alert ("error");
            }
        });
      });
      
     $("#but_elimina_file2_d").click(function(e){
         var file_solo =$("#lab_file_selezionato_d").text().trim();
         var dire2 =$("#hid_directory_selezionata_d").val();
        
         $.ajax({
            type: "post",
            url: "actions/submit_elimina_file.php",                                                 
            data: "dir="+dire2+"&file="+file_solo,
            success: function(msg) { 
//                alert(msg);
                $("#dialog_elimina_file_d").modal('hide');
                $('#alert_success_d').modal("show");  
                $("#lab_file_selezionato_d").html("");
                $("#hid_file_selezionato_d").val("");
                $("#lab_descrizione_file_selezionato_d").html("");
                loadFileTree();
            },
            error: function() {
                alert ("error");
            }
        });
     });           

        $("#but_salva_dettaglio_file_d").click(function(e){
        
         var file_solo =$("#lab_file_selezionato_d").text().trim();
         var dire2 =$("#hid_directory_selezionata_d").val();
    
        var ta_descrizione_file_dett_d=$("#ta_descrizione_file_dett_d").val();
        var ta_catalogazione_file_dett_d=$("#ta_catalogazione_file_dett_d").val();
        var data_scadenza_documento_d=$("#data_scadenza_documento_d").val(); 
        var fl_rinnovato=0;
        if ($("#chk_rinnovato_d").is(":checked")) fl_rinnovato=1;
               
        var ajax_data="";
        
        ajax_data={
            ta_descrizione_file_dett_d:ta_descrizione_file_dett_d,
            ta_catalogazione_file_dett_d:ta_catalogazione_file_dett_d,
            data_scadenza_documento_d:data_scadenza_documento_d,
            fl_rinnovato:fl_rinnovato,
            file_solo:file_solo,
            dire2:dire2
        };                
        
            $.ajax({
                type: "post",
                url: "actions/submit_salva_dati_file_edificio.php",                                                 
                data: ajax_data,
                success: function(msg) { 
    //              alert(msg); 
                    $("#dialog_dettaglio_file_d").modal("hide"); 
                    $('#alert_success_d').modal("show");
                    $("#lab_descrizione_file_selezionato_d").html(ta_descrizione_file_dett_d); 
                },
                error: function() {
                    alert ("error");
                }
            });                
     });
     
     $("#but_ricerca_file_d").click(function(e){
         var nome_file=$("#ricerca_file").val();
         if (nome_file!="") {
         $("#div_table_risultato_ricerca_d").load("tables/table_risultati_ricerca_d.php?nome_file="+encodeURIComponent(nome_file)+"&id_fabbricato="+id_fabbricato,function(){
             $("#dialog_ricerca_file_d").modal("show"); 
         });
        }  
     });
     
     $("#but_stampa_fascicolo_fabbricato").click(function(e){
        window.location="stampa_fascicolo_fabbricato.php?id_fabbricato=<?=$id_fabbricato;?>";
     });
     
     loadFileTree();
});

function loadFileTree() {

 var id_fabbricato=<?=$id_fabbricato;?>;
 var cartella ="E"+id_fabbricato+"/Archivio Dati Digitalizzati/";
 $('#div_filetree_d').empty();    
 
$('#div_filetree_d').fileTree({ 
    root:'/public/gestlavori/ArchDocEdifici/'+cartella,         
    script: 'jqueryFileTree/connectors/jqueryFileTree.php',
    multiFolder: true
    }, 
      function(file) { 
         var dir_idx = file.lastIndexOf("/");
         var dire2 = file.substr(0,dir_idx);
         var dir_idx2 = dire2.lastIndexOf("/");
         $("#lab_directory_selezionata_d").html("&nbsp;"+dire2.substr(dir_idx2+1,dire2.length));
         $("#hid_directory_selezionata_d").attr("value",dire2+"/");               

          var file_solo = file.substr(dir_idx+1,file.length).trim(); 

          $("#lab_file_selezionato_d").html("&nbsp;"+file_solo);
          $("#hid_file_selezionato_d").attr("value",file);
//                alert(dire2);
//                alert(file);
          //visualizzo la descrizione del file selezionato 
          $.ajax({
              type: "post",
              url: "actions/submit_descrizione_file.php",
              dataType: "json",
              data: "dir="+dire2+"/"+"&file="+file_solo,
              success: function(msg) {                           
                    $("#lab_descrizione_file_selezionato_d").html(msg[0]);                       
              },
              error: function() {
                  alert ("error");
              }
          }); 
      }, function(dire){
          var dire2 = dire.substr(0,dire.length-1);
          var dir_idx = dire2.lastIndexOf("/");
          $("#lab_directory_selezionata_d").html("&nbsp;"+dire2.substr(dir_idx+1,dire2.length));
          $("#hid_directory_selezionata_d").val(dire);

          $("#lab_file_selezionato_d").html("");
          $("#hid_file_selezionato_d").val("");
          $("#lab_descrizione_file_selezionato_d").html("");
      }); 
}
function openFile(file) {
        window.open(file); 
}
function recuperaDatiFile(){
    var file_solo =$("#lab_file_selezionato_d").text().trim();
    var dire2 =$("#hid_directory_selezionata_d").val();
         
    $.ajax({
        type: "post",
        url: "actions/submit_descrizione_file.php", 
        dataType: "json",
        data: "dir="+dire2+"&file="+file_solo,
        success: function(msg) {                           
            $("#ta_descrizione_file_dett_d").val(msg[0]); 
            $("#ta_catalogazione_file_dett_d").val(msg[2]);
            $("#data_scadenza_documento_d").val(msg[3]);
            $("#utente_ultima_modifica_d").val(msg[1]);
            $("#data_ultima_modifica_d").val(msg[4]);
            if (msg[5]==1) $("#chk_rinnovato_d").attr("checked",true);
        },
        error: function() {
            alert ("error");
        }
    });
}
</script>

<div class="span5">
    <fieldset >
        <legend>Archivio Dati Digitalizati</legend>  
        
        <div id="div_ricerca_files">
            <h5 style="font-weight: bold;">Ricerca</h5>           
            <input id="ricerca_file" value="" placeholder="Nome file"/> <button  id="but_ricerca_file_d" class="btn btn-mini btn-success" style="margin-left:30px;">Ricerca</button>
        </div>
        <br /><br />
        <div id="div_gestione_files">
        <label id="lab_directory_selezionata_d" style="font-size:9pt;font-weight: bold;border:1px solid;"></label>
        
        <button id="but_nuova_cartella_d" class="btn btn-mini btn-primary" style="margin-bottom:15px;">Nuova Cartella</button>
        <a href="#dialog_rinomina_cartella_d" id="but_rinomina_cartella_d" role="button" class="btn btn-mini btn-info" data-toggle="modal" style="margin-bottom:15px;">Rinomina Cartella</a>
        <a href="#dialog_elimina_cartella_d" id="but_elimina_cartella_d" role="button" class="btn btn-mini btn-danger" data-toggle="modal" style="margin-bottom:15px;">Elimina Cartella</a>
        <button  id="but_reset_d" class="btn btn-mini" style="margin-bottom:15px;margin-left:40px;">Reset</button>
        <label id="lab_file_selezionato_d" style="font-size:9pt;font-weight: bold;border:1px solid;"></label>   
        <label id="lab_descrizione_file_selezionato_d" style="font-size:9pt;font-weight: bold;border:1px solid;"></label>
        <a href="#lab_descrizione_file_selezionato_d" id="but_apri_file_d" role="button" class="btn btn-mini btn-primary" data-toggle="modal" >Apri File</a>
        <button  id="but_dettaglio_file_d" class="btn btn-mini btn-success" >Dettaglio File</button>
        <button  id="but_rinomina_file_d" class="btn btn-mini btn-info" >Rinomina File</button>
        <a href="#dialog_elimina_file_d" id="but_elimina_file_d" role="button" class="btn btn-mini btn-danger" data-toggle="modal" >Elimina File</a>
        <br /><br />
        <input type="hidden" id="hid_directory_selezionata_d" value=""/> 
        <input type="hidden" id="hid_file_selezionato_d" value=""/> 
        <input type="hidden" id="hid_num_file_in_coda_d" value="0"/> 
        <input type="file" name="file_upload_d" id="file_upload_d" />                 
        <br />
        <label id="lab_descrizione_file_d" class="control-label" for="ta_descrizione_file_d">Descrizione</label>     
        <textarea type="text" id="ta_descrizione_file_d" rows="3" style="width:95%;" class="input-large" value="" ></textarea>
        <button id="but_upload_file_d" class="btn btn-mini btn-success" type="button">Upload File</button>
        </div>
    </fieldset>
</div>
<div class="span5">
    <div id="div_filetree_d" style="margin-top:30px;overflow: auto;">
            
        </div>
</div>
    
<div id="dialog_nuova_cartella_d" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">        
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5>Nuova Cartella</h5>
    </div>
    <div class="modal-body">
    <p id="p_nuova_cartella_d"></p>
    Nuova cartella:<input type="text" id="inp_nuova_cartella_d" class="input-normal" value="" />
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Chiudi</a>
        <a href="#" id="but_crea_cartella_d" class="btn btn-success">Crea Cartella</a>
    </div>
</div> 
<div id="dialog_rinomina_cartella_d" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">        
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5>Rinomina Cartella</h5>
    </div>
    <div class="modal-body">
    <p id="p_rinomina_cartella_d"></p>
    <input type="hidden" id="hid_vecchia_cartella_d" value="" />
    Nuova cartella:<input type="text" id="inp_rinomina_cartella_d" class="input-normal" value="" />
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Chiudi</a>
        <a href="#" id="but_rinomina_cartella2_d" class="btn btn-success">Rinomina Cartella</a>
    </div>
</div>
<div id="dialog_rinomina_file_d" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">        
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5>Rinomina File</h5>
    </div>
    <div class="modal-body">
    <p id="p_rinomina_file_d"></p>
    <input type="hidden" id="hid_vecchio_file_d" value="" />
    <input type="hidden" id="hid_cartella_file_d" value="" />
    Nuovo file:<input type="text" id="inp_rinomina_file_d" class="input-normal" value="" />
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Chiudi</a>
        <a href="#" id="but_rinomina_file2_d" class="btn btn-success">Rinomina File</a>
    </div>
</div>
<div id="dialog_elimina_file_d" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">        
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5>Elimina File</h5>
    </div>
    <div class="modal-body">
    <p id="p_elimina_file_d">
        Vuoi eliminare il file selezionato?
    </p>        
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Chiudi</a>
        <a href="#" id="but_elimina_file2_d" class="btn btn-danger">Elimina</a>
    </div>
</div>
<div id="dialog_dettaglio_file_d" class="modal hide fade stop-scrolling" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-header">        
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5>Dettaglio File</h5>
    </div>
    <div class="modal-body">
        <table>
            <tr><td><label class="control-label" for="ta_descrizione_file_dett_d">Descrizione</label></td><td><textarea type="text" id="ta_descrizione_file_dett_d" rows="3" class="input-xxlarge" value=""></textarea></td></tr>
            <tr><td><label class="control-label" for="ta_catalogazione_file_dett_d">Catalogazione</label></td><td><textarea type="text" id="ta_catalogazione_file_dett_d" rows="3" class="input-xxlarge" value=""></textarea></td></tr>
            <tr><td><label class="control-label" for="data_scadenza_documento_d">Scadenza Documento</label></td><td><input id="data_scadenza_documento_d" value="" readonly />
                    <script>
                         $('#data_scadenza_documento_d').datepicker({
                            showOn: "button",
                            buttonImage: "images/calendar.gif",
                            buttonImageOnly: true,
                            changeMonth: true,
                            changeYear: true
                         },$.datepicker.regional['it']);  
                         
                        $.fn.modal.Constructor.prototype.enforceFocus = function() {};
                    </script>
                </td></tr>
            <tr><td><label class="control-label" for="chk_rinnovato_d">Rinnovato</label></td><td style="padding-bottom:4px;"><input type="checkbox" id="chk_rinnovato_d" value="" /></td></tr>
            <tr><td><label class="control-label" for="utente_ultima_modifica_d">Utente Ultima Modifica</label></td><td><input id="utente_ultima_modifica_d" value="" readonly /></td></tr>
            <tr><td><label class="control-label" for="data_ultima_modifica_d">Data Ultima Modifica</label></td><td><input id="data_ultima_modifica_d" value="" readonly /></td></tr>
        </table>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Chiudi</a>
        <a href="#" id="but_salva_dettaglio_file_d" class="btn btn-success">Salva</a>
    </div>
</div>
<div class="alert alert-success modal hide fade" id="alert_success_d">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
   <span>Operazione avvenuta con successo!</span>
</div>
<div class="alert modal hide fade" id="alert_gia_presente_d">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
   La cartella è già presente.
</div>
<div class="alert alert-error modal hide fade" id="alert_errore_cartella_d">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
   Errore di creazione cartella.
</div>
<div class="alert alert-info modal hide fade" id="alert_select_cartella_d">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
   Selezionare una cartella.
</div>
<div class="alert modal hide fade" id="alert_not_directory_d">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
   Non hai selezionato una directory.
</div>
<div class="alert modal hide fade" id="alert_direcoty_notempty_d">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
   La directory non è vuota.
</div>
<div class="alert alert-error modal hide fade" id="alert_db_error_d">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
   Errore aggiornamento DataBase.
</div>
<div class="alert alert-info modal hide fade" id="alert_select_file_d">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
   Selezionare un file.
</div>
<div id="dialog_ricerca_file_d" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">        
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h5>Risultato ricerca</h5>
    </div>
    <div class="modal-body" id="div_table_risultato_ricerca_d">
            
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Chiudi</a>        
    </div>
</div>

