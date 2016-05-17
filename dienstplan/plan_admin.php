<?php
########################################################################
# Dienstplan Modul for dotlan             			                   	   #
#                                                                      #
# Copyright (C) 2013 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# Version 1.0                                                          #
########################################################################

$MODUL_NAME = "dienstplan";
include_once("../../../global.php");
include("../functions.php");
include("dienstplan_function.php");
//$output .= "TEST ".  $selectet_event_id;

include('header.php');

if(!$DARF["view"] ) $PAGE->error_die($HTML->gettemplate("error_nopermission"));
else
{// $module_admin_check

if (mysql_num_rows($DB->query("SELECT plan_name FROM project_dienstplan WHERE event_id = '".$selectet_event_id."'")) != 0)
{
$plan = mysql_real_escape_string($_POST["plan_name"]);
if(empty($plan)) $plan = mysql_real_escape_string($_GET["plan_name"]);
if(empty($plan)){
  $plan = mysql_result($DB->query("SELECT plan_name FROM project_dienstplan WHERE event_id = '".$selectet_event_id."' ORDER BY plan_name LIMIT 1"),0,"plan_name");
}
}
else{

$output .= "<h2>Kein Plan zum Event!</h2>";
}

# Zeile adden
if($_GET["a"] == "add"){
  $t = mysql_real_escape_string($_GET["tag"]);
  $s = mysql_real_escape_string($_GET["std"])+1;

  if(strlen($s) == 1) $s= "0$s";
	if($freeze == 1) $output .= $freeze_meldung;
	else $DB->query("UPDATE project_dienstplan SET id_$s= CONCAT(id_$s, ',-1') WHERE event_id = '".$selectet_event_id."' AND plan_name = '$plan' AND tag = '$t' LIMIT 1");
}

if(!empty($_POST["commit"])){
  for($i=0; $i<=23; $i++){
    if(is_array($_POST["freitag"][$i])){
      $new = "";
      foreach($_POST["freitag"][$i] as $val){
        if($val != 0) $new .= $val.",";
      }
      $_POST["freitag"][$i] = substr($new,0,-1);
    }

    if(is_array($_POST["samstag"][$i])){
      $new = "";
      foreach($_POST["samstag"][$i] as $val){
        if($val != 0) $new .= $val.",";
      }
      $_POST["samstag"][$i] = substr($new,0,-1);
    }

    if(is_array($_POST["sonntag"][$i])){
      $new = "";
      foreach($_POST["sonntag"][$i] as $val){
        if($val != 0) $new .= $val.",";
      }
      $_POST["sonntag"][$i] = substr($new,0,-1);
    }
  }

	if($freeze == 1) $output .= $freeze_meldung;
	else
	{
    $DB->query("UPDATE project_dienstplan SET id_01 = '".mysql_real_escape_string($_POST["freitag"][0])."',
   id_02 = '".mysql_real_escape_string($_POST["freitag"][1])."',
   id_03 = '".mysql_real_escape_string($_POST["freitag"][2])."',
   id_04 = '".mysql_real_escape_string($_POST["freitag"][3])."',
   id_05 = '".mysql_real_escape_string($_POST["freitag"][4])."',
   id_06 = '".mysql_real_escape_string($_POST["freitag"][5])."',
   id_07 = '".mysql_real_escape_string($_POST["freitag"][6])."',
   id_08 = '".mysql_real_escape_string($_POST["freitag"][7])."',
   id_09 = '".mysql_real_escape_string($_POST["freitag"][8])."',
   id_10 = '".mysql_real_escape_string($_POST["freitag"][9])."',
   id_11 = '".mysql_real_escape_string($_POST["freitag"][10])."',
   id_12 = '".mysql_real_escape_string($_POST["freitag"][11])."',
   id_13 = '".mysql_real_escape_string($_POST["freitag"][12])."',
   id_14 = '".mysql_real_escape_string($_POST["freitag"][13])."',
   id_15 = '".mysql_real_escape_string($_POST["freitag"][14])."',
   id_16 = '".mysql_real_escape_string($_POST["freitag"][15])."',
   id_17 = '".mysql_real_escape_string($_POST["freitag"][16])."',
   id_18 = '".mysql_real_escape_string($_POST["freitag"][17])."',
   id_19 = '".mysql_real_escape_string($_POST["freitag"][18])."',
   id_20 = '".mysql_real_escape_string($_POST["freitag"][19])."',
   id_21 = '".mysql_real_escape_string($_POST["freitag"][20])."',
   id_22 = '".mysql_real_escape_string($_POST["freitag"][21])."',
   id_23 = '".mysql_real_escape_string($_POST["freitag"][22])."',
   id_24 = '".mysql_real_escape_string($_POST["freitag"][23])."' WHERE tag = '1' AND plan_name = '$plan' AND event_id = '".$selectet_event_id."'");

    $DB->query("UPDATE project_dienstplan SET id_01 = '".mysql_real_escape_string($_POST["samstag"][0])."',
   id_02 = '".mysql_real_escape_string($_POST["samstag"][1])."',
   id_03 = '".mysql_real_escape_string($_POST["samstag"][2])."',
   id_04 = '".mysql_real_escape_string($_POST["samstag"][3])."',
   id_05 = '".mysql_real_escape_string($_POST["samstag"][4])."',
   id_06 = '".mysql_real_escape_string($_POST["samstag"][5])."',
   id_07 = '".mysql_real_escape_string($_POST["samstag"][6])."',
   id_08 = '".mysql_real_escape_string($_POST["samstag"][7])."',
   id_09 = '".mysql_real_escape_string($_POST["samstag"][8])."',
   id_10 = '".mysql_real_escape_string($_POST["samstag"][9])."',
   id_11 = '".mysql_real_escape_string($_POST["samstag"][10])."',
   id_12 = '".mysql_real_escape_string($_POST["samstag"][11])."',
   id_13 = '".mysql_real_escape_string($_POST["samstag"][12])."',
   id_14 = '".mysql_real_escape_string($_POST["samstag"][13])."',
   id_15 = '".mysql_real_escape_string($_POST["samstag"][14])."',
   id_16 = '".mysql_real_escape_string($_POST["samstag"][15])."',
   id_17 = '".mysql_real_escape_string($_POST["samstag"][16])."',
   id_18 = '".mysql_real_escape_string($_POST["samstag"][17])."',
   id_19 = '".mysql_real_escape_string($_POST["samstag"][18])."',
   id_20 = '".mysql_real_escape_string($_POST["samstag"][19])."',
   id_21 = '".mysql_real_escape_string($_POST["samstag"][20])."',
   id_22 = '".mysql_real_escape_string($_POST["samstag"][21])."',
   id_23 = '".mysql_real_escape_string($_POST["samstag"][22])."',
   id_24 = '".mysql_real_escape_string($_POST["samstag"][23])."' WHERE tag = '2' AND plan_name = '$plan' AND event_id = '".$selectet_event_id."'");

    $DB->query("UPDATE project_dienstplan SET id_01 = '".mysql_real_escape_string($_POST["sonntag"][0])."',
   id_02 = '".mysql_real_escape_string($_POST["sonntag"][1])."',
   id_03 = '".mysql_real_escape_string($_POST["sonntag"][2])."',
   id_04 = '".mysql_real_escape_string($_POST["sonntag"][3])."',
   id_05 = '".mysql_real_escape_string($_POST["sonntag"][4])."',
   id_06 = '".mysql_real_escape_string($_POST["sonntag"][5])."',
   id_07 = '".mysql_real_escape_string($_POST["sonntag"][6])."',
   id_08 = '".mysql_real_escape_string($_POST["sonntag"][7])."',
   id_09 = '".mysql_real_escape_string($_POST["sonntag"][8])."',
   id_10 = '".mysql_real_escape_string($_POST["sonntag"][9])."',
   id_11 = '".mysql_real_escape_string($_POST["sonntag"][10])."',
   id_12 = '".mysql_real_escape_string($_POST["sonntag"][11])."',
   id_13 = '".mysql_real_escape_string($_POST["sonntag"][12])."',
   id_14 = '".mysql_real_escape_string($_POST["sonntag"][13])."',
   id_15 = '".mysql_real_escape_string($_POST["sonntag"][14])."',
   id_16 = '".mysql_real_escape_string($_POST["sonntag"][15])."',
   id_17 = '".mysql_real_escape_string($_POST["sonntag"][16])."',
   id_18 = '".mysql_real_escape_string($_POST["sonntag"][17])."',
   id_19 = '".mysql_real_escape_string($_POST["sonntag"][18])."',
   id_20 = '".mysql_real_escape_string($_POST["sonntag"][19])."',
   id_21 = '".mysql_real_escape_string($_POST["sonntag"][20])."',
   id_22 = '".mysql_real_escape_string($_POST["sonntag"][21])."',
   id_23 = '".mysql_real_escape_string($_POST["sonntag"][22])."',
   id_24 = '".mysql_real_escape_string($_POST["sonntag"][23])."' WHERE tag = '3' AND plan_name = '$plan' AND event_id = '".$selectet_event_id."'");
	}
}

$tag = array();
$query = $DB->query("SELECT * FROM project_dienstplan WHERE plan_name = '$plan' AND event_id = '".$selectet_event_id."'");
while($row = $DB->fetch_array($query)){
  $tag1 = $row["tag"];
  $tag[$tag1][0] = $row["id_01"];
  $tag[$tag1][1] = $row["id_02"];
  $tag[$tag1][2] = $row["id_03"];
  $tag[$tag1][3] = $row["id_04"];
  $tag[$tag1][4] = $row["id_05"];
  $tag[$tag1][5] = $row["id_06"];
  $tag[$tag1][6] = $row["id_07"];
  $tag[$tag1][7] = $row["id_08"];
  $tag[$tag1][8] = $row["id_09"];
  $tag[$tag1][9] = $row["id_10"];
  $tag[$tag1][10] = $row["id_11"];
  $tag[$tag1][11] = $row["id_12"];
  $tag[$tag1][12] = $row["id_13"];
  $tag[$tag1][13] = $row["id_14"];
  $tag[$tag1][14] = $row["id_15"];
  $tag[$tag1][15] = $row["id_16"];
  $tag[$tag1][16] = $row["id_17"];
  $tag[$tag1][17] = $row["id_18"];
  $tag[$tag1][18] = $row["id_19"];
  $tag[$tag1][19] = $row["id_20"];
  $tag[$tag1][20] = $row["id_21"];
  $tag[$tag1][21] = $row["id_22"];
  $tag[$tag1][22] = $row["id_23"];
  $tag[$tag1][23] = $row["id_24"];
}

function create_user_list($id = -1){
global $DB;
  $return = "<option value='-1' ";
  if($id == -1) $return .= "selected";
  $return .= ">-- bitte zuweisen --</option>";
  $return .= "<option value='0' ";
  if($id == 0) $return .= "selected";
  $return .= ">-- niemand --</option>";

 $query = $DB->query("SELECT nick, vorname, nachname, u.id AS id FROM user AS u, user_orga AS o WHERE o.user_id = u.id ORDER BY  `u`.`vorname` ASC");
  while($row = $DB->fetch_array($query)){
    if($row['id'] == $id) $select = "selected";
    $return .= "<option value='".$row["id"]."' $select>".$row["vorname"]." ".substr($row["nachname"],0,1).". (".$row["nick"].")</option>";
    unset($select);
  }
  return $return;
}


$output .="<table width='750' class='maincontent'>";

 // Maintable do not edit html upon // 

    $output .="<tr>";
    $output .="<td>";
     $output .="<table width='750'>";
	$output .="<tr>";
          $output .="<td class='maintd'><b>Zeitpl&auml;ne zu Projekt ".$_SESSION['projekt_name'].":</b>";
$output .="<form action='plan_admin.php?event=".$selectet_event_id."' method='POST'>";
$output .="<select name='plan_name'>";

  $query = $DB->query("SELECT plan_name FROM project_dienstplan WHERE event_id = '".$selectet_event_id."' GROUP BY (plan_name) ORDER BY plan_name");
  while($row = $DB->fetch_row($query)){
    $output .="<option value='".$row[0]."'";
    if($plan == $row[0]) $output .=" selected";
    $output .=">".$row[0]."</option>";
  }

$output .="</select>";
$output .="<input type='submit' name='change' value='ausw&auml;hlen'>";
$output .="</form>";
          $output .="</td>";
        $output .="</tr>";

if(!empty($meldung)) $output .="<tr><td>".$meldung."</td></tr>";

        $output .="<tr>";
          $output .="<td>";

$output .="<form action='plan_admin.php' method='post'>";
$output .="<table width='750'>";
  $output .="<tr>";
    $output .="<td  width='100'>&nbsp;</td>";
    $output .="<td  width='202'><b>Freitag</b></td>";
    $output .="<td  width='202'><b>Samstag</b></td>";
    $output .="<td  width='202'><b>Sonntag</b></td>";
  $output .="</tr>";


for($i=0;$i<24;$i++){
  if(strlen($i) == 1) $std = "0".$i;
  else $std = $i;
  $std1 = $std + 1;
  if(strlen($std1) == 1) $std1 = "0".$std1;

  $equal = bcmod($i, 2);
  if ($equal == 0) {
    $class= 'class="msgrow1" valign="top"';
  } else {
    $class= 'class="msgrow2" valign="top"';
  }

  if($tag[1][$i] == 0) $color_freitag = "#0000FF";
  elseif($tag[1][$i] > 0) $color_freitag = "#00FF00";
  else $color_freitag = "#FF0000";

  if($tag[2][$i] == 0) $color_samstag = "#0000FF";
  elseif($tag[2][$i] > 0) $color_samstag = "#00FF00";
  else $color_samstag = "#FF0000";

  if($tag[3][$i] == 0) $color_sonntag = "#0000FF";
  elseif($tag[3][$i] > 0) $color_sonntag = "#00FF00";
  else $color_sonntag = "#FF0000";


  $output .="<tr>";
    $output .="<td $class><b>".$std.":00 - ".$std1.":00</b></td>";

  $output .="<td $class>";
  if(strstr($tag[1][$i],",")){
    $bla = explode(",",$tag[1][$i]);
    $x=0;
    foreach($bla as $blubb){ 
      if($blubb == 0) $color = "#0000FF";
      elseif($blubb > 0) $color = "#00FF00";
      else $color = "#FF0000";

      $output .="<select name='freitag[$i][$x]' style='background-color: $color'>".create_user_list($blubb)."</select>"; 
      $x++; 
    }
  }else $output .="<select name='freitag[$i]' style='background-color: $color_freitag'>".create_user_list($tag[1][$i])."</select>";
  $output .="<a href='plan_admin.php?a=add&plan_name=$plan&tag=1&std=$i'><img align='right' title='Freitag $i:00 - ".($i+ 1).":00 --> Zeile hinzuf&uuml;gen' src='../images/16/db_add.png'></a>";
  $output .="</td>";

  $output .="<td $class>";
  if(strstr($tag[2][$i],",")){
    $bla = explode(",",$tag[2][$i]);
    $x=0;
    foreach($bla as $blubb){ 
      if($blubb == 0) $color = "#0000FF";
      elseif($blubb > 0) $color = "#00FF00";
      else $color = "#FF0000";

      $output .="<select name='samstag[$i][$x]' style='background-color: $color'>".create_user_list($blubb)."</select>"; 
      $x++; 
    }
  }else $output .="<select name='samstag[$i]' style='background-color: $color_samstag'>".create_user_list($tag[2][$i])."</select>";
  $output .="<a href='plan_admin.php?a=add&plan_name=$plan&tag=2&std=$i'><img align='right' title='Samstag $i:00 - ".($i+ 1).":00 --> Zeile hinzuf&uuml;gen' src='../images/16/db_add.png'></a>";
  $output .="</td>";

  $output .="<td $class>";
  if(strstr($tag[3][$i],",")){
    $bla = explode(",",$tag[3][$i]);
    $x=0;
    foreach($bla as $blubb){ 
      if($blubb == 0) $color = "#0000FF";
      elseif($blubb > 0) $color = "#00FF00";
      else $color = "#FF0000";

      $output .="<select name='sonntag[$i][$x]' style='background-color: $color'>".create_user_list($blubb)."</select>"; 
      $x++; 
    }
  }else 
  $output .="<select name='sonntag[$i]' style='background-color: $color_sonntag'>".create_user_list($tag[3][$i])."</select>";
  $output .="<a href='plan_admin.php?a=add&plan_name=$plan&tag=3&std=$i'><img align='right' title='Sonntag $i:00 - ".($i+ 1).":00 --> Zeile hinzuf&uuml;gen' src='../images/16/db_add.png'></a>";
  $output .="</td>";
  $output .="</tr>";
}

$output .="</table>";
$output .="<input type='hidden' name='plan_name' value='".$plan."'>";
$output .="<input type='submit' name='commit' value='speichern'>";
$output .="</form>";

          $output .="</td>";
        $output .="</tr>";
     $output .="</table>";
    $output .="</td>";
    $output .="</tr>";
 // Maintable do not edit html below // 
    $output .="</tr>";
    $output .="</table>";
}
$PAGE->render($output);
?>