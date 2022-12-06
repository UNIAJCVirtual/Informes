<?php
	header("Content-Type: text/html;charset=utf-8");
	function selectCategory(){
		include_once("services/connection.php");
		mysqli_set_charset($connection, "utf8");
		$result = $connection->query("SELECT id, name FROM mdl_course_categories WHERE parent = 0 ");
		while ($c = $result->fetch_assoc()) {echo ("<option value='".$c["id"]."'>".($c["name"])."</option>");}	
	}	
?>