<?



include_once("../../../global.php");
include("../functions.php");

$ticketid 	= $_GET['ticketid'];
$event_id = $EVENT->next;

$iCount		= 0;

$PAGE->sitetitle = $PAGE->htmltitle = _("Support-Ticket-System");

$sql_ticket_zoom = $DB->query("
								SELECT
									*
								FROM
									project_ticket_ticket
								LEFT JOIN
									project_ticket_sperre ON project_ticket_ticket.sperre = project_ticket_sperre.sperre_id
								WHERE
									project_ticket_ticket.id = '".$ticketid."'

							");

$out_ticket_zoom_data =
	$DB->fetch_array(
						$DB->query("
										SELECT
											*
										FROM
											project_ticket_ticket
										WHERE
											id = '".$ticketid."'

									")
					);

$sql_ticket_zoom_antworten = $DB->query("
											SELECT
												*
											FROM
												project_ticket_antworten
											WHERE
												ticket_id = '".$ticketid."'
											AND
											TYPE <> 'lock'
											AND TYPE <> 'move'
											ORDER BY
												erstellt DESC


										");



//////////
$user		= $user_id;
$text 		= nl2br($_POST['user_eingabe']);
$tucket_id 	= $_POST['ticket_id'];
$prio 		= $_POST['prio'];



if ($out_ticket_zoom_data[agent] == ".$user_id.")
{
	$update=$DB->query(	"
							UPDATE
								`project_ticket_antworten`
							SET
								`gelesen` =  1
							WHERE
								`ticket_id` = '".$ticketid."'
							AND
								( gelesen = 0 AND type = 'user' )

						");
}


if($_GET['action'] == "move")
{

	$out_ticket_show_ticketdata =
								$DB->fetch_array(
													$DB->query(	"
																	SELECT
																		*
																	FROM
																		`project_ticket_ticket`
																	WHERE
																		id='".$ticketid."'
																")
												);
	$out_ticket_show_userdata =
								$DB->fetch_array(
													$DB->query(	"
																	SELECT
																		*
																	FROM
																		`user`
																	WHERE
																		id='".$out_ticket_show_ticketdata['user']."'
																")
												);
	$out_ticket_show_vonqueue =
								$DB->fetch_array(
													$DB->query(	"
																	SELECT
																		*
																	FROM
																		`project_ticket_queue`
																	WHERE
																		id='".$out_ticket_show_ticketdata['queue']."'
																")
												);
	$out_ticket_show_zuqueue =
									$DB->fetch_array(
														$DB->query(	"
																		SELECT
																			*
																		FROM
																			`project_ticket_queue`
																		WHERE
																			id='".$_POST['queue']."'
																	")
													);

	$insert=$DB->query	("
							INSERT INTO
								`project_ticket_antworten`
									(
										id,
										user,
										erstellt,
										titel,
										text,
										ticket_id,
										prio,
										type,
										gelesen
									)
							VALUES
								(
									NULL,
									'".$user_id."',
									'".$datum."',
									'Ticket durch ".$CURRENT_USER->vorname."  verschoben ".$out_ticket_show_vonqueue['name']." --> ".$out_ticket_show_zuqueue['name']."',
									'Text',
									'".$ticketid."',
									'".$out_ticket_show_ticketdata['prio']."',
									'move',
									'1'
								);"
						);
	$update=$DB->query(	"
							UPDATE
								project_ticket_ticket
							SET
								`queue` =  '".$_POST['queue']."'
							WHERE
								`id` = '".$ticketid."'
						");

	$PAGE->redirect("{BASEDIR}admin/projekt/sts/TicketZoom.php?ticketid=".$ticketid."",$PAGE->sitetitle,"Das Tickets ".$out_ticket_show_ticketdata['titel']." f&uuml;r ".$out_ticket_show_userdata['vorname']." '".$out_ticket_show_userdata['nick']."' ".$out_ticket_show_userdata['nachname']." wurde verschoben!");
}

if($_GET['action'] == "sperren" || $_GET['action'] == "freigeben" )
{
if($_GET['action'] == "sperren")
{
	$out_ticket_show_ticketdata =
								$DB->fetch_array(
													$DB->query(	"
																	SELECT
																		*
																	FROM
																		`project_ticket_ticket`
																	WHERE
																		id='".$ticketid."'
																")
												);
	$out_ticket_show_userdata =
								$DB->fetch_array(
													$DB->query(	"
																	SELECT
																		*
																	FROM
																		`user`
																	WHERE
																		id='".$out_ticket_show_ticketdata['user']."'
																")
												);

	$insert=$DB->query	("
							INSERT INTO
								`project_ticket_antworten`
									(
										id,
										user,
										erstellt,
										titel,
										text,
										ticket_id,
										prio,
										type,
										gelesen
									)
							VALUES
								(
									NULL,
									'".$user_id."',
									'".$datum."',
									'Ticket wird durch ".$CURRENT_USER->vorname."  bearbeitet!',
									'Text',
									'".$ticketid."',
									'".$out_ticket_show_ticketdata['prio']."',
									'lock',
									'1'
								);"
						);
	$sperre = "2";
	$update=$DB->query(	"
							UPDATE
								project_ticket_ticket
							SET
								`sperre` =  '".$sperre."',
								`agent` =  '".$user_id."'
							WHERE
								`id` = '".$ticketid."'
						");
}
if($_GET['action'] == "freigeben")
{
	$out_ticket_show_ticketdata =
								$DB->fetch_array(
													$DB->query(	"
																	SELECT
																		*
																	FROM
																		`project_ticket_ticket`
																	WHERE
																		id='".$ticketid."'
																")
												);
	$out_ticket_show_userdata =
								$DB->fetch_array(
													$DB->query(	"
																	SELECT
																		*
																	FROM
																		`user`
																	WHERE
																		id='".$out_ticket_show_ticketdata['user']."'
																")
												);

	$insert=$DB->query	("
							INSERT INTO
								`project_ticket_antworten`
									(
										id,
										user,
										erstellt,
										titel,
										text,
										ticket_id,
										prio,
										type,
										gelesen
									)
							VALUES
								(
									NULL,
									'".$user_id."',
									'".$datum."',
									'Ticket durch ".$CURRENT_USER->vorname."  freigegeben!',
									'Text',
									'".$ticketid."',
									'".$out_ticket_show_ticketdata['prio']."',
									'lock',
									'1'
								);"
						);
	$sperre = "1";
	$update=$DB->query(	"
							UPDATE
								project_ticket_ticket
							SET
								`sperre` =  '".$sperre."',
								`agent` =  '0'
							WHERE
								`id` = '".$ticketid."'
						");
}


	$PAGE->redirect("{BASEDIR}admin/projekt/sts/TicketZoom.php?ticketid=".$ticketid."",$PAGE->sitetitle,"Das Tickets ".$out_ticket_show_ticketdata['titel']." f&uuml;r ".$out_ticket_show_userdata['vorname']." '".$out_ticket_show_userdata['nick']."' ".$out_ticket_show_userdata['nachname']." wurde verschoben ist ".$sperre."!");
}

/*###########################################################################################
Admin PAGE
*/

if(!$DARF_PROJEKT_VIEW) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

else
{
include("header.php");
include("news.php");
$output .=
"

<table width='100%' cellspacing='0' cellpadding='3' border='0'>
    <tbody>
		<tr>
			<td width='60%' class='mainhead'>
				[ Inhalt Ticket#: ".$out_ticket_zoom_data['id']."  ]
				".$out_ticket_zoom_data['titel']."
			</td>
			<td width='40%' align='right' class='mainhead'>
				[ Alter:
				";

$oldDate	= strtotime($out_ticket_zoom_data['erstellt']);
$actDate = strtotime($datum);              // aktuelles Datum
$diffDate = ($actDate-$oldDate);         // Differenz berechnen (in Sekunden)

$days = floor($diffDate / 24 / 60 / 60 );   // Anzahl Tage = Sekunden /24/60/60
$diffDate = $diffDate - ($days*24*60*60);   // den verbleibenden Rest berechnen = Stunden
$hours = floor($diffDate / 60 / 60);      // den Stundenanteil herausrechnen
$diffDate = ($diffDate - ($hours*60*60));
$minutes = floor($diffDate/60);            // den Minutenanteil
$diffDate = $diffDate - ($minutes*60);
$seconds = floor($diffDate);             // die verbleibenden Sekunden
if($days > 0)
{
$output .=
"
".$days." Tage

";
}
if($hours > 0)
{
$output .=
"
".$hours." Stunden

";
}
if($minutes > 0)
{
$output .=
"
".$minutes." Minuten

";
}
$output .=
"

				]
			</td>
		</tr>
	</tbody>
</table>
<table width='100%' cellspacing='0' cellpadding='3' border='0'>
    <tbody><tr>
        <td width='70%' class='menu'>
            <a href='Dashboard.php'>[ Zur&uuml;ck ]</a> |
			";
			if( ( $out_ticket_zoom_data['sperre'] == "1" && $out_ticket_zoom_data['agent'] <> $user_id ) || ( $out_ticket_zoom_data['sperre'] == "1" &&  $DARF_PROJEKT_EDIT ) )
			{
			$output .=
			"

			<!--start MenuItem-->
						<a title='Ticket bearbeiten!'  href='?action=sperren&ticketid=".$ticketid."'>Ticket bearbeiten</a>
			<!--stop MenuItem -->
			-
			";
			}
			if( ( $out_ticket_zoom_data['sperre'] == "2" && $out_ticket_zoom_data['agent'] == $user_id ) || ( $out_ticket_zoom_data['sperre'] == "2" && $DARF_PROJEKT_EDIT ) )
			{
			$output .=
			"

			<!--start MenuItem-->
						<a title='Ticket freigeben!'  href='?action=freigeben&ticketid=".$ticketid."'>Freigeben</a>
			<!--stop MenuItem -->
			-
			";
			}


			if($out_ticket_zoom_data['agent'] == $user_id || $DARF_PROJEKT_EDIT )
			{
$output .=
"

            <a title='&Auml;ndern der Ticket-Priorit&auml;t'  href='TicketHistory.php?ticketid=".$ticketid."'>History</a>

            -

			<a title='&Auml;ndern der Ticket-Priorit&auml;t'  href='TicketPriority.php?ticketid=".$ticketid."'>Priorit&auml;t</a>

            -

            <a title='&Auml;ndern des Ticket-Besitzers!'  href='TicketOwner.php?ticketid=".$ticketid."'>Besitzer</a>

            -

            <a title='&Auml;ndern des Ticket-Kunden!' href='TicketCustomer.php?ticketid=".$ticketid."'>Kunde</a>

            -

            <a title='Hinzuf&uuml;gen einer Notiz!' href='TicketNote.php?ticketid=".$ticketid."'>Notiz</a>


";

if($out_ticket_zoom_data['agent'] == $user_id || $DARF_PROJEKT_DELL )
			{
$output .=
"  			-

            <a title='Status' href='TicketClose.php?ticketid=".$ticketid."'>Status</a>
";
			}
$output .=
"
			</td>
";
			}

$output .=
"
        <td width='30%' align='right' class='menu'>
            <table cellspacing='0' cellpadding='0' border='0'>
                <tbody><tr>
                    <td class='mainkey'>Erstellt:</td>
                    <td class='mainvalue'>".date("d.m.Y H:i:s",strtotime($out_ticket_zoom_data['erstellt']))."</td>
                </tr>
            </tbody></table>
        </td>
    </tr>
</tbody></table>



";


while($out_ticket_zoom = $DB->fetch_array($sql_ticket_zoom))
{

	$out_ticket_zoom_user =
				$DB->fetch_array(
									$DB->query	("
												SELECT
													*
												FROM
													`user`
												WHERE
													id = ".$out_ticket_zoom['user']."
											")
								);
$out_ticket_zoom_user_sitz =
				$DB->fetch_array(
									$DB->query	("
												SELECT
													*
												FROM
													`event_teilnehmer`
												WHERE
													( user_id = ".$out_ticket_zoom['user']." AND event_id = ".$event_id.")
											")
								);								
	$out_ticket_zoom_agent =
				$DB->fetch_array(
									$DB->query	("
												SELECT
													*
												FROM
													`user`
												WHERE
													id = ".$out_ticket_zoom['agent']."
											")
								);
	$out_ticket_zoom_queue =
				$DB->fetch_array(
									$DB->query	("
												SELECT
													*
												FROM
													project_ticket_queue
												WHERE
													id = ".$out_ticket_zoom['queue']."
											")
								);
	$out_ticket_zoom_prio =
				$DB->fetch_array(
									$DB->query	("
												SELECT
													*
												FROM
													project_ticket_prio
												WHERE
													id = ".$out_ticket_zoom['prio']."
											")
								);
	$out_ticket_zoom_status =
				$DB->fetch_array(
									$DB->query	("
												SELECT
													*
												FROM
													project_ticket_status
												WHERE
													id = ".$out_ticket_zoom['status']."
											")
								);
	$out_ticket_count_user =
							$DB->query	("
											SELECT
												*
											FROM
												project_ticket_ticket
											WHERE
												user = ".$out_ticket_zoom['user']."
											AND
												status = 3
										");
	$menge_out_ticket_count_user = mysql_num_rows($out_ticket_count_user);

$output .=
"
<table width='100%' cellspacing='0' cellpadding='3' border='0' class'msg' style='border-bottom: solid 1px #000000;'>
<!--stop Header -->

<!--start Body-->
    <tbody>
			<tr>
			<td width='75%' valign='top' class='msgrow1' rowspan='2'>
";
//include("TicketHistory.php");
$output .=
"
				<a name='109554'></a>
				<table width='100%' cellspacing='0' cellpadding='1' border='0'>
					<tbody>
						<tr>
							<td width='98%' valign='top'>
								<table width='100%' cellspacing='0' cellpadding='1' border='0'>
								<!--start Row-->
									<tbody>
										<tr>
											<td width='15%' class='msgrow1'>Von:</td>
											<td width='85%' class='contentvalue'> <div title='".$out_ticket_zoom_user['vorname']." '".$out_ticket_zoom_user['nick']."' ".$out_ticket_zoom_user['nachname']." &lt;".$out_ticket_zoom_user['email']."&gt;'>".$out_ticket_zoom_user['vorname']." '".$out_ticket_zoom_user['nick']."' ".$out_ticket_zoom_user['nachname']." &lt;".$out_ticket_zoom_user['email']."&gt;</div></td>
										</tr>
									<!--stop Row -->
									<!--start Row-->
										<tr>
											<td width='15%' class='msgrow1'>An:</td>
											<td width='85%' class='contentvalue'> <div title='".$out_ticket_zoom_queue['name']."'>".$out_ticket_zoom_queue['name']."</div></td>
										</tr>
									<!--stop Row -->
									<!--start Row-->
										<tr>
											<td width='15%' class='msgrow1'>Betreff:</td>
											<td width='85%' class='contentvalue'> <div title='".$out_ticket_zoom['titel']."'>".$out_ticket_zoom['titel']."</div></td>
										</tr>
									<!--stop Row -->
										<tr>
											<td class='msgrow1'>Erstellt:</td>
											<td class='contentvalue'> <div title='".date("d.m.Y H:i:s",strtotime($out_ticket_zoom['erstellt']))."'>".date("d.m.Y H:i:s",strtotime($out_ticket_zoom['erstellt']))."</div></td>
										</tr>
									</tbody>
								</table>

								<!--start BodyPlain-->
								<div class='message'>
								<br>
									".$out_ticket_zoom['text']."
								</div>
								<!--stop BodyPlain -->
							</td>
						</tr>
					</tbody>
				</table>

			</td>
			<td width='25%' valign='top' class='PriorityID-".$out_ticket_zoom_prio['id']."'>
				<!--start Status-->
				<table width='100%' cellspacing='1' cellpadding='0' border='0'>
					<tbody>
						<tr valign='top'>
							<td><b>Status:</b></td>
							<td>
								<font color='red'>
								<div title='neu'>".$out_ticket_zoom_status['name']."</div>
								</font>
							</td>
						</tr>
						<tr valign='top'>
							<td><b>Bearbeitung:</b></td>
							<td>
								<font color='red'>
								<div title='Bearbeitung'>".$out_ticket_zoom['sperre_name']."</div>
								</font>
							</td>
						</tr>
						<tr valign='top'>
							<td><b>Priorit&auml;t:</b></td>
							<td>
								<font color='red'>
								<div title='".$out_ticket_zoom_prio['name']."'>".$out_ticket_zoom_prio['name']."</div>
								</font>
							</td>
						</tr>
						<tr valign='top'>
							<td><b>Bereich:</b></td>
							<td>
								<font color='red'>
								<div title='".$out_ticket_zoom_queue['name']."'>".$out_ticket_zoom_queue['name']."</div>
								</font>
							</td>
						</tr>
						<!--start Owner-->
						<tr valign='top'>
							<td><b>Besitzer:</b></td>
							<td>
								<div title='".$out_ticket_zoom_agent['vorname']." ".$out_ticket_zoom_agent['nachname']."'>
									".$out_ticket_zoom_agent['vorname']." ".$out_ticket_zoom_agent['nachname']."
								</div>
							</td>
						</tr>
						<!--stop Owner -->
						<!--start LinkTableSimple-->
						<!--stop LinkTableSimple -->
					</tbody>
				</table>
				<!--start CustomerTable-->
				<p></p>
				<hr>
				<b>Kunden-Info:</b>
				<!--start Customer-->
				<table cellspacing='1' cellpadding='0' border='0'>
				<!--start CustomerRow-->
					<tbody>
						<!--start CustomerRow-->
						  <tr>
							<td>IP:</td>
							<td class='contentvalue'><div title='".$out_ticket_zoom['ip']."'>".$out_ticket_zoom['ip']."</div>
							</td>
						  </tr>
						<!--stop CustomerRow -->
						<tr>
							<td >Vorname:</td>
							<td class='contentvalue'>
								<div title='".$out_ticket_zoom_user['vorname']."'>
									".$out_ticket_zoom_user['vorname']."
								</div>
							</td>
						</tr>
						<!--stop CustomerRow -->
						<!--start CustomerRow-->
						  <tr>
							<td >Nachname:</td>
							<td class='contentvalue'><div title='".$out_ticket_zoom_user['nachname']."'>".$out_ticket_zoom_user['nachname']."</div>

							</td>
						  </tr>
						<!--stop CustomerRow -->
						<!--start CustomerRow-->
						  <tr>
							<td>Nick:</td>
							<td class='contentvalue'><div title='".$out_ticket_zoom_user['nick']."'>".$out_ticket_zoom_user['nick']."</div>
							</td>
						  </tr>
						<!--stop CustomerRow -->
						<!--start CustomerRow-->
						  <tr>
							<td>Sitzplatz:</td>
							<td class='contentvalue'><div title='".$out_ticket_zoom_user_sitz['sitz_nr']."'>".$out_ticket_zoom_user_sitz['sitz_nr']."</div>
							</td>
						  </tr>
						<!--stop CustomerRow -->
						<!--start CustomerRow-->
						  <tr>
							<td>User ID:</td>
							<td class='contentvalue'><div title='".$out_ticket_zoom_user['id']."'>".$out_ticket_zoom_user['id']."</div>
							</td>
						  </tr>
						<!--stop CustomerRow -->
						  <tr>
							<td colspan='2'>
							  <table width='70%' cellspacing='3' cellpadding='1' border='0'>

						<!--start CustomerItem-->
								<tbody>
								<tr>

						<!--start CustomerItemRow-->
								  <td width='10%'>
";
		if($menge_out_ticket_count_user >= 3)
		{
			$led = "/images/projekt/16/status_unknown.png";
		}
		if($menge_out_ticket_count_user >= 5)
		{
			$led = "/images/projekt/16/stop.png";
		}
		else
		{
			$led = "/images/projekt/16/submit.png";
		}
$output .=
"
									<img border='0' title='Offene Tickets' alt='Offene Tickets' src='".$led."'>
								  </td>
								  <td>
									<nobr>Offene Tickets (".$menge_out_ticket_count_user.")</nobr>
								  </td>
						<!--stop CustomerItemRow -->
								</tr>
						<!--stop CustomerItem -->
							  </tbody></table>
							</td>
						  </tr>
						</tbody></table>
						<!--stop Customer -->
										<hr>
									<p></p>
						<!--stop CustomerTable -->
						<!--stop Status -->
									<table width='95%' cellspacing='0' cellpadding='1' border='0'>
										<tbody><tr>
											<td>
						<!--start AgentAnswer-->
";
					if($out_ticket_zoom_data['agent'] == $user_id || $DARF_PROJEKT_EDIT )
					{
$output .=
"
						<!--start AgentAnswerCompose-->
												<p>
												<b>Antwort erstellen:</b>
												<br>
												<table width='100%' border='0'>
													<tbody>
														<tr>
															<td colspan='2'>
															<li><a  href='TicketCompose.php?ticketid=".$out_ticket_zoom['id']."'>Standard-Antwort</a></li>
";
													if($out_ticket_zoom_data['agent'] == $user_id || $DARF_PROJEKT_DELL )
													{
$output .=
"
															<li><a  href='TicketClose.php?ticketid=".$out_ticket_zoom['id']."'>Ticket schlie&szlig;en...</a></li>
";
													}
$output .=
"
															</td>
														</tr>
														<form  name='move' method='POST' action='?action=move&ticketid=".$out_ticket_zoom_data['id']."'>
														<tr colspan='2'>
															<td >
																<p></p>
																<b>Queue wechseln:</b>
															</td>
														</tr>
														<tr>
															<td width='20%'>
																<select name='queue'>
															";

														$sql_list_category = $DB->query("SELECT * FROM project_ticket_queue");
														while($out_list_category = $DB->fetch_array($sql_list_category))
													{// begin while
																	if($out_list_category['id'] == $out_ticket_zoom_data['queue'])
																	{
																	$output .= "

																	<option value='".$out_list_category['id']."' selected>".$out_list_category['name']."</option>
																	";

																	}
																	else
																	{
																	$output .= "

																	<option value='".$out_list_category['id']."'>".$out_list_category['name']."</option>
																	";
																	}


													}

														$output .= "
																</select>
															</td>
															<td>
																<input type='submit' value='Verschieben' class='button'>
															</td>
														</tr>
														</form>
													</tbody></table>
												</p>
						<!--stop AgentAnswerCompose -->
";
					}
$output .=
"
						<!--stop AgentAnswer -->
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<!--stop Body -->
		<!--start Footer-->
	</tbody>
</table>
";
}


// Antworten  User oder Admin
while($out_ticket_zoom_antworten = $DB->fetch_array($sql_ticket_zoom_antworten))
{

	$out_ticket_zoom_antworten_user =
				$DB->fetch_array(
									$DB->query	("
												SELECT
													*
												FROM
													`user`
												WHERE
													id = ".$out_ticket_zoom_antworten['user']."
											")
								);
$out_ticket_zoom_antworten_ticket =
				$DB->fetch_array(
									$DB->query	("
												SELECT
													*
												FROM
													project_ticket_ticket
												WHERE
													id = ".$out_ticket_zoom_antworten['ticket_id']."
											")
								);

	$out_ticket_zoom_queue =
				$DB->fetch_array(
									$DB->query	("
												SELECT
													*
												FROM
													project_ticket_queue
												WHERE
													id = ".$out_ticket_zoom_antworten_ticket['queue']."
											")
								);

							if($iCount % 2 == 0)
							{
								$currentRowClass = "msgrow2";

							}
							else
							{
								$currentRowClass = "msgrow1";
							}


$output .=
"
<table width='100%' cellspacing='0' cellpadding='3' border='0' class='msg' style='border-bottom: solid 1px #000000;'>
<!--stop Header -->

<!--start Body-->
    <tbody>
		<tr>
			<td width='75%' valign='top' class='".$currentRowClass."'>

				<a name='Jump".$out_ticket_zoom_antworten['id']."'></a>
				<table width='100%' cellspacing='0' cellpadding='1' border='0'>
					<tbody>
						<tr>
							<td width='98%' valign='top'>
								<table width='100%' cellspacing='0' cellpadding='1' border='0'>
								<!--start Row-->
									<tbody>
										<tr>
											<td width='15%' class='".$currentRowClass."'>Von:</td>
											<td width='85%' class='contentvalue'> <div title='".$out_ticket_zoom_antworten_user['vorname']." '".$out_ticket_zoom_antworten_user['nick']."' ".$out_ticket_zoom_antworten_user['nachname']." &lt;".$out_ticket_zoom_antworten_user['email']."&gt;'>".$out_ticket_zoom_antworten_user['vorname']." '".$out_ticket_zoom_antworten_user['nick']."' ".$out_ticket_zoom_antworten_user['nachname']." &lt;".$out_ticket_zoom_antworten_user['email']."&gt;</div></td>
										</tr>
										<tr>
											<td class='".$currentRowClass."'>Erstellt:</td>
											<td class='contentvalue'> <div title='".date("d.m.Y H:i:s",strtotime($out_ticket_zoom_antworten['erstellt']))."'>".date("d.m.Y H:i:s",strtotime($out_ticket_zoom_antworten['erstellt']))."</div></td>
										</tr>
									</tbody>
								</table>

								<!--start BodyPlain-->
								<div class='message'>
								<br>
									".$out_ticket_zoom_antworten['text']."
								<br>
								<br>
								</div>
								<!--stop BodyPlain -->
							</td>
						</tr>
					</tbody>
				</table>

			</td>
			<td width='25%' height='100%' valign='top' class='PriorityID-".$out_ticket_zoom_antworten['prio']."'>



        </td>

		</tr>
		<!--stop Body -->
		<!--start Footer-->
	</tbody>
</table>

";


$iCount ++;
}
// ENDE Antworten  User oder Admin

/// ENDE Antworten
}
// ENDE darf Sehen

$PAGE->render(utf8_decode(utf8_encode($output)));
?>
