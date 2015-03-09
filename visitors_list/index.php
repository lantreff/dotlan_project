<?php

$MODUL_NAME="visitors_list";
include_once("../global.php");
include("../admin/projekt/functions.php");
include("visitors_functions.php");
include('header.php');



$output .= "
<script type='text/javascript' language='JavaScript'>
function sbm() {

	if (document.visitors.vorname.value==''){
	    alert('Bitte gebe deinen Vornamen an!');
		document.visitors.vorname.focus();
		return false;
	}    
	if (document.visitors.nachname.value==''){
	    alert('Bitte gebe deinen Nachnamen an!');
		document.visitors.nachname.focus();
		return false;
	}
	if (document.visitors.email.value!='') {
	   regX = new RegExp('^([a-zA-Z0-9\\-\\.\\_]+)(\\@)([a-zA-Z0-9\\-\\.]+)(\\.)([a-zA-Z]{2,4})$');
		if (!regX.test(document.visitors.email.value)) {
			alert('Es wurde keine g\u00fcltige E-Mail-Adresse eingetragen.');
			document.visitors.email.focus();
			return false;
	  }
	}	  
	if (document.visitors.plz.value==''){
	    alert('Bitte gebe die Postleitzahl an!');
		document.visitors.plz.focus();
		return false;
	}
	if (document.visitors.ort.value==''){
	    alert('Bitte gebe deinen Wohnort an!');
		document.visitors.ort.focus();
		return false;
	}		
			
			}
</script>	
";

//$cardNR 		= $_POST['card_nr'];
$cardNR = preg_replace('![^0-9]!', '', $_POST['card_nr']);
//$cardNR 		= $_GET['card_nr'];

