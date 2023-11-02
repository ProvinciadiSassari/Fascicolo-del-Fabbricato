<?php
session_start();


for ($i=800;$i<=825;$i++) {
    $id_fabbricato="E".$i;
    $path=$_SERVER['DOCUMENT_ROOT']."/public/gestlavori/ArchDocEdifici/$id_fabbricato/";
    if (!is_dir($path)) {
	mkdir($path,0777);
    }
    $dir="01-Estremi identificativi del fabbricato/";
    $path1=$path.$dir;
    mkdir($path1,0777); 
    $dir1="a-Caratteristiche del fabbricato/";
    $path2=$path1.$dir1;
    mkdir($path2,0777); 
    $dir1="b-Identificazione del fabbricato/";
    $path2=$path1.$dir1;
    mkdir($path2,0777);
    $dir1="c-Manufatti contermini/";
    $path2=$path1.$dir1;
    mkdir($path2,0777);
    $dir1="d-Caratteristiche del fabbricato e dati generali/";
    $path2=$path1.$dir1;
    mkdir($path2,0777);
    $dir1="e-Catasto Terreni/";
    $path2=$path1.$dir1;
    mkdir($path2,0777);
    $dir1="f-Castasto/";
    $path2=$path1.$dir1;
    mkdir($path2,0777);
    $dir1="g-Dati urbanistici tecnici generali/";
    $path2=$path1.$dir1;
    mkdir($path2,0777);
    $dir="02-Accertamento e analisi statica/";
    $path1=$path.$dir;
    mkdir($path1,0777); 
    $dir1="a-Documentazione statica del fabbricato/";
    $path2=$path1.$dir1;
    mkdir($path2,0777); 
    $dir1="b-Accertamento e analisi statica/";
    $path2=$path1.$dir1;
    mkdir($path2,0777);
    $dir1="c-Elaborati tecnici disponibili/";
    $path2=$path1.$dir1;
    mkdir($path2,0777);
    $dir1="d-Grado di conservazione e consistenza dei prospetti e delle finiture/";
    $path2=$path1.$dir1;
    mkdir($path2,0777);
    $dir1="e-Caratteristiche strutturali presenti nel fabbricato/";
    $path2=$path1.$dir1;
    mkdir($path2,0777);
    $dir1="f-Suppellettili presenti/";
    $path2=$path1.$dir1;
    mkdir($path2,0777);
    $dir="03-Accertamento e sicurezza impianti/";
    $path1=$path.$dir;
    mkdir($path1,0777); 
    $dir1="a-Impianto elettrico/";
    $path2=$path1.$dir1;
    mkdir($path2,0777); 
    $dir1="b-Impianto idrico - sanitario/";
    $path2=$path1.$dir1;
    mkdir($path2,0777);
    $dir1="c-Impianto antincendio/";
    $path2=$path1.$dir1;
    mkdir($path2,0777);
    $dir1="d-Reti e impianti speciali/";
    $path2=$path1.$dir1;
    mkdir($path2,0777);
    $dir1="e-Impianto riscaldamento - climatizzazione/";
    $path2=$path1.$dir1;
    mkdir($path2,0777);
    $dir="04-Sintesi degli accertamenti e provvedimenti/";
    $path1=$path.$dir;
    mkdir($path1,0777); 
    $dir1="a-Sintesi degli accertamenti e provvedimenti/";
    $path2=$path1.$dir1;
    mkdir($path2,0777); 
    $dir="Cost management/";
    $path1=$path.$dir;
    mkdir($path1,0777); 
    $dir="Documentazione fotografica/";
    $path1=$path.$dir;
    mkdir($path1,0777);
    $dir="Gestione anagrafe patrimoniale/";
    $path1=$path.$dir;
    mkdir($path1,0777);
    $dir="Planimetrie, superfici e volumi/";
    $path1=$path.$dir;
    mkdir($path1,0777); 
    $dir1="Planimetrie/";
    $path2=$path1.$dir1;
    mkdir($path2,0777); 
    $dir1="Superfici e Volumi/";
    $path2=$path1.$dir1;
    mkdir($path2,0777);
    
    echo "Creata cartella per fabbricato ID: $i <br />";
}