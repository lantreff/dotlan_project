<?php


include_once("../../../global.php");
include("../functions.php");

$query = $DB->query("SELECT vorname, nachname, u.id AS id
FROM user AS u, user_orga AS o
WHERE o.user_id = u.id
ORDER BY  `u`.`id` ASC");
while($row = $DB->fetch_array($query)){
  $DB->query("INSERT INTO `project_rights_user_rights` (`user_id`, `right_id`) VALUES ('".$row['id']."', '72')");
  $DB->query("INSERT INTO `project_rights_user_rights` (`user_id`, `right_id`) VALUES ('".$row['id']."', '74')");

}
?> 