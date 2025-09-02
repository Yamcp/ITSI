<?= $this->extend('estudiante/layouts/mainEstudiante') ?>

<?= $this->section('styles') ?>
<!-- CSS personalizado para documentos de prácticas -->
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/documentos.css') ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-file-alt me-2"></i>
                    Documentos de Prácticas
                </h3>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalSubirDocumentoPractica')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2 text-primary"></i>
                            <div class="fw-bold">Subir Documento</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Documentos de Prácticas -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-briefcase me-2"></i>
                            Documentos de Prácticas
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
                            <!-- Documento 1 -->
                            <div class="col-md-4 col-lg-3">
                                <div class="file-item p-3 h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="file-icon bg-primary me-3">
                                            <i class="fas fa-file-pdf"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Informe_Final_Practica.pdf</h6>
                                            <small class="text-muted">2.5 MB</small>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <span class="category-badge bg-primary text-white">Práctica</span>
                                        <span class="category-badge bg-success text-white ms-2">Aprobado</span>
                                        <span class="category-badge bg-info text-white ms-2">Prioridad: Media</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Subido: 30/08/2025</small>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="verDocumento(1)" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="descargarDocumento(1)" title="Descargar">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(1)" title="Cambiar Estado">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="eliminarDocumento(1)" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Documento 2 -->
                            <div class="col-md-4 col-lg-3">
                                <div class="file-item p-3 h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="file-icon bg-success me-3">
                                            <i class="fas fa-file-word"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Plan_Trabajo_Practica.docx</h6>
                                            <small class="text-muted">1.8 MB</small>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <span class="category-badge bg-primary text-white">Práctica</span>
                                        <span class="category-badge bg-warning text-dark ms-2">Pendiente</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Subido: 29/08/2025</small>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="verDocumento(2)" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="descargarDocumento(2)" title="Descargar">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="eliminarDocumento(2)" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Documento 3 -->
                            <div class="col-md-4 col-lg-3">
                                <div class="file-item p-3 h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="file-icon bg-warning me-3">
                                            <i class="fas fa-file-excel"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Registro_Actividades.xlsx</h6>
                                            <small class="text-muted">3.2 MB</small>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <span class="category-badge bg-primary text-white">Práctica</span>
                                        <span class="category-badge bg-danger text-white ms-2">Rechazado</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Subido: 28/08/2025</small>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="verDocumento(3)" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="descargarDocumento(3)" title="Descargar">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="eliminarDocumento(3)" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vista Lista (oculta por defecto) -->
                        <div id="vistaLista" class="d-none">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Documento</th>
                                            <th>Tipo</th>
                                            <th>Tamaño</th>
                                            <th>Estado</th>
                                            <th>Fecha Subida</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="file-icon bg-primary me-3" style="width: 40px; height: 40px; font-size: 1.2rem;">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">Informe_Final_Practica.pdf</div>
                                                        <small class="text-muted">Subido por: Yamilex Campues</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="category-badge bg-primary text-white">Informe Final</span></td>
                                            <td>2.5 MB</td>
                                            <td><span class="category-badge bg-success text-white">Aprobado</span></td>
                                            <td>30/08/2025</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" onclick="verDocumento(1)" title="Ver">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-outline-success" onclick="descargarDocumento(1)" title="Descargar">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                    <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(1)" title="Cambiar Estado">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-outline-danger" onclick="eliminarDocumento(1)" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
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
</div>

