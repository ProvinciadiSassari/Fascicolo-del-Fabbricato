<?php
	function conv_datetime2timestamp($str) {
		if(!$str){
			return null;
		}
		list($date, $time) = explode(' ', $str);
		list($year, $month, $day) = explode('-', $date);
		list($hour, $minute, $second) = explode(':', $time);
		$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
		return $timestamp;
	}
	function ggmmyyyyhhmmss_sep_slash2yyyymmgghhmmss($str) {
//		echo "ggmmyyyyhhmmss_sep_slash2yyyymmgghhmmss : ricevuto : " . $str . "\n";
		if(!$str){
			return null;
		}
		list($date, $time) = explode(' ', $str);
		list($day, $month, $year) = explode('/', $date);
		//Validazione data
//		echo "ggmmyyyyhhmmss_sep_slash2yyyymmgghhmmss : day=" . $day . "\n";
//		echo "ggmmyyyyhhmmss_sep_slash2yyyymmgghhmmss : month=" . $month . "\n";
//		echo "ggmmyyyyhhmmss_sep_slash2yyyymmgghhmmss : year=" . $year . "\n";
		if(!is_numeric($day) || ((int)$day)>31 || ((int)$day)<=0) return -1;
		if(!is_numeric($month) || ((int)$month)>12 || ((int)$month)<=0) return -1;
		if(!is_numeric($year) || ((int)$year)>2050 || ((int)$year)<=1970) return -1;
		
		if(!$year || !$month || !$day) return -1;
		
		if(strlen($day)==1)  $day = "0" . $day;
		if(strlen($month)==1)  $month = "0" . $month;
		//		echo "ggmmyyyyhhmmss_sep_slash2yyyymmgghhmmss : continuo...\n";
//		echo "ggmmyyyyhhmmss_sep_slash2yyyymmgghhmmss : continuo...\n";
		$response = $year . "-" . $month . "-" . $day;
		
		list($hour, $minute, $second) = explode(':', $time);
		
		if($hour || $minute || $minute){
			if(strlen($hour)==0)  $hour = "00" . $hour;
			if(strlen($hour)==1)  $hour = "0" . $hour;
			if(strlen($minute)==0)  $minute = "00" . $minute;
			if(strlen($minute)==1)  $minute = "0" . $minute;
			if(strlen($second)==0)  $second = "00" . $second;
			if(strlen($second)==1)  $second = "0" . $second;
	//		echo "ggmmyyyyhhmmss_sep_slash2yyyymmgghhmmss : hour=" . $hour . "\n";
	//		echo "ggmmyyyyhhmmss_sep_slash2yyyymmgghhmmss : minute=" . $minute . "\n";
	//		echo "ggmmyyyyhhmmss_sep_slash2yyyymmgghhmmss : second=" . $second . "\n";
			if($hour){
				//Validazione ora
				if(!$hour) return -1;
				if(!is_numeric($hour) || ((int)$hour)>23 || ((int)$hour)<0) return -1;
				if(!$minute) return -1;
				if(!is_numeric($minute) || ((int)$minute)>60 || ((int)$minute)<0) return -1;
				if(!$second) return -1;
				if(!is_numeric($second) || ((int)$second)>60 || ((int)$second)<0) return -1;
				$response .= " " . $hour . ":" . $minute . ":" . $second;
			}
		}
//		echo "ggmmyyyyhhmmss_sep_slash2yyyymmgghhmmss : restituisco : " . $response . "\n";
		return $response;
	}
	function conv_datetime2ggmmaaaa_hhmm($str) {
		if(!$str){
			return null;
		}
		list($date, $time) = explode(' ', $str);
		list($year, $month, $day) = explode('-', $date);
		list($hour, $minute, $second) = explode(':', $time);
		$ggmmaaaa_hhmm = $day . "/" . $month . "/" . $year;
		if($hour){
			$ggmmaaaa_hhmm .= " " . $hour . ":" . $minute;
		}
		return $ggmmaaaa_hhmm;
	}
	function conv_datetime2ggmmaaaa_hhmmss($str) {
		if(!$str){
			return null;
		}
		list($date, $time) = explode(' ', $str);
		list($year, $month, $day) = explode('-', $date);
		list($hour, $minute, $second) = explode(':', $time);
		$ggmmaaaa_hhmmss = $day . "/" . $month . "/" . $year;
		if($hour){
			$ggmmaaaa_hhmmss .= " " . $hour . ":" . $minute . ":" . $second;
		}
		return $ggmmaaaa_hhmmss;
	}
	function conv_datetime2mmggaaaa_hhmmss($str) {
		if(!$str){
			return null;
		}
		list($date, $time) = explode(' ', $str);
		list($year, $month, $day) = explode('-', $date);
		list($hour, $minute, $second) = explode(':', $time);
		$mmggaaaa_hhmmss = $month . "/" . $day . "/" . $year . " " . $hour . ":" . $minute . ":" . $second;
		return $mmggaaaa_hhmmss;
	}
	
	function conv_string2number($str) {
		if(!$str){
			return null;
		}
		if(strtolower(trim($str))=="null"){
			return null;
		}
		return $str;
	}
	function conv_string2sql($str) {
		if(!$str){
			return "null";
		}
		$_response = str_replace("'", "''", $str);
		$_response = trim($_response);
		$_response = "'" . $_response . "'";
		return $_response;
	}
	function conv_number2sql($number) {
		if(!$number){
			return null;
		}
		$_response=$number;
		if($_response==""){
			$_response=null;
		}
		return $_response;
	}
	function conv_sql2importo($number,$_flag_print_zero=false){
		if(!$number){
			if($_flag_print_zero){
				return "0,00";
			}else{
				return null;	
			}
		}
		if($number==""){
			if($_flag_print_zero){
				return "0,00";
			}else{
				return null;	
			}
			
		}
		$_response=$number;
		$_response = number_format($_response , 2, ",", ".");
		return $_response;
	}
	function conv_importo2sql($number){
		if(!$number){
			return null;
		}
		if($number==""){
			return null;
		}
		$_response=$number;
		if(substr_count($_response,",")>0){
			$_response = str_replace('.','',$_response);	
			$_response = str_replace(',','.',$_response);
		}
		return $_response;
	}
	function time2date_hh_mm_ss($time){
		if(!$time){
			return null;
		}
		$time_hour=substr($time,0,2);
		$time_minute=substr($time,3,2);
		$time_seconds=substr($time,6,2);
		$time=date("H:i", mktime($time_hour,$time_minute,$time_seconds)); 
		return $time;
	};
	function mysql_time2date_hh_mm($time){
		if(!$time){
			return null;
		}
		$time_hour=substr($time,0,2);
		$time_minute=substr($time,3,2);
		$time_seconds=substr($time,6,2);
		$time=date("H:i", mktime($time_hour,$time_minute));
		return $time;
	};
	function conv_date2sql($str) {
		if(!$str){
			return null;
		}
		$_response = str_replace("'", "''", $str);
		$_response = trim($_response);
		$_response  = ggmmyyyyhhmmss_sep_slash2yyyymmgghhmmss($str);
		$_response = "'" . $_response . "'";
		return $_response;
	}
	function conv_date2mysqldate($str) {
//		echo "conv_date2mysqldate : ricevuto : " . $str . "\n";
		if(!$str){
			return null;
		}
		$_response = str_replace("'", "''", $str);
		$_response = trim($_response);
		$_response = ggmmyyyyhhmmss_sep_slash2yyyymmgghhmmss($str);
//		echo "conv_date2mysqldate : restituisco : " . $_response . "\n";
		return $_response;
	}
	function conv_mysqldate2date($str) {
		if(!$str){
			return null;
		}
		$_response = str_replace("'", "''", $str);
		$_response = trim($_response);
		$_response  = conv_datetime2ggmmaaaa_hhmm($str);
		$_response = $_response ;
		return $_response;
	}
	function conv_sql2datetime($str) {
		if(!$str){
			return null;
		}
		return conv_datetime2ggmmaaaa_hhmm($str);
	}
	function conv_sql2stripslashes($str){
		if(!$str){
			return null;
		}
		return stripslashes(utf8_decode($str));
	}
	function conv_timehhmm2mysqltime($str) {
		if(!$str){
			return null;
		}
		$_response = str_replace("'", "''", $str);
		$_response = trim($_response);
		$time_hour=substr($_response,0,2);
		$time_minute=substr($_response,3,2);
		$_response  = $time_hour . ":" . $time_minute . ":00"; 
		return $_response;
	}
	function conv_mysqltime2timehhmm($time){
		if(!$time){
			return null;
		}
		$time_hour=substr($time,0,2);
		$time_minute=substr($time,3,2);
		$time_seconds=substr($time,6,2);
		$time=date("H:i", mktime($time_hour,$time_minute));
		return $time;
	};
	function ggmmaaaa2desc_mm_aaaa($str) {
		if(!$str || strlen($str)!=10){
			return null;
		}
		$_mese = substr($str,3,2);
		$_anno = substr($str,6,4);
		
		switch ($_mese){
			case "01" :
				$_mese_desc="Gen.";
				break;	
			case "02" :
				$_mese_desc="Feb.";
				break;	
			case "03" :
				$_mese_desc="Mar.";
				break;	
			case "04" :
				$_mese_desc="Apr.";
				break;	
			case "05" :
				$_mese_desc="Mag.";
				break;	
			case "06" :
				$_mese_desc="Giu.";
				break;	
			case "07" :
				$_mese_desc="Lug.";
				break;	
			case "08" :
				$_mese_desc="Ago.";
				break;	
			case "09" :
				$_mese_desc="Set.";
				break;	
			case "10" :
				$_mese_desc="Ott.";
				break;	
			case "11" :
				$_mese_desc="Nov.";
				break;	
			case "12" :
				$_mese_desc="Dic.";
				break;	
		}
	
		return $_mese_desc . " " . $_anno;
	}
    function dateDiff($dformat, $endDate, $beginDate)
    {
	    $date_parts1=explode($dformat, $beginDate);
	    $date_parts2=explode($dformat, $endDate);
	    $start_date=gregoriantojd($date_parts1[1], $date_parts1[0], $date_parts1[2]);
	    $end_date=gregoriantojd($date_parts2[1], $date_parts2[0], $date_parts2[2]);
	    return $end_date - $start_date;
    }
    function mesenum2mesedesc($mesenum)
    {
    	switch ($mesenum){
    		case 1 : 
    			return "Gen.";
    		case 2 : 
    			return "Feb.";
    		case 3 : 
    			return "Mar.";
    		case 4 : 
    			return "Apr.";
    		case 5 : 
    			return "Mag.";
    		case 6 : 
    			return "Giu.";
    		case 7 : 
    			return "Lug.";
    		case 8 : 
    			return "Ago.";
    		case 9 : 
    			return "Set.";
    		case 10 : 
    			return "Ott.";
    		case 11 : 
    			return "Nov.";
    		case 12 : 
    			return "Dic.";
    	}
    }
    function hourDiff($hseparator, $endHour, $beginHour, $formatResp=1)
    {
    	//formatResp
    	//  1= xx:yy
    	//	2= decimal
    	
	    $beginHour_date_parts=explode($hseparator, $beginHour);
	    $endHour_date_parts=explode($hseparator, $endHour);
	    $start_hour=$beginHour_date_parts[0]*60 + $beginHour_date_parts[1];
	    $end_hour=$endHour_date_parts[0]*60 + $endHour_date_parts[1];
	    $minutes_diff = $end_hour - $start_hour;
	    if($formatResp==1){
	    	$_diff_hour = floor($minutes_diff/60);
	    	if(strlen($_diff_hour)==1) $_diff_hour = "0" . $_diff_hour;
	    	$_diff_minutes = $minutes_diff - ($_diff_hour*60);
	    	if(strlen($_diff_minutes)==1) $_diff_minutes = "0" . $_diff_minutes;
	    	$_diff = $_diff_hour . $hseparator . $_diff_minutes;  
	    }else{
	    	$_diff = round($minutes_diff/60,1);
	    }
   	    return $_diff;
    }
	function escape4xml($jsonxml) {
		$jsonxml = str_replace("&","&amp;",$jsonxml);
		$jsonxml = str_replace("<","&lt;",$jsonxml);
		$jsonxml = str_replace(">","&gt;",$jsonxml);
		$jsonxml = str_replace("'","&#39;",$jsonxml);
		$jsonxml = str_replace('"',"&#34;",$jsonxml);
		$jsonxml = str_replace("\r\n","",$jsonxml);
		$jsonxml = str_replace("\n","",$jsonxml);
		return $jsonxml;
	}
    function escape4html($jsonxml) {
		$jsonxml = str_replace("&","&amp;",$jsonxml);
		$jsonxml = str_replace("<","&lt;",$jsonxml);
		$jsonxml = str_replace(">","&gt;",$jsonxml);
		$jsonxml = str_replace("\"","&quot;",$jsonxml);
		$jsonxml = str_replace("'","&#39;",$jsonxml);
		$jsonxml = str_replace("\\","&#92;",$jsonxml);
		return $jsonxml;
	}
    function conv_string2html($string,$parse_newline=true) {
		if($parse_newline){
			$occorrenze = array(
				"&",
				"\"",
				"'",
                                "&deg;",
				"À","Á","Â","Ã","Ä","Å","Æ","Ç","È","É","Ê","Ë","Ì","Í","Î","Ï","Ð","Ñ","Ò","Ó","Ô","Õ","Ö","×","Ø","Ù","Ú","Û","Ü","Ý","Þ","ß","à","á","â","ã","ä","å","æ","ç","è","é","ê","ë","ì","í","î","ï","ð","ñ","ò","ó","ô","õ","ö","÷","ø","ù","ú","û","ü","ý","þ","ÿ","<",">","€","ˆ","˜","…","™",
				"\n"
				);
			$sostituzioni= array(
				"&amp;",
				"&quot;",
				"&#39;",
                                "°",
				"&Agrave;","&Aacute;","&Acirc;","&Atilde;","&Auml;","&Aring;","&AElig;","&Ccedil;","&Egrave;","&Eacute;","&Ecirc;","&Euml;","&Igrave;","&Iacute;","&Icirc;","&Iuml;","&ETH;","&Ntilde;","&Ograve;","&Oacute;","&Ocirc;","&Otilde;","&Ouml;","&times;","&Oslash;","&Ugrave;","&Uacute;","&Ucirc;","&Uuml;","&Yacute;","&THORN;","&szlig;","&agrave;","&aacute;","&acirc;","&atilde;","&auml;","&aring;","&aelig;","&ccedil;","&egrave;","&eacute;","&ecirc;","&euml;","&igrave;","&iacute;","&icirc;","&iuml;","&eth;","&ntilde;","&ograve;","&oacute;","&ocirc;","&otilde;","&ouml;","&divide;","&oslash;","&ugrave;","&uacute;","&ucirc;","&uuml;","&yacute;","&thorn;","&yuml;","&lt;","&gt;","&euro;","&circ;","&tilde;","&hellip;","&trade;",
				"<BR>\n",
				);
		}else{
			$occorrenze = array(
				"&",
				"\"",
				"'",
                                "&deg;",
				"À","Á","Â","Ã","Ä","Å","Æ","Ç","È","É","Ê","Ë","Ì","Í","Î","Ï","Ð","Ñ","Ò","Ó","Ô","Õ","Ö","×","Ø","Ù","Ú","Û","Ü","Ý","Þ","ß","à","á","â","ã","ä","å","æ","ç","è","é","ê","ë","ì","í","î","ï","ð","ñ","ò","ó","ô","õ","ö","÷","ø","ù","ú","û","ü","ý","þ","ÿ","<",">","€","ˆ","˜","…","™" );
			$sostituzioni= array(
				"&amp;",
				"&quot;",
				"&#39;",
                                "°",
				"&Agrave;","&Aacute;","&Acirc;","&Atilde;","&Auml;","&Aring;","&AElig;","&Ccedil;","&Egrave;","&Eacute;","&Ecirc;","&Euml;","&Igrave;","&Iacute;","&Icirc;","&Iuml;","&ETH;","&Ntilde;","&Ograve;","&Oacute;","&Ocirc;","&Otilde;","&Ouml;","&times;","&Oslash;","&Ugrave;","&Uacute;","&Ucirc;","&Uuml;","&Yacute;","&THORN;","&szlig;","&agrave;","&aacute;","&acirc;","&atilde;","&auml;","&aring;","&aelig;","&ccedil;","&egrave;","&eacute;","&ecirc;","&euml;","&igrave;","&iacute;","&icirc;","&iuml;","&eth;","&ntilde;","&ograve;","&oacute;","&ocirc;","&otilde;","&ouml;","&divide;","&oslash;","&ugrave;","&uacute;","&ucirc;","&uuml;","&yacute;","&thorn;","&yuml;","&lt;","&gt;","&euro;","&circ;","&tilde;","&hellip;","&trade;");
		}
		$string = str_replace($occorrenze, $sostituzioni, $string);
//		$string = str_replace("&","&amp;",$string);
//		$string = str_replace("à","&agrave;",$string);
//		$string = str_replace("è","&egrave;",$string);
//		$string = str_replace("ì","&igrave;",$string);
//		$string = str_replace("ò","&ograve;",$string);
//		$string = str_replace("ù","&ugrave;",$string);
//		$string = str_replace("€","&euro;",$string);
//		$string = str_replace("£","&#163;",$string);
//		$string = str_replace("<","&lt;",$string);
//		$string = str_replace(">","&gt;",$string);
//		$string = str_replace("\"","&quot;",$string);
//		$string = str_replace("'","&#39;",$string);
//		$string = str_replace("\"","&#34;",$string);
//		$string = str_replace("\n","<BR>\n",$string);
		return $string;
	}
    function conv_html2string($string) {
		$occorrenze = array(
			"&amp;",
			"&quot;",
			"&#39;",
			"&Agrave;","&Aacute;","&Acirc;","&Atilde;","&Auml;","&Aring;","&AElig;","&Ccedil;","&Egrave;","&Eacute;","&Ecirc;","&Euml;","&Igrave;","&Iacute;","&Icirc;","&Iuml;","&ETH;","&Ntilde;","&Ograve;","&Oacute;","&Ocirc;","&Otilde;","&Ouml;","&times;","&Oslash;","&Ugrave;","&Uacute;","&Ucirc;","&Uuml;","&Yacute;","&THORN;","&szlig;","&agrave;","&aacute;","&acirc;","&atilde;","&auml;","&aring;","&aelig;","&ccedil;","&egrave;","&eacute;","&ecirc;","&euml;","&igrave;","&iacute;","&icirc;","&iuml;","&eth;","&ntilde;","&ograve;","&oacute;","&ocirc;","&otilde;","&ouml;","&divide;","&oslash;","&ugrave;","&uacute;","&ucirc;","&uuml;","&yacute;","&thorn;","&yuml;","&lt;","&gt;","&euro;","&circ;","&tilde;","&hellip;","&trade;");
		$sostituzioni= array(
			"&",
			"\"",
			"'",
			"À","Á","Â","Ã","Ä","Å","Æ","Ç","È","É","Ê","Ë","Ì","Í","Î","Ï","Ð","Ñ","Ò","Ó","Ô","Õ","Ö","×","Ø","Ù","Ú","Û","Ü","Ý","Þ","ß","à","á","â","ã","ä","å","æ","ç","è","é","ê","ë","ì","í","î","ï","ð","ñ","ò","ó","ô","õ","ö","÷","ø","ù","ú","û","ü","ý","þ","ÿ","<",">","€","ˆ","˜","…","™" );
	    $string = str_replace($occorrenze, $sostituzioni, $string);
//		$string = str_replace("&amp;","&",$string);
//     	$string = str_replace("&agrave;","à",$string);
//		$string = str_replace("&egrave;","è",$string);
//		$string = str_replace("&igrave;","ì",$string);
//		$string = str_replace("&ograve;","�",$string);
//		$string = str_replace("&ugrave;","�",$string);
//		$string = str_replace("&euro;","�",$string);
//		$string = str_replace("&#163;","�",$string);
//		$string = str_replace("&lt;","<",$string);
//		$string = str_replace("&gt;",">",$string);
//		$string = str_replace("&quot;","\"",$string);
//		$string = str_replace("&#39;","'",$string);
//		$string = str_replace("&#34;","\"",$string);
//		$string = str_replace("<BR>\n","\n",$string);
//		$string = str_replace("<BR>","\n",$string);
		return $string;
	}
	function conv_specialchar2htmlescape($string) {
		$string = str_replace("�","&agrave;",$string);
		$string = str_replace("�","&egrave;",$string);
		$string = str_replace("�","&igrave;",$string);
		$string = str_replace("�","&ograve;",$string);
		$string = str_replace("�","&ugrave;",$string);
		$string = str_replace("&","&amp;",$string);
		return $string;
	}
	function utf8_decode2($string) {
		return utf8_decode(str_replace("\xe2\x82\xac","\xc2\x80",$string));
	}

