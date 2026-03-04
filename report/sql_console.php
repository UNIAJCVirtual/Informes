<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consola SQL - Informes</title>
    <link rel="icon" href="../resources/img/logoCamacho.png" sizes="32x32">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css'>
    <link rel='stylesheet' href='https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css'>
    <link rel="stylesheet" href="../css/style-dashboard.css">
    <style>
        .sql-editor {
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
            font-size: 14px;
            background-color: #1e1e1e;
            color: #d4d4d4;
            border: 1px solid #3c3c3c;
            border-radius: 5px;
            padding: 15px;
            resize: vertical;
            min-height: 150px;
        }
        .sql-editor:focus {
            outline: none;
            border-color: #007acc;
            box-shadow: 0 0 5px rgba(0, 122, 204, 0.5);
        }
        .btn-execute {
            background-color: #28a745;
            border-color: #28a745;
            font-weight: bold;
        }
        .btn-execute:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .btn-clear {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .result-container {
            margin-top: 20px;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .query-info {
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 13px;
        }
        .query-info strong {
            color: #495057;
        }
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #f5c6cb;
        }
        .warning-banner {
            background: #fff3cd;
            color: #856404;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #ffeeba;
        }
        .table-responsive {
            max-height: 500px;
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-gradient topbar mb-4 static-top shadow">
                <a href="../index.php" class="btn btn-outline-light btn-sm mr-3">
                    ← Volver
                </a>
                <span class="navbar-text text-white font-weight-bold">
                    Consola SQL
                </span>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <img src="../resources/img/uvi-white-transparent.png" width="110" height="60" alt="Logo">
                    </li>
                </ul>
            </nav>

            <div class="container-fluid">
                <div class="warning-banner">
                    <strong>⚠️ Advertencia:</strong> Esta herramienta ejecuta consultas directamente en la base de datos de Moodle. 
                    Use solo consultas SELECT para evitar modificaciones accidentales.
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <strong>Editor SQL</strong>
                    </div>
                    <div class="card-body">
                        <form id="sqlForm" method="POST">
                            <div class="form-group">
                                <textarea 
                                    name="sql_query" 
                                    id="sql_query" 
                                    class="form-control sql-editor" 
                                    rows="6" 
                                    placeholder="SELECT * FROM mdl_course LIMIT 10;"
                                    spellcheck="false"
                                ><?php echo isset($_POST['sql_query']) ? htmlspecialchars($_POST['sql_query']) : ''; ?></textarea>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <button type="submit" class="btn btn-execute text-white">
                                        ▶ Ejecutar (Ctrl+Enter)
                                    </button>
                                    <button type="button" class="btn btn-clear text-white" onclick="clearEditor()">
                                        Limpiar
                                    </button>
                                </div>
                                <div class="text-muted small">
                                    <strong>Tablas comunes:</strong> 
                                    <code>mdl_course</code>, 
                                    <code>mdl_user</code>, 
                                    <code>mdl_course_categories</code>,
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
                            // Para queries INSERT, UPDATE, DELETE
                            $affectedRows = $con->affected_rows;
                            echo '<div class="result-container">';
                            echo '<div class="alert alert-success">';
                            echo "<strong>Consulta ejecutada exitosamente.</strong><br>";
                            echo "Filas afectadas: <strong>{$affectedRows}</strong><br>";
                            echo "Tiempo de ejecución: <strong>{$executionTime} ms</strong>";
                            echo '</div></div>';
                        } else {
                            // Para queries SELECT
                            $numRows = $result->num_rows;
                            $numFields = $result->field_count;
                            
                            echo '<div class="result-container">';
                            echo '<div class="query-info">';
                            echo "<strong>Resultados:</strong> {$numRows} filas | ";
                            echo "<strong>Columnas:</strong> {$numFields} | ";
                            echo "<strong>Tiempo:</strong> {$executionTime} ms";
                            echo '</div>';
                            
                            if ($numRows > 0) {
                                echo '<div class="table-responsive">';
                                echo '<table id="resultTable" class="table table-striped table-bordered table-sm">';
                                
                                // Headers
                                echo '<thead class="thead-dark"><tr>';
                                $fields = $result->fetch_fields();
                                foreach ($fields as $field) {
                                    echo '<th>' . htmlspecialchars($field->name) . '</th>';
                                }
                                echo '</tr></thead>';
                                
                                // Data
                                echo '<tbody>';
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    foreach ($row as $value) {
                                        $displayValue = $value !== null ? htmlspecialchars(substr($value, 0, 500)) : '<em class="text-muted">NULL</em>';
                                        if (strlen($value) > 500) {
                                            $displayValue .= '...';
                                        }
                                        echo '<td>' . $displayValue . '</td>';
                                    }
                                    echo '</tr>';
                                }
                                echo '</tbody>';
                                echo '</table>';
                                echo '</div>';
                            } else {
                                echo '<div class="alert alert-info">La consulta no devolvió resultados.</div>';
                            }
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
            </div>
        </div>
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
                            text: '📊 Exportar Excel',
                            className: 'btn btn-success btn-sm'
                        },
                        {
                            extend: 'csvHtml5',
                            text: '📄 Exportar CSV',
                            className: 'btn btn-info btn-sm'
                        },
                        {
                            extend: 'colvis',
                            text: '👁 Columnas',
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

        // Ejecutar con Ctrl+Enter
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

        // Auto-focus en el editor
        document.getElementById('sql_query').focus();
    </script>
</body>
</html>
