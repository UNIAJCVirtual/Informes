<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consola SQL - Informes</title>
    <link rel="icon" href="../resources/img/logoCamacho.png" sizes="32x32">
    <link rel="stylesheet" href="../css/theme.css">
    <link rel='stylesheet' href='https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css'>
    <link rel='stylesheet' href='https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css'>
    <script src="../js/theme.js" defer></script>
    <style>
        /* Layout específico para consola */
        .console-layout {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .console-topbar {
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
        
        .console-content {
            flex: 1;
            padding: var(--spacing-xl);
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }
        
        /* Editor SQL */
        .sql-editor {
            font-family: var(--font-mono);
            font-size: 0.875rem;
            background: var(--sidebar-bg);
            color: #e2e8f0;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
            padding: var(--spacing-lg);
            resize: vertical;
            min-height: 180px;
            width: 100%;
            line-height: 1.6;
        }
        
        .sql-editor:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: var(--shadow-glow);
        }
        
        .sql-editor::placeholder {
            color: var(--text-muted);
        }
        
        /* Warning banner */
        .warning-banner {
            background: rgba(255, 149, 0, 0.1);
            color: var(--color-warning);
            padding: var(--spacing-md) var(--spacing-lg);
            border-radius: var(--radius-md);
            margin-bottom: var(--spacing-lg);
            border: 1px solid rgba(255, 149, 0, 0.2);
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
        }
        
        .warning-banner svg {
            flex-shrink: 0;
        }
        
        /* Result container */
        .result-container {
            margin-top: var(--spacing-xl);
        }
        
        .query-info {
            background: var(--surface-bg);
            padding: var(--spacing-md) var(--spacing-lg);
            border-radius: var(--radius-md);
            margin-bottom: var(--spacing-lg);
            font-size: 0.875rem;
            display: flex;
            flex-wrap: wrap;
            gap: var(--spacing-lg);
            color: var(--text-secondary);
        }
        
        .query-info strong {
            color: var(--text-primary);
        }
        
        .error-message {
            background: var(--color-no-cumple-bg);
            color: var(--color-danger);
            padding: var(--spacing-lg);
            border-radius: var(--radius-md);
            border: 1px solid rgba(255, 59, 48, 0.2);
        }
        
        .success-message {
            background: var(--color-cumple-bg);
            color: var(--color-cumple);
            padding: var(--spacing-lg);
            border-radius: var(--radius-md);
            border: 1px solid rgba(0, 200, 83, 0.2);
        }
        
        /* Table styles */
        .table-container {
            overflow-x: auto;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-light);
            background: var(--surface-card);
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8125rem;
        }
        
        .data-table th {
            padding: var(--spacing-md);
            text-align: left;
            font-weight: 600;
            color: var(--text-inverse);
            background: var(--sidebar-bg);
            border-bottom: 1px solid var(--border-light);
            white-space: nowrap;
            position: sticky;
            top: 0;
        }
        
        .data-table td {
            padding: var(--spacing-sm) var(--spacing-md);
            border-bottom: 1px solid var(--border-light);
            color: var(--text-primary);
            max-width: 400px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .data-table tbody tr:hover {
            background: var(--surface-bg);
        }
        
        .null-value {
            color: var(--text-muted);
            font-style: italic;
        }
        
        /* Tables hint */
        .tables-hint {
            font-size: 0.8125rem;
            color: var(--text-muted);
        }
        
        .tables-hint code {
            background: var(--surface-bg);
            padding: 2px 6px;
            border-radius: var(--radius-sm);
            font-family: var(--font-mono);
            font-size: 0.75rem;
            color: var(--color-primary);
        }
        
        /* DataTables override for theme */
        .dataTables_wrapper {
            padding: var(--spacing-md);
        }
        
        .dataTables_filter input {
            background: var(--surface-card) !important;
            border: 1px solid var(--border-medium) !important;
            border-radius: var(--radius-sm) !important;
            color: var(--text-primary) !important;
            padding: 6px 12px !important;
        }
        
        .dataTables_length select {
            background: var(--surface-card) !important;
            border: 1px solid var(--border-medium) !important;
            border-radius: var(--radius-sm) !important;
            color: var(--text-primary) !important;
        }
        
        .dataTables_info {
            color: var(--text-secondary) !important;
        }
        
        .paginate_button {
            background: var(--surface-card) !important;
            border: 1px solid var(--border-light) !important;
            color: var(--text-primary) !important;
        }
        
        .paginate_button.current {
            background: var(--color-primary) !important;
            border-color: var(--color-primary) !important;
            color: white !important;
        }
    </style>
</head>
<body>
    <div class="console-layout">
        <header class="console-topbar">
            <div class="topbar-left flex items-center gap-md">
                <a href="../index.php" class="btn btn-ghost btn-sm">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Volver
                </a>
                <h1 class="topbar-title">Consola SQL</h1>
            </div>
            <div class="topbar-right">
                <button type="button" class="topbar-btn theme-toggle" aria-label="Cambiar tema">
                    <svg class="theme-toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                    </svg>
                </button>
            </div>
        </header>
        
        <main class="console-content">
            <div class="warning-banner">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0zM12 9v4M12 17h.01"/>
                </svg>
                <span>Esta herramienta ejecuta consultas directamente en la base de datos de Moodle. Use solo consultas SELECT para evitar modificaciones accidentales.</span>
            </div>
            
            <div class="card mb-lg">
                <div class="card-header">
                    <h2 class="card-title">Editor SQL</h2>
                </div>
                <div class="card-body">
                    <form id="sqlForm" method="POST">
                        <div class="form-group">
                            <textarea 
                                name="sql_query" 
                                id="sql_query" 
                                class="sql-editor" 
                                rows="6" 
                                placeholder="SELECT * FROM mdl_course LIMIT 10;"
                                spellcheck="false"
                            ><?php echo isset($_POST['sql_query']) ? htmlspecialchars($_POST['sql_query']) : ''; ?></textarea>
                        </div>
                        <div class="flex justify-between items-center flex-wrap gap-md">
                            <div class="flex gap-sm">
                                <button type="submit" class="btn btn-success">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="5 3 19 12 5 21 5 3"/>
                                    </svg>
                                    Ejecutar (Ctrl+Enter)
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="clearEditor()">
                                    Limpiar
                                </button>
                            </div>
                            <div class="tables-hint">
                                <strong>Tablas comunes:</strong> 
                                <code>mdl_course</code>
                                <code>mdl_user</code>
                                <code>mdl_course_categories</code>
                                <code>mdl_grade_items</code>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['sql_query'])) {
                require_once("../services/connection.php");
                
                $query = trim($_POST['sql_query']);
                $startTime = microtime(true);
                
                try {
                    $con = connection();
                    mysqli_set_charset($con, "utf8");
                    
                    $result = $con->query($query);
                    $endTime = microtime(true);
                    $executionTime = round(($endTime - $startTime) * 1000, 2);
                    
                    if ($result === false) {
                        throw new Exception($con->error);
                    }
                    
                    if ($result === true) {
                        $affectedRows = $con->affected_rows;
                        echo '<div class="result-container">';
                        echo '<div class="success-message">';
                        echo "<strong>Consulta ejecutada exitosamente.</strong><br>";
                        echo "Filas afectadas: <strong>{$affectedRows}</strong> | ";
                        echo "Tiempo: <strong>{$executionTime} ms</strong>";
                        echo '</div></div>';
                    } else {
                        $numRows = $result->num_rows;
                        $numFields = $result->field_count;
                        
                        echo '<div class="result-container">';
                        echo '<div class="query-info">';
                        echo "<span><strong>Resultados:</strong> {$numRows} filas</span>";
                        echo "<span><strong>Columnas:</strong> {$numFields}</span>";
                        echo "<span><strong>Tiempo:</strong> {$executionTime} ms</span>";
                        echo '</div>';
                        
                        echo '<div class="table-container">';
                        echo '<table id="resultTable" class="data-table">';
                        
                        // Siempre mostrar encabezados de columna
                        echo '<thead><tr>';
                        $fields = $result->fetch_fields();
                        foreach ($fields as $field) {
                            echo '<th>' . htmlspecialchars($field->name) . '</th>';
                        }
                        echo '</tr></thead>';
                        
                        echo '<tbody>';
                        if ($numRows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                foreach ($row as $value) {
                                    if ($value === null) {
                                        echo '<td><span class="null-value">NULL</span></td>';
                                    } else {
                                        $displayValue = htmlspecialchars(substr($value, 0, 500));
                                        if (strlen($value) > 500) {
                                            $displayValue .= '...';
                                        }
                                        echo '<td>' . $displayValue . '</td>';
                                    }
                                }
                                echo '</tr>';
                            }
                        } else {
                            // Fila indicando que no hay resultados
                            echo '<tr><td colspan="' . $numFields . '" style="text-align: center; color: var(--text-muted); padding: var(--spacing-xl);">La consulta no devolvió resultados.</td></tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';
                        echo '</div>';
                        
                        $result->free();
                    }
                } catch (Exception $e) {
                    echo '<div class="result-container">';
                    echo '<div class="error-message">';
                    echo '<strong>Error en la consulta:</strong><br>';
                    echo htmlspecialchars($e->getMessage());
                    echo '</div></div>';
                }
            }
            ?>
        </main>
        
        <?php include('../helpers/footer.php'); ?>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src='https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js'></script>
    <script src='https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js'></script>
    <script src='https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js'></script>
    
    <script>
        $(document).ready(function() {
            if ($('#resultTable').length) {
                $('#resultTable').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            text: 'Exportar Excel',
                            className: 'btn btn-sm'
                        },
                        {
                            extend: 'csvHtml5',
                            text: 'Exportar CSV',
                            className: 'btn btn-secondary btn-sm'
                        },
                        {
                            extend: 'colvis',
                            text: 'Columnas',
                            className: 'btn btn-secondary btn-sm'
                        }
                    ],
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json'
                    },
                    scrollX: true
                });
            }
        });

        document.getElementById('sql_query').addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('sqlForm').submit();
            }
        });

        function clearEditor() {
            document.getElementById('sql_query').value = '';
            document.getElementById('sql_query').focus();
        }

        document.getElementById('sql_query').focus();
    </script>
</body>
</html>
