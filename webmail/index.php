<?php
#
# session_name in der squirrel-config muss der gleiche sein wie der von dotlan - herausfinden kann man das mit der Funktion session_name();
#
$MODUL_NAME = "webmail";
require_once("../../../global.php");
require_once("../config.php");
require_once("../functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Webmail");

if(!$DARF["view"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

$_SESSION["webmail"]["user"] = $webmail_user;
$_SESSION["webmail"]["pw"] = $webmail_pw;
$output .= "<iframe style='width: 99%; height: 600px; border: 0;' src='/admin/projekt/webmail/squirrelmail/src/redirect.php?autologin=true'></iframe>";

$PAGE->render($output);
?>
