<?php


$MODUL_NAME = "sts";
include_once("../../../global.php");
include("../functions.php");
include("TicketFunctios.php");


$PAGE->sitetitle = $PAGE->htmltitle = _("Support-Ticket-System");

// Sortierung //
// Variablen für die Sortierfunktion
	$sort			= "erstellt"; // Standardfeld das zum Sortieren genutzt wird
	$order			= "DESC"; // oder DESC | Sortierung aufwerts, abwerts
////////////////////////////////////////////////
$eintraege_pro_seite = 15;

 /*###########################################################################################
Admin PAGE
*/

if(!$DARF["view"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

else
{
$sql_tickets_neu = "
					SELECT
						*
					FROM
						project_ticket_ticket
					WHERE
						( status = 3 AND sperre = '1' )
					AND
						agent = 0
					ORDER BY
						".$sort."
						".$order."
					
				";

$sql_tickets_erinnerung = "
							SELECT
								*
							FROM
								project_ticket_ticket
							WHERE
								status = 4
							ORDER BY
								".$sort."
								".$order."
						";
					
$sql_tickets_offen = "
						SELECT
							*
						FROM
							project_ticket_ticket
						WHERE
							( status = 3 AND sperre <> '1' )

						ORDER BY
							".$sort."
							".$order."
					";
					

$sql_tickets_geschlossen = "
							SELECT
								*
							FROM
								project_ticket_ticket
							WHERE
								status = 1
							OR
								status = 2
							ORDER BY
								".$sort."
								".$order."
						";
				
include("header.php");
include("news.php");
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



$output .=  ticket_output_bereiche($sql_tickets_neu,"Neue Tickets","N_seite",$eintraege_pro_seite);

$output .=  ticket_output_bereiche($sql_tickets_erinnerung,"Erinnerungs Tickets","E_seite",$eintraege_pro_seite);

$output .=  ticket_output_bereiche($sql_tickets_offen,"Offene Tickets","O_seite",$eintraege_pro_seite);

$output .=  ticket_output_bereiche($sql_tickets_geschlossen,"Geschlossene Tickets","G_seite",$eintraege_pro_seite);



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
// ENDE darf Sehen

$output.= "<meta http-equiv='refresh' content='60; URL=Dashboard.php' /> ";
$PAGE->render(utf8_decode(utf8_encode($output)));
?>
