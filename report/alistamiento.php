<?php
include_once("../models/alistamiento_model.php");
include_once("../helpers/strings.php");
include_once("../services/reportRequest.php");
header('Content-Type: text/html; charset=UTF-8');

/*
@Variables publicas
@description: El metodo se encarga de comparar el porcentaje de un course con en anterior, los cuales estan en un array de objectos.
@author:	José David Lamilla A.
@version	1.0
@fecha: 26/10/2022
*/
$fechaInicio;
$fechaFinal;
$countFails = 0;
$countSucces = 0;
//----------------------------------------------

/*
@function name: uniqueElements.
@description: El metodo se encarga de recibir un array, donde quitara los elementos duplicados del mismo.
@parameters: array
@return: awd (array without duplicates) : El array sin elementos duplicados 
@author:	José David Lamilla A.
@version	1.0
@fecha: 26/10/2022
*/
function uniqueElements($array)
{
	$awd = [];
	foreach ($array as $element) {
		if (!in_array($element, $awd)) {
			$awd[] = $element;
		}
	}
	return $awd;
}

/*
@function name: removeTildes.
@description: Convierte texto en minuscula y quita las tildes y las ñ a cualquier variable de tipo String.
@parameters: String $textInput.
@return: retorna un String sin tildes ni ñ. 
@author:	Julian Alberto Ortz V.
@version	3.0
@fecha: 19/09/2023
*/

function removeTildes(String $textInput)
{
	$notAllowed = array(
		"á", "Á", "é", "É", "í", "Í", "ó", "Ó", "ú", "Ú", "ñ", "Ñ",
	);
	$allowed = array(
		"a", "a", "e", "e", "i", "i", "o", "o", "u", "u", "n", "n",
	);

	// Reemplaza cada carácter acentuado con su contraparte sin acento
	$textOutput = str_replace($notAllowed, $allowed, $textInput);

	return $textOutput;
}

/*
@function name: periodDate
@description: La función busca la fecha de inicio y fecha de finalización del periodo actual, con base
al mes y día actualmente. Finaliza guardando el periodo en unas variables globales.
@return: void
@author:	José David Lamilla A.
@version	2.0
@fecha: 05/12/2022
*/
function periodDate()
{
	$mes = date("m");
	$dia = date("d");

	global $fechaFinal, $fechaInicio;

	if ($mes || $dia) {
		if ($mes == 7) {
			if ($dia < 16) {
				$fechaInicio = strtotime(date("Y") . "-01-01 00:00:00", time());
				$fechaFinal = strtotime(date("Y") . "-07-15 00:00:00", time());
			} else {
				$fechaInicio = strtotime(date("Y") . "-07-16 00:00:00", time());
				$fechaFinal = strtotime(date("Y") . "-12-31 00:00:00", time());
			}
		} elseif ($mes < 7) {
			$fechaInicio = strtotime(date("Y") . "-01-01 00:00:00", time());
			$fechaFinal = strtotime(date("Y") . "-07-15 00:00:00", time());
		} else {
			$fechaInicio = strtotime(date("Y") . "-07-16 00:00:00", time());
			$fechaFinal = strtotime(date("Y") . "-12-31 00:00:00", time());
		}
	} else {
		print('Error en la fecha');
		exit;
	}
}

/*
@function name: nameValidate.
@description: Tiene como finalidad validar si el nombre del profesor es igual al nombre digitado en el inicio del course debajo de la 
etiqueta de profesor.Para esto recibe el nombre del recourse page del inicio del course, el nombre y el apellido del profesor en el usuairo de moodle.
@parameters: String $contentName , String $name , String $lastname
@return:  retorna un String de validación que puede ser $fails o $succes
@author:	José David Lamilla A.
@version	2.1
@fecha: 05/12/2022
*/
function nameValidate(String $contentName, String $fullName)
{

	global $countFails, $countSucces, $fails, $succes, $descriptionName;

	$contentName = strtolower(removeTildes($contentName));
	$fullName = strtolower(removeTildes($fullName));

	// Validar caracteres no válidos
	//$contentName = preg_replace('/[0-9\@\.\:;\,""]+/', '', $contentName);

	$parts = explode(" ", $contentName);
	$partsFullname = explode(" ", $fullName);

	for ($i = 0; $i < count($parts); $i++) {
		$separateName = $parts[$i];
		if (in_array($separateName, $partsFullname)) {
			$countSucces++;
			return $succes;
		}
	}

	$countFails++;
	return $fails;
}

