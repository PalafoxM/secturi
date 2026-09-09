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

$calcularDias = static function ($fechaInicio, $fechaFin): int {
    $inicioTimestamp = strtotime((string) $fechaInicio);
    $finTimestamp = strtotime((string) $fechaFin);

    if (!$inicioTimestamp) {
        return 0;
    }

    if (!$finTimestamp) {
        $finTimestamp = $inicioTimestamp;
    }

    $inicio = new DateTimeImmutable(date('Y-m-d', $inicioTimestamp));
    $fin = new DateTimeImmutable(date('Y-m-d', $finTimestamp));
    $diferencia = (int) $inicio->diff($fin)->format('%r%a');

    return $diferencia >= 0 ? $diferencia + 1 : 1;
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
                <th width="22%">Periodo</th>
                <th width="8%" class="center">Días</th>
                <th width="28%">Detalles</th>
                <th width="24%">Comentarios</th>
                <th width="12%" class="center">Estatus</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($vacaciones)): ?>
                <?php foreach ($vacaciones as $indice => $vacacion): ?>
                    <?php
                    $fechaInicio = trim((string) ($vacacion->fecha_inicio ?? $vacacion->fecha ?? ''));
                    $fechaFin = trim((string) ($vacacion->fecha_fin ?? ''));
                    if ($fechaFin === '' || $fechaFin === '0000-00-00') {
                        $fechaFin = $fechaInicio;
                    }
                    $periodo = $formatearFecha($fechaInicio);
                    if ($fechaFin !== '' && $fechaFin !== $fechaInicio) {
                        $periodo .= ' al ' . $formatearFecha($fechaFin);
                    }
                    $totalDias = $calcularDias($fechaInicio, $fechaFin);
                    ?>
                    <tr>
                        <td class="center"><?= $indice + 1 ?></td>
                        <td><?= esc($periodo) ?></td>
                        <td class="center"><strong><?= $totalDias ?></strong></td>
                        <td><?= nl2br(esc((string) ($vacacion->detalles ?? '-'))) ?></td>
                        <td><?= nl2br(esc((string) ($vacacion->comentario ?? '-'))) ?></td>
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
