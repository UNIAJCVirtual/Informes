<!DOCTYPE html>
<html>

<head>
	<meta http-equiv=Content-Type content=text/html; UTF-8>
	<script type="text/javascript" src="../js/jquery.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="../css/css.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<script src="../js/jquery.blockUI.js"></script>
</head>

<body>
	<?php
	$mail_proccess = false;
	if ($_POST["report"] == 4) {
		$class = "top1";
	} else {
		$class = "top2";
	}
	?>
	<div class='container  <?php echo $class; ?>'>
		<?php
		$report = "Informe no disponible";
		switch ($_POST["report"]) {

			
			/*Caso 1: En esta sesión se tendrá el alistamiento, 
			se enviaran dos variables: categoría y programa*/
			
			case '1':
				
				echo "<div><h1>Alistamiento</h1></div><br>";
				require_once("alistamiento_new.php");
				enlistmentReport($_POST["category"], $_POST["program"], "");
				break;
			
			
		   	/*Caso 2: En esta sesión se tendrá el Avance formativo 1, 
		 	se enviaran tres variables: categoría, programa y el nombre de la categoría en Moodle */
			case '2':
				echo "<div><h1>Avance formativo 1</h1></div><br>
							 Los cursos que no aparecen en la lista es por que no tienen profesor, este informe esta verificado contra el libro de notas de la plataforma moodle seccion Avance formativo 1, se debe tener en cuenta que dichas secciones del libro de calificaciones no necesariamente debe tener foros, actividades y encuentros.<br><br>";
				require_once("advance.php");
			
				$data = advanceReport($_POST["category"], $_POST["program"], "", "Avance formativo 1");
				break;

			/*Caso 3: En esta sesión se tendrá el Avance formativo 2, 
		 	se enviaran tres variables: categoría, programa y el nombre de la categoría en Moodle */	
			case '3':
				echo "<div><h1>Avance formativo 2</h1></div><br>
							 Los cursos que no aparecen en la lista es por que no tienen profesor, este informe esta verificado contra el libro de notas de la plataforma moodle seccion Avance formativo 2, se debe tener en cuenta que dichas secciones del libro de calificaciones no necesariamente debe tener foros, actividades y encuentros.<br><br>";
				require_once("advance.php");
			
			
				$data = advanceReport($_POST["category"], $_POST["program"], "", "Avance formativo 2");
				break;
			
			/*Caso 4: En esta sesión se tendrá las estadisticas, 
		 	se enviara una sola variable programa */	
			case '4':
				echo "<div><h1>Estadisticas</h1></div><br>";
				require_once("statistics.php");
				statistics($_POST["program"]);
				break;
			}	?>
	</div>
	
</body>

</html>