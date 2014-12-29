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

			
			if($_GET['action'] == 'NEW_Leihe')
			{
				$c = 'shortbarbitselect';
				$c1 = 'shortbarlinkselect';
			}
				if($_GET['action'] == 'rueckgabe')
			{
				$d = 'shortbarbitselect';
				$d1 = 'shortbarlinkselect';
			}
				if($_GET['page'] == 'historie')
			{
				$e = 'shortbarbitselect';
				$e1 = 'shortbarlinkselect';
			}
			if($_GET['page'] == 'gruppen')
			{
				$f = 'shortbarbitselect';
				$f1 = 'shortbarlinkselect';
			}
$output .= "
				<a name='top' ></a>
					<table class='msg2' width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
						<a href='".$global['project_path']."'>Projekt</a>
							&raquo;
						<a href='".$dir."'>Leihsystem </a>
						
							&raquo; ".$_GET['action']."
						<br>
					</table>
					<br />
					
					<table width='100%' cellspacing='1' cellpadding='2' border='0' class='msg2'>
  						<tbody>
							<tr class='shortbarrow'>";
							
														
							$output .= "
								<td width='20%' class='".$b."'><a href='".$global['project_path']."equipment/' class='".$b1."'>Zum Equipment</a></td>
								<td width='20%' class='".$c."'><a href='".$dir."?hide=1&action=NEW_Leihe' class='".$c1."'>Artikel verleihen</a></td>
								<td width='20%' class='".$d."'>
									<form name='rueckgabe' action='".$dir."?hide=1&action=rueckgabe' method='POST'>
										<input name='id_leihe' value='' type='text'>
										<input name='senden' value='R&uuml;ckgabe' type='submit'>
									</form>
									</td>
								<td width='20%' class='".$e."'><a href='historie.php?page=historie'  class='".$e1."'>Historie</a></td>
								<td width='20%' class='".$f."'><a href='gruppen.php?page=gruppen'  class='".$f1."'>Gruppen verwalten</a></td>
							</tr>
						</tbody>
					</table>
					<br />
					<hr>
				";
?>