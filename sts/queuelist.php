<?

$output .= 
"
<table width='100%' cellspacing='0' cellpadding='3' border='0'>
    <tbody>
		
<!--start OverviewNavBarMain-->
		<tr>
			<td class='menu'>
			
				Queues: 
					<b>
						Meine Tickets (".$count_my_ticket.")
					</b>
						
";
$sql_queue = $DB->query("SELECT * FROM `project_ticket_queue`");
	
while($out_queue = $DB->fetch_array($sql_queue))
{
	$sql_queue_count_tickets = $DB->query	("
												SELECT
													*
												FROM 
													`project_ticket_ticket`
												WHERE
													queue = ".$out_queue['id']."
												AND
													( status <> 1  AND  status <> 2 )
											");
											
	$count_ticket = mysql_num_rows($sql_queue_count_tickets);
	

	if(project_check_queue_view($out_queue['id'],$user_id))
	{
		$output .= 
		"		
			- <a href='TicketQueue.php?queueid=".$out_queue['id']."' >".$out_queue['name']." (".$count_ticket.")</a>
		";
	}
}

$output .= 
"			
			</td>
		</tr>
<!--stop OverviewNavBarMain -->
	
	</tbody>
</table>
";

?>