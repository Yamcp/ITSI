<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Convenios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
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
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .estadisticas {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }
        .estadistica {
            text-align: center;
        }
        .estadistica .numero {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .estadistica .label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
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
        .estado-vigente {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
        }
        .estado-por-vencer {
            background-color: #fff3cd;
            color: #856404;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
        }
        .estado-vencido {
            background-color: #f8d7da;
            color: #721c24;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Convenios</h1>
        <p>Instituto Tecnológico Superior Ibarra</p>
        <p>Generado el: <?= $fecha_generacion ?></p>
    </div>

    <div class="estadisticas">
        <div class="estadistica">
            <div class="numero"><?= $estadisticas['total'] ?></div>
            <div class="label">Total Convenios</div>
        </div>
        <div class="estadistica">
            <div class="numero"><?= $estadisticas['vigentes'] ?></div>
            <div class="label">Vigentes</div>
        </div>
        <div class="estadistica">
            <div class="numero"><?= $estadisticas['por_vencer'] ?></div>
            <div class="label">Por Vencer</div>
        </div>
        <div class="estadistica">
            <div class="numero"><?= $estadisticas['vencidos'] ?></div>
            <div class="label">Vencidos</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Institución</th>
                <th>RUC</th>
                <th>Tipo</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Duración</th>
                <th>Estado</th>
                <th>Renovable</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($convenios as $convenio): ?>
                <tr>
                    <td><?= $convenio['ID_DETALLE_CONVENIO'] ?></td>
                    <td><?= $convenio['NOMBRE'] ?></td>
                    <td><?= $convenio['RUC'] ?></td>
                    <td><?= $convenio['TIPO_CONVENIO'] ?></td>
                    <td><?= date('d/m/Y', strtotime($convenio['FECHA_INICIO'])) ?></td>
                    <td><?= date('d/m/Y', strtotime($convenio['FECHA_FIN'])) ?></td>
                    <td><?= $convenio['DURACION'] ?> meses</td>
                    <td>
                        <?php 
                        $fechaActual = date('Y-m-d');
                        $fechaLimite = date('Y-m-d', strtotime('+30 days'));
                        if ($convenio['FECHA_FIN'] < $fechaActual) {
                            $estado = 'Vencido';
                            $clase = 'estado-vencido';
                        } elseif ($convenio['FECHA_FIN'] <= $fechaLimite) {
                            $estado = 'Por Vencer';
                            $clase = 'estado-por-vencer';
                        } else {
                            $estado = 'Vigente';
                            $clase = 'estado-vigente';
                        }
                        ?>
                        <span class="<?= $clase ?>"><?= $estado ?></span>
                    </td>
                    <td><?= $convenio['RENOVABLE'] ? 'Sí' : 'No' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Este reporte fue generado automáticamente por el Sistema de Gestión de Convenios</p>
        <p>Instituto Tecnológico Superior Ibarra - <?= date('Y') ?></p>
    </div>
</body>
</html>
