<?php
$url = $coordinador_whatsapp_url ?? '';
$nombreCoord = $coordinador_whatsapp_nombre ?? '';
?>
<?php if ($url !== ''): ?>
    <div class="card border-0 shadow-sm mb-4 contacto-coord-wa-card">
        <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 py-3 px-3 px-md-4">
            <div class="d-flex align-items-start gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 contacto-coord-wa-icon">
                    <i class="fab fa-whatsapp fa-2x text-white"></i>
                </div>
                <div>
                    <h6 class="fw-semibold mb-1">Contacto con coordinación</h6>
                    <p class="text-muted small mb-0">
                        <?php if ($nombreCoord !== ''): ?>
                            Coordinador(a): <strong><?= esc($nombreCoord) ?></strong>.
                        <?php else: ?>
                            Escribe al equipo de coordinación del Departamento de Vinculación.
                        <?php endif; ?>
                        Te abriremos WhatsApp con un mensaje preparado; puedes editarlo antes de enviar.
                    </p>
                </div>
            </div>
            <a href="<?= esc($url, 'attr') ?>" class="btn btn-success fw-semibold px-4 align-self-stretch align-self-md-center text-nowrap" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-whatsapp me-2"></i>Abrir WhatsApp
            </a>
        </div>
    </div>
    <style>
        .contacto-coord-wa-card {
            border-left: 4px solid #25D366 !important;
        }
        .contacto-coord-wa-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        }
    </style>
<?php endif; ?>
