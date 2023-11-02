<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (isset($_POST['id_pagamento'])) {
    $ret[0]=$_POST['id_pagamento'];   
   
    $query="SELECT  IDLavoro, IDCategPag, Data_certificato, Import_certificato, IDQuadroEconom_Importo, Aliquota_IVA, IDQuadroEconom_AliqIVA, 
                    Valuta, Descrizione, Note_mandato, IDPropostaPagamento
            FROM pagamenti
            where IDPagamenti=".$_POST['id_pagamento'];
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }
    if ($row = mysql_fetch_assoc($result)){
        $ret[1]=$row["IDLavoro"];        
        $ret[2]=$row["IDCategPag"]; 
        $ret[3]=$utility->convertDateToHTML($row["Data_certificato"]); 
        $ret[4]=$row["Import_certificato"]; 
        $ret[5]=$row["IDQuadroEconom_Importo"];   
        $ret[6]=$row["Aliquota_IVA"]; 
        $ret[7]=$row["IDQuadroEconom_AliqIVA"]; 
        $ret[8]=$row["Valuta"]; 
        $ret[9]=utf8_encode($row["Descrizione"]); 
        $ret[10]=utf8_encode($row["Note_mandato"]); 
        $ret[11]=$row["IDPropostaPagamento"]; 
    }  
    
    echo json_encode($ret);
}
