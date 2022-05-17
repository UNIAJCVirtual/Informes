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
				mdl_course.shortname course_shortname,
				mdl_course.id  course_id,
				mdl_course.startdate startdate,
				mdl_course.enddate enddate
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
function Enrolled($id, $tipoRol)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
			SELECT count(distinct
				mdl_user.username) matriculados,
				mdl_user.firstname,
				mdl_user.lastname
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
				mdl_role.id = " . $tipoRol . " AND 
				mdl_course.visible=TRUE AND
				mdl_course.id =" . $id . "
			");
	$connection3->close();
	return $result;
}
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
				LOWER(mdl_grade_categories.fullname) LIKE '%" . $filter . "'
				ORDER by 1,2 
		");
	$connection3->close();

	return $result;
}
function ItemCourse($courseid, $category)
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
			UPPER(gc.fullname) =  '$category' AND
			gi.courseid =  $courseid";
	//echo $quer;

	$result = $connection3->query($quer);
	$connection3->close();
	return $result;
}
function dataAssign($id)
{

	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$quer = "
		SELECT
		*
		FROM 
			mdl_assign
		WHERE 
			id=$id";


	$result = $connection3->query($quer);
	$connection3->close();
	return $result;
}
function dataQuiz($id)
{

	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$quer = "
		SELECT 
			*
		FROM 
			mdl_quiz
		WHERE 
			id=$id";


	$result = $connection3->query($quer);
	$connection3->close();
	return $result;
}

function dataForum($id)
{

	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$quer = "
		SELECT 
			*
		FROM 
			mdl_forum
		WHERE 
			id=$id";


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
			gc.fullname,
			gc.id as idc
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
function weighing($courseid, $categoryid)
{

	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$quer = "
		SELECT 
			SUM(`aggregationcoef`)  as gradeSum 
		FROM 
			`mdl_grade_items` 
		where
			`courseid`=$courseid and `categoryid`= $categoryid";

	//echo $quer;
	$result = $connection3->query($quer);
	$connection3->close();
	return $result;
}
function ScoreItem($itemid)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$query = "
	SELECT 
		SUM(gg.finalgrade) score
	FROM 
		mdl_grade_grades gg
	WHERE 
		gg.itemid = $itemid
	";
	//echo $query;
	$result = $connection3->query($query);
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
	$result = $result->fetch_assoc();

	return (count(explode(" ", $result["message"])) > 2) ? "CUMPLE" : "NO CUMPLE";
}
// modificar

function FeedbackActivity($iteminstance)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
			SELECT 
			
				COUNT(id) feedback
			FROM 
				mdl_assignfeedback_comments
			WHERE
				assignment = $iteminstance 
		");
	$connection3->close();
	$result = $result->fetch_assoc();
	return ($result["feedback"] > 0) ? "CUMPLE" : "NO CUMPLE";
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
function forum($course)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
			SELECT 
				id
			FROM 
				mdl_forum 
			WHERE 	
				mdl_forum.course= $course AND 
				UPPER(mdl_forum.NAME) LIKE UPPER('%consulta%')");
	$connection3->close();
	return $result;
}
function forumDiscussions($id)
{
	require_once("../database/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
			SELECT 
				count(id) as dis
			FROM 
				`mdl_forum_discussions` 
			where 
				`forum`= $id");
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
