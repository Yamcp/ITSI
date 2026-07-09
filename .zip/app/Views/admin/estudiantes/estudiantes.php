<?= $this->extend('admin/layouts/mainAdmin') ?>

<?php
$lista     = $estudiantes ?? [];
$total     = count($lista);
$carreras  = [];
foreach ($lista as $row) {
    $c = trim((string) ($row['CARRERA'] ?? ''));
    if ($c !== '') {
        $carreras[$c] = $c;
    }
}
ksort($carreras, SORT_NATURAL | SORT_FLAG_CASE);

$iniciales = static function (?string $nombre, ?string $apellido): string {
    $n = mb_strtoupper(mb_substr(trim((string) $nombre), 0, 1));
    $a = mb_strtoupper(mb_substr(trim((string) $apellido), 0, 1));

    return ($n . $a) !== '' ? $n . $a : '?';
};
?>

<?= $this->section('styles') ?>
<style>
    .estudiantes-page {
        --est-card-radius: 16px;
        --est-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        --est-border: 1px solid rgba(15, 23, 42, 0.06);
        --est-gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 55%, #a855f7 100%);
    }

    .estudiantes-hero {
        background: var(--est-gradient);
        border-radius: var(--est-card-radius);
        padding: 1.5rem 1.75rem;
        color: #fff;
        box-shadow: var(--est-shadow);
        margin-bottom: 1.5rem;
    }

    .estudiantes-hero h1 {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        margin: 0 0 0.35rem;
    }

    .estudiantes-hero p {
        margin: 0;
        opacity: 0.92;
        font-size: 0.95rem;
    }

    .estudiantes-hero .est-badge-total {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.35);
        border-radius: 999px;
        padding: 0.4rem 1rem;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .estudiantes-toolbar {
        background: #fff;
        border: var(--est-border);
        border-radius: var(--est-card-radius);
        padding: 1rem 1.25rem;
        box-shadow: var(--est-shadow);
        margin-bottom: 1.25rem;
    }

    .estudiantes-toolbar .input-group-text {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #64748b;
    }

    .estudiantes-toolbar .form-control,
    .estudiantes-toolbar .form-select {
        border-color: #e2e8f0;
    }

    .estudiantes-toolbar .form-control:focus,
    .estudiantes-toolbar .form-select:focus {
        border-color: #818cf8;
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.15);
    }

    .estudiantes-table-card {
        background: #fff;
        border: var(--est-border);
        border-radius: var(--est-card-radius);
        box-shadow: var(--est-shadow);
        overflow: hidden;
    }

    .estudiantes-table-card .table-head {
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .estudiantes-table-wrap {
        max-height: min(70vh, 640px);
        overflow: auto;
    }

    .estudiantes-table-wrap table thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        background: #f8fafc;
        color: #475569;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 600;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
        box-shadow: 0 1px 0 #e2e8f0;
    }

    .estudiantes-table-wrap tbody tr {
        transition: background 0.15s ease;
    }

    .estudiantes-table-wrap tbody tr:hover {
        background: #fafbff;
    }

    .est-avatar {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        color: #fff;
        flex-shrink: 0;
        background: linear-gradient(145deg, #6366f1, #8b5cf6);
        overflow: hidden;
    }

    .est-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .est-nombre {
        font-weight: 600;
        color: #0f172a;
        line-height: 1.25;
    }

    .est-sub {
        font-size: 0.8rem;
        color: #64748b;
    }

    .est-email-link {
        color: #4f46e5;
        text-decoration: none;
        word-break: break-word;
    }

    .est-email-link:hover {
        text-decoration: underline;
    }

    .est-sem-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2rem;
        padding: 0.2rem 0.5rem;
        border-radius: 8px;
        background: #eef2ff;
        color: #4338ca;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .est-empty {
        text-align: center;
        padding: 3.5rem 1.5rem;
        color: #64748b;
    }

    .est-empty .est-empty-icon {
        width: 88px;
        height: 88px;
        margin: 0 auto 1.25rem;
        border-radius: 50%;
        background: linear-gradient(145deg, #f1f5f9, #e2e8f0);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 2rem;
    }

    #estudiantesContadorFiltro {
        font-size: 0.8rem;
        color: #64748b;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper estudiantes-page py-3">
    <div class="container-fluid px-3 px-md-4">

        <div class="estudiantes-hero d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1>
                    <i class="fas fa-users me-2 opacity-90"></i>
                    <?= esc($title ?? 'Gestión de estudiantes') ?>
                </h1>
                <p>Consulta el padrón: datos de contacto, carrera y semestre.</p>
            </div>
            <div class="est-badge-total" id="estudiantesBadgeTotal" aria-live="polite">
                <i class="fas fa-user-graduate me-1"></i>
                <?= $total ?> <?= $total === 1 ? 'estudiante' : 'estudiantes' ?>
            </div>
        </div>

        <?php if ($total > 0): ?>
            <div class="estudiantes-toolbar">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-5 col-md-6">
                        <label for="busquedaEstudiantes" class="form-label small text-muted mb-1 fw-semibold">Buscar</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="search" class="form-control" id="busquedaEstudiantes"
                                placeholder="Nombre, apellido o cédula…" autocomplete="off"
                                title="Solo se filtra por nombre, apellido y cédula. Use «Carrera» para filtrar por carrera.">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="filtroCarreraEst" class="form-label small text-muted mb-1 fw-semibold">Carrera</label>
                        <select class="form-select" id="filtroCarreraEst">
                            <option value="">Todas las carreras</option>
                            <?php foreach ($carreras as $nomCarrera): ?>
                                <option value="<?= esc($nomCarrera) ?>"><?= esc($nomCarrera) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-12 d-flex align-items-center justify-content-lg-end pt-lg-4">
                        <span id="estudiantesContadorFiltro"></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="estudiantes-table-card">
            <div class="table-head">
                <span class="fw-semibold text-secondary">
                    <i class="fas fa-table-list me-2 text-primary"></i>Listado
                </span>
            </div>

            <?php if ($total === 0): ?>
                <div class="est-empty">
                    <div class="est-empty-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h5 class="text-dark fw-semibold mb-2">Aún no hay estudiantes</h5>
                    <p class="mb-0 small mx-auto" style="max-width: 420px;">
                        Cuando se registren estudiantes en el sistema, aparecerán aquí con búsqueda y filtros para localizarlos rápido.
                    </p>
                </div>
            <?php else: ?>
                <div class="estudiantes-table-wrap">
                    <table class="table table-hover align-middle mb-0" id="tablaEstudiantes">
                        <thead>
                            <tr>
                                <th scope="col" class="ps-3">Estudiante</th>
                                <th scope="col">Cédula</th>
                                <th scope="col">Contacto</th>
                                <th scope="col">Carrera</th>
                                <th scope="col" class="text-center pe-3">Semestre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lista as $row):
                                $nombre   = $row['NOMBRE'] ?? '';
                                $apellido = $row['APELLIDO'] ?? '';
                                $carrera  = trim((string) ($row['CARRERA'] ?? ''));
                                $email    = trim((string) ($row['EMAIL'] ?? ''));
                                $celular  = trim((string) ($row['CELULAR'] ?? ''));
                                $sem      = $row['SEMESTRE_ACTUAL'] ?? '';
                                $cedula   = $row['CEDULA'] ?? '';
                                $fotoFile = trim((string) ($row['FOTO_URL'] ?? ''));
                                $fotoPath = $fotoFile !== '' ? FCPATH . 'uploads/perfiles/' . $fotoFile : '';
                                $tieneFoto = $fotoPath !== '' && is_file($fotoPath);
                                $haystack = mb_strtolower(trim($nombre . ' ' . $apellido . ' ' . $cedula));
                                ?>
                                <tr class="fila-estudiante"
                                    data-search="<?= esc($haystack, 'attr') ?>"
                                    data-carrera="<?= esc($carrera, 'attr') ?>">
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="est-avatar" <?= $tieneFoto ? '' : 'aria-hidden="true"' ?>>
                                                <?php if ($tieneFoto): ?>
                                                    <img src="<?= esc(base_url('uploads/perfiles/' . $fotoFile), 'attr') ?>"
                                                        alt="<?= esc(trim($nombre . ' ' . $apellido)) ?>">
                                                <?php else: ?>
                                                    <?= esc($iniciales($nombre, $apellido)) ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="est-nombre text-truncate" style="max-width: 220px;" title="<?= esc($apellido . ', ' . $nombre) ?>">
                                                    <?= esc(trim($apellido . ', ' . $nombre, ', ')) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-monospace small"><?= esc($cedula !== '' ? $cedula : '—') ?></span>
                                    </td>
                                    <td>
                                        <?php if ($email !== ''): ?>
                                            <a class="est-email-link small d-block" href="mailto:<?= esc($email, 'attr') ?>"><?= esc($email) ?></a>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                        <?php if ($celular !== ''): ?>
                                            <div class="est-sub mt-1"><i class="fas fa-phone-alt me-1 opacity-75"></i><?= esc($celular) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="small" style="color: #334155;"><?= esc($carrera !== '' ? $carrera : '—') ?></span>
                                    </td>
                                    <td class="text-center pe-3">
                                        <?php if ($sem !== '' && $sem !== null): ?>
                                            <span class="est-sem-pill"><?= esc((string) $sem) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($total > 0): ?>
