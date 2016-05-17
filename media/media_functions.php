<?php

$MODUL_NAME = "media";

// sql abfragen
$sql_event_ids = mysql_query("SELECT * FROM events ORDER BY begin DESC");

function list_turniere($event_id){
global $DB;

$sql_t_groups = mysql_query("SELECT * FROM `t_groups` WHERE `active` = '1' ORDER BY name ASC");

$output .=  " <form action='?hide=1&action=show' method='POST'>
<table cellspacing='1' cellpadding='2' border='0' width='850'>
  <tbody>";

 $iCount = 0;

 while($out_data = mysql_fetch_array($sql_t_groups))
					{// begin while
//	$sql_t_turnier = mysql_query("SELECT * FROM `t_turnier` WHERE `teventid` = '".$event_id."' AND `tgroupid` = '".$out_data['id']."' AND `tactive` != '1' ORDER BY tname ASC");
	$sql_t_turnier = mysql_query("SELECT * FROM `t_turnier` WHERE `teventid` = '".$event_id."' AND `tgroupid` = '".$out_data['id']."' ORDER BY tstart,tname ASC");


					$output .=  "
					  <tr valign='middle' class='msgrow2' style='cursor: pointer;'>

						<td colspan='2' nowrap='' align='left'><b>".$out_data['name']."</b></td>
						
					 </tr>";
					 
 while($out_data = mysql_fetch_array($sql_t_turnier))
					{// begin while					 
					 
$output .=  "		 <tr>
						<td>".$out_data['tname']."</td>
						<td> <!-- <a href='?hide=1&action=show&tid=".$out_data['tid']."' target='_parent'></a> -->
							<input name='turniere[]' value='".$out_data['tid']."' type='checkbox' id='".$out_data['tid']."'></td>
					</tr>
			";
}					

  			$iCount++;
					}
$output .=  "
			<input name='event' value='".$event_id."' type='hidden'>
			<input name='senden' value='Anzeigen' type='submit'>
			</tbody>
			</table>
			</form>

";
return $output;
}


