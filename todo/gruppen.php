<?php
 
$MODUL_NAME = "todo";
include_once("../../../global.php");
include("../functions.php");
include("todo_functions.php");

$iCounter = 0;
$id = $_GET['id'];
$PAGE->sitetitle = $PAGE->htmltitle = _("ToDo Gruppenverwaltung");

$event_id = $EVENT->next;
$EVENT->getevent($event_id);

$sql_list_gruppen = list_groups();
$sql_list_gruppen1 = list_groups();
//$sql_list_equip_to_group = $DB->query("SELECT * FROM ( project_todo_gruppen AS g LEFT JOIN project_todo_g2u AS eg ON g.id = eg.group_id ) LEFT JOIN project_equipment AS e ON eg.user_id = e.id");


if(!$DARF["view"] ) $PAGE->error_die($HTML->gettemplate("error_nopermission"));
else
{

	include('header.php');



	$output .= "
		<h1 style='margin: 5px 0px 5px;'>
			Gruppe verwalten
		</h1>
		";
		if($_GET['do'] != "add_gruppe") {
		$output .= "
			<a href='?do=add_gruppe&page=gruppen' target='_parent'><input  value=\"Gruppe hinzuf&uuml;gen\" type=button></a>
			<br>
		";
		}
		$gruppe = $_POST['gruppe'];
		if( $_GET['type'] == "gruppe_speichern" )
			{

				$save_gruppe = $DB->query("
											INSERT INTO  `project_todo_gruppen` (
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
		if($_GET['do'] == "add_gruppe") {



		$output .= " <form action=\"?type=gruppe_speichern&page=gruppen\" method=\"post\"> Name: <input name='gruppe' value='' size='25' type='text' maxlength='50'>  <input name=\"add_gruppe\" value=\"gruppe hinzuf&uuml;gen\" type=submit>  </form>"; }

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

			$sql_list_gruppen_artikel = $DB->query("SELECT * FROM project_todo_g2u WHERE group_id = '".$out_list_gruppen['id']."'");

			while($out_list_gruppen_artikel = $DB->fetch_array($sql_list_gruppen_artikel))
			{// begin while
				$sql_list_orga_data =  list_userdata($out_list_gruppen_artikel['user_id']);

				$output .= "- &nbsp;".$sql_list_orga_data['vorname']." (".$sql_list_orga_data['nick'].") ".$sql_list_orga_data['nachname']." <br>";



			}
			$output .= "

				</td>
				<td>
					<a href='?do=add_orga&id=".$out_list_gruppen['id']."&page=gruppen' target='_parent'><img src='../images/16/db_add.png' title='Orga der Gruppe hinzuf&uuml;gen' ></a>
					<a href='?do=edit_".$out_list_gruppen['id']."&id=".$out_list_gruppen['id']."&page=gruppen' target='_parent'><img src='../images/16/edit.png' title='gruppe &auml;ndern' ></a>
					<a href='?do=del_".$out_list_gruppen['id']."&id=".$out_list_gruppen['id']."&page=gruppen' onClick='return confirm(\"gruppe ".$out_list_gruppen['bezeichnung']." wirklich l&ouml;schen?\");'><img src='../images/16/editdelete.png' title='gruppe ".$out_list_gruppen['bezeichnung']." l&ouml;schen?' ></a>
				";

			if($_GET['do'] == "edit_".$out_list_gruppen['id']) {

			if( $_GET['type'] == "save_gruppe_".$out_list_gruppen['id'])
			{
				$save_gruppe = $DB->query(" UPDATE `project_todo_gruppen` SET `bezeichnung` = '".$_POST['bezeichnung']."' WHERE `id` = '".$id."' ;");

				$output .= " gruppe wurde gespeichert!" ;
				$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."gruppen.php'>";
			}

		$output .= " <form action=?do=edit_".$out_list_gruppen['id']."&page=gruppen&type=save_gruppe_".$out_list_gruppen['id']."&id=".$out_list_gruppen['id']." method=\"post\"> bezeichnung: <input name='bezeichnung' value='' size='25' type='text' maxlength='50'>  <input name=\"edit_gruppe\" value=\"gruppe speichern\" type=submit>  </form>";}

			if($_GET['do'] == "del_".$out_list_gruppen['id']) {


				$del_gruppe = $DB->query(" DELETE FROM `project_todo_gruppen` WHERE `id` = '".$id."' ;");
				$del_user_aus_gruppe = $DB->query(" DELETE FROM `project_todo_g2u` WHERE `group_id` = '".$id."' ;");

				$output .= " gruppe wurde gel&ouml;scht!" ;
				$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."gruppen.php?page=gruppen'>";

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

		$DB->query("DELETE FROM project_todo_g2u WHERE group_id = ".$id." ");

	 foreach($leih_ids as $lid ){



					$key_leih .= " ('".$id."', '".$lid."'),";
					//$key_leih .= " ('".$id."', '".$lid."')";

					//$DB->query("INSERT INTO project_todo_g2u (`group_id`, `user_id`) VALUES $key_leih;");

		}
		$key_leih = substr($key_leih,0,-1);
		$DB->query("INSERT INTO project_todo_g2u (`group_id`, `user_id`) VALUES $key_leih;");



		$output .= "<meta http-equiv='refresh' content='0; URL=".$dir."gruppen.php'>";
}


	if($_GET['do'] == "add_orga")
	{
		$sql_list_orga = list_orgas();
		$out_group  = list_single_group($id);
		$output .= "
		<br>
		<h1 style='margin: 5px 0px 5px;'>
			Orga der Gruppe ".$out_group['bezeichnung']." hinzufügen!
		</h1>

		";
		$output .= "
		<form name='".$_GET['do']."equip' do='#' method='POST'>
		<table width='100%' cellspacing='1' cellpadding='2' border='0'>
						<tbody>";

							while($out_list_orga = $DB->fetch_array($sql_list_orga))
							{// begin while
								$sql_check_selected =  $DB->query("SELECT * FROM project_todo_g2u WHERE user_id = '".$out_list_orga['id']."' AND group_id = '".$id."'  ");
								$sql_check_selected_other =  $DB->query("SELECT * FROM project_todo_g2u WHERE user_id = '".$out_list_orga['id']."' AND group_id != '".$id."'  ");
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

												$output .= "<input $type name='leih_ids[]' value='".$out_list_orga['id']."' $checked >".$out_list_orga['vorname']." (".$out_list_orga['nick'].") ".$out_list_orga['nachname']."";

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
