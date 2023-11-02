<?php
session_start();
require_once('conf.inc.php');

$utility = new Utility();
$utility->connetti();



if (isset($_GET["id_fabbricato"])) {
    $id_fabbricato=$_GET["id_fabbricato"];     
}
else $id_fabbricato=0;

$fl_avanzato=$_SESSION['avanzato'];
?>
<style>
    #div_summernote_container table {
        /*width: 100%;*/
    }
</style>
<script type="text/javascript">
$(document).ready( function(){
    
       
      var id_fabbricato=<?=$id_fabbricato;?>;
      var fl_avanzato=<?=$fl_avanzato;?>;
      
      var id_livello=<?=$_SESSION["idlevel"];?>;
                           
      if (fl_avanzato==0) {
          $("#but_elimina_sottostruttura2").hide();
          $("#but_nuova_sottostruttura").hide();
      }   
      
      if (id_livello==7) {
          $("#but_elimina_sottostruttura2").hide();
          $("#but_nuova_sottostruttura").hide();
          $("#but_salva_descrizione_struttura").hide();
          $(".img_open_file").hide();
          
      }  
     
     $('#div_strutture').jstree({
        'core' : {
          'data' : {
            'url' : "views/view_struttura_edificio.php?id_edificio="+id_fabbricato,
            'data' : function (node) {
                    $("#ta_desc_struttura_edificio").summernote({
                        height: "auto",
                        lang: 'it-IT',
                        toolbar: [
                          // [groupName, [list of button]]
                          ['style', ['bold', 'italic', 'underline', 'clear']],  
                          ['fontname', ['fontname']],
                          ['fontsize', ['fontsize']],
                          ['color', ['color']],
                          ['para', ['ul', 'ol', 'paragraph']],
                          ['table', ['table']],    
                          ['insert', ['link', 'hr']],
                          ['view', ['fullscreen', 'codeview','undo', 'redo']]

                        ]
                    }); 
              return { 'id' : node.id };
            }
          }
        }
      });
      
      $('#div_strutture').on('loaded.jstree', function() {
            $('.jstree').jstree(true).select_node('13#48');
        });
  
      $('#div_strutture').on('changed.jstree', function (e, data) {
          
          
			
            if (data.selected.length) {
                
                var arr_ = data.instance.get_node(data.selected[0]).id.toString().split("#");

                var id_struttura=arr_[0];
                var id_sottostruttura=arr_[1];	
                var desc_sottostruttura=data.instance.get_node(data.selected[0]).text;   
                
               
               
                if (id_sottostruttura!=undefined && id_sottostruttura!=null) {
                    
                    $("#leg_descrizione_struttura").text(desc_sottostruttura);
                    
                    aggiornaDescrizione(id_fabbricato,id_sottostruttura);
                                                           
                    $("#hid_desc_sottostruttura").val(desc_sottostruttura);
                }
                else {
                    $("#leg_descrizione_struttura").text("");
                  
                    $("#hid_id_sottostruttura").val(0);
                    $("#hid_desc_sottostruttura").val("");
                }
            }
	}).jstree();

        $("#sel_strutture").change(function(){
            var id_struttura=$(this).val();
           $("#div_sottostrutture").load("views/view_select_sottostrutture.php?id_struttura="+id_struttura);
        });                
        
        $("#but_salva_sottostruttura").click(function(){
            var id_sottostruttura =$("#sel_sottostrutture").val();                       
            $.ajax({
                type: "post",
                url: "actions/submit_salva_nuova_sottostruttura.php",                                                 
                data: "id_edificio="+id_fabbricato+"&id_sottostruttura="+id_sottostruttura,
                success: function(msg) {    
                    
                    if (msg==1) {
                        $("#dialog_nuova_struttura").modal('hide');                       
                        $("#div_strutture").jstree("refresh");
                    }
                    else {
                        swal(
                            'Informazione',
                            'La sottostruttura scelta è già presente.',
                            'info'
                          );
                        $("#dialog_nuova_struttura").modal('hide');                         
                     }                                       
                },
                error: function() {
//                    alert ("error");
                }
            }); 
        }); 
        
        $("#but_elimina_sottostruttura2").click(function(e){
            var id_sottostruttura =$("#hid_id_sottostruttura").val();            
           
            if (id_sottostruttura=='0') {                
                swal(
                    'Informazione',
                    'Selezionare una sottostruttura.',
                    'info'
                  );
            } 
            else {
                        
            swal({
                    title: 'Eliminazione Sottostruttura',
                    html: "Vuoi eliminare la sottostruttura selezionata? <br />("+$("#hid_desc_sottostruttura").val()+")",
                    type: 'warning',
                    showCancelButton: true,
                    showLoaderOnConfirm: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                     cancelButtonText: 'Chiudi',
                    confirmButtonText: 'Si'
                }).then(function () {

                    swal({                   
                        type: 'info',
                        title: 'Elaborazione in corso...',
                        showConfirmButton: false,
                        onOpen: function () {
                            swal.showLoading();
                          }
                      });

                        $.ajax({
                            type: "post",
                            url: "actions/submit_elimina_sottostruttura.php",                                                 
                            data: "id_edificio="+id_fabbricato+"&id_sottostruttura="+id_sottostruttura,
                            success: function(msg) {    

                                if (msg==1) {                                    
                                   
                                    $("#hid_id_sottostruttura").val(0);
                                    $("#hid_desc_sottostruttura").val("");
                                    $("#div_strutture").jstree("refresh");                      
                                }                                  
                            },
                             complete: function(){
                                swal.close();
                            },
                            error: function() {
//                                alert ("error");
                            }
                        });  
                });
            }
        });                

     $("#but_stampa_fascicolo_fabbricato").click(function(e){
        window.location="stampa_fascicolo_fabbricato.php?id_fabbricato="+id_fabbricato;
     });
     
      $("#but_nuova_sottostruttura").click(function(e){
         
       $("#dialog_nuova_struttura").modal("show");
       
     });
     
     $("#but_salva_descrizione_struttura").click(function(){
            
            var id_sottostruttura =$("#hid_id_sottostruttura").val();             
            var desc=encodeURIComponent($("#ta_desc_struttura_edificio").summernote('code'));
//            var desc=encodeURIComponent($("#ta_desc_struttura_edificio").val());
//           swal({
//               title:"",
//               text: desc,
//               icon:"info",
//               width: "80%"
//           });
//            
//            return false;
            
            $.ajax({
                type: "post",
                url: "actions/submit_salva_descrizione_sottostruttura.php",                                                 
                data: "id_edificio="+id_fabbricato+"&id_sottostruttura="+id_sottostruttura+"&desc="+desc,
                success: function(msg) {  
//                    alert(msg); 
//                               swal({
//                        title:"",
//                        text: msg,
//                        icon:"info",
//                        width: "80%"
//                    });
                    swal("Informazione","Operazione avvenuta con successo.","success");
                },
                error: function() {
//                    alert ("error");
                }
            }); 
        
        });             

});

