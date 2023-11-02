<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top" style="font-size: 10pt;">
  <div style="margin-right: 10px;">
    <img src="../images/fabbricati.png" height="50"/>    
</div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="dati_amministratore.php"> Elenco Utenti </a>
      </li>
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="fa fa-folder-open"></span> Tabelle
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="fabbricati.php">Fabbricati</a>         
          <a class="dropdown-item" href="referenti_tecnici.php">Referenti Tecnici</a>   
          <a class="dropdown-item" href="strutture.php">Strutture</a> 
          <a class="dropdown-item" href="sottostrutture.php">Sottostrutture</a> 
          <a class="dropdown-item" href="categorie.php">Categorie</a> 
          <a class="dropdown-item" href="tipologie.php">Tipologie</a>         
          <a class="dropdown-item" href="responsabili_procedimento.php">Responsabili Procedimento</a>   
          <a class="dropdown-item" href="istruttori_pratica.php">Istruttori Pratica</a> 
          <a class="dropdown-item" href="professionisti_incaricati.php">Professionisti Incaricati</a> 
          <a class="dropdown-item" href="imprese.php">Imprese</a> 
          <a class="dropdown-item" href="descrizioni_quadro.php">Descrizioni Quadro Economico</a> 
          <a class="dropdown-item" href="sottodescrizioni_quadro.php">Sottodescrizioni Quadro Economico</a> 
        </div>
      </li> 
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="fa fa-cog"></span> Strumenti
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="elimina_lavoro.php">Elimina Lavoro</a>    
        </div>
      </li>
      </ul>
      <div class="nav-item dropdown active" >
           <a class="nav-link dropdown-toggle" style="color: #333;" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="user-icon"><i class="fa fa-user-circle"></i></span>
                    <span class="user-name"></span> <?=$_SESSION["nominativo"]."  (".$_SESSION['username'].")";?>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
<!--                    <a class="dropdown-item" href="profilo.php"><i class="fa fa-user-md" aria-hidden="true"></i> Profilo</a>
                    <a class="dropdown-item" href="cambia_password.php"><i class="fa fa-key" aria-hidden="true"></i> Cambia Password</a>
                     <div class="dropdown-divider"></div>-->
                    <a class="dropdown-item" href="../index.php" style="color: red;"><i class="fa fa-sign-out-alt" aria-hidden="true"></i> Log Out</a>
            </div>
    </div> 
    
  </div>
</nav> 
