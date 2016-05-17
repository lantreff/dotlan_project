<?php
$MODUL_NAME = "catering_order";
include_once("../../../global.php");
include("../functions.php");
$event_id = $EVENT->next;
if ($_GET['action'] == 'bp' && is_numeric($_GET['order_id_min']) && is_numeric($_GET['order_id_max'])){
	$min_order_id = security_number_int_input($_GET['order_id_min'],'','');
	$max_order_id = security_number_int_input($_GET['order_id_max'],'','');
} else {
 echo 'foo';
}


echo "<html>\n<head>\n\t<title>"._("Catering System: Bestellungen drucken")."</title>\n</head>\n\n<style type=\"text/css\">\n<!--\nBODY, P, DIV, TD {\n\tfont-family: Arial, Helvetica, Verdana;\n\tfont-size:13px;\n}\n\nTH {\n\tfont-family: Arial, Helvetica, Verdana;\n\tfont-size:13px;\n\tborder-bottom: 2px solid black;\n\tbackground-color:black;\n\tcolor:white;\n\tfont-weight:bold;\n\ttext-align:left\n}\n-->\n</style>\n\n<body onload=\"print();\">\n";


		echo "<div style=\"font-size: 44px;\"><b><i>"._("Catering Bestellungen")."</i></b></div>\n";
		echo "<div style=\"font-size: 32px; margin-left: 44px;\"><b><i>- "._("Zusammenfassung")." -</i></b></div>\n<br />\n";

		echo "<table border=0>\n";
		echo "\t<tr><td width=150><b>"._("Datum/Uhrzeit")."</b></td><td>".strftime("%d. %B %Y  %H:%M",time())."</td></tr>\n";
		echo "\t<tr><td><b>"._("Druck durch")."</b></td><td>".$CURRENT_USER->vorname." ".$CURRENT_USER->nachname."</td></tr>\n";
		echo "</table>\n<br />\n<br />\n";
		echo "<table cellspacing=0 cellpadding=5 border=0 width=\"100%\">\n";

		echo "\t<tr valign=top>\n\t\t<th><center>"._("Nr")."</center></th>\n\t\t<th>"._("Bestellung")."</th>\n\t\t<th><center>"._("Anzahl")."</center></th>\n\t\t<th><center>"._("Check")."</center></th>\n\t</tr>\n";

		$orders_query = $DB->query("SELECT catering_order_part.order_id, catering_products.name, catering_products.description, catering_products.order_nr, COUNT(*) AS count FROM catering_order_part LEFT JOIN catering_products ON catering_order_part.product_id = catering_products.id WHERE order_id >= '{$min_order_id}' AND order_id <= '{$max_order_id}' AND catering_order_part.status = 2 GROUP BY catering_order_part.product_id ORDER BY catering_order_part.order_nr ASC");
		while($product = $DB->fetch_array($orders_query)){
			$style	= "border-top: 2px solid black; ";
			echo "\t<tr>\n";
			echo "\t\t<td width=\"75\" style=\"".$style."font-size: 18px; border-left:2px solid black;\" bgcolor=\"#eeeeee\" align=\"center\" nowrap>".$product['order_nr']."&nbsp;</td>\n";
			echo "\t\t<td style=\"".$style."\" valign=\"middle\"><b>".$product['name']."</b><br /><span style=\"font-size: 11px;\">".$product['description']."</span>&nbsp;</td>\n";
			echo "\t\t<td width=\"75\" style=\"".$style."font-size: 18px; border-left:2px solid black; border-right:2px solid black;\" valign=\"middle\" align=\"center\" bgcolor=\"#eeeeee\" nowrap><b>".intval($product['count'])."</b></td>\n";
			echo "\t\t<td width=\"100\" style=\"".$style."border-right:2px solid black;\" align=\"center\" valign=\"center\" style=\"padding-top: 3px; padding-bottom: 3px;\"><table cellspacing=0 cellpadding=0 style=\"border-top: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; width: 20px; height: 20px;\"><tr><td>&nbsp;</td></table></td>\n";
			echo "\t</tr>\n";
		}
		echo "\t<tr>\n\t\t<td colspan=4 style=\"border-top:2px solid black;\">&nbsp;</td>\n\t</tr>\n</table>\n";
		echo "<div style=\"border-bottom: 1px solid black; margin-bottom: 10px;\"><b>"._("Information zum Druck").":</b></div>";
		echo "<table border=\"0\"><tr><td width=150><b>"._("Seite 1")."</b></td><td>"._("Zusammenfassung")."</td></tr><tr><td><b>"._("Seite 2 - X")."</b></td><td>"._("Einzelbestellungen incl. Kundeninfo")."</td></tr><tr><td colspan=2>&nbsp;</td></tr><tr><td colspan=2>"._("Bei evtl. Sonderw&uuml;nschen der Kunden, diese bitte nachfolgend vermerken.")."</td></tr></table>";

		echo "<div style=\"font-size: 44px; page-break-before:always;\"><b><i>"._("Catering Bestellungen")."</i></b></div>\n<br />\n";
		echo "<table border=0>\n";
		echo "\t<tr><td width=150><b>"._("Datum/Uhrzeit")."</b></td><td>".strftime("%d. %B %Y %H:%M",time())."</td></tr>\n";
		echo "\t<tr><td><b>"._("Druck durch")."</b></td><td>".$CURRENT_USER->vorname." ".$CURRENT_USER->nachname."</td></tr>\n";
		echo "</table>\n<br />\n<br />\n";
		echo "<table cellspacing=0 cellpadding=5 border=0 width=\"100%\">\n";
		echo "\t<tr valign=top>\n\t\t<th>"._("Person")."</th>\n\t\t<th>"._("Sitzplatz")."</th>\n\t\t<th><center>"._("Nr")."</center></th>\n\t\t<th>"._("Bestellung")."</th>\n\t\t<th><center>"._("Check")."</center></th>\n\t</tr>\n";

		$res = $DB->query("SELECT
			catering_order_part.order_id,
			catering_products.name,
			catering_products.description,
			catering_products.order_nr,
			user.nick,
			user.vorname,
			user.nachname,
			event_teilnehmer.sitz_id,
			event_teilnehmer.sitz_nr
			FROM catering_order_part
			LEFT JOIN catering_products ON catering_order_part.product_id = catering_products.id
			LEFT JOIN user ON catering_order_part.user_id = user.id
			LEFT JOIN event_teilnehmer ON (event_teilnehmer.user_id = user.id AND event_teilnehmer.event_id = '{$event_id}')
			WHERE order_id >= '{$min_order_id}' AND order_id <= '{$max_order_id}' AND catering_order_part.status = 2
			ORDER BY catering_order_part.id ASC");

		$last_nr	= FALSE;
		while($data = $DB->fetch_array($res)) {
			$style	= (($last_nr!=$data['order_id'])?"border-top: 2px solid black; ":"");
			$cssstyle	= ($style!="") ? $cssstyle = " style=\"".$style."\" " : "";
			echo "\t<tr>\n";
			if($data['user_id'] == -1) {
				echo "\t\t<td style=\"".$style."border-left:2px solid black;\"><i>"._("Barverkauf")."&nbsp;</i></td>\n";
			} else {
				echo "\t\t<td style=\"".$style."border-left:2px solid black;\" valign=\"middle\"><b>".$data['nick']."</b><br /><span style=\"font-size: 11px;\">".$data['vorname']." ".$data['nachname']."</span>&nbsp;</td>\n";
			}
			echo "\t\t<td".$cssstyle.">".((intval($data['sitz_id'])>0)?$data['sitz_nr']:"")."&nbsp;</td>\n";
			echo "\t\t<td style=\"".$style."font-size: 18px; border-left:2px solid black;\" align=center bgcolor=\"#eeeeee\">".$data['order_nr']."&nbsp;</td>\n";
			echo "\t\t<td".$cssstyle." valign=\"middle\"><b>".$data['name']."</b><br /><span style=\"font-size: 11px;\">".$data['description']."</span>&nbsp;</td>\n";
			echo "\t\t<td style=\"".$style."border-right:2px solid black; padding-top: 3px; padding-bottom: 3px;\" align=\"center\" valign=\"center\"><table cellspacing=0 cellpadding=0 style=\"border-top: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; width: 20px; height: 20px;\"><tr><td>&nbsp;</td></table></td>\n";
			echo "\t</tr>\n";
			$last_nr = $data['order_id'];

		}
		echo "\t<tr>\n\t\t<td colspan=6 style=\"border-top:2px solid black;\">&nbsp;</td>\n\t</tr>\n</table>\n";

		echo "</body>\n</html>";
	#Todos
	## 1 PM an User, dass die Bestellung nu auf geordert steht.
	$pmquery1 = $DB->query("SELECT * FROM catering_order_part WHERE order_id >= '{$min_order_id}' AND order_id <= '{$max_order_id}' AND catering_order_part.status = 2");
	while($pm = $DB->fetch_array($pmquery1)) {
		$subject = $global['sitename'].' - Statusänderung Bestellung Nr. '.$pm['order_id'].' - '.$pm['name'];
		$message = "Der Status deiner Bestellung mit der Nr. ".$pm['order_id']." - ".$pm['name']." wurde soeben auf\n\n---> Geordert <---\n\ngeändert.\n\nFür weitere Infos stehen wir dir gerne zur Verfügung. Desweiteren kannst du den Status deiner Bestellung ebenfalls weiter im Intranet verfolgen.";
		$PRVMSG->generate_message($pm['user_id'],"INBOX",$pm['user_id'],0,$subject,$message);
		## $PRVMSG->generate_message(1948,"INBOX",1948,0,"TEST Nachricht","<b>TEST</b>");
    }

	$order_query = $DB->query("UPDATE catering_order_part SET catering_order_part.status = 3, catering_order_part.time_changed = '{$datum}' WHERE order_id >= '{$min_order_id}' AND order_id <= '{$max_order_id}' AND catering_order_part.status = 2");
	die();
?>
