<?php
				$a = 'shortbarbit';
				$b = 'shortbarbit';
				$c = 'shortbarbit';
				$d = 'shortbarbit';
				$e = 'shortbarbit';
				$f = 'shortbarbit';


				$a1 = 'shortbarlink';
				$b1 = 'shortbarlink';
				$c1 = 'shortbarlink';
				$d1 = 'shortbarlink';
				$e1 = 'shortbarlink';
				$f1 = 'shortbarlink';
				
		
			if(!$_GET['action'] && !$_GET['page'])
			{
				$a = 'shortbarbitselect';
				$a1 = 'shortbarlinkselect';
			}
				if($_GET['action'] == 'add')
			{
				$d = 'shortbarbitselect';
				$d1 = 'shortbarlinkselect';
			}
			if($_GET['page'] == 'admin')
			{
				$b = 'shortbarbitselect';
				$b1 = 'shortbarlinkselect';
			}
$output .= "
				<a name='top' ></a>
					<table width='100%' cellspacing='2' cellpadding='2' border='0' class='msg2'>
  						<tbody>
							<tr class='shortbarrow'>";
							
														
							$output .= "
								<td width='20%' class='".$a."'><a href='".$dir."' class='".$a1."'>&Uuml;bersicht</a></td>
								<td width='1%' class='shortbarbitselect'>&nbsp;</td>
							";
/*							
							if($DARF["edit"])
							{
							$output .= "				
								<td width='20%' class='".$b."'><a href='?page=admin'  class='".$fb."'>&Uuml;bersicht</a></td>
							";
							}
							*/
							if($DARF["add"])
							{
							$output .= '
								<td width="20%" class="'.$d.'"><a href="?hide=1&action=add" class="'.$d1.'">hinzuf&uumlgen</a></td>
							';
							}
							$output .= "
							</tr>
						</tbody>
					</table>
					<br />
					<hr>		
</form>
	";
	
	$output .= $meldung;
?>