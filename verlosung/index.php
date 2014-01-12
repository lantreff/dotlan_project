<?php
########################################################################
# Dienstplan Modul for dotlan       		                           #
#                                                                      #
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# admin/dienstplan/export.php - Version 1.0                            #
########################################################################

include("../../../global.php");
include("../functions.php");

$PAGE->sitetitle = $PAGE->htmltitle = _("Verlosungszettel Drucken!");



if(!$module_admin_check && !$ADMIN->check(IS_ADMIN)) $PAGE->error_die($HTML->gettemplate("error_nopermission"));
else
{
	$output .= "<a name='top' >
				<a href='/admin/projekt/'>Projekt</a>
				<hr class='newsline' width='100%' noshade=''>
				<br />";
				
				
				$output .= "
				<br>
				<a href='druck.php' target='_blank'>Verlosungszettel jetzt drucken</a>
				<br>
				<br>
				";
}			
$PAGE->render(utf8_decode(utf8_encode($output) ));
?>