//Conversioni date
function conv_timestamp2str_datetime_ymdhms($timestamp,$separator_date="/",$separator_time=":") {
	if(!$timestamp){
		return null;
	}
	$_response = date("Y" . $separator_date ."m" . $separator_date ."d G" . $separator_time ."i" . $separator_time ."s", $timestamp);
	return $_response;
}
function conv_timestamp2str_datetime_ymd($timestamp,$separator_date="/") {
	if(!$timestamp){
		return null;
	}
	$_response = date("Y" . $separator_date ."m" . $separator_date ."d", $timestamp);
	return $_response;
}
function conv_timestamp2str_datetime_dmyhms($timestamp,$separator_date="/",$separator_time=":") {
	if(!$timestamp){
		return null;
	}
	$_response = date("d" . $separator_date ."m" . $separator_date ."Y G" . $separator_time ."i" . $separator_time ."s", $timestamp);
	return $_response;
}
function conv_timestamp2str_datetime_dmy($timestamp,$separator_date="/") {
	if(!$timestamp){
		return null;
	}
	$_response = date("d" . $separator_date ."m" . $separator_date ."Y", $timestamp);
	return $_response;
}
function conv_timestamp2str_datetime_hms($timestamp,$separator_time=":") {
	if(!$timestamp){
		return null;
	}
	$_response = date("G" . $separator_time ."i" . $separator_time ."s", $timestamp);
	return $_response;
}
function conv_timestamp2str_datetime_hm($timestamp,$separator_time=":") {
	if(!$timestamp){
		return null;
	}
	$_response = date("G" . $separator_time ."i", $timestamp);
	return $_response;
}
function conv_timestamp2db_ymdhms($timestamp,$separator_date="-",$separator_time=":") {
	if(!$timestamp){
		return null;
	}
	$_response = date("Y" . $separator_date ."m" . $separator_date ."d G" . $separator_time . "i" . $separator_time . "s", $timestamp);
	return $_response;
}
function conv_str_datetime_ymdhms2timestamp($str_datetime_ymdhms,$separator_date="/",$separator_time=":") {
	if(!$str_datetime_ymdhms){
		return null;
	}
	list($date, $time) = explode(' ', $str_datetime_ymdhms);
	list($year, $month, $day) = explode($separator_date, $date);
	list($hour, $minute, $second) = explode($separator_time, $time);
	if(!$time){
		$_response = mktime(0, 0, 0, $month, $day, $year);
	}else{
		$_response = mktime($hour, $minute, $second, $month, $day, $year);
	}
	return $_response;
}
function conv_str_datetime_dmyhms2timestamp($str_datetime_ymdhms,$separator_date="/",$separator_time=":") {
	if(!$str_datetime_ymdhms){
		return null;
	}
	list($date, $time) = explode(' ', $str_datetime_ymdhms);
	list($day,$month,$year) = explode($separator_date, $date);
	list($hour, $minute, $second) = explode($separator_time, $time);
	if($time){
		$_response = mktime($hour, $minute, $second, $month, $day, $year);
	}else{
		$_response = mktime(0, 0, 0, $month, $day, $year);
	}
	
	return $_response;
}
function conv_month_num2month_desc($month_num,$flag_desc_short=false) {
	if(!$month_num){
		return null;
	}
	switch ($month_num){
		case 1 :
			if($flag_desc_short){
				$_mese_desc="Gen.";
			}else{
				$_mese_desc="Gennaio";
			}
			break;
		case 2 :
			if($flag_desc_short){
				$_mese_desc="Feb.";
			}else{
				$_mese_desc="Febbraio";
			}
			break;
		case 3 :
			if($flag_desc_short){
				$_mese_desc="Mar.";
			}else{
				$_mese_desc="Marzo";
			}
			break;
		case 4 :
			if($flag_desc_short){
				$_mese_desc="Apr.";
			}else{
				$_mese_desc="Aprile";
			}
			break;
		case 5 :
			if($flag_desc_short){
				$_mese_desc="Mag.";
			}else{
				$_mese_desc="Maggio";
			}
			break;
		case 6 :
			if($flag_desc_short){
				$_mese_desc="Giu.";
			}else{
				$_mese_desc="Giugno";
			}
			break;
		case 7 :
			if($flag_desc_short){
				$_mese_desc="Lug.";
			}else{
				$_mese_desc="Luglio";
			}
			break;
		case 8 :
			if($flag_desc_short){
				$_mese_desc="Ago.";
			}else{
				$_mese_desc="Agosto";
			}
			break;
		case 9 :
			if($flag_desc_short){
				$_mese_desc="Set.";
			}else{
				$_mese_desc="Settembre";
			}
			break;
		case 10 :
			if($flag_desc_short){
				$_mese_desc="Ott.";
			}else{
				$_mese_desc="Ottobre";
			}
			break;
		case 11 :
			if($flag_desc_short){
				$_mese_desc="Nov.";
			}else{
				$_mese_desc="Novembre";
			}
			break;
		case 12 :
			if($flag_desc_short){
				$_mese_desc="Dic.";
			}else{
				$_mese_desc="Dicembre";
			}
			break;
	}
	return $_mese_desc;
}


