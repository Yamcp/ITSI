<!-- app/Views/dashboard/dashboard.php -->
<?= $this->extend('admin/layouts/mainAdmin') ?>

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
    
    .activity-timeline {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .timeline-item {
        position: relative;
        padding: 15px 0 15px 30px;
        border-left: 2px solid #e9ecef;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -6px;
        top: 20px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #007bff;
    }
    
    .timeline-item.success::before {
        background: #28a745;
    }
    
    .timeline-item.warning::before {
        background: #ffc107;
    }
    
    .timeline-item.info::before {
        background: #17a2b8;
    }
    
    .quick-action-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        border-radius: 15px;
    }
    
    .quick-action-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    
    .progress-ring {
        transform: rotate(-90deg);
    }
    
    .progress-ring-circle {
        stroke-linecap: round;
        transition: stroke-dasharray 0.5s ease;
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
            <div class="col-xl-3 col-md-6 mb-4">
                <a href="<?= base_url('admin/estudiantes') ?>" style="text-decoration: none;">
                    <div class="card metric-card shadow-sm" style="background: linear-gradient(135deg, #667eea 80%, #764ba2 100%); color: #fff; border: none;">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="metric-icon me-3" style="background: rgba(255,255,255,0.15); color: #fff;">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    <h3 class="mb-1" id="totalEstudiantes">1,247</h3>
                                    <p class="mb-0" style="color: #e0e0e0;">Total Estudiantes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <a href="<?= base_url('admin/instructores') ?>" style="text-decoration: none;">
                    <div class="card metric-card shadow-sm" style="background: linear-gradient(135deg, #f093fb 80%, #f5576c 100%); color: #fff; border: none;">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="metric-icon me-3" style="background: rgba(255,255,255,0.15); color: #fff;">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <div>
                                    <h3 class="mb-1" id="totalInstructores">89</h3>
                                    <p class="mb-0" style="color: #ffe6e6;">Instructores</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <a href="<?= base_url('admin/educacion') ?>" style="text-decoration: none;">
                    <div class="card metric-card shadow-sm" style="background: linear-gradient(135deg, #4facfe 80%, #00f2fe 100%); color: #fff; border: none;">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="metric-icon me-3" style="background: rgba(255,255,255,0.15); color: #fff;">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div>
                                    <h3 class="mb-1" id="actividadesActivas">156</h3>
                                    <p class="mb-0" style="color: #e0e0e0;">Actividades Activas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <a href="<?= base_url('admin/convenios') ?>" style="text-decoration: none;">
                    <div class="card metric-card shadow-sm" style="background: linear-gradient(135deg, #43e97b 80%, #38f9d7 100%); color: #fff; border: none;">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="metric-icon me-3" style="background: rgba(255,255,255,0.15); color: #fff;">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div>
                                    <h3 class="mb-1" id="conveniosVigentes">34</h3>
                                    <p class="mb-0" style="color: #e0e0e0;">Convenios Vigentes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Gráficas y Estadísticas -->
        <div class="row mb-4">
            <!-- Gráfica de Actividades por Mes -->
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line me-2 text-primary"></i>
                            Actividades Educativas por Mes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="actividadesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Distribución por Carrera -->
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-pie me-2 text-success"></i>
                            Distribución por Carrera
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="carrerasChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segunda Fila de Gráficas -->
        <div class="row mb-4">
            <!-- Rendimiento de Prácticas -->
            <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-briefcase me-2 text-info"></i>
                            Rendimiento de Prácticas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="practicasChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Evaluación de Instructores -->
            <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-star me-2 text-warning"></i>
                            Evaluación de Instructores
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="evaluacionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas y Timeline -->
        <div class="row mb-4">
            <!-- Acciones Rápidas -->
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-bolt me-2 text-warning"></i>
                            Acciones Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="card quick-action-card text-center p-3" onclick="navegarA('practicas')">
                                    <i class="fas fa-briefcase fa-2x text-primary mb-2"></i>
                                    <h6 class="mb-1">Prácticas</h6>
                                    <small class="text-muted">Gestionar</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card quick-action-card text-center p-3" onclick="navegarA('convenios')">
                                    <i class="fas fa-handshake fa-2x text-success mb-2"></i>
                                    <h6 class="mb-1">Convenios</h6>
                                    <small class="text-muted">Administrar</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card quick-action-card text-center p-3" onclick="navegarA('educacion')">
                                    <i class="fas fa-graduation-cap fa-2x text-info mb-2"></i>
                                    <h6 class="mb-1">Educación</h6>
                                    <small class="text-muted">Actividades</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card quick-action-card text-center p-3" onclick="navegarA('instructores')">
                                    <i class="fas fa-chalkboard-teacher fa-2x text-warning mb-2"></i>
                                    <h6 class="mb-1">Instructores</h6>
                                    <small class="text-muted">Gestionar</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Timeline de Actividades -->
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2 text-info"></i>
                            Actividades Recientes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="activity-timeline">
                            <div class="timeline-item success">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Nueva Práctica Asignada</h6>
                                        <p class="text-muted mb-1">Se asignó práctica preprofesional a Ana Yandun en Banco del Pacífico</p>
                                        <small class="text-muted">Hace 2 horas</small>
                                    </div>
                                    <span class="badge bg-success">Completado</span>
                                </div>
                            </div>
                            
                            <div class="timeline-item warning">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Convenio por Vencer</h6>
                                        <p class="text-muted mb-1">Convenio con Hospital San Vicente vence en 15 días</p>
                                        <small class="text-muted">Hace 4 horas</small>
                                    </div>
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                </div>
                            </div>
                            
                            <div class="timeline-item info">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Nueva Actividad Educativa</h6>
                                        <p class="text-muted mb-1">Se registró curso "Desarrollo Web Full Stack" con Ing. Carlos Mendoza</p>
                                        <small class="text-muted">Hace 6 horas</small>
                                    </div>
                                    <span class="badge bg-info">Nuevo</span>
                                </div>
                            </div>
                            
                            <div class="timeline-item success">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Evaluación Completada</h6>
                                        <p class="text-muted mb-1">Se completó evaluación del instructor Tec. Ana Ruiz</p>
                                        <small class="text-muted">Hace 1 día</small>
                                    </div>
                                    <span class="badge bg-success">Completado</span>
                                </div>
                            </div>
                            
                            <div class="timeline-item warning">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Documento Pendiente</h6>
                                        <p class="text-muted mb-1">Pedro Aguirre debe subir informe semanal de práctica</p>
                                        <small class="text-muted">Hace 2 días</small>
                                    </div>
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Detalladas -->
        <div class="row mb-4">
            <!-- Próximos Vencimientos -->
            <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                            Próximos Vencimientos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Elemento</th>
                                        <th>Fecha Vencimiento</th>
                                        <th>Días Restantes</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Convenio Hospital San Vicente</td>
                                        <td>15/09/2025</td>
                                        <td><span class="badge bg-danger">15 días</span></td>
                                        <td><span class="badge bg-warning text-dark">Crítico</span></td>
                                    </tr>
                                    <tr>
                                        <td>Práctica Ana Yandun</td>
                                        <td>30/09/2025</td>
                                        <td><span class="badge bg-warning text-dark">30 días</span></td>
                                        <td><span class="badge bg-info">Normal</span></td>
                                    </tr>
                                    <tr>
                                        <td>Curso Desarrollo Web</td>
                                        <td>15/10/2025</td>
                                        <td><span class="badge bg-success">45 días</span></td>
                                        <td><span class="badge bg-success">Seguro</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Resumen de Actividades -->
            <div class="col-xl-6 col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-bar me-2 text-primary"></i>
                            Resumen de Actividades
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="border-end">
                                    <h4 class="text-primary mb-1">24</h4>
                                    <p class="text-muted mb-0">Prácticas Activas</p>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <h4 class="text-success mb-1">156</h4>
                                <p class="text-muted mb-0">Actividades Educativas</p>
                            </div>
                            <div class="col-6">
                                <h4 class="text-info mb-1">34</h4>
                                <p class="text-muted mb-0">Convenios Vigentes</p>
                            </div>
                            <div class="col-6">
                                <h4 class="text-warning mb-1">89</h4>
                                <p class="text-muted mb-0">Instructores</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Actualizar hora en tiempo real
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('es-EC', { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit' 
        });
        document.getElementById('currentTime').textContent = timeString;
    }
    
    setInterval(updateTime, 1000);
    updateTime();

    // Gráfica de Actividades por Mes
    const actividadesCtx = document.getElementById('actividadesChart').getContext('2d');
    const actividadesChart = new Chart(actividadesCtx, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            datasets: [{
                label: 'Cursos',
                data: [12, 19, 15, 25, 22, 30, 28, 35, 32, 40, 38, 45],
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Talleres',
                data: [8, 12, 10, 18, 15, 22, 20, 28, 25, 32, 30, 38],
                borderColor: '#f093fb',
                backgroundColor: 'rgba(240, 147, 251, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Seminarios',
                data: [3, 5, 4, 8, 6, 12, 10, 15, 12, 18, 16, 22],
                borderColor: '#4facfe',
                backgroundColor: 'rgba(79, 172, 254, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
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
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            }
        }
    });

    // Gráfica de Distribución por Carrera
    const carrerasCtx = document.getElementById('carrerasChart').getContext('2d');
    const carrerasChart = new Chart(carrerasCtx, {
        type: 'doughnut',
        data: {
            labels: ['Sistemas de Información', 'Desarrollo de Software', 'Redes y Telecomunicaciones', 'Otras'],
            datasets: [{
                data: [45, 30, 20, 5],
                backgroundColor: [
                    '#667eea',
                    '#f093fb',
                    '#4facfe',
                    '#43e97b'
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

    // Gráfica de Rendimiento de Prácticas
    const practicasCtx = document.getElementById('practicasChart').getContext('2d');
    const practicasChart = new Chart(practicasCtx, {
        type: 'bar',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [{
                label: 'Completadas',
                data: [65, 72, 68, 75, 80, 85],
                backgroundColor: '#28a745',
                borderRadius: 5
            }, {
                label: 'En Proceso',
                data: [25, 28, 32, 30, 25, 20],
                backgroundColor: '#ffc107',
                borderRadius: 5
            }, {
                label: 'Pendientes',
                data: [10, 8, 12, 15, 10, 8],
                backgroundColor: '#dc3545',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
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
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            }
        }
    });

    // Gráfica de Evaluación de Instructores
    const evaluacionCtx = document.getElementById('evaluacionChart').getContext('2d');
    const evaluacionChart = new Chart(evaluacionCtx, {
        type: 'radar',
        data: {
            labels: ['Conocimiento Técnico', 'Comunicación', 'Puntualidad', 'Metodología', 'Evaluación', 'Disponibilidad'],
            datasets: [{
                label: 'Promedio General',
                data: [4.8, 4.6, 4.9, 4.7, 4.5, 4.8],
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.2)',
                pointBackgroundColor: '#ffc107',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#ffc107'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    max: 5,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            }
        }
    });

    // Función para navegar a diferentes secciones
    function navegarA(seccion) {
        const rutas = {
            'practicas': '/admin/practicas',
            'convenios': '/admin/convenios',
            'educacion': '/admin/educacion',
            'instructores': '/admin/instructores'
        };
        
        if (rutas[seccion]) {
            window.location.href = rutas[seccion];
        }
    }

    // Simular datos en tiempo real
    function actualizarMetricas() {
        // Simular cambios en las métricas
        const estudiantes = document.getElementById('totalEstudiantes');
        const instructores = document.getElementById('totalInstructores');
        const actividades = document.getElementById('actividadesActivas');
        const convenios = document.getElementById('conveniosVigentes');
        
        // Simular incrementos aleatorios
        setInterval(() => {
            estudiantes.textContent = Math.floor(Math.random() * 50 + 1247);
            instructores.textContent = Math.floor(Math.random() * 10 + 89);
            actividades.textContent = Math.floor(Math.random() * 20 + 156);
            convenios.textContent = Math.floor(Math.random() * 5 + 34);
        }, 10000); // Actualizar cada 10 segundos
    }

    // Inicializar actualizaciones
    document.addEventListener('DOMContentLoaded', function() {
        actualizarMetricas();
    });
</script>
<?= $this->endSection() ?>