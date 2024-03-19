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
