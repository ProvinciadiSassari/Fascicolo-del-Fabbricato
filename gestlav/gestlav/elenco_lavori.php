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

?>

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {   
        
    var id_fabbricato=<?=$id_fabbricato;?>;
    var fl_avanzato=<?=$fl_avanzato;?>;
    var id_livello=<?=$_SESSION["idlevel"];?>;
    
   $(".numeric_2").inputmask('decimal', { digits: 2, enforceDigitsOnBlur: true, integerDigits: 9,positionCaretOnTab: false }); 
    if (fl_avanzato==0)
         $("#but_nuovo_lavoro").hide();
         
    $("#sel_lavoro_origine").val(0);

    $('#tab_lavori').DataTable({
        responsive: true,
        "aaSorting": [[ 0, "asc" ]],
        "bDestroy": true,
        "language": {
            "url": 'language/Italian.json'
        } 
    });
    
     if (id_livello==7) {
          $("#but_nuovo_lavoro").hide();
          
      }  


    $(document).on("click","#img_mod_lavori",function(evt){
        var id_lavoro=$(this).attr("alt");
//        window.open("dati_lavori.php?id_lavoro="+id_lavoro,"_blank");
        $("#div_lavori2").empty();
        $("#div_lavori2").load("dati_lavori.php?id_lavoro="+id_lavoro);
    });
    
    $("#but_nuovo_lavoro").click(function(e){  
        $("#sel_lavoro_origine").val(0);
            $("#sel_lavoro_origine").prop("disabled",true);
            $("#inp_disponibilita").prop("readonly",true);
            $("#inp_disponibilita").val("");
            $("#chk_complementare").prop("checked",false);
        $('#dialog_nuovo_lavoro_lav').modal('show');
        
    });
    
    $("#but_salva_nuovo_lavoro").click(function(e){
       
        
        var inp_codice_contratto=$("#inp_codice_contratto").val();
        var inp_data_contratto=$("#inp_d_contratto").val();
        var sel_categoria=$("#sel_categoria").val();
        var sel_tipologia=$("#sel_tipologia").val();
        var ta_descrizione=$("#ta_descrizione").val();
        var ta_note_lavoro=$("#ta_note_lavoro").val();
        var sel_responsabile=$("#sel_responsabile").val();
        var sel_istruttore=$("#sel_istruttore").val();
        var chk_complementare =0;
        var sel_lavoro_origine=0;
        var inp_disponibilita=0;
        if ($("#chk_complementare").is(':checked')) {
            chk_complementare=1;
            sel_lavoro_origine=$("#sel_lavoro_origine").val();
            inp_disponibilita=$("#inp_disponibilita").val();
        } 
        
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
            success: function(msg) { 
//                alert(msg);  
                 $('#dialog_nuovo_lavoro_lav').modal('hide'); 
                swal(
                        'Informazione',
                        'Operazione avventuta con successo.',
                        'success'
                      );
               
                $("#inp_codice_contratto").val("");
                $("#inp_data_contratto").val("");
                $("#sel_categoria").val(0);
                $("#sel_tipologia").val(0);
                $("#ta_descrizione").val("");
                $("#ta_note_lavoro").val("");
                $("#sel_responsabile").val(0);
                $("#sel_istruttore").val(0);
                $("#sel_lavoro_origine").prop("disabled",false);
                $("#inp_disponibilita").prop("readonly",false);
                $("#chk_complementare").prop("checked",false);
                $("#div_lavori2").load("elenco_lavori.php?id_fabbricato="+id_fabbricato);
            },
            error: function() {
//                alert ("error");
            }
        });
    });
    
    $("#chk_complementare").click(function(){
        if ($(this).prop('checked')) {
            $("#sel_lavoro_origine").prop("disabled",false);
            $("#inp_disponibilita").prop("readonly",false);
        }
        else {
            $("#sel_lavoro_origine").val(0);
            $("#sel_lavoro_origine").prop("disabled",true);
            $("#inp_disponibilita").prop("readonly",true);
            $("#inp_disponibilita").val("");
        }
    });
});
</script>
<style>
#dialog_nuovo_lavoro_lav .modal-lg {
    max-width: 50%;
}

