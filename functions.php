<?php
// my autoloader
require_once 'lib/class.autoload.php';

// boolval wrapper
if(!function_exists('boolval')) {
	function boolval($var) {
		return (bool)$var;
	}
}

global $DB, $CURRENT_USER, $global, $styles;
$style = $global['defaultstyle'];

date_default_timezone_set('Europe/Berlin');
$datum 	= date("Y-m-d H:i:s");
$user_id = $CURRENT_USER->id;
$dir 		= dirname($_SERVER['PHP_SELF'])."/";
//$event_id = $EVENT->next;
global $global;
$sitename = $global['sitename'];

$data_event = mysql_fetch_array( mysql_query("SELECT * FROM events ORDER BY id DESC LIMIT 1"));

$event_id_next = $EVENT->next_event_id;

if($event_id_next != $data_event['id'] )
{
	$event_id = $data_event['id'];
}
elseif($event_id_next == 0)
{
$event_id = $data_event['id'];

}
else
{
$event_id = $EVENT->next_event_id;
}

function list_events()
{
	$sql = "SELECT * FROM events ORDER BY begin DESC";
	$out =  mysql_query($sql);
	return $out;
}

function list_event_data($id)
{
	$sql = "SELECT * FROM events WHERE id = '".$id."' ";
	$out = mysql_fetch_array ( mysql_query($sql) );
	return $out;
}

function list_event_location_data($id)
{
	$sql = "SELECT * FROM event_location  WHERE id = '".$id."' ";
	$out = mysql_fetch_array ( mysql_query($sql) );
	return $out;
}

function list_orgas()
{
	$sql = "SELECT vorname, nachname, nick, u.id AS id FROM user AS u, user_orga AS o WHERE o.user_id = u.id AND o.display_team = 1 ORDER BY u.vorname";
	$out =  mysql_query($sql);
	return $out;
}

function list_user_groups_dotlan()
{
	$sql = "SELECT * FROM user_groups";
	$out =  mysql_query($sql);
	return $out;
}

function umlaute_ersetzen($text){
$such_array  = array ('ä', 'ö', 'ü', 'ß');
$ersetzen_array = array ('ae', 'oe', 'ue', 'ss');
$neuer_text  = str_replace($such_array, $ersetzen_array, $text);
return $neuer_text;
}
function time2german($date2german){

	$out =  substr(date("d.m.Y H:i:s",strtotime($date2german)), 0, -3);
	return $out;
}
function date2german($date2german){

	$out =  substr(date("d.m.Y",strtotime($date2german)), 0);
	return $out;
}
function timestamp_mysql2german($date) {

    $stamp['date']    =    sprintf("%02d.%02d.%04d",
                             substr($date, 6, 2),
                             substr($date, 4, 2),
                             substr($date, 0, 4));



    $stamp['time']    =    sprintf("%02d:%02d:%02d",
                             substr($date, 8, 2),
                             substr($date, 10, 2),
                             substr($date, 12, 2));

    return $stamp;
}

function birthday2german($date) {

    $birthday    =    sprintf("%02d.%02d.%04d",
                             substr($date, 8, 2),
                             substr($date, 5, 2),
                             substr($date, 0, 4));

    return $birthday;
}

// Timestamp in Datum umwandeln
function throwDateTime($Timestamp) {
$Tag = strftime ("%d.", $Timestamp);
$month = strftime ("%m.", $Timestamp);
$Jahr = strftime ("%Y ", $Timestamp);
$Hour = strftime ("%H:", $Timestamp);
$Minutes = strftime ("%M:", $Timestamp);
$Seconds = strftime ("%S", $Timestamp);
$Datum = $Tag.$month.$Jahr.$Hour.$Minutes.$Seconds;
return $Datum;
}


