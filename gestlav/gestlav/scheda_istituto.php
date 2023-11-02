<?php
session_start();
require_once('conf.inc.php');
include('conv.php');

$utility = new Utility();
$utility->connetti();

if (!isset($_SESSION['idlevel']) || ($_SESSION['idlevel']!=2 && $_SESSION['idlevel']!=5 && $_SESSION['idlevel']!=7))
{ //se non passo il controllo ritorno all'index
    header("Location: index.php");
}

if (isset($_GET["id_fabbricato"])) {
    $id_fabbricato=$_GET["id_fabbricato"];     
}
else $id_fabbricato=0;

$id_competenza=$utility->getIDCompetenzaFabbricato($id_fabbricato);

if ($_SESSION["idcompetenza"]>0 && $id_competenza!=$_SESSION["idcompetenza"]) {
    header("Location: elenco_istituti_intro.php");
}

if ($id_fabbricato>0) {
    $utility->createFolderEdificio($id_fabbricato);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Gestione Lavori</title>
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
<link href="css/base_dialog.css" rel="stylesheet" type="text/css"/>
<link href="css/base.css" rel="stylesheet" type="text/css"/>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="css/base.css" rel="stylesheet" type="text/css"/>
<link href="css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<link href="css/datatables.min.css" rel="stylesheet" type="text/css"/>
<link href="css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
<link href="themes/default/style.min.css" rel="stylesheet" type="text/css"/>
<link href="css/fullcalendar.min.css" rel="stylesheet" type="text/css"/>
<link href="css/sweetalert2.min.css" rel="stylesheet" type="text/css"/>
<link href="css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" type="text/css"/>
<link href="css/summernote-bs4.css" rel="stylesheet" type="text/css"/>
<link href="css/elfinder.full.css" rel="stylesheet" type="text/css"/>
<link href="css/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
<link href="css/utils.css" rel="stylesheet" type="text/css"/>
<script src="js/popper.min.js" type="text/javascript"></script>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jquery-ui.min.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<script src="js/moment.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jstree.js"></script>
<script src="js/summernote-bs4.js" type="text/javascript"></script>
<script src="lang/summernote-it-IT.js" type="text/javascript"></script>
<script src="js/datatables.min.js" type="text/javascript"></script>
<script src="js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
<script src="lang/summernote-it-IT.js" type="text/javascript"></script>
<script src="js/fullcalendar.min.js" type="text/javascript"></script>
<script src="js/calendar_locale_it.js" type="text/javascript"></script>
<script src="js/fontawesome-all.js" type="text/javascript"></script>
<script src="js/sweetalert2.min.js" type="text/javascript"></script>
<script src="js/tempusdominus-bootstrap-4.min.js" type="text/javascript"></script>
<script src="js/it_locale.js" type="text/javascript"></script>
<script src="js/elfinder.full.js" type="text/javascript"></script>
<script src="js/inputmask.js" type="text/javascript"></script>
<script src="js/inputmask.extensions.js" type="text/javascript"></script>
<script src="js/inputmask.numeric.extensions.js" type="text/javascript"></script>
<script src="js/jquery.inputmask.js" type="text/javascript"></script>
<script src="js/bootstrap-select.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready( function(){
      
      var id_fabbricato=<?=$id_fabbricato;?>; 
      
      var id_livello=<?=$_SESSION['idlevel'];?>; 
      

       $("a[href='#documenti_edificio']").on('show.bs.tab', function(e) {
//            elFinder.prototype.commands.info=function(){};
        });
        $("a[href='#lavori']").on('show.bs.tab', function(e) {
            elFinder.prototype.commands.info=function(){};
        });
      $("#header_edificio").load("header_edificio.php?id_fabbricato="+id_fabbricato);  
      $("#edificio").load("dati_edificio.php?id_fabbricato="+id_fabbricato,function(){
           
      
      });
      $("#documenti_edificio").load("documenti_edificio.php?id_fabbricato="+id_fabbricato);
      $("#lavori").load("elenco_lavori.php?id_fabbricato="+id_fabbricato,function(){
           
      
      });

//      $("#dati_digitalizzati").load("archivio_dati_digitalizzati.php?id_fabbricato="+id_fabbricato); 
      $("#documenti_scadenza").load("documenti_in_scadenza.php?id_fabbricato="+id_fabbricato); 
      
    
      
  

});

