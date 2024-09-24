<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Informes - Ingles</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
    <link rel="icon" href="resources/img/logoCamacho.png" sizes="32x32">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="css/style-dashboard.css" rel="stylesheet">
</head>

<body id="page-top" class="sidebar-toggled">
    <form name="data_form" action="report/tipo_reporte.php" method="POST" target="_blank" onsubmit="return validacion()">
        <div id="wrapper">
            <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion show" id="accordionSidebar">
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="https://aulasvirtuales.uniajc.edu.co/">
                    <div class="sidebar-brand-icon">
                        <img class="img-profile" src="resources\img\uniajcEstadeModaNegro.png" width=150" height="50">
                    </div>
                </a>
                <hr class="sidebar-divider my-0">
                <hr class="sidebar-divider">
                <li class="nav-item active">
                    <a class="nav-link " href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                        <i><img src="resources\img\settings.png" width="25" height="25"></i>
                        <span>INFORMES - Ingles</span>
                    </a>
                    <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Tipo de informe:</h6>
                            <select name="report" id="report" class="select-inform" size="3" required>
                                <option value="1" class="collapse-item">Alistamiento</option>
                                <option value="2" class="collapse-item">Avance formativo 1</option>
                                <option value="3" class="collapse-item">Avance formativo 2</option>
                                <option value="4" class="collapse-item">Avance formativo 3</option>
                                <option value="5" class="collapse-item">Estadisticas</option>
                                <option value="6" class="collapse-item">Estadistica Institucionales</option>
                                <option value="7" class="collapse-item">Estadistica Ingles</option>
                            </select>
                            <a href="./report/tipo_reporte.php" class="collapse-item">Otras consultas</a>
                        </div>
                    </div>
                </li>
            </ul>
            <div id="content-wrapper" class="d-flex flex-column">
                <div id="content">
                    <nav class="navbar navbar-expand navbar-light bg-gradient topbar mb-4 static-top shadow">
                        <ul class="navbar-nav ml-auto">
                            <div class="topbar-divider d-none d-sm-block"></div>
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="resources\img\uniajcEstadeModaBlanco.png" width="95" height="30">
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="container-fluid">
                        <div id="category">
                            <div class="d-sm-flex justify-content-between mb-4">
                                <h1 id="title-prg" class="h3 mb-0 d-none mr-5">Programas</h1>
                                <div id="div-select" class="select">
                                    <select name="category" id="select-category" onchange='loadProgram();' class="custom-select p-l-1 d-none">x
                                        <option hidden selected value="">Categorias</option>
                                        <?php require_once('selectors/category.php');
                                        selectCategory(); ?>
                                    </select>

                                    <select name="user-querrys" id="user-querrys" class="custom-select p-l-1 d-none">x
                                        <option hidden selected value="">Consultas</option>
                                        <option value="">Usuarios sin ingreso en la plataforma</option>
                                        <option value="">Usuarios sin ingreso en cursos</option>
                                        <option value="">Usuarios sin realizar actividades</option>
                                        <option value="">Matriculas de estudiantes</option>
                                        <option value="">Matriculaciones manuales</option>
                                    </select>
                                </div>
                                <div id="selectInstitucional" class="select d-none">
                                    <select name="selectInsti" id="selectInsti" class="custom-select p-l-1">
                                        <option hidden selected value="">Institucionales</option>
                                        <option value="1">Cátedra Institucional</option>
                                        <option value="2">Constitución Política</option>
                                        <option value="3">Liderazgo y Emprendimiento</option>
                                        <option value="4">Medio Ambiente</option>
                                    </select>
                                </div>
                                <div id="generate" class="d-none">
                                    <input id="generate" class="btn btn1 d-flex p-3 " name="enviar" type="submit" value="Generar">
                                </div>
                            </div>
                        </div>
                        <div id="program-card" class="row d-none">
                            <div class="col-12 pb-5">
                                <div name="programs" id="programs"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form name='data_form' action='#' method='POST'>
        <div class='div-other d-none' id='english-querrys'>
            <input type='number' placeholder='Ingresa el ID de la categoria' name='english-querrys' id='english' class='custom-select p-l-1' style='height: 40px;width: 250px;margin-top:20px'>
            <input id='generate' class='btn btn1 d-flex p-3' name='enviar' type='submit' value='Generar'>
        </div>
    </form>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/sweetAlert.js"></script>
    <script src="js/index.js"></script>
    <script type="text/javascript">
        function loadProgram() {
            let category = $("#select-category").val();
            $.ajax({
                url: 'selectors/program.php',
                data: {
                    category: category,
                },
                type: 'post',
                success: function(data) {
                    $("#programs").html(data);
                }
            })
        }
    </script>
    <script>
        function validacion() {
            var count = 0;
            var checks = document.querySelectorAll('.checkbox-program');
            checks.forEach((e) => {
                if (e.checked == true) {
                    count++;
                }
            });
            if (count > 0) {
                return true
            } else {
                Swal.fire({
                    title: 'Advertencia',
                    text: 'Debes seleccionar por lo menos un programa.',
                    icon: 'error',
                    backdrop: true,
                    timer: 8000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'bottom-end',
                    allowOutsideClick: true,
                    allowEscapeKey: true,
                    allowEnterKey: true,
                    showConfirmButton: false,
                    buttonsStyling: true,
                    showCloseButton: true
                })
                return false;
            }
        }
    </script>
</body>

</html>