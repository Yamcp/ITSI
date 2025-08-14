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
                                <td>años</td>
                                <td>Ejemplo</td>
                                <td>Ejemplo</td>
                                <td>Ejemplo</td>
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
                                <label>RUC</label>
                                <input type="text" class="form-control" name="ruc" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Telefono</label>
                                <input type="text" class="form-control" name="direccion" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Email</label>
                                <input type="text" class="form-control" name="email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Representante Legal</label>
                                <input type="text" class="form-control" name="nombre_representante" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Contacto</label>
                                <input type="text" class="form-control" name="contacto" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Telefono del contacto</label>
                                <input type="text" class="form-control" name="telefono_contacto" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Email del contacto</label>
                                <input type="text" class="form-control" name="email_contacto" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Área de Interés</label>
                                <input type="text" class="form-control" name="area_interes" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Fecha Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Fecha Fin</label>
                                <input type="date" class="form-control" name="fecha_fin" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Observaciones</label>
                                <input type="text" class="form-control" name="observaciones" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Archivo</label>
                                <input type="file" class="form-control" name="archivo" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Renovable</label>
                                <input type="checkbox" class="form-control" name="renovable" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Tipo de Convenio</label>
                                <input type="text" class="form-control" name="renovable" required>
                            </div>                            
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>