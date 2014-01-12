<?
########################################################################
# Dienstplan Modul for dotlan                                		   #
#                                                                      #
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# - Version 1.0                                    #
########################################################################


include_once("../../../global.php");
include("../functions.php");


$PAGE->sitetitle = $PAGE->htmltitle = _("Dienstplan");

$event_id = $EVENT->next;
$EVENT->getevent($event_id);


$plan			= security_string_input($_GET['plan']);
$bereich		= security_string_input($_GET['bereich']);
$u_nick			= $CURRENT_USER->nick;
$std			= security_number_int_input($_GET['std'],"","");
$zWuser_id		= security_number_int_input($_POST['user_id'],"","");
$zWuser_id1		= security_number_int_input($_POST['user_id1'],"","");
$heute 			= date("Y-m-d H:m:s");

$check_eventbeginn = 0;
$sql_get_event_beginning_date = $DB->query("SELECT * FROM events WHERE `id` = '".$event_id."' LIMIT 1");
$out_event_beginning_date = $DB->fetch_array($sql_get_event_beginning_date);
if($out_event_beginning_date >  $heute 	|| $out_event_beginning_date < $heute );
	{
		$check_eventbeginn = "OK";
	}



if($_POST['user_id'] == "ok")
{
	$zWu_id = $zWuser_id1;

}
else
{
	$zWu_id = $zWuser_id;

}

 /*###########################################################################################
Admin PAGE
*/