/*
@function name: emailValidate
@description: El metodo se encarga de validar si el correo que esta dentro de la variable content es igual al correo del profesor que tiene en moodle,
si no son iguales envia a un metodo que valida si el correo es valido.
@parameters: String
@return: String : Si cumple o no Cumple.
@author:	José David Lamilla A.
@version	2.0
@fecha: 26/10/2022
*/

function emailValidate($content, $email)
{

	global $countFails, $countSucces, $fails, $succes;


	if (strpos($content, $email)) {
		$countSucces++;
		return $succes;
	} else {

		if (confirmarEmail($content, $email)) {
			$countSucces++;
			return $succes;
		} else {
			$countFails++;
			return $fails;
		}
	}
}
/*
@function name: emailValidate
@description: El metodo se encarga de validar si en el contenido de la información del tutor tiene un correo valido.
Nota: El uso de el email del profesor en plataforma se estaba usando para verifcar si el del recurso era identico al registrado.
@parameters: String
@return: Boolean : indicando true si es valido y false si no es valido.
@author:	José David Lamilla A.
@version	2.0
@fecha: 26/10/2022
*/

function confirmarEmail($cont, $e)
{

	$flag = false;
	$email = '';
	$buscar = array(chr(13) . chr(10), "\r\n", "\n", "\r");
	$reemplazar = array("", "", "", "");
	$sin = str_replace($buscar, $reemplazar, strip_tags($cont));
	$arrayContenido = explode(":", $sin);
	if (count($arrayContenido) > 0) {
		for ($i = 0; $i < count($arrayContenido); $i++) {
			if (substr_count($arrayContenido[$i], "@") >= 1) {
				$email = $arrayContenido[$i];
				$flag = true;
				break;
			}
		}
		/*  Validación del correo en el item (presentación) y en la plataforma (Moodle)
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
		}*/
		if ($flag) {
			return true;
		} else {
			return false;
		}
	}
}

/*
@function name: validateOpeningHours
@description: El metodo se encarga de validar si en la variable content existe cualquiera de los días de la semana.
@parameters: String
@return: String :  Si CUMPLE o no NO CUMPLE.
@author:	Julian Alberto Ortiz V.
@version	3.0
@fecha: 19/09/2023
*/

function validateOpeningHours($content)
{

	global $countFails, $countSucces, $fails, $succes;

	$regex = "/(lunes|martes|miercoles|jueves|viernes|sabado|sabados|domingo|domingos)/i";

	if ((strpos($content, 'indicar las horas de atencion que tendra para sus estudiantes') !== false)) {
		$countFails++;
		return $fails;
	} else  if (!preg_match($regex, $content)) {
		$countFails++;
		return $fails;
	} else {
		$countSucces++;
		return $succes;
	}
}

//---------------------------------------

function validarFotografia($content)
{

	global $countFails, $countSucces, $fails, $succes;

	if ((strpos($content, 'insertar foto de tamaño 200')) !== false) {
		$countFails++;
		return $fails;
	} else {
		// return hasImage($content);
		$countSucces++;
		return $succes;
	}
}

function hasImage($content)
{
	global $countFails, $countSucces, $fails, $succes;

	// Busca el atributo `src` de un elemento `img`.
	$matches = [];
	preg_match_all('/<img\s+src="(.*?)"/i', $content, $matches);

	// Verifica si la URL de la imagen es válida.
	foreach ($matches[1] as $url) {
		if (!filter_var($url, FILTER_VALIDATE_URL)) {
			$countFails++;
			return $fails;
		}
	}
	// Hay una imagen en la cadena de texto.
	$countSucces++;
	return $succes;
}


