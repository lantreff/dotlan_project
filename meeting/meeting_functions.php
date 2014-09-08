<?php
$MODUL_NAME = "meeting";
include_once("../../../global.php");
// sql abfragen
$sql_event_ids = $DB->query("SELECT * FROM events ORDER BY begin DESC");

///////////////

function meeting_list($edit,$del,$event_id){
global $DB;

$query = $DB->query("SELECT * FROM project_meeting_liste WHERE event_id = '".$event_id."' ORDER BY datum DESC;");
$output .=  '<tr><td class="msghead" nowrap="nowrap"><b>Gewesen</b></td><td width="100" class="msghead"><b>Titel&nbsp;</b></td><td class="msghead"><b>Datum / Uhrzeit&nbsp;</b></td><td width="100" class="msghead"><b>Location&nbsp;</b></td><td width="100" class="msghead" nowrap="nowrap"><b>Adresse&nbsp;</b></td><td class="msghead" nowrap="nowrap"><b>Anwesenheitsliste&nbsp;</b></td><td class="msghead" nowrap="nowrap"><b>Geplant&nbsp;</b></td><td class="msghead" nowrap="nowrap"><b>Protokoll&nbsp;</b></td><td class="msghead" nowrap="nowrap"><b>Kalender&nbsp;</b></td>';
if($edit || $del)
  $output .=  '<td class="msghead" nowrap="nowrap"><b>Action&nbsp;</b></td>';
$output .=  '<td></td></tr>';
if(mysql_num_rows($query) != 0)
{
while ($row = mysql_fetch_array($query)){
$output .=  '<tr class="msgrow'.(($i%2)?1:2).'" ><td  style="text-align:center;">';
  if($row["gewesen"] == 1){
    if((!$edit || !$del)) $output .=  'Ja';
    else $output .=  '<a href="index.php?action=gewesen&id='.$row["ID"].'&gewesen=0&event='.$event_id.'">Ja</a>';
  }else{
    if((!$edit || !$del)) $output .=  'Nein';
    else $output .=  '<a href="index.php?action=gewesen&id='.$row["ID"].'&gewesen=1&event='.$event_id.'">Nein</a>';
  }
  $date_ger = date("d.m.Y H:i:s",strtotime($row["datum"]));
  $date = explode(" ", $date_ger);
$output .=  '</td><td nowrap="nowrap">'.$row["titel"].' </td><td nowrap="nowrap">'.$date[0].'<br>'.$date[1].' </td><td  nowrap="nowrap">'.$row["location"].'</td><td nowrap="nowrap"><a target="new" href="https://www.google.de/maps/place/'.$row["adresse"].'"> '.nl2br($row["adresse"]).'</a></td><td nowrap="nowrap" style="text-align:center;"><a href="meetings_anwesenheitsliste.php?id='.$row["ID"].'">< klick ></a></td><td  nowrap="nowrap" style="text-align:center;"><a href="meetings_texte.php?typ=1&id='.$row["ID"].'">< klick ></a></td><td  nowrap="nowrap" style="text-align:center;"><a href="meetings_texte.php?typ=2&id='.$row["ID"].'">< klick ></a></td>';
 $output .=  '</td><td align="center">'.get_cal_links("meeting",$row["ID"]).'</td>';
 $output .=  '<td  align="center" nowrap="nowrap">';
if($edit)
  $output .=  '<a href="index.php?hide=1&action=change&id='.$row['ID'].'&event='.$event_id.'"><img src="../images/16/lists.png" title="Meeting --> ['.$row["location"].'] bearbeiten!" ></a> ';
if($del)
  $output .=  ' <a href="index.php?action=delete&id='.$row['ID'].'&moep='.$row['datum'].'&event='.$event_id.'"><img src="../images/16/editdelete.png" title="'.$row["location"].' l&ouml;schen"></a>';
  

$output .=  '</tr>';
$i++;
}
}
return $output;
}

