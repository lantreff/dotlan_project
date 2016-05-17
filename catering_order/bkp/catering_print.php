<?php

	function catering_print($part) 
	{
		global $DB, $HTML, $S, $CURRENT_USER, $PAGE, $ADMIN, $PRVMSG, $EVENT, $global;
		if(!$ADMIN->check(ADMIN_CATERING)) $PAGE->error_die($HTML->gettemplate("error_nopermission"));
		if(!is_array($part) || (is_array($part) && count($part) == 0))
			$PAGE->error_die(_("Es wurden keine Bestellungen ausgew&auml;hlt."));
		
		echo "<html>\n<head>\n\t<title>"._("Catering System: Bestellungen drucken")."</title>\n</head>\n\n<style type=\"text/css\">\n<!--\nBODY, P, DIV, TD {\n\tfont-family: Arial, Helvetica, Verdana;\n\tfont-size:13px;\n}\n\nTH {\n\tfont-family: Arial, Helvetica, Verdana;\n\tfont-size:13px;\n\tborder-bottom: 2px solid black;\n\tbackground-color:black;\n\tcolor:white;\n\tfont-weight:bold;\n\ttext-align:left\n}\n-->\n</style>\n\n<body onload=\"print();\">\n";
		
		///////////////////////////////////////////////////////////////////////////
		
		// Wenn keine Event gewählt ist gilt das aktive Event
		if(!$this->event_id && $global['modules']['event'] && is_object($EVENT) && $EVENT->next_event_id)
			$this->event_id = $EVENT->next_event_id;
			
		///////////////////////////////////////////////////////////////////////////
		
		$c = count($part);
		$i = 0;
		$where = "WHERE ";
		foreach($part AS $id) {
			$where .= "cop.id='".intval($id)."' ";
			if(++$i < $c) $where .= " OR ";
		}
		$res		= $DB->query("SELECT cop.order_id, cop.user_id, user.nick, user.nachname, user.vorname, cop.product_id, cp.name, cp.order_nr, cp.description, event_teilnehmer.sitz_nr, event_teilnehmer.sitz_id, event_teilnehmer.sitz_block FROM catering_order_part AS cop LEFT JOIN catering_products AS cp ON cp.id=cop.product_id LEFT JOIN user ON user.id=cop.user_id LEFT JOIN event_teilnehmer ON user.id=event_teilnehmer.user_id AND event_teilnehmer.event_id=".intval($this->event_id)." ".$where." ORDER by order_id ASC");
		
		$products	= array();
		while($data = $DB->fetch_array($res)) {
			if(isset($products[$data['product_id']]))
				$products[$data['product_id']]['count']++;
			else	$products[$data['product_id']] = array('data'=>$data, 'count'=>1);
		}
		
		///////////////////////////////////////////////////////////////////////////
		
		echo "<div style=\"font-size: 44px;\"><b><i>"._("Catering Bestellungen")."</i></b></div>\n";
		echo "<div style=\"font-size: 32px; margin-left: 44px;\"><b><i>- "._("Zusammenfassung")." -</i></b></div>\n<br />\n";
		
		echo "<table border=0>\n";
		echo "\t<tr><td width=150><b>"._("Datum/Uhrzeit")."</b></td><td>".strftime("%d. %B %Y  %H:%M",time())."</td></tr>\n";
		echo "\t<tr><td><b>"._("Druck durch")."</b></td><td>".html::entities($CURRENT_USER->vorname." ".$CURRENT_USER->nachname)."</td></tr>\n";
		echo "</table>\n<br />\n<br />\n";
		echo "<table cellspacing=0 cellpadding=5 border=0 width=\"100%\">\n";
		
		echo "\t<tr valign=top>\n\t\t<th><center>"._("Nr")."</center></th>\n\t\t<th>"._("Bestellung")."</th>\n\t\t<th><center>"._("Anzahl")."</center></th>\n\t\t<th><center>"._("Check")."</center></th>\n\t</tr>\n";
		
		foreach($products AS $product) {
			$style	= "border-top: 2px solid black; ";
			echo "\t<tr>\n";
			echo "\t\t<td width=\"75\" style=\"".$style."font-size: 18px; border-left:2px solid black;\" bgcolor=\"#eeeeee\" align=\"center\" nowrap>".html::entities($product['data']['order_nr'])."&nbsp;</td>\n";
			echo "\t\t<td style=\"".$style."\" valign=\"middle\"><b>".html::entities($product['data']['name'])."</b><br /><span style=\"font-size: 11px;\">".html::entities($product['data']['description'])."</span>&nbsp;</td>\n";
			echo "\t\t<td width=\"75\" style=\"".$style."font-size: 18px; border-left:2px solid black; border-right:2px solid black;\" valign=\"middle\" align=\"center\" bgcolor=\"#eeeeee\" nowrap><b>".intval($product['count'])."</b></td>\n";
			echo "\t\t<td width=\"100\" style=\"".$style."border-right:2px solid black;\" align=\"center\" valign=\"center\" style=\"padding-top: 3px; padding-bottom: 3px;\"><table cellspacing=0 cellpadding=0 style=\"border-top: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; width: 20px; height: 20px;\"><tr><td>&nbsp;</td></table></td>\n";
			echo "\t</tr>\n";
		}
		
		echo "\t<tr>\n\t\t<td colspan=4 style=\"border-top:2px solid black;\">&nbsp;</td>\n\t</tr>\n</table>\n";
		echo "<div style=\"border-bottom: 1px solid black; margin-bottom: 10px;\"><b>"._("Information zum Druck").":</b></div>";
		echo "<table border=\"0\"><tr><td width=150><b>"._("Seite 1")."</b></td><td>"._("Zusammenfassung")."</td></tr><tr><td><b>"._("Seite 2 - X")."</b></td><td>"._("Einzelbestellungen incl. Kundeninfo")."</td></tr><tr><td colspan=2>&nbsp;</td></tr><tr><td colspan=2>"._("Bei evtl. Sonderw&uuml;nschen der Kunden, diese bitte nachfolgend vermerken.")."</td></tr></table>";
		///////////////////////////////////////////////////////////////////////////
		
		$DB->data_seek(0,$res);
		
		///////////////////////////////////////////////////////////////////////////
		
		echo "<div style=\"font-size: 44px; page-break-before:always;\"><b><i>"._("Catering Bestellungen")."</i></b></div>\n<br />\n";
		echo "<table border=0>\n";
		echo "\t<tr><td width=150><b>"._("Datum/Uhrzeit")."</b></td><td>".strftime("%d. %B %Y %H:%M",time())."</td></tr>\n";
		echo "\t<tr><td><b>"._("Druck durch")."</b></td><td>".html::entities($CURRENT_USER->vorname." ".$CURRENT_USER->nachname)."</td></tr>\n";
		echo "</table>\n<br />\n<br />\n";
		echo "<table cellspacing=0 cellpadding=5 border=0 width=\"100%\">\n";
		echo "\t<tr valign=top>\n\t\t<th>"._("Person")."</th>\n\t\t<th>"._("Sitzplatz")."</th>\n\t\t<th><center>"._("Nr")."</center></th>\n\t\t<th>"._("Bestellung")."</th>\n\t\t<th><center>"._("Check")."</center></th>\n\t</tr>\n";

		$last_nr	= FALSE;
		while($data = $DB->fetch_array($res)) {
			$style	= (($last_nr!=$data['order_id'])?"border-top: 2px solid black; ":"");
			$cssstyle	= ($style!="") ? $cssstyle = " style=\"".$style."\" " : "";
			echo "\t<tr>\n";
			if($data['user_id'] == -1) {
				echo "\t\t<td style=\"".$style."border-left:2px solid black;\"><i>"._("Barverkauf")."&nbsp;</i></td>\n";
			} else {
				echo "\t\t<td style=\"".$style."border-left:2px solid black;\" valign=\"middle\"><b>".html::entities($data['nick'])."</b><br /><span style=\"font-size: 11px;\">".html::entities($data['vorname'])." ".html::entities($data['nachname'])."</span>&nbsp;</td>\n";
			}
			echo "\t\t<td".$cssstyle.">".((intval($data['sitz_id'])>0)?html::entities($data['sitz_nr']):"")."&nbsp;</td>\n";
			echo "\t\t<td style=\"".$style."font-size: 18px; border-left:2px solid black;\" align=center bgcolor=\"#eeeeee\">".html::entities($data['order_nr'])."&nbsp;</td>\n";
			echo "\t\t<td".$cssstyle." valign=\"middle\"><b>".html::entities($data['name'])."</b><br /><span style=\"font-size: 11px;\">".html::entities($data['description'])."</span>&nbsp;</td>\n";
			echo "\t\t<td style=\"".$style."border-right:2px solid black; padding-top: 3px; padding-bottom: 3px;\" align=\"center\" valign=\"center\"><table cellspacing=0 cellpadding=0 style=\"border-top: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; width: 20px; height: 20px;\"><tr><td>&nbsp;</td></table></td>\n";
			echo "\t</tr>\n";
			$last_nr = $data['order_id'];			
		
		}
		echo "\t<tr>\n\t\t<td colspan=6 style=\"border-top:2px solid black;\">&nbsp;</td>\n\t</tr>\n</table>\n";
		
		

		echo "</body>\n</html>";
		die();
			
	}
?>