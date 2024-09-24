<?php

function userNotSingup($consult)
{

    if ($consult == 1) {
        require_once("../services/connection.php");
        $connection3 = connection();
        mysqli_set_charset($connection3, "utf8");
        $result = $connection3->query("SELECT u.idnumber, u.firstname, u.lastname, u.email, u.institution, ul.timeaccess 
        FROM mdl_user u
        LEFT JOIN mdl_user_lastaccess ul ON u.id = ul.userid
        WHERE ul.timeaccess IS NULL;");
        $connection3->close();

        echo ("
            <div class='title-estadist'>
                <h2>Usuarios sin ingreso</h2>
            </div>
            <table id='example' class='table table-striped table-bordered' cellspacing='0' width='100%'>
                <thead>
                    <tr class='td1 thead-table' nowrap>
                        <th class='td1' nowrap>ID</th>
                        <th class='td1' nowrap>Nombre</th>
                        <th class='td1' nowrap>Apellido</th>
                        <th class='td1' nowrap>Correo</th>
                        <th class='td1' nowrap>Institución</th>
                        <th class='td1' nowrap>Ingreso</th>
                    </tr>
                </thead>
                <tbody>");

        while ($row = mysqli_fetch_array($result)) {
            echo ("
                <tr>
                    <td>" . $row['idnumber'] . "</td>
                    <td>" . $row['firstname'] . "</td>
                    <td>" . $row['lastname'] . "</td>
                    <td>" . $row['email'] . "</td>
                    <td>" . $row['institution'] . "</td>
                    <td>" . $row['timeaccess'] . "</td>
                </tr>
            ");
        }

        echo ("</tbody></table>");
    }

    if ($consult == 2) {
        require_once("../services/connection.php");
        $connection3 = connection();
        mysqli_set_charset($connection3, "utf8");
        $result = $connection3->query("SELECT DISTINCT u.idnumber, u.username, u.firstname, u.lastname, u.email, u.institution, c.fullname AS coursename, FROM_UNIXTIME(ul.timeaccess) AS lastaccess
        FROM mdl_user u
        LEFT JOIN mdl_user_lastaccess ul ON u.id = ul.userid
        LEFT JOIN mdl_user_enrolments ue ON u.id = ue.userid
        LEFT JOIN mdl_enrol e ON ue.enrolid = e.id
        LEFT JOIN mdl_course c ON e.courseid = c.id
        LEFT JOIN (
          SELECT userid, courseid, MAX(timeaccess) AS maxtime
          FROM mdl_user_lastaccess
          WHERE courseid IS NOT NULL
          GROUP BY userid, courseid
        ) latest ON latest.userid = u.id AND latest.courseid = c.id
        LEFT JOIN mdl_role_assignments ra ON ra.userid = u.id
        LEFT JOIN mdl_context ctx ON ctx.id = ra.contextid
        WHERE c.visible = 1
        AND latest.maxtime IS NULL
        AND ctx.contextlevel = 50
        AND u.email NOT LIKE '%@profesores.uniajc.edu.co'
        AND u.email NOT LIKE '%@admon.uniajc.edu.co'
        AND u.email NOT LIKE 'aguapanelaconleche@gmail.com'
        AND u.email NOT LIKE 'aulasvirtuales4040@gmail.com'
        AND ra.roleid = 5
        ORDER BY u.id, c.id ASC;");

        if (!$result) {
            die("Error en la consulta: " . mysqli_error($connection3));
        }


        echo ("
            <div class='title-estadist'>
                <h2>Usuarios sin ingreso</h2>
            </div>
            <table id='example' class='table table-striped table-bordered' cellspacing='0' width='100%'>
                <thead>
                    <tr class='td1 thead-table' nowrap>
                        <th class='td1' nowrap>ID</th>
                        <th class='td1' nowrap>Usuario</th>
                        <th class='td1' nowrap>Nombre</th>
                        <th class='td1' nowrap>Apellido</th>
                        <th class='td1' nowrap>Correo</th>
                        <th class='td1' nowrap>Institución</th>
                        <th class='td1' nowrap>Curso</th>
                        <th class='td1' nowrap>Ultimo ingreso plataforma</th>
                    </tr>
                </thead>
                <tbody>");

        while ($row = mysqli_fetch_array($result)) {
            echo ("
                <tr>
                    <td>" . $row['idnumber'] . "</td>
                    <td>" . $row['username'] . "</td>
                    <td>" . $row['firstname'] . "</td>
                    <td>" . $row['lastname'] . "</td>
                    <td>" . $row['email'] . "</td>
                    <td>" . $row['institution'] . "</td>
                    <td>" . $row['coursename'] . "</td>
                    <td>" . $row['lastaccess'] . "</td>
                </tr>
            ");
        }

        echo ("</tbody></table>");
    }
    if ($consult == 3) {
        require_once("../services/connection.php");
        $connection3 = connection();
        mysqli_set_charset($connection3, "utf8");
        $result = $connection3->query("SELECT DISTINCT u.idnumber, u.firstname, u.lastname, u.email, c.fullname AS coursename, gi.itemname, gi.itemmodule, gi.timemodified < UNIX_TIMESTAMP() AS isclosed
        FROM mdl_user u
        INNER JOIN mdl_user_enrolments ue ON u.id = ue.userid
        INNER JOIN mdl_enrol e ON ue.enrolid = e.id
        INNER JOIN mdl_course c ON e.courseid = c.id
        INNER JOIN mdl_context ct ON ct.instanceid = c.id
        LEFT JOIN mdl_grade_items gi ON gi.courseid = c.id
        LEFT JOIN mdl_grade_categories gc ON gi.categoryid = gc.id
        LEFT JOIN mdl_course_modules cm ON gi.iteminstance = cm.instance AND gi.itemmodule = cm.module AND cm.course = c.id
        LEFT JOIN mdl_course_modules_completion cmc ON cm.id = cmc.coursemoduleid
        WHERE ct.contextlevel = 50
        AND ue.status = 0
        AND cmc.id IS NULL
        AND u.id IN (SELECT userid FROM mdl_role_assignments WHERE roleid = 5)
        AND gi.itemtype = 'mod'
        AND gi.itemmodule NOT IN ('label', 'page', 'url', 'resource', 'folder')
        AND gi.iteminstance NOT IN (SELECT instance FROM mdl_quiz)
        ORDER BY c.fullname, gi.itemmodule, gi.itemname;
        ");
        $connection3->close();

        echo ("
            <div class='title-estadist'>
                <h2>Usuarios sin ingreso</h2>
            </div>
            <table id='example' class='table table-striped table-bordered' cellspacing='0' width='100%'>
                <thead>
                    <tr class='td1 thead-table' nowrap>
                        <th class='td1' nowrap>ID</th>
                        <th class='td1' nowrap>Nombre</th>
                        <th class='td1' nowrap>Apellido</th>
                        <th class='td1' nowrap>Correo</th>
                        <th class='td1' nowrap>Curso</th>
                        <th class='td1' nowrap>Actividad</th>
                        <th class='td1' nowrap>Itemmodule</th>
                        <th class='td1' nowrap>Disponible</th>
                    </tr>
                </thead>
                <tbody>");

        while ($row = mysqli_fetch_array($result)) {
            echo ("
                <tr>
                    <td>" . $row['idnumber'] . "</td>
                    <td>" . $row['firstname'] . "</td>
                    <td>" . $row['lastname'] . "</td>
                    <td>" . $row['email'] . "</td>
                    <td>" . $row['coursename'] . "</td>
                    <td>" . $row['itemname'] . "</td>
                    <td>" . $row['itemmodule'] . "</td>
                    <td>" . $row['isclosed'] . "</td>
                </tr>
            ");
        }

        echo ("</tbody></table>");
    }

    if ($consult == 4) {
        require_once("../services/connection.php");
        $connection3 = connection();
        mysqli_set_charset($connection3, "utf8");
        $result = $connection3->query("SELECT u.idnumber, u.firstname, u.lastname, c.fullname AS coursename
        FROM mdl_user u
        INNER JOIN mdl_user_enrolments ue ON u.id = ue.userid
        INNER JOIN mdl_enrol e ON ue.enrolid = e.id
        INNER JOIN mdl_course c ON e.courseid = c.id
        INNER JOIN mdl_context ct ON ct.instanceid = c.id
        WHERE ct.contextlevel = 50
        AND ue.status = 0
        AND u.id IN (SELECT userid FROM mdl_role_assignments WHERE roleid = 5)
        ORDER BY u.lastname, u.firstname, c.fullname;");
        $connection3->close();

        echo ("
            <div class='title-estadist'>
                <h2>Usuarios (Estudiantes) matriculados en Moodle</h2>
            </div>
            <table id='example' class='table table-striped table-bordered' cellspacing='0' width='100%'>
                <thead>
                    <tr class='td1 thead-table' nowrap>
                        <th class='td1' nowrap>Cedula</th>
                        <th class='td1' nowrap>Nombre</th>
                        <th class='td1' nowrap>Apellido</th>
                        <th class='td1' nowrap>Curso</th>
                    </tr>
                </thead>
                <tbody>");

        while ($row = mysqli_fetch_array($result)) {
            echo ("
                <tr>
                    <td>" . $row['idnumber'] . "</td>
                    <td>" . $row['firstname'] . "</td>
                    <td>" . $row['lastname'] . "</td>
                    <td>" . $row['coursename'] . "</td>
                </tr>
            ");
        }

        echo ("</tbody></table>");
    }

    if ($consult == 5) {
        require_once("../services/connection.php");
        $connection3 = connection();
        mysqli_set_charset($connection3, "utf8");
        $result = $connection3->query("SELECT u.idnumber, u.firstname, u.lastname, c.fullname AS coursename, CONCAT_WS(' / ', cc3.name, cc2.name, cc1.name) AS categoryname
        FROM mdl_user u
        INNER JOIN mdl_user_enrolments ue ON u.id = ue.userid
        INNER JOIN mdl_enrol e ON ue.enrolid = e.id
        INNER JOIN mdl_course c ON e.courseid = c.id
        INNER JOIN mdl_context ct ON ct.instanceid = c.id
        INNER JOIN mdl_course_categories cc1 ON c.category = cc1.id
        LEFT JOIN mdl_course_categories cc2 ON cc1.parent = cc2.id
        LEFT JOIN mdl_course_categories cc3 ON cc2.parent = cc3.id
        WHERE ct.contextlevel = 50
        AND ue.status = 0
        AND e.enrol = 'manual'
        AND u.id IN (SELECT userid FROM mdl_role_assignments WHERE roleid = 5)
        ORDER BY cc3.name, cc2.name, cc1.name, c.fullname, u.lastname, u.firstname;");
        $connection3->close();

        echo ("
            <div class='title-estadist'>
                <h2>Usuarios con matricula manual en Moodle</h2>
            </div>
            <table id='example' class='table table-striped table-bordered' cellspacing='0' width='100%'>
                <thead>
                    <tr class='td1 thead-table' nowrap>
                        <th class='td1' nowrap>Cedula</th>
                        <th class='td1' nowrap>Nombre</th>
                        <th class='td1' nowrap>Apellido</th>
                        <th class='td1' nowrap>Curso</th>
                        <th class='td1' nowrap>Categoria</th>
                    </tr>
                </thead>
                <tbody>");

        while ($row = mysqli_fetch_array($result)) {
            echo ("
                <tr>
                    <td>" . $row['idnumber'] . "</td>
                    <td>" . $row['firstname'] . "</td>
                    <td>" . $row['lastname'] . "</td>
                    <td>" . $row['coursename'] . "</td>
                    <td>" . $row['categoryname'] . "</td>
                </tr>
            ");
        }

        echo ("</tbody></table>");
    }
}

function idCourseEFC($idnumber, $idcourses)
{
    global $verde, $amarillo, $rojoClaro, $rojoOscuro;
    include("../services/reportRequest.php");
    include("../report/avances.php");

    date_default_timezone_set("America/Bogota");
    $dateNow = date("Y-m-d H:i:s");
    $vector_course = [];
    $vector_idcourse = [];
    $cantidadItems = 0;

        $coursesInformation = CoursesInformationID($idcourses);

        while ($courseInfo = $coursesInformation->fetch_assoc()) {

            $cumple = 0;
            $noCumple = 0;
            $course = new avance();
            $teachersNames = "";
            $teachersEmails = "";
            $teachersUsersIds = "";

            //la variable requerida en la función Usersquantity es el rol que vamos a buscar 3 Profesor
            $teachers = Usersquantity($courseInfo['course_id'], 3);

            while ($teacher = $teachers->fetch_assoc()) {
                if ($teachers->num_rows == 1) {
                    $teachersNames = ucwords(mb_strtolower($teacher['firstname'], 'UTF-8')) . " " . ucwords(mb_strtolower($teacher['lastname'], 'UTF-8'));
                    $teachersEmails = mb_strtolower($teacher['email'], 'UTF-8');
                    $teachersUsersIds = mb_strtolower($teacher['user_id'], 'UTF-8');
                } else {
                    $teachersNames .= ucwords(mb_strtolower($teacher['firstname'], 'UTF-8')) . " " . ucwords(mb_strtolower($teacher['lastname'], 'UTF-8')) . " <br> ";
                    $teachersEmails .= mb_strtolower($teacher['email'], 'UTF-8') . " <br> ";
                    $teachersUsersIds = mb_strtolower($teacher['user_id'], 'UTF-8');
                }
            }

            //Información del profesor y del course
            $course->setIdUser($teachersUsersIds);
            $course->setNombreProfesor($teachersNames);
            $course->setCorreo($teachersEmails);
            $course->setPrograma('none');
            $course->setSemestre('none');
            $course->setIdcurso($courseInfo['course_id']);
            $course->setNombreCurso($courseInfo['course_name']);

            $group = explode("*", $courseInfo["course_name"]);
            $course->setGrupo($group[count($group) - 1]);
            $code = explode($course->getGrupo(), $courseInfo['course_code']);
            $course->setcodigo($code[count($group) - 1]);

            // Se envian los dos tipos de reporte, el viejo (Avance formativo) y el nuevo (Evaluación formativa y continua)
            $gradesCategoryResult = GradesCategory($courseInfo['course_id'], $course->getIdUser(), $idnumber);
            //Validaciones
            if (is_object($gradesCategoryResult)) {
                if ($gradesCategoryResult->num_rows > 0) {
                    foreach ($gradesCategoryResult as $gradesCategory) {
                        $itemResult = GradesCategoryItem($courseInfo['course_id'], $idnumber);
                        $cantidadItems = ($cantidadItems < $itemResult->num_rows) ? $itemResult->num_rows : $cantidadItems;
                        if ($itemResult->num_rows > 0) {
                            foreach ($itemResult as $item) {

                                if ($item["itemmodule"] == "forum") {

                                    $course->items[] = $item["name"];

                                    //calificaciones del foro

                                    $score = ScoreItem($item["id"]);
                                    ($score == "CUMPLE") ? $cumple++ : $noCumple++;
                                    $course->items[] = $score;

                                    //retroalimentación del foro

                                    $resultFeedback = FeedbackForum1($gradesCategory['id'], $item["iteminstance"]);

                                    if ($resultFeedback->num_rows > 0) {
                                        $feed1 = $resultFeedback->fetch_assoc();
                                        $resultFeedback = FeedbackForum2($feed1['id'], $course->getIdUser());
                                        ($resultFeedback == "CUMPLE") ? $cumple++ : $noCumple++;
                                        $course->items[] = $resultFeedback;
                                    } else {
                                        $course->items[] = "NO CUMPLE";
                                        $noCumple++;
                                    }
                                } elseif ($item["itemmodule"] == "assign") {

                                    $course->items[] = $item["name"];

                                    //calificaciones de la tarea
                                    $score = ScoreItem($item["id"]);
                                    ($score == "CUMPLE") ? $cumple++ : $noCumple++;
                                    $course->items[] = $score;
                                    //retroalimentación de la tarea
                                    $resultFeedback = FeedbackActivity($item["iteminstance"]);
                                    ($resultFeedback == "CUMPLE") ? $cumple++ : $noCumple++;
                                    $course->items[] = $resultFeedback;
                                } elseif ($item["itemmodule"] == "quiz") {

                                    $course->items[] = $item["name"];
                                    //calificaciones del quiz	
                                    $course->items[] = "CUMPLE";
                                    //retroalimentaciones del quiz
                                    $course->items[] = "NO APLICA";
                                    $cumple++;
                                }
                            }
                        }
                        $total = (($cumple + $noCumple) == 0) ? -1 : ($cumple + $noCumple);
                        $per = ($total == -1) ? -1 : round(((100 / $total) * $cumple));
                        $course->setPorcentaje($per);
                    }
                } else {
                    $course->setPorcentaje(-2);
                }

                $vector_course[]  = $course;
                $vector_idcourse[] = $courseInfo['course_id'];
            }
        }
    echo ("
		<div class='title-estadist'>
			<h2>" . $idnumber . " </h2>
		</div>
		<table id='example' class='table table-striped table-bordered' cellspacing='0' width='100%'>
			<thead>
				<tr class='td1 thead-table' nowrap>
					<th class='td1' nowrap>Fecha</th>
					<th class='td1' nowrap>ID user</th>
					<th class='td1' nowrap>Nombre</th>
					<th class='td1' nowrap>Correo</th>
					<th class='td1' nowrap>Programa</th>
					<td class='td1' nowrap >ID Curso</td>
					<td class='td1' nowrap >Codigo</td>
					<th class='td1' nowrap>Semestre</th>
					<th class='td1' nowrap>Grupo</th>
					<th class='td1' nowrap>course</th>"
        . headerItems($cantidadItems) . "
					<th class='td1' nowrap>Porcentaje</th>
		  		</tr>
			</thead>
			<tbody>");

    foreach ($vector_course as $curse) {
        $color = color($curse->getPorcentaje());
        if ($curse->getPorcentaje() == -1) {
            $porcentaje = -1;
        } elseif ($curse->getPorcentaje() == -2) {
            $porcentaje = -2;
        } else {
            $porcentaje = $curse->getPorcentaje() . "%";
        }
        print("
					<tr class='" . $color . "'>
						<td nowrap class='" . $color . "'>" . $dateNow . "</td>
						<td nowrap class='" . $color . "'>" . $curse->getIdUser() . "</td>
						<td nowrap class='" . $color . "'>" . $curse->getNombreProfesor() . "</td>
						<td nowrap class='" . $color . "'>" . $curse->getCorreo() . "</td>				
						<td nowrap class='" . $color . "'>" . $curse->getPrograma() . "</td>
						<td nowrap class='" . $color . "'>" . $curse->getIdCurso() . "</td>
						<td nowrap class='" . $color . "'>" . $curse->getCodigo() . "</td>
						<td nowrap class='" . $color . "'>" . $curse->getSemestre() . "</td>
						<td nowrap class='" . $color . "'>" . $curse->getGrupo() . "</td>
						<td nowrap class='" . $color . "'>" . $curse->getNombreCurso() . "</td>"
            . items($curse->items, $cantidadItems, $porcentaje) . "
						<td nowrap class='" . $color . "'>" . $porcentaje . "</td>
					</tr>");
    }

    $sum = $verde + $amarillo + $rojoClaro + $rojoOscuro;
    $cantidadcourses = count(elementosUnicos($vector_idcourse));
    $cantidadRepetidos = count($vector_idcourse) - $cantidadcourses;
    echo ("
				</tbody>
			</table>
			<div class='container-items-porcent'>
				<div class='item-porcent tr1'><span class='txt-black'>100% - 80% |</span>		<h5>" . $verde . "</h5></div>
				<div class='item-porcent tr2'><span class='txt-black'>79% - 51%  |</span>     <h5>" . $amarillo . "</h5></div>
				<div class='item-porcent tr3'><span class='txt-black'>50% - 0%   |</span>		<h5>" . $rojoClaro . "</h5></div>
				<div class='item-porcent tr4'><span class='txt-black'>Sin actividades	|</span><h5>" . $rojoOscuro . "</h5></div>
				<div class='item-porcent td2'><span>Total de courses	|</span><h5>" . $sum . "</h5></div>
				<div class='item-porcent td2'><span>courses Repetidos	|</span><h5>" . $cantidadRepetidos . "</h5></div>
			</div>
			");
}
function idIsNotInCourse($idnumber)
{
    global $verde, $amarillo, $rojoClaro, $rojoOscuro;
    include("../services/reportRequest.php");
    include("../report/avances.php");

    date_default_timezone_set("America/Bogota");
    $dateNow = date("Y-m-d H:i:s");
    $vector_course = [];
    $vector_idcourse = [];
    $cantidadItems = 0;

        $coursesInformation = AllCoursesInformation();

        while ($courseInfo = $coursesInformation->fetch_assoc()) {

            $cumple = 0;
            $noCumple = 0;
            $course = new avance();
            $teachersNames = "";
            $teachersEmails = "";
            $teachersUsersIds = "";

            //la variable requerida en la función Usersquantity es el rol que vamos a buscar 3 Profesor
            $teachers = Usersquantity($courseInfo['course_id'], 3);

            while ($teacher = $teachers->fetch_assoc()) {
                if ($teachers->num_rows == 1) {
                    $teachersNames = ucwords(mb_strtolower($teacher['firstname'], 'UTF-8')) . " " . ucwords(mb_strtolower($teacher['lastname'], 'UTF-8'));
                    $teachersEmails = mb_strtolower($teacher['email'], 'UTF-8');
                    $teachersUsersIds = mb_strtolower($teacher['user_id'], 'UTF-8');
                } else {
                    $teachersNames .= ucwords(mb_strtolower($teacher['firstname'], 'UTF-8')) . " " . ucwords(mb_strtolower($teacher['lastname'], 'UTF-8')) . " <br> ";
                    $teachersEmails .= mb_strtolower($teacher['email'], 'UTF-8') . " <br> ";
                    $teachersUsersIds = mb_strtolower($teacher['user_id'], 'UTF-8');
                }
            }

            //Información del profesor y del course
            $course->setIdUser($teachersUsersIds);
            $course->setNombreProfesor($teachersNames);
            $course->setCorreo($teachersEmails);
            $course->setPrograma('none');
            $course->setSemestre('none');
            $course->setIdcurso($courseInfo['course_id']);
            $course->setNombreCurso($courseInfo['course_name']);

            $group = explode("*", $courseInfo["course_name"]);
            $course->setGrupo($group[count($group) - 1]);
            $code = explode($course->getGrupo(), $courseInfo['course_code']);
            $course->setcodigo($code[count($group) - 1]);

            // Se envian los dos tipos de reporte, el viejo (Avance formativo) y el nuevo (Evaluación formativa y continua)
            $gradesCategoryResult = GradesCategoryNoInID($courseInfo['course_id'], $course->getIdUser(), $idnumber);
            //Validaciones
            if (is_object($gradesCategoryResult)) {
                if ($gradesCategoryResult->num_rows > 0) {
                    foreach ($gradesCategoryResult as $gradesCategory) {
                        $itemResult = GradesCategoryItemNoInId($courseInfo['course_id'], $idnumber);
                        $cantidadItems = ($cantidadItems < $itemResult->num_rows) ? $itemResult->num_rows : $cantidadItems;
                        if ($itemResult->num_rows > 0) {
                            foreach ($itemResult as $item) {

                                if ($item["itemmodule"] == "forum") {

                                    $course->items[] = $item["name"];

                                    //calificaciones del foro

                                    $score = ScoreItem($item["id"]);
                                    ($score == "CUMPLE") ? $cumple++ : $noCumple++;
                                    $course->items[] = $score;

                                    //retroalimentación del foro

                                    $resultFeedback = FeedbackForum1($gradesCategory['id'], $item["iteminstance"]);

                                    if ($resultFeedback->num_rows > 0) {
                                        $feed1 = $resultFeedback->fetch_assoc();
                                        $resultFeedback = FeedbackForum2($feed1['id'], $course->getIdUser());
                                        ($resultFeedback == "CUMPLE") ? $cumple++ : $noCumple++;
                                        $course->items[] = $resultFeedback;
                                    } else {
                                        $course->items[] = "NO CUMPLE";
                                        $noCumple++;
                                    }
                                } elseif ($item["itemmodule"] == "assign") {

                                    $course->items[] = $item["name"];

                                    //calificaciones de la tarea
                                    $score = ScoreItem($item["id"]);
                                    ($score == "CUMPLE") ? $cumple++ : $noCumple++;
                                    $course->items[] = $score;
                                    //retroalimentación de la tarea
                                    $resultFeedback = FeedbackActivity($item["iteminstance"]);
                                    ($resultFeedback == "CUMPLE") ? $cumple++ : $noCumple++;
                                    $course->items[] = $resultFeedback;
                                } elseif ($item["itemmodule"] == "quiz") {

                                    $course->items[] = $item["name"];
                                    //calificaciones del quiz	
                                    $course->items[] = "CUMPLE";
                                    //retroalimentaciones del quiz
                                    $course->items[] = "NO APLICA";
                                    $cumple++;
                                }
                            }
                        }
                        $total = (($cumple + $noCumple) == 0) ? -1 : ($cumple + $noCumple);
                        $per = ($total == -1) ? -1 : round(((100 / $total) * $cumple));
                        $course->setPorcentaje($per);
                    }
                } else {
                    $course->setPorcentaje(-2);
                }

                $vector_course[]  = $course;
                $vector_idcourse[] = $courseInfo['course_id'];
            }
        }
    echo ("
		<div class='title-estadist'>
			<h2> NOT IN" . print_r($idnumber) . " </h2>
		</div>
		<table id='example' class='table table-striped table-bordered' cellspacing='0' width='100%'>
			<thead>
				<tr class='td1 thead-table' nowrap>
					<th class='td1' nowrap>Fecha</th>
					<th class='td1' nowrap>ID user</th>
					<th class='td1' nowrap>Nombre</th>
					<th class='td1' nowrap>Correo</th>
					<th class='td1' nowrap>Programa</th>
					<td class='td1' nowrap >ID Curso</td>
					<td class='td1' nowrap >Codigo</td>
					<th class='td1' nowrap>Semestre</th>
					<th class='td1' nowrap>Grupo</th>
					<th class='td1' nowrap>course</th>"
        . headerItems($cantidadItems) . "
					<th class='td1' nowrap>Porcentaje</th>
		  		</tr>
			</thead>
			<tbody>");

    foreach ($vector_course as $curse) {
        $color = color($curse->getPorcentaje());
        if ($curse->getPorcentaje() == -1) {
            $porcentaje = -1;
        } elseif ($curse->getPorcentaje() == -2) {
            $porcentaje = -2;
        } else {
            $porcentaje = $curse->getPorcentaje() . "%";
        }
        print("
					<tr class='" . $color . "'>
						<td nowrap class='" . $color . "'>" . $dateNow . "</td>
						<td nowrap class='" . $color . "'>" . $curse->getIdUser() . "</td>
						<td nowrap class='" . $color . "'>" . $curse->getNombreProfesor() . "</td>
						<td nowrap class='" . $color . "'>" . $curse->getCorreo() . "</td>				
						<td nowrap class='" . $color . "'>" . $curse->getPrograma() . "</td>
						<td nowrap class='" . $color . "'>" . $curse->getIdCurso() . "</td>
						<td nowrap class='" . $color . "'>" . $curse->getCodigo() . "</td>
						<td nowrap class='" . $color . "'>" . $curse->getSemestre() . "</td>
						<td nowrap class='" . $color . "'>" . $curse->getGrupo() . "</td>
						<td nowrap class='" . $color . "'>" . $curse->getNombreCurso() . "</td>"
            . items($curse->items, $cantidadItems, $porcentaje) . "
						<td nowrap class='" . $color . "'>" . $porcentaje . "</td>
					</tr>");
    }

    $sum = $verde + $amarillo + $rojoClaro + $rojoOscuro;
    $cantidadcourses = count(elementosUnicos($vector_idcourse));
    $cantidadRepetidos = count($vector_idcourse) - $cantidadcourses;
    echo ("
				</tbody>
			</table>
			<div class='container-items-porcent'>
				<div class='item-porcent tr1'><span class='txt-black'>100% - 80% |</span>		<h5>" . $verde . "</h5></div>
				<div class='item-porcent tr2'><span class='txt-black'>79% - 51%  |</span>     <h5>" . $amarillo . "</h5></div>
				<div class='item-porcent tr3'><span class='txt-black'>50% - 0%   |</span>		<h5>" . $rojoClaro . "</h5></div>
				<div class='item-porcent tr4'><span class='txt-black'>Sin actividades	|</span><h5>" . $rojoOscuro . "</h5></div>
				<div class='item-porcent td2'><span>Total de courses	|</span><h5>" . $sum . "</h5></div>
				<div class='item-porcent td2'><span>courses Repetidos	|</span><h5>" . $cantidadRepetidos . "</h5></div>
			</div>
			");
}
