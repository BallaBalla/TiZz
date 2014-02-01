<?php
// Tizz Lib

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
		return(0);
	}
	debug(2, "voteTexty: Start Function with Arg:[$UpOrDown].");
	if(!isset($UpOrDown) || $UpOrDown==""){
		debug(1, "voteTexty: Need Argument Up or Down.");
		debug(1, "voteTexty: Stop Function because arg error.");
		return(0);
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
		return(0);
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
		return(0);
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
		return(0);
	}
	debug(2, "getVote: Start Function with Arg:[$TEXTYID].");

	$sql="SELECT votepoints FROM texty WHERE textyid=$TEXTYID AND activ=1";
	$data=mysql_fetch_row(mysql_query($sql));
	debug(2, "getVotek: Votepoints: [".$data[0]."].");

	return($data[0]);
	debug(2, "getVote: Function end.");
}
//##############################################################################
//		addTexty
//##############################################################################
function addTexty($TITLE, $TEXT, $USERID){
	if(!isset($TITLE) || !isset($TEXT) || !isset($USERID)){
		debug(1, "addTexty: Need Arguments: TITLE, TEXT, USERID.");
		debug(1, "addTexty: Stop Function because arg error.");
		return(0);
	}
	debug(2, "addTexty: Start Function. Title:[$TITLE] USERID:[$USERID].");

	$sql="INSERT INTO texty (Texty,title,userfs) VALUES ('$TEXT','$TITLE',$USERID) ";
	mysql_query($sql);
	debug(2, "addTexty: Insert is make. Now check Insert......");

	$sql="SELECT textyid FROM texty WHERE Texty='$TEXT' AND title='$TITLE' AND userfs=$USERID AND activ=1 ORDER BY textyid DESC";
	$query=mysql_query($sql);
	if(mysql_num_rows($query) <= 0){
		debug(1, "addTexty: Texty not found in DB. Insert has Fail.");
		debug(1, "addTexty: Stop Function because Insert-Fail.");
		return(0);	
	}
	$data=mysql_fetch_row($query);
	debug(2, "addTexty: Insert is found in DB. State: OK. TextyID:[".$data[0]."]");

	//ABOS FEHLEN NOCH
	sendAbo($USERID,$data[0]);
	$textyidid=$data[0];

	//Return TextyID
	debug(2, "addTexty: Function end.");
	return($textyidid);

}
//##############################################################################
//		addAbo
//##############################################################################
function addAbo($MAIL, $USERID){
	if(!isset($MAIL) || !isset($USERID)){
		debug(1, "addAbo: Need Arguments: MAIL, USERID.");
		debug(1, "addAbo: Stop Function because arg error.");
		return(0);
	}
	debug(2, "addAbo: Start Function. E-Mail:[$MAIL] USERID:[$USERID].");

	$actkey=sha1(rand(1,100).time().rand(100,10000)."BANANA");
	$sql="INSERT INTO abonnement (mail,userfs,activationkey) VALUES ('$MAIL',$USERID,'$actkey') ";
	mysql_query($sql);
	debug(2, "addAbo: Insert is make. Now check Insert......");

	$sql="SELECT aboID FROM abonnement WHERE mail='$MAIL' AND activationkey='$actkey' AND userfs=$USERID AND activ=1";
	$query=mysql_query($sql);
	if(mysql_num_rows($query) <= 0){
		debug(1, "addAbo: Abo not found in DB. Insert has Fail.");
		debug(1, "addAbo: Stop Function because Insert-Fail.");
		return(0);	
	}
	$data=mysql_fetch_row($sql);
	debug(2, "addAbo: Insert is found in DB. State: OK. AboID:[".$data[0]."]");

	debug(2, "addAbo: Send Mail to $MAIL.");
	$betreff = "Pete Abonnoment bestaetigen";
	$from = "From: Brain Shit <noreply@platz.halter>";
	$text = "Bestaetigen Sie Ihr Abonnoment mit diesem Link: [http://ka.bue.ch/acti.php?key=$actkey]. Oder ignorieren Sie dieses Mail.";

	mail($MAIL, $betreff, $text, $from);

	//Return AboID
	return($data[0]);
	debug(2, "addAbo: Function end.");
}
//##############################################################################
//		sendAbo
//##############################################################################
function sendAbo($USERID,$TEXTYID){
	if(!isset($USERID) || !isset($TEXTYID)){
		debug(1, "sendAbo: Need Arguments: TextyID, USERID.");
		debug(1, "sendAbo: Stop Function because arg error.");
		return(0);
	}
	debug(2, "sendAbo: Start Function. TextyID[$TEXTYID] USERID:[$USERID].");

	$sql="SELECT title,Texty FROM texty WHERE textyid=$TEXTYID AND activ=1";
	$data=mysql_fetch_row(mysql_query($sql));
	$title=$data[0];
	$text=$data[0];

	$sql="SELECT mail FROM abonnement WHERE userfs=$USERID AND activ=1";
	$query=mysql_query($sql);
	$i=0;
	while($data=mysql_fetch_row($query)){
		$betreff = "Pete.domain.ch: $title";
		$from = "From: Brain Shit <noreply@platz.halter>";
		$text = "New Post: $title:\n$text";

		mail($data[0], $betreff, $text, $from);
		$i++;
	}
	debug(2, "sendAbo: Send (hopfully) $i Mails.");
	debug(2, "sendAbo: Function End.");
}
//##############################################################################
//		trimTexty
//##############################################################################
function trimTexty($TEXT, $ROWS){
	if(!isset($TEXT) || !isset($ROWS)){
		debug(1, "trimTexty: Need Arguments: Text and RowCount.");
		debug(1, "trimTexty: Stop Function because arg error.");
		return(0);
	}
	debug(2, "trimTexty: Start Function.");

	$rows=explode("\n", $TEXT);
	$i=0;
	while($i < $ROWS && $rows[$i]){
		$newTexty.=$rows[$i];
		$i++;
	}

	debug(2, "trimTexty: Function End. Rows: [$i].");
	return($newTexty);
	
}
?>
