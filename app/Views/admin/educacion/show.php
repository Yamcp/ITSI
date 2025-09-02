<?= $this->extend('admin/layouts/mainAdmin') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/actividades.css') ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Detalles de la Actividad
                    </h3>
                    <div class="d-flex gap-2">
                        <a href="<?= base_url('admin/actividades-educacion/editar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>Editar
                        </a>
                        <a href="<?= base_url('admin/actividades-educacion') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de la Actividad -->
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-graduation-cap me-2"></i>
                            <?= $actividad['NOMBRE_ACTIVIDAD'] ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Tipo de Actividad:</strong> 
                                    <span class="badge bg-info"><?= $actividad['TIPO_ACTIVIDAD'] ?></span>
                                </p>
                                <p><strong>Instructor:</strong> <?= $actividad['NOMBRE'] ?> <?= $actividad['APELLIDO'] ?></p>
                                <p><strong>Especialidad:</strong> <?= $actividad['ESPECIALIDAD'] ?></p>
                                <p><strong>Modalidad:</strong> 
                                    <span class="badge bg-secondary"><?= $actividad['MODALIDAD'] ?></span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Período:</strong> 
                                    <?= date('d/m/Y', strtotime($actividad['FECHA_INICIO'])) ?> - 
                                    <?= date('d/m/Y', strtotime($actividad['FECHA_FIN'])) ?>
                                </p>
                                <p><strong>Duración:</strong> 
                                    <span class="badge bg-warning text-dark"><?= $actividad['DURACION_HORAS'] ?> horas</span>
                                </p>
                                <p><strong>Lugar:</strong> <?= $actividad['LUGAR'] ?></p>
                                <p><strong>Horario:</strong> <?= $actividad['HORARIO'] ?></p>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <h6><strong>Descripción:</strong></h6>
                                <p class="text-muted"><?= nl2br($actividad['DESCRIPCION']) ?></p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h6><strong>Objetivos:</strong></h6>
                                <p class="text-muted"><?= nl2br($actividad['OBJETIVOS']) ?></p>
                            </div>
                        </div>

                        <?php if (!empty($actividad['PROGRAMA_DETALLADO'])): ?>
                        <div class="row">
                            <div class="col-12">
                                <h6><strong>Programa Detallado:</strong></h6>
                                <p class="text-muted"><?= nl2br($actividad['PROGRAMA_DETALLADO']) ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Estado de la Actividad -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Estado de la Actividad</h6>
                    </div>
                    <div class="card-body text-center">
                        <?php 
                        $fechaFin = new DateTime($actividad['FECHA_FIN']);
                        $hoy = new DateTime();
                        if ($fechaFin >= $hoy) {
                            echo '<h4 class="text-success">Activa</h4>';
                            echo '<p class="text-muted">La actividad está en curso</p>';
                        } else {
                            echo '<h4 class="text-secondary">Finalizada</h4>';
                            echo '<p class="text-muted">La actividad ha concluido</p>';
                        }
                        ?>
                        
                        
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h6 class="mb-0">Acciones</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="gestionarParticipantes(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)">
                                <i class="fas fa-users me-1"></i>Gestionar Participantes
                            </button>
                            
                            
                            
                            <button class="btn btn-outline-info btn-sm" onclick="generarReporte(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)">
                                <i class="fas fa-file-alt me-1"></i>Reporte de Asistencia
                            </button>
                            
                            <a href="<?= base_url('admin/actividades-educacion/eliminar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" 
                               class="btn btn-outline-danger btn-sm" 
                               onclick="return confirm('¿Estás seguro de que deseas eliminar esta actividad?')">
                                <i class="fas fa-trash me-1"></i>Eliminar Actividad
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function gestionarParticipantes(id) {
        showNotification('Función de gestión de participantes en desarrollo', 'info');
    }

    

    function generarReporte(id) {
        showNotification('Generando reporte...', 'info');
        setTimeout(() => {
            showNotification('Reporte generado exitosamente', 'success');
        }, 2000);
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
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
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
</script>

<?= $this->endSection() ?>
