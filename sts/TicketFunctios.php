<?php

function ticket_output_bereiche($uebergabe,$label,$sortierung,$eintraege_pro_seite){
global $DB, $CURRENT_USER;
////////////////////////////////////////////////

$seite = $_GET[$sortierung];  //Abfrage auf welcher Seite man ist

//Wenn man keine Seite angegeben hat, ist man automatisch auf Seite 1
if(!isset($seite))
   {
   $seite = 1;
   }

//Ausrechen welche Spalte man zuerst ausgeben muss:
$start = $seite * $eintraege_pro_seite - $eintraege_pro_seite;

if(isset($_GET['queueid']))
{
	$queueid = $_GET['queueid'];
}
else
{
	$queueid = 1;
}

$sql = $DB->query($uebergabe."
					LIMIT
					".$start.", ".$eintraege_pro_seite."");
$sql1 = $DB->query($uebergabe);

	if (mysql_num_rows($sql) > 0)
	{
	$output =
	"
	<a name='".$label."' ></a>
	<table width='100%' cellspacing='2' cellpadding='0' border='0' align='center'>
								<tbody><tr>
									<td>

										<table width='100%' cellspacing='0' cellpadding='4' border='0' align='center'>
											<tbody><tr>
												<td title='".$label."' class='contenthead'>".$label."</td>
												<td align='right' class='contenthead'>
												</td>
											</tr>
											<tr>
												<td class='contentbody' colspan='2'>
													<div >

	<table width='100%' cellspacing='0' cellpadding='2' border='0'>

	<!--start ContentLargeTicketGenericFilter-->
	  <tbody><tr>
		<td width='70%' class='small'>

		</td>
		<td align='right' class='small'>

	<!--start ContentLargeTicketGenericFilterNavBar-->
	";
	 $output .= "<b>Seite:</b> ";

	$menge_neu = mysql_num_rows($sql1);
	//Errechnen wieviele Seiten es geben wird
	$wieviel_seiten_neu = $menge_neu / $eintraege_pro_seite;
	//Ausgabe der Links zu den Seiten
	for($z=0; $z < $wieviel_seiten_neu; $z++)
	   {
	   $y = $z + 1;

	   //Wenn der User sich auf dieser Seite befindet, keinen Link ausgeben
	   if($seite == $y)
		  {
		  $output .= "  <b>".$y."</b> ";
		  }

	   //Aus dieser Seite ist der User nicht, also einen Link ausgeben
	   else
		  {
		  $output .= "  <a href='?".$sortierung."=".$y."#".$label."'>".$y."</a> ";
		  }


	   }

	$output .=
	"
	<!--stop ContentLargeTicketGenericFilterNavBar -->
		</td>
	  </tr>
	<!--stop ContentLargeTicketGenericFilter -->
	  <tr>
		<td colspan='2'>
		  <table width='100%' cellspacing='0' cellpadding='1' border='0'>

	<!--start ContentLargeTicketGenericRow-->

			<tbody>
			<tr class='msghead'>
					<td width='3%'>
					&nbsp;
					</td>
					<td width='3%'>
						 &nbsp;<b>Ticket#</b>
					</td>
					<td  width='25%'>
						<b>Betreff</b>
					</td>
					<td width='20%' >
						<b>Bearbeiter</b>
					</td>
					<td width='15%'>
						<b>letzte &Auml;nderung</b>
					</td>
					<td width='15%'>
						<b>Alter</b>
					</td>
					<td width='4%'>
						<b>Bereich</b>
					</td>
				</tr>

	";

	while($out_tickets_neu = $DB->fetch_array($sql))
	{
		if( project_check_queue_view($out_tickets_neu['queue'],$CURRENT_USER->id) )
		{
	$out_tickets_neu_user =
				$DB->fetch_array(
									$DB->query	("
												SELECT
													*
												FROM
													`user`
												WHERE
													id = ".$out_tickets_neu['user']."
											")
								);
	$out_tickets_neu_queue =
				$DB->fetch_array(
									$DB->query	("
												SELECT
													*
												FROM
													project_ticket_queue
												WHERE
													id = ".$out_tickets_neu['queue']."
											")
								);
	$out_tickets_neu_prio =
				$DB->fetch_array(
									$DB->query	("
												SELECT
													*
												FROM
													project_ticket_prio
												WHERE
													id = ".$out_tickets_neu['prio']."
											")
								);
	$out_tickets_neu_bearbeiter =
				$DB->fetch_array(
									$DB->query	("
												SELECT
													*
												FROM
													user
												WHERE
													id = ".$out_tickets_neu['agent']."
											")
								);								
	if($iCount1 % 2 == 0)
								{
									$currentRowClass1 = "msgrow2";

								}
								else
								{
									$currentRowClass1 = "msgrow1";
								}

	$output .=
	"
				<tr class='".$currentRowClass1."'>
						<td align='center' valign='middle' class='PriorityID-".$out_tickets_neu_prio['id']."' >

	";

		$sql_antwort1 =
				$DB->fetch_array(
									$DB->query	("
													SELECT
														*
													FROM
														`project_ticket_antworten`
													WHERE
														ticket_id = ".$out_tickets_neu['id']."
													AND
														type <> 'notiz'
													ORDER BY
														erstellt DESC

												")
								);

		if( $sql_antwort1['gelesen'] == 1 ||   $sql_antwort1['gelesen'] == '' || $sql_antwort1['type'] == 'agent')
		{
	$output .=
	"
			<div>
				&nbsp;<img border='0' alt='Keine neuen Nachrichten' title='Keine neuen Nachrichten' src='../images/sts/ticket_no_new_messages.png'  >
			</div>
	";
		}
		else
		{

	$output .=
	"
				<div>
					&nbsp;<img border='0' alt='Neuen Nachrichten' title='Neuen Nachrichten' src='../images/sts/ticket_new_messages.png'  >
				</div>
	";
		}


	$output .=
	"

						</td>

	";
				if($out_tickets_neu['sperre'] == "2")
				{
					$sql_ticket_antwort_user =
						$DB->fetch_array(
											$DB->query	("
															SELECT
																*
															FROM
																`project_ticket_antworten`
															WHERE
																ticket_id = ".$out_tickets_neu['id']."
															AND
																( type <> 'agent' or type <> 'notiz' )
															ORDER BY
																erstellt DESC

														")
										);
					$sql_ticket_agent =
						$DB->fetch_array(
											$DB->query	("
															SELECT
																*
															FROM
																`project_ticket_ticket`
															WHERE
																id = ".$out_tickets_neu['id']."

														")
										);

					If($sql_ticket_antwort_user['user'] <> $sql_ticket_agent['agent'] && $sql_ticket_antwort_user['user'] > 0)
					{
					$output .=
							"<td width='32' align='center' valign='middle'>
							<a title='' href='TicketZoom.php?ticketid=".$out_tickets_neu['id']."'>
							&nbsp;<img src='../images/sts/ticket_processing.png'>
							&nbsp;".$out_tickets_neu['id']."
							</a>
							</td>
							";
					}
					else
					{
					$output .=
							"<td width='32' align='center' valign='middle'>
							<a title='in Bearbeitung!' href='TicketZoom.php?ticketid=".$out_tickets_neu['id']."'>
							&nbsp;<img src='../images/sts/ticket_processing.png'>
							&nbsp;".$out_tickets_neu['id']."
							</a>
							</td>
							";
					}
				}
				else
				{

					$output .=
							"<td  width='100'  align='center'>
							&nbsp;<a title='Ticket offen!!!' href='TicketZoom.php?ticketid=".$out_tickets_neu['id']."'> ".$out_tickets_neu['id']."</a>
							</td>  ";


				}
	$output .=
	"


				  <td width='400'>
					<div title='".$out_tickets_neu_user['vorname']." ".$out_tickets_neu_user['nick']." ".$out_tickets_neu_user['nachname']."'>".$out_tickets_neu_user['vorname']." '".$out_tickets_neu_user['nick']."' ".$out_tickets_neu_user['nachname']."</div>

					<div title='".$out_tickets_neu['titel']."'>".$out_tickets_neu['titel']."</div>
				  </td>
				  <td>
				 ";
				 
					if($out_tickets_neu_bearbeiter['vorname'] <> "" )
					{
	$output .=
	"	
					". $out_tickets_neu_bearbeiter['vorname']." '".$out_tickets_neu_bearbeiter['nick']."' ".$out_tickets_neu_bearbeiter['nachname']."
	";
					}
					
			$output .=
	"			
				  </td>
				   <td>
	";
	if(date("d.m.Y H:i:s", strtotime($sql_antwort1['erstellt'])) == '01.01.1970 01:00:00' )
	{
	$output .=
	"
					<div title:'".date("d.m.Y H:i:s", strtotime($out_tickets_neu['erstellt']))."'>".date("d.m.Y H:i:s", strtotime($out_tickets_neu['erstellt']))."</div>
	";
	}
	else
	{
	$output .=
	"
					<div title:'".date("d.m.Y H:i:s", strtotime($sql_antwort1['erstellt']))."'>".date("d.m.Y H:i:s", strtotime($sql_antwort1['erstellt']))."</div>
	";
	}
	$output .=
	"
				  </td>
				  <td >

				  ";

	$oldDate	= strtotime($out_tickets_neu['erstellt']);
	$actDate = strtotime(date("Y-m-d H:i:s"));             // aktuelles Datum
	$diffDate = ($actDate-$oldDate);         // Differenz berechnen (in Sekunden)

	$days = floor($diffDate / 24 / 60 / 60 );   // Anzahl Tag(e) = Sekunden /24/60/60
	$diffDate = $diffDate - ($days*24*60*60);   // den verbleibenden Rest berechnen = Stunde(n)
	$hours = floor($diffDate / 60 / 60);      // den Stunde(n)anteil herausrechnen
	$diffDate = ($diffDate - ($hours*60*60));
	$minutes = floor($diffDate/60);            // den Minute(n)anteil
	$diffDate = $diffDate - ($minutes*60);
	$seconds = floor($diffDate);             // die verbleibenden Sekunden
	if($days > 0)
	{
	$output .=
	"
	".$days." Tag(e)

	";
	}
	if($hours > 0)
	{
	$output .=
	"
	".$hours." Stunde(n)

	";
	}
	if($minutes > 0 && $days == 0)
	{
	$output .=
	"
	".$minutes." Minute(n)

	";
	}
	$output .=
	"

				  </td>
				  <td align='right' width='65'>
					<div title='".$out_tickets_neu_queue['name']."'><small>".$out_tickets_neu_queue['name']."</small></div>
				  </td>
				</tr>
	";
	$iCount1 ++;
	}  // nur Berechtigte Bereiche sehen
	}
	$output .=
	"
			</tbody>
		</table>
		</td>
	  </tr>
	</tbody></table>
													</div>

												</td>
											</tr>
											<tr>
												<td class='contentfooter' colspan='2'>
													&nbsp;

												</td>
											</tr>
										</tbody></table>

									</td>
								</tr>
							</tbody></table>

	";

	}
/*	
	else
	{
$output .=
"
	FEHLER in ".$label."
	";
	}
*/	
	
	
	return $output;
	}
	
?>