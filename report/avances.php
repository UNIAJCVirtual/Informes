<?php

include("../models/avance_model.php");

/*
@Variables publicas
@description: Son todas la variables globales de avances.php
@author:	José David Lamilla A.
@version	1.0
@fecha: 26/10/2022
*/

$verde = 0;
$amarillo = 0;
$rojoClaro = 0;
$rojoOscuro = 0;

//----------------------------------------------
function elementosUnicos($array)
{
	$arraySinDuplicados = [];
	foreach ($array as $elemento) {
		if (!in_array($elemento, $arraySinDuplicados)) {
			$arraySinDuplicados[] = $elemento;
		}
	}
	return $arraySinDuplicados;
}

/*
@fuction: color
@description: El metodo se encarga de definir la clase de cada fila.
@param: int $porcentaje
@return: String : que dependera de la clase el color que llevara la fila.
@author:	José David Lamilla A.
@version	1.0
@fecha: 26/10/2022
*/

function color($porcentaje)
{
	global $verde, $amarillo, $rojoClaro, $rojoOscuro;
	if ($porcentaje >= 80 && $porcentaje <= 100) {
		$verde++;
		return "tr1";
	} elseif ($porcentaje >= 51 && $porcentaje <= 79) {
		$amarillo++;
		return "tr2";
	} elseif ($porcentaje >= 0 && $porcentaje <= 50) {
		$rojoClaro++;
		return "tr3";
	} else {
		$rojoOscuro++;
		return "tr4";
	}
}
/*
@fuction: headerItems
@description: Imprimir el encabezado de items siguiendo un patron
@param: int $cantItems
@return: String : devuelve un String el cual contendra las columnas de los encabezados de los items.
@author:	José David Lamilla A.
@version	1.0
@fecha: 25/10/2022
*/
function headerItems($cantItems)
{
	$result = "";
	if ($cantItems > 0) {
		for ($i = 1; $i < $cantItems + 1; ++$i) {
			$result .= "<th nowrap class='td1'>Nombre de la actividad " . $i . "</th>";
			$result .= "<th nowrap class='td1'>Calificaciones " . $i . "</th>";
			$result .= "<th nowrap class='td1'>Retroalimentaciones " . $i . "</th>";
		}
	} else {
		$result .= "<th nowrap class='td1'>No hay actividades por mostrar</th>";
	}

	return $result;
}
/*
@fuction: items
@description: Imprimir los items con la evaluación en una tabla.
@param: array $items
@param: int $cantItems
@return: String : devuelve un String el cual contendra los items alineados en una fila.
@author:	José David Lamilla A.
@version	2.0
@fecha: 26/10/2022
*/

function items($items, $cantItems, $porcentaje)
{
	$result = "";
	$cantItems *= 3;
	if (count($items) > 0) {
		for ($i = 0; $i < $cantItems; ++$i) {
			$result .= count($items) > $i ? "<td nowrap>" . $items[$i] . "</td>" : "<td></td>";
		}
	} elseif ($porcentaje == -2) {
		$result .= "<td nowrap class='tr4'>No coincide la categoría</td>";
		for ($i = 0; $i < $cantItems - 1; ++$i) {
			$result .= "<td nowrap></td>";
		}
	} else {
		$result .= "<td nowrap class='tr4'>Sin actividades</td>";
		for ($i = 0; $i < $cantItems - 1; ++$i) {
			$result .= "<td nowrap></td>";
		}
	}
	return $result;
}
/*
@fuction: advanceReport
@description: Imprimir los items con la evaluación en una tabla.
@param: 
@param: 
@param: 
@return: String : devuelve un String el cual contendra los items alineados en una fila.
@author:	José David Lamilla A.
@version	2.0
@fecha: 26/10/2022
*/

function advanceReport($program, $idnumber)
{
	global $verde, $amarillo, $rojoClaro, $rojoOscuro;
	include("../services/reportRequest.php");
	date_default_timezone_set("America/Bogota");
	$dateNow = date("Y-m-d H:i:s");
	$vector_course = [];
	$vector_idcourse = [];
	$cantidadItems = 0;

	$semesters = Semesters(implode(",", $program));

	if ($semesters) {

		foreach ($semesters as $semester) {
			$programName = ProgramsName($semester['parent']);
			$semesterName = $semester['name'];
			$coursesInformation = CoursesInformation($semester['id']);

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
				$course->setPrograma($programName);
				$course->setSemestre($semesterName);
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
							print $itemResult->num_rows;
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
	} else {
		echo ("<b> En estos momentos el programa: " . ProgramsName(implode(",", $program)) . "  no cuenta con courses.</b><br>");
		echo ("
		<div class='title-estadist'>
		<p>ESTADISTICA DE LOS courseS EN</p>
		<h2>AULAS VIRTUALES MOODLE</h2>
		</div>");
	}
}