//---------------------------------------

function validarForoConsultas($idcourse)
{

	global $countFails, $countSucces, $fails, $succes;

	$resultFC = forum($idcourse);

	if ($resultFC->num_rows > 0) {

		$resultFC = mysqli_fetch_array($resultFC);
		$discussions = forumDiscussions($resultFC["id"]);
		$discussions = mysqli_fetch_array($discussions);

		if ($discussions["dis"] > 0) {
			$countSucces++;
			return $succes;
		} else {
			$countFails++;
			return $fails;
		}
	} else {
		$countFails++;
		return $fails;
	}
}

//---------------------------------------

function validateDateUnits($sumarycon)
{

	global $countFails, $countSucces, $fails, $succes;

	if ((strpos($sumarycon, 'DD/MM/AAAA')) !== false) {
		$countFails++;
		return $fails;
	} else {
		// TODO: Validar si la fecha es correcta : Año, mes.
		$countSucces++;
		return $succes;
	}
}

//---------------------------------------

function validarActividadesCategoria($itemsCategoria)
{

	global $countSucces, $countFails;

	if ($itemsCategoria->num_rows > 0) {

		$countSucces++;
		return true;
	} else {

		$countFails += 3;
		return false;
	}
}
//---------------------------------------

// function validarDisponibilidadCategoria($itemsCategoria){
	
// 	global $countFails, $countSucces, $fechaInicio, $fechaFinal, $fails, $succes;

// 	$countFailsDispo=0;
// 	$countSuccesDispo=0;

// 	foreach ($itemsCategoria as $record) {
// 	if ($record["itemmodule"] == "assign") {
// 		$dataAssign = dataAssign($record["iteminstance"]);
// 		foreach ($dataAssign as $data) {
// 			if ($data["duedate"] == 0 || $data["allowsubmissionsfromdate"] == 0 || $data["cutoffdate"] == 0) {
// 				$countFailsDispo++;
// 			} else {
// 				//Variable de Permitir entregas desde
// 				$duedate = $data["duedate"];
// 				//Variable de Fecha de entrega
// 				$dateSubmissions = $data["allowsubmissionsfromdate"];
// 				//Variable de Fecha límite
// 				$cutoffdate = intval($data["cutoffdate"]);
// 				if ($dateSubmissions > $fechaInicio && $duedate > $fechaInicio && $cutoffdate < $fechaFinal) {
// 					$countSuccesDispo++;
// 				} else {
// 					$countFailsDispo++;
// 				}
// 			}
// 		}
// 	} elseif ($record["itemmodule"] == "quiz") {
// 		$dataQuiz = dataQuiz($record["iteminstance"]);
	
// 		foreach ($dataQuiz as $data) {
// 			if ($data["timeopen"] == 0 || $data["timeclose"] == 0) {
// 				$countFailsDispo++;
// 			} else {
// 				//la variable timeOpen significa la fecha Abrir cuestionario
// 				$timeOpen = $data["timeopen"];
// 				//la variable timeClose significa la fecha cerrar cuestionario
// 				$timeClose = $data["timeclose"];
// 				if ($timeOpen > $fechaInicio && $timeClose < $fechaFinal) {
// 					$countSuccesDispo++;
// 				} else {
// 					$countFailsDispo++;
// 				}
// 			}
// 		}
// 	} elseif ($record["itemmodule"] == "forum") {

// 		$dataForum = dataForum($record["iteminstance"]);
	
// 		foreach ($dataForum as $data) {
// 			if ($data["duedate"] == 0 || $data["cutoffdate"] == 0) {
// 				$countFailsDispo++;
// 			} else {
// 				//la variable duedate significa Fecha de entrega
// 				$duedate = $data["duedate"];
// 				//la variable cutoffdate significa Fecha límite
// 				$cutoffdate = $data["cutoffdate"];
// 				if ($duedate > $fechaInicio && $cutoffdate < $fechaFinal) {
// 					$countSuccesDispo++;
// 				} else {
// 					$countFailsDispo++;
// 				}
// 			}
// 		}
// 	}
// 	}
// 	if($countFailsDispo>0){
// 		$countFails++;
// 		return $fails;
// 	}else{
// 		$countSucces++;
// 		return $succes;
// 	}
// }
//---------------------------------------

