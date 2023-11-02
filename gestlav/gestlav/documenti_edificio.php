<?php
session_start();
require_once('conf.inc.php');
include('conv.php');

$utility = new Utility();
$utility->connetti();



if (isset($_GET["id_fabbricato"])) {
    $id_fabbricato=$_GET["id_fabbricato"];     
}
else $id_fabbricato=0;

$fl_avanzato=$_SESSION['avanzato'];
$livello=$_SESSION['idlevel'];
$cartella='../../public/gestlavori/ArchDocEdifici/E'.$id_fabbricato."/";

$_SESSION['cartella']=$cartella;



?>
<style>
    .elfinder-upload-dropbox {
        display: none;
    }
    .elfinder-upload-dialog-or {
        display: none;
    }
</style>
<script type="text/javascript">
$(document).ready( function(){
     
       
      var id_fabbricato=<?=$id_fabbricato;?>;
      var cartella ="E"+id_fabbricato+"/";
      var fl_avanzato=<?=$fl_avanzato;?>;
      var livello=<?=$livello;?>; 
      
      var id_livello=<?=$_SESSION['idlevel'];?>; 
      
      if (id_livello==7) {
          $("#but_salva_dettaglio_file").hide();
      }

      elFinder.prototype.commands.info= function() {
          var self  = this,
          fm = self.fm;
          var ret=0;
                this.exec = function(hashes) {
                      var file = this.files(hashes);
                    var hash = file[0].hash;                   
                    var url = fm.url(hash);
                    url=url.replace("/gestlav/php/../..", "");
                    url=decodeURI(url);
                     var n = url.includes("php/connector.minimal.php");
                    if (n==true) {
                        return false;
                    }
                    
                     $.ajax({
                        type: "post",
                        url: "actions/submit_descrizione_file.php", 
                        dataType: "json",
                        data: "dir="+url+"&id_fabbricato="+id_fabbricato,
                        success: function(msg) {   
//                          alert(url);
                            if (msg[0]!=-1) {
                               
                                $("#ta_descrizione_file_dett").val(msg[0]); 
                                $("#ta_catalogazione_file_dett").val(msg[2]);
                                $("#data_scadenza_documento").val(msg[3]);
                                $("#utente_ultima_modifica").val(msg[1]);
                                $("#data_ultima_modifica").val(msg[4]);
                                if (msg[5]==1) {
                                    $("#chk_rinnovato").prop("checked",true);
                                }
                                else {
                                     $("#chk_rinnovato").prop("checked",false);
                                }
                                var dir_idx = url.lastIndexOf("/");      
                                var file_solo = url.substr(dir_idx+1,url.length).trim(); 
                                $("#hid_url_file").val(url); 
                                $("#hid_name_file").val(file_solo); 
                                $("#hid_tipo_operazione_desc_file").val(msg[6]); 
                                $("#dialog_dettaglio_file").modal("show");
                            }
                        },
                        error: function() {
//                                            alert ("error");
                        }
                    });
                };
                this.getstate = function() {
                      //return 0 to enable, -1 to disable icon access                      
                      return ret;
                };
      };
      
   if (id_livello==7) {
     var elf = $('#elfinder').elfinder({
            cssAutoLoad : false,    
            url : 'php/connector.minimal_3.php',
            read:true,
            write:false,
            locked: true,
            lang : 'it',
            commands : null,
            contextmenu : null,
            handlers: null,
            disabled : ['extract', 'archive', 'mkdir']

    }).elfinder('instance');
   }   
   else  {
    var elf = $('#elfinder').elfinder({
            cssAutoLoad : false,    
            url : 'php/connector.minimal.php',
            lang : 'it',
            commands : [
                    'open', 'reload', 'home', 'up', 'back', 'forward', 'getfile', 'quicklook', 
                    'download', 'rm', 'duplicate', 'rename', 'mkdir', 'mkfile', 'upload', 'copy', 
                    'cut', 'paste', 'edit', 'extract', 'archive', 'search', 'info', 'view', 'help', 'resize', 'sort', 'netmount'
            ],
            contextmenu : {
                    // navbarfolder menu
                    navbar : ['open', '|', 'copy', 'cut', 'paste', 'duplicate', '|', 'rm', '|', 'info'],
                    // current directory menu
                    cwd    : ['reload', 'back', '|', 'upload', 'mkdir', 'mkfile', 'paste', '|', 'sort', '|', 'info'],
                    // current directory file menu
                    files  : ['getfile', '|', 'quicklook', '|', 'download', '|', 'copy', 'cut', 'paste', 'duplicate', '|', 'rm', '|', 'edit', 'rename', 'resize', '|', 'archive', 'extract', '|', 'info']
            },
            
            handlers: {
                select: function (event, elfinderInstance) {
                    if (event.data.selected.length == 1) {

                        var hash = event.data.selected[0]; 
                        var url = elfinderInstance.url(hash);
                        url=url.replace("/gestlav/php/../..", "");
                        url=decodeURI(url)+"/";        
                        $("#hid_url_solo").val(url);
                    }
                }
            }

    }).elfinder('instance');
    }
    
    elf.upload = function(files) {
        var hasError;
        var names= new Array();

        if (hasError) {
            elf.error('upload error');
            return $.Deferred().reject();
        } else {
            
            $.each(files.input.files, function (key, file) {
                names.push(file.name);
            });    
            
            
                var dire2 =$("#hid_url_solo").val(); 
               
               var ajax_data={
                   
                   dire2:dire2,
                   id_fabbricato:id_fabbricato,
                   names:names
               };                

                   $.ajax({
                       type: "post",
                       url: "actions/submit_salva_dati_file_upload_edificio.php",                                                 
                       data: ajax_data,
                       async: false,
                       success: function(msg) { 
       //                  alert(msg); 
                           
                       },
                       error: function() {
//                           alert ("error");
                       }
                   }); 

            return elf.transport.upload(files, elf);
        }
    };
        
      if (fl_avanzato==1 && livello==2) {
          
      }        
      else {
        $("#but_rinomina_cartella").hide();
        $("#but_elimina_cartella").hide();
        $("#but_elimina_file").hide();
      } 
      
      $("#hid_directory_selezionata").attr("value",'/public/gestlavori/ArchDocEdifici/'+cartella);
      $("#but_upload_file").hide();
      $("#lab_descrizione_file").hide();
      $("#ta_descrizione_file").hide();                  

        $("#but_salva_dettaglio_file").click(function(e){
         e.preventDefault();                 
         var dire2 =$("#hid_url_file").val(); 
         var file_solo =$("#hid_name_file").val();
        var ta_descrizione_file_dett=$("#ta_descrizione_file_dett").val();
        var ta_catalogazione_file_dett=$("#ta_catalogazione_file_dett").val();
        var data_scadenza_documento=$("#data_scadenza_documento").val(); 
        var fl_rinnovato=0;
        if ($("#chk_rinnovato").is(":checked")) fl_rinnovato=1;
        var tipo_operazione=$("#hid_tipo_operazione_desc_file").val(); 
               
        if (ta_descrizione_file_dett=="") {
            swal(
                'Informazione',
                'La Descrizione del documento è obbligatoria.',
                'warning'
              ); 
      
              return false;
        }
        
        var ajax_data={
            ta_descrizione_file_dett:ta_descrizione_file_dett,
            ta_catalogazione_file_dett:ta_catalogazione_file_dett,
            data_scadenza_documento:data_scadenza_documento,
            fl_rinnovato:fl_rinnovato,           
            dire2:dire2,
            id_fabbricato:id_fabbricato,
            file_solo:file_solo,
            tipo_operazione:tipo_operazione
        };                
        
            $.ajax({
                type: "post",
                url: "actions/submit_salva_dati_file_edificio.php",                                                 
                data: ajax_data,
                success: function(msg) { 
//                  alert(msg); 
                    $("#dialog_dettaglio_file").modal("hide"); 
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
<div class="container-fluid" id="" > 
<div class="form-group row " id="" >     
<div class="col-lg-12">     
        <h4>Documenti Fabbricato</h4>        
</div>
</div> 
<div class="form-group row " id="" >     
<div class="col-lg-12" id="elfinder">     
             
</div>
</div> 


<div id="dialog_dettaglio_file" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
    <div class="modal-header">                
        <h5>Dettaglio File</h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <input type="hidden" id="hid_url_solo" value=""/>
        <input type="hidden" id="hid_url_file" value=""/>
        <input type="hidden" id="hid_name_file" value=""/>
        <input type="hidden" id="hid_tipo_operazione_desc_file" value=""/>
        <div class="container-fluid">
            <div class="form-group row">
                <label for="ta_descrizione_file_dett" class="col-lg-2 col-form-label" style="">Descrizione</label>
                <div class="col-lg-10">
                    <textarea  id="ta_descrizione_file_dett" class="form-control" value=""></textarea>
                </div>     
            </div>
            <div class="form-group row">
                <label for="ta_catalogazione_file_dett" class="col-lg-2 col-form-label" style="">Catalogazione</label>
                <div class="col-lg-10">
                    <textarea  id="ta_catalogazione_file_dett" class="form-control" value=""></textarea>
                </div>     
            </div>
            <div class="form-group row">
                <label for="d_scadenza_documento" class="col-form-label col-lg-2" style="">Scadenza documento</label>
                <div class="col-lg-4" style="">
                <div class="input-group date" id="d_scadenza_documento" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#d_scadenza_documento" id="data_scadenza_documento" value=""/>
                            <div class="input-group-append" data-target="#d_scadenza_documento" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                        <script type="text/javascript">
                        $(function () {
                            $('#d_scadenza_documento').datetimepicker({
                                locale: 'it',
                                format: 'L'
                            });
                        });
                    </script>
                </div>
                 <div class="custom-control custom-checkbox " style="margin-left:20px;margin-top: 5px;">           
                    <input type="checkbox" class="custom-control-input" id="chk_rinnovato" style="">
                     <label class="custom-control-label" for="chk_rinnovato" style="color: green;margin-top: 5px;">Rinnovato</label>  
                 </div>
            </div> 
            <div class="form-group row">
                <label for="utente_ultima_modifica" class="col-lg-3 col-form-label" style="">Utente Ultima Modifica</label>
                <div class="col-lg-3">
                    <input  id="utente_ultima_modifica" class="form-control" value="" readonly/>
                </div> 
                <label for="utente_ultima_modifica" class="col-lg-3 col-form-label" style="">Data Ultima Modifica</label>
                <div class="col-lg-3">
                    <input  id="data_ultima_modifica" class="form-control" value="" readonly/>
                </div>
            </div>
        </div>

    </div>
    <div class="modal-footer  justify-content-between">
       
        <button id="but_salva_dettaglio_file" class="btn btn-success">Salva</button>
         <button class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
    </div>
</div>
</div>
</div>
</div>

