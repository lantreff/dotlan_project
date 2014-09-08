<?php

$MODUL_NAME = "Dienstplan";
$freeze_meldung = "<h3>Freeze *brrr* - &Auml;nderungen am Plan nicht mehr m&ouml;glich!!!</h3>";

// sql abfragen
$sql_event_ids = $DB->query("SELECT * FROM events ORDER BY begin DESC");
if(!$DARF['edit_freeze']) $freeze = mysql_result($DB->query("SELECT freeze FROM project_dienstplan WHERE event_id = '".$event_id."'"),0,"freeze");
else $freeze = 0;

// Timestamp in Datum umwandeln
function throwDatum($Timestamp) {
$Tag = strftime ("%d. ", $Timestamp);
$month = strftime ("%m", $Timestamp);
$Jahr = strftime (" %Y", $Timestamp);
$Stunden = strftime ("%H", $Timestamp);
$Minuten = strftime ("%M", $Timestamp);
$Monatsnamen = array("Januar","Februar","März","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember");
$month = $month - 1;
$Monat = $Monatsnamen[$month];
$Datum = $Tag.$Monat.$Jahr." | ".$Stunden.":".$Minuten;
return $Datum;
}

// Timestamp in Datum umwandeln
function throwDatumEng($Timestamp) {
$Tag = strftime ("%d", $Timestamp);
$month = strftime ("%m", $Timestamp);
$Jahr = strftime ("%Y", $Timestamp);
$Stunden = strftime ("%H", $Timestamp);
$Minuten = strftime ("%M", $Timestamp);
$Datum = $Jahr.'-'.$month.'-'.$Tag.' '.$Stunden.':'.$Minuten.':00';
return $Datum;
}

// Benutzer
function listuser(){
$query = $DB->query("SELECT * FROM user ORDER BY ad_level;");
$output .="<tr><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Nick</b>&nbsp;</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Vorname</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Nachname&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Last Login IP&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Last Login&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Admin Level&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Action&nbsp;</b></td></tr>";
while ($user = $DB->fetch_array($query)){
  $level_array = array("nix","User","Trial","Orga","Management","Admin");
    $time = throwDatum($user['login_time']);
$output .="<tr><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['nick']."</td><td class=\"anmeldung_typ\">".$user['vorname']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['nachname']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">&nbsp;".$user['login_ip']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$time."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$level_array[$user['ad_level']]."</td><td class=\"anmeldung_data\" nowrap=\"nowrap\"><a href=\"admin.php?action=change&user=".$user['id']."\">editieren</a>|<a href=\"admin.php?action=delete&user=".$user['id']."\">löschen</a></td></tr>";
}
}

function insertuser($nick, $vorname, $nachname, $passwort, $ad_level){
  $passwort = md5($passwort);
  $DB->query("INSERT INTO user SET nick = \"$nick\", vorname = \"$vorname\", nachname = \"$nachname\", passwort = \"$passwort\", ad_level = \"$ad_level\"") or die($DB->error());
}

function updateuser($User_id, $nick, $vorname, $nachname, $passwort, $ad_level){
  if ($passwort == ""){
    $DB->query("UPDATE user SET nick = \"$nick\", vorname = \"$vorname\", nachname = \"$nachname\", ad_level = \"$ad_level\" WHERE id = \"$User_id\"") or die($DB->error());
  } else {
    $passwort = md5($passwort);
    $DB->query("UPDATE user SET nick = \"$nick\", vorname = \"$vorname\", nachname = \"$nachname\", passwort = \"$passwort\", ad_level = \"$ad_level\" WHERE id = \"$User_id\"") or die($DB->error());
  }
}

function deleteuser($User_id){
  $DB->query("DELETE FROM user WHERE id = \"$User_id\"") or die($DB->error());
}

function throwadlevel($User_id){
  $query = $DB->query("SELECT * FROM user WHERE id = \"$User_id\"");

   if ($User_id == ""){
   $output .="<OPTION selected = \"selected\" VALUE=\"NULL\">&nbsp;</OPTION>";
   } else {
  $output .="<OPTION VALUE=\"\">&nbsp;</OPTION>"; }

  while ($user = $DB->fetch_array($query)){
    $level = $user['ad_level'];
    echo $level;
    }

  $level_array = array("User","Trial","Orga","Management","Admin");

  for ($n = 0; $n < 5; ++$n){
    $pruef = $n+1;
    if ($pruef == $level){
      $output .="<OPTION selected = \"selected\" VALUE=\"".$pruef."\">".$level_array[$n]."</OPTION>";
  } else {
  $output .="<OPTION VALUE=\"".$pruef."\">".$level_array[$n]."</OPTION>";
  }
  }
}

// Team
function listteam(){
$query = $DB->query("SELECT * FROM user ORDER BY nachname;");
$output .="<tr>
  <td></td>
  <td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Nick</b>&nbsp;</td>
  <td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Vorname</b></td>
  <td class=\"anmeldung_typ\"><b>Nachname&nbsp;</b></td>
  <td class=\"anmeldung_typ\"><b>Straße&nbsp;</b></td>
  <td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Ort&nbsp;</b></td>
  <td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Mail&nbsp;</b></td>
  <td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Handy&nbsp;</b></td>
  <td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Tel&nbsp;</b></td>
  <td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>ICQ&nbsp;</b></td>
  <td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Skype&nbsp;</b></td>
  <td></td>
</tr>";
while ($user = $DB->fetch_array($query)){
    $time = throwDatum($user['login_time']);
$output .="<tr>
  <td>"; show_avatar($user["id"],"20"); $output .="</td>
  <td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['nick']."&nbsp;</td>
  <td class=\"anmeldung_typ\">".$user['vorname']."&nbsp;</td>
  <td class=\"anmeldung_typ\">".$user['nachname']."&nbsp;</td>
  <td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['street']."&nbsp;</td>
  <td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['ort']."&nbsp;</td>
  <td class=\"anmeldung_typ\" nowrap=\"nowrap\">";
if(!empty($user['mail'])) echo"<a href=\"mailto:".$user['mail']."\">email</a>";
$output .="&nbsp;</td>
  <td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['handy']."&nbsp;</td>
  <td class=\"anmeldung_typ\">".$user['tel']."&nbsp;</td>
  <td class=\"anmeldung_typ\" nowrap=\"nowrap\">";
if(!empty($user['icq'])) echo $user['icq'];
$output .="&nbsp;</td>
  <td class=\"anmeldung_typ\" nowrap=\"nowrap\">";
if(!empty($user['skype'])) echo $user['skype'];
$output .="&nbsp;</td>
  <td><a href='vcard.php?id=".$user["id"]."'><img border=0 src='template/images/vcard.png' height='15'></a></td>
</tr>";
}
}

function read_single_user($id){
  return $DB->fetch_array($DB->query("SELECT * FROM user WHERE `id` = $id LIMIT 1;"));
}

function read_team_with_mail(){
global $DB;
  return $DB->query("SELECT * FROM user WHERE `email` != '' ORDER BY nachname;");
}

// Mailliste
function maillist_list_lists(){
global $DB;
  return $DB->query("SELECT * FROM user ORDER BY vorname;");
}

function maillist_read_list($id){
global $DB;
  return $DB->query("SELECT * FROM mailmember WHERE `list_id` = $id ORDER BY kontakt_id;");
}

function maillist_read_mail($id,$table){
global $DB;
  $query = $DB->query("SELECT email, vorname, nachname FROM `$table` WHERE `id` = $id LIMIT 1;");
  return array(mysql_result($query,0,"email"),mysql_result($query,0,"nachname")." ".mysql_result($query,0,"vorname"));
}

function maillist_add($_POST,$fkt=0,$list_id=0){
  if($fkt != 1){
    $DB->query("INSERT INTO maillisten SET `name` = \"".$_POST["name"]."\";");
    $list_id = $DB->insert_id();
  }
  for($i=0;$i<count($_POST["user"]);$i++){
    $DB->query("INSERT INTO mailmember SET `list_id` = $list_id, `user_id` = ".$_POST["user"][$i].";");
  }
  for($i=0;$i<count($_POST["kontakte"]);$i++){
    $DB->query("INSERT INTO mailmember SET `list_id` = $list_id, `kontakt_id` = ".$_POST["kontakte"][$i].";");
  }
}

function maillist_del($id,$fkt=0){
  if($fkt != 1) $DB->query("DELETE FROM maillisten WHERE id = \"$id\"") or die($DB->error());
  $DB->query("DELETE FROM mailmember WHERE list_id = \"$id\"") or die($DB->error());
}

function maillist_change($post){
  $id = $post["id"];
  $name = $post["name"];
  $user = $post["user"];
  $kontakte = $post["kontakte"];
  $DB->query("UPDATE maillisten SET name = \"$name\" WHERE `id` = '$id' LIMIT 1;") or die($DB->error());
  maillist_del($id,1);
  maillist_add($post,1,$id);
}

