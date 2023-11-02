<?php
session_start();
require_once('../conf.inc.php');
require_once('../conv.php');

$utility = new Utility();
$utility->connetti();

$id_utente=$_SESSION["iduser"];

if (isset($_GET['nome_file'])) {
    $nome_file = $_GET['nome_file'];
} else {
    $nome_file = "";
}

if (isset($_GET['id_fabbricato'])) {
    $id_fabbricato = $_GET['id_fabbricato'];
} else {
    $id_fabbricato = 0;
}

$cartella ="E".$id_fabbricato."/Archivio Dati Digitalizzati";
$dir='/public/gestlavori/ArchDocEdifici/'.$cartella;

$query="create temporary table risultati_ricerca_d select * from temp";
$result=  mysql_query($query);
if (!$result){
    die ("Could not query the database: <br />". mysql_error());
} 

$query="truncate table risultati_ricerca_d";
$result=  mysql_query($query);
if (!$result){
    die ("Could not query the database: <br />". mysql_error());
}  

?>

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {                

   
    var id_utente=<?=$id_utente;?>;
    
    var oTable = $('#tab_risultati_ricerca_d').dataTable({
        "bJQueryUI": true,
        "aaSorting": [[ 0, "asc" ]],
        "iDisplayLength": 25,
        "sPaginationType": "full_numbers"
    });


    $("#tab_risultati_ricerca_d tbody").click(function(event) {
        $(oTable.fnSettings().aoData).each(function (){
                $(this.nTr).removeClass('row_selected');
        });
        $(event.target.parentNode).addClass('row_selected');
    });  
    
    $("#img_ricerca_file_d").live("click",function(evt){
        evt.stopImmediatePropagation();
        evt.preventDefault();
        var path=$(this).attr("alt");
//        alert(path);
        window.open(path,"_blank");       
    });

});


</script>
<?php


function get_filelist_as_array($dir, $recursive = true, $basedir = '', $include_dirs = false) {
    if ($dir == '') {return array();} else {$results = array(); $subresults = array();}
    if (!is_dir($dir)) {$dir = dirname($dir);} // so a files path can be sent
    if ($basedir == '') {$basedir = realpath($dir)."/";}
   
    $files = scandir($dir);
    foreach ($files as $key => $value){
        if ( ($value != '.') && ($value != '..') ) {
            $path = ($dir."/".$value);
            if (is_dir($path)) {
                // optionally include directories in file list
                if ($include_dirs) {$subresults[] = str_replace($basedir, '', $path."/");}
                // optionally get file list for all subdirectories
                if ($recursive) {
                    $subdirresults = get_filelist_as_array($path, $recursive, $basedir, $include_dirs);
                    $results = array_merge($results, $subdirresults);
                }
            } else {
                // strip basedir and add to subarray to separate file list
                $subresults[] = str_replace($basedir, '', $path);
            }
        }
    }
    // merge the subarray to give the list of files then subdirectory files
    if (count($subresults) > 0) {$results = array_merge($subresults, $results);}
    return $results;
}

    $files = get_filelist_as_array($_SERVER['DOCUMENT_ROOT'].urldecode($dir), true, '', true);
    
    foreach($files as $value){
            $value= str_replace($_SERVER['DOCUMENT_ROOT'], '', $value);
            $query="insert into risultati_ricerca_d (testo) values (". conv_string2sql($value).")";
            $result=  mysql_query($query);
            if (!$result){
                die ("Could not query the database: <br />". mysql_error());
            }
    }
                        
    $query="insert into temp select * from risultati_ricerca_d";
        $result=  mysql_query($query);
        if (!$result){
            die ("Could not query the database: <br />". mysql_error());
        } 

?>
<div class="container-fluid">        

    <table cellpadding="0" cellspacing="0" border="0" class="display" id="tab_risultati_ricerca_d">
       <thead><tr><th>Percorso</th><th align='center'>File</th></tr></thead><tbody>
	
	<?php
      
            $query="select testo from risultati_ricerca_d where LOWER(REPLACE(testo, ' ', '')) like LOWER(REPLACE('%". $nome_file . "%', ' ', ''))";
            $result=  mysql_query($query);
            if (!$result){
                die ("Could not query the database: <br />". mysql_error());
            } 
            
            while ($row = mysql_fetch_assoc($result)){
                $filename=$row["testo"];
               
		echo "<tr>";		
		echo "<td>". utf8_encode($filename)."</td>";
                echo "<td align='center'>";
                if (!is_dir(utf8_encode($_SERVER['DOCUMENT_ROOT'].$filename))) {                                  
                    echo "<img src='images/document.png' class='hand' style='width:24px;' id='img_ricerca_file_d' alt='".utf8_encode($filename)."'/>"; 
                }
                echo "</td>";
		echo "</tr>";	
            }	     
        ?>
	</tbody>
    </table>
</div> 


