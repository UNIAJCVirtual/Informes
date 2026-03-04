<?php
require_once __DIR__ . '/env.php';
loadEnv(__DIR__ . '/../.env');

/**
 * Singleton para conexión a base de datos.
 * Reutiliza la misma conexión y reconecta si es necesario.
 */
function connection()
{
	static $connection = null;

	// Si ya existe conexión, verificar que siga activa
	if ($connection !== null) {
		if ($connection->ping()) {
			return $connection;
		}
		// Conexión perdida, cerrar y reconectar
		@$connection->close();
		$connection = null;
	}

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
	
	// Aumentar timeout para reportes largos
	$connection->options(MYSQLI_OPT_CONNECT_TIMEOUT, 300);

	return $connection;
}

$connection = connection();
