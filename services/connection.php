<?php
function connection()
{

	// Conexion a base de datos externa -> Remplace with your credentials
	$server = "xxxxx.ccccc.us-east-1.ss.xxxxxx.com";
	$user = "xxxxxxxxx";
	$pass = "xxxxxxxxx";
	$database = "xxxxxxx";
	$connection = new mysqli($server, $user, $pass, $database);

	// Conexion a base de datos de localhost
	/*
		$server = "localhost";
		$user = "root";
		$pass = "";
		$database = "moodle";
		$connection = new mysqli($server, $user, $pass, $database);
	*/

	$connection->set_charset("utf8");

	return $connection;
	if ($connection->connect_errno) {
		printf("Conexión fallida: %s\n", $connection->connect_error);
		exit();
	}


	//en base de datos hay crear un usuariopara localhost y otro para %
}
$connection = connection();
