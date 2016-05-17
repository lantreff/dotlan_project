<?php
########################################################################
# Equipment Verwaltungs Modul for dotlan                               #
#                                                                      #
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# admin/equipment/index.php - Version 1.0                              #
########################################################################

$MODUL_NAME = "catering_order";
include_once("../../../global.php");
include("../functions.php");
$event_id = $EVENT->next;
$product_group = 17; // Produktgruppe für Catering Bestellungen
$num_orders = 10;
$max_orders = 20;
$debug = 0;
if(!$DARF["view"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

$PAGE->sitetitle = $PAGE->htmltitle = _("Catering Order Modul");

if ($_GET['action'] == "r4c" && is_numeric($_GET['order_id'])){

	$order_id = security_number_int_input($_GET['order_id'],'','');
	$order_query = $DB->query("UPDATE catering_order_part SET catering_order_part.status = 4, catering_order_part.time_changed = '{$datum}' WHERE order_id = '{$order_id}' AND catering_order_part.status = 3");

	## 1 PM an User, dass die Bestellung nu abholbereit ist
	$pmquery1 = $DB->query("SELECT * FROM catering_order_part WHERE order_id = '{$order_id}' AND catering_order_part.status = 4");
	while($pm = $DB->fetch_array($pmquery1)) {
		$subject = $global['sitename'].' - Statusänderung Bestellung Nr. '.$pm['order_id'].' - '.$pm['name'];
		$message = "Der Status deiner Bestellung mit der Nr. ".$pm['order_id']." - ".$pm['name']." wurde soeben auf\n\n---> Abholbereit <---\n\ngeändert.\n\nFür weitere Infos stehen wir dir gerne zur Verfügung. Desweiteren kannst du den Status deiner Bestellung ebenfalls weiter im Intranet verfolgen.";
		$PRVMSG->generate_message($pm['user_id'],"INBOX",$pm['user_id'],0,$subject,$message);
		## $PRVMSG->generate_message(1948,"INBOX",1948,0,"TEST Nachricht","<b>TEST</b>");
	}
	print "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
}

$oquery = $DB->query("SELECT
					catering_order_part.order_id,
					catering_order_part.order_nr,
					catering_order_part.user_id,
					catering_order_part.name AS product_name,
					catering_order_part.price,
					catering_order_part.time_added,
					catering_order_part.time_changed,
					catering_order_part.status,
					user.nick,
					event_teilnehmer.sitz_nr,
					event_teilnehmer.sitz_id
 					FROM `catering_order_part` LEFT JOIN catering_products ON (catering_products.id = catering_order_part.product_id AND catering_products.group_id = '{$product_group}') LEFT JOIN user ON catering_order_part.user_id = user.id LEFT JOIN event_teilnehmer ON (event_teilnehmer.user_id = catering_order_part.user_id AND event_teilnehmer.event_id = '{$event_id}') WHERE catering_order_part.status = 2 AND catering_products.group_id = '{$product_group}' ORDER BY time_added ASC");
if ($debug == 1){
	$output .= "foo";
}
$output .="<h1>Aktuelle wartenden Bestellungen</h1>";
$output .= "<table class='msg2' cellspacing='1' cellpadding='2' border='0'><tbody><tr><td width='70' class='msghead'><b>BNr</b></td><td class='msghead'><b>Nr</b></td><td class='msghead'><b>Produktname</b></td><td class='msghead'><b>Benutzer</b></td><td class='msghead'><b>Sitz</b></td><td class='msghead'><b>Preis</b></td><td class='msghead'><b>Datum</b></td><td class='msghead'><b>Änderung</b></td><td class='msghead'><b>Status</b></td>";
if ($debug == 1){
	$output .= "<td class='msghead'><b>Counter</b></td>";
}
$iCounter = 1;
$kCounter = 1;
$lCounter = 1;
$order_pre = '';
## Initial Order Query for number of min/max order_id
$init_query1 = $DB->query("SELECT min(t.order_id) AS min_id, max(t.order_id) AS max_id FROM (SELECT catering_order_part.order_id FROM `catering_order_part` LEFT JOIN catering_products ON (catering_products.id = catering_order_part.product_id AND catering_products.group_id = '{$product_group}') LEFT JOIN user ON catering_order_part.user_id = user.id LEFT JOIN event_teilnehmer ON (event_teilnehmer.user_id = catering_order_part.user_id AND event_teilnehmer.event_id = '{$event_id}') WHERE catering_order_part.status = 2 AND catering_products.group_id = '{$product_group}' ORDER BY time_added ASC LIMIT 20) AS t");
while ($initdata = $DB->fetch_array($init_query1)){
	$init_min = $initdata['min_id'];
	$init_max = $initdata['max_id'];
}
$init_query2 = $DB->query("SELECT * FROM catering_order_part WHERE order_id >= '{$init_min}' AND order_id <= '{$init_max}' AND catering_order_part.status = 2");
$num_init_values = mysql_num_rows($init_query2);

if ($debug == 1){
	$output .= '<br>ID min: '.$init_min.' | ID max: '.$init_max.' | num: '.$num_init_values.'<br><br>';
}

while($orders = $DB->fetch_array($oquery)){


	if($iCounter % 2 == 0){
		$currentRowClass = 'class="msgrow1"';
    } else {
		$currentRowClass = 'class="msgrow2"';
	}

	if ($iCounter % $num_orders == 0){
		// $special_style = ' style="border-bottom: 2px solid #aa0000;" ';
	} else {
		$special_style = '';
	}


    if($orders['order_id'] == $order_id_pre){
		$special_style = '';
	} elseif ($orders['order_id'] > $order_id_pre && $kCounter > $num_orders){
		$special_style = ' style="border-top: 2px solid #aa0000;" ';
		// $special_style = '';
		$kCounter = 1;
	}

	if($kCounter == 1){
		$first_order_id = $orders['order_id'];
	}


	if($kCounter == $num_orders){
		$last_order_id = $order_id_pre;
	}

	if ($iCounter == 1){
		$special_style = ' style="border-top: 2px solid #aa0000;" ';
		if ($DARF["print_order"]) {
			$order_now = '<td style="border-top: 2px solid #aa0000;" rowspan="'.$num_init_values.'" align="center"><a href="print.php?order_id_min='.$init_min.'&order_id_max='.$init_max.'&action=bp" target="_blank"';
			$order_now .= "onClick=\"return confirm ('Willst du wirklich die Bestellungen auf &quot; geordert &quot setzen und die Bestellliste in einem neuen Fenster zum Ausdrucken anzeigen?')\">";
			$order_now .='Bestellen (Artikel geordert) und Drucken</a>';
			if ($debug == 1){
				$order_now .= '<br>fo '.$init_min.'<br>lo '.$init_max;
			}
			$order_now .= '</td>';
		} else {
			$order_now = '<td style="border-top: 2px solid #aa0000;" rowspan="'.$num_init_values.'" align="center">Bestellung möglich, du hast allerdings keine Rechte!</td>';
		}
		$lCounter = 1;
	} elseif($orders['order_id'] > $order_id_pre && $lCounter > $num_orders){
		$order_now = '<td style="border-top: 2px solid #aa0000;">&nbsp;</td>';
		$lCounter = 1;
	} else {
		$order_now = '';
	}



	if($orders['status'] == 2){
		$status = 'Bezahlt';
	}
	if($orders['time_changed'] == '0000-00-00 00:00:00'){
		$time_changed = 'keine';
	} else {
		$time_changed = $orders['time_changed'];
	}
	$output .= '<tr '.$currentRowClass.'><td '.$special_style.'><b><'.$orders['order_id'].'></b></td><td '.$special_style.'><i>'.$orders['order_nr'].'</i></td><td '.$special_style.'>'.$orders['product_name'].'</td><td align="left" '.$special_style.'><a href="{BASEDIR}user/?id='.$orders['user_id'].'">'.$orders['nick'].'</a></td><td align="left" '.$special_style.'><a href="{BASEDIR}party/?do=seats&id='.$orders['sitz_id'].'&highlight=42261">'.$orders['sitz_nr'].'</td><td align="right" '.$special_style.'><b>'.$orders['price'].' &euro;</b></td><td '.$special_style.'><i>'.$orders['time_added'].'</i></td><td '.$special_style.'><i>'.$time_changed.'</i></td><td '.$special_style.'><i>'.$status.'</i></td>';
	if ($debug == 1){
		$output .='<td '.$special_style.'>i: '.$iCounter.'<br>k: '.$kCounter.'<br>l: '.$lCounter.'</i></td>';
	}
	$output .= $order_now.$orderings.'</tr>';
	$sum = $sum + $user['to_pay'];
	$iCounter++;
	$kCounter++;
	$lCounter++;
	$order_id_pre_pre = $order_id_pre;
	$order_id_pre = $orders['order_id'];
}

$output .="</tbody></table>";

$output .="<br><br><h1>Georderte Bestellungen - Ätere Bestellungen weiter oben</h1>";

$best_query = $DB->query("SELECT * FROM project_catering_bestellungen WHERE event_id = '".$event_id."' ORDER BY id DESC");
while($bestellung = $DB->fetch_array($best_query)){
	$gquery = $DB->query("SELECT
						catering_order_part.order_id,
						catering_order_part.order_nr,
						catering_order_part.user_id,
						catering_order_part.name AS product_name,
						catering_order_part.price,
						catering_order_part.time_added,
						catering_order_part.time_changed,
						catering_order_part.status,
						user.nick,
						event_teilnehmer.sitz_nr,
						event_teilnehmer.sitz_id
	 					FROM `catering_order_part` LEFT JOIN catering_products ON (catering_products.id = catering_order_part.product_id AND catering_products.group_id = '{$product_group}') LEFT JOIN user ON catering_order_part.user_id = user.id LEFT JOIN event_teilnehmer ON (event_teilnehmer.user_id = catering_order_part.user_id AND event_teilnehmer.event_id = '{$event_id}') WHERE catering_order_part.status = 3 AND catering_products.group_id = '{$product_group}' ORDER BY time_changed, order_nr, time_added ASC");
	if ($debug == 1){
		$output .= "foo";
	}
	$output .= "<table class='msg2' cellspacing='1' cellpadding='2' border='0'><tbody><tr><td width='70' class='msghead'><b>BNr</b></td><td class='msghead'><b>Nr</b></td><td class='msghead'><b>Produktname</b></td><td class='msghead'><b>Benutzer</b></td><td class='msghead'><b>Sitz</b></td><td class='msghead'><b>Preis</b></td><td class='msghead'><b>Datum</b></td><td class='msghead'><b>Änderung</b></td><td class='msghead'><b>Status</b></td>";
	
	
	$iCounter = 1;
	$kCounter = 1;
	$lCounter = 1;
	$order_pre = '';
	## Initial Order Query for number of min/max order_id
	$init_query1 = $DB->query("SELECT min(t.order_id) AS min_id, max(t.order_id) AS max_id FROM (SELECT catering_order_part.order_id FROM `catering_order_part` LEFT JOIN catering_products ON (catering_products.id = catering_order_part.product_id AND catering_products.group_id = '{$product_group}') LEFT JOIN user ON catering_order_part.user_id = user.id LEFT JOIN event_teilnehmer ON (event_teilnehmer.user_id = catering_order_part.user_id AND event_teilnehmer.event_id = '{$event_id}') WHERE catering_order_part.status = 2 AND catering_products.group_id = '{$product_group}' ORDER BY time_added ASC LIMIT 20) AS t");
	while ($initdata = $DB->fetch_array($init_query1)){
		$init_min = $initdata['min_id'];
		$init_max = $initdata['max_id'];
	}
	$init_query2 = $DB->query("SELECT * FROM catering_order_part WHERE order_id >= '{$init_min}' AND order_id <= '{$init_max}' AND catering_order_part.status = 2");
	$num_init_values = mysql_num_rows($init_query2);
	
	if ($debug == 1){
		$output .= '<br>ID min: '.$init_min.' | ID max: '.$init_max.' | num: '.$num_init_values.'<br><br>';
	}
	
	while($gorders = $DB->fetch_array($gquery)){
	
		if($iCounter % 2 == 0){
			$currentRowClass = 'class="msgrow1"';
	    } else {
			$currentRowClass = 'class="msgrow2"';
		}
	
		if ($DARF["print_order"]) {
			$collect_now = '<td '.$special_style.'><a href="index.php?order_id='.$gorders['order_id'].'&action=r4c">Abholbereit</a></td>';
		} else {
			$collect_now = '<td '.$special_style.'></td>';
		}
	
		if($gorders['status'] == '3'){
			$status = 'Geordert';
		} else {
			$status = '';
		}
		if($gorders['time_changed'] == '0000-00-00 00:00:00'){
			$time_changed = 'keine';
		} else {
			$time_changed = $gorders['time_changed'];
		}
		$output .= '<tr '.$currentRowClass.'><td '.$special_style.'><b><'.$gorders['order_id'].'></b></td><td '.$special_style.'><i>'.$gorders['order_nr'].'</i></td><td '.$special_style.'>'.$gorders['product_name'].'</td><td align="left" '.$special_style.'><a href="{BASEDIR}user/?id='.$gorders['user_id'].'">'.$gorders['nick'].'</a></td><td align="left" '.$special_style.'><a href="{BASEDIR}party/?do=seats&id='.$gorders['sitz_id'].'&highlight=42261">'.$gorders['sitz_nr'].'</td><td align="right" '.$special_style.'><b>'.$gorders['price'].' &euro;</b></td><td '.$special_style.'><i>'.$gorders['time_added'].'</i></td><td '.$special_style.'><i>'.$time_changed.'</i></td><td '.$special_style.'><i>'.$status.'</i></td>'.$collect_now.'</tr>';
		$sum = $sum + $user['to_pay'];
		$iCounter++;
	}
	$output .="</tbody></table> <br><br><br>";
}
$PAGE->render(utf8_decode(utf8_encode($output) ));
?>
