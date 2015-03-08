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
//$output .= "TEST ".  $event_id;

include('header.php');

if(!$DARF["view"] ) $PAGE->error_die($HTML->gettemplate("error_nopermission"));
else
{// $module_admin_check

if(empty($plan)) $plan = $DB->query($_GET["plan"]);
if(empty($plan)){
  $plan = mysql_result($DB->query("SELECT plan_name FROM project_dienstplan WHERE event_id = '".$event_id."' ORDER BY plan_name LIMIT 1"),0,"plan_name");
}

header('Content-type: text/csv');
header('Content-Disposition: attachment; filename="Dienstplan_'.$plan.'_'.$_SESSION["projekt_name"].'_'.date("Ymd_Hi").'.csv"');

$output .="Uhrzeit;Freitag;Samstag;Sonntag\n";


$query = $DB->query("SELECT * FROM project_dienstplan WHERE plan_name = '$plan' AND event_id = '".$event_id."'");
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

for($i=0;$i<24;$i++){
  if(strlen($i) == 1) $std = "0".$i;
  else $std = $i;
  $std1 = $std + 1;
  if(strlen($std1) == 1) $std1 = "0".$std1;

  echo $std.":00 - ".$std1.":00;";

  if($tag[1][$i] == -1) $output .="noch frei";
  elseif($tag[1][$i] == 0) $output .="-- niemand --";
  else{
    if(strstr($tag[1][$i],",")){
      $bla = explode(",",$tag[1][$i]);
      $eintrag = "";
      foreach($bla as $blubb){
        if($blubb == -1) $eintrag .= "noch frei,";
        elseif($blubb == 0) $eintrag .= "-- niemand --,";
        else{
          $query = $DB->query("SELECT id, vorname, nachname, nick FROM user WHERE id = '".$blubb."' LIMIT 1");
          $eintrag .= mysql_result($query,0,"vorname")." ".substr(mysql_result($query,0,"nachname"),0,1).". (".mysql_result($query,0,"nick")."),";
        }
      }
      echo substr($eintrag,0,-1);
    }else{
      $query = $DB->query("SELECT id, vorname, nachname, nick FROM user WHERE id = '".$tag[1][$i]."' LIMIT 1");
      echo mysql_result($query,0,"vorname")." ".substr(mysql_result($query,0,"nachname"),0,1).". (".mysql_result($query,0,"nick").")";
    }
  }

  $output .=";";

  if($tag[2][$i] == -1) $output .="noch frei";
  elseif($tag[2][$i] == 0) $output .="-- niemand --";
  else{
    if(strstr($tag[2][$i],",")){
      $bla = explode(",",$tag[2][$i]);
      $eintrag = "";
      foreach($bla as $blubb){
        if($blubb == -1) $eintrag .= "noch frei,";
        elseif($blubb == 0) $eintrag .= "-- niemand --,";
        else{
          $query = $DB->query("SELECT id, vorname, nachname, nick FROM user WHERE id = '".$blubb."' LIMIT 1");
          $eintrag .= mysql_result($query,0,"vorname")." ".substr(mysql_result($query,0,"nachname"),0,1).". (".mysql_result($query,0,"nick")."),";
        }
      }
      echo substr($eintrag,0,-1);
    }else{
      $query = $DB->query("SELECT id, vorname, nachname, nick FROM user WHERE id = '".$tag[2][$i]."' LIMIT 1");
      echo mysql_result($query,0,"vorname")." ".substr(mysql_result($query,0,"nachname"),0,1).". (".mysql_result($query,0,"nick").")";
    }
  }

  $output .=";";

  if($tag[3][$i] == -1) $output .="noch frei";
  elseif($tag[3][$i] == 0) $output .="-- niemand --";
  else{
    if(strstr($tag[3][$i],",")){
      $bla = explode(",",$tag[3][$i]);
      $eintrag = "";
      foreach($bla as $blubb){
        if($blubb == -1) $eintrag .= "noch frei,";
        elseif($blubb == 0) $eintrag .= "-- niemand --,";
        else{
          $query = $DB->query("SELECT id, vorname, nachname, nick FROM user WHERE id = '".$blubb."' LIMIT 1");
          $eintrag .= mysql_result($query,0,"vorname")." ".substr(mysql_result($query,0,"nachname"),0,1).". (".mysql_result($query,0,"nick")."),";
        }
      }
      echo substr($eintrag,0,-1);
    }else{
      $query = $DB->query("SELECT id, vorname, nachname, nick FROM user WHERE id = '".$tag[3][$i]."' LIMIT 1");
      echo mysql_result($query,0,"vorname")." ".substr(mysql_result($query,0,"nachname"),0,1).". (".mysql_result($query,0,"nick").")";
    }
  }
  
  $output .="\n";
}

}
?>
