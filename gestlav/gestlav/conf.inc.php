<?php
class Utility {
   private $db_host = 'localhost';
   private $db_username = ''; //db user
   private $db_password = ''; //password
   private $db_database = ''; //db name
   

   public function connetti() {
  

    $db = mysql_connect($this->db_host,$this->db_username,$this->db_password) or die("error=could not connect to $this->host");
    $db = mysql_select_db($this->db_database);
    mysql_set_charset('utf8');
    if(!$db)
    {
      print "error=could not connect to $this->db_database";
      exit;
    }
    else return true;
   }
   
   public function convert($str){
        $ky='key'; //encoding key
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
    
    public function rrmdir_edificio($dir,$id_fabbricato) {
        if (is_dir($dir)) {
          $objects = scandir($dir);
          foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
              if (filetype($dir."/".$object) == "dir") {
                  $this->rrmdir_edificio($dir."/".$object); 
              }    
              else {                                     
                    $path1=$dir."/".$object;
                    
                    //prima cancello l'eventuale record per lo stesso file
                    $query="delete from descrizione_files_edifici where File like '%$object%' and IDEdificio=$id_fabbricato";
                    $result = mysql_query($query);
                    if (!$result){
                            die ("Could not query the database: <br />". mysql_error());
                    }        
                     unlink($path1);
              }
            }
          }
          reset($objects);
          rmdir($dir);
        }
    } 
    
    
    public function delSpecials_($str)
    {
        $str = str_replace(' ', '_', $str);
        return preg_replace("/[^a-zA-Z0-9_-]/", "", $str);
    }

    
    public function ControlloEmail($email){
	// elimino spazi, "a capo" e altro alle estremità della stringa
	$email = trim($email);

	// se la stringa è vuota sicuramente non è una mail
	if(!$email) {
		return false;
	}

	// controllo che ci sia una sola @ nella stringa
	$num_at = count(explode( '@', $email )) - 1;
	if($num_at != 1) {
		return false;
	}

	// controllo la presenza di ulteriori caratteri "pericolosi":
	if(strpos($email,';') || strpos($email,',') || strpos($email,' ')) {
		return false;
	}

	// la stringa rispetta il formato classico di una mail?
	if(!preg_match( '/^[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}$/', $email)) {
		return false;
	}

	return true;
   }
   
   public function createFolderEdificio($id_fabbricato) {
             
       $cartella=$_SERVER['DOCUMENT_ROOT'].'/public/gestlavori/ArchDocEdifici/E'.$id_fabbricato."/";
       
       if (!is_dir($cartella)) {
           mkdir($cartella, 0777);
       }              
   }
   
   public function createFolderLavori($id_lavoro) {
             
       $cartella=$_SERVER['DOCUMENT_ROOT'].'/public/gestlavori/Archivio Lavori/L'.$id_lavoro."/";
       
       if (!is_dir($cartella)) {
           mkdir($cartella, 0777);
       }              
   }
   
   public function getTOTSpeseGeneraliProgetto($id_lavoro) {
      $ret=0;
      
      
      if (empty($id_lavoro)) return $ret;
      
      $query="select sum(imp_qe_progetto) as somma from quadro_economico_generale where id_lavoro=$id_lavoro and id_sottodescrizione_quadro between 6 and 13";
      $result=  mysql_query($query);
       if (!$result){
            die ("Could not query the database: 19<br />". mysql_error());
       }       
       if ($row=  mysql_fetch_array($result)) {
            $ret=$row["somma"];
       }         
        
       return $ret;
   }
   
   public function getTOTLavoriEconomiaProgetto($id_lavoro) {
      $ret=0;
      
      
      if (empty($id_lavoro)) return $ret;
      
      $query="select sum(imp_qe_progetto) as somma from quadro_economico_generale where id_lavoro=$id_lavoro and id_sottodescrizione_quadro=5";
      $result=  mysql_query($query);
       if (!$result){
            die ("Could not query the database: 19<br />". mysql_error());
       }       
       if ($row=  mysql_fetch_array($result)) {
            $ret=$row["somma"];
       }         
        
       return $ret;
   }
   
   public function getTOTCNPAIAProgetto($id_lavoro) {
      $ret=0;
      
      
      if (empty($id_lavoro)) return $ret;
      
      $query="select sum(imp_qe_progetto) as somma from quadro_economico_generale where id_lavoro=$id_lavoro and id_sottodescrizione_quadro=14";
      $result=  mysql_query($query);
       if (!$result){
            die ("Could not query the database: 19<br />". mysql_error());
       }       
       if ($row=  mysql_fetch_array($result)) {
            $ret=$row["somma"];
       }         
        
       return $ret;
   }
   
   public function getTOTIndaginiGeneraliProgetto($id_lavoro) {
      $ret=0;
      
      
      if (empty($id_lavoro)) return $ret;
      
      $query="select sum(imp_qe_progetto) as somma from quadro_economico_generale where id_lavoro=$id_lavoro and id_sottodescrizione_quadro between 15 and 21";
      $result=  mysql_query($query);
       if (!$result){
            die ("Could not query the database: 19<br />". mysql_error());
       }       
       if ($row=  mysql_fetch_array($result)) {
            $ret=$row["somma"];
       }         
        
       return $ret;
   }
   
   public function getTOTSpeseRUPProgetto($id_lavoro) {
      $ret=0;
      
      
      if (empty($id_lavoro)) return $ret;
      
      $query="select sum(imp_qe_progetto) as somma from quadro_economico_generale where id_lavoro=$id_lavoro and id_sottodescrizione_quadro=30";
      $result=  mysql_query($query);
       if (!$result){
            die ("Could not query the database: 19<br />". mysql_error());
       }       
       if ($row=  mysql_fetch_array($result)) {
            $ret=$row["somma"];
       }         
        
       return $ret;
   }
   
   
   public function weekNumber($date)
   {
       return ceil(substr($date, -2) / 7);
   }
   
   public function getAge($year,$month,$day) {
       
    $anno_nascita = $year;
    $mese_nascita = $month;
    $giorno_nascita = $day;

    //Non modificare da qui in poi---------------
    $anno_oggi = date('Y');
    $mese_oggi = date('m');
    $giorno_oggi = date('g');

    $somma1 = $mese_nascita+$giorno_nascita;
    $somma2 = $mese_oggi+$giorno_oggi;

    if ($somma2>$somma1){
    $eta = $anno_oggi - $anno_nascita;
    $eta = $eta - 1;
    }else{
        $eta = $anno_oggi - $anno_nascita;

        return $eta;
    }
   }
   