//Conversioni date

function conv_datetime_ymdhms2timestamp($_datetime,$_date_separator="-",$_time_separator=":") {
	return conv_datetime2timestamp_v2($_datetime,false,$_date_separator,true,$_time_separator,true,true);
}
function conv_datetime_ymdhm2timestamp($_datetime,$_date_separator="-",$_time_separator=":") {
	return conv_datetime2timestamp_v2($_datetime,false,$_date_separator,true,$_time_separator,true,false);
}
function conv_datetime_dmyhms2timestamp($_datetime,$_date_separator="/",$_time_separator=":") {
	return conv_datetime2timestamp_v2($_datetime,true,$_date_separator,true,$_time_separator=":",true,true);
}
function conv_datetime_dmyhm2timestamp($_datetime,$_date_separator="/",$_time_separator=":") {
	return conv_datetime2timestamp_v2($_datetime,true,$_date_separator,true,$_time_separator,true,false);
}
function conv_date_ymd2timestamp($_date,$_date_separator="-") {
	return conv_datetime2timestamp_v2($_date,false,$_date_separator,true,null,false,false);
}
function conv_date_dmy2timestamp($_date,$_date_separator="/") {
	return conv_datetime2timestamp_v2($_date,true,$_date_separator,true,null,false,false);
}
function conv_time_hms2timestamp($_time,$_time_separator=":") {
	return conv_datetime2timestamp_v2($_time,false,false,null,$_time_separator,true,true);
}
function conv_time_hm2timestamp($_time,$_time_separator=":") {
	return conv_datetime2timestamp_v2($_time,false,false,null,$_time_separator,true,false);
}
function conv_datetime2timestamp_v2($_datetime,$_gregorian=false,$_date_separator="-",$_parse_dmy=false,$_time_separator=":",$_parse_hm=false,$_parse_s=false) {
	if(
		!$_datetime
		||
		(
			!$_parse_dmy
			&&
			!$_parse_hm
			&&
			!$_parse_s
		)
	){
		return null;
	}

	$_datetime = trim($_datetime);
	$date=null;
	$time=null;
	$day=0;
	$month=0;
	$year=0;
	$hour=0;
	$minute=0;
	$second=0;

	if(
		$_parse_dmy
		&&
		(
			$_parse_hm
			||
			$_parse_s
		)
	){
		list($date, $time) = explode(' ', $_datetime);
	}else if(
		$_parse_dmy
	){
		$date = $_datetime;
	}else if(
		$_parse_hm
		||
		$_parse_s
	){
		$time = $_datetime;
	}else{
		return null;
	}

	if(
		$_parse_dmy
	){
		if($_gregorian){
			list($day, $month, $year) = explode($_date_separator, $date);
		}else{
			list($year, $month, $day) = explode($_date_separator, $date);
		}
		if(
				!checkdate($month ,$day ,$year)
				||
				!is_numeric($month)
				||
				!is_numeric($day)
				||
				!is_numeric($year)
		){
			return -1;
		}
	}
	if(
		$_parse_hm
		||
		$_parse_s
	){
		if(
			$_parse_hm
			&&
			$_parse_s
			&&
			$time
		){
			list($hour, $minute, $second) = explode($_time_separator, $time);
			if(
					!is_numeric($hour)
					||
					!is_numeric($minute)
					||
					!is_numeric($second)
					||
					$hour<0
					||
					$hour>23
					||
					$minute<0
					||
					$minute>59
					||
					$second<0
					||
					$second>59
			){
				return -2;
			}
		}else if($time){
			list($hour, $minute) = explode($_time_separator, $time);
			if(
					!is_numeric($hour)
					||
					!is_numeric($minute)
					||
					$hour<0
					||
					$hour>23
					||
					$minute<0
					||
					$minute>59
			){
				return -2;
			}
		}
	}
	$_response = mktime($hour, $minute, $second, $month, $day, $year);
	return $_response;
}

