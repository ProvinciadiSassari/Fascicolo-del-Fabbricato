<?php
session_start();
require_once('conf.inc.php');
include('conv.php');

$utility = new Utility();
$utility->connetti();

if (!isset($_SESSION['idlevel']) || ($_SESSION['idlevel']!=2 && $_SESSION['idlevel']!=5 && $_SESSION['idlevel']!=7))
{ //se non passo il controllo ritorno all'index
    header("Location: /gestlav/index.php");
}
$id_utente=$_SESSION["iduser"];

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Gestione Lavori</title>
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen" />
<link rel="stylesheet" href="css/base.css" type="text/css" />
<style>
    #map {
  height: 84vh;  
  width: 100%;  
 }
 .dropdown-item {
     font-size: 9pt;
 }
</style>
<script src="js/popper.min.js" type="text/javascript"></script>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js"></script> 
<script src="js/moment.min.js" type="text/javascript"></script>
<script src="js/proj4.js" type="text/javascript"></script>
<script src="js/fontawesome-all.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {   
    
    var data_essere="<?=date("Y-m-d");?>";
    
    $("#id_fabbricato").load("views/select_id_fabbricato_id_comune.php?id_comune="+$("#id_comune").val()+"&data_essere="+data_essere,function(){
        $("#id_fabbricato").val(0);
    });
	
    $(document).on("click","#img_mod",function(evt){
        var id_fabbricato=$(this).attr("alt");
        window.open("scheda_istituto.php?id_fabbricato="+id_fabbricato,"_blank");
    });
       
    $(document).on("change","#id_comune",function(evt){
        $("#id_fabbricato").empty();
        $("#id_fabbricato").load("views/select_id_fabbricato_id_comune.php?id_comune="+$("#id_comune").val()+"&data_essere="+data_essere);
        initMap();
    });
    
    $(document).on("change","#id_fabbricato",function(evt){

        initMap();
    });

    
});

function initMap() {
    var id_comune=$("#id_comune").val();
    var id_fabbricato2=$("#id_fabbricato").val();
        
    $.ajax({
                type: "post",  
                dataType: "json",
                async: false,
                url: "query/retrieve_coordinate_fabbricati.php",  
                data: "id_competenza=<?=$_SESSION["idcompetenza"]?>&id_comune="+id_comune+"&id_fabbricato="+id_fabbricato2,
                success: function(json_array) {
//                    alert(json_array);
                    
                    var utm = "+proj=utm +zone=32";
                    var wgs84 = "+proj=longlat +ellps=WGS84 +datum=WGS84 +no_defs";
                    var markers = [];
                    var long=0,lat=0;
//                    var coordinate0_ = proj4(utm,wgs84,[json_array[2],  json_array[3]]);
//                    var coord0=coordinate0_.toString().split(",");
//                    var center = new google.maps.LatLng(40.759490713442946, 8.963442804234292);
                    var polyCoords = new Array();
                    var k=0;var id_fabbricato=0;
                    for (var i=0; i<json_array.length; i++) {
                        id_fabbricato=json_array[i];i++;i++;
                        lat = json_array[i]; i++; 
                       
                        long = json_array[i];i++;   

                        polyCoords.push(new google.maps.LatLng(lat,long));
                    
                    }
                    
                    var bounds = new google.maps.LatLngBounds();
                    for (var i = 0; i < polyCoords.length; i++) {
                      bounds.extend(polyCoords[i]);
                    }
                    var center = bounds.getCenter();
                    var zoom=10;
                    if (id_comune==0 && (id_fabbricato2==0 || id_fabbricato2===null)) {
                        zoom=10;
                    }
                    else if (json_array.length>20) {
                        zoom=14;
                    }
                    else if (json_array.length<=20) {
                        zoom=16;
                    }
                    
//                    alert("id_comune:"+id_comune+" - id_fabbricato:"+id_fabbricato2+" - json_array.length:"+json_array.length+" - zoom:"+zoom);
                    
                    var mapOptions = {
                        center: center,
                        zoom: zoom,
                        mapTypeId: google.maps.MapTypeId.HYBRID
                      };
//
                    var map = new google.maps.Map(document.getElementById('map'),mapOptions);
                    var marker=[],id_fabbricat_arr=[],k=0,content=[];var id_fabbricato=0;var desc_fabbricato="";var denominazione_fabbricato="";var myLatlng = null;
                    for (var i=0; i<json_array.length; i++) {
                        id_fabbricato=json_array[i];i++;
                        denominazione_fabbricato=json_array[i];i++;
                        id_fabbricat_arr[k]=id_fabbricato;
                        lat = json_array[i]; i++; 
                       
                        long = json_array[i];i++;   
                        desc_fabbricato=json_array[i];
//                        var coordinate_ = proj4(utm,wgs84,[coordinata_punto_est,  coordinata_punto_nord]);
//                        var coord=coordinate_.toString().split(",");
                        myLatlng = new google.maps.LatLng(lat,long);
//                            alert(myLatlng);
                        map.setTilt(45);
                        content[k]="<b>"+id_fabbricato+"</b>"+" - <b>Fabbricato:</b> "+desc_fabbricato+"<br /><br /><b>Scheda Fabbricato:</b> <img src='images/document.png' height='24' class='hand' id='img_mod' alt='"+id_fabbricato+"' >";
                        
                         var infowindow = new google.maps.InfoWindow();
                        
                         marker = new google.maps.Marker({
                            position: myLatlng,
                            map: map,
                            title: id_fabbricato+" - "+denominazione_fabbricato
                        });
                        
                       google.maps.event.addListener(marker, 'click', (function(marker, k) {
                            return function() {
                              infowindow.setContent(content[k]);
                              infowindow.open(map, marker);
                            };
                          })(marker, k));
                        
                        k++;
                        }
                    },
                    error: function() {
                        alert("errore");
                    }
             });
}
</script>

<body>
<?php
include("menu.php");
echo "<br /><br /><br /><br />";
?> 
<div class="container-fluid">      


 <div class="form-group row">
     <label class="col-form-label col-lg-1" style="max-width: 90px;">Comuni: </label>
     <div class="col-lg-2">
    <select class="form-control" id="id_comune">
        <option value="0">- Tutti -</option>
        <?php
        
            $query="select id_comune, desc_comune from comuni where 1=1 ";
            if ($_SESSION["idcompetenza"]>0) {
                $query.=" and id_competenza=".$_SESSION["idcompetenza"];
            }
            $query.=" order by desc_comune asc";
            $result = mysql_query($query);
            if (!$result) {
                die("Could not query the database: 3<br />" . mysql_error());
            }
            $i=0;
            while ($row = mysql_fetch_assoc($result)){
                $id_comune=$row["id_comune"];
                $desc_comune= utf8_encode($row["desc_comune"]);
                echo "<option  value='$id_comune'>$desc_comune</option>";
            }
        ?>

    </select>
    </div>     
    <label class="col-form-label col-lg-1" style="max-width: 100px;">Fabbricati: </label>
     <div class="col-lg-2">
    <select name="id_fabbricato" id="id_fabbricato" class="form-control">
        
    </select>     
  </div>
</div>
<div class="form-group row"> 
     <div  id="map">
         
    </div>    
</div>

<!-- Replace the value of the key parameter with your own API key. -->
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=-----apikey-----&callback=initMap">
</script>    
             
</div>  
</body>
</html>
