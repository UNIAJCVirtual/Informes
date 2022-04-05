<!DOCTYPE html>
<html>
<head>
	<meta http-equiv=Content-Type content=text/html; UTF-8> 
	<title>Alistamiento</title>
</head>
<body>
<?php
	function rellenar($n){
		$c="";
		for ($i=0; $i < $n; $i++) {
			$c .= "<td>NO APLICA</td>";
		}
		return $c;
	}
	function validarEmail($cont,$e,$c){
		$flag = false;
		$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
		$reemplazar=array("", "", "", "");
		$sin =str_replace($buscar,$reemplazar,strip_tags($cont));
		$arrayContenido = explode(":", $sin);
		// var_dump($sin);
		// echo $e."-->".$c;
		if (count($arrayContenido)>0) {
			$posicion = 0;
			// var_dump($arrayContenido);
			for ($i=0;$i<count($arrayContenido);$i++) {
				if (substr_count($arrayContenido[$i],"@")==1) {
					$email = $arrayContenido[$i];
					$flag = true;
					break;
				}else{
					// echo "Error5  ".$e."  --> ".$arrayContenido[$i]."-->$i<br>";					
				}

			}
			if ($flag) {
				// echo $arrayContenido[$i];
				if( (strpos($email,$e)) !== false){
					return true;
				}
				// echo $e." ".$email;
				// validaciones de correo
				if(substr_count($email,"@")==1){
					$var = explode("@",$email);
					if (strlen($var[0])>2) {
						$domainsExt = array(".com",".edu",".es",".co",".org",".gob",".mil",".ws",".biz",".cc",".info",".tv",".net",".pro",".coop");
						$domainsExt = implode(" ", $domainsExt);
						$validacion = strpos($domainsExt,".com");
						// var_dump($validacion);
						if($validacion !== false){
							return true;
						}else{
							// echo "Error4  ".$e."  -->".$email."<br>";		
							return false;
						}
					}else{
						// echo "Error3  ".$e."  -->".$email."<br>";		
						return false;
					}
				}else{
				// echo "Error2  ".$e."  -->".$email."<br>";		
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;	
			// echo "Error1  ".$e;		
		}
	}
	// Conectando, seleccionando la base de datos
	$link = mysqli_connect('conexiones.cpbouvmap8du.us-east-1.rds.amazonaws.com:3306', 'conexion_ajc', 'uKDmQHN7YMwEX7QL')
	    or die('No se pudo conectar: ' . mysql_error());
	mysqli_select_db($link,'conexion_ajc') or die('No se pudo seleccionar la base de datos');
	$sqlCategorias='
		SELECT DISTINCT id
		FROM 
		mdl_course_categories
		WHERE
		parent IN(2629, 2631, 2649, 2690)';
	$resultCategorias = mysqli_query($link,$sqlCategorias) or die('Consulta fallida: ' . mysqli_error());
	echo "<table border='1' cellspacing='0' cellpadding='0'>
		<tr>
			<td>ID</td>
			<td>NOMBRE</td>
			<td>CORREO</td>
			<td>Programa</td>
			<td>Semestre</td>
			<td>CURSO</td>
			<td>Nombre CURSO</td>
			<td>Informacion del docente</td>
			<td>Informacion de contacto (email)</td>
			<td>Horario de atencion</td>
			<td>Fotografia del Tutor</td>
			<td>La informacion del Tutor es Visible</td>
			<td>seccion 1</td>
			<td>seccion 2</td>
			<td>seccion 3</td>
			<td>seccion 4</td>
			<td>seccion 5</td>
			<td>seccion 6</td>
			<td>seccion 7</td>
			<td>seccion 8</td>
			<td>porcentaje</td>
	  	</tr>";
	foreach($resultCategorias as $valorCate){
		$sql = "SELECT id,name,parent FROM mdl_course_categories WHERE id =".$valorCate['id'];
		$result = mysqli_query($link,$sql) or die('Consulta fallida Semestre: ' . mysqli_error());
		$row = $result->fetch_assoc();
		$semestre = $row["name"];
		$sql = "SELECT id,name,parent FROM mdl_course_categories WHERE id =".$row['parent'];
		$result = mysqli_query($link,$sql) or die('Consulta fallida Programa: ' . mysqli_error());
		$row = $result->fetch_assoc();
		$programa = $row["name"];
		$query = "SELECT distinct
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
			mdl_course.category = ".$valorCate['id']."
			ORDER BY username,course_fullname";
		// echo $query;
		$result = mysqli_query($link,$query) or die('Consulta fallida: ' . mysqli_error());
		while ($columna = $result->fetch_assoc()){
			 // var_dump($columna);
			$email = trim(strtolower($columna['email']));
			$queryContenido="SELECT distinct(mdl_course_sections.section) section_id,
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
				mdl_course.id ='".$columna['course_id']."'
				ORDER BY section_id";
				// echo $queryContenido;
			$resultContenido = mysqli_query($link,$queryContenido) or die('Consulta fallida: ' . mysqli_error());
			$cadena="";
			$fila= "
				<tr>
					<td>".$columna['user_id']."</td>
					<td>" . ucwords(strtolower($columna['firstname']))." ".ucwords(strtolower($columna['lastname'])). "</td>
					<td>" . $columna['email']. "</td>
					<td>$programa</td>
					<td>$semestre</td>
					<td>" . $columna['course_id'] . "</td>
					<td>" . $columna['course_fullname'] . "</td>";
			// echo $queryContenido;
			$contador=8;
			$totalValidaciones=0;
			$cumple=0;
			while ($resultInformacion = $resultContenido->fetch_assoc()){
				$namesection=$resultInformacion['name'];
				$contenidoName=strtolower($resultInformacion['page_name']);
				$contenido=strtolower($resultInformacion['page_content']);
				$idsection=$resultInformacion['section_id'];
				if($contador>0){
					if($cadena!=$namesection){
						if($idsection==0){
							//Req. 1 - Información del docente
							$nombreCompleto = strtolower($columna['firstname'])." ".strtolower($columna['lastname']);
							$aNombre = explode(" ",$nombreCompleto);
							$sw = false;
							foreach($aNombre as $valor){
								if ($valor != null ){
									if ((strpos($contenidoName,$valor))!==false){
										$sw = true;
										$fila.= "<td>CUMPLE</td>";
										$cumple++;
										$totalValidaciones++;
										break;
									}
								}
							}
							if ($sw == false){	
								$fila.= "<td>NO CUMPLE</td>";
								$totalValidaciones++;
							}
							// Req. 2 - Información de contacto (email)
							$validar=strpos($contenido,$email);
							if ($validar !== false){
								$fila.= "<td>CUMPLE</td>";
								$totalValidaciones++;
							}else{
								if(validarEmail($contenido,$email,$columna['course_fullname'])){
									$fila.= "<td>CUMPLE</td>";
									$cumple++;
									$totalValidaciones++;
								}else{
									$fila.= "<td>NO CUMPLE</td>";
									$totalValidaciones++;
								}
							}
							//Req. 3 - Horario de atenciòn
							if ((strpos($contenido,"foto de tamaño 200")!== false)){
								$fila.= "<td>NO CUMPLE</td>";		
								$totalValidaciones++;
							}else{
								$fila.= "<td>CUMPLE</td>";	
								$cumple++;
								$totalValidaciones++;
							}
							//Req. 4 - Fotografia del Tutor
							if ((strpos($contenido,'foto'))!== false){
								$fila.= "<td>NO CUMPLE</td>";
								$totalValidaciones++;
							}else{
								$fila.= "<td>CUMPLE</td>";
								$totalValidaciones++;
								$cumple++;
							}
							//Req. 5 - La información del Tutor es Visible
							if ($resultInformacion['visible'] === false){
								$fila.= "<td>NO CUMPLE</td>";
								$totalValidaciones++;
							}else{
								$fila.= "<td>CUMPLE</td>";
								$totalValidaciones++;
								$cumple++;
							}
						}else if ($idsection>0){
							// validacion de unidades
							$sumarycon=$resultInformacion['summary'];
							$queryValidacion="
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
							modulo.course= ".$columna['course_id']."
							GROUP BY unidad.name
							ORDER BY unidad.section";
							
							$resultValidacion = mysqli_query($link,$queryValidacion) or die('Consulta fallida: ' . mysqli_error());
							// echo $queryValidacion;								
							if($resultValidacion->num_rows>0){
								$contador--;
								$columnaval = mysqli_fetch_array($resultValidacion);
								// var_dump($columnaval);
								$contadorval=$columnaval['contador'];
								$contadorval=(int)$contadorval;
								$section=$columnaval['section'];									
									//Req. 6 - Publicación de fechas uniadad1
								$pos=(strripos($sumarycon, "Fecha de inicio"))+16;
								$pos2=(strripos($sumarycon,"Fecha de "));
								$fecha= str_replace ( "_" , "" ,substr ($sumarycon,  $pos , $pos2 -$pos ));
								$long=strlen ($fecha);
								if ($long<3){ 
									$fila.= "<td>NO CUMPLE</td>";		
									$totalValidaciones++;
								}else{
									$fila.= "<td>CUMPLE</td>";
									$cumple++;
									$totalValidaciones++;
								}
							}				
						}
					}
				}				
				$cadena=$namesection;
				// echo $contador;
			}
			if ($totalValidaciones>0) {
				// echo $cumple. "-->".$totalValidaciones."-->".$columna['course_fullname'];
				$porcentaje = str_replace(".",",",(($cumple/$totalValidaciones)*100));
				echo $fila."".rellenar($contador)."<td>".$porcentaje."%</td></tr>";
			}else{
				echo $fila."".rellenar($contador)."<td>0%</td></tr>";
			}
		}
	}
	echo "</table>";
?>
</body>
</html>