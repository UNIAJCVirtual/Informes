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
				switch ($_POST["report"]) {
						/*Caso 1: En esta sesión se tendrá el alistamiento, se enviaran dos variables: categoría y programa*/
					case '1':
						require_once("alistamiento.php");
						enlistmentReport($_POST["program"], $_POST["category"], "");
						break;
						/*Caso 2: En esta sesión se tendrá el Avance formativo 1, se enviaran tres variables: categoría, programa y el nombre de la categoría en Moodle */
					case '2':
						require_once("avances.php");
						advanceReport($_POST["program"],"Avance formativo 1");
						break;
						/*Caso 3: En esta sesión se tendrá el Avance formativo 2, se enviaran tres variables: categoría, programa y el nombre de la categoría en Moodle */
					case '3':
						require_once("avances.php");
						advanceReport($_POST["program"],"Avance formativo 2");
						break;
						/*Caso 4: En esta sesión se tendrá las estadisticas, se enviara una sola variable programa */
					case '4':
						require_once("estadistica.php");
						statistics($_POST["program"]);
						break;
						/*Caso 4: En esta sesión se tendrá las estadisticas, se enviara una sola variable programa */
					case '5':
						require_once("estadistica_institucionales.php");
						estadisticasInstitucionales($_POST["program"],$_POST["selectInsti"]);
						break;
						/*Caso 4: En esta sesión se tendrá las estadisticas, se enviara una sola variable programa */
					case '6':
						require_once("estadistica_ingles.php");
						statistics($_POST["program"]);
						break;
				}	?>
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
				data: {loading: loading,},
				type: 'post',
				success: function(data) {$("#programs").html(data);}
			})
	</script>
</body>

</html>