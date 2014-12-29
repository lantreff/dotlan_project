<?php

//$sql_agent_queue = $DB->query("SELECT * FROM `project_ticket_agent_queue`");

$output .= 
"
<table width='100%' cellspacing='0' cellpadding='3' border='0'>
    <tbody>
		
<!--start OverviewNavBarMain-->
		<tr>
			<td>
			
				Queues: 
				<br />	
						
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
												( status = 3 AND sperre = '1' )
											");
											
	$count_ticket = mysql_num_rows($sql_queue_count_tickets);
	
$output .= 
"		
						<a href='sts/TicketQueue.php?queueid=".$out_queue['id']."&new=1' > ".$count_ticket." Ticket(s) in ".$out_queue['name']."</a> <br />
";
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