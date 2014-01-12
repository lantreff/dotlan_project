<?

include_once("../../../global.php");
global $global;

$email 	= $global['email'];
$sitename 	= $global['sitename'];

$timestamp = time() - 5 * 60;  // 5 Minuten
$von	= date( "Y-m-d H:i:s", $timestamp);  
$Zeit	= date( "H:i:s", $timestamp);

$betreff 		= "Neuer Groupware Eintrag";
$from 			= "From:".$sitename ." Groupware <".$email .">";

$sql = "SELECT * FROM `groupware_todo` WHERE `td_date` > '".$von."'";
$data = $DB->query($sql);

						
while($out_data = $DB->fetch_array($data))
{// begin while
	$out_ersteller = $DB->fetch_array($DB->query("SELECT * FROM user WHERE id ='".$out_data['td_user_id']."'"));
	
	$email_text		=  utf8_decode(utf8_encode("Die Aufgabe ".$out_data['td_title']." in der Groupware ist von ".$out_ersteller['vorname']." ".$out_ersteller['nachname']." angelegt worden.\nEs wurde Folgendes eingetragen:\n".$out_data['td_description']."\n\nHier kannst du dir die Aufgabe angucken:\nhttp://www.".$_SERVER["SERVER_NAME"]."/groupware/todo/?id=".$out_data['td_id']."\n"));
	
		$orga_id =	$DB->query("SELECT * FROM user_g2u WHERE group_id ='".$out_data['td_group_id']."'");
		while($out_orga_id = $DB->fetch_array($orga_id))
			{
				$out_mail_grp = $DB->fetch_array($DB->query("SELECT * FROM user WHERE id ='".$out_orga_id['user_id']."'"));
			
				$empfaenger		= $out_mail_grp['email'];
				
				mail($empfaenger, $betreff, $email_text, $from);
				echo "Mail an ".$out_mail_grp['email']." gesenddet<br>";
			}
			
}

?>
