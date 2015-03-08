<?php
########################################################################
# Dienstplan Modul for dotlan             			                   	 #
#                                                                      #
# Copyright (C) 2013 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# Version 1.0                                                          #
########################################################################

$MODUL_NAME = "dienstplan";
include_once("../../../global.php");
include("../functions.php");
include("./dienstplan_function.php");

include('header.php');

if(!$DARF["view"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));
else
{// $module_admin_check

if($_GET["f"] == -1 || $_GET["f"] == 1){
  if($_GET["f"] == -1) $_GET["f"] = 0;
  $DB->query("UPDATE project_dienstplan SET freeze = '".$_GET["f"]."' WHERE event_id = '".$event_id."'");
}

$freeze = mysql_result($DB->query("SELECT freeze FROM project_dienstplan WHERE event_id = '".$event_id."' LIMIT 1"),0,"freeze");

$output .="<table class='maincontent'>";

// Maintable do not edit html upon // 


    $output .="<tr>";
    $output .="<td>";



if(mysql_num_rows($DB->query("SELECT freeze FROM project_dienstplan WHERE event_id = '".$event_id."' LIMIT 1")) != 0)
{$output .="Freeze: ";
	
	if($freeze)	
	{
	
		if($DARF['edit_freeze'])$output .="<a href='plan_freeze.php?f=-1'>";
		$output .="an";
		if($DARF['edit_freeze'])$output .="</a>";
	}
	else 
	{
		if($DARF['freeze'])$output .="<a href='plan_freeze.php?f=1'>";
		$output .="aus";
		if($DARF['freeze'])$output .="</a>";
	}
}
else
{
	$output .="<h2>Kein Freeze zu diesem Event!! </h2>";
}

    $output .="</td>";
    $output .="</tr>";
// Maintable do not edit html below //
$output .="</table>";

}
$PAGE->render($output);
?>
