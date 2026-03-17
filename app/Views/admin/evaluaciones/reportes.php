<?= $this->extend('admin/layouts/mainAdmin') ?>

<?= $this->section('styles') ?>
<!-- CSS personalizado para reportes -->
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/documentos.css') ?>" />
<!-- Chart.js para gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .report-card {
        transition: all 0.3s ease;
        border-left: 4px solid #007bff;
    }

    .report-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .report-card.pdf {
        border-left-color: #dc3545;
    }

    .report-card.excel {
        border-left-color: #28a745;
    }

    .report-card.csv {
        border-left-color: #ffc107;
    }

    .report-card.graficos {
        border-left-color: #6f42c1;
    }

    .chart-container {
        position: relative;
        height: 300px;
        margin: 20px 0;
    }

    .filter-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .export-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .export-btn {
        min-width: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
        margin-bottom: 40px;
        width: 100%;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .stat-card {
        background: transparent;
        color: #333;
        padding: 0;
        border-radius: 0;
        text-align: left;
        border: none;
        box-shadow: none;
    }

    .stat-card h3 {
        font-size: 2.5rem;
        margin: 0;
        font-weight: 600;
        color: #333;
        line-height: 1;
    }

    .stat-card p {
        margin: 8px 0 0 0;
        color: #666;
        font-size: 0.9rem;
        font-weight: 400;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h3 class="mb-0" style="color: #333; font-weight: 600;">
                    <i class="fas fa-list me-2" style="color: #007bff;"></i>
                    Reportes y Exportación de Evaluaciones
                </h3>
                <button class="btn btn-outline-primary" onclick="history.back()" style="border-radius: 20px; padding: 8px 20px;">
                    <i class="fas fa-arrow-left me-1"></i>Volver
                </button>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3 id="totalEvaluacionesReporte">0</h3>
                <p>Total Evaluaciones</p>
            </div>
            <div class="stat-card">
                <h3 id="evaluacionesActivasReporte">0</h3>
                <p>Evaluaciones Activas</p>
            </div>
            <div class="stat-card">
                <h3 id="totalRespuestasReporte">0</h3>
                <p>Total Respuestas</p>
            </div>
            <div class="stat-card">
                <h3 id="promedioRespuestasReporte">0</h3>
                <p>Promedio Respuestas</p>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filter-section">
            <h5 class="mb-3" style="color: #333; font-weight: 500;">
                <i class="fas fa-chevron-down me-2" style="color: #666;"></i>
                Filtros de Búsqueda
            </h5>
            <form id="formFiltrosReportes">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Tipo de Evaluación</label>
                            <select class="form-select" name="tipo" id="filtroTipo">
                                <option value="">Todos los tipos</option>
                                <?php foreach ($tipos_evaluacion as $key => $value): ?>
                                    <option value="<?= $key ?>"><?= $value ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="estado" id="filtroEstado">
                                <option value="">Todos los estados</option>
                                <?php foreach ($estados as $estado): ?>
                                    <option value="<?= $estado ?>"><?= ucfirst($estado) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Curso</label>
                            <select class="form-select" name="curso" id="filtroCurso">
                                <option value="">Todos los cursos</option>
                                <?php foreach ($cursos as $curso): ?>
                                    <option value="<?= $curso['ID_ACTIVIDAD_EDUCACION'] ?>"><?= $curso['NOMBRE_ACTIVIDAD'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Fecha Desde</label>
                            <input type="date" class="form-control" name="fecha_desde" id="filtroFechaDesde">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Fecha Hasta</label>
                            <input type="date" class="form-control" name="fecha_hasta" id="filtroFechaHasta">
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="mb-3 d-flex align-items-end">
                            <button type="button" class="btn btn-primary me-2" onclick="aplicarFiltrosReportes()">
                                <i class="fas fa-search me-1"></i>Aplicar Filtros
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="limpiarFiltrosReportes()">
                                <i class="fas fa-times me-1"></i>Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Opciones de Exportación -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-download me-2"></i>
                            Opciones de Exportación
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="export-buttons">
                            <button class="btn btn-danger export-btn" onclick="generarPDF()">
                                <i class="fas fa-file-pdf"></i>
                                Exportar PDF
                            </button>
                            <button class="btn btn-success export-btn" onclick="exportarExcel()">
                                <i class="fas fa-file-excel"></i>
                                Exportar Excel
                            </button>
                            <button class="btn btn-info export-btn" onclick="mostrarGraficos()">
                                <i class="fas fa-chart-pie"></i>
                                Ver Gráficos
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="row" id="seccionGraficos" style="display: none;">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-bar me-2"></i>
                            Evaluaciones por Tipo
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="graficoPorTipo"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-pie me-2"></i>
                            Evaluaciones por Estado
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="graficoPorEstado"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de líneas -->
        <div class="row mt-4" id="seccionGraficoLineas" style="display: none;">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-warning text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-line me-2"></i>
                            Evaluaciones por Mes
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="graficoPorMes"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vista previa de datos -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-table me-2"></i>
                            Vista Previa de Datos
                        </span>
                        <button class="btn btn-light btn-sm" onclick="actualizarVistaPrevia()">
                            <i class="fas fa-sync-alt me-1"></i>Actualizar
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Tipo</th>
                                        <th>Curso</th>
                                        <th>Estado</th>
                                        <th>Fecha Creación</th>
                                        <th>Vencimiento</th>
                                        <th>Respuestas</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaVistaPrevia">
                                    <!-- Los datos se cargarán dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    let datosGraficos = {};
    let graficoPorTipo = null;
    let graficoPorEstado = null;
    let graficoPorMes = null;

    // Funciones principales
    function aplicarFiltrosReportes() {
        cargarEstadisticasReportes();
        actualizarVistaPrevia();
        showNotification('Filtros aplicados correctamente', 'success');
    }

    function limpiarFiltrosReportes() {
        document.getElementById('formFiltrosReportes').reset();
        cargarEstadisticasReportes();
        actualizarVistaPrevia();
        showNotification('Filtros limpiados', 'info');
    }

    function generarPDF() {
        const filtros = obtenerFiltrosActuales();
        const url = `<?= base_url('admin/reportes-evaluaciones/pdf') ?>?${new URLSearchParams(filtros).toString()}`;
        window.open(url, '_blank');
        showNotification('Generando reporte PDF...', 'info');
    }

    function exportarExcel() {
        const filtros = obtenerFiltrosActuales();
        const url = `<?= base_url('admin/reportes-evaluaciones/excel') ?>?${new URLSearchParams(filtros).toString()}`;
        window.open(url, '_blank');
        showNotification('Exportando a Excel...', 'info');
    }

    function exportarCSV() {
        const filtros = obtenerFiltrosActuales();
        const url = `<?= base_url('admin/reportes-evaluaciones/csv') ?>?${new URLSearchParams(filtros).toString()}`;
        window.open(url, '_blank');
        showNotification('Exportando a CSV...', 'info');
    }

    function mostrarGraficos() {
        const seccionGraficos = document.getElementById('seccionGraficos');
        const seccionGraficoLineas = document.getElementById('seccionGraficoLineas');

        if (seccionGraficos.style.display === 'none') {
            seccionGraficos.style.display = 'block';
            seccionGraficoLineas.style.display = 'block';
            cargarDatosGraficos();
        } else {
            seccionGraficos.style.display = 'none';
            seccionGraficoLineas.style.display = 'none';
        }
    }

    function cargarDatosGraficos() {
        const filtros = obtenerFiltrosActuales();
        const url = `<?= base_url('admin/reportes-evaluaciones/graficos') ?>?${new URLSearchParams(filtros).toString()}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    datosGraficos = data.data;
                    crearGraficos();
                } else {
                    showNotification('Error al cargar datos para gráficos', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al cargar datos para gráficos', 'error');
            });
    }

    function crearGraficos() {
        // Gráfico de barras - Por tipo
        const ctxTipo = document.getElementById('graficoPorTipo').getContext('2d');
        if (graficoPorTipo) graficoPorTipo.destroy();

        graficoPorTipo = new Chart(ctxTipo, {
            type: 'bar',
            data: {
                labels: Object.keys(datosGraficos.por_tipo),
                datasets: [{
                    label: 'Cantidad',
                    data: Object.values(datosGraficos.por_tipo),
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ],
                    borderColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Gráfico de dona - Por estado
        const ctxEstado = document.getElementById('graficoPorEstado').getContext('2d');
        if (graficoPorEstado) graficoPorEstado.destroy();

        graficoPorEstado = new Chart(ctxEstado, {
            type: 'doughnut',
            data: {
                labels: Object.keys(datosGraficos.por_estado),
                datasets: [{
                    data: Object.values(datosGraficos.por_estado),
                    backgroundColor: [
                        '#28a745',
                        '#dc3545',
                        '#ffc107'
                    ],
                    borderColor: [
                        '#28a745',
                        '#dc3545',
                        '#ffc107'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Gráfico de líneas - Por mes
        const ctxMes = document.getElementById('graficoPorMes').getContext('2d');
        if (graficoPorMes) graficoPorMes.destroy();

        const meses = datosGraficos.por_mes.map(item => item.mes);
        const cantidades = datosGraficos.por_mes.map(item => item.total);

        graficoPorMes = new Chart(ctxMes, {
            type: 'line',
            data: {
                labels: meses,
                datasets: [{
                    label: 'Evaluaciones creadas',
                    data: cantidades,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    function cargarEstadisticasReportes() {
        const filtros = obtenerFiltrosActuales();
        const url = `<?= base_url('admin/evaluaciones/estadisticas') ?>?${new URLSearchParams(filtros).toString()}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('totalEvaluacionesReporte').textContent = data.data.total;
                    document.getElementById('evaluacionesActivasReporte').textContent = data.data.activas;
                    document.getElementById('totalRespuestasReporte').textContent = data.data.total_respuestas;
                    document.getElementById('promedioRespuestasReporte').textContent = data.data.promedio_respuestas;
                }
            })
            .catch(error => {
                console.error('Error cargando estadísticas:', error);
            });
    }

    function actualizarVistaPrevia() {
        const filtros = obtenerFiltrosActuales();
        const url = `<?= base_url('admin/evaluaciones/filtros') ?>`;

        const formData = new FormData();
        Object.keys(filtros).forEach(key => {
            if (filtros[key]) {
                formData.append(key, filtros[key]);
            }
        });

        fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarVistaPrevia(data.data);
                } else {
                    showNotification('Error al cargar vista previa', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al cargar vista previa', 'error');
            });
    }

    function mostrarVistaPrevia(evaluaciones) {
        const tbody = document.getElementById('tablaVistaPrevia');
        tbody.innerHTML = '';

        evaluaciones.forEach(eval => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${eval.id}</td>
                <td>${eval.nombre}</td>
                <td><span class="badge bg-secondary">${eval.tipo}</span></td>
                <td>${eval.curso}</td>
                <td><span class="badge bg-${eval.estado === 'activo' ? 'success' : eval.estado === 'inactivo' ? 'danger' : 'warning'}">${eval.estado}</span></td>
                <td>${formatearFecha(eval.fecha_creacion)}</td>
                <td>${formatearFecha(eval.fecha_vencimiento)}</td>
                <td class="text-center">${eval.respuestas}</td>
            `;
            tbody.appendChild(row);
        });
    }

    function obtenerFiltrosActuales() {
        return {
            tipo: document.getElementById('filtroTipo').value,
            estado: document.getElementById('filtroEstado').value,
            curso: document.getElementById('filtroCurso').value,
            fecha_desde: document.getElementById('filtroFechaDesde').value,
            fecha_hasta: document.getElementById('filtroFechaHasta').value
        };
    }

    function formatearFecha(fecha) {
        return new Date(fecha).toLocaleDateString('es-ES');
    }

    function showNotification(message, type = 'info') {
        const colors = {
            success: '#27ae60',
            error: '#e74c3c',
            warning: '#f39c12',
            info: '#3498db'
        };

        const notification = document.createElement('div');
        notification.className = 'position-fixed top-0 end-0 m-3';
        notification.style.zIndex = '9999';
        notification.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert" style="background: ${colors[type]}; color: white; border: none; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Inicialización
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Vista de reportes cargada');
        cargarEstadisticasReportes();
        actualizarVistaPrevia();
    });
</script>
<?= $this->endSection() ?>