<?php
$MODUL_NAME="gamesdb";
include_once("../../../global.php");
include("../functions.php");
include("functions.php");
include("header.php");
$event_data = list_event_data($EVENT->next_event_id);


if($_GET['hide'] != 1 )
{
	$output .= out_table(list_games(),$DARF,$_GET,$user=0,$event_id);
}
else
{	if($_GET['action'] == "add" || $_GET['action'] == "edit")
	{
		if($_GET['action'] == "add" && isset($_POST['senden']))
		{
			$save  = add_game($_POST);
			$PAGE->redirect($dir."index.php",$PAGE->sitetitle,$meldung);	
		}
		if($_GET['action'] == "edit" && isset($_POST['senden']))
		{
			$save  = edit_game($_POST);
			$PAGE->redirect($dir."index.php",$PAGE->sitetitle,$meldung);
				
		}
		
		$output .= out_form($id,$_GET);
	}
	if($_GET['action'] == "del")
	{
		if(isset($_POST['senden']))
		{
			$del  					= del_game($_POST);
			$del_game_from_user 	= del_game_from_user($_POST);
			$PAGE->redirect($dir."#",$PAGE->sitetitle,$meldung);
		}
		
	$out_list_name = list_single_game($_GET['id']);
	$output .= "<br> ".$_GET['action']." <br>
				
				<form method='post' action='?hide=1&action=del'&id='".$_GET['id']."'>
				<h2 style='color:RED;'>Achtung!!!!<h2>
				<br />

				<p>Sind Sie sich sicher das
				<font style='color:RED;'>".$out_list_name['name']."</font> gel&ouml;scht werden soll?</p>
				<br />
				<input name='senden' type='submit' value='l&ouml;schen'>
				<input type='hidden' name='id' value='".$_GET['id']."'>
				 \t</form>
				<a href='./' target='_parent'>
				<input value='Zur&uuml;ck' type='button'></a>
				";
	}
}
$PAGE->render(utf8_decode(utf8_encode($output) ));
?>