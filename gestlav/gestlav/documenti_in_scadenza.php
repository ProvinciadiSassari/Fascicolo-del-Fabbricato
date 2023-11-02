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

?>

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {   
        
    var id_fabbricato=<?=$id_fabbricato;?>;
 
     
    $('#calendario_scadenze').fullCalendar({
            header: { center: 'month,agendaWeek' },
            showNonCurrentDates:false,
            displayEventTime: false,
            eventTextColor: "white",
            dayClick: function(date, jsEvent, view) {
               
            },
            eventClick: function(calEvent, jsEvent, view) {
                
                window.open(calEvent.percorso_completo);
            },
            loading: function() {
                var d = $('#calendario_scadenze').fullCalendar('getDate');        
                var mese = d.get('month') + 1;
                var anno = d.get('year'); 

            },
            
            events: "calendario_scadenze_documenti.php?id_fabbricato="+id_fabbricato
        }); 
        
        
     $(document).on("change","#id_mese",function(){
            var mese = $(this).val(); 
            var mese_c=mese;
            if (parseInt(mese)<10) {
                mese_c="0"+mese_c;
            }
            
            var anno = $("#anno_riferimento").val();
          
            var newDate = new Date(anno+"-"+mese_c+"-01");
            $('#calendario_scadenze').fullCalendar('gotoDate', newDate);
            
        });
        
        
     $(document).on("change","#anno_riferimento",function(){
            var mese = $("#id_mese").val(); 
            var mese_c=mese;
            if (parseInt(mese)<10) {
                mese_c="0"+mese_c;
            }
            var anno = $(this).val();
            var newDate = new Date(anno+"-"+mese_c+"-01");
            $('#calendario_scadenze').fullCalendar('gotoDate', newDate);
            
            
        });   
        
        $("#but_open_documenti_scadenza").click(function(){
            $("#div_table_scadenza_docs").load("tables/table_scadenze_documenti.php?id_fabbricato="+id_fabbricato,function(){

                if ($("#hid_num_doc_scadenza").val()>0) {   
                   $('#alert_documenti_scaduti').modal("show");
               }   
            });
        });
});
</script>
<table>
    <tr>
        <td style="padding-left:30px;">Fabbricati</td><td style="background-color: #fe760a;">&nbsp;&nbsp;&nbsp;</td>
        <td style="padding-left:30px;">Lavori</td><td style="background-color:red;">&nbsp;&nbsp;&nbsp;</td>
        <td style="padding-left:30px;">Rinnovato</td><td style="background-color: blue;">&nbsp;&nbsp;&nbsp;</td>
    </tr>
</table>
<br /><hr />
<div class="form-group row">
    <label class="control-label col-lg-1" style="max-width: 80px;">Mese</label>
                <div class="col-lg-1">
                 <select id="id_mese" class="form-control">                  
                  <?php
                    $query="select Id,mese FROM mesi ORDER BY Id ASC";
                    $result = mysql_query($query);
                      if (!$result){
                          die ("Could not query the database: <br />". mysql_error());
                      }
                      while ($row = mysql_fetch_array($result, MYSQLI_ASSOC)) {
                          $id=$row['Id'];
                          $val=$row['mese'];
                          
                          echo "<option value='$id' "; 
                          if ($id==date("n")) {
                                 echo "selected";
                             }
                          echo ">".($val)."</option>";
                      }
                  ?>
              </select>
                </div>
                <label class="control-label col-lg-1" style="max-width: 80px;">Anno</label>
                <div class="col-lg-1">
                 <select id="anno_riferimento" class="form-control">
                     
                     <?php
                      for ($a=2008;$a<=date("Y");$a++) {                            

                             echo "<option value='$a' ";
                             if ($a==date("Y")) {
                                 echo "selected";
                             }
                             echo ">".$a."</option>";
                         }
                     ?>
                 </select>
                </div>
                <div class="col-lg-2">
                    <button type="button" id="but_open_documenti_scadenza" class="btn btn-info btn-sm" >Visualizza Documenti in scadenza </button>
                </div>    
</div>
<hr />
<div id="calendario_scadenze" style="margin-top:20px;">  
    
</div> 

           
 

