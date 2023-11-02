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

<button id="but_nuovo_incarico" class="btn btn-warning btn-sm" type="button">Nuovo Incarico</button>
<br /><br />
<?php
$query = "SELECT i.IDIncarico,i.Incarico FROM incaricati c,incarichi i
                WHERE i.IDIncarico=c.IDIncarico AND c.IDLavoro=$id_lavoro
                order by i.IDIncarico";

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}
?>
<div class="container-fluid">
<div class="form-group row col-lg-12" id="" > 
    <ul class="nav nav-pills" id="tabs_dati_incarichi" role="tablist">  
        
<?php
$i=0;$array_incarichi="";$k=0;
while ($row = mysql_fetch_assoc($result)){
    
    $Incarico=$row["Incarico"];
    $IDIncarico=$row["IDIncarico"];
    $array_incarichi[$k]=$IDIncarico;
    
    echo "<li class='nav-item' >";
   
    
    echo "<a href='#div_$IDIncarico'  data-toggle='pill' class='nav-link ";
     if ($i==0) {
        echo " active'>";
        $i++;
    }
    else echo "'>";
   
    echo utf8_encode($Incarico);
    echo "</a></li>"; 
    
    $k++;
}
?>
</ul>
<div class="tab-content col-lg-12"  style="margin-top:20px;">     
<input type="hidden" id="hid_num_incarichi" value="<?=$k;?>" /> 
<?php
$query = "SELECT i.IDIncarico,i.Incarico FROM incaricati c,incarichi i
                WHERE i.IDIncarico=c.IDIncarico AND c.IDLavoro=$id_lavoro order by i.IDIncarico";

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}
$i=0;
while ($row = mysql_fetch_assoc($result)){
    
    $Incarico=$row["Incarico"];
    $IDIncarico=$row["IDIncarico"];
  
    echo "<div class='tab-pane fade";
    if ($i==0) {
        echo "  show active' ";
        $i++;
    }
    else echo "' ";
    echo " id='div_$IDIncarico' role='tabpanel' aria-labelledby='div_$IDIncarico' id='div_$IDIncarico'>";    
    echo "</div>";                         
}
?>  
</div>
</div>
</div>    
<script type="text/javascript">
$(document).ready( function() {
    
      var id_lavoro=<?=$id_lavoro;?>;
      var fl_avanzato=<?=$fl_avanzato;?>;
      var id_livello=<?=$_SESSION['idlevel'];?>;
     
      if (fl_avanzato==0)
         $("#but_nuovo_incarico").hide();
     
      if (id_livello==7) {
          $("#but_nuovo_incarico").hide();
          
      }
     
      var num_incarichi=0;
      if ($("#hid_num_incarichi").val()!=undefined && $("#hid_num_incarichi").val()!="") {
          num_incarichi=$("#hid_num_incarichi").val();
      }
//      alert(num_incarichi);
     var array_incarichi=0;

      if (num_incarichi>0) {
          array_incarichi=<?php if (!empty($array_incarichi)) { echo $array_incarichi[0];} else {echo "0";}?>;
      }
      
//       alert(array_incarichi);           
      if (num_incarichi>0 && array_incarichi>0) {
          $("#div_"+array_incarichi).load("lavori/dati_incarichi.php?id_lavoro="+id_lavoro+"&id_incarico="+array_incarichi);
      }
      
       $("#tabs_dati_incarichi a[data-toggle='pill']").on('show.bs.tab', function(e) {
            var  nuovo_tab2 = ""+e.target; 
            var  vecchio_tab2 = ""+e.relatedTarget; 
            vecchio_tab2 = vecchio_tab2.split("#");
            var vecchio_tab=vecchio_tab2[1].split("_");  
            nuovo_tab2 = nuovo_tab2.split("#");            
            var nuovo_tab =nuovo_tab2[1].split("_");  
            
            if (isNumeric(nuovo_tab[1])) {   
                $("#div_"+vecchio_tab[1]).empty();
                $("#div_"+nuovo_tab[1]).load("lavori/dati_incarichi.php?id_lavoro="+id_lavoro+"&id_incarico="+nuovo_tab[1]);
            }
        });

      $("#but_salva_nuovo_incarico").click(function(e) {
       
        var sel_incarico=$("#sel_incarico_d").val();
        var sel_incaricato=$("#sel_incaricato_d").val();
        var inp_num_convenzione=$("#inp_num_convenzione_d").val();
        var inp_data_convenzione=$("#inp_d_convenzione_d").val();
        var ta_note_incarichi=$("#ta_note_incarichi_d").val();
        var inp_num_delibera=$("#inp_num_delibera_d").val();
        var inp_data_delibera=$("#inp_d_delibera_d").val();
        if (sel_incarico==0 || sel_incaricato==0) {
            swal(
                    'Informazione',
                    'Incarico ed Incaricato sono campi obbligatori.',
                    'warning'
                  );
          return false;
        }
              
        var ajax_data="";
        
        ajax_data={            
            id_lavoro:id_lavoro,
            sel_incarico:sel_incarico,
            sel_incaricato:sel_incaricato,
            inp_num_convenzione:inp_num_convenzione,
            inp_data_convenzione:inp_data_convenzione,
            ta_note_incarichi:ta_note_incarichi,
            inp_num_delibera:inp_num_delibera,                            
            inp_data_delibera:inp_data_delibera
        };
        
        $.ajax({
            type: "post",
            url: "actions/submit_salva_nuovo_incarico.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);  
                
                if (msg==0) {                                  
                    swal(
                        'Informazione',
                        'Operazione avventuta con successo.',
                        'success'
                      );
                    $('#dialog_nuovo_incarico').modal('hide');
                    $('.modal-backdrop').remove();
                    $("#div_incarichi").load("lavori/incarichi.php?id_lavoro="+id_lavoro); 
                }
                else {
                    swal(
                        'Informazione',
                        'Il professionista incaricato è già presente.',
                        'warning'
                      );
                }
            },
            error: function() {
//                alert ("error");
            }
        });
        
     });  
        
      $("#but_nuovo_incarico").click(function(e){
        $("#sel_incarico_d").val(0);
         $("#sel_incaricato_d").val(0);
         $("#inp_num_convenzione_d").val("");
         $("#inp_d_convenzione_d").val("");
         $("#ta_note_incarichi_d").val("");
         $("#inp_num_delibera_d").val("");
         $("#inp_d_delibera_d").val(""); 
         $('#dialog_nuovo_incarico').modal('show');
      });  
});

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
</script>
<div id="dialog_nuovo_incarico" class="modal fade"  role="dialog">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
<div class="modal-header">                
        <h5>Nuovo Incarico</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