</style>
<div class="container-fluid" id="div_lavori2">
    <h4 style="text-align: center;">ELENCO LAVORI</h4>
    
    <button id="but_nuovo_lavoro" class="btn btn-info" type="button">Nuovo Lavoro</button>
        <br /><br />
    <table class="table table-striped table-bordered" width="100%" cellspacing="0" id="tab_lavori" >
       <thead><tr><th align='center'>ID Lavoro</th><th align='center'>ID Lavoro Iniziale</th><th>Descrizione</th><th>Codice Contratto</th><th>Data Contratto</th><th>Categoria</th><th>Tipologia</th><th align='center'>Stato</th><th align='center'>Scheda</th></tr></thead>
       <tbody>
	
	<?php
        $query = "SELECT
                    l.IDLavoro,
                    l.Descrizione,
                    l.Codice_contratto,
                    l.data_contratto,
                    c.Categoria,
                    t.Tipologia,                    
                    l.LavoroChiuso,
                    l.id_lavoro_origine
                    FROM lavori l left join categorie c on (l.IDCategoria=c.IDCategoria)
                        left join tipologie t on (l.IDTipologia=t.IDTipologia)
                WHERE l.IDEdificio=$id_fabbricato";
        
	// Execute the query
	$result = mysql_query($query);
	if (!$result){
		die ("Could not query the database: <br />". mysql_error());
	}
	while ($row = mysql_fetch_assoc($result)){
				
		$IDLavoro = $row["IDLavoro"];     
                $id_lavoro_origine = $row["id_lavoro_origine"];    
		$Descrizione = $row["Descrizione"]; 		
		$Codice_contratto = $row["Codice_contratto"];		
		$data_contratto = $utility->convertDateToHTML($row["data_contratto"]);
                $Categoria = $row["Categoria"];
		$Tipologia = $row["Tipologia"];
                
                
                if ($row["LavoroChiuso"]=='0') {
                    $stato="Aperto";
                    $style="style='text-align:center;color:green;'";
                }
                else {
                    $stato="Chiuso";   
                    $style="style='text-align:center;color:red;'";
                }
		
		echo "<tr>";
		echo "<td align='center'>$IDLavoro</td>";
                echo "<td align='center'>$id_lavoro_origine</td>";
		echo "<td width='250'>".conv_string2html($Descrizione)."</td>";
		echo "<td>".$Codice_contratto."</td>";		
		echo "<td>".$data_contratto."</td>";
                echo "<td>".$Categoria."</td>";
		echo "<td width='150'>".$Tipologia."</td>";
		echo "<td $style>$stato</td>"; 
                echo "<td align='center'><img src='images/document.png' class='hand img_mod_lavori' id='img_mod_lavori' style='width:24px;' alt='".$IDLavoro."'/></td>"; 
		echo "</tr>";		
	}
        ?>
	</tbody>
    </table>
</div>        

<div id="dialog_nuovo_lavoro_lav" class="modal fade"  role="dialog">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">    
<div class="modal-header">        
        <h5>Nuovo Lavoro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
</div>   
<div class="modal-body">    

    <div class="form-group row">       
        <label class="col-form-label col-lg-2" for="inp_codice_contratto">Codice Contratto</label>     
        <div class="col-lg-2">    
         <input type="text" id="inp_codice_contratto" class="form-control" value="" /> 
        </div>
         <label class="col-form-label col-lg-2" for="inp_data_contratto">Data Contratto</label>     
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
        </div>  
</div> 
<div class="form-group row"> 
   
    <div class="col-lg-2">          
    <div class="custom-control custom-checkbox " style="">           
        <input type="checkbox" class="custom-control-input" id="chk_complementare" style="opacity: 100;z-index: 1500;"/>
         <label class="custom-col-form-label" for="chk_complementare" style="color: green;">Complementare</label>  
     </div>
  </div>
    <label class="col-form-label col-lg-2" for="sel_lavoro_origine" style="max-width: 130px;">Lavoro iniziale</label>
    <div class="col-lg-2"> 
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
            </select> 
    </div>
    <label class="col-form-label col-lg-1" for="inp_disponibilita" style="">Disponibilità</label>
            <div class="col-lg-3">     
            <input type="text" id="inp_disponibilita" class="form-control numeric_2" value=""/> 
            </div>
</div>    
<div class="form-group row"> 
          <label class="col-form-label col-lg-2" for="sel_categoria">Categoria</label>  
            <div class="col-lg-4"> 
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
            <label class="col-form-label col-lg-2" for="sel_tipologia">Tipologia</label>       
            <div class="col-lg-4" id="td_tipologie"> 
                  <select id="sel_tipologia" class="form-control">
                      <option value="0">- Seleziona -</option>
                   <?php
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
              </div>
          </div>        
<div class="form-group row">
   
    <label class="col-form-label col-lg-2" for="ta_descrizione">Descrizione</label>     
    <div class="col-lg-10" id=""> 
    <textarea type="text" id="ta_descrizione" rows="3" class="form-control" value="" ></textarea> 
    </div>
</div>
<div class="form-group row">
    <label class="col-form-label col-lg-2" for="ta_note_lavoro">Note</label>     
    <div class="col-lg-10" id="">  
    <textarea type="text" id="ta_note_lavoro" rows="3" class="form-control" value="" ></textarea> 
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-4" id="">
    <h5>Procedimento</h5>
    </div>  
</div>     
<div class="form-group row">
        <label class="col-form-label col-lg-2" for="sel_responsabile">Responsabile</label>
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
        <label class="col-form-label col-lg-2" for="sel_istruttore" style="max-width: 160px;">Istruttore Pratica</label>
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
 </div> 
  <div class="modal-footer  justify-content-between">
       
        <button id="but_salva_nuovo_lavoro" class="btn btn-success">Salva</button>
         <button class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
    </div>      
  
</div>
</div>
</div>


