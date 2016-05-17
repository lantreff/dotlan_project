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
// $output .= $selectet_event_id.'<br><br>';

$output .="<table class='maincontent'>";

 // Maintable do not edit html upon //


    $output .="<tr>";
    $output .="<td>";
     $output .="<table>";

$userid = $user_id;

if ($DARF['edit']){
  if(!empty($_POST["userid"])) $userid = mysql_real_escape_string($_POST["userid"]);

        $output .="<tr>";
          $output .="<td class='maintd'><b>User:</b>";
$output .="<form action='plan_my.php?event=".$selectet_event_id."' method='POST'>";
$output .="<select name='userid' style='font-family: Courier;'>";

  //$query = $DB->query("SELECT id, nick, vorname, nachname FROM user ORDER BY vorname");
  $query = $DB->query("SELECT nick, vorname, nachname, u.id AS id FROM user AS u, user_orga AS o WHERE o.user_id = u.id ORDER BY  `u`.`vorname` ASC");
  while($row = $DB->fetch_array($query)){
    $count = 0;
    $count2 = 0;
    for($i=1;$i<=24;$i++){
      $num = sprintf("%02d",$i);
      $count += $DB->num_rows($DB->query("SELECT event_id FROM project_dienstplan WHERE doppelt_erlaubt = 0 AND event_id = '".$selectet_event_id."' AND (id_$num = '".$row["id"]."' OR id_$num LIKE '".$row["id"].",%' OR id_$num LIKE '%,".$row["id"].",%' OR id_$num LIKE '%,".$row["id"]."')"));
      $count2 += $DB->num_rows($DB->query("SELECT event_id FROM project_dienstplan WHERE doppelt_erlaubt = 1 AND event_id = '".$selectet_event_id."' AND (id_$num = '".$row["id"]."' OR id_$num LIKE '".$row["id"].",%' OR id_$num LIKE '%,".$row["id"].",%' OR id_$num LIKE '%,".$row["id"]."')"));
#$output .="SELECT event_id FROM project_dienstplan WHERE event_id = '".$selectet_event_id."' AND id_$num = '".$row["id"]."' OR id_$num LIKE '".$row["id"].",%' OR id_$num LIKE '%,".$row["id"].",%' OR id_$num LIKE '%,".$row["id"]."'\n";
    }
    $output .="<option value='".$row["id"]."'";
    if($userid == $row['id']) $output .=" selected";
    $name = $row["vorname"]." ".substr($row["nachname"],0,1).". (".$row["nick"].")";
    $output .=">".$name;
    for ($i=0; $i<(30-strlen($name)); $i++) $output .="&nbsp;";
    $output .=" - Anz: $count (+$count2)</option>";
  }

$output .="</select>";
$output .="<input type='submit' name='change' value='ausw&auml;hlen'>";
$output .="</form>";
          $output .="</td>";
        $output .="</tr>";

}


# Freitag
$anwesend[1] = array();
for($a1=0;$a1<24;$a1++)
{
	$ab_x = $DB->fetch_array($DB->query("SELECT ab_$a1 FROM project_anwesenheit WHERE event_id = '".$selectet_event_id."' AND user_id = '".$userid."' AND tag LIKE '%Freitag%'"));
	// $output .='<br><br>'.$ab_x['ab_'.$a1].'<br><br>';
	if($ab_x['ab_'.$a1] == 1){
		$anwesend[1][] = $a1;
	}
}

# Samstag
$anwesend[2] = array();
for($a2=0;$a2<24;$a2++)
{
	$ab_y = $DB->fetch_array( $DB->query("SELECT ab_$a2 FROM project_anwesenheit WHERE event_id = '".$selectet_event_id."' AND user_id = '".$userid."' AND tag LIKE '%Samstag%'"));
	if($ab_y['ab_'.$a2] == 1){
		$anwesend[2][] = $a2;
	}
}

# Sonntag
$anwesend[3] = array();
for($a3=0;$a3<24;$a3++)
{
	$ab_z = $DB->fetch_array($DB->query("SELECT ab_$a3 FROM project_anwesenheit WHERE event_id = '".$selectet_event_id."' AND user_id = '".$userid."' AND tag LIKE '%Sonntag%'"));
	if($ab_z['ab_'.$a3] == 1){
		$anwesend[3][] = $a3;
	}
}
/*
*/