// Rechteverwaltung
$DARF = project_get_rights($MODUL_NAME);
////////////////////////////////////////////////
function project_get_rights($bereich){
  global $CURRENT_USER;

  $rechte = array();
  $query = mysql_query("SELECT * FROM project_rights_rights WHERE bereich = '".$bereich."'");
  while($row = mysql_fetch_array($query)){
    $query2 = mysql_query("SELECT * FROM project_rights_user_rights WHERE user_id = '".$CURRENT_USER->id."' AND right_id = '".$row["id"]."' LIMIT 1");
    if(mysql_num_rows($query2) < 1) $rechte[$row["recht"]] = false;
    else $rechte[$row["recht"]] = true;
  }
  return $rechte;
}

function project_check_queue_view($queueid1,$user1_id){
	global  $ADMIN;

	$out_agent_queue = mysql_query("SELECT * FROM `project_ticket_agent_queue` WHERE queueid = '".$queueid1."' AND user_id = '".$user1_id."' ") ;

	if(mysql_num_rows($out_agent_queue) > 0 )  // optional Globaler Admin darf alles!!!
	{
		return TRUE;
	}
}
// ########################################################################
// # String und NUMBER_INT abfangen made by Christian Egbers              #
// ########################################################################


function security_string_input($input_string){
$input_string = strip_tags($input_string);
$input_string = trim($input_string);
$input_string = filter_var($input_string, FILTER_SANITIZE_STRING);
$input_string = mysql_real_escape_string($input_string);
return $input_string;
}

function security_number_int_input($input_int, $min, $max)
{$security_return = 0;
	if ($min == '' && $max == '')
	{
			if (filter_var($input_int,FILTER_SANITIZE_NUMBER_INT))
			{

				return $input_int;
			}
			else
			{
				$security_return = 1;
				return '';
			}
	}
	else
	{
		$int_range = array("options" => array("min_range"=>$min, "max_range"=>$max));
		if (filter_var($input_int,FILTER_SANITIZE_NUMBER_INT,$int_range))
		{

			return $input_int;
		}
		else
		{
			$security_return = 1;
			return '';
		}
	}
}


	#####################
		#   EMAIL an Info	#
		#####################
