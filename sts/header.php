<?php

$sql_queue_count_my_tickets = $DB->query	("
												SELECT
													*
												FROM
													`project_ticket_ticket`
												WHERE
													agent = ".$user_id ."
												AND
													 sperre = '2'
												AND
													( status <> 1 AND status <> 2 )


											");
	$count_my_ticket = mysql_num_rows($sql_queue_count_my_tickets);


$output .= "<a name='top' >
			<a href='/admin/projekt/'>Projekt</a>
			&raquo;
			<a href='/admin/projekt/sts'>Support Ticket System</a>

";
	if($_SERVER['SCRIPT_NAME'] == '/admin/projekt/sts/TicketZoom.php' || $_SERVER['SCRIPT_NAME'] == '/admin/projekt/sts/TicketPriority.php' || $_SERVER['SCRIPT_NAME'] == '/admin/projekt/sts/TicketOwner.php' || $_SERVER['SCRIPT_NAME'] == '/admin/projekt/sts/TicketCustomer.php' || $_SERVER['SCRIPT_NAME'] == '/admin/projekt/sts/TicketNote.php' || $_SERVER['SCRIPT_NAME'] == '/admin/projekt/sts/TicketClose.php')
	{

$output .=
"
			&raquo; <a href='".substr($_SERVER['SCRIPT_NAME'], 19, -4).".php?ticketid=".$ticketid."'> ".substr($_SERVER['SCRIPT_NAME'], 19, -4)." </a>
";

	}
	if($_SERVER['SCRIPT_NAME'] == '/admin/projekt/sts/Dashboard.php')
	{
$output .=
"
			&raquo; ". substr($_SERVER['SCRIPT_NAME'], 19, -4) ."
";

	}
	else
	{
$output .=
"
			&raquo; <a href='TicketZoom.php?ticketid=".$ticketid."'> Ticket ".$_GET['ticketid']."</a>
";
	}


$output .=
"
			<hr class='newsline' width='100%' noshade=''>
			<br />";


$output .=
"
<table width='100%' cellspacing='0' cellpadding='2' border='0'>
  <tbody><tr>

    <td valign='top' align='left' class='nav'>
      <table cellspacing='2' cellpadding='2' border='0'>
        <tbody><tr>
			<td valign='top' align='center' class='nav'>
            <div title='Agent Dashboard'>
            <a href='Dashboard.php'><img border='0' alt='Alle Tickets' src='../images/sts/ticket_all_tickets.png'><br>Alle Tickets</a>
            </div>
          </td>
          </td>
		  <td valign='middle'>&nbsp;<img border='0' alt='-' src='../images/sts/h-line.png'>&nbsp;</td>
<!--start Item-->
          <td valign='top' align='center' class='nav'>
            <div title='&Uuml;bersicht &uuml;ber alle Tickets in Bearbeitung'>
            <a  href='TicketQueue.php'><img border='0' alt='Bereiche' src='../images/sts/ticket_queues.png'><br>Bereiche</a>
            </div>
          </td>
<!--stop Item -->
";
if($user_id || $DARF["add"] )
{
$output .=
"
<!--start Item-->
          <td valign='top' align='center' class='nav'>
            <div title='Neues Ticket wird erstellt'>
            <a href='TicketAdd.php'><img border='0' alt='Ticket anlegen' src='../images/sts/ticket_new_ticket.png'><br>Ticket anlegen</a>
            </div>
          </td>
<!--stop Item -->
";
}
$output .=
"
<!--start ItemPersonal-->
          <td valign='top' align='center' class='nav'>
            <div title='Meine Tickets (".$count_my_ticket.")'>
            <a href='TicketLocked.php'><img border='0' alt='Meine Tickets (".$count_my_ticket.")' src='../images/sts/ticket_my_processing_tickets.png'><br>Meine Tickets (".$count_my_ticket.")</a>
            </div>
          </td>
";
if( $ADMIN->check(GLOBAL_ADMIN) )
{
$output .=
"
<!--start Item-->
		  <td valign='middle'>&nbsp;<img border='0' alt='-' src='../images/sts/h-line.png'>&nbsp;</td>
          <td valign='top' align='center' class='nav'>
            <div title='TTS-Rechte'>
            <a href='./admin'><img border='0' alt='Ticket anlegen' src='../images/sts/ticket_orga_rights.png'><br>TTS-Rechte</a>
            </div>
          </td>
		  		  <td valign='middle'>&nbsp;<img border='0' alt='-' src='../images/sts/h-line.png'>&nbsp;</td>
<!--stop Item -->
";
}
$output .=
"
		  <td>
				<form name='suche' action='TicketQueue.php' method='POST'>
					<input name='suche'  style='width: 100%;' type='text' maxlength='120' title='Hier kann nach Ticket-ID, Inhalt, User-ID, Datum, ..... gesucht werden'>
		 </td>
		  <td >
					<input name='senden' value='Suchen' type='submit' >
				</form>
		  </td>
<!--stop ItemPersonal -->
        </tr>
      </tbody></table>
    </td>
  </tr>
</tbody>
</table>

";


?>
