<?php
########################################################################
# Maxlan Card Modul for dotlan             			                   #
#                                                                      #
# Copyright (C) 2014 Jens Broens <jens@broens.de>                      #
#                                                                      #
# Version 0.1                                                          #
########################################################################

$MODUL_NAME = "card";
include("function_card.php");
//$output .= "TEST ".  $event_id;

$allow_print = $DARF['print_cards'];

if($allow_print && $_GET['action'] == "print_ordered_cards"){
    generate_cards_pdf_queue();
	$output .= '<meta http-equiv="refresh" content="0; URL=card_print_list.php?action=print">';
	$PAGE->render($output);
	exit();
}

include('header.php');



if(!$DARF["print_cards"] ) $PAGE->error_die($HTML->gettemplate("error_nopermission"));

$output .= '<a href="card_print_list.php?action=print_ordered_cards"><b>Alle angenommenen Bestellungen in einem PDF-Dokument erzeugen</b></a><br><br>';
$output .= show_cards(0,FALSE);
$output .= show_cards(98,$allow_print);
$output .= '<br><br>'.show_card_documents();
// $module_admin_check ENDE

$PAGE->render($output);
?>
