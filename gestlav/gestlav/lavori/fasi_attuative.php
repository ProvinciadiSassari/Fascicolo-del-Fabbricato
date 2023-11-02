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

$query = "SELECT data_affidamento_incarico_servivi_arch_ing, data_contratto_convenzione_progettista, data_consegna_progetto_preliminare, 
                data_proposta_approvazione_gp, data_approvazione_progetto_preliminare, data_consegna_progetto_definitivo, 
                data_proposta_progetto_definitivo, data_approvazione_progetto_definitivo, data_consegna_progetto_esecutivo, 
                data_proposta_approvazione_progetto_esecutivo, data_approvazione_progetto_esecutivo, data_completamento_reperimento_pareri_enti_terzi,
                data_determina_contrarre, data_procedure_appalto, data_contratto_lavori, data_consegna_lavori, data_collaudo_lavori
            FROM fasi_attuattive_lavori
            WHERE id_lavoro=$id_lavoro";

$result = mysql_query($query);
if (!$result){
        die ("Could not query the database: <br />". mysql_error());
}

$data_affidamento_incarico_servivi_arch_ing="";
$data_contratto_convenzione_progettista="";    
$data_consegna_progetto_preliminare="";  
$data_proposta_approvazione_gp="";  
$data_approvazione_progetto_preliminare="";
$data_consegna_progetto_definitivo="";
$data_proposta_progetto_definitivo="";
$data_approvazione_progetto_definitivo="";     
$data_consegna_progetto_esecutivo="";
$data_proposta_approvazione_progetto_esecutivo="";  
$data_approvazione_progetto_esecutivo=""; 
$data_completamento_reperimento_pareri_enti_terzi="";  
$data_determina_contrarre="";  
$data_procedure_appalto="";
$data_contratto_lavori="";
$data_consegna_lavori="";
$data_collaudo_lavori="";

if ($row = mysql_fetch_assoc($result)){
       
    $data_affidamento_incarico_servivi_arch_ing=$utility->convertDateToHTML($row["data_affidamento_incarico_servivi_arch_ing"]);
    $data_contratto_convenzione_progettista=$utility->convertDateToHTML($row["data_contratto_convenzione_progettista"]);    
    $data_consegna_progetto_preliminare=$utility->convertDateToHTML($row["data_consegna_progetto_preliminare"]);  
    $data_proposta_approvazione_gp=$utility->convertDateToHTML($row["data_proposta_approvazione_gp"]);  
    $data_approvazione_progetto_preliminare=$utility->convertDateToHTML($row["data_approvazione_progetto_preliminare"]);
    $data_consegna_progetto_definitivo=$utility->convertDateToHTML($row["data_consegna_progetto_definitivo"]);
    $data_proposta_progetto_definitivo=$utility->convertDateToHTML($row["data_proposta_progetto_definitivo"]);
    $data_approvazione_progetto_definitivo=$utility->convertDateToHTML($row["data_approvazione_progetto_definitivo"]);     
    $data_consegna_progetto_esecutivo=$utility->convertDateToHTML($row["data_consegna_progetto_esecutivo"]);
    $data_proposta_approvazione_progetto_esecutivo=$utility->convertDateToHTML($row["data_proposta_approvazione_progetto_esecutivo"]);  
    $data_approvazione_progetto_esecutivo=$utility->convertDateToHTML($row["data_approvazione_progetto_esecutivo"]); 
    $data_completamento_reperimento_pareri_enti_terzi=$utility->convertDateToHTML($row["data_completamento_reperimento_pareri_enti_terzi"]);  
    $data_determina_contrarre=$utility->convertDateToHTML($row["data_determina_contrarre"]);  
    $data_procedure_appalto=$utility->convertDateToHTML($row["data_procedure_appalto"]);
    $data_contratto_lavori=$utility->convertDateToHTML($row["data_contratto_lavori"]);
    $data_consegna_lavori=$utility->convertDateToHTML($row["data_consegna_lavori"]);
    $data_collaudo_lavori=$utility->convertDateToHTML($row["data_collaudo_lavori"]);
   
}

?>
<style>
    .control-label {
        margin-left:10px;
        font-size:9pt;
    }
