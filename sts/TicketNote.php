<?php

$MODUL_NAME = "sts";
include_once("../../../global.php");
include("../functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Support-Ticket-System");

$ticketid	= $_GET['ticketid'];

$type 		= $_POST['type'];
$queue 		= $_POST['queue'];
$agent 		= $_POST['agent'];
$titel 		= $_POST['titel'];
$text 		= nl2br($_POST['user_eingabe']);
$status 	= $_POST['status'];
$prio 		= $_POST['prio'];
$user 		= $_POST['user'];
$gelesen	= $_POST['gelesen'];

$out_ticket = 
		$DB->fetch_array	(
						$DB->query	("
										SELECT
											*
										FROM 
											project_ticket_ticket
										WHERE
											id = ".$ticketid."
									")
							);

if($_GET['action'] == "note")
{
//				an, von, Betreff, Betreff Zusatz, Nachricht, Ticket_ID
	//user_mail($user,$user_id,$titel,"Ticket: neue ",$text,$ticketid);
	//user_pm($user,$user_id,$titel,"Ticket: neue ",$text,$ticketid);
	
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
															'".$titel."',
															'".$text."',
															'".$ticketid."',
															'".$out_ticket['prio']."',
															'".$type."',
															'".$gelesen."'
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
	$PAGE->redirect("{BASEDIR}admin/projekt/sts/TicketZoom.php?ticketid=".$ticketid."",$PAGE->sitetitle,"Die Notiz des Tickets ".$out_ticket_show_ticketdata['titel']." f&uuml;r ".$out_ticket_show_userdata['vorname']." '".$out_ticket_show_userdata['nick']."' ".$out_ticket_show_userdata['nachname']." wurde gespeichert <br>Nachricht: <br> ".$text.".");
}

/*###########################################################################################
Admin PAGE
*/

if(!$DARF["edit"]) $PAGE->error_die(html::template("error_nopermission"));

else
{
include("header.php");
include("news.php");		
$output .=
"

<form name='note' method='post' action='?action=note&ticketid=".$out_ticket['id']."'>
	<input type='hidden' name='type' value='notitz'>
	<input type='hidden' name='titel' value='Interne Notiz hinzugef&uumlgt'>
	<input type='hidden' name='gelesen' value='1'>
	<input type='hidden' name='agent' value='".$out_ticket['agent']."'>
	<table width='100%' border='0'>
		<tbody>
						<tr>
                            <td class='contentkey'>Text:</td>
                            <td class='contentvalue'>
									<textarea wrap='hard'name='user_eingabe'  rows='15' cols='60' style='background: none repeat scroll 0 0 buttonface;'></textarea>
                            </td>
                        </tr>
                    </tbody>
				</table>
				
				<input type='submit' value='&Uuml;bermitteln' accesskey='s'>
</form>

";


}
// ENDE darf Sehen

$PAGE->render(utf8_decode(utf8_encode($output)));
?>
