<?php
########################################################################
# Equipment Verwaltungs Modul for dotlan                               #
#                                                                      #
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# admin/equipment/index.php - Version 1.0                              #
########################################################################


$MODUL_NAME = "media";
include_once("../../../global.php");
include("../functions.php");
include("media_functions.php");

include('header.php');

if (isset($_POST['turniere1']) )
{
$turnier_ids = $_POST['turniere1'];
}
if (isset($_POST['turniere']) )
{
$turnier_ids = $_POST['turniere'];
}

if(!$DARF["view"] ) $PAGE->error_die($HTML->gettemplate("error_nopermission"));
else
{// $module_admin_check



//$data = $DB->query("SELECT * FROM `t_contest` WHERE DATE_FORMAT( `starttime`, '%j' ) BETWEEN DATE_FORMAT( NOW() , '%j' )  AND DATE_FORMAT( DATE_ADD( NOW(), INTERVAL 1 HOUR), '%j') AND `user_id` != '-1' ORDER BY starttime DESC");


 /*###########################################################################################
Admin PAGE
*/
if($_GET['hide'] != 1)
{
		$output .= list_turniere($event_id); // $event_id

}	
	if($_GET['hide'] == "1"){	
	
			if($_GET['action'] == "show"){
			 $output .= list_begegnung($turnier_ids,$event_id);// $event_id
						
			}
	}


}
$PAGE->render($output);
?>
