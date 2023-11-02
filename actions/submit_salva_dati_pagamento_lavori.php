<?php

session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

$data_certificato=$utility->convertDateToDB($_POST["inp_data_certificato"]);

if ($_POST["tipo_operazione"]=="ins") {
    $query="INSERT INTO pagamenti (
            IDLavoro, IDCategPag, Data_certificato, Import_certificato, IDQuadroEconom_Importo, Aliquota_IVA, IDQuadroEconom_AliqIVA, Valuta, Descrizione, Note_mandato, IDPropostaPagamento
          ) 
          VALUES
            (
              ".$_POST["id_lavoro"].", 1, '$data_certificato', ".$_POST["inp_importo_certificato"].", ".$_POST["id_descrizione_quadro"].", ".$_POST["inp_aliquota_iva"].", ".$_POST["id_descrizione_quadro"].", '2', ".conv_string2sql($_POST["ta_descrizione"]).", ".conv_string2sql($_POST["ta_note"]).", ".conv_string2sql($_POST["inp_id_proposta_pagamento"])."
            )";
    
    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    } 
}
else if ($_POST["tipo_operazione"]=="mod") {
    
    $query="UPDATE pagamenti
            SET           
              Data_certificato = '".$data_certificato."',
              Import_certificato = ".$_POST["inp_importo_certificato"].", 
              IDQuadroEconom_Importo=".$_POST["id_descrizione_quadro"].",    
              Aliquota_IVA = ".$_POST["inp_aliquota_iva"].",                      
              Descrizione = ".conv_string2sql($_POST["ta_descrizione"]).",
              Note_mandato = ".conv_string2sql($_POST["ta_note"]).",
              IDPropostaPagamento = ".conv_string2sql($_POST["inp_id_proposta_pagamento"])." 
            WHERE IDPagamenti = ".$_POST["id_pagamento"];

    $result = mysql_query($query);
    if (!$result){
            die ("Could not query the database: <br />". mysql_error());
    }   
}