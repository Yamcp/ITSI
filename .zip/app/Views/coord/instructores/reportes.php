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
            padding-bottom: 20px;
        }

        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .info-section {
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }

        .info-section h3 {
            margin: 0 0 10px 0;
            color: #007bff;
            font-size: 16px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #007bff;
            color: white;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }

        .stat-card h4 {
            margin: 0;
            font-size: 20px;
        }

        .stat-card p {
            margin: 5px 0 0 0;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-primary {
            background-color: #007bff;
            color: white;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }

        .badge-warning {
            background-color: #ffc107;
            color: black;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .filtros-applied {
            background: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .filtros-applied h4 {
            margin: 0 0 10px 0;
            color: #495057;
            font-size: 14px;
        }

        .filtro-item {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 2px 8px;
            border-radius: 3px;
            margin: 2px;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Reporte de Instructores</h1>
        <p>Generado el: <?= $fecha_generacion ?></p>
        <p>Total de instructores: <?= $total_instructores ?></p>
    </div>

    <?php if (!empty($filtros) && array_filter($filtros)): ?>
        <div class="filtros-applied">
            <h4>Filtros Aplicados:</h4>
            <?php foreach ($filtros as $key => $value): ?>
                <?php if (!empty($value)): ?>
                    <span class="filtro-item"><?= ucfirst(str_replace('_', ' ', $key)) ?>: <?= $value ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card">
            <h4><?= $total_instructores ?></h4>
            <p>Total Instructores</p>
        </div>
        <div class="stat-card">
            <h4><?= count(array_filter($instructores, function ($i) {
                    return $i['TIPO_INSTRUCTOR'] === 'Interno';
                })) ?></h4>
            <p>Instructores Internos</p>
        </div>
        <div class="stat-card">
            <h4><?= count(array_filter($instructores, function ($i) {
                    return $i['TIPO_INSTRUCTOR'] === 'Externo';
                })) ?></h4>
            <p>Instructores Externos</p>
        </div>
        <div class="stat-card">
            <h4><?= array_sum(array_column($instructores, 'total_actividades')) ?></h4>
            <p>Total Actividades</p>
        </div>
    </div>

    <?php if (!empty($instructores)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Instructor</th>
                    <th>Tipo</th>
                    <th>Especialidad</th>
                    <th>Título</th>
                    <th>Email</th>
                    <th>Celular</th>
                    <th>Actividades</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($instructores as $instructor): ?>
                    <tr>
                        <td><?= $instructor['ID_INSTRUCTOR'] ?></td>
                        <td><?= $instructor['NOMBRE'] ?> <?= $instructor['APELLIDO'] ?></td>
                        <td>
                            <?php
                            $badgeClass = $instructor['TIPO_INSTRUCTOR'] === 'Interno' ? 'badge-success' : 'badge-info';
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= $instructor['TIPO_INSTRUCTOR'] ?></span>
                        </td>
                        <td><?= $instructor['ESPECIALIDAD'] ?></td>
                        <td><?= $instructor['TITULO_PROFESIONAL'] ?></td>
                        <td><?= $instructor['EMAIL'] ?></td>
                        <td><?= $instructor['CELULAR'] ?></td>
                        <td>
                            <span class="badge badge-warning"><?= $instructor['total_actividades'] ?? 0 ?> Total</span>
                            <br>
                            <span class="badge badge-success"><?= $instructor['actividades_activas'] ?? 0 ?> Activas</span>
                        </td>
                        <td>
                            <span class="badge badge-success">Activo</span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="info-section">
            <h3>No se encontraron instructores</h3>
            <p>No hay instructores registrados en el sistema.</p>
        </div>
    <?php endif; ?>

    <div class="footer">
        <p>Reporte generado automáticamente por el Sistema de Gestión de Actividades Educativas</p>
        <p>Fecha de generación: <?= $fecha_generacion ?></p>
    </div>
</body>

</html>