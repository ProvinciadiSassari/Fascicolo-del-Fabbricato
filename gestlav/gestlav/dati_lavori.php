<?php
session_start();
require_once('conf.inc.php');
include('conv.php');

$utility = new Utility();
$utility->connetti();


if (isset($_GET["id_lavoro"])) {
    $id_lavoro=$_GET["id_lavoro"];     
}
else $id_lavoro=0;

$id_fabbricato=$utility->getIDFabbricatoFromIDLavoro($id_lavoro);

if ($id_lavoro>0) {
    $utility->createFolderLavori($id_lavoro);
}


?>

<script type="text/javascript">
$(document).ready( function(){
    
      var id_lavoro=<?=$id_lavoro;?>;
      var id_fabbricato=<?=$id_fabbricato;?>;

        $("#div_dati_lavoro").load("lavori/dati_lavoro.php?id_lavoro="+id_lavoro); 
        $("#div_documenti").load("lavori/documenti.php?id_lavoro="+id_lavoro);
        $("#div_incarichi").load("lavori/incarichi.php?id_lavoro="+id_lavoro); 
        $("#div_impresa").load("lavori/impresa.php?id_lavoro="+id_lavoro); 
        $("#div_subappalti").load("lavori/subappalti.php?id_lavoro="+id_lavoro); 
        $("#div_finanziamenti").load("lavori/finanziamenti.php?id_lavoro="+id_lavoro);
        $("#div_pagamenti_lavori").load("lavori/pagamenti_lavori.php?id_lavoro="+id_lavoro); 
        $("#div_pagamenti_sd").load("lavori/pagamenti_lavori_sd.php?id_lavoro="+id_lavoro); 
        $("#div_vecchio_quadro_economico").load("lavori/quadro_economico_old.php?id_lavoro="+id_lavoro); 
        $("#div_quadro_economico").load("lavori/quadro_economico.php?id_lavoro="+id_lavoro); 
        $("#div_tempi").load("lavori/tempi.php?id_lavoro="+id_lavoro); 
        $("#div_fasi_attuative").load("lavori/fasi_attuative.php?id_lavoro="+id_lavoro); 
        $("#div_corrispondenza_entrata").load("lavori/corrispondenza_entrata.php?id_lavoro="+id_lavoro); 
        $("#div_corrispondenza_uscita").load("lavori/corrispondenza_uscita.php?id_lavoro="+id_lavoro); 
        
        $("#but_elenco_lavori").click(function(e){
            e.preventDefault();
            $("#div_lavori2").load("elenco_lavori.php?id_fabbricato="+id_fabbricato);
        });
        
});
</script>
<div class="container-fluid">
<div class="form-group row" >  
<button id="but_elenco_lavori" class="btn btn-success btn-sm" type="button">Elenco Lavori</button>
</div>
<div class="form-group row" id="" > 
    
<ul class="nav flex-column nav-pills col-lg-2" id="tabs_dati_lavoro" role="tablist" aria-orientation="vertical" >
    <li class="nav-item"><a class="nav-link active" href="#div_dati_lavoro" data-toggle="pill">Dati Lavoro</a></li>
    <li class="nav-item"><a class="nav-link" href="#div_documenti" data-toggle="pill">Documenti</a></li>
    <li class="nav-item"><a class="nav-link" href="#div_incarichi" data-toggle="pill">Incarichi</a></li>
    <li class="nav-item"><a class="nav-link" href="#div_impresa" data-toggle="pill">Impresa</a></li>
    <li class="nav-item"><a class="nav-link" href="#div_subappalti" data-toggle="pill">Subappalti</a></li>
    <li class="nav-item"><a class="nav-link" href="#div_finanziamenti" data-toggle="pill">Finanziamenti</a></li>
    <li class="nav-item"><a class="nav-link" href="#div_pagamenti_lavori" data-toggle="pill">Pagamenti Lavori</a></li>
    <li class="nav-item"><a class="nav-link" href="#div_pagamenti_sd" data-toggle="pill">Pagamenti Somme Disp.</a></li>
    <li class="nav-item"><a class="nav-link" href="#div_vecchio_quadro_economico" data-toggle="pill">Quadro Economico Precedente</a></li>
    <li class="nav-item"><a class="nav-link" href="#div_quadro_economico" data-toggle="pill">Quadro Economico</a></li>
    <li class="nav-item"><a class="nav-link" href="#div_tempi" data-toggle="pill">Tempi</a></li>
    <li class="nav-item"><a class="nav-link" href="#div_fasi_attuative" data-toggle="pill">Fasi Attuative Lavoro</a></li>
    <li class="nav-item"><a class="nav-link" href="#div_corrispondenza_entrata" data-toggle="pill">Corrispondenza Entrata</a></li>
    <li class="nav-item"><a class="nav-link" href="#div_corrispondenza_uscita" data-toggle="pill">Corrispondenza Uscita</a></li>            
</ul>
<div class="tab-content col-lg-10"  style="margin-top:20px;">             
        <div class="tab-pane fade show active" id="div_dati_lavoro">
                        
        </div> 
        <div class="tab-pane fade" role="tabpanel" aria-labelledby="div_documenti" id="div_documenti">
                        
        </div>
        <div class="tab-pane fade" role="tabpanel" aria-labelledby="div_incarichi" id="div_incarichi">
                        
        </div> 
        <div class="tab-pane fade" role="tabpanel" aria-labelledby="div_impresa" id="div_impresa">
                        
        </div> 
        <div class="tab-pane fade" role="tabpanel" aria-labelledby="div_subappalti" id="div_subappalti">
                        
        </div>    
        <div class="tab-pane fade" role="tabpanel" aria-labelledby="div_finanziamenti" id="div_finanziamenti">
                        
        </div> 
        <div class="tab-pane fade" role="tabpanel" aria-labelledby="div_pagamenti_lavori" id="div_pagamenti_lavori">
                        
        </div>
        <div class="tab-pane fade" role="tabpanel" aria-labelledby="div_pagamenti_sd" id="div_pagamenti_sd">
                        
        </div>    
        <div class="tab-pane fade" role="tabpanel" aria-labelledby="div_vecchio_quadro_economico" id="div_vecchio_quadro_economico">
                        
        </div>
        <div class="tab-pane fade" role="tabpanel" aria-labelledby="div_quadro_economico" id="div_quadro_economico">
                        
        </div> 
        <div class="tab-pane fade" role="tabpanel" aria-labelledby="div_tempi" id="div_tempi">
                        
        </div>
        <div class="tab-pane fade" role="tabpanel" aria-labelledby="div_fasi_attuative" id="div_fasi_attuative">
                        
        </div>
        <div class="tab-pane fade" role="tabpanel" aria-labelledby="div_corrispondenza_entrata" id="div_corrispondenza_entrata">
                        
        </div>    
        <div class="tab-pane fade" role="tabpanel" aria-labelledby="div_corrispondenza_uscita" id="div_corrispondenza_uscita">
                        
        </div>                   
</div> 
</div>
</div>
