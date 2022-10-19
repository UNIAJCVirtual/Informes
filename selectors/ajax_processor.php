<?php
switch ($_POST["opc"]) {
	case '1':
		loadProgram();
		$count = 1;
		break;
	case '2':
		loadSemester();
		break;
	default:
		$count = 0;
		break;
}
function loadProgram()
{

	include_once("../services/connection.php");
	mysqli_set_charset($connection, "utf8");
	$result = $connection->query("SELECT id, name FROM mdl_course_categories WHERE parent = " . $_POST["category"]);
	$inputs = "<table>";
	if (is_array($result) || is_object($result))
	{
		foreach ($result as $data) {
			$inputs .= "<tr><td colspan='2'><label for='" . $data["id"] . "'><input type='checkbox' id='" . $data["id"] . "' name='program[]' value='" . $data["id"] . "'> " . $data["name"] . "</label></td></tr>";
		}
	}
	echo $inputs . "</table>";
}



function loadSemester()
{
	include_once("../services/connection.php");
	$sql = "SELECT id, name FROM mdl_course_categories WHERE parent = " . $_POST["program"];
	$result = $connection->query($sql);
	echo "<option value=''>Seleccionar...</option>";
	while ($data = $result->fetch_assoc()) {
		echo "<option value='" . $data["id"] . "'>" . utf8_encode($data["name"]) . "</option>";
	}
}
