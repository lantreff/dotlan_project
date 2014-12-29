<?php


$event_id = $EVENT->next;

$sql_leihsystem_nicht_verliehen = $DB->query("SELECT * FROM project_equipment WHERE ist_leihartikel = '1' AND ausleihe != '1' ORDER BY  `category` ,  `bezeichnung`  ASC");
$sql_leihsystem_verliehen = $DB->query("SELECT * FROM  project_leih_leihe AS l INNER JOIN project_equipment AS e ON l.id_leih_artikel = e.id WHERE l.event_id = '".$event_id."' AND l.rueckgabe_datum = '0000-00-00 00:00:00' ");
$sql_leih_groups  = $DB->query("SELECT eg.bezeichnung AS eg_group_bezeichnung, eg.id AS eg_group_id FROM  project_equipment AS e INNER JOIN project_equipment_equip_group AS g ON g.id_equipment = e.id, project_equipment_groups AS eg WHERE e.ist_leihartikel = '1' AND eg.ausleihe = '0' GROUP BY eg_group_id");
$sql_leih_groups_verliehen = $DB->query("SELECT * FROM   project_leih_leihe AS l  INNER JOIN project_equipment_groups AS g ON l.id_leih_gruppe = g.id  WHERE l.event_id = '".$event_id."' AND l.rueckgabe_datum = '0000-00-00 00:00:00'");

/*###########################################################################################*/

$output .= 
"
<br>
Verliehene Artikel:
<br>
<table  cellspacing='1' cellpadding='2' border='0'>
											<tbody>";

				

								
							while($out_leihe = $DB->fetch_array($sql_leihsystem_verliehen))
						{// begin while
						
						$out_user_artikel = $DB->fetch_array($DB->query("SELECT * FROM user WHERE id = ".$out_leihe['id_leih_user'].""));
						$output .= "<tr >
										<td   class=\"msgrow".(($a%2)?1:2)."\" >
											".$out_leihe['bezeichnung']." an ".$out_user_artikel['nick']."
										</td>
									</tr>";
							$a++;
						} // end while
						while($out_leih_groups_verliehen = $DB->fetch_array($sql_leih_groups_verliehen))
						{// begin while

						$out_user_artikel = $DB->fetch_array($DB->query("SELECT * FROM user WHERE id = ".$out_leih_groups_verliehen['id_leih_user'].""));
						$output .= "<tr >
										<td   class=\"msgrow".(($i%2)?1:2)."\" >
											".$out_leih_groups_verliehen['bezeichnung']." an ".$out_user_artikel['nick']."
										</td>
									</tr>";
							$i++;
						} // end while
									
							

						$output .= "		</tbody>
									</table>
";

?>