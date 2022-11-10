<?php
	function selectCategory(){
		require_once("services/connection.php");
		$con = connection();
		$result = $con->query("SELECT id, name FROM mdl_course_categories WHERE parent = 0 ");
		while ($c = $result->fetch_assoc()) {echo ("<option value='".$c["id"]."'>".utf8_encode($c["name"])."</option>");}	
	}	
?>