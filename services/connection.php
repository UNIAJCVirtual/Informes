<?php
	function connection()
	{
		$server = "uniajctmp.chf279hfbxqe.us-east-1.rds.amazonaws.com";
		$user = "aulasvirtuales";
		$pass = "ioxlFNdODPLZkMXU";
		$database = "uniajc";
		$connection = new mysqli($server, $user, $pass, $database);
		return $connection;
		
		/*
		$server = "localhost";
		$user = "root";
		$pass = "";
		$database = "moodle";
		$connection = new mysqli($server, $user, $pass, $database);
		$connection->set_charset("utf8");
		return $connection;
		*/
		
		//en base de datos hay crear un usuariopara localhost y otro para %
	}
	$connection = connection();
	?>