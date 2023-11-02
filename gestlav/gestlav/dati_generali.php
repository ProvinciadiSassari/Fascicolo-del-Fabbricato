<?php
session_start();
require_once('conf.inc.php');
include('conv.php');

$utility = new Utility();
$utility->connetti();

if (!isset($_SESSION['idlevel']) || ($_SESSION['idlevel']!=2 && $_SESSION['idlevel']!=5))
{ //se non passo il controllo ritorno all'index
    header("Location: /gestlav/index.php");
}

if (isset($_GET["id_istituto"])) {
    $id_istituto=$_GET["id_istituto"];     
}
else $id_istituto=0;

$query="SELECT 	IDReferente,
                Dirigente_responsabile, 
                Titolo_possesso, 
                Plessi, 
                Locali, 
                N_Piani, 
                Superficie_cubatura, 
                Certif_agibilita, 
                Situaz_catast, 
                Certif_collaudo, 
                CPI
        FROM edifici where ID=$id_istituto";


$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}
if ($row = mysql_fetch_assoc($result)){
    $IDReferente=$row["IDReferente"];
    $Dirigente_responsabile=$row["Dirigente_responsabile"];
    $Titolo_possesso=$row["Titolo_possesso"];
    $Plessi=$row["Plessi"];
    $Locali=$row["Locali"];
    $N_Piani=$row["N_Piani"];
    $Superficie_cubatura=$row["Superficie_cubatura"];
    $Certif_agibilita=$row["Certif_agibilita"];
    $Situaz_catast=$row["Situaz_catast"];
    $Certif_collaudo=$row["Certif_collaudo"];
    $CPI=$row["CPI"];
}

//echo $_SERVER["DOCUMENT_ROOT"];

?>

<script type="text/javascript">
$(document).ready( function(){
    
      var id_istituto=<?=$id_istituto;?>;
           
     
     $("#but_salva_dati_generali").click(function(e) {
        e.preventDefault();
        
        var sel_referente=$("#sel_referente").val();
        var inp_responsabile=$("#inp_responsabile").val();
        var ta_titolo_possesso=$("#ta_titolo_possesso").val();
        var ta_plessi=$("#ta_plessi").val();
        var ta_locali=$("#ta_locali").val();
        var ta_piani=$("#ta_piani").val();
        var ta_superficie_cubatura=$("#ta_superficie_cubatura").val();
        var ta_certif_agibilita=$("#ta_certif_agibilita").val();
        var ta_situazione_catastale=$("#ta_situazione_catastale").val();
        var ta_certif_collaudo=$("#ta_certif_collaudo").val();
        var ta_cpi=$("#ta_cpi").val();        
        
        var ajax_data="";
        
        ajax_data={
            id_istituto:id_istituto,
            sel_referente:sel_referente,
            inp_responsabile:inp_responsabile,
            ta_titolo_possesso:ta_titolo_possesso,
            ta_plessi:ta_plessi,
            ta_locali:ta_locali,                            
            ta_piani:ta_piani,
            ta_superficie_cubatura:ta_superficie_cubatura,
            ta_certif_agibilita:ta_certif_agibilita,
            ta_situazione_catastale:ta_situazione_catastale,
            ta_certif_collaudo:ta_certif_collaudo,
            ta_cpi:ta_cpi
        };
        
        $.ajax({
            type: "post",
            url: "actions/submit_salva_dati_generali.php",                                                 
            data: ajax_data,
            success: function(msg) { 
//                alert(msg);               
                $('#alert_success2').modal("show");                 
            },
            error: function() {
                alert ("error");
            }
        });
        
     });
});
</script>
<div class="span9">  
      <table>
          <tr>
              <td>
                  <label class="control-label" for="sel_referente">Referente tecnico presso l'Amm.</label>     
              </td><td>    
                  <select id="sel_referente">                      
                   <?
                    $query="SELECT
                            IDReferente,
                            Referente
                            FROM referenti_edificio";
                    $result = mysql_query($query);
                    if (!$result){
                            die ("Could not query the database: <br />". mysql_error());
                    }
                    while ($row = mysql_fetch_assoc($result)){
                        $id=$row["IDReferente"];
                        $val=$row["Referente"];
                        echo "<option value='$id'";
                        if ($id==$IDReferente) echo " selected";
                        echo ">";
                        echo $val;
                        echo "</option>";                        
                    }                    
                   ?>   
                  </select>
              </td>
          </tr> 
          <tr>
              <td>
                  <label class="control-label" for="inp_responsabile">Dirigente Istituto/Responsabile</label>     
              </td><td>    
                  <input type="text" id="inp_responsabile" class="input-xlarge" value="<?=$Dirigente_responsabile?>" /> 
              </td>
          </tr>     
        <tr>
            <td>
            <label class="control-label" for="ta_titolo_possesso">Titolo di possesso</label>     
            </td><td> 
            <textarea type="text" id="ta_titolo_possesso" rows="3" class="input-xxlarge" value="" ><?=($Titolo_possesso);?></textarea> 
        </tr>
        <tr>
            <td>
            <label class="control-label" for="ta_plessi">Plessi</label>     
            </td><td> 
            <textarea type="text" id="ta_plessi" rows="3" class="input-xxlarge" value="" ><?=($Plessi);?></textarea> 
        </tr>
        <tr>
            <td>
            <label class="control-label" for="ta_locali">Locali e Classi</label>     
            </td><td> 
            <textarea type="text" id="ta_locali" rows="3" class="input-xxlarge" value="" ><?=($Locali);?></textarea> 
        </tr>
        <tr>
            <td>
            <label class="control-label" for="ta_piani">N. Piani</label>     
            </td><td> 
            <textarea type="text" id="ta_piani" rows="3" class="input-xxlarge" value="" ><?=($N_Piani);?></textarea> 
        </tr>
        <tr>
            <td>
            <label class="control-label" for="ta_superficie_cubatura">Superficie e cubatura</label>     
            </td><td> 
            <textarea type="text" id="ta_superficie_cubatura" rows="3" class="input-xxlarge" value="" ><?=($Superficie_cubatura);?></textarea> 
        </tr>
        <tr>
            <td>
            <label class="control-label" for="ta_certif_agibilita">Certificato agibilità</label>     
            </td><td> 
            <textarea type="text" id="ta_certif_agibilita" rows="3" class="input-xxlarge" value="" ><?=($Certif_agibilita);?></textarea> 
        </tr>
        <tr>
            <td>
            <label class="control-label" for="ta_situazione_catastale">Situazione Catastale</label>     
            </td><td> 
            <textarea type="text" id="ta_situazione_catastale" rows="3" class="input-xxlarge" value="" ><?=($Situaz_catast);?></textarea> 
        </tr>
        <tr>
            <td>
            <label class="control-label" for="ta_certif_collaudo">Certificato collaudo</label>     
            </td><td> 
            <textarea type="text" id="ta_certif_collaudo" rows="3" class="input-xxlarge" value="" ><?=($Certif_collaudo);?></textarea> 
        </tr>
        <tr>
            <td>
            <label class="control-label" for="ta_cpi">Certificato prevenzione incendi</label>     
            </td><td> 
            <textarea type="text" id="ta_cpi" rows="3" class="input-xxlarge" value="" ><?=($CPI);?></textarea> 
        </tr>
      </table>
<hr />    
<button id="but_salva_dati_generali" class="btn btn-primary" type="button">Salva</button>    
</div>
<div class="alert alert-success modal hide fade" id="alert_success2">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
   <span>Operazione avvenuta con successo!</span>
</div>


