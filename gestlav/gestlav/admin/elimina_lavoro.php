<?php
session_start();
require_once('../conf.inc.php');
include('../conv.php');

$utility = new Utility();
$utility->connetti();

if (!isset($_SESSION["idlevel"]) || $_SESSION["idlevel"]!=1)
    header("Location: /gestlav/index.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Gestione Lavori</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />
<link href="../css/bootstrap.min.css" rel="stylesheet" media="screen" />
<link rel="stylesheet" href="../css/base.css" type="text/css" />
<link href="../css/sweetalert2.min.css" rel="stylesheet" type="text/css"/>
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/bootstrap.min.js"></script> 
<script src="../js/moment.min.js" type="text/javascript"></script>
<script src="../js/fontawesome-all.js" type="text/javascript"></script>
<script src="../js/sweetalert2.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready( function(){
  
    
    $("#but_elimina_lavoro").click(function(e){
    
        var inp_id_lavoro=$("#inp_id_lavoro").val();               
        
        if (inp_id_lavoro!="") {
            var ajax_data="";

            ajax_data={
                inp_id_lavoro:inp_id_lavoro           
            };

            $.ajax({
                type: "post",
                url: "actions/submit_elimina_lavoro.php",                                                 
                data: ajax_data,
                success: function(id_lavoro) { 
    //                alert(msg);                   
                      if (id_lavoro>0) {                          
                          $("#inp_id_lavoro").val("");
                          swal(
                    'Informazione',
                    'Operazione avvenuta con successo.',
                    'success'
                  );
                        }                 
                },
                error: function() {
                    alert ("error");
                }
            });
        }
        else {
             swal(
                    'Informazione',
                    "Inserire l'ID Lavoro.",
                    'warning'
                  );
        }
    });
});
</script>

</head>
<body>
<?php 
include('menu.php');
?>
<br /><br /><br /><br />    
<h4 style="text-align: center;">ELIMINA LAVORO</h4><br /><br />  
<div class="container-fluid">  
    <div class="form-group row">
        <label for="inp_id_lavoro" class="control-label col-lg-1" style="color:red;max-width: 120px;">ID Lavoro</label>
        <div class="col-lg-2">
        <input type="text" id="inp_id_lavoro" class="form-control" value=""/>
       </div>
    </div>
    <hr />
    <div class="form-group row">   
     <div class="col-lg-2" >
    <button id='but_elimina_lavoro' class='btn btn-danger' >Elimina Lavoro</button>
    </div>
    </div>    
</div>    
</body>
</html>