function save_mail($betreff,$text){
  $DB->query("INSERT INTO mails_send SET event_id = '".$event_id."', time = '".time()."', betreff = '".$betreff."', text = '".$text."'");
  return $DB->insert_id();
}

function save_mail_to($mailid,$name,$mail,$code = ""){
  $DB->query("INSERT INTO mails_read SET mail_id = '".$mailid."', name = '".$name."', mail = '".$mail."', code = '".$code."'");
}

function mailforum($User_id, $event_id, $Thread_Titel, $Thread_Post,  $List_id, $code = false){
#	$List_id = 4;
	$from = "info@maxlan.de";
	$query = maillist_read_list($List_id);
	$Betreff = "[".$_SESSION["projekt_name"]."] Neuer Forenthread: ".$Thread_Titel;
	$Text = "Es wurde ein neuer Forenthread angelegt. Der Threadstarter möchte mit euch dringend das Thema diskutieren oder euch informieren!

Projekt: ".$_SESSION['projekt_name']."
User: ".$_SESSION["vorname"]." ".$_SESSION["nachname"]."
Thread: ".$Thread_Titel."
Inhalt: 

".$Thread_Post;

	$mailid = save_mail($Betreff,$Text);

	while($row = $DB->fetch_array($query)){
          $Text2 = $Text;
          $arr = maillist_read_mail($row["user_id"],"user");
          $to = $arr[0];
          if($code){
            $c = md5($to.time());
            $Text2 .= "\n\n<a href='https://projekt.lantreff.net/mail_read.php?code=".$c."'>Ich habe die Mail gelesen</a>";
            save_mail_to($mailid,"U: ".$arr[1],$to,$c);
          }
          $meldung .= "<br>Sende mail an $to ...  ";
          if(mail($to,$Betreff,nl2br($Text2),'From: '.$from."\r\nContent-type: text/html; charset=iso-8859-1\r\n")) $meldung .= "ok";
            else $meldung .= "fehler";
        }
echo $meldung;
}


// Kontakte
function listkontakte($ad_level2){
$query = $DB->query("SELECT * FROM kontakte ORDER BY nachname;");
$output .="<tr><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Vorname</b></td><td class=\"anmeldung_typ\"><b>Nachname&nbsp;</b></td><td class=\"anmeldung_typ\"><b>Straße&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Ort&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Mail&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Tel&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Info&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>URL&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Sponsor&nbsp;</b></td>";
if ($_SESSION['ad_level'] >= $ad_level2)
  $output .="<td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Action&nbsp;</b></td>";
$output .="</tr>";
while ($user = $DB->fetch_array($query)){
    $time = throwDatum($user['login_time']);
$output .="<tr><td class=\"anmeldung_typ\">".$user['vorname']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['nachname']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['street']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['ort']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['mail']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['tel']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['info']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">";
if(!empty($user['url'])) $output .="<a href='".$user['url']."' target='_blank'>klick</a>";
$output .="<tr><td class=\"anmeldung_typ\">".$user['vorname']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['nachname']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['street']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['ort']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['mail']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['tel']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['info']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">";
if(!empty($user['url'])) $output .="<a href='".$user['url']."' target='_blank'>klick</a>";
$output .="&nbsp;</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['sponsor']."</td>";
if ($_SESSION['ad_level'] >= $ad_level2)
  $output .="<td class=\"anmeldung_data\" nowrap=\"nowrap\"><a href=\"kontakte.php?action=change&user=".$user['id']."\">editieren</a>|<a href=\"kontakte.php?action=delete&user=".$user['id']."&moep=".$user['vorname']." ".$user["nachname"]."\">löschen</a></td>";
$output .="</tr>";
}
}

function kontakt_insert($vorname,$nachname,$street,$ort,$tel,$mail,$info,$sponsor,$url){
  $info = addslashes($info);
  $DB->query("INSERT INTO kontakte SET vorname = \"$vorname\", nachname = \"$nachname\", street = \"$street\", ort = \"$ort\", tel = \"$tel\", mail = \"$mail\", info = \"$info\", url = \"$url\", sponsor = \"$sponsor\"") or die($DB->error());
}

function kontakt_del($id){
  $DB->query("DELETE FROM kontakte WHERE id = \"$id\"") or die($DB->error());
  $DB->query("DELETE FROM sponsoren WHERE kontakt_id = \"$id\"") or die ($DB->error());
}

function kontakt_change($id,$vorname,$nachname,$street,$ort,$tel,$mail,$info,$sponsor,$url){
  $info = addslashes($info);
  $DB->query("UPDATE kontakte SET vorname = \"$vorname\", nachname = \"$nachname\", street = \"$street\", ort = \"$ort\", tel = \"$tel\", mail = \"$mail\", info = \"$info\", url = \"$url\", sponsor = \"$sponsor\" WHERE `id` = '$id' LIMIT 1;") or die($DB->error());
}

function read_kontakte_with_mail(){
global $DB;
  return $DB->query("SELECT * FROM user WHERE `email` != '';");
}

// Projekte
function projekte_list($admin = 0){
if($admin == 1) $query = $DB->query("SELECT * FROM projekte ORDER BY name");
  else $query = $DB->query("SELECT * FROM projekte WHERE ad_level <= ".str_replace(",",".",$_SESSION["ad_level"])." AND active = 1 ORDER BY name");
$output .="<tr><td class=\"anmeldung_typ\" nowrap=\"nowrap\">&nbsp;</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Name</b></td><td class=\"anmeldung_typ\"><b>Stat.&nbsp;</b></td><td class=\"anmeldung_typ\"><b>Von&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Bis&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>TN&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Location&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Typ&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Std.&nbsp;</b></td>";
if($admin == 1)
  $output .="<td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>AD&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Act&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Dotlan&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Action&nbsp;</b></td>";
$output .="<td class='anmeldung_typ'></td></tr>";
while ($user = $DB->fetch_array($query)){
$year = explode("-",$user["bis"]);
$year = $year[0];
if($year < date("Y")-2 && $admin != 1) $hidden = "style='display: none;'";
else $hidden = "";

$output .="<tr $hidden><td class=\"anmeldung_typ\" nowrap=\"nowrap\">";
if($user["id"] == $event_id) $output .="aktiv";
else $output .="<a href=\"index.php?event_id=".$user["id"]."\">auswählen</a>";

$new = 0;
$meldung = "";
$Dienstplan = "";
// offene Dienstplans
$query2 = $DB->query("SELECT id,datum,location FROM Dienstplan_liste WHERE event_id = '".$user["id"]."' AND gewesen = 0");
if($DB->num_rows($query2) > 0) $Dienstplan = " (Dienstplan!)";

// neue sachen
$query2 = $DB->query("SELECT id,datum,location FROM Dienstplan_liste WHERE datum > '".date("Y-m-d H:i:s",$_SESSION["last_login"])."' AND event_id = '".$user["id"]."' AND gewesen = 1 AND protokoll != ''");
if($DB->num_rows($query2) > 0) $new = 1;
$query2 = $DB->query("SELECT t.id, t.Titel, p.Userid, p.PostDatum, p.Threadid FROM forum_post AS p LEFT JOIN forum_thread AS t ON p.Threadid = t.id WHERE p.PostDatum > '".$_SESSION["last_login"]."' AND t.Projektid = '".$user["id"]."' AND p.versteckt = 0 AND t.versteckt = 0 AND p.Userid <> '".$user_id."'");
if($DB->num_rows($query2) > 0) $new =1;
$query2 = $DB->query("SELECT Titel, Userid, Groesse, URL, time FROM downloads WHERE time > '".$_SESSION["last_login"]."' AND Projektid = '".$user["id"]."' AND Userid <> '".$user_id."'");
if($DB->num_rows($query2) > 0) $new = 1;
if($new == 1) $meldung = " (Hier gibts was neues!!)";

$output .="</td><td class=\"anmeldung_typ\"";
if(!empty($meldung)) $output .=" style=\"background-color: #FF0000;\"";
if(empty($meldung) && !empty($Dienstplan)) $output .=" style=\"background-color: #0000FF;\"";
$output .=">".$user['name'].$meldung." ".$Dienstplan."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['status']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['von']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['bis']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['teilnehmer']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['location']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user['typ']."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\" style=\"text-align:center;\">";
    if($user["standart"] == 1) $output .="X"; else $output .="&nbsp;";
  $output .="</td>";
if($admin == 1){
  $dotlan = @mysql_result($DB->query("SELECT name FROM maxlan.events WHERE id = '".$user["dotlan_event_id"]."' LIMIT 1"),0,"name");
  $output .="<td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user["ad_level"]."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$user["active"]."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$dotlan."</td><td class=\"anmeldung_data\" nowrap=\"nowrap\"><a href=\"projekte.php?action=change&id=".$user['id']."\">editieren</a>|<a href=\"projekte.php?action=delete&id=".$user['id']."&moep=".$user['name']."\">löschen</a>|<a href=\"projekte.php?action=std&id=".$user['id']."\">standart</a></td>";
}
$output .="<td class='anmeldung_typ'>".get_cal_links("projekt",$user["id"])."</td>";
$output .="</tr>";
}
}

