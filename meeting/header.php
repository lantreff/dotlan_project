<?php

$PAGE->sitetitle = $PAGE->htmltitle = _("Meeting");

				$a = 'shortbarbit';
				


				$a1 = 'shortbarlink';
			

			
			if($_GET['action'] == 'add')
			{
				$b = 'shortbarbitselect';
				$c1 = 'shortbarlinkselect';
			}

$output .= "
				<a name='top' ></a>
					<table class='msg2' width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
						<a href='".$global['project_path']."'>Projekt</a>
							&raquo;
						<a href='".$dir."'>Meeting </a>
						
							&raquo; ".$_GET['action']."
						<br>
					</table>
					<br />
					
					<table cellspacing='1' cellpadding='2' border='0' class='msg2'>
  						<tbody>
							<tr class='shortbarrow'>";
							
														
						if($DARF["add"] )
							{$breite = "120";
							$output .= "
							<td width='".$breite."' class='shortbarbit'><a href='?hide=1&action=add' class='shortbarlink'>Neu Anlegen</a></td>
							<td width='2' class='shortbarbitselect'>&nbsp;</td>
							";
							}
$output .= "	
								<td width='".$breite."' class='".$a."'><a href='".$dir."' class='".$a1."'>Übersicht</a></td>
								
							</tr>
						</tbody>
					</table>
					<br />
					<hr>
				";
?>