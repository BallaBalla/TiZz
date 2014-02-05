<?php
$ls = shell_exec("ls");
$ls_arr=explode("\n", $ls);
foreach($ls_arr as $object){
	echo "<a href='./$object'>$object</a><br>\n";
}
?>
