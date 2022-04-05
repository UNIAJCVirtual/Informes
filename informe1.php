<!DOCTYPE html>
<html>

<head>
	<meta http-equiv=Content-Type content=text/html; UTF-8>
	<title>informe 1</title>
</head>

<body>

</body>

</html>
<?php
function rellenar($n, $cadena, $encuentro)
{
	if ($encuentro == 1) {
		for ($i = 0; $i < $n; $i++) {
			$cadena .= "<td>NO APLICA</td>";
		}
	} else {
		for ($i = 0; $i < $n; $i++) {
			$cadena .= "<td>NO APLICA</td><td>NO APLICA</td>";
		}
	}
	return $cadena;
}
// header("Content-Type: text/html;charset=UTF-8");
// Conectando, seleccionando la base de datos
/*$link = mysqli_connect('conexiones.cpbouvmap8du.us-east-1.rds.amazonaws.com:3306', 'conexion_ajc', 'uKDmQHN7YMwEX7QL')
	or die('No se pudo conectar: ' . mysql_error());
mysqli_select_db($link, 'conexion_ajc') or die('No se pudo seleccionar la base de datos');*/

$link = mysqli_connect('localhost', 'root', '', 'moodle')
	or die('No se pudo conectar: ' . mysql_error());
mysqli_select_db($link, 'conexion_ajc') or die('No se pudo seleccionar la base de datos');

$sqlDocentes = "
		SELECT distinct
			mdl_user.id userid,
			mdl_user.username AS mdl_user_username,
			mdl_user.firstname AS mdl_user_firstname,
			mdl_user.lastname AS mdl_user_lastname,
			mdl_user.email AS mdl_user_email, 
			mdl_course.fullname course_name,
			mdl_course.id courseid
		FROM 
			mdl_user, 
			mdl_role,
			mdl_role_assignments,
			mdl_user_enrolments,
			mdl_enrol,
			mdl_course
		where
			mdl_role.id = 3 and 
			mdl_role.id = mdl_role_assignments.roleid AND
			mdl_role_assignments.userid = mdl_user.id and
			mdl_user.id = mdl_user_enrolments.userid AND
			mdl_course.id = mdl_enrol.courseid AND
			mdl_course.visible = true and
			mdl_enrol.id= mdl_user_enrolments.enrolid AND
			mdl_course.category IN (SELECT DISTINCT id
			FROM mdl_course_categories 
				WHERE parent IN(2629, 2631, 2649, 2690))";
echo "
		<table border='1' cellspacing='1' cellpadding='0'>
		<tr>
			<td>Id usuario</td>
			<td>Nombre</td>
			<td>Email</td>
			<td>Cantidad de Cursos</td>
			<td>Alistamiento</td>
			<td>Primer Avance</td>
			<td>Segundo Avance</td>		
			<td>Final y Adicionales</td>
			<td>Total</td>		
		</tr>";
