<?php

$MODUL_NAME = "equipment";
include_once("../../../global.php");
include("../functions.php");

$iCounter = 0;
$id = $_GET['id'];
$PAGE->sitetitle = $PAGE->htmltitle = _("Leihsystem Gruppenverwaltung");


$sql_list_gruppen = $DB->query("SELECT * FROM project_equipment_groups");
$sql_list_gruppen1 = $DB->query("SELECT * FROM project_equipment_groups");
//$sql_list_equip_to_group = $DB->query("SELECT * FROM ( project_equipment_groups AS g LEFT JOIN project_equipment_equip_group AS eg ON g.id = eg.id_group ) LEFT JOIN project_equipment AS e ON eg.id_equipment = e.id");


if(!$DARF["view"] ) $PAGE->error_die($HTML->gettemplate("error_nopermission"));
else
{

	include('header.php');



	$output .= "
		<h1 style='margin: 5px 0px 5px;'>
			Gruppe verwalten
		</h1>
		";
		if($_GET['action'] != "add_gruppe") {
		$output .= "
			<a href='?action=add_gruppe' target='_parent'><input  value=\"Gruppe hinzuf&uuml;gen\" type=button></a>
			<br>
		";
		}
		$gruppe = $_POST['gruppe'];
		if( $_GET['type'] == "gruppe_speichern" )
			{

				$save_gruppe = $DB->query("
											INSERT INTO  `project_equipment_groups` (
											`id` ,
											`bezeichnung`
											)
											VALUES (
												NULL ,
												'".$gruppe."'
											);

										");

						//	$raus =	 debug_backtrace();

			$output .= " gruppe wurde gespeichert! <br>";
			$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."gruppen.php?page=gruppen'>";
			}
		if($_GET['action'] == "add_gruppe") {



		$output .= " <form action=\"?type=gruppe_speichern\" method=\"post\"> Name: <input name='gruppe' value='' size='25' type='text' maxlength='50'>  <input name=\"add_gruppe\" value=\"gruppe hinzuf&uuml;gen\" type=submit>  </form>"; }

		$output .= "

		<br>
		<table cellspacing='0' cellpadding='2' border='0'>
			";

			while($out_list_gruppen = $DB->fetch_array($sql_list_gruppen))
			{// begin while

		$output .= "
			<tr>
				<td>
					<b>".$out_list_gruppen['bezeichnung']."</b><br>

					";

			$sql_list_gruppen_artikel = $DB->query("SELECT * FROM project_equipment_equip_group WHERE id_group = '".$out_list_gruppen['id']."'");

			while($out_list_gruppen_artikel = $DB->fetch_array($sql_list_gruppen_artikel))
			{// begin while
				$sql_list_artikel_data =  $DB->fetch_array( $DB->query("SELECT * FROM project_equipment WHERE id = '".$out_list_gruppen_artikel['id_equipment']."'") );

				$output .= "- &nbsp;".$sql_list_artikel_data['bezeichnung']." <br>";



			}
			$output .= "

				</td>
				<td>
					<a href='?action=add_artikel&id=".$out_list_gruppen['id']."' target='_parent'><img src='../images/16/db_add.png' title='Artikel der Gruppe hinzuf&uuml;gen' ></a>
					<a href='?action=edit_".$out_list_gruppen['id']."&id=".$out_list_gruppen['id']."' target='_parent'><img src='../images/16/edit.png' title='gruppe &auml;ndern' ></a>
					<a href='?action=del_".$out_list_gruppen['id']."&id=".$out_list_gruppen['id']."' onClick='return confirm(\"gruppe ".$out_list_gruppen['bezeichnung']." wirklich l&ouml;schen?\");'><img src='../images/16/editdelete.png' title='gruppe ".$out_list_gruppen['bezeichnung']." l&ouml;schen?' ></a>
				";

			if($_GET['action'] == "edit_".$out_list_gruppen['id']) {

			if( $_GET['type'] == "save_gruppe_".$out_list_gruppen['id'])
			{
				$save_gruppe = $DB->query(" UPDATE `project_equipment_groups` SET `bezeichnung` = '".$_POST['bezeichnung']."' WHERE `id` = '".$id."' ;");

				$output .= " gruppe wurde gespeichert!" ;
				$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."gruppen.php'>";
			}

		$output .= " <form action=?action=edit_".$out_list_gruppen['id']."&type=save_gruppe_".$out_list_gruppen['id']."&id=".$out_list_gruppen['id']." method=\"post\"> bezeichnung: <input name='bezeichnung' value='' size='25' type='text' maxlength='50'>  <input name=\"edit_gruppe\" value=\"gruppe speichern\" type=submit>  </form>";}

			if($_GET['action'] == "del_".$out_list_gruppen['id']) {


				$del_gruppe = $DB->query(" DELETE FROM `project_ticket_queue` WHERE `project_ticket_queue`.`id` = '".$id."' ;");
				$del_user_aus_gruppe = $DB->query(" DELETE FROM `project_ticket_agent_queue` WHERE `project_ticket_agent_queue`.`queueid` = '".$id."' ;");

				$output .= " gruppe wurde gel&ouml;scht!" ;
				$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."gruppen.php'>";

			}

			$output .= "
				</td>
			</tr>
				";
			}
		$output .= "

		</table>
		";




if($_POST["leih_ids"]){

		$DB->query("DELETE FROM project_equipment_equip_group WHERE id_group = ".$id." ");

	 foreach($leih_ids as $lid ){



					$key_leih .= " ('".$id."', '".$lid."'),";
					//$key_leih .= " ('".$id."', '".$lid."')";

					//$DB->query("INSERT INTO project_equipment_equip_group (`id_group`, `id_equipment`) VALUES $key_leih;");

		}
		$key_leih = substr($key_leih,0,-1);
		$DB->query("INSERT INTO project_equipment_equip_group (`id_group`, `id_equipment`) VALUES $key_leih;");



		$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."gruppen.php'>";
}


	if($_GET['action'] == "add_artikel")
	{
		$sql_list_artikel = $DB->query("SELECT * FROM project_equipment WHERE ist_leihartikel = 1");
		$out_group  =  $DB->fetch_array( $DB->query("SELECT * FROM project_equipment_groups WHERE id = '".$id."'") );
		$output .= "
		<br>
		<h1 style='margin: 5px 0px 5px;'>
			Artikel der Gruppe ".$out_group['bezeichnung']." hinzufügen!
		</h1>

		";
		$output .= "
		<form name='".$_GET['action']."equip' action='#' method='POST'>
		<table width='100%' cellspacing='1' cellpadding='2' border='0'>
						<tbody>";

							while($out_list_artikel = $DB->fetch_array($sql_list_artikel))
							{// begin while
								$sql_check_selected =  $DB->query("SELECT * FROM project_equipment_equip_group WHERE id_equipment = '".$out_list_artikel['id']."' AND id_group = '".$id."'  ");
								$sql_check_selected_other =  $DB->query("SELECT * FROM project_equipment_equip_group WHERE id_equipment = '".$out_list_artikel['id']."' AND id_group != '".$id."'  ");
								$checked = "";
								$type = "type='checkbox'";

								if(mysql_num_rows($sql_check_selected) != 0)
								{
									$checked = "checked='checked'";
								}
								/*
								if(mysql_num_rows($sql_check_selected_other) != 0)
								{
									$type = "type='hidden'";
								}
								*/
							$output .= "<tr class=\"msgrow".(($i%2)?1:2)."\">
											<td width='100%'  class='shortbarbit_left'>";

												$output .= "<input $type name='leih_ids[]' value='".$out_list_artikel['id']."' $checked >".$out_list_artikel['bezeichnung']."";

$output .= "								</td>
										</tr>";
								$i++;
							} // end while

		$output .= "	</tbody>
					</table>
					<input name='senden' value='Zuweisung speichern' type='submit'>
		</form>

		";
	}


#######################################

}
$PAGE->render(utf8_decode(utf8_encode($output) ));
?>
