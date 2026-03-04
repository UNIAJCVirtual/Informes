<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informes Moodle</title>
    <link rel="icon" href="resources/img/logoCamacho.png" sizes="32x32">
    <link rel="stylesheet" href="css/theme.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/theme.js" defer></script>
</head>
<body>
    <div class="app-layout">
        <!-- Backdrop móvil -->
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
        
        <!-- Sidebar -->
        <aside class="app-sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="resources/img/logoCamacho.png" alt="Logo" class="sidebar-logo">
                <span class="sidebar-brand">Informes</span>
            </div>
            
            <nav class="sidebar-nav">
                <div class="sidebar-section">
                    <span class="sidebar-section-title">Reportes</span>
                    
                    <button type="button" class="sidebar-item" data-report="1">
                        <svg class="sidebar-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <span>Alistamiento</span>
                    </button>
                    
                    <button type="button" class="sidebar-item" data-report="2">
                        <svg class="sidebar-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span>Avance Formativo 1</span>
                    </button>
                    
                    <button type="button" class="sidebar-item" data-report="3">
                        <svg class="sidebar-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span>Avance Formativo 2</span>
                    </button>
                    
                    <button type="button" class="sidebar-item" data-report="4">
                        <svg class="sidebar-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span>Avance Formativo 3</span>
                    </button>
                    
                    <button type="button" class="sidebar-item" data-report="5">
                        <svg class="sidebar-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span>Estadísticas</span>
                    </button>
                    
                    <button type="button" class="sidebar-item" data-report="6">
                        <svg class="sidebar-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Institucionales</span>
                    </button>
                    
                    <button type="button" class="sidebar-item" data-report="7">
                        <svg class="sidebar-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                        </svg>
                        <span>Inglés</span>
                    </button>
                </div>
                
                <div class="sidebar-section">
                    <span class="sidebar-section-title">Herramientas</span>
                    
                    <a href="./report/sql_console.php" class="sidebar-item" target="_blank">
                        <svg class="sidebar-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Consola SQL</span>
                    </a>
                    
                    <a href="./report/tipo_reporte.php" class="sidebar-item">
                        <svg class="sidebar-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span>Otras Consultas</span>
                    </a>
                </div>
            </nav>
        </aside>
        
        <!-- Contenido Principal -->
        <main class="app-main">
            <header class="app-topbar">
                <div class="topbar-left">
                    <button type="button" class="topbar-btn" id="menuToggle" aria-label="Menú">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <h1 class="topbar-title" id="pageTitle">Generador de Informes</h1>
                </div>
                <div class="topbar-right">
                    <button type="button" class="topbar-btn theme-toggle" aria-label="Cambiar tema">
                        <svg class="theme-toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                        </svg>
                    </button>
                </div>
            </header>
            
            <div class="app-content">
                <form name="data_form" id="reportForm" action="report/tipo_reporte.php" method="POST" target="_blank">
                    <input type="hidden" name="report" id="reportType" value="">
                    
                    <!-- Paso 1: Selección de reporte -->
                    <section id="step1" class="fade-in">
                        <div class="card mb-lg">
                            <div class="card-header">
                                <h2 class="card-title">Selecciona el tipo de informe</h2>
                            </div>
                            <div class="card-body">
                                <div class="report-grid" id="reportGrid">
                                    <label class="report-card stagger-item" data-report="1">
                                        <input type="radio" name="report_select" value="1">
                                        <div class="report-card-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                            </svg>
                                        </div>
                                        <h3 class="report-card-title">Alistamiento</h3>
                                        <p class="report-card-desc">Verifica el cumplimiento de criterios de alistamiento en los cursos</p>
                                    </label>
                                    
                                    <label class="report-card stagger-item" data-report="2">
                                        <input type="radio" name="report_select" value="2">
                                        <div class="report-card-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                        </div>
                                        <h3 class="report-card-title">Avance Formativo 1</h3>
                                        <p class="report-card-desc">Seguimiento de progreso en el primer corte académico</p>
                                    </label>
                                    
                                    <label class="report-card stagger-item" data-report="3">
                                        <input type="radio" name="report_select" value="3">
                                        <div class="report-card-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                        </div>
                                        <h3 class="report-card-title">Avance Formativo 2</h3>
                                        <p class="report-card-desc">Seguimiento de progreso en el segundo corte académico</p>
                                    </label>
                                    
                                    <label class="report-card stagger-item" data-report="4">
                                        <input type="radio" name="report_select" value="4">
                                        <div class="report-card-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                        </div>
                                        <h3 class="report-card-title">Avance Formativo 3</h3>
                                        <p class="report-card-desc">Seguimiento de progreso en el tercer corte académico</p>
                                    </label>
                                    
                                    <label class="report-card stagger-item" data-report="5">
                                        <input type="radio" name="report_select" value="5">
                                        <div class="report-card-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                        </div>
                                        <h3 class="report-card-title">Estadísticas</h3>
                                        <p class="report-card-desc">Análisis estadístico completo de cursos y participación</p>
                                    </label>
                                    
                                    <label class="report-card stagger-item" data-report="6">
                                        <input type="radio" name="report_select" value="6">
                                        <div class="report-card-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <h3 class="report-card-title">Institucionales</h3>
                                        <p class="report-card-desc">Estadísticas de cursos institucionales transversales</p>
                                    </label>
                                    
                                    <label class="report-card stagger-item" data-report="7">
                                        <input type="radio" name="report_select" value="7">
                                        <div class="report-card-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                            </svg>
                                        </div>
                                        <h3 class="report-card-title">Inglés</h3>
                                        <p class="report-card-desc">Estadísticas de cursos de idiomas</p>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </section>
                    
                    <!-- Paso 2: Selección de categoría/programa -->
                    <section id="step2" class="hidden fade-in">
                        <div class="card mb-lg">
                            <div class="card-header">
                                <h2 class="card-title" id="step2Title">Selecciona la categoría y programas</h2>
                                <button type="button" class="btn btn-ghost btn-sm" id="backToStep1">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                                    </svg>
                                    Volver
                                </button>
                            </div>
                            <div class="card-body">
                                <!-- Selector de categoría (reportes 1-5) -->
                                <div id="categorySelector" class="form-group">
                                    <label for="select-category" class="form-label">Categoría</label>
                                    <select name="category" id="select-category" class="form-control form-select">
                                        <option hidden selected value="">Selecciona una categoría</option>
                                        <?php require_once('selectors/category.php'); selectCategory(); ?>
                                    </select>
                                </div>
                                
                                <!-- Selector institucionales (reporte 6) -->
                                <div id="institucionalSelector" class="form-group hidden">
                                    <label for="selectInsti" class="form-label">Curso Institucional</label>
                                    <select name="selectInsti" id="selectInsti" class="form-control form-select">
                                        <option hidden selected value="">Selecciona un institucional</option>
                                        <option value="1">Cátedra Institucional</option>
                                        <option value="2">Constitución Política</option>
                                        <option value="3">Liderazgo y Emprendimiento</option>
                                        <option value="4">Medio Ambiente</option>
                                    </select>
                                </div>
                                
                                <!-- Input inglés (reporte 7) -->
                                <div id="englishSelector" class="form-group hidden">
                                    <label for="english" class="form-label">ID de Categoría de Inglés</label>
                                    <input type="number" name="english-querrys" id="english" class="form-control" placeholder="Ingresa el ID de la categoría">
                                </div>
                                
                                <!-- Lista de programas -->
                                <div id="programsContainer" class="mt-lg hidden">
                                    <div class="flex justify-between items-center mb-md">
                                        <label class="form-label" style="margin-bottom: 0;">Programas disponibles</label>
                                        <div class="flex gap-sm">
                                            <button type="button" class="btn btn-sm btn-secondary" id="selectAllBtn">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M9 11l3 3L22 4"/>
                                                    <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                                                </svg>
                                                Seleccionar todo
                                            </button>
                                            <button type="button" class="btn btn-sm btn-ghost" id="deselectAllBtn">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                                </svg>
                                                Deseleccionar
                                            </button>
                                        </div>
                                    </div>
                                    <div id="programs" class="program-list"></div>
                                </div>
                            </div>
                            <div class="card-footer flex justify-between items-center">
                                <span class="text-muted text-sm" id="selectedCount">0 programas seleccionados</span>
                                <button type="submit" class="btn btn-lg" id="generateBtn" disabled>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Generar Informe
                                </button>
                            </div>
                        </div>
                    </section>
                </form>
            </div>
            
            <?php include('helpers/footer.php'); ?>
        </main>
    </div>
    
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>
    
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/sweetAlert.js"></script>
    <script>
    (function() {
        'use strict';
        
        // Estado de la aplicación
        const state = {
            selectedReport: null,
            selectedPrograms: []
        };
        
        // Elementos DOM
        const elements = {
            step1: document.getElementById('step1'),
            step2: document.getElementById('step2'),
            reportGrid: document.getElementById('reportGrid'),
            reportType: document.getElementById('reportType'),
            categorySelector: document.getElementById('categorySelector'),
            institucionalSelector: document.getElementById('institucionalSelector'),
            englishSelector: document.getElementById('englishSelector'),
            programsContainer: document.getElementById('programsContainer'),
            programs: document.getElementById('programs'),
            selectCategory: document.getElementById('select-category'),
            generateBtn: document.getElementById('generateBtn'),
            selectedCount: document.getElementById('selectedCount'),
            backToStep1: document.getElementById('backToStep1'),
            pageTitle: document.getElementById('pageTitle'),
            step2Title: document.getElementById('step2Title'),
            sidebar: document.getElementById('sidebar'),
            sidebarBackdrop: document.getElementById('sidebarBackdrop'),
            menuToggle: document.getElementById('menuToggle'),
            loadingOverlay: document.getElementById('loadingOverlay')
        };
        
        // Nombres de reportes
        const reportNames = {
            1: 'Alistamiento',
            2: 'Avance Formativo 1',
            3: 'Avance Formativo 2',
            4: 'Avance Formativo 3',
            5: 'Estadísticas',
            6: 'Institucionales',
            7: 'Inglés'
        };
        
        // Inicialización
        function init() {
            bindEvents();
        }
        
        // Vincular eventos
        function bindEvents() {
            // Selección de reporte desde grid
            elements.reportGrid.addEventListener('click', handleReportCardClick);
            
            // Selección de reporte desde sidebar
            document.querySelectorAll('[data-report]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const reportId = e.currentTarget.dataset.report;
                    if (reportId) selectReport(parseInt(reportId));
                });
            });
            
            // Cambio de categoría
            elements.selectCategory.addEventListener('change', loadPrograms);
            
            // Volver al paso 1
            elements.backToStep1.addEventListener('click', goToStep1);
            
            // Toggle sidebar móvil
            elements.menuToggle.addEventListener('click', toggleSidebar);
            elements.sidebarBackdrop.addEventListener('click', closeSidebar);
            
            // Validación de formulario
            document.getElementById('reportForm').addEventListener('submit', validateForm);
            
            // Observar cambios en programas
            elements.programs.addEventListener('change', updateProgramCount);
            
            // Delegación de eventos para checkboxes de programas
            elements.programs.addEventListener('click', handleProgramClick);
            
            // Botones seleccionar/deseleccionar todo
            document.getElementById('selectAllBtn').addEventListener('click', selectAllPrograms);
            document.getElementById('deselectAllBtn').addEventListener('click', deselectAllPrograms);
        }
        
        // Seleccionar todos los programas
        function selectAllPrograms() {
            document.querySelectorAll('.checkbox-program').forEach(cb => {
                cb.checked = true;
                cb.closest('.program-item')?.classList.add('selected');
            });
            updateProgramCount();
        }
        
        // Deseleccionar todos los programas
        function deselectAllPrograms() {
            document.querySelectorAll('.checkbox-program').forEach(cb => {
                cb.checked = false;
                cb.closest('.program-item')?.classList.remove('selected');
            });
            updateProgramCount();
        }
        
        // Manejar click en item de programa (delegación de eventos)
        function handleProgramClick(e) {
            const item = e.target.closest('.program-item');
            if (!item) return;
            
            const checkbox = item.querySelector('.checkbox-program');
            if (!checkbox) return;
            
            // Si el click no fue directamente en el checkbox, toggle manualmente
            if (e.target !== checkbox) {
                checkbox.checked = !checkbox.checked;
            }
            
            // Actualizar clase selected
            item.classList.toggle('selected', checkbox.checked);
            updateProgramCount();
        }
        
        // Manejar click en tarjeta de reporte
        function handleReportCardClick(e) {
            const card = e.target.closest('.report-card');
            if (!card) return;
            
            const reportId = parseInt(card.dataset.report);
            selectReport(reportId);
        }
        
        // Seleccionar reporte
        function selectReport(reportId) {
            state.selectedReport = reportId;
            elements.reportType.value = reportId;
            
            // Actualizar UI
            document.querySelectorAll('.report-card').forEach(c => c.classList.remove('selected'));
            document.querySelector(`.report-card[data-report="${reportId}"]`)?.classList.add('selected');
            
            document.querySelectorAll('.sidebar-item[data-report]').forEach(item => {
                item.classList.toggle('active', parseInt(item.dataset.report) === reportId);
            });
            
            // Actualizar título
            elements.pageTitle.textContent = reportNames[reportId];
            
            // Configurar paso 2 según tipo de reporte
            configureStep2(reportId);
            
            // Ir al paso 2
            goToStep2();
        }
        
        // Configurar paso 2 según reporte
        function configureStep2(reportId) {
            // Ocultar todos los selectores
            elements.categorySelector.classList.add('hidden');
            elements.institucionalSelector.classList.add('hidden');
            elements.englishSelector.classList.add('hidden');
            elements.programsContainer.classList.add('hidden');
            
            if (reportId >= 1 && reportId <= 5) {
                // Reportes estándar: categoría + programas
                elements.categorySelector.classList.remove('hidden');
                elements.step2Title.textContent = 'Selecciona la categoría y programas';
            } else if (reportId === 6) {
                // Institucionales
                elements.institucionalSelector.classList.remove('hidden');
                elements.step2Title.textContent = 'Selecciona el curso institucional';
                elements.generateBtn.disabled = false;
            } else if (reportId === 7) {
                // Inglés
                elements.englishSelector.classList.remove('hidden');
                elements.step2Title.textContent = 'Ingresa el ID de la categoría de inglés';
                elements.generateBtn.disabled = false;
            }
        }
        
        // Ir al paso 1
        function goToStep1() {
            elements.step2.classList.add('hidden');
            elements.step1.classList.remove('hidden');
            elements.pageTitle.textContent = 'Generador de Informes';
            state.selectedReport = null;
            elements.programs.innerHTML = '';
            elements.programsContainer.classList.add('hidden');
            
            document.querySelectorAll('.sidebar-item[data-report]').forEach(item => {
                item.classList.remove('active');
            });
        }
        
        // Ir al paso 2
        function goToStep2() {
            elements.step1.classList.add('hidden');
            elements.step2.classList.remove('hidden');
            closeSidebar();
        }
        
        // Cargar programas
        function loadPrograms() {
            const category = elements.selectCategory.value;
            if (!category) {
                elements.programsContainer.classList.add('hidden');
                return;
            }
            
            showLoading();
            
            $.ajax({
                url: 'selectors/program.php',
                data: { category: category },
                type: 'POST',
                success: function(data) {
                    elements.programs.innerHTML = data;
                    elements.programsContainer.classList.remove('hidden');
                    updateProgramCount();
                    hideLoading();
                },
                error: function() {
                    hideLoading();
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudieron cargar los programas',
                        icon: 'error',
                        toast: true,
                        position: 'bottom-end',
                        timer: 5000,
                        showConfirmButton: false
                    });
                }
            });
        }
        
        // Actualizar contador de programas
        function updateProgramCount() {
            const checked = document.querySelectorAll('.checkbox-program:checked').length;
            elements.selectedCount.textContent = `${checked} programa${checked !== 1 ? 's' : ''} seleccionado${checked !== 1 ? 's' : ''}`;
            elements.generateBtn.disabled = checked === 0 && state.selectedReport <= 5;
        }
        
        // Validar formulario
        function validateForm(e) {
            if (state.selectedReport >= 1 && state.selectedReport <= 5) {
                const checked = document.querySelectorAll('.checkbox-program:checked').length;
                if (checked === 0) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Advertencia',
                        text: 'Debes seleccionar por lo menos un programa.',
                        icon: 'warning',
                        toast: true,
                        position: 'bottom-end',
                        timer: 5000,
                        showConfirmButton: false,
                        showCloseButton: true
                    });
                    return false;
                }
            }
            return true;
        }
        
        // Toggle sidebar
        function toggleSidebar() {
            elements.sidebar.classList.toggle('open');
            elements.sidebarBackdrop.classList.toggle('active');
        }
        
        // Cerrar sidebar
        function closeSidebar() {
            elements.sidebar.classList.remove('open');
            elements.sidebarBackdrop.classList.remove('active');
        }
        
        // Mostrar loading
        function showLoading() {
            elements.loadingOverlay.classList.add('active');
        }
        
        // Ocultar loading
        function hideLoading() {
            elements.loadingOverlay.classList.remove('active');
        }
        
        // Iniciar
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
    </script>
</body>

</html>