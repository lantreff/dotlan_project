<?php
########################################################################
# Maxlan Card Modul for dotlan             			                   #
#                                                                      #
# Copyright (C) 2014 Jens Broens <jens@broens.de>                      #
#                                                                      #
# Version 0.1                                                          #
########################################################################

$MODUL_NAME = "card";
include('function_card.php');

if(!$DARF["view"] ) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

include('header.php');
$u_id 		= security_number_int_input($_GET['user'],"","");
$sql_user = $DB->query("SELECT * FROM user WHERE id = '".$u_id."'");
$sql_card = $DB->query("SELECT * FROM project_card WHERE user_id = '".$u_id."'");

while ($card = mysql_fetch_array($sql_card)){
	switch($card['card_status']){
		case 0:
			$card_status .= '<span style="color: #ff7200;"><b>Maxlan Card bestellt ('.throwDateTime($card['last_order_date']).')</b></span>';
			break;
		case 1:
			$card_status .= '<span style="color: #ff9c00;"><b>Maxlan Card ist in Produktion</b></span>';
			break;
		case 2:
			$card_status .= '<span style="color: #1fca03;"><b>Maxlan Card kann am Support abgeholt werden</b></span>';
			break;
		case 3:
			$card_status .= '<span style="color: #00ce00;"><b>Maxlan Card abgeholt</b></span>';
			break;
		case 98:
			$card_status .= '<span style="color: #ff9c00;"><b>Maxlan Card Bestellung wurde angnommen</b></span></i>';
			break;
		case 99:
			$card_status .= '<span style="color: #ff0000;"><b>Maxlan Card Bestellung wurde abgelehnt</b></span><br><b>Grund:</b> <i>'.$card['card_info'].'</i>';
			break;
	}
	$card_img = '<br><b>Aktuelles Bild:</b><br><img src="./userpics/'.$card['pic_hash'].'" height="100" width="67">';
}

while ($user = mysql_fetch_array($sql_user)){

	$output .='<h3>Benutzerdaten für Maxlan Card</h3>';
	$output .='<br><b>Status:</b> '.$card_status;
	$output .='<br><b>Benutzer:</b> '.$user['vorname'].' <i>"'.$user['nick'].'"</i> '.$user['nachname'];
	$output .='<br><b>User-ID:</b> '.sprintf("%04d",$u_id);
	$output .='<br><b>Geburtstdatum:</b> '.birthday2german($user['geb']);
	$output .=$card_img;
}
$output .= "<form name='image' ACTION='index.php' METHOD=POST>";
$output .='<br><h4>Ablehnungsgrund für Maxlan Card</h4>';
$output .= '<select name="card_info" size="1">';
$output .= '<option value="Bildqualität reicht nicht aus (zu Dunkel, zu Hell, zuviel Rauschen)">Bildqualität reicht nicht aus (zu Dunkel, zu Hell, zuviel Rauschen)</option>';
$output .= '<option value="Person nicht erkennbar">Person nicht erkennbar</option>';
$output .= '<option value="Der Kopf ist zu klein, daher Person nur schwer erkennbar">Der Kopf ist zu klein, daher Person nur schwer erkennbar</option>';
$output .= '<option value="Person verzerrt, bitte unbedingt Bild im 2:3 Format hochladen">Person verzerrt, bitte unbedingt Bild im 2:3 Format hochladen</option>';
$output .= '<option value="Bildausrichtung falsch, Person auf dem Kopf/auf der Seite">Bildausrichtung falsch, Person auf dem Kopf/auf der Seite</option>';
$output .= '<option value="In Produktion gesehen: Bild zu dunkel">In Produktion gesehen: Bild zu dunkel</option>';
$output .= '<option value="In Produktion gesehen: Bild zu hell">In Produktion gesehen: Bild zu hell</option>';
$output .= '<option value="Schwarzes Bild - bitte nur jpg-Dateien hochladen">Schwarzes Bild - bitte nur jpg-Dateien hochladen</option>';
$output .= '</select>';
$output .= '<INPUT TYPE = "HIDDEN" NAME = "user" VALUE = "'.$u_id.'">';
$output .= "<br><br><table width='100%' cellspacing='1' cellpadding='3' class='msg2'><tr class='msgrow2'><td colspan='2' align='center' width='100%'><br /><input name='admin_decline_card' type='submit' value='Kartenbestellung ablehnen' />&nbsp;&nbsp;&nbsp;<input name='admin_accept_card' type='submit' value='Kartenbestellung annehmen' /><br><br></td></tr></table>";
$output .= '</form>';
$PAGE->render($output);
?>
