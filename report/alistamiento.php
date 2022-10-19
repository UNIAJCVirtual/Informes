<?php
include_once("../models/alistamiento_model.php");
header('Content-Type: text/html; charset=UTF-8');

/*

@fuction: Ordenar
@description: El metodo se encarga de comparar el porcentaje de un curso con en anterior, los cuales estan en un array de objectos.
@param: object
@param: object
@return: int : -1 si el curso1 es mayor que el curso2, 1 si el curso1 es menor que el curso2 y 0 si son iguales.
@author:	José David Lamilla A.
@version	1.0

*/

function ordenar($curso1, $curso2)
{
   if ($curso1->{'porcentaje'} > $curso2->{'porcentaje'}){
	return -1;
   }      
   else if ($curso1->{'porcentaje'}<$curso2->{'porcentaje'}){
	return 1;
   }
   return 0;
}

/*
@fuction: validarEmail
@description: El metodo se encarga de validar si en el contenido de la información del tutor tiene un correo valido.
@param: String
@param: String
@return: Boolean : indicando true si es valido y false si no es valido.
@author:	José David Lamilla A.
@version	2.0
*/

function validarEmail($cont, $e)
{
	$flag = false;
	$email='';
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
			}else if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				return true;
			}else {
				return false;
			}
		} else {
			return false;
		}
	} else {
		return false;
	}
}

