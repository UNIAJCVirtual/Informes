<!DOCTYPE html>
<html>
<head>
	<meta http-equiv=Content-Type content=text/html; UTF-8> 
	<title>Prueba</title>
</head>
<body>
<?php 
	function prueba(){
		$link = mysqli_connect('conexiones.cpbouvmap8du.us-east-1.rds.amazonaws.com:3306', 'conexion_ajc', 'uKDmQHN7YMwEX7QL')
	    or die('No se pudo conectar: ' . mysql_error());
		mysqli_select_db($link,'conexion_ajc') or die('No se pudo seleccionar la base de datos');
		$sql="SELECT 
		        b.fullname curso,
		        a.itemname item,
		        a.itemmodule tipo,
		        a.grademax
		FROM 
		        mdl_grade_items a,
		        mdl_course b
		WHERE 
		        a.courseid = b.id AND
		        a.grademax > 5 AND
		        a.itemmodule <> 'scorm' AND
		        b.visible = true
		ORDER BY  a.grademax DESC";
		$result = mysqli_query($link,$sql) or die('Consulta fallida: ' . mysqli_error());
		echo "<table border='1' cellspacing='0' cellpadding='0'>
			<tr>
				<td>CURSO</td>
				<td>ITEM</td>
				<td>TIPO</td>
				<td>NOTA MAXIMA</td>
		  	</tr>";
		foreach($result as $value){
			echo "<tr>
					<td>".$value["curso"]."</td>
					<td>".$value["item"]."</td>
					<td>".$value["tipo"]."</td>
					<td>".$value["grademax"]."</td>
				  </tr>";
		}
		echo "</table>";
	}
?>