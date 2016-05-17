<?php
function list_userdata($id)
{
	$sql = "SELECT * FROM `user` WHERE id = '".$id."' ";
	$out =  mysql_fetch_array( mysql_query($sql) );
	return $out;
}

function list_groups()
{
	$sql = "SELECT * FROM `project_todo_gruppen`";
	//$sql = "SELECT * FROM `user_groups`";
	$out =  mysql_query($sql);
	return $out;
}

function list_single_group($id)
{
	$sql = "SELECT * FROM `project_todo_gruppen` WHERE id = '".$id."' ";
	//$sql = "SELECT * FROM `user_groups` WHERE id = '".$id."' ";
	$out =  mysql_fetch_array( mysql_query($sql) );
	return $out;
}

function list_prio()
{
	$sql = "SELECT * FROM `project_todo_prio`";
	$out =  mysql_query($sql);
	return $out;
}

function list_single_prio($id)
{
	$sql = "SELECT * FROM `project_todo_prio` WHERE id = '".$id."' ";
	//$sql = "SELECT * FROM `user_groups` WHERE id = '".$id."' ";
	$out =  mysql_fetch_array( mysql_query($sql) );
	return $out;
}

function list_status()
{
	$sql = "SELECT * FROM `project_todo_status`";
	$out =  mysql_query($sql);
	return $out;
}
function list_single_status($id)
{
	$sql = "SELECT * FROM `project_todo_status` WHERE id = ".$id." ";
	$out =  mysql_fetch_array( mysql_query($sql) );
	return $out;
}

####### TODO #######

function list_todo($event_id,$sort_by)
{
	$sql = "SELECT * FROM `project_todo` WHERE event_id = ".$event_id." $sort_by ";
	$out =  mysql_query($sql);
	return $out;
}

function list_single_todo($id)
{
	$sql = "SELECT * FROM `project_todo` WHERE id = ".$id." ";
	$out =  mysql_fetch_array(mysql_query($sql));
	return $out;
}

function list_my_todo($id,$event_id,$user_id,$sort_by)
{
	$sql = "SELECT * FROM `project_todo` WHERE bearbeiter = '".$id."' AND ersteller <> '".$user_id."' AND gruppe = 0  AND event_id ='".$event_id."' $sort_by ";
	$out =  mysql_query($sql);
	return $out;
}

function list_my_insert_todo($id,$event_id,$sort_by)
{
	$sql = "SELECT * FROM `project_todo` WHERE ersteller = '".$id."' AND event_id ='".$event_id."' $sort_by ";
	$out =  mysql_query($sql);
	return $out;
}

function list_my_group_todo($id,$event_id,$sort_by)
{
	
	$sql = "SELECT *,u.vorname,u.nick,u.nachname,g2u.group_id,u.id,t.id AS id FROM ( user AS u JOIN project_todo_g2u AS g2u ON u.id = g2u.user_id ) JOIN project_todo AS t ON g2u.group_id = t.gruppe AND g2u.user_id = ".$id."  AND t.event_id ='".$event_id."' $sort_by ";
	$out =  mysql_query($sql);
	return $out;
}

function todo_add($daten)
{
	$sql = "INSERT INTO `project_todo` (`id`, `bezeichnung`, `beschreibung`, `prio`, `end`, `gruppe`, `bearbeiter`, `ersteller`, `erstellt`, `status`, `event_id`) VALUES (NULL, '".$daten['bezeichnung']."', '".$daten['beschreibung']."', '".$daten['prio']."', '".$daten['end_datum']." ".$daten['end_zeit'].":00', '".$daten['gruppe']."', '".$daten['bearbeiter']."', '".$daten['ersteller']."', '".$daten['erstellt_datum']." ".$daten['erstellt_zeit'].":00', '', '".$daten['event_id']."' );";
	$out =  mysql_query($sql);
	
	$meldung = "Die Daten wurde gespeichert!";
	return $meldung;
}

