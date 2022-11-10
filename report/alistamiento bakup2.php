<?php
include_once("../class/model.php");
header('Content-Type: text/html; charset=UTF-8');
function rellenar($n)
{
	$c = "";
	for ($i = 0; $i < $n; $i++) {
		$c .= "<td>NO APLICA</td>";
	}
	return $c;
}
function validarEmail($cont, $e)
{
	$flag = false;
	$email = '';
	$buscar = array(chr(13) . chr(10), "\r\n", "\n", "\r");
	$reemplazar = array("", "", "", "");
	$sin = str_replace($buscar, $reemplazar, strip_tags($cont));
	$arrayContenido = explode(":", $sin);
	if (count($arrayContenido) > 0) {
		for ($i = 0; $i < count($arrayContenido); $i++) {
			if (substr_count($arrayContenido[$i], "@") == 1) {
				$email = $arrayContenido[$i];
				$flag = true;
				break;
			}
		}
		if ($flag) {
			if ((strpos($email, $e)) !== false) {
				return true;
			} else if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	} else {
		return false;
	}
}
function quitar_tildes($cadena)
{
	$cadena = utf8_decode($cadena);
	$no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú");
	$permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U");
	$texto = str_replace($no_permitidas, $permitidas, $cadena);
	return $texto;
}

/*
@fuction:
@description:
@param: 
@return: int : 
@author:	José David Lamilla A.
@version	2.0
*/

function enlistmentReport($program, $semester)
{
	include("../services/reportRequest.php");
	if (date("m") == 7) {
		if(date("d") < 16){
			$fechaInicio = strtotime(date("Y") . "-01-01 00:00:00", time());
			$fechaFinal = strtotime(date("Y") . "-07-15 00:00:00", time());
		}else {
			$fechaInicio = strtotime(date("Y") . "-07-16 00:00:00", time());
			$fechaFinal = strtotime(date("Y") . "-12-31 00:00:00", time());
	    }
	}elseif (date("m") < 7) {
		$fechaInicio = strtotime(date("Y") . "-01-01 00:00:00", time());
		$fechaFinal = strtotime(date("Y") . "-07-15 00:00:00", time());
	}else {
		$fechaInicio = strtotime(date("Y") . "-07-16 00:00:00", time());
		$fechaFinal = strtotime(date("Y") . "-12-31 00:00:00", time());
	}

	$categoriesResult = Categories(implode(",", $program));
	echo ("
	<table class='table'>
		<tr class='td1'>
			<td>ID user</td>
			<td>Nombre</td>
			<td>Correo</td>
			<td>Programa</td>
			<td>Semestre</td>
			<td>Curso</td>
			<td>Nombre del curso</td>
			<td>Nombre del profesor</td>
			<td>Correo</td>
			<td>Horario de atención</td>
			<td>Fotografía</td>
			<td>Foro de consulta</td>
			<td>Fecha de inicio y Finalización Unidad 1</td>
			<td>Fecha de inicio y Finalización Unidad 2</td>
			<td>Fecha de inicio y Finalización Unidad 3</td>
			<td>Fecha de inicio y Finalización Unidad 4</td>
			<td>Fecha de inicio y Finalización Unidad 5</td>
			<td>Fecha de inicio y Finalización Unidad 6</td>
			<td>Fecha de inicio y Finalización Unidad 7</td>
			<td>Fecha de inicio y Finalización Unidad 8</td>
			<td>AF01 Actividades</td>
			<td>AF01 Disponibilidad</td>
			<td>AF01 Ponderaciones</td>
			<td>AF02 Actividades</td>
			<td>AF02 Disponibilidad</td>
			<td>AF02 Ponderaciones</td>
			<td>AF03 Actividades</td>
			<td>AF03 Disponibilidad</td>
			<td>AF03 Ponderaciones</td>
			<td>AF03 Porcentaje</td>
		</tr>");
	foreach ($categoriesResult as $val) {
		$result = NameCategory($val['id']);
		$semester = $result["name"];
		$program = Program($result['parent']);
		$result = StatisticsInformation($val['id']);
		while ($columna = $result->fetch_assoc()) {
			$email = trim(strtolower($columna['email']));
			$resultContenido = content($columna['course_id']);
			$cadena = "";
			$fila = "<tr>
				<td>" . $columna['user_id'] . "</td>
				<td>" . ucwords(strtolower($columna['firstname'])) . " " . ucwords(strtolower($columna['lastname'])) . "</td>
				<td>" . $columna['email'] . "</td>
				<td>$program</td>
				<td>$semester</td>
				<td>" . $columna['course_id'] . "</td>
				<td>" . $columna['course_fullname'] . "</td>";
			$noCumple = 0;
			$cumple = 0;
			$contador = 8;
			while ($resultInformacion = $resultContenido->fetch_assoc()) {
				$namesection = $resultInformacion['name'];
				$contenidoName = strtolower($resultInformacion['page_name']);
				$contenido = strtolower(quitar_tildes($resultInformacion['page_content']));
				$idsection = $resultInformacion['section_id'];
				$sectionvisible = $resultInformacion['visible'];
				if ($contador > 0) {
					if ($cadena != $namesection) {
						if ($idsection == 0) {
							//Req. 1 - Información del docente
							$nombreCompleto = strtolower($columna['firstname']) . " " . strtolower($columna['lastname']);
							$Nombre = explode(" ", $nombreCompleto);
							$name = false;
							foreach ($Nombre as $valor) {
								if ($valor != null) {
									if ((strpos($contenidoName, $valor))) {
										$fila .= "<td>CUMPLE</td>";
										$cumple++;
										$name = true;
										break;
									}
								}
							}
							if ($name == false) {
								$fila .= "<td>NO CUMPLE</td>";
								$noCumple++;
							}
							// Req. 2 - Información de contacto (email)
							$validar = strpos($contenido, $email);
							if ($validar !== false) {
								$fila .= "<td>CUMPLE</td>";
								$cumple++;
							} else {
								if (validarEmail($contenido, $email, $columna['course_fullname'])) {
									$fila .= "<td>CUMPLE</td>";
									$cumple++;
								} else {
									$fila .= "<td>NO CUMPLE</td>";
									$noCumple++;
								}
							}
							//Req. 3 - Horario de atención
							if ((strpos($contenido, 'indicar las horas de atencion que tendra para sus estudiantes') !== false)) {
								$fila .= "<td>NO CUMPLE</td>";
								$noCumple++;
							} else  if (
								(strpos($contenido, 'lunes') !== false) ||
								(strpos($contenido, 'martes') !== false) ||
								(strpos($contenido, 'miercoles') !== false) ||
								(strpos($contenido, 'jueves') !== false) ||
								(strpos($contenido, 'viernes') !== false) ||
								(strpos($contenido, 'sabado') !== false) ||
								(strpos($contenido, 'sabados') !== false) ||
								(strpos($contenido, 'domingos') !== false) ||
								(strpos($contenido, 'domingo') !== false)
							) {
								$fila .= "<td>CUMPLE</td>";
								$cumple++;
							} else {
								$fila .= "<td>NO CUMPLE</td>";
								$noCumple++;
							}
							//Req. 4 - Fotografia del Profesor
							if ((strpos($contenido, 'insertar foto de tamaño 200')) !== false) {
								$fila .= "<td>NO CUMPLE</td>";
								$noCumple++;
							} else {
								$fila .= "<td>CUMPLE</td>";
								$cumple++;
							}

							// validacion foro consulta
							$resultFC = forum($columna['course_id']);

							if ($resultFC->num_rows > 0) {
								$resultFC = mysqli_fetch_array($resultFC);
								$discussions = forumDiscussions($resultFC["id"]);
								$discussions = mysqli_fetch_array($discussions);
								if ($discussions["dis"] > 0) {
									$fila .= "<td>CUMPLE</td>";
									$cumple++;
								} else {
									$fila .= "<td>NO CUMPLE</td>";
									$noCumple++;
								}
							} else {
								$fila .= "<td>NO CUMPLE</td>";
								$noCumple++;
							}
						} else {
							// 	
							$sumarycon = $resultInformacion['summary'];
							$resultValidacion = summary($idsection, $columna['course_id']);
							if ($resultValidacion->num_rows > 0) {
								$contador--;
								$columnaval = mysqli_fetch_array($resultValidacion);
								$contadorval = $columnaval['contador'];
								$contadorval = (int) $contadorval;
								$section = $columnaval['section'];

								if ($sectionvisible === "0") {
									$fila .= "<td>NO APLICA</td>";
								} else if ((strpos($sumarycon, 'DD/MM/AAAA')) !== false) {
									$fila .= "<td>NO CUMPLE</td>";
									$noCumple++;
								} else {
									$fila .= "<td>CUMPLE</td>";
									$cumple++;
								}
							}
						}
					}
				}
				$cadena = $namesection;
			}
				$avanceFormativo1 = gradeItems($columna['course_id'], "AVANCE FORMATIVO 1");
				$rowsAvanceFormativo1 = $avanceFormativo1->num_rows;
				$avanceFormativo2 = gradeItems($columna['course_id'], "AVANCE FORMATIVO 2");
				$rowsAvanceFormativo2 = $avanceFormativo2->num_rows;
				$avanceFormativo3 = gradeItems($columna['course_id'], "AVANCE FORMATIVO 3");
				$rowsAvanceFormativo3 = $avanceFormativo3->num_rows;
				$rowsGrade = "";
				//Revisar libro de calificaciones categoría Avance formativo 1------------------------	

				if ($rowsAvanceFormativo1 > 0) {

					$rowsGrade .= "<td>CUMPLE</td>";

					$gc = mysqli_fetch_array($avanceFormativo1);
					$weighingGrade = weighing($columna['course_id'], $gc["idc"]);
					$weighing = mysqli_fetch_array($weighingGrade);
					$noCumpleDispo = 0;
					$CumpleDispo = 0;

					//Disponibilidad de las actividades  Avance formativo 1------------------------------------------

					foreach ($avanceFormativo1 as $record) {

						if ($record["itemmodule"] == "assign") {
							$dataAssign = dataAssign($record["iteminstance"]);

							foreach ($dataAssign as $data) {

								if ($data["duedate"] == 0 || $data["allowsubmissionsfromdate"] == 0 || $data["cutoffdate"] == 0) {
									$noCumpleDispo++;
								} else {
									//Variable de Permitir entregas desde
									$duedate = $data["duedate"];
									//Variable de Fecha de entrega
									$dateSubmissions = $data["allowsubmissionsfromdate"];
									//Variable de Fecha límite
									$cutoffdate = $data["cutoffdate"];
									if ($dateSubmissions > $fechaInicio && $duedate > $fechaInicio && $cutoffdate < $fechaFinal) {
										$CumpleDispo++;
									} else {
										$noCumpleDispo++;
									}
								}
							}
						} elseif ($record["itemmodule"] == "quiz") {
							$dataQuiz = dataQuiz($record["iteminstance"]);

							foreach ($dataQuiz as $data) {
								if ($data["timeopen"] == 0 || $data["timeclose"] == 0) {
									$noCumpleDispo++;
								} else {
									//la variable timeOpen significa la fecha Abrir cuestionario
									$timeOpen = $data["timeopen"];
									//la variable timeClose significa la fecha cerrar cuestionario
									$timeClose = $data["timeclose"];
									if ($timeOpen > $fechaInicio && $timeClose < $fechaFinal) {
										$CumpleDispo++;
									} else {
										$noCumpleDispo++;
									}
								}
							}
						} elseif ($record["itemmodule"] == "forum") {
							$dataForum = dataForum($record["iteminstance"]);

							foreach ($dataForum as $data) {
								if ($data["duedate"] == 0 || $data["cutoffdate"] == 0) {
									$noCumpleDispo++;
								} else {
									//la variable duedate significa Fecha de entrega
									$duedate = $data["duedate"];
									//la variable cutoffdate significa Fecha límite
									$cutoffdate = $data["cutoffdate"];
									if ($duedate > $fechaInicio && $cutoffdate < $fechaFinal) {
										$CumpleDispo++;
									} else {
										$noCumpleDispo++;
									}
								}
							}
						}
					}
					if ($noCumpleDispo > 0) {
						$rowsGrade .= "<td>NO CUMPLE</td>";
						$noCumple++;
					} else {
						$rowsGrade .= "<td>CUMPLE</td>";
						$cumple++;
					}
					//------Ponderación de las actividades  Avance formativo 1------------------------------------------------------------------------

					if ($weighing["gradeSum"] > 100 || $weighing["gradeSum"] == 0) {
						$rowsGrade .= "<td>NO CUMPLE</td>";
						$noCumple++;
					} else {
						if ($weighing["gradeSum"] == 100 || $weighing["gradeSum"] == 30 || $weighing["gradeSum"] == 0.30 || $weighing["gradeSum"] == 1 || $weighing["gradeSum"] == 99.99) {
							$rowsGrade .= "<td>CUMPLE</td>";
							$cumple++;
						} else {
							$rowsGrade .= "<td>NO CUMPLE</td>";
							$noCumple++;
						}
					}
				} else {
					$rowsGrade .= "<td>NO CUMPLE</td>";
					$rowsGrade .= "<td>NO CUMPLE</td>";
					$rowsGrade .= "<td>NO CUMPLE</td>";
					$noCumple += 3;
				}

				//Revisar libro de calificaciones categoría Avance formativo 2------------------------	

				if ($rowsAvanceFormativo2 > 0) {
					$rowsGrade .= "<td>CUMPLE</td>";
					$gc = mysqli_fetch_array($avanceFormativo2);
					$weighingGrade = weighing($columna['course_id'], $gc["idc"]);
					$weighing = mysqli_fetch_array($weighingGrade);
					$noCumpleDispo = 0;
					$CumpleDispo = 0;

					//Disponibilidad de las actividades  Avance formativo 2------------------------------------------

					foreach ($avanceFormativo2 as $record) {

						if ($record["itemmodule"] == "assign") {
							$dataAssign = dataAssign($record["iteminstance"]);

							foreach ($dataAssign as $data) {

								if ($data["duedate"] == 0 || $data["allowsubmissionsfromdate"] == 0 || $data["cutoffdate"] == 0) {
									$noCumpleDispo++;
								} else {
									//Variable de Permitir entregas desde
									$duedate = $data["duedate"];
									//Variable de Fecha de entrega
									$dateSubmissions = $data["allowsubmissionsfromdate"];
									//Variable de Fecha límite
									$cutoffdate = intval($data["cutoffdate"]);

									if ($dateSubmissions > $fechaInicio && $duedate > $fechaInicio && $cutoffdate < $fechaFinal) {
										$CumpleDispo++;
									} else {
										$noCumpleDispo++;
									}
								}
							}
						} elseif ($record["itemmodule"] == "quiz") {
							$dataQuiz = dataQuiz($record["iteminstance"]);

							foreach ($dataQuiz as $data) {
								if ($data["timeopen"] == 0 || $data["timeclose"] == 0) {
									$noCumpleDispo++;
								} else {
									//la variable timeOpen significa la fecha Abrir cuestionario
									$timeOpen = $data["timeopen"];
									//la variable timeClose significa la fecha cerrar cuestionario
									$timeClose = $data["timeclose"];
									if ($timeOpen > $fechaInicio && $timeClose < $fechaFinal) {
										$CumpleDispo++;
									} else {
										$noCumpleDispo++;
									}
								}
							}
						} elseif ($record["itemmodule"] == "forum") {
							$dataForum = dataForum($record["iteminstance"]);

							foreach ($dataForum as $data) {
								if ($data["duedate"] == 0 || $data["cutoffdate"] == 0) {
									$noCumpleDispo++;
								} else {
									//la variable duedate significa Fecha de entrega
									$duedate = $data["duedate"];
									//la variable cutoffdate significa Fecha límite
									$cutoffdate = $data["cutoffdate"];
									if ($duedate > $fechaInicio && $cutoffdate < $fechaFinal) {
										$CumpleDispo++;
									} else {
										$noCumpleDispo++;
									}
								}
							}
						}
					}
					if ($noCumpleDispo > 0) {
						$rowsGrade .= "<td>NO CUMPLE</td>";
						$noCumple++;
					} else {
						$rowsGrade .= "<td>CUMPLE</td>";
						$cumple++;
					}
					//------Ponderación de las actividades  Avance formativo 2------------------------------------------------------------------------

					if ($weighing["gradeSum"] > 100 || $weighing["gradeSum"] == 0) {
						$rowsGrade .= "<td>NO CUMPLE</td>";
						$noCumple++;
					} else {
						if ($weighing["gradeSum"] == 100 || $weighing["gradeSum"] == 30 || $weighing["gradeSum"] == 0.30 || $weighing["gradeSum"] == 1 || $weighing["gradeSum"] == 99.99) {
							$rowsGrade .= "<td>CUMPLE</td>";
							$cumple++;
						} else {
							$rowsGrade .= "<td>NO CUMPLE</td>";
							$noCumple++;
						}
					}
				} else {
					$rowsGrade .= "<td>NO CUMPLE</td>";
					$rowsGrade .= "<td>NO CUMPLE</td>";
					$rowsGrade .= "<td>NO CUMPLE</td>";
					$noCumple += 3;
				}

				//Revisar libro de calificaciones categoría Avance formativo 3------------------------	

				if ($rowsAvanceFormativo3 > 0) {
					$rowsGrade .= "<td>CUMPLE</td>";
					$gc = mysqli_fetch_array($avanceFormativo3);
					$weighingGrade = weighing($columna['course_id'], $gc["idc"]);
					$weighing = mysqli_fetch_array($weighingGrade);
					$noCumpleDispo = 0;
					$CumpleDispo = 0;

					//Disponibilidad de las actividades  Avance formativo 3------------------------------------------

					foreach ($avanceFormativo3 as $record) {

						if ($record["itemmodule"] == "assign") {
							$dataAssign = dataAssign($record["iteminstance"]);

							foreach ($dataAssign as $data) {

								if ($data["duedate"] == 0 || $data["allowsubmissionsfromdate"] == 0 || $data["cutoffdate"] == 0) {
									$noCumpleDispo++;
								} else {
									//Variable de Permitir entregas desde
									$duedate = $data["duedate"];
									//Variable de Fecha de entrega
									$dateSubmissions = $data["allowsubmissionsfromdate"];
									//Variable de Fecha límite
									$cutoffdate = intval($data["cutoffdate"]);

									if ($dateSubmissions > $fechaInicio && $duedate > $fechaInicio && $cutoffdate < $fechaFinal) {
										$CumpleDispo++;
									} else {
										$noCumpleDispo++;
									}
								}
							}
						} elseif ($record["itemmodule"] == "quiz") {
							$dataQuiz = dataQuiz($record["iteminstance"]);

							foreach ($dataQuiz as $data) {
								if ($data["timeopen"] == 0 || $data["timeclose"] == 0) {
									$noCumpleDispo++;
								} else {
									//la variable timeOpen significa la fecha Abrir cuestionario
									$timeOpen = $data["timeopen"];
									//la variable timeClose significa la fecha cerrar cuestionario
									$timeClose = $data["timeclose"];
									if ($timeOpen > $fechaInicio && $timeClose < $fechaFinal) {
										$CumpleDispo++;
									} else {
										$noCumpleDispo++;
									}
								}
							}
						} elseif ($record["itemmodule"] == "forum") {
							$dataForum = dataForum($record["iteminstance"]);

							foreach ($dataForum as $data) {
								if ($data["duedate"] == 0 || $data["cutoffdate"] == 0) {
									$noCumpleDispo++;
								} else {
									//la variable duedate significa Fecha de entrega
									$duedate = $data["duedate"];
									//la variable cutoffdate significa Fecha límite
									$cutoffdate = $data["cutoffdate"];
									if ($duedate > $fechaInicio && $cutoffdate < $fechaFinal) {
										$CumpleDispo++;
									} else {
										$noCumpleDispo++;
									}
								}
							}
						}
					}
					if ($noCumpleDispo > 0) {
						$rowsGrade .= "<td>NO CUMPLE</td>";
						$noCumple++;
					} else {
						$rowsGrade .= "<td>CUMPLE</td>";
						$cumple++;
					}
					//------Ponderación de las actividades  Avance formativo 3-----------------------------------------------------------------------
					if ($weighing["gradeSum"] > 100 || $weighing["gradeSum"] == 0) {
						$rowsGrade .= "<td>NO CUMPLE</td>";
						$noCumple++;
					} else {
						if ($weighing["gradeSum"] == 100 || $weighing["gradeSum"] == 40 || $weighing["gradeSum"] == 0.40 || $weighing["gradeSum"] == 1 || $weighing["gradeSum"] == 99.99) {
							$rowsGrade .= "<td>CUMPLE</td>";
							$cumple++;
						} else {
							$rowsGrade .= "<td>NO CUMPLE</td>";
							$noCumple++;
						}
					}
				} else {
					$rowsGrade .= "<td>NO CUMPLE</td>";
					$rowsGrade .= "<td>NO CUMPLE</td>";
					$rowsGrade .= "<td>NO CUMPLE</td>";
					$noCumple += 3;
				}
				//-----------------------------------------------------------------------------

				$total = $noCumple + $cumple;
				$porcentaje = (round(((100 / $total) * $cumple), 2));
				if ($total > 0) {
					echo $fila . "" . rellenar($contador) . " " . $rowsGrade . "<td>" . $porcentaje . "%</td></tr>";
				} else {
					echo $fila . "" . rellenar($contador) . "<td>0%</td></tr>";
				}
			}
		}
	echo "</table>";
}
?>