function list_begegnung($t_ids, $event_id){
global $DB;
//$turnier_ids = $_POST['turniere'];
			//$count_ids = count($turnier_ids);
				$output.= "
				<form action='?hide=1&action=show' method='POST'>
				<input name='event' value='".$event_id."' type='hidden'>
				
				<input name='senden' value='Aktualisieren' type='submit'>
				";			
			$i=0;
			foreach($t_ids as $tids){
				
				$t_contest = mysql_query("SELECT * FROM t_contest WHERE tid = ".$tids."  AND ( ready_a != '0000-00-00 00:00:00' AND ready_b != '0000-00-00 00:00:00') AND ( wins_a = 0 AND wins_b = 0) AND ( team_a != -1 AND team_b != -1) ORDER BY tcrunde,starttime DESC LIMIT 1 "); // LIMIT 1
				//$t_contest = mysql_query("SELECT * FROM t_contest WHERE tid = ".$tids."  AND ( team_a != -1 AND team_b != -1) ORDER BY tcrunde,starttime DESC LIMIT 1 "); // LIMIT 1
				$t_turnier = mysql_fetch_array( mysql_query("SELECT * FROM t_turnier WHERE tid = ".$tids." LIMIT 1"));
				if(mysql_num_rows($t_contest) != 0)
				{
				
				
				$output.= "
				<table cellspacing='1	' cellpadding='2 width='100%' border='0'>
				<tr>
				<td class='msghead' colspan='4'> <b>".$t_turnier['tname']." </b></td>
				<!-- <td class='msghead' > <b>".$t_turnier['tid']." </b></td> -->
				<input name='turniere[]' value='".$t_turnier['tid']."' type='hidden'>
				</tr>
							<tr>
								<td width='150'  nowrap=\"nowrap\"><b>Runde</b></td>
								<td  nowrap=\"nowrap\"><b>Team A</b></td>
								<td  nowrap=\"nowrap\"><b>Team B&nbsp;</b></td>
								<td  nowrap=\"nowrap\"><b>Kommentare</b></td>
							</tr>";
				while ($turnier = mysql_fetch_array($t_contest)){
				  $output.='<tr class="msgrow2">
								<td>
									'.get_round($t_turnier['tplaytype'],$turnier['tcrunde']).'
								</td>
								<td width="38%" >
									<table cellspacing="0" cellpadding="0" width="100%" border="0">
										<tbody>
											<tr>
												<td>
													<a href="/turnier/?do=contest&amp;id='.$turnier['tcid'].'">';
													
													$team_a = mysql_fetch_array(mysql_query("SELECT * FROM t_teilnehmer WHERE tnid = ".$turnier['team_a']." LIMIT 1"));
if($team_a['tnname'] != '')
{													
					$output.='						
													'.$team_a['tnname'].'
													
							';
}
else
{
						$user_a_name = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE id = ".$team_a['tnleader']." LIMIT 1"));
					$output.='						
													'.$user_a_name['nick'].'<br>
													
							';
}

													
													
					$output.='						</a>
												</td>
												<td align="right" class="small">
												';
													$tnleader_a = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE id = ".$team_a['tnleader']." "));
													$sitz_a = mysql_fetch_array(mysql_query("SELECT * FROM event_teilnehmer WHERE event_id = ".$event_id." AND user_id = ".$team_a['tnleader']." "));
													$sitznr_a = explode(" ",$sitz_a['sitz_nr']);
					$output.='						
													<b>Teamleader:</b> '.$tnleader_a['nick'].' &nbsp; <b>'.$sitznr_a[0].'</b> '.$sitznr_a[1].'
													
								
							';
													
													
					$output.='																			
												</td>
											</tr>
										</tbody>
									</table>
								</td>
								<td width="38%">
									<table cellspacing="0" cellpadding="0" width="100%" border="0">
										<tbody>
											<tr>
												<td>
													<a href="/turnier/?do=contest&amp;id='.$turnier['tcid'].'">';
													
													$team_b = mysql_fetch_array(mysql_query("SELECT * FROM t_teilnehmer WHERE tnid = ".$turnier['team_b']." LIMIT 1"));
if($team_b['tnname'] != '')
{													
					$output.='						
													'.$team_b['tnname'].'
													
							';
}
else
{
						$user_b_name = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE id = ".$team_b['tnleader']." LIMIT 1"));
					$output.='						
													'.$user_b_name['nick'].'
													
							';
}

													
													
					$output.='						</a>
												</td>
												<td align="right" class="small">
													';
													$tnleader_b = mysql_fetch_array(mysql_query("SELECT * FROM user WHERE id = ".$team_b['tnleader']." "));
													$sitz_b = mysql_fetch_array(mysql_query("SELECT * FROM event_teilnehmer WHERE event_id = ".$event_id." AND user_id = ".$team_b['tnleader']." "));
													$sitznr_b = explode(" ",$sitz_b['sitz_nr']);
					$output.='						
													<b>Teamleader:</b> '.$tnleader_a['nick'].' &nbsp; <b>'.$sitznr_b[0].'</b> '.$sitznr_b[1].'
							';
													
													
					$output.='																			
												</td>																							
											</tr>
										</tbody>
									</table>
								</td>';
								$sql_postings =mysql_query("SELECT * FROM forum_thread WHERE ext_id = ".$turnier['tcid']."");
								if(mysql_num_rows($sql_postings) != 0)
								{
$output.='	
								<td valign="middle" align="center">
									<div style="white-space: nowrap;">
										<a target="_NEW" href="/turnier/?do=contest&amp;id='.$turnier['tcid'].'#postings">
											<img width="16" height="16" border="0" align="absmiddle" title="Es liegen Kommentare zu dieser Begegnung vor." alt="Comments" src="/images/admin/icon_edit.gif">
										</a>
									</div>
								</td>';
								}
								
$output.='									
								
							</tr>
						';
				}
				$output.="</table> <br> <br>";
			}
			}
			$output.="</form>";
			if(mysql_num_rows($t_contest) == 0)
				{
					$output.= "<h2> Keie Daten! <h2>";
				}
				//$output .= '<meta http-equiv="refresh" content="10;url=index.php?hide=1&action=show&turniere='.$t_ids.'">';
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