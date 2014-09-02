<?php
$MODUL_NAME = "meeting";
include_once("../../../global.php");
include("meeting_functions.php");
include("../functions.php");
$event_id = $EVENT->next;
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