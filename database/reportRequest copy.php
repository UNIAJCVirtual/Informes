<?php
// Statistics Rport
function Categories($id_programs)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("SELECT DISTINCT id FROM mdl_course_categories WHERE parent IN(" . $id_programs . ")");
	$connection3->close();
	return $result;
}
function NameCategory($id)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("SELECT id,name,parent FROM mdl_course_categories WHERE id =" . $id);
	$r = $result->fetch_assoc();
	$connection3->close();
	return $r;
}
function Program($id)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("SELECT id,name,parent FROM mdl_course_categories WHERE id =" . $id);
	$r = $result->fetch_assoc();
	$connection3->close();
	return $r["name"];
}
function StatisticsInformation($id)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
			SELECT distinct
				mdl_user.username,
				mdl_user.firstname,
				mdl_user.lastname,
				mdl_user.email,
				mdl_user.id user_id,
				mdl_course.fullname  course_fullname,
				mdl_course.id  course_id
				FROM 
				mdl_user, 
				mdl_role,
				mdl_role_assignments,
				mdl_user_enrolments,
				mdl_course, 
				mdl_enrol
				WHERE 
				mdl_role.id = mdl_role_assignments.roleid AND
				mdl_role_assignments.userid = mdl_user.id AND
				mdl_user.id = mdl_user_enrolments.userid AND
				mdl_course.id = mdl_enrol.courseid AND
				mdl_enrol.id= mdl_user_enrolments.enrolid AND
				mdl_role.id = 3 AND 
				mdl_course.visible=TRUE AND
				mdl_course.category = " . $id . "
				ORDER BY username,course_fullname
			");
	$connection3->close();
	return $result;
}
function Enrolled($id, $category)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
			SELECT count(distinct
				mdl_user.username) matriculados,
				mdl_user.firstname,
				mdl_user.lastname,
				mdl_user.email,
				mdl_user.id user_id,
				mdl_course.fullname  course_fullname,
				mdl_course.id  course_id
				FROM 
				mdl_user, 
				mdl_role,
				mdl_role_assignments,
				mdl_user_enrolments,
				mdl_course, 
				mdl_enrol
				WHERE 
				mdl_role.id = mdl_role_assignments.roleid AND
				mdl_role_assignments.userid = mdl_user.id AND
				mdl_user.id = mdl_user_enrolments.userid AND
				mdl_course.id = mdl_enrol.courseid AND
				mdl_enrol.id= mdl_user_enrolments.enrolid AND
				mdl_role.id = 5 AND 
				mdl_course.visible=TRUE AND
				mdl_course.id =" . $id . " AND
				mdl_course.category = " . $category . "
				ORDER BY username,course_fullname
			");
	$connection3->close();
	return $result;
}
// FirstAdvance
function Teachers($filter)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$query = "SELECT DISTINCT u.id userid, u.username AS mdl_user_username, u.firstname AS mdl_user_firstname, u.lastname AS mdl_user_lastname, u.email AS mdl_user_email, c.fullname course_name, c.id courseid, c.category cat 
			FROM mdl_course c,
			mdl_enrol e,
			mdl_user_enrolments en,
			mdl_user u,
			mdl_role_assignments ra
			WHERE
			c.visible = true AND
			c.category IN (SELECT DISTINCT id FROM mdl_course_categories WHERE parent IN($filter) GROUP BY c.id) AND
			c.id=e.courseid AND
			e.id=en.enrolid AND
			en.userid = u.id AND
			u.id = ra.userid AND
			ra.roleid= 3";
	//echo "<p>$query</p><br>";
	$result = $connection3->query($query);
	$connection3->close();
	return $result;
}
function Courses($course, $userid, $filter)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
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
				mdl_course.id = $course AND
				mdl_user_enrolments.userid = $userid AND
				mdl_course.id = mdl_grade_categories.courseid AND
				LOWER(mdl_grade_categories.fullname) LIKE '" . $filter . "'
				ORDER by 1,2 
		");
	$connection3->close();
	return $result;
}
function ItemCourse($courseid, $category, $type)
{
	$begin = ($type == "foro") ? 'Ff' : (($type == "encuentro") ? 'Ee' : 'Tt');
	$name = ($type == "foro") ? 'FORO' : (($type == "encuentro") ? 'ENCUENTRO' : 'TALLER');

	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$quer = "
		SELECT 
			gi.id,
			gi.courseid,
			gi.iteminstance iteminstance,
			gi.itemname name,				
			gi.itemmodule,
			gc.fullname
		FROM 
			mdl_grade_items gi,
			mdl_grade_categories gc
		WHERE 
			gc.id = gi.categoryid AND
			UPPER(gc.fullname) =  '$category' AND
			gi.courseid =  $courseid AND
			(gi.itemname RLIKE '^[$begin][0-9].*' OR gi.itemname RLIKE '^[$begin] [0-9].*' OR UPPER(gi.itemname) LIKE '$name%')";
	//echo $quer;

	$result = $connection3->query($quer);
	$connection3->close();
	return $result;
}
function gradeItems($courseid, $category)
{

	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$quer = "
		SELECT
			gi.id,
			gi.courseid,
			gi.iteminstance iteminstance,
			gi.itemname name,				
			gi.itemmodule,
			gc.fullname
		FROM
			mdl_grade_items gi,
			mdl_grade_categories gc
		WHERE
			gc.id = gi.categoryid AND
			gc.fullname LIKE '$category%' AND
			gi.courseid =  $courseid";

	$result = $connection3->query($quer);
	$connection3->close();
	return $result;
}
function ItemEva($courseid, $category)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
			SELECT 
				gi.id,
			    gi.courseid,
				gi.iteminstance iteminstance,
				gi.itemname name,				
				gi.itemmodule,
				gc.fullname
			FROM 
				mdl_grade_items gi,
				mdl_grade_categories gc
			WHERE 
			    gc.id = gi.categoryid AND
				gc.fullname = '$category' AND
				gi.courseid =  $courseid
		");
	$connection3->close();
	return $result;
}
function ScoreItem($itemid)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
			SELECT 
				SUM(gg.finalgrade) score
			FROM 
				mdl_grade_grades gg
			WHERE 
			    gg.itemid = $itemid
		");
	$connection3->close();
	$score = $result->fetch_assoc();
	return ($score["score"] > 0) ? "CUMPLE" : "NO CUMPLE";
}
function FeedbackForum1($courseid, $instance)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
			SELECT 
				id
			FROM 
				mdl_forum_discussions 
			WHERE 
				course = $courseid AND 
				forum = $instance
		");
	$connection3->close();
	return $result;
}
function FeedbackForum2($id, $user)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
			SELECT 
				MAX(message) message
			FROM 
				mdl_forum_posts
			WHERE
				discussion = $id AND
				userid = $user AND 
				LOWER(subject) LIKE 're:%'
		");
	$connection3->close();
	return $result;
}
// modificar
function FeedbackActivity($iteminstance)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
			SELECT 
				COUNT(id) feedbackComments
				COUNT (numfiles) feedbackFiles
			FROM 
				mdl_assignfeedback_comments
				mdl_assignfeedback_file
			WHERE
				assignment = $iteminstance 
		");
	$connection3->close();
	$result = $result->fetch_assoc();
	return ($result["feedbackComments"] > 0 || $result["feedbackFiles"] > 0) ? "CUMPLE" : "NO CUMPLE";
}
function finalExam($courseid)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
			SELECT 
				SUM(mdl_grade_grades.finalgrade) record
						
			FROM 
				mdl_grade_items, mdl_grade_grades,mdl_grade_categories
			WHERE 
				UPPER(mdl_grade_categories.fullname) LIKE UPPER('%EVALUACI%N FINAL') and
				mdl_grade_items.courseid =  $courseid  and
				mdl_grade_items.id = mdl_grade_grades.itemid and
				mdl_grade_items.id = mdl_grade_grades.itemid");
	$r = $result->fetch_array();
	$connection3->close();
	return (($r["record"] != null) ? $r["record"] : 0);
}
function additionalNote($courseid)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
			SELECT 
				SUM(mdl_grade_grades.finalgrade) record
						
			FROM 
				mdl_grade_items, mdl_grade_grades,mdl_grade_categories
			WHERE 
				UPPER(mdl_grade_categories.fullname) LIKE UPPER('%NOTAS ADICIONALES') and
				mdl_grade_items.courseid =  $courseid  and
				mdl_grade_items.id = mdl_grade_grades.itemid and
				mdl_grade_items.id = mdl_grade_grades.itemid");
	$r = $result->fetch_array();
	$connection3->close();
	return (($r["record"] != null) ? $r["record"] : 0);
}
function content($course)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
			SELECT distinct(mdl_course_sections.section) section_id,
				mdl_course_sections.name name,
				mdl_course_sections.visible visible,
				mdl_course_sections.summary summary,
				mdl_course_sections.sequence sequence,
				COUNT(mdl_page.id) contador,
				mdl_page.name page_name,
				mdl_page.content page_content,
				mdl_page.revision page_revision
			FROM 
				mdl_course, 
				mdl_course_sections,
				mdl_page
			WHERE 
				mdl_course.id = mdl_course_sections.course AND
				mdl_course.id= mdl_page.course AND
				mdl_course.id ='" . $course . "'
				ORDER BY section_id");
	$connection3->close();
	return $result;
}
function contentValidation($course)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
			SELECT 
				modulo.course,
				modulo.module,
				modulo.instance,
				unidad.section,
				unidad.name,
				count(unidad.name) contador,
				modulo.section,
				modulo.visible
			FROM 
				mdl_course_modules modulo,
				mdl_course_sections unidad
			WHERE 
				modulo.section=unidad.id AND 
				unidad.section <> 0 AND
				unidad.section = $idsection AND
				modulo.course= " . $course . "
				GROUP BY unidad.name
				ORDER BY unidad.section");
	$connection3->close();
	return $result;
}
function forum($course)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
			SELECT 
				count(id)
			FROM 
				mdl_forum 
			WHERE 	
				mdl_forum.course= $course AND 
				mdl_forum.type= single AND
				UPPER(mdl_forum.NAME) LIKE UPPER('%consulta%')");
	$connection3->close();
	return $result;
}


