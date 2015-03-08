<?php

				$a = 'shortbarbit';
				$a1 = 'shortbarlink';
				$b = 'shortbarbit';
				$b1 = 'shortbarlink';
				$c = 'shortbarbit';
				$c1 = 'shortbarlink';
				$d = 'shortbarbit';
				$d1 = 'shortbarlink';
				$e = 'shortbarbit';
				$e1 = 'shortbarlink';
				$ee = 'shortbarbit';
				$ee1 = 'shortbarlink';
			

			
			if(basename($_SERVER['SCRIPT_NAME']) == 'plan_add.php')
			{
				$b = 'shortbarbitselect';
				$b1 = 'shortbarlinkselect';
			}
			
			if(basename($_SERVER['SCRIPT_NAME']) == 'plan_overview.php')
			{
				$a = 'shortbarbitselect';
				$a1 = 'shortbarlinkselect';
			}
			
			if(basename($_SERVER['SCRIPT_NAME']) == 'plan_my.php')
			{
				$c = 'shortbarbitselect';
				$c1 = 'shortbarlinkselect';
			}
			
			if(basename($_SERVER['SCRIPT_NAME']) == 'index.php')
			{
				$d = 'shortbarbitselect';
				$d1 = 'shortbarlinkselect';
			}
			
			if(basename($_SERVER['SCRIPT_NAME']) == 'plan_admin.php')
			{
				$e = 'shortbarbitselect';
				$e1 = 'shortbarlinkselect';
			}
			
			if(basename($_SERVER['SCRIPT_NAME']) == 'plan_freeze.php')
			{
				$ee = 'shortbarbitselect';
				$ee1 = 'shortbarlinkselect';
			}

$output .= "
				<a name='top' ></a>
					<table class='msg2' width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
						<a href='".$global['project_path']."'>Projekt</a>
							&raquo;
						<a href='".$dir."'>Dienstplan </a>
						
							&raquo; ".$_GET['action']."
						<br>
					</table>
					<br />
					
					<table cellspacing='1' cellpadding='2' border='0' class='msg2'>
  						<tbody>
							<tr class='shortbarrow'>";
							
						$breite = "120";								
						if($DARF["add"] )
							{
							$output .= "
							<td width='".$breite."' class='".$b."'> <a href='plan_add.php' class='".$b1."'>Neu Anlegen / L&ouml;schen / &Auml;ndern</a></td>
							
							";
							}
$output .= "	
								<td width='2' class='shortbarbitselect'>&nbsp;</td>
								<td width='".$breite."' class='".$d."'><a href='index.php' class='".$d1."'>Dienstplan</a></td>
								<td width='".$breite."' class='".$a."'><a href='plan_overview.php' class='".$a1."'>Übersicht</a></td>
								<td width='".$breite."' class='".$c."'><a href='plan_my.php' class='".$c1."'>Mein Plan</a></td>
							";
						if($DARF["edit"] )
						{
							$output .= "
							<td width='".$breite."' class='".$e."'> <a href='plan_admin.php' class='".$e1."'>Plan Zuweisung</a></td>
							
							";
							}
						if($DARF["freeze"] )
						{
							$output .= "
							<td width='".$breite."' class='".$ee."'> <a href='plan_freeze.php' class='".$ee1."'>Freeze</a></td>
							
							";
						}
$output .= "				</tr>
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