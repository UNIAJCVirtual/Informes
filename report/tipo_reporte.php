<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv=Content-Type content=text/html; UTF-8>
	<link rel="icon" href="../resources/img/logoCamacho.png" sizes="32x32">
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>
	<link rel='stylesheet' href='https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css'>
	<link rel='stylesheet' href='https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css'>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="../css/style-dashboard.css">
	<title>Reporte</title>
	<?php include("../helpers/strings.php"); ?>
</head>

<body>
	<div id="loading"></div>
	<div id="content-wrapper" class="d-flex flex-column">
		<!-- Main Content -->
		<div id="content">
			<!-- Topbar -->
			<nav class="navbar navbar-expand navbar-light bg-gradient topbar mb-4 static-top shadow">
				<!-- Sidebar Toggle (Topbar) -->
				<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
					<i class="fa fa-bars"></i>
				</button>
				<!-- Topbar Search -->
				<!-- Topbar Navbar -->
				<ul class="navbar-nav ml-auto">
					<!-- Nav Item - Search Dropdown (Visible Only XS) -->
					<li class="nav-item dropdown no-arrow d-sm-none">

					</li>
					<div class="topbar-divider d-none d-sm-block"></div>
					<!-- Nav Item - User Information -->
					<li class="nav-item dropdown no-arrow">
						<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<img src="../resources\img\uniajcEstadeModaBlanco.png" width="95" height="30">
						</a>
					</li>
				</ul>
			</nav>
			<!-- End of Topbar -->
			<!-- Begin Page Content -->
			<div class="container-table	">
				<!-- Page Heading -->
				<?php
				if (isset($_POST["report"])) {
					switch ($_POST["report"]) {
							/*Caso 1: En esta sesión se tendrá el alistamiento, se enviaran dos variables: categoría y programa*/
						case '1':
							require_once("alistamiento.php");
							enlistmentReport($_POST["program"], $_POST["category"], "");
							break;
							/*Caso 2: En esta sesión se tendrá el Avance formativo 1, se enviaran tres variables: categoría, programa y el nombre de la categoría en Moodle */
						case '2':
							require_once("avances.php");
							advanceReport($_POST["program"], $ac1);
							break;
							/*Caso 3: En esta sesión se tendrá el Avance formativo 2, se enviaran tres variables: categoría, programa y el nombre de la categoría en Moodle */
						case '3':
							require_once("avances.php");
							advanceReport($_POST["program"], $ac2);
							break;
							/*Caso 3: En esta sesión se tendrá el Avance formativo 3, se enviaran tres variables: categoría, programa y el nombre de la categoría en Moodle */
						case '4':
							require_once("avances.php");
							advanceReport($_POST["program"], $ac3);
							break;
							/*Caso 4: En esta sesión se tendrá las estadisticas, se enviara una sola variable programa */
						case '5':
							require_once("estadistica.php");
							statistics($_POST["program"]);
							break;
							/*Caso 4: En esta sesión se tendrá las estadisticas, se enviara una sola variable programa */
						case '6':
							require_once("estadistica_institucionales.php");
							estadisticasInstitucionales($_POST["program"], $_POST["selectInsti"]);
							break;
							/*Caso 4: En esta sesión se tendrá las estadisticas, se enviara una sola variable programa */
						case '7':
							require_once("estadistica_ingles.php");
							echo "INGRESO";
							
							// statistics($_POST["program"]);
							break;
					}
				} else {
					echo "
				<form name='data_form' action='#' method='POST' >
				<div class='div-other'>
					<h1 id='title-prg' class=h3 mb-0 d-none mr-5>Otras opciones</h1>
					<select name='user-querrys' id='user-querrys' class='custom-select p-l-1'>x
						<option hidden selected value=''>Consultas</option> 	
						<option value='1'>Usuarios sin ingreso en la plataforma</option>
						<option value='2'>Usuarios sin ingreso en cursos</option>
						<option value='3'>Usuarios sin realizar actividades</option>
						<option value='4'>Matriculas de estudiantes</option>
						<option value='5'>Matriculaciones manuales</option>
						<option value='6'>EFC Por ID de curso</option>
						<option value='7'>EFC Por ID que no este</option>
					</select>
                        <input id=generate' class='btn btn1 d-flex p-3 ' name='enviar' type='submit' value='Generar'>
				</form>
				</div>";
				}
				if (isset($_POST['enviar'])) {
					require_once("user_consult.php");
					switch ($_POST['user-querrys']) {
						case '1':
							userNotSingup(1);
							break;
						case '2':
							userNotSingup(2);
							break;
						case '3':
							userNotSingup(3);
							break;
						case '4':
							userNotSingup(4);
							break;
						case '5':
							userNotSingup(5);
							break;
						case '6':
							echo "
							<form name='efc_form' action='#' method='POST' >
								<div id='selectInstitucional'  class='select'>
									<input type='text' placeholder='Ingresa el ID de la categoria' name='idnumber' id='selectInsti' class='custom-select p-l-1' style='height: 40px;width: 250px;margin-top:20px'>
									<input type='text' placeholder='Ingresa los codigos con comas' name='idCourses' id='selectInsti' class='custom-select p-l-1' style='height: 40px;width: 270px;margin-top:20px'>
									<input id=generate' class='btn btn1 d-flex p-3 ' name='efcConsult' type='submit' value='Generar'>
								</div>
							</form>";
							break;
						case '7':
							echo "
							<p>Busca que cursos no tienen el ID de categoria correspondiente</p>
							<form name='not_efc_form' action='#' method='POST' >
								<div id='selectInstitucional'  class='select'>
									<input type='text' placeholder='Ingresa el ID de la categoria' name='idnumber' id='selectInsti' class='custom-select p-l-1' style='height: 40px;width: 250px;margin-top:20px'>
									<input id=generate' class='btn btn1 d-flex p-3 ' name='efcConsult2' type='submit' value='Generar'>
								</div>
							</form>";
							break;
					}
				}
				if (isset($_POST['efcConsult'])) {
					require_once("user_consult.php");

					// Obtener los IDs de los cursos ingresados y convertirlos en un array
					$idcourse = explode(',', $_POST['idCourses']);
					// Eliminar espacios en blanco de cada elemento del array
					$idcourse = array_map('trim', $idcourse);

					idCourseEFC($_POST['idnumber'], $idcourse);
				}
				if (isset($_POST['efcConsult2'])) {
					require_once("user_consult.php");
					// Obtener los IDs de categoria ingresados y convertirlos en un array
					$idnumbers = explode(',', $_POST['idnumber']);
					// Eliminar espacios en blanco de cada elemento del array
					$idnumbers = array_map('trim', $idnumbers);

					idIsNotInCourse($idnumbers);
				}
				?>

			</div>
		</div>
		<!-- /.container-fluid -->
	</div>

	<!-- partial -->
	<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
	<script src='https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js'></script>
	<script src='https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js'></script>
	<script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js'></script>
	<script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js'></script>
	<script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js'></script>
	<script src='https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js'></script>
	<script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js'></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js'></script>
	<script src='https://unpkg.com/feather-icons'></script>
	<script src="../js/datatable.js"></script>

	<script type="text/javascript">
		let loading = $("#loading").val();
		$.ajax({
			url: 'program.php',
			data: {
				loading: loading,
			},
			type: 'post',
			success: function(data) {
				$("#programs").html(data);
			}
		})
	</script>
</body>

</html>