function projekte_add($post){
  $DB->query("INSERT INTO projekte SET dotlan_event_id = '".$post["event_id"]."', name = \"".$post["name"]."\", status = \"".$post["status"]."\", von = \"".$post["von"]."\", bis = \"".$post["bis"]."\", location = \"".$post["location"]."\", teilnehmer = \"".$post["teilnehmer"]."\", typ = \"".$post["typ"]."\", ad_level = \"".$post["ad"]."\", active = \"".$post["active"]."\"") or die($DB->error());
}

function projekte_del($id){ // <--------------------------- WICHTIG - ERWEITERN BEI NEUEN MODULEN !!!
  $DB->query("DELETE FROM projekte WHERE id = \"$id\"") or die($DB->error());
}

function projekte_update($post){
  $DB->query("UPDATE projekte SET dotlan_event_id = '".$post["event_id"]."', name = \"".$post["name"]."\", status = \"".$post["status"]."\", von = \"".$post["von"]."\", bis = \"".$post["bis"]."\", teilnehmer = \"".$post["teilnehmer"]."\", location = \"".$post["location"]."\", typ = \"".$post["typ"]."\", ad_level = \"".$post["ad"]."\", active = \"".$post["active"]."\" WHERE `id` = '".$post["id"]."' LIMIT 1;") or die($DB->error());
}

function projekte_chg_std($id){
  $DB->query("UPDATE projekte SET standart = 0 WHERE standart = 1;");
  $DB->query("UPDATE projekte SET standart = 1 WHERE id = ".$id.";");
}

function projekte_set_active($id){
  $event_id = $id;
  $query = $DB->query("SELECT * FROM projekte WHERE `id` = $id LIMIT 1;");
    $_SESSION['projekt_name'] = mysql_result($query,0,"name");
    $_SESSION['dotlan_event_id'] = mysql_result($query,0,"dotlan_event_id");
}

// Dienstplan
function Dienstplan_list($admin=0){
$query = $DB->query("SELECT * FROM Dienstplan_liste WHERE event_id = ".$event_id." ORDER BY datum DESC;");
$output .="<tr><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Gewesen</b></td><td class=\"anmeldung_typ\"><b>Datum&nbsp;</b></td><td class=\"anmeldung_typ\"><b>Location&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Adresse&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Anwesenheitsliste&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Geplant&nbsp;</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Protokoll&nbsp;</b></td>";
if($admin == 1)
  $output .="<td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Action&nbsp;</b></td>";
$output .="<td></td></tr>";
while ($row = $DB->fetch_array($query)){
$output .="<tr><td class=\"anmeldung_typ\" style=\"text-align:center;\">";
  if($row["gewesen"] == 1){
    if($admin==0) $output .="Ja";
    else $output .="<a href=\"Dienstplans.php?action=gewesen&id=".$row["id"]."&gewesen=0\">Ja</a>";
  }else{
    if($admin==0) $output .="Nein";
    else $output .="<a href=\"Dienstplans.php?action=gewesen&id=".$row["id"]."&gewesen=1\">Nein</a>";
  }
$output .="</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$row["datum"]."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".$row["location"]."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".nl2br($row["adresse"])."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\" style=\"text-align:center;\"><a href=\"Dienstplans_anwesenheitsliste.php?id=".$row["id"]."\">< klick ></a></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\" style=\"text-align:center;\"><a href=\"Dienstplans_texte.php?typ=1&id=".$row["id"]."\">< klick ></a></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\" style=\"text-align:center;\"><a href=\"Dienstplans_texte.php?typ=2&id=".$row["id"]."\">< klick ></a></td>";
if($admin == 1)
  $output .="<td class=\"anmeldung_data\" nowrap=\"nowrap\"><a href=\"Dienstplans.php?action=change&id=".$row['id']."\">editieren</a>|<a href=\"Dienstplans.php?action=delete&id=".$row['id']."&moep=".$row['datum']."\">löschen</a></td>";
$output .="<td>".get_cal_links("Dienstplan",$row["id"])."</td>";
$output .="</tr>";
}
}

function Dienstplan_insert($post){
  $DB->query("INSERT INTO Dienstplan_liste SET event_id = \"".$event_id."\", datum = \"".$post["datum"]."\", location = \"".$post["location"]."\", adresse = \"".$post["adresse"]."\", geplant = \"".$post["geplant"]."\"") or die($DB->error());
}

function Dienstplan_del($id){
  $DB->query("DELETE FROM Dienstplan_liste WHERE id = \"$id\"") or die($DB->error());
  $DB->query("DELETE FROM Dienstplan_anwesenheit WHERE Dienstplan_id = \"$id\"") or die($DB->error());
}

function Dienstplan_update($post){
  $DB->query("UPDATE Dienstplan_liste SET datum = \"".$post["datum"]."\", location = \"".$post["location"]."\", adresse = \"".$post["adresse"]."\", geplant = \"".$post["geplant"]."\" WHERE id = ".$post["id"].";");
}

function Dienstplan_chg_gewesen($id,$gewesen){
  $DB->query("UPDATE Dienstplan_liste SET gewesen = $gewesen WHERE id = $id;");
}

function Dienstplan_showtext($id,$typ,$ad_level2){
  if($typ == 1){
    $query = $DB->query("SELECT geplant,datum FROM Dienstplan_liste WHERE id = $id LIMIT 1;");
    $output .="<tr><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Geplant am ".mysql_result($query,0,"datum")."</b></td></tr><tr><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".nl2br(mysql_result($query,0,"geplant"))."</td></tr>";
  }elseif($typ == 2){
    $query = $DB->query("SELECT protokoll,datum FROM Dienstplan_liste WHERE id = $id LIMIT 1;");
    $output .="<tr><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Protokoll vom ".mysql_result($query,0,"datum")."</b></td></tr><tr><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".nl2br(mysql_result($query,0,"protokoll"))."</td></tr>";
  }
  if ($_SESSION['ad_level'] >= $ad_level2){
    $output .="<tr><td class=\"anmeldung_typ\" nowrap=\"nowrap\" style=\"text-align:center;\"><b><a href=\"Dienstplans_texte.php?action=change&typ=".$_GET["typ"]."&id=".$_GET["id"]."\">bearbeiten</a></b></td></tr>";
  }
}

function Dienstplan_showchangetext($id,$typ,$ad_level2){
  if($typ == 1){
    $query = $DB->query("SELECT geplant,datum FROM Dienstplan_liste WHERE id = $id LIMIT 1;");
    $output .="<form action=\"Dienstplans_texte.php?typ=$typ&id=$id\" method=\"POST\">";
    $output .="<tr><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Geplant am ".mysql_result($query,0,"datum")."</b></td></tr><tr><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><textarea class=\"editbox\" name=\"geplant\" rows=\"10\" cols=\"50\">".mysql_result($query,0,"geplant")."</textarea></td></tr>";
  }elseif($typ == 2){
    $query = $DB->query("SELECT protokoll,datum FROM Dienstplan_liste WHERE id = $id LIMIT 1;");
    $output .="<form action=\"Dienstplans_texte.php?typ=$typ&id=$id\" method=\"POST\">";
    $output .="<tr><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Protokoll vom ".mysql_result($query,0,"datum")."</b></td></tr><tr><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><textarea class=\"editbox\" name=\"protokoll\" rows=\"10\" cols=\"50\">".mysql_result($query,0,"protokoll")."</textarea></td></tr>";
  }
  if ($_SESSION['ad_level'] >= $ad_level2){
    $output .="<tr><td class=\"anmeldung_typ\" nowrap=\"nowrap\" style=\"text-align:center;\"><input class=\"okbuttons\" name=\"submit\" type=\"submit\" value=\"Anpassen\"></td></form></tr>";
  }
}

function Dienstplan_updatetext($id,$typ,$post){
  if($typ == 1) $DB->query("UPDATE Dienstplan_liste SET geplant = \"".$post["geplant"]."\" WHERE id = $id;");
  if($typ == 2) $DB->query("UPDATE Dienstplan_liste SET protokoll = \"".$post["protokoll"]."\" WHERE id = $id;");
}

