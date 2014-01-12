<?php

$sql_leihsystem_verliehen = $DB->query("SELECT * FROM project_leih_article WHERE ausleihe = '1' ORDER BY u_id ASC");

$output .= 
"
<br>
Verliehene Artikel:
<br>
<table  cellspacing='1' cellpadding='2' border='0'>
											<tbody>";

								while($out_leihe = $DB->fetch_array($sql_leihsystem_verliehen))
						{// begin while
						if($iCount % 2 == 0)
							{
								$currentRowClass = "msgrow2";
							}
							else
							{
									$currentRowClass = "msgrow1";
								
							}
						
						if ($out_leihe['rueckgabe_datum'] == '0000-00-00 00:00:00')
								{
									$currentRowClass = "msgrowORANGE";
								}
							if ($out_leihe['rueckgabe_datum'] == '0000-00-00 00:00:00' && $out_leihe['diff_zeit']  > 3600 )
								{
									$currentRowClass = "msgrowRED";
								}
								

						$out_user_artikel = $DB->fetch_array($DB->query("SELECT * FROM user WHERE id = ".$out_leihe['u_id'].""));
						$output .= "<tr >
										<td width='50%'  class='".$currentRowClass."' >
										".$out_leihe['bezeichnung']." an ".$out_user_artikel['nick']."
										</td>
									</tr>";
									
							$iCount++;
						} // end while


						$output .= "		</tbody>
									</table>
";

?>