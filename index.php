<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Informes</title>

    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://s3-us-west-2.amazonaws.com/s.cdpn.io/1462889/unicons.css'>
    <link rel="icon" href="https://www.uniajc.edu.co/wp-content/uploads/2018/06/cropped-favicon-32x32.png"
        sizes="32x32">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet"
        type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="css/style-dashboard.css" rel="stylesheet">
</head>

<body id="page-top" class="sidebar-toggled" onload="loadProgram()">
    <!-- Page Wrapper -->
    <form name="data_form" action="report/tipo_reporte.php" method="POST" target="_blank">
        <div id="wrapper">
            <!-- Sidebar -->
            <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion show" id="accordionSidebar">

                <!-- Sidebar - Brand -->
                <a class="sidebar-brand d-flex align-items-center justify-content-center"
                    href="https://aulasvirtuales.uniajc.edu.co/">
                    <div class="sidebar-brand-icon">
                        <!-- <i class="fas fa-laugh-wink"></i> -->
                        <img class="img-profile" src="resources\img\uniajcEstadeModaNegro.png" width=150" height="50">
                    </div>
                </a>
                <!-- Divider -->
                <hr class="sidebar-divider my-0">
                <!-- Divider -->
                <hr class="sidebar-divider">
                <!-- Nav Item - Pages Collapse Menu -->
                <li class="nav-item active">
                    <a class="nav-link " href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
                        aria-controls="collapseTwo">
                        <i class="fas fa-fw fa-cog"></i>
                        <span>INFORMES</span>
                    </a>
                    <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo"
                        data-parent="#accordionSidebar">
                        <div class="py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Tipo de informe:</h6>
                            <select name="report" id="report" class="select-inform" size="3" required>
                                <option value="1" class="collapse-item">Alistamiento</option>
                                <option value="2" class="collapse-item">Avance formativo 1</option>
                                <option value="3" class="collapse-item">Avance formativo 2</option>
                                <option value="4" class="collapse-item">Estadisticas</option>
                                <option value="5" class="collapse-item">Estadistica Institucionales</option>
                                <option value="6" class="collapse-item">Estadistica Ingles</option>
                            </select>
                        </div>
                    </div>
                </li>
            </ul>
            <!-- End of Sidebar -->
            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
                <!-- Main Content -->
                <div id="content">
                    <!-- Topbar -->
                    <nav class="navbar navbar-expand navbar-light bg-gradient topbar mb-4 static-top shadow">
                        <!-- Sidebar Toggle (Topbar) -->
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                        <!-- Topbar Search -->

                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav ml-auto">

                            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                            <li class="nav-item dropdown no-arrow d-sm-none">
                                <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-search fa-fw"></i>
                                </a>
                                <!-- Dropdown - Messages -->
                                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                    aria-labelledby="searchDropdown">
                                    <form class="form-inline mr-auto w-100 navbar-search">
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light border-0 small"
                                                placeholder="Search for..." aria-label="Search"
                                                aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button">
                                                    <i class="fas fa-search fa-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </li>
                            <div class="topbar-divider d-none d-sm-block"></div>
                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="resources\img\uniajcEstadeModaBlanco.png" width="95" height="30">
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <!-- End of Topbar -->
                    <!-- Begin Page Content -->
                    <div class="container-fluid">
                        <!-- Page Heading -->
                        <div id="category">
                            <!-- Link to table -->
                            <div class="d-sm-flex justify-content-between mb-4">
                                <h1 id="title-prg" class="h3 mb-0 d-none mr-5">Programas</h1>
                                <div id="select" class="select d-none">
                                    <select name="category" id="select-category" onfocus='this.size=4;'
                                        onblur='this.size=1;' onchange='this.size=1; this.blur(); loadProgram();'
                                        class="custom-select p-l-1" required>
                                        <option hidden selected value="">Categorias</option>
                                        <?php require_once('selectors/category.php');
                                        selectCategory(); ?>
                                    </select>
                                </div>
                                <div class="">
                                    <input id="generate" class="btn btn1 d-flex p-3 " type="submit" value="Generar">
                                </div>
                            </div>
                        </div>

                        <!-- Content Row -->
                        <div id="program-card" class="row d-none">
                            <div class="col-12 pb-5">
                                <div name="programs" id="programs"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->
        </div>
        <!-- End of Page Wrapper -->
    </form>
    <!-- Page level plugins -->
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
                success: function (data) {
                    $("#programs").html(data);
                }
            })
        }
    </script>
</body>

</html>