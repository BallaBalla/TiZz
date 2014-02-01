<?php
	$order="new";
	include('./preload.php');
?>
<table border=1 width=100%>
	<tr  width=100%> <!--- Head --->
		<td width=10% text-align=left>Bild</td><td width=10% text-align=left>Order</td>
	</tr>
	
	<tr  width=100%>
		<td width=10%>
			navigation
		</td>

		<td width=80%>
			<?php
				$TextyArray=lsTexty($order, 5);
				$i=0;
				while($TextyArray[$i]){
					echo"<textarea border=0>".trimTexty($TextyArray[$i], 10)."</textarea>";
					$i++;
				}
			?>
		</td>

		<td width=10%>
			Words
		</td>
	</tr>
</table>