/*
@fuction:quitar_tildes
@description: Permite quitarle las tildes a cualquier variable de tipo String
@param: String
@return: String : retorna una cadena sin tildes. 
@author:	José David Lamilla A.
@version	2.0
*/

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
	$fechaInicioP1 = strtotime(date("Y")."-02-01 00:00:00",time());
	$fechaFinalP1 = strtotime(date("Y")."-07-15 00:00:00",time());
	$fechaInicioP2 = strtotime(date("Y")."-08-01 00:00:00",time());
	$fechaFinalP2 = strtotime(date("Y")."-12-15 00:00:00",time());
	$vector_curso = [];
	$categoriesResult = Categories(implode(",", $program));
	$verde=0;
	$amarillo=0;
	$rojoClaro=0;
	$rojoOscuro=0;

	foreach ($categoriesResult as $val) {

		$result = NameCategory($val['id']);
		$semester = $result["name"];
		$program = Program($result['parent']);
		$result = StatisticsInformation($val['id']);

		while ($columna = $result->fetch_assoc()) {

			$curso = new alistamiento(); 
			$email = trim(strtolower($columna['email']));
			$resultContenido = content($columna['course_id']);

			$curso->setIdUser($columna['user_id']);
			$curso->setNombre(ucwords(strtolower($columna['firstname'])) . " " . ucwords(strtolower($columna['lastname'])));
			$curso->setCorreo($columna['email']);
			$curso->setPrograma($program);
			$curso->setSemestre($semester);
			$curso->setIdCurso($columna['course_id']);
			$curso->setNombreCurso($columna['course_fullname']);
			$noCumple = 0;
			$cumple = 0;
			while ($resultInformacion = $resultContenido->fetch_assoc()) {
				$contenidoName = strtolower($resultInformacion['page_name']);
				$contenido = strtolower(quitar_tildes($resultInformacion['page_content']));
				$idsection = $resultInformacion['section_id'];
				
						if ($idsection == 0) {

							//Req. 1 - Información del docente
							$nombreCompleto = strtolower($columna['firstname'])." ".strtolower($columna['lastname']);
							$Nombre =explode(" ",$nombreCompleto);
							$name=false;
							foreach ($Nombre as $valor) {
								if ($valor != null) {
									if ((strpos($contenidoName, $valor))) {
										$curso->setNombreProfesor("CUMPLE");
										$cumple++;
										break;
									}else{
										$name=true;
									}
								}
							}
							if($name){
								$curso->setNombreProfesor("NO CUMPLE");
								$noCumple++;
							}
							// Req. 2 - Información de contacto (email)
							$validar = strpos($contenido, $email);
							if ($validar !== false) {
								$curso->setCorreoProfesor("CUMPLE");
								$cumple++;
							} else {
								if (validarEmail($contenido, $email, $columna['course_fullname'])) {
									$curso->setCorreoProfesor("CUMPLE");
									$cumple++;
								} else {
									$curso->setCorreoProfesor("NO CUMPLE");
									$noCumple++;
								}
							}
							//Req. 3 - Horario de atención
							if ((strpos($contenido, 'indicar las horas de atencion que tendra para sus estudiantes') !== false)) {
								$curso->setHorarioAtencion("NO CUMPLE");
								$noCumple++;
							} else  if (
								(strpos($contenido, 'lunes') !== false) ||
								(strpos($contenido, 'martes') !== false) ||
								(strpos($contenido, 'miercoles') !== false) ||
								(strpos($contenido, 'jueves') !== false) ||
								(strpos($contenido, 'viernes') !== false) ||
								(strpos($contenido, 'sabado') !== false) ||
								(strpos($contenido, 'domingo') !== false)
							) {
								$curso->setHorarioAtencion("CUMPLE");
								$cumple++;
							} else {
								$curso->setHorarioAtencion("NO CUMPLE");
								$noCumple++;
							}
							//Req. 4 - Fotografia del Profesor
							if ((strpos($contenido, 'insertar foto de tamaño 200')) !== false) {
								$curso->setFotografia("NO CUMPLE");
								
								$noCumple++;
							} else {
								$curso->setFotografia("CUMPLE");
								$cumple++;
							}

							// validacion foro consulta
							$resultFC = forum($columna['course_id']);

							if ($resultFC->num_rows > 0) {
								$resultFC = mysqli_fetch_array($resultFC);
								$discussions = forumDiscussions($resultFC["id"]);
								$discussions = mysqli_fetch_array($discussions);
								if ($discussions["dis"] > 0) {
									$curso->setForoConsulta("CUMPLE");
									$cumple++;
								} else {
									$curso->setForoConsulta("NO CUMPLE");
									$noCumple++;
								}
							} else {
								$curso->setForoConsulta("NO CUMPLE");
								$noCumple++;
							}
						} else{
							// validacion de unidades
							$sumarycon = $resultInformacion['summary'];

							$resultValidacion = summary($idsection, $columna['course_id']);

							if ($resultValidacion->num_rows > 0) {
								$columnaval = mysqli_fetch_array($resultValidacion);
								$contadorval = $columnaval['contador'];
								$contadorval = (int) $contadorval;
								$section = $columnaval['section'];

							if ((strpos($sumarycon, 'DD/MM/AAAA')) !== false) {
								$curso->unidades[]= "NO CUMPLE";
								$noCumple++;
								
								} else {
									$curso->unidades[]= "CUMPLE";
								$cumple++;
								}
							}
						}
			}

			$avanceFormativo1 = gradeItems($columna['course_id'], "AVANCE FORMATIVO 1");
			$rowsAvanceFormativo1 = $avanceFormativo1->num_rows;

			$avanceFormativo2 = gradeItems($columna['course_id'], "AVANCE FORMATIVO 2");
			$rowsAvanceFormativo2 = $avanceFormativo2->num_rows;

			$avanceFormativo3 = gradeItems($columna['course_id'], "AVANCE FORMATIVO 3");
			$rowsAvanceFormativo3 = $avanceFormativo3->num_rows;

			

			//Revisar libro de calificaciones categoría Avance formativo 1------------------------	

			if ($rowsAvanceFormativo1 > 0) {

				$curso->setAF01Actividades("CUMPLE");
				
				$gc = mysqli_fetch_array($avanceFormativo1);
				$weighingGrade = weighing($columna['course_id'], $gc["idc"]);
				$weighing = mysqli_fetch_array($weighingGrade);
				$noCumpleDispo=0;
				$CumpleDispo=0;

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
						$cutoffdate = intval($data["cutoffdate"]);

						if ($dateSubmissions > $fechaInicioP1 && $duedate > $fechaInicioP1 && $cutoffdate < $fechaFinalP1 
						 || $dateSubmissions > $fechaInicioP2 && $duedate > $fechaInicioP2 && $cutoffdate < $fechaFinalP2 ) {
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
						if ($timeOpen > $fechaInicioP1 && $timeClose < $fechaFinalP1 ||
							$timeOpen > $fechaInicioP2 && $timeClose < $fechaFinalP2) {
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
						if ($duedate > $fechaInicioP1 && $cutoffdate < $fechaFinalP1 ||
							$duedate > $fechaInicioP2 && $cutoffdate < $fechaFinalP2) {
							$CumpleDispo++;
						} else {
							$noCumpleDispo++;
						}
					}
				}
			}
		}
		if($noCumpleDispo>0){
			$curso->setAF01Disponibilidad("NO CUMPLE");
			$noCumple++;
		}else{
			$curso->setAF01Disponibilidad("CUMPLE");
			$cumple++;
		}
		//------Ponderación de las actividades  Avance formativo 1------------------------------------------------------------------------
				
				if ($weighing["gradeSum"] > 100 || $weighing["gradeSum"] == 0) {
					$curso->setAF01Ponderaciones("NO CUMPLE");
					$noCumple++;
				} else {
					if ($weighing["gradeSum"] == 100 || $weighing["gradeSum"] == 30 || $weighing["gradeSum"] == 0.30 || $weighing["gradeSum"] == 1) {
						$curso->setAF01Ponderaciones("CUMPLE");
						$cumple++;
					} else {
						$curso->setAF01Ponderaciones("NO CUMPLE");
						$noCumple++;
					}
				}

			}
			else {
				$curso->setAF01Actividades("NO CUMPLE");
				$curso->setAF01Ponderaciones("NO CUMPLE");
				$curso->setAF01Disponibilidad("NO CUMPLE");
				$noCumple+=3;
			}

			//Revisar libro de calificaciones categoría Avance formativo 2------------------------	

			if ($rowsAvanceFormativo2 > 0) {
				$curso->setAF02Actividades("CUMPLE");
				$gc = mysqli_fetch_array($avanceFormativo2);
				$weighingGrade = weighing($columna['course_id'], $gc["idc"]);
				$weighing = mysqli_fetch_array($weighingGrade);
				$noCumpleDispo=0;
				$CumpleDispo=0;

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

						if ($dateSubmissions > $fechaInicioP1 && $duedate > $fechaInicioP1 && $cutoffdate < $fechaFinalP1 
						 || $dateSubmissions > $fechaInicioP2 && $duedate > $fechaInicioP2 && $cutoffdate < $fechaFinalP2 ) {
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
						if ($timeOpen > $fechaInicioP1 && $timeClose < $fechaFinalP1 ||
							$timeOpen > $fechaInicioP2 && $timeClose < $fechaFinalP2) {
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
						if ($duedate > $fechaInicioP1 && $cutoffdate < $fechaFinalP1 ||
							$duedate > $fechaInicioP2 && $cutoffdate < $fechaFinalP2) {
							$CumpleDispo++;
						} else {
							$noCumpleDispo++;
						}
					}
				}
			}
		}
		if($noCumpleDispo>0){
			$curso->setAF02Disponibilidad("NO CUMPLE");
			$noCumple++;
		}else{
			$curso->setAF02Disponibilidad("CUMPLE");
			$cumple++;
		}
		//------Ponderación de las actividades  Avance formativo 2------------------------------------------------------------------------
				
				if ($weighing["gradeSum"] > 100 || $weighing["gradeSum"] == 0) {
					$curso->setAF02Ponderaciones("NO CUMPLE");
					$noCumple++;
				} else {
					if ($weighing["gradeSum"] == 100 || $weighing["gradeSum"] == 30 || $weighing["gradeSum"] == 0.30 || $weighing["gradeSum"] == 1) {
						$curso->setAF02Ponderaciones("CUMPLE");
						$cumple++;
					} else {
						$curso->setAF02Ponderaciones("NO CUMPLE");
						$noCumple++;
					}
				}
			}else {
				$curso->setAF02Actividades("NO CUMPLE");
				$curso->setAF02Ponderaciones("NO CUMPLE");
				$curso->setAF02Disponibilidad("NO CUMPLE");
				$noCumple+=3;
			}

		//Revisar libro de calificaciones categoría Avance formativo 3------------------------	

			if ($rowsAvanceFormativo3 > 0) {
				$curso->setAF03Actividades("CUMPLE");
				$gc = mysqli_fetch_array($avanceFormativo3);
				$weighingGrade = weighing($columna['course_id'], $gc["idc"]);
				$weighing = mysqli_fetch_array($weighingGrade);
				$noCumpleDispo=0;
				$CumpleDispo=0;

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

						if ($dateSubmissions > $fechaInicioP1 && $duedate > $fechaInicioP1 && $cutoffdate < $fechaFinalP1 
						 || $dateSubmissions > $fechaInicioP2 && $duedate > $fechaInicioP2 && $cutoffdate < $fechaFinalP2 ) {
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
						if ($timeOpen > $fechaInicioP1 && $timeClose < $fechaFinalP1 ||
							$timeOpen > $fechaInicioP2 && $timeClose < $fechaFinalP2) {
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
						if ($duedate > $fechaInicioP1 && $cutoffdate < $fechaFinalP1 ||
							$duedate > $fechaInicioP2 && $cutoffdate < $fechaFinalP2) {
							$CumpleDispo++;
						} else {
							$noCumpleDispo++;
						}
					}
				}
			}
		}
		if($noCumpleDispo>0){
			$curso->setAF03Disponibilidad("NO CUMPLE");
			$noCumple++;
		}else{
			$curso->setAF03Disponibilidad("CUMPLE");
			$cumple++;
		}
		//------Ponderación de las actividades  Avance formativo 3------------------------------------------------------------------------
		

				if ($weighing["gradeSum"] > 100 || $weighing["gradeSum"] == 0) {
					$curso->setAF03Ponderaciones("NO CUMPLE");
					$noCumple++;
				} else {
					if ($weighing["gradeSum"] == 100 || $weighing["gradeSum"] == 40 || $weighing["gradeSum"] == 0.40 || $weighing["gradeSum"] == 1) {
						$curso->setAF03Ponderaciones("CUMPLE");
						$cumple++;
					} else {
						$curso->setAF03Ponderaciones("NO CUMPLE");
						$noCumple++;
					}
				}
			} else {
				$curso->setAF03Actividades("NO CUMPLE");
				$curso->setAF03Ponderaciones("NO CUMPLE");
				$curso->setAF03Disponibilidad("NO CUMPLE");
				$noCumple+=3;
			}
		//-----------------------------------------------------------------------------

			$total = $noCumple+$cumple;
			$porcentaje = (round(((100 / $total) * $cumple), 2));
			$curso->setPorcentaje($porcentaje);
			$vector_curso[] = $curso;
		}
	}
	usort($vector_curso,'ordenar');

	echo("
	<table class='table'>
		<tr class='td1'>
			<td class='td1' rowspan='2'>ID user</td>
			<td class='td1' rowspan='2'>Nombre</td>
			<td class='td1' rowspan='2'>Correo</td>
			<td class='td1' rowspan='2'>Programa</td>
			<td class='td1' rowspan='2'>Semestre</td>
			<td class='td1' rowspan='2'>Curso</td>
			<td class='td1' rowspan='2'>Nombre del curso</td>
			<td class='td1' colspan='4'>Presentación del profesor</td>
			<td class='td1' colspan='9'>Foro de consultas y fechas de unidades</td>
			<td class='td1' colspan='3'>Avance formativo 1</td>
			<td class='td1' colspan='3'>Avance formativo 2</td>
			<td class='td1' colspan='3'>Avance formativo 3</td>
			<td class='td1' rowspan='2'>Porcentaje</td>
		</tr>
		<tr class='td1'>
			<td class='td1'>Nombre</td>
			<td class='td1'>Correo</td>
			<td class='td1'>Horario de atención</td>
			<td class='td1'>Fotografía</td>
			<td class='td1'>Foro de consulta</td>
			<td class='td1'>Unidad 1</td>
			<td class='td1'>Unidad 2</td>
			<td class='td1'>Unidad 3</td>
			<td class='td1'>Unidad 4</td>
			<td class='td1'>Unidad 5</td>
			<td class='td1'>Unidad 6</td>
			<td class='td1'>Unidad 7</td>
			<td class='td1'>Unidad 8</td>
			<td class='td1'>Actividades</td>
			<td class='td1'>Disponibilidad</td>
			<td class='td1'>Ponderaciones</td>
			<td class='td1'>Actividades</td>
			<td class='td1'>Disponibilidad</td>
			<td class='td1'>Ponderaciones</td>
			<td class='td1'>Actividades</td>
			<td class='td1'>Disponibilidad</td>
			<td class='td1'>Ponderaciones</td>
		</tr>");
	
	foreach($vector_curso as $curse){
		
		if($curse->getPorcentaje() >= 80 && $curse->getPorcentaje() <= 100){
			//Porcentaje verde
			$verde++;
			$color="tr1";
	
		}else if ($curse->getPorcentaje() >= 51 && $curse->getPorcentaje() <= 79){
			//Porcentaje amarillo
			$amarillo++;
			$color="tr2";
			
		}else if ($curse->getPorcentaje() >= 0 && $curse->getPorcentaje() <= 50){
		//Porcentaje rojo claro
			$rojoClaro++;
			$color="tr3";

		}else if ($curse->getPorcentaje() == 0){
			//Porcentaje rojo claro
			$rojoOscuro++;
			$color="tr4";
		}
		echo("<tr class='".$color."'>
		<td class='".$color."'>".$curse->getIdUser()."</td>
		<td class='".$color."'>".$curse->getNombre()."</td>
		<td class='".$color."'>".$curse->getCorreo()."</td>
		<td class='".$color."'>".$curse->getPrograma()."</td>
		<td class='".$color."'>".$curse->getSemestre()."</td>
		<td class='".$color."'>".$curse->getIdCurso()."</td>
		<td class='".$color."'>".$curse->getNombreCurso()."</td>
		<td class='".$color."'>".$curse->getNombreProfesor()."</td>
		<td class='".$color."'>".$curse->getCorreoProfesor()."</td>
		<td class='".$color."'>".$curse->getHorarioAtencion()."</td>
		<td class='".$color."'>".$curse->getFotografia()."</td>
		<td class='".$color."'>".$curse->getForoConsulta()."</td>
		");
		$contar = count($curse->unidades);
		for ($i = 0; $i <= 7; $i++) {
			if($contar > 0){
				echo("<td class='".$color."'>".$curse->unidades[$i]."</td>");
			}else{
				echo("<td class='".$color."'>NO APLICA</td>");
			}
			$contar--;
		}

		echo("
		<td class='".$color."'>".$curse->getAF01Actividades()."</td>
		<td class='".$color."'>".$curse->getAF01Disponibilidad()."</td>
		<td class='".$color."'>".$curse->getAF01Ponderaciones()."</td>
		<td class='".$color."'>".$curse->getAF02Actividades()."</td>
		<td class='".$color."'>".$curse->getAF02Disponibilidad()."</td>
		<td class='".$color."'>".$curse->getAF02Ponderaciones()."</td>
		<td class='".$color."'>".$curse->getAF03Actividades()."</td>			
		<td class='".$color."'>".$curse->getAF03Disponibilidad()."</td>
		<td class='".$color."'>".$curse->getAF03Ponderaciones()."</td>
		<td class='".$color."'>".$curse->getPorcentaje()."%"."</td>
		</tr>");
	}


	$sum=$verde+$amarillo+$rojoClaro+$rojoOscuro;
	
	echo"
	</table>
	<br>

	<table class='tables'>

		<tr class='td1'>
		<td class='td1' colspan='2'>Porcentajes</th>
		<td class='td1' colspan='2'>Cantidad de cursos</th>
		</tr>

		<tr class='tr1'>
		<td class='tr1' colspan='2' > 100% - 80% </td>
		<td class='tr1' colspan='2'>".$verde."</td>
		</tr>
		<tr class='tr2'>
		<td class='tr2' colspan='2'> 79% - 51% </td>
		<td class='tr2' colspan='2'>".$amarillo."</td>
		</tr>
		<tr class='tr3'>
		<td class='tr3' colspan='2'> 50% - 1% </td>
		<td class='tr3' colspan='2'>".$rojoClaro."</td>
		</tr>
		<tr class='tr4'>
		<td class='tr4' colspan='2'> Sin modificaciones </td>
		<td class='tr4' colspan='2'>".$rojoOscuro."</td>
		</tr>
		<tr class='td1'>
		<td class='td1' colspan='2'> Total de cursos </td>
		<td class='td1' colspan='2'>".$sum."</td>
		</tr>
	</table>
	";
	
	
}
?>