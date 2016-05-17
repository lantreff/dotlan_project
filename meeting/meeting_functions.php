<?php
$MODUL_NAME = "meeting";
include_once("../../../global.php");
// sql abfragen
$sql_event_ids = mysql_query("SELECT * FROM events ORDER BY begin DESC");

///////////////

function meeting_list($DARF,$event_id){
global $DB;

$query = mysql_query("SELECT * FROM project_meeting_liste WHERE event_id = '".$event_id."' ORDER BY datum DESC;");
$output .=  '<tr>
				<td class="msghead" nowrap="nowrap">
					<b>Gewesen</b>
				</td>
				<td width="100" class="msghead">
					<b>Titel&nbsp;</b>
				</td>
				<td class="msghead">
					<b>Datum / Uhrzeit&nbsp;</b>
				</td>
				<td width="100" class="msghead">
					<b>Location&nbsp;</b>
				</td>
				<td width="100" class="msghead" nowrap="nowrap">
					<b>Adresse&nbsp;</b>
				</td>
				<td class="msghead" nowrap="nowrap">
					<b>Anwesenheitsliste&nbsp;</b>
				</td>
				<td class="msghead" nowrap="nowrap">
					<b>Geplant&nbsp;</b>
				</td>
				<td class="msghead" nowrap="nowrap">
					<b>Protokoll&nbsp;</b>
				</td>
				<td class="msghead" nowrap="nowrap">
					<b>Kalender&nbsp;</b>
				</td>';
if($DARF['edit'] || $DARF['del'] )
  $output .=  '<td class="msghead" nowrap="nowrap">
					<b>Action&nbsp;</b>
				</td>';
$output .=  '	<td></td>
			</tr>';			
if(mysql_num_rows($query) != 0)
{
while ($row = mysql_fetch_array($query)){
	
$output .=  '<tr class="msgrow'.(($i%2)?1:2).'" >
				<td  style="text-align:center;">';
				  if($row["gewesen"] == 1){
					if((!$DARF['edit'] || !$DARF['del'])) $output .=  'Ja';
					else $output .=  '<a href="index.php?action=gewesen&id='.$row["ID"].'&gewesen=0&event='.$event_id.'">Ja</a>';
				  }else{
					if((!$DARF['edit'] || !$DARF['del'])) $output .=  'Nein';
					else $output .=  '<a href="index.php?action=gewesen&id='.$row["ID"].'&gewesen=1&event='.$event_id.'">Nein</a>';
				  }
  $date_ger = date("d.m.Y H:i:s",strtotime($row["datum"]));
  $date = explode(" ", $date_ger);
$output .=  '	</td>
				<td nowrap="nowrap">
					'.$row["titel"].'
				</td>
				<td nowrap="nowrap">
					'.$date[0].'<br>'.$date[1].'
				</td>
				<td  nowrap="nowrap">
					'.$row["location"].'
				</td>
				<td nowrap="nowrap">
					<a target="new" href="https://www.google.de/maps/place/'.nl2br($row["adresse"]).'">
						'.nl2br($row["adresse"]).'
					</a>
				</td>
				<td nowrap="nowrap" align="center">
					';
					
$output .=  '			<table width="100%">
							<tbody>
								<tr style="text-align:center;">';
								$sql_0 =  mysql_query("SELECT COUNT(user_id) AS anz FROM project_meeting_anwesenheit WHERE meeting_id = '".$row["ID"]."' AND wahrscheinlichkeit = 0 ");
								if(mysql_num_rows($sql_0) > 0)
								{
								$count_0 	= mysql_fetch_array($sql_0);
$output .=  '						<td title="Anzahl der Personen die nicht da sind --> 0%" style="background-color:RED;">
										'.$count_0['anz'].'
									</td>';
								}
								$sql_0_99 =  mysql_query("SELECT COUNT(user_id) AS anz FROM project_meeting_anwesenheit WHERE meeting_id = '".$row["ID"]."' AND wahrscheinlichkeit BETWEEN 1 AND 99 ");
								if(mysql_num_rows($sql_0_99) > 0)
								{
								$count_0_99 	= mysql_fetch_array($sql_0_99);
$output .=  '						<td title="Anzahl der Personen die 1% - 99% da sind" style="background-color:ORANGE;">
										'.$count_0_99['anz'].'
									</td>';
								}
								
								$sql_100 	=  mysql_query("SELECT COUNT(user_id) AS anz FROM project_meeting_anwesenheit WHERE meeting_id = '".$row["ID"]."' AND wahrscheinlichkeit = 100 ");	
								if(mysql_num_rows($sql_100) > 0)
								{
								$count_100 	= mysql_fetch_array($sql_100);
$output .=  '						<td title="Anzahl der Personen die zu 100% da sind" style="background-color:GREEN;">
										'.$count_100['anz'].'
									</td>';
								}
									
$output .=  '						<td width="53">
										<a href="meetings_anwesenheitsliste.php?id='.$row["ID"].'">
											< klick >
										</a>
									</td>
									
								</tr>
							</tbody>
						</table>
									';
$output .=  '
					</a>
				</td>
				<td  nowrap="nowrap" style="text-align:center;">
					<a href="meetings_texte.php?typ=1&id='.$row["ID"].'">
						< klick >
					</a>
				</td>
				<td  nowrap="nowrap" style="text-align:center;">
					<a href="meetings_texte.php?typ=2&id='.$row["ID"].'">
						< klick >
					</a>
				</td>';
 $output .=  '	<td align="center">
					'.get_cal_links("meeting",$row["ID"]).'
				</td>';
 $output .=  '	<td  align="center" nowrap="nowrap">';
					if($DARF['edit']) $output .=  '<a href="index.php?hide=1&action=change&id='.$row['ID'].'&event='.$event_id.'"><img src="../images/16/lists.png" title="Meeting --> ['.$row["location"].'] bearbeiten!" ></a> ';
					if($DARF['del'])  $output .=  '<a href="index.php?action=delete&id='.$row['ID'].'&moep='.$row['datum'].'&event='.$event_id.'"><img src="../images/16/editdelete.png" title="'.$row["location"].' l&ouml;schen"></a>';
$output .=  '	</td>
			</tr>';
$i++;
}
}
return $output;
}

