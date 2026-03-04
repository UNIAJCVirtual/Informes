<?php
/**
 * Selector de programas - carga dinámica vía AJAX
 * Renderiza checkboxes estilizados para selección de programas
 */
require_once("../services/connection.php");

$con = connection();
mysqli_set_charset($con, "utf8");

$category = isset($_POST["category"]) ? intval($_POST["category"]) : 0;

if ($category > 0) {
    $result = $con->query("SELECT id, name FROM mdl_course_categories WHERE parent = " . $category . " ORDER BY name ASC");
    
    if ($result && $result->num_rows > 0) {
        while ($data = $result->fetch_assoc()) {
            $id = htmlspecialchars($data["id"]);
            $name = htmlspecialchars($data["name"]);
            echo <<<HTML
            <label class="program-item" for="program_{$id}">
                <input class="checkbox-program" type="checkbox" name="program[]" id="program_{$id}" value="{$id}">
                <span class="program-checkbox">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </span>
                <span class="program-name">{$name}</span>
            </label>
HTML;
        }
        $result->free();
    } else {
        echo '<p class="text-muted">No se encontraron programas en esta categoría.</p>';
    }
} else {
    echo '<p class="text-muted">Selecciona una categoría válida.</p>';
}
?>