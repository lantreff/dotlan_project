<?php
require_once("../../../global.php");
require_once("../functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Tools");

if(!$DARF_PROJEKT_VIEW) $PAGE->error_die($HTML->gettemplate("error_rechtesystem"));
else{
  $output .= "<h3>Zettel drucken</h3>";
  $output .= "<ul>";
  $output .= "  <li><a href='platzzettel' target='_blank'>Platzzettel</a></li>";
  $output .= "  <li><a href='verlosungszettel' target='_blank'>Verlosungszettel</a></li>";
  $output .= "</ul>";
  $output .= "<h3>Sonstiges</h3>";
  $output .= "<ul>";
  $output .= "  <li><a href='freeze'>Freeze</a> (DB-Funktionen die vor/nach dem Einspielen ins Intranet ausgef&uuml;hrt werden m&uuml;ssen)</li>";
  $output .= "  <li><a href='gaesteserver'>G&auml;steserver Mails verschicken</a></li>";
  $output .= "</ul>";
}
$PAGE->render($output);
?>
