<?

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
$gelesen 	= $_POST['gelesen'];

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

if($_GET['action'] == "prio")
{
//				an, von, Betreff, Betreff Zusatz, Nachricht, Ticket_ID
	//user_mail($agent,$user_id,$titel,"Ticket: ",$text,$ticketid);
	//user_pm($agent,$user_id,$titel,"Ticket: ",$text,$ticketid);
	
	$update=$DB->query(	"
							UPDATE
								`project_ticket_ticket`
							SET
								`prio` = ".$prio."
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
															'".$prio."',
															'".$type."',
															'".$gelesen."'
														);"
												);
	$output .= "<meta http-equiv='refresh' content='0; URL=TicketZoom.php?ticketid=".$ticketid."'>";
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
	$PAGE->redirect("{BASEDIR}admin/projekt/sts/TicketZoom.php?ticketid=".$ticketid."",$PAGE->sitetitle,"Die Priorit&auml;t des Tickets ".$out_ticket_show_ticketdata['titel']." f&uuml;r ".$out_ticket_show_userdata['vorname']." '".$out_ticket_show_userdata['nick']."' ".$out_ticket_show_userdata['nachname']." wurde ge&auml;ndert <br>Nachricht: <br> ".$text.".");	
}

/*###########################################################################################
Admin PAGE
*/

if(!$DARF_PROJEKT_EDIT) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

else
{
include("header.php");
include("news.php");		
$output .=
"
<a href='TicketZoom.php?ticketid=".$ticketid."'>[ Zur&uuml;ck ]</a>
<form name='prio' method='post' action='?action=prio&ticketid=".$out_ticket['id']."'>
	<input type='hidden' name='type' value='notitz'>
	<input type='hidden' name='titel' value='Priortät durch ".$CURRENT_USER->vorname." ".$CURRENT_USER->nachname." aktualisiert'>
	<input type='hidden' name='agent' value='".$out_ticket['agent']."'>
	<input type='hidden' name='gelesen' value='1'>
	<table width='100%' border='0'>
		<tbody>
						<tr>
                            <td class='contentkey'>Text:</td>
                            <td class='contentvalue'>
									<textarea wrap='hard'name='user_eingabe'  rows='15' cols='60' style='background: none repeat scroll 0 0 buttonface;'>
Priorit&auml;t aktuallisiert.
Grund:
</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class='contentkey'>Status des Tickets:</td>
                            <td class='contentvalue'> 
								<select name='prio'>
";

						$sql_list_prio = $DB->query("SELECT * FROM project_ticket_prio");
						while($out_list_prio = $DB->fetch_array($sql_list_prio))
					{// begin while
									if($out_list_prio['id'] == ( $out_ticket['prio'] + 1))
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
				
				<input type='submit' value='&Uuml;bermitteln' accesskey='s'>
</form>

";


}
// ENDE darf Sehen

$PAGE->render(utf8_decode(utf8_encode($output)));
?>