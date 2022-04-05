<!DOCTYPE html>
<html>

<head>
	<title>Informes</title>
	<meta http-equiv=Content-Type content=text/html; UTF-8>
	<script type="text/javascript" src="js/jquery.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/css.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body onload="loadProgram()" class="body1">
	<div class="container">
		<br><br><br>
		<a href="#modal_conf_mail" data-toggle="modal">
			<button type='button' class='btn btn-success btn-sm'><span class='glyphicon glyphicon-wrench' aria-hidden='true'></span> Configurar Correos</button>
		</a>
		<?php
		require("report/components/conf_mail.php");
		modal_conf_mail("modal_conf_mail", "", "", "");
		?>
		<br><br><br>
		<h2>Informes</h2>
		<div class="panel panel-default">
			<div class="panel-body">
				<form name="data_form" action="report/show_report.php" method="POST" target="_blank">
					Informe:
					<select name="report" id="report" onchange="show(this)" required>
						<option value="">Seleccionar...</option>
						<option value="1">Alistamiento</option>
						<option value="2">Avance formativo 1</option>
						<option value="3">Avance formativo 2</option>
						<option value="4">Estadisticas</option>
					</select><br>
					<div id="inputs">
						Categoria:
						<select name="category" id="category" onchange="loadProgram();" required>
							<option value="null">Seleccionar...</option>
							<?php require_once('selectors/category.php');
							selectCategory('');	?>
						</select><br>
						Programa:
						<div name="programs" id="programs"></div>
						<br>
					<input type="submit" value="Siguiente">
				</form>
			</div>
		</div>
	</div>

	<!--  Scripts con ajax al final para que la pagina pueda cargar toda la parte visual, si la parte de los script es muy pesada-->
	<script type="text/javascript">
		function loadProgram() {
			var input1 = document.getElementById("category");
			if (!input1.readOnly) {
				var category = $("#category").val();
				$.ajax({
					url: 'selectors/ajax_processor.php',
					data: {
						category: category,
						opc: '1'
					},
					type: 'post',
					success: function(data) {
						$("#programs").html(data);
					}
				})
			}
		}

		function loadSemester() {
			var input2 = document.getElementById("program");
			if (!input2.disabled) {
				var program = $("#program").val();
				$.ajax({
					url: 'selectors/ajax_processor.php',
					data: {
						program: program,
						opc: '2'
					},
					type: 'post',
					success: function(data) {
						$("#semester").html(data);
					}
				})
			}
		}

		function show(report) {
			var x = document.getElementById("div_scale");
			report.value == 7 ? x.style.display = "block" : x.style.display = "none";
			var inp = document.getElementById("inputs");
			report.value == 8 ? inp.style.display = "none" : inp.style.display = "block";
			var div = document.getElementById("div_date");
			report.value == 8 ? div.style.display = "block" : div.style.display = "none";
		}
	</script>
</body>

</html>