// Dienstplan - Anwesenheit
function anw_liste($id,$gewesen,$ad_level2){
  $output .="<tr><td></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Nick</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Vorname</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Nachname</b></td><td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Wahrscheinlichkeit</b></td>";
  if($gewesen == 1){
    $output .="<td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>war anwesend</b></td>";
    if($_SESSION['ad_level'] >= $ad_level2) $output .="<td class=\"anmeldung_typ\" nowrap=\"nowrap\"><b>Action</b></td>";
  }
  $output .="</tr>";
  $query = $DB->query("SELECT * FROM Dienstplan_anwesenheit WHERE Dienstplan_id = $id ORDER BY wahrscheinlichkeit DESC;");
  while($row = $DB->fetch_array($query)){
    $query2 = $DB->query("SELECT * FROM user WHERE id = ".$row["user_id"]." LIMIT 1;");
    $output .="<tr><td>"; show_avatar(mysql_result($query2,0,"id"),"20"); echo"</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".mysql_result($query2,0,"nick")."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".mysql_result($query2,0,"vorname")."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\">".mysql_result($query2,0,"nachname")."</td><td class=\"anmeldung_typ\" nowrap=\"nowrap\" style=\"text-align:center;\">".$row["wahrscheinlichkeit"]."%</td>";
    if($gewesen == 1){
      $output .="<td class=\"anmeldung_typ\" nowrap=\"nowrap\" style=\"text-align:center;\">";
      if($row["anwesend"] == 0) $output .="k/A";
      elseif($row["anwesend"] == 1) $output .="ja";
      elseif($row["anwesend"] == 2) $output .="nein";
      $output .="</td>";

      if($_SESSION['ad_level'] >= $ad_level2) $output .="<td class=\"anmeldung_typ\" nowrap=\"nowrap\"><a href=\"Dienstplans_anwesenheitsliste.php?id=$id&action=anw&user_id=".$row["user_id"]."\">anwesend</a>|<a href=\"Dienstplans_anwesenheitsliste.php?id=$id&action=abw&user_id=".$row["user_id"]."\">abwesend</a></td>";
    }
    $output .="</tr>";
  }
}

function anw_chg_wahr($wahr,$id){
  $DB->query("UPDATE Dienstplan_anwesenheit SET wahrscheinlichkeit = \"".$wahr."\" WHERE Dienstplan_id = $id AND user_id = ".$user_id.";");
}

function anw_del($id){
  $DB->query("DELETE FROM Dienstplan_anwesenheit WHERE Dienstplan_id = \"$id\" AND user_id = ".$user_id.";") or die($DB->error());
}

function anw_add($wahr,$id){
  $DB->query("INSERT INTO Dienstplan_anwesenheit SET Dienstplan_id = \"$id\", user_id = ".$user_id.", wahrscheinlichkeit = '$wahr';") or die($DB->error());
}

function anw_chg_anw($id,$user_id,$anw){
  $DB->query("UPDATE Dienstplan_anwesenheit SET anwesend = \"".$anw."\" WHERE Dienstplan_id = $id AND user_id = ".$user_id.";");
}

// Forum-Functions Start //
// Threads ausgeben
function throwThreads($Proid) {
#$Thr = $DB->query("SELECT forum_thread.id, forum_thread.Userid, forum_thread.Projektid, forum_thread.Titel, forum_thread.datum, forum_thread.IP, forum_thread.versteckt, forum_thread.geschlossen, user.Vorname AS userVorname, user.Nachname AS userNachname FROM forum_thread LEFT JOIN user ON user.id = forum_thread.Userid WHERE forum_thread.Projektid = \"$Proid\" AND forum_thread.versteckt = 0 ORDER BY forum_thread.last DESC");
echo '<tr><td class="typotext" align = "right" valign="top" colspan = "4"><a href="forum.php?Projektid='.$event_id.'&action=newthread"><b>Neues Thema</b></a></td></tr>';
$query = $DB->query("SELECT * FROM maillisten ORDER BY name");
$gruppen = array();
$i=0;
while($row = $DB->fetch_array($query)){
  $gruppen[$i]["id"] = $row["id"];
  $gruppen[$i]["name"] = $row["name"];
  $i++;
}
$gruppen[$i]["id"] = -1;
$gruppen[$i]["name"] = "Archiv";

foreach($gruppen as $row){
  $Thr = $DB->query("SELECT forum_thread.id, forum_thread.Userid, forum_thread.Projektid, forum_thread.Titel, forum_thread.datum, forum_thread.IP, forum_thread.versteckt, forum_thread.geschlossen, user.Vorname AS userVorname, user.Nachname AS userNachname FROM forum_thread LEFT JOIN user ON user.id = forum_thread.Userid WHERE forum_thread.Projektid = \"$Proid\" AND forum_thread.versteckt = 0 AND forum_thread.group = '".$row["id"]."' ORDER BY forum_thread.last DESC");
  if($DB->num_rows($Thr) == 0) continue;
  $output .="<tr>";
  $output .="  <td colspan='4'><br><b>".$row["name"]."</b></td>";
  $output .="</tr>";
  echo '<tr>';
  echo '<td class="forumtop" align="left" valign="top" width="300"><b>Thema</b></td>';
  echo '<td class="forumtop" align="left" valign="top" width="150"><b>Starter</b></td>';
  echo '<td class="forumtop" align="left" valign="top" width="30"><b>Antw.</b></td>';
  echo '<td class="forumtop" align="left" valign="top" width="200"><b>Letzter Post</b></td>';
  echo '</tr>';
  while ($Thread = $DB->fetch_array($Thr)){
    $anz_posts = getNumberOfPosts($Thread['id']);
    $last_post = getLastPost($Thread['id']);
    $starter = getStarter($Thread['id']);
    echo '<tr>';
    echo '<td class="forumcontent" align="left" valign="middle"><a href="threadshow.php?Projektid='.$event_id.'&Threadid='.$Thread['id'].'"><SPAN STYLE="color: #07038F;"><b>'.$Thread['Titel'].'</b></SPAN></a></td>';
    echo '<td class="forumcontent" align="center" valign="middle">'.$starter.'</td>';
    echo '<td class="forumcontent" align="center" valign="middle">'.$anz_posts.'</td>';
    echo '<td class="forumcontent" align="center" valign="middle">'.$last_post.'</td>';
    echo '</tr>';
  }
}
}

// Forum-Posts ausgeben
function throwThreadContent($Proid, $Threadid){
$Thr = $DB->query("SELECT forum_thread.id, forum_thread.Userid, forum_thread.Projektid, forum_thread.Titel, forum_thread.datum, forum_thread.IP, forum_thread.versteckt, forum_thread.geschlossen, user.Vorname AS userVorname, user.Nachname AS userNachname FROM forum_thread LEFT JOIN user ON user.id = forum_thread.Userid WHERE forum_thread.Projektid = \"$Proid\" AND forum_thread.id = \"$Threadid\" AND forum_thread.versteckt = 0 ORDER BY forum_thread.datum DESC");

while ($Threadinfo = $DB->fetch_array($Thr)){

echo '<tr><td class="typotext" align = "left" valign="top" colspan = "2">';
echo '<table><tr><td class="typotext" align = "left" valign="top" width="450">';
echo '<a href="forum.php?Projektid='.$event_id.'"><b>Zurück zur Forumsübersicht</b></a>';
echo '&nbsp;&nbsp;&nbsp;<a href="#last">Zu neuen Beitr&auml;gen springen</a>';
echo '</td>';
echo '<td class="typotext" align = "right" valign="top" width="150">';
if ($Threadinfo['geschlossen'] == "0") {
echo '<a href="threadshow.php?Projektid='.$event_id.'&Threadid='.$Threadid.'&action=newanswer"><b>Antwort erstellen</b></a>'; }
else { echo '&nbsp;'; }
echo '</td></tr></table>';
echo '</td></tr>';
echo '<tr>';
echo '<td class="forumtop" align="left" valign="top" width="150"><b>Autor</b></td>';
echo '<td class="forumtop" align="left" valign="top" width="460"><b>Thema:</b> '.$Threadinfo['Titel'].'</td>';
echo '</tr>';
}
$forumpost = $DB->query("SELECT forum_post.POSTid, forum_post.Userid, forum_post.Content, forum_post.PostDatum, forum_post.PostIP, forum_post.versteckt, forum_post.geschlossen, user.Vorname AS userVorname, user.Nachname AS userNachname FROM forum_post LEFT JOIN user ON user.id = forum_post.Userid WHERE forum_post.Threadid = \"$Threadid\" ORDER BY forum_post.PostDatum ASC") or die($DB->error());
$linie = true;
while ($posts = $DB->fetch_array($forumpost)){
  $poster = getPoster($posts['POSTid']);
  if($linie && $_SESSION['last_login'] <= $posts["PostDatum"]){
    $output .="<tr><td colspan='2' style='background-color: #FF0000; font-size: 2px;'><a name='last'></a>&nbsp;</td></tr>";
    $linie = false;
  }
  if($posts["versteckt"] == 1){
    $output .="<tr><td class='forumcontent' colspan='2'>".getPoster($posts['POSTid'],true)." - editierter/gel&ouml;schter Post - <a onClick='document.getElementById(\"p".$posts["POSTid"]."\").style.display = \"table-row\"'>anzeigen</a></td></tr>";
    echo '<tr id="p'.$posts["POSTid"].'" style="display: none;">';
  }else{
    echo '<tr>';
  }
  echo '<td class="forumcontent" align="left" valign="top" width="120">'.$poster; show_avatar($posts["Userid"],"100"); 
  if($posts["Userid"] == $_SESSION["user_id"]){
    $output .="<br><a href='threadshow.php?Projektid=".$event_id."&Threadid=".$Threadid."&action=edit&post_id=".$posts["POSTid"]."'>edit</a>";
    $output .=" &nbsp; <a href='threadshow.php?Projektid=".$event_id."&Threadid=".$Threadid."&action=del&post_id=".$posts["POSTid"]."' onClick='return confirm(\"Post wirklich verstecken?\");'>del</a>";
  }
  echo '</td>';
  echo '<td class="forumcontent" align="left" valign="top" width="400">'.nl2br(preg_replace("/(http[s]?:\/\/[a-zA-Z0-9\.\,\;\~\#\-\_\?\&\=\/\%]*)/","<a href='$1' target='_blank'>$1</a>",$posts['Content'])).'</td>';
  echo '</tr>';

}
}




