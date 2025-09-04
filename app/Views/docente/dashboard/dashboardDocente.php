<?= $this->extend('docente/layouts/mainDocente') ?>

<?= $this->section('styles') ?>
<!-- Chart.js para gráficas -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- CSS personalizado para el dashboard -->
<style>
    .metric-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 15px;
    }
    
    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    
    .metric-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }
    
    .chart-container {
        position: relative;
        height: 300px;
        margin: 20px 0;
    }
    
    .welcome-card {
        background: #f8f9fa;
        color: #333;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid #e9ecef;
    }
    
    .quick-action-btn {
        border-radius: 10px;
        padding: 12px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header del Dashboard -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">
                            <i class="fas fa-tachometer-alt me-2 text-primary"></i>
                            Panel de Control
                        </h2>
                        <p class="text-muted mb-0">Bienvenido al Sistema del Departamento de Vinculación</p>
                    </div>                    
                    <div class="text-end">
                    <span class="badge bg-light text-dark fs-6 mb-2">Docente</span>
                        <p class="mb-0 text-muted">
                            <i class="fas fa-calendar-alt me-1"></i>
                            <?= date('d/m/Y') ?>
                        </p>
                        <p class="mb-0 text-muted">
                            <i class="fas fa-clock me-1"></i>
                            <span id="currentTime"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Métricas Principales -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card metric-card text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <div class="card-body">
                        <div class="metric-icon mx-auto mb-3" style="background: rgba(255,255,255,0.2);">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3 class="mb-1"><?= $total_actividades ?? 0 ?></h3>
                        <p class="mb-0">Total Actividades</p>
                        <small class="opacity-75">Cursos y talleres</small>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card metric-card text-center" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                    <div class="card-body">
                        <div class="metric-icon mx-auto mb-3" style="background: rgba(255,255,255,0.2);">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <h3 class="mb-1"><?= $actividades_activas ?? 0 ?></h3>
                        <p class="mb-0">Actividades Activas</p>
                        <small class="opacity-75">En progreso</small>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card metric-card text-center" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                    <div class="card-body">
                        <div class="metric-icon mx-auto mb-3" style="background: rgba(255,255,255,0.2);">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="mb-1"><?= $total_estudiantes ?? 0 ?></h3>
                        <p class="mb-0">Total Estudiantes</p>
                        <small class="opacity-75">Registrados</small>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card metric-card text-center" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                    <div class="card-body">
                        <div class="metric-icon mx-auto mb-3" style="background: rgba(255,255,255,0.2);">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3 class="mb-1">4.8</h3>
                        <p class="mb-0">Calificación</p>
                        <small class="opacity-75">Promedio</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Acciones Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="<?= base_url('docente/educacion') ?>" class="btn btn-primary quick-action-btn w-100">
                                    <i class="fas fa-plus-circle me-2"></i>
                                    Nueva Actividad
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="<?= base_url('docente/actividades') ?>" class="btn btn-success quick-action-btn w-100">
                                    <i class="fas fa-list me-2"></i>
                                    Mis Actividades
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="<?= base_url('docente/estudiantes') ?>" class="btn btn-info quick-action-btn w-100">
                                    <i class="fas fa-users me-2"></i>
                                    Ver Estudiantes
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="<?= base_url('docente/perfil') ?>" class="btn btn-warning quick-action-btn w-100">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Mi Perfil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficas y Estadísticas -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Actividades por Mes</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="actividadesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Distribución de Actividades</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="distribucionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actividades Recientes -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Actividades Recientes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Actividad</th>
                                        <th>Tipo</th>
                                        <th>Fecha Inicio</th>
                                        <th>Estado</th>
                                        <th>Estudiantes</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No hay actividades registradas aún
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Actualizar fecha y hora en tiempo real
    function actualizarFechaHora() {
        try {
            const ahora = new Date();
            const opcionesFecha = { 
                day: '2-digit',
                month: '2-digit', 
                year: 'numeric'
            };
            const opcionesHora = { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: true
            };
            
            const fechaElement = document.getElementById('fechaActual');
            const horaElement = document.getElementById('horaActual');
            
            if (fechaElement && horaElement) {
                fechaElement.textContent = ahora.toLocaleDateString('es-ES', opcionesFecha);
                horaElement.textContent = ahora.toLocaleTimeString('es-ES', opcionesHora);
            }
        } catch (error) {
            console.error('Error al actualizar fecha y hora:', error);
        }
    }
    
    // Esperar a que el DOM esté completamente cargado
    document.addEventListener('DOMContentLoaded', function() {
        actualizarFechaHora(); // Llamar inmediatamente
        setInterval(actualizarFechaHora, 1000); // Actualizar cada segundo
    });

    // Gráfica de actividades por mes
    const ctxActividades = document.getElementById('actividadesChart').getContext('2d');
    new Chart(ctxActividades, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            datasets: [{
                label: 'Actividades',
                data: [2, 3, 1, 4, 2, 3, 5, 2, 4, 3, 2, 1],
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Gráfica de distribución
    const ctxDistribucion = document.getElementById('distribucionChart').getContext('2d');
    new Chart(ctxDistribucion, {
        type: 'doughnut',
        data: {
            labels: ['Cursos', 'Talleres', 'Seminarios'],
            datasets: [{
                data: [40, 35, 25],
                backgroundColor: [
                    '#667eea',
                    '#f093fb',
                    '#4facfe'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
</script>
<?= $this->endSection() ?>
