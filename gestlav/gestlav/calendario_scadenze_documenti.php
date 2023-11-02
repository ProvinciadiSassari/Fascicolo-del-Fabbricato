<?php

session_start();
require_once('conf.inc.php');

$utility = new Utility();
$utility->connetti();


if (isset($_GET['id_fabbricato'])) {   
    $id_fabbricato=$_GET['id_fabbricato'];
        
$query="SELECT IDDescrizione, Percorso_completo, file, Descrizione, UtenteArchiv, Catalogazione, fl_rinnovato, data_scadenza_documento, data_ultima_modifica
        FROM descrizione_files_edifici
        WHERE IDEdificio=$id_fabbricato AND data_scadenza_documento IS NOT NULL AND data_scadenza_documento!='0000-00-00' ORDER BY data_scadenza_documento";

$result=  mysql_query($query);
if (!$result){
    die ($query);
}

$conta=0;
$return_value=null;
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {   
    
    $IDDescrizione=$row['IDDescrizione'];
    $Percorso_completo=$row['Percorso_completo'];
    $file=$row['file'];    
    $Descrizione=$row['Descrizione'];
    $UtenteArchiv=$row['UtenteArchiv'];
    $Catalogazione=$row['Catalogazione'];
    $fl_rinnovato=$row['fl_rinnovato'];
    $data_ultima_modifica=$row['data_ultima_modifica'];
    $data_scadenza_documento=$row['data_scadenza_documento'];  
    if (empty($data_scadenza_documento)) continue;
           
    
    $return_value[$conta]['id']=$IDDescrizione;
    $return_value[$conta]['title']="File: ".$file."\nDescrizione: ".$Descrizione;
    $return_value[$conta]['start']=$data_scadenza_documento;
    $return_value[$conta]['end']=$data_scadenza_documento;
    $return_value[$conta]['url']="";
    $return_value[$conta]['percorso_completo']=$Percorso_completo;
    if ($fl_rinnovato==0)
        $return_value[$conta]['color']="#fe760a";
    else if ($fl_rinnovato==1)
        $return_value[$conta]['color']="blue";
   
        
  $conta++;
}

$query="SELECT d.IDDescrizione, d.IDLavoro,d.Percorso_completo, d.file, d.Descrizione, d.UtenteArchiv, d.Catalogazione, d.fl_rinnovato, d.data_scadenza_documento, d.data_ultima_modifica
        FROM descrizione_files d, lavori l 
        WHERE l.IDEdificio=$id_fabbricato AND d.IDLavoro=l.IDLavoro and d.data_scadenza_documento IS NOT NULL AND d.data_scadenza_documento!='0000-00-00' ORDER BY d.data_scadenza_documento";

$result=  mysql_query($query);
if (!$result){
    die ($query);
}

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {   
    
    $IDDescrizione=$row['IDDescrizione'];
    $IDLavoro=$row['IDLavoro'];
    $Percorso_completo=$row['Percorso_completo'];
    $file=$row['file'];    
    $Descrizione=$row['Descrizione'];
    $UtenteArchiv=$row['UtenteArchiv'];
    $Catalogazione=$row['Catalogazione'];
    $fl_rinnovato=$row['fl_rinnovato'];
    $data_ultima_modifica=$row['data_ultima_modifica'];
    $data_scadenza_documento=$row['data_scadenza_documento'];  
    if (empty($data_scadenza_documento)) continue;
           
    
    $return_value[$conta]['id']=$IDDescrizione;
    $return_value[$conta]['title']="ID Lavoro:".$IDLavoro."\nFile: ".$file."\nDescrizione: ".$Descrizione;
    $return_value[$conta]['start']=$data_scadenza_documento;
    $return_value[$conta]['end']=$data_scadenza_documento;
    $return_value[$conta]['url']="";
    $return_value[$conta]['percorso_completo']=$Percorso_completo;
    if ($fl_rinnovato==0)
        $return_value[$conta]['color']="red";
    else if ($fl_rinnovato==1)
        $return_value[$conta]['color']="blue";
   
        
  $conta++;
}


echo json_encode($return_value);
    
}

?>


