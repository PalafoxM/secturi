<?php
$logoPath = FCPATH . 'assets/logo3.png';
$logoBase64 = file_exists($logoPath)
    ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
    : '';

$nombreUsuario = trim((string) ($usuario->nombre_completo ?? ''));
$numeroEmpleado = trim((string) ($usuario->no_empleado ?? ''));
$areaUsuario = trim((string) ($usuario->dsc_area ?? ''));

$formatearFecha = static function ($fecha): string {
    $fecha = trim((string) $fecha);
    if ($fecha === '') {
        return '-';
    }

    $timestamp = strtotime($fecha);
    return $timestamp ? date('d/m/Y', $timestamp) : $fecha;
};
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vacaciones aprobadas</title>
    <style>
        body {
            color: #172b4d;
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        .header {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }

        .header td {
            padding: 8px;
            vertical-align: middle;
        }

        .logo-cell {
            width: 25%;
        }

        .title-cell {
            text-align: right;
        }

        .title-cell h1 {
            margin: 0 0 4px;
            color: #174b7a;
            font-size: 20px;
        }

        .title-cell p {
            margin: 0;
            color: #667085;
            font-size: 9px;
        }

        .user-card {
            margin-bottom: 14px;
            padding: 11px 13px;
            border: 1px solid #cbd5e1;
            border-left: 5px solid #2f80c3;
            background: #f7fafc;
        }

        .user-card strong {
            color: #174b7a;
        }

        .summary {
            margin: 0 0 8px;
            color: #344054;
            font-size: 10px;
        }

        .vacation-table {
            width: 100%;
            border-collapse: collapse;
        }

        .vacation-table thead {
            display: table-header-group;
        }

        .vacation-table th {
            padding: 7px 6px;
            border: 1px solid #174b7a;
            color: #fff;
            background: #174b7a;
            font-size: 9px;
            text-align: left;
        }

        .vacation-table td {
            padding: 7px 6px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
            line-height: 1.35;
        }

        .vacation-table tr:nth-child(even) td {
            background: #f8fafc;
        }

        .center {
            text-align: center;
        }

        .empty {
            padding: 24px !important;
            color: #667085;
            text-align: center;
        }

        .status {
            color: #16794b;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td class="logo-cell">
                <?php if ($logoBase64 !== ''): ?>
                    <img src="<?= $logoBase64 ?>" width="155" alt="SUSI">
                <?php endif; ?>
            </td>
            <td class="title-cell">
                <h1>Vacaciones aprobadas</h1>
                <p>Reporte generado por SUSI el <?= esc($fechaGeneracion ?? '') ?></p>
            </td>
        </tr>
    </table>

    <div class="user-card">
        <strong>Persona servidora pública:</strong> <?= esc($nombreUsuario !== '' ? $nombreUsuario : 'Sin nombre') ?><br>
        <?php if ($numeroEmpleado !== ''): ?>
            <strong>No. de empleado:</strong> <?= esc($numeroEmpleado) ?><br>
        <?php endif; ?>
        <?php if ($areaUsuario !== ''): ?>
            <strong>Área:</strong> <?= esc($areaUsuario) ?>
        <?php endif; ?>
    </div>

    <p class="summary">
        Total de registros aprobados: <strong><?= count($vacaciones ?? []) ?></strong>
    </p>

    <table class="vacation-table">
        <thead>
            <tr>
                <th width="6%" class="center">No.</th>
                <th width="23%">Periodo</th>
                <th width="24%">Detalles</th>
                <th width="20%">Comentarios</th>
                <th width="17%">Observaciones</th>
                <th width="10%" class="center">Estatus</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($vacaciones)): ?>
                <?php foreach ($vacaciones as $indice => $vacacion): ?>
                    <?php
                    $fechaInicio = $vacacion->fecha_inicio ?? $vacacion->fecha ?? '';
                    $fechaFin = $vacacion->fecha_fin ?? $fechaInicio;
                    $periodo = $formatearFecha($fechaInicio);
                    if ($fechaFin !== '' && $fechaFin !== $fechaInicio) {
                        $periodo .= ' al ' . $formatearFecha($fechaFin);
                    }
                    ?>
                    <tr>
                        <td class="center"><?= $indice + 1 ?></td>
                        <td><?= esc($periodo) ?></td>
                        <td><?= nl2br(esc((string) ($vacacion->detalles ?? '-'))) ?></td>
                        <td><?= nl2br(esc((string) ($vacacion->comentario ?? '-'))) ?></td>
                        <td><?= nl2br(esc((string) ($vacacion->observaciones ?? '-'))) ?></td>
                        <td class="center status">Aprobado</td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="empty">No se encontraron vacaciones aprobadas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
