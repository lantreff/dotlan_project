<?php

$PAGE->sitetitle = $PAGE->htmltitle = _("Meeting");

				$a = 'shortbarbit';
				$a1 = 'shortbarlink';
				$b = 'shortbarbit';
				$b1 = 'shortbarlink';
			

			
			if($_GET['action'] == 'add')
			{
				$b = 'shortbarbitselect';
				$b1 = 'shortbarlinkselect';
			}
			
			if($_GET['action'] != 'add')
			{
				$a = 'shortbarbitselect';
				$a1 = 'shortbarlinkselect';
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
							<td width='".$breite."' class='".$b."'> <a href='index.php?hide=1&action=add' class='".$b1."'>Neu Anlegen</a></td>
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
				
if (isset($_GET['event']))
{
$event_id = $_GET['event'];
}
if (isset($_POST['event']) )
{
$event_id = $_POST['event'];
}
$output .= "<form name='change_event' action='' method='POST'>				
			<select name='event' onChange='document.change_event.submit()''>
				<option value='1'>w&auml;hle das Event !</option>";
				while($out_event_ids = $DB->fetch_array($sql_event_ids))
				{// begin While Historie
					if	($out_event_ids['id'] == $event_id)
					{
		$output .= "					
					<option selected value='".$out_event_ids['id']."'>".$out_event_ids['name']."</option>";
					
					}else
					{
					
	$output .= "					
					<option value='".$out_event_ids['id']."'>".$out_event_ids['name']."</option>";
					}
				}

				
$output .= "									
			</select>
						<!-- <input name='senden' value='Event wechseln' type='submit'> -->
			</form>";
?>