function meeting_input($add,$edit,$titel,$datum,$meeting_datum,$location,$adresse,$geplant){
global $global;

		if ($add || $edit)
		{

			$output .= 	'
			<form onsubmit="return mail_senden();" id="meeting_input" name="meeting_input" action="index.php" method="POST">
			<table class="msg">
			  <tr>
				<td colspan="2" class="msghead" nowrap="nowrap"><b>Meeting
								';
			if($_GET["action"] !== "change"){
				$output .= 'anlegen';
			}
			else{
				$output .= 'editieren';				
			}
$output .= 	'
				:</b></td>
			  </tr>
			   <tr class="msgrow1">
				<td class="anmeldung_typ" nowrap="nowrap"><b>Titel</b>&nbsp;</td>
				<td class="anmeldung_typ" nowrap="nowrap"><input class="editbox" type="text" name="titel" size="20" value="'.$titel.'"></td>
			  </tr>
			  <tr>
				<td class="anmeldung_typ" nowrap="nowrap"><b>Datum / Uhrzeit</b>&nbsp;</td>';
if($_GET["action"] != "change"){
$output .= ' <td class="anmeldung_typ" nowrap="nowrap"><input class="editbox" type="text" name="datum" size="20" value="'.$datum.'"><br><font style="font-size:8px;">(YYYY-MM-DD HH:MM:SS)</font></td>';
}
else{
$output .= ' <td class="anmeldung_typ" nowrap="nowrap"><input class="editbox" type="text" name="datum" size="20" value="'.$meeting_datum.'"><br><font style="font-size:8px;">(YYYY-MM-DD HH:MM:SS)</font></td>';
}
$output .= '
			  </tr>
			  <tr class="msgrow1">
				<td class="anmeldung_typ" nowrap="nowrap"><b>Location</b>&nbsp;</td>
				<td class="anmeldung_typ" nowrap="nowrap"><input class="editbox" type="text" name="location" size="20" value="'.$location.'"></td>
			  </tr>
			  <tr class="msgrow2">
				<td class="anmeldung_typ" nowrap="nowrap"><b>Adresse</b>&nbsp;</td>
				<td class="anmeldung_typ" nowrap="nowrap"><textarea class="editbox" wrap="hard" name="adresse" rows="2" cols="17">'.$adresse.'</textarea></td>
			  </tr>
			  <tr class="msgrow1"valign="top" >
				<td class="anmeldung_typ" nowrap="nowrap"><b>Geplant*</b>&nbsp;</td>
				<td class="anmeldung_typ" nowrap="nowrap"><textarea class="editbox"  wrap="hard" name="geplant" rows="10" cols="50">'.$geplant.'</textarea></td>
			  </tr>
			   <tr class="msgrow2">
				<td class="anmeldung_typ" nowrap="nowrap"><b>E-Mail senden?</b>&nbsp;</td>
				<td class="anmeldung_typ" nowrap="nowrap">
					<input type="checkbox" name="mail">
					 an:
						<select name="user_groups" onChange="document.meeting_input.mail.checked = true;">
							<option value="">Gruppe w&auml;hlen</option>
							<option value="all_orgas">alle Orgas</option>
					';
					$user_groups =  list_user_groups_dotlan();
					while($out = mysql_fetch_array($user_groups))
					{
$output .= '				<option value="'.$out['id'].'">'.$out['name'].'</option>';
					}

$output .= '			</select>				
				</td>
			  </tr>
			   <tr class="msgrow1" valign="top">
				<td class="anmeldung_typ" nowrap="nowrap"><b>Betreff* <br> <br> E-Mail Text*</b><br>(freier Text der oberhalb <br> der Mail eingetragen wird)&nbsp;</td>
				<td class="anmeldung_typ" nowrap="nowrap">
					<input class="editbox" type="text" name="betreff" size="30" value="'.ucfirst($global['sitename'])." neues Meeting ".$titel.'">
					<br>
					<textarea class="editbox"  wrap="hard" name="email_text" rows="10" cols="50">Hallo zusammen,</textarea>
					</td>
			  </tr>
			  <tr>
				<td colspan="2">
					<b>
						* Wird in die E-Mail eingetragen!
					</b>
				</td>
			  </tr>
			  <tr class="msgrow2">
			   <input type="hidden" name="id" value="'.$_GET["id"].'">
				<td colspan="2" class="anmeldung_typ" nowrap="nowrap" style="text-align:center;">
					<input class="okbuttons" type="submit" name="submit" value=';
					if(!$_POST["submit"]) { $output .= ' Anlegen'; }
					else{ $output .= $_POST["submit"];}
$output .= '	></td>
			  </tr>
			</table>
			</form>
			';

		}
		$output .= 	' </td>';

return $output;
}