function microcurriculo($course)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
			SELECT  
				count(a.id) 
			FROM  
				mdl_course_modules a,
				mdl_scorm b 
			WHERE  
				a.instance = b.id AND
				UPPER(b.name) LIKE UPPER('microcurr%culo') AND 
				a.course =$course AND
				a.module = 16 AND 
				b.reference IS NOT NULL AND  b.revision > 0");
	$connection3->close();
	return $result;
}
function summary($idsection, $course)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result =  $connection3->query("
			SELECT 
				modulo.course, 
				modulo.module, 
				modulo.instance, 
				unidad.section, 
				unidad.name, 
				count(unidad.name) contador,
				modulo.section, 
				modulo.visible 
			FROM  
				mdl_course_modules modulo, 
				mdl_course_sections unidad
			WHERE  
				modulo.section=unidad.id AND  
				unidad.section <> 0 AND 
				unidad.section = $idsection AND
				modulo.course= $course
			GROUP BY unidad.name 
			ORDER BY unidad.section");
	$connection3->close();
	return $result;
}

// Informe error scale
function errorScale($id, $scale)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
			SELECT 
				mdl_course.fullname  course_fullname,
				mdl_course.id  course_id,
				mdl_forum.name forum_name,
				mdl_forum.id forum,
				mdl_forum.scale scale
				FROM 
				mdl_course, 
				mdl_forum
				WHERE 
				mdl_course.id = mdl_forum.course AND
				mdl_course.visible=TRUE AND
				mdl_forum.scale = $scale AND
				mdl_course.category = $id
				ORDER BY course_fullname
			");
	$connection3->close();
	return $result;
}
function log_users($min, $max)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
			SELECT 
				l.userid,
				u.firstname,
				u.lastname,
				u.email,
				l.courseid,
				c.fullname,
				l.contextinstanceid,
				l.action,
				l.timecreated,
				l.ip
				FROM 
				mdl_logstore_standard_log l, 
				mdl_user u,
				mdl_course c
				WHERE 
				l.userid = u.id AND
				l.courseid = c.id AND
				l.timecreated BETWEEN '$min' AND '$max'
			");
	$connection3->close();
	return $result;
}
