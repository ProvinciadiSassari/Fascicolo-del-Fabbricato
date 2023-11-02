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

if (isset($_GET["id_incarico"])) {
    $id_incarico=$_GET["id_incarico"];     
}
else $id_incarico=0;

$query = "SELECT                      
            i.IDProfess_incaricato,           
            i.Numero_delibera,
            i.Data_delibera,
            i.Numero_convenzione,
            i.Data_convenzione,
            i.Note
        FROM incaricati i
        WHERE i.IDLavoro=$id_lavoro and i.IDIncarico=$id_incarico";

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}
if ($row = mysql_fetch_assoc($result)){    
    $IDProfess_incaricato=$row["IDProfess_incaricato"];   
    $Data_delibera=$utility->convertDateToHTML($row["Data_delibera"]);
    $Numero_delibera=$row["Numero_delibera"];
    $Numero_convenzione=$row["Numero_convenzione"];
    $Data_convenzione=$utility->convertDateToHTML($row["Data_convenzione"]);   
    $Note=$row["Note"];
}

$fl_avanzato=$_SESSION['avanzato'];
$livello=$_SESSION['idlevel'];

?>

<script type="text/javascript">
$(document).ready( function(){
        var id_incarico=<?=$id_incarico;?>;
        var id_lavoro=<?=$id_lavoro;?>;
        var fl_avanzato=<?=$fl_avanzato;?>;
        var id_livello=<?=$_SESSION['idlevel'];?>;

     
      if (id_livello==7) {
          $("#but_salva_dati_incarico").hide();
          
      }
      
      if (fl_avanzato==0)
         $("#but_salva_dati_incarico").hide();
        
     
      $("#but_salva_dati_incarico").click(function(e) {
      
        var sel_incaricato=$("#sel_incaricato").val();
        var inp_num_convenzione=$("#inp_num_convenzione").val();
        var inp_data_convenzione=$("#inp_d_convenzione").val();
        var ta_note_incarichi=$("#ta_note_incarichi").val();
        var inp_num_delibera=$("#inp_num_delibera").val();
        var inp_data_delibera=$("#inp_d_delibera").val();
              
        var ajax_data="";
        
        ajax_data={
            id_incarico:id_incarico,
            id_lavoro:id_lavoro,
            sel_incaricato:sel_incaricato,
            inp_num_convenzione:inp_num_convenzione,
            inp_data_convenzione:inp_data_convenzione,
            ta_note_incarichi:ta_note_incarichi,
            inp_num_delibera:inp_num_delibera,                            
            inp_data_delibera:inp_data_delibera
        };
        
        $.ajax({
            type: "post",
            url: "actions/submit_salva_dati_incarico.php",                                                 
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

});

</script>
<div class="container-fluid">   
<div class="form-group row">
    <label class="control-label col-lg-1" for="sel_incaricato">Incaricato</label>     
    <div class="col-lg-8">    
      <select id="sel_incaricato" class="form-control" name="sel_incaricato">
       <?php
        $query="SELECT IDProfessionista,Professionista FROM professionisti";
        $result = mysql_query($query);
        if (!$result){
                die ("Could not query the database: <br />". mysql_error());
        }
        while ($row = mysql_fetch_assoc($result)){
            $id=$row["IDProfessionista"];
            $val=$row["Professionista"];
            echo "<option value='$id'";
            if ($id==$IDProfess_incaricato) echo " selected";
            echo ">";
            echo $val;
            echo "</option>";                        
        }                    
       ?>   
      </select>
  </div>              
</div>
<div class="form-group row">
        <label class="control-label col-lg-2" for="inp_num_convenzione" style="max-width: 130px;">N. Convenzione</label>     
        <div class="col-lg-2">  
             <input type="text" id="inp_num_convenzione" class="form-control" value="<?=$Numero_convenzione?>" /> 
         </div>
         <td>
             <label class="control-label" for="inp_data_convenzione">Data Convenzione</label>     
         <div class="col-lg-3">    
        <div class="input-group date" id="inp_data_convenzione" data-target-input="nearest">
                 <input type="text" class="form-control datetimepicker-input" data-target="#inp_data_convenzione" id="inp_d_convenzione" value="<?=$Data_convenzione?>"/>
                 <div class="input-group-append" data-target="#inp_data_convenzione" data-toggle="datetimepicker">
                     <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                 </div>
             </div>
             <script type="text/javascript">
             $(function () {
                 $('#inp_data_convenzione').datetimepicker({
                     locale: 'it',
                     format: 'L'
                 });
             });
         </script>                  
     </div>              
</div>         
<div class="form-group row">
    <label class="control-label col-lg-1" for="ta_note_incarichi">Note</label>     
    <div class="col-lg-10"> 
    <textarea type="text" id="ta_note_incarichi" rows="3" class="form-control" value="" ><?=($Note);?></textarea> 
    </div>
</div>
<div class="form-group row">
    <h5>Determinazione/Delibera</h5>
</div>    
<div class="form-group  row">
    <label class="control-label col-lg-1" for="inp_num_delibera" style="max-width: 30px;">N.</label>     
              <div class="col-lg-3">    
                  <input type="text" id="inp_num_delibera" class="form-control" value="<?=($Numero_delibera);?>" /> 
              </div>            
                  <label class="control-label col-lg-1" for="inp_data_delibera" style="max-width: 50px;">del</label>     
             <div class="col-lg-3">    
            <div class="input-group date" id="inp_data_delibera" data-target-input="nearest">
                     <input type="text" class="form-control datetimepicker-input" data-target="#inp_data_delibera" id="inp_d_delibera" value="<?=$Data_convenzione?>"/>
                     <div class="input-group-append" data-target="#inp_data_delibera" data-toggle="datetimepicker">
                         <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                     </div>
                 </div>
                 <script type="text/javascript">
                 $(function () {
                     $('#inp_data_delibera').datetimepicker({
                         locale: 'it',
                         format: 'L'
                     });
                 });
             </script>                  
         </div>           
          </div> 
</div>
<hr />
<button id="but_salva_dati_incarico" class="btn btn-primary" type="button">Salva</button> 
