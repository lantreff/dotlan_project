<?php

$MODUL_NAME = "media";

// sql abfragen
$sql_event_ids = $DB->query("SELECT * FROM events ORDER BY begin DESC");

// Benutzer
function listturnierbegegnung($tid){

$query = $DB->query("SELECT * FROM t_contest WHERE tid = ".$tid." ORDER BY starttime DESC;");
$output .="<table>
			<tr>
				<td  nowrap=\"nowrap\"><b>Nick</b>&nbsp;</td>
				<td  nowrap=\"nowrap\"><b>Vorname</b></td>
				<td  nowrap=\"nowrap\"><b>Nachname&nbsp;</b></td>
				<td  nowrap=\"nowrap\"><b>Last Login IP&nbsp;</b></td>
				<td  nowrap=\"nowrap\"><b>Last Login&nbsp;</b></td>
				<td  nowrap=\"nowrap\"><b>Admin Level&nbsp;</b></td>
				<td  nowrap=\"nowrap\"><b>Action&nbsp;</b></td>
			</tr>";
while ($turnier = $DB->fetch_array($query)){
  $output .="<tr><td  nowrap=\"nowrap\">".$turnier['team_a']."</td><td >".$turnier['vorname']."</td><td  nowrap=\"nowrap\">".$turnier['nachname']."</td><td  nowrap=\"nowrap\">&nbsp;".$turnier['login_ip']."</td><td  nowrap=\"nowrap\"></td><td  nowrap=\"nowrap\"></td><td class=\"anmeldung_data\" nowrap=\"nowrap\"><a href=\"admin.php?action=change&user=".$turnier['id']."\">editieren</a>|<a href=\"admin.php?action=delete&user=".$turnier['id']."\">löschen</a></td></tr>";
}
$output .="</table>";
return $output;
}

function get_round($mode,$round)
	{
		
		if($round == 8)		$title = "NIX";
		if($round == 7)		$title = "NIX";
		if($round == 6)		$title = "2. Finale";
		if($round == 5)		$title = "Finale";
		if($round == 4)		$title = "Halbfinale";
		if($round == 3)		$title = "Runde 3";
		if($round == 2)		$title = "Runde 2";
		if($round == 1)		$title = "Runde 1";
		if($round == 0)		$title = "Start";
		if($round == (-1))	$title = "Runde 1 LB";
		if($round == (-2))	$title = "Runde 1.5 LB";
		if($round == (-3))	$title = "Runde 2 LB";
		if($round == (-4))	$title = "Runde 2.5 LB";
		if($round == (-5))	$title = "Runde 3 LB";
		if($round == (-6))	$title = "Runde 3.5 LB";
		if($round == (-7))	$title = "Runde 4 LB";
		if($round == (-8))	$title = "Halbfinale LB";
		
		return $title;
	}
/*
	function get_round($mode,$round)
	{
		if($col == 0)								$title = "Start";
		elseif($col == $this->colcount && $this->tplaytype == "single")		$title = "Finale";
		elseif($col == $this->colcount-1 && $this->tplaytype == "single")	$title = "Halbfinale";
		elseif($col == $this->colcount)						$title = "Halbfinale";
		elseif($col == -($this->colcount*2))					$title = "Halbfinale LB";
		elseif($col > 0 && $col <= $this->colcount)				$title = sprintf("Runde %d"),abs($col));
		elseif($col == $this->colcount+1 && $this->tplaytype == "double")	$title = "Finale";
		elseif($col == $this->colcount+2 && $this->tplaytype == "double")	$title = "2. Finale";
		elseif($col < 0 && abs($col)%2)						$title = sprintf("Runde %s LB"),floor(abs($col)/2+1));
		elseif($col < 0 && !($col%2))						$title = sprintf("Runde %s LB"),floor((abs($col)-1)/2+1).".5");
		return $title;
	}
*/
?>