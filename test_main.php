<?php
	$order="new";
	if(isset($_GET['sort'])){
		if($_GET['sort']=="new"){
			$order="new";
		}
		if($_GET['sort']=="old"){
			$order="old";
		}
		if($_GET['sort']=="mklicks"){
			$order="mklicks";
		}
		if($_GET['sort']=="lklicks"){
			$order="lklicks";
		}
		if($_GET['sort']=="bvote"){
			$order="bvote";
		}
		if($_GET['sort']=="wvote"){
			$order="wvote";
		}
	}
	include('./preload.php');
?>
<table border=1 width=100%>
	<tr  width=100%> <!--- Head --->
		<td width=10% text-align=left>Bild</td>
		<td width=10% text-align=left>	
						<a href="./test_main.php?sort=new">Neuste</a> 			<a href="./test_main.php?sort=old">&Auml;lteste</a> 
						<a href="./test_main.php?sort=bvote">Beste Bewertung</a>	<a href="./test_main.php?sort=wvote">Niedrigste Bewertung</a>
						<a href="./test_main.php?sort=mklicks">Meist gelesene</a>	<a href="./test_main.php?sort=lklicks">Wenigsten gelesen</a>
		</td>
	</tr>
	
	<tr  width=100%>
		<td width=10%>
			navigation
		</td>

		<td width=80%>
			<?php
				$TextyArray=lsTexty($order, 10);
				$i=0;

				while($TextyArray[$i]){
					$Texty_array=getTexty($TextyArray[$i]);
					
					echo"<textarea style='border:none;width:100%;overflow:hidden;resize: none;background-color: ".chcolor().";' 
						cols=100% rows=10 readonly onclick=\"location.href='./test_texty.php?id=".$Texty_array['TextyID']."'\"> -[ ".$Texty_array['Title']." ]-\n\n".trimTexty($Texty_array['Texty'], 10)."</textarea>";
					$i++;
				}
			?>
		</td>

		<td width=10%>	
		Random Words:
				<?php		//Rand Words / rightBar
				$i=0;
				while($i<28){
					$Wordtext="";
					$txtyid=rndTextyID();
					$Words=rndWords($txtyid);
					$o=1;
					while($Words["word".$o] && $o<=10){
						$Wordtext.=$Words["word".$o]." ";
						$o++;
					}
					echo"<textarea style='border:none;width:100%;overflow:hidden;resize: none;background-color: ".chcolor().";' 
						cols=100% rows=2 readonly onclick=\"location.href='./test_texty.php?id=".$txtyid."'\">".$Wordtext."</textarea>";
					$i++;
				}

				?>
			
		</td>
	</tr>
</table>