function todo_edit($daten,$id)
{
	$sql = "UPDATE `project_todo` SET `bezeichnung` = '".$daten['bezeichnung']."',`beschreibung` = '".$daten['beschreibung']."', `prio` = '".$daten['prio']."', `end` = '".$daten['end_datum']." ".$daten['end_zeit'].":00', `gruppe` = '".$daten['gruppe']."', `bearbeiter` = '".$daten['bearbeiter']."', `erstellt` = '".$daten['erstellt_datum']." ".$daten['erstellt_zeit'].":00', `status` = '".$daten['status']."' WHERE `id` = ".$id." ;";
	
	$out =  mysql_query( $sql); 	
	$meldung = "Die Daten wurde gespeichert!";
	return $meldung;
}

function todo_del($id)
{
	$sql = "DELETE FROM `project_todo` WHERE `id` = ".$id."";
	$out =  mysql_query($sql);
	
	$meldung = "Die ToDo wurde gel&ouml;scht!";
	return $meldung;
}

function list_todo_remember($event_id)
{
	$sql = "SELECT * FROM `project_todo` WHERE event_id = ".$event_id." AND status != 11  ";
	$out =   mysql_query($sql);
	return $out;
}

function todo_check_new($event_id)
{
	$sql = "SELECT * FROM `project_todo` WHERE event_id = ".$event_id." ORDER BY id DESC LIMIT 1 ";
	$out =   mysql_fetch_array( mysql_query($sql) );
	return $out;
}

####### TODO #######

####### VORLAGEN #######

function list_todo_vorlagen()
{
	$sql = "SELECT * FROM `project_todo_vorlagen` ";
	$out =  mysql_query($sql);
	return $out;
}

function list_single_todo_vorlage($id)
{
	$sql = "SELECT * FROM `project_todo_vorlagen` WHERE id = ".$id." ";
	$out =  mysql_fetch_array(mysql_query($sql));
	return $out;
}

function todo_add_vorlage($daten)
{
	$sql = "INSERT INTO `project_todo_vorlagen` (`id`, `bezeichnung`, `beschreibung`, `prio`, `end`, `gruppe`, `bearbeiter`, `ersteller`, `erstellt`, `status`, `event_id`) VALUES (NULL, '".$daten['bezeichnung']."', '".$daten['beschreibung']."', '".$daten['prio']."', '".$daten['end_datum']." ".$daten['end_zeit'].":00', '".$daten['gruppe']."', '".$daten['bearbeiter']."', '".$daten['ersteller']."', '".$daten['erstellt_datum']." ".$daten['erstellt_zeit'].":00', '', '".$daten['event_id']."' );";
	$out =  mysql_query($sql);
	
	$meldung = "Die Daten wurde gespeichert!";
	return $meldung;
}

function todo_edit_vorlage($daten,$id)
{
	$sql = "UPDATE `project_todo_vorlagen` SET `bezeichnung` = '".$daten['bezeichnung']."',`beschreibung` = '".$daten['beschreibung']."', `prio` = '".$daten['prio']."', `end` = '".$daten['end_datum']." ".$daten['end_zeit'].":00', `gruppe` = '".$daten['gruppe']."', `bearbeiter` = '".$daten['bearbeiter']."', `erstellt` = '".$daten['erstellt_datum']." ".$daten['erstellt_zeit'].":00', `status` = '".$daten['status']."' WHERE `id` = ".$id." ;";
	
	$out =  mysql_query( $sql); 	
	$meldung = "Die Daten wurde gespeichert!";
	return $meldung;
}

function todo_del_vorlage($id)
{
	$sql = "DELETE FROM `project_todo_vorlagen` WHERE `id` = ".$id."";
	$out =  mysql_query($sql);
	
	$meldung = "Die ToDo wurde gel&ouml;scht!";
	return $meldung;
}

function todo_copy_vorlage($ids,$event_id)
{
	foreach($ids['vorlage_ids'] AS $id)
	{
	$sql_update = "UPDATE `project_todo_vorlagen` SET `event_id` = '".$event_id."' WHERE `id` = ".$id." ;";
	$update = mysql_query($sql_update);
	
	$sql_insert = "INSERT INTO project_todo(bezeichnung, beschreibung, prio, end, gruppe, bearbeiter, ersteller, erstellt, status ,event_id)
			SELECT bezeichnung, beschreibung, prio, DATE_FORMAT(end  + INTERVAL 1 YEAR, '%Y-%m-%d %H:%i:%s' ) AS end, gruppe, bearbeiter, ersteller,  DATE_FORMAT(erstellt  + INTERVAL 1 YEAR, '%Y-%m-%d %H:%i:%s' ) AS erstellt, status ,event_id
			FROM project_todo_vorlagen
			WHERE id = ".$id."";
			
		$insert =  mysql_query($sql_insert);
	}
	$meldung = "Die ToDo/'s wurde Kopiert!";
	return $meldung;
}

