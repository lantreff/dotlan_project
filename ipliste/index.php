<?php
########################################################################
# Iplisten Modul for dotlan             			                   #
#                                                                      #
# Copyright (C) 2013 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# admin/ipliste/index.php - Version 1.0                                #
########################################################################
$MODUL_NAME = "ipliste";
include_once("../../../global.php");
include("../functions.php");


$PAGE->sitetitle = $PAGE->htmltitle = _("IP Liste");
$id				= $_GET['id'];
$event_id = $EVENT->next;
$EVENT->getevent($event_id);

$läufer =0;
$läufer1 =0;

$ipadresse		= security_number_int_input($_POST['ip'],"","");
$bezeichnung	= security_string_input($_POST['bezeichnung']);
$mac			= preg_replace("/[^a-zA-Z0-9\: ]/",":",strtoupper ( security_string_input($_POST['mac']) ));
$cat			= security_string_input($_POST['category']);
$cat1			= security_string_input($_POST['category1']);
$update_domain	= $_POST['update_domain'];
$category = "";

if($_POST['category1'] <> "" )
{
	$category = $cat1;

}
else
{
	$category = $cat;

}



$breite = "150";

 /*###########################################################################################
Admin PAGE
*/


if(!$DARF["view"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

else
{
		$a = 'shortbarbit';
		$a1 = 'shortbarlink';

		if($_GET['action'] == 'add')
			{
				$a = 'shortbarbitselect';
				$b = 'shortbarbit';
				$c = 'shortbarbit';
				$d = 'shortbarbit';


				$a1 = 'shortbarlinkselect';
				$b1 = 'shortbarlink';
				$c1 = 'shortbarlink';
				$d1 = 'shortbarlink';


			}



 		if($DARF["view"] )
		{ //$ADMIN

			$output .= "<a name='top' >
				<a href='".$global['project_path']."'>Projekt</a>
				&raquo;
				<a href='".$global['project_path']."ipliste'>Ip-Liste</a>
				&raquo; ".$_GET['action']."
				<hr class='newsline' width='100%' noshade=''>
				<br />";

			if($DARF["add"] || $DARF["edit"])
			{
			$output .= "
	<table width='100%' cellspacing='1' cellpadding='2' border='0' class='shortbar'>
	  <tbody>
			<tr class='shortbarrow'>";
			if($DARF["add"] )
			{
				$output .= "
				<td width='".$breite."' class='".$a."'><a href='?hide=1&action=add' class='".$a1."'>IP Anlegen</a></td>";
			}
			$output .="
				<td width='4' class='shortbarbitselect'>&nbsp;</td>
				<td width='".$breite."' class='shortbarbit'><a href='export.php' target='_new' class='shortbarlink'>export</a></td>
				<td width='".$breite."' class='shortbarbit'><a href='export_zonefiles.php' target='_parent' class='shortbarlink'>hotsts-Datei erstellen</a></td>";
			if (file_exists('hosts'))
				{
					$output .="
					<td width='".$breite."' class='shortbarbit'><a href='hosts' target='_new' class='shortbarlink'>hosts-Datei &ouml;ffnen</a></td>";


				}
	$output .="</tr>
		</tbody>
	</table>
			
			<hr>
			<form name='update_domain' action='?hide=1&action=update_domain&comand=senden' method='POST' >
			<input name='update_domain' value='' size='50' type='text' maxlength='500'><input name='senden' value='Domain &auml;ndern' type='submit'>
			</form>
			<br>
			<b>Subnetsmaske:</b> 255.255.240.0 - <b>F&uuml;r Switches:</b> 255.255.254.0
			<br>
			<b>Gateway:</b> 10.10.1.1
			<br>
			<b>1. DNS:</b> 10.10.1.253
			<br>
			<b>2. DNS:</b> 10.10.1.1
			<br>
			<br>


				";
			}

				if($_GET['hide'] != 1)
				{ // hide
		 $sql_list_category = $DB->query("SELECT category FROM project_ipliste GROUP BY category");
		 $sql_list_category_dlink = $DB->query("SELECT category FROM project_ipliste GROUP BY category");

				$output .= "

					<b>DirektLink:</b>";



				while($out_list_category_dlink = $DB->fetch_array($sql_list_category_dlink))
						{// begin while

					$output .= "
					<a href='#".$out_list_category_dlink['category']."'>".$out_list_category_dlink['category']."</a>&nbsp;";
						}

				$output .= "<br><br>";




						while($out_list_category = $DB->fetch_array($sql_list_category))
						{// begin while




					//$out_category  = $DB->fetch_array($DB->query("SELECT * FROM project_ipliste WHERE id = '".$out_list_uID['u_id']."'"));
				$cat_list = 	str_replace(' ','',$out_list_category['category']);
				
				
				if(substr($out_list_category['category'],0,5)  == 'Block')
				{
					$display = "style=' display:none'\" ";
					$imgupdown = "../images/sts/collapse-up.png";
				}
				else
				{
					$display = "";
					$imgupdown = "../images/sts/expand-down.png";
				}
				
					$output .= "
	
				<table width='100%' cellspacing='1' cellpadding='2' border='0' style='border-bottom-color:#c33333;border-bottom-style:solid;border-bottom-width:2px;'>
					<tbody>
						<tr>
							<td>
								<a style='font-family: Arial,Helvetica,sans-serif,Verdana;font-size: 18px;font-weight: normal; line-height: 20px;' name='".$out_list_category['category']."'>
									<b>
										".$out_list_category['category']."
									</b>
								</a> 
							
								 - <a href='#top'>top</a> |
							
								<a href='#".$out_list_category['category']."' title='Bereich ein bzw. ausblenden!' onclick=showTable('".$cat_list."','".$cat_list."1'); ><img id='".$cat_list."1'   src='".$imgupdown."'> </a>
							</td>
						</tr>
					</tbody>
				</table>
				";

				$sql_list_ip = $DB->query("SELECT * FROM project_ipliste WHERE category = '".$out_list_category['category']."' GROUP BY ip ORDER BY inet_aton(ip) ");
			
					
				$output .= "
				<table width='100%' cellspacing='1' cellpadding='2' border='0' id='".$cat_list."' ".$display.">
							<tbody>
								<tr >
									<td width='80'  class='msghead'>
										IP
									</td>
									<td width='300'  class='msghead'>
										Bezeichnung
									</td>
									<td width='80'  class='msghead'>
										MAC
									</td>
									<td width='80' class='msghead'>
										DNS
									</td>";
							if($DARF["edit"]  || $DARF["del"] )
							{  // Admin
								$output .="
									<td width='45' class='msghead'>
										admin";
										
										
										if($DARF["add"] )
												{
										$output .= "
												<a href='?hide=1&action=add&add_cat=".$out_list_category['category']."' >
												<img src='../images/16/db_add.png' title='IP-Adresse in der Kategorie ".$out_list_category['category']." anlegen' align='right'></a>";
												}

												$output .= "
									</td>";
							}
							$output .="
								</tr>";



				$iCount = 0;
				while($out_list_ip = $DB->fetch_array($sql_list_ip))
						{// begin while
							if($iCount % 2 == 0)
							{
								$currentRowClass = "msgrow1";

							}
							else
							{
								$currentRowClass = "msgrow2";
							}
							
							$sql_check_ip = $DB->query("SELECT * FROM project_ipliste WHERE ip = '".$out_list_ip['ip']."'");
							
							$sql_list_ip_Bezeichnung = $DB->query("SELECT * FROM project_ipliste WHERE ip = '".$out_list_ip['ip']."'");
							$sql_list_ip_MAC = $DB->query("SELECT mac FROM project_ipliste WHERE ip = '".$out_list_ip['ip']."'");
							$sql_list_ip_DNS = $DB->query("SELECT * FROM project_ipliste WHERE ip = '".$out_list_ip['ip']."'");
							$sql_list_ip_DNS1 = $DB->query("SELECT * FROM project_ipliste WHERE ip = '".$out_list_ip['ip']."'");
							//$sql_list_ip_DNS2 = $DB->query("SELECT lan FROM project_ipliste WHERE bezeichnung = '".$out_list_ip['bezeichnung']."'");
							if (mysql_num_rows($sql_check_ip) > 1)
							{
								$currentRowClass = "msgrowRED";
								
							$output .= "

								<tr class='".$currentRowClass."'>
										<td >
											".$out_list_ip['ip']." 
										</td>
										<td >";
							while($out_list_ip_Bezeichnung = $DB->fetch_array($sql_list_ip_Bezeichnung))
								{
										
$output .= "							".htmlentities($out_list_ip_Bezeichnung['bezeichnung'])."<br>";
								}

$output .= "
										</td>
										<td >";
							while($out_list_ip_MAC = $DB->fetch_array($sql_list_ip_MAC))
								{
										
$output .= "							".htmlentities($out_list_ip_MAC['mac'])."<br>";
								}

$output .= "
										</td>
										<td >";
							
							while($out_list_ip_DNS = $DB->fetch_array($sql_list_ip_DNS))
							{
								$teile = explode(",", str_replace(' ','',$out_list_ip_DNS['dns']));
								
								foreach($teile as $list_dns)
									{
											
	$output .= "							<a href='http://".$list_dns.".".$out_list_ip_DNS['lan']."' target='_new'>".$list_dns.".".$out_list_ip_DNS['lan']."</a><br>";
									}																
							}
$output .= "
										</td>";
							if($DARF["edit"] || $DARF["del"] )
							{ //  Admin
								$output .="
										<td >";
								while($out_list_ip_DNS1 = $DB->fetch_array($sql_list_ip_DNS1))
									{
										if($DARF["edit"] )
										{ //  Admin
											$output .="
														<a href='?hide=1&action=edit&id=".$out_list_ip_DNS1['id']."' target='_parent'>
														<img src='../images/16/edit.png' title='\"".$out_list_ip_DNS1['bezeichnung']."\" anzeigen/&auml;ndern' ></a>
														";
											}
										if($DARF["del"] )
										{ //  Admin
											$output .="
														<a href='?hide=1&action=del&id=".$out_list_ip_DNS1['id']."' target='_parent'>
														<img src='../images/16/editdelete.png' title='\"".$out_list_ip_DNS1['bezeichnung']."\" l&ouml;schen'></a>
														<br>
													";
										}
									}
								$output .="
										</td>";
							}
								$output .="</tr>";
								
							}
							else
							{
							
												$output .= "

								<tr class='".$currentRowClass."'>
										<td >
											".$out_list_ip['ip']."  
										</td>
										<td >
											".htmlentities($out_list_ip['bezeichnung'])."
										</td>
										<td>
											".$out_list_ip['mac']."
										</td>
										<td >
											 ";
							
						
								$teile = explode(",", str_replace(' ','',$out_list_ip['dns']));
								
								foreach($teile as $list_dns)
									{
											
	$output .= "							<a href='http://".$list_dns.".".$out_list_ip['lan']."' target='_new'>".$list_dns.".".$out_list_ip['lan']."</a><br>";
									}																
						
$output .= "
										</td>";
							if($DARF["edit"] || $DARF["del"] )
							{ //  Admin
								$output .="
										<td >";

								if($DARF["edit"] )
								{ //  Admin
									$output .="
											<a href='?hide=1&action=edit&id=".$out_list_ip['id']."' target='_parent'>
											<img src='../images/16/edit.png' title='Deteils anzeigen/&auml;ndern' ></a>";
								}
								if($DARF["del"] )
								{ //  Admin
								$output .="
											<a href='?hide=1&action=del&id=".$out_list_ip['id']."' target='_parent'>
											<img src='../images/16/editdelete.png' title='IP l&ouml;schen'></a>
										";
								}
								$output .="
										</td>";
							}
								$output .="</tr>";
							
							}


						$iCount++;
						} // end while


						$output .= "
					</tbody>
						</table>
						";
										$sql_add_new_ip = "SELECT * FROM `project_ipliste` WHERE `category` = '".$out_list_category['category']."' ORDER BY inet_aton(ip) DESC LIMIT 1";
										$out_add_new_ip = $DB->fetch_array( $DB->query($sql_add_new_ip));
										
										$ip_plus1 = explode(".", $out_add_new_ip['ip']);
										$ip_neu = ( $ip_plus1[3] + 1);
										if($DARF["add"])
												{
										$output .= "
												<a href='?hide=1&action=add&ip=".$ip_plus1[0].".".$ip_plus1[1].".".$ip_plus1[2].".".$ip_neu."&add_cat=".$out_list_category['category']."' >
												<img src='../images/16/db_add.png' title='IP-Adresse ".$ip_plus1[0].".".$ip_plus1[1].".".$ip_plus1[2].".".$ip_neu." in dem Bereich ".$out_list_category['category']." anlegen'></a>";
												}

												$output .= "
						<br>";







	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						} // end while







			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// table viwe used io adress
	$output .= "
	<table>
		<tbody>
			<tr>

			";
	for($a=0;$a<=3;$a++)
	{
		$output .= "
			<td>

			";
	$output .= "<table>
					<tbody>";


			$output .= "<tr>
							<td> IP-Bereich 10.10.".$a.".x </td>
						</tr>
						<tr>
							<td>
								<table border='2' style='border-collapse: collapse;'>
									<tbody>

					";


								for($x=0;$x<=15;$x++)
									{	// for 0-16

										$output .= "
													<tr>";

													for($y=0;$y<=15;$y++)
														{
														if($x == 0)
														{
															$ip = $y;
														}
														if($x == 1)
														{
															$ip = $y+16;
														}
														if($x == 2)
														{
															$ip = $y+32;
														}
														if($x == 3)
														{
															$ip = $y+48;
														}
														if($x == 4)
														{
															$ip = $y+64;
														}
														if($x == 5)
														{
															$ip = $y+80;
														}
														if($x == 6)
														{
															$ip = $y+96;
														}
														if($x == 7)
														{
															$ip = $y+112;
														}
														if($x == 8)
														{
															$ip = $y+128;
														}
														if($x == 9)
														{
															$ip = $y+144;
														}
														if($x == 10)
														{
															$ip = $y+160;
														}
														if($x == 11)
														{
															$ip = $y+176;
														}
														if($x == 12)
														{
															$ip = $y+192;
														}
														if($x == 13)
														{
															$ip = $y+208;
														}
														if($x == 14)
														{
															$ip = $y+224;
														}
														if($x == 15)
														{
															$ip = $y+240;
														}

															$out_used_ip = $DB->fetch_array($DB->query("SELECT * FROM project_ipliste WHERE ip = '10.10.".$a.".".$ip."'"));


																if($ip == 0 && $a == 0)
																{

																	$output .= "

																				<td align='center' style='background-color: #AC021C; width: 6px; height: 6px;'>
																					<div id='box'>
																						<a href='?hide=1&action=edit&id='>
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																								<span>
																									<b>
																										NET-ID
																									</b>
																									<br>
																									Keine Normale IP :-)
																								</span>
																						</a>
																					</div>
																				</td>

																				";
																}
																if($ip == 0 && $a !=0 )
																{

																	$output .= "

																				<td align='center' style='background-color: #66DD66; width: 6px; height: 6px;'>
																					<div id='box'>
																						<a href='?hide=1&action=edit&id=".$out_used_ip['id']."'>
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																								<span>
																									<b>
																										Noch frei ..
																									</b>
																									<br>
																									IP: 10.10.".$a.".".$ip."
																								</span>
																						</a>
																					</div>
																				</td>

																				";
																}
																if(!$out_used_ip['ip'] && $ip > 0  && $ip < 255 )
																{
																	$addr ="10.10";
																	$output .= "

																				<td align='center' style='background-color: #66DD66; width: 6px; height: 6px;'>
																					<div id='box'>
																						<a href='?hide=1&action=add&ip=".$addr.".".$a.".".$ip."'>
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																								<span>
																									<b>
																										Noch frei ..
																									</b>
																									<br>
																									IP: 10.10.".$a.".".$ip."
																								</span>
																						</a>
																					</div>
																				</td>

																				";
																}
																if(!$out_used_ip['ip'] && $ip == 255 )
																{
																	$addr ="10.10";
																	$output .= "

																				<td align='center' style='background-color: #66DD66; width: 6px; height: 6px;'>
																					<div id='box'>
																						<a href='?hide=1&action=add&ip=".$addr.".".$a.".".$ip."'>
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																								<span>
																									<b>
																										Noch frei ..
																									</b>
																									<br>
																									IP: 10.10.".$a.".".$ip."
																								</span>
																						</a>
																					</div>
																				</td>

																				";
																}
																if($out_used_ip['ip']  && $ip > 0)
																{
																	$sql_used_ip_List = $DB->query("SELECT * FROM project_ipliste WHERE ip = '".$out_used_ip['ip']."'");

																	$output .= "

																				<td align='center' style='background-color: #AC021C; width: 6px; height: 6px;'>
																					<div id='box'>
																						<a href='?hide=1&action=edit&id=".$out_used_ip['id']."'>
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																								<span>
																									<b>
																										".$out_used_ip['category']."
																									</b>
																									<br>
																										IP: ".$out_used_ip['ip']."
																									<br>																									
																									";
																					while($out_used_ip_List = $DB->fetch_array($sql_used_ip_List))
																					{																												
																								$output .= "
																										<br>
																										MAC: ".$out_used_ip_List['mac']." 
																										<br>
																										DNS: ".$out_used_ip_List['dns']." 
																										<br>
																										Bez.: ".htmlentities($out_used_ip_List['bezeichnung'])."
																										<br>";
																					}
																						$output .= "
																								</span>
																						</a>
																					</div>
																				</td>

																				";
																}
																if($ip == 255 && $a == 15)
																{

																	$output .= "

																				<td align='center' style='background-color: #AC021C; width: 6px; height: 6px;'>
																					<div id='box'>
																						<a href='?hide=1&action=edit&id='>
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																								<span>
																									<b>
																										Broadcast
																									</b>
																									<br>
																									Keine Normale IP :-)
																								</span>
																						</a>
																					</div>
																				</td>

																				";

																}
																else
																{}


														}
													$output .= "</t>";






									} // ende for 0-15



													$output .= "
												</tbody>
											</table>

								</td>

							</tr>
						</tbody>
					</table>

					</td>
					";

			}

	$output .= "
			</tr>
			<tr>

			";
	for($z=4;$z<=7;$z++)
	{
		$output .= "
			<td>

			";
	$output .= "<table>
					<tbody>";


			$output .= "<tr>
							<td> IP-Bereich 10.10.".$z.".x </td>
						</tr>
						<tr>
							<td>
								<table border='2' style='border-collapse: collapse;'>
									<tbody>

					";


								for($x=0;$x<=15;$x++)
									{	// for 0-15

										$output .= "
													<tr>";

													for($y=0;$y<=15;$y++)
																			{
														if($x == 0)
														{
															$ip = $y;
														}
														if($x == 1)
														{
															$ip = $y+16;
														}
														if($x == 2)
														{
															$ip = $y+32;
														}
														if($x == 3)
														{
															$ip = $y+48;
														}
														if($x == 4)
														{
															$ip = $y+64;
														}
														if($x == 5)
														{
															$ip = $y+80;
														}
														if($x == 6)
														{
															$ip = $y+96;
														}
														if($x == 7)
														{
															$ip = $y+112;
														}
														if($x == 8)
														{
															$ip = $y+128;
														}
														if($x == 9)
														{
															$ip = $y+144;
														}
														if($x == 10)
														{
															$ip = $y+160;
														}
														if($x == 11)
														{
															$ip = $y+176;
														}
														if($x == 12)
														{
															$ip = $y+192;
														}
														if($x == 13)
														{
															$ip = $y+208;
														}
														if($x == 14)
														{
															$ip = $y+224;
														}
														if($x == 15)
														{
															$ip = $y+240;
														}
															$out_used_ip = $DB->fetch_array($DB->query("SELECT * FROM project_ipliste WHERE ip = '10.10.".$z.".".$ip."'"));


																if($ip == 0 && $z == 0)
																{

																	$output .= "

																				<td align='center' style='background-color: #AC021C; width: 6px; height: 6px;'>
																					<div id='box'>
																						<a href='?hide=1&action=edit&id='>
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																								<span>
																									<b>
																										NET-ID
																									</b>
																									<br>
																									Keine Normale IP :-)
																								</span>
																						</a>
																					</div>
																				</td>

																				";
																}
																if($ip == 0 && $z !=0 )
																{

																	$output .= "

																				<td align='center' style='background-color: #66DD66; width: 6px; height: 6px;'>
																					<div id='box'>
																						<a href='?hide=1&action=edit&id=".$out_used_ip['id']."'>
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																								<span>
																									<b>
																										Noch frei ..
																									</b>
																									<br>
																									IP: 10.10.".$z.".".$ip."
																								</span>
																						</a>
																					</div>
																				</td>

																				";
																}
																if(!$out_used_ip['ip'] && $ip > 0  && $ip < 255 )
																{
																	$addr ="10.10";
																	$output .= "

																				<td align='center' style='background-color: #66DD66; width: 6px; height: 6px;'>
																					<div id='box'>
																						<a href='?hide=1&action=add&ip=".$addr.".".$z.".".$ip."'>
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																								<span>
																									<b>
																										Noch frei ..
																									</b>
																									<br>
																									IP: 10.10.".$z.".".$ip."
																								</span>
																						</a>
																					</div>
																				</td>

																				";
																}
																if(!$out_used_ip['ip'] && $ip == 255 )
																{
																	$addr ="10.10";
																	$output .= "

																				<td align='center' style='background-color: #66DD66; width: 6px; height: 6px;'>
																					<div id='box'>
																						<a href='?hide=1&action=add&ip=".$addr.".".$z.".".$ip."'>
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																								<span>
																									<b>
																										Noch frei ..
																									</b>
																									<br>
																									IP: 10.10.".$z.".".$ip."
																								</span>
																						</a>
																					</div>
																				</td>

																				";
																}
																if($out_used_ip['ip']  && $ip > 0)
																{
																	$sql_used_ip_List = $DB->query("SELECT * FROM project_ipliste WHERE ip = '".$out_used_ip['ip']."'");

																	$output .= "

																				<td align='center' style='background-color: #AC021C; width: 6px; height: 6px;'>
																					<div id='box'>
																						<a href='?hide=1&action=edit&id=".$out_used_ip['id']."'>
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																								<span>
																									<b>
																										".$out_used_ip['category']."
																									</b>
																									<br>
																										IP: ".$out_used_ip['ip']."
																									<br>																									
																									";
																					while($out_used_ip_List = $DB->fetch_array($sql_used_ip_List))
																					{																												
																								$output .= "
																										<br>
																										MAC: ".$out_used_ip_List['mac']." 
																										<br>
																										DNS: ".$out_used_ip_List['dns']." 
																										<br>
																										Bez.: ".htmlentities($out_used_ip_List['bezeichnung'])."
																										<br>";
																					}
																						$output .= "
																								</span>
																						</a>
																					</div>
																				</td>

																				";
																}
																if($ip == 255 && $z == 15)
																{

																	$output .= "

																				<td align='center' style='background-color: #AC021C; width: 6px; height: 6px;'>
																					<div id='box'>
																						<a href='?hide=1&action=edit&id='>
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																								<span>
																									<b>
																										Broadcast
																									</b>
																									<br>
																									Keine Normale IP :-)
																								</span>
																						</a>
																					</div>
																				</td>

																				";

																}
																else
																{}


														}
													$output .= "</t>";






									} // ende for 0-15



													$output .= "
												</tbody>
											</table>

								</td>

							</tr>
						</tbody>
					</table>

					</td>
							
					";
				

			}
			
				$output .= "
			</tr>
			<tr>";
	
	for($z=8;$z<=11;$z++)
	{
		$output .= "
			<td>

			";
	$output .= "<table>
					<tbody>";


			$output .= "<tr>
							<td> IP-Bereich 10.10.".$z.".x </td>
						</tr>
						<tr>
							<td>
								<table border='2' style='border-collapse: collapse;'>
									<tbody>

					";


								for($x=0;$x<=15;$x++)
									{	// for 0-15

										$output .= "
													<tr>";

													for($y=0;$y<=15;$y++)
																			{
														if($x == 0)
														{
															$ip = $y;
														}
														if($x == 1)
														{
															$ip = $y+16;
														}
														if($x == 2)
														{
															$ip = $y+32;
														}
														if($x == 3)
														{
															$ip = $y+48;
														}
														if($x == 4)
														{
															$ip = $y+64;
														}
														if($x == 5)
														{
															$ip = $y+80;
														}
														if($x == 6)
														{
															$ip = $y+96;
														}
														if($x == 7)
														{
															$ip = $y+112;
														}
														if($x == 8)
														{
															$ip = $y+128;
														}
														if($x == 9)
														{
															$ip = $y+144;
														}
														if($x == 10)
														{
															$ip = $y+160;
														}
														if($x == 11)
														{
															$ip = $y+176;
														}
														if($x == 12)
														{
															$ip = $y+192;
														}
														if($x == 13)
														{
															$ip = $y+208;
														}
														if($x == 14)
														{
															$ip = $y+224;
														}
														if($x == 15)
														{
															$ip = $y+240;
														}
															$out_used_ip = $DB->fetch_array($DB->query("SELECT * FROM project_ipliste WHERE ip = '10.10.".$z.".".$ip."'"));


																if($ip == 0 && $z == 0)
																{

																	$output .= "

																				<td align='center' style='background-color: #AC021C; width: 6px; height: 6px;'>
																					<div id='box'>
																						<a href='?hide=1&action=edit&id='>
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																								<span>
																									<b>
																										NET-ID
																									</b>
																									<br>
																									Keine Normale IP :-)
																								</span>
																						</a>
																					</div>
																				</td>

																				";
																}
																if($ip == 0 && $z !=0 )
																{

																	$output .= "

																				<td align='center' style='background-color: #66DD66; width: 6px; height: 6px;'>
																					<div id='box'>
																						<a href='?hide=1&action=edit&id=".$out_used_ip['id']."'>
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																								<span>
																									<b>
																										Noch frei ..
																									</b>
																									<br>
																									IP: 10.10.".$z.".".$ip."
																								</span>
																						</a>
																					</div>
																				</td>

																				";
																}
																if(!$out_used_ip['ip'] && $ip > 0  && $ip < 255 )
																{
																	$addr ="10.10";
																	$output .= "

																				<td align='center' style='background-color: #66DD66; width: 6px; height: 6px;'>
																					<div id='box'>
																						<a href='?hide=1&action=add&ip=".$addr.".".$z.".".$ip."'>
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																								<span>
																									<b>
																										Noch frei ..
																									</b>
																									<br>
																									IP: 10.10.".$z.".".$ip."
																								</span>
																						</a>
																					</div>
																				</td>

																				";
																}
																if(!$out_used_ip['ip'] && $ip == 255 )
																{
																	$addr ="10.10";
																	$output .= "

																				<td align='center' style='background-color: #66DD66; width: 6px; height: 6px;'>
																					<div id='box'>
																						<a href='?hide=1&action=add&ip=".$addr.".".$z.".".$ip."'>
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																								<span>
																									<b>
																										Noch frei ..
																									</b>
																									<br>
																									IP: 10.10.".$z.".".$ip."
																								</span>
																						</a>
																					</div>
																				</td>

																				";
																}
																if($out_used_ip['ip']  && $ip > 0)
																{
																	$sql_used_ip_List = $DB->query("SELECT * FROM project_ipliste WHERE ip = '".$out_used_ip['ip']."'");

																	$output .= "

																				<td align='center' style='background-color: #AC021C; width: 6px; height: 6px;'>
																					<div id='box'>
																						<a href='?hide=1&action=edit&id=".$out_used_ip['id']."'>
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																								<span>
																									<b>
																										".$out_used_ip['category']."
																									</b>
																									<br>
																										IP: ".$out_used_ip['ip']."
																									<br>																									
																									";
																					while($out_used_ip_List = $DB->fetch_array($sql_used_ip_List))
																					{																												
																								$output .= "
																										<br>
																										MAC: ".$out_used_ip_List['mac']." 
																										<br>
																										DNS: ".$out_used_ip_List['dns']." 
																										<br>
																										Bez.: ".htmlentities($out_used_ip_List['bezeichnung'])."
																										<br>";
																					}
																						$output .= "
																								</span>
																						</a>
																					</div>
																				</td>

																				";
																}
																if($ip == 255 && $z == 15)
																{

																	$output .= "

																				<td align='center' style='background-color: #AC021C; width: 6px; height: 6px;'>
																					<div id='box'>
																						<a href='?hide=1&action=edit&id='>
																							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																								<span>
																									<b>
																										Broadcast
																									</b>
																									<br>
																									Keine Normale IP :-)
																								</span>
																						</a>
																					</div>
																				</td>

																				";

																}
																else
																{}


														}
													$output .= "</t>";






									} // ende for 0-15



													$output .= "
												</tbody>
											</table>

								</td>

							</tr>
						</tbody>
					</table>

					</td>
					";

			}

	$output .= "
			</tr>

			</tbody>
			</table>

			";



	}  // hide ende

	/////////////////////////////////////////////////////////////////////////////////////////////

	if($_GET['hide'] == "1")
	{
		if($_GET['action'] == 'del')
		{
			if (!$DARF["del"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

				if($_GET['comand'] == 'senden')

			{
				$del=$DB->query("DELETE FROM project_ipliste WHERE id = '".$_GET['id']."'");
				$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."#".$category."'>";
			}

			 $new_id = $_GET['id'];
			 $out_list_name = $DB->fetch_array($DB->query("SELECT * FROM project_ipliste WHERE id = '".$new_id."' LIMIT 1"));

		$output .="

					<h2 style='color:RED;'>Achtung!!!!<h2>
					<br />

					<p>Sind Sie sich sicher das
					<font style='color:RED;'>".$out_list_name['bezeichnung']."</font> gel&ouml;scht werden soll?</p>
					<br />
					<a href='?hide=1&action=del&comand=senden&id=".$new_id."' target='_parent'>
					<input value='l&ouml;schen' type='button'></a>
					 \t
					<a href='/admin/projekt/ipliste/#".$category."' target='_parent'>
					<input value='Zur&uuml;ck' type='button'></a>




				";



		}
		
		if($_GET['action'] == 'update_domain')
		{ $out_list_name = $DB->fetch_array($DB->query("SELECT * FROM project_ipliste"));
			if (!$DARF["edit"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

			if($_GET['comand'] == 'senden')

			{
				$update=$DB->query("ALTER TABLE  `project_ipliste` CHANGE  `lan`  `lan` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  '".$update_domain."'");
				$update=$DB->query("UPDATE `project_ipliste` SET `lan`= '".$update_domain."' WHERE lan = '".$out_list_name['lan']."';");
				$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."'>";
			}
			$output .=	"
						<p>Daten wurden gesendet!</p>					
						
						";
		
		}



		if($_GET['action'] == 'add')
		{
			if (!$DARF["add"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

			if($_GET['action'] == 'add' && $_GET['comand'] == 'senden')

			{
			/*
				$sql_check_ip = $DB->fetch_array($DB->query("SELECT * FROM project_ipliste WHERE ip = '".$ipadresse."'"));

				if ($sql_check_ip['ip'] == $ipadresse)
				{
					$output .= "
								<br />
								<b><font size='+1' style='color:RED;'>!! Achtung die IP ".$ipadresse." existiert schon !!</font></b>
								<br />
								<br />
							   ";

					$output .= "<meta http-equiv='refresh' content='4; URL=/admin/projekt/ipliste/#".$category."'>";

				}
				else
				{
				*/
						$insert=$DB->query("INSERT INTO `project_ipliste` (id, ip, bezeichnung, mac, dns, category) VALUES (NULL, '".$ipadresse."','".$bezeichnung."', '".$mac."', '".$dns."', '".$category."')");
						//Echo "VALUES (NULL, '".$ipadresse."','".$bezeichnung."', '".$mac."', '".$dns."', '".$category."')";
						$output .= "Daten wurden gesendet";
						$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."#".$category."'>";
				//}
			}


			$output .= "
								<form name='addip' action='?hide=1&action=add&comand=senden' method='POST' >
								<table class='msg2' width='100%' cellspacing='1' cellpadding='2' border='0'>
								<tbody>
									<tr >
										<td width='100'  class='msghead'>
											IP
										</td>
										<td width='300'  class='msghead'>
											Bezeichnung
										</td>
										<td width='150' class='msghead'>
											MAC
										</td>
										<td width='150' class='msghead'>
											DNS
										</td>
										<td width='100' class='msghead'>
											Kategorie
										</td>

									</tr>
									<tr class='msgrow1'>
										<td >
											<input name='ip' value='".$_GET['ip']."' size='40' type='text' maxlength='39'>
										</td>
										<td >
											<input name='bezeichnung' value='' size='50' type='text' maxlength='150'>
										</td>
										<td >
											<input name='mac' value='' size='20' type='text' maxlength='17'>
										</td>
										<td >
											<input name='dns' value='' size='30' type='text' maxlength='250'>
										</td>
										<td >

										<select name='category'>
										<option value='1'>w&auml;hlen</option>";

										$sql_list_category = $DB->query("SELECT category FROM project_ipliste GROUP BY category ASC");
							while($out_list_category = $DB->fetch_array($sql_list_category))
						{// begin while
										$output .="

										<option value='".$out_list_category['category']."'>".$out_list_category['category']."</option>";
						}

							$output .="
										</select>
										oder neu eintragen
										<input name='category1' value='".$_GET['add_cat']."' size='13' type='text' maxlength='25'>
										</td>

									</tr>
							</tbody>
								</table>

									<input name='senden' value='Daten senden' type='submit'> \t
									<br /><br /><a href='".$dir."' target='_parent'>Zur&uuml;ck zur &Uuml;bersicht</a>
									</form>";
		}


		if($_GET['action'] == 'edit' )
		{
			if (!$DARF["edit"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

			$sql_edit_ipliste = $DB->query("SELECT * FROM project_ipliste WHERE id = ".$id."");

			if($_GET['action'] == 'edit' && $_GET['comand'] == 'senden')

			{
					/*$sql_check_ip = $DB->fetch_array($DB->query("SELECT * FROM project_ipliste WHERE ip = '".$ipadresse."'"));

					if ($sql_check_ip['ip'] == $ipadresse)
					{
						$output .= "
									<br />
									<b><font size='+1' style='color:RED;'>! Achtung die IP ".$ipadresse." existiert schon !!</font></b>
									<br />
									<br />
								   ";

						$output .= "<meta http-equiv='refresh' content='4; URL=".$dir."#".$category."'>";

					}
					else
					{*/

						$update=$DB->query(	"UPDATE project_ipliste SET `ip` = '".$ipadresse."',`bezeichnung` = '".$bezeichnung."', `mac` = '".$mac."', `dns` = '".$dns."', `category` = '".$category."' WHERE `id` = '".$id."';");

						$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."#".$category."'>
				";
					//}

			}

			while($out_edit_ipliste = $DB->fetch_array($sql_edit_ipliste))
			{// begin while

			$output .= "
								<form name='editip' action='?hide=1&action=edit&comand=senden&id=".$id."' method='POST'>
								<table class='msg2' width='100%' cellspacing='1' cellpadding='2' border='0'>
								<tbody>
									<tr >
										<td width='100'  class='msghead'>
											IP
										</td>
										<td width='360'  class='msghead'>
											Bezeichnung
										</td>
										<td width='150' class='msghead'>
											MAC
										</td>
										<td width='150' class='msghead'>
											DNS
										</td>
										<td width='120' class='msghead'>
											Kategorie
										</td>

									</tr>
									<tr class='msgrow1'>
										<td >
											<input name='ip' value='".$out_edit_ipliste['ip']."' size='40' type='text' maxlength='39'>
										</td>
										<td>
											<input name='bezeichnung' value='".$out_edit_ipliste['bezeichnung']."' size='50' type='text' maxlength='150'>
										</td>
										<td >
											<input name='mac' value='".$out_edit_ipliste['mac']."' size='20' type='text' maxlength='17'>
										</td>
										<td >
											<input name='dns' value='".$out_edit_ipliste['dns']."' size='30' type='text' maxlength='250'>
										</td>
										<td >

										<select name='category'>
										<option value='1'>w&auml;hlen</option>";

										$sql_list_category = $DB->query("SELECT category FROM project_ipliste GROUP BY category ASC");
							while($out_list_category = $DB->fetch_array($sql_list_category))
						{// begin while
										$output .="

										<option value='".$out_list_category['category']."'>".$out_list_category['category']."</option>";
						}

							$output .="
										</select>
										oder neu eintragen
										<input name='category1' value='".$out_edit_ipliste['category']."' size='13' type='text' maxlength='25'>
										</td>

									</tr>
								</tbody>
							</table>

									<input name='senden' value='Daten senden' type='submit'> \t
									<br /><br /><a href='".$dir."#".$out_edit_ipliste['category']."' target='_parent'>Zur&uuml;ck zu ".$out_edit_ipliste['category']."</a>
									</form>";
			}
		}


	}




	}
/*###########################################################################################
ENDE Admin PAGE
*/

}
$PAGE->render(utf8_decode(utf8_encode($output) ));
?>