// $output .= '<br><br><pre>'.print_r($anwesend).'</pre><br><br>'; ## bugfixing use

        $output .="<tr>";
          $output .="<td>";

$output .="<table>";
  $output .="<tr>";
    $output .="<td class='msghead' width='100'>&nbsp;</td>";
    $output .="<td class='msghead' width='10'>&nbsp;</td>";
    $output .="<td class='msghead' width='130'><b>Freitag</b></td>";
    $output .="<td class='msghead' width='10'>&nbsp;</td>";
    $output .="<td class='msghead' width='130'><b>Samstag</b></td>";
    $output .="<td class='msghead' width='10'>&nbsp;</td>";
    $output .="<td class='msghead' width='130'><b>Sonntag</b></td>";
  $output .="</tr>";


$projekt_von = @mysql_result($DB->query("SELECT UNIX_TIMESTAMP(begin) as von FROM events WHERE id = '".$selectet_event_id."' LIMIT 1"),0,"von");
for($i=0;$i<24;$i++){
	$test = $i;
  if(strlen($i) == 1) $std = "0".$i;
  else $std = $i;
  $std1 = $std + 1;
  if(strlen($std1) == 1) $std1 = "0".$std1;

  $equal = bcmod($i, 2);
  if ($equal == 0) {
    $class= 'class="msgrow1"';
  } else {
    $class= 'class="msgrow2"';
  }

  $output .="<tr>";
    $output .="<td $class><b>".$std.":00 - ".$std1.":00</b></td>";

  for($tag=1;$tag<=3;$tag++){
      $query = $DB->query("SELECT plan_name, doppelt_erlaubt FROM project_dienstplan WHERE event_id = '".$selectet_event_id."' AND tag = '".$tag."' AND FIND_IN_SET('{$userid}',id_$std1)") or die(mysql_query());
      //$query = $DB->query("SELECT plan_name, doppelt_erlaubt FROM project_dienstplan WHERE event_id = '".$selectet_event_id."'  AND tag = '".$tag."' AND id_$std1 LIKE '%".$userid."%' ");
	  //$query = $DB->query("SELECT plan_name, doppelt_erlaubt FROM project_dienstplan WHERE event_id = '".$selectet_event_id."' AND tag = '".$tag."' AND  id_$std1 IN ('".$userid."') ");
    $plan = "";
    $color = "";
    $doppel = 0;
    while($row = $DB->fetch_array($query)){
	// while($row = mysql_fetch_assoc($query)){
      $plan .= $row["plan_name"];

      if($tag == 1) $dow = 5;
      elseif($tag == 2) $dow = 6;
      else $dow = 0;

      $tmp_von = $projekt_von;
      while(date("w",$tmp_von) != $dow){
        $tmp_von += 86400;
      }
global $global;
      $von = date("Ymd",$tmp_von)."T".sprintf("%02d",($std-1))."0000Z";
      $bis = date("Ymd",$tmp_von)."T".sprintf("%02d",($std1-1))."0000Z";
	  $plan_name = sonderzeichen($row["plan_name"]);
      $plan .= " <a href='https://www.google.com/calendar/render?action=TEMPLATE&text=".$plan_name."&dates=$von/$bis&sprop=website:".$_SERVER["SERVER_NAME"]."&trp=true&location=".$global['sitename']."' target='_blank'><img align='right' border=0 src='../images/16/google_calendar.png' height='13' title='Zu google-Kalender hinzuf&uuml;gen'></a>";
      $plan .= "<br>";
      if($row["doppelt_erlaubt"] == 0) $doppel++;
    }
    if($doppel > 1) $color = "#FF0000";

    $color_anwesend = "#AA0000";
    if(in_array($std,$anwesend[$tag])) $color_anwesend = "#00AA00";

    $output .="<td class='msghead' width='10' style='background-color: $color_anwesend'>&nbsp;</td>";
    $output .="<td $class style='background-color: $color'>$plan</td>";
  }
  $output .="</tr>";
}

$output .="</table>";

          $output .="</td>";
        $output .="</tr>";
     $output .="</table>";

    $output .="</td>";
    $output .="</tr>";
 // Maintable do not edit html below //
$output .="</table>";
$output .="</td>";



}
$PAGE->render($output);
?>