function getNumberOfPosts($Threadid){
	$query = $DB->query("SELECT * FROM forum_post WHERE Threadid = \"$Threadid\"");
	$numbers = $DB->num_rows($query);
	return $numbers;
}

function getLastPost($Threadid){
	$query = $DB->query("SELECT forum_post.PostDatum, forum_post.Userid, user.Vorname AS userVorname, user.Nachname AS userNachname FROM forum_post LEFT JOIN user ON user.id = forum_post.Userid WHERE forum_post.Threadid = \"$Threadid\"  AND forum_post.versteckt = 0 ORDER BY PostDatum");
	$numbers = $DB->num_rows($query);
	$i = 1;
	while ($Postdata = $DB->fetch_array($query)){
		if ($i == $numbers){
			$date = strftime ("%d.%m.%Y - %H:%M", $Postdata['PostDatum']);
			if ($Postdata['Userid'] != "0"){
				$LastPost = $date.'<br />'.$Postdata['userVorname'].' '.$Postdata['userNachname'];
			} elseif ($Postdata['Lehrid'] != "0") {
			    if ($Postdata['dozentTitel']) { $dozTitel = $Postdata['dozentTitel'].' '; } else { $dotTitel = ""; }
				$LastPost = $date.'<br /><i>'.$dozTitel.$Postdata['dozentVorname'].' '.$Postdata['dozentNachname'].'</i>';
		    } else { }
		}
		$i++;
	}
	return $LastPost;
}

function getPoster($Postid,$short = false){
	$query = $DB->query("SELECT forum_post.PostDatum, forum_post.Userid, user.Vorname AS userVorname, user.Nachname AS userNachname FROM forum_post LEFT JOIN user ON user.id = forum_post.Userid WHERE forum_post.POSTid = \"$Postid\"  ORDER BY PostDatum");
	$numbers = $DB->num_rows($query);
	$i = 1;
	while ($Postdata = $DB->fetch_array($query)){
		if ($i == $numbers){
			$date = strftime ("%d.%m.%Y - %H:%M", $Postdata['PostDatum']);
			if ($Postdata['Userid'] != "0"){
				$LastPost = $Postdata['userVorname'].' '.$Postdata['userNachname'];
                                if(!$short) $LastPost .= '<br />';
                                else $LastPost .= " | ";
                                $LastPost .= $date;
			} elseif ($Postdata['Lehrid'] != "0") {
			    if ($Postdata['dozentTitel']) { $dozTitel = $Postdata['dozentTitel'].' '; } else { $dotTitel = ""; }
				$LastPost = '<i>'.$dozTitel.$Postdata['dozentVorname'].' '.$Postdata['dozentNachname'].'</i><br />'.$date;
		    } else { }
		}
		$i++;
	}
	return $LastPost;
}

function getStarter($Threadid){
	$query = $DB->query("SELECT forum_thread.Userid, user.Vorname AS userVorname, user.Nachname AS userNachname FROM forum_thread LEFT JOIN user ON user.id = forum_thread.Userid WHERE forum_thread.id = \"$Threadid\"");
	while ($Postdata = $DB->fetch_array($query)){
			if ($Postdata['Userid'] != "0"){
				$Starter = $Postdata['userVorname'].' '.$Postdata['userNachname'];
			} elseif ($Postdata['Lehrid'] != "0") {
			    if ($Postdata['dozentTitel']) { $dozTitel = $Postdata['dozentTitel'].' '; } else { $dotTitel = ""; }
				$Starter = '<i>'.$dozTitel.$Postdata['dozentVorname'].' '.$Postdata['dozentNachname'].'</i>';
		    } else { }

	}
	return $Starter;
}

function insertThread($Userid, $Projektid, $ThreadTitel, $PostInhalt, $group){
	$zeit = time();
	$IP = $IP = $_SERVER["REMOTE_ADDR"];
	$DB->query("INSERT INTO forum_thread SET Projektid = \"$Projektid\", Userid = \"$Userid\", Titel = \"$ThreadTitel\", datum = \"$zeit\", IP = \"$IP\", `group` = '".$group."', last='".time()."'") or die("Fehler 1 ".$DB->error());
	$Threadid = $DB->insert_id();
	$DB->query("INSERT INTO forum_post SET Threadid = \"$Threadid\", Userid = \"$Userid\", Content = \"$PostInhalt\", PostDatum = \"$zeit\", PostIP = \"$IP\"");
}

function insertPost($Userid, $Threadid, $PostInhalt){
	$zeit = time();
	$IP = $IP = $_SERVER["REMOTE_ADDR"];
	$DB->query("INSERT INTO forum_post SET Threadid = \"$Threadid\", Userid = \"$Userid\", Content = \"$PostInhalt\", PostDatum = \"$zeit\", PostIP = \"$IP\"");
        $DB->query("UPDATE forum_thread SET last = '".time()."' WHERE id = '".$Threadid."' LIMIT 1");
}


// Threads ausgeben
function throwAdminThreads($Proid) {
$Thr = $DB->query("SELECT forum_thread.id, forum_thread.Userid, forum_thread.Projektid, forum_thread.Titel, forum_thread.datum, forum_thread.IP, forum_thread.versteckt, forum_thread.geschlossen, user.Vorname AS userVorname, user.Nachname AS userNachname FROM forum_thread LEFT JOIN user ON user.id = forum_thread.Userid WHERE forum_thread.Projektid = \"$Proid\" ORDER BY forum_thread.datum DESC") or die($DB->error());
echo '<tr><td class="typotext" align = "right" valign="top" colspan = "4"><a href="forumadmin.php?Projektid='.$event_id.'&action=newthread"><b>Neues Thema</b></a></td></tr>';
echo '<tr>';
echo '<td class="forumtop" align="left" valign="top" width="280"><b>Thema</b></td>';
echo '<td class="forumtop" align="left" valign="top" width="120"><b>Starter</b></td>';
echo '<td class="forumtop" align="left" valign="top" width="30"><b>Antw.</b></td>';
echo '<td class="forumtop" align="left" valign="top" width="130"><b>Letzter Post</b></td>';
echo '</tr>';
while ($Thread = $DB->fetch_array($Thr)){
$anz_posts = getNumberOfPosts($Thread['id']);
$last_post = getLastPost($Thread['id']);
$starter = getStarter($Thread['id']);
echo '<tr>';
echo '<td class="forumcontent" align="left" valign="top"><a href="threadadmin.php?Projektid='.$event_id.'&Threadid='.$Thread['id'].'"><SPAN STYLE="color: #07038F;"><b>'.$Thread['Titel'].'</b></SPAN></a>';
echo '<br /><a href="forumadmin.php?Projektid='.$event_id.'&Threadid='.$Thread['id'].'&action=delete" onClick="return confirm (\'Wollen Sie das Thema wirklich unwiederbringlich löschen?\')"><SPAN STYLE="color: #E10000;"><b>löschen</b></SPAN></a> || ';
if ($Thread['versteckt'] == "0"){
echo '<a href="forumadmin.php?Projektid='.$event_id.'&Threadid='.$Thread['id'].'&action=hide" onClick="return confirm (\'Wollen Sie das Thema wirklich verstecken?\')"><SPAN STYLE="color: #E10000;"><b>verstecken</b></SPAN></a> || ';
} else {
echo '<a href="forumadmin.php?Projektid='.$event_id.'&Threadid='.$Thread['id'].'&action=show"><SPAN STYLE="color: #00E124;"><b>anzeigen</b></SPAN></a> || ';
}
if ($Thread['geschlossen'] == "0"){
echo '<a href="forumadmin.php?Projektid='.$event_id.'&Threadid='.$Thread['id'].'&action=close"><SPAN STYLE="color: #E10000;" onClick="return confirm (\'Wollen Sie das Thema wirklich schliessen? Es können dann keine weiteren Einträge mehr vorgenommen werden!\')"><b>schliessen</b></SPAN></a>';
} else {
echo '<a href="forumadmin.php?Projektid='.$event_id.'&Threadid='.$Thread['id'].'&action=open"><SPAN STYLE="color: #00E124;"><b>öffnen</b></SPAN></a>';
}
echo '</td>';
echo '<td class="forumcontent" align="center" valign="middle">'.$starter.'</td>';
echo '<td class="forumcontent" align="center" valign="middle">'.$anz_posts.'</td>';
echo '<td class="forumcontent" align="center" valign="middle">'.$last_post.'</td>';
echo '</tr>';
}
}