public function fDateDiff($dateFrom, $dateTo, $unit = 'd')
{
    $difference = 0;

    $date1 = $dateFrom;
    $date2 = $dateTo;

    if( $date1 > $date2 ){
        return 0;
    }

    $diff = $date2 - $date1;

    $days = 0;
    $hours = 0;
    $minutes = 0;
    $seconds = 0;

    if ($diff % 86400 <= 0){ // Ci sono 86400 secondi in un giorno
        $days = $diff / 86400;
    }

    if($diff % 86400 > 0){
        $rest = ($diff % 86400);
        $days = ($diff - $rest) / 86400;

        if($rest % 3600 > 0 ){
            $rest1 = ($rest % 3600);
            $hours = ($rest - $rest1) / 3600;

            if( $rest1 % 60 > 0 ){
                $rest2 = ($rest1 % 60);
                $minutes = ($rest1 - $rest2) / 60;
                $seconds = $rest2;
            }else{
                $minutes = $rest1 / 60;
            }
        }else{
            $hours = $rest / 3600;
        }
    }

    //In quel unit� restituire
    //la differenza ?
    switch(strtolower($unit)){
        case 'd':
            $partialDays = 0;
            $partialDays += ($seconds / 86400);
            $partialDays += ($minutes / 1440);
            $partialDays += ($hours / 24);
            $difference = $days + $partialDays;
            break;

        case 'h':
            $partialHours = 0;
            $partialHours += ($seconds / 3600);
            $partialHours += ($minutes / 60);
            $difference = $hours + ($days * 24) + $partialHours;
            break;

        case 'm':
            $partialMinutes = 0;
            $partialMinutes += ($seconds / 60);
            $difference = $minutes + ($days * 1440) + ($hours * 60) + $partialMinutes;
            break;

        case 's':
            $difference = $seconds + ($days * 86400) + ($hours * 3600) + ($minutes * 60);
            break;

        case 'a':
            $difference = array (
                "days" => $days,
                "hours" => $hours,
                "minutes" => $minutes,
                "seconds" => $seconds
                 );
            break;
    }

    //Ritorno la differenza
    if(is_array($difference)){
        return $difference;
    }else{
        return round($difference);
    }
}
   
   public function get_time_difference( $start, $end )
    {
        $uts['start']      =    strtotime( $start );
        $uts['end']        =    strtotime( $end );
        if( $uts['start']!==-1 && $uts['end']!==-1 )
        {
            if( $uts['end'] >= $uts['start'] )
            {
                $diff    =    $uts['end'] - $uts['start'];
                if( $days=intval((floor($diff/86400))) )
                    $diff = $diff % 86400;
                if( $hours=intval((floor($diff/3600))) )
                    $diff = $diff % 3600;
                if( $minutes=intval((floor($diff/60))) )
                    $diff = $diff % 60;
                $diff    =    intval( $diff );            
                return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
            }
            else
            {
                trigger_error( "Ending date/time is earlier than the start date/time", E_USER_WARNING );
            }
        }
        else
        {
            trigger_error( "Invalid date/time data detected", E_USER_WARNING );
        }
        return( false );
    }

   public function FormatNumber($number, $cents = 1) { // cents: 0=never, 1=if needed, 2=always
      if (is_numeric($number)) { // a number
        if (!$number) { // zero
          $money = ($cents == 2 ? '0.00' : '0'); // output zero
        } else { // value
          if (floor($number) == $number) { // whole number
            $money = number_format($number, ($cents == 2 ? 2 : 0)); // format
          } else { // cents
            $money = number_format(round($number, 2), ($cents == 0 ? 0 : 2)); // format
          } // integer or decimal
        } // value
        $money=  str_replace(".", "#", $money);
        $money=  str_replace(",", ".", $money);
        $money=  str_replace("#", ",", $money);
        return '&#8364; '.$money;
      } // numeric
   }
   
   public function lastDay($month = '', $year = '') {
       if (empty($month)) {
          $month = date('m');
       }
       if (empty($year)) {
          $year = date('Y');
       }
       $result = strtotime("{$year}-{$month}-01");
       $result = strtotime('-1 second', strtotime('+1 month', $result));
       return date('d', $result);
   }
   
   public function lastMonthDay($month = '', $year = '') {
       if (empty($month)) {
          $month = date('m');
       }
       if (empty($year)) {
          $year = date('Y');
       }
       $result = strtotime("{$year}-{$month}-01");
       $result = strtotime('-1 second', strtotime('+1 month', $result));
       return date('Y-m-d', $result);
   }

   public function getIdUserFromISTAT($codiceistat,$ruolo) {
       $ret=0;

       $query="select id_user from accounts where codiceistat='$codiceistat' and id_ruolo=$ruolo";

       $result = mysql_query($query);

       if (!$result)
           die("Impossibile accedere al DataBase - getIdUserFromISTAT");
             
       if ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
           $ret=$row['id_user'];
       }

       return $ret;
   }

   public function max_id_utente() {
       $query="SHOW TABLE STATUS LIKE 'utenti'";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");
       $max_id=0;
       if ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
           $max_id=$row['Auto_increment'];
       }
       return $max_id;
   }

   public function max_id_lavoro() {
       $query="SHOW TABLE STATUS LIKE 'lavori'";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");
       $max_id=0;
       if ($row = mysql_fetch_array($result)){
           $max_id=$row['Auto_increment'];
       }
       return $max_id;
   }
   
   public function max_progressivo_ordine() {
       $query="select max(progressivo_ordine) as max_ord from sottodescrizioni_quadro where id_sottodescrizione_quadro<300";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");
       $max_id=0;
       if ($row = mysql_fetch_array($result)){
           $max_id=$row['max_ord'];
       }
       return $max_id+1;
   }
   
   public function max_id_sottodescrizione_quadro() {
       $query="select max(id_sottodescrizione_quadro) as max_ord from sottodescrizioni_quadro where id_sottodescrizione_quadro<300";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");
       $max_id=0;
       if ($row = mysql_fetch_array($result)){
           $max_id=$row['max_ord'];
       }
       return $max_id+1;
   }
   
   public function aggiorna_progressivi_ordine_update_up($ordine_min,$ordine_max) {
       
       $query="update sottodescrizioni_quadro set progressivo_ordine=(progressivo_ordine+1) where progressivo_ordine>=$ordine_min and progressivo_ordine<$ordine_max";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");                    
   }
   
   public function aggiorna_progressivi_ordine_update_down($ordine_min,$ordine_max) {
       
       $query="update sottodescrizioni_quadro set progressivo_ordine=(progressivo_ordine-1) where progressivo_ordine>$ordine_min and progressivo_ordine<=$ordine_max";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");                    
   }
   
   public function aggiorna_progressivi_ordine_go($ordine) {
       
       $query="update sottodescrizioni_quadro set progressivo_ordine=(progressivo_ordine+1)*(-1) where progressivo_ordine>=$ordine";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");                    
   }
   
   public function aggiorna_progressivi_ordine_back($ordine) {
       
       $query="update sottodescrizioni_quadro set progressivo_ordine=(progressivo_ordine)*(-1) where progressivo_ordine<0";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");                    
   }
   
   
   public function aggiorna_progressivi_ordine_delete($ordine) {
       
       $query="update sottodescrizioni_quadro set progressivo_ordine=(progressivo_ordine-1) where progressivo_ordine>$ordine";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");                    
   }
   
   public function getProgressivoOrdine($id) { //id_sottodescrizione-quadro
       $ret=0;
       
       if (empty($id) || $id==0) return $ret;
       
       $query="select progressivo_ordine from sottodescrizioni_quadro where id_sottodescrizione_quadro=$id";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");
      
       if ($row = mysql_fetch_array($result)){
           $ret=$row['progressivo_ordine'];
       }
       return $ret;
   }

   public function max_id_soggetto() {
       $query="SHOW TABLE STATUS LIKE 'altri_soggetti'";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");
       $max_id=0;
       if ($row = mysql_fetch_array($result)){
           $max_id=$row['Auto_increment'];
       }
       return $max_id;
   }

   public function convertDateToDB($date_in) {
       $date_out="";
       if ($date_in!="" && !strpos($date_in,'/00/')) {
           list($day,$month,$year) = explode("/", $date_in);
           $date_out = $year."-".$month."-".$day;
       }
       return $date_out;
   }

   public function convertDateToHTML($date_in) {
       $date_out="";
       if ($date_in!="" && !strpos($date_in,'-00-')) {
           $date_in = substr($date_in, 0, 10);
           list($year,$month,$day) = explode("-", $date_in);
           $date_out = $day."/".$month."/".$year;
       }
       return $date_out;
   }

   public function convertDateTimeToHTML($datetime_in) {
       $datetime_out="";
       if ($datetime_in!="") {
           $date_in = substr($datetime_in, 0, 10);
           $time_in = substr($datetime_in, 11,5);
           list($year,$month,$day) = explode("-", $date_in);
           $datetime_out = trim($day."/".$month."/".$year." ".$time_in);
       }
       return $datetime_out;
   }
   
   public function convertDateTimeToDB($datetime_in) {
       $datetime_out="";
       if ($datetime_in!="") {
           $date_in = substr($datetime_in, 0, 10);
           $time_in = substr($datetime_in, 11,5);
           list($day,$month,$year) = explode("/", $date_in);
           
           $datetime_out = trim($year."-".$month."-".$day." ".$time_in);
       }
       return $datetime_out;
   }
   
   
   public function getDescrizioneUnitaTempo($id) {
       $ret="";
       $query="select Unita_tempo from unitatempo where IDUnitaTempo=".(int)$id;
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");

       if ($row = mysql_fetch_array($result)){
           $ret=$row['Unita_tempo'];
       }
       return $ret;
   }

   public function getDescrizioneMese($id_mese) {
       $ret="";
       $query="select desc_mese from mesi where id_mese=".(int)$id_mese;
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");

       if ($row = mysql_fetch_array($result)){
           $ret=$row['desc_mese'];
       }
       return $ret;
   }

   public function controlloCFUtenteMod($cf_in,$id) {
       $ret=0;
       if (strlen($cf_in)==16 || strlen($cf_in)==11) {
           $query="select count(*) as conta from utenti where cf_uten='$cf_in' and id_utente<>$id";
          
           $result = mysql_query($query);
           if (!$result)
               die("Impossibile accedere al DataBase");
           $conta=0;
           if ($row = mysql_fetch_array($result)){
               $conta=$row['conta'];
           }
           if ($conta!=0) $ret=-1;
       }
       else $ret=-1;

       return $ret;
   }

   public function controlloCFUtente($cf_in) {
       $ret=0;
       if (strlen($cf_in)==16 || strlen($cf_in)==11) {
           $query="select count(*) as conta from utenti where cf_uten='$cf_in'";
           $result = mysql_query($query);
           if (!$result)
               die("Impossibile accedere al DataBase");
           $conta=0;
           if ($row = mysql_fetch_array($result)){
               $conta=$row['conta'];
           }
           if ($conta!=0) $ret=-1;
       }
       else $ret=-1;

       return $ret;
   }

   public function controlloCFOperatoreMod($cf_in,$id) {
       $ret=0;
       if (strlen($cf_in)==16 || strlen($cf_in)==11) {
           $query="select count(*) as conta from operatori where cf_oper='$cf_in' and id_operatore<>$id";
           $result = mysql_query($query);
           if (!$result)
               die("Impossibile accedere al DataBase");
           $conta=0;
           if ($row = mysql_fetch_array($result)){
               $conta=$row['conta'];
           }
           if ($conta!=0) $ret=-1;
       }
       else $ret=-1;

       return $ret;
   }

   public function controlloCFOperatore($cf_in) {
       $ret=0;
       if (strlen($cf_in)==16 || strlen($cf_in)==11) {
           $query="select count(*) as conta from operatori where cf_oper='$cf_in'";
           $result = mysql_query($query);
           if (!$result)
               die("Impossibile accedere al DataBase");
           $conta=0;
           if ($row = mysql_fetch_array($result)){
               $conta=$row['conta'];
           }
           if ($conta!=0) $ret=-1;
       }
       else $ret=-1;

       return $ret;
   }

   public function controlloCFSoggettoMod($cf_in,$id,$idc) {
       $ret=0;
       if (strlen($cf_in)==16 || strlen($cf_in)==11) {
           $query="select count(*) as conta from legami_soggetti_coop l, altri_soggetti a where l.id_soggetto=a.id_soggetto and l.id_cooperativa=$idc and a.cf_sogg='$cf_in' and l.id_soggetto<>$id";
           $result = mysql_query($query);
           if (!$result)
               die("Impossibile accedere al DataBase");
           $conta=0;
           if ($row = mysql_fetch_array($result)){
               $conta=$row['conta'];
           }
           if ($conta!=0) $ret=-1;
       }
       else $ret=-1;

       return $ret;
   }

   public function controlloCFSoggetto($cf_in,$idc) {
       $ret=0;
       if (strlen($cf_in)==16 || strlen($cf_in)==11) {
           $query="select count(*) as conta from legami_soggetti_coop l, altri_soggetti a where l.id_soggetto=a.id_soggetto and l.id_cooperativa=$idc and a.cf_sogg='$cf_in'";
           $result = mysql_query($query);
           if (!$result)
               die("Impossibile accedere al DataBase");
           $conta=0;
           if ($row = mysql_fetch_array($result)){
               $conta=$row['conta'];
           }
           if ($conta!=0) $ret=-1;
       }
       else $ret=-1;

       return $ret;
   }

   public function calcoloCostoIvato($ore,$tipo,$feriale,$ts,$comune) { //$tipo=1 generica $tipo=2 qualificata
       $ret=0;                                              //$feriale=1 feriale $feriale=2 festiva
       $query="";
       if ($ts==1) { //SAD
           if ($feriale==1) {
               $query="select costo_ora_tipo_assistenza,iva_tipo_assistenza from tipi_assistenza where id_tipo_servizio=1 and codiceistat='$comune' and id_tipo_prestazione=$tipo and fl_festivo=0";
           } else
           if ($feriale==2) {
               $query="select costo_ora_tipo_assistenza,iva_tipo_assistenza from tipi_assistenza where id_tipo_servizio=1 and codiceistat='$comune' and id_tipo_prestazione=$tipo and fl_festivo=1";
           } 
       }
       else if ($ts==2) { //162
           if ($feriale==1) {
               $query="select costo_ora_tipo_assistenza,iva_tipo_assistenza from tipi_assistenza where id_tipo_servizio=2 and codiceistat='$comune' and id_tipo_prestazione=$tipo and fl_festivo=0";
           } else
           if ($feriale==2) {
               $query="select costo_ora_tipo_assistenza,iva_tipo_assistenza from tipi_assistenza where id_tipo_servizio=2 and codiceistat='$comune' and id_tipo_prestazione=$tipo and fl_festivo=1";
           }
       }

       if (!empty($query)) {
           $result=mysql_query($query);

           if (!$result)
                   die("Impossibile accedere al DataBase");
           else
           {
                if ($row=  mysql_fetch_array($result)) {
                    $ret=$row['costo_ora_tipo_assistenza']+($row['costo_ora_tipo_assistenza']*$row['iva_tipo_assistenza']/100);
                }
           }
       }
       else $ret=0;

       
       return round($ret,2);
   }

   public function calcoloCostoNoIvato($ore,$tipo,$feriale,$ts,$comune) { //$tipo=1 generica $tipo=2 qualificata
       $ret=0;                                              //$feriale=1 feriale $feriale=2 festiva
       if ($ts==1) { //SAD
           if ($feriale==1) {
               $query="select costo_ora_tipo_assistenza from tipi_assistenza where id_tipo_servizio=1 and codiceistat='$comune' and id_tipo_prestazione=$tipo and fl_festivo=0";
           } else
           if ($feriale==2) {
               $query="select costo_ora_tipo_assistenza from tipi_assistenza where id_tipo_servizio=1 and codiceistat='$comune' and id_tipo_prestazione=$tipo and fl_festivo=1";
           } 
       }
       else if ($ts==2) {
           if ($feriale==1) {
               $query="select costo_ora_tipo_assistenza from tipi_assistenza where id_tipo_servizio=2 and codiceistat='$comune' and id_tipo_prestazione=$tipo and fl_festivo=0";
           } else
           if ($feriale==2) {
               $query="select costo_ora_tipo_assistenza from tipi_assistenza where id_tipo_servizio=2 and codiceistat='$comune' and id_tipo_prestazione=$tipo and fl_festivo=1";
           }
       }

       if (!empty($query)) {
           $result=mysql_query($query);
           if (!$result)
                   die("Impossibile accedere al DataBase");
           else
           {
                if ($row=  mysql_fetch_array($result)) {
                    $ret=$row['costo_ora_tipo_assistenza'];
                }
           }
       }
       else $ret=0;
       
       return round($ret,2);
   }
   
   public function getNomeUtente($id) {
      $ret="";
      $query="select cognome_uten, nome_uten from utenti where id_utente=".(int)$id;
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");

       if ($row = mysql_fetch_array($result)){
           $ret=$row['cognome_uten']." ".$row['nome_uten'];
       }
       return $ret;
      
   }
   
   public function getPercentualePagamentoUtente($id) {
      $ret=0;
      
      $query="select perc_pagamento_individuale from utenti where id_utente=".(int)$id;
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");

       if ($row = mysql_fetch_array($result)){
           $ret=$row['perc_pagamento_individuale'];
       }
       return $ret;
      
   }
   
   public function getNomeOperatore($id) {
      $ret="";
      $query="select cognome_oper, nome_oper from operatori where id_operatore=".(int)$id;
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");

       if ($row = mysql_fetch_array($result)){
           $ret=$row['cognome_oper']." ".$row['nome_oper'];
       }
       return $ret;
      
   }
   
   public function getDescFabbricato($id) {
      $ret="";
      $query="select descrizione_fabbricato from fabbricati where id_fabbricato=".(int)$id;
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");

       if ($row = mysql_fetch_array($result)){
           $ret=$row['descrizione_fabbricato'];
       }
       return $ret;      
   }
   
   public function getIDFabbricatoFromIDEdificio($id) { //ID Edificio
       $ret=0;
       
       if (empty($id)) return $ret;
       
       $query="select id_fabbricato from istituti where id_esterno=$id";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");

       if ($row = mysql_fetch_array($result)){
           $ret=$row['id_fabbricato'];
       }
       
       return $ret;
   }
   
   public function getIDCompetenzaFabbricato($id) { //ID Fabbricato
       $ret=0;
       
       if (empty($id)) return $ret;
       
       $query = "SELECT c.id_competenza
                    FROM fabbricati f,comuni c 
                    where f.id_comune=c.id_comune and f.id_fabbricato=$id";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");

       if ($row = mysql_fetch_array($result)){
           $ret=$row['id_competenza'];
       }
       
       return $ret;
   }
   
   public function getIDLavoroOrigine($id) { //ID Lavoro
       $ret=0;
       
       if (empty($id)) return $ret;
       
       $ret=$id;
       
       $query="select id_lavoro_origine from lavori where fl_complementare=1 and id_lavoro_origine=$id";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");

       if ($row = mysql_fetch_array($result)){
           $ret=$row['id_lavoro_origine'];
       }
       
       return $ret;
   }
   
   public function getDescMese($mese) {
       $ret="";
       
       $query="select desc_mese from mesi where id_mese=".(int)$mese;
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");

       if ($row = mysql_fetch_array($result)){
           $ret=$row['desc_mese'];
       }
       return $ret;
   }
   
   
   
   public function getDescComune($comune) {
       $ret="";
       
       $query="select descristat from comuni where codiceistat='$comune'";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");

       if ($row = mysql_fetch_array($result)){
           $ret=$row['descristat'];
       }
       return $ret;
   }
   
   public function getIDFabbricatoFromIDLavoro($id) { //ID Lavoro
       $ret=0;
       
       if (empty($id)) return $ret;
       
       $query="select IDEdificio from lavori where IDLavoro=$id";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");

       if ($row = mysql_fetch_array($result)){
           $ret=$row['IDEdificio'];
       }
       
       return $ret;
   }
   
   public function getDescLavoro($id) {
       $ret="";
       
       if (empty($id)) return $ret;
       
       $query="select Descrizione from lavori where IDLavoro=$id";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");

       if ($row = mysql_fetch_array($result)){
           $ret=$row['Descrizione'];
       }
       return $ret;
   }
   
   public function getDescSottodescrizioneQuadro($id) {
       $ret="";
       
       $query="select desc_sottodescrizione_quadro from sottodescrizioni_quadro where id_sottodescrizione_quadro=$id";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");

       if ($row = mysql_fetch_array($result)){
           $ret=$row['desc_sottodescrizione_quadro'];
       }
       return $ret;
   }
   
   public function getIdComune($comune) {
       $ret="";
       
       $query="select codiceistat from comuni where descristat='$comune'";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");

       if ($row = mysql_fetch_array($result)){
           $ret=$row['descristat'];
       }
       return $ret;
   }
   
   public function getRuoloCoop($id_coop) {
       $ret=0;
       
       if (empty($id_coop)) $id_coop=0;
       
       $query="select id_ruolo from accounts where id_cooperativa=$id_coop";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");

       if ($row = mysql_fetch_array($result)){
           $ret=$row['id_ruolo'];
       }
       return $ret;
   }
   
   public function getStrutturaFromIDSottostruttura($id) { //id sottostruttura
       $ret[0]=0;
       $ret[1]="";
       
       if (empty($id)) return $ret;
       
       $query="SELECT ss.id_struttura, s.desc_struttura
                FROM sottostrutture ss, strutture s where ss.id_sottostruttura=$id and ss.id_struttura=s.id_struttura";
       
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");

       if ($row = mysql_fetch_array($result)){
           $ret[0]=$row['id_struttura'];
           $ret[1]=$row['desc_struttura'];
       }
       
       return $ret;
   }
   
   public function getDescMotivazioneVerifica($ts) {
       $ret="";
       
       $query="select desc_tipo_esito_verifica from tipi_esito_verifica where id_tipo_esito_verifica=$ts";
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase");

       if ($row = mysql_fetch_array($result)){
           $ret=$row['desc_tipo_esito_verifica'];
       }
       return $ret;
   }
   
   public function InsertIntoStoricoUtenti($id_utente) {
       $query="INSERT INTO storico_utenti (id_utente,cognome_uten,nome_uten,sesso_uten,data_nascita_uten,id_comune_nascita_uten,
                cf_uten,indirizzo_res_uten,id_comune_res_uten,cap_res_uten,civico_res_uten,piano_res_uten,interno_res_uten,
                indirizzo_dom_uten,id_comune_dom_uten,cap_dom_uten,civico_dom_uten,piano_dom_uten,interno_dom_uten,
                telefono_1_uten,telefono_2_uten,telefono_3_uten,email_uten,fl_sad,fl_162,fl_disattivato,data_inserimento,data_modifica,
                id_user,data_richiesta_servizio,data_inizio_servizio,data_fine_servizio,id_motivo_fine,note_fine_servizio,id_livello_autosufficienza,
                tot_budget_162,ore_generiche,ore_qualificate,tot_budget_162_residuo,tot_budget_162_residuo_prec,fl_presenza_familiari,perc_pagamento_individuale,data_inserimento_storico)
                SELECT id_utente,cognome_uten,nome_uten,sesso_uten,data_nascita_uten,id_comune_nascita_uten,
                cf_uten,indirizzo_res_uten,id_comune_res_uten,cap_res_uten,civico_res_uten,piano_res_uten,interno_res_uten,
                indirizzo_dom_uten,id_comune_dom_uten,cap_dom_uten,civico_dom_uten,piano_dom_uten,interno_dom_uten,
                telefono_1_uten,telefono_2_uten,telefono_3_uten,email_uten,fl_sad,fl_162,fl_disattivato,data_inserimento,data_modifica,
                id_user,data_richiesta_servizio,data_inizio_servizio,data_fine_servizio,id_motivo_fine,note_fine_servizio,id_livello_autosufficienza,
                tot_budget_162,ore_generiche,ore_qualificate,tot_budget_162_residuo,tot_budget_162_residuo_prec,fl_presenza_familiari,perc_pagamento_individuale,NOW() 
                FROM utenti WHERE id_utente=$id_utente";
       
       $result = mysql_query($query);
       if (!$result)
           die("Impossibile accedere al DataBase - InsertIntoStoricoUtenti");
       
   }
}

