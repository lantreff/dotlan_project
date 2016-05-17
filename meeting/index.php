<?php
########################################################################
# Meeting Modul for dotlan             			                   	   #
#                                                                      #
# Copyright (C) 2013 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# Version 1.0                                                          #
########################################################################

$MODUL_NAME = "meeting";
include_once("../../../global.php");
include("../functions.php");
include("meeting_functions.php");
//$output .= "TEST ".  $event_id;

include('header.php');


if(!$DARF["view"] ) $PAGE->error_die($HTML->gettemplate("error_nopermission"));
else
{// $module_admin_check
	if ($DARF["view"])
	{
			

		  if ($DARF["add"]){
			if($_POST["submit"] == "Anlegen"){
			  meeting_insert($_POST,$event_id);
			 }elseif($_POST["submit"] == "Anpassen"){
			  meeting_update($_POST,$id);
			  $_POST["submit"] = "Anlegen";
			}
			
			if($_GET["action"] == "change"){
			  $query =$DB->query("SELECT * FROM project_meeting_liste WHERE `ID` = ".$_GET["id"]." LIMIT 1;");
			  $titel = mysql_result($query,0,"titel");
			  $meeting_datum = mysql_result($query,0,"datum");
			  $location = mysql_result($query,0,"location");
			  $adresse = mysql_result($query,0,"adresse");
			  $geplant = mysql_result($query,0,"geplant");			  
			  $_POST["submit"] = "Anpassen";
			}elseif($_GET["action"] == "delete"){
			  $meldung = 'Das Meeting am '.date("d.m.Y H:i:s",strtotime($_GET["moep"])).' wirklich löschen? - <a href="?action=del&id='.$_GET["id"].'">Ja</a> | <a href="index.php">nein</a><br><br>';
			}elseif($_GET["action"] == "del"){
			  meeting_del($_GET["id"]);
			}elseif($_GET["action"] == "gewesen"){
			  meeting_chg_gewesen($_GET["id"],$_GET["gewesen"]);
			}
		  }
if($_GET['hide'] !=1)
{		  
$output .= 	$meldung.'
<table class="maincontent">

';
if ($DARF["del"] || $DARF["edit"]) $output .=  meeting_list($DARF,$event_id);
else $output .=  meeting_list($DARF,$event_id);
$output .= 	'
</table>
';
}
if($_GET['action'] == 'add' || $_GET['action'] == 'change')
{
	$output .= meeting_input($DARF["add"],$DARF["edit"],$titel,$datum,$meeting_datum,$location,$adresse,$geplant);
}
		

		//include('./template/bottom.php');
	}
	else 
	{
		$output .=  'RECHTE FALSCH';
	}

	
}
// $module_admin_check ENDE

$PAGE->render($output);
?>