function parseToken (token) {
    var base64Url = token.split('_')[1];
    var folderPathDecoded = decodeBase64Url(base64Url);
    return folderPathDecoded;
};

function decodeBase64Url(s) {
    var e = {}, i, b = 0, c, x, l = 0, a, r = '', w = String.fromCharCode, L = s.length;
    var A = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
    for (i = 0; i < 64; i++) { e[A.charAt(i)] = i; }
    for (x = 0; x < L; x++) {
        c = e[s.charAt(x)]; b = (b << 6) + c; l += 6;
        while (l >= 8) { ((a = (b >>> (l -= 8)) & 0xff) || (x < (L - 2))) && (r += w(a)); }
    }
    return r;
};
function controllaScadenzeDocumenti() {
    var id_fabbricato=<?=$id_fabbricato;?>; 
    
   $("#div_table_scadenza_docs").load("tables/table_scadenze_documenti.php?id_fabbricato="+id_fabbricato,function(){

        if ($("#hid_num_doc_scadenza").val()>0) {   
           $('#alert_documenti_scaduti').modal("show");
       }   
    });
}
</script>

</head>
<body onload="controllaScadenzeDocumenti();">
<?php
    include("menu.php");
?>   
 <br /><br /><br />      
<div class="container-fluid" >
   
    <div class="" id="header_edificio">        
       
    </div>
    <ul class="nav nav-tabs mb-3" id="">           
        <li class="nav-item">
            <a class="nav-link active" id="edificio-tab" data-toggle="pill" href="#edificio" role="tab" aria-controls="edificio" aria-selected="true">Fabbricato</a>
        </li>   
        <li class="nav-item">
            <a class="nav-link " id="documenti_edificio-tab" data-toggle="pill" href="#documenti_edificio" role="tab" aria-controls="documenti_edificio" aria-selected="true">Documenti Fabbricato</a>
        </li><!--
        <li class="nav-item">
            <a class="nav-link " id="dati_digitalizzati-tab" data-toggle="pill" href="#dati_digitalizzati" role="tab" aria-controls="dati_digitalizzati" aria-selected="true">Archivio Dati Digitalizzati</a>
        </li>
-->        <li class="nav-item">
            <a class="nav-link " id="lavori-tab" data-toggle="pill" href="#lavori" role="tab" aria-controls="lavori" aria-selected="true">Lavori</a>
        </li>            
      
        <li class="nav-item">
            <a class="nav-link " id="documenti_scadenza-tab" data-toggle="pill" href="#documenti_scadenza" role="tab" aria-controls="documenti_scadenza" aria-selected="true">Documenti in Scadenza</a>
        </li>
    </ul>
        <div class="tab-content" id="tab_container" style="margin-top:20px;">

        <div class="tab-pane fade show active" role="tabpanel" aria-labelledby="edificio" id="edificio" >
                        
        </div> 
         <div class="tab-pane fade" role="tabpanel" aria-labelledby="documenti_edificio" id="documenti_edificio">
               
        </div> <!--
         <div class="tab-pane fade" role="tabpanel" aria-labelledby="dati_digitalizzati" id="dati_digitalizzati">
               
        </div>    
-->     <div class="tab-pane fade" role="tabpanel" aria-labelledby="lavori" id="lavori">
                        
        </div> 
        <div class="tab-pane fade" role="tabpanel" aria-labelledby="documenti_scadenza" id="documenti_scadenza">
               
        </div>
       </div>  
    
<div id="alert_documenti_scaduti" class="modal fade"  role="dialog">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">               
        <h5 class="modal-title">Elenco Documenti in scadenza</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body" id="div_table_scadenza_docs">
       
    </div>
    <div class="modal-footer justify-content-between">
        <button class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
    </div>
</div>
</div>
</div>
    
 </div>   
</body>
</html>
