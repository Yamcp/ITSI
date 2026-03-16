<?= $this->extend('estudiante/layouts/mainEstudiante') ?>

<?= $this->section('styles') ?>
<!-- CSS personalizado para documentos de prácticas -->
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/documentos.css') ?>" />
<style>
    .progress-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .document-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
        border-radius: 15px;
        overflow: hidden;
    }
    
    .document-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border-color: #007bff;
    }
    
    .document-card.subido {
        border-color: #28a745;
    }
    
    .document-card.pendiente {
        border-color: #ffc107;
    }
    
    .document-card.aprobado {
        border-color: #28a745;
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    }
    
    .document-card.rechazado {
        border-color: #dc3545;
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    }
    
    .status-badge {
        font-size: 0.8rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
    }
    
    .upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .upload-area:hover {
        border-color: #007bff;
        background-color: #f8f9fa;
    }
    
    .upload-area.dragover {
        border-color: #007bff;
        background-color: #e3f2fd;
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
                    <i class="fas fa-file-alt me-2"></i>
                    Documentos de Prácticas Preprofesionales
                </h3>
                <p class="text-center text-muted">Sube los documentos requeridos conforme avances en tus prácticas</p>
            </div>
        </div>

        <!-- Progreso General -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="progress-card">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-2">
                                <i class="fas fa-chart-line me-2"></i>
                                Progreso de Documentos
                            </h4>
                            <div class="progress mb-2" style="height: 20px;">
                                <div class="progress-bar bg-light" role="progressbar" 
                                     style="width: <?= $estadisticas['porcentaje_completado'] ?>%" 
                                     aria-valuenow="<?= $estadisticas['porcentaje_completado'] ?>" 
                                     aria-valuemin="0" aria-valuemax="100">
                                    <?= $estadisticas['porcentaje_completado'] ?>%
                                </div>
                            </div>
                            <p class="mb-0"><?= $estadisticas['aprobados'] ?> de 12 documentos aprobados</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="row text-center">
                                <div class="col-3">
                                    <h5 class="mb-0"><?= $estadisticas['total'] ?></h5>
                                    <small>Total</small>
                                </div>
                                <div class="col-3">
                                    <h5 class="mb-0 text-success"><?= $estadisticas['aprobados'] ?></h5>
                                    <small>Aprobados</small>
                                </div>
                                <div class="col-3">
                                    <h5 class="mb-0 text-warning"><?= $estadisticas['pendientes'] ?></h5>
                                    <small>Pendientes</small>
                                </div>
                                <div class="col-3">
                                    <h5 class="mb-0 text-danger"><?= $estadisticas['rechazados'] ?></h5>
                                    <small>Rechazados</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documentos Requeridos -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-list-check me-2"></i>
                            Documentos Requeridos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php foreach ($tipos_documentos as $index => $tipo): ?>
                                <?php 
                                $documentoEstudiante = null;
                                foreach ($progreso as $doc) {
                                    if ($doc['ID_TIPO_DOCUMENTO'] == $tipo['ID_TIPO_DOCUMENTO'] && $doc['ID_DOCUMENTO_PRACTICA']) {
                                        $documentoEstudiante = $doc;
                                        break;
                                    }
                                }
                                
                                $estado = $documentoEstudiante ? $documentoEstudiante['ESTADO_REVISION'] : 'No subido';
                                $claseCard = '';
                                $iconoEstado = '';
                                $colorEstado = '';
                                
                                switch ($estado) {
                                    case 'Aprobado':
                                        $claseCard = 'aprobado';
                                        $iconoEstado = 'fas fa-check-circle';
                                        $colorEstado = 'success';
                                        break;
                                    case 'Rechazado':
                                        $claseCard = 'rechazado';
                                        $iconoEstado = 'fas fa-times-circle';
                                        $colorEstado = 'danger';
                                        break;
                                    case 'En Revisión':
                                        $claseCard = 'pendiente';
                                        $iconoEstado = 'fas fa-eye';
                                        $colorEstado = 'info';
                                        break;
                                    case 'Pendiente':
                                        $claseCard = 'pendiente';
                                        $iconoEstado = 'fas fa-clock';
                                        $colorEstado = 'warning';
                                        break;
                                    default:
                                        $claseCard = '';
                                        $iconoEstado = 'fas fa-upload';
                                        $colorEstado = 'secondary';
                                }
                                ?>
                                
                                <div class="col-md-6 col-lg-4">
                                    <div class="card document-card <?= $claseCard ?> h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="file-icon bg-primary me-3" style="width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-file-alt text-white"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1"><?= $tipo['CODIGO'] ?>. <?= $tipo['NOMBRE'] ?></h6>
                                                    <small class="text-muted"><?= $tipo['DESCRIPCION'] ?></small>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <span class="status-badge bg-<?= $colorEstado ?> text-white">
                                                    <i class="<?= $iconoEstado ?> me-1"></i>
                                                    <?= $estado ?>
                                                </span>
                                                <?php if ($tipo['REQUERIDO']): ?>
                                                    <span class="badge bg-danger ms-2">Requerido</span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <?php if ($documentoEstudiante): ?>
                                                <div class="mb-3">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        Subido: <?= date('d/m/Y', strtotime($documentoEstudiante['FECHA_SUBIDA'])) ?>
                                                    </small>
                                                    <?php if ($documentoEstudiante['OBSERVACIONES_REVISOR']): ?>
                                                        <div class="mt-2">
                                                            <small class="text-muted">
                                                                <strong>Observaciones:</strong><br>
                                                                <?= $documentoEstudiante['OBSERVACIONES_REVISOR'] ?>
                                                            </small>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="d-flex gap-2">
                                                <?php if ($documentoEstudiante): ?>
                                                    <button class="btn btn-outline-primary btn-sm" 
                                                            onclick="verDocumento(<?= $documentoEstudiante['ID_DOCUMENTO_PRACTICA'] ?>)"
                                                            title="Ver Documento">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-outline-success btn-sm" 
                                                            onclick="descargarDocumento(<?= $documentoEstudiante['ID_DOCUMENTO_PRACTICA'] ?>)"
                                                            title="Descargar">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                    <?php if ($estado != 'Aprobado'): ?>
                                                        <button class="btn btn-outline-danger btn-sm" 
                                                                onclick="eliminarDocumento(<?= $documentoEstudiante['ID_DOCUMENTO_PRACTICA'] ?>)"
                                                                title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <button class="btn btn-primary btn-sm" 
                                                            onclick="mostrarModalSubir(<?= $tipo['ID_TIPO_DOCUMENTO'] ?>, '<?= $tipo['NOMBRE'] ?>')"
                                                            title="Subir Documento">
                                                        <i class="fas fa-upload me-1"></i>
                                                        Subir
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Subir Documento -->
<div class="modal fade" id="modalSubirDocumento" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-cloud-upload-alt me-2"></i>
                    Subir Documento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formSubirDocumento" enctype="multipart/form-data">
                    <input type="hidden" name="tipo_documento" id="tipo_documento_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Tipo de Documento</label>
                        <input type="text" class="form-control" id="tipo_documento_nombre" readonly>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Entidad Receptora</label>
                                <input type="text" class="form-control" name="entidad_receptora" 
                                       placeholder="Ej: Instituto Tecnológico Superior Ibarra">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Docente Tutor</label>
                                <input type="text" class="form-control" name="docente_tutor" 
                                       placeholder="Nombre del docente tutor">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Archivo</label>
                        <div class="upload-area" id="uploadArea">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Arrastra y suelta tu archivo aquí</h5>
                            <p class="text-muted mb-3">o</p>
                            <input type="file" class="form-control" name="archivo" id="archivoInput" 
                                   accept=".pdf,application/pdf" required>
                            <small class="text-muted">Solo PDF. Máximo 10 MB.</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Observaciones (Opcional)</label>
                        <textarea class="form-control" name="observaciones" rows="3" 
                                  placeholder="Observaciones adicionales sobre el documento..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="subirDocumento()">
                    <i class="fas fa-upload me-1"></i>Subir Documento
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function mostrarModalSubir(tipoId, tipoNombre) {
        document.getElementById('tipo_documento_id').value = tipoId;
        document.getElementById('tipo_documento_nombre').value = tipoNombre;
        
        const modal = new bootstrap.Modal(document.getElementById('modalSubirDocumento'));
        modal.show();
    }

    function subirDocumento() {
        const form = document.getElementById('formSubirDocumento');
        const formData = new FormData(form);
        
        const archivo = document.getElementById('archivoInput').files[0];
        if (!archivo) {
            showNotification('Debes seleccionar un archivo', 'error');
            return;
        }
        
        // Mostrar loading
        const btnSubir = document.querySelector('#modalSubirDocumento .btn-primary');
        const textoOriginal = btnSubir.innerHTML;
        btnSubir.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Subiendo...';
        btnSubir.disabled = true;
        
        fetch('<?= base_url('estudiante/documentos-practicas/subir') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('modalSubirDocumento')).hide();
                form.reset();
                // Recargar la página para mostrar los cambios
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al subir el documento', 'error');
        })
        .finally(() => {
            btnSubir.innerHTML = textoOriginal;
            btnSubir.disabled = false;
        });
    }

    function verDocumento(id) {
        window.open('<?= base_url('estudiante/documentos-practicas/descargar') ?>/' + id, '_blank');
    }

    function descargarDocumento(id) {
        window.location.href = '<?= base_url('estudiante/documentos-practicas/descargar') ?>/' + id;
    }

    function eliminarDocumento(id) {
        if (confirm('¿Estás seguro de que quieres eliminar este documento?')) {
            fetch('<?= base_url('estudiante/documentos-practicas/eliminar') ?>/' + id, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al eliminar el documento', 'error');
            });
        }
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
            <div class="alert alert-${type} alert-dismissible fade show" role="alert" 
                 style="background: ${colors[type]}; color: white; border: none; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
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

    // Drag and Drop functionality
    document.addEventListener('DOMContentLoaded', function() {
        const uploadArea = document.getElementById('uploadArea');
        const archivoInput = document.getElementById('archivoInput');

        // Drag and drop events
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                archivoInput.files = files;
                // Trigger change event
                const event = new Event('change', { bubbles: true });
                archivoInput.dispatchEvent(event);
            }
        });

        // File input change event
        archivoInput.addEventListener('change', function(e) {
            if (this.files.length > 0) {
                const file = this.files[0];
                const fileSize = (file.size / (1024 * 1024)).toFixed(2);
                
                // Update upload area with file info
                uploadArea.innerHTML = `
                    <i class="fas fa-file fa-3x text-primary mb-3"></i>
                    <h5 class="text-primary">${file.name}</h5>
                    <p class="text-muted mb-2">Tamaño: ${fileSize} MB</p>
                    <small class="text-muted">Archivo seleccionado correctamente</small>
                `;
            }
        });
    });
</script>
<?= $this->endSection() ?>