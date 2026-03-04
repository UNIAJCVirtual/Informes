<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Reporte - Informes Moodle</title>
	<link rel="icon" href="../resources/img/logoCamacho.png" sizes="32x32">
	<link rel="stylesheet" href="../css/theme.css">
	<link rel='stylesheet' href='https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css'>
	<link rel='stylesheet' href='https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="../js/theme.js" defer></script>
	<?php include("../helpers/strings.php"); ?>
	<style>
		/* Estilos específicos para reportes */
		.report-layout {
			display: flex;
			flex-direction: column;
			min-height: 100vh;
		}
		
		.report-topbar {
			position: sticky;
			top: 0;
			height: var(--topbar-height);
			background: var(--surface-card);
			border-bottom: 1px solid var(--border-light);
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding: 0 var(--spacing-xl);
			z-index: var(--z-sticky);
		}
		
		.report-content {
			flex: 1;
			padding: var(--spacing-xl);
		}
		
		/* Loading overlay */
		#loading {
			display: none;
			position: fixed;
			inset: 0;
			background: var(--surface-overlay);
			z-index: var(--z-modal);
			align-items: center;
			justify-content: center;
		}
		
		#loading.active {
			display: flex;
		}
		
		#loading::after {
			content: '';
			width: 50px;
			height: 50px;
			border: 4px solid var(--border-light);
			border-top-color: var(--color-primary);
			border-radius: 50%;
			animation: spin 0.8s linear infinite;
		}
		
		@keyframes spin {
			to { transform: rotate(360deg); }
		}
		
		/* Formulario otras opciones */
		.other-options {
			max-width: 800px;
			margin: 0 auto;
		}
		
		.other-options .card {
			margin-bottom: var(--spacing-lg);
		}
		
		.options-form {
			display: flex;
			flex-direction: column;
			gap: var(--spacing-lg);
		}
		
		.form-row {
			display: flex;
			flex-wrap: wrap;
			gap: var(--spacing-md);
			align-items: flex-end;
		}
		
		.form-row .form-group {
			flex: 1;
			min-width: 200px;
			margin-bottom: 0;
		}
		
		/* Tablas de reportes */
		.report-table-container {
			background: var(--surface-card);
			border-radius: var(--radius-lg);
			border: 1px solid var(--border-light);
			overflow: hidden;
			margin-bottom: var(--spacing-xl);
		}
		
		.report-table-header {
			padding: var(--spacing-lg);
			border-bottom: 1px solid var(--border-light);
			background: var(--surface-bg);
		}
		
		.report-table-header h2 {
			font-size: 1.125rem;
			font-weight: 600;
			color: var(--text-primary);
			margin: 0;
		}
		
		.report-table-body {
			padding: var(--spacing-md);
		}
		
		/* DataTables overrides */
		.dataTables_wrapper {
			padding: 0;
		}
		
		.dataTables_filter input,
		.dataTables_length select {
			background: var(--surface-card) !important;
			border: 1px solid var(--border-medium) !important;
			border-radius: var(--radius-sm) !important;
			color: var(--text-primary) !important;
			padding: 6px 12px !important;
		}
		
		.dataTables_info {
			color: var(--text-secondary) !important;
			font-size: 0.875rem;
		}
		
		/* Paginación DataTables */
		.dataTables_paginate {
			display: flex;
			align-items: center;
			gap: 4px;
			margin-top: var(--spacing-md);
			padding: var(--spacing-sm) 0;
		}
		
		.dataTables_paginate .paginate_button {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			min-width: 36px;
			height: 36px;
			color: var(--text-primary) !important;
			font-size: 0.875rem;
			cursor: pointer;
			text-decoration: none !important;
			transition: all 0.15s ease;
		}
		
		.dataTables_paginate .paginate_button:hover {
			background: var(--surface-elevated) !important;
			border-color: var(--border-medium) !important;
		}
		
		.dataTables_paginate .paginate_button.current {
			background: var(--color-primary) !important;
			border-color: var(--color-primary) !important;
			color: white !important;
			font-weight: 600;
		}
		
		.dataTables_paginate .paginate_button.disabled {
			opacity: 0.5;
			cursor: not-allowed;
			pointer-events: none;
		}
		
		.dataTables_paginate .ellipsis {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			min-width: 36px;
			height: 36px;
			color: var(--text-secondary);
		}
		
		/* Paginación Bootstrap DataTables - Ocultar viñetas */
		.dataTables_paginate ul.pagination {
			display: flex;
			list-style: none;
			padding: 0;
			margin: 0;
			gap: 4px;
		}
		
		.dataTables_paginate ul.pagination li {
			list-style: none;
		}
		
		.dataTables_paginate ul.pagination li a,
		.dataTables_paginate ul.pagination li span {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			min-width: 36px;
			height: 36px;
			padding: 0 12px;
			background: var(--surface-card);
			border: 1px solid var(--border-light);
			color: var(--text-primary);
			border-radius: var(--radius-sm);
			font-size: 0.875rem;
			cursor: pointer;
			text-decoration: none;
			transition: all 0.15s ease;
		}
		
		.dataTables_paginate ul.pagination li a:hover {
			background: var(--surface-elevated);
			border-color: var(--border-medium);
		}
		
		.dataTables_paginate ul.pagination li.active a,
		.dataTables_paginate ul.pagination li.active span {
			background: var(--color-primary);
			border-color: var(--color-primary);
			color: white;
			font-weight: 600;
		}
		
		.dataTables_paginate ul.pagination li.disabled a,
		.dataTables_paginate ul.pagination li.disabled span {
			opacity: 0.5;
			cursor: not-allowed;
			pointer-events: none;
		}
		
		/* Tabla de datos */
		table.dataTable {
			border-collapse: collapse !important;
			width: 100% !important;
		}
		
		table.dataTable thead th {
			background: var(--sidebar-bg) !important;
			color: var(--text-inverse) !important;
			font-weight: 600;
			font-size: 0.8125rem;
			padding: var(--spacing-md) !important;
			border-bottom: none !important;
			white-space: nowrap;
		}
		
		table.dataTable tbody td {
			padding: var(--spacing-sm) var(--spacing-md) !important;
			border-bottom: 1px solid var(--border-light) !important;
			color: var(--text-primary);
			font-size: 0.875rem;
			vertical-align: middle;
		}
		
		table.dataTable tbody tr:hover {
			background: var(--surface-bg) !important;
		}
		
		table.dataTable tbody tr:nth-child(even) {
			background: var(--surface-card);
		}
		
		/* Botones DataTables */
		.dt-buttons .btn {
			background: var(--color-primary) !important;
			border: none !important;
			color: white !important;
			font-weight: 500;
			padding: 8px 16px !important;
			border-radius: var(--radius-md) !important;
			margin-right: var(--spacing-sm) !important;
			font-size: 0.8125rem !important;
		}
		
		.dt-buttons .btn:hover {
			background: var(--color-primary-hover) !important;
		}
		
		.dt-buttons .btn-secondary {
			background: var(--surface-elevated) !important;
			color: var(--text-primary) !important;
			border: 1px solid var(--border-medium) !important;
		}
		
		/* Dropdown de visibilidad de columnas (colVis) */
		.dt-button-collection {
			position: absolute !important;
			z-index: 2002 !important;
			background: var(--surface-card) !important;
			border: 1px solid var(--border-light) !important;
			border-radius: var(--radius-md) !important;
			box-shadow: var(--shadow-lg) !important;
			padding: var(--spacing-sm) !important;
			min-width: 180px;
			max-height: 400px;
			overflow-y: auto;
		}
		
		.dt-button-collection .dt-button {
			display: block !important;
			width: 100% !important;
			text-align: left !important;
			padding: 8px 12px !important;
			background: transparent !important;
			border: none !important;
			color: var(--text-primary) !important;
			font-size: 0.875rem !important;
			border-radius: var(--radius-sm) !important;
			cursor: pointer;
			transition: background 0.15s ease;
		}
		
		.dt-button-collection .dt-button:hover {
			background: var(--surface-bg) !important;
		}
		
		.dt-button-collection .dt-button.active {
			background: var(--color-primary-subtle) !important;
			color: var(--color-primary) !important;
			font-weight: 500;
		}
		
		.dt-button-collection .dt-button span {
			display: flex;
			align-items: center;
			gap: 8px;
		}
		
		.dt-button-collection .dt-button span::before {
			content: '☐';
			font-size: 1rem;
		}
		
		.dt-button-collection .dt-button.active span::before {
			content: '☑';
			color: var(--color-primary);
		}
		
		/* Fondo oscuro detrás del dropdown */
		.dt-button-background {
			position: fixed !important;
			inset: 0 !important;
			background: rgba(0, 0, 0, 0.3) !important;
			z-index: 2001 !important;
		}
		
		/* Badges de estado en tablas */
		.badge-cumple {
			display: inline-block;
			padding: 4px 10px;
			font-size: 0.6875rem;
			font-weight: 600;
			border-radius: var(--radius-full);
			text-transform: uppercase;
			background: var(--color-cumple-bg);
			color: var(--color-cumple);
		}
		
		.badge-no-cumple {
			display: inline-block;
			padding: 4px 10px;
			font-size: 0.6875rem;
			font-weight: 600;
			border-radius: var(--radius-full);
			text-transform: uppercase;
			background: var(--color-no-cumple-bg);
			color: var(--color-no-cumple);
		}
		
		.badge-no-aplica {
			display: inline-block;
			padding: 4px 10px;
			font-size: 0.6875rem;
			font-weight: 600;
			border-radius: var(--radius-full);
			text-transform: uppercase;
			background: var(--color-no-aplica-bg);
			color: var(--color-no-aplica);
		}
	</style>
