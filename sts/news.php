<?
/*
$count_mesage = 0;
$sql_queue_count_my_tickets = $DB->query	("
												SELECT
													*
												FROM 
													`project_ticket_ticket`
												WHERE
													agent = ".$user_id ."
												AND
													( status <> 1 or status <> 2 )
												AND
													sperre = 'gesperrt'
													
												
											");
while($out_queue_count_my_tickets = $DB->fetch_array($sql_queue_count_my_tickets))
{
	$sql_queue_count_my_tickets_ticket = 
			$DB->fetch_array(
								$DB->query	("
												SELECT
													*
												FROM 
													`project_ticket_antworten`
												WHERE
													ticket_id = ".$out_queue_count_my_tickets['id']."
												ORDER BY
													erstellt DESC
																							
											")
							);
	if( $sql_queue_count_my_tickets_ticket['gelesen'] == 0 && ( $sql_queue_count_my_tickets_ticket['user'] <> $user_id && $sql_queue_count_my_tickets_ticket['user'] <> "" ) && ( $sql_queue_count_my_tickets_ticket['type'] <> 'agent' || $sql_queue_count_my_tickets_ticket['type'] <> 'notiz' ) )
	{
		$count_mesage = $count_mesage + 1 ;
		//$output .= "Ticket iD: ".$out_queue_count_my_tickets['id']."antwort ID: ".$sql_queue_count_my_tickets_ticket['id']."<br>";
		//$output .= "user_antwort: ".$sql_queue_count_my_tickets_ticket['user']."  -  User jetzt: ".$user_id."<br>";
	}


}
if($count_mesage > 0)
{	
$output .=
"
<table width='100%' cellspacing='0' cellpadding='3' border='0'>
  <tbody><tr align='left'>
    <td width='25' align='right' class='contentbody'>

<!--start Warning-->
      <img border='0' alt='Info' src='../images/sts/warning.png'>
<!--stop Warning -->

    </td>
    <td class='contentbody'>:

<!--start LinkStart-->
      <a href='TicketLocked.php?filter=New'>
<!--stop LinkStart -->


<!--start Data-->
      Sie haben ".$count_mesage." neue Nachricht(en) bekommen!
<!--stop Data -->

<!--start LinkStop-->
      </a>
<!--stop LinkStop -->
    </td>
  </tr>
</tbody></table>
";
}
*/
?>