if(isset($_POST["vorname"]) ) {
	$vorname 	= security_string_input($_POST["vorname"]);
	$nachname 	= security_string_input($_POST["nachname"]);
	$strasse 	= security_string_input($_POST["strasse"]);
	$hnr 		= security_number_int_input($_POST["hnr"],"","");
	$email 		= security_string_input($_POST["email"],"","");
	$geb_m 		= security_string_input($_POST["geb_m"]);
	$geb_j 		= security_number_int_input($_POST["geb_j"],"","");
	$plz 		= security_number_int_input($_POST["plz"],"","");
	$ort 		= security_string_input($_POST["ort"]);
	
	$sql = "INSERT INTO `project_visitors_list` ( `id` , `vorname` , `nachname` , `strasse` , `hnr` , `plz` , `ort` , `email` , `kommt` , `geht` , `card_nr` , `bezahlt` )
							VALUES ( 'NULL', '".$vorname."', '".$nachname."', '".$strasse."', '".$hnr."', '".$plz."', '".$ort."', '".$email."', '".$datum."', '', '0', '0' )";
	mysql_query( $sql);
	
	//$sql_id = "SELECT * FROM `project_visitors_list` WHERE vorname = '".$vorname."' AND nachname = '".$nachname."' AND plz = '".$plz."' AND ort = '".$ort."' ; ";
	//$out_id =  mysql_fetch_array( mysql_query( $sql_id));
	//
	//$sql_1 = "INSERT INTO `project_visitors_log` ( `id`, `visitor_id`, `date`, `vorname` , `nachname` , `kommt` , `geht` , `card_nr`)
	//						VALUES ( 'NULL', '".$out_id['id']."', '".$datum."', '".$vorname."', '".$nachname."', '".$datum."', '0000-00-00 00:00:00', '0')";
	//$sql_2 = "INSERT INTO `project_visitors_log` (`id`, `visitor_id`, `date`, `vorname`, `nachname`, `kommt`, `geht`, `cardnr`) 
	//						VALUES (NULL, '".$out_id['id']."', '".$datum."', '".$vorname."', '".$nachname."', '".$datum."', '0000-00-00 00:00:00', '0');";								
	//mysql_query( $sql_2);
	
}
if($_GET['hide'] != 1)
{
$output .= '
<form action="#" method="post"  onsubmit="return sbm();" id="visitors" name="visitors" >
<table cellspacing="0" cellpadding="0" width="100%" border="0">
  <tbody><tr height="1"><td><img width="1" height="1" border="0" alt="" src="/images/pixel.gif"></td></tr>
  <tr valign="top">
    <td class="rahmen_msg"><table cellspacing="0" cellpadding="0" width="100%" border="0"><tbody><tr><td style="background-image:url("/styles/maxlan/menubanner1.gif");" class="rahmen_msg2"><table cellspacing="0" cellpadding="0" width="100%" border="0"><tbody><tr><td class="rahmen_msgtitle">Angaben zur Person</td></tr></tbody></table></td></tr></tbody></table></td>
  </tr>       
  <tr height="1"><td><img width="1" height="1" border="0" alt="" src="/images/pixel.gif"></td></tr>
  <tr valign="top">
    <td class="rahmen_msg"><table cellspacing="1" cellpadding="5" width="100%" border="0" class="msg2">
      <tbody>
	  <tr class="msgrow2">
        <td width="30%"><b>Vorname*</b><br><span class="small"></span></td>
        <td width="70%">
		<input type="text" value="" name="vorname" size="15" style="width: 100%;"> 
<span style="color:#ff0000;" class="small"></span>
        </td>
      </tr>
      <tr class="msgrow1">
        <td width="30%"><b>Nachname*</b><br><span class="small"></span></td>
        <td width="70%">
		<input type="text" value="" name="nachname" size="15" style="width: 100%;"> 
<span style="color:#ff0000;" class="small"></span>
        </td>
      </tr>
      </tr>
      <tr class="msgrow2">
        <td width="30%"><b>E-Mail</b><br><span class="small"></span></td>
        <td width="70%">
		<input type="text" value="" name="email" size="15" style="width: 50%;"> 
<span style="color:#ff0000;" class="small"></span>
        </td>
      </tr>	  
	  <tr class="msgrow1">
        <td width="30%"><b>Straﬂe / Nr.</b><br><span class="small"></span></td>
        <td width="70%">
			<table cellspacing="0" cellpadding="0" width="100%" border="0">
				<tbody>
					<tr>
						<td width="100%">
							<input type="text" value="" style="width:99%" size="36" name="strasse">
						</td>
						<td>
							&nbsp;
						</td>
						<td>
							<input type="text" value="" name="hnr" size="5">
						</td>
					</tr>
				</tbody>
			</table>
			<span style="color:#ff0000;" class="small"></span>
        </td>
      </tr>
      <tr class="msgrow2">
        <td width="30%"><b>PLZ/Wohnort*</b><br><span class="small"></span></td>
        <td width="70%">
			<table cellspacing="0" cellpadding="0" width="100%" border="0">
				<tbody>
					<tr>
						<td>
							<input type="text" value="" name="plz" size="5">
						</td>
						<td>
							&nbsp;
						</td>
						<td width="100%">
							<input type="text" value="" style="width:100%" size="36" name="ort">
						</td>
					</tr>
				</tbody>
			</table>
				<span style="color:#ff0000;" class="small"></span>
        </td>
      </tr>
	  
           
    </tbody></table></td>
  </tr>

  
  <tr valign="top">
    <td class="rahmen_msg"><table cellspacing="1" cellpadding="5" width="100%" border="0" class="msg2">
      <tbody><tr class="msgrow1">
        <td width="100%" align="center" class="msgrow2" colspan="2"><input type="submit" value="Speichern">&nbsp;&nbsp;<input type="reset"></td>
      </tr>
    </tbody></table></td>
  </tr>
</tbody></table>
</form>
';
}
else
{

	if( $DARF['edit'] && $_GET['action'] == "list_visitors")
	{
		$sql = list_visitors();

		$output .=	'<table cellspacing="2" cellpadding="1" width="100%" border="0">';
		$output .=	'<tbody>
						<tr >
							<td class="msghead" width="8%">
								<b>Karten Nr.</b>
							</td>
							<td class="msghead">
								<b>Vorname</b>
							</td>
							<td class="msghead">
								<b>Nachname</b>
							</td>
							<td class="msghead">
								<b>Straﬂe / Nr.</b>
							</td>
							<td class="msghead">
								<b>PLZ / Wohnort</b>
							</td>
							<td class="msghead">
								<b>Gekommen</b>
							</td>
							<td class="msghead">
								<b>Ging</b>
							</td>
							<td class="msghead">
								<b>Anwesend</b>
							</td>
							<td class="msghead" align="center">
								<b>Bezahlt</b>
							</td>
						</tr>
		';

		
		while($out = mysql_fetch_array($sql))
		{
			$sql_cards = list_cards_not_used();
			$sql_cards1 = list_cards();
			$single_card = list_single_card($out['card_nr']);
			$anwesenheit = anwesenheit($out['kommt'],$datum);
			$class = '';		
			/*
			if($anwesenheit <= "8:00:00" && $out['geht'] == "0000-00-00 00:00:00")
			{
				$class = 'style="background: GREEN"';
			}
			if($anwesenheit >= "8:00:00" && $anwesenheit <= "12:00:00" && $out['geht'] == "0000-00-00 00:00:00")
			{
				$class = 'style="background: ORANGE"';
			}
			if($anwesenheit > "12:00:00" && $out['geht'] == "0000-00-00 00:00:00")
			{
				$class = 'style="background: RED"';
			}
			
			*/
			//$output .= '';
						
			$output .= '<tr class="msgrow'.(($z%2)+1).'" >';
			$output .= '	<td>
							';
							if($_GET['do'] == "submit_card_".$out["id"])
							{
			$output .= '		<form name="change_card_'.$out["id"].'" action="?hide=1&action=list_visitors&do=card_nr&cardNR='.$cardNR.'&id='.$out['id'].'" method="POST">
									<input type="text" value="" name="card_nr" size="10">
								</form>
					   ';
							}
							elseif(!$out['card_nr'])
							{
				$output .= '		<a href="?hide=1&action=list_visitors&do=submit_card_'.$out['id'].'&id='.$out['id'].'" onClick="return confirm(\'Hat der Gast Bezahlt?\');" > <input type="button" name="bezahlt" value="Scannen"></a>';						
							}
							else{
								$output .= $single_card['bezeichnung'];
							}
$output .= '
							</td>
							<td >
								'.$out['vorname'].'
							</td>
							<td >
								'.$out['nachname'].'
							</td>
							<td >
								'.$out['strasse'].' '.$out['hnr'].'
							</td>
							<td >
								'.$out['plz'].' '.$out['ort'].'
							</td>
							<td >
								'.time2german($out['kommt']).'
							</td>
							<td >
								'; 
									if($out['geht'] != "0000-00-00 00:00:00")
									{
$output .= 							time2german($out['geht']);
									
									}
$output .= '										
							</td>
							<td '.$class.'>';
							if($out['geht'] == "0000-00-00 00:00:00")
							{
								$output .= $anwesenheit;
							}
								
$output .= '
							</td>
							<td align="center">';
							
							if($out['bezahlt'] != 0)
							{
$output .= '					&#10004; ';
							}
$output .= '				</td>
						</tr>
			';
			$z++;
$output .= '					</form>';
		}
		
		
		if( $DARF['edit'] && $_GET['do'] == "bezahlt")
		{
			$meldung = bezahlt_melden($_GET['id']);
			$PAGE->redirect("{BASEDIR}visitors_list/index.php?hide=1&action=list_visitors",$PAGE->sitetitle,$meldung);
		}
		
		if( $DARF['edit'] && $_GET['do'] == "card_nr")
		{
			
			$card = list_single_card($cardNR);
			$is_card_used = is_cards_used($cardNR);
			if(!$is_card_used)
			{
				if($card['id'])
				{
					$meldung  = card_nr_melden($_GET['id'],$cardNR,$datum);
					$meldung .= bezahlt_melden($_GET['id']);
					$PAGE->redirect("{BASEDIR}visitors_list/index.php?hide=1&action=list_visitors",$PAGE->sitetitle,$meldung);
				}
				else
				{
						$output .= '<h2 align="center" style="color:RED;"> Karte nicht vorhanden!</h3><br>';
				}
			}
			else{
				$output .= '<h2 align="center" style="color:RED;">Die Karte wird bereits verwendet!</h3><br>';
			}
			
		}
	}
	
	if( $DARF['edit'] && $_GET['action'] == "geht")
		{
			$card 		= list_single_card($cardNR);
			$visitor 	= list_single_visitor_with_cardnr($cardNR);
			if($visitor['id'])
			{
			$output .= '
			<h2 align="center" style="color:RED;"> Bitte Daten Pr¸fen! </h2>
			<br>
				<table cellspacing="0" cellpadding="0" width="100%" border="0">
				  <tbody><tr height="1"><td><img width="1" height="1" border="0" alt="" src="/images/pixel.gif"></td></tr>
				<tr height="1"><td><img width="1" height="1" border="0" alt="" src="/images/pixel.gif"></td></tr>
				  <tr valign="top">
					<td class="rahmen_msg"><table cellspacing="1" cellpadding="5" width="100%" border="0" class="msg2">
					  <tbody>
					  <tr class="msgrow2">
						<td width="30%"><b>Vorname*</b><br><span class="small"></span></td>
						<td width="70%">
						'.$visitor['vorname'].'
				<span style="color:#ff0000;" class="small"></span>
						</td>
					  </tr>
					  <tr class="msgrow1">
						<td width="30%"><b>Nachname*</b><br><span class="small"></span></td>
						<td width="70%">
						'.$visitor['nachname'].' 
				<span style="color:#ff0000;" class="small"></span>
						</td>
					  </tr>
					  </tr>
					  <tr class="msgrow2">
						<td width="30%"><b>E-Mail</b><br><span class="small"></span></td>
						<td width="70%">
						'.$visitor['email'].'
				<span style="color:#ff0000;" class="small"></span>
						</td>
					  </tr>	  
					  <tr class="msgrow1">
						<td width="30%"><b>Straﬂe / Nr.</b><br><span class="small"></span></td>
						<td width="70%">
							<table cellspacing="0" cellpadding="0" width="100%" border="0">
								<tbody>
									<tr>
										<td width="100%">
											'.$visitor['strasse'].'
										</td>
										<td>
											&nbsp;
										</td>
										<td>
											'.$visitor['hnr'].'
										</td>
									</tr>
								</tbody>
							</table>
							<span style="color:#ff0000;" class="small"></span>
						</td>
					  </tr>
					  <tr class="msgrow2">
						<td width="30%"><b>PLZ/Wohnort*</b><br><span class="small"></span></td>
						<td width="70%">
							<table cellspacing="0" cellpadding="0" width="100%" border="0">
								<tbody>
									<tr>
										<td>
											'.$visitor['plz'].'
										</td>
										<td>
											&nbsp;
										</td>
										<td width="100%">
											'.$visitor['ort'].'
										</td>
									</tr>
								</tbody>
							</table>
								<span style="color:#ff0000;" class="small"></span>
						</td>
					  </tr>
					  
						   
					</tbody></table></td>
				  </tr>

				  
				  <tr valign="top">
					<td class="rahmen_msg"><table cellspacing="1" cellpadding="5" width="100%" border="0" class="msg2">
					  <tbody><tr class="msgrow1">
						<td width="100%" align="center" class="msgrow2" colspan="2">
							<a href="?hide=1&action=geht&id='.$visitor['id'].'&kommt='.$visitor['kommt'].'&senden=ok">
								<input type="submit" value="Geht melden!">
							</a>
						</td>
					  </tr>
					</tbody></table></td>
				  </tr>
				</tbody></table>
				
			';
			}
			else{
				$output .= '
					<h2 align="center" style="color:RED;"> Keine Daten gefunden! </h2>
				';
			}
			
			if($_GET['senden'] == "ok")
			{
				$meldung = geht_melden($_GET['id'],$_GET['kommt'],$datum);
				$PAGE->redirect("{BASEDIR}visitors_list/index.php?hide=1&action=list_visitors",$PAGE->sitetitle,$meldung);
			}
			
			
		}
	
	if( $DARF['edit'] && $_GET['action'] == "list_cards")
	{	
		if($_GET['hide1'] != 1)
		{
		$sql = list_cards();
		
		$output .=	"<table cellspacing='1' cellpadding='2' border='0' class='msg2'>
  						<tbody>
							<tr class='shortbarrow'>
								<td width='100' class='shortbarbit'> <a href='?hide=1&hide1=1&action=list_cards&do=add' class='shortbarlink'>Karten hinzuf&uuml;gen</a></td>
							</tr>
						</tbody>
					</table>
					<BR>
							";
							
		$output .=	'<table cellspacing="0" cellpadding="0" width="100%" border="0">';
		$output .=	'<tbody>
						<tr>
							
							<td width="80"  class="msghead">
								Karten Nr.
							</td>
							<td class="msghead">
								Bezeichnung
							</td>
							<td width="50"  class="msghead" >
								Admin
							</td>
							
							
						</tr>
		';
		
		while($out = mysql_fetch_array($sql))
		{
			
			$output .= '
			<tr class="msgrow'.(($z%2)+1).'">
							
							<td>
								'.$out['nr'].'
							</td>
							<td >
								'.$out['bezeichnung'].'
							</td>
							<td>';
							if($DARF["edit"] )
							{ //  Admin
								$output .="
											<a href='?hide=1&hide1=1&action=list_cards&do=edit&id=".$out['id']."' target='_parent'>
											<img src='../admin/projekt/images/16/edit.png' title='\"".$out['bezeichnung']."\" anzeigen/&auml;ndern' ></a>
											";
								}
							if($DARF["del"] )
							{ //  Admin
								$output .="
											<a href='?hide=1&hide1=1&action=list_cards&do=del&id=".$out['id']."' target='_parent'>
											<img src='../admin/projekt/images/16/editdelete.png' title='\"".$out['bezeichnung']."\" l&ouml;schen'></a>
											<br>
										";
							}
							
				$output .= '				
							</td>
							
						</tr>
			';
			
			$z++;
		}
		
		$output .= '	
					</tbody>
				</table>';
		}
		
		if( ( $DARF['edit'] ||  $DARF['add'] ) &&  $_GET['do'] == "add" || $_GET['do'] == "edit" )
		{
			if($_GET['do'] == "edit")
			{
				$out = list_single_card($_GET['id']);
							
			}
			
			$output .= "
								<form name='addip' action='?hide=1&hide1=1&action=list_cards&do=".$_GET['do']."&id=".$_GET['id']."&senden=ok' method='POST' >
								<table class='msg2' width='100%' cellspacing='1' cellpadding='2' border='0'>
								<tbody>
									<tr >
										<td width='100'  class='msghead'>
											NR.
										</td>
										<td width='300'  class='msghead'>
											Bezeichnung
										</td>
									</tr>
									<tr class='msgrow1'>
										<td >
											<input name='nr' value='".$out['nr']."' size='10' type='text' maxlength='20'>
										</td>
										<td >
											<input name='bezeichnung' value='".$out['bezeichnung']."' size='50' type='text' maxlength='150'>
										</td>
									</tr>
							</tbody>
								</table>

									<input name='senden' value='Daten senden' type='submit'> \t
									<br /><br /><a href='".$dir."' target='_parent'>Zur&uuml;ck zur &Uuml;bersicht</a>
									</form>";
									
			if($_GET['do'] == "add" && $_GET['senden'] == "ok")
			{
				$meldung = card_add($_POST);
				$PAGE->redirect("{BASEDIR}visitors_list/index.php?hide=1&action=list_cards",$PAGE->sitetitle,$meldung);
			}
			if($_GET['do'] == "edit" && $_GET['senden'] == "ok")
			{
				$meldung = card_edit($_POST,$_GET['id']);
				$PAGE->redirect("{BASEDIR}visitors_list/index.php?hide=1&action=list_cards",$PAGE->sitetitle,$meldung);
			}
			
		}
				
		if( $DARF['dell'] && $_GET['do'] == "del")
		{
			$out = list_single_card($_GET['id']);
			
			$output .= "
					<h2 style='color:RED;'>Achtung!!!!<h2>
					<br />

					<p>Sind Sie sich sicher das
					<font style='color:RED;'>".$out['bezeichnung']."</font> gel&ouml;scht werden soll?</p>
					<br />
					<a href='?hide=1&hide1=1&action=list_cards&do=del&id=".$out['id']."&senden=ok' target='_parent'>
					<input value='l&ouml;schen' type='button'></a>
					 \t
					<a href='{BASEDIR}visitors_list/index.php?hide=1&action=list_cards' target='_parent'>
					<input value='Zur&uuml;ck' type='button'></a>
			";
			
			
			if($_GET['senden'] == "ok")
			{
				$meldung = card_del($_GET['id']);
				$PAGE->redirect("{BASEDIR}visitors_list/index.php?hide=1&action=list_cards",$PAGE->sitetitle,$meldung);
			}
		}	
	}
	
	if( $DARF['edit'] && $_GET['action'] == "historie")
	{	
		
		$sql 		= list_visitors_log();
				
		$output .=	'<table cellspacing="0" cellpadding="0" width="100%" border="0">';
		$output .=	'<tbody>
						<tr>
							<td class="msghead">
								Vorname
							</td>
							<td class="msghead">
								Nachname
							</td>
							<td  class="msghead" >
								kommt
							</td>
							<td  class="msghead" >
								geht
							</td>
							<td  class="msghead" >
								Karten Nr.
							</td>
							<td  class="msghead" >
								Anwesenheit 
							</td>
						</tr>
		';
		
		while($out = mysql_fetch_array($sql))
		{
			$out_card 	= list_single_card($out['cardnr']);
			$anwesenheit = anwesenheit($out['kommt'],$out['geht']);
			$class = 'class="msgrow'.(($z%2)+1).'"';		
			/*
			if($anwesenheit < "8:00:00" && $out['geht'] == "0000-00-00 00:00:00")
			{
				$class = 'style="background: GREEN"';
			}
			if($anwesenheit > "8:00:00" && $anwesenheit < "12:00:00" && $out['geht'] == "0000-00-00 00:00:00")
			{
				$class = 'style="background: ORANGE"';
			}
			if($anwesenheit > "12:00:00" && $out['geht'] == "0000-00-00 00:00:00")
			{
				$class = 'style="background: RED"';
			}
			*/
			$output .= '
						<tr class="msgrow'.(($z%2)+1).'">
							<td>
								'.$out['vorname'].'
							</td>
							<td>
								'.$out['nachname'].'
							</td>
							<td>
								'.time2german($out['kommt']).'
							</td>
							<td>
								'; 
									if($out['geht'] != "0000-00-00 00:00:00")
									{
$output .= 							time2german($out['geht']);
									
									}
$output .= '										
							</td>
							<td>
								'.$out['cardnr'].' ('.$out_card['bezeichnung'].')
							</td>
							<td '.$class.'>
								'.$anwesenheit.'
							</td>
						</tr>
			';
			
			$z++;
		}
		
		$output .= '	
					</tbody>
				</table>';
		
	}
	
	if($_GET['action'] == "clear")
	{
		if (!$DARF["del"]) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

		if($_GET['comand'] == 'senden')
		{
			$clear_list = mysql_query("TRUNCATE TABLE project_visitors_list");
			$clear_log	= mysql_query("TRUNCATE TABLE project_visitors_log");
			$meldung = "Liste gelˆscht";
			$PAGE->redirect($dir."?hide=1&action=list_visitors",$PAGE->sitetitle,$meldung);
		}
		$output .= "

				<h2 style='color:RED;'>Achtung!!!!<h2>
				<br />

				<p>Sind Sie sich sicher das die Liste gel&ouml;scht werden soll?</p>
				<br />
				<a href='?hide=1&action=clear&comand=senden' target='_parent'>
				<input value='l&ouml;schen' type='button'></a>
				 \t
				<a href='?hide=1&action=list_visitors' target='_parent'>
				<input value='Zur&uuml;ck' type='button'></a>
				";	
	}

}

$PAGE->render($output);
?>