// Forum-Posts ausgeben
function throwAdminThreadContent($Proid, $Threadid){
$Thr = $DB->query("SELECT forum_thread.id, forum_thread.Userid, forum_thread.Projektid, forum_thread.Titel, forum_thread.datum, forum_thread.IP, forum_thread.versteckt, forum_thread.geschlossen, user.Vorname AS userVorname, user.Nachname AS userNachname FROM forum_thread LEFT JOIN user ON user.id = forum_thread.Userid WHERE forum_thread.Projektid = \"$Proid\" AND forum_thread.id = \"$Threadid\" ORDER BY forum_thread.datum DESC");

while ($Threadinfo = $DB->fetch_array($Thr)){

echo '<tr><td class="typotext" align = "left" valign="top" colspan = "2">';
echo '<table><tr><td class="typotext" align = "left" valign="top" width="450">';
echo '<a href="forumadmin.php?Projektid='.$event_id.'"><b>Zurück zur Forumsübersicht</b></a>';
echo '</td>';
echo '<td class="typotext" align = "right" valign="top" width="150">';
if ($Threadinfo['geschlossen'] == "0") {
echo '<a href="threadadmin.php?Projektid='.$event_id.'&Threadid='.$Threadid.'&action=newanswer"><b>Antwort erstellen</b></a>'; }
else { echo '&nbsp;'; }
echo '</td></tr></table>';
echo '</td></tr>';
echo '<tr>';
echo '<td class="forumtop" align="left" valign="top" width="150"><b>Autor</b></td>';
echo '<td class="forumtop" align="left" valign="top" width="460"><b>Thema:</b> '.$Threadinfo['Titel'].'</td>';
echo '</tr>';
}
$forumpost = $DB->query("SELECT forum_post.POSTid, forum_post.Userid, forum_post.Content, forum_post.PostDatum, forum_post.PostIP, forum_post.versteckt, forum_post.geschlossen, user.Vorname AS userVorname, user.Nachname AS userNachname FROM forum_post LEFT JOIN user ON user.id = forum_post.Userid WHERE forum_post.Threadid = \"$Threadid\"  ORDER BY forum_post.PostDatum ASC") or die($DB->error());
while ($posts = $DB->fetch_array($forumpost)){
$poster = getPoster($posts['POSTid']);
echo '<tr>';
echo '<td class="forumcontent" align="left" valign="top" width="150">'.$poster;
echo '<br /><br />IP: '.$posts['PostIP'];
echo '<br /><a href="threadadmin.php?Projektid='.$event_id.'&Threadid='.$Threadid.'&Postid='.$posts['POSTid'].'&action=delete" onClick="return confirm (\'Wollen Sie den Eintrag wirklich unwiederbringlich löschen?\')"><SPAN STYLE="color: #E10000;"><b>löschen</b></SPAN></a> || ';
if ($posts['versteckt'] == "0"){
echo '<a href="threadadmin.php?Projektid='.$event_id.'&Threadid='.$Threadid.'&Postid='.$posts['POSTid'].'&action=hide" onClick="return confirm (\'Wollen Sie den Eintrag wirklich verstecken?\')"><SPAN STYLE="color: #E10000;"><b>verstecken</b></SPAN></a>';
} else {
echo '<a href="threadadmin.php?Projektid='.$event_id.'&Threadid='.$Threadid.'&Postid='.$posts['POSTid'].'&action=show"><SPAN STYLE="color: #00E124;"><b>anzeigen</b></SPAN></a>';
}
echo '</td>';
echo '<td class="forumcontent" align="left" valign="top" width="460">'.nl2br($posts['Content']).'</td>';
echo '</tr>';

}
}

function deleteThread($Threadid){
	$query = $DB->query("DELETE FROM forum_thread WHERE id = \"$Threadid\"");
	$query2 = $DB->query("DELETE FROM forum_post WHERE Threadid = \"$Threadid\"");
}

function hideThread($Threadid){
	$query = $DB->query("UPDATE forum_thread SET versteckt = \"1\" WHERE id = \"$Threadid\"");
}

function showThread($Threadid){
	$query = $DB->query("UPDATE forum_thread SET versteckt = \"0\" WHERE id = \"$Threadid\"");
}

function closeThread($Threadid){
	$query = $DB->query("UPDATE forum_thread SET geschlossen = \"1\" WHERE id = \"$Threadid\"");
}

function openThread($Threadid){
	$query = $DB->query("UPDATE forum_thread SET geschlossen = \"0\" WHERE id = \"$Threadid\"");
}

function deletePost($Postid){
	$query2 = $DB->query("DELETE FROM forum_post WHERE POSTid = \"$Postid\"");
}

function hidePost($Postid){
	$query = $DB->query("UPDATE forum_post SET versteckt = \"1\" WHERE POSTid = \"$Postid\"");
}

function showPost($Postid){
	$query = $DB->query("UPDATE forum_post SET versteckt = \"0\" WHERE POSTid = \"$Postid\"");
}
// Forum Functions End //

// Functions Down-  Uploads Start//

// Downloads von Usern ausgeben
function throwDownloadUser($Proid,$gruppe,$id) {
$sql = "SELECT downloads.id, downloads.Userid, downloads.Titel, downloads.Projektid, downloads.Beschreibung, downloads.Dateityp, downloads.Groesse, downloads.URL, downloads.time, user.Vorname, user.Nachname, downloads.version FROM";
if(empty($id)){
  $sql .= " downloads, user WHERE downloads.Projektid = \"$Proid\" AND user.id = downloads.Userid AND downloads.gruppe = '".$gruppe."'";
}else{
  $sql .= " downloads_history AS downloads, user WHERE dl_id = '".$id."' AND user.id = downloads.Userid";
}
$sql .= " ORDER BY downloads.Titel ASC";
$Download = $DB->query($sql);
return $Download;
}



// Upload durch User in DB eintragen
function downadd($Proid, $Userid, $Titel, $gruppe, $Dateityp, $Groesse, $URL, $time, $filename) {
$IP = $_SERVER["REMOTE_ADDR"];
$DB->query("INSERT INTO downloads SET Projektid=\"$Proid\", Userid=\"$Userid\", Titel=\"$Titel\", filename='$filename', gruppe=\"$gruppe\", Dateityp=\"$Dateityp\", Groesse=\"$Groesse\", URL=\"$URL\", time=\"$time\", UPIP=\"$IP\"");
}


// Ändern des DL durch Lehrkraft in DB eintragen
function downadminchange($dl_id, $NameDat, $gruppe, $TypeDat, $SizeDat, $PathDB, $Time, $userid, $filename){
  $IP = $_SERVER["REMOTE_ADDR"];
  if (empty($SizeDat)){
    $DB->query("UPDATE downloads SET Titel=\"$NameDat\", gruppe=\"$gruppe\", Dateityp=\"$TypeDat\", time=\"$Time\", UPIP=\"$IP\" WHERE id = \"$dl_id\"");
  } else {
    # Kopie vom alten Eintrag:
    $DB->query("INSERT INTO downloads_history (`dl_id`,`Projektid`,`Userid`,`gruppe`,`Titel`,`version`,`filename`,`Beschreibung`,`Dateityp`,`Groesse`,`URL`,`time`,`UPIP`) SELECT `id`,`Projektid`,`Userid`,`gruppe`,`Titel`,`version`,`filename`,`Beschreibung`,`Dateityp`,`Groesse`,`URL`,`time`,`UPIP` FROM downloads WHERE id = '".$dl_id."'");
    $DB->query("UPDATE downloads SET Titel=\"$NameDat\", gruppe=\"$gruppe\", Dateityp=\"$TypeDat\", Groesse = \"$SizeDat\", URL = \"$PathDB\", time=\"$Time\", UPIP=\"$IP\", version = version+1, Userid = '".$userid."', filename='$filename'  WHERE id = \"$dl_id\"");
  }
}

// Umlaute und Sonderzeichen aus Dateinamen entfernen

function stripfilename($datei_name){
	$umlaute = Array("/ä/","/ö/","/ü/","/Ä/","/Ö/","/Ü/","/ß/");
	$replace = Array("ae","oe","ue","Ae","Oe","Ue","ss");
    // Umlaute entfernen
    $datei_name = preg_replace($umlaute, $replace, $datei_name);
	// sonstige sonderzeichen entfernen
	$datei_name = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $datei_name);
	return $datei_name;
}
// Functions Down- / Uploads End//

