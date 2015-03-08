<?php
########################################################################
# Dienstplan Modul for dotlan             			                   #
#                                                                      #
# Copyright (C) 2013 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# Version 1.0                                                          #
########################################################################

$tag = 1;
$zeit_von = date('G');
$zeit_bis = ($zeit_von + 1);
if(date('N') == 5)
{
$tag = 1;
}
if(date('N') == 6)
{
$tag = 2;
}
if(date('N') == 7)
{
$tag = 3;
}

$sql_plan = $DB->query("SELECT * FROM project_dienstplan WHERE event_id = '".$event_id."' AND tag = '".$tag."'");

/*###########################################################################################*/

 $output .="<table cellspacing='0' cellpadding='0' border='0'>";
   	$output .="<tr >";
while($out_data_plan = $DB->fetch_array($sql_plan))
{ 
				$query = $DB->query("SELECT id_$zeit_bis AS u FROM project_dienstplan WHERE event_id = '".$event_id."' AND tag = '".$tag."' AND plan_name = '".$out_data_plan['plan_name']."'");
				$out_u_ids = $DB->fetch_array($query);
				$u_ids = explode(",",$out_u_ids['u']);
	if($u_ids[0] != -1)
	{
	$output .="<td >";
		$output .="<table cellspacing='0' cellpadding='1' border='0' >";
				$output .="<tr>";
					$output .="<td  valign='top' class='msghead' width='100'>".$out_data_plan['plan_name']."</td>";
				$output .="</tr>";
				$output .="<tr>";
					$output .="<td>";
						$output .="<table cellspacing='0' cellpadding='0' border='0'>";
									
						foreach($u_ids as $blubb){
								if($blubb == 0 || $blubb == -1) continue;
								else
								{
								$out_u_data = $DB->fetch_array( $DB->query("SELECT * FROM user WHERE id = '".$blubb."'"));
								$output .="<tr>";
								$output .="<td class='msgrow1' width='100'>".$out_u_data['nick']."</td>";			
								$output .="</tr>";
								}
								
						 } 
						$output .="</table>";       
					$output .="</td>";
				$output .="</tr>";
		$output .="</table>";
	$output .="</td>";
	}
}  

	$output .="</tr>";
$output .="</table>";
 

?>