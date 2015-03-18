<?php

$MODUL_NAME = "sts";
include_once("../../../global.php");
include("../functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Support-Ticket-System");

$ticketid	= $_GET['ticketid'];

$type 		= $_POST['type'];
$queue 		= $_POST['queue'];
$user 		= $_POST['user'];
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

if($_GET['action'] == "user")
{
//				an, von, Betreff, Betreff Zusatz, Nachricht, Ticket_ID
	//user_mail($user,$user_id,$titel,"Ticket: ",$text,$ticketid);
	//user_pm($user,$user_id,$titel,"Ticket: ",$text,$ticketid);
	
	$update=$DB->query(	"
							UPDATE
								`project_ticket_ticket`
							SET
								`user` = ".$user.",
								`status` = ".$status."
							WHERE
								`id` = ".$ticketid."
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
	$PAGE->redirect("{BASEDIR}admin/projekt/sts/TicketZoom.php?ticketid=".$ticketid."",$PAGE->sitetitle,"Der Kunde des Tickets ".$out_ticket_show_ticketdata['titel']." f&uuml;r ".$out_ticket_show_userdata['vorname']." '".$out_ticket_show_userdata['nick']."' ".$out_ticket_show_userdata['nachname']." wurde ge&auml;ndert <br>Nachricht: <br> ".$text.".");

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
<form name='agent' method='post' action='?action=user&ticketid=".$out_ticket['id']."' onSubmit='return checkSubmit()'>
	<input type='hidden' name='type' value='notitz'>
	<input type='hidden' name='titel' value='Kunde aktualisiert!'>
	<input type='hidden' name='gelesen' value='1'>
	<table width='100%' border='0'>
		<tbody>
						<tr>
                                    <td width='18%' class='contentkey'>Neuer Kunde:</td>
                                    <td width='80%' class='contentvalue'>
									<div id='divsearch' style=' display:none;'>
								<table cellspacing='0' cellpadding='0'  border='0'>
									<tr>
										<td>
											<input type='text' id='insearch' name='search' size='60' >
										</td>
										<td>&nbsp;</td>
										<td>
											<input type='button' value='Suchen' onClick='javascript:searchUser();'>
										</td>
									</tr>
								</table>
							</div>
							<div id='divselect' style=' display:none;'>
								<table cellspacing='0' cellpadding='0' border='0'>
									<tr>
										<td >
											<select id='inselect' name='user'  ></select>
										</td>
										<td>&nbsp;</td>
										<td>
											<input type='button' value='X' onClick='javascript:clearSearch();'>
										</td>
									</tr>
								</table>
							</div>
							<noscript>
							<b>Javascript is needed for UserSearch</b>
							</noscript>

						<iframe frameborder='0' style='width:0px; height:0px;' src='about:blank' id='operasucks'></iframe>
						<script type='text/javascript' src='/user/xmlusersearch.js'></script>
						<script type='text/javascript'>
						<!--

						// define variables needed by xmlusersearch.js
						var inselect	= document.getElementById('inselect');
						var divselect	= document.getElementById('divselect');
						var insearch	= document.getElementById('insearch');
						var divsearch	= document.getElementById('divsearch');
						var xmllink	= '/user/?do=xmlsearch';

						function checkSubmit()
						{
							if(insearch.value != '') {
								searchUser();
								return false;
							}
							if(inselect.length == 0 || inselect.options[0].value == '' || inselect.options[0].value == '0') {
								alert('Es wurde kein Benutzer gewaehlt');
								return false;
							}
						}

						initUserSearch('0');

						//-->
						</script>


						<br />		
								</td>
                                </tr>
						<tr>
                            <td class='contentkey'>Text:</td>
                            <td class='contentvalue'>
									<textarea wrap='hard'name='user_eingabe'  rows='15' cols='60' style='background: none repeat scroll 0 0 buttonface;'></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class='contentkey'>Neuer Status:</td>
                            <td class='contentvalue'> 
								<select name='status'>
";

						$sql_list_status = $DB->query("SELECT * FROM project_ticket_status");
						while($out_list_status = $DB->fetch_array($sql_list_status))
					{// begin while
									if($out_list_status['id'] == $out_ticket['status'] )
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
                    </tbody>
				</table>
				
				<input type='submit' value='&Uuml;bermitteln' accesskey='s'>
</form>

";


}
// ENDE darf Sehen

$PAGE->render(utf8_decode(utf8_encode($output)));
?>