<script>
(function () {
    var input = document.getElementById('busquedaEstudiantes');
    var selCarrera = document.getElementById('filtroCarreraEst');
    var filas = document.querySelectorAll('#tablaEstudiantes tbody tr.fila-estudiante');
    var total = filas.length;
    var badge = document.getElementById('estudiantesBadgeTotal');
    var contador = document.getElementById('estudiantesContadorFiltro');

    function aplicarFiltros() {
        var q = (input && input.value || '').toLowerCase().trim();
        var carrera = (selCarrera && selCarrera.value || '').trim();
        var visibles = 0;
        filas.forEach(function (tr) {
            var okTexto = !q || (tr.getAttribute('data-search') || '').indexOf(q) !== -1;
            var okCarrera = !carrera || (tr.getAttribute('data-carrera') || '') === carrera;
            var mostrar = okTexto && okCarrera;
            tr.style.display = mostrar ? '' : 'none';
            if (mostrar) visibles++;
        });
        if (badge) {
            badge.innerHTML = '<i class="fas fa-user-graduate me-1"></i>' + visibles + (visibles === 1 ? ' estudiante' : ' estudiantes');
        }
        if (contador) {
            if (visibles === total && !q && !carrera) {
                contador.textContent = '';
            } else {
                contador.textContent = 'Mostrando ' + visibles + ' de ' + total;
            }
        }
    }

    if (input) input.addEventListener('input', aplicarFiltros);
    if (selCarrera) selCarrera.addEventListener('change', aplicarFiltros);
    aplicarFiltros();
})();
</script>
<?php endif; ?>

<?= $this->endSection() ?>
