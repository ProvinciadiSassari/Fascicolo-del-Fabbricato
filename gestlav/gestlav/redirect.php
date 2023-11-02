<?php
session_start();
require_once('conf.inc.php');
require_once('conv.php');

$utility = new Utility();
$utility->connetti(); 

function convert($str){
        $ky='forzainter';
        if($ky=='')return $str;
        $ky=str_replace(chr(32),'',$ky);
        if(strlen($ky)<8)exit('key error');
        $kl=strlen($ky)<32?strlen($ky):32;
        $k=array();for($i=0;$i<$kl;$i++){
        $k[$i]=ord($ky{$i})&0x1F;}
        $j=0;for($i=0;$i<strlen($str);$i++){
        $e=ord($str{$i});
        $str{$i}=$e&0xE0?chr($e^$k[$j]):chr($e);
        $j++;$j=$j==$kl?0:$j;}
        return $str;
 }
    
$username = trim($_POST['username']);
$pass = convert(trim($_POST['password']),'forzainter');  
   
   $query="SELECT a006_ID,a006_livello,nominativo,id_competenza,id_squadra,id_tipo_settore,fl_avanzato,id_responsabile,id_zona FROM utenti_lav WHERE a006_descrizione=".conv_string2sql($username)." AND a006_password=".conv_string2sql($pass);
   
   $result = mysql_query($query);   
    if (!$result){
            die ($query."Could not query the database: <br />". mysql_error());
    }

	// Fetch and display the results
	if ($row = mysql_fetch_array($result, MYSQL_ASSOC)){            
            $id_competenza = $row["id_competenza"];
            $id_squadra = $row["id_squadra"];
            $id_tipo_settore = $row["id_tipo_settore"];           
            $_SESSION['iduser']=$row["a006_ID"];;
            $_SESSION['idlevel']=$row["a006_livello"];
            $_SESSION['username']=$username;
            $_SESSION['nominativo']=$row["nominativo"];
            $_SESSION['idcompetenza']=$id_competenza;
            $_SESSION['idsquadra']=$id_squadra;
            $_SESSION['avanzato']=$row["fl_avanzato"];
            $_SESSION['idresponsabile']=$row["id_responsabile"];
            $_SESSION['idzona']=$row["id_zona"];
            $_SESSION['idtiposettore']=1;
            $_SESSION['comunicazione']=0;
	}
        
        $ret= mysql_num_rows($result);  
        
        
   $query="SELECT soglia_doc_fabbricati_gg, soglia_doc_lavori_gg FROM configurazione WHERE id=1";
    $result=  mysql_query($query);
    if (!$result){
        die ($query);
    }
    if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $_SESSION['soglia_doc_fabbricati_gg']=$row['soglia_doc_fabbricati_gg']; 
        $_SESSION['soglia_doc_lavori_gg']=$row['soglia_doc_lavori_gg']; 
    }     

	
$result1=$ret;
	      
        
if ($result1==0) {		
        $return_value=0;                     
}
else { 

    if ($_SESSION['idlevel']==1){
            $return_value="admin/dati_amministratore.php";
     }
     else if ($_SESSION['idlevel']==2){
            $return_value="mappa_istituti_intro.php";
     }
     else if ($_SESSION['idlevel']==5){
             $return_value="mappa_istituti_intro.php";
     }  
     else if ($_SESSION['idlevel']==7){
             $return_value="mappa_istituti_intro.php";
     }  
}

echo $return_value;