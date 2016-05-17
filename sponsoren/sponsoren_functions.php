<?php

function sponsoren_list_sponsor($event_id)
{
	$sql = mysql_query("SELECT * FROM project_sponsoren_artikel AS a LEFT JOIN project_sponsoren AS s ON s.id = a.s_id WHERE a.event_id = '".$event_id."' GROUP BY a.s_id ORDER BY s.name,a.s_id,a.sp_art_name ASC");
	return $sql;
}
function sponsoren_list_artikel_by_sponsor($sponsor_id)
{
	$sql = mysql_query("SELECT * FROM project_sponsoren_artikel WHERE s_id = '".$sponsor_id."' ORDER BY s_id,sp_art_name ASC");
	return $sql;
}
function sponsoren_list_sponsor_single($sponsor_id)
{
	$out = mysql_fetch_array( mysql_query("SELECT * FROM project_sponsoren WHERE id = '".$sponsor_id."'"));
	return $out;
}
function sponsoren_ges_wert($anz,$wert)
{
	$ges_wert = ( $anz * $wert );
	
	return $ges_wert." €";
}

?>