// Functions Aufgaben Start //

// Aufgaben ausgeben
function throwAufgaben($Proid, $Userid, $Zuweiserid, $order, $erledigt_anzeigen = 1) {
$Sessid = $_SESSION['id'];
$order = "Aufgabe";
if($erledigt_anzeigen == 0) $erledigt_where = " AND aufgaben.status < 100 ";
if ($Userid != 0 && $Zuweiserid == 0){
$Aufgaben = $DB->query("SELECT aufgaben.id, aufgaben.Projektid, aufgaben.Userid, aufgaben.Zuweiserid, aufgaben.kategorie, aufgaben.Aufgabe, aufgaben.beschreibung, aufgaben.status, aufgaben.prio, aufgaben.start, aufgaben.end, user.vorname, user.nachname, maillisten.name FROM aufgaben LEFT JOIN user ON user.id = aufgaben.Userid LEFT JOIN maillisten ON maillisten.id = aufgaben.Groupid WHERE aufgaben.Projektid = \"$Proid\" AND aufgaben.Userid = \"$Sessid\" $erledigt_where ORDER BY $order") or die ("Keine Aufgaben gefunden 1: ".$DB->error());
echo '<tr><td class="maintd" colspan="7"><h2>Meine Aufgaben</h2></td></tr>';
echo '<tr><td class="forumtop" width="300">Aufgabe</td><td class="forumtop" width="150">User/Gruppe</td><td class="forumtop" width="80">Start</td><td class="forumtop" width="80">Stop</td><td class="forumtop" width="50">Prio</td><td class="forumtop" width="40">Status</td><td class="forumtop" width="100">Action</td></tr>';
while ($Auf = $DB->fetch_array($Aufgaben)){
echo '<tr><td class="forumcontent" width="300">'.$Auf['Aufgabe'].'</td><td class="forumcontent" width="150">'.$Auf['name'].'<br />'.$Auf['vorname'].' '.$Auf['nachname'].'</td><td class="forumcontent" width="80">'.$Auf['start'].'</td><td class="forumcontent" width="80">'.$Auf['end'].'</td><td class="forumcontent" width="50">'.$Auf['prio'].'&nbsp;</td><td class="forumcontent" width="40">'.$Auf['status'].' %</td><td class="forumcontent" width="100"><a href="aufgaben.php?action=change&Aufgabeid='.$Auf['id'].'"><b>ändern</b></a><br /><a href="aufgaben.php?action=done&Aufgabeid='.$Auf['id'].'"><SPAN STYLE="color: #00E124;"><b>erledigt</b></SPAN></a><br /><a href="aufgaben.php?action=delete&Aufgabeid='.$Auf['id'].'" onClick="return confirm (\'Wollen Sie die Aufgabe wirklich unwiederbringlich löschen?\')"><SPAN STYLE="color: #E10000;"><b>löschen</b></SPAN></a></td></tr>';
}
} elseif ($Userid != 0 && $Zuweiserid != 0){
$Groups = $DB->query("SELECT list_id FROM mailmember WHERE user_id = \"$Userid\" ORDER BY list_id");
echo '<tr><td class="maintd" colspan="7"><h2>Meine Gruppenaufgaben</h2></td></tr>';
echo '<tr><td class="forumtop" width="300">Aufgabe</td><td class="forumtop" width="150">User/Gruppe</td><td class="forumtop" width="80">Start</td><td class="forumtop" width="80">Stop</td><td class="forumtop" width="50">Prio</td><td class="forumtop" width="40">Status</td><td class="forumtop" width="100">Action</td></tr>';
while ($Group = $DB->fetch_array($Groups)){
$Groupid = $Group['list_id'];
$Aufgaben = $DB->query("SELECT aufgaben.id, aufgaben.Projektid, aufgaben.Userid, aufgaben.Zuweiserid, aufgaben.kategorie, aufgaben.Aufgabe, aufgaben.beschreibung, aufgaben.status, aufgaben.prio, aufgaben.start, aufgaben.end, user.vorname, user.nachname, maillisten.name FROM aufgaben LEFT JOIN user ON user.id = aufgaben.Userid LEFT JOIN maillisten ON maillisten.id = aufgaben.Groupid WHERE aufgaben.Projektid = \"$Proid\" AND aufgaben.Groupid = \"$Groupid\" $erledigt_where ORDER BY $order") or die ("Keine Aufgaben gefunden 1: ".$DB->error());
while ($Auf = $DB->fetch_array($Aufgaben)){
echo '<tr><td class="forumcontent" width="300">'.$Auf['Aufgabe'].'</td><td class="forumcontent" width="150">'.$Auf['name'].'<br />'.$Auf['vorname'].' '.$Auf['nachname'].'</td><td class="forumcontent" width="80">'.$Auf['start'].'</td><td class="forumcontent" width="80">'.$Auf['end'].'</td><td class="forumcontent" width="50">'.$Auf['prio'].'&nbsp;</td><td class="forumcontent" width="40">'.$Auf['status'].' %</td><td class="forumcontent" width="100"><a href="aufgaben.php?action=change&Aufgabeid='.$Auf['id'].'"><b>ändern</b></a><br /><a href="aufgaben.php?action=done&Aufgabeid='.$Auf['id'].'"><SPAN STYLE="color: #00E124;"><b>erledigt</b></SPAN></a><br /><a href="aufgaben.php?action=delete&Aufgabeid='.$Auf['id'].'" onClick="return confirm (\'Wollen Sie die Aufgabe wirklich unwiederbringlich löschen?\')"><SPAN STYLE="color: #E10000;"><b>löschen</b></SPAN></a></td></tr>';
}}
} elseif ($Userid == 0 && $Zuweiserid != 0 && $_SESSION['ad_level'] >= 4){
$Aufgaben = $DB->query("SELECT aufgaben.id, aufgaben.Projektid, aufgaben.Userid, aufgaben.Zuweiserid, aufgaben.kategorie, aufgaben.Aufgabe, aufgaben.beschreibung, aufgaben.status, aufgaben.prio, aufgaben.start, aufgaben.end, user.vorname, user.nachname, maillisten.name FROM aufgaben LEFT JOIN user ON user.id = aufgaben.Userid LEFT JOIN maillisten ON maillisten.id = aufgaben.Groupid WHERE aufgaben.Projektid = \"$Proid\" AND aufgaben.Zuweiserid = \"$Sessid\" AND (aufgaben.Userid != \"$Sessid\") ORDER BY $order") or die ("Keine Aufgaben gefunden 2: ".$DB->error());
echo '<tr><td class="maintd" colspan="7"><h2>Aufgaben, die ich zugeordnet habe</h2></td></tr>';
echo '<tr><td class="forumtop" width="300">Aufgabe</td><td class="forumtop" width="150">User/Gruppe</td><td class="forumtop" width="80">Start</td><td class="forumtop" width="80">Stop</td><td class="forumtop" width="50">Prio</td><td class="forumtop" width="40">Status</td><td class="forumtop" width="100">Action</td></tr>';
while ($Auf = $DB->fetch_array($Aufgaben)){
echo '<tr><td class="forumcontent" width="300">'.$Auf['Aufgabe'].'</td><td class="forumcontent" width="150">'.$Auf['name'].'<br />'.$Auf['vorname'].' '.$Auf['nachname'].'</td><td class="forumcontent" width="80">'.$Auf['start'].'</td><td class="forumcontent" width="80">'.$Auf['end'].'</td><td class="forumcontent" width="50">'.$Auf['prio'].'&nbsp;</td><td class="forumcontent" width="40">'.$Auf['status'].' %</td><td class="forumcontent" width="100"><a href="aufgaben.php?action=change&Aufgabeid='.$Auf['id'].'"><b>ändern</b></a><br /><a href="aufgaben.php?action=mahn&Aufgabeid='.$Auf['id'].'"><b>anmahnen</b></a><br /><a href="aufgaben.php?action=done&Aufgabeid='.$Auf['id'].'"><SPAN STYLE="color: #00E124;"><b>erledigt</b></SPAN></a><br /><a href="aufgaben.php?action=delete&Aufgabeid='.$Auf['id'].'"  onClick="return confirm (\'Wollen Sie die Aufgabe wirklich unwiederbringlich löschen?\')"><SPAN STYLE="color: #E10000;"><b>löschen</b></SPAN></a></td></tr>';
}
}
elseif ($Userid == 0 && $Zuweiserid == 0  && $_SESSION['ad_level'] >= 3){
$Aufgaben = $DB->query("SELECT aufgaben.id, aufgaben.Projektid, aufgaben.Userid, aufgaben.Zuweiserid, aufgaben.kategorie, aufgaben.Aufgabe, aufgaben.beschreibung, aufgaben.status, aufgaben.prio, aufgaben.start, aufgaben.end, user.vorname, user.nachname, maillisten.name FROM aufgaben LEFT JOIN user ON user.id = aufgaben.Userid LEFT JOIN maillisten ON maillisten.id = aufgaben.Groupid WHERE aufgaben.Projektid = \"$Proid\" AND aufgaben.Zuweiserid != \"$Sessid\" AND aufgaben.Userid != \"$Sessid\" ORDER BY $order") or die ("Keine Aufgaben gefunden 3: ".$DB->error());
echo '<tr><td class="maintd" colspan="7"><h2>Weitere Aufgaben</h2></td></tr>';
echo '<tr><td class="forumtop" width="300">Aufgabe</td><td class="forumtop" width="150">User/Gruppe</td><td class="forumtop" width="80">Start</td><td class="forumtop" width="80">Stop</td><td class="forumtop" width="50">Prio</td><td class="forumtop" width="40">Status</td><td class="forumtop" width="100">Action</td></tr>';
while ($Auf = $DB->fetch_array($Aufgaben)){
echo '<tr><td class="forumcontent" width="300">'.$Auf['Aufgabe'].'</td><td class="forumcontent" width="150">'.$Auf['name'].'<br />'.$Auf['vorname'].' '.$Auf['nachname'].'</td><td class="forumcontent" width="80">'.$Auf['start'].'</td><td class="forumcontent" width="80">'.$Auf['end'].'</td><td class="forumcontent" width="50">'.$Auf['prio'].'&nbsp;</td><td class="forumcontent" width="40">'.$Auf['status'].' %</td><td class="forumcontent" width="100"><a href="aufgaben.php?action=change&Aufgabeid='.$Auf['id'].'"><b>ändern</b></a><br /><a href="aufgaben.php?action=done&Aufgabeid='.$Auf['id'].'"><SPAN STYLE="color: #00E124;"><b>erledigt</b></SPAN></a><br /><a href="aufgaben.php?action=delete&Aufgabeid='.$Auf['id'].'" onClick="return confirm (\'Wollen Sie die Aufgabe wirklich unwiederbringlich löschen?\')"><SPAN STYLE="color: #E10000;"><b>löschen</b></SPAN></a></td></tr>';
}
}
}