//							an		von				Betreff			Nachricht			Ticket_ID
function info_mail(	$mail_von_id,	$mail_betreff,	$mail_betreff_zusatz, 	$mail_nachricht,	$mail_ticketid){
global   $global;
			$out_an 	= $global['email'];
			$out_von	= mysql_fetch_array( mysql_query("SELECT * FROM user WHERE id = ".$mail_von_id." LIMIT 1 ") );


			$email_text		= 	"
									<html>
											<head>
												<title>".$global['sitename']."  Support-Ticket</title>
											</head>
										<body>
								"
								."\n<p>"
									.$mail_nachricht
								."</p>"
								."\n"
								."\n"
									."<a href='http://".$_SERVER["SERVER_NAME"].$global['admin_sts_path']."'>Hier geht's zum Ticket-System</a>"
										."</bod>"
									."</html>
								";


			$empfaenger		= $out_an;
			$absender		= $out_von["email"];
			$mail_zusatz_betreff	= $mail_betreff_zusatz.$mail_betreff;

			$header  	 = "MIME-Version: 1.0\r\n";
			$header 	.= "Content-Type: text/html; charset=UTF-8\r\n";
			$header 	.= "Content-Transfer-Encoding: quoted-printable\r\n";
			$header 	.= "From: $absender\r\n";
			$header 	.= "Reply-To: $absender\r\n";
			$header 	.= "X-Mailer: PHP/".phpversion();


			######################################################################################################
			mail($empfaenger, $mail_zusatz_betreff, $email_text, $header);
			######################################################################################################

		return '';
}
function message(	$message_an_id,	$message_von_id,	$message_betreff,	$message_betreff_zusatz, 	$message_nachricht,	$message_ticketid){
global   $global;

	$out_pm_mail =
						mysql_fetch_array(

											mysql_query("
															SELECT
																*
															FROM
																`project_ticket_globals`
															WHERE
																type = 'mail_pm'

														")
										);
				if($out_pm_mail['wert'] == "1" )
				{
					user_mail(	$message_an_id,	$message_von_id,	$message_betreff,	$message_betreff_zusatz, 	$message_nachricht,	$message_ticketid);
				}
				else
				{
					user_pm(	$message_an_id,	$message_von_id,	$message_betreff,	$message_betreff_zusatz, 	$message_nachricht,	$message_ticketid);
				}


}

		#############
		#   EMAIL	#
		#############
//							an		von				Betreff			Nachricht			Ticket_ID
function user_mail(	$mail_an_id,	$mail_von_id,	$mail_betreff,	$mail_betreff_zusatz, 	$mail_nachricht,	$mail_ticketid){
global   $global;
			$out_an 	= mysql_fetch_array( mysql_query("SELECT * FROM user WHERE id = ".$mail_an_id." LIMIT 1 ") );
			$out_von	= mysql_fetch_array( mysql_query("SELECT * FROM user WHERE id = ".$mail_von_id." LIMIT 1 ") );


			$email_text		= 	"
									<html>
											<head>
												<title>".$global['sitename']."  Support-Ticket</title>
											</head>
										<body>
										<p>Hallo ".$out_an['vorname']."</p>
								"
								."\n<p>"
									.$mail_nachricht
								."</p>"
								."\n"
								."\n"
									."<a href='http://".$_SERVER["SERVER_NAME"]."/tts/TicketZoom.php?ticketid=".$mail_ticketid."'>Hier kannst du dein Ticket ansehen</a>"
										."</bod>"
									."</html>
								";


			$empfaenger		= $out_an["email"];
			$absender		= $global['email'];
			$mail_zusatz_betreff	= $mail_betreff_zusatz.$mail_betreff;

			$header  	 = "MIME-Version: 1.0\r\n";
			$header 	.= "Content-Type: text/html; charset=UTF-8\r\n";
			$header 	.= "Content-Transfer-Encoding: quoted-printable\r\n";
			$header 	.= "From: $absender\r\n";
			$header 	.= "Reply-To: $absender\r\n";
			$header 	.= "X-Mailer: PHP/".phpversion();


			######################################################################################################
			mail($empfaenger, $mail_zusatz_betreff, $email_text, $header);
			######################################################################################################

		return '';
}




		#############
		#   PM      #
		#############

function user_pm(	$pm_an_id,	$pm_von_id,	$pm_betreff, $pm_betreff_zusatz,	$pm_nachricht,	$pm_ticketid){
global   $global,$PRVMSG;
$datum 	= date("Y-m-d H:i:s");


		$out_an 	= mysql_fetch_array( mysql_query("SELECT * FROM user WHERE id = ".$pm_an_id." LIMIT 1 ") );
		$out_von	= mysql_fetch_array( mysql_query("SELECT * FROM user WHERE id = ".$pm_von_id." LIMIT 1 ") );
		$pm_zusatz_betreff	= $pm_betreff_zusatz.$pm_betreff;

		$pm_text		= 	strip_tags(
										"Hallo ".$out_an['vorname']
										."\n"
										."\n"
										.$pm_nachricht
										."\n"
										."\n"
										."http://".$_SERVER["SERVER_NAME"]."/tts/TicketZoom.php?ticketid=".$pm_ticketid
										."\n"
										."\n"
										."Mit freundlichem Gru&szlig;"
										."\n"
										.$out_von['vorname']." \'".$out_von['nick']."\' ".$out_von['nachname']
										."\n"
										.$global['sitename']." Team"

										);

















		$PRVMSG->generate_message($pm_an_id,"INBOX",$pm_an_id,0,$pm_zusatz_betreff,$pm_text);	





		######################################################################################################

}
		######################################
		#   PM  an agent bei antwort, ....   #
		######################################

function agent_pm(	$pm_an_id,	$pm_von_id,	$pm_betreff, $pm_betreff_zusatz,	$pm_nachricht,	$pm_ticketid ){
global   $global,$PRVMSG;
$datum 	= date("Y-m-d H:i:s");

		$out_an 	= mysql_fetch_array( mysql_query("SELECT * FROM user WHERE id = ".$pm_an_id." LIMIT 1 ") );
		$out_von	= mysql_fetch_array( mysql_query("SELECT * FROM user WHERE id = ".$pm_von_id." LIMIT 1 ") );
		$pm_zusatz_betreff	= $pm_betreff_zusatz.$pm_betreff." durch ".$out_von['vorname']." ".$out_von['nachname'];

		$pm_text		= 	strip_tags("Hallo ".$out_an['vorname']
							."\n"
							."\n"
							."Ticket Aktualisierung ".$pm_betreff_zusatz." "
							."\n"
							.$pm_nachricht
							."\n"
							."\n"
							."http://".$_SERVER["SERVER_NAME"].$global['admin_sts_path']."TicketZoom.php?ticketid=".$pm_ticketid);

















		$PRVMSG->generate_message($pm_an_id,"INBOX",$pm_an_id,0,$pm_zusatz_betreff,$pm_text);	





		
		######################################################################################################

}


##
# Berechnung der IP aus dem Sitz
##
function sitz_to_ip($sitz){
  global $ip_prefix, $ip_block;

  if(preg_match("/([A-HV])\-([0-9][0-9]?)$/",$sitz,$matches) && $matches[1] && $matches[2]){
    $block = $matches[1];
    $platz = (int) $matches[2];
    return $ip_prefix.$ip_block[$block].".".$platz;
  } else return false;
}




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Funktion zum speichern der Änderungen der Standard Antwort
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function antwort_save_changes($id,$text){
  global $DB;
  //$query = mysql_query("SELECT * FROM project_ticket_std_antworten WHERE id = '".$id."'");
	$query = mysql_query("UPDATE  `project_ticket_std_antworten` SET  `std_antwort` =  '".$text."' WHERE  `id` = ".$id.";");
	
	$ausgabe = "Daten wurden gesendet.";
	
return $ausgabe;
}
function antwort_save($antwort_titel_save,$text){
  global $DB;
 $insert_p_message = mysql_query("
										INSERT INTO  `project_ticket_std_antworten` (
										`id` ,
										`std_titel` ,
										`std_antwort`
										)
										VALUES (
										NULL ,  '".$antwort_titel_save."',  '".$text."'
										);
										");
	
	$ausgabe = "Daten wurden gesendet.";
	
return $ausgabe;
}



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ENDE Funktion zum speichern der Änderungen der Standard Antwort
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////







// BOXEN LEFT / RIGHT AUSBLENDEN !!!!!
$output .=" <style type='text/css'> #content_left { display:none } #content_right { display:none } </style>";
// BOXEN LEFT / RIGHT AUSBLENDEN !!!!!

########################################################################################################################
## Eventbezogene Abfragen ##

function check_user_bezahlt($id,$event_id)
{
	$sql = "SELECT * FROM `event_teilnehmer` WHERE user_id = ".$id." AND event_id = ".$event_id."";
	$out =  mysql_fetch_array( mysql_query($sql) );
	
	if($out['bezahlt'] == 1 || admin::check(IS_ADMIN))
	{
		return TRUE;
		
	}
	else
	{
		return FALSE;
	}
	
}
function check_user_angemeldet($user_id,$event_id)
{
	$sql = "SELECT * FROM `event_teilnehmer` WHERE user_id = ".$user_id." AND event_id = ".$event_id."";
	$out =  mysql_query($sql);
	
	if(mysql_num_rows($out) == 0)
	{
		return FALSE;
		
	}
	else
	{
		return TRUE;
	}
	
}
function check_user_anwesend($id,$event_id)
{
	$sql = "SELECT * FROM `event_teilnehmer` WHERE user_id = ".$id." AND event_id = ".$event_id."";
	$out =  mysql_fetch_array( mysql_query($sql) );

	if($out['anwesend'] <> "0000-00-00 00:00:00" || admin::check(IS_ADMIN))
	{
		return TRUE;

	}
	else
	{
		return FALSE;
	}

}
########################################################################################################################
?>
