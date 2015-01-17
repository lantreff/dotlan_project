<?php
 
$MODUL_NAME="todo";
include_once("../../../global.php");
include("../functions.php");
include("todo_functions.php");
include('header.php');

// Sortierung //
// Variablen für die Sortierfunktion
	$sort		= "bezeichnung"; // Standardfeld das zum Sortieren genutzt wird
	$order		= "ASC"; // oder DESC | Sortierung aufwerts, abwerts
	$sort_by	= "";
	if (IsSet ($_GET['sort'] ) && IsSet ($_GET['order'] ) )
	{
		$sort		= security_string_input($_GET['sort']);
		$order		= security_string_input($_GET['order']);
		$sort_by = "ORDER BY ".$sort."  ".$order." ";
	}		
////////////////////////////////////////////////

$cardNR 		= $_POST['card_nr'];
//$cardNR 		= $_GET['card_nr'];

if(!$DARF["view"] ) $PAGE->error_die($HTML->gettemplate("error_nopermission"));
else
{// $module_admin_check
	if($_GET['hide'] != 1)
	{
			
	$output .= '
			<h3> Von Mir erstellte Aufgaben </h3>
			<br>';
			
			
		$output .= out_table(list_my_insert_todo($user_id,$event_id,$sort_by),$DARF);
						
		$output .= '			
			<br>
			<br>
			<h3> Mir zugewiesene Aufgaben </h3>
			<br>';
			
			
		$output .= out_table(list_my_todo($user_id,$event_id,$sort_by),$DARF);
						
		$output .= '			
			<br>
			<h3> Meine Gruppen-Aufgaben </h3>
			<br>';
		
			$output .=  out_table(list_my_group_todo($user_id,$event_id,$sort_by),$DARF);
		
		$output .= '
			<br>
			';
	$output .= '<br>';

	}
	else
	{
			
			if($_GET['do'] == "uebersicht")
			{
				$output .= out_table(list_todo($event_id,$sort_by),$DARF);				
			}
			
			
			if( ( $DARF['edit'] ||  $DARF['add'] ) &&  $_GET['do'] == "add" || $_GET['do'] == "edit" )
			{
				$ersteller 		= $user_id;
				$erstellt_datum	= date("Y-m-d");
				$erstellt_zeit 	= date("H:i");
				$end_datum		= date("Y-m-d");
				$end_zeit 		= date("H:i");
				
				if($_GET['do'] == "edit")
				{
					$out = list_single_todo($_GET['id']);
					$ersteller 	= 0; 
					$erstellt_datum	= substr ($out['erstellt'],0,10);
					$erstellt_zeit 	= substr ($out['erstellt'],11,-3);
					$end_datum		= substr ($out['end'],0,10);
					$end_zeit 		= substr ($out['end'],11,-3);			
				}
				
	$output .= '
			<form name="addedit" action="?hide=1&do='.$_GET['do'].'&id='.$_GET["id"].'&senden=ok" method="POST" >
				<table class="msg2" width="100%" cellspacing="1" cellpadding="2" border="0">
					<tbody>
							<tr>
								<td align="LEFT" ><b>Titel</b></td>
								<td align="LEFT" valign="TOP">
									<input type="TEXT"  maxlength="250" size="70" value="'.$out['bezeichnung'].'" name="bezeichnung">
								</td>
							</tr>
							<tr>
								<td align="LEFT" ><b>Beschreibung</b></td>
								<td align="LEFT" valign="TOP">
									<textarea  rows="5" cols="60" wrap="hard" name="beschreibung">'.$out['beschreibung'].'</textarea>
								</td>
							</tr>
							<tr>
								<td align="LEFT" ><b>Startzeitpunkt</b></td>
								<td align="LEFT" width="100%">
									<input type="text" name="erstellt_datum" class="tcal" value="'.$erstellt_datum.'" />
									<input type="text" value="'.$erstellt_zeit.'" size="5" name="erstellt_zeit"> Uhr
								</td>
							</tr>
							<tr>
								<td align="LEFT" ><b>Erledigen bis</b></td>
								<td align="LEFT" width="100%" valign="bottom">
									<input type="text" name="end_datum" class="tcal" value="'.$end_datum.'" />
									<input type="text" value="'.$end_zeit.'" size="5" name="end_zeit"> Uhr
								</td>
							</tr>
							<tr>
								<td nowrap="nowrap" ><b>Gruppe</b></td>
								<td>
									<select name="gruppe">
										<option value="0">Bitte w&auml;hlen</option>';
					
										$sql_gruppe = list_groups();

										while($out_gruppe = mysql_fetch_array($sql_gruppe))
										{
											if($out_gruppe['id'] == $out['gruppe'] )
											{
												$output .= '<option selected value="'.$out_gruppe['id'].'">'.$out_gruppe['bezeichnung'].'</option>';
												//$output .= '<option selected value="'.$out_gruppe['id'].'">'.$out_gruppe['name'].'</option>';
											}
											else
											{
												$output .= '<option value="'.$out_gruppe['id'].'">'.$out_gruppe['bezeichnung'].'</option>';
												//$output .= '<option value="'.$out_gruppe['id'].'">'.$out_gruppe['name'].'</option>';				
											}
										}

						$output .= '</select>
								</td>
							</tr>
							<tr>
								<td nowrap="nowrap" ><b>Teammitglied</b></td>
								<td>
									<select name="bearbeiter" >
										<option value="0">Bitte w&auml;hlen</option>';
					
										$sql_gruppe = list_orgas();

										while($out_group = mysql_fetch_array($sql_gruppe))
										{
											if($out_group['id'] == $out['bearbeiter'] )
											{
												$output .= '<option selected value="'.$out_group['id'].'">'.$out_group['vorname'].' ('.$out_group['nick'].') '.$out_group['nachname'].'</option>';
											}
											else
											{
												$output .= '<option value="'.$out_group['id'].'">'.$out_group['vorname'].' ('.$out_group['nick'].') '.$out_group['nachname'].'</option>';											
											}
										}

						$output .= '</select>
								</td>
							</tr>
							<tr>
								<td ><b>Priorität</b></td>
								<td>
									<select name="prio" >
										<option value="0">Bitte w&auml;hlen</option>';
										$sql_prio = list_prio();

										while($out_prio = mysql_fetch_array($sql_prio))
										{
											if($out_prio['id'] == $out['prio'] )
											{
												$output .= '<option selected value="'.$out_prio['id'].'">'.$out_prio['bezeichnung'].'</option>';
											}
											else
											{
												$output .= '<option value="'.$out_prio['id'].'">'.$out_prio['bezeichnung'].'</option>';											
											}
										}

						$output .= '</select>
								</td>
							</tr>
							<tr>
								<td ><b>Status</b></td>
								<td>
									<select name="status" >';
										$sql_status = list_status();

										while($out_status = mysql_fetch_array($sql_status))
										{
											if($out_status['id'] == $out['status'] )
											{
												$output .= '<option selected value="'.$out_status['id'].'">'.$out_status['bezeichnung'].'</option>';
											}
											else
											{
												$output .= '<option value="'.$out_status['id'].'">'.$out_status['bezeichnung'].'</option>';											
											}
										}

						$output .= '</select>
								</td>
							</tr>
							<tr>
								<td colspan="3">
									<input type="HIDDEN" value="'.$event_id.'" name="event_id">
									<input type="HIDDEN" value="'.$ersteller.'" name="ersteller">
								</td>
							</tr>
									
					</tbody>
				</table>
				<br />
				<input name="senden" value="Daten senden" type="submit">
				<br /><br /><a href="'.$dir.'" target="_parent">Zur&uuml;ck zu Meinen Aufgaben</a>
			</form>';
										
				if($_GET['do'] == "add" && $_GET['senden'] == "ok")
				{
					$meldung 	= todo_add($_POST,$event_id);
					$new_todo 	= todo_check_new($event_id);
								  send_mail($new_todo,1);	
					$PAGE->redirect($dir."index.php",$PAGE->sitetitle,$meldung);
					
				}
				if($_GET['do'] == "edit" && $_GET['senden'] == "ok")
				{
					$meldung = todo_edit($_POST,$_GET['id']);
					$PAGE->redirect($dir."index.php",$PAGE->sitetitle,$meldung);
				}
				
			}
					
			if( $DARF['del'] && $_GET['do'] == "del")
			{
				$out = list_single_todo($_GET['id']);
				
				$output .= "
						<h2 style='color:RED;'>Achtung!!!!<h2>
						<br />

						<p>Sind Sie sich sicher das
						<font style='color:RED;'>".$out['bezeichnung']."</font> gel&ouml;scht werden soll?</p>
						<br />
						<a href='?hide=1&hide1=1&action=list_cards&do=del&id=".$out['id']."&senden=ok' target='_parent'>
						<input value='l&ouml;schen' type='button'></a>
						 \t
						<a href='{BASEDIR}todo/index.php?hide=1&action=list_cards' target='_parent'>
						<input value='Zur&uuml;ck' type='button'></a>
				";
				
				
				if($_GET['senden'] == "ok")
				{
					$meldung = todo_del($_GET['id']);
					$PAGE->redirect($dir."index.php",$PAGE->sitetitle,$meldung);
				}
			}
			
			if( $DARF['remind'] && $_GET['do'] == "remind")
			{
				$out = list_single_todo($_GET['id']);
				$meldung = send_mail($out,0);
				$PAGE->redirect($dir."index.php",$PAGE->sitetitle,$meldung);
			}
			
			if( $DARF['remind'] && $_GET['do'] == "remind_all")
			{
				$sql_remind = list_todo_remember($event_id);

				while($out_remind = mysql_fetch_array($sql_remind))
				{
					$meldung = send_mail($out_remind,0);
				}
				$PAGE->redirect($dir."index.php",$PAGE->sitetitle,$meldung);
			}
		}
		
		if( ( $DARF['edit'] ||  $DARF['add'] ) &&  $_GET['do'] == "vorlagen")
			{
				$breite = "150";
				$b = 'shortbarbit';
				$b1 = 'shortbarlink';
				$c = 'shortbarbit';
				$c1 = 'shortbarlink';

			

			
			if($_GET['action'] == 'add_vorlage')
			{
				$b = 'shortbarbitselect';
				$b1 = 'shortbarlinkselect';
			}
			if($_GET['action'] == 'copy_vorlage')
			{
				$c = 'shortbarbitselect';
				$c1 = 'shortbarlinkselect';
			}
			$output .= '
			
			<table cellspacing="1" cellpadding="2" border="0" class="msg2">
  						<tbody>
							<tr class="shortbarrow">';
							
					$output .= '<td width="'.$breite.'" class="'.$b.'"> <a href="index.php?hide1=1&hide=1&do=vorlagen&action=add_vorlage" class="'.$b1.'">Neue Vorlage</a></td>';
					$output .= '<td width="150" class="'.$c.'"> <a href="index.php?hide1=1&hide=1&do=vorlagen&action=copy_vorlage" class="'.$c1.'">Vorlagen Kopieren</a></td>';
			
				$output .= 	'</tr>
						</tbody>
					</table>
					<br>		';
			if($_GET['hide1'] != 1)
			{
			
			
$output .= '	<table cellspacing="0" cellpadding="0" width="100%" border="0">
					<tbody>
						<tr>
							<td class="msghead" width="30">
								Prio
							</td>
							<td class="msghead"> 
								Bezeichnung
							</td>
							<td class="msghead">
								Beschreibung
							</td> 	 
							<td class="msghead" width="55">
								
							</td>
						</tr>';
				$a = 0;			
				$sql = list_todo_vorlagen();

				while($out = mysql_fetch_array($sql))
				{
				$ersteller 	= list_userdata($out['ersteller']);
				$bearbeiter = list_userdata($out['bearbeiter']);
				$gruppe 	= list_single_group($out['gruppe']);
				$status 	= list_single_status($out['status']);

				$output .= '			
						<tr class="msgrow'.(($a%2)+1).'"  >
							<td align="center">
								<p style="margin-top: 0px; margin-bottom: 0px; height:15px; width:15px;" class="PriorityID-'.$out['prio'].'" ></p>
							</td>
							<td >
								'.$out['bezeichnung'].'
							</td>
							<td >
								'.$out['beschreibung'].'
							</td>
							<td >';
										
										if($DARF["edit"] )
										{ //  Admin
											$output .="
											<a href='?hide1=1&hide=1&do=vorlagen&action=edit_vorlage&id=".$out['id']."' target='_parent'>
											<img src='../images/16/edit.png' title='\"".$out['bezeichnung']."\" anzeigen/&auml;ndern' ></a>
											";
											}
										if($DARF["del"] )
										{ //  Admin
											$output .="
											<a href='?hide1=1&hide=1&do=vorlagen&action=del_vorlage&id=".$out['id']."' target='_parent'>
											<img src='../images/16/editdelete.png' title='\"".$out['bezeichnung']."\" l&ouml;schen'></a>
											<br>
											";
										}
									
															
				$output .= '			</td>
						</tr>';
				$a++;
				}			
						
				$output .= '			
					</tbody>
				</table>

				<br>
				';
			}
			else
			{
					
					if( ( $DARF['edit'] ||  $DARF['add'] ) &&  $_GET['do'] == "vorlagen" && ( $_GET['action'] == "add_vorlage" || $_GET['action'] == "edit_vorlage") )
					{
					$ersteller 		= $user_id;
					$erstellt_datum	= date("Y-m-d");
					$erstellt_zeit 	= date("H:i");
					$end_datum		= date("Y-m-d");
					$end_zeit 		= date("H:i");
					
					if($_GET['action'] == "edit_vorlage")
					{
						$out = list_single_todo_vorlage($_GET['id']);
						$ersteller 	= 0; 
						$erstellt_datum	= substr ($out['erstellt'],0,10);
						$erstellt_zeit 	= substr ($out['erstellt'],11,-3);
						$end_datum		= substr ($out['end'],0,10);
						$end_zeit 		= substr ($out['end'],11,-3);			
					}
				
						
			$output .= '
					<form name="hh" action="?hide1=1&hide=1&do='.$_GET['do'].'&action='.$_GET["action"].'&id='.$_GET["id"].'&senden=ok" method="POST" >
						<table class="msg2" width="100%" cellspacing="1" cellpadding="2" border="0">
					<tbody>
							<tr>
								<td align="LEFT" ><b>Titel</b></td>
								<td align="LEFT" valign="TOP">
									<input type="TEXT"  maxlength="250" size="70" value="'.$out['bezeichnung'].'" name="bezeichnung">
								</td>
							</tr>
							<tr>
								<td align="LEFT" ><b>Beschreibung</b></td>
								<td align="LEFT" valign="TOP">
									<textarea  rows="5" cols="60" wrap="hard" name="beschreibung">'.$out['beschreibung'].'</textarea>
								</td>
							</tr>
							<tr>
								<td align="LEFT" ><b>Startzeitpunkt</b></td>
								<td align="LEFT" width="100%">
									<input type="text" name="erstellt_datum" class="tcal" value="'.$erstellt_datum.'" />
									<input type="text" value="'.$erstellt_zeit.'" size="5" name="erstellt_zeit"> Uhr
								</td>
							</tr>
							<tr>
								<td align="LEFT" ><b>Erledigen bis</b></td>
								<td align="LEFT" width="100%" valign="bottom">
									<input type="text" name="end_datum" class="tcal" value="'.$end_datum.'" />
									<input type="text" value="'.$end_zeit.'" size="5" name="end_zeit"> Uhr
								</td>
							</tr>
							<tr>
								<td nowrap="nowrap" ><b>Gruppe</b></td>
								<td>
									<select name="gruppe">
										<option value="0">Bitte w&auml;hlen</option>';
					
										$sql_gruppe = list_groups();

										while($out_gruppe = mysql_fetch_array($sql_gruppe))
										{
											if($out_gruppe['id'] == $out['gruppe'] )
											{
												$output .= '<option selected value="'.$out_gruppe['id'].'">'.$out_gruppe['bezeichnung'].'</option>';
												//$output .= '<option selected value="'.$out_gruppe['id'].'">'.$out_gruppe['name'].'</option>';
											}
											else
											{
												$output .= '<option value="'.$out_gruppe['id'].'">'.$out_gruppe['bezeichnung'].'</option>';
												//$output .= '<option value="'.$out_gruppe['id'].'">'.$out_gruppe['name'].'</option>';				
											}
										}

						$output .= '</select>
								</td>
							</tr>
							<tr>
								<td nowrap="nowrap" ><b>Teammitglied</b></td>
								<td>
									<select name="bearbeiter" >
										<option value="0">Bitte w&auml;hlen</option>';
					
										$sql_gruppe = list_orgas();

										while($out_group = mysql_fetch_array($sql_gruppe))
										{
											if($out_group['id'] == $out['bearbeiter'] )
											{
												$output .= '<option selected value="'.$out_group['id'].'">'.$out_group['vorname'].' ('.$out_group['nick'].') '.$out_group['nachname'].'</option>';
											}
											else
											{
												$output .= '<option value="'.$out_group['id'].'">'.$out_group['vorname'].' ('.$out_group['nick'].') '.$out_group['nachname'].'</option>';											
											}
										}

						$output .= '</select>
								</td>
							</tr>
							<tr>
								<td ><b>Priorität</b></td>
								<td>
									<select name="prio" >
										<option value="0">Bitte w&auml;hlen</option>';
										$sql_prio = list_prio();

										while($out_prio = mysql_fetch_array($sql_prio))
										{
											if($out_prio['id'] == $out['prio'] )
											{
												$output .= '<option selected value="'.$out_prio['id'].'">'.$out_prio['bezeichnung'].'</option>';
											}
											else
											{
												$output .= '<option value="'.$out_prio['id'].'">'.$out_prio['bezeichnung'].'</option>';											
											}
										}

						$output .= '</select>
								</td>
							</tr>
							<tr>
								<td ><b>Status</b></td>
								<td>
									<select name="status" >';
										$sql_status = list_status();

										while($out_status = mysql_fetch_array($sql_status))
										{
											if($out_status['id'] == $out['status'] )
											{
												$output .= '<option selected value="'.$out_status['id'].'">'.$out_status['bezeichnung'].'</option>';
											}
											else
											{
												$output .= '<option value="'.$out_status['id'].'">'.$out_status['bezeichnung'].'</option>';											
											}
										}

						$output .= '</select>
								</td>
							</tr>
							<tr>
								<td colspan="3">
									<input type="HIDDEN" value="'.$event_id.'" name="event_id">
									<input type="HIDDEN" value="'.$ersteller.'" name="ersteller">
								</td>
							</tr>
									
					</tbody>
				</table>
						<br />
						<input name="senden" value="Daten senden" type="submit">
						<br /><br /><a href="".$dir."" target="_parent">Zur&uuml;ck zur &Uuml;bersicht</a>
					</form>';
												
						if($_GET['action'] == "add_vorlage" && $_GET['senden'] == "ok")
						{
							$meldung = todo_add_vorlage($_POST,$event_id);
							$PAGE->redirect($dir."index.php?hide=1&do=vorlagen",$PAGE->sitetitle,$meldung);
						}
						if($_GET['action'] == "edit_vorlage" && $_GET['senden'] == "ok")
						{
							$meldung = todo_edit_vorlage($_POST,$_GET['id']);
							$PAGE->redirect($dir."index.php?hide=1&do=vorlagen",$PAGE->sitetitle,$meldung);
						}
						
					}
							
					if( $DARF['del'] && $_GET['do'] == "vorlagen" && $_GET['action'] == "del_vorlage")
					{
						$out = list_single_todo_vorlage($_GET['id']);
						
						$output .= "
								<h2 style='color:RED;'>Achtung!!!!<h2>
								<br />

								<p>Sind Sie sich sicher das
								<font style='color:RED;'>".$out['bezeichnung']."</font> gel&ouml;scht werden soll?</p>
								<br />
								<a href='?hide1=1&hide=1&do=vorlagen&action=del_vorlage&id=".$out['id']."&senden=ok' target='_parent'>
								<input value='l&ouml;schen' type='button'></a>
								 \t
								<a href='".$dir."index.php?hide=1&do=vorlagen' target='_parent'>
								<input value='Zur&uuml;ck' type='button'></a>
						";
						
						
						if($_GET['senden'] == "ok")
						{
							$meldung = todo_del_vorlage($_GET['id']);
							$PAGE->redirect($dir."index.php?hide=1&do=vorlagen",$PAGE->sitetitle,$meldung);
						}
					}
					
					if( $DARF['del'] && $_GET['do'] == "vorlagen" && $_GET['action'] == "copy_vorlage")
					{	
						 // Select all - Zeile
					$output .= "<script>
								  function select_all(vorlage){
									var state = document.getElementById(vorlage).checked;
									var boxes = document.getElementsByTagName('input');
									var regex = new RegExp('^'+vorlage+'_');

									for(var i=0; i<boxes.length; i++){
									  if(boxes[i].type == 'checkbox' && boxes[i].id.match(regex)){
										boxes[i].checked = state;
									  }
									}
								  }
								</script>";
						
						$sql = list_todo_vorlagen();
						$vorlage = 'check';
			$output .= "<h3><b> Markierte Vorlagen in aktuelles Event übertragen!</b></h3>
						<form method='post' action='index.php?hide1=1&hide=1&do=vorlagen&action=copy_vorlage&senden=ok' >
						<br>
							<table width='100%' cellspacing='1' cellpadding='2' border='0'>
								<tbody>
								<tr>
									<td>
										 <input type='checkbox' id='".$vorlage."' onClick='select_all(\"".$vorlage."\");'> select all
									</td>
								</tr>";
						$m = 0;
						while($out = mysql_fetch_array($sql))
						{
							
						$output .= '<tr class="msgrow'.(($m%2)?1:2).'">
										<td width="100%"  class="shortbarbit_left">
											<input id=check_recht type="checkbox" name="vorlage_ids[]" value="'.$out['id'].'" >'.$out['bezeichnung'].' | '.$out['beschreibung'].'</input>
										</td>
									</tr>';
							$m++;
						} // end while
							
						
						$output .= '
								</tbody>
							</table>
												<br>
						<input type="submit" value="ToDo/s Kopieren" />
						</form>';
						
						if($_GET['action'] == "copy_vorlage" && $_GET['senden'] == "ok")
						{
							$output .= 'DATEN abgesendet!';
							$meldung = todo_copy_vorlage($_POST,$event_id);
							$PAGE->redirect($dir."index.php?hide=1&do=vorlagen",$PAGE->sitetitle,$meldung);
						}
						
					}
			
			}
		}
}
$PAGE->render($output);
?>