function conv_timestamp2date_ymd($_timestamp,$_date_separator="-",$_show_d=true,$_show_m=true,$_show_y=true) {
	return conv_timestamp2datetime($_timestamp,false,$_date_separator,$_show_d,$_show_m,$_show_y);
}
function conv_timestamp2datetime_ymdhm($_timestamp,$_date_separator="-",$_show_d=true,$_show_m=true,$_show_y=true,$_time_separator=":",$_show_h=true,$_show_min=true) {
	return conv_timestamp2datetime($_timestamp,false,$_date_separator,$_show_d,$_show_m,$_show_y,$_time_separator,$_show_h,$_show_min,false);
}
function conv_timestamp2datetime_ymdhms($_timestamp,$_date_separator="-",$_show_d=true,$_show_m=true,$_show_y=true,$_time_separator=":",$_show_h=true,$_show_min=true,$_show_s=true) {
	return conv_timestamp2datetime($_timestamp,false,$_date_separator,$_show_d,$_show_m,$_show_y,$_time_separator,$_show_h,$_show_min,$_show_s);
}
function conv_timestamp2date_dmy($_timestamp,$_date_separator="/",$_show_d=true,$_show_m=true,$_show_y=true) {
	return conv_timestamp2datetime($_timestamp,true,$_date_separator,$_show_d,$_show_m,$_show_y);
}
function conv_timestamp2datetime_dmyhm($_timestamp,$_date_separator="/",$_show_d=true,$_show_m=true,$_show_y=true,$_time_separator=":",$_show_h=true,$_show_min=true) {
	return conv_timestamp2datetime($_timestamp,true,$_date_separator,$_show_d,$_show_m,$_show_y,$_time_separator,$_show_h,$_show_min,false);
}
function conv_timestamp2datetime_dmyhms($_timestamp,$_date_separator="/",$_show_d=true,$_show_m=true,$_show_y=true,$_time_separator=":",$_show_h=true,$_show_min=true,$_show_s=true) {
	return conv_timestamp2datetime($_timestamp,true,$_date_separator,$_show_d,$_show_m,$_show_y,$_time_separator,$_show_h,$_show_min,$_show_s);
}
function conv_timestamp2time_hms($_timestamp,$_time_separator=":",$_show_h=false,$_show_min=false,$_show_s=false) {
	return conv_timestamp2datetime($_timestamp,false,false,false,false,null,$_time_separator,true,true,true);
}
function conv_timestamp2time_hm($_timestamp,$_time_separator=":",$_show_h=false,$_show_min=false) {
	return conv_timestamp2datetime($_timestamp,false,false,false,false,null,$_time_separator,true,true,false);
}
function conv_timestamp2datetime($_timestamp,$_gregorian=false,$_date_separator="/",$_show_d=false,$_show_m=false,$_show_y=false,$_time_separator=":",$_show_h=false,$_show_min=false,$_show_s=false) {
	if(!$_timestamp){
		return null;
	}
	$_response=null;
	$_response_time=null;

	if(
		$_show_d
		||
		$_show_m
		||
		$_show_y
	){
		if($_gregorian){
			if($_show_d){
				$_day = date("d", $_timestamp);
				if(strlen($_day)==1) $_day = "0" . $_day;
				$_response .= $_day;
			}
			if(
				$_show_d
				&&
				(
					$_show_m
					||
					$_show_y
				)
			){
				$_response .= $_date_separator;
			}
			if($_show_m){
				$_month = date("m", $_timestamp);
				if(strlen($_month)==1) $_month = "0" . $_month;
				$_response .= $_month;
			}
			if(
				$_show_m
				&&
				$_show_y
			){
				$_response .= $_date_separator;
			}
			if($_show_y){
				$_year = date("Y", $_timestamp);
				if(strlen($_year)==1) $_year = "0" . $_year;
				$_response .= $_year;
			}
		}else{
			if($_show_y){
				$_year = date("Y", $_timestamp);
				if(strlen($_year)==1) $_year = "0" . $_year;
				$_response .= $_year;
			}
			if(
				$_show_y
				&&
				(
					$_show_m
					||
					$_show_d
				)
			){
				$_response .= $_date_separator;
			}
			if($_show_m){
				$_month = date("m", $_timestamp);
				if(strlen($_month)==1) $_month = "0" . $_month;
				$_response .= $_month;
			}
			if(
				$_show_m
				&&
				$_show_d
			){
				$_response .= $_date_separator;
			}
			if($_show_d){
				$_day = date("d", $_timestamp);
				if(strlen($_day)==1) $_day = "0" . $_day;
				$_response .= $_day;
			}
		}
	}
	if(
		$_show_h
		||
		$_show_min
		||
		$_show_y
	){
		if($_show_h){
			$_hours = date("H", $_timestamp);
			if(strlen($_hours)==1) $_hours = "0" . $_hours;
			$_response_time .= $_hours;
		}
		if(
			$_show_h
			&&
			(
				$_show_min
				||
				$_show_s
			)
		){
			$_response_time .= $_time_separator;
		}
		if($_show_min){
			$_minutes = date("i", $_timestamp);
			if(strlen($_minutes)==1) $_minutes = "0" . $_minutes;
			$_response_time .= $_minutes;
		}
		if(
			$_show_min
			&&
			$_show_s
		){
			$_response_time .= $_time_separator;
		}
		if($_show_s){
			$_seconds = date("s", $_timestamp);
			if(strlen($_seconds)==1) $_seconds = "0" . $_seconds;
			$_response_time .= $_seconds;
		}
	}
	if(
		(
			$_show_d
			||
			$_show_m
			||
			$_show_y
		)
		&&
		(
			$_show_h
			||
			$_show_min
			||
			$_show_y
		)
		&&
		$_response_time
	){
		$_response .= " ";
	}
	$_response .= $_response_time;
	return $_response;
}

?>