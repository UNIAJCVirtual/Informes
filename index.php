<!DOCTYPE html>
<html>

<head>
<title>Informes</title>
	<meta http-equiv=Content-Type content=text/html; UTF-8>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/css.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet"> 
</head>

<body onload="loadProgram()">
	<div class="panel">
		<img src="resources\img\uniajc2.png" alt="uniajc">
		<div class="contenedor">
		<form name="data_form" action="report/tipo_reporte.php" method="POST" target="_blank">	
		<table class="table table-borderless">

			<tr>
				<td><label class="text" for="Name">Tipo de Informe:</label></td>
				<td><select name="report" id="report" required>
						<option value="">Seleccionar...</option>
						<option value="1">Alistamiento</option>
						<option value="2">Avance formativo 1</option>
						<option value="3">Avance formativo 2</option>
						<option value="4">Estadisticas</option>
					</select><br></td>
				<div id="inputs" class="contenedor">

				<td><label class="text" for="Name">Categoria:</label></td>

				<td>
				<select name="category" id="category" onchange="loadProgram();" required>
							<option value="">Seleccionar...</option>
							<?php require_once('selectors/category.php');
							selectCategory('');	?>
				</select>
				<br>
				</td>
			</tr>
			<tr>
				<td>
			<label class="text" for="Name">Programa:</label>
				</td>
				<td colspan="3">
				<div name="programs" id="programs"></div>
				<br>
			</div>
				</td>
			</tr>
			<tr>
				<td colspan="4">
				<input class="btn btn1" type="submit" value="Generar">
				</td>
			</tr>	
		</div>
		</table>
				
				</form>
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
	</script>
</body>

</html>