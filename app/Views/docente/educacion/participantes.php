<?= $this->extend('docente/layouts/mainDocente') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/actividades.css') ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        Participantes: <?= esc($actividad['NOMBRE_ACTIVIDAD']) ?>
                    </h3>
                    <a href="<?= base_url('docente/actividades-educacion') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver a Mis Actividades
                    </a>
                </div>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Agregar participante</h5>
                    </div>
                    <div class="card-body">
                        <form id="formAgregarParticipante">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id_actividad" value="<?= (int)$actividad['ID_ACTIVIDAD_EDUCACION'] ?>">
                            <div class="mb-3">
                                <label class="form-label">Estudiante</label>
                                <select class="form-select" name="id_estudiante" id="selectEstudiante" required>
                                    <option value="">Seleccionar estudiante...</option>
                                    <?php foreach ($estudiantes as $est): ?>
                                        <option value="<?= $est['ID_ESTUDIANTE'] ?>">
                                            <?= esc($est['APELLIDO']) ?>, <?= esc($est['NOMBRE']) ?> - <?= esc($est['CEDULA']) ?>
                                            <?= !empty($est['CARRERA']) ? ' (' . esc($est['CARRERA']) . ')' : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" id="btnAgregar">
                                <i class="fas fa-plus me-1"></i>Inscribir
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h4 class="text-primary" id="totalParticipantes"><?= count($participantes) ?></h4>
                        <p class="text-muted mb-0">Inscritos en esta actividad</p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-list me-2"></i>Lista de participantes</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Apellidos y nombres</th>
                                        <th>Cédula</th>
                                        <th>Carrera</th>
                                        <th>Fecha inscripción</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyParticipantes">
                                    <?php if (empty($participantes)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="fas fa-users fa-3x mb-2"></i>
                                                <p class="mb-0">Aún no hay participantes. Agregue estudiantes con el formulario.</p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($participantes as $i => $p): ?>
                                            <tr data-id-estudiante="<?= $p['ID_ESTUDIANTE'] ?>">
                                                <td><?= $i + 1 ?></td>
                                                <td><?= esc($p['APELLIDO']) ?>, <?= esc($p['NOMBRE']) ?></td>
                                                <td><?= esc($p['CEDULA']) ?></td>
                                                <td><?= esc($p['CARRERA'] ?? '-') ?></td>
                                                <td><?= !empty($p['FECHA_INSCRIPCION']) ? date('d/m/Y', strtotime($p['FECHA_INSCRIPCION'])) : '-' ?></td>
                                                <td><span class="badge bg-success"><?= esc($p['ESTADO'] ?? 'Inscrito') ?></span></td>
                                                <td>
                                                    <button type="button" class="btn btn-outline-danger btn-sm btn-quitar" data-id-estudiante="<?= $p['ID_ESTUDIANTE'] ?>" title="Quitar de la actividad">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
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
    (function() {
        const idActividad = <?= (int)$actividad['ID_ACTIVIDAD_EDUCACION'] ?>;
        const urlAgregar = '<?= base_url('docente/actividades-educacion/participantes/agregar') ?>';
        const urlQuitar = '<?= base_url('docente/actividades-educacion/participantes/quitar') ?>';

        function notificar(msg, tipo) {
            const colors = {
                success: '#28a745',
                error: '#dc3545',
                info: '#17a2b8'
            };
            const n = document.createElement('div');
            n.className = 'position-fixed top-0 end-0 m-3';
            n.style.zIndex = '9999';
            n.innerHTML = '<div class="alert alert-' + tipo + ' alert-dismissible fade show" role="alert" style="background:' + (colors[tipo] || colors.info) + ';color:white;border:none;">' + msg + '<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button></div>';
            document.body.appendChild(n);
            setTimeout(function() {
                if (n.parentNode) n.remove();
            }, 5000);
        }

        function actualizarTotal() {
            const filas = document.querySelectorAll('#tbodyParticipantes tr[data-id-estudiante]');
            const el = document.getElementById('totalParticipantes');
            if (el) el.textContent = filas.length;
        }

        document.getElementById('formAgregarParticipante').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnAgregar');
            const fd = new FormData(this);
            btn.disabled = true;
            fetch(urlAgregar, {
                    method: 'POST',
                    body: fd
                })
                .then(r => r.json())
                .then(function(res) {
                    if (res.success) {
                        notificar(res.message, 'success');
                        window.location.reload();
                    } else {
                        notificar(res.message || 'Error al agregar', 'error');
                        btn.disabled = false;
                    }
                })
                .catch(function() {
                    notificar('Error de conexión', 'error');
                    btn.disabled = false;
                });
        });

        document.getElementById('tbodyParticipantes').addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-quitar');
            if (!btn) return;
            const idEstudiante = btn.getAttribute('data-id-estudiante');
            if (!confirm('¿Quitar a este participante de la actividad?')) return;
            const fd = new FormData();
            fd.append('id_actividad', idActividad);
            fd.append('id_estudiante', idEstudiante);
            fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
            fetch(urlQuitar, {
                    method: 'POST',
                    body: fd
                })
                .then(r => r.json())
                .then(function(res) {
                    if (res.success) {
                        notificar(res.message, 'success');
                        const row = document.querySelector('tr[data-id-estudiante="' + idEstudiante + '"]');
                        if (row) row.remove();
                        actualizarTotal();
                        if (document.querySelectorAll('#tbodyParticipantes tr[data-id-estudiante]').length === 0) {
                            document.getElementById('tbodyParticipantes').innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-users fa-3x mb-2"></i><p class="mb-0">Aún no hay participantes. Agregue estudiantes con el formulario.</p></td></tr>';
                        }
                    } else {
                        notificar(res.message || 'Error al quitar', 'error');
                    }
                })
                .catch(function() {
                    notificar('Error de conexión', 'error');
                });
        });
    })();
</script>
<?= $this->endSection() ?>