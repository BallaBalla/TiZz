<?php

	$id=$_GET['id'];
	include('./preload.php');
	
	addKlick($id);
	if(isset($_GET['vote'])){
		if($_GET['vote']=="up"){
			voteTexty($id, "up");
		}
		if($_GET['vote']=="down"){
			voteTexty($id, "down");
		}
	}
	$texty_arr=getTexty($id);
?>
<table border=1 width=100%>
	<tr  width=100%> <!--- Head --->
		<td width=10% text-align=left>Bild</td>
		<td width=10% text-align=left>	
		
		</td>
	</tr>
	
	<tr  width=100%>
		<td width=10%>
			navigation
		</td>

		<td width=80%>
			<?php

			echo"<textarea style='border:none;width:100%;overflow:hidden;resize: none;background-color: ".chcolor()."; height:100%;' 
				cols=100% rows=10 readonly> -[ ".$texty_arr['Title']." ]-\n\n".trimTexty($texty_arr['Texty'], 10)."</textarea>";

			?>
		</td>

		<td width=10%>	
		Texty:
			<?php		//Rand Words / rightBar
				
			echo"<textarea style='border:none;width:100%;overflow:hidden;resize: none;background-color: ".chcolor()."; height:100%;' 
				cols=100% rows=10 readonly> Title:".$texty_arr['Title']."</textarea>";	

			$sql="SELECT name FROM user WHERE userid=".$texty_arr['userfs'];
			$user_arr=mysql_fetch_row(mysql_query($sql));
			$user=$user_arr['0'];
			echo"<textarea style='border:none;width:100%;overflow:hidden;resize: none;background-color: ".chcolor()."; height:100%;' 
				cols=100% rows=10 readonly> Author:".$user."</textarea>";

			echo"<textarea style='border:none;width:100%;overflow:hidden;resize: none;background-color: ".chcolor()."; height:100%;' 
				cols=100% rows=10 readonly> Klicks:".$texty_arr['klicks']."</textarea>";
	
			echo"<textarea style='border:none;width:100%;overflow:hidden;resize: none;background-color: ".chcolor()."; height:100%;' 
				cols=100% rows=10 readonly> Ponits:".$texty_arr['votepoints']."</textarea>";
	
			echo "<p><a href='./test_texty.php?id=".$id."&vote=up'>Vote Good!</a></p>";

			echo "<p><a href='./test_texty.php?id=".$id."&vote=down'>Vote Bad!</a></p>";		
			?>
		
		</td>
	</tr>
</table>