</div>   
<div class="modal-body">
<div class="container-fluid">   
<div class="form-group row">
    <label class="col-lg-1 col-form-label" for="sel_incarico_d">Incarico</label>     
    <div class="col-lg-7">  
      <select id="sel_incarico_d" class="form-control">
          <option value="0">- Seleziona -</option>
       <?php
        $query="SELECT IDIncarico,Incarico FROM incarichi where IDIncarico not in (select IDIncarico from incaricati where IDLavoro=$id_lavoro)";
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }
        while ($row = mysql_fetch_assoc($result)){
            $id=$row["IDIncarico"];
            $val=$row["Incarico"];
            echo "<option value='$id'>";
            echo $val;
            echo "</option>";                        
        }                    
       ?>   
      </select>
  </div>              
</div>  
<div class="form-group row">
    <label class="col-lg-1 col-form-label" for="sel_incaricato_d">Incaricato</label>     
    <div class="col-lg-10">     
      <select id="sel_incaricato_d" class="form-control" name="sel_incaricato">
          <option value="0">- Seleziona -</option>
       <?php
        $query="SELECT IDProfessionista,Professionista FROM professionisti";
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }
        while ($row = mysql_fetch_assoc($result)){
            $id=$row["IDProfessionista"];
            $val=$row["Professionista"];
            echo "<option value='$id'>";
            echo $val;
            echo "</option>";                        
        }                    
       ?>   
      </select>
  </div>              
</div>
 <div class="form-group row">
    <label class="col-lg-2 col-form-label" for="inp_num_convenzione_d">N. Convenzione</label>     
  <div class="col-lg-3">   
      <input type="text" id="inp_num_convenzione_d" class="form-control" value="" /> 
  </div>              
      <label class="col-lg-2 col-form-label" for="inp_data_convenzione_d">Data Convenzione</label>     
  <div class="col-lg-3">    
    <div class="input-group date" id="inp_data_convenzione_d" data-target-input="nearest">
             <input type="text" class="form-control datetimepicker-input" data-target="#inp_data_convenzione_d" id="inp_d_convenzione_d" value=""/>
             <div class="input-group-append" data-target="#inp_data_convenzione_d" data-toggle="datetimepicker">
                 <div class="input-group-text"><i class="fa fa-calendar"></i></div>
             </div>
         </div>
         <script type="text/javascript">
         $(function () {
             $('#inp_data_convenzione_d').datetimepicker({
                 locale: 'it',
                 format: 'L'
             });
         });
     </script>                  
 </div>               
</div>         
 <div class="form-group row">        
            <label class="col-lg-1 col-form-label" for="ta_note_incarichi_d">Note</label>     
            <div class="col-lg-10">
            <textarea type="text" id="ta_note_incarichi_d" rows="3" class="form-control" value="" ></textarea> 
            </div>
</div>
<div class="form-group row"> 
                    <h5>Determinazione/Delibera</h5>
</div>                   
<div class="form-group row">
                  <label class="col-lg-1 col-form-label" for="inp_num_delibera_d">N.</label>     
              <div class="col-lg-4">   
                  <input type="text" id="inp_num_delibera_d" class="form-control" value="" /> 
              </div>
                  <label class="col-lg-1 col-form-label" for="inp_data_delibera_d">del</label>     
              <div class="col-lg-3">    
            <div class="input-group date" id="inp_data_delibera_d" data-target-input="nearest">
                     <input type="text" class="form-control datetimepicker-input" data-target="#inp_data_delibera_d" id="inp_d_delibera_d" value=""/>
                     <div class="input-group-append" data-target="#inp_data_delibera_d" data-toggle="datetimepicker">
                         <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                     </div>
                 </div>
                 <script type="text/javascript">
                 $(function () {
                     $('#inp_data_delibera_d').datetimepicker({
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
     <button id='but_salva_nuovo_incarico' class='btn btn-success' > Salva</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Chiudi</button>          
 </div>  
</div>
</div>
</div>    
