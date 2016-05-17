<?php

$PAGE->sitetitle = $PAGE->htmltitle = _("Maxlan Card");

$a = 'shortbarbit';
$a1 = 'shortbarlink';
$b = 'shortbarbit';
$b1 = 'shortbarlink';
$c = 'shortbarbit';
$c1 = 'shortbarlink';

if(($_GET['action'] == 'add' || $_GET['action'] == 'addpic' || $_GET['action'] == 'uploadpic' || $_GET['action'] == 'createcard') && $_GET['action'] != 'print'){
	$b = 'shortbarbitselect';
	$b1 = 'shortbarlinkselect';
}

if($_GET['action'] != 'add' && $_GET['action'] != 'addpic' && $_GET['action'] != 'uploadpic' && $_GET['action'] != 'createcard' && $_GET['action'] != 'print'){
	$a = 'shortbarbitselect';
	$a1 = 'shortbarlinkselect';
}

if($_GET['action'] != 'add' && $_GET['action'] != 'addpic' && $_GET['action'] != 'uploadpic' && $_GET['action'] != 'createcard' && $_GET['action'] == 'print'){
	$c = 'shortbarbitselect';
	$c1 = 'shortbarlinkselect';
}

$breite = "120";
$output .= "
	<a name='top' ></a>
	<table class='msg2' width='100%' cellspacing='0' cellpadding='0' border='0' align='center'><a href='".$global['project_path']."'>Projekt</a>&raquo;<a href='".$dir."'>Maxlan Card </a>&raquo; ".$_GET['action']."<br></table>
	<br />
    <table cellspacing='1' cellpadding='2' border='0' class='msg2'><tbody>
		<tr class='shortbarrow'>
					<td width='".$breite."' class='".$a."'> <a href='".$dir."' class='".$a1."'> Übersicht </a></td>
					<td width='1' class='shortbarbitselect'>&nbsp;</td>
";

if($DARF["create_cards"] ) {
	$output .= "
		<td width='".$breite."' class='".$b."'> <a href='new_maxlan_card.php?action=add' class='".$b1."'> Karte anlegen </a></td>
		<td width='1' class='shortbarbitselect'>&nbsp;</td>";
}

if($DARF["print_cards"]) {
	$output .= "
		<td width='150' class='".$c."'> <a href='card_print_list.php?action=print' class='".$c1."'> Karten drucken </a></td>
		<td width='1' class='shortbarbitselect'>&nbsp;</td>";
}

$output .= "</tr></tbody></table><br />";

?>
