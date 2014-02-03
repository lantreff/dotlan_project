<?
$MODUL_NAME = "anwesenheit";
include_once("../../../global.php");
include("../functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Anwesenheit");
$event_id = $EVENT->next;
$tage = array("Mittwoch","Donnerstag","Freitag","Samstag","Sonntag","Montag");

if(!$DARF["edit"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

#######################################

// Anwesenheit eintragen
if($_POST["anwesenheit"]){
  foreach($_POST["data"] as $data){
    foreach($tage as $tag){
      $key = "event_id = '".$event_id."',";
      $key .= "tag = '".$tag."',";
      $key .= "user_id = '0',";
        
      $sql = "name = '".mysql_real_escape_string($data["name"])."',";
      $sql .= "abwesend = '0',";
      for($i=0; $i<24; $i++) $sql .= "ab_$i = '".($data[$tag][$i] ? '1' : '0')."',";
      $sql = substr($sql,0,-1);

      if(!empty($data["old_name"])) $DB->query("DELETE FROM project_anwesenheit WHERE ".implode(" AND ",explode(",",$key))." name = '".mysql_real_escape_string($data["old_name"])."'");
        
      $DB->query("INSERT INTO project_anwesenheit SET $key $sql ON DUPLICATE KEY UPDATE $sql");
    }
  }
  $DB->query("DELETE FROM project_anwesenheit WHERE ab_0 = '0' AND ab_1 = '0' AND ab_2 = '0' AND ab_3 = '0' AND ab_4 = '0' AND ab_5 = '0' AND ab_6 = '0' AND ab_7 = '0' AND ab_8 = '0' AND ab_9 = '0' AND ab_10 = '0' AND ab_11 = '0' AND ab_12 = '0' AND ab_13 = '0' AND ab_14 = '0' AND ab_15 = '0' AND ab_16 = '0' AND ab_17 = '0' AND ab_18 = '0' AND ab_19 = '0' AND ab_20 = '0' AND ab_21 = '0' AND ab_22 = '0' AND ab_23 = '0' AND abwesend = '0'"); // leere Datensaetze entfernen
}

#######################################

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

// CheckSubmit
$output .= "  function check_submit(){";
$output .= "    var inputs = document.getElementsByTagName('input');";
$output .= "    for(var i=0; i<inputs.length; i++){";
$output .= "      if(inputs[i].name.match(/\[name\]$/) && inputs[i].value == ''){";
$output .= "        alert('Die Namens-Felder duerfen nicht leer sein!');";
$output .= "        return false;";
$output .= "      }";
$output .= "    }";
$output .= "    return true";
$output .= "  }";

// AddEvent
$output .= "  function add_event(){";
$output .= "    var d = document.getElementById('d');";
$output .= "    var template = document.getElementById('template').innerHTML;";
$output .= "    var formular = document.getElementById('formular');";
$output .= "    template = template.replace(/##name##/g,'');";
$output .= "    template = template.replace(/##d##/g,d.value);";
$output .= "    formular.innerHTML += template;";
$output .= "    d.value = d.value+1;";
$output .= "  }";
$output .= "</script>";

// Events hinzufuegen
$output .= "Zum l&ouml;schen eines Events einfach alle Haken entfernen und speichern.<br>";
$output .= "<a href='#' onClick='add_event();'>Neues Event hinzuf&uuml;gen</a> | <a href='index.php'>zur&uuml;ck zur &Uuml;bersicht</a>";

// Template
$template = "<br><hr><br>";
$template .= "<input type='hidden' name='data[##d##][old_name]' value='##name##'>";
$template .= "<h3>Name: <input type='text' name='data[##d##][name]' value='##name##'></h3>";
$template .= "<table width='950'>";
$template .= "  <tr>";
$template .= "    <th class='forumhead'>Tag</th>";
for($i=0; $i<24; $i++) $template .= "<th class='forumhead' width='30' align='center' style='font-size: 8px;'>".$i."-".($i+1)."</th>";
$template .= "    <th class='forumhead' width='30' align='center' style='font-size: 8px;'>all</th>";
$template .= "  </tr>";
$z=0;
foreach($tage as $tag){
  $template .= "<tr class='msgrow".(($z%2)+1)."'>";
  $template .= "  <td>$tag</td>";
  $template .= "##checkboxes_$tag##";
  $template .= "  <td class='msgrow2' align='center'><input style='margin: 0px;' type='checkbox' onClick='check_all(\"data\\\[##d##\\\]\\\[$tag\\\]\");' id='data\\[##d##\\]\\[$tag\\]'></td>";
  $template .= "</tr>";
  $z++;
}
$template .= "</table>";

// Template fuer JS einbinden
$output .= "<div id='template' style='display: none;'>";
$tmp = $template;
foreach($tage as $tag){
$chkboxes = "";
for($i=0; $i<24; $i++) $chkboxes .= "<td align='center'><input style='margin: 0px;' type='checkbox' name='data[##d##][".$tag."][".$i."]'></td>";
  $tmp = str_replace("##checkboxes_$tag##",$chkboxes,$tmp);
}
$output .= $tmp;
$output .= "</div>";

// Events
$output .= "<form action='edit.php' method='POST'><div id='formular'>";
$d=0;
$query = $DB->query("SELECT * FROM project_anwesenheit WHERE event_id = '".$event_id."' AND user_id = '0' GROUP BY name ORDER BY name");
while($row = $DB->fetch_array($query)){
  $tmp = str_replace(array("##d##","##name##"),array($d,$row["name"]),$template);
  foreach($tage as $tag){
    $row2 = $DB->query_first("SELECT * FROM project_anwesenheit WHERE event_id = '".$event_id."' AND name = '".$row["name"]."' AND tag = '".$tag."' LIMIT 1");
    $chkboxes = "";
    for($i=0; $i<24; $i++) $chkboxes .= "<td align='center'><input style='margin: 0px;' type='checkbox' name='data[$d][".$tag."][".$i."]' ".($row2["ab_$i"] == 1 ? "checked='checked'" : "")."></td>";
    $tmp = str_replace("##checkboxes_$tag##",$chkboxes,$tmp);
  }
  $output .= $tmp;
  $d++;
}
$output .= "</div><br><hr><br>";
$output .= "<input type='hidden' name='d' id='d' value='$d'>";
$output .= "<center><input type='submit' value='speichern' name='anwesenheit' onClick='return check_submit();'></center>";
$output .= "</form>";

$PAGE->render($output);
?> 
