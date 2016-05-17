<?php
############################################################
# Freeze-Button Modul for dotlan                           #
#                                                          #
# Copyright (C) 2010 Torsten Amshove <torsten@amshove.net> #
############################################################
$MODUL_NAME = "tools";
include_once("../../../../global.php");
require_once("../../functions.php");
$PAGE->sitetitle = $PAGE->htmltitle = _("Orga Catering Konten");

if(!$DARF["orga_konten"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

$a = 'shortbarbit';
$a1 = 'shortbarlink';

$output .= "<br>";

$output .= "<a name='top' >
      <a href='/admin/projekt/'>Projekt</a>
      &raquo;
      <a href='/admin/projekt/tools/'>Tools</a>
      &raquo;
      <a href='/admin/projekt/tools/orga_konten/'>Orga Catering Konten</a>
      &raquo;
      <hr class='newsline' width='100%' noshade=''>
      <br />";

if ($_GET['action'] == 'send_mail'){

	$mquery = $DB->query("SELECT user.id, user.nick, user.vorname, user.nachname, user.email, catering_konto.credits, ((100 - catering_konto.credits)*0.75) AS to_pay FROM catering_konto LEFT JOIN user ON catering_konto.user_id = user.id LEFT JOIN user_orga ON catering_konto.user_id = user_orga.user_id WHERE user_orga.team_forum = 1 ORDER BY user.nachname");
	$mcounter = 1;
	while($user = $DB->fetch_array($mquery)){
$betreff = 'Ausgleich Orga Konten Maxlan';
$nachricht = 'Hallo '.$user['vorname'].',

wir möchten Dich bitten, dein Maxlan Orga Konto für den Verzehr auf der Maxlan auszugleichen.

Jeder Orga hat einen Kredit von 100 EUR. Wir berechnen dann, wieviel du an Getränken und Catering ausgegeben hast und ziehen davon 25% ab. Der entsprechende Betrag sollte dann von dir ausgeglichen werden.

Stand Catering Konto: '.$user['credits'].' EUR
Zu zahlen (abzgl. 25%): '.$user['to_pay'].' EUR

Bitte überweise den Betrag auf das Maxlan Konto, schicke die Summe per Paypal oder gib sie fl_dutch privat.

Kontoverbindung:
LAN Treff Haren
Konto: 708154500
BLZ: 26661494
Bank: Emsländische Volksbank Meppen

IBAN DE94 2666 1494 0708 1545 00
BIC GENODEF1MEP

Paypal Adresse: info@maxlan.de

Nette Grüße
Die Maxlan Teamleitung

PS: Falls Du denkst, dass Du bereits bezahlt hast oder der Betrag ggf. falsch ist, schick uns bitte einfach eine Antwort auf diese Email.
PPS: Ein paar Orgas haben ehemaliges Teilnehmer Guthaben, ergo bitte diese Mail ignorieren!
PPPS: Diese Email wurde automatisch durch das Maxlan Projekt System generiert.

';

	mail($user['email'],$betreff,$nachricht,"From: maxlan <info@maxlan.de>");
	$output .= '<br>'.$mcounter.'. Mail gesendet: '.$user['vorname'].' <i>"'.$user['nick'].'"</i> '.$user['nachname'].', Mail: '.$user['email'];
	$mcounter++;
	}
	$output .= "<meta http-equiv='refresh' content='5; URL=/admin/projekt/tools/orga_konten/'>";
	$PAGE->render($output);
	exit();
}

if ($_GET['action'] == "equalize" && is_numeric($_GET['user'])){
	$user_id = security_number_int_input($_GET['user'],'','');
	$uquery = $DB->query("SELECT user.id AS user_id, user.nick, user.vorname, user.nachname, catering_konto.credits, ((100 - catering_konto.credits)*0.75) AS to_pay FROM catering_konto LEFT JOIN user ON catering_konto.user_id = user.id LEFT JOIN user_orga ON catering_konto.user_id = user_orga.user_id WHERE user_orga.team_forum = 1 AND user.id = '{$user_id}' ORDER BY user.nachname");
	while($user = $DB->fetch_array($uquery)){
		$output .= '<br><b>Orga Catering Konto zurücksetzen</b>';
		$output .= '<br><br>Möchtest Du das Catering Konto von '.$user['vorname'].' <i>"'.$user['nick'].'"</i> '.$user['nachname'].' wirklich auf 100 EUR zurücksetzen?';
		$output .= '<br><br>Kontostand: '.$user['credits'].' EUR';
		$output .= '<br>Zu Zahlen: '.$user['to_pay'].' EUR';
		$output .= '<br><br>';
		$output .= '<a href="index.php">Nein, nichts tun</a>&nbsp;&nbsp;||&nbsp;&nbsp;<a href="index.php?action=do_equal&user='.$user_id.'">Ja, auf 100 EUR zurücksetzen</a>';
	}
	$PAGE->render($output);
	exit();
}

if ($_GET['action'] == "do_equal" && is_numeric($_GET['user'])){
	$user_id = security_number_int_input($_GET['user'],'','');
	$uquery = $DB->query("SELECT user.id AS user_id, user.nick, user.vorname, user.nachname, catering_konto.credits, ((100 - catering_konto.credits)*0.75) AS to_pay FROM catering_konto LEFT JOIN user ON catering_konto.user_id = user.id LEFT JOIN user_orga ON catering_konto.user_id = user_orga.user_id WHERE user_orga.team_forum = 1 AND user.id = '{$user_id}' ORDER BY user.nachname");
	while ($user = $DB->fetch_array($uquery)){
		$optional = 'ORGA ID: '.$user['user_id'].', NICK: '.$user['nick'].', CREDITS: '.$user['credits'].', TOPAY: '.$user['to_pay'];
	}
	if (mysql_num_rows($uquery) == 1){
		$kquery = $DB->query("UPDATE catering_konto SET credits = 100 WHERE user_id = '{$user_id}'");
		$output .= '<span style="color: #00aa00; font-weight: bold;">Catering Konto erfolgreich auf 100 EUR gesetzt.</span>';
	} else {
		$output .= '<span style="color: #aa0000; font-weight: bold;">Error - nothing done</span>';
	}
	$output .= "<meta http-equiv='refresh' content='5; URL=/admin/projekt/tools/orga_konten/'>";

	$PAGE->render($output);
	exit();
}


$output .= "<table  width='10%' cellspacing='1' cellpadding='2' border='0' class='shortbar'><tbody><tr class='shortbarrow'>";
$output .= "<td width='100' class='".$a."'><a href='index.php?action=send_mail' class='".$a1."'>Mail an Orgas</a></td>";
$output .= "</tr></tbody></table><br>";

$uquery = $DB->query("SELECT user.id AS user_id, user.nick, user.vorname, user.nachname, catering_konto.credits, ((100 - catering_konto.credits)*0.75) AS to_pay FROM catering_konto LEFT JOIN user ON catering_konto.user_id = user.id LEFT JOIN user_orga ON catering_konto.user_id = user_orga.user_id WHERE user_orga.team_forum = 1 ORDER BY user.nachname");
$output .= "<table class='msg2' cellspacing='1' cellpadding='2' border='0'><tbody><tr><td width='70' class='msghead'><b>Vorname</b></td><td class='msghead'><b>Nick</b></td><td class='msghead'><b>Nachname</b></td><td class='msghead'><b>Kontostand</b></td><td class='msghead'><b>zu zahlen (-25%)</b></td><td class='msghead' width='70' align='center'>&nbsp;</td></tr>";
$iCounter=0;
$sum = 0;
while($user = $DB->fetch_array($uquery)){
	if($iCounter % 2 == 0){
		$currentRowClass = "msgrow1";
    } else {
		$currentRowClass = "msgrow2";
	}
	$output .= '<tr class="'.$currentRowClass.'"><td >'.$user['vorname'].'</td><td ><i>'.$user['nick'].'</i></td><td >'.$user['nachname'].'</td><td align="right">'.$user['credits'].' EUR</td><td align="right">'.$user['to_pay'].' EUR</td><td class="msgrow2" style="background-color: green;" NOWRAP><a href="index.php?action=equalize&user='.$user['user_id'].'" ><b><font color="white">Zurücksetzen</font></b></a></TD></tr>';
	$sum = $sum + $user['to_pay'];
	$iCounter++;
}
	$output .= '<tr class="'.$currentRowClass.'"><td colspan="4" align="right"><b>SUMME:</b> </td><td align="right">'.$sum.' EUR</td></tr>';
$output .= '</tbody></table>';

$PAGE->render($output);
?>
