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

$query = mysql_query("SELECT id,nick,vorname,nachname FROM user");
while($row = mysql_fetch_array($query)){
#  $users[$row["id"]] = $row["vorname"]." ".substr($row["nachname"],0,1).". (".$row["nick"].")";
  $users[$row["id"]] = $row["nick"];
}

$query = mysql_query("SELECT plan_name FROM project_dienstplan WHERE event_id = '".$selectet_event_id."' GROUP BY plan_name ORDER BY plan_name");
$plan = array();
while($row = mysql_fetch_array($query)) $plan[] = $row["plan_name"];

$query = mysql_query("SELECT * FROM project_dienstplan WHERE event_id = '".$selectet_event_id."'");
while($row = mysql_fetch_array($query)){
  $plan_all[$row["plan_name"]][$row["tag"]][01] = $row["id_01"];
  $plan_all[$row["plan_name"]][$row["tag"]][02] = $row["id_02"];
  $plan_all[$row["plan_name"]][$row["tag"]][03] = $row["id_03"];
  $plan_all[$row["plan_name"]][$row["tag"]][04] = $row["id_04"];
  $plan_all[$row["plan_name"]][$row["tag"]][05] = $row["id_05"];
  $plan_all[$row["plan_name"]][$row["tag"]][06] = $row["id_06"];
  $plan_all[$row["plan_name"]][$row["tag"]][07] = $row["id_07"];
  $plan_all[$row["plan_name"]][$row["tag"]][08] = $row["id_08"];
  $plan_all[$row["plan_name"]][$row["tag"]][09] = $row["id_09"];
  $plan_all[$row["plan_name"]][$row["tag"]][10] = $row["id_10"];
  $plan_all[$row["plan_name"]][$row["tag"]][11] = $row["id_11"];
  $plan_all[$row["plan_name"]][$row["tag"]][12] = $row["id_12"];
  $plan_all[$row["plan_name"]][$row["tag"]][13] = $row["id_13"];
  $plan_all[$row["plan_name"]][$row["tag"]][14] = $row["id_14"];
  $plan_all[$row["plan_name"]][$row["tag"]][15] = $row["id_15"];
  $plan_all[$row["plan_name"]][$row["tag"]][16] = $row["id_16"];
  $plan_all[$row["plan_name"]][$row["tag"]][17] = $row["id_17"];
  $plan_all[$row["plan_name"]][$row["tag"]][18] = $row["id_18"];
  $plan_all[$row["plan_name"]][$row["tag"]][19] = $row["id_19"];
  $plan_all[$row["plan_name"]][$row["tag"]][20] = $row["id_20"];
  $plan_all[$row["plan_name"]][$row["tag"]][21] = $row["id_21"];
  $plan_all[$row["plan_name"]][$row["tag"]][22] = $row["id_22"];
  $plan_all[$row["plan_name"]][$row["tag"]][23] = $row["id_23"];
  $plan_all[$row["plan_name"]][$row["tag"]][24] = $row["id_24"];
}



$output .="<table class='maincontent'>";

 // Maintable do not edit html upon // 


    $output .="<tr>";
    $output .="<td>";
     $output .="<table>";
        $output .="<tr>";
          $output .="<td>";

$output .="<table>";
  $output .="<tr>";
    $output .="<td class='msghead'><b>Plan</b></td>";
    $output .="<td class='msghead'><b>Schichten gesamt</b></td>";
    $output .="<td class='msghead'><b>Schichten vergeben</b></td>";
    $output .="<td class='msghead'><b>Schichten offen</b></td>";
  $output .="</tr>";

$fields = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24");
$sum_gesamt = 0;
$sum_vergeben = 0;
$sum_offen = 0;
foreach($plan as $p){
  // Doppelt erlaubte ueberspringen
  //if(mysql_result(mysql_query("SELECT doppelt_erlaubt FROM project_dienstplan WHERE event_id = '".$selectet_event_id."' AND plan_name = '".$p."'"),0,"doppelt_erlaubt") == 1) continue; 
  $count_gesamt = 0;
  $count_vergeben = 0;
  $count_offen = 0;
  $sum_gesamt = 0;
  $sum_vergeben = 0;
  $sum_offen = 0;
  $query = mysql_query("SELECT * FROM project_dienstplan WHERE event_id = '".$selectet_event_id."' AND plan_name = '".$p."'");
  while($row = mysql_fetch_array($query)){
    foreach($fields as $f){
      if(!is_numeric($row["id_".$f])){
        $tmp = explode(",",$row["id_".$f]);
        foreach($tmp as $val){
          if($val != 0) $count_gesamt++;
          if($val > 0) $count_vergeben++;
          if($val == (-1)) $count_offen++;
        }
      }else{
        if($row["id_".$f] != 0) $count_gesamt++;
        if($row["id_".$f] > 0) $count_vergeben++;
        if($row["id_".$f] == (-1)) $count_offen++;
      }
    }
    $sum_gesamt += $count_gesamt;
    $sum_vergeben += $count_vergeben;
    $sum_offen += $count_offen;
  }
  $output .="<tr>";
    $output .="<td class='msgrow1'>$p</td>";
    $output .="<td class='msgrow1'>$count_gesamt</td>";
    $output .="<td class='msgrow1'>$count_vergeben</td>";
    $output .="<td class='msgrow1'>$count_offen</td>";
  $output .="</tr>";
}
$output .="<tr>";
    $output .="<td class='msgrow1'><b>Gesamt:</b></td>";
    $output .="<td class='msgrow1'><b>$sum_gesamt</b></td>";
    $output .="<td class='msgrow1'><b>$sum_vergeben</b></td>";
    $output .="<td class='msgrow1'><b>$sum_offen</b></td>";
  $output .="</tr>";

$output .="</table>";

$output .="<br><br><hr><br><br>";

$output .="<table>";
  $output .="<tr>";
    $output .="<td class='msghead' width='100'>&nbsp;</td>";
    $output .="<td class='msghead' width='100'>&nbsp;</td>";
    $output .="<td class='msghead' width='200'><b>Freitag</b></td>";
    $output .="<td class='msghead' width='200'><b>Samstag</b></td>";
    $output .="<td class='msghead' width='200'><b>Sonntag</b></td>";
  $output .="</tr>";


#$time = 1289074812;
$time = time();
$tmp = date("w",$time);
if($tmp == 0) $akt_tag = 3;
elseif($tmp == 5) $akt_tag = 1;
elseif($tmp == 6) $akt_tag = 2;
else $akt_tag = 0;
$akt_std = date("H",$time);

for($i=0;$i<24;$i++){
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

  $output .="<td $class><b>";
  foreach($plan as $p) $output .= $p."<br>";
  $output .="</b></td>";

  for($tag=1;$tag<=3;$tag++){
    $color = "";
    if($tag == $akt_tag && $std == $akt_std) $color = "#aaaaff";

    $output .="<td $class style='background-color: $color' nowrap>";
    if($tag == $akt_tag && $std == $akt_std) $output .="<a name='akt'></a>";
    foreach($plan as $p){
      $user = explode(",",$plan_all[$p][$tag][((int)$std1)]);
      $tmp="";
      foreach($user as $u){
        if($u > 0) $tmp .= $users[$u].", ";
      }
      $output .= substr($tmp,0,-2);
      $output .="<br>";
    }
    $output .="</td>";
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



}

$PAGE->render($output);
?>
