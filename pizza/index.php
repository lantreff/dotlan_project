<?php
########################################################################
# Equipment Verwaltungs Modul for dotlan                               #
#                                                                      #
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# admin/equipment/index.php - Version 1.0                              #
########################################################################


include_once("../../../global.php");
include("../functions.php");
$event_id = $EVENT->next;
//$data = $DB->query("SELECT * FROM `catering_order_part` WHERE DATE_FORMAT( `time_added`, '%j' ) BETWEEN DATE_FORMAT( NOW() , '%j' )  AND DATE_FORMAT( DATE_ADD( NOW(), INTERVAL 1 HOUR), '%j') AND `status` = '4' ORDER BY time_added DESC");
$data = $DB->query("SELECT * FROM `catering_products` AS p  JOIN `catering_order_part` AS o ON  o.product_id = p.id WHERE p.group_id = 17 AND o.status = 4");

 /*###########################################################################################
Admin PAGE
*/

echo  "

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='de' lang='de'>
<head>
  <title>Pizza</title>
  <link rel='stylesheet' type='text/css' href='/../../styles/maxlan_lan.css' >
  <link rel='stylesheet' href='/../../styles/css/slide.css' type='text/css' media='screen' />
  <meta name='author' content='egge' />
  <meta name='keywords'                 content='dotlan, .lan, lanparty, lan-party, intranet, turnier, turniersystem, eventplanung, sitzplan, event, netzwerk, php' />
  <meta http-equiv='content-type'         content='text/html; charset=ISO-8859-1' />
  <meta http-equiv='Content-Style-Type'        content='text/css' />
</head>
<body style='background: url(/styles/maxlan_lan/Hintergrund_Clean.jpg); background-repeat: no-repeat; background-position: 50% 0px; background-clip: border-box; background-origin: padding-box; background-size: 100% 100%; background-attachment: fixed;'>
<div align='center' style='color:#FFFFFF;'> <h1> Abholbereite Bestellungen </h1> </div>
<br>
<table cellspacing='1' cellpadding='2' border='0' width='100%'>
  <tbody><tr valign='middle'>

    <td style='cursor: pointer; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 20px;' class='msghead3' nowrap='' width='30' align='center' title='Bestell Nr (Catering)'>BNr</td>
    <td style='cursor: pointer; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 20px;' class='msghead3' nowrap='' width='30' align='center' title='Produkt Bestell Nr'>Nr</td>
    <td style='cursor: pointer; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 20px;' class='msghead3' width='250'>Produktname</td>
    <td style='cursor: pointer; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 20px;' class='msghead3' width='10%' nowrap=''>Benutzer</td>
    <td style='cursor: pointer; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 20px;' class='msghead3' width='40' nowrap=''>Sitz</td>
    <td style='cursor: pointer; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 20px;' class='msghead3' width='25' nowrap=''>Preis</td>
    <td style='cursor: pointer; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 20px;' class='msghead3' width='100'nowrap=''>Status</td>
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
			$currentRowClass = "msgrow1";

		}
		else
		{
			$currentRowClass = "msgrow2";
		}

					echo  "
					  <tr valign='middle' class='".$currentRowClass."' style='cursor: pointer; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 20px;'>

						<td style='cursor: pointer; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 20px;' nowrap='' align='center'><b>&lt;".$out_data['order_id']."&gt;</b></td>
						<td style='cursor: pointer; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 20px;'  nowrap='' align='center'>".$out_data['order_nr']." </td>
						<td style='cursor: pointer; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 20px;' ><b>".$out_data['name']."</b> </td>";

						$out_user_data  = $DB->fetch_array($DB->query("SELECT * FROM user WHERE id = '".$out_data['user_id']."'  LIMIT 1"));

						echo  "
						<td style='cursor: pointer; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 20px;'  nowrap=''><a target='_blank' href='/user/?id=".$out_user_data['id']."'>".$out_user_data['nick']."</a></td>";


						echo  "
						<td style='cursor: pointer; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 20px;'  nowrap=''>".$out_ticket_zoom_user_sitz['sitz_nr']."</td>
						<td style='cursor: pointer; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 20px;'  nowrap='' align='right'><b>".$out_data['price']." &euro;</b></td>
						<td style='cursor: pointer; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 20px;'  nowrap=''>Abholbereit</td>
					  </tr>";
  			$iCount++;
					}
echo  "

			</tbody></table>

</body>
</html>

";

?>
