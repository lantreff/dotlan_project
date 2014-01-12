<?php
############################################################
# Freeze-Button Modul for dotlan                           #
#                                                          #
# Copyright (C) 2010 Torsten Amshove <torsten@amshove.net> #
############################################################
include_once("../../../../global.php");
require_once("../../functions.php");
$PAGE->sitetitle = $PAGE->htmltitle = _("Freeze");

if($ADMIN->check(ADMIN_USER)){
  $output .= "<br>";

  if($_POST["submit"]){
    $output .= "<b>F&uuml;hre freeze aus:<br>";
    $event_id = mysql_real_escape_string($_POST["event_id"]);
    if($_POST["projekt"] == 1){
      $DB->query("DELETE FROM project_rights_user_rights WHERE right_id IN (SELECT id FROM project_rights_rights WHERE name NOT LIKE '%view')");
      $output .= " - Alle Projekt-Rechte (au&szlig;er \"view\") entfernt.<br>";
    }
    if($_POST["turniere"] == 1){ 
      $DB->query("UPDATE t_turnier SET topen = 0 WHERE teventid = '".$event_id."'");
      $output .= " - Alle Turnieranmeldungen gestoppt.<br>";
    }
    if($_POST["turnier_abmeldung"] ==1){
      $query = $DB->query("SELECT p.tnid AS tnid, p.user_id AS user_id
                           FROM `t_teilnehmer` AS n, t_turnier AS t, t_teilnehmer_part AS p
                           WHERE n.tid = t.tid
                             AND t.teventid = '".$event_id."'
                             AND p.tnid = n.tnid
                             AND p.user_id NOT IN (SELECT user_id FROM event_teilnehmer WHERE event_id = '".$event_id."')");
      $tnids = array();
      $userids = array();
      while($data = $DB->fetch_array($query)){
        if(!in_array($data["tnid"],$tnids)) $tnids[] = $data["tnid"];
        if(!in_array($data["user_id"],$userids)) $userids[] = $data["user_id"];
        $DB->query("DELETE FROM t_teilnehmer_part WHERE tnid = '".$data["tnid"]."' AND user_id = '".$data["user_id"]."' LIMIT 1");
      }

      foreach($tnids as $tnid){
        $DB->query("DELETE FROM t_teilnehmer WHERE tnid = '".$tnid."' AND tnanz = 1");
        $data = $DB->query_first("SELECT tnid FROM t_teilnehmer WHERE tnid = '".$tnid."' AND tnleader IN (".implode(",",$userids).")");
        if(!empty($data["tnid"])){
          $query = $DB->query("SELECT user_id FROM t_teilnehmer_part WHERE tnid = '".$tnid."'");
          if($DB->num_rows($query) > 0){
            $new_tnleader = mysql_result($query,0,'user_id');
            $DB->query("UPDATE t_teilnehmer SET tnleader = '".$new_tnleader."' WHERE tnid = '".$tnid."' LIMIT 1");
          }else{
            $DB->query("DELETE FROM t_teilnehmer WHERE tnid = '".$tnid."' LIMIT 1");
          }
        }
      }
      
      $output .= " - Abgemeldete User aus Turnieren gel&ouml;scht. WICHTIG: unter <a href='/admin/support'>/admin/support</a> den Cache leeren!<br>";
    }
    if($_POST["catering"] == 1){
      $DB->query("UPDATE catering_groups SET active = 0");
      $output .= " - Alle Catering Artikelgruppen versteckt.<br>";
    }
    $output .= "</b><br><br>";
  }

  $res = $DB->query("SELECT id, name FROM events ORDER BY id");

  $output .= "<form action='index.php' method='POST'>";
  $output .= "<select name='event_id'>"; 
  while($data = $DB->fetch_array($res)){
    $output .= "<option value='".$data["id"]."'";
    if($data["id"] == $EVENT->next) $output .= " selected ";
    $output .= ">".$data["name"]."</option>";
  }
  $output .= "</select><br>";
  $output .= "<br>";
  $output .= "<h3>Vor dem DB-Dump</h3>";
  $output .= "<input type='checkbox' name='turnier_abmeldung' value='1'> Abgemeldete User aus Turnieren l&ouml;schen<br>";
  $output .= "<br>";
  $output .= "<h3>Nach dem DB-Dump (im Web)</h3>";
  $output .= "<input type='checkbox' name='projekt' value='1'> Alle Projekt-Rechte (au&szlig;er \"view\") entziehen<br>";
  $output .= "<input type='checkbox' name='turniere' value='1'> Turnieranmeldungen stoppen<br>";
  $output .= "<input type='checkbox' name='catering' value='1'> Catering Artikelgruppen verstecken<br>";
  $output .= "<br>";
  $output .= "<input type='submit' name='submit' value='Ausf&uuml;hren'>";
  $output .= "</form>";
}

$PAGE->render($output);
?>
