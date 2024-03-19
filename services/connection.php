<?php
	function connection()
	{
		$server = "uniajctmp.chf279hfbxqe.us-east-1.rds.amazonaws.com";
		$user = "aulasvirtuales";
		$pass = "XN4pH7ddI3Ffl1vC";
		$database = "uniajc";
		$connection = new mysqli($server, $user, $pass, $database);
		
		/*
		$server = "localhost";
		$user = "root";
		$pass = "";
		$database = "moodle";
		$connection = new mysqli($server, $user, $pass, $database);
		$connection->set_charset("utf8");
		*/
		
		return $connection;
		if ($connection->connect_errno) {
			printf("Conexión fallida: %s\n", $connection->connect_error);
			exit();
		}

		
		//en base de datos hay crear un usuariopara localhost y otro para %
	}
	$connection = connection();
	?>