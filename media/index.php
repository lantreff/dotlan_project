<?php
########################################################################
# Equipment Verwaltungs Modul for dotlan                               #
#                                                                      #
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# admin/equipment/index.php - Version 1.0                              #
########################################################################


$MODUL_NAME = "media";
include_once("../../../global.php");
include("../functions.php");
include("media_functions.php");

include('header.php');

if(!$DARF["view"] ) $PAGE->error_die($HTML->gettemplate("error_nopermission"));
else
{// $module_admin_check
	

//$data = $DB->query("SELECT * FROM `t_contest` WHERE DATE_FORMAT( `starttime`, '%j' ) BETWEEN DATE_FORMAT( NOW() , '%j' )  AND DATE_FORMAT( DATE_ADD( NOW(), INTERVAL 1 HOUR), '%j') AND `user_id` != '-1' ORDER BY starttime DESC");
$sql_t_groups = $DB->query("SELECT * FROM `t_groups` WHERE `active` = '1' ORDER BY name ASC");

 /*###########################################################################################
Admin PAGE
*/
if($_GET['hide'] != 1)
{
$output .=  " <form action='?hide=1&action=show' method='POST'>
<table cellspacing='1' cellpadding='2' border='0' width='850'>
  <tbody>";

 $iCount = 0;

 while($out_data = $DB->fetch_array($sql_t_groups))
					{// begin while
//	$sql_t_turnier = $DB->query("SELECT * FROM `t_turnier` WHERE `teventid` = '".$event_id."' AND `tgroupid` = '".$out_data['id']."' AND `tactive` != '1' ORDER BY tname ASC");
	$sql_t_turnier = $DB->query("SELECT * FROM `t_turnier` WHERE `teventid` = '".$event_id."' AND `tgroupid` = '".$out_data['id']."' ORDER BY tstart,tname ASC");


					$output .=  "
					  <tr valign='middle' class='msgrow2' style='cursor: pointer;'>

						<td colspan='2' nowrap='' align='left'><b>".$out_data['name']."</b></td>
						
					 </tr>";
					 
 while($out_data = $DB->fetch_array($sql_t_turnier))
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
			<input name='senden' value='Anzeigen' type='submit'>
			</tbody>
			</table>
			</form>

";
}	
		$turnier_ids = array();
		if($_GET['action'] == "show")
		{
			//$turnier_ids = $_POST['turniere'];
			//$count_ids = count($turnier_ids);
			$i=0;
			foreach($_POST['turniere'] as $tids){
				
			
			//$output .= listturnierbegegnung($_GET['tid']);
				//$t_contest = $DB->query("SELECT * FROM t_contest WHERE tid = ".$tids."  AND ( ready_a != '0000-00-00 00:00:00' AND ready_b != '0000-00-00 00:00:00') AND ( wins_a = 0 AND wins_b = 0) AND ( team_a != -1 AND team_b != -1) ORDER BY tcrunde,starttime DESC LIMIT 1 "); // LIMIT 1
				$t_contest = $DB->query("SELECT * FROM t_contest WHERE tid = ".$tids."  AND ( team_a != -1 AND team_b != -1) ORDER BY tcrunde,starttime DESC LIMIT 1 "); // LIMIT 1
				$t_turnier = $DB->fetch_array( $DB->query("SELECT * FROM t_turnier WHERE tid = ".$tids." LIMIT 1"));
				if(mysql_num_rows($t_contest) != 0)
				{
				
				$output .="
				<table width='100%'>
				<tr>
				<td class='msghead' colspan='5'> <b>".$t_turnier['tname']." </b></td>
				</tr>
							<tr>
								<td  nowrap=\"nowrap\"><b>Runde</b></td>
								<td  nowrap=\"nowrap\"><b>Team A</b></td>
								<td  nowrap=\"nowrap\"><b>Team B&nbsp;</b></td>
								<td  nowrap=\"nowrap\"><b>NIX</b></td>
								<td  nowrap=\"nowrap\"><b>NIX</b></td>
							</tr>";
				while ($turnier = $DB->fetch_array($t_contest)){
				  $output .='<tr class="msgrow2">
								<td>
									'.get_round($t_turnier['tplaytype'],$turnier['tcrunde']).'
								</td>
								<td width="38%" >
									<table cellspacing="0" cellpadding="0" width="100%" border="0">
										<tbody>
											<tr>
												<td>
													<a href="/turnier/?do=contest&amp;id='.$turnier['tcid'].'">';
													
													$team_a = $DB->fetch_array($DB->query("SELECT * FROM t_teilnehmer WHERE tnid = ".$turnier['team_a']." LIMIT 1"));
													
					$output .='						
													'.$team_a['tnname'].'
													
							';
													
													
					$output .='						</a>
												</td>
												<td align="right" class="small">
												';
													
													//$sitz_a = $DB->fetch_array($DB->query("SELECT * FROM event_teilnehmer WHERE event_id = ".$event_id." AND user_id = ".$team_a['tnleader']." "));
					$output .='						
													&nbsp; '.$sitz_a['sitz_nr'].'
							';
													
													
					$output .='																			
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
													
													$team_b = $DB->fetch_array($DB->query("SELECT * FROM t_teilnehmer WHERE tnid = ".$turnier['team_b']." LIMIT 1"));
													
													
					$output .='						
													'.$team_b['tnname'].'
													
							';
													
													
					$output .='						</a>
												</td>
												<td align="right" class="small">
													';
													
													//$sitz_b = $DB->fetch_array($DB->query("SELECT * FROM event_teilnehmer WHERE event_id = ".$event_id." AND user_id = ".$team_b['tnleader']." "));
					$output .='						
													&nbsp; '.$sitz_b['sitz_nr'].'
							';
													
													
					$output .='																			
												</td>
											</tr>
										</tbody>
									</table>
								</td>
								<td valign="middle" align="center">
									<div style="white-space: nowrap;">
										<a href="/turnier/?do=contest&amp;id='.$turnier['tcid'].'#postings">
											<img width="16" height="16" border="0" align="absmiddle" title="Es liegen Kommentare zu dieser Begegnung vor." alt="Comments" src="/images/admin/icon_edit.gif">
										</a>
									</div>
								</td>
								<td width="1" align="center">
									<b>
										<div style="white-space: nowrap; padding-left: 6px; padding-right: 6px;">
											<a href="/turnier/?do=contest&amp;id=12412">
												0 : 1
											</a>
										</div>
									</b>
								</td>
							</tr>
						';
				}
				$output .="</table> <br> <br>";
			}
			}
			if(mysql_num_rows($t_contest) == 0)
				{
					$output .= "<h2> Keie Daten! <h2>";
				}
		}
}
$PAGE->render($output);
?>
