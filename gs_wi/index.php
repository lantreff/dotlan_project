<?
########################################################################
# Sponsorenverwaltung Modul for dotlan                                 #
#                                                                      #
# Copyright (C) 2013 Christian Egbers <c.egbers@servious-networx.net>  #
#                                                                      #
########################################################################

include_once("../../../global.php");
include("../functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("SNX Server Verwaltung - Testing!"); // Tietel der als THML-Überschrift der Seite angezeigt wird
$event_id		= $EVENT->next;			// ID des anstehenden Event's

$date 			= date("Y.m.d");
$time			= date("H:i:s");

// auslesen der einzelnen Werte die über die Adresszeile übergeben werden
	$id				= $_GET['id'];
	$sponsor		= $_GET['sponsor'];
	$n_id 			= $_GET['n_id'];
////////////////////////////////////////////////

// auslesen der Eingabefelder für 'edit' | 'add' | 'usw...'
	$name 			= $_POST['name'];
	$str			= $_POST['str'];
	$hnr			= $_POST['hnr'];
	$plz			= $_POST['plz'];
	$ort			= $_POST['ort'];
	$homepage		= $_POST['homepage'];
	$email			= $_POST['email'];
	$tel			= $_POST['tel'];
	$formular		= $_POST['formular'];
	$status			= $_POST['status'];
	$comment		= $_POST['comment'];
	$add_kontakt_id = $_POST['add_kontakt_id'];
////////////////////////////////////////////////

// Sponsoren Artikel auslesen 
	$sp_art_anz		= $_POST['sp_art_anz'];  
	$sp_art_name	= $_POST['sp_art_name']; 
	$sp_art_wert	= $_POST['sp_art_wert']; 
////////////////////////////////////////////////

// Sortierung //
// Variablen für die Sortierfunktion
	$sort			= "starttime"; // Standardfeld das zum Sortieren genutzt wird
	$order			= "ASC"; // oder DESC | Sortierung aufwerts, abwerts

	if (IsSet ($_GET['sort'] ) )
	{
		$sort		= $_GET['sort'];
	}
	if (IsSet ($_GET['order'] ) )
	{
		$order		= $_GET['order'];
	}
////////////////////////////////////////////////

	function date_mysql2german($date1) {
    	$d    =    explode("-",$date1);
	return    sprintf("%02d.%02d.%04d", $d[2], $d[1], $d[0]);
}
 /*###########################################################################################
Admin PAGE
*/

			if (IsSet ($_POST['suche'] ) ) // nur wenn im fled suchen etwas eingegeben wurde wird in den eingetragenen spalten gesucht. diese können um noch weitere Ergänzt werden, dies kann einfach duch ein "OR" getrennt geschehen
			 {
				$sql_turnier = $DB->query("
													SELECT

														*
													FROM

														project_sponsoren
													WHERE  
														`name` 		LIKE  '%".$_POST['suche']."%' OR
														`homepage` 	LIKE  '%".$_POST['suche']."%' OR
														`email` 	LIKE  '%".$_POST['suche']."%' OR
														`formular` 	LIKE  '%".$_POST['suche']."%'																								
																												
												");
				}
				else {
				
				$sql_turnier = $DB->query("
													SELECT
														*
													FROM
														t_contest
													WHERE
														ready_a <> '0000-00-00 00:00:00'
													or
														ready_b <> '0000-00-00 00:00:00'
													ORDER BY

														".$sort."
														".$order."
														
																												
												");
				}
 
 

				
				
				$output .= 	"
								<table class='msg2' width='100%' cellspacing='1' cellpadding='2' border='0'>
									<tbody>
										<tr>
											<!-- <td   class='msghead'>
											Contest ID	
											</td> -->
											<td   class='msghead' width='80'>
											Logo
											</td>
											<td   class='msghead'>
											Team
											</td>
											<td   class='msghead'>
											dateline
											</td>
											<td   class='msghead'>
											host
											</td>
											<td   class='msghead'>
											UserID
											</td>
											<td   class='msghead'>
											Startzeit
											</td>
											<td   class='msghead'>
											Ready A
											</td>
											<td   class='msghead'>
											Ready B
											</td>
											<td   class='msghead'>
											Def WIN
											</td>
										</tr>";
			$iCount = 0;	
			 while($out_turnier = $DB->fetch_array($sql_turnier))
			{// begin while
			
					$out_contest_logo = $DB->fetch_array($DB->query("SELECT * FROM t_turnier WHERE tid = '".$out_turnier['tid']."' "));
					$out_contest_team_name_a = $DB->fetch_array($DB->query("SELECT * FROM t_teilnehmer WHERE tnid = '".$out_turnier['team_a']."' "));
					$out_contest_team_name_b = $DB->fetch_array($DB->query("SELECT * FROM t_teilnehmer WHERE tnid = '".$out_turnier['team_b']."' "));

				if($iCount % 2 == 0)
							{
								$currentRowClass = "msgrow2";
				
							}
							else
							{
								$currentRowClass = "msgrow1";
							}
							
				$output .= 	"
										<tr class='".$currentRowClass."'>
											<!-- <td>
											".$out_turnier['tcid']."
											</td> -->
											<td>
											<img width='100%' src='/images/turnier_logo/".$out_contest_logo['tlogo']."'>
											</td>
											<td>
											".$out_contest_team_name_a['tnname']."
											<br>
												vs.
											<br>
											".$out_contest_team_name_b['tnname']."
											</td>
											<td>
											".$out_turnier['dateline']."
											</td>
											<td>
											".$out_turnier['host']."
											</td>
											<td>
											".$out_turnier['user_id']."
											</td>
											<td>
											".$out_turnier['starttime']."
											</td>
											<td>
											".$out_turnier['ready_a']."
											</td>
											<td>
											".$out_turnier['ready_b']."
											</td>
											<td>
											".$out_turnier['defaultwin']."
											</td>
										</tr>
				
							";			
			}// end while							
										
										
				$output .=	"		</tbody>
								</table>
							";
			
			
$PAGE->render(utf8_decode(utf8_encode($output) ));
?>