</head>
<body>
	<div id="loading"></div>
	<div class="report-layout">
		<header class="report-topbar">
			<div class="topbar-left flex items-center gap-md">
				<a href="../index.php" class="btn btn-ghost btn-sm">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M19 12H5M12 19l-7-7 7-7"/>
					</svg>
					Volver
				</a>
				<h1 class="topbar-title">Reporte</h1>
			</div>
			<div class="topbar-right">
				<button type="button" class="topbar-btn theme-toggle" aria-label="Cambiar tema">
					<svg class="theme-toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
					</svg>
				</button>
			</div>
		</header>
		
		<main class="report-content">
			<?php
			if (isset($_POST["report"])) {
				switch ($_POST["report"]) {
					case '1':
						require_once("alistamiento.php");
						enlistmentReport($_POST["program"], $_POST["category"], "");
						break;
					case '2':
						require_once("avances.php");
						advanceReport($_POST["program"], $ac1);
						break;
					case '3':
						require_once("avances.php");
						advanceReport($_POST["program"], $ac2);
						break;
					case '4':
						require_once("avances.php");
						advanceReport($_POST["program"], $ac3);
						break;
					case '5':
						require_once("estadistica.php");
						statistics($_POST["program"]);
						break;
					case '6':
						require_once("estadistica_institucionales.php");
						estadisticasInstitucionales($_POST["program"], $_POST["selectInsti"]);
						break;
					case '7':
						require_once("estadistica_ingles.php");
						echo "<div class='card'><div class='card-body'><p class='text-muted'>Funcionalidad de inglés en desarrollo.</p></div></div>";
						break;
				}
			} else {
				// Formulario de Otras opciones
				echo "
				<div class='other-options fade-in'>
					<div class='card'>
						<div class='card-header'>
							<h2 class='card-title'>
								<svg width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' style='margin-right: 8px; vertical-align: middle;'>
									<circle cx='12' cy='12' r='3'/>
									<path d='M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z'/>
								</svg>
								Otras Consultas
							</h2>
						</div>
						<div class='card-body'>
							<form name='data_form' action='#' method='POST' class='options-form'>
								<div class='form-group'>
									<label for='user-querrys' class='form-label'>Tipo de consulta</label>
									<select name='user-querrys' id='user-querrys' class='form-control form-select'>
										<option hidden selected value=''>Selecciona una consulta</option>
										<option value='1'>Usuarios sin ingreso en la plataforma</option>
										<option value='2'>Usuarios sin ingreso en cursos</option>
										<option value='3'>Usuarios sin realizar actividades</option>
										<option value='4'>Matrículas de estudiantes</option>
										<option value='5'>Matriculaciones manuales</option>
										<option value='6'>EFC por ID de curso</option>
										<option value='7'>EFC por ID que no esté</option>
									</select>
								</div>
								<div>
									<button type='submit' name='enviar' class='btn btn-lg'>
										<svg width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'>
											<polygon points='5 3 19 12 5 21 5 3'/>
										</svg>
										Generar Consulta
									</button>
								</div>
							</form>
						</div>
					</div>
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
						<div class='other-options fade-in mt-lg'>
							<div class='card'>
								<div class='card-header'>
									<h2 class='card-title'>EFC por ID de curso</h2>
								</div>
								<div class='card-body'>
									<form name='efc_form' action='#' method='POST' class='options-form'>
										<div class='form-row'>
											<div class='form-group'>
												<label for='idnumber' class='form-label'>ID de la categoría</label>
												<input type='text' name='idnumber' id='idnumber' class='form-control' placeholder='Ej: 123'>
											</div>
											<div class='form-group'>
												<label for='idCourses' class='form-label'>Códigos de cursos (separados por coma)</label>
												<input type='text' name='idCourses' id='idCourses' class='form-control' placeholder='Ej: 101, 102, 103'>
											</div>
										</div>
										<div>
											<button type='submit' name='efcConsult' class='btn'>
												<svg width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'>
													<polygon points='5 3 19 12 5 21 5 3'/>
												</svg>
												Generar
											</button>
										</div>
									</form>
								</div>
							</div>
						</div>";
						break;
					case '7':
						echo "
						<div class='other-options fade-in mt-lg'>
							<div class='card'>
								<div class='card-header'>
									<h2 class='card-title'>Buscar cursos sin ID de categoría</h2>
								</div>
								<div class='card-body'>
									<p class='text-muted mb-md'>Busca qué cursos no tienen el ID de categoría correspondiente.</p>
									<form name='not_efc_form' action='#' method='POST' class='options-form'>
										<div class='form-group'>
											<label for='idnumber2' class='form-label'>ID de la categoría</label>
											<input type='text' name='idnumber' id='idnumber2' class='form-control' placeholder='Ej: 123' style='max-width: 300px;'>
										</div>
										<div>
											<button type='submit' name='efcConsult2' class='btn'>
												<svg width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'>
													<polygon points='5 3 19 12 5 21 5 3'/>
												</svg>
												Generar
											</button>
										</div>
									</form>
								</div>
							</div>
						</div>";
						break;
				}
			}
			
			if (isset($_POST['efcConsult'])) {
				require_once("user_consult.php");
				$idcourse = explode(',', $_POST['idCourses']);
				$idcourse = array_map('trim', $idcourse);
				idCourseEFC($_POST['idnumber'], $idcourse);
			}
			
			if (isset($_POST['efcConsult2'])) {
				require_once("user_consult.php");
				$idnumbers = explode(',', $_POST['idnumber']);
				$idnumbers = array_map('trim', $idnumbers);
				idIsNotInCourse($idnumbers);
			}
			?>
		</main>
		
		<?php include('../helpers/footer.php'); ?>
	</div>

	<script src='https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js'></script>
	<script src='https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js'></script>
	<script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js'></script>
	<script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js'></script>
	<script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js'></script>
	<script src='https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js'></script>
	<script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js'></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js'></script>
	<script src="../js/datatable.js"></script>
</body>
</html>