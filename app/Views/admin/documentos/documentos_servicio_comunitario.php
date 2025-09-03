<?= $this->extend('admin/layouts/mainAdmin') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/documentos.css') ?>" />
<style>
    .documento-card {
        transition: all 0.3s ease;
        border-left: 4px solid #17a2b8;
    }
    .documento-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .estado-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    .tipo-documento-header {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        border-radius: 8px 8px 0 0;
    }
    .filtros-rapidos {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .estadistica-card {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        border-radius: 10px;
        transition: transform 0.3s ease;
    }
    .estadistica-card:hover {
        transform: scale(1.05);
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
                    <i class="fas fa-hands-helping me-2"></i>
                    Gestión de Documentos de Servicio Comunitario
                </h3>
                <p class="text-center text-muted">Administra todos los documentos de servicio comunitario de los estudiantes clasificados por tipo</p>
            </div>
        </div>

        <!-- Estadísticas Generales -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card estadistica-card text-center shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="totalDocumentosServicio" style="font-size:2.5rem;"><?= $estadisticas['total'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Total Documentos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: #fff;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="documentosAprobadosServicio" style="font-size:2.5rem;"><?= $estadisticas['aprobados'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Aprobados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); color: #fff;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="documentosPendientesServicio" style="font-size:2.5rem;"><?= $estadisticas['pendientes'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #fffbe6;">Pendientes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%); color: #fff;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="documentosRechazadosServicio" style="font-size:2.5rem;"><?= $estadisticas['rechazados'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #ffe0e0;">Rechazados</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros Rápidos -->
        <div class="filtros-rapidos">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Filtrar por Estado:</label>
                    <select class="form-select" id="filtroEstado" onchange="aplicarFiltros()">
                        <option value="">Todos los estados</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="En Revisión">En Revisión</option>
                        <option value="Aprobado">Aprobado</option>
                        <option value="Rechazado">Rechazado</option>
                        <option value="Requiere Corrección">Requiere Corrección</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Filtrar por Tipo:</label>
                    <select class="form-select" id="filtroTipo" onchange="aplicarFiltros()">
                        <option value="">Todos los tipos</option>
                        <?php if (isset($tiposDocumentos)): ?>
                            <?php foreach ($tiposDocumentos as $tipo): ?>
                                <option value="<?= $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>"><?= $tipo['CODIGO'] ?>. <?= $tipo['NOMBRE'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Buscar Estudiante:</label>
                    <input type="text" class="form-control" id="buscarEstudiante" placeholder="Nombre o cédula..." onkeyup="aplicarFiltros()">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary" onclick="limpiarFiltros()">
                            <i class="fas fa-times me-1"></i>Limpiar
                        </button>
                        <button class="btn btn-primary" onclick="generarReporteServicio()">
                            <i class="fas fa-download me-1"></i>Exportar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row mb-4 justify-content-center">
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalSubirDocumentoServicio')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #17a2b8;"></i>
                            <div class="fw-bold">Nuevo Documento</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="revisionMasiva()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-tasks fa-2x mb-2" style="color: #007bff;"></i>
                            <div class="fw-bold">Revisión Masiva</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="cambiarVista('grid')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-th-large fa-2x mb-2" style="color: #6f42c1;"></i>
                            <div class="fw-bold">Vista Grid</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="cambiarVista('list')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-list fa-2x mb-2" style="color: #fd7e14;"></i>
                            <div class="fw-bold">Vista Lista</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vista Grid de Documentos por Tipo -->
        <div id="vistaGrid">
            <?php if (!empty($tiposDocumentos)): ?>
                <?php foreach ($tiposDocumentos as $tipo): ?>
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="tipo-documento-header p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1">
                                                <i class="fas fa-hands-helping me-2"></i>
                                                <?= $tipo['CODIGO'] ?>. <?= $tipo['NOMBRE'] ?>
                                            </h5>
                                            <small class="opacity-75"><?= $tipo['DESCRIPCION'] ?></small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-light text-dark">
                                                <?= $tipo['OBLIGATORIO'] ? 'Obligatorio' : 'Opcional' ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row" id="documentos-<?= $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>">
                                        <!-- Los documentos de este tipo se cargarán aquí -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay tipos de documentos configurados
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Vista Lista -->
        <div id="vistaLista" class="d-none">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-list me-2"></i>
                                Lista de Documentos de Servicio Comunitario
                            </span>
                            <div class="d-flex gap-2">
                                <button class="btn btn-light btn-sm" onclick="cambiarVista('grid')">
                                    <i class="fas fa-th-large me-1"></i>Grid
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Estudiante</th>
                                            <th>Tipo de Documento</th>
                                            <th>Estado</th>
                                            <th>Fecha Subida</th>
                                            <th>Proyecto Social</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaDocumentosLista">
                                        <!-- Los documentos se cargarán dinámicamente aquí -->
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
    let documentosServicio = [];
    let vistaActual = 'grid';

    // Funciones principales
    function showModal(modalId) {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    function cambiarVista(tipo) {
        vistaActual = tipo;
        if (tipo === 'grid') {
            document.getElementById('vistaGrid').classList.remove('d-none');
            document.getElementById('vistaLista').classList.add('d-none');
            cargarDocumentosGrid();
        } else {
            document.getElementById('vistaGrid').classList.add('d-none');
            document.getElementById('vistaLista').classList.remove('d-none');
            generarVistaLista();
        }
    }

    function cargarDocumentosGrid() {
        // Cargar documentos para cada tipo
        fetch('<?= base_url('admin/documentos/servicio/obtenerDocumentos') ?>')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    documentosServicio = data.documentos;
                    mostrarDocumentosPorTipo();
                } else {
                    showNotification('Error al cargar documentos: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al cargar documentos', 'error');
            });
    }

    function mostrarDocumentosPorTipo() {
        const tiposDocumentos = <?= json_encode($tiposDocumentos ?? []) ?>;
        
        tiposDocumentos.forEach(tipo => {
            const contenedor = document.getElementById(`documentos-${tipo.ID_TIPO_DOCUMENTO_SERVICIO}`);
            if (contenedor) {
                contenedor.innerHTML = '';
                
                // Filtrar documentos de este tipo
                const documentosTipo = documentosServicio.filter(doc => 
                    doc.ID_TIPO_DOCUMENTO == tipo.ID_TIPO_DOCUMENTO_SERVICIO
                );
                
                if (documentosTipo.length === 0) {
                    contenedor.innerHTML = `
                        <div class="col-12 text-center py-4">
                            <div class="alert alert-light">
                                <i class="fas fa-inbox me-2"></i>
                                No hay documentos subidos para este tipo
                            </div>
                        </div>
                    `;
                } else {
                    documentosTipo.forEach(doc => {
                        const documentoCard = crearCardDocumento(doc);
                        contenedor.appendChild(documentoCard);
                    });
                }
            }
        });
    }

    function crearCardDocumento(doc) {
        const col = document.createElement('div');
        col.className = 'col-md-6 col-lg-4 mb-3';
        
        const estadoClass = obtenerClaseEstado(doc.ESTADO_REVISION);
        const fecha = new Date(doc.FECHA_SUBIDA).toLocaleDateString('es-ES');
        
        col.innerHTML = `
            <div class="card documento-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0">${doc.NOMBRE_ESTUDIANTE} ${doc.APELLIDO_ESTUDIANTE}</h6>
                        <span class="badge ${estadoClass} estado-badge">${doc.ESTADO_REVISION}</span>
                    </div>
                    <p class="card-text text-muted small mb-2">
                        <i class="fas fa-file me-1"></i>${doc.NOMBRE_ARCHIVO}
                    </p>
                    <p class="card-text text-muted small mb-2">
                        <i class="fas fa-project-diagram me-1"></i>${doc.PROYECTO_SOCIAL || 'No especificado'}
                    </p>
                    <p class="card-text text-muted small mb-3">
                        <i class="fas fa-calendar me-1"></i>${fecha}
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">${doc.CEDULA_ESTUDIANTE}</small>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" onclick="verDocumento(${doc.ID_DOCUMENTO_SERVICIO})" title="Ver">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-success" onclick="descargarDocumento(${doc.ID_DOCUMENTO_SERVICIO})" title="Descargar">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(${doc.ID_DOCUMENTO_SERVICIO})" title="Cambiar Estado">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-danger" onclick="eliminarDocumento(${doc.ID_DOCUMENTO_SERVICIO})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        return col;
    }

    function generarVistaLista() {
        const tbody = document.getElementById('tablaDocumentosLista');
        tbody.innerHTML = '';

        if (documentosServicio.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay documentos para mostrar
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        documentosServicio.forEach(doc => {
            const estadoClass = obtenerClaseEstado(doc.ESTADO_REVISION);
            const fecha = new Date(doc.FECHA_SUBIDA).toLocaleDateString('es-ES');
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <div class="d-flex align-items-center">
                        <div class="file-icon bg-info me-3" style="width: 40px; height: 40px; font-size: 1.2rem;">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">${doc.NOMBRE_ESTUDIANTE} ${doc.APELLIDO_ESTUDIANTE}</div>
                            <small class="text-muted">${doc.CEDULA_ESTUDIANTE}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <div>
                        <div class="fw-semibold">${doc.TIPO_DOCUMENTO_NOMBRE}</div>
                        <small class="text-muted">${doc.NOMBRE_ARCHIVO}</small>
                    </div>
                </td>
                <td><span class="badge ${estadoClass} estado-badge">${doc.ESTADO_REVISION}</span></td>
                <td>${fecha}</td>
                <td>${doc.PROYECTO_SOCIAL || 'No especificado'}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" onclick="verDocumento(${doc.ID_DOCUMENTO_SERVICIO})" title="Ver">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-success" onclick="descargarDocumento(${doc.ID_DOCUMENTO_SERVICIO})" title="Descargar">
                            <i class="fas fa-download"></i>
                        </button>
                        <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(${doc.ID_DOCUMENTO_SERVICIO})" title="Cambiar Estado">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-danger" onclick="eliminarDocumento(${doc.ID_DOCUMENTO_SERVICIO})" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            
            tbody.appendChild(row);
        });
    }

    function obtenerClaseEstado(estado) {
        switch (estado) {
            case 'Aprobado': return 'bg-success text-white';
            case 'Rechazado': return 'bg-danger text-white';
            case 'En Revisión': return 'bg-info text-white';
            case 'Requiere Corrección': return 'bg-warning text-dark';
            case 'Pendiente': return 'bg-secondary text-white';
            default: return 'bg-secondary text-white';
        }
    }

    function aplicarFiltros() {
        const filtroEstado = document.getElementById('filtroEstado').value;
        const filtroTipo = document.getElementById('filtroTipo').value;
        const buscarEstudiante = document.getElementById('buscarEstudiante').value.toLowerCase();
        
        let documentosFiltrados = [...documentosServicio];
        
        if (filtroEstado) {
            documentosFiltrados = documentosFiltrados.filter(doc => doc.ESTADO_REVISION === filtroEstado);
        }
        
        if (filtroTipo) {
            documentosFiltrados = documentosFiltrados.filter(doc => doc.ID_TIPO_DOCUMENTO == filtroTipo);
        }
        
        if (buscarEstudiante) {
            documentosFiltrados = documentosFiltrados.filter(doc => 
                doc.NOMBRE_ESTUDIANTE.toLowerCase().includes(buscarEstudiante) ||
                doc.APELLIDO_ESTUDIANTE.toLowerCase().includes(buscarEstudiante) ||
                doc.CEDULA_ESTUDIANTE.includes(buscarEstudiante)
            );
        }
        
        // Actualizar la vista con los documentos filtrados
        const documentosOriginales = documentosServicio;
        documentosServicio = documentosFiltrados;
        
        if (vistaActual === 'grid') {
            mostrarDocumentosPorTipo();
        } else {
            generarVistaLista();
        }
        
        // Restaurar documentos originales para futuros filtros
        documentosServicio = documentosOriginales;
    }

    function limpiarFiltros() {
        document.getElementById('filtroEstado').value = '';
        document.getElementById('filtroTipo').value = '';
        document.getElementById('buscarEstudiante').value = '';
        
        if (vistaActual === 'grid') {
            cargarDocumentosGrid();
        } else {
            generarVistaLista();
        }
    }

    function verDocumento(id) {
        // Redirigir a la descarga para ver el documento
        window.open(`<?= base_url('admin/documentos/descargarDocumentoServicio') ?>/${id}`, '_blank');
    }

    function descargarDocumento(id) {
        // Crear un enlace temporal para descargar
        const link = document.createElement('a');
        link.href = `<?= base_url('admin/documentos/descargarDocumentoServicio') ?>/${id}`;
        link.download = '';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function eliminarDocumento(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este documento?')) {
            fetch(`<?= base_url('admin/documentos/eliminarDocumentoServicio') ?>/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Documento eliminado exitosamente', 'success');
                    cargarDocumentosGrid(); // Recargar documentos
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al eliminar el documento', 'error');
            });
        }
    }

    function subirDocumento(tipoDocumentoId) {
        // Crear input de archivo
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = '.pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar';
        
        input.onchange = function(e) {
            const archivo = e.target.files[0];
            if (!archivo) return;
            
            // Validar tamaño (50 MB máximo)
            if (archivo.size > 50 * 1024 * 1024) {
                showNotification('El archivo excede el tamaño máximo permitido (50 MB)', 'error');
                return;
            }
            
            // Crear FormData
            const formData = new FormData();
            formData.append('archivo', archivo);
            formData.append('id_tipo_documento', tipoDocumentoId);
            formData.append('id_servicio', 1); // Por ahora usar ID 1, esto debería venir de la sesión o contexto
            
            fetch('<?= base_url('admin/documentos/subirDocumentoServicio') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Documento subido exitosamente', 'success');
                    cargarDocumentosGrid(); // Recargar documentos
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al subir el documento', 'error');
            });
        };
        
        input.click();
    }

    function cambiarEstadoDocumento(id) {
        const nuevoEstado = prompt('Ingrese el nuevo estado (Pendiente, Aprobado, Rechazado):');
        
        if (!nuevoEstado || !['Pendiente', 'Aprobado', 'Rechazado'].includes(nuevoEstado)) {
            showNotification('Estado no válido', 'error');
            return;
        }
        
        const observaciones = prompt('Ingrese observaciones (opcional):');
        
        const formData = new FormData();
        formData.append('id', id);
        formData.append('estado', nuevoEstado);
        if (observaciones) {
            formData.append('observaciones', observaciones);
        }
        
        fetch('<?= base_url('admin/documentos/cambiarEstadoDocumentoServicio') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Estado actualizado exitosamente', 'success');
                cargarDocumentosGrid(); // Recargar documentos
            } else {
                showNotification('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al actualizar el estado', 'error');
        });
    }

    function generarReporteServicio() {
        const fechaInicio = prompt('Ingrese fecha de inicio (YYYY-MM-DD) o deje vacío para todos:');
        const fechaFin = prompt('Ingrese fecha de fin (YYYY-MM-DD) o deje vacío para todos:');
        const estado = prompt('Ingrese estado a filtrar (Pendiente, Aprobado, Rechazado) o deje vacío para todos:');
        
        let url = '<?= base_url('admin/documentos/generarReporteServicio') ?>?';
        const params = new URLSearchParams();
        
        if (fechaInicio) params.append('fecha_inicio', fechaInicio);
        if (fechaFin) params.append('fecha_fin', fechaFin);
        if (estado) params.append('estado', estado);
        
        url += params.toString();
        
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Crear y descargar archivo CSV
                const csv = generarCSV(data.data);
                descargarCSV(csv, 'reporte_documentos_servicio_comunitario.csv');
                showNotification('Reporte generado exitosamente', 'success');
            } else {
                showNotification('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al generar el reporte', 'error');
        });
    }

    function generarCSV(datos) {
        if (!datos || datos.length === 0) {
            return 'No hay datos para exportar';
        }
        
        const headers = ['ID', 'Tipo Documento', 'Proyecto Social', 'Estudiante', 'Estado', 'Fecha Subida', 'Observaciones'];
        const csvContent = [
            headers.join(','),
            ...datos.map(doc => [
                doc.ID_DOCUMENTO_SERVICIO,
                `"${doc.TIPO_DOCUMENTO_NOMBRE}"`,
                `"${doc.PROYECTO_SOCIAL || ''}"`,
                `"${doc.NOMBRE_ESTUDIANTE} ${doc.APELLIDO_ESTUDIANTE}"`,
                doc.ESTADO_REVISION,
                doc.FECHA_SUBIDA,
                `"${doc.OBSERVACIONES || ''}"`
            ].join(','))
        ].join('\n');
        
        return csvContent;
    }

    function descargarCSV(csvContent, filename) {
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function revisionMasiva() {
        showNotification('Función de revisión masiva - Por implementar', 'info');
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

    // Inicialización al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Vista de documentos de servicio comunitario cargada');
        
        // Cargar documentos inicialmente
        cargarDocumentosGrid();
    });
</script>
<?= $this->endSection() ?>
