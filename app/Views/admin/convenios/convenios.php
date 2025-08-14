<!-- app/Views/convenios/convenios.php -->
<?= $this->extend('admin/layouts/mainAdmin') ?>

<?= $this->section('styles') ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="body-wrapper">
    <div class="container-fluid mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Instituciones de convenio</h4>
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregar">
                        <i class="mdi mdi-plus"></i> + Añadir Nuevo
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Objetivo</th>
                                <th>Duracion</th>
                                <th>Fecha inicio</th>
                                <th>Fecha fin</th>
                                <th>Responsable</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Ejemplo</td>
                                <td>correo@ejemplo.com</td>
                                <td>Si</td>
                                <td>
                                    <button class="btn btn-sm btn-warning">Editar</button>
                                    <button class="btn btn-sm btn-danger">Eliminar</button>
                                    <button class="btn btn-sm btn-info">Ver</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Agregar -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form action="<?= base_url('guardar-dato') ?>" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Nuevo Registro</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nombre</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Descripción</label>
                                <textarea class="form-control" name="descripcion" required></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Objetivos</label>
                                <textarea class="form-control" name="objetivos" required></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Duración (horas)</label>
                                <input type="number" class="form-control" name="duracion" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Fecha de Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Fecha de Fin</label>
                                <input type="date" class="form-control" name="fecha_fin" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Lugar</label>
                                <input type="text" class="form-control" name="lugar" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Horario</label>
                                <input type="text" class="form-control" name="horario" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Certificado (Sí/No)</label>
                                <select class="form-control" name="certificado" required>
                                    <option value="Si">Sí</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Detalles</label>
                                <textarea class="form-control" name="detalles" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>