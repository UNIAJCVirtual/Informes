<?php
require_once __DIR__ . '/env.php';
loadEnv(__DIR__ . '/../.env');

function connection()
{
	$server = $_ENV['DB_SERVER'];
	$user = $_ENV['DB_USER'];
	$pass = $_ENV['DB_PASS'];
	$database = $_ENV['DB_NAME'];

	$connection = new mysqli($server, $user, $pass, $database);

	if ($connection->connect_errno) {
		printf("Conexión fallida: %s\n", $connection->connect_error);
		exit();
	}

	$connection->set_charset("utf8");

	return $connection;
}

$connection = connection();