function create_aufgabe($Projektid, $Aufgabe, $beschreibung, $start, $end, $Userid, $Groupid, $Zuweiserid, $Prio, $Status){
$DB->query("INSERT INTO aufgaben SET Projektid = \"$Projektid\", Aufgabe = \"$Aufgabe\", beschreibung = \"$beschreibung\", start = \"$start\", end = \"$end\", Userid = \"$Userid\", Groupid = \"$Groupid\", Zuweiserid = \"$Zuweiserid\", prio = \"$Prio\", status=\"$Status\"") or die("Fehler beim Anlegen der Aufgabe: ".$DB->error());
}

function change_aufgabe($Aufgabeid, $Aufgabe, $beschreibung, $start, $end, $Userid, $Groupid, $Zuweiserid, $Prio, $Status){
$DB->query("UPDATE aufgaben SET Aufgabe = \"$Aufgabe\", beschreibung = \"$beschreibung\", start = \"$start\", end = \"$end\", Userid = \"$Userid\", Groupid = \"$Groupid\", Zuweiserid = \"$Zuweiserid\", prio = \"$Prio\", status=\"$Status\" WHERE id = \"$Aufgabeid\"") or die("Fehler beim Anlegen der Aufgabe: ".$DB->error());
}

function delete_aufgabe($Aufgabeid){
$DB->query("DELETE FROM aufgaben WHERE id = \"$Aufgabeid\"") or die($DB->error());
}

function done_aufgabe($Aufgabeid){
$DB->query("UPDATE aufgaben SET status = 100 WHERE id = \"$Aufgabeid\"") or die($DB->error());
}

function mahn_aufgabe($Aufgabeid){

$query=$DB->query("SELECT aufgaben.Aufgabe, aufgaben.beschreibung, aufgaben.Userid, aufgaben.Groupid, aufgaben.Zuweiserid, aufgaben.start, aufgaben.end, aufgaben.status, aufgaben.prio, projekte.name AS projektname, user.vorname, user.nachname, user.nick, user.mail FROM aufgaben LEFT JOIN projekte ON projekte.id = aufgaben.Projektid LEFT JOIN user ON user.id = aufgaben.Zuweiserid WHERE aufgaben.id = \"$Aufgabeid\"") or die($DB->error());

while ($Auf=$DB->fetch_array($query)){
	$Aufgabe = $Auf['Aufgabe'];
	$Beschreibung = $Auf['beschreibung'];
	$Userid = $Auf['Userid'];
	$Groupid = $Auf['Groupid'];
	$Zuweiserid = $Auf['Zuweiserid'];
	$Start = $Auf['start'];
	$End = $Auf['end'];
	$Status = $Auf['status'];
	$Prio = $Auf['prio'];
	$Projektname = $Auf['projektname'];
	$Zuweiser = $Auf['vorname'].' "'.$Auf['nick'].'" '.$Auf['nachname'];
	$Zuweisermail =$Auf['mail'];
}

if ($Groupid == 0){
$quser = $DB->query("SELECT * FROM user WHERE id = \"$Userid\"");
while ($User = $DB->fetch_array($quser)){
	$to = $User['mail'];
	$Username = $User['vorname'].' "'.$User['nick'].'" '.$User['nachname'];
}

$Betreff = $Projektname.' - Aufgabenerinnerung: '.$Aufgabe;
$Content = "Hallo $Username,

ich möchte dich mit dieser Mail kurz an die von dir übernommene Aufgabe erinnern:

$Aufgabe
$Beschreibung

Priorität: $Prio
Status: $Status %

Zu erledingen bis: $End

Solltest du die Aufgabe bereits erledigt haben, ändere bitte entsprechend den Status unter http://projekt.lantreff.net.

Ansonsten möchte ich dich bitten, die Aufgabe möglichst zügig abzuschliessen. Solltest du zeitlich nicht in der Lage sein, die Aufgabe abzuschliessen, kontaktiere mich bitte umgehend, damit wir die Aufgabe ggf. an jemand anderes abgeben können.

Nette Grüße
$Zuweiser";


mail($to,$Betreff,$Content,'From: '.$Zuweisermail);
}

if ($Groupid != 0){
$quser = $DB->query("SELECT maillisten.name, user.vorname, user.nachname, user.nick, user.mail FROM maillisten LEFT JOIN mailmember ON mailmember.list_id = maillisten.id LEFT JOIN user ON user.id = mailmember.user_id WHERE maillisten.id = \"$Groupid\"");

while ($User = $DB->fetch_array($quser)){
	$Group = $User['name'];
	$to = $User['mail'];
	$Username = $User['vorname'].' "'.$User['nick'].'" '.$User['nachname'];


$Betreff = $Projektname.' - Aufgabenerinnerung: '.$Aufgabe;
$Content = "Hallo $Username,

ich möchte dich mit dieser Mail kurz an die von der Gruppe $Group übernommene Aufgabe erinnern:

$Aufgabe
$Beschreibung

Priorität: $Prio
Status: $Status %

Zu erledingen bis: $End

Solltet ihr die Aufgabe bereits erledigt haben, ändert bitte entsprechend den Status unter http://projekt.lantreff.net.

Ansonsten möchte ich die Gruppe bitten, die Aufgabe möglichst zügig abzuschliessen. Solltet ihr zeitlich nicht in der Lage sein, die Aufgabe abzuschliessen, kontaktiert mich bitte umgehend, damit wir die Aufgabe ggf. an jemand anderes abgeben können.

Nette Grüße
$Zuweiser";


mail($to,$Betreff,$Content,"From: $Zuweisermail");
}
}

}
// Functions Aufgaben End //

function get_google_cal_link($typ,$id){
global $global;

  if($typ == "projekt"){
    $query = mysql_query("SELECT ad_level, name, location, DATE_FORMAT(von - INTERVAL 2 HOUR,'%Y%m%dT%H%i%sZ') AS von, DATE_FORMAT(bis - INTERVAL 1 HOUR,'%Y%m%dT%H%i%sZ') AS bis FROM projekte WHERE id = '".$id."' LIMIT 1");
    if($_SESSION['ad_level'] < mysql_result($query,0,"ad_level")) die("Nicht ausrechend Bereichtigung");
    $name = mysql_result($query,0,"name");
    $von = mysql_result($query,0,"von");
    $bis = mysql_result($query,0,"bis");
    $wo = mysql_result($query,0,"location");
  }elseif($typ == "Dienstplan"){
    $query = mysql_query("SELECT adresse, DATE_FORMAT(datum  - INTERVAL 2 HOUR, '%Y%m%dT%H%i%sZ') AS datum, DATE_FORMAT(datum  - INTERVAL 1 HOUR,'%Y%m%dT%H%i%sZ') AS bis FROM project_dienstplan_liste WHERE id = '".$id."' LIMIT 1");
    $name = $global['sitename']." - Dienstplan";
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
