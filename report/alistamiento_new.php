
<?php
include_once("../class/curso.php");

function rellenar($n)
{
	$c = "";
	for ($i = 0; $i < $n; $i++) {
		$c .= "<td>NO APLICA</td>";
	}
	return $c;
}

function headerDetail($items)
{
	$result = "<table border='1' cellspacing='1' cellpadding='0'>
				<tr  class='tr'>";


	if ($items->num_rows > 0) {
		foreach ($items as $recordItems) {
			$result .= "<td>" . $recordItems["name"] . "</td>";
		}
	} else {
		$result .= "<td>SIN DATOS</td>";
	}
	return $result . "</tr>";
}

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
	$cant=0;
	$vector_curso = [];
	$curso = new curso(); 
	
	$categoriesResult = Categories(implode(",", $program));
	echo "<table border='1' cellspacing='0' cellpadding='0'>
			<tr class='tr'>
				<td>ID user</td>
				<td>Nombre</td>
				<td>Correo</td>
				<td>Programa</td>
				<td>Semestre</td>
				<td>Curso</td>
				<td>Nombre del curso</td>
				<td>Nombre</td>
				<td>Correo</td>
				<td>Horario de atención</td>
				<td>Fotografía</td>
				<td>Foro consulta</td>
				<td>Fecha I Y F S1</td>
				<td>Fecha I Y F S2</td>
				<td>Fecha I Y F S3</td>
				<td>Fecha I Y F S4</td>
				<td>Fecha I Y F S5</td>
				<td>Fecha I Y F S6</td>
				<td>Fecha I Y F S7</td>
				<td>Fecha I Y F S8</td>
				<td>Avance formativo 1 Actividades</td>
				<td>Avance formativo 1 Ponderaciones</td>
				<td>Avance formativo 2 Actividades</td>
				<td>Avance formativo 2 Ponderaciones</td>
				<td>Avance formativo 3 Actividades</td>
				<td>Avance formativo 3 Ponderaciones</td>
				<td>Porcentaje</td>
		  	</tr>";
	foreach ($categoriesResult as $val) {

		$result = NameCategory($val['id']);
		$semester = $result["name"];
		$program = Program($result['parent']);
		$result = StatisticsInformation($val['id']);
		$color_rows = 1;

		while ($columna = $result->fetch_assoc()) {

			// var_dump($columna);
			if ($color_rows == 0) {
				$class_row = "td1";
				$color_rows = 1;
			} else {
				$class_row = "td2";
				$color_rows = 0;
			}

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

			//------PARA BORRAR
			$fila = "
					<tr class='" . $class_row . "'>
						<td>" . $columna['user_id'] . "</td>
						<td>" . ucwords(strtolower($columna['firstname'])) . " " . ucwords(strtolower($columna['lastname'])) . "</td>
						<td>" . $columna['email'] . "</td>
						<td>$program</td>
						<td>$semester</td>
						<td>" . $columna['course_id'] . "</td>
						<td>" . $columna['course_fullname'] . "</td>";
			$contador = 8;
			$noCumple = 0;
			$cumple = 0;

			//--------------------------
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
										$fila .= "<td>CUMPLE</td>";
										$cumple++;
										break;
									} else if ((strpos($contenido, $valor)) !== false) {
										$sw = true;
										$curso->setNombreProfesor("CUMPLE");
										$fila .= "<td>CUMPLE</td>";
										$cumple++;
										break;
									}
								}
							}
							if ($sw == false) {
								$curso->setNombreProfesor("NO CUMPLE");
								$fila .= "<td>NO CUMPLE</td>";
								$noCumple++;
							}
							// Req. 2 - Información de contacto (email)
							$validar = strpos($contenido, $email);
							if ($validar !== false) {
								$curso->setCorreoProfesor("CUMPLE");
								$fila .= "<td>CUMPLE</td>";
								$cumple++;
							} else {
								if (validarEmail($contenido, $email, $columna['course_fullname'])) {
									$curso->setCorreoProfesor("CUMPLE");
									$fila .= "<td>CUMPLE</td>";
									$cumple++;
								} else {
									$curso->setCorreoProfesor("NO CUMPLE");
									$fila .= "<td>NO CUMPLE</td>";
									$noCumple++;
								}
							}
							//Req. 3 - Horario de atención
							if ((strpos($contenido, 'indicar las horas de atencion que tendra para sus estudiantes') !== false)) {
								$curso->setHorarioAtencion("NO CUMPLE");
								$fila .= "<td>NO CUMPLE</td>";
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
								$fila .= "<td>CUMPLE</td>";
								$cumple++;
							} else {
								$curso->setHorarioAtencion("NO CUMPLE");
								$fila .= "<td>NO CUMPLE</td>";
								$cumple++;
							}
							//Req. 4 - Fotografia del Profesor
							if ((strpos($contenido, 'insertar foto de tamaño 200')) !== false) {
								$curso->setFotografia("NO CUMPLE");
								$fila .= "<td>NO CUMPLE</td>";
								$noCumple++;
							} else {
								$curso->setFotografia("CUMPLE");
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
									$curso->setForoConsulta("CUMPLE");
									$fila .= "<td>CUMPLE</td>";
									$cumple++;
								} else {
									$curso->setForoConsulta("NO CUMPLE");
									$fila .= "<td>NO CUMPLE</td>";
									$noCumple++;
								}
							} else {
								$curso->setForoConsulta("NO CUMPLE");
								$fila .= "<td>NO CUMPLE</td>";
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
							if ($sectionvisible === "0") {
								$fila .= "<td>NO APLICA</td>";
							} else {
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
									$fila .= "<td>NO CUMPLE</td>";
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
									$fila .= "<td>CUMPLE</td>";
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

			$rowsGrade = "";

			//Revisar libro de calificaciones categoría Avance formativo 1------------------------	

			if ($rowsAvanceFormativo1 > 0) {

				$curso->setAF01Actividades("CUMPLE");
				$rowsGrade .= "<td>CUMPLE</td>";
				$gc = mysqli_fetch_array($avanceFormativo1);
				$weighingGrade = weighing($columna['course_id'], $gc["idc"]);
				$weighing = mysqli_fetch_array($weighingGrade);

				if ($weighing["gradeSum"] > 100 || $weighing["gradeSum"] == 0) {
					$curso->setAF01Ponderaciones("NO CUMPLE");
					$rowsGrade .= "<td>NO CUMPLE</td>";
					$noCumple++;
				} else {
					if ($weighing["gradeSum"] == 100 || $weighing["gradeSum"] == 30 || $weighing["gradeSum"] == 0.30) {
						$curso->setAF01Ponderaciones("CUMPLE");
						$rowsGrade .= "<td>CUMPLE</td>";
						$cumple++;
					} else {
						$curso->setAF01Ponderaciones("NO CUMPLE");
						$rowsGrade .= "<td>NO CUMPLE</td>";
						$noCumple++;
					}
				}
			} else {
				$curso->setAF01Actividades("NO CUMPLE");
				$curso->setAF01Ponderaciones("NO CUMPLE");
				$rowsGrade .= "<td>NO CUMPLE</td>";
				$rowsGrade .= "<td>NO CUMPLE</td>";
				$noCumple+=2;
			}

			//Revisar libro de calificaciones categoría Avance formativo 2------------------------	

			if ($rowsAvanceFormativo2 > 0) {
				$curso->setAF02Actividades("CUMPLE");
				$rowsGrade .= "<td>CUMPLE</td>";
				$gc = mysqli_fetch_array($avanceFormativo2);
				$weighingGrade = weighing($columna['course_id'], $gc["idc"]);
				$weighing = mysqli_fetch_array($weighingGrade);

				if ($weighing["gradeSum"] > 100 || $weighing["gradeSum"] == 0) {
					$curso->setAF02Ponderaciones("NO CUMPLE");
					$rowsGrade .= "<td>NO CUMPLE</td>";
					$noCumple++;
				} else {
					if ($weighing["gradeSum"] == 100 || $weighing["gradeSum"] == 30 || $weighing["gradeSum"] == 0.30) {
						$curso->setAF02Ponderaciones("CUMPLE");
						$rowsGrade .= "<td>CUMPLE</td>";
						$cumple++;
					} else {
						$curso->setAF02Ponderaciones("NO CUMPLE");
						$rowsGrade .= "<td>NO CUMPLE</td>";
						$noCumple++;
					}
				}
			}else {
				$curso->setAF02Actividades("NO CUMPLE");
				$curso->setAF02Ponderaciones("NO CUMPLE");
				$rowsGrade .= "<td>NO CUMPLE</td>";
				$rowsGrade .= "<td>NO CUMPLE</td>";
				$noCumple+=2;
			}

			//Revisar libro de calificaciones categoría Avance formativo 3------------------------	

			if ($rowsAvanceFormativo3 > 0) {
				$curso->setAF03Actividades("CUMPLE");
				$rowsGrade .= "<td>CUMPLE</td>";
				$gc = mysqli_fetch_array($avanceFormativo3);
				$weighingGrade = weighing($columna['course_id'], $gc["idc"]);
				$weighing = mysqli_fetch_array($weighingGrade);

				if ($weighing["gradeSum"] > 100 || $weighing["gradeSum"] == 0) {
					$curso->setAF03Ponderaciones("NO CUMPLE");
					$rowsGrade .= "<td>NO CUMPLE</td>";
					$noCumple++;
				} else {
					if ($weighing["gradeSum"] == 100 || $weighing["gradeSum"] == 40 || $weighing["gradeSum"] == 0.40) {
						$curso->setAF03Ponderaciones("CUMPLE");
						$rowsGrade .= "<td>CUMPLE</td>";
						$cumple++;
					} else {
						$curso->setAF03Ponderaciones("NO CUMPLE");
						$rowsGrade .= "<td>NO CUMPLE</td>";
						$noCumple++;
					}
				}
			} else {
				$curso->setAF03Actividades("NO CUMPLE");
				$curso->setAF03Ponderaciones("NO CUMPLE");
				$rowsGrade .= "<td>NO CUMPLE</td>";
				$rowsGrade .= "<td>NO CUMPLE</td>";
				$noCumple+=2;
			}
			//-----------------------------------------------------------------------------

			
			$total = $noCumple+$cumple;
			if ($total > 0) {
				$porcentaje = str_replace(".", ",", (round(((100 / $total) * $cumple), 2)));
				$curso->setPorcentaje($porcentaje);
				if($porcentaje >= 80 && $porcentaje <= 100){
					echo $fila . "" . rellenar($contador) . " " . $rowsGrade . " <td>" . $porcentaje . "%</td></tr>";
					?>
					<script>
						const colorFila = document.querySelector('.td2');
						colorFila.style.backgroundColor = '#92e27a';
					</script>
					<?php
				
				}else if ($porcentaje >= 51 && $porcentaje <= 79){
					echo $fila . "" . rellenar($contador) . " " . $rowsGrade . " <td>" . $porcentaje . "%</td></tr>";
					?>
					<script>
						const colorFila = document.querySelector('.td2');
						colorFila.style.backgroundColor = '#FBDB48';
					</script>
					<?php

				}else if ($porcentaje >= 0 && $porcentaje <= 50){
					echo $fila . "" . rellenar($contador) . " " . $rowsGrade . " <td>" . $porcentaje . "%</td></tr>";

					?>
					<script>
						const colorFila = document.querySelector('.td2');
						colorFila.style.backgroundColor = '#F2ACB8';
					</script>
					<?php
				}
				
			} else {
				echo $fila . "" . rellenar($contador) . "<td>0%</td></tr>";
			}
			
		
			$vector_curso[$cant] = $curso;
			$cant++;
		
		}
		
		
	}
	
	echo "</table>";
	
    print_r($vector_curso);
}
?>
<script>
		
		$vector_curso.sort((a, b) => {
			return a - b;
		});


</script>