####### VORLAGEN ########
 
####### Table ###########
function out_table($sql,$DARF,$user_id){
	if(mysql_num_rows($sql) > 0)
	{
		$output .='
			<table cellspacing="0" cellpadding="0" width="100%" border="0">
				<tbody>
					<tr>
						<td class="msghead" width="40">
							Prio';
							if ( $_GET['order'] == "ASC" && $_GET['sort'] == "prio" )
							{
								$output .= '<a href="/admin/projekt/todo/?hide='.$_GET['hide'].'&do='.$_GET['do'].'&sort=prio&order=DESC" > <img src="/admin/projekt/images/16/minisort2.gif" alt="Sortieren nach Prio" border="0" > </a>';
							}
							else
							{
								$output .= '<a href="/admin/projekt/todo/?hide='.$_GET['hide'].'&do='.$_GET['do'].'&sort=prio&order=ASC" > <img src="/admin/projekt/images/16/minisort.gif" alt="Sortieren nach Prio" border="0" > </a>';
							}
			$output .= '
						</td>
						<td class="msghead" width="90" >
							Fortschritt
							';
							if ( $_GET['order'] == "ASC" && $_GET['sort'] == "status" )
							{
								$output .= '<a href="/admin/projekt/todo/?hide='.$_GET['hide'].'&do='.$_GET['do'].'&sort=status&order=DESC" > <img src="/admin/projekt/images/16/minisort2.gif" alt="Sortieren nach Fortschritt" border="0" > </a>';
							}
							else
							{
								$output .= '<a href="/admin/projekt/todo/?hide='.$_GET['hide'].'&do='.$_GET['do'].'&sort=status&order=ASC" > <img src="/admin/projekt/images/16/minisort.gif" alt="Sortieren nach Fortschritt" border="0" > </a>';
							}
			$output .= '
						</td>
						<td class="msghead"> 
							Bezeichnung
							';
							if ( $_GET['order'] == "ASC" && $_GET['sort'] == "bezeichnung" )
							{
								$output .= '<a href="/admin/projekt/todo/?hide='.$_GET['hide'].'&do='.$_GET['do'].'&sort=bezeichnung&order=DESC" > <img src="/admin/projekt/images/16/minisort2.gif" alt="Sortieren nach Bezeichnung" border="0" > </a>';
							}
							else
							{
								$output .= '<a href="/admin/projekt/todo/?hide='.$_GET['hide'].'&do='.$_GET['do'].'&sort=bezeichnung&order=ASC" > <img src="/admin/projekt/images/16/minisort.gif" alt="Sortieren nach Bezeichnung" border="0" > </a>';
							}
			$output .= '
						</td>
						<td class="msghead" width="200">
							Gruppe/Bearbeiter
							';
							if ( $_GET['order'] == "ASC" && $_GET['sort'] == "gruppe,bearbeiter" )
							{
								$output .= '<a href="/admin/projekt/todo/?hide='.$_GET['hide'].'&do='.$_GET['do'].'&sort=gruppe,bearbeiter&order=DESC" > <img src="/admin/projekt/images/16/minisort2.gif" alt="Sortieren nach Gruppe/Bearbeiter" border="0" > </a>';
							}
							else
							{
								$output .= '<a href="/admin/projekt/todo/?hide='.$_GET['hide'].'&do='.$_GET['do'].'&sort=gruppe,bearbeiter&order=ASC" > <img src="/admin/projekt/images/16/minisort.gif" alt="Sortieren nach Gruppe/Bearbeiter" border="0" > </a>';
							}
			$output .= '
						</td> 	 
						<td class="msghead" width="110" style="padding-left:5px;">
							vom/fällig
							';
							if ( $_GET['order'] == "ASC" && $_GET['sort'] == "erstellt" )
							{
								$output .= '<a href="/admin/projekt/todo/?hide='.$_GET['hide'].'&do='.$_GET['do'].'&sort=erstellt&order=DESC" > <img src="/admin/projekt/images/16/minisort2.gif" alt="Sortieren nach fällig bis" border="0" > </a>';
							}
							else
							{
								$output .= '<a href="/admin/projekt/todo/?hide='.$_GET['hide'].'&do='.$_GET['do'].'&sort=erstellt&order=ASC" > <img src="/admin/projekt/images/16/minisort.gif" alt="Sortieren nach fällig bis" border="0" > </a>';
							}
							
							$admin_breite = 36;
							if($DARF["remind"] )
							{
								$admin_breite = 56;
							}
			$output .= '
						</td>
						<td class="msghead" width="'.$admin_breite.'">
							
						</td>
					</tr>';
					
				$a = 0;			
				

				while($out = mysql_fetch_array($sql))
				{
				$ersteller 	= list_userdata($out['ersteller']);
				$bearbeiter = list_userdata($out['bearbeiter']);
				$gruppe 	= list_single_group($out['gruppe']);
				$status 	= list_single_status($out['status']);
				$prio 		= list_single_prio($out['prio']);

			$output .= '			
					<tr class="msgrow'.(($a%2)+1).'"  >
						<td align="center">
							<p style="margin-top: 0px; margin-bottom: 0px; height:15px; width:15px;" class="PriorityID-'.$prio['id'].'" title="'.$prio['bezeichnung'].'"></p>
						</td>
						<td  valign="center" style="text-align:center;  padding-right: 5px;">';
						if($status['bezeichnung'])
						{
		$output .= '
							<p style="margin-top: 0px; margin-bottom: 0px;   height:15px; width:'.$status['bezeichnung'].'; background-color:green;" >
								'.$status['bezeichnung'].'
							</p>
							';
						}
						else
						{
		$output .= 			$status['bezeichnung'];
						}
							
		$output .= '					
						</td>
						<td id="box">
							<div>
								'.$out['bezeichnung'].'							
								<span>
									'.$ersteller['vorname'].' ('.$ersteller['nick'].') '.$ersteller['nachname'].' <br>
									am '.time2german($out['erstellt']).'<br><br>
									'.$out['beschreibung'].'
								</span>
							</div>
							
						</td>
						<td valign="top" > 
							'.$gruppe['bezeichnung'].' '.$gruppe['name'];
						if($bearbeiter['id'])
						{				
		$output .= '	<br>
							'.$bearbeiter['vorname'].' ('.$bearbeiter['nick'].') '.$bearbeiter['nachname'];
						}
							
		$output .= '					
						</td>';
						$timestamp_2 = time() - (14 * 24 * 60 * 60); // 2 Wochen
						$wochen_2	= date( "Y-m-d H:i:s", $timestamp_2); 
						$backgroundcolor = "style='padding-left:5px;'";
						
						if($out['end'] > $wochen_2 				&& $out['prio'] >= 3 ){$backgroundcolor = " style='background-color:ORANGE; padding-left:5px;'";}
						if($out['end'] < date( "Y-m-d H:i:s") 	&& $out['prio'] >= 3 ){$backgroundcolor = " style='background-color:RED; 	padding-left:5px;'";}
						if($status['id']== 11 										 ){$backgroundcolor = " style='background-color:GREEN; 	padding-left:5px;'";}
						
		$output .= '		
						<td id="box" '.$backgroundcolor.'>
							<div>
								'.time2german($out['end']).'							
								<span>
									Erstellt am '.time2german($out['erstellt']).'<br>									
								</span>
							</div>
							
						</td>
						<td >';
									
									if($DARF["edit"] || $out['bearbeiter'] == $user_id)
									{ //  Admin
										$output .="
										<a href='?hide=1&do=edit&id=".$out['id']."' target='_parent'>
										<img src='/admin/projekt/images/16/edit.png' title='\"".$out['bezeichnung']."\" anzeigen/&auml;ndern' ></a>
										";
										}
									if($DARF["del"] )
									{ //  Admin
										$output .="
										<a href='?hide=1&do=del&id=".$out['id']."' target='_parent'>
										<img src='/admin/projekt/images/16/editdelete.png' title='\"".$out['bezeichnung']."\" l&ouml;schen'></a>
										";
									}
									if($DARF["remind"] && $status['id'] != "11")
									{ //  Admin
										$output .='
										<a onClick= "return confSend();" href="?hide=1&do=remind&id='.$out['id'].'" target="_parent">
										<img src="/admin/projekt/images/16/mail_send.png" title="Erinnerungsmail zur Aufgabe '.$out['bezeichnung'].' senden?"></a>
										<br>
										';
									}
								
														
			$output .= '</td>
					</tr>
							';
				$a++;
				}			
							
				$output .= '			
				</tbody>
			</table>';
	}
			
	return $output;
}
####### Table ###########


