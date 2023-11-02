<?php
    session_start();                         
    require_once('conf.inc.php');    
    
    $offline=0; 
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
    <title>Multiss - Gestione Lavori</title>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />

    <link href="css/materialdesignicons.min.css" rel="stylesheet" type="text/css"/>
    <link href="css/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>

        
    <script src="js/jquery.js" type="text/javascript"></script>

    <script src="js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript">
    $(document).ready( function(){
        
        $("#div_login_error").hide();

        $("#but_login").click(function(e){
          login_(); 

            return false;
        });

            
        $("#frm_login").keypress(function(e) {

            if(e.which == 13) {
               login_(); 
            }
            
        });       

    });
    
    function login_() {
       var username=$("#username").val();
        var password=$("#password").val();
         $.ajax({
            type: "post",
            async: false,
            url: "redirect.php", 
            data:"username="+username+"&password="+password,
            success: function(msg) { 
//                        alert(msg); 
              if (msg!=0) {
                  $("#div_login_error").hide();
                  window.location=msg;
              } 
              else {
                  $("#div_login_error").html("Credenziali di accesso non corrette. Riprovare.");
                  $("#div_login_error").show();
              }
            },
            error: function() {
//                alert ("error");
            }
        });
  }
  
    </script>
  </head>
  <body>
    <div class="container-scroller">
    <div class="container-fluid page-body-wrapper">
      <div class="row">
        <div class="content-wrapper full-page-wrapper d-flex align-items-center auth-pages">
            <div class="card col-lg-4 mx-auto" >
              
            <div class="card-body px-5 py-5">
                <img src="images/fabbricati.png" style="width: 100%; margin-bottom:15px;"/>
              <h3 class="card-title text-center mb-3">Login</h3>
              <form id="frm_login">
                <div class="form-group">
                  <label>Username</label>
                  <input type="text" name="username" id="username" placeholder="Username" class="form-control p_input" />                  					                   
                </div>
                <div class="form-group">
                  <label>Password</label>
                  <input type="password" name="password" id="password" placeholder="Password" class="form-control p_input">
                </div>
                <div class="form-group d-flex align-items-center justify-content-between">
<!--                  <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input">
                        Remember me
                      </label>
                  </div>-->
                  <!--<a href="password_dimenticata.php" class="forgot-pass">Password dimenticata?</a>-->
                  <!--<a href="registrazione.php" class="forgot-pass">Registrazione</a>-->
                </div>
                <div class="text-center">
                  <button type="submit" id="but_login" class="btn btn-primary btn-block enter-btn">Login</button>
                </div>
                <div id="div_login_error" class="text-center" style="color: red;">
      
                </div>
<!--                <div class="d-flex">
                  <button class="btn btn-facebook mr-2 col">
                      <i class="mdi mdi-facebook"></i> Facebook
                  </button>
                  <button class="btn btn-google col">
                      <i class="mdi mdi-google-plus"></i> Google plus
                  </button>
                </div>
                <p class="sign-up">Don't have an Account?<a href="#"> Sign Up</a></p>-->
              </form>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- row ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
             
  </body>
</html>