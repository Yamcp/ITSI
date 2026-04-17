<?= $this->extend('coord/layouts/mainCoord') ?>

<?php
$lista    = $docentes ?? [];
$total    = count($lista);
$cargos   = [];
foreach ($lista as $row) {
    $c = trim((string) ($row['ESPECIALIDAD'] ?? ''));
    if ($c !== '') {
        $cargos[$c] = $c;
    }
}
ksort($cargos, SORT_NATURAL | SORT_FLAG_CASE);

$iniciales = static function (?string $nombre, ?string $apellido): string {
    $n = mb_strtoupper(mb_substr(trim((string) $nombre), 0, 1));
    $a = mb_strtoupper(mb_substr(trim((string) $apellido), 0, 1));

    return ($n . $a) !== '' ? $n . $a : '?';
};
?>

<?= $this->section('styles') ?>
<style>
    .docentes-page {
        --doc-card-radius: 16px;
        --doc-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        --doc-border: 1px solid rgba(15, 23, 42, 0.06);
        --doc-gradient: linear-gradient(135deg, #059669 0%, #0d9488 50%, #0891b2 100%);
    }

    .docentes-hero {
        background: var(--doc-gradient);
        border-radius: var(--doc-card-radius);
        padding: 1.5rem 1.75rem;
        color: #fff;
        box-shadow: var(--doc-shadow);
        margin-bottom: 1.5rem;
    }

    .docentes-hero h1 {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        margin: 0 0 0.35rem;
    }

    .docentes-hero p {
        margin: 0;
        opacity: 0.92;
        font-size: 0.95rem;
    }

    .docentes-hero .doc-badge-total {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.35);
        border-radius: 999px;
        padding: 0.4rem 1rem;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .docentes-toolbar {
        background: #fff;
        border: var(--doc-border);
        border-radius: var(--doc-card-radius);
        padding: 1rem 1.25rem;
        box-shadow: var(--doc-shadow);
        margin-bottom: 1.25rem;
    }

    .docentes-toolbar .input-group-text {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #64748b;
    }

    .docentes-toolbar .form-control,
    .docentes-toolbar .form-select {
        border-color: #e2e8f0;
    }

    .docentes-toolbar .form-control:focus,
    .docentes-toolbar .form-select:focus {
        border-color: #2dd4bf;
        box-shadow: 0 0 0 0.2rem rgba(13, 148, 136, 0.18);
    }

    .docentes-table-card {
        background: #fff;
        border: var(--doc-border);
        border-radius: var(--doc-card-radius);
        box-shadow: var(--doc-shadow);
        overflow: hidden;
    }

    .docentes-table-card .table-head {
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .docentes-table-wrap {
        max-height: min(70vh, 640px);
        overflow: auto;
    }

    .docentes-table-wrap table thead th {
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

    .docentes-table-wrap tbody tr {
        transition: background 0.15s ease;
    }

    .docentes-table-wrap tbody tr:hover {
        background: #f0fdfa;
    }

    .doc-avatar {
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
        background: linear-gradient(145deg, #059669, #0d9488);
        overflow: hidden;
    }

    .doc-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .doc-nombre {
        font-weight: 600;
        color: #0f172a;
        line-height: 1.25;
    }

    .doc-sub {
        font-size: 0.8rem;
        color: #64748b;
    }

    .doc-email-link {
        color: #0f766e;
        text-decoration: none;
        word-break: break-word;
    }

    .doc-email-link:hover {
        text-decoration: underline;
    }

    .doc-act-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2rem;
        padding: 0.2rem 0.5rem;
        border-radius: 8px;
        background: #ccfbf1;
        color: #0f766e;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .doc-act-sub {
        font-size: 0.75rem;
        color: #64748b;
    }

    .doc-empty {
        text-align: center;
        padding: 3.5rem 1.5rem;
        color: #64748b;
    }

    .doc-empty .doc-empty-icon {
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

    #docentesContadorFiltro {
        font-size: 0.8rem;
        color: #64748b;
    }

    .docentes-hero a.doc-hero-link {
        color: rgba(255, 255, 255, 0.95);
        font-size: 0.875rem;
        text-decoration: underline;
        text-underline-offset: 3px;
    }

    .docentes-hero a.doc-hero-link:hover {
        color: #fff;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper docentes-page py-3">
    <div class="container-fluid px-3 px-md-4">

        <div class="docentes-hero d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1>
                    <i class="fas fa-user-tie me-2 opacity-90"></i>
                    <?= esc($title ?? 'Gestión de docentes') ?>
                </h1>
                <p class="mb-2">Docentes y tutores: contacto, cargo y actividades en educación continua.</p>
            </div>
            <div class="doc-badge-total" id="docentesBadgeTotal" aria-live="polite">
                <i class="fas fa-building me-1"></i>
                <?= $total ?> <?= $total === 1 ? 'docente' : 'docentes' ?>
            </div>
        </div>

        <?php if ($total > 0): ?>
            <div class="docentes-toolbar">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-5 col-md-6">
                        <label for="busquedaDocentes" class="form-label small text-muted mb-1 fw-semibold">Buscar</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="search" class="form-control" id="busquedaDocentes"
                                placeholder="Nombre, apellido o cédula…" autocomplete="off"
                                title="Filtra por nombre, apellido y cédula. Use «Cargo» para filtrar por cargo registrado.">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="filtroCargoDoc" class="form-label small text-muted mb-1 fw-semibold">Cargo</label>
                        <select class="form-select" id="filtroCargoDoc">
                            <option value="">Todos los cargos</option>
                            <?php foreach ($cargos as $nomCargo): ?>
                                <option value="<?= esc($nomCargo) ?>"><?= esc($nomCargo) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-12 d-flex align-items-center justify-content-lg-end pt-lg-4">
                        <span id="docentesContadorFiltro"></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="docentes-table-card">
            <div class="table-head">
                <span class="fw-semibold text-secondary">
                    <i class="fas fa-table-list me-2 text-success"></i>Listado
                </span>
            </div>

            <?php if ($total === 0): ?>
                <div class="doc-empty">
                    <div class="doc-empty-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h5 class="text-dark fw-semibold mb-2">No hay docentes internos registrados</h5>
                    <p class="mb-0 small mx-auto" style="max-width: 440px;">
                        Aparecerán aquí los instructores internos y los empleados cuyo cargo indique docente o tutor.
                    </p>
                </div>
            <?php else: ?>
                <div class="docentes-table-wrap">
                    <table class="table table-hover align-middle mb-0" id="tablaDocentes">
                        <thead>
                            <tr>
                                <th scope="col" class="ps-3">Docente</th>
                                <th scope="col">Cédula</th>
                                <th scope="col">Contacto</th>
                                <th scope="col">Cargo / especialidad</th>
                                <th scope="col" class="text-center pe-3">Actividades</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lista as $row):
                                $nombre    = $row['NOMBRE'] ?? '';
                                $apellido  = $row['APELLIDO'] ?? '';
                                $titulo    = trim((string) ($row['TITULO_PROFESIONAL'] ?? ''));
                                $cargoEsp  = trim((string) ($row['ESPECIALIDAD'] ?? ''));
                                $email     = trim((string) ($row['EMAIL'] ?? ''));
                                $celular   = trim((string) ($row['CELULAR'] ?? ''));
                                $cedula    = $row['CEDULA'] ?? '';
                                $fotoFile  = trim((string) ($row['FOTO_URL'] ?? ''));
                                $fotoPath  = $fotoFile !== '' ? FCPATH . 'uploads/perfiles/' . $fotoFile : '';
                                $tieneFoto = $fotoPath !== '' && is_file($fotoPath);
                                $haystack  = mb_strtolower(trim($nombre . ' ' . $apellido . ' ' . $cedula));
                                $activas   = (int) ($row['actividades_activas'] ?? 0);
                                $complet   = (int) ($row['actividades_completadas'] ?? 0);
                                ?>
                                <tr class="fila-docente"
                                    data-search="<?= esc($haystack, 'attr') ?>"
                                    data-cargo="<?= esc($cargoEsp, 'attr') ?>">
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="doc-avatar" <?= $tieneFoto ? '' : 'aria-hidden="true"' ?>>
                                                <?php if ($tieneFoto): ?>
                                                    <img src="<?= esc(base_url('uploads/perfiles/' . $fotoFile), 'attr') ?>"
                                                        alt="<?= esc(trim($nombre . ' ' . $apellido)) ?>">
                                                <?php else: ?>
                                                    <?= esc($iniciales($nombre, $apellido)) ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="doc-nombre text-truncate" style="max-width: 260px;" title="<?= esc(trim($apellido . ', ' . $nombre, ', ')) ?>">
                                                    <?= esc(trim($apellido . ', ' . $nombre, ', ')) ?>
                                                </div>
                                                <?php if ($titulo !== ''): ?>
                                                    <div class="doc-sub text-truncate" style="max-width: 260px;"><?= esc($titulo) ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-monospace small"><?= esc($cedula !== '' ? $cedula : '—') ?></span>
                                    </td>
                                    <td>
                                        <?php if ($email !== ''): ?>
                                            <a class="doc-email-link small d-block" href="mailto:<?= esc($email, 'attr') ?>"><?= esc($email) ?></a>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                        <?php if ($celular !== ''): ?>
                                            <div class="doc-sub mt-1"><i class="fas fa-phone-alt me-1 opacity-75"></i><?= esc($celular) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="small" style="color: #334155;"><?= esc($cargoEsp !== '' ? $cargoEsp : '—') ?></span>
                                        <?php if ($titulo !== '' && strcasecmp($titulo, $cargoEsp) !== 0): ?>
                                            <div class="doc-act-sub mt-1"><?= esc($titulo) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center pe-3">
                                        <div>
                                            <span class="doc-act-pill" title="Activas / completadas"><?= esc((string) $activas) ?></span>
                                            <span class="text-muted small"> / </span>
                                            <span class="small text-secondary"><?= esc((string) $complet) ?></span>
                                        </div>
                                        <div class="doc-act-sub">activas / hechas</div>
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
    var input = document.getElementById('busquedaDocentes');
    var selCargo = document.getElementById('filtroCargoDoc');
    var filas = document.querySelectorAll('#tablaDocentes tbody tr.fila-docente');
    var total = filas.length;
    var badge = document.getElementById('docentesBadgeTotal');
    var contador = document.getElementById('docentesContadorFiltro');

    function aplicarFiltros() {
        var q = (input && input.value || '').toLowerCase().trim();
        var cargo = (selCargo && selCargo.value || '').trim();
        var visibles = 0;
        filas.forEach(function (tr) {
            var okTexto = !q || (tr.getAttribute('data-search') || '').indexOf(q) !== -1;
            var okCargo = !cargo || (tr.getAttribute('data-cargo') || '') === cargo;
            var mostrar = okTexto && okCargo;
            tr.style.display = mostrar ? '' : 'none';
            if (mostrar) visibles++;
        });
        if (badge) {
            badge.innerHTML = '<i class="fas fa-building me-1"></i>' + visibles + (visibles === 1 ? ' docente' : ' docentes');
        }
        if (contador) {
            if (visibles === total && !q && !cargo) {
                contador.textContent = '';
            } else {
                contador.textContent = 'Mostrando ' + visibles + ' de ' + total;
            }
        }
    }

    if (input) input.addEventListener('input', aplicarFiltros);
    if (selCargo) selCargo.addEventListener('change', aplicarFiltros);
    aplicarFiltros();
})();
</script>
<?php endif; ?>

<?= $this->endSection() ?>
