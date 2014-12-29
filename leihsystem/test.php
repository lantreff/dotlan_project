<?
include_once("../../../global.php");
include("../functions.php");


$PAGE->sitetitle = $PAGE->htmltitle = _("Leihsystem");

$output .= "
<form method='post' name='leihe' onSubmit='Weiterleiten()' action=''  >
<input  type='submit' value=' W E I T E R ' />
</form>
";
$PAGE->render( utf8_decode($output) );
?>