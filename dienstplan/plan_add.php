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

if($freeze != 1)
{
if(!empty($_POST["add"])){
  $name = mysql_real_escape_string($_POST["name"]);
  $DB->query("INSERT INTO project_dienstplan (event_id, plan_name, tag, id_01, id_02, id_03, id_04, id_05, id_06, id_07, id_08, id_09, id_10, id_11, id_12, id_13, id_14, id_15, id_16, id_17, id_18, id_19, id_20, id_21, id_22, id_23, id_24) VALUES ('".$event_id."','$name','1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1')");
  $DB->query("INSERT INTO project_dienstplan (event_id, plan_name, tag, id_01, id_02, id_03, id_04, id_05, id_06, id_07, id_08, id_09, id_10, id_11, id_12, id_13, id_14, id_15, id_16, id_17, id_18, id_19, id_20, id_21, id_22, id_23, id_24) VALUES ('".$event_id."','$name','2','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1')");
  $DB->query("INSERT INTO project_dienstplan (event_id, plan_name, tag, id_01, id_02, id_03, id_04, id_05, id_06, id_07, id_08, id_09, id_10, id_11, id_12, id_13, id_14, id_15, id_16, id_17, id_18, id_19, id_20, id_21, id_22, id_23, id_24) VALUES ('".$event_id."','$name','3','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1','-1')");
  $output .="<h2>Plan $name angelegt</h2>";
}

if(!empty($_POST["del"])){
  $output .="<h2>Plan <u>".$_POST["name"]."</u> wirklich l&ouml;schen?</h2> <a href='plan_add.php?del2=".$_POST["name"]."'>ja</a> | <a href='plan_add.php'>nein</a>";
}

if(!empty($_GET["del2"])){
  $name = mysql_real_escape_string($_GET["del2"]);
  $DB->query("DELETE FROM project_dienstplan WHERE plan_name = '$name' AND event_id = '".$event_id."'");
  $output .="$name wurde gel&ouml;scht";
}

if(!empty($_POST["doppel"])){
  $name = mysql_real_escape_string($_POST["name"]);
  $DB->query("UPDATE project_dienstplan SET doppelt_erlaubt = IF(doppelt_erlaubt = 1,0,1) WHERE plan_name = '$name' AND event_id = '".$event_id."'");
}
if(!empty($_POST["copy"])){
	
	copy_plan($event_id);
	$meldung = "Daten gesendet";
	$PAGE->redirect("index.php",$PAGE->sitetitle,$meldung);
}
}
else{
	$output .= $freeze_meldung;
}
$output .="<table>";

 // Maintable do not edit html upon // 


    $output .="<tr>";
    $output .="<td>";
     $output .="<table>";
if($DARF['add'])
{
	 $output .="<tr>";
          $output .="<td class='msgrow2'>";

$output .="<b>Plan anlegen:</b>";
$output .="<form action='plan_add.php' method='POST'>";
$output .="<input type='text' name='name'>";
$output .="<input type='submit' name='add' value='anlegen'>";
$output .="</form>";

          $output .="</td>";
        $output .="</tr>";
}		
if($DARF['edit'])
{
        $output .="<tr>";
          $output .="<td>";

$output .="<b>In den Doppel-Check aufnehmen?</b>";
$output .="<form action='plan_add.php' method='POST'>";
$output .="<select name='name'>";

  $query = $DB->query("SELECT plan_name, doppelt_erlaubt FROM project_dienstplan WHERE event_id = '".$event_id."' GROUP by (plan_name) ORDER BY plan_name");
  while($row = $DB->fetch_row($query)){
    if($row[1] == 0) $d = "Ja";
    else $d = "Nein";

    $output .="<option value='".$row[0]."'>".$row[0]." - ".$d."</option>";
  }

$output .="</select>";
$output .="<input type='submit' name='doppel' value='&auml;ndern'>";
$output .="</form>";

          $output .="</td>";
        $output .="</tr>";
}

if($DARF['del'])
{	 
	$output .="<tr>";
          $output .="<td class='msgrow2'><b>Zeitpl&auml;ne zu Projekt ".$_SESSION['projekt_name']." l&ouml;schen:</b>";

$output .="<form action='plan_add.php?event=".$event_id."' method='POST'>";
$output .="<select name='name'>";

  $query = $DB->query("SELECT plan_name FROM project_dienstplan WHERE event_id = '".$event_id."' GROUP by (plan_name) ORDER BY plan_name");
  while($row = $DB->fetch_row($query)) $output .="<option value='".$row[0]."'>".$row[0]."</option>";

$output .="</select>";
$output .="<input type='submit' name='del' value='l&ouml;schen'>";
$output .="</form>";

          $output .="</td>";
        $output .="</tr>";
}

if($DARF['edit'])
{
        $output .="<tr>";
          $output .="<td>";

$output .="<b>Alle Pl&auml;ne des letzten Events kopieren</b>";
$output .="<form action='plan_add.php' method='POST'>";
$output .="<input type='submit' name='copy' value='kopieren'>";
$output .="</form>";

          $output .="</td>";
        $output .="</tr>";
}
     $output .="</table>";
    $output .="</td>";
    $output .="</tr>";
 // Maintable do not edit html below //
$output .="</table>";
$output .="</td>";


}

$PAGE->render($output);
?>