<?php
include_once("../class/curso.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset=utf-8 />
<title>Ejemplo</title>
</head>
<body>
    <?php
    $vector_curso = [];
	$curso = new curso();

    $curso->setNomre
    $curso->setNombreCurso("hola2");
    $curso->setNombreProfesor("hola3");

    $vector_curso[] = $curso;
    print_r($vector_curso);
    ?>

</body>
</html>