function send_mail($out,$new)
{	
	// s "GRP: ".$out['gruppe']."<br> USR: ".$out['bearbeiter']."<br>";
	global $global;	
		
		$new_remind = "ist noch nicht erledigt";
		if($new == 1){$new_remind = "ist erstellt worden";}
		
	if($out['gruppe'] > 0){
	$sql_team = mysql_query("SELECT * FROM project_todo_gruppen WHERE id ='".$out['gruppe']."'");
	if(mysql_num_rows($sql_team) > 0)
			{	
				$team = mysql_fetch_array($sql_team);
				$name = "Team ".$team['bezeichnung'];
				$hallo = "Hallo "	.$name.","
									."<br>"
									."<br>"
									."Eine deiner Gruppe zugewiesene Aufgabe ".$new_remind."!"
									."<br>"
									."<br>"
									."<br>"
									."Die Aufgabe sollte bis ".time2german($out['end'])." Uhr erledigt sein!"
									."<br>"
									."Bitte kümmeret euch darum, dass die Aufgabe pünktlich erledigt wird."
									."<br>"
									."Solltest Ihr Hilfe benötigen oder zu wenig Zeit haben, fragt bitte nach oder gebt bescheid."
									."<br>"	
									."<br>";		
			}
	}
	if($out['bearbeiter'] > 0 && $out['gruppe'] == 0){
	$sql_user = mysql_query("SELECT * FROM user WHERE id ='".$out['bearbeiter']."'");
	if(mysql_num_rows($sql_user) > 0)
			{	
				$user = mysql_fetch_array($sql_user);
				$name = $user['vorname'];
				$hallo = "Hallo "	.$name.","
									."<br>"
									."<br>"
									."Eine dir zugewiesene Aufgabe ".$new_remind."!"
									."<br>"
									."<br>"
									."<br>"
									."Die Aufgabe sollte bis ".time2german($out['end'])." Uhr erledigt sein!"
									."<br>"
									."Bitte kümmere dich darum, dass die Aufgabe pünktlich erledigt wird."
									."<br>"
									."Solltest Du Hilfe benötigen oder zu wenig Zeit haben, frag bitte nach oder gib bescheid."
									."<br>"	
									."<br>";				
			}
	}
	
	
	$email_text		=	$hallo
												."Hier die Infos:"
												."<br>"
												."<br>"
												."Aufgabe: ".$out['bezeichnung']
												."<br>"
												."<b>Beschreibung:</b>"
												."<br>"
												.$out['beschreibung']
												."<br>"
												."<br>"
												."Hier gelangst du zur Aufgabe:"
												."<br>"
												." <a href='\http://".$_SERVER["SERVER_NAME"]."/admin/projekt/todo/index.php?hide&#61;1&do&#61;edit&id&#61;".$out['id']."'>http://".$_SERVER["SERVER_NAME"]."/admin/projekt/todo/index.php?hide&#61;1&do&#61;edit&id&#61;".$out['id']."</a>
													";
												
				$betreff 		= ucfirst($global['sitename'])." Erinnerung an Aufgabe ".$out['bezeichnung'];
				$absender 		= $global['email'];
				
				$header  	 = "MIME-Version: 1.0\r\n";
				$header 	.= "Content-type: text/html; charset=iso-8859-1\r\n";
				$header 	.= "Content-Transfer-Encoding: quoted-printable\r\n";
				$header 	.= "From: $absender\r\n";
				$header 	.= "Reply-To: $absender\r\n";
				$header 	.= "X-Mailer: PHP/".phpversion();

			##################################################################################################################################
			if($out['gruppe'] > 0){
				if(mysql_num_rows($sql_team) > 0)
				{
					//$out_mail_id = mysql_fetch_array(mysql_query("SELECT * FROM project_todo WHERE id ='".$out['id']."'"));	
					$orga_id =	mysql_query("SELECT * FROM project_todo_g2u WHERE group_id ='".$out['gruppe']."'");
			
						while($out_orga_id = mysql_fetch_array($orga_id))
						{
							$out_mail_grp = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE id ='".$out_orga_id['user_id']."'"));
						
							$empfaenger		= $out_mail_grp['email'];

								######################################################################################################
									mail($empfaenger, $betreff, $email_text, $header);
								######################################################################################################
						}
				}
			}
			if($out['bearbeiter'] > 0 && $out['gruppe'] == 0){
				if(mysql_num_rows($sql_user) > 0)
				{
					
					$empfaenger		= $user['email'];

					######################################################################################################
						mail($empfaenger, $betreff, $email_text, $header);
					######################################################################################################
				}
			}
			
			$meldung = "Mail Gesendet";
			return $meldung;
			
}

