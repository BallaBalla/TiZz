<?php
// Tizz Lib
// cfg load and mysql connection are temporary


//##############################################################################
//		Load Settings
//##############################################################################
	include('./cfg.php');

//##############################################################################
//		Temporary MySQL Connection
//##############################################################################
	//Zum server verbinden:
	$MC1 = mysql_pconnect("$MCONNECT_HOST","$MCONNECT_USER","$MCONNECT_PW")
		or die ("Interner MySQL Server Verbindungs Fehler.");
	// use DB:
	$MC2 = mysql_select_db($MCONNECT_DB)
		or die ("Interner MySQL Server Verbindungs Fehler.");

//##############################################################################
//		DEBUG debug()
//##############################################################################

function debug($REPORTLEVEL, $MESSAGE) {
	// Debug
	// Funktion für einfacheres debuging. Konfigurierbar.

	//Initialisierung:
	global 	$DEBUG, $OUTPUT_HTMLFORMAT, 		// Lade globale Variablen
		$OUTPUT_TO_FILE, $FILEOUTPUT_PATH; 	// Die globalen Variablen müssen definiert sein.

	if($OUTPUT_HTMLFORMAT==1){	// Definiere einen <br> Tag in einer Variable für
		$HTML_NEWLINE="<br>";	// eine schönere Ausgabe, wenn dies über HTML geschiet.
		$MESSAGE=str_replace("ä","&auml;",$MESSAGE);	// Ersetzte Umlaute
		$MESSAGE=str_replace("ü","&uuml;",$MESSAGE);
		$MESSAGE=str_replace("ö","&ouml;",$MESSAGE);
		$MESSAGE=str_replace("ä","&Auml;",$MESSAGE);
		$MESSAGE=str_replace("ü","&Uuml;",$MESSAGE);
		$MESSAGE=str_replace("ö","&Ouml;",$MESSAGE);
	}else{				
		$HTML_NEWLINE="";
	}

	// Create Output or Input in File:
	if("$OUTPUT_TO_FILE"==1){
		$LOGFILE = fopen("$FILEOUTPUT_PATH", "a");
		if($REPORTLEVEL==1 && $DEBUG>=1){
			fwrite($LOGFILE, "ERROR: ".$MESSAGE." ".$HTML_NEWLINE."\n");
		}
		if($REPORTLEVEL==2 && $DEBUG==2){
			fwrite($LOGFILE, "DEBUG: ".$MESSAGE." ".$HTML_NEWLINE."\n");
		}
		fclose($LOGFILE);
	}else{
		if($REPORTLEVEL==1 && $DEBUG>=1){
			echo "ERROR: ".$MESSAGE." ".$HTML_NEWLINE."\n";
		}
		if($REPORTLEVEL==2 && $DEBUG==2){
			echo "DEBUG: ".$MESSAGE." ".$HTML_NEWLINE."\n";
		}
	}
}
//##############################################################################
//		Random Texty ID rndTextyID
//##############################################################################
function rndTextyID(){
	$sql="SELECT textyid FROM texty";
	$query=mysql_query($sql); $i=0;
	while($data=mysql_fetch_rows($query)){
		$TempIDs[$i]=$data[0]; $i++;
	} $i--;
	$ID="";
	while($ID==""){
		$ID="".$TempIDs[rand(0,$i)];
		if(!$ID){
			$ID="";
		}
	}
	return($ID);
}

//##############################################################################
//		Random Words rndWords
//##############################################################################
function rndWords($TEXTYID){
	$sql="SELECT Texty FROM texty WHERE textyid=$TEXTYID";
	$data=mysql_fetch_row(mysql_query($sql));
	$Texty=$data[0];
	$textarr=explode(" ", $Texty);
	$i=0;
	while($textarr[$i]){
		$i++;
	}$i--;
	$wordsarr= array(
		'textyid' => $TEXTYID,
		'word1' => $textarr[rand(0,$i)],
		'word2' => $textarr[rand(0,$i)],
		'word3' => $textarr[rand(0,$i)],
		'word4' => $textarr[rand(0,$i)],
		'word5' => $textarr[rand(0,$i)],
		'word6' => $textarr[rand(0,$i)],
		'word7' => $textarr[rand(0,$i)],
		'word8' => $textarr[rand(0,$i)],
		'word9' => $textarr[rand(0,$i)],
		'word10' => $textarr[rand(0,$i)]
	);
	return($wordsarr);
}






?>