function meeting_input($add,$edit,$titel,$datum,$meeting_datum,$location,$adresse,$geplant){


		if ($add || $edit)
		{

			$output .= 	'
			<form action="index.php" method="POST">
			<table class="msg">
			  <tr>
				<td colspan="2" class="msghead" nowrap="nowrap"><b>Meeting anlegen:</b></td>
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
				<td class="anmeldung_typ" nowrap="nowrap"><textarea class="editbox" name="adresse" rows="2" cols="17">'.$adresse.'</textarea></td>
			  </tr>
			  <tr class="msgrow1">
				<td class="anmeldung_typ" nowrap="nowrap"><b>Geplant</b>&nbsp;</td>
				<td class="anmeldung_typ" nowrap="nowrap"><textarea class="editbox" name="geplant" rows="10" cols="50">'.$geplant.'</textarea></td>
			  </tr>
			  <tr class="msgrow2">
			   <input type="hidden" name="id" value="'.$_GET["id"].'">
				<td colspan="2" class="anmeldung_typ" nowrap="nowrap" style="text-align:center;"><input class="okbuttons" type="submit" name="submit" value=';if(!$_POST["submit"]) $output .=  "Anlegen"; else $output .=  $_POST["submit"];'></td>
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
}

function meeting_del($id){
  mysql_query("DELETE FROM project_meeting_liste WHERE ID = $id ") or die(mysql_error());
  mysql_query("DELETE FROM project_meeting_anwesenheit WHERE meeting_id = $id") or die(mysql_error());
}

function meeting_update($post,$id){
  mysql_query("UPDATE project_meeting_liste SET titel = '".$post["titel"]."', datum = '".$post["datum"]."', location = '".$post["location"]."', adresse = '".$post["adresse"]."', geplant = '".$post["geplant"]."' WHERE ID = ".$id.";");
}

function meeting_chg_gewesen($id,$gewesen){
  mysql_query("UPDATE project_meeting_liste SET gewesen = $gewesen WHERE ID = $id;");
}


function meeting_showtext($id,$typ,$edit){
  if($typ == 1){
    $query = mysql_query("SELECT geplant,datum FROM project_meeting_liste WHERE ID = '".$id."' LIMIT 1;");
    $output .=  '<tr><td class="msghead" nowrap="nowrap"><b>Geplant am '.mysql_result($query,0,"datum").$DARF['edit'].'</b></td></tr><tr><td width="900" class="msgrow1" nowrap="nowrap">'.nl2br(mysql_result($query,0,"geplant")).'</td></tr>';
  }elseif($typ == 2){
    $query = mysql_query("SELECT protokoll,datum FROM project_meeting_liste WHERE ID = $id LIMIT 1;");
	$query2 = mysql_query("SELECT text,date FROM project_notizen WHERE id = '".mysql_result($query,0,"protokoll")."' LIMIT 1;");
   if(mysql_num_rows($query2)!=0) $output .=  '<tr><td class="msghead" nowrap="nowrap"><b>Protokoll</b></td></tr><tr><td class="msgrow1" nowrap="nowrap">'. nl2br(mysql_result($query2,0,"text")).'</td></tr>';
   else $output .= 'Kein Protokoll gefunden';
   if($edit)
   {
		$output .= ', bitte Protokoll über "bearbeiten" auswählen!';
	}
  }
  if ($edit){
    $output .=  '<tr><td width="900" nowrap="nowrap" style="text-align:center;"><b><a href="meetings_texte.php?action=change&typ='.$_GET["typ"].'&id='.$_GET["id"].'">bearbeiten</a></b></td></tr>';
  }
  return $output;
}

function meeting_showchangetext($id,$typ,$edit,$event_id){
  if($typ == 1){
    $query = mysql_query("SELECT geplant,datum FROM project_meeting_liste WHERE ID = $id LIMIT 1;");
    $output .=  '<form action="meetings_texte.php?typ='.$typ.'&id='.$id.'" method="POST">';
    $output .=  '<tr><td class="msghead" nowrap="nowrap"><b>Geplant am '.mysql_result($query,0,"datum").'</b></td></tr><tr><td  nowrap="nowrap"><textarea class="editbox" name="geplant" rows="10" cols="50">'.mysql_result($query,0,"geplant").'</textarea></td></tr>';
  }elseif($typ == 2){
    $query = mysql_query("SELECT protokoll,datum FROM project_meeting_liste WHERE ID = $id LIMIT 1;");
	$query2 = mysql_query("SELECT 
							n.bezeichnung as note_bezeichnung,
							n.date as note_date,
							n.id as note_id,
							n.text as note_text
							
							FROM project_notizen AS n  WHERE ( n.kategorie = 'Protokoll' AND n.event_id = '".$event_id."'   )
							 ");
	
    $output .=  '<form action="meetings_texte.php?typ='.$typ.'&id='.$id.'" method="POST">';
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
  $output .=  '<tr><td></td><td class="msghead" nowrap="nowrap"><b>Nick</b></td><td class="msghead" nowrap="nowrap"><b>Vorname</b></td><td class="msghead" nowrap="nowrap"><b>Nachname</b></td><td class="msghead" nowrap="nowrap"><b>Wahrscheinlichkeit</b></td>';
  if($gewesen == 1){
    $output .=  '<td class="msghead" nowrap="nowrap"><b>war anwesend</b></td>';
    if($admin) $output .=  '<td class="msghead" nowrap="nowrap"><b>Action</b></td>';
  }
  $output .=  "</tr>";
  $query = mysql_query("SELECT * FROM project_meeting_anwesenheit WHERE meeting_id = $id ORDER BY wahrscheinlichkeit DESC;");
  while($row = mysql_fetch_array($query)){
    $query2 = mysql_query("SELECT * FROM user WHERE id = ".$row["user_id"]." LIMIT 1;");
    $output .=  '<tr class="msgrow'.(($i%2)?1:2).'" ><td>'.show_avatar(mysql_result($query2,0,"id"),"20").'</td><td  nowrap="nowrap">'.mysql_result($query2,0,"nick").'</td><td  nowrap="nowrap">'.mysql_result($query2,0,"vorname").'</td><td  nowrap="nowrap">'.mysql_result($query2,0,"nachname").'</td><td  nowrap="nowrap" style="text-align:center;">'.$row["wahrscheinlichkeit"].'%</td>';
    if($gewesen == 1){
      $output .=  '<td  nowrap="nowrap" style="text-align:center;">';
      if($row["anwesend"] == 0) $output .=  'k/A';
      elseif($row["anwesend"] == 1) $output .=  'ja';
      elseif($row["anwesend"] == 2) $output .=  'nein';
      $output .=  '</td>';

		 if($admin){
			$output .=  '<td nowrap="nowrap"><a href="meetings_anwesenheitsliste.php?id='.$id.'&action=anw&user_id='.$row["user_id"].'">anwesend</a>
			|
			<a href="meetings_anwesenheitsliste.php?id='.$id.'&action=abw&user_id='.$row["user_id"].'">abwesend</a></td>';
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
  mysql_query("DELETE FROM project_meeting_anwesenheit WHERE meeting_id = $id AND user_id = $user_id ") or die(mysql_error());
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
    $query = mysql_query("SELECT adresse, DATE_FORMAT(datum  - INTERVAL 2 HOUR, '%Y%m%dT%H%i%sZ') AS datum, DATE_FORMAT(datum  - INTERVAL 1 HOUR,'%Y%m%dT%H%i%sZ') AS bis FROM project_meeting_liste WHERE ID = '".$id."' LIMIT 1");
    $name = $global['sitename']." - Meeting";
    $von = mysql_result($query,0,"datum");
    $bis = mysql_result($query,0,"bis");
    $wo = str_replace(array("\n","\r",",")," ",umlaute_ersetzen(mysql_result($query,0,"adresse")));
  }else return false;

  return 'https://www.google.com/calendar/render?action=TEMPLATE&text='.$name.'&dates='.$von.'/'.$bis.'&sprop=website:'.$_SERVER["SERVER_NAME"].'&trp=true&location='.$wo;
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

?>