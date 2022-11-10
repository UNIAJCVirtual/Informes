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
@fuction: ordenar
@description: Se declaran las varialbes que ayudaran a ordenar comparando el porcentaje y utiliazando el metodo usort.
@param: array $ar1
@param: array $ar2
@return: int : el cual ayudara a ordenar utilizando el metodo usort.
@author:	José David Lamilla A.
@version	1.0
@fecha: por definir
*/

function ordenar($ar1, $ar2)
{
	if ($ar1->{'porcentaje'} > $ar2->{'porcentaje'}) {
		return -1;
	} else if ($ar1->{'porcentaje'} < $ar2->{'porcentaje'}) {
		return 1;
	}
	return 0;
}

/*
@fuction: quitar_tildes
@description: se formatea la cadena con utf8 y se quitan las tildes.
@param: String $cadena
@return: String : devuelve la cadena ya sin tildes.
@author:	José David Lamilla A.
@version	1.0
@fecha: por definir
*/

function quitar_tildes($cadena)
{
	$cade = utf8_decode($cadena);
	$no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "?");
	$permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "ñ");
	$texto = str_replace($no_permitidas, $permitidas, $cade);
	return $texto;
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

function items($items, $cantItems)
{
	$result = "";
	$cantItems *= 3;

	if (count($items) > 0) {
		for ($i = 0; $i < $cantItems; ++$i) {
			$result .= count($items) > $i ? "<td nowrap>" . $items[$i] . "</td>" : "<td></td>";
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

function advanceReport($program, $semestre, $type_report)
{
	global $verde, $amarillo, $rojoClaro, $rojoOscuro;
	include("../services/reportRequest.php");
	$vector_curso = [];
	$teachesResult = Teachers(implode(",", $program));
	$cantidadItems = 0;

	if ($teachesResult->num_rows > 0) {

		foreach ($teachesResult as $value) {

			$cumple = 0;
			$noCumple = 0;
			$curso = new avance();
			$semestre = NameCategory($value['cat']);
			$program = Program($semestre["parent"]);
			$coursesResult = Courses($value['courseid'], $value['userid'], $type_report);

			foreach ($coursesResult as $valueC) {
				$curso->setIdUser($value['userid']);
				$curso->setNombreProfesor(quitar_tildes(ucwords(strtolower($value['mdl_user_firstname'])) . " " . ucwords(strtolower($value['mdl_user_lastname']))));
				$curso->setCorreo($value["mdl_user_email"]);
				$curso->setPrograma($program);
				$curso->setSemestre($semestre["name"]);
				$curso->setNombreCurso($value['course_name']);

				$recordItem = ItemCourse($value['courseid'], strtoupper($type_report));
				$cantidad = $recordItem->num_rows;
				$cantidadItems = ($cantidadItems < $cantidad) ? $cantidad : $cantidadItems;
				if ($cantidad > 0) {

					foreach ($recordItem as $recordItems) {
						if ($recordItems["itemmodule"] == "forum") {

							$curso->items[] = $recordItems["name"];

							//calificaciones del foro
							$score = ScoreItem($recordItems["id"]);
							($score == "CUMPLE") ? $cumple++ : $noCumple++;
							$curso->items[] = $score;

							//retroalimentación del foro
							$resultFeedback = FeedbackForum1($valueC['id'], $recordItems["iteminstance"]);

							if ($resultFeedback->num_rows > 0) {
								$feed1 = $resultFeedback->fetch_assoc();
								$resultFeedback = FeedbackForum2($feed1['id'], $value["userid"]);
								($resultFeedback == "CUMPLE") ? $cumple++ : $noCumple++;
								$curso->items[] = $resultFeedback;
							} else {
								$curso->items[] = "NO CUMPLE";
								$noCumple++;
							}
						} elseif ($recordItems["itemmodule"] == "assign") {

							$curso->items[] = $recordItems["name"];

							//calificaciones de la tarea
							$score = ScoreItem($recordItems["id"]);
							($score == "CUMPLE") ? $cumple++ : $noCumple++;
							$curso->items[] = $score;
							//retroalimentación de la tarea
							$resultFeedback = FeedbackActivity($recordItems["iteminstance"]);
							($resultFeedback == "CUMPLE") ? $cumple++ : $noCumple++;
							$curso->items[] = $resultFeedback;
						} elseif ($recordItems["itemmodule"] == "quiz") {

							$curso->items[] = $recordItems["name"];
							//calificaciones del quiz	
							$curso->items[] = "CUMPLE";
							//retroalimentaciones del quiz
							$curso->items[] = "NO APLICA";
							$cumple++;
						}
					}
				}
				$total = (($cumple + $noCumple) == 0) ? -1 : ($cumple + $noCumple);
				$per = ($total == -1) ? -1 : round(((100 / $total) * $cumple));
				$curso->setPorcentaje($per);
			}
			$vector_curso[] = $curso;
		}
		usort($vector_curso, 'ordenar');
		echo "

		<table id='example' class='table table-striped table-bordered' cellspacing='0' width='100%'>
			<thead>
				<tr class='td1 thead-table' nowrap>
					<th class='td1' nowrap>ID user</th>
					<th class='td1' nowrap>Nombre</th>
					<th class='td1' nowrap>Correo</th>
					<th class='td1' nowrap>Curso</th>
					<th class='td1' nowrap>Programa</th>
					<th class='td1' nowrap>Semestre</th>"
			. headerItems($cantidadItems) . "
					<th class='td1' nowrap>Porcentaje</th>
		  		</tr>
			</thead>
			<tbody>";

		foreach ($vector_curso as $curse) {

			$color = color($curse->getPorcentaje());
			$porcentaje = ($curse->getPorcentaje() == -1) ? 'Sin actividades' : $curse->getPorcentaje() . "%";
			print("
				<tr class='" . $color . "'>
					<td nowrap class='" . $color . "'>" . $curse->getIdUser() . "</td>
					<td nowrap class='" . $color . "'>" . $curse->getNombreProfesor() . "</td>
					<td nowrap class='" . $color . "'>" . $curse->getCorreo() . "</td>				
					<td nowrap class='" . $color . "'>" . $curse->getNombreCurso() . "</td>
					<td nowrap class='" . $color . "'>" . $curse->getPrograma() . "</td>
					<td nowrap class='" . $color . "'>" . $curse->getSemestre() . "</td>"
				. items($curse->items, $cantidadItems) . "
					<td nowrap class='" . $color . "'>" . $porcentaje . "</td>
				</tr>");
		}

		$sum = $verde + $amarillo + $rojoClaro + $rojoOscuro;

		echo "
			</tbody>
		</table>
		<br>
		<table class='tables'>
	
			<tr class='td1'>
			<td class='td1' colspan='2'>Porcentajes</th>
			<td class='td1' colspan='2'>Cantidad de cursos</th>
			</tr>
	
			<tr class='tr1'>
			<td class='tr1' colspan='2' > 100% - 80% </td>
			<td class='tr1' colspan='2'>" . $verde . "</td>
			</tr>
			<tr class='tr2'>
			<td class='tr2' colspan='2'> 79% - 51% </td>
			<td class='tr2' colspan='2'>" . $amarillo . "</td>
			</tr>
			<tr class='tr3'>
			<td class='tr3' colspan='2'> 50% - 0% </td>
			<td class='tr3' colspan='2'>" . $rojoClaro . "</td>
			</tr>
			<tr class='tr4'>
			<td class='tr4' colspan='2'> Sin actividades </td>
			<td class='tr4' colspan='2'>" . $rojoOscuro . "</td>
			</tr>
			<tr class='td1'>
			<td class='td1' colspan='2'> Total de cursos </td>
			<td class='td1' colspan='2'>" . $sum . "</td>
			</tr>
		</table>
		";
	} else {
		echo ("<b> En estos momentos el programa: " . Program(implode(",", $program)) . "  no cuenta con cursos.</b><br>");
	}
}
