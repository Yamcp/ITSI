<?php
$modalId = $modalId ?? 'modalVisorFormato';
?>
<div class="modal fade" id="<?= esc($modalId, 'attr') ?>" tabindex="-1" aria-labelledby="<?= esc($modalId, 'attr') ?>Label" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title text-truncate me-2" id="<?= esc($modalId, 'attr') ?>Label">
                    <i class="fas fa-file-lines me-2 text-primary"></i><span class="visor-formato-titulo">Vista previa</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-0 bg-light position-relative" style="min-height: 50vh;">
                <iframe class="visor-formato-iframe d-none w-100" title="Vista previa del documento" style="min-height: min(75vh, 720px); border: 0;"></iframe>
                <div class="visor-formato-no-soportado d-none p-4 text-center">
                    <i class="fas fa-file-circle-exclamation fa-3x text-warning mb-3"></i>
                    <p class="mb-2">La vista previa en el navegador no está disponible para este tipo de archivo.</p>
                    <p class="text-muted small mb-3">Descarga el documento para abrirlo en tu equipo.</p>
                    <a href="#" class="btn btn-primary visor-formato-link-descarga"><i class="fas fa-download me-1"></i>Descargar</a>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-outline-primary btn-sm visor-formato-link-descarga-footer" target="_blank" rel="noopener">
                    <i class="fas fa-download me-1"></i>Descargar
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
(function() {
    var modalId = <?= json_encode($modalId) ?>;
    var initKey = 'visorFormatoBound_' + modalId;
    if (window[initKey]) return;
    window[initKey] = true;

    function escAttrSel(s) {
        if (typeof CSS !== 'undefined' && CSS.escape) return CSS.escape(s);
        return String(s).replace(/\\/g, '\\\\').replace(/"/g, '\\"');
    }

    function extVisualizable(ext) {
        ext = (ext || '').toLowerCase();
        return ext === 'pdf' || ext === 'png' || ext === 'jpg' || ext === 'jpeg' || ext === 'gif' || ext === 'webp';
    }

    function aplicarContenidoVisor(modalEl, btn) {
        var iframe = modalEl.querySelector('.visor-formato-iframe');
        var bloqueNo = modalEl.querySelector('.visor-formato-no-soportado');
        var tituloEl = modalEl.querySelector('.visor-formato-titulo');
        var linkDescCentro = modalEl.querySelector('.visor-formato-link-descarga');
        var linkDescFooter = modalEl.querySelector('.visor-formato-link-descarga-footer');
        if (!iframe || !bloqueNo || !btn) return;

        var urlVer = btn.getAttribute('data-formato-ver-url') || '';
        var urlDesc = btn.getAttribute('data-formato-desc-url') || '#';
        var titulo = btn.getAttribute('data-formato-titulo') || 'Documento';
        var ext = btn.getAttribute('data-formato-ext') || '';

        if (tituloEl) tituloEl.textContent = titulo;
        if (linkDescCentro) linkDescCentro.setAttribute('href', urlDesc);
        if (linkDescFooter) linkDescFooter.setAttribute('href', urlDesc);

        if (extVisualizable(ext) && urlVer) {
            bloqueNo.classList.add('d-none');
            iframe.classList.remove('d-none');
            iframe.src = urlVer;
        } else {
            iframe.classList.add('d-none');
            iframe.src = 'about:blank';
            bloqueNo.classList.remove('d-none');
        }
    }

    function bindVisorFormato() {
        var BS = window.bootstrap;
        if (!BS || !BS.Modal) return false;

        var modalEl = document.getElementById(modalId);
        if (!modalEl) return false;

        var iframe = modalEl.querySelector('.visor-formato-iframe');
        if (!iframe) return false;

        /* Los modales dentro de #layoutSidenav / .page-wrapper suelen tener un ancestro con transform u overflow;
           eso rompe position:fixed y el modal no se ve. Lo movemos a body. */
        if (modalEl.parentNode !== document.body) {
            document.body.appendChild(modalEl);
        }

        if (!modalEl.dataset.visorFormatoListeners) {
            modalEl.dataset.visorFormatoListeners = '1';
            modalEl.addEventListener('hidden.bs.modal', function() {
                iframe.src = 'about:blank';
            });
        }

        var sel = '.btn-ver-formato[data-formato-modal-id="' + escAttrSel(modalId) + '"]';
        var buttons = document.querySelectorAll(sel);
        buttons.forEach(function(btn) {
            if (btn.dataset.visorFormatoClickBound === '1') return;
            btn.dataset.visorFormatoClickBound = '1';
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var m = document.getElementById(modalId);
                if (!m || !window.bootstrap || !window.bootstrap.Modal) return;
                aplicarContenidoVisor(m, btn);
                try {
                    var instance = window.bootstrap.Modal.getOrCreateInstance(m);
                    instance.show(btn);
                } catch (err) {
                    console.error('Visor formatos: no se pudo abrir el modal', err);
                    window.open(btn.getAttribute('data-formato-ver-url') || btn.getAttribute('data-formato-desc-url') || '#', '_blank', 'noopener');
                }
            });
        });

        return true;
    }

    function scheduleBind() {
        if (bindVisorFormato()) return;
        document.addEventListener('DOMContentLoaded', function() { bindVisorFormato(); });
        window.addEventListener('load', function() { bindVisorFormato(); });
        setTimeout(function() { bindVisorFormato(); }, 50);
        setTimeout(function() { bindVisorFormato(); }, 300);
    }

    scheduleBind();
})();
</script>
