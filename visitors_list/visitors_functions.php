<?php

function list_visitors()
{
	$sql_visitors = "SELECT * FROM `project_visitors_list`";
	$visitors_list =  mysql_query( $sql_visitors);
	return $visitors_list;
}
function list_single_visitor($id)
{
	$sql = "SELECT * FROM `project_visitors_list` WHERE id = '".$id."' ";
	$out =  mysql_fetch_array( mysql_query( $sql));
	return $out;
}

function list_single_visitor_with_cardnr($cardnr)
{
	$sql = "SELECT * FROM `project_visitors_list` WHERE card_nr = '".$cardnr."' ";
	$out =  mysql_fetch_array( mysql_query( $sql));
	return $out;
}

function list_visitors_log()
{
	$sql = "SELECT * FROM `project_visitors_log` ";
	$out =  mysql_query( $sql);
	return $out;
}
function list_cards()
{
	$sql_cards = "SELECT * FROM `project_visitors_cards` ";
	$cards_list =  mysql_query( $sql_cards);
	return $cards_list;
}
function list_cards_not_used()
{
	$sql_cards = "SELECT t1.id,t1.nr,t1.bezeichnung FROM project_visitors_cards AS t1 WHERE t1.id NOT IN ( SELECT t2.card_nr FROM project_visitors_list AS t2 WHERE t2.card_nr IS NOT NULL )";
	$cards_list =  mysql_query( $sql_cards);
	return $cards_list;
}
function list_single_card($id)
{
	$sql = "SELECT * FROM `project_visitors_cards` WHERE id = ".$id."; ";
	$out =  mysql_fetch_array( mysql_query( $sql));
	return $out;
}

function is_cards_used($id)
{
	$sql = "SELECT * FROM `project_visitors_list` WHERE card_nr = ".$id.";";
	$out =  mysql_fetch_array( mysql_query( $sql));
	if($out['id']){
		return TRUE;
	}
	else{
		return FALSE;
	}
}
function geht_melden($geht_id,$kommt,$datum)
{
	$out		= list_single_visitor($geht_id);
	
	$sql_geht_meldung = "UPDATE `project_visitors_list` SET `geht` = '".$datum."', `card_nr` = '0' WHERE `id` = ".$geht_id.";";
	$geht_meldung =  mysql_query( $sql_geht_meldung);

	$sql_log_meldung = "UPDATE `project_visitors_log` SET `geht` = '".$datum."' WHERE `visitor_id` = '".$geht_id."' AND `kommt` = '".$kommt."';";
	$geht_log_meldung =  mysql_query( $sql_log_meldung);
	
	$meldung = "Das Geht wurde gemeldet!";
	return $meldung;
}
function bezahlt_melden($bezahlt_id)
{
	$sql_bezahlt_meldung = "UPDATE `project_visitors_list` SET `bezahlt` = 1 WHERE `id` = ".$bezahlt_id.";";
	$bezahlt_meldung =  mysql_query( $sql_bezahlt_meldung);
	
	$meldung = "Daten gesendet!";
	return $meldung;
}
function card_nr_melden($card_nr_id,$card_nr,$datum)
{
	$out1 = list_single_visitor($card_nr_id);
	
	$sql_card_nr_meldung = "UPDATE `project_visitors_list` SET `kommt` = '".$datum."', `geht` = '0000-00-00 00:00:00', `card_nr` = ".$card_nr." WHERE `id` = ".$card_nr_id 	.";";
	$card_nr_meldung =  mysql_query( $sql_card_nr_meldung);

	if($out1['geht'] != "0000-00-00 00:00:00")
	{
		$sql_log_meldung1 = "INSERT INTO `project_visitors_log` (`id`, `visitor_id`, `date`, `vorname`, `nachname`, `kommt`, `geht`, `cardnr`) 
														VALUES (NULL, '".$card_nr_id."', '".$datum."', '".$out1['vorname']."', '".$out1['nachname']."', '".$datum."', '0000-00-00 00:00:00', '".$card_nr."');";
		$geht_log_meldung1 =  mysql_query( $sql_log_meldung1);
	}
	if($out1['geht'] == "0000-00-00 00:00:00")
	{
		$sql_log_meldung2 = "INSERT INTO `project_visitors_log` (`id`, `visitor_id`, `date`, `vorname`, `nachname`, `kommt`, `geht`, `cardnr`) 
														VALUES (NULL, '".$card_nr_id."', '".$datum."', '".$out1['vorname']."', '".$out1['nachname']."', '".$datum."', '0000-00-00 00:00:00', '".$card_nr."');";
		$geht_log_meldung2 =  mysql_query( $sql_log_meldung2);
	}
	
	$meldung = "Die Card-Nr. wurde gespeichert!";
	return $meldung;
}

function card_add($daten)
{
	$sql = "INSERT INTO `project_visitors_cards` (`id`, `nr`, `bezeichnung`) VALUES (NULL, '".$daten['nr']."', '".$daten['bezeichnung']."');";
	$out =  mysql_query($sql);
	
	$meldung = "Die Daten wurde gespeichert!";
	return $meldung;
}

function card_edit($daten,$id)
{
	$sql = "UPDATE `project_visitors_cards` SET `nr` = '".$daten['nr']."', `bezeichnung` = '".$daten['bezeichnung']."' WHERE `id` = '".$id."';";
	$out =  mysql_query( $sql);
	
	$meldung = "Die Daten wurde gespeichert!";
	return $meldung;
}
function card_del($id)
{
	$sql = "DELETE FROM `project_visitors_cards` WHERE `id` = '".$id."';";
	$out =  mysql_query( $sql);
	
	$meldung = "Die Daten wurde gespeichert!";
	return $meldung;
}
function anwesenheit($kommt,$geht)
{	
	$datetime1 = date_create($kommt);
	$datetime2 = date_create($geht);
	
	if($geht == "0000-00-00 00:00:00")
	{
		$datetime2 = date_create(date("Y-m-d H:i:s"));
	}
	
	
	$interval = date_diff($datetime1, $datetime2);
	$anwesenheit = $interval->format('%h:%I:%s');  
	return $anwesenheit;	 
}
?>