function meeting_insert($post,$event_id){

	mysql_query("INSERT INTO project_meeting_liste SET event_id = '".$event_id."', titel = '".$post["titel"]."', datum = '".$post["datum"]."', location = '".$post["location"]."', adresse = '".$post["adresse"]."', geplant = '".$post["geplant"]."'") or die(mysql_error());
	if($post['mail']) email($post);
}

function meeting_del($id){
  mysql_query("DELETE FROM project_meeting_liste WHERE ID = $id ") or die(mysql_error());
  mysql_query("DELETE FROM project_meeting_anwesenheit WHERE meeting_id = $id") or die(mysql_error());
}

function meeting_update($post,$id){

	mysql_query("UPDATE project_meeting_liste SET titel = '".$post["titel"]."', datum = '".$post["datum"]."', location = '".$post["location"]."', adresse = '".$post["adresse"]."', geplant = '".$post["geplant"]."' WHERE ID = ".$id.";");
	if($post['mail']) email($post);
}

function meeting_chg_gewesen($id,$gewesen){
  mysql_query("UPDATE project_meeting_liste SET gewesen = $gewesen WHERE ID = $id;");
}


function meeting_showtext($id,$typ,$edit){
  if($typ == 1){
    $query = mysql_query("SELECT geplant,datum FROM project_meeting_liste WHERE ID = '".$id."' LIMIT 1;");
    $output .=  '<tr>
					<td class="msghead" nowrap="nowrap">
						<b>Geplant am '.mysql_result($query,0,"datum").$DARF['edit'].'</b>
					</td>
				</tr>
				<tr>
					<td width="900" class="msgrow1" nowrap="nowrap">
						'.nl2br(mysql_result($query,0,"geplant")).'
					</td>
				</tr>';
  }elseif($typ == 2){
    $query = mysql_query("SELECT protokoll,datum FROM project_meeting_liste WHERE ID = $id LIMIT 1;");
	$query2 = mysql_query("SELECT text,date FROM project_notizen WHERE id = '".mysql_result($query,0,"protokoll")."' LIMIT 1;");
   if(mysql_num_rows($query2)!=0) $output .=  '<tr><td class="msghead" nowrap="nowrap"><b>Protokoll</b></td></tr><tr><td class="msgrow1" nowrap="nowrap">'. nl2br(mysql_result($query2,0,"text")).'</td></tr>';
   else $output .= 'Kein Protokoll gefunden';
   if($edit1)
   {
		$output .= ', bitte Protokoll über "bearbeiten" auswählen!';
	}
  }
  if ($edit){
    $output .=  '<tr><td width="900" nowrap="nowrap" style="text-align:center;"><b><a href="meetings_texte.php?action=change&typ='.$_GET["typ"].'&id='.$_GET["id"].'">bearbeiten</a></b></td></tr>';
  }
  return $output;
}
function meeting_addprotokoll($kategorie,$bezeichnung,$text,$DARF,$event_id,$meeting_id,$date){
global $PAGE;	
	if($DARF['add'])
	{
		$insert= mysql_query("INSERT INTO `project_notizen` (id, event_id, bezeichnung, text, kategorie, date, last_work) VALUES (NULL, '".$event_id."', '".$bezeichnung."', '".nl2br($text)."', '".$kategorie."', '".$date."', '".$date."');");
		$query = mysql_query("SELECT id FROM project_notizen WHERE bezeichnung = '".$bezeichnung."' AND kategorie = '".$kategorie."' ");
		
		mysql_query("UPDATE project_meeting_liste SET protokoll = '".mysql_result($query,0,"id")."' WHERE ID = $meeting_id;");
		$PAGE->redirect("{BASEDIR}admin/projekt/notiz/?hide=1&action=edit&id=".mysql_result($query,0,"id")."",$PAGE->sitetitle,"Das Protokoll ".$bezeichnung." wurde gespeichert");	
	}else
	{
		$output .= 'keine Rechte!';
	}
}

