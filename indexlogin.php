<?php
	if (isset($_POST["pass"])) {
		$options = array (
  'dbpersist' => 0,
  'dbport' => '',
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_unicode_ci',
);
		// define('PASSWORD_BCRYPT', 1);
		// define('PASSWORD_DEFAULT', PASSWORD_BCRYPT);
		$pass = md5($_POST["pass"]);
		echo $pass."<br>";
		// $pass = sha1($pass);
		// echo $pass."<br>";
		$pass = password_hash($pass, PASSWORD_BCRYPT);
		echo $pass."<br>";
		$pass = password_hash($_POST["pass"], PASSWORD_BCRYPT,$options);
		echo $pass."<br>";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<meta http-equiv=Content-Type content=text/html; UTF-8> 
	<script type="text/javascript" src="js/jquery.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/css.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
	$2y$10$HEoxhQ4wcXPdKgJwxgEnaOsQdPlJiY9XbiFXWsu5nosFTuGpiO/6O
	<div class="container">
		<h2>Login</h2>
		<div class="panel panel-default">
			<div class="panel-body">
				<form name="data_form" method="POST">
					<input type="text" name="pass">
					<input type="submit" value="Siguiente">
				</form>
			</div>
		</div>
	</div>
</body>
</html>