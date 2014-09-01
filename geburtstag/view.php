<?php


 $data = $DB->query("SELECT * FROM `user` WHERE DATE_FORMAT( `geb`, '%j' ) BETWEEN DATE_FORMAT( NOW() , '%j' ) AND DATE_FORMAT( DATE_ADD( NOW(), INTERVAL 7 DAY), '%j') ORDER BY MONTH(geb), DAY(geb)");
 
 $output 	.= "
											<table width='100%'>
												<tr >
													<td class='msghead' width='150'>
														Nick
													</td>
													<td class='msghead'  width='150'>
														Name
													</td>
													<td class='msghead'>
														Geburtsdatum
													</td>
												</tr>";
												
 while($out_data = $DB->fetch_array($data))
					{// begin while
 
		 			$gebdat = date("d.m.Y", strtotime($out_data['geb']));
 		
					$output 	.= "
												<tr  class='msgrow".(($i%2)?1:2)."'>
													<td>
														<a href=\"".$S->link(sprintf($USER->link['userdetails'],$out_data['id']))."\">".htmlentities($out_data['nick'])."</a>
													</td>
													<td>
														".$out_data['vorname']." ".$out_data['nachname']."
													</td>
													<td>
														".$gebdat."
													</td>
												</tr>
											
											
										";
 
					$i++;
					}
 


?>