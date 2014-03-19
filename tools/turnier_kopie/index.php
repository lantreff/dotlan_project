<?php
$MODUL_NAME = "tools";
include_once("../../../../global.php");
require_once("../../functions.php");
require_once("../../config.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Turniere kopieren");

if(!$DARF["turnier_kopie"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

$event_id = $EVENT->next;

if(!$_POST["step2"] && !$_POST["kopieren"]){
  $events = array();
  $query = $DB->query("SELECT id, name FROM events ORDER BY id DESC");
  while($row = $DB->fetch_array($query)) $events[$row["id"]] = $row["name"];
  
  $output .= "<form action='index.php' method='POST'>";
  $output .= "<table>";
  $output .= "  <tr>";
  $output .= "    <th class='forumhead'>&nbsp;</th>";
  $output .= "    <th class='forumhead'>Quelle</th>";
  $output .= "    <th class='forumhead'>Ziel</th>";
  $output .= "  </tr>";
  $output .= "  <tr>";
  $output .= "    <td>Event:</td>";
  $output .= "    <td><select name='quell_event'>";
  foreach($events as $id => $name){
    if($id == ($event_id-1)) $selected = "selected='selected'";
    else $selected = "";
    $output .= "<option $selected value='$id'>$name</option>";
  }
  $output .= "    </td>";
  $output .= "    <td><select name='ziel_event'>";
  foreach($events as $id => $name){
    if($id == $event_id) $selected = "selected='selected'";
    else $selected = "";
    $output .= "<option $selected value='$id'>$name</option>";
  }
  $output .= "    </td>";
  $output .= "  </tr>";
  $output .= "  <tr>";
  $output .= "    <td>String im Namen ersetzen:</td>";
  $output .= "    <td><input type='text' name='quell_string'></td>";
  $output .= "    <td><input type='text' name='ziel_string'></td>";
  $output .= "  </tr>";
  $output .= "  <tr>";
  $output .= "    <td colspan='2' align='center'><input type='submit' name='step2' value='weiter'></td>";
  $output .= "  </tr>";
  $output .= "</table>";
  $output .= "</form>";
}else if($_POST["step2"]){
  $quell_event = mysql_real_escape_string($_POST["quell_event"]);
  $ziel_event = mysql_real_escape_string($_POST["ziel_event"]);
  $quell_string = mysql_real_escape_string($_POST["quell_string"]);
  $ziel_string = mysql_real_escape_string($_POST["ziel_string"]);

  if($quell_event == $ziel_event) $output .= "<b>Quelle und Ziel sind gleich ...</b>";
  else{
    $output .= "<script>";
    $output .= "  function check_all(){";
    $output .= "    boxes = document.getElementsByTagName(\"input\");";
    $output .= "    if(document.getElementById('box_checkall').checked == true){ ";
    $output .= "      var change = true; ";
    $output .= "    } else { ";
    $output .= "      var change = false; ";
    $output .= "    } ";
    $output .= "    for(var i=0; i<boxes.length; i++){ ";
    $output .= "      if(boxes[i].id.search(\"^chk_\") >= 0){ ";
    $output .= "        boxes[i].checked = change; ";
    $output .= "      }";
    $output .= "    }";
    $output .= "  }";
    $output .= "</script>";
  
    $output .= "<form action='index.php' method='POST'>";
    $output .= "<input type='hidden' name='quell_event' value='$quell_event'>";
    $output .= "<input type='hidden' name='ziel_event' value='$ziel_event'>";
    $output .= "<input type='hidden' name='quell_string' value='$quell_string'>";
    $output .= "<input type='hidden' name='ziel_string' value='$ziel_string'>";
    $output .= "<table>";
    $output .= "  <tr>";
    $output .= "    <th class='forumhead'>&nbsp;</th>";
    $output .= "    <th class='forumhead'>Quelle</th>";
    $output .= "    <th class='forumhead'>Ziel</th>";
    $output .= "  </tr>";
  
    $query = $DB->query("SELECT tid, tname FROM t_turnier WHERE teventid = '".$quell_event."'");
    while($row = $DB->fetch_array($query)){
      $output .= "<tr>";
      $output .= "  <td align='center'><input id='chk_".$row["tid"]."' type='checkbox' name='tids[]' value='".$row["tid"]."'></td>";
      $output .= "  <td>".$row["tname"]."</td>";
      $output .= "  <td>".str_replace($quell_string,$ziel_string,$row["tname"])."</td>";
      $output .= "</tr>";
    }
  
    $output .= "  <tr>";
    $output .= "    <td colspan=3 align='center'><a href='index.php'>zur&uuml;ck</a> <input type='submit' name='kopieren' value='kopieren'></td>";
    $output .= "  </tr>";
    $output .= "</table>";
    $output .= "</form>";
    $output .= "<input type='checkbox' id='box_checkall' onClick='check_all();'> Alle ausw&auml;hlen";
  }
}else if($_POST["kopieren"]){
  $quell_event = mysql_real_escape_string($_POST["quell_event"]);
  $ziel_event = mysql_real_escape_string($_POST["ziel_event"]);
  $quell_string = mysql_real_escape_string($_POST["quell_string"]);
  $ziel_string = mysql_real_escape_string($_POST["ziel_string"]);
  $tids = $_POST["tids"];

  if(!is_array($tids) || count($tids) < 1) $output .= "<b>Du hast keine Turniere selektiert.</b>";
  else{
    foreach($tids as $tid){
      $vals = $DB->query_first("SELECT tname, tstart FROM t_turnier WHERE tid = '$tid' LIMIT 1");

      $tname = str_replace($quell_string,$ziel_string,$vals["tname"]);
      $tmp = explode(" ",$vals["tstart"]);
      $turnierstart = date("Y-m-d")." ".$tmp[1];

      $DB->query("INSERT INTO `t_turnier` 
        (`tgroupid`, `tactive`, `topen`, `tclosed`, `tpause`, `teventid`, `tname`, `tplaytype`, 
         `tparams`, `tminanz`, `tmaxanz`, `tuserproteam`, `tmoreplayer`, `tlogo`, `tregeln`, `tinfotext`, 
         `tstart`, `troundtime`, `troundpause`, `tcheckin`, `tcheckintime`, `tautodefaultwin`, `tadultsonly`, `tpassword`, 
         `tnight`, `tnightbegin`, `tnightend`, `tdefmap`, `tadmins`, `tcoins`, `tcoinsreturn`
        ) SELECT 
         `tgroupid`, '0', '1', '0', `tpause`, '$ziel_event', '$tname', `tplaytype`, 
         `tparams`, `tminanz`, `tmaxanz`, `tuserproteam`, `tmoreplayer`, `tlogo`, `tregeln`, `tinfotext`, 
         '$turnierstart', `troundtime`, `troundpause`, `tcheckin`, `tcheckintime`, `tautodefaultwin`, `tadultsonly`, `tpassword`, 
         `tnight`, `tnightbegin`, `tnightend`, `tdefmap`, `tadmins`, `tcoins`, `tcoinsreturn` 
        FROM `t_turnier` WHERE tid = '$tid'");
      $new_tid = $DB->insert_id();

      $DB->query("INSERT INTO `t_ligasupport` (`tid`, `liga`, `game`) SELECT '$new_tid', `liga`, `game` FROM `t_ligasupport` WHERE tid = '$tid'");

      $output .= "<b>".$vals["tname"]."</b> kopiert.<br>";
    }
  }
}

$PAGE->render($output);
?>
