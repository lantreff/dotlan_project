<?php

$PAGE->sitetitle = $PAGE->htmltitle = _(ucfirst($MODUL_NAME));
				
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
				$f = 'shortbarbit';
				$f1 = 'shortbarlink';
			
			

			
			if($_GET['do'] == 'uebersicht')
			{
				$a = 'shortbarbit';
				$a1 = 'shortbarlink';
				$b = 'shortbarbitselect';
				$b1 = 'shortbarlinkselect';
			}
			if($_GET['do'] == 'add')
			{
				$a = 'shortbarbit';
				$a1 = 'shortbarlink';
				$c = 'shortbarbitselect';
				$c1 = 'shortbarlinkselect';
			}
			if($_GET['page'] == 'gruppen')
			{
				$a = 'shortbarbit';
				$a1 = 'shortbarlink';
				$d = 'shortbarbitselect';
				$d1 = 'shortbarlinkselect';
			}
			if($_GET['do'] == 'vorlagen')
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
						<a href='".$dir."'>".ucfirst($MODUL_NAME)."</a>
						
							&raquo; ".$_GET['action']."
						<br>
					</table>
					<br />
					
					<table cellspacing='1' cellpadding='2' border='0' class='msg2'>
  						<tbody>
							<tr class='shortbarrow'>";
							
														
						
							$output .= "
							<td width='".$breite."' class='".$a."'> <a href='index.php' class='".$a1."'>Meine Aufgaben</a></td>
							<td width='2' class='shortbarbitselect'>&nbsp;</td>
							";
			$output .= "	<td width='".$breite."' class='".$b."'> <a href='index.php?hide=1&do=uebersicht' class='".$b1."'>Übersicht</a></td>";
			
			if($DARF["add"] )
							{
			$output .= "	<td width='".$breite."' class='".$c."'> <a href='index.php?hide=1&do=add' class='".$c1."'>Aufgabe erstellen</a></td>";
			$output .= "	<td width='".$breite."' class='".$d."'> <a href='gruppen.php?page=gruppen' class='".$d1."'>Gruppe erstellen/bearbeiten</a></td>";	
//			$output .= "	<td width='".$breite."' class='".$d."'> <a href='/admin/?do=usergroup' class='".$d1."'>Gruppe erstellen/bearbeiten</a></td>"; // target='_NEW'			
			$output .= "	<td width='".$breite."' class='".$e."'> <a href='index.php?hide=1&do=vorlagen' class='".$e1."'>Vorlagen erstellen/bearbeiten</a></td>";														
							}
			if($DARF["remind"] )
							{
			$output .= "	<td width='".$breite."' class='".$f."'> <a href='index.php?hide=1&do=remind_all' class='".$f1."'>Erinnerungsmail senden</a></td>";
							}
						
							
						
$output .= "	
								
							</tr>
						</tbody>
					</table>
					<br />
					<hr>
					<br>
						".$meldung."
				";
			
?>