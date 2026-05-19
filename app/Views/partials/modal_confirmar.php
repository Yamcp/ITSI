<!-- Modal de Confirmación Global -->
<div class="modal fade" id="modalConfirmarGlobal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-body text-center px-4 pt-4 pb-2">
                <div id="confirmarIcono" class="mb-3">
                    <div style="width:56px;height:56px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:1.6rem;" class="bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                <h6 class="fw-bold mb-2" id="confirmarTitulo">¿Estás seguro?</h6>
                <p class="text-muted small mb-0" id="confirmarMensaje">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pb-4 pt-2">
                <button type="button" class="btn btn-light px-4" style="border-radius:10px;" data-bs-dismiss="modal" id="confirmarBtnCancelar">Cancelar</button>
                <button type="button" class="btn btn-danger px-4" style="border-radius:10px;" id="confirmarBtnAceptar">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Muestra un modal de confirmación estilizado.
 * @param {Object} opciones
 * @param {string}   opciones.titulo   - Título del modal (default: '¿Estás seguro?')
 * @param {string}   opciones.mensaje  - Mensaje descriptivo
 * @param {string}   opciones.icono    - Clase del icono FA (default: 'fas fa-exclamation-triangle')
 * @param {string}   opciones.colorIcono - Clase de color Bootstrap (default: 'text-warning')
 * @param {string}   opciones.bgIcono  - Clase de fondo (default: 'bg-warning bg-opacity-10')
 * @param {string}   opciones.textoAceptar - Texto del botón aceptar (default: 'Aceptar')
 * @param {string}   opciones.textoCancelar - Texto del botón cancelar (default: 'Cancelar')
 * @param {string}   opciones.colorBoton - Clase del botón aceptar (default: 'btn-danger')
 * @param {Function} opciones.onAceptar - Callback al confirmar
 */
function confirmarAccion(opciones) {
    const modal = document.getElementById('modalConfirmarGlobal');
    if (!modal) { console.error('Modal de confirmación no encontrado'); return; }

    const titulo   = opciones.titulo || '¿Estás seguro?';
    const mensaje  = opciones.mensaje || 'Esta acción no se puede deshacer.';
    const icono    = opciones.icono || 'fas fa-exclamation-triangle';
    const colorIco = opciones.colorIcono || 'text-warning';
    const bgIco    = opciones.bgIcono || 'bg-warning bg-opacity-10';
    const txtAcep  = opciones.textoAceptar || 'Aceptar';
    const txtCanc  = opciones.textoCancelar || 'Cancelar';
    const colorBtn = opciones.colorBoton || 'btn-danger';

    document.getElementById('confirmarTitulo').textContent = titulo;
    document.getElementById('confirmarMensaje').textContent = mensaje;
    document.getElementById('confirmarBtnCancelar').textContent = txtCanc;

    const btnAceptar = document.getElementById('confirmarBtnAceptar');
    btnAceptar.textContent = txtAcep;
    btnAceptar.className = 'btn ' + colorBtn + ' px-4';
    btnAceptar.style.borderRadius = '10px';

    const iconoDiv = document.getElementById('confirmarIcono');
    iconoDiv.innerHTML = '<div style="width:56px;height:56px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:1.6rem;" class="' + bgIco + ' ' + colorIco + '"><i class="' + icono + '"></i></div>';

    // Limpiar listener anterior
    const nuevoBtn = btnAceptar.cloneNode(true);
    btnAceptar.parentNode.replaceChild(nuevoBtn, btnAceptar);
    nuevoBtn.addEventListener('click', function() {
        const bsModal = bootstrap.Modal.getInstance(modal);
        if (bsModal) bsModal.hide();
        if (typeof opciones.onAceptar === 'function') {
            opciones.onAceptar();
        }
    });

    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}
</script>