function meeting_showchangetext($id,$typ,$edit,$event_id){
  if($typ == 1){
    $query = mysql_query("SELECT geplant,datum FROM project_meeting_liste WHERE ID = $id LIMIT 1;");
    $output .=  '<form action="meetings_texte.php?typ='.$typ.'&id='.$id.'" method="POST">';
    $output .=  '<tr><td class="msghead" nowrap="nowrap"><b>Geplant am '.mysql_result($query,0,"datum").'</b></td></tr><tr><td  nowrap="nowrap"><textarea class="editbox" name="geplant" rows="10" cols="50">'.mysql_result($query,0,"geplant").'</textarea></td></tr>';
  }elseif($typ == 2){
    $query = mysql_query("SELECT * FROM project_meeting_liste WHERE ID = $id LIMIT 1;");
	$query2 = mysql_query("SELECT
							n.bezeichnung as note_bezeichnung,
							n.date as note_date,
							n.id as note_id,
							n.text as note_text

							FROM project_notizen AS n  WHERE ( n.kategorie = 'Protokoll' AND n.event_id = '".$event_id."'   )
							 ");

    $output .=  '<div title="Es wird eine neue Notiz mit Inhalt des Mettings erstellt und danach ge&ouml;ffnet!"><a href="?action=add&kategorie=Protokoll&bezeichnung='.mysql_result($query,0,"titel").'&geplant='.mysql_result($query,0,"geplant").'&id='.$id.'"><input type="button" value="Protokoll f&uuml;r dieses Meeting anlegen"></a></div>
				<form action="meetings_texte.php?typ='.$typ.'&id='.$id.'" method="POST">';
    $output .=  '<tr>
					<td class="msghead" nowrap="nowrap">
						<b>Protokoll</b>
					</td>
				</tr>
				';
	$output .= '
  <tr>
  <td>
<table width="100%" class="msg">
';
	$id_protokoll = mysql_result($query,0,"protokoll");
	if(mysql_num_rows($query2) != 0 && mysql_num_rows($query) != 0)
	{
		   while($row = mysql_fetch_array($query2)){
				 $sql_metting_list  = mysql_query("SELECT * FROM project_meeting_liste WHERE protokoll  = '".$row["note_id"]."' ");

				 if(mysql_num_rows($sql_metting_list) == 0)
				 {
					$output .=  '<tr title="'.strip_tags($row["note_text"]).'" class="msgrow'.(($i%2)?1:2).'" >
									<td  nowrap="nowrap"><input type="radio" name="protokoll" value="'.$row["note_id"].'" '.(($row["note_id"] == $id_protokoll) ? "checked='checked'" : " " ).'	</td>
									<td  nowrap="nowrap">'.$row["note_bezeichnung"].'</td>
									<td  nowrap="nowrap">'.$row["note_date"].'</td>
									<td  nowrap="nowrap"><a href="../notiz/?hide=1&action=show&id='.$row["note_id"].'&event=2"> öffnen </a></td>
									</tr>
								';
				}


			$i++;

		  }
	}
	else
	{
		$output .= 'Kein Protokoll in den Notizen hinterlegt <a href="../notiz/?hide=1&action=add&kategorie=Protokoll"> "hier klicken um Notiz hinzuzufügen."</a> <br> Bitte beachte das als Kategorie "Protokoll" eingetragen wird!"';
	}
$output .= '
</td>
</tr>
</table>
';



	}

	if ($edit == 1){
			$output .=  '<tr><td  nowrap="nowrap" style="text-align:center;"><input class="okbuttons" name="submit" type="submit" value="Anpassen"></td></form></tr>';
		}

  return $output;
}

function meeting_updatetext($id,$typ,$post){
  if($typ == 1) mysql_query("UPDATE project_meeting_liste SET geplant = '".$post["geplant"]."' WHERE ID = $id;");
  if($typ == 2)	mysql_query("UPDATE project_meeting_liste SET protokoll = '".$post["protokoll"]."' WHERE ID = $id;");
}

// Meeting - Anwesenheit
function anw_liste($id,$gewesen,$admin){
	$meeting = mysql_fetch_array(mysql_query("SELECT * FROM project_meeting_liste WHERE ID = $id"));
  $output .=  '<br>
				<tr>
					<td colspan="6" align="center">
						<b>
							<h3>
								'.$meeting['titel'].'
							</h3>
						</b>
					</td>
				</tr>
				<tr>
					<td></td>
					<td class="msghead" nowrap="nowrap">
						<b>Nick</b>
					</td>
					<td class="msghead" nowrap="nowrap">
						<b>Vorname</b>
					</td>
					<td class="msghead" nowrap="nowrap">
						<b>Nachname</b>
					</td>
					<td class="msghead" nowrap="nowrap">
						<b>Wahrscheinlichkeit</b>
					</td>';
  if($gewesen == 1){
    $output .=  '<td class="msghead" nowrap="nowrap">
					<b>war anwesend</b>
				</td>';
if($admin) $output .=  '<td class="msghead" nowrap="nowrap">
					<b>Action</b>
				</td>';
  }
  $output .=  "</tr>";
  $query = mysql_query("SELECT * FROM project_meeting_anwesenheit WHERE meeting_id = $id ORDER BY wahrscheinlichkeit DESC;");
  while($row = mysql_fetch_array($query)){
    $query2 = mysql_query("SELECT * FROM user WHERE id = ".$row["user_id"]." LIMIT 1;");
	$style ='';
		if($row["wahrscheinlichkeit"] == 100) $style =' background-color:GREEN;"';
		if($row["wahrscheinlichkeit"] <  100) $style =' background-color:orange;"';
		if($row["wahrscheinlichkeit"] == 0  ) $style =' background-color:RED;"';

    $output .=  '<tr class="msgrow'.(($i%2)?1:2).'" >
					<td>
						'.show_avatar(mysql_result($query2,0,"id"),"20").'
					</td>
					<td  nowrap="nowrap">
						'.mysql_result($query2,0,"nick").'
					</td>
					<td  nowrap="nowrap">
						'.mysql_result($query2,0,"vorname").'
					</td>
					<td  nowrap="nowrap">
						'.mysql_result($query2,0,"nachname").'
					</td>';
					
					
	$output .=  '	<td  nowrap="nowrap" style="text-align:center; '.$style.'">';
	$output .=  		$row["wahrscheinlichkeit"].'%';
	$output .=  '	</td>';
	
    if($gewesen == 1){
      $output .=  '<td  nowrap="nowrap" style="text-align:center;">';
					  if($row["anwesend"] == 0) $output .=  'k/A';
					  elseif($row["anwesend"] == 1) $output .=  'ja';
					  elseif($row["anwesend"] == 2) $output .=  'nein';
      $output .=  '</td>';

		 if($admin){
			$output .=  '<td nowrap="nowrap">
							<a href="meetings_anwesenheitsliste.php?id='.$id.'&action=anw&user_id='.$row["user_id"].'">anwesend</a>
							|
							<a href="meetings_anwesenheitsliste.php?id='.$id.'&action=abw&user_id='.$row["user_id"].'">abwesend</a>
						</td>';
		}

    }
    $output .=  '</tr>';
	$i++;
  }
  return $output;
}

function anw_chg_wahr($wahr,$id,$user_id){
  mysql_query("UPDATE project_meeting_anwesenheit SET wahrscheinlichkeit = ".$wahr." WHERE meeting_id = $id AND user_id = '".$user_id."' ");
}

function anw_del($id,$user_id){
  mysql_query("UPDATE project_meeting_anwesenheit SET wahrscheinlichkeit = '0' WHERE meeting_id = $id AND user_id = $user_id ") or die(mysql_error());
}

function anw_add($wahr,$id,$user_id){
  mysql_query("INSERT INTO project_meeting_anwesenheit SET meeting_id = $id, user_id = $user_id, wahrscheinlichkeit = $wahr ") or die(mysql_error());
}

function anw_chg_anw($id,$user_id,$anw){
  mysql_query("UPDATE project_meeting_anwesenheit SET anwesend = $anw WHERE meeting_id = $id AND user_id = $user_id ;");
}


function get_google_cal_link($typ,$id){
global $global;

  if($typ == "projekt"){
    $query = mysql_query("SELECT ad_level, name, location, DATE_FORMAT(von - INTERVAL 2 HOUR,'%Y%m%dT%H%i%sZ') AS von, DATE_FORMAT(bis - INTERVAL 1 HOUR,'%Y%m%dT%H%i%sZ') AS bis FROM projekte WHERE ID = '".$id."' LIMIT 1");
    if($_SESSION['ad_level'] < mysql_result($query,0,"ad_level")) die("Nicht ausrechend Bereichtigung");
    $name = mysql_result($query,0,"name");
    $von = mysql_result($query,0,"von");
    $bis = mysql_result($query,0,"bis");
    $wo = mysql_result($query,0,"location");
  }elseif($typ == "meeting"){
    $query = mysql_query("SELECT titel, adresse, geplant, DATE_FORMAT(datum  - INTERVAL 2 HOUR, '%Y%m%dT%H%i%sZ') AS datum, DATE_FORMAT(datum  - INTERVAL 1 HOUR,'%Y%m%dT%H%i%sZ') AS bis FROM project_meeting_liste WHERE ID = '".$id."' LIMIT 1");
    $name = ucfirst($global['sitename'])." - ".mysql_result($query,0,"titel");
    $von = mysql_result($query,0,"datum");
    $bis = mysql_result($query,0,"bis");
    $wo = str_replace(array("\n","\r",",")," ",umlaute_ersetzen(mysql_result($query,0,"adresse")));
	$was = umlaute_ersetzen(mysql_result($query,0,"geplant"));
  }else return false;

  return 'https://www.google.com/calendar/render?action=TEMPLATE&text='.$name.'&dates='.$von.'/'.$bis.'&sprop=website:'.$_SERVER["SERVER_NAME"].'&trp=true&location='.$wo.'&details='.$was;
}

function get_cal_links($typ,$id){
  if(empty($typ) || empty($id) || !is_numeric($id)) return false;
  return "<a href='ical.php?typ=".$typ."&id=".$id."'><img border=0 src='../images/16/ical.png' height='15' title='iCal herunterladen'></a> <a href='".get_google_cal_link($typ,$id)."' target='_blank'><img border=0 src='../images/16/google_calendar.png' height='15' title='Zu google-Kalender hinzuf&uuml;gen'></a>";
}

function show_avatar($userid,$height){
  $img = "http://".$_SERVER["SERVER_NAME"]."/images/avatar/tn_".$userid.".jpg";

if(@fopen($img, "r")){

  $output .= "<a class='info'><img src='$img' height='$height'><span><img src='$img'></span></a>";
  return $output;
}
else{
  return false;
}


}

function email($post)
{
				global $global,$CURRENT_USER;	
				$betreff 		 = ucfirst($global['sitename'])." Neues Meeting ".$post['titel']." am: ".time2german($post['datum']);
				//$absender 	 = $global['email'];
				$absender 		 = "info@maxlan.de";
				$email_text		 = utf8_decode(utf8_encode( nl2br($post['email_text'])));
				$email_text		.= utf8_decode(utf8_encode(
															"<br>"
															."<br>"
															."Folgendes ist am: ".time2german($post['datum'])." geplant:"
															."<br>"
															."<br>"
															. nl2br($post['geplant'])
															."<br>"
															."<br>"
															."Gruß "
															.$CURRENT_USER->vorname
															."<br>"
															."<br>"
															."Hier gelangst du zur Meeting:"
															."<br>"
															." <a href='http://".$_SERVER["SERVER_NAME"]."/admin/projekt/meeting'>http://".$_SERVER["SERVER_NAME"]."/admin/projekt/meeting</a>
																")
															);
				
				$header  	 = "MIME-Version: 1.0\r\n";
				$header 	.= "Content-type: text/html; charset=iso-8859-1\r\n";
				$header 	.= "Content-Transfer-Encoding: quoted-printable\r\n";
				$header 	.= "From: $absender\r\n";
				$header 	.= "Reply-To: $absender\r\n";
				$header 	.= "X-Mailer: PHP/".phpversion();
				
	if($post['user_groups'] == "all_orgas")	{ $orga_id =	mysql_query("SELECT * FROM user_orga WHERE display_team = '1' ");}
	//if($post['user_groups'] == "all_orgas")	{ $orga_id =	mysql_query("SELECT * FROM user_orga WHERE id = '64' ");}
	else{ 	$orga_id =	mysql_query("SELECT * FROM user_g2u WHERE group_id ='".$post['user_groups']."'");}
			
						while($out_orga_id = mysql_fetch_array($orga_id))
						{
							$out_mail_grp = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE id ='".$out_orga_id['user_id']."'"));
						
							$empfaenger		= $out_mail_grp['email'];
							//$empfaenger	= "3gg3.ce@gmail.com";

								######################################################################################################
									mail($empfaenger, $betreff, $email_text, $header);
								######################################################################################################
						}

}

?>
