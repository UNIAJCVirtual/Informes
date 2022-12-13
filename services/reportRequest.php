<?php

//SI SE USA
function Semesters($id_programs)
{
	require_once("../services/connection.php");
	$con = connection();
	mysqli_set_charset($con, "utf8");
	$result = $con->query("SELECT DISTINCT id,name,parent FROM mdl_course_categories WHERE parent IN(" . $id_programs . ")");
	$con->close();
	return $result;
}
//SI SE USA
function ProgramsName($id_programs)
{
	require_once("../services/connection.php");
	$con = connection();
	mysqli_set_charset($con, "utf8");
	$result = $con->query("SELECT name FROM mdl_course_categories WHERE id =" . $id_programs);
	$r = $result->fetch_assoc();
	$con->close();
	return $r["name"];
}
//SI SE USA
function CoursesInformation($idCategory)
{
	require_once("../services/connection.php");
	$con = connection();
	mysqli_set_charset($con, "utf8");
	$result = $con->query("SELECT 
							mdl_course.id as course_id,
							mdl_course.fullname as course_name,
							mdl_course.shortname as course_code
							FROM 
							mdl_course 
							WHERE 
							mdl_course.category =".$idCategory);
	$con->close();
	return $result;
}
//SI SE USA
function Usersquantity($idCourse, $rol)
{
	require_once("../services/connection.php");
	$con = connection();
	mysqli_set_charset($con, "utf8");
	$result = $con->query("SELECT distinct 
							mdl_user.firstname as firstname,
							mdl_user.lastname as lastname,
							mdl_user.id user_id
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
							mdl_course.visible=TRUE AND
							mdl_role.id = ".$rol." AND
							mdl_course.id = ".$idCourse."");
	$con->close();
	return $result;
}
//SI SE USA
function Teachers($filter)
{
	require_once("../services/connection.php");
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
	$result = $connection3->query($query);
	$connection3->close();
	return $result;
}
//SI SE USA
function Courses($course, $userid, $filter)
{
	require_once("../services/connection.php");
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
//SI SE USA
function ItemCourse($courseid, $tipoReport)
{
	require_once("../services/connection.php");
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
			UPPER(gc.fullname) =  '$tipoReport'AND
			gi.courseid =  $courseid";
	$result = $connection3->query($quer);
	$connection3->close();
	return $result;
}
//SI SE USA
function dataAssign($id)
{

	require_once("../services/connection.php");
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
//SI SE USA
function dataQuiz($id)
{

	require_once("../services/connection.php");
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
//SI SE USA
function dataForum($id)
{

	require_once("../services/connection.php");
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
//SI SE USA
function gradeItems($courseid, $category)
{

	require_once("../services/connection.php");
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
//SI SE USA
function weighing($courseid, $category)
{
	require_once("../services/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
		SELECT 
			SUM(gi.aggregationcoef)  as gradeSum 
		FROM 
			mdl_grade_items gi,
			mdl_grade_categories gc 
		where
			gc.id = gi.categoryid AND
			gc.fullname LIKE '$category%' AND
			gi.courseid =  $courseid
			");
	$row = mysqli_fetch_array($result);	
	$gradesum = $row['gradeSum'];
	$connection3->close();
	return $gradesum;
}
//SI SE USA
function ScoreItem($itemid)
{
	require_once("../services/connection.php");
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
	$result = $connection3->query($query);
	$connection3->close();
	$score = $result->fetch_assoc();
	return ($score["score"] > 0) ? "CUMPLE" : "NO CUMPLE";
}
//SI SE USA
function FeedbackForum1($courseid, $instance)
{
	require_once("../services/connection.php");
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
//SI SE USA
function FeedbackForum2($id, $user)
{
	require_once("../services/connection.php");
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
//SI SE USA
function FeedbackActivity($iteminstance)
{
	require_once("../services/connection.php");
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
//SI SE USA
function contentPage($course)
{
	require_once("../services/connection.php");
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
//SI SE USA
function contentPageId($course,$idNumberPage)
{
	require_once("../services/connection.php");
	$conn = connection();
	mysqli_set_charset($conn, "utf8");
	$result = $conn->query("
			SELECT 
				mdl_page.name as name, 
				mdl_page.content as content
			FROM 
				mdl_page,
				mdl_course_modules
			WHERE 
				mdl_course_modules.course = '" . $course . "' AND 
				mdl_course_modules.idnumber = '" . $idNumberPage . "' AND
				mdl_course_modules.instance = mdl_page.id");
	$conn->close();
	return $result;
}
//SI SE USA
function contentUnits($course)
{
	require_once("../services/connection.php");
	$connection3 = connection();
	mysqli_set_charset($connection3, "utf8");
	$result = $connection3->query("
			SELECT distinct(mdl_course_sections.section) as section_id,
				mdl_course_sections.name as name,
				mdl_course_sections.visible as visible,
				mdl_course_sections.summary as summary
			FROM 
				mdl_course, 
				mdl_course_sections
			WHERE 
				mdl_course.id = mdl_course_sections.course AND
				mdl_course.id = '" . $course . "' AND
				mdl_course_sections.section != 0
				ORDER BY section_id");
	$connection3->close();
	return $result;
}
//SI SE USA
function forum($course)
{
	require_once("../services/connection.php");
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
//SI SE USA
function forumDiscussions($id)
{
	require_once("../services/connection.php");
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
//NO SE USA
/*
function summary($idsection, $course)
{
	require_once("../services/connection.php");
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
}*/