######### MAIL als Tabelle

function send_mail_table($sql,$sql1)
{	
	$out = mysql_fetch_array($sql1);
	$sql_team = mysql_query("SELECT * FROM project_todo_gruppen WHERE id ='".$out['gruppe']."'");
	$sql_user = mysql_query("SELECT * FROM user WHERE id ='".$out['bearbeiter']."'");
	
	//$meldung .= "GRP: ".$out['gruppe']."<br> USR: ".$out['bearbeiter']."<br>";
	//$meldung .= out_table($sql1,$DARF);
	
	global $global;	
		
		$new_remind = "ist noch nicht erledigt";
		if($new == 1){$new_remind = "ist erstellt worden";}
		
	if($out['gruppe'] > 0){
	
	if(mysql_num_rows($sql_team) > 0)
			{	
				$team = mysql_fetch_array($sql_team);
				$name = "Team ".$team['bezeichnung'];
				$hallo = "Hallo "	.$name.","
									."<br>"
									."<br>"
									."folgende Aufgabe/n ist/sind noch nicht erledigt!"
									."<br>"
									."<br>"
									."Bitte kümmert euch darum, dass die Aufgaben pünktlich erledigt werden."
									."<br>"
									."Solltest ihr Hilfe benötigen oder zu wenig Zeit haben, fragt bitte nach oder gebt bescheid."
									."<br>"	
									."<br>";		
			}
	}
	if($out['bearbeiter'] > 0 && $out['gruppe'] == 0){
	
	if(mysql_num_rows($sql_user) > 0)
			{	
				$user = mysql_fetch_array($sql_user);
				$name = $user['vorname'];
				$hallo = "Hallo "	.$name.","
									."<br>"
									."<br>"
									."folgende Aufgabe/n ist/sind noch nicht erledigt!"
									."<br>"
									."<br>"
									."Bitte kümmere dich darum, dass die Aufgabe pünktlich erledigt wird."
									."<br>"
									."Solltest du Hilfe benötigen oder zu wenig Zeit haben, frag bitte nach oder gib bescheid."
									."<br>"	
									."<br>";				
			}
	}
	
	
	$email_text		= utf8_decode(utf8_encode(	'<html>'
												.'<head>'
												.'<link rel="stylesheet" type="text/css" href="/styles/maxlan.css">'
												.'<script type="text/javascript" src="/styles/Project.js"></script>'
												.'
												<style type="text/css">
												#box div:hover {
													/* background: none repeat scroll 0 0 #FFFFFF; */
													position: relative;
												}
												#box div span {
													display: none;
													font-size: 12px;
													text-align: left;
												}
												#box div:hover span {
													background: none repeat scroll 0 0 #FFFFFF;
													border: solid 1px #000000;
													color: #000000;
													display: block;
													left: 0;
													padding: 15px;
													position: absolute;
													top: 20px;
													
													width: 250px;
												}
												.msg				{background-color: #FFFFFF;}
												.msg2				{background-color: #FFFFFF;}
												.msgbody			{background-color: #F0F0F0; color: #000000;}
												.msghead			{background-color: #e6e6e6; color: #000000;  font-weight: bold;}
												.msghead2			{background-color: #FAFAFA; color: #000000; font-size: 10px; font-weight: bold; text-align: center;}
												.msghead3			{background-color: #FAFAFA; color: #000000; font-size: 10px; border-bottom: solid 1px #C33333;}
												.msgrow1			{background-color: #FFFFFF; color: #000000;}
												.msgrow2			{background-color: #e6e6e6; color: #000000;}
												.msgrowRED 			{background-color: #FF0000; color: #000000;}
												.msgrowRED a			{color: #000000;}
												.msgrowRED td 		{ color: #000000;}
												.msgrowRED a:hover	{ color: #99CC00;}
												.msgrowORANGE 		{background-color: #FF8000; color: #000000;}
												.msgrowORANGE a		{ color: #000000;}
												.msgrowORANGE a:hover{ color: #99CC00;}
												.msgrowORANGE td 	{ color: #000000;}
												.msg_over			{background-color: #C33333; color: #000000;}
												.small
												.PriorityID-1 {
													background-color: #49FF49;
													font-family: Geneva,Helvetica,Arial,sans-serif;
													font-size: 11px;
													width: 18px;
													height: 18px;
												   /* padding-bottom: 17px;*/
												}
												.PriorityID-2 {
													background-color: #EEEEEE;
													font-family: Geneva,Helvetica,Arial,sans-serif;
													font-size: 11px;
													width: 18px;
													height: 18px;
												   /* padding-bottom: 17px;*/
												}
												.PriorityID-3 {
													background-color: #FFFF49;
													font-family: Geneva,Helvetica,Arial,sans-serif;
													font-size: 11px;
													width: 18px;
													height: 18px;
												   /* padding-bottom: 17px;*/
												}
												.PriorityID-4 {
													background-color: #FF9900;
													font-family: Geneva,Helvetica,Arial,sans-serif;
													font-size: 11px;
													width: 18px;
													height: 18px;
												   /* padding-bottom: 17px;*/

												}
												.PriorityID-5 {
													background-color: #FF8888;
													font-family: Geneva,Helvetica,Arial,sans-serif;
													font-size: 11px;
													width: 18px;
													height: 18px;
												   /* padding-bottom: 17px;*/
												}												
												</style>
												</head>'
												.'<body style="background-collor:#FFFFFF;  background: url(/styles/maxlan/bg.jpg) ;">'
												.$hallo
												.'<br>'
												.out_table($sql,$DARF)
												.'<br>'
												.'Hier gehts zu den Aufgaben:'
												.'<br>'
												.'<a href="http://'.$_SERVER["SERVER_NAME"].'/admin/projekt/todo">http://'.$_SERVER["SERVER_NAME"].'/admin/projekt/todo</a>'
												.'</body>'
												.'</html>'
																								)
												);
												
				$betreff 		= ucfirst($global['sitename'])." Erinnerung an Aufgabe/n";
				$absender 		= $global['email'];
				
				$header  	 = "MIME-Version: 1.0\r\n";
				$header 	.= "Content-type: text/html; charset=iso-8859-15\r\n";
				$header 	.= "Content-Transfer-Encoding: quoted-printable\r\n";
				$header 	.= "From: $absender\r\n";
				$header 	.= "Reply-To: $absender\r\n";
				$header 	.= "X-Mailer: PHP ". phpversion();

			##################################################################################################################################
			if($out['gruppe'] > 0){
				if(mysql_num_rows($sql_team) > 0)
				{
					//$out_mail_id = mysql_fetch_array(mysql_query("SELECT * FROM project_todo WHERE id ='".$out['id']."'"));	
					$orga_id =	mysql_query("SELECT * FROM project_todo_g2u WHERE group_id ='".$out['gruppe']."'");
			
						while($out_orga_id = mysql_fetch_array($orga_id))
						{
							$out_mail_grp = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE id ='".$out_orga_id['user_id']."'"));
						
							$empfaenger		= $out_mail_grp['email'];

								######################################################################################################
									mail($empfaenger, $betreff, $email_text, $header);
								######################################################################################################
						}
				}
			}
			if($out['bearbeiter'] > 0 && $out['gruppe'] == 0){
				if(mysql_num_rows($sql_user) > 0)
				{
					
					$empfaenger		= $user['email'];

					######################################################################################################
						//mail($empfaenger, $betreff, $email_text, $header);
					######################################################################################################
				}
			}
			//$meldung .= $email_text;
			//$meldung .= "Mail Gesendet";
			return $meldung;
			
}

########## / Mal als Tabelle
?>