<?php
include_once("../class/curso.php");

//Ordenar array con el método sort

function ordenar($ar1, $ar2)
{
   if ($ar1->{'porcentaje'} > $ar2->{'porcentaje'}){
	return -1;
   }      
   else if ($ar1->{'porcentaje'}<$ar2->{'porcentaje'}){
	return 1;
   }
   return 0;
}
//----------------------------------------------

function validarEmail($cont, $e, $c)
{
	$flag = false;
	$buscar = array(chr(13) . chr(10), "\r\n", "\n", "\r");
	$reemplazar = array("", "", "", "");
	$sin = str_replace($buscar, $reemplazar, strip_tags($cont));
	$arrayContenido = explode(":", $sin);
	if (count($arrayContenido) > 0) {
		$posicion = 0;
		for ($i = 0; $i < count($arrayContenido); $i++) {
			if (substr_count($arrayContenido[$i], "@") == 1) {
				$email = $arrayContenido[$i];
				$flag = true;
				break;
			} else {
				// echo "Error5  ".$e."  --> ".$arrayContenido[$i]."-->$i<br>";					
			}
		}
		if ($flag) {
			if ((strpos($email, $e)) !== false) {
				return true;
			}
			if (substr_count($email, "@") == 1) {
				$var = explode("@", $email);
				if (strlen($var[0]) > 2) {
					$domainsExt = array(".com", ".edu", ".es", ".co", ".org", ".gob", ".mil", ".ws", ".biz", ".cc", ".info", ".tv", ".net", ".pro", ".coop");
					$domainsExt = implode(" ", $domainsExt);
					$validacion = strpos($domainsExt, ".com");
					if ($validacion !== false) {
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
		} else {
			return false;
		}
	} else {
		return false;
	}
}


function quitar_tildes($cadena)
{
	//$cade = utf8_decode($cadena);
	$no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú");
	$permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U",);
	$texto = str_replace($no_permitidas, $permitidas, $cadena);
	return $texto;
}


function enlistmentReport($category, $program, $semester)
{
	include("../database/reportRequest.php");
	
	$vector_curso = [];
	$categoriesResult = Categories(implode(",", $program));

	foreach ($categoriesResult as $val) {

		$result = NameCategory($val['id']);
		$semester = $result["name"];
		$program = Program($result['parent']);
		$result = StatisticsInformation($val['id']);

		while ($columna = $result->fetch_assoc()) {

			$curso = new curso(); 
			$email = trim(strtolower($columna['email']));
			$resultContenido = content($columna['course_id']);
			$cadena = "";

			$curso->setIdUser($columna['user_id']);
			$curso->setNombre(ucwords(strtolower($columna['firstname'])) . " " . ucwords(strtolower($columna['lastname'])));
			$curso->setCorreo($columna['email']);
			$curso->setPrograma($program);
			$curso->setSemestre($semester);
			$curso->setIdCurso($columna['course_id']);
			$curso->setNombreCurso($columna['course_fullname']);

			$contador = 8;
			$noCumple = 0;
			$cumple = 0;

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
							$aNombre = explode(" ", $nombreCompleto);
							$sw = false;
							foreach ($aNombre as $valor) {
								if ($valor != null) {
									if ((strpos($contenidoName, $valor)) !== false) {
										$sw = true;
										$curso->setNombreProfesor("CUMPLE");
										$cumple++;
										break;
									} else if ((strpos($contenido, $valor)) !== false) {
										$sw = true;
										$curso->setNombreProfesor("CUMPLE");

										$cumple++;
										break;
									}
								}
							}
							if ($sw == false) {
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
						} else if ($idsection > 0) {
							// validacion de unidades
							$sumarycon = $resultInformacion['summary'];

							$resultValidacion = summary($idsection, $columna['course_id']);

							if ($resultValidacion->num_rows > 0) {

								$contador--;
								$columnaval = mysqli_fetch_array($resultValidacion);
								$contadorval = $columnaval['contador'];
								$contadorval = (int) $contadorval;
								$section = $columnaval['section'];
								$unidad = $section - 1;
							if ($sectionvisible ==! "0") {

								if ((strpos($sumarycon, 'DD/MM/AAAA')) !== false) {
									if($unidad==8){
										$curso->setFechasUnidad8("NO CUMPLE");
									} elseif ($unidad==7){
										$curso->setFechasUnidad7("NO CUMPLE");
									} elseif ($unidad==6){
										$curso->setFechasUnidad6("NO CUMPLE");
									} elseif ($unidad==5){
										$curso->setFechasUnidad5("NO CUMPLE");
									} elseif ($unidad==4){
										$curso->setFechasUnidad4("NO CUMPLE");
									} elseif ($unidad==3){
										$curso->setFechasUnidad3("NO CUMPLE");
									} elseif ($unidad==2){
										$curso->setFechasUnidad2("NO CUMPLE");
									} elseif($unidad==1){
										$curso->setFechasUnidad1("NO CUMPLE");
									}					
									$noCumple++;
								} else {
									if($unidad==8){
										$curso->setFechasUnidad8("CUMPLE");
									} elseif ($unidad==7){
										$curso->setFechasUnidad7("CUMPLE");
									} elseif ($unidad==6){
										$curso->setFechasUnidad6("CUMPLE");
									} elseif ($unidad==5){
										$curso->setFechasUnidad5("CUMPLE");
									} elseif ($unidad==4){
										$curso->setFechasUnidad4("CUMPLE");
									} elseif ($unidad==3){
										$curso->setFechasUnidad3("CUMPLE");
									} elseif ($unidad==2){
										$curso->setFechasUnidad2("CUMPLE");
									} elseif ($unidad==1){
										$curso->setFechasUnidad1("CUMPLE");
									}
									$cumple++;
								}
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

			

			//Revisar libro de calificaciones categoría Avance formativo 1------------------------	

			if ($rowsAvanceFormativo1 > 0) {

				$curso->setAF01Actividades("CUMPLE");
				
				$gc = mysqli_fetch_array($avanceFormativo1);
				$weighingGrade = weighing($columna['course_id'], $gc["idc"]);
				$weighing = mysqli_fetch_array($weighingGrade);

				if ($weighing["gradeSum"] > 100 || $weighing["gradeSum"] == 0) {
					$curso->setAF01Ponderaciones("NO CUMPLE");
					$noCumple++;
				} else {
					if ($weighing["gradeSum"] == 100 || $weighing["gradeSum"] == 30 || $weighing["gradeSum"] == 0.30) {
						$curso->setAF01Ponderaciones("CUMPLE");
						$cumple++;
					} else {
						$curso->setAF01Ponderaciones("NO CUMPLE");
						$noCumple++;
					}
				}
			} else {
				$curso->setAF01Actividades("NO CUMPLE");
				$curso->setAF01Ponderaciones("NO CUMPLE");
				$noCumple+=2;
			}

			//Revisar libro de calificaciones categoría Avance formativo 2------------------------	

			if ($rowsAvanceFormativo2 > 0) {
				$curso->setAF02Actividades("CUMPLE");
				$gc = mysqli_fetch_array($avanceFormativo2);
				$weighingGrade = weighing($columna['course_id'], $gc["idc"]);
				$weighing = mysqli_fetch_array($weighingGrade);

				if ($weighing["gradeSum"] > 100 || $weighing["gradeSum"] == 0) {
					$curso->setAF02Ponderaciones("NO CUMPLE");
					$noCumple++;
				} else {
					if ($weighing["gradeSum"] == 100 || $weighing["gradeSum"] == 30 || $weighing["gradeSum"] == 0.30) {
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
				$noCumple+=2;
			}

			//Revisar libro de calificaciones categoría Avance formativo 3------------------------	

			if ($rowsAvanceFormativo3 > 0) {
				$curso->setAF03Actividades("CUMPLE");
				$gc = mysqli_fetch_array($avanceFormativo3);
				$weighingGrade = weighing($columna['course_id'], $gc["idc"]);
				$weighing = mysqli_fetch_array($weighingGrade);

				if ($weighing["gradeSum"] > 100 || $weighing["gradeSum"] == 0) {
					$curso->setAF03Ponderaciones("NO CUMPLE");
					$noCumple++;
				} else {
					if ($weighing["gradeSum"] == 100 || $weighing["gradeSum"] == 40 || $weighing["gradeSum"] == 0.40) {
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
				$noCumple+=2;
			}
			//-----------------------------------------------------------------------------

			
			$total = $noCumple+$cumple;
			$porcentaje = str_replace(".", ",", (round(((100 / $total) * $cumple), 2)));
			$curso->setPorcentaje($porcentaje);
			$vector_curso[] = $curso;
		}
		usort($vector_curso,'ordenar');

		echo "<div>
		<table class='table table-hover'>
		<thead class='table-dark' align='center'>
			<tr>
			<th>ID user</th>
			<th>Nombre</th>
			<th>Correo</th>
			<th>Programa</th>
			<th>Semestre</th>
			<th>Curso</th>
			<th>Nombre del curso</th>
			<th>Nombre</th>
			<th>Correo</th>
			<th>Horario de atención</th>
			<th>Fotografía</th>
			<th>Foro de consulta</th>
			<th>Fecha I Y F S1</th>
			<th>Fecha I Y F S2</th>
			<th>Fecha I Y F S3</th>
			<th>Fecha I Y F S4</th>
			<th>Fecha I Y F S5</th>
			<th>Fecha I Y F S6</th>
			<th>Fecha I Y F S7</th>
			<th>Fecha I Y F S8</th>
			<th>Avance formativo 1 Actividades</th>
			<th>Avance formativo 1 Ponderaciones</th>
			<th>Avance formativo 2 Actividades</th>
			<th>Avance formativo 2 Ponderaciones</th>
			<th>Avance formativo 3 Actividades</th>
			<th>Avance formativo 3 Ponderaciones</th>
			<th>Porcentaje</th>
		  	</tr>

		</thead>";
		
		foreach($vector_curso as $curse){
			if($curse->getPorcentaje() >= 80 && $curse->getPorcentaje() <= 100){
		
			echo"
				<tr class='tr1'>
				<td>".$curse->getIdUser()."</td>
				<td>".$curse->getNombre()."</td>
				<td>".$curse->getCorreo()."</td>
				<td>".$curse->getPrograma()."</td>
				<td align='center'>".$curse->getSemestre()."</td>
				<td align='center'>".$curse->getIdCurso()."</td>
				<td>".$curse->getNombreCurso()."</td>
				<td align='center'>".$curse->getNombreProfesor()."</td>
				<td align='center'>".$curse->getCorreoProfesor()."</td>
				<td align='center'>".$curse->getHorarioAtencion()."</td>
				<td align='center'>".$curse->getFotografia()."</td>
				<td align='center'>".$curse->getForoConsulta()."</td>
				<td align='center'>".$curse->getFechasUnidad1()."</td>
				<td align='center'>".$curse->getFechasUnidad2()."</td>
				<td align='center'>".$curse->getFechasUnidad3()."</td>
				<td align='center'>".$curse->getFechasUnidad4()."</td>
				<td align='center'>".$curse->getFechasUnidad5()."</td>
				<td align='center'>".$curse->getFechasUnidad6()."</td>
				<td align='center'>".$curse->getFechasUnidad7()."</td>
				<td align='center'>".$curse->getFechasUnidad8()."</td>
				<td align='center'>".$curse->getAF01Actividades()."</td>
				<td align='center'>".$curse->getAF01Ponderaciones()."</td>
				<td align='center'>".$curse->getAF02Actividades()."</td>
				<td align='center'>".$curse->getAF02Ponderaciones()."</td>
				<td align='center'>".$curse->getAF03Actividades()."</td>
				<td align='center'>".$curse->getAF03Ponderaciones()."</td>
				<td align='center'>".$curse->getPorcentaje()."%"."</td>
				</tr>";
			
		
			}else if ($curse->getPorcentaje() >= 51 && $curse->getPorcentaje() <= 79){
				echo"
				<tr class='table-warning'>
				<td>".$curse->getIdUser()."</td>
				<td>".$curse->getNombre()."</td>
				<td>".$curse->getCorreo()."</td>
				<td>".$curse->getPrograma()."</td>
				<td align='center'>".$curse->getSemestre()."</td>
				<td align='center'>".$curse->getIdCurso()."</td>
				<td>".$curse->getNombreCurso()."</td>
				<td align='center'>".$curse->getNombreProfesor()."</td>
				<td align='center'>".$curse->getCorreoProfesor()."</td>
				<td align='center'>".$curse->getHorarioAtencion()."</td>
				<td align='center'>".$curse->getFotografia()."</td>
				<td align='center'>".$curse->getForoConsulta()."</td>
				<td align='center'>".$curse->getFechasUnidad1()."</td>
				<td align='center'>".$curse->getFechasUnidad2()."</td>
				<td align='center'>".$curse->getFechasUnidad3()."</td>
				<td align='center'>".$curse->getFechasUnidad4()."</td>
				<td align='center'>".$curse->getFechasUnidad5()."</td>
				<td align='center'>".$curse->getFechasUnidad6()."</td>
				<td align='center'>".$curse->getFechasUnidad7()."</td>
				<td align='center'>".$curse->getFechasUnidad8()."</td>
				<td align='center'>".$curse->getAF01Actividades()."</td>
				<td align='center'>".$curse->getAF01Ponderaciones()."</td>
				<td align='center'>".$curse->getAF02Actividades()."</td>
				<td align='center'>".$curse->getAF02Ponderaciones()."</td>
				<td align='center'>".$curse->getAF03Actividades()."</td>
				<td align='center'>".$curse->getAF03Ponderaciones()."</td>
				<td align='center'>".$curse->getPorcentaje()."%"."</td>
				</tr>";

	
			}else if ($curse->getPorcentaje() >= 0 && $curse->getPorcentaje() <= 50){

				print("
				<tr class='table-danger' >
				<td>".$curse->getIdUser()."</td>
				<td>".$curse->getNombre()."</td>
				<td>".$curse->getCorreo()."</td>
				<td>".$curse->getPrograma()."</td>
				<td align='center'>".$curse->getSemestre()."</td>
				<td align='center'>".$curse->getIdCurso()."</td>
				<td>".$curse->getNombreCurso()."</td>
				<td align='center'>".$curse->getNombreProfesor()."</td>
				<td align='center'>".$curse->getCorreoProfesor()."</td>
				<td align='center'>".$curse->getHorarioAtencion()."</td>
				<td align='center'>".$curse->getFotografia()."</td>
				<td align='center'>".$curse->getForoConsulta()."</td>
				<td align='center'>".$curse->getFechasUnidad1()."</td>
				<td align='center'>".$curse->getFechasUnidad2()."</td>
				<td align='center'>".$curse->getFechasUnidad3()."</td>
				<td align='center'>".$curse->getFechasUnidad4()."</td>
				<td align='center'>".$curse->getFechasUnidad5()."</td>
				<td align='center'>".$curse->getFechasUnidad6()."</td>
				<td align='center'>".$curse->getFechasUnidad7()."</td>
				<td align='center'>".$curse->getFechasUnidad8()."</td>
				<td align='center'>".$curse->getAF01Actividades()."</td>
				<td align='center'>".$curse->getAF01Ponderaciones()."</td>
				<td align='center'>".$curse->getAF02Actividades()."</td>
				<td align='center'>".$curse->getAF02Ponderaciones()."</td>
				<td align='center'>".$curse->getAF03Actividades()."</td>
				<td align='center'>".$curse->getAF03Ponderaciones()."</td>
				<td align='center'>".$curse->getPorcentaje()."%"."</td>
				</tr>");

			}
			
		}
	}
	echo"</table>
	</div>";
}
?>