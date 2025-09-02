<?= $this->extend('admin/layouts/mainAdmin') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/documentos.css') ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-hands-helping me-2"></i>
                    Documentos de Servicio Comunitario
                </h3>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="totalDocumentosServicio" style="font-size:2.5rem;"><?= $estadisticas['total'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Total Documentos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #28a745 80%, #155724 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="documentosAprobadosServicio" style="font-size:2.5rem;"><?= $estadisticas['aprobados'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Aprobados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #ffc107 80%, #b38600 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="documentosPendientesServicio" style="font-size:2.5rem;"><?= $estadisticas['pendientes'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #fffbe6;">Pendientes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #dc3545 80%, #a71e2a 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="documentosRechazadosServicio" style="font-size:2.5rem;"><?= $estadisticas['rechazados'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #ffe0e0;">Rechazados</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row mb-4 justify-content-center">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalNuevoDocumentoPractica')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #28a745; text-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);"
                            ></i>
                            <div class="fw-bold">Nuevo Documento</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalFiltrosPracticas')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-filter fa-2x mb-2" style="color: #007bff; text-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);"></i>
                            <div class="fw-bold">Filtros</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="generarReportePracticas()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-chart-bar fa-2x mb-2" style="color: #ffc107; text-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);"></i>
                            <div class="fw-bold">Generar Reporte</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-hands-helping me-2"></i>
                            Documentos de Servicio Comunitario
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
                        <div id="vistaGrid" class="row g-3">
                            <?php if (!empty($tiposDocumentos)): ?>
                                <?php foreach ($tiposDocumentos as $tipo): ?>
                                    <?php 
                                    // Buscar si existe un documento de este tipo
                                    $documentoExistente = null;
                                    foreach ($documentosRecientes as $doc) {
                                        if ($doc['ID_TIPO_DOCUMENTO'] == $tipo['ID_TIPO_DOCUMENTO_SERVICIO']) {
                                            $documentoExistente = $doc;
                                            break;
                                        }
                                    }
                                    ?>
                                    <div class="col-md-4 col-lg-3">
                                        <div class="file-item p-3 h-100" id="doc-<?= $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="file-icon bg-primary me-3">
                                                    <i class="fas fa-clipboard-list"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1"><?= $tipo['CODIGO'] ?>. <?= $tipo['NOMBRE'] ?></h6>
                                                    <small class="text-muted" id="estado-<?= $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>">
                                                        Estado: <?= $documentoExistente ? $documentoExistente['ESTADO_REVISION'] : 'No subido' ?>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <span class="category-badge bg-primary text-white"><?= $tipo['CODIGO'] ?></span>
                                                <span class="category-badge <?= $documentoExistente ? 
                                                    ($documentoExistente['ESTADO_REVISION'] == 'Aprobado' ? 'bg-success' : 
                                                     ($documentoExistente['ESTADO_REVISION'] == 'Rechazado' ? 'bg-danger' : 'bg-warning')) : 'bg-secondary' ?> text-white ms-2" 
                                                    id="status-<?= $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>">
                                                    <?= $documentoExistente ? $documentoExistente['ESTADO_REVISION'] : 'No subido' ?>
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted" id="fecha-<?= $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>">
                                                    <?= $documentoExistente ? date('d/m/Y', strtotime($documentoExistente['FECHA_SUBIDA'])) : 'No subido' ?>
                                                </small>
                                                <div class="btn-group btn-group-sm">
                                                    <?php if ($documentoExistente): ?>
                                                        <button class="btn btn-outline-primary" onclick="verDocumento(<?= $documentoExistente['ID_DOCUMENTO_SERVICIO'] ?>)" title="Ver" id="btn-ver-<?= $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-outline-success" onclick="descargarDocumento(<?= $documentoExistente['ID_DOCUMENTO_SERVICIO'] ?>)" title="Descargar" id="btn-descargar-<?= $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                        <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(<?= $documentoExistente['ID_DOCUMENTO_SERVICIO'] ?>)" title="Cambiar Estado" id="btn-estado-<?= $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger" onclick="eliminarDocumento(<?= $documentoExistente['ID_DOCUMENTO_SERVICIO'] ?>)" title="Eliminar" id="btn-eliminar-<?= $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <button class="btn btn-outline-info" onclick="subirDocumento(<?= $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>)" title="Subir Documento" id="btn-subir-<?= $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>">
                                                            <i class="fas fa-upload"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12 text-center">
                                    <p class="text-muted">No hay tipos de documentos configurados</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div id="vistaLista" class="d-none">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Documento</th>
                                            <th>Tipo</th>
                                            <th>Estado</th>
                                            <th>Fecha Subida</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaDocumentosLista">
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
function cambiarVista(tipo) {
    if (tipo === 'grid') {
        document.getElementById('vistaGrid').classList.remove('d-none');
        document.getElementById('vistaLista').classList.add('d-none');
    } else {
        document.getElementById('vistaGrid').classList.add('d-none');
        document.getElementById('vistaLista').classList.remove('d-none');
        generarVistaLista();
    }
}

function generarVistaLista() {
    const tbody = document.getElementById('tablaDocumentosLista');
    tbody.innerHTML = '';
    
    // Obtener datos de la vista PHP
    const tiposDocumentos = <?= json_encode($tiposDocumentos) ?>;
    const documentosRecientes = <?= json_encode($documentosRecientes) ?>;
    
    tiposDocumentos.forEach(tipo => {
        // Buscar si existe un documento de este tipo
        const documentoExistente = documentosRecientes.find(doc => 
            doc.ID_TIPO_DOCUMENTO == tipo.ID_TIPO_DOCUMENTO_SERVICIO
        );
        
        const row = document.createElement('tr');
        const estado = documentoExistente ? documentoExistente.ESTADO_REVISION : 'No subido';
        const fecha = documentoExistente ? 
            new Date(documentoExistente.FECHA_SUBIDA).toLocaleDateString('es-ES') : 
            'No subido';
        
        let estadoClass = 'bg-secondary';
        if (documentoExistente) {
            switch (estado) {
                case 'Aprobado': estadoClass = 'bg-success'; break;
                case 'Rechazado': estadoClass = 'bg-danger'; break;
                case 'Pendiente': estadoClass = 'bg-warning'; break;
            }
        }
        
        row.innerHTML = `
            <td>
                <div class="d-flex align-items-center">
                    <div class="file-icon bg-primary me-3" style="width: 40px; height: 40px; font-size: 1.2rem;">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div>
                        <div class="fw-semibold">${tipo.CODIGO}. ${tipo.NOMBRE}</div>
                        <small class="text-muted">${tipo.DESCRIPCION || 'Sin descripción'}</small>
                    </div>
                </div>
            </td>
            <td><span class="category-badge bg-primary text-white">${tipo.CODIGO}</span></td>
            <td><span class="category-badge ${estadoClass} text-white">${estado}</span></td>
            <td>${fecha}</td>
            <td>
                <div class="btn-group btn-group-sm">
                    ${documentoExistente ? `
                        <button class="btn btn-outline-primary" onclick="verDocumento(${documentoExistente.ID_DOCUMENTO_SERVICIO})" title="Ver">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-success" onclick="descargarDocumento(${documentoExistente.ID_DOCUMENTO_SERVICIO})" title="Descargar">
                            <i class="fas fa-download"></i>
                        </button>
                        <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(${documentoExistente.ID_DOCUMENTO_SERVICIO})" title="Cambiar Estado">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-danger" onclick="eliminarDocumento(${documentoExistente.ID_DOCUMENTO_SERVICIO})" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    ` : `
                        <button class="btn btn-outline-info" onclick="subirDocumento(${tipo.ID_TIPO_DOCUMENTO_SERVICIO})" title="Subir Documento">
                            <i class="fas fa-upload"></i>
                        </button>
                    `}
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function showModal(modalId) {
    alert('Modal: ' + modalId);
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
                alert('Documento eliminado exitosamente');
                location.reload(); // Recargar la página para actualizar la vista
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el documento');
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
            alert('El archivo excede el tamaño máximo permitido (50 MB)');
            return;
        }
        
        // Crear FormData
        const formData = new FormData();
        formData.append('archivo', archivo);
        formData.append('id_tipo_documento', tipoDocumentoId);
        formData.append('id_servicio', 1); // Por ahora usar ID 1, esto debería venir de la sesión o contexto
        
        // Mostrar indicador de carga
        const btn = document.getElementById(`btn-subir-${tipoDocumentoId}`);
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;
        
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
                alert('Documento subido exitosamente');
                location.reload(); // Recargar la página para actualizar la vista
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al subir el documento');
        })
        .finally(() => {
            btn.innerHTML = originalContent;
            btn.disabled = false;
        });
    };
    
    input.click();
}

function cambiarEstadoDocumento(id) {
    const nuevoEstado = prompt('Ingrese el nuevo estado (Pendiente, Aprobado, Rechazado):');
    
    if (!nuevoEstado || !['Pendiente', 'Aprobado', 'Rechazado'].includes(nuevoEstado)) {
        alert('Estado no válido');
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
            alert('Estado actualizado exitosamente');
            location.reload(); // Recargar la página para actualizar la vista
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar el estado');
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
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al generar el reporte');
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
    alert('Función de revisión masiva - Por implementar');
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Vista de servicio comunitario cargada');
});
</script>
<?= $this->endSection() ?>
