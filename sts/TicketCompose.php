<?php

$MODUL_NAME = "sts";
include_once("../../../global.php");
include("../functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Support-Ticket-System");

$ticketid	= $_GET['ticketid'];

$type 					= $_POST['type'];
$queue 					= $_POST['queue'];
$agent 					= $_POST['agent'];
$titel 					= $_POST['titel'];
		$search = array("'","´"); 
        $replace = array('',''); 
		$text1 .= str_replace($search, $replace, $_POST['user_eingabe']);  
$text 					= nl2br($text1);
$status 				= $_POST['status'];
$prio 					= $_POST['prio'];
$user 					= $_POST['user'];
$std_antwort_id			= $_POST['id_std_antwort'];
$checkbox_save_changes 	= $_POST['antwort_save_changes'];
$checkbox_save 			= $_POST['antwort_save'];
$antwort_titel_save		= $_POST['antwort_titel_save'];

$out_ticket =
		$DB->fetch_array	(
						$DB->query	("
								SELECT
									*
								FROM
									project_ticket_ticket
								LEFT JOIN
									project_ticket_sperre ON project_ticket_ticket.sperre = project_ticket_sperre.sperre_id
								WHERE
									project_ticket_ticket.id = '".$ticketid."'
									")
							);
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

if($_GET['action'] == "add")
{
//				an, von, Betreff, Betreff Zusatz, Nachricht, Ticket_ID

	message($user, $user_id, 'Neue Antwort Ticket Nr. '.$ticketid, 'Support Ticket: ', $text, $ticketid);

	if( $out_ticket['agent'] == 0)
	{
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
										'Ticket durch ".$CURRENT_USER->vorname."  gesperrt!',
										'Text',
										'".$ticketid."',
										'".$prio."',
										'lock',
										'1'
									)"
							);


		$update=$DB->query(	"
							UPDATE
								`project_ticket_ticket`
							SET
								`agent` = ".$user_id.",
								`status` = ".$status.",
								`sperre` = '2'
							WHERE
								`id` = ".$ticketid."
						");
	}

	$update=$DB->query(	"
							UPDATE
								`project_ticket_ticket`
							SET
								`prio` = ".$prio.",
								`status` = ".$status."
							WHERE
								`id` = ".$ticketid."
						");
	$update=$DB->query(	"
							UPDATE
								`project_ticket_antworten`
							SET
								`prio` = ".$prio."
							WHERE
								`ticket_id` = ".$ticketid."
						");

						// Liefert: <body text='schwarz'>
						
	$out_user_name = $DB->fetch_array( $DB->query("
											SELECT
												*
											FROM
												user
											WHERE
												id = '".$out_ticket['user']."'
											LIMIT 1

										")
									);
					
$new_text = str_replace("%NICK%", $out_user_name['nick'], $text);
$new_text = str_replace("%VORNAME%", $out_user_name['vorname'], $new_text );
$new_text = str_replace("%NACHNAME%", $out_user_name['nachname'], $new_text );
$new_text = str_replace("%ONICK%", $CURRENT_USER->nick, $new_text);
$new_text = str_replace("%OVORNAME%", $CURRENT_USER->vorname, $new_text );
$new_text = str_replace("%ONACHNAME%", $CURRENT_USER->nachname, $new_text );



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
																type
															)
													VALUES
														(
															NULL,
															'".$user_id."',
															'".$datum."',
															'".$titel."',
															'".$new_text."',
															'".$ticketid."',
															'".$prio."',
															'".$type."'
														);"
												);
												

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
	if(isset($checkbox_save_changes ))
	{	$std_antwort_id = $_GET['std_antwort_id'];
		$output .=  antwort_save_changes($std_antwort_id,$text);		
	}
	if(isset($checkbox_save ))
	{
		$output =  antwort_save($antwort_titel_save,$text);
		//$output .= $antwort_titel_save;
	}
	$PAGE->redirect("{BASEDIR}admin/projekt/sts/TicketZoom.php?ticketid=".$ticketid."",$PAGE->sitetitle,"Die Antwort aus das Ticket: ".$out_ticket_show_ticketdata['titel']." f&uuml;r ".$out_ticket_show_userdata['vorname']." '".$out_ticket_show_userdata['nick']."' ".$out_ticket_show_userdata['nachname']." wurde gesendet <br>Nachricht: <br> ".$text.".");
	
}

/*###########################################################################################
Admin PAGE
*/

if(!$DARF["view"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

else
{
include("header.php");
include("news.php");
$output .=
"
<a href='TicketZoom.php?ticketid=".$ticketid."'>[ Zur&uuml;ck ]</a>

	<table width='100%' border='0'>
		<tbody>

                        <tr>
                            <td class='contentkey'>Standard Antwort:</td>
                            <td class='contentvalue'>";
							
							$sql_list_std_antworten = $DB->query("SELECT * FROM project_ticket_std_antworten");
							if(mysql_num_rows($sql_list_std_antworten) > 0)
							{
$output .= "							
							<form name='std_antwort' action='?ticketid=".$_GET['ticketid']."&action=std_antwort' method='POST'>	
								<select name='id_std_antwort' onChange='document.std_antwort.submit()'>
								
									<option value='NOK' selected>Standard Antwort ausw&auml;hlen</option>
									
";										
											while($out_list_std_antworten = $DB->fetch_array($sql_list_std_antworten))
											{// begin while
												$output .= "
													<option value='".$out_list_std_antworten['id']."'>".$out_list_std_antworten['std_titel']."</option>
												";
											}
											if($_GET['action'] == "std_antwort")
											{
											$std_antwort = $DB->fetch_array($DB->query("SELECT * FROM project_ticket_std_antworten WHERE id = ".$std_antwort_id." "));
											//echo $std_antwort_id = $std_antwort['id'];
											}
																	
											$output .= "
								</select>
							</form>";
							}
							$output .= "
							
							<form name='antwort' method='post' action='?action=add&ticketid=".$out_ticket['id']."&std_antwort_id=".$std_antwort_id."'>
							<input type='hidden' name='type' value='agent'>
							<input type='hidden' name='titel' value='Antwort durch ".$CURRENT_USER->vorname." ".$CURRENT_USER->nachname." hinzugefügt.'>
							<input type='hidden' name='user' value='".$out_ticket['user']."'>
							</td>
						</tr>
						<tr>
                            <td class='contentkey'>Text: 
							<br><br> Variablen die verwendet werden k&ouml;nnen: <br> <b>%NICK%</b> <br> <b>%VORNAME%</b> <br> <b>%NACHNAME%</b>
							<br><br><br> Orga Variablen: <br> <b>%ONICK%</b> <br> <b>%OVORNAME%</b> <br> <b>%ONACHNAME%</b>
							</td>
                            <td class='contentvalue'>
								<textarea wrap='hard'name='user_eingabe'  rows='15' cols='60' style='background: none repeat scroll 0 0 buttonface;'>".strip_tags($std_antwort['std_antwort'])."</textarea>
                            </td>
                        </tr>";
						
						if($_GET['action'] == "std_antwort")
						{
$output .= "						
						 <tr>
                            <td class='contentkey'>&Auml;nderungen der Antwort speichern?</td>
                            <td class='contentvalue'>
							<input type='checkbox' name='antwort_save_changes'>
							</td>
						</tr>";
						}
						else
						{
$output .= "						
						 <tr>
                            <td class='contentkey'>Antwort als Vorlage speichern?</td>
                            <td class='contentvalue'>
							Titel: <input type='text' name='antwort_titel_save' size='60' ><input type='checkbox' name='antwort_save'>
							</td>
						</tr>";
						}
						
$output .= "						
                        <tr>
                            <td class='contentkey'>Status des Tickets:</td>
                            <td class='contentvalue'>
								<select name='status'>
";

						$sql_list_status = $DB->query("SELECT * FROM project_ticket_status");
						while($out_list_status = $DB->fetch_array($sql_list_status))
					{// begin while
									if($out_list_status['id'] == $out_ticket['status'])
									{
									$output .= "

									<option value='".$out_list_status['id']."' selected>".$out_list_status['name']."</option>
									";
									}
									else
									{
									$output .= "

									<option value='".$out_list_status['id']."'>".$out_list_status['name']."</option>
									";
									}
					}

						$output .= "
								</select>
							</td>
                        </tr>
                        <tr>
                            <td class='contentkey'>Priorit&auml;t:</td>
                            <td class='contentvalue'>
									<select name='prio'>
						";

						$sql_list_prio = $DB->query("SELECT * FROM project_ticket_prio");
						while($out_list_prio = $DB->fetch_array($sql_list_prio))
					{// begin while

									if($out_list_prio['id'] == $out_ticket['prio'] )
									{
									$output .= "

									<option value='".$out_list_prio['id']."' selected>".$out_list_prio['name']."</option>
									";
									}
									else
									{
									$output .= "

									<option value='".$out_list_prio['id']."'>".$out_list_prio['name']."</option>
									";
									}
					}

						$output .= "
									</select>
							</td>
                        </tr>
                    </tbody>
				</table>

				<input type='submit' value='Antworten' accesskey='s'>
</form>
<br>
<br>
<b>Verlauf: </b>
<br>
";


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Auflistung der Frage und Antworten !!
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
<table width='100%' cellspacing='0' cellpadding='3' border='0' class'msg' style='border-bottom: solid 1px #000000; border-top: solid 1px #000000;'>
<!--stop Header -->

<!--start Body-->
    <tbody>
		<tr>
			<td width='75%' valign='top' class='msgrow1' rowspan='2'>

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
			$led = "../images/16/status_unknown.png";
		}
		if($menge_out_ticket_count_user >= 5)
		{
			$led = "../images/16/stop.png";
		}
		else
		{
			$led = "../images/16/submit.png";
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

				<a name='109554'></a>
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

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ENDE Auflistung der Frage und Antworten !!
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$iCount ++;
}




}
// ENDE darf Sehen

$PAGE->render(utf8_decode(utf8_encode($output) ));
?>
