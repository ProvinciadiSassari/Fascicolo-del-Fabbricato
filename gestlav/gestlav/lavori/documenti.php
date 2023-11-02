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
$cartella='../../public/gestlavori/Archivio Lavori/L'.$id_lavoro."/";


$_SESSION['cartella_lavoro']=$cartella;

?>
<style>
    .control-label {
        margin-left:10px;
        font-size:9pt;
    }
</style>
<script type="text/javascript">
$(document).ready( function(){
    
      var id_fabbricato=<?=$id_fabbricato;?>;
      var id_lavoro=<?=$id_lavoro;?>; 
      var id_livello=<?=$_SESSION['idlevel'];?>;
      
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
                    
                     var n = url.includes("php/connector.minimal_2.php");
                    if (n==true) {
                        return false;
                    }
                    
                     $.ajax({
                        type: "post",
                        url: "actions/submit_descrizione_file_lavori.php", 
                        dataType: "json",
                        data: "dir="+url+"&id_fabbricato="+id_fabbricato,
                        success: function(msg) {   
//                          alert(msg);
                            if (msg[0]!=-1) {
                               
                                $("#ta_descrizione_file_dett2").val(msg[0]); 
                                $("#ta_catalogazione_file_dett2").val(msg[2]);
                                $("#data_scadenza_documento2").val(msg[3]);
                                $("#utente_ultima_modifica2").val(msg[1]);
                                $("#data_ultima_modifica2").val(msg[4]);
                                if (msg[5]==1) {
                                    $("#chk_rinnovato2").prop("checked",true);
                                }
                                else {
                                     $("#chk_rinnovato2").prop("checked",false);
                                }
                                var dir_idx = url.lastIndexOf("/");      
                                var file_solo = url.substr(dir_idx+1,url.length).trim(); 
                                $("#hid_url_file2").val(url); 
                                $("#hid_name_file2").val(file_solo); 
                                $("#hid_tipo_operazione_desc_file2").val(msg[6]); 
                                $("#dialog_dettaglio_file2").modal("show");
                            }
                            

                        },
                        error: function() {
//                            alert ("error s");
                        }
                    });
                };
                this.getstate = function() {
                      //return 0 to enable, -1 to disable icon access                      
                      return ret;
                };
      };
      
      if (id_livello==7) {
          
          var elf2 = $('#2_elfinder').elfinder({
                  cssAutoLoad : false,    
                  url : 'php/connector.minimal_2.php',
                  read:true,
                  write:true,
                  locked: true,
                  lang : 'it',
                    commands : null,
                    contextmenu : null,
                    handlers: null

          }).elfinder('instance');
      }
      else {
    
            var elf2 = $('#2_elfinder').elfinder({
                  cssAutoLoad : false,    
                  url : 'php/connector.minimal_2.php',
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
                  }

          }).elfinder('instance');
      }
    
    $("#but_salva_dettaglio_file2").click(function(e){
         e.preventDefault();                 
         var dire2 =$("#hid_url_file2").val(); 
         var file_solo =$("#hid_name_file2").val();
        var ta_descrizione_file_dett=$("#ta_descrizione_file_dett2").val();
        var ta_catalogazione_file_dett=$("#ta_catalogazione_file_dett2").val();
        var data_scadenza_documento=$("#data_scadenza_documento2").val(); 
        var fl_rinnovato=0;
        if ($("#chk_rinnovato2").is(":checked")) fl_rinnovato=1;
        var tipo_operazione=$("#hid_tipo_operazione_desc_file2").val(); 
               
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
            id_lavoro:id_lavoro,
            file_solo:file_solo,
            tipo_operazione:tipo_operazione
        };                
        
            $.ajax({
                type: "post",
                url: "actions/submit_salva_dati_file_lavoro.php",                                                 
                data: ajax_data,
                success: function(msg) { 
//                  alert(msg); 
                    $("#dialog_dettaglio_file2").modal("hide"); 
                    swal(
                        'Informazione',
                        'Operazione avventuta con successo.',
                        'success'
                      ); 
                    
                },
                error: function() {
//                    alert ("error");
                }
            });                
     });
                
});
</script>
<div class="container-fluid">
<div class="form-group row " id="" >     
<div class="col-lg-12">     
        <h4>Documenti Lavoro</h4>        
</div>
</div> 
<div class="form-group row " id="" >     
<div class="col-lg-12" id="2_elfinder">     
             
</div>
</div> 
</div>
<div id="dialog_dettaglio_file2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
    <div class="modal-header">                
        <h5>Dettaglio File</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <input type="hidden" id="hid_url_file2" value=""/>
        <input type="hidden" id="hid_name_file2" value=""/>
        <input type="hidden" id="hid_tipo_operazione_desc_file2" value=""/>
        <div class="container-fluid">
            <div class="form-group row">
            <label for="ta_descrizione_file_dett2" class="col-lg-2 col-form-label">Descrizione</label>
            <div class="col-lg-10">
            <textarea type="text" id="ta_descrizione_file_dett2" class="form-control" value=""></textarea>
            </div>
            <label for="ta_catalogazione_file_dett2" class="col-lg-2 col-form-label">Catalogazione</label>
            <div class="col-lg-10">
            <textarea type="text" id="ta_catalogazione_file_dett2" class="form-control" value=""></textarea>
            </div>
            </div>
            <div class="form-group row">
                <label for="d_scadenza_documento" class="col-lg-2 col-form-label " style="">Scadenza documento</label>
                <div class="col-lg-4" style="">
                <div class="input-group date" id="d_scadenza_documento2" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#d_scadenza_documento2" id="data_scadenza_documento2" value=""/>
                            <div class="input-group-append" data-target="#d_scadenza_documento2" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                        <script type="text/javascript">
                        $(function () {
                            $('#d_scadenza_documento2').datetimepicker({
                                locale: 'it',
                                format: 'L'
                            });
                        });
                    </script>
                </div>
                 <div class="custom-control custom-checkbox " style="margin-left:20px;margin-top: 5px;">           
                    <input type="checkbox" class="custom-control-input" id="chk_rinnovato2" style="">
                     <label class="custom-control-label" for="chk_rinnovato2" style="color: green;margin-top: 5px;">Rinnovato</label>  
                 </div>
            </div>
            <div class="form-group row">
                <label for="utente_ultima_modifica2" class="col-lg-3 col-form-label" style="">Utente Ultima Modifica</label>
                <div class="col-lg-3">
                    <input  id="utente_ultima_modifica2" class="form-control" value="" readonly/>
                </div> 
                <label for="utente_ultima_modifica2" class="col-lg-3 col-form-label" style="">Data Ultima Modifica</label>
                <div class="col-lg-3">
                    <input  id="data_ultima_modifica2" class="form-control" value="" readonly/>
                </div>
            </div>
        
        </div>    
    </div>
    <div class="modal-footer justify-content-between">        
        <button id="but_salva_dettaglio_file2" class="btn btn-success">Salva</button>
        <button class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
    </div>
</div>
</div>
</div>