$resultDocentes = mysqli_query($link, $sqlDocentes) or die('Consulta fallida Docentes: ' . mysqli_error());
if ($resultDocentes->num_rows > 0) {
	foreach ($resultDocentes as $valor) {
		$courseid = $valor['courseid'];
		$userid = $valor['userid'];
		$sqlCursos = " 
			SELECT distinct
				mdl_course.id id,
				mdl_course.category,
				mdl_course.fullname AS mdl_course_fullname,
				mdl_course.visible,
				mdl_grade_categories.id mdl_grade_categories_id
			FROM 
				mdl_user_enrolments,
				mdl_course, 
				mdl_enrol,
				mdl_grade_categories
			WHERE
				mdl_course.id = $courseid AND
				mdl_user_enrolments.userid = $userid AND
				mdl_course.id = mdl_grade_categories.courseid AND
				LOWER(mdl_grade_categories.fullname) LIKE 'primer avance'
				ORDER by 1,2 ";
		echo $sqlCursos;
		$resultCursos = mysqli_query($link, $sqlCursos) or die('Consulta fallida Cursos: ' . mysqli_error());
		foreach ($resultCursos as $valorC) {
			$courseCategory = $valorC['mdl_grade_categories_id'];
			$sqlNota = "
					SELECT 
						DISTINCT(mdl_grade_items.courseid),
						mdl_grade_items.iteminstance iteminstance,
						mdl_grade_items.itemname name,
						SUM(mdl_grade_grades.finalgrade) suma,
						mdl_grade_grades.userid,
						mdl_grade_items.itemmodule,
					    mdl_grade_grades.feedback
					FROM 
						mdl_grade_items, mdl_grade_grades
					WHERE 
						
						
						mdl_grade_items.courseid =  $courseid  and
						mdl_grade_items.categoryid= $courseCategory  and
						mdl_grade_items.id = mdl_grade_grades.itemid and 
						mdl_grade_grades.finalgrade <> ' '
					GROUP BY iteminstance
					ORDER BY itemmodule DESC";
			// echo $sqlNota;
			$resultNota = mysqli_query($link, $sqlNota) or die('Consulta fallida: ' . mysqli_error());
			// echo $resultNota->num_rows."<br>";
			// echo $sqlNota;

			$fila = "
					<tr>
						<td>" . $valor["userid"] . "</td>
						<td>" . $valor["mdl_user_firstname"] . " " . $valor["mdl_user_lastname"] . "</td>
						<td>" . $valor["mdl_user_email"] . "</td>
						<td>" . $valorC["id"] . "</td>
						<td>" . $valorC["mdl_course_fullname"] . "</td>
						<td>$courseCategory</td>";
			if ($resultNota->num_rows > 0) {
				$foro = 3;
				$actividad = 3;
				$encuentro = 3;
				foreach ($resultNota as $valorN) {

					if ($foro > 0 and $valorN["itemmodule"] == "forum") {
						$feedback = "
								SELECT 
									id
								FROM 
									mdl_forum_discussions 
								WHERE 
									course = " . $valorC['id'] . " AND 
									forum = " . $valorN['iteminstance'] . "";
						// echo $feedback;
						$resultFeedback = mysqli_query($link, $feedback) or die('Consulta fallida: ' . mysqli_error());
						$feed = $resultFeedback->fetch_assoc();
						$feedback = "
								SELECT 
									MAX(message) message
								FROM 
									mdl_forum_posts
								WHERE
									discussion = " . $feed['id'] . " AND
									userid = " . $valor["userid"] . " AND 
									LOWER(subject) LIKE 're:%'";
						// echo $feedback;
						$resultFeedback = mysqli_query($link, $feedback) or die('Consulta fallida: ' . mysqli_error());
						$feed = $resultFeedback->fetch_assoc();

						if (count(explode(" ", $feed["message"])) > 2) {
							// $fila.="<td>".$feed["message"]."</td>";	
							$fila .= "<td>CUMPLE</td>";
						} else {
							$fila .= "<td>NO CUMPLE</td>";
						}
						if ($valorN["suma"] <> null) {
							$fila .= "<td>" . $valorN["suma"] . "</td>";
						} else {
							$fila .= "<td>NO CUMPLE</td>";
						}
						$foro--;
					} else if ($actividad > 0 and $valorN["itemmodule"] == "assign") {
						$validacion = trim(strtolower(substr($valorN["name"], 0, 1)));
						$validacion1 = trim(strtolower(substr($valorN["name"], 1, 1)));
						if (is_numeric($validacion1) and $validacion == "t") {
							if ($foro > 0) {
								$fila = rellenar($foro, $fila, 0);
								$foro = 0;
							}
							if ($valorN["itemmodule"] == "assign") {
								if ($valorN["suma"] <> null) {
									$fila .= "<td>" . $valorN["suma"] . "</td>";
								} else {
									$fila .= "<td>NO CUMPLE</td>";
								}
								if ($valorN["feedback"] <> null) {
									$fila .= "<td>" . $valorN["feedback"] . "</td>";
								} else {
									$fila .= "<td>NO CUMPLE</td>";
								}
								$actividad--;
							}
						}
					} else if ($encuentro > 0 and $valorN["itemmodule"] == "assign") {
						$validacion = trim(strtolower(substr($valorN["name"], 0, 1)));
						$contiene = strpos($valorN["name"], "Encuentro");
						if ($contiene === true or $validacion == "e") {
							if ($actividad > 0) {
								$fila = rellenar($actividad, $fila, 0);
								$actividad = 0;
							}
							$fila .= "<td>" . $valorN["suma"] . "</td>";
						} else {
							$fila .= "<td>NO CUMPLE</td>";
						}
						$encuentro--;
					}
				}
				if ($foro > 0) {
					$fila = rellenar($foro, $fila, 0);
				}
				if ($actividad > 0) {
					$fila = rellenar($actividad, $fila, 0);
				}
				if ($encuentro > 0) {
					$fila = rellenar($encuentro, $fila, 1);
				}
				$fila .= "<tr>";
				echo $fila;
			}
		}
	}
	echo "</table>";
}
?>