<!-- Modal Subir Documento de Práctica -->
<div class="modal fade" id="modalSubirDocumentoPractica" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-cloud-upload-alt me-2"></i>
                    Subir Documento de Práctica
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formSubirDocumentoPractica">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Documento</label>
                                <select class="form-select" name="tipo_documento" required>
                                    <option value="">Seleccionar tipo...</option>
                                    <option value="oficio_asignacion_tutor">1.1. Oficio de Asignación de Tutor Docente</option>
                                    <option value="oficio_personal_entidad">1.2. Oficio Personal a Entidad Receptora</option>
                                    <option value="carta_aceptacion">1.3. Carta de Aceptación de Entidad Receptora</option>
                                    <option value="solicitud_institucional">1.4. Solicitud Institucional Valorada</option>
                                    <option value="certificado_culminacion">1.5. Certificado de Culminación (60 horas)</option>
                                    <option value="rubrica_evaluacion_entidad">1.6. Rúbrica de Evaluación Entidad Receptora</option>
                                    <option value="hojas_asistencia">1.7. Hojas de Asistencia de Estudiantes</option>
                                    <option value="ficha_registro_actividades">1.8. Ficha de Registro de Actividades Realizadas</option>
                                    <option value="ficha_control_seguimiento">1.9. Ficha de Control y Seguimiento Docente</option>
                                    <option value="rubrica_evaluacion_docente">1.10. Rúbrica de Evaluación de Control y Seguimiento Docente</option>
                                    <option value="rubrica_evaluacion_resultados">1.11. Rúbrica de Evaluación de Resultados</option>
                                    <option value="respaldo_fotos">1.12. Respaldo en Fotos, Videos y Evidencias</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estudiante</label>
                                <select class="form-select" name="estudiante" required>
                                    <option value="">Seleccionar estudiante...</option>
                                    <option value="1">Yamilex Campues - Sistemas</option>
                                    <option value="2">Ana Yandun - Desarrollo</option>
                                    <option value="3">Pedro Aguirre - Desarrollo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Entidad Receptora</label>
                                <input type="text" class="form-control" name="entidad_receptora" placeholder="Ej: Instituto Tecnológico Superior Ibarra" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Docente Tutor</label>
                                <select class="form-select" name="docente_tutor" required>
                                    <option value="">Seleccionar docente tutor...</option>
                                    <option value="1">Dr. Mario Montenegro - Rector</option>
                                    <option value="2">Ing. Juan Pérez - Coordinador</option>
                                    <option value="3">Mg. María González - Tutora</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Archivo</label>
                        <div class="upload-card p-4 text-center" id="uploadAreaPractica">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Arrastra y suelta archivos aquí</h5>
                            <p class="text-muted mb-3">o</p>
                            <input type="file" class="form-control" name="archivo" id="archivoInputPractica" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar" required>
                            <small class="text-muted">Máximo 50 MB. Formatos: PDF, DOC, XLS, JPG, ZIP</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estado de Revisión</label>
                                <select class="form-select" name="estado_revision" required>
                                    <option value="">Seleccionar estado...</option>
                                    <option value="pendiente">Pendiente de Revisión</option>
                                    <option value="en_revision">En Revisión</option>
                                    <option value="aprobado">Aprobado</option>
                                    <option value="rechazado">Rechazado</option>
                                    <option value="requiere_correccion">Requiere Corrección</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Prioridad</label>
                                <select class="form-select" name="prioridad" required>
                                    <option value="">Seleccionar prioridad...</option>
                                    <option value="baja">Baja</option>
                                    <option value="media" selected>Media</option>
                                    <option value="alta">Alta</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Observaciones del Administrador</label>
                        <textarea class="form-control" name="observaciones" rows="3" placeholder="Observaciones adicionales sobre el documento, estado de revisión, correcciones necesarias..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="subirDocumentoPractica()">
                    <i class="fas fa-upload me-1"></i>Subir Documento
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filtros -->
<div class="modal fade" id="modalFiltrosPracticas" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-filter me-2"></i>
                    Filtros de Búsqueda
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formFiltrosPracticas">
                    <div class="mb-3">
                        <label class="form-label">Tipo de Documento</label>
                        <select class="form-select" name="filtro_tipo_documento">
                            <option value="">Todos los tipos</option>
                            <option value="oficio_asignacion_tutor">1.1. Oficio de Asignación de Tutor Docente</option>
                            <option value="oficio_personal_entidad">1.2. Oficio Personal a Entidad Receptora</option>
                            <option value="carta_aceptacion">1.3. Carta de Aceptación de Entidad Receptora</option>
                            <option value="solicitud_institucional">1.4. Solicitud Institucional Valorada</option>
                            <option value="certificado_culminacion">1.5. Certificado de Culminación (60 horas)</option>
                            <option value="rubrica_evaluacion_entidad">1.6. Rúbrica de Evaluación Entidad Receptora</option>
                            <option value="hojas_asistencia">1.7. Hojas de Asistencia de Estudiantes</option>
                            <option value="ficha_registro_actividades">1.8. Ficha de Registro de Actividades Realizadas</option>
                            <option value="ficha_control_seguimiento">1.9. Ficha de Control y Seguimiento Docente</option>
                            <option value="rubrica_evaluacion_docente">1.10. Rúbrica de Evaluación de Control y Seguimiento Docente</option>
                            <option value="rubrica_evaluacion_resultados">1.11. Rúbrica de Evaluación de Resultados</option>
                            <option value="respaldo_fotos">1.12. Respaldo en Fotos, Videos y Evidencias</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado de Revisión</label>
                        <select class="form-select" name="filtro_estado">
                            <option value="">Todos los estados</option>
                            <option value="pendiente">Pendiente de Revisión</option>
                            <option value="en_revision">En Revisión</option>
                            <option value="aprobado">Aprobado</option>
                            <option value="rechazado">Rechazado</option>
                            <option value="requiere_correccion">Requiere Corrección</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Docente Tutor</label>
                        <select class="form-select" name="filtro_docente">
                            <option value="">Todos los docentes</option>
                            <option value="1">Dr. Mario Montenegro - Rector</option>
                            <option value="2">Ing. Juan Pérez - Coordinador</option>
                            <option value="3">Mg. María González - Tutora</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Entidad Receptora</label>
                        <input type="text" class="form-control" name="filtro_entidad" placeholder="Buscar por entidad...">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha Desde</label>
                                <input type="date" class="form-control" name="fecha_desde">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha Hasta</label>
                                <input type="date" class="form-control" name="fecha_hasta">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="limpiarFiltrosPracticas()">Limpiar</button>
                <button type="button" class="btn btn-primary" onclick="aplicarFiltrosPracticas()">
                    <i class="fas fa-search me-1"></i>Aplicar Filtros
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cambiar Estado del Documento -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Cambiar Estado del Documento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formCambiarEstado">
                    <input type="hidden" name="documento_id" id="documento_id_estado">
                    <div class="mb-3">
                        <label class="form-label">Documento</label>
                        <input type="text" class="form-control" id="nombre_documento_estado" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nuevo Estado</label>
                        <select class="form-select" name="nuevo_estado" required>
                            <option value="">Seleccionar nuevo estado...</option>
                            <option value="pendiente">Pendiente de Revisión</option>
                            <option value="en_revision">En Revisión</option>
                            <option value="aprobado">Aprobado</option>
                            <option value="rechazado">Rechazado</option>
                            <option value="requiere_correccion">Requiere Corrección</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comentarios del Administrador</label>
                        <textarea class="form-control" name="comentarios_estado" rows="3" placeholder="Comentarios sobre el cambio de estado, correcciones necesarias, etc..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarCambioEstado()">
                    <i class="fas fa-save me-1"></i>Guardar Cambio
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Funciones principales
    function showModal(modalId) {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    function cambiarVista(tipo) {
        if (tipo === 'grid') {
            document.getElementById('vistaGrid').classList.remove('d-none');
            document.getElementById('vistaLista').classList.add('d-none');
        } else {
            document.getElementById('vistaGrid').classList.add('d-none');
            document.getElementById('vistaLista').classList.remove('d-none');
        }
    }

    function verDocumento(id) {
        showNotification('Visualizando documento...', 'info');
    }

    function descargarDocumento(id) {
        showNotification('Descargando documento...', 'success');
    }

    function eliminarDocumento(id) {
        if (confirm('¿Estás seguro de que quieres eliminar este documento?')) {
            showNotification('Documento eliminado exitosamente', 'success');
        }
    }

    function subirDocumentoPractica() {
        showNotification('Documento subido exitosamente', 'success');
        bootstrap.Modal.getInstance(document.getElementById('modalSubirDocumentoPractica')).hide();
    }

    function aplicarFiltrosPracticas() {
        showNotification('Filtros aplicados', 'info');
        bootstrap.Modal.getInstance(document.getElementById('modalFiltrosPracticas')).hide();
    }

    function limpiarFiltrosPracticas() {
        document.getElementById('formFiltrosPracticas').reset();
        showNotification('Filtros limpiados', 'info');
    }

    function exportarDocumentosPracticas() {
        showNotification('Exportando documentos...', 'info');
    }

    function generarReportePracticas() {
        showNotification('Generando reporte...', 'info');
    }

    function cambiarEstadoDocumento(id) {
        // Simular obtención de datos del documento
        document.getElementById('documento_id_estado').value = id;
        document.getElementById('nombre_documento_estado').value = 'Informe_Final_Practica.pdf';
        
        // Mostrar modal
        showModal('modalCambiarEstado');
    }

    function guardarCambioEstado() {
        const nuevoEstado = document.querySelector('select[name="nuevo_estado"]').value;
        const comentarios = document.querySelector('textarea[name="comentarios_estado"]').value;
        
        if (!nuevoEstado) {
            showNotification('Debe seleccionar un nuevo estado', 'error');
            return;
        }
        
        // Aquí se enviaría la petición al servidor
        showNotification(`Estado cambiado a: ${nuevoEstado}`, 'success');
        
        // Cerrar modal
        bootstrap.Modal.getInstance(document.getElementById('modalCambiarEstado')).hide();
        
        // Limpiar formulario
        document.getElementById('formCambiarEstado').reset();
    }

    function revisionMasiva() {
        showNotification('Función de revisión masiva en desarrollo. Permite cambiar el estado de múltiples documentos a la vez.', 'info');
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

    // Drag and Drop functionality
    document.addEventListener('DOMContentLoaded', function() {
        const uploadArea = document.getElementById('uploadAreaPractica');
        const archivoInput = document.getElementById('archivoInputPractica');

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
