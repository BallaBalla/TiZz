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
	debug(2, "rndTextyID: Start function.");

	//Select textys
	$sql="SELECT textyid FROM texty WHERE activ=1";
	$query=mysql_query($sql); $i=0;
	while($data=mysql_fetch_rows($query)){
		$TempIDs[$i]=$data[0]; $i++;	//schreibt textyid in array
	} $i--;

	//select textyid
	debug(2, "rndTextyID: Found $i Textys.");
	$ID="";
	while($ID==""){
		$ID="".$TempIDs[rand(0,$i)];//default textyid aus array
		if(!$ID){	//check auf fehler
			$ID="";
		}
	}

	//check auf fehler ..... again -.-
	if($ID!=""){
		debug(2, "rndTextyID: Return TextyID [$ID].");
	}else{
		debug(1, "rndTextyID: TextyID is empty!!!");
	}

	//finito
	debug(2, "rndTextyID: End of function.");
	return($ID);
}

//##############################################################################
//		Random Words rndWords
//##############################################################################
function rndWords($TEXTYID){
	//check textyid is set
	if(!isset($TEXTYID)){
		debug(1, "rndWords: Function need a TextyID.");
		die("Ein interner Fehler ist aufgetreten.<br>\n");
	}

	//Get Text from texty
	debug(2, "rndWords: Start function.");
	$sql="SELECT Texty FROM texty WHERE textyid=$TEXTYID AND activ=1";
	$data=mysql_fetch_row(mysql_query($sql));

	//Teile texty nach leerzeichen
	debug(2, "rndWords: Fetch Texty [$TEXTYID]: SQL Query:[$sql]");
	$Texty=$data[0];
	$Texty=str_replace("\n"," ", $Texty);
	$Texty=str_replace("\t"," ", $Texty);
	$textarr=explode(" ", $Texty);

	//count words
	$i=0;
	while($textarr[$i]){
		$i++;
	}$i--;

	//per random 10 wörter in array schreiben (plus id)
	debug(2, "rndWords: Found $i Words in Texty.");
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

	debug(2, "rndWords: End of function.");
	return($wordsarr);
}
//##############################################################################
//		Get Texty
//##############################################################################
function getTexty($TEXTYID){
	if(!isset($TEXTYID)){
		debug(1, "getTexty: Function need a TextyID.");
		die("Ein interner Fehler ist aufgetreten.<br>\n");
	}

	debug(2, "getTexty: Start function.");
	$sql="SELECT Texty,title, votepoints, userfs, klicks FROM texty WHERE textyid=$TEXTYID AND activ=1";
	$data=mysql_fetch_row(mysql_query($sql));
	debug(2, "getTexty: Fetch Texty [$TEXTYID]: SQL Query:[$sql]");

	$Texty=array(
		'TextyID' => $TEXTYID,
		'Title' => $data[1],
		'Texty' => $data[0],
		'votepoints' => $data[2],
		'userfs' => $data[3],
		'klicks' => $data[4]
	);

	debug(2, "getTexty: End of function.");
	return($Texty);
}
//##############################################################################
//		List Texty
//##############################################################################
function lsTexty($ORDER, $COUNT){

	if(!isset($ORDER) || !isset($COUNT)){
		debug(2, "lsTexty: Function need a Order and Count Argument.");
		if(!isset($ORDER) || $ORDER==""){
			debug(2, "lsTexty: Order is not set. Use default Value: Order: [new].");
			$ORDER="new";
		}
		if(!isset($COUNT) || $COUNT==""){
			debug(2, "lsTexty: Count is not set. Use default Value: Count: [10].");
			$COUNT=10;
		}
		
	}
	debug(2, "lsTexty: Start function.");

	if($ORDER != "new" && $ORDER != "old" && $ORDER != "mklicks" && $ORDER != "lklicks" && $ORDER != "bvote" && $ORDER != "wvote"){
		debug(1, "lsTexty: Order is incorrect. Use new.");
		$ORDER="new";
	}
	if($ORDER=="new"){
		$by="TextyID";
		$ord="DESC";
	}
	if($ORDER=="old"){
		$by="TextyID";
		$ord="ASC";
	}
	if($ORDER=="mklicks"){
		$by="klicks";
		$ord="DESC";
	}
	if($ORDER=="lklicks"){
		$by="klicks";
		$ord="ASC";
	}
	if($ORDER=="bvote"){
		$by="votepoints";
		$ord="DESC";
	}
	if($ORDER=="wvote"){
		$by="votepoints";
		$ord="ASC";
	}

	$sql="SELECT textyid FROM texty WHERE activ=1 ORDER BY $by $ord";
	debug(2, "lsTexty: Start fetching TextyIDs (Order By [$by][$ord] /Arg:[$ORDER]/Count:[$COUNT])");
	debug(2, "lsTexty: SQL Query:[$sql]");
	$query=mysql_query($sql);

	$i=0;
	while($data=mysql_fetch_row($query) && $i < $COUNT){
		$TextyList[$i]=$data[0];
		debug(2, "lsTexty: Wrote TextyID [".$data[0]."] in TextyList [$i].");
		$i++;
	}

	debug(2, "lsTexty: End of function.");
	return($TextyList);
}
//##############################################################################
//		Vote Texty
//##############################################################################
function voteTexty($TEXTYID, $UpOrDown){
	if(!isset($TEXTYID)){
		debug(1, "voteTexty: Need Argument TextyID.");
		debug(1, "voteTexty: Stop Function because arg error.");
		return();
	}
	debug(2, "voteTexty: Start Function with Arg:[$UpOrDown].");
	if(!isset($UpOrDown) || $UpOrDown==""){
		debug(1, "voteTexty: Need Argument Up or Down.");
		debug(1, "voteTexty: Stop Function because arg error.");
		return();
	}

	if($UpOrDown=="up"){
		$UpOrDown=1;
		debug(2, "voteTexty: Translate [up] to [1].");
	}
	if($UpOrDown=="down"){
		$UpOrDown="-1";
		debug(2, "voteTexty: Translate [down] to [-1].");
	}

	if($UpOrDown==1 || $UpOrDown=="-1"){
		debug(2, "voteTexty: Change Texty with ID:[$TEXTYID].");
		$sql="SELECT votepoints FROM texty WHERE textyid=$TEXTYID AND activ=1";
		$data=mysql_fetch_row(mysql_query($sql));
		debug(2, "voteTexty: Old Points [".$data[0]."].");

		$new=$data[0]+($UpOrDown);
		debug(2, "voteTexty: Change Points to [".$new."].");
		$sql="UPDATE texty SET votepoints='$new' WHERE textyid=$TEXTYID AND activ=1";
		mysql_query($sql);

		$sql="SELECT votepoints FROM texty WHERE textyid=$TEXTYID AND activ=1";
		$data=mysql_fetch_row(mysql_query($sql));
		debug(2, "voteTexty: New Points [".$data[0]."].");

		if($data[0] != $new){
			debug(1, "voteTexty: New Points in DB [".$data[0]."] are not the new Points [$new] we want.");
		}
	}else{
		debug(2, "voteTexty: Argument incorrect.");
	}
}
//##############################################################################
//		addKlick
//##############################################################################
function addKlick($TEXTYID){
	if(!isset($TEXTYID)){
		debug(1, "addKlick: Need Argument TextyID.");
		debug(1, "addKlick: Stop Function because arg error.");
		return();
	}
	debug(2, "addKlick: Start Function with Arg:[$TEXTYID].");

	$sql="SELECT klicks FROM texty WHERE textyid=$TEXTYID AND activ=1";
	$data=mysql_fetch_row(mysql_query($sql));
	debug(2, "addKlick: Old Klicks: [".$data[0]."].");

	$new=$data[0]+1;
	debug(2, "addKlick: Change Klicks to [".$new."].");
	$sql="UPDATE texty SET klicks='$new' WHERE textyid=$TEXTYID AND activ=1";
	mysql_query($sql);

	$sql="SELECT klicks FROM texty WHERE textyid=$TEXTYID AND activ=1";
	$data=mysql_fetch_row(mysql_query($sql));
	debug(2, "addKlick: New Klicks [".$data[0]."].");

	if($data[0] != $new){
		debug(1, "addKlick: New Klicks in DB [".$data[0]."] are not the new Klicks [$new] we want.");
	}
}
//##############################################################################
//		getKlick
//##############################################################################
function getKlick($TEXTYID){
	if(!isset($TEXTYID)){
		debug(1, "getKlick: Need Argument TextyID.");
		debug(1, "getKlick: Stop Function because arg error.");
		return();
	}
	debug(2, "getKlick: Start Function with Arg:[$TEXTYID].");

	$sql="SELECT klicks FROM texty WHERE textyid=$TEXTYID AND activ=1";
	$data=mysql_fetch_row(mysql_query($sql));
	debug(2, "getKlick: Klicks: [".$data[0]."].");

	return($data[0]);
	debug(2, "getKlick: Function end.");
}
//##############################################################################
//		getVote
//##############################################################################
function getVote($TEXTYID){
	if(!isset($TEXTYID)){
		debug(1, "getVote: Need Argument TextyID.");
		debug(1, "getVote: Stop Function because arg error.");
		return();
	}
	debug(2, "getVote: Start Function with Arg:[$TEXTYID].");

	$sql="SELECT votepoints FROM texty WHERE textyid=$TEXTYID AND activ=1";
	$data=mysql_fetch_row(mysql_query($sql));
	debug(2, "getVotek: Votepoints: [".$data[0]."].");

	return($data[0]);
	debug(2, "getVote: Function end.");
}
?>
