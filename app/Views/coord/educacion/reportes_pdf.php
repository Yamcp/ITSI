<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Actividades Educativas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }

        .header h2 {
            color: #6c757d;
            margin: 5px 0 0 0;
            font-size: 16px;
            font-weight: normal;
        }

        .info-section {
            margin-bottom: 25px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }

        .info-section h3 {
            margin: 0 0 10px 0;
            color: #495057;
            font-size: 14px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
        }

        .info-label {
            font-weight: bold;
            color: #6c757d;
        }

        .info-value {
            color: #495057;
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }

        td {
            font-size: 10px;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-curso {
            background-color: #28a745;
            color: white;
        }

        .badge-taller {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-seminario {
            background-color: #17a2b8;
            color: white;
        }

        .badge-presencial {
            background-color: #6f42c1;
            color: white;
        }

        .badge-virtual {
            background-color: #fd7e14;
            color: white;
        }

        .badge-mixta {
            background-color: #20c997;
            color: white;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }

        .no-data {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 20px;
        }

        .summary {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .summary h3 {
            margin: 0 0 10px 0;
            color: #1976d2;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div style="text-align: center; margin-bottom: 15px;">
            <?php
            helper('PdfHelper');
            echo \App\Helpers\PdfHelper::getLogoHtml('Logo PDF.jpg', [
                'style' => 'height: 60px; max-width: 200px;'
            ]);
            ?>
        </div>
        <h1>REPORTE DE ACTIVIDADES EDUCATIVAS</h1>
        <h2>Instituto Tecnológico Superior de Informática</h2>
    </div>

    <div class="info-section">
        <h3>Información del Reporte</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Fecha de Generación:</span>
                <span class="info-value"><?= \App\Helpers\PdfHelper::getCurrentDateTime() ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Total de Actividades:</span>
                <span class="info-value"><?= $total_actividades ?></span>
            </div>
        </div>
    </div>

    <?php if (!empty($filtros) && array_filter($filtros)): ?>
        <div class="info-section">
            <h3>Filtros Aplicados</h3>
            <div class="info-grid">
                <?php if (!empty($filtros['tipo_actividad'])): ?>
                    <div class="info-item">
                        <span class="info-label">Tipo de Actividad:</span>
                        <span class="info-value"><?= $filtros['tipo_actividad'] ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($filtros['modalidad'])): ?>
                    <div class="info-item">
                        <span class="info-label">Modalidad:</span>
                        <span class="info-value"><?= $filtros['modalidad'] ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($filtros['fecha_inicio'])): ?>
                    <div class="info-item">
                        <span class="info-label">Fecha Inicio:</span>
                        <span class="info-value"><?= $filtros['fecha_inicio'] ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($filtros['fecha_fin'])): ?>
                    <div class="info-item">
                        <span class="info-label">Fecha Fin:</span>
                        <span class="info-value"><?= $filtros['fecha_fin'] ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($filtros['instructor'])): ?>
                    <div class="info-item">
                        <span class="info-label">Instructor:</span>
                        <span class="info-value"><?= $filtros['instructor'] ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($filtros['carrera'])): ?>
                    <div class="info-item">
                        <span class="info-label">Carrera:</span>
                        <span class="info-value"><?= esc($filtros['carrera']) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="summary">
        <h3>Resumen</h3>
        <p>Este reporte contiene <?= $total_actividades ?> actividad(es) educativa(s) que cumplen con los criterios de búsqueda especificados.</p>
    </div>

    <div class="table-container">
        <?php if (!empty($actividades)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Modalidad</th>
                        <th>Instructor</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Horas</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($actividades as $actividad): ?>
                        <tr>
                            <td><?= $actividad['ID_ACTIVIDAD'] ?? 'N/A' ?></td>
                            <td><?= htmlspecialchars($actividad['NOMBRE_ACTIVIDAD'] ?? 'N/A') ?></td>
                            <td>
                                <?php
                                $tipo = $actividad['TIPO_ACTIVIDAD'] ?? 'N/A';
                                $badgeClass = '';
                                switch (strtolower($tipo)) {
                                    case 'curso':
                                        $badgeClass = 'badge-curso';
                                        break;
                                    case 'taller':
                                        $badgeClass = 'badge-taller';
                                        break;
                                    case 'seminario':
                                        $badgeClass = 'badge-seminario';
                                        break;
                                }
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= $tipo ?></span>
                            </td>
                            <td>
                                <?php
                                $modalidad = $actividad['MODALIDAD'] ?? 'N/A';
                                $badgeClass = '';
                                switch (strtolower($modalidad)) {
                                    case 'presencial':
                                        $badgeClass = 'badge-presencial';
                                        break;
                                    case 'virtual':
                                        $badgeClass = 'badge-virtual';
                                        break;
                                    case 'mixta':
                                        $badgeClass = 'badge-mixta';
                                        break;
                                }
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= $modalidad ?></span>
                            </td>
                            <td><?= htmlspecialchars($actividad['INSTRUCTOR'] ?? 'N/A') ?></td>
                            <td><?= $actividad['FECHA_INICIO'] ?? 'N/A' ?></td>
                            <td><?= $actividad['FECHA_FIN'] ?? 'N/A' ?></td>
                            <td><?= $actividad['HORAS_TOTALES'] ?? 'N/A' ?></td>
                            <td><?= $actividad['ESTADO'] ?? 'N/A' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <p>No se encontraron actividades que cumplan con los criterios especificados.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p>Reporte generado automáticamente por el Sistema de Gestión de Actividades Educativas</p>
        <p>Instituto Tecnológico Superior de Informática - <?= date('Y') ?></p>
    </div>
</body>

</html>