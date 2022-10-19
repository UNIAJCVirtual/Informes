<?php
	function selectCategory($ch){
		require_once("services/connection.php");
		$connection3=connection();
		$doc=$connection3->query("SELECT id, name FROM mdl_course_categories WHERE parent = 0 ");
		while ($d=$doc->fetch_assoc()) {
			if ($ch==$d["id"] OR $d["id"]=='2628') {
				echo "<option value='".$d["id"]."' selected>".utf8_encode($d["name"])."</option>";
			}else{
				echo "<option value='".$d["id"]."'>".utf8_encode($d["name"])."</option>";
			}				
		}	
	}	
?>