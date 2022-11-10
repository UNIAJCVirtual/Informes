<?php
	include_once("../services/connection.php");
	mysqli_set_charset($connection, "utf8");
	$result = $connection->query("SELECT id, name FROM mdl_course_categories WHERE parent = " . $_POST["category"]);
	$inputs = "";
	if (is_array($result) || is_object($result))
	{
		foreach ($result as $data) {
			$inputs.= "<input class='checkbox-program' type='checkbox' name='program[]' id='" . $data["id"] . "' value='" . $data["id"] . "'><label class='for-checkbox-program' for='" . $data["id"] ."'><span>" . $data["name"] . "</span></label>";
		}
	}
	echo $inputs;
?>