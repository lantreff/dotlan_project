<?php
include_once("../../../global.php");
include("../functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Support-Ticket-System");


$count = 1;
$tab = "&nbsp;";

$ticketid 	= $_GET['ticketid'];

$sql_historie = $DB->query	("
								SELECT
									*
								FROM 
									`project_ticket_antworten`
								WHERE
									ticket_id = ".$ticketid."
								
								
							");
//$num_rows = mysql_num_rows($sql_historie);
//ORDER BY
//erstellt DESC
include("header.php");
include("news.php");	
$output .=
"
<table width='100%' cellspacing='0' cellpadding='3' border='0'>
	<tbody>
		<tr>
			<td class='mainhead'>[ Historie von Ticket#: ".$ticketid." ] </td>
		</tr>
		<tr>
			<td class='menu'>
				<a href='TicketZoom.php?ticketid=".$ticketid."'>[ Zur&uuml;ck ]</a>
			</td>
		</tr>
		<tr>
			<td class='mainbody'>
				<table width='100%' cellspacing='0' cellpadding='2' border='0'>
					<tbody>
						<tr class='contenthead'>
							<td  width='60'>Aktion: </td>
							<td width='400'>Kommentar: </td>
							<td width='60' align='center'>Inhalt: </td>
							<td >User: </td>
							<td width='150'>Erstellt am: </td>
						</tr>
";	
						if (mysql_num_rows($sql_historie) == 0)
						{
						$output .=
						"	<tr>
								<td align='center'>
								<font style='color:RED;'>	!! Keine Daten vorhanden !! </font>
								</td>
							</tr>

						";

						}
						while($out_historie = $DB->fetch_array($sql_historie))
						{
								$out_user_data = 
												$DB->fetch_array(
																	$DB->query	("
																					SELECT
																						*
																					FROM 
																						`user`
																					WHERE
																						id = ".$out_historie['user']."
																					
																				")
																);
										
$output .=
"						

							<!--start Row-->
						<tr class=\"msgrow".(($i%2)?1:2)."\">
							<td>
								".ucfirst($out_historie['type'])."
							</td>
							<td>
								".ucfirst(htmlentities($out_historie['titel']))."
							</td>
							<td align='center'>
								<a href='TicketZoom.php?ticketid=".$ticketid."&queueid=2#Jump".$ticketid."'> <u>X</u> </a>
							</td>
							<td>
								".$out_user_data['email']." (".$out_user_data['vorname']." '".$out_user_data['nick']."' ".$out_user_data['nachname'].")
							</td>
							<td>
								".date("d.m.Y H:i:s",strtotime($out_historie['erstellt']))."
							</td>
						</tr>
							<!--stop Row -->
";
							$i ++;
						}							
$output .=
"							

					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td class='mainhead'>
				&nbsp;
			</td>
		</tr>
	</tbody>
</table>
";


$PAGE->render(utf8_decode(utf8_encode($output)));
?>