if(!$DARF_PROJEKT_VIEW){ $PAGE->error_die($HTML->gettemplate("error_rechtesystem"));}
else
{

	if($plan == 'Freitag')
			{
				$a = 'shortbarbitselect';
				$b = 'shortbarbit';
				$c = 'shortbarbit';


				$a1 = 'shortbarlinkselect';
				$b1 = 'shortbarlink';
				$c1 = 'shortbarlink';


			}
			if($plan == 'Samstag')
			{
				$a = 'shortbarbit';
				$b = 'shortbarbitselect';
				$c = 'shortbarbit';


				$a1 = 'shortbarlink';
				$b1 = 'shortbarlinkselect';
				$c1 = 'shortbarlink';


			}
			if($plan == 'Sonntag')
			{
				$a = 'shortbarbit';
				$b = 'shortbarbit';
				$c = 'shortbarbitselect';


				$a1 = 'shortbarlink';
				$b1 = 'shortbarlink';
				$c1 = 'shortbarlinkselect';


			}



		$output .= "<a name='top' >

				<a href=/admin/projekt/'>Projekt</a>
				&raquo;
				<a href=/admin/projekt/dienstplan/'>Dienstplan</a>
				&raquo; ".$_GET['plan']."
				<hr class='newsline' width='100%' noshade=''>
				<br />


<table class='shortbar' cellspacing='1' cellpadding='2' border='0'>
  <tbody>";
if($DARF_PROJEKT_DEL )
{
  	$output .= "
	<tr>
    	<td width='20%' class='".$a."'><a href='?plan=Freitag' class='".$a1."'>Freitag</a></td>
  		<td width='20%' class='".$b."'><a href='?plan=Samstag' class='".$b1."'>Samstag</a></td>
   		<td width='20%' class='".$c."'><a href='?plan=Sonntag' class='".$c1."'>Sonntag</a></td>
    	<td width='0' class='shortbarbitselect'>&nbsp;</td>
   		<td width='20%' class='shortbarbit'><a href='export.php' target='_new' class='shortbarlink'>export (testing)</a></td>
		<td width='20%' class='shortbarbit'><a href='?action=clear_all' target='_new' class='shortbarlink'>!! reset all !!</a></td>

  	</tr>
			";
}
else
{
	$output .= "
	<tr>
    	<td width='25%' class='".$a."'><a href='?plan=Freitag' class='".$a1."'>Freitag</a></td>
  		<td width='25%' class='".$b."'><a href='?plan=Samstag' class='".$b1."'>Samstag</a></td>
   		<td width='25%' class='".$c."'><a href='?plan=Sonntag' class='".$c1."'>Sonntag</a></td>
    	<td width='0' class='shortbarbitselect'>&nbsp;</td>
   		<td width='25%' class='shortbarbit'><a href='export.php' target='_new' class='shortbarlink'>export (testing)</a></td>
  	</tr>
			";

}

$output .= "
</tbody></table>
				<br />
				";

	if($_GET['action'] == 'clear_all' && $DARF_PROJEKT_DEL)
	{
		$clear=$DB->query("TRUNCATE TABLE project_dienstplan");

		$file = file_get_contents('project_dienstplan.sql');
    	$sql = explode(';',$file);

			for($i=0;$i<count($sql)-1 && $error=='';$i++)
			{

				$update=$DB->query(($sql[$i]));


			}

	$output .= "<meta http-equiv='refresh' content='0; URL=/admin/projekt/dienstplan/?plan=Freitag'>";
	}

	if($_GET['action'] == 'add01' && $DARF_PROJEKT_ADD)
	{
		 $sql_get_ids = $DB->query("SELECT * FROM project_dienstplan WHERE `std` = '".$std."' AND `plan` = '".$plan."';");
		  $check = true;
		 while($out_ids = $DB->fetch_array($sql_get_ids))
		 {
			 if($user_id == $out_ids['u_01'] || $user_id == $out_ids['u_02'])
			 {
				$output .= "<font size='+1' style='color:RED;'>
					Du kannst nicht an zwei Orten gleichzeitig sein!!</font>
				<meta http-equiv='refresh' content='3; URL=/admin/projekt/dienstplan/?plan=".$plan."'>
				";

				$check = false;
			 }


		 }
		if($check == true)
		{
			$update=$DB->query(	"UPDATE project_dienstplan SET `u_01` = '".$user_id."' WHERE `std` = '".$std."' AND `plan` = '".$plan."' AND `bereich` = '".$bereich."';");
				$output .= "Anwesenheit wurde eingetragen.
				<meta http-equiv='refresh' content='3; URL=/admin/projekt/dienstplan/?plan=".$plan."'>";


		}

	}
	if($_GET['action'] == 'add02' && $DARF_PROJEKT_ADD)

	{
	$sql_get_ids = $DB->query("SELECT * FROM project_dienstplan WHERE `std` = '".$std."' AND `plan` = '".$plan."';");
		  $check = true;
		 while($out_ids = $DB->fetch_array($sql_get_ids))
		 {
			 if($user_id == $out_ids['u_01'] || $user_id == $out_ids['u_02'])
			 {
				$output .= "<font size='+1' style='color:RED;'>
					Du kannst nicht an zwei Orten gleichzeitig sein!!</font>
				<meta http-equiv='refresh' content='3; URL=/admin/projekt/dienstplan/?plan=".$plan."'>
				";

				$check = false;
			 }


		 }
		if($check == true)
		{
			$update=$DB->query(	"UPDATE project_dienstplan SET `u_02` = '".$user_id."' WHERE `std` = '".$std."' AND `plan` = '".$plan."' AND `bereich` = '".$bereich."';");
				$output .= "Anwesenheit wurde eingetragen.
				<meta http-equiv='refresh' content='3; URL=/admin/projekt/dienstplan/?plan=".$plan."'>";


		}
	}



	if($_GET['action'] == 'rem01' && $DARF_PROJEKT_DEL)

	{
	$update=$DB->query(	"UPDATE project_dienstplan SET `u_01` = '0' WHERE `std` = '".$std."' AND `plan` = '".$plan."' AND `bereich` = '".$bereich."';");

	$output .= "Anwesenheit wurde ausgetragen.

	<meta http-equiv='refresh' content='0; URL=/admin/projekt/dienstplan/?plan=".$plan."'>";
	}
	if($_GET['action'] == 'rem02' && $DARF_PROJEKT_DEL)

	{
	$update=$DB->query(	"UPDATE project_dienstplan SET `u_02` = '0' WHERE `std` = '".$std."' AND `plan` = '".$plan."' AND `bereich` = '".$bereich."';");

	$output .= "Anwesenheit wurde ausgetragen.

	<meta http-equiv='refresh' content='0; URL=/admin/projekt/dienstplan/?plan=".$plan."'>";
	}
	if($_GET['action'] == 'sperren' &&  ( $DARF_PROJEKT_EDIT || $DARF_PROJEKT_DEL ))

	{
	$update=$DB->query(	"UPDATE project_dienstplan SET `u_01` = ''  WHERE `std` = '".$std."' AND `plan` = '".$plan."' AND `bereich` = '".$bereich."';");

	$output .= "Gesperrt.

	<meta http-equiv='refresh' content='0; URL=/admin/projekt/dienstplan/?plan=".$plan."'>";
	}
	if($_GET['action'] == 'sperren1' && ( $DARF_PROJEKT_EDIT || $DARF_PROJEKT_DEL ))

	{
	$update=$DB->query(	"UPDATE project_dienstplan SET `u_02` = ''  WHERE `std` = '".$std."' AND `plan` = '".$plan."' AND `bereich` = '".$bereich."';");

	$output .= "Gesperrt.

	<meta http-equiv='refresh' content='0; URL=/admin/projekt/dienstplan/?plan=".$plan."'>";
	}
		if($_GET['action'] == 'entsperren' && ( $DARF_PROJEKT_EDIT ||$DARF_PROJEKT_DEL ))

	{
	$update=$DB->query(	"UPDATE project_dienstplan SET `u_01` = '0'  WHERE `std` = '".$std."' AND `plan` = '".$plan."' AND `bereich` = '".$bereich."';");

	$output .= "Entsperrt.

	<meta http-equiv='refresh' content='0; URL=/admin/projekt/dienstplan/?plan=".$plan."'>";
	}
	if($_GET['action'] == 'entsperren1' && ( $DARF_PROJEKT_EDIT ||$DARF_PROJEKT_DEL ))

	{
	$update=$DB->query(	"UPDATE project_dienstplan SET `u_02` = '0'  WHERE `std` = '".$std."' AND `plan` = '".$plan."' AND `bereich` = '".$bereich."';");

	$output .= "Entsperrt.

	<meta http-equiv='refresh' content='0; URL=/admin/projekt/dienstplan/?plan=".$plan."'>";
	}
if($_GET['action'] == 'zuweisen' && ( $DARF_PROJEKT_EDIT ||$DARF_PROJEKT_DEL ))
	{
		$sql_user_orga = $DB->query("SELECT * FROM user_orga ORDER BY user_id ASC");

	if($_GET['comand'] == 'senden')

	{
	 $sql_get_ids = $DB->query("SELECT * FROM project_dienstplan WHERE `std` = '".$std."' AND `plan` = '".$plan."';");
		  $check = true;
		 while($out_ids = $DB->fetch_array($sql_get_ids))
		 {
			 if($user_id == $out_ids['u_01'])
			 {
				$output .= "<font size='+1' style='color:RED;'>
					Der zugewiesene User kann nicht an zwei Orten gleichzeitig sein!!</font>
				<meta http-equiv='refresh' content='3; URL=/admin/projekt/dienstplan/?plan=".$plan."'>
				";

				$check = false;
			 }


		 }
		if($check == true)
		{
			$update=$DB->query(	"UPDATE project_dienstplan SET `u_01` = '".$zWu_id."' WHERE `std` = '".$std."' AND `plan` = '".$plan."' AND `bereich` = '".$bereich."';");
				$output .= "Anwesenheit wurde eingetragen.
				<meta http-equiv='refresh' content='3; URL=/admin/projekt/dienstplan/?plan=".$plan."'>";


		}

	}


		$output .= "<form name='adduser' action='?action=zuweisen&comand=senden&plan=".$_GET['plan']."&bereich=".$_GET['bereich']."&std=".$_GET['std']."' method='POST'>
						<select name='user_id'>
									<option value='ok'>w&auml;hlen</option>";


						while($out_user_orga = $DB->fetch_array($sql_user_orga))
					{// begin while
								$out_orga_data = $DB->fetch_array($DB->query("SELECT * FROM user WHERE id = ".$out_user_orga['user_id']." "));
									$output .= "

									<option value='".$out_orga_data['id']."'>".$out_orga_data['vorname']." '".$out_orga_data['nick']."' ".$out_orga_data['nachname']."</option>";
					}

						$output .= "
									</select>
									oder user ID eintragen (0 = frei f&uuml;r alle)
									<input name='user_id1' value='' size='15' type='text' maxlength='25'>
									<br>
									<input name='senden' value='Daten senden' type='submit'>
								</form>";


}
if($_GET['action'] == 'zuweisen1' && ( $DARF_PROJEKT_EDIT || $DARF_PROJEKT_DEL ))
	{
		$sql_user_orga = $DB->query("SELECT * FROM user_orga ORDER BY user_id ASC");

	if($_GET['comand'] == 'senden')

	{
	 $sql_get_ids = $DB->query("SELECT * FROM project_dienstplan WHERE `std` = '".$std."' AND `plan` = '".$plan."';");
		  $check = true;
		 while($out_ids = $DB->fetch_array($sql_get_ids))
		 {
			 if($user_id == $out_ids['u_02'])
			 {
				$output .= "<font size='+1' style='color:RED;'>
					Der zugewiesene User kann nicht an zwei Orten gleichzeitig sein!!</font>
				<meta http-equiv='refresh' content='3; URL=/admin/projekt/dienstplan/?plan=".$plan."'>
				";

				$check = false;
			 }


		 }
		if($check == true)
		{
			$update=$DB->query(	"UPDATE project_dienstplan SET `u_02` = '".$zWu_id."' WHERE `std` = '".$std."' AND `plan` = '".$plan."' AND `bereich` = '".$bereich."';");
				$output .= "Anwesenheit wurde eingetragen.
				<meta http-equiv='refresh' content='3; URL=/admin/projekt/dienstplan/?plan=".$plan."'>";


		}

	}


		$output .= "<form name='adduser' action='?action=zuweisen1&comand=senden&plan=".$_GET['plan']."&bereich=".$_GET['bereich']."&std=".$_GET['std']."' method='POST'>
						<select name='user_id'>
									<option value='ok'>w&auml;hlen</option>";


						while($out_user_orga = $DB->fetch_array($sql_user_orga))
					{// begin while
								$out_orga_data = $DB->fetch_array($DB->query("SELECT * FROM user WHERE id = ".$out_user_orga['user_id']." "));
									$output .= "

									<option value='".$out_orga_data['id']."'>".$out_orga_data['vorname']." '".$out_orga_data['nick']."' ".$out_orga_data['nachname']."</option>";
					}

						$output .= "
									</select>
									oder user ID eintragen (0 = frei f&uuml;r alle)
									<input name='user_id1' value='' size='15' type='text' maxlength='25'>
									<br>
									<input name='senden' value='Daten senden' type='submit'>
								</form>";


}



		$output .= "<table class='msg2' width='100%' cellspacing='0' cellpadding='0' border='0'>
							<tbody>
								<tr>
									<td width='25' class='msghead'>Zeit</td>
									<td  class='msghead'>Catering</td>
									<td  class='msghead'>CheckIn</td>
									<td  class='msghead'>Support</td>
									<td  class='msghead'>Theke</td>
									<td  class='msghead'>Turniere</td>
									<td  class='msghead'>WC</td>
								</tr>
								<tr class='".$currentRowClass."'>
									<td class='shortbarbit_left'><!-- Zeiten -->
								<table width='25'>
									<tbody>";
									if( $_GET['plan'] == 'Freitag')
									{
										$begin = 17;
										$end  = 23;
									}
									if( $_GET['plan'] == 'Sonntag')
									{
										$begin = 0;
										$end  = 13;
									}
									else
									{
										$zeit = 0;
										$end  = 23;
									}

									for($a=$begin;$a<=$end;$a++)
									{

										if($a<9)
										{
										$output .= "
										<tr>
											<td height='15'  class='".$currentRowClass."'>0".$a.":00-</td>
										</tr>
										<tr >
											<td height='15'  class='".$currentRowClass."'  style='border-bottom: 1px solid rgb(0, 0, 0); padding-right: 0px; padding-left: 0px; padding-top: 1px;'>0".($a+1).":00</td>
										</tr>




										";
										}
										if($a == 9)
										{
										$output .= "
										<tr>
											<td height='15'  class='".$currentRowClass."'>0".$a.":00-</td>
										</tr>
										<tr class='".$currentRowClass."'  style='border-bottom: 1px solid rgb(0, 0, 0); padding-right: 0px; padding-left: 0px; padding-top: 1px;'>
											<td height='15'  class='".$currentRowClass."'>".($a+1).":00</td>
										</tr>



										";
										}
										if($a>9)
										{
										$output .= "
										<tr>
											<td height='15' >".$a.":00-</td>
										</tr>
										<tr >
											<td height='15'   style='border-bottom: 1px solid rgb(0, 0, 0); padding-right: 0px; padding-left: 0px; padding-top: 1px;'>".($a+1).":00</td>
										</tr>

										";
										}
										$iCount++;

									}

									$output .= "</tbody>
								</table>

									</td>";

						$sql_list_bereich = $DB->query("SELECT bereich FROM project_dienstplan GROUP BY bereich ASC");

						while($out_list_bereich = $DB->fetch_array($sql_list_bereich)) // && $check_eventbeginn == "OK"
					{// begin while



							$output .= "
									<td width='110'><!-- ".$out_list_bereich['bereich']." -->
								<table width='100%' >
									<tbody>	";
									$in_berech = $out_list_bereich['bereich'];



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


								  for($x=$begin;$x<=$end;$x++)
										{

											$out_dienst = $DB->fetch_array($DB->query("SELECT * FROM project_dienstplan WHERE bereich = '".$in_berech."' AND plan ='".$plan."' AND std = '".$x."'"));

											$out_u_01 = $DB->fetch_array($DB->query("SELECT * FROM user WHERE id = '".$out_dienst['u_01']."'"));
											$out_u_02 = $DB->fetch_array($DB->query("SELECT * FROM user WHERE id = '".$out_dienst['u_02']."'"));

											if( $out_dienst['u_01'] == "0"  && $out_dienst['u_02'] == "0"  )
												{
									$output .= "
											<tr>
												<td height='15' class='shortbarbit_left'>";

									$output .= "<a href='?action=add01&plan=".$plan."&bereich=".$in_berech."&std=".$out_dienst['std']."'>frei</a> &nbsp;";

												if($ADMIN->check(GLOBAL_ADMIN))
												{
												$output .= "

												<a href='?action=zuweisen&plan=".$plan."&bereich=".$in_berech."&std=".$out_dienst['std']."'>+</a>

												";

												}

									$output .= "
												</td>
											</tr>
											<tr >
												<td height='15'  style='border-bottom: 1px solid rgb(0, 0, 0); padding-right: 0px; padding-left: 0px; padding-top: 1px;'>
												";

												if($ADMIN->check(GLOBAL_ADMIN))
												{
												$output .= "

												<a href='?action=sperren1&plan=".$plan."&bereich=".$in_berech."&std=".$out_dienst['std']."'>X</a>

												";

												}
												$output .= "&nbsp;
												</td>
											</tr>

										";


											}

									if( $out_dienst['u_01'] > "0"  &&  $out_dienst['u_02'] == "0"  )
											{

									$output .= "
											<tr >
												<td height='15' >
												";
												if($user_id == $out_dienst['u_01'] || $ADMIN->check(GLOBAL_ADMIN))
												{

									$output .= "		<a href='?action=rem01&plan=".$plan."&bereich=".$in_berech."&std=".$out_dienst['std']."'>".$out_u_01['nick']."</a>";
												}
												else
												{
									$output .= "
													".$out_u_01['nick']."

												";

												}
									$output .= "	</td>
											</tr>
											";


									$output .= "
											<tr >
												<td height='15'  style='border-bottom: 1px solid rgb(0, 0, 0); padding-right: 0px; padding-left: 0px; padding-top: 1px;'>
													<a href='?action=add02&plan=".$plan."&bereich=".$in_berech."&std=".$out_dienst['std']."'>frei</a>";

												if($ADMIN->check(GLOBAL_ADMIN))
												{
												$output .= "

												<a href='?action=sperren1&plan=".$plan."&bereich=".$in_berech."&std=".$out_dienst['std']."'>X</a>
												<a href='?action=zuweisen1&plan=".$plan."&bereich=".$in_berech."&std=".$out_dienst['std']."'>+</a>

												";

												}
												$output .= "
												</td>
											</tr>

											";



											}
											if( $out_dienst['u_01'] == "0" &&  $out_dienst['u_02'] > "0"  )
											{
									$output .= "
											<tr >
												<td height='15' >";


									$output .= " <a href='?action=add01&plan=".$plan."&bereich=".$in_berech."&std=".$out_dienst['std']."'>frei</a>";

											if($ADMIN->check(GLOBAL_ADMIN))
												{
												$output .= "

												<a href='?action=sperren&plan=".$plan."&bereich=".$in_berech."&std=".$out_dienst['std']."'>X</a>
												<a href='?action=zuweisen&plan=".$plan."&bereich=".$in_berech."&std=".$out_dienst['std']."'>+</a>

												";

												}


									$output .= "</td>
											</tr>";

									$output .= "
											<tr >
												<td height='15'>";

										if($user_id == $out_dienst['u_02'] || $ADMIN->check(GLOBAL_ADMIN))
												{
									$output .= "	<a href='?action=rem02&plan=".$plan."&bereich=".$in_berech."&std=".$out_dienst['std']."'>".$out_u_02['nick']."</a>";
												}
												else
												{
									$output .= "
													".$out_u_02['nick']."

												";

												}

									$output .= "</td>
											</tr>

											";


											}

									if( $out_dienst['u_01'] > "0" &&  $out_dienst['u_02'] > "0"  )
											{
									$output .= "
											<tr>
												<td height='15'>";

										if($user_id == $out_dienst['u_01'] || $ADMIN->check(GLOBAL_ADMIN))
												{
									$output .= " <a href='?action=rem01&plan=".$plan."&bereich=".$in_berech."&std=".$out_dienst['std']."'>".$out_u_01['nick']."</a>";
												}
												else
												{
									$output .= "
													".$out_u_01['nick']."

												";




									$output .= "</td>
											</tr>";
											}
									$output .= "
											<tr >
												<td height='15'  style='border-bottom: 1px solid rgb(0, 0, 0); padding-right: 0px; padding-left: 0px; padding-top: 1px;'>";

										if($user_id == $out_dienst['u_02'] || $ADMIN->check(GLOBAL_ADMIN))
												{
									$output .= "	<a href='?action=rem02&plan=".$plan."&bereich=".$in_berech."&std=".$out_dienst['std']."'>".$out_u_02['nick']."</a>";
												}
												else
												{
									$output .= "
													".$out_u_02['nick']."

												";

												}

									$output .= "</td>
											</tr>

											";


											}

									if( $out_dienst['u_01'] == ""  && $out_dienst['u_02'] == "" )
											{
									$output .= "
										<tr >
											<td height='15'>
												";

												if($ADMIN->check(GLOBAL_ADMIN))
												{
												$output .= "&nbsp;
												<a href='?action=entsperren&plan=".$plan."&bereich=".$in_berech."&std=".$out_dienst['std']."'>&radic;</a>

												";

												}
												$output .= "&nbsp;
											</td>
										</tr>
										<tr >
											<td height='15'  style='border-bottom: 1px solid rgb(0, 0, 0); padding-right: 0px; padding-left: 0px; padding-top: 1px;'>
											&nbsp;
											</td>
										</tr>

											";


											}

									if(  $out_dienst['u_01'] == "0" &&   $out_dienst['u_02'] == ""    )
											{
									$output .= "
										<tr >
											<td height='15'>
												<a href='?action=add01&plan=".$plan."&bereich=".$in_berech."&std=".$out_dienst['std']."'>frei</a>									";

												if($ADMIN->check(GLOBAL_ADMIN))
												{
												$output .= "

												<a href='?action=sperren&plan=".$plan."&bereich=".$in_berech."&std=".$out_dienst['std']."'>X</a>
												<a href='?action=zuweisen&plan=".$plan."&bereich=".$in_berech."&std=".$out_dienst['std']."'>+</a>

												";

												}
												$output .= "
											</td>
										</tr>
										<tr >
											<td height='15'  style='border-bottom: 1px solid rgb(0, 0, 0); padding-right: 0px; padding-left: 0px; padding-top: 1px;' >
											";

												if($ADMIN->check(GLOBAL_ADMIN))
												{
												$output .= "


												<a href='?action=entsperren1&plan=".$plan."&bereich=".$in_berech."&std=".$out_dienst['std']."'>&radic;</a>



												";

												}
												$output .= "&nbsp;
											</td>
										</tr>

										";

											}

											if( $out_dienst['u_01'] > "0"  &&  $out_dienst['u_02'] == "" )
											{
									$output .= "
										<tr>
											<td height='15' class='shortbarbit_left_red'>
												<a href='?action=rem01&plan=".$plan."&bereich=".$in_berech."&std=".$out_dienst['std']."'>".$out_u_01['nick']."</a>
											</td>
										</tr>
										<tr >
											<td height='15'   style='border-bottom: 1px solid rgb(0, 0, 0); padding-right: 0px; padding-left: 0px; padding-top: 1px;'>
											";

												if($ADMIN->check(GLOBAL_ADMIN))
												{
												$output .= "


												<a href='?action=entsperren1&plan=".$plan."&bereich=".$in_berech."&std=".$out_dienst['std']."'>&radic;</a>



												";

												}
												$output .= "&nbsp;
											</td>
										</tr>

										";

											}






										} // ende for 0 - 23





								$output .= "	</tbody>
								</table>

									</td>";


					}
							$output .= "	</tbody>
								</table>
								";










$output .= "<div align='center'><br><a href='/admin/projekt/' target='_parent'>Zur&uuml;ck zur Projekt&uuml;bersicht</a></div>";
	}
/*###########################################################################################
ENDE Admin PAGE
*/

$PAGE->render(utf8_decode(utf8_encode($output) ));
?>