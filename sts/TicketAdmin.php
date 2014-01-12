<?

include_once("../../../global.php");
include("../functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Support-Ticket-System Adminbereich");

$ticketid	= $_GET['ticketid'];

$type 		= $_POST['type'];
$queue 		= $_POST['queue'];
$agent 		= $_POST['agent'];
$titel 		= $_POST['titel'];
$text 		= nl2br($_POST['user_eingabe']);
$status 	= $_POST['status'];
$prio 		= $_POST['prio'];
$user 		= $_POST['user'];

						
$sql_queue1 = 
			$DB->query	("
							SELECT
								*
							FROM 
								project_ticket_queue
						");							

if($_GET['action'] == "close")
{
//				an, von, Betreff, Betreff Zusatz, Nachricht, Ticket_ID
	//user_mail($user,$user_id,$titel,"Ticket geschlossen: ",$text,$ticketid);
	//user_pm($user,$user_id,$titel,"Ticket geschlossen: ",$text,$ticketid);
	
	$update=$DB->query(	"
							UPDATE
								`project_ticket_ticket`
							SET
								`status` = ".$status.",
								`sperre` = 'frei'
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
																type
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
															'".$type."'															
														);"
												);
	$output .= "<meta http-equiv='refresh' content='0; URL=TicketZoom.php?ticketid=".$ticketid."'>";
}

/*###########################################################################################
Admin PAGE
*/

if(!$ADMIN->check(GLOBAL_ADMIN)) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

else
{
include("header.php");
include("news.php");		
$output .=
"

<form name='close' method='post' action='?action=queue'>
	<input type='hidden' name='type' value='notitz'>
	<input type='hidden' name='user' value='".$out_ticket['user']."'>
	<table width='100%' border='0'>
		<tbody>
						<tr>
                            <td class='contentkey' width='50%'>Name:</td>
                            <td class='contentvalue'>
                               Queues
                            </td>
                        </tr>
						<tr>
							<td class='contentvalue' colspan='2'>
							<table border='0'>
								<tbody>
									<tr>
									<td > </td>
									
									
";

while($out_queue1 = $DB->fetch_array($sql_queue1))
					{// begin while
									
									
									$output .="<td  class='contentvalue' align='center'>
												
													".$out_queue1['name']."
												</td>
											";
									
					}
$output .= "	
								
								</tr>
                        
";
						$sql_ticket_show_orga = $DB->query("SELECT * FROM user_orga ");
					while($out_ticket_show_orga = $DB->fetch_array($sql_ticket_show_orga))
					{
						$out_ticket_show_orga_name = 
								$DB->fetch_array(
													$DB->query(	"
																	SELECT 
																		*
																	FROM
																		user
																	WHERE
																		id= '".$out_ticket_show_orga['user_id']."'
																")
												);
$output .= "							

						
							<tr>
                            <td class='contentkey'>
							".$out_ticket_show_orga_name['vorname']." '".$out_ticket_show_orga_name['nick']."' ".$out_ticket_show_orga_name['nachname']."
							</td>
                           
						 
								";
								$sql_queue = 
											$DB->query	("
															SELECT
																*
															FROM 
																project_ticket_queue
														");	
		
								while($out_queue = $DB->fetch_array($sql_queue))
								{// begin while
								$sql_agent_queue = 
													$DB->query	("
																	SELECT
																		*
																	FROM 
																		project_ticket_agent_queue
																	WHERE
																		user_id = ".$out_ticket_show_orga_name['id']."
																");	
											if( mysql_num_rows($sql_agent_queue) != 0)
											{$set1 = 0;
												while($out_agent_queue = $DB->fetch_array($sql_agent_queue))
												{// begin while
													$set = 0;
													if($out_queue['id'] == $out_agent_queue['queueid'] )
													{													
														$output .="<td class='contentvalue' align='center'> 
																	
																	if<input type='checkbox' name='".$out_queue['name']."' value='".$out_queue['id']."' checked>
																</td>	
																";
														$set ++;
													}
																									
											
												}
												if($set  == 1 && $set1 == 0)
													{
														$output .="<td class='contentvalue' align='center'> 
																	
																	e -".$set."	e<input type='checkbox' name='".$out_queue['name']."' value='".$out_queue['id']."' >
																</td>	
																";													
													
													}
												
												
											}
											else
											{
												
												if($set  > 0)
													{
														$output .="<td class='contentvalue' align='center'> 
																	
																	e0 -".$set."	e0<input type='checkbox' name='".$out_queue['name']."' value='".$out_queue['id']."' >
																</td>	
																";													
													
													}
													else
													{
														$output .="<td class='contentvalue' align='center'> 
																	
																e1 -".$set."		e1 <input type='checkbox' name='".$out_queue['name']."' value='".$out_queue['id']."' >
																</td>	
																";
													}
											
											}
												
								}

						$output .= "
														
							
                        </tr>
";
					}
$output .= "	
                    </tbody>
				</table>
				</td>
				</tr>
				</tbody>
				</table>
				
				<input type='submit' value='&Uuml;bermitteln'>
</form>

";


}
// ENDE darf Sehen

$PAGE->render(utf8_decode(utf8_encode($output)));
?>