<?php
########################################################################
# Maxlan Card Modul for dotlan             			                   #
#                                                                      #
# Copyright (C) 2014 Jens Broens <jens@broens.de>                      #
#                                                                      #
# Version 0.1                                                          #
########################################################################

$MODUL_NAME = "card";
include('function_card.php');

$allow_print = $DARF['print_cards'];

if(!$DARF["view"] ) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

if($allow_print && is_numeric($_GET['card']) && is_numeric($_GET['user']) && $_GET['action'] == "abholbereit"){
	$creation_time = time();
	$UserID = security_number_int_input($_GET['user'],'','');
	$update = mysql_query("UPDATE project_card SET last_creation_date = '{$creation_time}', card_status = 2 WHERE card_ID = '{$_GET['card']}'") or die(mysql_error());
	$PRVMSG->generate_message($UserID,"INBOX",$UserID,0,"Deine Maxlan Card ist fertig",'Deine Maxlan Card ist fertig und Du kannst sie am Support Counter (vorne) abholen.');
	$output .= '<meta http-equiv="refresh" content="0; URL=index.php">';
	$PAGE->render($output);
	exit();
}

if($allow_print && is_numeric($_GET['card']) && is_numeric($_GET['user']) && $_GET['action'] == "abgeholt"){
	$creation_time = time();
	$UserID = security_number_int_input($_GET['user'],'','');
	$update = mysql_query("UPDATE project_card SET last_creation_date = '{$creation_time}', card_status = 3 WHERE card_ID = '{$_GET['card']}'") or die(mysql_error());
	$PRVMSG->generate_message($UserID,"INBOX",$UserID,0,"Deine Maxlan Card wurde abgeholt",'Deine Maxlan Card wurde durch dich abgeholt.');
	$output .= '<meta http-equiv="refresh" content="0; URL=index.php">';
	$PAGE->render($output);
	exit();
}

if ($allow_print && is_numeric($_GET['user']) && $_GET['action'] == "singleprint"){
	$UserID = security_number_int_input($_GET['user'],'','');
	generate_card_pdf_single($UserID);
	$PRVMSG->generate_message($UserID,"INBOX",$UserID,0,"Deine Maxlan Card ist in Produktion",'Deine Maxlan Card wird gerade hergestellt.');
	$output .= '<meta http-equiv="refresh" content="0; URL=card_print_list.php?action=print">';
	$PAGE->render($output);
	exit();
}

if ($_POST['admin_decline_card'] == "Kartenbestellung ablehnen" && is_numeric($_POST['user'])){
	$UserID = security_number_int_input($_POST['user'],'','');
    $Card_Info = security_string_input($_POST['card_info']);
    $update = mysql_query("UPDATE project_card SET card_status = 99, card_info = '{$Card_Info}' WHERE user_ID = '{$UserID}'");
	$PRVMSG->generate_message($UserID,"INBOX",$UserID,0,"Deine Maxlan Card Bestellung wurde abgelehnt",'Grund: '.$Card_Info);
	$output .= '<meta http-equiv="refresh" content="0; URL=index.php">';
	$PAGE->render($output);
	exit();
}

if ($_POST['admin_accept_card'] == "Kartenbestellung annehmen" && is_numeric($_POST['user'])){
	$UserID = security_number_int_input($_POST['user'],'','');
    $Card_Info = security_string_input($_POST['card_info']);
    $update = mysql_query("UPDATE project_card SET card_status = 98, card_info = '{$Card_Info}' WHERE user_ID = '{$UserID}'");
	$PRVMSG->generate_message($UserID,"INBOX",$UserID,0,"Deine Maxlan Card Bestellung wurde angenommen.",'Deine Maxlan Card Bestellung wurde angenommen.');
	$output .= '<meta http-equiv="refresh" content="0; URL=index.php">';
	$PAGE->render($output);
	exit();
}

include('header.php');

$output .= show_cards(0,FALSE);
$output .= show_cards(98,$allow_print);
$output .= show_cards(99,FALSE);
$output .= show_cards(1,$allow_print);
$output .= show_cards(2,$allow_print);
$output .= show_cards(3,$allow_print);


$PAGE->render($output);
?>
