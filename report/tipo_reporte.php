<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv=Content-Type content=text/html; UTF-8>
	<link rel="icon" href="https://www.uniajc.edu.co/wp-content/uploads/2018/06/cropped-favicon-32x32.png" sizes="32x32">
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>
	<link rel='stylesheet' href='https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css'>
	<link rel='stylesheet' href='https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css'>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
	<!-- <link rel="stylesheet" href="../style/style.css?v=<?php echo (rand()); ?>" /> -->
	<link rel="stylesheet" href="../css/style-dashboard.css">
	<title>Reporte</title>
</head>

<body>
	<div class='wrapper'>
		<?php
		print_r($_POST["program"]);
		print_r("category: " . $_POST["category"]);
		switch ($_POST["report"]) {
				/*Caso 1: En esta sesión se tendrá el alistamiento, 
			se enviaran dos variables: categoría y programa*/
			case '1':
				require_once("alistamiento.php");
				enlistmentReport($_POST["program"], $_POST["category"], "");
				break;
				/*Caso 2: En esta sesión se tendrá el Avance formativo 1, 
		 	se enviaran tres variables: categoría, programa y el nombre de la categoría en Moodle */
			case '2':
				require_once("avances.php");
				$data = advanceReport($_POST["program"], "", "Avance formativo 1");
				break;
				/*Caso 3: En esta sesión se tendrá el Avance formativo 2, 
		 	se enviaran tres variables: categoría, programa y el nombre de la categoría en Moodle */
			case '3':
				require_once("avances.php");
				$data = advanceReport($_POST["program"], "", "Avance formativo 2");
				break;
				/*Caso 4: En esta sesión se tendrá las estadisticas, 
		 	se enviara una sola variable programa */
			case '4':
				require_once("estadistica.php");
				statistics($_POST["program"]);
				break;
				/*Caso 4: En esta sesión se tendrá las estadisticas, 
		 	se enviara una sola variable programa */
			case '5':
				require_once("estadistica_institucionales.php");
				statistics($_POST["program"]);
				break;
				/*Caso 4: En esta sesión se tendrá las estadisticas, 
		 	se enviara una sola variable programa */
			case '6':
				require_once("estadistica_ingles.php");
				statistics($_POST["program"]);
				break;
		}	?>
	</div>
	<!-- partial -->
	<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
	<script src='https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js'></script>
	<script src='https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js'></script>
	<script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js'></script>
	<script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js'></script>
	<script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js'></script>
	<script src='https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js'></script>
	<script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js'></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js'></script>
	<script src='https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js'></script>
	<script src='https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js'></script>
	<script src='https://unpkg.com/feather-icons'></script>
	<script src="../js/script-datatable.js"></script>

</body>

</html>

</html>