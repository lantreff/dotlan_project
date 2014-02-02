<?
$MODUL_NAME = "anwesenheit";
include_once("../../../global.php");
include("../functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Anwesenheit");
$event_id = $EVENT->next;
$tage = array("Mittwoch","Donnerstag","Freitag","Samstag","Sonntag","Montag");

if(!$DARF["view"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

#######################################

// Anwesenheit eintragen
if($_POST["anwesenheit"]){
  foreach($tage as $tag){
    $key = "event_id = '".$event_id."',";
    $key .= "tag = '".$tag."',";
    $key .= "user_id = '".$CURRENT_USER->id."',";
    $key .= "name = '',";

    $sql = "";
    if($_POST["abwesend"]){
      $sql .= "abwesend = '1',";
      unset($_POST[$tag]);
    }else $sql .= "abwesend = '0',";
    for($i=0; $i<24; $i++) $sql .= "ab_$i = '".($_POST[$tag][$i] ? '1' : '0')."',";
    $sql = substr($sql,0,-1);

    $DB->query("INSERT INTO project_anwesenheit SET $key $sql ON DUPLICATE KEY UPDATE $sql");
  }
  $DB->query("DELETE FROM project_anwesenheit WHERE ab_0 = '0' AND ab_1 = '0' AND ab_2 = '0' AND ab_3 = '0' AND ab_4 = '0' AND ab_5 = '0' AND ab_6 = '0' AND ab_7 = '0' AND ab_8 = '0' AND ab_9 = '0' AND ab_10 = '0' AND ab_11 = '0' AND ab_12 = '0' AND ab_13 = '0' AND ab_14 = '0' AND ab_15 = '0' AND ab_16 = '0' AND ab_17 = '0' AND ab_18 = '0' AND ab_19 = '0' AND ab_20 = '0' AND ab_21 = '0' AND ab_22 = '0' AND ab_23 = '0' AND abwesend = '0'"); // leere Datensaetze entfernen
}

#######################################

// Daten alle auslesen - ist einfacher beim Aufbauen der Tabellen
$user_data = array();
$user_abwesend = array();
$user_anwesend = array();
$event_data = array();
$query = $DB->query("SELECT * FROM project_anwesenheit WHERE event_id = '".$event_id."' ORDER BY name");
while($row = $DB->fetch_array($query)){
  if($row["user_id"] == 0) $event_data[$row["tag"]] = $row;
  else{
    if($row["abwesend"] == 1) $user_abwesend[] = $row["user_id"];
    else $user_anwesend[] = $row["user_id"];
    $user_data[$row["tag"]][$row["user_id"]] = $row;
  }
}
$user_abwesend = array_unique($user_abwesend);
$user_anwesend = array_unique($user_anwesend);

// Alle moeglichen User (alle mit view-rechten)
$all_users = array();
$query = $DB->query("SELECT user_id FROM project_rights_rights AS r, project_rights_user_rights AS u WHERE bereich = 'anwesenheit' AND recht = 'view' AND r.id = u.right_id");
while($row = $DB->fetch_array($query)) $all_users[] = $row["user_id"];

// Usernamen auslesen
$user_names = array();
if(count($all_users) > 0){
  $query = $DB->query("SELECT id, nick FROM user WHERE id IN (".implode(",",$all_users).")");
  while($row = $DB->fetch_array($query)) $user_names[$row["id"]] = $row["nick"];
  asort($user_names);
}

// Tabellen-Kopf generieren
$thead  = "<tr>";
$thead .= "  <th class='forumhead'>User</th>";
for($i=0; $i<24; $i++) $thead .= "<th class='forumhead' width='30' align='center' style='font-size: 8px;'>".$i."-".($i+1)."</th>";
$thead .= "</tr>";

// Tabelle pro Tag anzeigen
foreach($tage as $tag){
  $z=0;
  $output .= "<h3>$tag</h3>";
  $output .= "<table width='950'>";
  $output .= $thead;

  // Events anzeigen
  foreach($event_data[$tag] as $event){
    $output .= "<tr class='msgrow".(($z%2)+1)."'>";
    $output .= "  <td>".$row["name"]."</td>";
    for($i=0; $i<24; $i++){
      $output .= "<td bgcolor='".($event["ab_$i"] == 1 ? "#000099" : "")."'>&nbsp;</td>";
    }
    $output .= "</tr>";
    $z++;
  }

  // User anzeigen
  foreach($user_names as $uid => $uname){
    if(!in_array($uid,$user_anwesend)) continue;

    if($CURRENT_USER->id == $uid) $color = "#00AAAA";
    else $color = "#00AA00";

    $output .= "<tr class='msgrow".(($z%2)+1)."'>";
    $output .= "  <td>".$uname."</td>";
    for($i=0; $i<24; $i++){
      $output .= "<td bgcolor='".($user_data[$tag][$uid]["ab_$i"] == 1 ? $color : "")."'>&nbsp;</td>";
    }
    $output .= "</tr>";
    $z++;
  }

  $output .= "</table>";
  $output .= "<br>";
}

// CheckAll Funktion
$output .= "<script>";
$output .= "  function check_all(tag){";
$output .= "    var val = document.getElementById(tag).checked;";
$output .= "    var boxes = document.getElementsByTagName('input');";
$output .= "    var regex = new RegExp('^'+tag);";
$output .= "    for(var i=0; i<boxes.length; i++){";
$output .= "      if(boxes[i].name.match(regex)){";
$output .= "        boxes[i].checked = val;";
$output .= "      }";
$output .= "    }";
$output .= "  }";
$output .= "</script>";

// Eigene Anwesenheit
$output .= "<br><hr>";
$output .= "<h3>Eigene Anwesenheit</h3>";
$output .= "<form action='index.php' method='POST'>";
$output .= "<table width='950'>";
$output .= "  <tr>";
$output .= "    <th class='forumhead'>Tag</th>";
for($i=0; $i<24; $i++) $output .= "<th class='forumhead' width='30' align='center' style='font-size: 8px;'>".$i."-".($i+1)."</th>";
$output .= "    <th class='forumhead' width='30' align='center' style='font-size: 8px;'>all</th>";
$output .= "  </tr>";

$z=0;
foreach($tage as $tag){
  $output .= "<tr class='msgrow".(($z%2)+1)."'>";
  $output .= "  <td>$tag</td>";
  for($i=0; $i<24; $i++) $output .= "<td align='center'><input style='margin: 0px;' type='checkbox' name='".$tag."[".$i."]' ".($user_data[$tag][$CURRENT_USER->id]["ab_$i"] ? "checked='checked'" : "")."></td>";
  $output .= "  <td class='msgrow2' align='center'><input style='margin: 0px;' type='checkbox' onClick='check_all(\"$tag\");' id='$tag'></td>";
  $output .= "</tr>";
  $z++;
}

$output .= "  <tr>";
$output .= "    <td colspan='25' align='center'><input type='checkbox' name='abwesend' ".(in_array($CURRENT_USER->id,$user_abwesend) ? "checked='checked'" : "")."> Ich komme nicht zur LAN <input type='submit' value='speichern' name='anwesenheit'></td>";
$output .= "  </tr>";
$output .= "</table>";
$output .= "</form>";

// Abwesende / nicht eingetragene User
$abwesend = "";
foreach($user_abwesend as $uid) $abwesend .= $user_names[$uid]."<br>";

$nicht_eingetragen = "";
foreach($all_users as $uid) if(!in_array($uid,$user_anwesend) && !in_array($uid,$user_abwesend)) $nicht_eingetragen .= $user_names[$uid]."<br>";

$output .= "<br><br><hr>";
$output .= "<table>";
$output .= "  <tr>";
$output .= "    <th class='forumhead' width='200'>Abwesend</th>";
$output .= "    <th class='forumhead' width='200'>Noch nicht eingetragen</th>";
$output .= "  </tr>";
$output .= "  <tr>";
$output .= "    <td valign='top'>$abwesend</td>";
$output .= "    <td valign='top'>$nicht_eingetragen</td>";
$output .= "  </tr>";
$output .= "</table>";

$PAGE->render($output);
?>
