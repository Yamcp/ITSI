<?= $this->extend('estudiante/layouts/mainEstudiante') ?>

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
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
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
    
    .progress-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header con saludo personalizado -->
        <div class="welcome-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2">
                        <i class="fas fa-user-graduate me-2"></i>
                        ¡Bienvenido, <?= session()->get('nombre') ?>!
                    </h2>
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <span id="fechaActual"></span> - 
                        <span id="horaActual"></span>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex flex-column align-items-end">
                        <span class="badge bg-light text-dark fs-6 mb-2">Estudiante</span>
                        <small class="opacity-75">Panel de Control</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Métricas Principales -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card metric-card text-center" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                    <div class="card-body">
                        <div class="metric-icon mx-auto mb-3" style="background: rgba(255,255,255,0.2);">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h3 class="mb-1"><?= $total_practicas ?? 0 ?></h3>
                        <p class="mb-0">Prácticas</p>
                        <small class="opacity-75">Asignadas</small>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card metric-card text-center" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                    <div class="card-body">
                        <div class="metric-icon mx-auto mb-3" style="background: rgba(255,255,255,0.2);">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <h3 class="mb-1"><?= $practicas_activas ?? 0 ?></h3>
                        <p class="mb-0">Activas</p>
                        <small class="opacity-75">En progreso</small>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card metric-card text-center" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                    <div class="card-body">
                        <div class="metric-icon mx-auto mb-3" style="background: rgba(255,255,255,0.2);">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3 class="mb-1"><?= $total_actividades ?? 0 ?></h3>
                        <p class="mb-0">Actividades</p>
                        <small class="opacity-75">Disponibles</small>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card metric-card text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <div class="card-body">
                        <div class="metric-icon mx-auto mb-3" style="background: rgba(255,255,255,0.2);">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3 class="mb-1">85%</h3>
                        <p class="mb-0">Progreso</p>
                        <small class="opacity-75">Académico</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progreso de Prácticas -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="progress-card">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-2">
                                <i class="fas fa-chart-line me-2"></i>
                                Progreso de Prácticas Preprofesionales
                            </h5>
                            <div class="progress mb-2" style="height: 8px; background: rgba(255,255,255,0.2);">
                                <div class="progress-bar bg-light" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="opacity-75">75% completado - 3 de 4 módulos</small>
                        </div>
                        <div class="col-md-4 text-end">
                            <h3 class="mb-0">75%</h3>
                            <small class="opacity-75">Completado</small>
                        </div>
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
                                <a href="<?= site_url('estudiante/practicas') ?>" class="btn btn-primary quick-action-btn w-100">
                                    <i class="fas fa-briefcase me-2"></i>
                                    Mis Prácticas
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="<?= site_url('estudiante/actividades') ?>" class="btn btn-success quick-action-btn w-100">
                                    <i class="fas fa-graduation-cap me-2"></i>
                                    Actividades
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="<?= site_url('estudiante/documentos') ?>" class="btn btn-info quick-action-btn w-100">
                                    <i class="fas fa-file-alt me-2"></i>
                                    Documentos
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="<?= site_url('estudiante/perfil') ?>" class="btn btn-warning quick-action-btn w-100">
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
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Progreso de Prácticas</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="progresoChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Estado de Prácticas</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="estadoChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prácticas Recientes -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Mis Prácticas</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Institución</th>
                                        <th>Tipo</th>
                                        <th>Fecha Inicio</th>
                                        <th>Estado</th>
                                        <th>Progreso</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No tienes prácticas asignadas aún
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actividades Disponibles -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Actividades Disponibles</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <i class="fas fa-laptop-code fa-3x text-primary mb-3"></i>
                                        <h5>Curso de Programación</h5>
                                        <p class="text-muted">Desarrollo web con PHP</p>
                                        <span class="badge bg-primary">Disponible</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <i class="fas fa-database fa-3x text-success mb-3"></i>
                                        <h5>Taller de Bases de Datos</h5>
                                        <p class="text-muted">MySQL y PostgreSQL</p>
                                        <span class="badge bg-success">Inscrito</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <i class="fas fa-chart-bar fa-3x text-info mb-3"></i>
                                        <h5>Seminario de Análisis</h5>
                                        <p class="text-muted">Estadística aplicada</p>
                                        <span class="badge bg-info">Próximamente</span>
                                    </div>
                                </div>
                            </div>
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
        const ahora = new Date();
        const opcionesFecha = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        const opcionesHora = { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit' 
        };
        
        document.getElementById('fechaActual').textContent = 
            ahora.toLocaleDateString('es-ES', opcionesFecha);
        document.getElementById('horaActual').textContent = 
            ahora.toLocaleTimeString('es-ES', opcionesHora);
    }
    
    // Actualizar cada segundo
    setInterval(actualizarFechaHora, 1000);
    actualizarFechaHora(); // Llamar inmediatamente

    // Gráfica de progreso
    const ctxProgreso = document.getElementById('progresoChart').getContext('2d');
    new Chart(ctxProgreso, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            datasets: [{
                label: 'Progreso (%)',
                data: [10, 25, 35, 45, 55, 65, 70, 75, 80, 85, 90, 95],
                borderColor: '#4facfe',
                backgroundColor: 'rgba(79, 172, 254, 0.1)',
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
                    max: 100,
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

    // Gráfica de estado
    const ctxEstado = document.getElementById('estadoChart').getContext('2d');
    new Chart(ctxEstado, {
        type: 'doughnut',
        data: {
            labels: ['Completadas', 'En Progreso', 'Pendientes'],
            datasets: [{
                data: [60, 25, 15],
                backgroundColor: [
                    '#43e97b',
                    '#4facfe',
                    '#f093fb'
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