function validarPonderacionCategoria($weighingGrade, $tipoCategoria)
{
	global $countFails, $countSucces, $fails, $succes;

	if ($weighingGrade > 100 || $weighingGrade == 0) {

		$countFails++;
		return $fails;
	} elseif ($weighingGrade == 100 || $weighingGrade == 1 || $weighingGrade == 99.99 || $weighingGrade == 99.9) {

		$countSucces++;
		return $succes;
	} elseif ($tipoCategoria == 1 || $tipoCategoria == 2) {
		if ($weighingGrade == 30 || $weighingGrade == 0.30 || $weighingGrade == 30.0 || $weighingGrade == 100 ) {

			$countSucces++;
			return $succes;
		} else {

			$countFails++;
			return $fails;
		}
	} elseif ($tipoCategoria == 3) {

		if ($weighingGrade == 40 || $weighingGrade == 0.40 || $weighingGrade == 40.0) {

			$countSucces++;
			return $succes;
		} else {

			$countFails++;
			return $fails;
		}
	}
}

//---------------------------------------

/*
@function name:
@description:
@parameters: 
@return: int : 
@author:	José David Lamilla A.
@version	2.0
*/

function enlistmentReport($program, $semester)
{
	periodDate();
	date_default_timezone_set("America/Bogota");
	$fecha = date("Y-m-d H:i:s");
	global $countFails, $countSucces, $ac1, $ac2, $ac3, $fails, $succes, $notApply, $pageId, $hidden, $notExist;
	$green = 0;
	$yellow = 0;
	$lightRed = 0;
	$darkRed = 0;
	$vector_curse = [];
	$vector_idCurse = [];
	$semestersResult = Semesters(implode(",", $program));


	foreach ($semestersResult as $semester) {

		$programName = ProgramsName($semester["parent"]);
		$semesterName = $semester["name"];
		$coursesInformation = CoursesInformation($semester["id"]);

		while ($courseInfo = $coursesInformation->fetch_assoc()) {

			$course = new alistamiento();
			$teachersNames = "";
			$teachersEmails = "";
			$teachersUsersIds = "";
			$teachersUserDoc = "";

			//la variable requerida en la función Usersquantity es el rol que vamos a buscar 3 Profesor
			$teachers = Usersquantity($courseInfo['course_id'], 3);

			while ($teacher = $teachers->fetch_assoc()) {
				if ($teachers->num_rows == 1) {
					$teachersNames = ucwords(mb_strtolower($teacher['firstname'], 'UTF-8')) . " " . ucwords(mb_strtolower($teacher['lastname'], 'UTF-8'));
					$teachersEmails = mb_strtolower($teacher['email'], 'UTF-8');
					$teachersUsersIds = mb_strtolower($teacher['user_id'], 'UTF-8');
					$teachersUserDoc = $teacher['user_doc'];
				} else {
					$teachersNames .= ucwords(mb_strtolower($teacher['firstname'], 'UTF-8')) . " " . ucwords(mb_strtolower($teacher['lastname'], 'UTF-8')) . " <br> ";
					$teachersEmails .= mb_strtolower($teacher['email'], 'UTF-8') . " <br> ";
					$teachersUsersIds .= mb_strtolower($teacher['user_id'], 'UTF-8') . " <br> ";
					$teachersUserDoc = $teacher['user_doc'];
				}
			}

			$course->setIdUser($teachersUsersIds);
			$course->setDocUser($teachersUserDoc);
			$course->setNombre($teachersNames);
			$course->setCorreo($teachersEmails);
			$course->setPrograma($programName);
			$course->setSemestre($semesterName);
			$course->setIdcurso($courseInfo['course_id']);
			$course->setNombreCurso($courseInfo['course_name']);

			$group = explode("*", $courseInfo["course_name"]);
			$course->setGrupo($group[count($group) - 1]);
			$code = explode($course->getGrupo(), $courseInfo['course_code']);
			$course->setcodigo($code[count($group) - 1]);

			$vector_idCurse[] = $course->getIdCurso();
			$countFails = 0;
			$countSucces = 0;


			// Resive el id de la pagina: DP01
			$resultContentPage = contentPageId($courseInfo['course_id'], $pageId);

			$page = $resultContentPage->fetch_assoc();

			if (is_array($page)) {
				$contenido = strtolower(removeTildes($page['content']));

				// Req. 1 - Validar si existe la pagina de Información del profesor

				// Req. 2 - Validar el nombre del profesor
				$course->setNombreProfesor(nameValidate($page['name'], $teachersNames));
				// Req. 3 - Validar el correo del profesor
				$course->setCorreoProfesor(emailValidate($contenido, $teachersEmails));

				//Req. 3 - Validar el Horario de atención
				$course->setHorarioAtencion(validateOpeningHours($contenido));
				//Req. 4 - Validar la fotografia del profesor
				$course->setFotografia(validarFotografia($contenido));
				$prueba = $contenido;
			} else {
				$course->setNombreProfesor($notExist);
				$course->setCorreoProfesor($notExist);
				$course->setHorarioAtencion($notExist);
				$course->setFotografia($notExist);
				$countFails += 4;
			}
			// echo $prueba;


			// hasImage($prueba);
			// echo "___";

			//Req. 5 validacion foro consulta
			$course->setForoConsulta(validarForoConsultas($courseInfo['course_id']));


			//Req. 6 validacion de las fechas de inicio y finalización de las unidades
			$resultContentUnits = contentUnits($courseInfo['course_id']);
			while ($unit = $resultContentUnits->fetch_assoc()) {

				$course->unidades[] = ($unit['visible'] == 1) ? validateDateUnits($unit['summary']) : $notApply;
			}

			$itemsC1 = gradeItems($courseInfo['course_id'], $ac1, 'AF01', 'af1');
			$itemsC2 = gradeItems($courseInfo['course_id'], $ac2, 'AF02', 'af2');
			$itemsC3 = gradeItems($courseInfo['course_id'], $ac3, 'AF03', 'af3');


			//-------------------------------VALIDAR C1----------------

			//Req. 7 validar si existen actividades dentro de la categoria C1


			if (validarActividadesCategoria($itemsC1)) {
				// echo "AV01";
				$course->setAF01Actividades($succes);

				//Req. 8 validar la disponibilidad de las actividades dentro de la categoria AF01

				// $course->setAF01Disponibilidad(validarDisponibilidadCategoria($itemsC1));

				//Req. 9 validar las ponderaciones de las actividades dentro de la categoria AF01

				$course->setAF01Ponderaciones(validarPonderacionCategoria(weighing($courseInfo['course_id'], $ac1, 'AF01', 'af1'), 1));
			} else {
				$course->setAF01Actividades($fails);
				// $course->setAF01Disponibilidad($fails);
				$course->setAF01Ponderaciones($fails);
			}

			//-------------------------------VALIDAR C2----------------

			//Req. 10 validar si existen actividades dentro de la categoria AF02

			if (validarActividadesCategoria($itemsC2)) {
				// echo "AV02";
				$course->setAF02Actividades($succes);

				//Req. 11 validar la disponibilidad de las actividades dentro de la categoria AF02

				// $course->setAF02Disponibilidad(validarDisponibilidadCategoria($itemsC2));

				//Req. 12 validar las ponderaciones de las actividades dentro de la categoria AF02

				$course->setAF02Ponderaciones(validarPonderacionCategoria(weighing($courseInfo['course_id'], $ac2, 'AF02', 'af2'), 2));
			} else {
				$course->setAF02Actividades($fails);
				// $course->setAF02Disponibilidad($fails);
				$course->setAF02Ponderaciones($fails);
			}

			//-------------------------------VALIDAR C3----------------

			//Req. 13 validar si existen actividades dentro de la categoria AF03


			if (validarActividadesCategoria($itemsC3)) {

				$course->setAF03Actividades($succes);

				//Req. 14 validar la disponibilidad de las actividades dentro de la categoria AF03

				// $course->setAF03Disponibilidad(validarDisponibilidadCategoria($itemsC3));

				//Req. 15 validar las ponderaciones de las actividades dentro de la categoria AF03

				$course->setAF03Ponderaciones(validarPonderacionCategoria(weighing($courseInfo['course_id'], $ac3, 'AF03', 'af3'), 3));
			} else {
				$course->setAF03Actividades($fails);
				// $course->setAF03Disponibilidad($fails);
				$course->setAF03Ponderaciones($fails);
			}
			$total = $countFails + $countSucces;
			$percentage = (round(((100 / $total) * $countSucces)));
			$course->setPorcentaje($percentage);
			$vector_curse[] = $course;
		}
	}
	echo ("
	<div class='title-estadist'>
			<h2>ALISTAMIENTO</h2>
	</div>
	<table id='example' class='table table-striped table-bordered' cellspacing='0' width='100%'>
	<thead class='td1 thead-table' nowrap>
		<td class='td1' nowrap >Fecha</td>
		<td class='td1' nowrap >ID Usuario</td>
		<td class='td1' nowrap >Documento</td>
		<td class='td1' nowrap >Nombre completo</td>
		<td class='td1' nowrap >Correo electrónico</td>
		<td class='td1' nowrap >Programa</td>
		<td class='td1' nowrap >ID Curso</td>
		<td class='td1' nowrap >Codigo</td>
		<td class='td1' nowrap >Semestre</td>
		<td class='td1' nowrap >Grupo</td>
		<td class='td1' nowrap >Nombre del curso</td>
		<td class='td1' nowrap >Nombre del profesor</td>
		<td class='td1' nowrap >Correo</td>
		<td class='td1' nowrap >Horario de atención</td>
		<td class='td1' nowrap >Fotografía</td>
		<td class='td1' nowrap >Foro de consulta</td>
		<td class='td1' nowrap >Fecha de inicio y Finalización Unidad 1</td>
		<td class='td1' nowrap >Fecha de inicio y Finalización Unidad 2</td>
		<td class='td1' nowrap >Fecha de inicio y Finalización Unidad 3</td>
		<td class='td1' nowrap >Fecha de inicio y Finalización Unidad 4</td>
		<td class='td1' nowrap >Fecha de inicio y Finalización Unidad 5</td>
		<td class='td1' nowrap >Fecha de inicio y Finalización Unidad 6</td>
		<td class='td1' nowrap >Fecha de inicio y Finalización Unidad 7</td>
		<td class='td1' nowrap >Fecha de inicio y Finalización Unidad 8</td>
		<td class='td1' nowrap >" . $ac1 . " Actividades</td>
		<td class='td1' nowrap >" . $ac1 . " Ponderaciones</td>
		<td class='td1' nowrap >" . $ac2 . " Actividades</td>
		<td class='td1' nowrap >" . $ac2 . " Ponderaciones</td>
		<td class='td1' nowrap >" . $ac3 . " Actividades</td>
		<td class='td1' nowrap >" . $ac3 . " Ponderaciones</td>
		<td class='td1' nowrap >Porcentaje</td>
	</thead>");
	foreach ($vector_curse as $curse) {

		if ($curse->getPorcentaje() >= 80 && $curse->getPorcentaje() <= 100) {
			//Porcentaje green$green
			$green++;
			$color = "tr1";
		} else if ($curse->getPorcentaje() >= 51 && $curse->getPorcentaje() <= 79) {
			//Porcentaje yellow
			$yellow++;
			$color = "tr2";
		} else if ($curse->getPorcentaje() >= 0 && $curse->getPorcentaje() <= 50) {
			//Porcentaje rojo claro
			$lightRed++;
			$color = "tr3";
		} else if ($curse->getPorcentaje() == 0) {
			//Porcentaje rojo claro
			$darkRed++;
			$color = "tr4";
		}
		echo ("
		<tr nowrap class='" . $color . "'>
			<td nowrap class='" . $color . "'>" . $fecha . "</td>
			<td nowrap class='" . $color . "'>" . $curse->getIdUser() . "</td>
			<td nowrap class='" . $color . "'>" . $curse->getDocUser() . "</td>
			<td nowrap class='" . $color . "'>" . $curse->getNombre() . "</td>
			<td nowrap class='" . $color . "'>" . $curse->getCorreo() . "</td>
			<td nowrap class='" . $color . "'>" . $curse->getPrograma() . "</td>
			<td nowrap class='" . $color . "'>" . $curse->getIdCurso() . "</td>
			<td nowrap class='" . $color . "'>" . $curse->getCodigo() . "</td>
			<td nowrap class='" . $color . "'>" . $curse->getSemestre() . "</td>
			<td nowrap class='" . $color . "'>" . $curse->getGrupo() . "</td>
			<td nowrap class='" . $color . "'>" . $curse->getNombreCurso() . "</td>
			<td nowrap class='" . $color . "'>" . $curse->getNombreProfesor() . "</td>
			<td nowrap class='" . $color . "'>" . $curse->getCorreoProfesor() . "</td>
			<td nowrap class='" . $color . "'>" . $curse->getHorarioAtencion() . "</td>
			<td nowrap class='" . $color . "'>" . $curse->getFotografia() . "</td>
			<td nowrap class='" . $color . "'>" . $curse->getForoConsulta() . "</td>
		");
		$count = count($curse->unidades);
		for ($i = 0; $i <= 7; $i++) {
			if ($count > 0) {
				echo ("<td class='" . $color . "'>" . $curse->unidades[$i] . "</td>");
			} else {
				echo ("<td class='" . $color . "'>" . $notApply . "</td>");
			}
			$count--;
		}
		$count = 0;
		// i deleted this one <td nowrap class='" . $color . "'>" . $curse->getAF01Disponibilidad() . "</td>

		echo ("
		<td nowrap class='" . $color . "'>" . $curse->getAF01Actividades() . "</td>
		<td nowrap class='" . $color . "'>" . $curse->getAF01Ponderaciones() . "</td>
		<td nowrap class='" . $color . "'>" . $curse->getAF02Actividades() . "</td>
		<td nowrap class='" . $color . "'>" . $curse->getAF02Ponderaciones() . "</td>
		<td nowrap class='" . $color . "'>" . $curse->getAF03Actividades() . "</td>			
		<td nowrap class='" . $color . "'>" . $curse->getAF03Ponderaciones() . "</td>
		<td nowrap class='" . $color . "'>" . $curse->getPorcentaje() . "%" . "</td>
		</tr>");
	}
	$sum = $green + $yellow + $lightRed + $darkRed;
	$cantidadcourses = count(uniqueElements($vector_idCurse));
	$cantidadRepetidos = count($vector_idCurse) - $cantidadcourses;
	echo ("
	</table>
	<div class='container-items-porcent'>
        <div class='item-porcent tr1'><span class='txt-black'>100% - 80% |</span>		<h5>" . $green . "</h5></div>
        <div class='item-porcent tr2'><span class='txt-black'>79% - 51%  |</span>     <h5>" . $yellow . "</h5></div>
        <div class='item-porcent tr3'><span class='txt-black'>50% - 0%   |</span>		<h5>" . $lightRed . "</h5></div>
        <div class='item-porcent tr4'><span class='txt-black'>Sin actividades	|</span><h5>" . $darkRed . "</h5></div>
        <div class='item-porcent td2'><span>Total de courses	|</span><h5>" . $sum . "</h5></div>
		<div class='item-porcent td2'><span>courses Repetidos	|</span><h5>" . $cantidadRepetidos . "</h5></div>
   	</div>
	");
}
