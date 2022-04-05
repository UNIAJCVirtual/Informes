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

	include_once("../database/connection.php");
	mysqli_set_charset($connection, "utf8");
	$result = $connection->query("SELECT id, name FROM mdl_course_categories WHERE parent = " . $_POST["category"]);
	$c = 0;
	$inputs = "<table class = 'unborder'><tr>";
	foreach ($result as $data) {
		$c++;
		$inputs .= "<td><input type='checkbox' id='" . $data["id"] . "' name='program[]' value='" . $data["id"] . "'>&nbsp;<label for='" . $data["id"] . "'>" . $data["name"] . "</label></td>";
		if ($c % 3 == 0) {
			$inputs .= "</tr><tr>";
		}
	}
	echo $inputs . "</table>";
}



function loadSemester()
{
	include_once("../database/connection.php");
	$sql = "SELECT id, name FROM mdl_course_categories WHERE parent = " . $_POST["program"];
	$result = $connection->query($sql);
	echo "<option value=''>Seleccionar...</option>";
	while ($data = $result->fetch_assoc()) {
		echo "<option value='" . $data["id"] . "'>" . utf8_encode($data["name"]) . "</option>";
	}
}
