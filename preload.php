<?php
//##############################################################################
//		Load Settings
//##############################################################################
	include('./cfg.php');

//##############################################################################
//		Open MySQL Connection
//##############################################################################
	//Zum server verbinden:
	$MC1 = mysql_pconnect("$MCONNECT_HOST","$MCONNECT_USER","$MCONNECT_PW")
		or die ("Interner MySQL Server Verbindungs Fehler.");
	// use DB:
	$MC2 = mysql_select_db($MCONNECT_DB)
		or die ("Interner MySQL Server Verbindungs Fehler.");

//##############################################################################
//		Load TiZzLibary (tzlib.php)
//##############################################################################
	include('./tzlib.php');

?>
