<?= $this->extend('estudiante/layouts/mainEstudiante') ?>

<?= $this->section('styles') ?>
<!-- CSS personalizado para evaluaciones -->
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/documentos.css') ?>" />
<style>
    .evaluation-card {
        transition: all 0.3s ease;
        border-left: 4px solid #007bff;
    }

    .evaluation-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .evaluation-card.satisfaccion {
        border-left-color: #28a745;
    }

    .evaluation-card.instructores {
        border-left-color: #ffc107;
    }

    .evaluation-card.practicas {
        border-left-color: #17a2b8;
    }

    .evaluation-card.cursos {
        border-left-color: #6f42c1;
    }

    .evaluation-card.comunidad {
        border-left-color: #fd7e14;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-activo {
        background-color: #d4edda;
        color: #155724;
    }

    .status-inactivo {
        background-color: #f8d7da;
        color: #721c24;
    }

    .status-vencido {
        background-color: #fff3cd;
        color: #856404;
    }

    .link-preview {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 0.5rem;
        font-size: 0.875rem;
        color: #6c757d;
        word-break: break-all;
    }

    .btn-evaluacion {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-evaluacion:hover {
        background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .info-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    .urgent-card {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border: 1px solid #ffc107;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-clipboard-check me-2"></i>
                    Formularios de Evaluación
                </h3>
                <p class="text-center text-muted mb-4">
                    Completa las evaluaciones de tus cursos y actividades
                </p>
            </div>
        </div>

        <!-- Alerta de Evaluaciones Urgentes -->
        <div class="row mb-4" id="alertaUrgentes" style="display: none;">
            <div class="col-12">
                <div class="urgent-card">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle text-warning me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <h6 class="mb-1">Evaluaciones Próximas a Vencer</h6>
                            <p class="mb-0 text-muted">
                                Tienes evaluaciones que vencen pronto. Te recomendamos completarlas lo antes posible.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Básicas -->
        <div class="row mb-4">
            <div class="col-md-4 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="totalEvaluaciones" style="font-size:2.5rem;">0</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Evaluaciones Disponibles</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #28a745 80%, #155724 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="evaluacionesActivas" style="font-size:2.5rem;">0</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Activas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #ffc107 80%, #b38600 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="evaluacionesPendientes" style="font-size:2.5rem;">0</h2>
                        <p class="card-text fw-bold" style="color: #fffbe6;">Pendientes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Evaluaciones -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-clipboard-check me-2"></i>
                            Formularios de Evaluación
                        </span>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-sm" onclick="cambiarVista('grid')">
                                <i class="fas fa-th-large me-1"></i>Grid
                            </button>
                            <button class="btn btn-light btn-sm" onclick="cambiarVista('list')">
                                <i class="fas fa-list me-1"></i>Lista
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Vista Grid -->
                        <div id="vistaGrid" class="row g-3">
                            <!-- Las evaluaciones se cargarán dinámicamente aquí -->
                        </div>

                        <!-- Vista Lista -->
                        <div id="vistaLista" class="d-none">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Evaluación</th>
                                            <th>Tipo</th>
                                            <th>Curso</th>
                                            <th>Estado</th>
                                            <th>Vencimiento</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaEvaluacionesLista">
                                        <!-- Las evaluaciones se cargarán dinámicamente aquí -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    let evaluaciones = [];
    let vistaActual = 'grid';

    // Funciones principales
    function cambiarVista(tipo) {
        vistaActual = tipo;
        if (tipo === 'grid') {
            document.getElementById('vistaGrid').classList.remove('d-none');
            document.getElementById('vistaLista').classList.add('d-none');
            generarVistaGrid();
        } else {
            document.getElementById('vistaGrid').classList.add('d-none');
            document.getElementById('vistaLista').classList.remove('d-none');
            generarVistaLista();
        }
    }

    function generarVistaGrid() {
        const container = document.getElementById('vistaGrid');
        container.innerHTML = '';

        if (evaluaciones.length === 0) {
            container.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay evaluaciones disponibles</h5>
                    <p class="text-muted">No tienes evaluaciones pendientes en este momento.</p>
                </div>
            `;
            return;
        }

        evaluaciones.forEach(eval => {
            const card = document.createElement('div');
            card.className = 'col-md-6 col-lg-4';

            // Verificar si está próxima a vencer (7 días o menos)
            const fechaVencimiento = new Date(eval.fecha_vencimiento);
            const hoy = new Date();
            const diasRestantes = Math.ceil((fechaVencimiento - hoy) / (1000 * 60 * 60 * 24));
            const esUrgente = diasRestantes <= 7 && diasRestantes >= 0;

            card.innerHTML = `
                <div class="card evaluation-card ${eval.tipo.toLowerCase()} h-100 ${esUrgente ? 'border-warning' : ''}">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="card-title mb-0">${eval.nombre}</h6>
                            <div class="d-flex flex-column align-items-end">
                                <span class="status-badge status-${eval.estado}">${eval.estado}</span>
                                ${esUrgente ? '<small class="text-warning mt-1"><i class="fas fa-clock"></i> Próxima a vencer</small>' : ''}
                            </div>
                        </div>
                        <p class="card-text text-muted small mb-3 flex-grow-1">${eval.descripcion}</p>
                        <div class="mb-3">
                            <small class="text-muted d-block">Curso:</small>
                            <strong>${eval.curso}</strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Vence:</small>
                            <strong class="${esUrgente ? 'text-warning' : ''}">${formatearFecha(eval.fecha_vencimiento)}</strong>
                            ${esUrgente ? `<small class="text-warning d-block">(${diasRestantes} días restantes)</small>` : ''}
                        </div>
                        <div class="mt-auto">
                            <a href="${eval.enlace}" target="_blank" class="btn-evaluacion w-100 text-center">
                                <i class="fas fa-external-link-alt"></i>
                                Completar Evaluación
                            </a>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });
    }

    function generarVistaLista() {
        const tbody = document.getElementById('tablaEvaluacionesLista');
        tbody.innerHTML = '';

        if (evaluaciones.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <i class="fas fa-clipboard-check fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No hay evaluaciones disponibles</p>
                    </td>
                </tr>
            `;
            return;
        }

        evaluaciones.forEach(eval => {
            const row = document.createElement('tr');

            // Verificar si está próxima a vencer
            const fechaVencimiento = new Date(eval.fecha_vencimiento);
            const hoy = new Date();
            const diasRestantes = Math.ceil((fechaVencimiento - hoy) / (1000 * 60 * 60 * 24));
            const esUrgente = diasRestantes <= 7 && diasRestantes >= 0;

            row.innerHTML = `
                <td>
                    <div class="fw-semibold">${eval.nombre}</div>
                    <small class="text-muted">${eval.descripcion}</small>
                    ${esUrgente ? '<small class="text-warning d-block"><i class="fas fa-clock"></i> Próxima a vencer</small>' : ''}
                </td>
                <td><span class="badge bg-secondary">${eval.tipo}</span></td>
                <td>${eval.curso}</td>
                <td><span class="status-badge status-${eval.estado}">${eval.estado}</span></td>
                <td class="${esUrgente ? 'text-warning' : ''}">
                    ${formatearFecha(eval.fecha_vencimiento)}
                    ${esUrgente ? `<br><small>(${diasRestantes} días)</small>` : ''}
                </td>
                <td>
                    <a href="${eval.enlace}" target="_blank" class="btn-evaluacion">
                        <i class="fas fa-external-link-alt"></i>
                        Completar
                    </a>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function formatearFecha(fecha) {
        return new Date(fecha).toLocaleDateString('es-ES');
    }

    function cargarEvaluaciones() {
        fetch('<?= base_url('estudiante/evaluaciones/obtener') ?>')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    evaluaciones = data.data;
                    verificarEvaluacionesUrgentes();
                    if (vistaActual === 'grid') {
                        generarVistaGrid();
                    } else {
                        generarVistaLista();
                    }
                } else {
                    console.error('Error cargando evaluaciones:', data.message);
                    showNotification('Error al cargar evaluaciones: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error cargando evaluaciones:', error);
                showNotification('Error al cargar evaluaciones desde el servidor', 'error');
            });
    }

    function verificarEvaluacionesUrgentes() {
        const hoy = new Date();
        const evaluacionesUrgentes = evaluaciones.filter(eval => {
            const fechaVencimiento = new Date(eval.fecha_vencimiento);
            const diasRestantes = Math.ceil((fechaVencimiento - hoy) / (1000 * 60 * 60 * 24));
            return diasRestantes <= 7 && diasRestantes >= 0;
        });

        if (evaluacionesUrgentes.length > 0) {
            document.getElementById('alertaUrgentes').style.display = 'block';
        } else {
            document.getElementById('alertaUrgentes').style.display = 'none';
        }
    }

    function cargarEstadisticas() {
        fetch('<?= base_url('estudiante/evaluaciones/estadisticas') ?>')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('totalEvaluaciones').textContent = data.data.total || 0;
                    document.getElementById('evaluacionesActivas').textContent = data.data.activas || 0;
                    document.getElementById('evaluacionesPendientes').textContent = data.data.pendientes || 0;
                }
            })
            .catch(error => {
                console.error('Error cargando estadísticas:', error);
            });
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
        console.log('Vista de evaluaciones para estudiantes cargada');
        cargarEvaluaciones();
        cargarEstadisticas();
    });
</script>
<?= $this->endSection() ?>