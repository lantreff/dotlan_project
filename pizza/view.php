<?php

//$data = $DB->query("SELECT * FROM `catering_order_part` WHERE DATE_FORMAT( `time_added`, '%j' ) BETWEEN DATE_FORMAT( NOW() , '%j' )  AND DATE_FORMAT( DATE_ADD( NOW(), INTERVAL 1 HOUR), '%j') AND `status` = '4' ORDER BY time_added DESC");
$data = $DB->query("SELECT * FROM `catering_products` AS p  JOIN `catering_order_part` AS o ON  o.product_id = p.id WHERE p.group_id = 17 AND o.status = 4");

$event_id = $EVENT->next;
$output .=  "
<br>
<table cellspacing='1' cellpadding='2' border='0' width='100%'>
  <tbody><tr valign='middle'>

    <td class='msghead' nowrap='' width='30' align='center' title='Bestell Nr (Catering)'>BNr</td>
    <td class='msghead' nowrap='' width='30' align='center' title='Produkt Bestell Nr'>Nr</td>
    <td class='msghead' width='100%'>Produktname</td>
    <td class='msghead' nowrap=''>Benutzer</td>
    <td class='msghead' nowrap=''>Sitz</td>
    <td class='msghead' nowrap=''>Preis</td>
    <td class='msghead' nowrap=''>Status</td>
  </tr>";

 $iCount = 0;

 while($out_data = $DB->fetch_array($data))
					{// begin while
  /* Jede zweite Zeile anders darstellen */
  
   $out_ticket_zoom_user_sitz =
				$DB->fetch_array(
									$DB->query	("
												SELECT
													*
												FROM
													`event_teilnehmer`
												WHERE
													( user_id = ".$out_data['user_id']." AND event_id = ".$event_id.")
												LIMIT 1
											")
								);
								
		if($iCount % 2 == 0)
		{
			$currentRowClass = "msgrow2";

		}
		else
		{
			$currentRowClass = "msgrow1";
		}

					$output .=   "
					  <tr valign='middle' class=\"$currentRowClass\"' style='cursor: pointer;'>

						<td nowrap='' align='center'><b>&lt;".$out_data['order_id']."&gt;</b></td>
						<td  nowrap='' align='center'>".$out_data['order_nr']." </td>
						<td ><b>".htmlentities($out_data['name'])."</b> </td>";

						$out_user_data  = $DB->fetch_array($DB->query("SELECT * FROM user WHERE id = '".$out_data['user_id']."'  LIMIT 1"));

						$output .=   "
						<td  nowrap=''><a target='_blank' href='/user/?id=".$out_user_data['id']."'>".$out_user_data['nick']."</a></td>";


						$output .=   "
						<td  nowrap=''>".$out_ticket_zoom_user_sitz['sitz_nr']."</td>
						<td  nowrap='' align='right'><b>".$out_data['price']." &euro;</b></td>
						<td  nowrap=''>Abholbereit</td>
					  </tr>";
  			$iCount++;
					}
$output .=   "

			</tbody></table>


";

?>
