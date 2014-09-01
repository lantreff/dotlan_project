<?php

$MODUL_NAME = "geburtstag";
include_once("../../../global.php");
include("../functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Geburtstage");

include("view.php");

$PAGE->render($output);
?>