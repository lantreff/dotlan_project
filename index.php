<?php
#########################################################################
# Kontakt-Verwaltungsmodul for dotlan                               	#
#                                                                      	#
# Copyright (C) 2013 Christian Egbers <christian@3gg3.de>              	#
#                                                                      	#
# admin/kontakte/index.php                              				#
#########################################################################
include_once("../../global.php");
include("functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Projektverwaltung");



if(!$module_admin_check && !$ADMIN->check(IS_ADMIN)) $PAGE->error_die($HTML->gettemplate("error_nopermission"));
else
{
	$output .= "<a name='top' >
				<a href='/admin/projekt/'>Projekt</a>
				<hr class='newsline' width='100%' noshade=''>
				<br />";

	$verz = $_SERVER['DOCUMENT_ROOT']."/admin/projekt";
	$handle = opendir($verz);
	

				$output .= "
				
				<div style=' float:left; min-height: 200px;
				background-color:#DDDDDD;
				border: solid 1px #000000;
			  	margin-top: 	5px;
				margin-right: 	2.5px;
				margin-left: 	2.5px;
				margin-bottom:	5px;
			  	padding-bottom:	10px;
				padding-left:	5px;
				padding-right:	5px;
				padding-top:	10px;
				'>
				<h3>Support Ticket System</h3>

			";
				include($verz.'/sts/view.php');

			$output .= "
				<br>
				<a href='sts'>zum Support Ticket System</a>
				</div>
				";
/////////////////////////////////////

				$output .= "
				
				<div style=' float:left; min-height: 200px;
				background-color:#DDDDDD;
				border: solid 1px #000000;
			  	margin-top: 	5px;
				margin-right: 	2.5px;
				margin-left: 	2.5px;
				margin-bottom:	5px;
			  	padding-bottom:	10px;
				padding-left:	5px;
				padding-right:	5px;
				padding-top:	10px;
				'>
				<h3>Leihsystem</h3>

			";
				include($verz.'/leihsystem/view.php');

			$output .= "
				<br>
				<a href='leihsystem'>zum Leihsystem</a>
				</div>

				";	
				
/////////////////////////////////////				

				$output .= "
				
				<div style=' float:left; min-height: 200px;
				background-color:#DDDDDD;
				border: solid 1px #000000;
			  	margin-top: 	5px;
				margin-right: 	2.5px;
				margin-left: 	2.5px;
				margin-bottom:	5px;
			  	padding-bottom:	10px;
				padding-left:	5px;
				padding-right:	5px;
				padding-top:	10px;
				'>
				<h3>Catering</h3>

			";
				include($verz.'/pizza/view.php');

			$output .= "
				<br>
				<a href='pizza'>zu Catering (Bestellungen die da sind!!!)</a>
				</div>
				";
/////////////////////////////////////				


	closedir ($handle);

}
$output.= "<meta http-equiv='refresh' content='60; URL=index.php' /> ";
$PAGE->render( utf8_decode($output) ); // Ausgabe des gesamten Seiten inhaltes über das Dotlan-System
?>
