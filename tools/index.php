<?php
$MODUL_NAME = "tools";
require_once("../../../global.php");
require_once("../functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Tools");

if(!$DARF["view"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

if($DARF["platzzettel"] || $DARF["verlosungszettel"]){
  $output .= "<h3>Zettel drucken</h3>";
  $output .= "<ul>";
  if($DARF["platzzettel"])      $output .= "  <li><a href='platzzettel' target='_blank'>Platzzettel</a></li>";
  if($DARF["verlosungszettel"]) $output .= "  <li><a href='verlosungszettel' target='_blank'>Verlosungszettel</a></li>";
  $output .= "</ul>";
}

if($DARF["freeze"] || $DARF["gaesteserver"]){
  $output .= "<h3>Sonstiges</h3>";
  $output .= "<ul>";
  if($DARF["freeze"])       $output .= "  <li><a href='freeze'>Freeze</a> (DB-Funktionen die vor/nach dem Einspielen ins Intranet ausgef&uuml;hrt werden m&uuml;ssen)</li>";
  if($DARF["gaesteserver"]) $output .= "  <li><a href='gaesteserver'>G&auml;steserver Mails verschicken</a></li>";
  $output .= "</ul>";
}

$PAGE->render($output);
?>