</style>
<script type="text/javascript">
$(document).ready( function(){
          
      var id_lavoro=<?=$id_lavoro;?>;
      var id_livello=<?=$_SESSION['idlevel'];?>;

      if (id_livello==7) {
          $("#but_salva").hide();
        
      }
          
      $("#but_salva").click(function(e) {
       
        var data_affidamento_incarico_servivi_arch_ing=$("#data_affidamento_incarico_servivi_arch_ing").val();       
        var data_contratto_convenzione_progettista=$("#data_contratto_convenzione_progettista").val();
        var data_consegna_progetto_preliminare=$("#data_consegna_progetto_preliminare").val();       
        var data_proposta_approvazione_gp=$("#data_proposta_approvazione_gp").val();
        var data_approvazione_progetto_preliminare=$("#data_approvazione_progetto_preliminare").val();
        var data_consegna_progetto_definitivo=$("#data_consegna_progetto_definitivo").val();        
        var data_proposta_progetto_definitivo=$("#data_proposta_progetto_definitivo").val();        
        var data_approvazione_progetto_definitivo=$("#data_approvazione_progetto_definitivo").val();
        var data_consegna_progetto_esecutivo=$("#data_consegna_progetto_esecutivo").val();        
        var data_proposta_approvazione_progetto_esecutivo=$("#data_proposta_approvazione_progetto_esecutivo").val();        
        var data_approvazione_progetto_esecutivo=$("#data_approvazione_progetto_esecutivo").val();   
        var data_completamento_reperimento_pareri_enti_terzi=$("#data_completamento_reperimento_pareri_enti_terzi").val();
        var data_determina_contrarre=$("#data_determina_contrarre").val();  
        var data_procedure_appalto=$("#data_procedure_appalto").val();       
        var data_contratto_lavori=$("#data_contratto_lavori").val();
        var data_consegna_lavori=$("#data_consegna_lavori").val();       
        var data_collaudo_lavori=$("#data_collaudo_lavori").val();       
                     
        var ajax_data="";
        
        ajax_data={            
            id_lavoro:id_lavoro,
            data_affidamento_incarico_servivi_arch_ing:data_affidamento_incarico_servivi_arch_ing,
            data_contratto_convenzione_progettista:data_contratto_convenzione_progettista,
            data_consegna_progetto_preliminare:data_consegna_progetto_preliminare,
            data_proposta_approvazione_gp:data_proposta_approvazione_gp,
            data_approvazione_progetto_preliminare:data_approvazione_progetto_preliminare,                            
            data_consegna_progetto_definitivo:data_consegna_progetto_definitivo,
            data_proposta_progetto_definitivo:data_proposta_progetto_definitivo,
            data_approvazione_progetto_definitivo:data_approvazione_progetto_definitivo,
            data_consegna_progetto_esecutivo:data_consegna_progetto_esecutivo,                            
            data_proposta_approvazione_progetto_esecutivo:data_proposta_approvazione_progetto_esecutivo,
            data_approvazione_progetto_esecutivo:data_approvazione_progetto_esecutivo,
            data_completamento_reperimento_pareri_enti_terzi:data_completamento_reperimento_pareri_enti_terzi,
            data_determina_contrarre:data_determina_contrarre,
            data_procedure_appalto:data_procedure_appalto,
            data_contratto_lavori:data_contratto_lavori,                            
            data_consegna_lavori:data_consegna_lavori,
            data_collaudo_lavori:data_collaudo_lavori
        };
        
        $.ajax({
            type: "post",            
            url: "actions/submit_salva_dati_fasi_attuative.php",                                                 
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
                alert ("error");
            }
        });
        
     });               
                  
});
</script>
<fieldset>
    <legend>Fasi Attuative Lavoro</legend>
      <table>
          <tr>
              <td>
                  <label class="control-label" for="data_affidamento_incarico_servivi_arch_ing">Affidamento incarico servizi di ingegneria e architettura</label>     
              </td><td >                      
                  <div class="input-group date" id="d_affidamento_incarico_servivi_arch_ing" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#d_affidamento_incarico_servivi_arch_ing" id="data_affidamento_incarico_servivi_arch_ing" value="<?=$data_affidamento_incarico_servivi_arch_ing;?>"/>
                <div class="input-group-append" data-target="#d_affidamento_incarico_servivi_arch_ing" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#d_affidamento_incarico_servivi_arch_ing').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
              </td>
           </tr><tr>   
              <td>
                  <label class="control-label" for="data_contratto_convenzione_progettista">Contratto / convenzione con il Progettista</label>     
              </td><td >                    
                   <div class="input-group date" id="d_contratto_convenzione_progettista" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#d_contratto_convenzione_progettista" id="data_contratto_convenzione_progettista" value="<?=$data_contratto_convenzione_progettista;?>"/>
                <div class="input-group-append" data-target="#d_contratto_convenzione_progettista" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#d_contratto_convenzione_progettista').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
              </td>
           </tr><tr>   
              <td>
                  <label class="control-label" for="data_consegna_progetto_preliminare">Consegna progetto preliminare</label>     
              </td><td>    
                   <div class="input-group date" id="d_consegna_progetto_preliminare" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#d_consegna_progetto_preliminare" id="data_consegna_progetto_preliminare" value="<?=$data_consegna_progetto_preliminare;?>"/>
                <div class="input-group-append" data-target="#d_consegna_progetto_preliminare" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#d_consegna_progetto_preliminare').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
              </td>
              <td>
                  <label class="control-label" for="data_proposta_approvazione_gp">Proposta di approvazione in G.P.</label>     
              </td><td>    
                   <div class="input-group date" id="d_proposta_approvazione_gp" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#d_proposta_approvazione_gp" id="data_proposta_approvazione_gp" value="<?=$data_proposta_approvazione_gp;?>"/>
                <div class="input-group-append" data-target="#d_proposta_approvazione_gp" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#d_proposta_approvazione_gp').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
              </td>
          </tr>
          <tr>   
              <td>
                  <label class="control-label" for="data_approvazione_progetto_preliminare">Approvazione progetto preliminare</label>     
              </td><td >    
                   <div class="input-group date" id="d_approvazione_progetto_preliminare" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#d_approvazione_progetto_preliminare" id="data_approvazione_progetto_preliminare" value="<?=$data_approvazione_progetto_preliminare;?>"/>
                <div class="input-group-append" data-target="#d_approvazione_progetto_preliminare" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#d_approvazione_progetto_preliminare').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
              </td>
          </tr><tr>    
              <td>
                  <label class="control-label" for="data_consegna_progetto_definitivo">Consegna progetto definitivo</label>     
              </td><td>    
                   <div class="input-group date" id="d_consegna_progetto_definitivo" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#d_consegna_progetto_definitivo" id="data_consegna_progetto_definitivo" value="<?=$data_consegna_progetto_definitivo;?>"/>
                <div class="input-group-append" data-target="#d_consegna_progetto_definitivo" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#d_consegna_progetto_definitivo').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
              </td>    
              <td>
                  <label class="control-label" for="data_proposta_progetto_definitivo">Proposta progetto definitivo</label>     
              </td><td>                      
                   <div class="input-group date" id="d_proposta_progetto_definitivo" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#d_proposta_progetto_definitivo" id="data_proposta_progetto_definitivo" value="<?=$data_proposta_progetto_definitivo;?>"/>
                <div class="input-group-append" data-target="#d_proposta_progetto_definitivo" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#d_proposta_progetto_definitivo').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
              </td>
          </tr>
          <tr>   
              <td>
                  <label class="control-label" for="data_approvazione_progetto_definitivo">Approvazione progetto definitivo</label>     
              </td><td >               
                   <div class="input-group date" id="d_approvazione_progetto_definitivo" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#d_approvazione_progetto_definitivo" id="data_approvazione_progetto_definitivo" value="<?=$data_approvazione_progetto_definitivo;?>"/>
                <div class="input-group-append" data-target="#d_approvazione_progetto_definitivo" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#d_approvazione_progetto_definitivo').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
              </td>
          </tr><tr>    
              <td>
                  <label class="control-label" for="data_consegna_progetto_esecutivo">Consegna progetto esecutivo</label>     
              </td><td >    
                   <div class="input-group date" id="d_consegna_progetto_esecutivo" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#d_consegna_progetto_esecutivo" id="data_consegna_progetto_esecutivo" value="<?=$data_consegna_progetto_esecutivo;?>"/>
                <div class="input-group-append" data-target="#d_consegna_progetto_esecutivo" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#d_consegna_progetto_esecutivo').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
              </td> 
           </tr><tr>   
              <td>
                  <label class="control-label" for="data_proposta_approvazione_progetto_esecutivo">Proposta di approvazione progetto esecutivo</label>     
              </td><td >                      
                   <div class="input-group date" id="d_proposta_approvazione_progetto_esecutivo" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#d_proposta_approvazione_progetto_esecutivo" id="data_proposta_approvazione_progetto_esecutivo" value="<?=$data_proposta_approvazione_progetto_esecutivo;?>"/>
                <div class="input-group-append" data-target="#d_proposta_approvazione_progetto_esecutivo" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#d_proposta_approvazione_progetto_esecutivo').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
              </td>
          </tr>
          <tr>   
              <td>
                  <label class="control-label" for="data_approvazione_progetto_esecutivo">Approvazione progetto esecutivo</label>     
              </td><td >    
                   <div class="input-group date" id="d_approvazione_progetto_esecutivo" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#d_approvazione_progetto_esecutivo" id="data_approvazione_progetto_esecutivo" value="<?=$data_approvazione_progetto_esecutivo;?>"/>
                <div class="input-group-append" data-target="#d_approvazione_progetto_esecutivo" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#d_approvazione_progetto_esecutivo').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
              </td>
          </tr>
          <tr>   
              <td>
                  <label class="control-label" for="data_completamento_reperimento_pareri_enti_terzi">Completamento reperimento pareri enti terzi</label>     
              </td><td >                      
                   <div class="input-group date" id="d_completamento_reperimento_pareri_enti_terzi" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#d_completamento_reperimento_pareri_enti_terzi" id="data_completamento_reperimento_pareri_enti_terzi" value="<?=$data_completamento_reperimento_pareri_enti_terzi;?>"/>
                <div class="input-group-append" data-target="#d_completamento_reperimento_pareri_enti_terzi" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#d_completamento_reperimento_pareri_enti_terzi').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
              </td>
          </tr>
          <tr>   
              <td>
                  <label class="control-label" for="data_determina_contrarre">Determina a contrarre</label>     
              </td><td>    
                   <div class="input-group date" id="d_determina_contrarre" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#d_determina_contrarre" id="data_determina_contrarre" value="<?=$data_determina_contrarre;?>"/>
                <div class="input-group-append" data-target="#d_determina_contrarre" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#d_determina_contrarre').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
              </td>
              <td>
                  <label class="control-label" for="data_procedure_appalto">Procedure di appalto</label>     
              </td><td>    
                   <div class="input-group date" id="d_procedure_appalto" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#d_procedure_appalto" id="data_procedure_appalto" value="<?=$data_procedure_appalto;?>"/>
                <div class="input-group-append" data-target="#d_procedure_appalto" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#d_procedure_appalto').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
              </td>
          </tr>
          <tr>   
              <td>
                  <label class="control-label" for="data_contratto_lavori">Contratto lavori</label>     
              </td><td>    
                   <div class="input-group date" id="d_contratto_lavori" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#d_contratto_lavori" id="data_contratto_lavori" value="<?=$data_contratto_lavori;?>"/>
                <div class="input-group-append" data-target="#d_contratto_lavori" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#d_contratto_lavori').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
              </td>
              <td>
                  <label class="control-label" for="data_consegna_lavori">Consegna lavori</label>     
              </td><td>    
                   <div class="input-group date" id="d_consegna_lavori" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#d_consegna_lavori" id="data_consegna_lavori" value="<?=$data_consegna_lavori;?>"/>
                <div class="input-group-append" data-target="#d_consegna_lavori" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#d_consegna_lavori').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
              </td>
          </tr>
          <tr>   
              <td>
                  <label class="control-label" for="data_collaudo_lavori">Collaudo lavori</label>     
              </td><td >    
                   <div class="input-group date" id="d_collaudo_lavori" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#d_collaudo_lavori" id="data_collaudo_lavori" value="<?=$data_collaudo_lavori;?>"/>
                <div class="input-group-append" data-target="#d_collaudo_lavori" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            <script type="text/javascript">
            $(function () {
                $('#d_collaudo_lavori').datetimepicker({
                    locale: 'it',
                    format: 'L'
                });
            });
        </script>
              </td>
          </tr>
      </table>   
<hr />
<button id="but_salva" class="btn btn-primary">Salva</button>
</fieldset>