function aggiornaDescrizione(id_fabbricato,id_sottostruttura) {
//     alert("entrato"+id_fabbricato+" - "+id_sottostruttura);   
     
//     $("#ta_desc_struttura_edificio").summernote({
//            height: 400,
//            toolbar: [
//              // [groupName, [list of button]]
//              ['style', ['bold', 'italic', 'underline', 'clear']],             
//              ['fontsize', ['fontsize']],
//              ['color', ['color']],
//              ['para', ['ul', 'ol', 'paragraph']]
//            ]
//        });
//        
//    
//        
    $.ajax({
        type: "post",
        url: "views/view_descrizione_struttura.php",                                                 
        data: "id_edificio="+id_fabbricato+"&id_sottostruttura="+id_sottostruttura,
        success: function(msg) {  
    //                            alert(msg.toString()); 
//                jQuery.noConflict();    
                $("#ta_desc_struttura_edificio").summernote("code",msg.toString());
                $("#hid_id_sottostruttura").val(id_sottostruttura);

        },
        error: function() {
//                                alert ("error111");
        }
    });     
}

</script>

<div class="form-group row col-lg-3" > 
    <button id="but_stampa_fascicolo_fabbricato" class="btn btn-warning btn-sm" style="margin-bottom:15px;"> <i class="fa fa-print"></i> Stampa Fascicolo del Fabbricato</button> 
</div>
<div class="form-group row col-lg-12" id="" > 
    
<div class="col-lg-4">     
<fieldset>
        <legend>Struttura Edificio</legend>
        <div id="div_strutture" style="background-color: white;font-size: 8pt;">
            
        </div>
        <div id="div_buttons" style="margin-top:15px;">
            <button id="but_nuova_sottostruttura" class="btn btn-sm btn-success">Nuova Sottostruttura</button>           
            <button id="but_elimina_sottostruttura2" class="btn btn-sm btn-danger">Elimina Sottostruttura</button>
        </div>
</fieldset>
<input type="hidden" id="hid_id_sottostruttura" value="0" />  
<input type="hidden" id="hid_desc_sottostruttura" value="" /> 
</div>
     
<div class="col-lg-8">    
<div class="form-group row col-lg-12" >
        <h4 id="leg_descrizione_struttura"></h4>
        <div  class="col-lg-12" id="div_summernote_container">
            <textarea class="form-control" id="ta_desc_struttura_edificio" style="height: 400px;" value=""></textarea>            
        </div>
       
</div> 
<div class="form-group row col-lg-2" > 
        <button id="but_salva_descrizione_struttura" class="btn btn-primary" type="button">Salva</button>
</div>            
</div>
    
</div> 

<div id="dialog_nuova_struttura" class="modal fade"  role="dialog">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">               
        <h5 class="modal-title">Nuova Sottostruttura</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
    <p id="p_nuova_struttura">
        Struttura: <select id="sel_strutture" class="form-control">
            <?php
            $query="SELECT  id_struttura, desc_struttura FROM strutture";
            $result = mysql_query($query);
            if (!$result){
                    die ("Could not query the database: <br />". mysql_error());
            }
            while ($row = mysql_fetch_assoc($result)){
                $id=$row["id_struttura"];
                $val=$row["desc_struttura"];
                echo "<option value='$id'>$val</option>";
            }
            ?>
        </select>
        <br />
    <div id="div_sottostrutture">
        
    </div>
        
    </p>        
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal">Chiudi</button>
        <button id="but_salva_sottostruttura" class="btn btn-success">Salva</button>
    </div>
</div>
</div>
</div>

