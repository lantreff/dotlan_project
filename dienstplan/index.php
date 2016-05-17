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
include("./dienstplan_function.php");
//$output .= "TEST ".  $event_id;

include('header.php');

if(!$DARF["view"] ) $PAGE->error_die($HTML->gettemplate("error_nopermission"));
else
{// $module_admin_check



if (mysql_num_rows($DB->query("SELECT plan_name FROM project_dienstplan WHERE event_id = '".$selectet_event_id."'")) != 0)
{
$plan = mysql_real_escape_string($_POST["plan_name"]);
if(empty($plan)) $plan = mysql_real_escape_string($_GET["plan"]);
if(empty($plan)){
  $plan = mysql_result($DB->query("SELECT plan_name FROM project_dienstplan WHERE event_id = '".$selectet_event_id."' ORDER BY plan_name LIMIT 1"),0,"plan_name");
}
}
else{

$output .= "<h2>Kein Plan zum Event!</h2>";
}

if($_GET["a"] == "add"){
  $id = mysql_real_escape_string($_GET["std"]) + 1;
  if(strlen($id) == 1) $id = "0".$id;

  if($_GET["x"] > -1){
    $alt = mysql_result(mysql_query("SELECT id_$id FROM project_dienstplan WHERE tag = '".mysql_real_escape_string($_GET["tag"])."' AND plan_name = '$plan' AND event_id = '".$selectet_event_id."'"),0,"id_$id");

    $bla = explode(",",$alt);
    $bla[$_GET["x"]] = mysql_real_escape_string($user_id);
    $neu = implode(",",$bla);
  }else $neu = mysql_real_escape_string($user_id);

  $sql = "UPDATE project_dienstplan SET ";
  $sql .= "id_".$id." = '".$neu."' ";
  $sql .= " WHERE tag = '".mysql_real_escape_string($_GET["tag"])."' AND plan_name = '$plan' AND event_id = '".$selectet_event_id."'";

	if($freeze == 1) $output .= $freeze_meldung;
	else $DB->query($sql);
}

if($_GET["a"] == "del"){
  $id = mysql_real_escape_string($_GET["std"]) + 1;
  if(strlen($id) == 1) $id = "0".$id;

  if($_GET["x"] > -1){
    $alt = mysql_result(mysql_query("SELECT id_$id FROM project_dienstplan WHERE tag = '".mysql_real_escape_string($_GET["tag"])."' AND plan_name = '$plan' AND event_id = '".$selectet_event_id."'"),0,"id_$id");

    $bla = explode(",",$alt);
    $bla[$_GET["x"]] = "-1";
    $neu = implode(",",$bla);
  }else $neu = "-1";

  $sql = "UPDATE project_dienstplan SET ";
  $sql .= "id_".$id." = '$neu' ";
  $sql .= " WHERE tag = '".mysql_real_escape_string($_GET["tag"])."' AND plan_name = '$plan' AND event_id = '".$selectet_event_id."'";


	if($freeze == 1) $output .= $freeze_meldung;
	else $DB->query($sql);
}

$tag = array();
$query = $DB->query("SELECT * FROM project_dienstplan WHERE plan_name = '$plan' AND event_id = '".$selectet_event_id."'");
while($row =  $DB->fetch_array($query)){

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

function doppeleitrag_check($selectet_event_id){
global $DB;
  $doppelt = array();
  $d=0;

  for($tag=1;$tag<=3;$tag++){
	$query = $DB->query("SELECT * FROM project_dienstplan WHERE event_id = '".$selectet_event_id."' AND tag = '".$tag."' AND doppelt_erlaubt = 0");
     while($row =  mysql_fetch_assoc($query)){
      for($std=1;$std<=24;$std++){
        if(strlen($std) == 1) $id = "0$std";
        else $id = $std;

        if(strstr($row["id_$id"],",")){
          $bla = explode(",",$row["id_$id"]);
          foreach($bla as $blubb){
            if($blubb == 0 || $blubb == -1) continue;
            if(!empty($array[$tag][$std][$blubb])){
              $array[$tag][$std][$blubb] = $array[$tag][$std][$blubb].", ".$row["plan_name"];
              $doppelt[$d]['tag'] = $tag;
              $doppelt[$d]['std'] = $std;
              $doppelt[$d]['uid'] = $blubb;
              $d++;
            }else $array[$tag][$std][$blubb] = $row["plan_name"];
          }
        }else{
          if($row["id_$id"] == 0 || $row["id_$id"] == -1) continue;
          if(!empty($array[$tag][$std][$row["id_$id"]])){
            $array[$tag][$std][$row["id_".$id]] = $array[$tag][$std][$row["id_".$id]].", ".$row["plan_name"];
            $doppelt[$d]['tag'] = $tag;
            $doppelt[$d]['std'] = $std;
            $doppelt[$d]['uid'] = $row["id_$id"];
            $d++;
          }else $array[$tag][$std][$row["id_".$id]] = $row["plan_name"];
        }
      }
    }
  }

  $output .="<table width='800'>
          <tr>
            <td class=\"maintd\" colspan='4'><b>Leute, die zur gleichen Zeit in zwei Pl&auml;nen stehen:</b></td>
          </tr>";
  $output .="  <tr>
            <td class='msghead'><b>Orga</b></td>
            <td class='msghead' width='70'><b>Tag</b></td>
            <td class='msghead' width='100'><b>Schicht</b></td>
            <td class='msghead' width='400'><b>Pl&auml;ne</b></td>
          </tr>";

  $i=0;
  foreach($doppelt as $dd){
    $equal = bcmod($i, 2);
    if ($equal == 0) {
      $class= 'class="msgrow1"';
    } else {
      $class= 'class="msgrow2"';
    }
    $i++;

    $query = $DB->query("SELECT vorname, nachname, nick FROM user WHERE id = '".$dd["uid"]."' LIMIT 1");
    $orga = mysql_result($query,0,"vorname")." ".substr(mysql_result($query,0,"nachname"),0,1).". (".mysql_result($query,0,"nick").")";

    if($dd["tag"] == 1) $tag = "Freitag";
    elseif($dd["tag"] == 2) $tag = "Samstag";
    else $tag = "Sonntag";

    $tmp = ($dd["std"] - 1);
    if(strlen($tmp) == 1) $std = "0$tmp:00 - ";
    else $std = $tmp.":00 - ";
    $tmp = $dd["std"];
    if(strlen($tmp) == 1) $std .= "0$tmp:00";
    else $std .= $tmp.":00";

    $output .="<tr>";
    $output .="  <td $class>".$orga."</td>";
    $output .="  <td $class>".$tag."</td>";
    $output .="  <td $class>".$std."</td>";
    $output .="  <td $class>".$array[$dd["tag"]][$dd["std"]][$dd["uid"]]."</td>";
    $output .="</tr>";
  }

  $output .="</table>";

}

 $output .="<table class='maincontent'>";

// Maintable do not edit html upon //


 $output .="<tr>";
 $output .="<td>";
 $output .="<table>";
 $output .="<tr>";
 $output .="<td class='maintd'>";
 $output .="<form action='index.php?event=".$selectet_event_id."' method='POST'>";
 $output .="<select name='plan_name'>";

  $query = $DB->query("SELECT plan_name FROM project_dienstplan WHERE event_id = '".$selectet_event_id."' GROUP BY (plan_name) ORDER BY plan_name");
  while($row = $DB->fetch_array($query)){
    $output .="<option value='".$row[0]."'";
    if($plan == $row[0]) $output .=" selected";
    $output .=">".$row[0]."</option>";
  }



 $output .="</select>";
  $output .= '
<script type="text/javascript">
function FensterOeffnen (Adresse) {
  MeinFenster = window.open(Adresse, "Zweitfenster", "width=850,height=600,left=10,top=10");
  MeinFenster.focus();
 }
</script>
';


 $output .="<input type='submit' name='change' value='ausw&auml;hlen'>";
 $output .=" <a href='plan_csv_export.php?plan=".$plan."'><img width='16' height='16' title='CSV Export' alt='' src='../images/22/csv.png'></a>";
 $output .=" <a href='plan_druck.php?plan=".$plan."&event=".$selectet_event_id."' onclick='FensterOeffnen(this.href); return false '><img width='16' title='Drucken' height='16' alt='' src='/images/admin/icon_print.gif'></a> ";
 $output .="</form>";
 $output .="</td>";
	$output .="</tr>";
	$output .="<tr>";
 $output .="<td>";

 //$output .="<form action='index.php' method='post'>";
 $output .="<table width='850'>";
   $output .="<tr>";
     $output .="<td class='msghead' width='100'>&nbsp;</td>";
     $output .="<td class='msghead' width='250'><b>Freitag</b></td>";
     $output .="<td class='msghead' width='250'><b>Samstag</b></td>";
     $output .="<td class='msghead' width='250'><b>Sonntag</b></td>";
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

  if(strstr($tag[1][$i],",")){
    $bla = explode(",",$tag[1][$i]);
    $x=1;
    foreach($bla as $blubb){
      if($blubb == "-1") $x=0;
    }
    if($x == 1) $color_freitag = "#00FF00";
    else $color_freitag = "#FF0000";
  }else{
    if($tag[1][$i] == 0) $color_freitag = "#0000FF";
    elseif($tag[1][$i] > 0) $color_freitag = "#00FF00";
    else $color_freitag = "#FF0000";
  }

  if(strstr($tag[2][$i],",")){
    $bla = explode(",",$tag[2][$i]);
    $x=1;
    foreach($bla as $blubb){
      if($blubb == "-1") $x=0;
    }
    if($x == 1) $color_samstag = "#00FF00";
    else $color_samstag = "#FF0000";
  }else{
    if($tag[2][$i] == 0) $color_samstag = "#0000FF";
    elseif($tag[2][$i] > 0) $color_samstag = "#00FF00";
    else $color_samstag = "#FF0000";
  }

  if(strstr($tag[3][$i],",")){
    $bla = explode(",",$tag[3][$i]);
    $x=1;
    foreach($bla as $blubb){
      if($blubb == "-1") $x=0;
    }
    if($x == 1) $color_sonntag = "#00FF00";
    else $color_sonntag = "#FF0000";
  }else{
    if($tag[3][$i] == 0) $color_sonntag = "#0000FF";
    elseif($tag[3][$i] > 0) $color_sonntag = "#00FF00";
    else $color_sonntag = "#FF0000";
  }

  $output .="
  <tr>
    <td $class><b>".$std.":00 - ".$std1.":00</b></td>
    <td $class style='background-color: $color_freitag'>";

  if(strstr($tag[1][$i],",")){
    $bla = explode(",",$tag[1][$i]);
    $x=0;
    foreach($bla as $blubb){
      if($blubb == -1) $output .="<a href='index.php?plan=$plan&tag=1&std=$i&x=$x&a=add'>noch frei - hier klicken</a><br>";
      else{
        $query = $DB->query("SELECT id, vorname, nachname, nick FROM user WHERE id = '".$blubb."' LIMIT 1");
        $output .="<div style='width: 100%; height: 100%; background-color: #00FF00'>";
        $output .= mysql_result($query,0,"vorname")." ".substr(mysql_result($query,0,"nachname"),0,1).". (".mysql_result($query,0,"nick").")";

        if(mysql_result($query,0,"id") == $user_id) $output .=" - <a href='index.php?plan=$plan&tag=1&std=$i&x=$x&a=del'>(l&ouml;schen)</a><br>";
        $output .="</div>";
      }
      $x++;
    }
  }else{
    if($tag[1][$i] == -1) $output .="<a href='index.php?plan=$plan&tag=1&std=$i&a=add'>noch frei - hier klicken</a>";
    elseif($tag[1][$i] == 0) $output .="-- niemand --";
    else{
      $query = $DB->query("SELECT id, vorname, nachname, nick FROM user WHERE id = '".$tag[1][$i]."' LIMIT 1");
      $output .= mysql_result($query,0,"vorname")." ".substr(mysql_result($query,0,"nachname"),0,1).". (".mysql_result($query,0,"nick").")";

      if(mysql_result($query,0,"id") == $user_id) $output .=" - <a href='index.php?plan=$plan&tag=1&std=$i&a=del'>(l&ouml;schen)</a>";
    }
  }

  $output .="</td>
    <td $class style='background-color: $color_samstag'>";

  if(strstr($tag[2][$i],",")){
    $bla = explode(",",$tag[2][$i]);
    $x=0;
    foreach($bla as $blubb){
      if($blubb == -1) $output .="<a href='index.php?plan=$plan&tag=2&std=$i&x=$x&a=add'>noch frei - hier klicken</a><br>";
      else{
        $query = $DB->query("SELECT id, vorname, nachname, nick FROM user WHERE id = '".$blubb."' LIMIT 1");
        $output .="<div style='width: 100%; height: 100%; background-color: #00FF00'>";
        $output .= mysql_result($query,0,"vorname")." ".substr(mysql_result($query,0,"nachname"),0,1).". (".mysql_result($query,0,"nick").")";

        if(mysql_result($query,0,"id") == $user_id) $output .=" - <a href='index.php?plan=$plan&tag=2&std=$i&x=$x&a=del'>(l&ouml;schen)</a><br>";
        $output .="</div>";
      }
      $x++;
    }
  }else{
    if($tag[2][$i] == -1) $output .="<a href='index.php?plan=$plan&tag=2&std=$i&a=add'>noch frei - hier klicken</a>";
    elseif($tag[2][$i] == 0) $output .="-- niemand --";
    else{
      $query = $DB->query("SELECT id, vorname, nachname, nick FROM user WHERE id = '".$tag[2][$i]."' LIMIT 1");
      $output .= mysql_result($query,0,"vorname")." ".substr(mysql_result($query,0,"nachname"),0,1).". (".mysql_result($query,0,"nick").")";

      if(mysql_result($query,0,"id") == $user_id) $output .=" - <a href='index.php?plan=$plan&tag=2&std=$i&a=del'>(l&ouml;schen)</a>";
    }
  }

  $output .="</td>
    <td $class style='background-color: $color_sonntag'>";

  if(strstr($tag[3][$i],",")){
    $bla = explode(",",$tag[3][$i]);
    $x=0;
    foreach($bla as $blubb){
      if($blubb == -1) $output .="<a href='index.php?plan=$plan&tag=3&std=$i&x=$x&a=add'>noch frei - hier klicken</a><br>";
      else{
        $query = $DB->query("SELECT id, vorname, nachname, nick FROM user WHERE id = '".$blubb."' LIMIT 1");
        $output .="<div style='width: 100%; height: 100%; background-color: #00FF00'>";
         $output .= mysql_result($query,0,"vorname")." ".substr(mysql_result($query,0,"nachname"),0,1).". (".mysql_result($query,0,"nick").")";

        if(mysql_result($query,0,"id") == $user_id) $output .=" - <a href='index.php?plan=$plan&tag=3&std=$i&x=$x&a=del'>(l&ouml;schen)</a><br>";
        $output .="</div>";
      }
      $x++;
    }
  }else{
    if($tag[3][$i] == -1) $output .="<a href='index.php?plan=$plan&tag=3&std=$i&a=add'>noch frei - hier klicken</a>";
    elseif($tag[3][$i] == 0) $output .="-- niemand --";
    else{
      $query = $DB->query("SELECT id, vorname, nachname, nick FROM user WHERE id = '".$tag[3][$i]."' LIMIT 1");
       $output .= mysql_result($query,0,"vorname")." ".substr(mysql_result($query,0,"nachname"),0,1).". (".mysql_result($query,0,"nick").")";

      if(mysql_result($query,0,"id") == $user_id) $output .=" - <a href='index.php?plan=$plan&tag=3&std=$i&a=del'>(l&ouml;schen)</a>";
    }
  }

  $output .="</td>";
  $output .="</tr>";
}

 $output .="</table>";
 //$output .="<input type='hidden' name='plan_name' value='".$plan."'>";
 //$output .="<input type='submit' name='commit' value='speichern'>";
 //$output .="</form>";

 $output .="</td>";
 $output .="</tr>";
 $output .="</table>";
 $output .="<br><hr>";


$doppelt = array();
  $d=0;

  for($tag=1;$tag<=3;$tag++){
	$query = $DB->query("SELECT * FROM project_dienstplan WHERE event_id = '".$selectet_event_id."' AND tag = '".$tag."' AND doppelt_erlaubt = 0");
     while($row =  mysql_fetch_assoc($query)){
      for($std=1;$std<=24;$std++){
        if(strlen($std) == 1) $id = "0$std";
        else $id = $std;

        if(strstr($row["id_$id"],",")){
          $bla = explode(",",$row["id_$id"]);
          foreach($bla as $blubb){
            if($blubb == 0 || $blubb == -1) continue;
            if(!empty($array[$tag][$std][$blubb])){
              $array[$tag][$std][$blubb] = $array[$tag][$std][$blubb].", ".$row["plan_name"];
              $doppelt[$d]['tag'] = $tag;
              $doppelt[$d]['std'] = $std;
              $doppelt[$d]['uid'] = $blubb;
              $d++;
            }else $array[$tag][$std][$blubb] = $row["plan_name"];
          }
        }else{
          if($row["id_$id"] == 0 || $row["id_$id"] == -1) continue;
          if(!empty($array[$tag][$std][$row["id_$id"]])){
            $array[$tag][$std][$row["id_".$id]] = $array[$tag][$std][$row["id_".$id]].", ".$row["plan_name"];
            $doppelt[$d]['tag'] = $tag;
            $doppelt[$d]['std'] = $std;
            $doppelt[$d]['uid'] = $row["id_$id"];
            $d++;
          }else $array[$tag][$std][$row["id_".$id]] = $row["plan_name"];
        }
      }
    }
  }

  $output .="<table width='800'>
          <tr>
            <td class=\"maintd\" colspan='4'><b>Leute, die zur gleichen Zeit in zwei Pl&auml;nen stehen:</b></td>
          </tr>";


if($doppelt)
{

  $output .="  <tr>
            <td class='msghead'><b>Orga</b></td>
            <td class='msghead' width='70'><b>Tag</b></td>
            <td class='msghead' width='100'><b>Schicht</b></td>
            <td class='msghead' width='400'><b>Pl&auml;ne</b></td>
          </tr>";

  $i=0;
  foreach($doppelt as $dd){
    $equal = bcmod($i, 2);
 if ($equal == 0) {
    $class= 'class="msgrow1" valign="top"';
  } else {
    $class= 'class="msgrow2" valign="top"';
  }
    $i++;

    $query = $DB->query("SELECT vorname, nachname, nick FROM user WHERE id = '".$dd["uid"]."' LIMIT 1");
    $orga = mysql_result($query,0,"vorname")." ".substr(mysql_result($query,0,"nachname"),0,1).". (".mysql_result($query,0,"nick").")";

    if($dd["tag"] == 1) $tag = "Freitag";
    elseif($dd["tag"] == 2) $tag = "Samstag";
    else $tag = "Sonntag";

    $tmp = ($dd["std"] - 1);
    if(strlen($tmp) == 1) $std = "0$tmp:00 - ";
    else $std = $tmp.":00 - ";
    $tmp = $dd["std"];
    if(strlen($tmp) == 1) $std .= "0$tmp:00";
    else $std .= $tmp.":00";

    $output .="<tr>";
    $output .="  <td $class>".$orga."</td>";
    $output .="  <td $class>".$tag."</td>";
    $output .="  <td $class>".$std."</td>";
    $output .="  <td $class>".$array[$dd["tag"]][$dd["std"]][$dd["uid"]]."</td>";
    $output .="</tr>";
  }
}
  $output .="</table>";


 $output .="<hr><br>";


 $output .="<table width='800'>
          <tr>
            <td class=\"maintd\"><b>Leute, die nicht im Dienstplan stehen:</b></td>
          </tr>";

  $userids = array();
  $in_plan = array();
  $query = $DB->query("SELECT * FROM project_dienstplan WHERE event_id = '".$selectet_event_id."'");
  if(mysql_num_rows($query) != 0)
  {
  while($row = $DB->fetch_array($query)){
    for($i=1;$i<=24;$i++){
      if(strlen($i) == 1) $x = "0".$i;
      else $x = $i;

      if(stristr($row["id_".$x],",")){
        $ids = explode(",",$row["id_".$x]);
        foreach($ids as $id) $in_plan[] = "'".$id."'";
      }else $in_plan[] = "'".$row["id_".$x]."'";
    }
  }
$output .="  <tr>
            <td class='msghead'><b>Orga</b></td>
          </tr>";
  $i=0;
  //$query = $DB->query("SELECT id, nick, vorname, nachname FROM user WHERE id NOT IN (".implode(",",$in_plan).") ");
  $query = $DB->query("SELECT nick, vorname, nachname, u.id AS id FROM user AS u, user_orga AS o WHERE o.user_id = u.id AND o.user_id NOT IN (".implode(",",$in_plan).") ORDER BY  `u`.`vorname` ASC");
  while($row = @$DB->fetch_array($query)){
    $equal = bcmod($i, 2);
  if ($equal == 0) {
    $class= 'class="msgrow1" valign="top"';
  } else {
    $class= 'class="msgrow2" valign="top"';
  }
    $i++;

    $userids[] = "users[]=".$row["id"];
    $output .="<tr>";
    $output .="  <td $class>".$row["vorname"]." ".substr($row["nachname"],0,1).". (".$row["nick"].")</td>";
    $output .="</tr>";
  }
 }

  $output .="</table>";
  //$output .="<a href='mail.php?".implode("&",$userids)."'>Mail an diese User schicken</a>";

     $output .="</td>";
     $output .="</tr>";
// Maintable do not edit html below //

}
$PAGE->render($output);
?>
