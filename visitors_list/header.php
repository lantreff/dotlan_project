<?php

$PAGE->sitetitle = $PAGE->htmltitle = _("Visitors List");
				
				$breite = "120";
				$a = 'shortbarbitselect';
				$a1 = 'shortbarlinkselect';
				$b = 'shortbarbit';
				$b1 = 'shortbarlink';
				$c = 'shortbarbit';
				$c1 = 'shortbarlink';
				$d = 'shortbarbit';
				$d1 = 'shortbarlink';
				$e = 'shortbarbit';
				$e1 = 'shortbarlink';
			
			

			
			if($_GET['action'] == 'list_cards')
			{
				$a = 'shortbarbit';
				$a1 = 'shortbarlink';
				$b = 'shortbarbitselect';
				$b1 = 'shortbarlinkselect';
			}
			if($_GET['action'] == 'list_visitors')
			{
				$a = 'shortbarbit';
				$a1 = 'shortbarlink';
				$c = 'shortbarbitselect';
				$c1 = 'shortbarlinkselect';
			}
			if($_GET['action'] == 'historie')
			{
				$a = 'shortbarbit';
				$a1 = 'shortbarlink';
				$d = 'shortbarbitselect';
				$d1 = 'shortbarlinkselect';
			}
			if($_GET['action'] == 'clear')
			{
				$a = 'shortbarbit';
				$a1 = 'shortbarlink';
				$e = 'shortbarbitselect';
				$e1 = 'shortbarlinkselect';
			}
			
			

$output .= "
				<a name='top' ></a>
					<table class='msg2' width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
						<a href='".$global['project_path']."'>Projekt</a>
							&raquo;
						<a href='".$dir."'>Visitors List </a>
						
							&raquo; ".$_GET['action']."
						<br>
					</table>
					<br />
					
					<table cellspacing='1' cellpadding='2' border='0' class='msg2'>
  						<tbody>
							<tr class='shortbarrow'>";
							
														
						
							$output .= "
							<td width='".$breite."' class='".$a."'> <a href='index.php' class='".$a1."'>Besucher anlegen</a></td>
							<td width='2' class='shortbarbitselect'>&nbsp;</td>
							";
						
														
						
						if($DARF["add"] )
							{
							$output .= "
							<td width='".$breite."' class='".$b."'> <a href='index.php?hide=1&action=list_cards' class='".$b1."'>Besucherkarten</a></td>
							
							";
							}
						if($DARF["edit"] )
							{
							$output .= "
							<td width='".$breite."' class='".$c."'> <a href='index.php?hide=1&action=list_visitors' class='".$c1."'>Besucher Liste</a></td>
							<td width='".$breite."' class='".$d."'> <a href='index.php?hide=1&action=historie' class='".$d1."'>Historie</a></td>
							
							";
							}
						if($DARF["del"] )
							{
							$output .= "
							<td width='".$breite."' class='".$e."'> <a href='index.php?hide=1&action=clear' class='".$e1."'>Liste Leeren</a></td>
							
							";
							}
							
				$output .= '<script>';
				if($_GET['action'] && !$_GET['do'])
				{
					$output .= '
						window.onload=function()
					{ document.suche.card_nr.focus(); }
					';
				}
				$output .= '</script>';	
$output .= '	
								
							</tr>
						</tbody>
					</table>
					<br />
					<form action="?hide=1&action=geht" method="POST" name="suche">
									Zum Geht melden Karte einscannen. <input type="text" value="" name="card_nr" size="10">
								</form>
					<hr>
					<br>
						'.$meldung.'
					<br>
				';
				
?>