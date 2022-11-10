<?php

	include("../services/reportRequest.php");

	
	
	$teachesResult = Teachers('4833');

	if ($teachesResult->num_rows > 0) {

		foreach ($teachesResult as $value) {
			$coursesResult = Courses($value['courseid'], $value['userid'], 'Avance formativo 1');
			print_r($coursesResult);
		}
	}
?>