<?php
$MODUL_NAME = "meeting";
include_once("../../../global.php");
include("meeting_functions.php");
include("../functions.php");
//$output .=  $event_id;
$user_id = $CURRENT_USER->id;

include('header.php');

if ($DARF['view']){



  if ($DARF['edit']){
    if($_POST["submit"] == "Anpassen")  $output .= meeting_updatetext($_GET["id"],$_GET["typ"],$_POST);
  }
$output .= '
<table class="msg">
';

if($_GET["action"] == "change") {
	$output .= meeting_showchangetext($_GET["id"],$_GET["typ"],$DARF['edit'],$event_id);
	//$output .= meeting_showprotokolls($_GET["typ"],$DARF['edit']);
}
elseif($_GET["action"] == "add") {
	$output .= meeting_addprotokoll($_GET["kategorie"],$_GET["bezeichnung"],$_GET["geplant"],$DARF,$event_id,$_GET['id'],$datum);
}
else{ 
	$output .= meeting_showtext($_GET["id"],$_GET["typ"],$DARF['edit']);
	//$output .=  meeting_showprotokolltext($_GET["id"],$_GET["typ"],$DARF['edit']);
}

$output .= '
</table>
</td>
';
} 

$PAGE->render($output);
?>